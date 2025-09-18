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
use Carbon\Carbon;

class StaffAttendanceController extends Controller
{
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
     */
    private function getTodayStats(string $date): array
    {
        try {
            $totalStaff = Staff::where('status', 'active')->count();
            
            $attendanceToday = StaffAttendance::whereDate('clock_in', $date)->get();
            
            $presentCount = $attendanceToday->where('status', 'present')->count();
            $absentCount = $totalStaff - $attendanceToday->count();
            $lateCount = $attendanceToday->where('status', 'late')->count();
            $overtimeCount = $attendanceToday->where('status', 'overtime')->count();
            
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
}
