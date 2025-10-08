<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\WeeklySchedule;
use App\Models\WeeklyRotaTemplate;
use App\Models\StaffShiftAssignment;
use App\Models\WeeklyScheduleAssignment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class WeeklyScheduleService
{
    /**
     * Get or create a weekly schedule for a specific date.
     */
    public function getOrCreateScheduleForDate(Carbon $date, ?string $createdBy = null): WeeklySchedule
    {
        return WeeklySchedule::getOrCreateForDate($date, $createdBy);
    }

    /**
     * Get weekly schedules within a date range.
     */
    public function getSchedulesInRange(Carbon $startDate, Carbon $endDate): Collection
    {
        return WeeklySchedule::inDateRange($startDate, $endDate)
            ->with(['template', 'creator', 'assignments.staffShiftAssignment.staff'])
            ->orderBy('week_start_date')
            ->get();
    }

    /**
     * Apply a template to a weekly schedule.
     */
    public function applyTemplateToSchedule(WeeklySchedule $schedule, WeeklyRotaTemplate $template): void
    {
        DB::transaction(function () use ($schedule, $template) {
            $schedule->applyTemplate($template);
        });
    }

    /**
     * Create assignments for a weekly schedule without a template.
     */
    public function createManualAssignment(
        WeeklySchedule $schedule,
        string $staffId,
        string $shiftId,
        int $dayOfWeek,
        ?string $assignedBy = null
    ): WeeklyScheduleAssignment {
        return DB::transaction(function () use ($schedule, $staffId, $shiftId, $dayOfWeek, $assignedBy) {
            // Calculate the actual date
            $assignmentDate = $schedule->week_start_date->copy()->addDays(
                $dayOfWeek === 0 ? 6 : $dayOfWeek - 1
            );

            // Create the staff shift assignment
            $staffShiftAssignment = StaffShiftAssignment::create([
                'staff_shift_id' => $shiftId,
                'staff_id' => $staffId,
                'assigned_date' => $assignmentDate,
                'status' => 'scheduled',
                'assigned_by' => $assignedBy ?? $schedule->created_by,
            ]);

            // Create the weekly schedule assignment link
            $weeklyAssignment = WeeklyScheduleAssignment::create([
                'weekly_schedule_id' => $schedule->id,
                'staff_shift_assignment_id' => $staffShiftAssignment->id,
                'staff_id' => $staffId,
                'staff_shift_id' => $shiftId,
                'assigned_date' => $assignmentDate,
                'day_of_week' => $dayOfWeek,
                'assignment_status' => 'scheduled',
            ]);

            // Recalculate schedule statistics
            $schedule->recalculateStatistics();

            return $weeklyAssignment;
        });
    }

    /**
     * Remove an assignment from a weekly schedule.
     */
    public function removeAssignment(WeeklyScheduleAssignment $assignment): void
    {
        DB::transaction(function () use ($assignment) {
            $schedule = $assignment->weeklySchedule;
            
            // Delete the staff shift assignment
            $assignment->staffShiftAssignment->delete();
            
            // Delete the weekly schedule assignment link
            $assignment->delete();
            
            // Recalculate schedule statistics
            $schedule->recalculateStatistics();
        });
    }

    /**
     * Publish a weekly schedule.
     */
    public function publishSchedule(WeeklySchedule $schedule, ?string $publishedBy = null): void
    {
        if (!$schedule->canBeEdited()) {
            throw new \Exception(__('shifts.weekly_schedule.cannot_edit_archived'));
        }

        $schedule->publish($publishedBy);
        
        // TODO: Send notifications to staff members
        // $this->notifyStaffOfSchedule($schedule);
    }

    /**
     * Archive a weekly schedule.
     */
    public function archiveSchedule(WeeklySchedule $schedule): void
    {
        $schedule->update(['status' => 'archived']);
    }

    /**
     * Get weekly schedule statistics for a date range.
     */
    public function getStatistics(Carbon $startDate, Carbon $endDate): array
    {
        $schedules = $this->getSchedulesInRange($startDate, $endDate);
        
        $totalWeeks = $schedules->count();
        $totalShifts = $schedules->sum('total_shifts');
        $totalHours = $schedules->sum('total_scheduled_hours');
        $totalLaborCost = $schedules->sum('estimated_labor_cost');
        
        // Get most used template
        $templateUsage = $schedules->whereNotNull('template_id')
            ->groupBy('template_id')
            ->map(function ($group) {
                return [
                    'template' => $group->first()->template,
                    'usage_count' => $group->count(),
                ];
            })
            ->sortByDesc('usage_count')
            ->first();

        return [
            'total_weeks_managed' => $totalWeeks,
            'total_shifts' => $totalShifts,
            'total_hours' => $totalHours,
            'total_labor_cost' => $totalLaborCost,
            'average_shifts_per_week' => $totalWeeks > 0 ? round($totalShifts / $totalWeeks, 1) : 0,
            'average_hours_per_week' => $totalWeeks > 0 ? round($totalHours / $totalWeeks, 1) : 0,
            'average_labor_cost_per_week' => $totalWeeks > 0 ? round($totalLaborCost / $totalWeeks, 2) : 0,
            'most_used_template' => $templateUsage['template'] ?? null,
            'most_used_template_count' => $templateUsage['usage_count'] ?? 0,
        ];
    }

    /**
     * Copy assignments from one week to another.
     */
    public function copyWeeklySchedule(WeeklySchedule $sourceSchedule, WeeklySchedule $targetSchedule): void
    {
        if ($targetSchedule->assignments()->exists()) {
            throw new \Exception('Target schedule already has assignments');
        }

        DB::transaction(function () use ($sourceSchedule, $targetSchedule) {
            $sourceAssignments = $sourceSchedule->assignments()
                ->with('staffShiftAssignment')
                ->get();

            foreach ($sourceAssignments as $sourceAssignment) {
                $originalAssignment = $sourceAssignment->staffShiftAssignment;
                
                // Calculate new assignment date
                $newAssignmentDate = $targetSchedule->week_start_date->copy()->addDays(
                    $sourceAssignment->day_of_week === 0 ? 6 : $sourceAssignment->day_of_week - 1
                );

                // Create new staff shift assignment
                $newStaffShiftAssignment = StaffShiftAssignment::create([
                    'staff_shift_id' => $originalAssignment->staff_shift_id,
                    'staff_id' => $originalAssignment->staff_id,
                    'assigned_date' => $newAssignmentDate,
                    'status' => 'scheduled',
                    'notes' => $originalAssignment->notes,
                    'assigned_by' => $targetSchedule->created_by,
                ]);

                // Create new weekly schedule assignment
                WeeklyScheduleAssignment::create([
                    'weekly_schedule_id' => $targetSchedule->id,
                    'staff_shift_assignment_id' => $newStaffShiftAssignment->id,
                    'staff_id' => $sourceAssignment->staff_id,
                    'staff_shift_id' => $sourceAssignment->staff_shift_id,
                    'assigned_date' => $newAssignmentDate,
                    'day_of_week' => $sourceAssignment->day_of_week,
                    'assignment_status' => 'scheduled',
                ]);
            }

            // Recalculate statistics
            $targetSchedule->recalculateStatistics();
        });
    }

    /**
     * Get upcoming weekly schedules that need attention.
     */
    public function getUpcomingSchedulesNeedingAttention(): Collection
    {
        $twoWeeksFromNow = now()->addWeeks(2);
        
        return WeeklySchedule::where('week_start_date', '<=', $twoWeeksFromNow)
            ->where('week_start_date', '>=', now()->startOfWeek())
            ->whereIn('status', ['draft'])
            ->with(['template', 'creator'])
            ->orderBy('week_start_date')
            ->get();
    }

    /**
     * Auto-create weekly schedules for upcoming weeks based on patterns.
     */
    public function autoCreateUpcomingSchedules(int $weeksAhead = 4, ?string $createdBy = null): array
    {
        $created = [];
        $startDate = now()->addWeek();
        
        for ($i = 0; $i < $weeksAhead; $i++) {
            $weekDate = $startDate->copy()->addWeeks($i);
            
            $existingSchedule = WeeklySchedule::forDate($weekDate);
            if (!$existingSchedule) {
                $schedule = $this->getOrCreateScheduleForDate($weekDate, $createdBy);
                $created[] = $schedule;
            }
        }
        
        return $created;
    }

    /**
     * Get conflict analysis for a weekly schedule.
     */
    public function getScheduleConflicts(WeeklySchedule $schedule): array
    {
        $conflicts = [];
        $assignments = $schedule->assignments()
            ->with(['staffShiftAssignment.staff', 'staffShiftAssignment.shift'])
            ->get();

        // Group assignments by staff and date
        $staffAssignments = $assignments->groupBy(function ($assignment) {
            return $assignment->staff_id . '_' . $assignment->assigned_date->format('Y-m-d');
        });

        foreach ($staffAssignments as $key => $dayAssignments) {
            if ($dayAssignments->count() > 1) {
                // Check for overlapping shifts
                $sortedAssignments = $dayAssignments->sortBy(function ($assignment) {
                    return $assignment->staffShiftAssignment->shift->start_time;
                });

                for ($i = 0; $i < $sortedAssignments->count() - 1; $i++) {
                    $current = $sortedAssignments->values()[$i];
                    $next = $sortedAssignments->values()[$i + 1];
                    
                    $currentShift = $current->staffShiftAssignment->shift;
                    $nextShift = $next->staffShiftAssignment->shift;
                    
                    if ($this->shiftsOverlap($currentShift, $nextShift)) {
                        $conflicts[] = [
                            'type' => 'overlapping_shifts',
                            'staff' => $current->staffShiftAssignment->staff,
                            'date' => $current->assigned_date,
                            'shifts' => [$currentShift, $nextShift],
                            'assignments' => [$current, $next],
                        ];
                    }
                }
            }
        }

        return $conflicts;
    }

    /**
     * Check if two shifts overlap.
     */
    private function shiftsOverlap($shift1, $shift2): bool
    {
        $start1 = Carbon::parse($shift1->start_time);
        $end1 = Carbon::parse($shift1->end_time);
        $start2 = Carbon::parse($shift2->start_time);
        $end2 = Carbon::parse($shift2->end_time);
        
        // Handle overnight shifts
        if ($end1->lt($start1)) {
            $end1->addDay();
        }
        if ($end2->lt($start2)) {
            $end2->addDay();
        }
        
        return $start1->lt($end2) && $start2->lt($end1);
    }
}
