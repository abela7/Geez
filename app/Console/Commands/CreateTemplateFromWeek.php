<?php

namespace App\Console\Commands;

use App\Models\WeeklySchedule;
use App\Models\WeeklyRotaTemplate;
use App\Models\StaffShiftAssignment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CreateTemplateFromWeek extends Command
{
    protected $signature = 'template:create-from-week {week_number?} {--name=} {--description=}';
    protected $description = 'Create a template from an existing week\'s assignments';

    public function handle()
    {
        $weekNumber = $this->argument('week_number');
        $templateName = $this->option('name');
        $templateDescription = $this->option('description');
        
        $this->info('📋 CREATE TEMPLATE FROM WEEK');
        $this->line(str_repeat('=', 50));
        
        // Find the weekly schedule
        $weeklySchedule = null;
        if ($weekNumber) {
            $weeklySchedule = WeeklySchedule::where('week_number', $weekNumber)->first();
        } else {
            // Get the most recent weekly schedule
            $weeklySchedule = WeeklySchedule::orderBy('week_start_date', 'desc')->first();
        }
        
        if (!$weeklySchedule) {
            $this->error('No weekly schedule found!');
            return 1;
        }
        
        $this->info("Creating template from Week {$weeklySchedule->week_number} ({$weeklySchedule->week_start_date->format('M j')} - {$weeklySchedule->week_end_date->format('M j, Y')})");
        $this->line("Assignments in this week: {$weeklySchedule->total_staff_assignments}");
        
        // Get template name
        if (!$templateName) {
            $templateName = $this->ask('Enter template name', "Week {$weeklySchedule->week_number} Template");
        }
        
        if (!$templateDescription) {
            $templateDescription = $this->ask('Enter template description', "Template created from Week {$weeklySchedule->week_number}");
        }
        
        // Get assignments for this week
        $assignments = StaffShiftAssignment::whereBetween('assigned_date', [
            $weeklySchedule->week_start_date,
            $weeklySchedule->week_end_date
        ])->with(['staff', 'shift'])->get();
        
        if ($assignments->isEmpty()) {
            $this->error('No assignments found for this week!');
            return 1;
        }
        
        $this->line('');
        $this->info('📊 Assignments to include in template:');
        $this->line(str_repeat('-', 50));
        
        // Group assignments by day
        $assignmentsByDay = $assignments->groupBy(function($assignment) {
            return Carbon::parse($assignment->assigned_date)->format('l');
        });
        
        foreach ($assignmentsByDay as $day => $dayAssignments) {
            $this->line("{$day}:");
            foreach ($dayAssignments as $assignment) {
                $staffName = $assignment->staff->first_name . ' ' . $assignment->staff->last_name;
                $shiftName = $assignment->shift->name;
                $this->line("  • {$staffName} → {$shiftName}");
            }
        }
        
        if (!$this->confirm('Create template with these assignments?')) {
            $this->info('Template creation cancelled.');
            return 0;
        }
        
        // Create the template
        $template = WeeklyRotaTemplate::create([
            'name' => $templateName,
            'description' => $templateDescription,
            'is_active' => true,
            'created_by' => $weeklySchedule->created_by,
        ]);
        
        $this->line('');
        $this->info('🔄 Creating template assignments...');
        
        // Create template assignments (day-based, not date-specific)
        foreach ($assignments as $assignment) {
            $dayOfWeek = Carbon::parse($assignment->assigned_date)->dayOfWeek;
            
            \App\Models\WeeklyRotaTemplateAssignment::create([
                'template_id' => $template->id,
                'staff_id' => $assignment->staff_id,
                'staff_shift_id' => $assignment->staff_shift_id,
                'day_of_week' => $dayOfWeek,
                'status' => 'scheduled',
                'notes' => $assignment->notes,
            ]);
        }
        
        // Update template statistics
        $template->update([
            'total_assignments' => $assignments->count(),
            'total_hours' => $assignments->sum(function($assignment) {
                return $assignment->shift->duration_hours ?? 0;
            }),
        ]);
        
        $this->line('');
        $this->info('✅ Template created successfully!');
        $this->line("Template ID: {$template->id}");
        $this->line("Template Name: {$template->name}");
        $this->line("Total Assignments: {$template->total_assignments}");
        $this->line("Total Hours: {$template->total_hours}");
        
        $this->line('');
        $this->info('🚀 How to use this template:');
        $this->line('1. Go to /admin/shifts/templates');
        $this->line("2. Find template: {$template->name}");
        $this->line('3. Click "Apply to Week"');
        $this->line('4. Select the target week');
        $this->line('5. The system will create assignments for that week!');
        
        return 0;
    }
}