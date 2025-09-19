<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\StaffTask;
use App\Models\StaffTaskAssignment;
use App\Models\StaffType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffTasksController extends Controller
{
    /**
     * Display the main tasks dashboard.
     */
    public function index(Request $request): View
    {
        try {
            // Get filter parameters
            $filters = [
                'search' => $request->get('search'),
                'status' => $request->get('status'),
                'priority' => $request->get('priority'),
                'category' => $request->get('category'),
                'assignee' => $request->get('assignee'),
                'due_date' => $request->get('due_date'),
            ];

            // Get dashboard data
            $dashboardData = $this->getDashboardData($filters);
            $tasks = $this->getFilteredTasks($filters);
            $assignments = $this->getFilteredAssignments($filters);
            
            // Get supporting data
            $staffMembers = Staff::active()->with('staffType')->get();
            $staffTypes = StaffType::active()->get();
            
            return view('admin.staff.tasks', compact(
                'dashboardData',
                'tasks',
                'assignments',
                'staffMembers',
                'staffTypes',
                'filters'
            ));
            
        } catch (\Exception $e) {
            // Graceful fallback for missing data
            return view('admin.staff.tasks', [
                'dashboardData' => $this->getEmptyDashboardData(),
                'tasks' => collect(),
                'assignments' => collect(),
                'staffMembers' => collect(),
                'staffTypes' => collect(),
                'filters' => $filters ?? [],
            ]);
        }
    }

    /**
     * Get dashboard statistics and data.
     */
    private function getDashboardData(array $filters): array
    {
        try {
            $totalTasks = StaffTask::active()->count();
            $totalAssignments = StaffTaskAssignment::whereHas('task', fn($q) => $q->active())->count();
            $completedAssignments = StaffTaskAssignment::where('status', 'completed')->count();
            $overdueAssignments = StaffTaskAssignment::where('status', '!=', 'completed')
                ->where('due_date', '<', now())->count();
            $inProgressAssignments = StaffTaskAssignment::where('status', 'in_progress')->count();

            return [
                'total_tasks' => $totalTasks,
                'total_assignments' => $totalAssignments,
                'completed_assignments' => $completedAssignments,
                'overdue_assignments' => $overdueAssignments,
                'in_progress_assignments' => $inProgressAssignments,
                'completion_rate' => $totalAssignments > 0 ? round(($completedAssignments / $totalAssignments) * 100, 1) : 0,
            ];
        } catch (\Exception $e) {
            return $this->getEmptyDashboardData();
        }
    }

    /**
     * Get empty dashboard data for fallback.
     */
    private function getEmptyDashboardData(): array
    {
        return [
            'total_tasks' => 0,
            'total_assignments' => 0,
            'completed_assignments' => 0,
            'overdue_assignments' => 0,
            'in_progress_assignments' => 0,
            'completion_rate' => 0,
        ];
    }

    /**
     * Get filtered tasks.
     */
    private function getFilteredTasks(array $filters)
    {
        try {
            $query = StaffTask::with(['creator', 'assignments.staff.staffType'])
                ->active();

            if (!empty($filters['search'])) {
                $query->where(function ($q) use ($filters) {
                    $q->where('title', 'like', "%{$filters['search']}%")
                      ->orWhere('description', 'like', "%{$filters['search']}%");
                });
            }

            if (!empty($filters['priority'])) {
                $query->where('priority', $filters['priority']);
            }

            if (!empty($filters['category'])) {
                $query->where('category', $filters['category']);
            }

            return $query->orderBy('created_at', 'desc')->paginate(20);
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Get filtered assignments.
     */
    private function getFilteredAssignments(array $filters)
    {
        try {
            $query = StaffTaskAssignment::with(['task', 'staff.staffType', 'assignedBy'])
                ->whereHas('task', fn($q) => $q->active());

            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            if (!empty($filters['assignee'])) {
                $query->where('staff_id', $filters['assignee']);
            }

            if (!empty($filters['due_date'])) {
                $query->whereDate('due_date', $filters['due_date']);
            }

            return $query->orderBy('created_at', 'desc')->paginate(20);
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Store a new task.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'task_type' => 'required|in:daily,weekly,monthly,project,maintenance',
                'priority' => 'required|in:low,medium,high,urgent',
                'category' => 'required|string|max:50',
                'estimated_hours' => 'nullable|numeric|min:0|max:999.99',
                'is_template' => 'boolean',
                'template_name' => 'nullable|string|max:255',
                'requires_approval' => 'boolean',
                'tags' => 'nullable|array',
                'tags.*' => 'string|max:50',
            ]);

            $task = StaffTask::create([
                ...$validated,
                'is_active' => true,
                'created_by' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => __('staff.tasks.task_created_successfully'),
                'task' => $task->load('creator'),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('staff.tasks.task_creation_failed'),
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Show a specific task.
     */
    public function show(StaffTask $task): JsonResponse
    {
        try {
            $task->load([
                'creator',
                'updater',
                'assignments.staff.staffType',
                'assignments.comments.staff',
                'assignments.attachments',
                'assignments.timeEntries.staff',
                'assignments.notifications',
                'dependencies.dependsOnTask',
                'dependentTasks.task',
            ]);

            return response()->json([
                'success' => true,
                'task' => $task,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('staff.tasks.task_not_found'),
            ], 404);
        }
    }

    /**
     * Update a task.
     */
    public function update(Request $request, StaffTask $task): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'task_type' => 'required|in:daily,weekly,monthly,project,maintenance',
                'priority' => 'required|in:low,medium,high,urgent',
                'category' => 'required|string|max:50',
                'estimated_hours' => 'nullable|numeric|min:0|max:999.99',
                'is_template' => 'boolean',
                'template_name' => 'nullable|string|max:255',
                'requires_approval' => 'boolean',
                'tags' => 'nullable|array',
                'tags.*' => 'string|max:50',
                'is_active' => 'boolean',
            ]);

            $task->update([
                ...$validated,
                'updated_by' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => __('staff.tasks.task_updated_successfully'),
                'task' => $task->fresh()->load('creator', 'updater'),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('staff.tasks.task_update_failed'),
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Delete a task.
     */
    public function destroy(StaffTask $task): JsonResponse
    {
        try {
            $task->delete();

            return response()->json([
                'success' => true,
                'message' => __('staff.tasks.task_deleted_successfully'),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('staff.tasks.task_deletion_failed'),
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Assign a task to staff member(s).
     */
    public function assign(Request $request, StaffTask $task): JsonResponse
    {
        try {
            $validated = $request->validate([
                'staff_ids' => 'required|array|min:1',
                'staff_ids.*' => 'exists:staff,id',
                'due_date' => 'nullable|date|after:today',
                'priority_override' => 'nullable|in:low,medium,high,urgent',
                'notes' => 'nullable|string',
            ]);

            $assignments = [];
            
            foreach ($validated['staff_ids'] as $staffId) {
                $assignment = StaffTaskAssignment::create([
                    'staff_task_id' => $task->id,
                    'staff_id' => $staffId,
                    'assigned_date' => now(),
                    'due_date' => $validated['due_date'] ?? null,
                    'status' => 'assigned',
                    'priority_override' => $validated['priority_override'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                    'assigned_by' => auth()->id(),
                ]);

                $assignments[] = $assignment->load('staff.staffType');
            }

            return response()->json([
                'success' => true,
                'message' => __('staff.tasks.task_assigned_successfully'),
                'assignments' => $assignments,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('staff.tasks.task_assignment_failed'),
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Update assignment status.
     */
    public function updateAssignmentStatus(Request $request, StaffTaskAssignment $assignment): JsonResponse
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:assigned,in_progress,completed,cancelled',
                'progress_percentage' => 'nullable|integer|min:0|max:100',
                'notes' => 'nullable|string',
            ]);

            $oldStatus = $assignment->status;
            
            $assignment->update([
                'status' => $validated['status'],
                'progress_percentage' => $validated['progress_percentage'] ?? $assignment->progress_percentage,
                'notes' => $validated['notes'] ?? $assignment->notes,
                'started_at' => $validated['status'] === 'in_progress' && !$assignment->started_at ? now() : $assignment->started_at,
                'completed_at' => $validated['status'] === 'completed' ? now() : null,
                'completed_by' => $validated['status'] === 'completed' ? auth()->id() : null,
                'updated_by' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => __('staff.tasks.assignment_status_updated'),
                'assignment' => $assignment->fresh()->load('staff.staffType', 'task'),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('staff.tasks.assignment_update_failed'),
                'error' => $e->getMessage(),
            ], 422);
        }
    }
}
