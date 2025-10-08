<?php

namespace App\Console\Commands;

use App\Models\StaffShift;
use App\Models\StaffShiftAssignment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DebugShiftDurations extends Command
{
    protected $signature = 'debug:shift-durations';
    protected $description = 'Debug shift durations to see what\'s happening with the calculation';

    public function handle()
    {
        $this->info('🔍 DEBUGGING SHIFT DURATIONS');
        $this->line(str_repeat('=', 50));
        
        // Get all shifts
        $shifts = StaffShift::all();
        
        $this->info('📊 All Shifts and Their Durations:');
        $this->line(str_repeat('-', 50));
        
        foreach ($shifts as $shift) {
            $startTime = Carbon::parse($shift->start_time);
            $endTime = Carbon::parse($shift->end_time);
            
            $this->line("Shift: {$shift->name}");
            $this->line("  Start Time: {$shift->start_time}");
            $this->line("  End Time: {$shift->end_time}");
            $this->line("  Department: {$shift->department}");
            
            // Check if it's an overnight shift
            $isOvernight = $endTime->lt($startTime);
            $this->line("  Is Overnight: " . ($isOvernight ? 'Yes' : 'No'));
            
            if ($isOvernight) {
                $endTimeCopy = $endTime->copy()->addDay();
                $totalMinutes = $endTimeCopy->diffInMinutes($startTime);
                $duration = $totalMinutes / 60;
                $this->line("  Duration (with overnight): {$duration} hours");
            } else {
                $totalMinutes = $endTime->diffInMinutes($startTime);
                $duration = $totalMinutes / 60;
                $this->line("  Duration: {$duration} hours");
            }
            
            // Check for break time
            if (isset($shift->break_minutes) && $shift->break_minutes > 0) {
                $this->line("  Break Minutes: {$shift->break_minutes}");
                $duration -= ($shift->break_minutes / 60);
                $this->line("  Duration (after break): {$duration} hours");
            } elseif (isset($shift->break_duration) && $shift->break_duration > 0) {
                $this->line("  Break Duration: {$shift->break_duration}");
                $duration -= ($shift->break_duration / 60);
                $this->line("  Duration (after break): {$duration} hours");
            }
            
            $this->line('');
        }
        
        // Now check assignments for this week
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = $weekStart->copy()->endOfWeek();
        
        $assignments = StaffShiftAssignment::whereBetween('assigned_date', [$weekStart->format('Y-m-d'), $weekEnd->format('Y-m-d')])
            ->with(['shift'])
            ->get();
        
        $this->info('📅 Assignments for Current Week:');
        $this->line(str_repeat('-', 50));
        
        $totalHours = 0;
        foreach ($assignments as $assignment) {
            $shift = $assignment->shift;
            $startTime = Carbon::parse($shift->start_time);
            $endTime = Carbon::parse($shift->end_time);
            
            // Handle overnight shifts
            if ($endTime->lt($startTime)) {
                $endTime->addDay();
            }
            
            // Use diffInMinutes to avoid negative hour calculations
            $totalMinutes = $endTime->diffInMinutes($startTime);
            
            // Subtract break time
            if (isset($shift->break_minutes) && $shift->break_minutes > 0) {
                $totalMinutes -= $shift->break_minutes;
            } elseif (isset($shift->break_duration) && $shift->break_duration > 0) {
                $totalMinutes -= $shift->break_duration;
            }
            
            // Ensure total minutes is not negative and convert to hours
            $totalMinutes = max(0, $totalMinutes);
            $duration = $totalMinutes / 60;
            $totalHours += $duration;
            
            $this->line("{$assignment->assigned_date}: {$shift->name} - {$duration} hours");
        }
        
        $this->line('');
        $this->info("Total Hours for Week: {$totalHours}");
        
        return 0;
    }
}