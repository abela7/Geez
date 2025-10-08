<?php

namespace App\Console\Commands;

use App\Models\StaffShiftAssignment;
use App\Models\WeeklySchedule;
use App\Models\WeeklyScheduleAssignment;
use Illuminate\Console\Command;

class ClearAssignments extends Command
{
    protected $signature = 'assignments:clear-all {--confirm : Skip confirmation prompt}';
    protected $description = 'Clear all assignments and weekly schedules to start fresh';

    public function handle()
    {
        $this->info('🧹 CLEARING ALL ASSIGNMENTS AND WEEKLY SCHEDULES');
        $this->line(str_repeat('=', 60));
        
        // Show current counts
        $totalAssignments = StaffShiftAssignment::withTrashed()->count();
        $activeAssignments = StaffShiftAssignment::count();
        $deletedAssignments = StaffShiftAssignment::onlyTrashed()->count();
        $weeklySchedules = WeeklySchedule::count();
        $weeklyScheduleAssignments = WeeklyScheduleAssignment::count();
        
        $this->info('Current Data:');
        $this->line("📋 Total assignments (including deleted): {$totalAssignments}");
        $this->line("📋 Active assignments: {$activeAssignments}");
        $this->line("🗑️ Deleted assignments: {$deletedAssignments}");
        $this->line("📅 Weekly schedules: {$weeklySchedules}");
        $this->line("🔗 Weekly schedule assignments: {$weeklyScheduleAssignments}");
        
        if ($totalAssignments === 0 && $weeklySchedules === 0) {
            $this->info('✅ Database is already clean!');
            return 0;
        }
        
        // Confirmation
        if (!$this->option('confirm')) {
            $this->warn('⚠️  WARNING: This will permanently delete ALL assignment data!');
            $this->line('This includes:');
            $this->line('• All staff shift assignments (active and deleted)');
            $this->line('• All weekly schedules');
            $this->line('• All weekly schedule assignment links');
            $this->line('');
            
            if (!$this->confirm('Are you sure you want to proceed?')) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }
        
        $this->line('');
        $this->info('🗑️ Clearing data...');
        
        // Clear weekly schedule assignments first (foreign key constraints)
        if ($weeklyScheduleAssignments > 0) {
            $this->line('Clearing weekly schedule assignments...');
            WeeklyScheduleAssignment::query()->delete();
        }
        
        // Clear weekly schedules
        if ($weeklySchedules > 0) {
            $this->line('Clearing weekly schedules...');
            WeeklySchedule::query()->delete();
        }
        
        // Clear all assignments (including soft-deleted)
        if ($totalAssignments > 0) {
            $this->line('Clearing all staff shift assignments...');
            StaffShiftAssignment::withTrashed()->forceDelete();
        }
        
        $this->line('');
        $this->info('✅ Database cleared successfully!');
        
        // Verify cleanup
        $remainingAssignments = StaffShiftAssignment::withTrashed()->count();
        $remainingSchedules = WeeklySchedule::count();
        $remainingScheduleAssignments = WeeklyScheduleAssignment::count();
        
        $this->line('');
        $this->info('📊 Verification:');
        $this->line("📋 Remaining assignments: {$remainingAssignments}");
        $this->line("📅 Remaining weekly schedules: {$remainingSchedules}");
        $this->line("🔗 Remaining weekly schedule assignments: {$remainingScheduleAssignments}");
        
        if ($remainingAssignments === 0 && $remainingSchedules === 0 && $remainingScheduleAssignments === 0) {
            $this->info('🎉 Database is now completely clean!');
            $this->line('');
            $this->info('🚀 Ready to test the new weekly schedule system:');
            $this->line('1. Go to /admin/shifts/assignments');
            $this->line('2. Create new assignments for the week');
            $this->line('3. Watch the weekly schedule system automatically create summaries');
            $this->line('4. Use php artisan shifts:weekly-schedules stats to see the magic!');
        } else {
            $this->error('❌ Some data remains. Please check manually.');
        }
        
        return 0;
    }
}