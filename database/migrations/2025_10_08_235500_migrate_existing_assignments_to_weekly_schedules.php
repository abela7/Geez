<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration creates weekly schedules for existing staff shift assignments
     * and links them through the weekly_schedule_assignments table.
     */
    public function up(): void
    {
        // Only run if we have existing staff shift assignments
        if (DB::table('staff_shift_assignments')->count() === 0) {
            return;
        }

        $this->info('Starting migration of existing shift assignments to weekly schedule system...');

        // Get all existing staff shift assignments grouped by week
        $assignments = DB::table('staff_shift_assignments')
            ->whereNull('deleted_at')
            ->orderBy('assigned_date')
            ->get();

        $weeklySchedules = [];
        $processedWeeks = [];

        foreach ($assignments as $assignment) {
            $assignedDate = Carbon::parse($assignment->assigned_date);
            $weekStart = $assignedDate->copy()->startOfWeek(Carbon::MONDAY);
            $weekEnd = $assignedDate->copy()->endOfWeek(Carbon::SUNDAY);
            $weekKey = $weekStart->format('Y-m-d');

            // Create weekly schedule if it doesn't exist
            if (!isset($processedWeeks[$weekKey])) {
                $weeklyScheduleId = $this->generateUlid();
                
                DB::table('weekly_schedules')->insert([
                    'id' => $weeklyScheduleId,
                    'week_start_date' => $weekStart->format('Y-m-d'),
                    'week_end_date' => $weekEnd->format('Y-m-d'),
                    'year' => $weekStart->year,
                    'week_number' => $weekStart->weekOfYear,
                    'name' => null,
                    'description' => 'Migrated from existing assignments',
                    'template_id' => null,
                    'is_template_applied' => false,
                    'status' => $this->determineWeekStatus($weekStart),
                    'total_shifts' => 0,
                    'total_staff_assignments' => 0,
                    'total_scheduled_hours' => 0,
                    'estimated_labor_cost' => 0,
                    'published_at' => $weekStart->isPast() ? $weekStart->endOfWeek() : null,
                    'published_by' => null,
                    'created_by' => $assignment->assigned_by ?? $this->getFirstAdminStaffId(),
                    'updated_by' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'deleted_at' => null,
                ]);

                $processedWeeks[$weekKey] = $weeklyScheduleId;
                $weeklySchedules[$weeklyScheduleId] = [
                    'week_start' => $weekStart,
                    'assignments' => []
                ];
            }

            $weeklyScheduleId = $processedWeeks[$weekKey];

            // Calculate day of week (0 = Sunday, 1 = Monday, etc.)
            $dayOfWeek = $assignedDate->dayOfWeek;

            // Create weekly schedule assignment link
            DB::table('weekly_schedule_assignments')->insert([
                'id' => $this->generateUlid(),
                'weekly_schedule_id' => $weeklyScheduleId,
                'staff_shift_assignment_id' => $assignment->id,
                'staff_id' => $assignment->staff_id,
                'staff_shift_id' => $assignment->staff_shift_id,
                'assigned_date' => $assignment->assigned_date,
                'day_of_week' => $dayOfWeek,
                'assignment_status' => $assignment->status,
                'scheduled_hours' => $this->calculateScheduledHours($assignment->staff_shift_id),
                'hourly_rate' => $this->getStaffHourlyRate($assignment->staff_id),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Track assignment for statistics calculation
            $weeklySchedules[$weeklyScheduleId]['assignments'][] = $assignment;
        }

        // Update weekly schedule statistics
        foreach ($weeklySchedules as $weeklyScheduleId => $scheduleData) {
            $this->updateWeeklyScheduleStatistics($weeklyScheduleId, $scheduleData['assignments']);
        }

        $this->info('Migration completed. Created ' . count($processedWeeks) . ' weekly schedules.');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove all weekly schedule assignments that were created during migration
        DB::table('weekly_schedule_assignments')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('weekly_schedules')
                    ->whereColumn('weekly_schedules.id', 'weekly_schedule_assignments.weekly_schedule_id')
                    ->where('weekly_schedules.description', 'Migrated from existing assignments');
            })
            ->delete();

        // Remove weekly schedules created during migration
        DB::table('weekly_schedules')
            ->where('description', 'Migrated from existing assignments')
            ->delete();
    }

    /**
     * Generate a ULID for database records.
     */
    private function generateUlid(): string
    {
        return (string) \Illuminate\Support\Str::ulid();
    }

    /**
     * Determine the status of a week based on its date.
     */
    private function determineWeekStatus(Carbon $weekStart): string
    {
        $now = now();
        $currentWeekStart = $now->copy()->startOfWeek(Carbon::MONDAY);

        if ($weekStart->eq($currentWeekStart)) {
            return 'active';
        } elseif ($weekStart->lt($currentWeekStart)) {
            return 'completed';
        } else {
            return 'published';
        }
    }

    /**
     * Get the first admin staff ID for created_by field.
     */
    private function getFirstAdminStaffId(): ?string
    {
        $adminStaff = DB::table('staff')
            ->join('staff_profiles', 'staff.id', '=', 'staff_profiles.staff_id')
            ->where('staff_profiles.role', 'admin')
            ->orWhere('staff_profiles.role', 'manager')
            ->first();

        return $adminStaff ? $adminStaff->id : null;
    }

    /**
     * Calculate scheduled hours for a shift.
     */
    private function calculateScheduledHours(string $shiftId): ?float
    {
        $shift = DB::table('staff_shifts')->where('id', $shiftId)->first();
        
        if (!$shift) {
            return null;
        }

        $startTime = Carbon::parse($shift->start_time);
        $endTime = Carbon::parse($shift->end_time);

        // Handle overnight shifts
        if ($endTime->lt($startTime)) {
            $endTime->addDay();
        }

        $totalMinutes = $endTime->diffInMinutes($startTime);
        
        // Subtract break time if applicable
        if (isset($shift->break_minutes) && $shift->break_minutes > 0) {
            $totalMinutes -= $shift->break_minutes;
        } elseif (isset($shift->break_duration) && $shift->break_duration > 0) {
            $totalMinutes -= $shift->break_duration;
        }

        // Ensure we don't have negative hours
        $totalMinutes = max(0, $totalMinutes);

        return $totalMinutes / 60; // Convert to hours
    }

    /**
     * Get staff hourly rate at time of migration.
     */
    private function getStaffHourlyRate(string $staffId): ?float
    {
        $profile = DB::table('staff_profiles')
            ->where('staff_id', $staffId)
            ->first();

        return $profile ? $profile->hourly_rate : null;
    }

    /**
     * Update weekly schedule statistics.
     */
    private function updateWeeklyScheduleStatistics(string $weeklyScheduleId, array $assignments): void
    {
        $totalShifts = count($assignments);
        $totalStaffAssignments = count($assignments);
        $totalScheduledHours = 0;
        $estimatedLaborCost = 0;

        foreach ($assignments as $assignment) {
            $scheduledHours = $this->calculateScheduledHours($assignment->staff_shift_id) ?? 0;
            $hourlyRate = $this->getStaffHourlyRate($assignment->staff_id) ?? 0;

            $totalScheduledHours += $scheduledHours;
            $estimatedLaborCost += ($scheduledHours * $hourlyRate);
        }

        DB::table('weekly_schedules')
            ->where('id', $weeklyScheduleId)
            ->update([
                'total_shifts' => $totalShifts,
                'total_staff_assignments' => $totalStaffAssignments,
                'total_scheduled_hours' => $totalScheduledHours,
                'estimated_labor_cost' => $estimatedLaborCost,
                'updated_at' => now(),
            ]);
    }

    /**
     * Output info message during migration.
     */
    private function info(string $message): void
    {
        if (app()->runningInConsole()) {
            echo $message . "\n";
        }
    }
};
