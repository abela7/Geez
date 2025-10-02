<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\StaffShift;
use App\Models\StaffShiftAssignment;
use App\Models\StaffTimeOffRequest;
use App\Models\StaffShiftSwap;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class StaffScheduleController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display the main scheduling interface.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', StaffShift::class);

        // Get current week or requested week
        $weekStart = $request->get('week') 
            ? Carbon::parse($request->get('week'))->startOfWeek()
            : Carbon::now()->startOfWeek();
        
        $weekEnd = $weekStart->copy()->endOfWeek();

        // Get all shifts for the week
        $assignments = StaffShiftAssignment::with(['staff', 'shift'])
            ->whereBetween('assigned_date', [$weekStart->format('Y-m-d'), $weekEnd->format('Y-m-d')])
            ->get()
            ->groupBy('assigned_date');

        // Get all available shifts
        $shifts = StaffShift::active()->get();

        // Get all active staff
        $staff = Staff::active()->with('staffType')->get();

        // Get coverage statistics
        $coverageStats = $this->getCoverageStats($weekStart, $weekEnd);

        // Get pending requests that need attention
        $pendingRequests = $this->getPendingRequests();

        return view('admin.staff.schedule.index', compact(
            'assignments',
            'shifts', 
            'staff',
            'weekStart',
            'weekEnd',
            'coverageStats',
            'pendingRequests'
        ));
    }

    /**
     * Assign staff to a shift.
     */
    public function assign(Request $request)
    {
        $this->authorize('assign', StaffShift::class);

        $validated = $request->validate([
            'shift_id' => 'required|exists:staff_shifts,id',
            'staff_id' => 'required|exists:staff,id',
            'date' => 'required|date|after_or_equal:today',
            'role_assigned' => 'nullable|string',
            'position_details' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Check if staff is already assigned for this date
        $existingAssignment = StaffShiftAssignment::where('staff_id', $validated['staff_id'])
            ->where('assigned_date', $validated['date'])
            ->first();

        if ($existingAssignment) {
            return response()->json([
                'success' => false,
                'message' => 'Staff member is already assigned for this date.'
            ], 422);
        }

        // Create the assignment
        $assignment = StaffShiftAssignment::create([
            'staff_shift_id' => $validated['shift_id'],
            'staff_id' => $validated['staff_id'],
            'assigned_date' => $validated['date'],
            'status' => 'scheduled',
            'notes' => $validated['notes'],
            'assigned_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Staff assigned successfully.',
            'assignment' => $assignment->load(['staff', 'shift'])
        ]);
    }

    /**
     * Get coverage statistics for a date range.
     */
    private function getCoverageStats(Carbon $startDate, Carbon $endDate): array
    {
        $stats = [];
        $current = $startDate->copy();

        while ($current->lte($endDate)) {
            $dateStr = $current->format('Y-m-d');
            
            // Get all shifts for this date
            $shifts = StaffShift::active()->get();
            $assignments = StaffShiftAssignment::where('assigned_date', $dateStr)
                ->whereIn('status', ['scheduled', 'confirmed', 'completed'])
                ->get()
                ->groupBy('staff_shift_id');

            $dayStats = [
                'date' => $dateStr,
                'total_shifts' => $shifts->count(),
                'covered_shifts' => 0,
                'understaffed_shifts' => 0,
                'total_staff_needed' => 0,
                'total_staff_assigned' => 0,
            ];

            foreach ($shifts as $shift) {
                $assignedCount = $assignments->get($shift->id, collect())->count();
                $dayStats['total_staff_needed'] += $shift->min_staff_required;
                $dayStats['total_staff_assigned'] += $assignedCount;

                if ($assignedCount >= $shift->min_staff_required) {
                    $dayStats['covered_shifts']++;
                }

                if ($assignedCount < $shift->min_staff_required) {
                    $dayStats['understaffed_shifts']++;
                }
            }

            $stats[$dateStr] = $dayStats;
            $current->addDay();
        }

        return $stats;
    }

    /**
     * Get pending requests that need manager attention.
     */
    private function getPendingRequests(): array
    {
        return [
            'time_off_requests' => StaffTimeOffRequest::pending()
                ->with('staff')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            
            'shift_swaps' => StaffShiftSwap::pending()
                ->with(['requestingStaff', 'targetStaff'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
        ];
    }
}
