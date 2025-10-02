<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\StaffAttendance;
use App\Models\StaffType;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;

class StaffAttendanceController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request): View
    {
        // Get filter parameters
        $date = $request->get('date', now()->format('Y-m-d'));
        $staffTypeId = $request->get('staff_type_id');
        $status = $request->get('status');
        $search = $request->get('search');
        
        // Get today's attendance overview
        $todayStats = $this->getTodayStats($date);
        
        // Get attendance records with filters
        $attendanceRecords = $this->getAttendanceRecords($request);
        
        // Get currently clocked in staff
        $currentlyClocked = $this->getCurrentlyClockedIn();
        
        // Get recent attendance activity
        $recentActivity = $this->getRecentActivity();
        
        // Get staff types for filter
        $staffTypes = StaffType::active()->get();
        
        // Get all staff for dropdown
        $allStaff = Staff::with('staffType')
            ->where('status', 'active')
            ->orderBy('first_name')
            ->get();
        
        return view('admin.staff.attendance', compact(
            'todayStats',
            'attendanceRecords',
            'currentlyClocked',
            'recentActivity',
            'staffTypes',
            'allStaff',
            'date',
            'staffTypeId',
            'status',
            'search'
        ));
    }
    
    /**
     * Get today's attendance statistics.
     * 
     * TODO: FIX ATTENDANCE LOGIC - Currently assumes all staff are scheduled
     * This is incorrect! We need a staff_schedules table to properly calculate:
     * - Total scheduled staff (not all active staff)
     * - Absent staff (only those who were scheduled but didn't show)
     * - Attendance rate (present/scheduled, not present/total)
     * 
     * Current logic: totalStaff = all active staff (WRONG)
     * Correct logic: totalStaff = staff scheduled for this date (NEEDS staff_schedules table)
     */
    private function getTodayStats(string $date): array
    {
        try {
            // TODO: Replace with scheduled staff count when staff_schedules table exists
            $totalStaff = Staff::where('status', 'active')->count();
            
            $attendanceToday = StaffAttendance::whereDate('clock_in', $date)->get();
            
            $presentCount = $attendanceToday->where('status', 'present')->count();
            // TODO: This is wrong - counts all non-attending staff as absent, even if not scheduled
            $absentCount = $totalStaff - $attendanceToday->count();
            $lateCount = $attendanceToday->where('status', 'late')->count();
            $overtimeCount = $attendanceToday->where('status', 'overtime')->count();
            
            // TODO: Attendance rate should be present/scheduled, not present/total
            $attendanceRate = $totalStaff > 0 ? round(($presentCount / $totalStaff) * 100, 1) : 0;
            
            return [
                'total_staff' => $totalStaff,
                'present_count' => $presentCount,
                'absent_count' => $absentCount,
                'late_count' => $lateCount,
                'overtime_count' => $overtimeCount,
                'attendance_rate' => $attendanceRate,
                'total_hours' => $attendanceToday->sum('hours_worked') ?: 0,
            ];
        } catch (\Exception $e) {
            return [
                'total_staff' => 0,
                'present_count' => 0,
                'absent_count' => 0,
                'late_count' => 0,
                'overtime_count' => 0,
                'attendance_rate' => 0,
                'total_hours' => 0,
            ];
        }
    }
    
    /**
     * Get attendance records with filters and pagination.
     */
    private function getAttendanceRecords(Request $request)
    {
        try {
            $query = StaffAttendance::with(['staff.staffType'])
                ->orderBy('clock_in', 'desc');
            
            // Apply filters
            if ($request->filled('date')) {
                $query->whereDate('clock_in', $request->get('date'));
            }
            
            if ($request->filled('staff_type_id')) {
                $query->whereHas('staff', function ($q) use ($request) {
                    $q->where('staff_type_id', $request->get('staff_type_id'));
                });
            }
            
            if ($request->filled('status')) {
                $query->where('status', $request->get('status'));
            }
            
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->whereHas('staff', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%");
                });
            }
            
            return $query->paginate(20)->withQueryString();
        } catch (\Exception $e) {
            // Return empty paginated result when table doesn't exist
            return new LengthAwarePaginator(
                collect(), // Empty collection
                0, // Total items
                20, // Per page
                $request->get('page', 1), // Current page
                [
                    'path' => $request->url(),
                    'pageName' => 'page',
                ]
            );
        }
    }
    
    /**
     * Get staff currently clocked in.
     */
    private function getCurrentlyClockedIn()
    {
        try {
            return StaffAttendance::with(['staff.staffType'])
                ->whereNull('clock_out')
                ->whereDate('clock_in', now())
                ->orderBy('clock_in', 'desc')
                ->limit(10)
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }
    
    /**
     * Get recent attendance activity.
     */
    private function getRecentActivity()
    {
        try {
            return StaffAttendance::with(['staff.staffType'])
                ->where('clock_in', '>=', now()->subDays(7))
                ->orderBy('clock_in', 'desc')
                ->limit(15)
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Store a new attendance record (admin creating attendance for staff).
     */
    public function store(Request $request)
    {
        $this->authorize('create', StaffAttendance::class);

        $validated = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'clock_in' => 'required|date',
            'clock_out' => 'nullable|date|after:clock_in',
            'status' => 'required|in:present,absent,late,early_leave,overtime',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            // Check for existing attendance on the same day
            $existingAttendance = StaffAttendance::where('staff_id', $validated['staff_id'])
                ->whereDate('clock_in', Carbon::parse($validated['clock_in'])->format('Y-m-d'))
                ->first();

            if ($existingAttendance) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', __('admin.staff.attendance.already_clocked_in'));
            }

            $attendance = StaffAttendance::create([
                ...$validated,
                'created_by' => auth()->id(),
            ]);

            return redirect()->route('admin.staff.attendance.index')
                ->with('success', __('admin.staff.attendance.created_successfully'));
        } catch (\Exception $e) {
            \Log::error('Failed to create attendance: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', __('admin.staff.attendance.create_failed'));
        }
    }

    /**
     * Update an attendance record (admin override).
     */
    public function update(Request $request, StaffAttendance $staffAttendance)
    {
        $this->authorize('update', $staffAttendance);

        $validated = $request->validate([
            'clock_in' => 'required|date',
            'clock_out' => 'nullable|date|after:clock_in',
            'status' => 'required|in:present,absent,late,early_leave,overtime',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $staffAttendance->update([
                ...$validated,
                'updated_by' => auth()->id(),
            ]);

            return redirect()->route('admin.staff.attendance.index')
                ->with('success', __('admin.staff.attendance.updated_successfully'));
        } catch (\Exception $e) {
            \Log::error('Failed to update attendance: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', __('admin.staff.attendance.update_failed'));
        }
    }

    /**
     * Delete an attendance record.
     */
    public function destroy(StaffAttendance $staffAttendance)
    {
        $this->authorize('delete', $staffAttendance);

        try {
            $staffAttendance->delete();

            return redirect()->route('admin.staff.attendance.index')
                ->with('success', __('admin.staff.attendance.deleted_successfully'));
        } catch (\Exception $e) {
            \Log::error('Failed to delete attendance: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', __('admin.staff.attendance.delete_failed'));
        }
    }
}
