<?php

namespace App\Console\Commands;

use App\Models\StaffShift;
use Illuminate\Console\Command;

class ActivateShift extends Command
{
    protected $signature = 'shifts:activate {name : The name of the shift to activate}';
    protected $description = 'Activate a shift by name';

    public function handle()
    {
        $shiftName = $this->argument('name');
        
        $shift = StaffShift::where('name', 'like', '%' . $shiftName . '%')->first();
        
        if (!$shift) {
            $this->error("Shift '{$shiftName}' not found.");
            
            // Show available shifts
            $this->info('Available shifts:');
            StaffShift::all()->each(function($s) {
                $status = $s->is_active ? 'Active' : 'Inactive';
                $this->line("  - {$s->name} ({$status})");
            });
            
            return 1;
        }
        
        if ($shift->is_active) {
            $this->info("Shift '{$shift->name}' is already active.");
            return 0;
        }
        
        $shift->update(['is_active' => true]);
        
        $this->info("Successfully activated shift: {$shift->name}");
        $this->line("Department: {$shift->department}");
        $this->line("Time: {$shift->start_time} - {$shift->end_time}");
        
        return 0;
    }
}