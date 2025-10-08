<?php

namespace App\Console\Commands;

use App\Models\StaffShiftAssignment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AnalyzeDeletedAssignments extends Command
{
    protected $signature = 'assignments:analyze-deleted';
    protected $description = 'Analyze soft-deleted assignments to see what was cleared';

    public function handle()
    {
        $this->info('🗑️ SOFT-DELETED ASSIGNMENTS ANALYSIS');
        $this->line(str_repeat('=', 60));
        
        $deletedAssignments = StaffShiftAssignment::onlyTrashed()->count();
        $this->info("Total soft-deleted assignments: {$deletedAssignments}");
        
        if ($deletedAssignments === 0) {
            $this->info('No soft-deleted assignments found.');
            return 0;
        }
        
        // Group deleted assignments by date
        $deletedByDate = StaffShiftAssignment::onlyTrashed()
            ->selectRaw('assigned_date, COUNT(*) as count')
            ->groupBy('assigned_date')
            ->orderBy('assigned_date')
            ->get();
        
        $this->line('');
        $this->info('📅 Deleted Assignments by Date:');
        $this->line(str_repeat('-', 50));
        
        foreach ($deletedByDate as $assignment) {
            $date = Carbon::parse($assignment->assigned_date);
            $weekNumber = $date->weekOfYear;
            $dayName = $date->format('l');
            $weekStart = $date->copy()->startOfWeek(Carbon::MONDAY);
            $weekEnd = $date->copy()->endOfWeek(Carbon::SUNDAY);
            
            $this->line("{$assignment->assigned_date} ({$dayName}) - Week {$weekNumber}: {$assignment->count} deleted assignments");
            $this->line("  Week: {$weekStart->format('M j')} - {$weekEnd->format('M j, Y')}");
        }
        
        // Group by week
        $this->line('');
        $this->info('📅 Deleted Assignments by Week:');
        $this->line(str_repeat('-', 50));
        
        $deletedByWeek = [];
        foreach ($deletedByDate as $assignment) {
            $date = Carbon::parse($assignment->assigned_date);
            $weekStart = $date->copy()->startOfWeek(Carbon::MONDAY);
            $weekKey = $weekStart->format('Y-m-d');
            
            if (!isset($deletedByWeek[$weekKey])) {
                $deletedByWeek[$weekKey] = [
                    'week_start' => $weekStart,
                    'count' => 0
                ];
            }
            $deletedByWeek[$weekKey]['count'] += $assignment->count;
        }
        
        foreach ($deletedByWeek as $weekKey => $weekData) {
            $weekStart = $weekData['week_start'];
            $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SUNDAY);
            $weekNumber = $weekStart->weekOfYear;
            
            $this->line("Week {$weekNumber} ({$weekStart->format('M j')} - {$weekEnd->format('M j, Y')}): {$weekData['count']} deleted assignments");
        }
        
        // Show details of deleted assignments
        $this->line('');
        $this->info('🔍 DETAILED BREAKDOWN OF DELETED ASSIGNMENTS:');
        $this->line(str_repeat('-', 50));
        
        $deletedAssignments = StaffShiftAssignment::onlyTrashed()
            ->with(['staff', 'shift'])
            ->orderBy('assigned_date')
            ->get();
        
        foreach ($deletedAssignments as $assignment) {
            $staffName = $assignment->staff->first_name . ' ' . $assignment->staff->last_name;
            $shiftName = $assignment->shift->name;
            $date = Carbon::parse($assignment->assigned_date)->format('M j, Y');
            $deletedAt = $assignment->deleted_at->format('M j, Y H:i');
            
            $this->line("• {$date}: {$staffName} → {$shiftName} (deleted on {$deletedAt})");
        }
        
        $this->line('');
        $this->info('💡 EXPLANATION:');
        $this->line('• These are assignments from previous weeks that you cleared');
        $this->line('• They are "soft-deleted" - not permanently removed');
        $this->line('• This preserves data for reporting and audit purposes');
        $this->line('• The weekly schedule system helps manage this better');
        
        $this->line('');
        $this->info('🚀 BENEFITS OF WEEKLY SCHEDULE MANAGEMENT:');
        $this->line('• Archive entire weeks instead of deleting individual assignments');
        $this->line('• Keep historical data for reporting');
        $this->line('• Easy cleanup of very old data when needed');
        $this->line('• Better performance with weekly summaries');
        
        return 0;
    }
}