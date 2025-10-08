<?php

namespace App\Console\Commands;

use App\Models\WeeklyRotaTemplate;
use Illuminate\Console\Command;

class CheckTemplate extends Command
{
    protected $signature = 'template:check {name}';
    protected $description = 'Check template details';

    public function handle()
    {
        $name = $this->argument('name');
        
        $template = WeeklyRotaTemplate::where('name', $name)->first();
        
        if (!$template) {
            $this->error("Template '{$name}' not found!");
            return 1;
        }
        
        $this->info("Template: {$template->name}");
        $this->line("ID: {$template->id}");
        $this->line("Description: {$template->description}");
        $this->line("Active: " . ($template->is_active ? 'Yes' : 'No'));
        $this->line("Total assignments: {$template->total_assignments}");
        $this->line("Total hours: {$template->total_hours}");
        
        $assignments = $template->assignments()->with(['staff', 'shift'])->get();
        $this->line("Actual assignments count: {$assignments->count()}");
        
        if ($assignments->count() > 0) {
            $this->line('');
            $this->info('Sample assignments:');
            foreach ($assignments->take(5) as $assignment) {
                $staffName = $assignment->staff->first_name . ' ' . $assignment->staff->last_name;
                $shiftName = $assignment->shift->name;
                $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                $dayName = $dayNames[$assignment->day_of_week];
                
                $this->line("  {$dayName}: {$staffName} → {$shiftName}");
            }
        }
        
        return 0;
    }
}