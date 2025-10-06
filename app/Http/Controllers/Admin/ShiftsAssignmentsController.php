<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\StaffShift;
use App\Models\StaffShiftAssignment;
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
    public function update(Request $request, string $id): JsonResponse
    {
        $assignment = StaffShiftAssignment::findOrFail($id);

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
     * Remove a shift assignment.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $assignment = StaffShiftAssignment::findOrFail($id);
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
}