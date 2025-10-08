<?php

namespace App\Console\Commands;

use App\Models\WeeklyRotaTemplate;
use App\Models\WeeklyRotaTemplateAssignment;
use Illuminate\Console\Command;

class FixTemplateAssignments extends Command
{
    protected $signature = 'template:fix-assignments {template_name}';
    protected $description = 'Fix template assignments by linking them to the correct template';

    public function handle()
    {
        $templateName = $this->argument('template_name');
        
        $template = WeeklyRotaTemplate::where('name', $templateName)->first();
        
        if (!$template) {
            $this->error("Template '{$templateName}' not found!");
            return 1;
        }
        
        $this->info("Fixing assignments for template: {$template->name}");
        $this->line("Template ID: {$template->id}");
        
        // Find assignments that don't have the correct template_id
        $orphanedAssignments = WeeklyRotaTemplateAssignment::where('template_id', '!=', $template->id)
            ->orWhereNull('template_id')
            ->get();
        
        $this->line("Found {$orphanedAssignments->count()} orphaned assignments");
        
        if ($orphanedAssignments->count() > 0) {
            $this->line('');
            $this->info('Sample orphaned assignments:');
            foreach ($orphanedAssignments->take(5) as $assignment) {
                $this->line("  ID: {$assignment->id}, Template ID: {$assignment->template_id}");
            }
            
            if ($this->confirm('Update these assignments to use the correct template ID?')) {
                $updated = WeeklyRotaTemplateAssignment::where('template_id', '!=', $template->id)
                    ->orWhereNull('template_id')
                    ->update(['template_id' => $template->id]);
                
                $this->info("Updated {$updated} assignments");
            }
        }
        
        // Check final count
        $finalCount = WeeklyRotaTemplateAssignment::where('template_id', $template->id)->count();
        $this->line('');
        $this->info("Final assignment count for template: {$finalCount}");
        
        // Update template statistics
        $template->update([
            'total_assignments' => $finalCount,
            'total_hours' => 0, // We'll calculate this properly later
        ]);
        
        $this->info('✅ Template assignments fixed!');
        
        return 0;
    }
}