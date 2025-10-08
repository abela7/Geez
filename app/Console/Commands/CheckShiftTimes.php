<?php

namespace App\Console\Commands;

use App\Models\StaffShift;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckShiftTimes extends Command
{
    protected $signature = 'check:shift-times';
    protected $description = 'Check shift times to understand the calculation issue';

    public function handle()
    {
        $this->info('🕐 CHECKING SHIFT TIMES');
        $this->line(str_repeat('=', 50));
        
        $shifts = StaffShift::all();
        
        foreach ($shifts as $shift) {
            $this->line("Shift: {$shift->name}");
            $this->line("  Raw Start: {$shift->start_time}");
            $this->line("  Raw End: {$shift->end_time}");
            
            $start = Carbon::parse($shift->start_time);
            $end = Carbon::parse($shift->end_time);
            
            $this->line("  Start Carbon: {$start->format('H:i:s')}");
            $this->line("  End Carbon: {$end->format('H:i:s')}");
            $this->line("  End < Start: " . ($end->lt($start) ? 'Yes' : 'No'));
            
            $minutes = $end->diffInMinutes($start);
            $this->line("  Diff in minutes: {$minutes}");
            $this->line("  Hours: " . ($minutes / 60));
            
            // Test the correct calculation
            $startMinutes = $start->hour * 60 + $start->minute;
            $endMinutes = $end->hour * 60 + $end->minute;
            
            if ($end->lt($start)) {
                $endMinutes += 1440; // Add 24 hours
            }
            
            $totalMinutes = $endMinutes - $startMinutes;
            $this->line("  Manual calculation: {$totalMinutes} minutes = " . ($totalMinutes / 60) . " hours");
            
            $this->line('');
        }
        
        return 0;
    }
}