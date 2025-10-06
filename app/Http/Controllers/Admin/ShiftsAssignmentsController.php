<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\StaffShift;
use App\Models\StaffShiftAssignment;
use App\Models\StaffShiftException;
use App\Models\WeeklyRotaTemplate;
use App\Models\WeeklyRotaTemplateAssignment;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShiftsAssignmentsController extends Controller
{
    /**
     * Display the weekly shift assignments (rota) page.
     */
    public function index(Request $request): View
    {
        // Get the week start date (default to current week Monday)
        $weekStart = $request->get('week') 
            ? Carbon::parse($request->get('week'))->startOfWeek() 
            : Carbon::now()->startOfWeek();
        
        $weekEnd = $weekStart->copy()->endOfWeek();

        // Get all active shift templates
        $shiftTemplates = StaffShift::templates()
            ->active()
            ->with(['assignments' => function ($query) use ($weekStart, $weekEnd) {
                $query->whereBetween('assigned_date', [$weekStart, $weekEnd])
                      ->with(['staff.staffType', 'staff.profile']);
            }])
            ->orderBy('department')
            ->orderBy('start_time')
            ->get();

        // Get all active staff with their profiles and types
        $staff = Staff::active()
            ->with(['staffType', 'profile'])
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        // Get existing assignments for the week
        $assignments = StaffShiftAssignment::whereBetween('assigned_date', [$weekStart, $weekEnd])
            ->with(['staff.staffType', 'staff.profile', 'shift'])
            ->get()
            ->groupBy(function ($assignment) {
                return $assignment->assigned_date->format('Y-m-d') . '_' . $assignment->staff_shift_id;
            });

        // Generate week days
        $weekDays = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $weekStart->copy()->addDays($i);
            $weekDays[] = [
                'date' => $date,
                'formatted' => $date->format('Y-m-d'),
                'display' => $date->format('D j'),
                'full' => $date->format('l, F j, Y'),
                'is_today' => $date->isToday(),
                'is_weekend' => $date->isWeekend(),
            ];
        }

        // Get departments for filtering
        $departments = $shiftTemplates->pluck('department')->unique()->sort()->values();

        return view('admin.shifts.assignments.index', compact(
            'shiftTemplates',
            'staff',
            'assignments',
            'weekDays',
            'weekStart',
            'weekEnd',
            'departments'
        ));
    }

    /**
     * Store a new shift assignment.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'staff_shift_id' => 'required|exists:staff_shifts,id',
            'assigned_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            // Check for existing assignment (prevent double booking)
            $existingAssignment = StaffShiftAssignment::where([
                'staff_id' => $validated['staff_id'],
                'assigned_date' => $validated['assigned_date'],
                'staff_shift_id' => $validated['staff_shift_id'],
            ])->first();

            if ($existingAssignment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Staff member is already assigned to this shift on this date.',
                ], 422);
            }

            // Create the assignment
            $assignment = StaffShiftAssignment::create([
                'staff_id' => $validated['staff_id'],
                'staff_shift_id' => $validated['staff_shift_id'],
                'assigned_date' => $validated['assigned_date'],
                'status' => 'scheduled',
                'notes' => $validated['notes'] ?? null,
                'assigned_by' => auth()->user()->id ?? null,
            ]);

            // Load relationships for response
            $assignment->load(['staff.staffType', 'staff.profile', 'shift']);

            return response()->json([
                'success' => true,
                'message' => 'Shift assigned successfully.',
                'assignment' => [
                    'id' => $assignment->id,
                    'staff_name' => $assignment->staff->full_name,
                    'staff_type' => $assignment->staff->staffType?->display_name ?? 'No Type',
                    'staff_photo' => $assignment->staff->profile?->photo_url,
                    'shift_name' => $assignment->shift->name,
                    'status' => $assignment->status,
                    'notes' => $assignment->notes,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign shift: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update an existing shift assignment.
     */
    public function update(Request $request, StaffShiftAssignment $assignment): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'sometimes|in:scheduled,confirmed,cancelled,completed',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $assignment->update(array_merge(
                $validated,
                ['updated_by' => auth()->user()->id ?? null]
            ));

            return response()->json([
                'success' => true,
                'message' => 'Assignment updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update assignment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show details for a specific assignment.
     */
    public function show(StaffShiftAssignment $assignment): JsonResponse
    {
        $assignment->load([
            'staff.staffType',
            'staff.profile',
            'shift',
            'exceptions.replacementStaff',
        ]);

        return response()->json([
            'success' => true,
            'assignment' => [
                'id' => $assignment->id,
                'date' => $assignment->assigned_date->format('Y-m-d'),
                'status' => $assignment->status,
                'notes' => $assignment->notes,
                'staff' => [
                    'id' => $assignment->staff->id,
                    'name' => $assignment->staff->full_name,
                    'type' => $assignment->staff->staffType?->display_name,
                    'photo' => $assignment->staff->profile?->photo_url,
                ],
                'shift' => [
                    'id' => $assignment->shift->id,
                    'name' => $assignment->shift->name,
                    'department' => $assignment->shift->department,
                    'start_time' => \Carbon\Carbon::parse($assignment->shift->start_time)->format('H:i'),
                    'end_time' => \Carbon\Carbon::parse($assignment->shift->end_time)->format('H:i'),
                    'break_minutes' => $assignment->shift->break_minutes ?? 0,
                    'min_staff_required' => $assignment->shift->min_staff_required,
                    'max_staff_allowed' => $assignment->shift->max_staff_allowed,
                ],
                'actuals' => [
                    'start' => optional($assignment->actual_start_time)?->format('Y-m-d H:i'),
                    'end' => optional($assignment->actual_end_time)?->format('Y-m-d H:i'),
                    'worked_hours' => $assignment->getTotalHoursWorked(),
                ],
                'exceptions' => $assignment->exceptions->map(function ($ex) {
                    return [
                        'id' => $ex->id,
                        'type' => $ex->exception_type,
                        'minutes_affected' => $ex->minutes_affected,
                        'status' => $ex->status,
                        'replacement' => $ex->replacementStaff?->full_name,
                        'description' => $ex->description,
                    ];
                }),
            ],
        ]);
    }

    /**
     * Record an exception (e.g., no-show, early departure, replacement coverage).
     */
    public function addException(Request $request, StaffShiftAssignment $assignment): JsonResponse
    {
        $validated = $request->validate([
            'exception_type' => 'required|in:late_arrival,early_departure,extended_break,no_show,sick_call_out,emergency_leave,overtime,role_change,replacement,other',
            'minutes_affected' => 'nullable|integer|min:0',
            'financial_impact' => 'nullable|numeric|min:0',
            'replacement_staff_id' => 'nullable|exists:staff,id',
            'replacement_start_time' => 'nullable|date',
            'replacement_end_time' => 'nullable|date|after:replacement_start_time',
            'description' => 'required|string|max:2000',
            'action_taken' => 'nullable|string|max:2000',
            'requires_disciplinary_action' => 'boolean',
            'affects_payroll' => 'boolean',
            'follow_up_notes' => 'nullable|string|max:2000',
        ]);

        $exception = StaffShiftException::create(array_merge($validated, [
            'assignment_id' => $assignment->id,
            'status' => 'reported',
            'reported_by' => auth()->user()->id ?? null,
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Exception recorded successfully.',
            'exception_id' => $exception->id,
        ]);
    }

    /**
     * Replace the currently assigned staff with another staff member.
     */
    public function replaceStaff(Request $request, StaffShiftAssignment $assignment): JsonResponse
    {
        $validated = $request->validate([
            'replacement_staff_id' => 'required|exists:staff,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Record replacement as an exception
        StaffShiftException::create([
            'assignment_id' => $assignment->id,
            'exception_type' => 'replacement',
            'minutes_affected' => 0,
            'financial_impact' => 0,
            'replacement_staff_id' => $validated['replacement_staff_id'],
            'description' => $validated['notes'] ?? 'Staff replaced',
            'status' => 'reported',
            'reported_by' => auth()->user()->id ?? null,
        ]);

        // Reassign
        $assignment->update([
            'staff_id' => $validated['replacement_staff_id'],
            'updated_by' => auth()->user()->id ?? null,
            'notes' => $request->input('notes', $assignment->notes),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Staff replaced successfully.',
        ]);
    }

    /**
     * Remove a shift assignment.
     */
    public function destroy(StaffShiftAssignment $assignment): JsonResponse
    {
        try {
            $staffName = $assignment->staff->full_name;
            $shiftName = $assignment->shift->name;
            
            $assignment->delete();

            return response()->json([
                'success' => true,
                'message' => "Removed {$staffName} from {$shiftName}.",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove assignment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get staff availability for a specific date.
     */
    public function getStaffAvailability(Request $request): JsonResponse
    {
        $date = $request->get('date');
        $shiftId = $request->get('shift_id');

        if (!$date) {
            return response()->json(['error' => 'Date is required'], 400);
        }

        // Get all active staff
        $allStaff = Staff::active()
            ->with(['staffType', 'profile'])
            ->orderBy('first_name')
            ->get();

        // Get staff already assigned on this date
        $assignedStaffIds = StaffShiftAssignment::where('assigned_date', $date)
            ->pluck('staff_id')
            ->toArray();

        // Mark availability
        $staffAvailability = $allStaff->map(function ($staff) use ($assignedStaffIds, $shiftId) {
            $isAssigned = in_array($staff->id, $assignedStaffIds);
            
            return [
                'id' => $staff->id,
                'name' => $staff->full_name,
                'type' => $staff->staffType?->display_name ?? 'No Type',
                'photo' => $staff->profile?->photo_url,
                'hourly_rate' => $staff->profile?->hourly_rate ?? 0,
                'is_available' => !$isAssigned,
                'status' => $isAssigned ? 'assigned' : 'available',
            ];
        });

        return response()->json([
            'staff' => $staffAvailability,
            'date' => $date,
        ]);
    }

    /**
     * Bulk assign shifts (for copying previous week, etc.)
     */
    public function bulkAssign(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'assignments' => 'required|array',
            'assignments.*.staff_id' => 'required|exists:staff,id',
            'assignments.*.staff_shift_id' => 'required|exists:staff_shifts,id',
            'assignments.*.assigned_date' => 'required|date',
        ]);

        try {
            $created = 0;
            $skipped = 0;
            $assignedBy = auth()->user()->id ?? null;

            foreach ($validated['assignments'] as $assignmentData) {
                // Check for existing assignment
                $exists = StaffShiftAssignment::where([
                    'staff_id' => $assignmentData['staff_id'],
                    'staff_shift_id' => $assignmentData['staff_shift_id'],
                    'assigned_date' => $assignmentData['assigned_date'],
                ])->exists();

                if (!$exists) {
                    StaffShiftAssignment::create([
                        'staff_id' => $assignmentData['staff_id'],
                        'staff_shift_id' => $assignmentData['staff_shift_id'],
                        'assigned_date' => $assignmentData['assigned_date'],
                        'status' => 'scheduled',
                        'assigned_by' => $assignedBy,
                    ]);
                    $created++;
                } else {
                    $skipped++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Bulk assignment completed. Created: {$created}, Skipped: {$skipped}",
                'created' => $created,
                'skipped' => $skipped,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bulk assignment failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Save current week's assignments as a template.
     */
    public function saveAsTemplate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:standard,holiday,seasonal,custom',
            'week_start' => 'required|date',
            'set_as_default' => 'boolean',
        ]);

        try {
            $weekStart = Carbon::parse($validated['week_start'])->startOfWeek();
            $weekEnd = $weekStart->copy()->endOfWeek();

            // Get all assignments for this week
            $assignments = StaffShiftAssignment::whereBetween('assigned_date', [$weekStart, $weekEnd])
                ->with(['staff', 'shift'])
                ->get();

            if ($assignments->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No assignments found for this week to save as template.',
                ], 400);
            }

            // Create the template
            $template = WeeklyRotaTemplate::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'type' => $validated['type'],
                'is_active' => true,
                'is_default' => $validated['set_as_default'] ?? false,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            // Set as default if requested
            if ($validated['set_as_default'] ?? false) {
                $template->setAsDefault();
            }

            // Save template assignments
            foreach ($assignments as $assignment) {
                $dayOfWeek = $assignment->assigned_date->dayOfWeek;
                
                WeeklyRotaTemplateAssignment::create([
                    'template_id' => $template->id,
                    'staff_shift_id' => $assignment->staff_shift_id,
                    'staff_id' => $assignment->staff_id,
                    'day_of_week' => $dayOfWeek,
                    'status' => $assignment->status,
                    'notes' => $assignment->notes,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => "Weekly rota template '{$template->name}' created successfully with {$assignments->count()} assignments.",
                'template_id' => $template->id,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save template: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Apply a template to a specific week.
     */
    public function applyTemplate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'template_id' => 'required|exists:weekly_rota_templates,id',
            'week_start' => 'required|date',
            'overwrite_existing' => 'boolean',
        ]);

        try {
            $template = WeeklyRotaTemplate::with('assignments.staff', 'assignments.shift')
                ->findOrFail($validated['template_id']);

            $weekStart = Carbon::parse($validated['week_start'])->startOfWeek();
            $overwriteExisting = $validated['overwrite_existing'] ?? false;

            $created = 0;
            $skipped = 0;
            $errors = [];

            foreach ($template->assignments as $templateAssignment) {
                $assignmentDate = $weekStart->copy()->addDays($templateAssignment->day_of_week);

                // Check if assignment already exists
                $existingAssignment = StaffShiftAssignment::where([
                    'staff_id' => $templateAssignment->staff_id,
                    'staff_shift_id' => $templateAssignment->staff_shift_id,
                    'assigned_date' => $assignmentDate->format('Y-m-d'),
                ])->first();

                if ($existingAssignment) {
                    if ($overwriteExisting) {
                        $existingAssignment->update([
                            'status' => $templateAssignment->status,
                            'notes' => $templateAssignment->notes,
                            'assigned_by' => auth()->id(),
                        ]);
                        $created++;
                    } else {
                        $skipped++;
                    }
                } else {
                    try {
                        StaffShiftAssignment::create([
                            'staff_id' => $templateAssignment->staff_id,
                            'staff_shift_id' => $templateAssignment->staff_shift_id,
                            'assigned_date' => $assignmentDate->format('Y-m-d'),
                            'status' => $templateAssignment->status,
                            'notes' => $templateAssignment->notes,
                            'assigned_by' => auth()->id(),
                        ]);
                        $created++;
                    } catch (\Exception $e) {
                        $errors[] = "Failed to assign {$templateAssignment->staff->full_name} to {$templateAssignment->shift->name} on {$assignmentDate->format('Y-m-d')}: " . $e->getMessage();
                        $skipped++;
                    }
                }
            }

            // Mark template as used
            $template->markAsUsed();

            $message = "Template applied successfully. Created: {$created}, Skipped: {$skipped}";
            if (!empty($errors)) {
                $message .= ". Errors: " . implode('; ', $errors);
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'created' => $created,
                'skipped' => $skipped,
                'errors' => $errors,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to apply template: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all available templates.
     */
    public function getTemplates(): JsonResponse
    {
        $templates = WeeklyRotaTemplate::active()
            ->with('creator')
            ->orderBy('is_default', 'desc')
            ->orderBy('usage_count', 'desc')
            ->orderBy('name')
            ->get()
            ->map(function ($template) {
                return [
                    'id' => $template->id,
                    'name' => $template->name,
                    'description' => $template->description,
                    'type' => $template->type,
                    'is_default' => $template->is_default,
                    'usage_count' => $template->usage_count,
                    'last_used_at' => $template->last_used_at?->format('Y-m-d H:i:s'),
                    'created_by' => $template->creator->full_name ?? 'Unknown',
                    'created_at' => $template->created_at->format('Y-m-d H:i:s'),
                    'total_assignments' => $template->getTotalAssignments(),
                    'unique_staff_count' => $template->getUniqueStaffCount(),
                    'shifts_count' => $template->getShiftsCount(),
                ];
            });

        return response()->json([
            'success' => true,
            'templates' => $templates,
        ]);
    }
}