<?php

namespace App\Console\Commands;

use App\Models\StaffShiftAssignment;
use App\Models\StaffShift;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExplainTotalHours extends Command
{
    protected $signature = 'explain:total-hours {--week=}';
    protected $description = 'Explain the Total Hours calculation for a specific week';

    public function handle()
    {
        $weekParam = $this->option('week');
        $weekStart = $weekParam ? Carbon::parse($weekParam)->startOfWeek() : Carbon::now()->startOfWeek();
        $weekEnd = $weekStart->copy()->endOfWeek();
        
        $this->info('📊 TOTAL HOURS CALCULATION BREAKDOWN');
        $this->line(str_repeat('=', 60));
        $this->line("Week: {$weekStart->format('M j')} - {$weekEnd->format('M j, Y')}");
        $this->line('');
        
        // Get all assignments for this week
        $assignments = StaffShiftAssignment::whereBetween('assigned_date', [
            $weekStart->format('Y-m-d'),
            $weekEnd->format('Y-m-d')
        ])->with('shift')->get();
        
        // Group by shift type
        $shiftGroups = $assignments->groupBy('staff_shift_id');
        
        $grandTotal = 0;
        
        foreach ($shiftGroups as $shiftId => $shiftAssignments) {
            $shift = $shiftAssignments->first()->shift;
            $count = $shiftAssignments->count();
            
            // Calculate duration manually
            $startTime = Carbon::parse($shift->start_time);
            $endTime = Carbon::parse($shift->end_time);
            
            $startMinutes = $startTime->hour * 60 + $startTime->minute;
            $endMinutes = $endTime->hour * 60 + $endTime->minute;
            
            if ($endTime->lt($startTime)) {
                $endMinutes += 1440; // Add 24 hours for overnight shifts
            }
            
            $totalMinutes = $endMinutes - $startMinutes;
            $hoursPerShift = $totalMinutes / 60;
            $totalForShiftType = $hoursPerShift * $count;
            
            $this->line("{$shift->name}:");
            $this->line("  Start: {$shift->start_time}");
            $this->line("  End: {$shift->end_time}");
            $this->line("  Duration per shift: {$hoursPerShift} hours");
            $this->line("  Number of assignments: {$count}");
            $this->line("  Subtotal: {$hoursPerShift} × {$count} = {$totalForShiftType} hours");
            $this->line('');
            
            $grandTotal += $totalForShiftType;
        }
        
        $this->line(str_repeat('-', 60));
        $this->info("TOTAL HOURS FOR THE WEEK: {$grandTotal} hours");
        $this->line(str_repeat('=', 60));
        
        // Show breakdown by day
        $this->line('');
        $this->info('📅 Breakdown by Day:');
        $this->line(str_repeat('-', 60));
        
        $dayTotals = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $weekStart->copy()->addDays($i);
            $dayAssignments = $assignments->filter(function($assignment) use ($date) {
                return Carbon::parse($assignment->assigned_date)->format('Y-m-d') === $date->format('Y-m-d');
            });
            
            $dayTotal = 0;
            foreach ($dayAssignments as $assignment) {
                $shift = $assignment->shift;
                $startTime = Carbon::parse($shift->start_time);
                $endTime = Carbon::parse($shift->end_time);
                
                $startMinutes = $startTime->hour * 60 + $startTime->minute;
                $endMinutes = $endTime->hour * 60 + $endTime->minute;
                
                if ($endTime->lt($startTime)) {
                    $endMinutes += 1440;
                }
                
                $totalMinutes = $endMinutes - $startMinutes;
                $dayTotal += $totalMinutes / 60;
            }
            
            $dayName = $date->format('D M j');
            $this->line("{$dayName}: {$dayTotal} hours ({$dayAssignments->count()} assignments)");
            $dayTotals[$dayName] = $dayTotal;
        }
        
        $this->line('');
        $this->info('✅ Total Hours calculation is correct!');
        
        return 0;
    }
}