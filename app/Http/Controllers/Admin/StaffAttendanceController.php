<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\StaffAttendance;
use App\Models\StaffAttendanceInterval;
use App\Models\StaffType;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

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

        // Get today's attendance overview with filters applied
        $todayStats = $this->getTodayStats($date, $staffTypeId, $status, $search);

        // Get attendance records with filters
        $attendanceRecords = $this->getAttendanceRecords($request);

        // Get currently clocked in staff (active sessions) - apply filters
        $currentlyClocked = $this->getCurrentlyActive($date, $staffTypeId, $search);

        // Get staff on break - apply filters
        $staffOnBreak = $this->getStaffOnBreak($date, $staffTypeId, $search);

        // Get attendance needing review - apply filters
        $needsReview = $this->getAttendanceNeedingReview($date, $staffTypeId, $search);

        // Get recent attendance activity
        $recentActivity = $this->getRecentActivity();

        // Get staff types for filter
        $staffTypes = StaffType::active()->get();

        // Get all staff for dropdown
        $allStaff = Staff::with('staffType')
            ->where('status', 'active')
            ->orderBy('first_name')
            ->get();

        // Get active shift templates for dropdown
        $activeShifts = \App\Models\StaffShift::where('is_active', true)
            ->where('is_template', true)
            ->orderBy('name')
            ->get();

        return view('admin.staff.attendance', compact(
            'todayStats',
            'attendanceRecords',
            'currentlyClocked',
            'staffOnBreak',
            'needsReview',
            'recentActivity',
            'staffTypes',
            'allStaff',
            'activeShifts',
            'date',
            'staffTypeId',
            'status',
            'search'
        ));
    }

    /**
     * Get today's attendance statistics with filters applied.
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
    private function getTodayStats(string $date, ?string $staffTypeId = null, ?string $status = null, ?string $search = null): array
    {
        try {
            // Build base query for attendance records
            $attendanceQuery = StaffAttendance::whereDate('clock_in', $date)
                ->with('staff');

            // Apply staff type filter
            if ($staffTypeId) {
                $attendanceQuery->whereHas('staff', function ($q) use ($staffTypeId) {
                    $q->where('staff_type_id', $staffTypeId);
                });
            }

            // Apply status filter
            if ($status) {
                $attendanceQuery->where('status', $status);
            }

            // Apply search filter
            if ($search) {
                $attendanceQuery->whereHas('staff', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            }

            $attendanceToday = $attendanceQuery->get();

            // Get total staff count with same filters applied
            $totalStaffQuery = Staff::where('status', 'active');
            
            if ($staffTypeId) {
                $totalStaffQuery->where('staff_type_id', $staffTypeId);
            }
            
            if ($search) {
                $totalStaffQuery->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            }
            
            $totalStaff = $totalStaffQuery->count();

            // Calculate stats based on current_state for real-time data
            $currentlyWorking = $attendanceToday->where('current_state', 'clocked_in')->count();
            $onBreak = $attendanceToday->where('current_state', 'on_break')->count();
            $completedToday = $attendanceToday->whereIn('current_state', ['clocked_out', 'auto_closed'])->count();
            $needsReview = $attendanceToday->where('review_needed', true)->count();

            // Legacy status-based stats (keep for now)
            $presentCount = $attendanceToday->where('status', 'present')->count();
            $absentCount = $totalStaff - $attendanceToday->count(); // TODO: Fix when schedules exist
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

                // New keys for view
                'currently_working' => $currentlyWorking,
                'on_break' => $onBreak,
                'completed_today' => $completedToday,
                'needs_review' => $needsReview,
            ];
        } catch (\Exception $e) {
            // Return defaults on error
            return [
                'total_staff' => 0,
                'present_count' => 0,
                'absent_count' => 0,
                'late_count' => 0,
                'overtime_count' => 0,
                'attendance_rate' => 0,
                'total_hours' => 0,
                'currently_working' => 0,
                'on_break' => 0,
                'completed_today' => 0,
                'needs_review' => 0,
            ];
        }
    }

    /**
     * Get attendance records with filters and pagination.
     */
    private function getAttendanceRecords(Request $request)
    {
        try {
            $query = StaffAttendance::with(['staff.staffType', 'shiftAssignment', 'intervals'])
                ->whereHas('staff') // Only get records that have valid staff
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
            \Log::error('Failed to get attendance records: ' . $e->getMessage());
            
            // Return empty paginated result when there's an error
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
     * Get currently active staff (clocked in or on break) with filters.
     */
    private function getCurrentlyActive(?string $date = null, ?string $staffTypeId = null, ?string $search = null)
    {
        try {
            $query = StaffAttendance::with(['staff.staffType', 'intervals' => function ($query) {
                $query->active()->latest();
            }])
                ->whereHas('staff') // Only get records that have valid staff
                ->active(); // Using the new scope

            // Apply date filter
            if ($date) {
                $query->whereDate('clock_in', $date);
            } else {
                $query->whereDate('clock_in', now());
            }

            // Apply staff type filter
            if ($staffTypeId) {
                $query->whereHas('staff', function ($q) use ($staffTypeId) {
                    $q->where('staff_type_id', $staffTypeId);
                });
            }

            // Apply search filter
            if ($search) {
                $query->whereHas('staff', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            }

            return $query->orderBy('clock_in', 'desc')
                ->limit(15)
                ->get();
        } catch (\Exception $e) {
            \Log::error('Failed to get currently active staff: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get staff currently on break with filters.
     */
    private function getStaffOnBreak(?string $date = null, ?string $staffTypeId = null, ?string $search = null)
    {
        try {
            $query = StaffAttendance::with(['staff.staffType'])
                ->whereHas('staff') // Only get records that have valid staff
                ->where('current_state', 'on_break');

            // Apply date filter
            if ($date) {
                $query->whereDate('clock_in', $date);
            } else {
                $query->whereDate('clock_in', now());
            }

            // Apply staff type filter
            if ($staffTypeId) {
                $query->whereHas('staff', function ($q) use ($staffTypeId) {
                    $q->where('staff_type_id', $staffTypeId);
                });
            }

            // Apply search filter
            if ($search) {
                $query->whereHas('staff', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            }

            return $query->orderBy('current_break_start', 'desc')
                ->limit(10)
                ->get();
        } catch (\Exception $e) {
            \Log::error('Failed to get staff on break: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get attendance records needing review with filters.
     */
    private function getAttendanceNeedingReview(?string $date = null, ?string $staffTypeId = null, ?string $search = null)
    {
        try {
            $query = StaffAttendance::with(['staff.staffType'])
                ->whereHas('staff') // Only get records that have valid staff
                ->needsReview(); // Using the new scope

            // Apply date filter
            if ($date) {
                $query->whereDate('clock_in', $date);
            }

            // Apply staff type filter
            if ($staffTypeId) {
                $query->whereHas('staff', function ($q) use ($staffTypeId) {
                    $q->where('staff_type_id', $staffTypeId);
                });
            }

            // Apply search filter
            if ($search) {
                $query->whereHas('staff', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            }

            return $query->orderBy('clock_in', 'desc')
                ->limit(10)
                ->get();
        } catch (\Exception $e) {
            \Log::error('Failed to get attendance needing review: ' . $e->getMessage());
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
                ->whereHas('staff') // Only get records that have valid staff
                ->where('clock_in', '>=', now()->subDays(7))
                ->orderBy('clock_in', 'desc')
                ->limit(15)
                ->get();
        } catch (\Exception $e) {
            \Log::error('Failed to get recent activity: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Show attendance details.
     */
    public function show(StaffAttendance $staffAttendance): View
    {
        $this->authorize('view', $staffAttendance);

        // Load relationships needed for the detail view
        $staffAttendance->load([
            'staff.staffType',
            'shiftAssignment.shift',
            'intervals' => function ($query) {
                $query->orderBy('start_time', 'asc');
            }
        ]);

        // Calculate metrics for the view
        $metrics = $this->calculateAttendanceMetrics($staffAttendance);

        return view('admin.staff.attendance.show', compact('staffAttendance', 'metrics'));
    }

    /**
     * Calculate attendance metrics for display.
     */
    private function calculateAttendanceMetrics(StaffAttendance $staffAttendance): array
    {
        $totalMinutes = $staffAttendance->clock_in->diffInMinutes($staffAttendance->clock_out);
        $totalHours = $totalMinutes / 60;
        $breakMinutes = $staffAttendance->total_break_minutes ?? 0;
        $netMinutes = $totalMinutes - $breakMinutes;
        $netHours = $netMinutes / 60;

        // Calculate efficiency score (net hours / total hours * 100)
        $efficiencyScore = $totalHours > 0 ? round(($netHours / $totalHours) * 100, 1) : 0;

        // Calculate punctuality score based on scheduled vs actual start time
        $punctualityScore = 100; // Default to perfect score
        if ($staffAttendance->shiftAssignment && $staffAttendance->shiftAssignment->shift) {
            $assignment = $staffAttendance->shiftAssignment;
            $startTime = $assignment->shift->start_time;
            
            // Handle different time formats
            if ($startTime instanceof \Carbon\Carbon) {
                $startTime = $startTime->format('H:i:s');
            }
            
            $assignedDate = $assignment->assigned_date instanceof \Carbon\Carbon 
                ? $assignment->assigned_date->format('Y-m-d')
                : $assignment->assigned_date;
                
            $scheduledStart = Carbon::parse($assignedDate . ' ' . $startTime);
            $actualStart = $staffAttendance->clock_in;
            
            if ($actualStart->gt($scheduledStart)) {
                $lateMinutes = $actualStart->diffInMinutes($scheduledStart);
                // Deduct points for being late (max 50 points deduction)
                $punctualityScore = max(50, 100 - ($lateMinutes * 2));
            }
        }

        // Determine compliance status
        $complianceStatus = 'on_time';
        if ($staffAttendance->shiftAssignment && $staffAttendance->shiftAssignment->shift) {
            $assignment = $staffAttendance->shiftAssignment;
            $startTime = $assignment->shift->start_time;
            $endTime = $assignment->shift->end_time;
            
            // Handle different time formats
            if ($startTime instanceof \Carbon\Carbon) {
                $startTime = $startTime->format('H:i:s');
            }
            if ($endTime instanceof \Carbon\Carbon) {
                $endTime = $endTime->format('H:i:s');
            }
            
            $assignedDate = $assignment->assigned_date instanceof \Carbon\Carbon 
                ? $assignment->assigned_date->format('Y-m-d')
                : $assignment->assigned_date;
                
            $scheduledStart = Carbon::parse($assignedDate . ' ' . $startTime);
            $scheduledEnd = Carbon::parse($assignedDate . ' ' . $endTime);
            
            // Handle overnight shifts
            if ($scheduledEnd->format('H:i') < $scheduledStart->format('H:i')) {
                $scheduledEnd->addDay();
            }
            
            $actualStart = $staffAttendance->clock_in;
            $actualEnd = $staffAttendance->clock_out;

            if ($actualStart->gt($scheduledStart->addMinutes(15))) {
                $complianceStatus = 'late_arrival';
            } elseif ($actualEnd && $actualEnd->lt($scheduledEnd->subMinutes(15))) {
                $complianceStatus = 'early_departure';
            }
        } else {
            $complianceStatus = 'unscheduled';
        }

        return [
            'total_hours' => $totalHours,
            'net_hours' => $netHours,
            'break_hours' => $breakMinutes / 60,
            'efficiency_score' => $efficiencyScore,
            'punctuality_score' => $punctualityScore,
            'compliance_status' => $complianceStatus,
        ];
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
                    ->with('error', __('staff.attendance.already_clocked_in'));
            }

            // Determine the initial state based on whether clock_out is provided
            $initialState = $validated['clock_out'] ? 'clocked_out' : 'clocked_in';
            
            $attendance = StaffAttendance::create([
                ...$validated,
                'current_state' => $initialState,
                'created_by' => auth()->id(),
            ]);

            return redirect()->route('admin.staff.attendance.index')
                ->with('success', __('staff.attendance.created_successfully'));
        } catch (\Exception $e) {
            \Log::error('Failed to create attendance: '.$e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', __('staff.attendance.create_failed'));
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
            // Determine the state based on whether clock_out is provided
            $newState = $validated['clock_out'] ? 'clocked_out' : 'clocked_in';
            
            $staffAttendance->update([
                ...$validated,
                'current_state' => $newState,
                'updated_by' => auth()->id(),
            ]);

            return redirect()->route('admin.staff.attendance.index')
                ->with('success', __('staff.attendance.updated_successfully'));
        } catch (\Exception $e) {
            \Log::error('Failed to update attendance: '.$e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', __('staff.attendance.update_failed'));
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
                ->with('success', __('staff.attendance.deleted_successfully'));
        } catch (\Exception $e) {
            \Log::error('Failed to delete attendance: '.$e->getMessage());

            return redirect()->back()
                ->with('error', __('staff.attendance.delete_failed'));
        }
    }

    // ==================== STATE MACHINE METHODS ====================

    /**
     * Start a break for a staff member.
     */
    public function startBreak(Request $request, StaffAttendance $staffAttendance): JsonResponse
    {
        $this->authorize('update', $staffAttendance);

        $validated = $request->validate([
            'break_category' => 'required|in:scheduled,emergency,restroom,personal,unauthorized',
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            // Check if already on break
            if ($staffAttendance->is_currently_on_break) {
                return response()->json([
                    'success' => false,
                    'message' => __('staff.attendance.already_on_break')
                ], 400);
            }

            // Start break
            $interval = $staffAttendance->startBreak(
                $validated['break_category'],
                $validated['reason'] ?? null
            );

            return response()->json([
                'success' => true,
                'message' => __('staff.attendance.break_started'),
                'data' => [
                    'attendance' => $staffAttendance->fresh(['staff', 'intervals']),
                    'interval' => $interval,
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to start break: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('staff.attendance.break_start_failed')
            ], 500);
        }
    }

    /**
     * Resume work after break.
     */
    public function resumeWork(StaffAttendance $staffAttendance): JsonResponse
    {
        $this->authorize('update', $staffAttendance);

        try {
            // Check if on break
            if (!$staffAttendance->is_currently_on_break) {
                return response()->json([
                    'success' => false,
                    'message' => __('staff.attendance.not_on_break')
                ], 400);
            }

            // Resume work
            $interval = $staffAttendance->resumeWork();

            return response()->json([
                'success' => true,
                'message' => __('staff.attendance.work_resumed'),
                'data' => [
                    'attendance' => $staffAttendance->fresh(['staff', 'intervals']),
                    'interval' => $interval,
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to resume work: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('staff.attendance.resume_work_failed')
            ], 500);
        }
    }

    /**
     * Clock out a staff member.
     */
    public function clockOut(StaffAttendance $staffAttendance): JsonResponse
    {
        $this->authorize('update', $staffAttendance);

        try {
            // Check if already clocked out
            if ($staffAttendance->current_state === 'clocked_out') {
                return response()->json([
                    'success' => false,
                    'message' => __('staff.attendance.already_clocked_out')
                ], 400);
            }

            // Clock out
            $staffAttendance->clockOut();

            return response()->json([
                'success' => true,
                'message' => __('staff.attendance.clocked_out_successfully'),
                'data' => [
                    'attendance' => $staffAttendance->fresh(['staff', 'intervals']),
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to clock out: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('staff.attendance.clock_out_failed')
            ], 500);
        }
    }

    /**
     * Auto-close attendance session.
     */
    public function autoClose(Request $request, StaffAttendance $staffAttendance): JsonResponse
    {
        $this->authorize('update', $staffAttendance);

        $validated = $request->validate([
            'reason' => 'required|string|max:500',
            'close_time' => 'nullable|date',
        ]);

        try {
            $closeTime = $validated['close_time'] ? Carbon::parse($validated['close_time']) : now();
            
            $staffAttendance->autoClose($closeTime, $validated['reason']);

            return response()->json([
                'success' => true,
                'message' => __('staff.attendance.auto_closed_successfully'),
                'data' => [
                    'attendance' => $staffAttendance->fresh(['staff', 'intervals']),
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to auto-close: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('staff.attendance.auto_close_failed')
            ], 500);
        }
    }

    // ==================== INTERVAL MANAGEMENT ====================

    /**
     * Get intervals for an attendance record.
     */
    public function getIntervals(StaffAttendance $staffAttendance): JsonResponse
    {
        $this->authorize('view', $staffAttendance);

        try {
            $intervals = $staffAttendance->intervals()
                ->with(['approver', 'creator'])
                ->orderBy('start_time')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'intervals' => $intervals,
                    'summary' => [
                        'total_work_minutes' => $intervals->where('interval_type', 'work')->sum('duration_minutes'),
                        'total_break_minutes' => $intervals->whereIn('interval_type', ['break', 'emergency', 'unauthorized'])->sum('duration_minutes'),
                        'break_count' => $intervals->whereIn('interval_type', ['break', 'emergency', 'unauthorized'])->count(),
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to get intervals: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('staff.attendance.intervals_fetch_failed')
            ], 500);
        }
    }

    /**
     * Approve a break interval.
     */
    public function approveInterval(Request $request, StaffAttendanceInterval $interval): JsonResponse
    {
        $validated = $request->validate([
            'approval_notes' => 'nullable|string|max:1000',
        ]);

        try {
            $interval->update([
                'is_approved' => true,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'approval_notes' => $validated['approval_notes'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => __('staff.attendance.interval_approved'),
                'data' => [
                    'interval' => $interval->fresh(['approver']),
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to approve interval: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('staff.attendance.interval_approval_failed')
            ], 500);
        }
    }

    // ==================== REAL-TIME API METHODS ====================

    /**
     * Get real-time attendance dashboard data.
     */
    public function getDashboardData(Request $request): JsonResponse
    {
        try {
            $date = $request->get('date', now()->format('Y-m-d'));
            $staffTypeId = $request->get('staff_type_id');
            $status = $request->get('status');
            $search = $request->get('search');

            return response()->json([
                'success' => true,
                'data' => [
                    'stats' => $this->getTodayStats($date, $staffTypeId, $status, $search),
                    'currently_active' => $this->getCurrentlyActive($date, $staffTypeId, $search),
                    'staff_on_break' => $this->getStaffOnBreak($date, $staffTypeId, $search),
                    'needs_review' => $this->getAttendanceNeedingReview($date, $staffTypeId, $search),
                    'timestamp' => now()->toISOString(),
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to get dashboard data: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('staff.attendance.dashboard_data_failed')
            ], 500);
        }
    }

    /**
     * Get active session details for a staff member.
     */
    public function getActiveSession(Staff $staff): JsonResponse
    {
        try {
            $attendance = StaffAttendance::with(['intervals' => function ($query) {
                $query->orderBy('start_time');
            }])
                ->where('staff_id', $staff->id)
                ->active()
                ->whereDate('clock_in', now())
                ->first();

            if (!$attendance) {
                return response()->json([
                    'success' => false,
                    'message' => __('staff.attendance.no_active_session')
                ], 404);
            }

            // Calculate real-time duration
            $currentInterval = $attendance->getCurrentInterval();
            $totalMinutes = $attendance->clock_in->diffInMinutes(now());
            $workMinutes = $totalMinutes - $attendance->total_break_minutes;

            return response()->json([
                'success' => true,
                'data' => [
                    'attendance' => $attendance,
                    'current_interval' => $currentInterval,
                    'real_time' => [
                        'total_minutes' => $totalMinutes,
                        'work_minutes' => $workMinutes,
                        'break_minutes' => $attendance->total_break_minutes,
                        'current_interval_minutes' => $currentInterval ? $currentInterval->start_time->diffInMinutes(now()) : 0,
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to get active session: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('staff.attendance.active_session_failed')
            ], 500);
        }
    }
}
