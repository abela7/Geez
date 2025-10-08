<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\ShiftsOverviewController;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class TestOverviewController extends Command
{
    protected $signature = 'test:overview-controller';
    protected $description = 'Test the overview controller to see if it works with real data';

    public function handle()
    {
        $this->info('🧪 Testing Overview Controller');
        $this->line(str_repeat('=', 50));
        
        try {
            $controller = new ShiftsOverviewController();
            $request = new Request();
            
            // Test with current week
            $this->line('Testing with current week...');
            $view = $controller->index($request);
            
            $this->info('✅ Controller executed successfully!');
            
            // Get the view data
            $viewData = $view->getData();
            
            $this->line('');
            $this->info('📊 View Data Summary:');
            $this->line("Week Start: {$viewData['weekStart']->format('Y-m-d')}");
            $this->line("Total Shifts: {$viewData['shiftSummary']['total_shifts']}");
            $this->line("Total Staff Scheduled: {$viewData['shiftSummary']['total_staff_scheduled']}");
            $this->line("Total Hours: {$viewData['shiftSummary']['total_hours']}");
            $this->line("Coverage Gaps: {$viewData['shiftSummary']['coverage_gaps']}");
            $this->line("Weekly Schedule Days: " . count($viewData['weeklyScheduleData']));
            
            // Show sample of weekly schedule data
            $this->line('');
            $this->info('📅 Sample Weekly Schedule Data:');
            foreach ($viewData['weeklyScheduleData'] as $day) {
                $this->line("{$day['day_short']} ({$day['date']->format('M j')}): {$day['total_shifts']} shifts, {$day['total_staff']} staff");
                
                if (!empty($day['shifts'])) {
                    foreach ($day['shifts'] as $shift) {
                        $this->line("  • {$shift['name']}: {$shift['assigned_staff_count']}/{$shift['required_staff']} staff ({$shift['status']})");
                    }
                }
            }
            
            $this->line('');
            $this->info('🎉 Overview controller is working with real data!');
            $this->line('You can now visit /admin/shifts/overview to see the updated page.');
            
        } catch (\Exception $e) {
            $this->error('❌ Controller failed: ' . $e->getMessage());
            $this->line('Stack trace:');
            $this->line($e->getTraceAsString());
            return 1;
        }
        
        return 0;
    }
}