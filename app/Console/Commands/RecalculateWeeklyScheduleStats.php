<?php

namespace App\Console\Commands;

use App\Models\WeeklySchedule;
use Illuminate\Console\Command;

class RecalculateWeeklyScheduleStats extends Command
{
    protected $signature = 'shifts:recalculate-stats';
    protected $description = 'Recalculate statistics for all weekly schedules';

    public function handle()
    {
        $this->info('Recalculating weekly schedule statistics...');
        
        $schedules = WeeklySchedule::all();
        $count = 0;
        
        foreach ($schedules as $schedule) {
            $schedule->recalculateStatistics();
            $this->line("Recalculated: Week of {$schedule->week_start_date->format('M j, Y')} - {$schedule->total_scheduled_hours} hours");
            $count++;
        }
        
        $this->info("Successfully recalculated statistics for {$count} weekly schedules.");
        return 0;
    }
}