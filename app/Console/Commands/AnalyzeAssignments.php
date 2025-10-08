<?php

namespace App\Console\Commands;

use App\Models\StaffShiftAssignment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AnalyzeAssignments extends Command
{
    protected $signature = 'assignments:analyze';
    protected $description = 'Analyze all assignments in the database to see what data exists';

    public function handle()
    {
        $this->info('📊 STAFF SHIFT ASSIGNMENTS ANALYSIS');
        $this->line(str_repeat('=', 60));
        
        $totalAssignments = StaffShiftAssignment::count();
        $this->info("Total assignments in database: {$totalAssignments}");
        
        if ($totalAssignments === 0) {
            $this->warn('No assignments found in database.');
            return 0;
        }
        
        // Group by assigned_date
        $assignmentsByDate = StaffShiftAssignment::selectRaw('assigned_date, COUNT(*) as count')
            ->groupBy('assigned_date')
            ->orderBy('assigned_date')
            ->get();
        
        $this->line('');
        $this->info('📅 Assignments by Date:');
        $this->line(str_repeat('-', 40));
        
        foreach ($assignmentsByDate as $assignment) {
            $date = Carbon::parse($assignment->assigned_date);
            $weekNumber = $date->weekOfYear;
            $dayName = $date->format('l');
            
            $this->line("{$assignment->assigned_date} ({$dayName}) - Week {$weekNumber}: {$assignment->count} assignments");
        }
        
        // Group by week
        $this->line('');
        $this->info('📅 Assignments by Week:');
        $this->line(str_repeat('-', 40));
        
        $assignmentsByWeek = [];
        foreach ($assignmentsByDate as $assignment) {
            $date = Carbon::parse($assignment->assigned_date);
            $weekStart = $date->copy()->startOfWeek(Carbon::MONDAY);
            $weekKey = $weekStart->format('Y-m-d');
            
            if (!isset($assignmentsByWeek[$weekKey])) {
                $assignmentsByWeek[$weekKey] = [
                    'week_start' => $weekStart,
                    'count' => 0
                ];
            }
            $assignmentsByWeek[$weekKey]['count'] += $assignment->count;
        }
        
        foreach ($assignmentsByWeek as $weekKey => $weekData) {
            $weekStart = $weekData['week_start'];
            $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SUNDAY);
            $weekNumber = $weekStart->weekOfYear;
            
            $this->line("Week {$weekNumber} ({$weekStart->format('M j')} - {$weekEnd->format('M j, Y')}): {$weekData['count']} assignments");
        }
        
        // Show current week specifically
        $currentWeekStart = now()->startOfWeek(Carbon::MONDAY);
        $currentWeekEnd = now()->endOfWeek(Carbon::SUNDAY);
        $currentWeekAssignments = StaffShiftAssignment::whereBetween('assigned_date', [$currentWeekStart, $currentWeekEnd])->count();
        
        $this->line('');
        $this->info('📅 Current Week Analysis:');
        $this->line(str_repeat('-', 40));
        $this->line("Current week: {$currentWeekStart->format('M j')} - {$currentWeekEnd->format('M j, Y')} (Week {$currentWeekStart->weekOfYear})");
        $this->line("Assignments in current week: {$currentWeekAssignments}");
        
        // Show assignments outside current week
        $otherWeekAssignments = $totalAssignments - $currentWeekAssignments;
        $this->line("Assignments in other weeks: {$otherWeekAssignments}");
        
        if ($otherWeekAssignments > 0) {
            $this->line('');
            $this->warn('🔍 DETAILED BREAKDOWN OF OTHER WEEKS:');
            
            foreach ($assignmentsByWeek as $weekKey => $weekData) {
                $weekStart = $weekData['week_start'];
                $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SUNDAY);
                $weekNumber = $weekStart->weekOfYear;
                
                if (!$weekStart->isCurrentWeek()) {
                    $this->line("Week {$weekNumber} ({$weekStart->format('M j')} - {$weekEnd->format('M j, Y')}): {$weekData['count']} assignments");
                    
                    // Show details for this week
                    $weekAssignments = StaffShiftAssignment::whereBetween('assigned_date', [$weekStart, $weekEnd])
                        ->with(['staff', 'shift'])
                        ->get();
                    
                    foreach ($weekAssignments as $assignment) {
                        $staffName = $assignment->staff->first_name . ' ' . $assignment->staff->last_name;
                        $shiftName = $assignment->shift->name;
                        $date = Carbon::parse($assignment->assigned_date)->format('M j');
                        
                        $this->line("  - {$date}: {$staffName} → {$shiftName}");
                    }
                }
            }
        }
        
        // Show status breakdown
        $this->line('');
        $this->info('📊 Status Breakdown:');
        $this->line(str_repeat('-', 40));
        
        $statusCounts = StaffShiftAssignment::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();
        
        foreach ($statusCounts as $status) {
            $this->line("{$status->status}: {$status->count} assignments");
        }
        
        return 0;
    }
}