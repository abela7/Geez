<?php

namespace App\Console\Commands;

use App\Models\WeeklyRotaTemplate;
use App\Models\StaffShiftAssignment;
use App\Models\WeeklySchedule;
use App\Models\WeeklyScheduleAssignment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ApplyTemplateToWeek extends Command
{
    protected $signature = 'template:apply {template_name} {week_start_date}';
    protected $description = 'Apply a template to a specific week';

    public function handle()
    {
        $templateName = $this->argument('template_name');
        $weekStartDate = $this->argument('week_start_date');
        
        $this->info('📋 APPLY TEMPLATE TO WEEK');
        $this->line(str_repeat('=', 50));
        
        // Find the template
        $template = WeeklyRotaTemplate::where('name', $templateName)->first();
        if (!$template) {
            $this->error("Template '{$templateName}' not found!");
            return 1;
        }
        
        // Parse the week start date
        try {
            $weekStart = Carbon::parse($weekStartDate)->startOfWeek(Carbon::MONDAY);
        } catch (\Exception $e) {
            $this->error("Invalid date format: {$weekStartDate}");
            $this->line("Please use format: YYYY-MM-DD (e.g., 2025-10-13)");
            return 1;
        }
        
        $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SUNDAY);
        
        $this->info("Template: {$template->name}");
        $this->info("Target Week: {$weekStart->format('M j')} - {$weekEnd->format('M j, Y')} (Week {$weekStart->weekOfYear})");
        
        // Check if weekly schedule already exists for this week
        $existingSchedule = WeeklySchedule::where('week_start_date', $weekStart)->first();
        if ($existingSchedule) {
            $this->warn("Weekly schedule already exists for this week!");
            $this->line("Status: {$existingSchedule->status}");
            $this->line("Assignments: {$existingSchedule->total_staff_assignments}");
            
            if (!$this->confirm('Do you want to add template assignments to existing week?')) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }
        
        // Get template assignments
        $templateAssignments = $template->assignments()->with(['staff', 'shift'])->get();
        
        if ($templateAssignments->isEmpty()) {
            $this->error('No assignments found in template!');
            return 1;
        }
        
        $this->line('');
        $this->info('📊 Template assignments to apply:');
        $this->line(str_repeat('-', 50));
        
        // Group by day
        $assignmentsByDay = $templateAssignments->groupBy('day_of_week');
        $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        
        foreach ($assignmentsByDay as $dayOfWeek => $dayAssignments) {
            $dayName = $dayNames[$dayOfWeek];
            $targetDate = $weekStart->copy()->addDays($dayOfWeek);
            
            $this->line("{$dayName} ({$targetDate->format('M j')}):");
            foreach ($dayAssignments as $assignment) {
                $staffName = $assignment->staff->first_name . ' ' . $assignment->staff->last_name;
                $shiftName = $assignment->shift->name;
                $this->line("  • {$staffName} → {$shiftName}");
            }
        }
        
        if (!$this->confirm('Apply these assignments to the target week?')) {
            $this->info('Operation cancelled.');
            return 0;
        }
        
        $this->line('');
        $this->info('🔄 Creating assignments...');
        
        // Create or get weekly schedule
        if (!$existingSchedule) {
            $firstStaff = \App\Models\Staff::first();
            
            $weeklySchedule = WeeklySchedule::create([
                'week_start_date' => $weekStart,
                'week_end_date' => $weekEnd,
                'year' => $weekStart->year,
                'week_number' => $weekStart->weekOfYear,
                'name' => null,
                'description' => "Created from template: {$template->name}",
                'template_id' => $template->id,
                'is_template_applied' => true,
                'status' => 'draft',
                'total_shifts' => 0,
                'total_staff_assignments' => 0,
                'total_scheduled_hours' => 0,
                'estimated_labor_cost' => 0,
                'created_by' => $firstStaff ? $firstStaff->id : null,
            ]);
        } else {
            $weeklySchedule = $existingSchedule;
            $weeklySchedule->update([
                'template_id' => $template->id,
                'is_template_applied' => true,
                'description' => $weeklySchedule->description . " + Template: {$template->name}",
            ]);
        }
        
        $createdAssignments = 0;
        
        // Create assignments for each day
        foreach ($templateAssignments as $templateAssignment) {
            $targetDate = $weekStart->copy()->addDays($templateAssignment->day_of_week);
            
            // Check if assignment already exists for this date/staff/shift
            $existingAssignment = StaffShiftAssignment::where('assigned_date', $targetDate)
                ->where('staff_id', $templateAssignment->staff_id)
                ->where('staff_shift_id', $templateAssignment->staff_shift_id)
                ->first();
            
            if ($existingAssignment) {
                $this->line("  ⚠️ Assignment already exists: {$templateAssignment->staff->first_name} → {$templateAssignment->shift->name} on {$targetDate->format('M j')}");
                continue;
            }
            
            // Create new assignment
            $assignment = StaffShiftAssignment::create([
                'staff_id' => $templateAssignment->staff_id,
                'staff_shift_id' => $templateAssignment->staff_shift_id,
                'assigned_date' => $targetDate,
                'status' => 'scheduled',
                'assigned_by' => $weeklySchedule->created_by,
                'notes' => $templateAssignment->notes,
            ]);
            
            // Create weekly schedule assignment link
            WeeklyScheduleAssignment::create([
                'weekly_schedule_id' => $weeklySchedule->id,
                'staff_shift_assignment_id' => $assignment->id,
                'staff_id' => $assignment->staff_id,
                'staff_shift_id' => $assignment->staff_shift_id,
                'assigned_date' => $assignment->assigned_date,
                'day_of_week' => $templateAssignment->day_of_week,
                'assignment_status' => $assignment->status,
            ]);
            
            $createdAssignments++;
        }
        
        // Recalculate weekly schedule statistics
        $weeklySchedule->recalculateStatistics();
        
        $this->line('');
        $this->info('✅ Template applied successfully!');
        $this->line("Weekly Schedule ID: {$weeklySchedule->id}");
        $this->line("New assignments created: {$createdAssignments}");
        $this->line("Total assignments in week: {$weeklySchedule->total_staff_assignments}");
        $this->line("Total hours: {$weeklySchedule->total_scheduled_hours}");
        
        $this->line('');
        $this->info('🎯 Next steps:');
        $this->line('1. Go to /admin/shifts/assignments');
        $this->line("2. Navigate to week: {$weekStart->format('M j')} - {$weekEnd->format('M j, Y')}");
        $this->line('3. Review and adjust assignments as needed');
        $this->line('4. Publish the schedule when ready');
        
        return 0;
    }
}