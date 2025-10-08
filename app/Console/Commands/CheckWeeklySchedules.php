<?php

namespace App\Console\Commands;

use App\Models\WeeklySchedule;
use App\Models\StaffShiftAssignment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckWeeklySchedules extends Command
{
    protected $signature = 'weekly:check';
    protected $description = 'Check weekly schedules and create them for existing assignments';

    public function handle()
    {
        $this->info('📅 WEEKLY SCHEDULES CHECK');
        $this->line(str_repeat('=', 50));
        
        $weeklySchedules = WeeklySchedule::count();
        $assignments = StaffShiftAssignment::count();
        
        $this->info("Weekly schedules: {$weeklySchedules}");
        $this->info("Staff assignments: {$assignments}");
        
        if ($assignments > 0 && $weeklySchedules === 0) {
            $this->warn('⚠️  Assignments exist but no weekly schedules found!');
            $this->line('This means the integration between assignments and weekly schedules needs to be set up.');
            
            if ($this->confirm('Would you like me to create weekly schedules for existing assignments?')) {
                $this->createWeeklySchedulesForExistingAssignments();
            }
        } elseif ($weeklySchedules > 0) {
            $this->info('✅ Weekly schedules exist!');
            $this->showWeeklyScheduleDetails();
        } else {
            $this->info('No assignments or weekly schedules found.');
        }
        
        return 0;
    }
    
    private function createWeeklySchedulesForExistingAssignments()
    {
        $this->line('');
        $this->info('🔄 Creating weekly schedules for existing assignments...');
        
        // Get all assignments grouped by week
        $assignments = StaffShiftAssignment::orderBy('assigned_date')->get();
        $weeks = [];
        
        foreach ($assignments as $assignment) {
            $date = Carbon::parse($assignment->assigned_date);
            $weekStart = $date->copy()->startOfWeek(Carbon::MONDAY);
            $weekKey = $weekStart->format('Y-m-d');
            
            if (!isset($weeks[$weekKey])) {
                $weeks[$weekKey] = [
                    'week_start' => $weekStart,
                    'assignments' => []
                ];
            }
            $weeks[$weekKey]['assignments'][] = $assignment;
        }
        
        foreach ($weeks as $weekKey => $weekData) {
            $weekStart = $weekData['week_start'];
            $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SUNDAY);
            
            $this->line("Creating weekly schedule for: {$weekStart->format('M j')} - {$weekEnd->format('M j, Y')}");
            
            // Get first staff member as created_by (since we're in console)
            $firstStaff = \App\Models\Staff::first();
            $createdBy = $firstStaff ? $firstStaff->id : null;
            
            // Create weekly schedule
            $weeklySchedule = WeeklySchedule::create([
                'week_start_date' => $weekStart,
                'week_end_date' => $weekEnd,
                'year' => $weekStart->year,
                'week_number' => $weekStart->weekOfYear,
                'name' => null,
                'description' => 'Created from existing assignments',
                'template_id' => null,
                'is_template_applied' => false,
                'status' => $weekStart->isPast() ? 'completed' : 'active',
                'total_shifts' => 0,
                'total_staff_assignments' => 0,
                'total_scheduled_hours' => 0,
                'estimated_labor_cost' => 0,
                'created_by' => $createdBy,
            ]);
            
            // Create weekly schedule assignment links
            foreach ($weekData['assignments'] as $assignment) {
                $dayOfWeek = Carbon::parse($assignment->assigned_date)->dayOfWeek;
                
                \App\Models\WeeklyScheduleAssignment::create([
                    'weekly_schedule_id' => $weeklySchedule->id,
                    'staff_shift_assignment_id' => $assignment->id,
                    'staff_id' => $assignment->staff_id,
                    'staff_shift_id' => $assignment->staff_shift_id,
                    'assigned_date' => $assignment->assigned_date,
                    'day_of_week' => $dayOfWeek,
                    'assignment_status' => $assignment->status,
                ]);
            }
            
            // Recalculate statistics
            $weeklySchedule->recalculateStatistics();
            
            $this->line("  ✅ Created with {$weeklySchedule->total_staff_assignments} assignments");
        }
        
        $this->info('🎉 Weekly schedules created successfully!');
    }
    
    private function showWeeklyScheduleDetails()
    {
        $schedules = WeeklySchedule::with('assignments')->get();
        
        $this->line('');
        $this->info('📊 Weekly Schedule Details:');
        $this->line(str_repeat('-', 50));
        
        foreach ($schedules as $schedule) {
            $this->line("Week {$schedule->week_number} ({$schedule->week_start_date->format('M j')} - {$schedule->week_end_date->format('M j, Y')})");
            $this->line("  Status: {$schedule->status}");
            $this->line("  Assignments: {$schedule->total_staff_assignments}");
            $this->line("  Hours: {$schedule->total_scheduled_hours}");
            $this->line("  Labor Cost: $" . number_format($schedule->estimated_labor_cost, 2));
        }
    }
}