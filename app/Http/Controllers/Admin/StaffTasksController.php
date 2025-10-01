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
            
            // Get task settings for filters
            $taskTypes = \App\Models\TaskType::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
            $taskPriorities = \App\Models\TaskPriority::where('is_active', true)->orderBy('sort_order')->orderBy('level')->get();
            $taskCategories = \App\Models\TaskCategory::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
            
            return view('admin.staff.tasks', compact(
                'dashboardData',
                'tasks',
                'assignments',
                'staffMembers',
                'staffTypes',
                'taskTypes',
                'taskPriorities',
                'taskCategories',
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
                'taskTypes' => collect(),
                'taskPriorities' => collect(),
                'taskCategories' => collect(),
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
            // Count tasks that actually have assignments (real active tasks)
            $totalTasks = StaffTask::active()
                ->whereHas('assignments')
                ->count();
            
            $totalAssignments = StaffTaskAssignment::whereHas('task', fn($q) => $q->active())->count();
            $completedAssignments = StaffTaskAssignment::where('status', 'completed')
                ->whereHas('task', fn($q) => $q->active())
                ->count();
            
            $overdueAssignments = StaffTaskAssignment::where('status', '!=', 'completed')
                ->whereHas('task', fn($q) => $q->active())
                ->where('due_date', '<', now())
                ->count();
            
            $inProgressAssignments = StaffTaskAssignment::where('status', 'in_progress')
                ->whereHas('task', fn($q) => $q->active())
                ->count();

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
            $query = StaffTask::with(['creator', 'assignments.staff.staffType', 'taskType', 'taskPriority', 'taskCategory'])
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
            $query = StaffTaskAssignment::with(['task.taskType', 'task.taskPriority', 'task.taskCategory', 'staff.staffType', 'assignedBy'])
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
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'instructions' => 'nullable|string',
                'task_type' => 'required|exists:task_types,slug',
                'priority' => 'required|exists:task_priorities,slug',
                'category' => 'required|exists:task_categories,slug',
                'estimated_hours' => 'nullable|numeric|min:0|max:999.99',
                'duration_minutes' => 'nullable|integer|min:1|max:1440',
                'scheduled_date' => 'nullable|date|after_or_equal:today',
                'scheduled_time' => 'nullable|date_format:H:i',
                'is_template' => 'boolean',
                'template_name' => 'nullable|string|max:255',
                'requires_approval' => 'boolean',
                'auto_assign' => 'boolean',
                'assigned_staff' => 'nullable|array',
                'assigned_staff.*' => 'exists:staff,id',
                'tags' => 'nullable|string', // Handle as string and convert to array
            ]);

            // Convert tags string to array
            if (!empty($validated['tags'])) {
                $validated['tags'] = array_map('trim', explode(',', $validated['tags']));
            } else {
                $validated['tags'] = [];
            }

            // Handle staff assignments
            $assignedStaff = $validated['assigned_staff'] ?? [];
            unset($validated['assigned_staff']); // Remove from task data

            // Store default assignees if auto_assign is enabled
            if ($validated['auto_assign'] && !empty($assignedStaff)) {
                $validated['default_assignees'] = $assignedStaff;
            }

            $task = StaffTask::create([
                ...$validated,
                'is_active' => true,
                'created_by' => auth()->id(),
            ]);

            // Create assignments
            if ($validated['auto_assign'] && !empty($assignedStaff)) {
                // Create assignments for selected staff
                $this->createTaskAssignments($task, $assignedStaff, $validated);
            } else {
                // If no auto-assign or no staff selected, assign to task creator
                $this->createTaskAssignments($task, [auth()->id()], $validated);
            }

            return redirect()->route('admin.staff.tasks.index')
                ->with('success', __('staff.tasks.task_created_successfully'));

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Validation failed. Please check the form and try again.');
        } catch (\Exception $e) {
            \Log::error('Task creation failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', __('staff.tasks.task_creation_failed') . ': ' . $e->getMessage());
        }
    }

    /**
     * Show a specific task.
     */
    public function show(StaffTask $task): View
    {
        $task->load([
            'creator',
            'updater',
            'taskType',
            'taskPriority', 
            'taskCategory',
            'assignments.staff.staffType',
            'assignments.comments.staff',
            'assignments.attachments',
            'assignments.timeEntries.staff',
            'assignments.notifications',
            'dependencies.dependsOnTask',
            'dependentTasks.task',
        ]);

        return view('admin.staff.tasks-show', compact('task'));
    }

    /**
     * Get task details for modal display.
     */
    public function modal(StaffTask $task)
    {
        try {
            // Load the task with safe relationships
            $task->load([
                'assignments.staff.staffType',
                'assignments.staff',
                'taskType',
                'taskPriority', 
                'taskCategory'
            ]);

            // Try to load creator/updater relationships safely
            try {
                $task->load(['creator', 'updater']);
            } catch (\Exception $e) {
                // Ignore if these relationships don't exist
                \Log::info('Creator/Updater relationships not available: ' . $e->getMessage());
            }

            $html = view('admin.staff.tasks-modal-content', compact('task'))->render();

            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to load task modal: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load task details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a task.
     */
    public function update(Request $request, StaffTask $task)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'instructions' => 'nullable|string',
                'task_type' => 'required|exists:task_types,slug',
                'priority' => 'required|exists:task_priorities,slug',
                'category' => 'required|exists:task_categories,slug',
                'estimated_hours' => 'nullable|numeric|min:0|max:999.99',
                'duration_minutes' => 'nullable|integer|min:1|max:1440',
                'scheduled_date' => 'nullable|date',
                'scheduled_time' => 'nullable|date_format:H:i',
                'auto_assign' => 'boolean',
                'assigned_staff' => 'nullable|array',
                'assigned_staff.*' => 'exists:staff,id',
                'is_template' => 'boolean',
                'template_name' => 'nullable|string|max:255',
                'requires_approval' => 'boolean',
                'tags' => 'nullable|string', // Handle as string and convert to array
                'is_active' => 'boolean',
            ]);

            // Convert tags string to array
            if (!empty($validated['tags'])) {
                $validated['tags'] = array_map('trim', explode(',', $validated['tags']));
            } else {
                $validated['tags'] = [];
            }

            // Remove assigned_staff from validated data before updating task
            $assignedStaff = $validated['assigned_staff'] ?? [];
            unset($validated['assigned_staff']);

            $task->update([
                ...$validated,
                'updated_by' => auth()->id(),
            ]);

            // Update staff assignments if auto_assign is enabled
            if ($validated['auto_assign']) {
                if (!empty($assignedStaff)) {
                    // Remove existing assignments and recreate using the same helper
                    // used by the create flow so all required columns are filled.
                    $task->assignments()->delete();

                    // This will set assigned_date, due_date, scheduled_date/time, etc.
                    $this->createTaskAssignments($task, $assignedStaff, $validated);
                }
                // If auto_assign is enabled but no staff selected, keep existing assignments
            }
            // If auto_assign is disabled, keep existing assignments (don't delete them)

            return redirect()->route('admin.staff.tasks.index')
                ->with('success', __('staff.tasks.task_updated_successfully'));

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', __('staff.tasks.task_update_failed') . ': ' . $e->getMessage());
        }
    }

    /**
     * Delete a task.
     */
    public function destroy(StaffTask $task)
    {
        try {
            $task->delete();

            return redirect()->route('admin.staff.tasks.index')
                ->with('success', __('staff.tasks.task_deleted_successfully'));

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('staff.tasks.task_deletion_failed') . ': ' . $e->getMessage());
        }
    }

    /**
     * Handle bulk actions on task assignments.
     */
    public function bulkAction(Request $request)
    {
        try {
            $validated = $request->validate([
                'action' => 'required|in:delete,complete,pending,in_progress',
                'assignment_ids' => 'required|array|min:1',
                'assignment_ids.*' => 'exists:staff_task_assignments,id',
            ]);

            $assignmentIds = $validated['assignment_ids'];
            $action = $validated['action'];
            $count = 0;

            switch ($action) {
                case 'delete':
                    // Delete the task assignments and their tasks if no other assignments exist
                    $assignments = \App\Models\StaffTaskAssignment::whereIn('id', $assignmentIds)->get();
                    $tasksToCheck = [];
                    
                    foreach ($assignments as $assignment) {
                        $tasksToCheck[] = $assignment->task_id;
                        $assignment->delete();
                        $count++;
                    }
                    
                    // Clean up orphaned tasks (tasks with no assignments)
                    $uniqueTaskIds = array_unique($tasksToCheck);
                    foreach ($uniqueTaskIds as $taskId) {
                        $task = StaffTask::find($taskId);
                        if ($task && $task->assignments()->count() === 0) {
                            $task->delete();
                        }
                    }
                    
                    // Also clean up any other orphaned tasks
                    $this->cleanupOrphanedTasks();
                    break;

                case 'complete':
                    $count = \App\Models\StaffTaskAssignment::whereIn('id', $assignmentIds)
                        ->update([
                            'status' => 'completed',
                            'completed_at' => now(),
                            'progress_percentage' => 100,
                        ]);
                    break;

                case 'pending':
                    $count = \App\Models\StaffTaskAssignment::whereIn('id', $assignmentIds)
                        ->update([
                            'status' => 'pending',
                            'completed_at' => null,
                            'progress_percentage' => 0,
                        ]);
                    break;

                case 'in_progress':
                    // For in_progress, keep existing progress if > 0, otherwise set to 25%
                    $assignments = \App\Models\StaffTaskAssignment::whereIn('id', $assignmentIds)->get();
                    foreach ($assignments as $assignment) {
                        $newProgress = $assignment->progress_percentage > 0 ? $assignment->progress_percentage : 25;
                        $assignment->update([
                            'status' => 'in_progress',
                            'completed_at' => null,
                            'progress_percentage' => $newProgress,
                            'started_at' => $assignment->started_at ?: now(),
                        ]);
                    }
                    $count = $assignments->count();
                    break;
            }

            $message = match($action) {
                'delete' => "Successfully deleted {$count} task assignment(s).",
                'complete' => "Successfully marked {$count} task(s) as completed.",
                'pending' => "Successfully marked {$count} task(s) as pending.",
                'in_progress' => "Successfully marked {$count} task(s) as in progress.",
            };

            return redirect()->route('admin.staff.tasks.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('staff.tasks.bulk_action_failed') . ': ' . $e->getMessage());
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
                    'status' => 'pending',
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
                'status' => 'required|in:pending,in_progress,completed,cancelled,overdue',
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

    /**
     * Show the form for creating a new task.
     */
    public function create(): View
    {
        $staffMembers = Staff::active()->with('staffType')->get();
        $staffTypes = StaffType::active()->get();
        
        // Get task settings data from database
        $taskTypes = \App\Models\TaskType::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        $taskPriorities = \App\Models\TaskPriority::where('is_active', true)->orderBy('sort_order')->orderBy('level')->get();
        $taskCategories = \App\Models\TaskCategory::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        $taskTags = \App\Models\TaskTag::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.staff.tasks-create', compact(
            'staffMembers', 
            'staffTypes', 
            'taskTypes', 
            'taskPriorities', 
            'taskCategories', 
            'taskTags'
        ));
    }

    /**
     * Show the form for editing a task.
     */
    public function edit(StaffTask $task): View
    {
        // Load task relationships
        $task->load(['assignments.staff']);
        
        $staffMembers = Staff::active()->with('staffType')->get();
        $staffTypes = StaffType::active()->get();
        
        // Get task settings data from database
        $taskTypes = \App\Models\TaskType::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        $taskPriorities = \App\Models\TaskPriority::where('is_active', true)->orderBy('sort_order')->orderBy('level')->get();
        $taskCategories = \App\Models\TaskCategory::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        $taskTags = \App\Models\TaskTag::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.staff.tasks-edit', compact(
            'task', 
            'staffMembers', 
            'staffTypes', 
            'taskTypes', 
            'taskPriorities', 
            'taskCategories', 
            'taskTags'
        ));
    }

    /**
     * Create task assignments for selected staff members.
     */
    private function createTaskAssignments(StaffTask $task, array $staffIds, array $taskData): void
    {
        $baseDate = $taskData['scheduled_date'] ?? now()->toDateString();
        $baseTime = $taskData['scheduled_time'] ?? null;
        
        // Combine date and time if both are provided
        $scheduledDateTime = null;
        if ($baseDate && $baseTime) {
            $scheduledDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $baseDate . ' ' . $baseTime);
        }

        foreach ($staffIds as $staffId) {
            StaffTaskAssignment::create([
                'staff_task_id' => $task->id,
                'staff_id' => $staffId,
                'assigned_date' => now()->toDateString(),
                'due_date' => $baseDate,
                'scheduled_date' => $baseDate,
                'scheduled_time' => $baseTime,
                'scheduled_datetime' => $scheduledDateTime,
                'status' => 'pending',
                'assigned_by' => auth()->id(),
                'estimated_hours' => $taskData['estimated_hours'] ?? null,
                'assignment_notes' => 'Auto-assigned when task was created.',
            ]);
        }
    }

    /**
     * Clean up orphaned tasks (tasks without any assignments) and orphaned assignments.
     */
    private function cleanupOrphanedTasks(): int
    {
        // Clean up tasks without assignments
        $orphanedTasksCount = StaffTask::whereDoesntHave('assignments')->count();
        StaffTask::whereDoesntHave('assignments')->delete();
        
        // Clean up assignments without tasks
        $orphanedAssignmentsCount = \App\Models\StaffTaskAssignment::whereDoesntHave('task')->count();
        \App\Models\StaffTaskAssignment::whereDoesntHave('task')->delete();
        
        return $orphanedTasksCount + $orphanedAssignmentsCount;
    }

}
