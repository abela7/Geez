<?php

namespace App\Console\Commands;

use App\Models\WeeklyRotaTemplate;
use App\Models\WeeklyRotaTemplateAssignment;
use App\Models\StaffShiftAssignment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RecreateTemplateAssignments extends Command
{
    protected $signature = 'template:recreate-assignments {template_name}';
    protected $description = 'Recreate template assignments from the original week';

    public function handle()
    {
        $templateName = $this->argument('template_name');
        
        $template = WeeklyRotaTemplate::where('name', $templateName)->first();
        
        if (!$template) {
            $this->error("Template '{$templateName}' not found!");
            return 1;
        }
        
        $this->info("Recreating assignments for template: {$template->name}");
        
        // Delete all existing template assignments
        $deletedCount = WeeklyRotaTemplateAssignment::where('template_id', $template->id)->count();
        WeeklyRotaTemplateAssignment::where('template_id', $template->id)->delete();
        $this->line("Deleted {$deletedCount} existing assignments");
        
        // Get the original week's assignments (Week 41)
        $weekStart = Carbon::parse('2025-10-06')->startOfWeek(Carbon::MONDAY);
        $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SUNDAY);
        
        $assignments = StaffShiftAssignment::whereBetween('assigned_date', [$weekStart, $weekEnd])
            ->with(['staff', 'shift'])
            ->get();
        
        $this->line("Found {$assignments->count()} assignments from original week");
        
        if ($assignments->isEmpty()) {
            $this->error('No assignments found for the original week!');
            return 1;
        }
        
        $this->line('');
        $this->info('Creating template assignments...');
        
        $createdCount = 0;
        foreach ($assignments as $assignment) {
            $dayOfWeek = Carbon::parse($assignment->assigned_date)->dayOfWeek;
            
            WeeklyRotaTemplateAssignment::create([
                'template_id' => $template->id,
                'staff_id' => $assignment->staff_id,
                'staff_shift_id' => $assignment->staff_shift_id,
                'day_of_week' => $dayOfWeek,
                'status' => 'scheduled',
                'notes' => $assignment->notes,
            ]);
            
            $createdCount++;
        }
        
        // Update template statistics
        $template->update([
            'total_assignments' => $createdCount,
            'total_hours' => $assignments->sum(function($assignment) {
                return $assignment->shift->duration_hours ?? 0;
            }),
        ]);
        
        $this->line('');
        $this->info('✅ Template assignments recreated successfully!');
        $this->line("Created {$createdCount} assignments");
        $this->line("Total assignments: {$template->total_assignments}");
        $this->line("Total hours: {$template->total_hours}");
        
        return 0;
    }
}