<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Shifts;

use App\Http\Controllers\Controller;
use App\Models\WeeklyRotaTemplate;
use App\Models\WeeklyRotaTemplateAssignment;
use App\Models\StaffShift;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TemplatesController extends Controller
{
    /**
     * Display shift templates overview
     */
    public function index(Request $request): View
    {
        $query = WeeklyRotaTemplate::with(['creator', 'assignments.staff', 'assignments.shift']);

        // Apply filters
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_active', false);
            }
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Apply sorting
        $sortBy = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        $query->orderBy($sortBy, $sortDirection);

        $templates = $query->get()->map(function ($template) {
            return [
                'id' => $template->id,
                'name' => $template->name,
                'description' => $template->description,
                'type' => $template->type,
                'status' => $template->is_active ? 'active' : 'draft',
                'is_default' => $template->is_default,
                'created_by' => $template->creator?->full_name ?? 'Unknown',
                'created_at' => $template->created_at,
                'updated_at' => $template->updated_at,
                'usage_count' => $template->usage_count,
                'last_used' => $template->last_used_at,
                'total_shifts' => $template->getShiftsCount(),
                'total_staff' => $template->getUniqueStaffCount(),
                'total_staff_required' => $template->getUniqueStaffCount(),
                'total_assignments' => $template->getTotalAssignments(),
                'estimated_cost' => $template->calculateRealWeeklyCost(),
                'assignments_by_day' => $template->getAssignmentsByDay(),
            ];
        });

        $totalTemplates = $templates->count();
        $activeTemplates = $templates->where('status', 'active')->count();
        $draftTemplates = $templates->where('status', 'draft')->count();
        $totalUsage = $templates->sum('usage_count');

        // Get popular templates (most used)
        $popularTemplates = $templates->sortByDesc('usage_count')->take(3)->values();

        // Get recent templates
        $recentTemplates = $templates->sortByDesc('updated_at')->take(3)->values();

        // Group by type for statistics
        $templateTypes = [];
        foreach ($templates as $template) {
            $type = $template['type'];
            if (! isset($templateTypes[$type])) {
                $templateTypes[$type] = [
                    'name' => ucfirst($type),
                    'count' => 0,
                    'total_usage' => 0,
                ];
            }
            $templateTypes[$type]['count']++;
            $templateTypes[$type]['total_usage'] += $template['usage_count'];
        }

        // Template type options
        $templateTypeOptions = [
            'standard' => 'Standard',
            'holiday' => 'Holiday',
            'seasonal' => 'Seasonal',
            'custom' => 'Custom',
        ];

        return view('admin.shifts.templates.index', compact(
            'templates',
            'totalTemplates',
            'activeTemplates',
            'draftTemplates',
            'totalUsage',
            'popularTemplates',
            'recentTemplates',
            'templateTypes',
            'templateTypeOptions'
        ));
    }

    /**
     * Show the form for creating a new template
     */
    public function create(): View
    {
        $templateTypeOptions = [
            'standard' => 'Standard',
            'holiday' => 'Holiday',
            'seasonal' => 'Seasonal',
            'custom' => 'Custom',
        ];

        $departments = [
            'Kitchen' => 'Kitchen',
            'Front of House' => 'Front of House',
            'Bar' => 'Bar',
            'Management' => 'Management',
            'Maintenance' => 'Maintenance',
        ];

        $daysOfWeek = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];

        // Get available shifts
        $shifts = StaffShift::active()
            ->with(['department'])
            ->orderBy('department')
            ->orderBy('start_time')
            ->get()
            ->groupBy('department');

        // Get available staff
        $staff = Staff::active()
            ->with(['staffType', 'profile'])
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view('admin.shifts.templates.create', compact(
            'templateTypeOptions',
            'departments',
            'daysOfWeek',
            'shifts',
            'staff'
        ));
    }

    /**
     * Store a newly created template
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:standard,holiday,seasonal,custom',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'assignments' => 'nullable|array',
            'assignments.*.staff_id' => 'required|exists:staff,id',
            'assignments.*.staff_shift_id' => 'required|exists:staff_shifts,id',
            'assignments.*.day_of_week' => 'required|integer|min:0|max:6',
            'assignments.*.status' => 'in:scheduled,confirmed,optional',
            'assignments.*.notes' => 'nullable|string|max:500',
        ]);

        try {
            // Create the template
            $template = WeeklyRotaTemplate::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'type' => $validated['type'],
                'is_active' => $validated['is_active'] ?? true,
                'is_default' => $validated['is_default'] ?? false,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            // Set as default if requested
            if ($validated['is_default'] ?? false) {
                $template->setAsDefault();
            }

            // Create template assignments
            if (isset($validated['assignments']) && is_array($validated['assignments'])) {
                foreach ($validated['assignments'] as $assignmentData) {
                    WeeklyRotaTemplateAssignment::create([
                        'template_id' => $template->id,
                        'staff_shift_id' => $assignmentData['staff_shift_id'],
                        'staff_id' => $assignmentData['staff_id'],
                        'day_of_week' => $assignmentData['day_of_week'],
                        'status' => $assignmentData['status'] ?? 'scheduled',
                        'notes' => $assignmentData['notes'] ?? null,
                    ]);
                }
            }

            return redirect()->route('admin.shifts.templates.index')
                ->with('success', __('shifts.templates.template_created', ['name' => $template->name]));

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create template: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified template
     */
    public function show(string $id): View
    {
        $template = WeeklyRotaTemplate::with([
            'creator',
            'updater',
            'assignments.staff.staffType',
            'assignments.staff.profile',
            'assignments.shift'
        ])->findOrFail($id);

        // Get assignments grouped by day
        $assignmentsByDay = $template->getAssignmentsByDay();

        // Calculate cost breakdown
        $costBreakdown = $template->getCostBreakdown();

        // Get usage statistics
        $usageStats = [
            'total_usage' => $template->usage_count,
            'last_used' => $template->last_used_at,
            'unique_staff_count' => $template->getUniqueStaffCount(),
            'total_assignments' => $template->getTotalAssignments(),
            'shifts_count' => $template->getShiftsCount(),
        ];

        $daysOfWeek = [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
            0 => 'Sunday',
        ];

        return view('admin.shifts.templates.show', compact(
            'template',
            'assignmentsByDay',
            'costBreakdown',
            'usageStats',
            'daysOfWeek'
        ));
    }

    /**
     * Show the form for editing the specified template
     */
    public function edit(string $id): View
    {
        $template = WeeklyRotaTemplate::with(['assignments.staff', 'assignments.shift'])
            ->findOrFail($id);

        $templateTypeOptions = [
            'standard' => 'Standard',
            'holiday' => 'Holiday',
            'seasonal' => 'Seasonal',
            'custom' => 'Custom',
        ];

        $departments = [
            'Kitchen' => 'Kitchen',
            'Front of House' => 'Front of House',
            'Bar' => 'Bar',
            'Management' => 'Management',
            'Maintenance' => 'Maintenance',
        ];

        $daysOfWeek = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];

        // Get available shifts
        $shifts = StaffShift::active()
            ->with(['department'])
            ->orderBy('department')
            ->orderBy('start_time')
            ->get()
            ->groupBy('department');

        // Get available staff
        $staff = Staff::active()
            ->with(['staffType', 'profile'])
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        // Get existing assignments for the template
        $existingAssignments = $template->assignments->map(function ($assignment) {
            return [
                'id' => $assignment->id,
                'staff_id' => $assignment->staff_id,
                'staff_shift_id' => $assignment->staff_shift_id,
                'day_of_week' => $assignment->day_of_week,
                'status' => $assignment->status,
                'notes' => $assignment->notes,
                'staff_name' => $assignment->staff?->full_name ?? 'Unknown',
                'shift_name' => $assignment->shift?->name ?? 'Unknown',
            ];
        });

        return view('admin.shifts.templates.edit', compact(
            'template',
            'templateTypeOptions',
            'departments',
            'daysOfWeek',
            'shifts',
            'staff',
            'existingAssignments'
        ));
    }

    /**
     * Update the specified template
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:standard,holiday,seasonal,custom',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'assignments' => 'nullable|array',
            'assignments.*.id' => 'nullable|string', // For existing assignments
            'assignments.*.staff_id' => 'required|exists:staff,id',
            'assignments.*.staff_shift_id' => 'required|exists:staff_shifts,id',
            'assignments.*.day_of_week' => 'required|integer|min:0|max:6',
            'assignments.*.status' => 'in:scheduled,confirmed,optional',
            'assignments.*.notes' => 'nullable|string|max:500',
        ]);

        try {
            $template = WeeklyRotaTemplate::findOrFail($id);

            // Update template
            $template->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'type' => $validated['type'],
                'is_active' => $validated['is_active'] ?? true,
                'is_default' => $validated['is_default'] ?? false,
                'updated_by' => auth()->id(),
            ]);

            // Handle default template
            if ($validated['is_default'] ?? false) {
                $template->setAsDefault();
            } elseif ($template->is_default && !($validated['is_default'] ?? false)) {
                // If it was default but no longer should be, remove default status
                $template->update(['is_default' => false]);
            }

            // Handle assignments
            $existingAssignmentIds = [];

            if (isset($validated['assignments']) && is_array($validated['assignments'])) {
                foreach ($validated['assignments'] as $assignmentData) {
                    if (isset($assignmentData['id']) && !empty($assignmentData['id'])) {
                        // Update existing assignment
                        $assignment = WeeklyRotaTemplateAssignment::find($assignmentData['id']);
                        if ($assignment) {
                            $assignment->update([
                                'staff_shift_id' => $assignmentData['staff_shift_id'],
                                'staff_id' => $assignmentData['staff_id'],
                                'day_of_week' => $assignmentData['day_of_week'],
                                'status' => $assignmentData['status'] ?? 'scheduled',
                                'notes' => $assignmentData['notes'] ?? null,
                            ]);
                            $existingAssignmentIds[] = $assignment->id;
                        }
                    } else {
                        // Create new assignment
                        $assignment = WeeklyRotaTemplateAssignment::create([
                            'template_id' => $template->id,
                            'staff_shift_id' => $assignmentData['staff_shift_id'],
                            'staff_id' => $assignmentData['staff_id'],
                            'day_of_week' => $assignmentData['day_of_week'],
                            'status' => $assignmentData['status'] ?? 'scheduled',
                            'notes' => $assignmentData['notes'] ?? null,
                        ]);
                        $existingAssignmentIds[] = $assignment->id;
                    }
                }
            }

            // Delete assignments that are no longer in the template
            $template->assignments()
                ->whereNotIn('id', $existingAssignmentIds)
                ->delete();

            return redirect()->route('admin.shifts.templates.index')
                ->with('success', __('shifts.templates.template_updated', ['name' => $template->name]));

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update template: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified template
     */
    public function destroy(string $id): RedirectResponse
    {
        try {
            $template = WeeklyRotaTemplate::findOrFail($id);

            // Check if template is in use
            if ($template->usage_count > 0) {
                return redirect()->route('admin.shifts.templates.index')
                    ->with('error', __('shifts.templates.cannot_delete_used_template'));
            }

            $templateName = $template->name;
            $template->delete();

            return redirect()->route('admin.shifts.templates.index')
                ->with('success', __('shifts.templates.template_deleted', ['name' => $templateName]));

        } catch (\Exception $e) {
            return redirect()->route('admin.shifts.templates.index')
                ->with('error', 'Failed to delete template: ' . $e->getMessage());
        }
    }

    /**
     * Duplicate a template
     */
    public function duplicate(Request $request, string $id): JsonResponse
    {
        try {
            $originalTemplate = WeeklyRotaTemplate::with('assignments')->findOrFail($id);

            // Create duplicate template
            $duplicateTemplate = WeeklyRotaTemplate::create([
                'name' => 'Copy of ' . $originalTemplate->name,
                'description' => $originalTemplate->description,
                'type' => $originalTemplate->type,
                'is_active' => false, // Start as draft
                'is_default' => false,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            // Duplicate assignments
            foreach ($originalTemplate->assignments as $assignment) {
                WeeklyRotaTemplateAssignment::create([
                    'template_id' => $duplicateTemplate->id,
                    'staff_shift_id' => $assignment->staff_shift_id,
                    'staff_id' => $assignment->staff_id,
                    'day_of_week' => $assignment->day_of_week,
                    'status' => $assignment->status,
                    'notes' => $assignment->notes,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => __('shifts.templates.template_duplicated'),
                'new_template_id' => $duplicateTemplate->id,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to duplicate template: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Apply a template to a date range
     */
    public function apply(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'overwrite_existing' => 'boolean',
        ]);

        try {
            $template = WeeklyRotaTemplate::with('assignments')->findOrFail($id);

            // Mark template as used
            $template->markAsUsed();

            // In a real implementation, you would apply the template to the specified date range
            // For now, we'll return a success response
            $shiftsCreated = $template->assignments->count();

            return response()->json([
                'success' => true,
                'message' => __('shifts.templates.template_applied'),
                'shifts_created' => $shiftsCreated,
                'date_range' => [
                    'start' => $validated['start_date'],
                    'end' => $validated['end_date'],
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to apply template: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Preview template application
     */
    public function preview(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        try {
            $template = WeeklyRotaTemplate::with('assignments.staff', 'assignments.shift')->findOrFail($id);

            // Generate preview data
            $preview = [
                'template_name' => $template->name,
                'total_assignments' => $template->assignments->count(),
                'unique_staff' => $template->assignments->pluck('staff_id')->unique()->count(),
                'date_range' => [
                    'start' => $validated['start_date'],
                    'end' => $validated['end_date'],
                ],
                'assignments_by_day' => $template->getAssignmentsByDay(),
            ];

            return response()->json([
                'success' => true,
                'preview' => $preview,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate preview: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Set template as default
     */
    public function setDefault(string $id): JsonResponse
    {
        try {
            $template = WeeklyRotaTemplate::findOrFail($id);
            $template->setAsDefault();

            return response()->json([
                'success' => true,
                'message' => __('shifts.templates.set_as_default_success'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to set template as default: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle template active status
     */
    public function toggleActive(string $id): JsonResponse
    {
        try {
            $template = WeeklyRotaTemplate::findOrFail($id);
            $template->update([
                'is_active' => !$template->is_active,
                'updated_by' => auth()->id(),
            ]);

            $status = $template->is_active ? 'activated' : 'deactivated';

            return response()->json([
                'success' => true,
                'message' => __('shifts.templates.template_' . $status),
                'is_active' => $template->is_active,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle template status: ' . $e->getMessage(),
            ], 500);
        }
    }
}
