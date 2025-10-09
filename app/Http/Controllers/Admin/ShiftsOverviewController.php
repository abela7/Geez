<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaffShift;
use App\Models\StaffShiftAssignment;
use App\Models\WeeklySchedule;
use App\Models\WeeklyScheduleAssignment;
use App\Models\ShiftType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShiftsOverviewController extends Controller
{
    public function index(Request $request): View
    {
        // Get week from request or default to current week
        $weekParam = $request->get('week');
        $weekStart = $weekParam ? Carbon::parse($weekParam)->startOfWeek() : Carbon::now()->startOfWeek();
        $weekEnd = (clone $weekStart)->endOfWeek();

        // Week navigation
        $weekNavigation = [
            'previous_week' => (clone $weekStart)->subWeek(),
            'next_week' => (clone $weekStart)->addWeek(),
            'is_current_week' => $weekStart->isCurrentWeek(),
        ];

        // Get or create weekly schedule for this week
        $weeklySchedule = WeeklySchedule::where('week_start_date', $weekStart->format('Y-m-d'))->first();
        
        if (!$weeklySchedule) {
            // Create a weekly schedule if it doesn't exist
            $firstStaff = \App\Models\Staff::first();
            $weeklySchedule = WeeklySchedule::create([
                'week_start_date' => $weekStart,
                'week_end_date' => $weekEnd,
                'year' => $weekStart->year,
                'week_number' => $weekStart->weekOfYear,
                'name' => null,
                'description' => 'Auto-created for overview',
                'template_id' => null,
                'is_template_applied' => false,
                'status' => 'draft',
                'total_shifts' => 0,
                'total_staff_assignments' => 0,
                'total_scheduled_hours' => 0,
                'estimated_labor_cost' => 0,
                'created_by' => $firstStaff ? $firstStaff->id : null,
            ]);
        }

        // Get assignments for this week using weekly schedule system
        $assignments = StaffShiftAssignment::whereBetween('assigned_date', [$weekStart->format('Y-m-d'), $weekEnd->format('Y-m-d')])
            ->where('status', '!=', 'cancelled')
            ->with(['staff.staffType', 'staff.profile', 'shift'])
            ->get();

        // Generate weekly schedule data
        $weeklyScheduleData = $this->generateWeeklySchedule($weekStart, $assignments);

        // Calculate summary statistics
        $shiftSummary = $this->calculateShiftSummary($weeklyScheduleData, $assignments);

        // Get current shifts (shifts happening right now)
        $currentShifts = $this->getCurrentShifts();

        // Identify coverage gaps
        $coverageGaps = $this->identifyCoverageGaps($weeklyScheduleData);

        return view('admin.shifts.overview.index', compact(
            'weekStart',
            'weekNavigation',
            'shiftSummary',
            'currentShifts',
            'coverageGaps',
            'weeklyScheduleData'
        ));
    }

    private function generateWeeklySchedule(Carbon $weekStart, $assignments): array
    {
        $schedule = [];
        
        // Define shift type colors
        $shiftTypeColors = [
            'Injera Room' => '#8B5CF6', // Purple
            'Waitress' => '#F59E0B',    // Amber
            'Main Chef' => '#EF4444',   // Red
            'Helper Chef' => '#F97316', // Orange
            'Kitchen Porter' => '#10B981', // Emerald
            'Management' => '#3B82F6',  // Blue
            'Bar' => '#EC4899',         // Pink
            'default' => '#6B7280'      // Gray
        ];

        for ($i = 0; $i < 7; $i++) {
            $date = (clone $weekStart)->addDays($i);
            $dayShifts = [];

            // Group assignments by shift for this day
            $dayAssignments = $assignments->filter(function ($assignment) use ($date) {
                return Carbon::parse($assignment->assigned_date)->format('Y-m-d') === $date->format('Y-m-d');
            });

            $shiftsByType = $dayAssignments->groupBy('staff_shift_id');

            foreach ($shiftsByType as $shiftId => $shiftAssignments) {
                $shift = $shiftAssignments->first()->shift;
                $assignedCount = $shiftAssignments->count();
                $requiredCount = $shift->min_staff_required ?? 1;

                // Determine coverage status
                $status = 'not_covered';
                if ($assignedCount >= $requiredCount) {
                    $status = 'fully_covered';
                } elseif ($assignedCount > 0) {
                    $status = 'partially_covered';
                }

                // Calculate duration (handle overnight shifts)
                $startTime = Carbon::parse($shift->start_time);
                $endTime = Carbon::parse($shift->end_time);
                
                // Calculate duration in minutes manually
                $startMinutes = $startTime->hour * 60 + $startTime->minute;
                $endMinutes = $endTime->hour * 60 + $endTime->minute;
                
                if ($endTime->lt($startTime)) {
                    // Overnight shift: add 24 hours (1440 minutes) to end time
                    $endMinutes += 1440;
                }
                
                $totalMinutes = $endMinutes - $startMinutes;
                
                // Subtract break time if applicable
                if (isset($shift->break_minutes) && $shift->break_minutes > 0) {
                    $totalMinutes -= $shift->break_minutes;
                } elseif (isset($shift->break_duration) && $shift->break_duration > 0) {
                    $totalMinutes -= $shift->break_duration;
                }
                
                // Ensure total minutes is not negative and convert to hours
                $totalMinutes = max(0, $totalMinutes);
                $duration = $totalMinutes / 60;

                // Get shift type color
                $color = $shiftTypeColors[$shift->name] ?? $shiftTypeColors['default'];

                $dayShifts[] = [
                    'id' => $shift->id,
                    'name' => $shift->name,
                    'start_time' => $shift->start_time,
                    'end_time' => $shift->end_time,
                    'duration_hours' => $duration,
                    'department' => $shift->department,
                    'color' => $color,
                    'status' => $status,
                    'assigned_staff_count' => $assignedCount,
                    'required_staff' => $requiredCount,
                    'assigned_staff' => $shiftAssignments->map(function ($assignment) {
                        return [
                            'id' => $assignment->staff->id,
                            'name' => $assignment->staff->full_name,
                            'role' => $assignment->staff->staffType->display_name ?? 'No Role',
                            'checked_in' => false, // Would come from attendance system
                        ];
                    })->toArray(),
                ];
            }

            $schedule[] = [
                'date' => $date,
                'day_short' => $date->format('D'),
                'is_today' => $date->isToday(),
                'is_weekend' => $date->isWeekend(),
                'shifts' => $dayShifts,
                'total_shifts' => count($dayShifts),
                'total_staff' => collect($dayShifts)->sum('assigned_staff_count'),
            ];
        }

        return $schedule;
    }

    private function calculateShiftSummary(array $weeklyScheduleData, $assignments): array
    {
        $totalShifts = 0;
        $totalStaffScheduled = 0;
        $totalHours = 0;
        $coverageGaps = 0;

        foreach ($weeklyScheduleData as $day) {
            $totalShifts += $day['total_shifts'];
            $totalStaffScheduled += $day['total_staff'];

            foreach ($day['shifts'] as $shift) {
                $totalHours += $shift['duration_hours'];
                
                if ($shift['status'] === 'not_covered') {
                    $coverageGaps += $shift['required_staff'];
                } elseif ($shift['status'] === 'partially_covered') {
                    $coverageGaps += $shift['required_staff'] - $shift['assigned_staff_count'];
                }
            }
        }

        return [
            'total_shifts' => $totalShifts,
            'total_staff_scheduled' => $totalStaffScheduled,
            'total_hours' => $totalHours,
            'coverage_gaps' => $coverageGaps,
            'average_hours_per_staff' => $totalStaffScheduled > 0 ? round($totalHours / $totalStaffScheduled, 1) : 0,
            'coverage_percentage' => $totalStaffScheduled > 0 ? round(($totalStaffScheduled / ($totalStaffScheduled + $coverageGaps)) * 100, 1) : 0,
        ];
    }

    private function getCurrentShifts(): array
    {
        $now = Carbon::now();
        $currentShifts = [];

        // Get shifts that are currently active
        $activeAssignments = StaffShiftAssignment::where('status', '!=', 'cancelled')
            ->whereDate('assigned_date', $now->format('Y-m-d'))
            ->with(['shift', 'staff.staffType'])
            ->get()
            ->groupBy('staff_shift_id');

        foreach ($activeAssignments as $shiftId => $assignments) {
            $shift = $assignments->first()->shift;
            $startTime = Carbon::parse($shift->start_time);
            $endTime = Carbon::parse($shift->end_time);

            // Check if shift is currently active
            if ($now->between($startTime, $endTime)) {
                $progressPercentage = $this->calculateShiftProgress($startTime, $endTime, $now);
                $timeRemaining = $endTime->diffForHumans($now, true);

                $currentShifts[] = [
                    'id' => $shift->id,
                    'name' => $shift->name,
                    'time_remaining' => $timeRemaining,
                    'progress_percentage' => $progressPercentage,
                    'assigned_staff' => $assignments->map(function ($assignment) {
                        return [
                            'id' => $assignment->staff->id,
                            'name' => $assignment->staff->full_name,
                            'checked_in' => false, // Would come from attendance system
                        ];
                    })->toArray(),
                ];
            }
        }

        return $currentShifts;
    }

    private function getUpcomingShifts(Carbon $weekStart): array
    {
        $upcomingShifts = [];
        $now = Carbon::now();

        // Get shifts for the next 3 days
        for ($i = 0; $i < 3; $i++) {
            $date = (clone $now)->addDays($i);
            
            $assignments = StaffShiftAssignment::whereDate('assigned_date', $date->format('Y-m-d'))
                ->where('status', '!=', 'cancelled')
                ->with(['shift', 'staff.staffType'])
                ->get()
                ->groupBy('staff_shift_id');

            foreach ($assignments as $shiftId => $shiftAssignments) {
                $shift = $shiftAssignments->first()->shift;
                $startTime = Carbon::parse($shift->start_time);
                $hoursUntil = $now->diffInHours($startTime, false);

                if ($hoursUntil > 0 && $hoursUntil <= 72) { // Next 3 days
                    $upcomingShifts[] = [
                        'id' => $shift->id,
                        'name' => $shift->name,
                        'date' => $date,
                        'start_time' => $shift->start_time,
                        'end_time' => $shift->end_time,
                        'department' => $shift->department,
                        'status' => 'scheduled',
                        'hours_until' => $hoursUntil,
                        'assigned_staff' => $shiftAssignments->map(function ($assignment) {
                            return [
                                'id' => $assignment->staff->id,
                                'name' => $assignment->staff->full_name,
                            ];
                        })->toArray(),
                    ];
                }
            }
        }

        // Sort by hours until
        usort($upcomingShifts, function ($a, $b) {
            return $a['hours_until'] <=> $b['hours_until'];
        });

        return array_slice($upcomingShifts, 0, 5); // Return top 5 upcoming shifts
    }

    private function identifyCoverageGaps(array $weeklySchedule): array
    {
        $gaps = [];

        foreach ($weeklySchedule as $day) {
            foreach ($day['shifts'] as $shift) {
                if ($shift['status'] !== 'fully_covered') {
                    $gapCount = $shift['required_staff'] - $shift['assigned_staff_count'];
                    
                    if ($gapCount > 0) {
                        $priority = 'high';
                        if ($gapCount <= 1) {
                            $priority = 'medium';
                        }
                        if ($gapCount <= 0.5) {
                            $priority = 'low';
                        }

                        $gaps[] = [
                            'shift_name' => $shift['name'],
                            'date' => $day['date'],
                            'time' => $shift['start_time'] . ' - ' . $shift['end_time'],
                            'gap_count' => $gapCount,
                            'priority' => $priority,
                            'department' => $shift['department'],
                        ];
                    }
                }
            }
        }

        // Sort by priority and date
        usort($gaps, function ($a, $b) {
            $priorityOrder = ['high' => 3, 'medium' => 2, 'low' => 1];
            $priorityDiff = $priorityOrder[$b['priority']] - $priorityOrder[$a['priority']];
            
            if ($priorityDiff !== 0) {
                return $priorityDiff;
            }
            
            return $a['date']->timestamp - $b['date']->timestamp;
        });

        return array_slice($gaps, 0, 10); // Return top 10 gaps
    }

    private function calculateShiftProgress(Carbon $startTime, Carbon $endTime, Carbon $now): float
    {
        $totalDuration = $endTime->diffInMinutes($startTime);
        
        if ($totalDuration === 0) {
            return 0;
        }
        
        $elapsed = $now->diffInMinutes($startTime);
        $progress = ($elapsed / $totalDuration) * 100;
        return (int) min(max(round($progress), 0), 100);
    }
}
