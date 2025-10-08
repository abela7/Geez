<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\WeeklySchedule;
use App\Services\WeeklyScheduleService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ManageWeeklySchedules extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'shifts:weekly-schedules 
                            {action : The action to perform (create, archive, stats, cleanup)}
                            {--weeks-ahead=4 : Number of weeks ahead to create schedules for}
                            {--archive-older-than=12 : Archive schedules older than X weeks}
                            {--delete-older-than=52 : Delete archived schedules older than X weeks}
                            {--date= : Specific date to work with (YYYY-MM-DD)}';

    /**
     * The console command description.
     */
    protected $description = 'Manage weekly schedules (create upcoming, archive old, cleanup, statistics)';

    public function __construct(
        private WeeklyScheduleService $weeklyScheduleService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $action = $this->argument('action');

        return match ($action) {
            'create' => $this->createUpcomingSchedules(),
            'archive' => $this->archiveOldSchedules(),
            'stats' => $this->showStatistics(),
            'cleanup' => $this->cleanupOldSchedules(),
            default => $this->error("Unknown action: {$action}. Available actions: create, archive, stats, cleanup"),
        };
    }

    /**
     * Create upcoming weekly schedules.
     */
    private function createUpcomingSchedules(): int
    {
        $weeksAhead = (int) $this->option('weeks-ahead');
        
        $this->info("Creating weekly schedules for the next {$weeksAhead} weeks...");

        try {
            $created = $this->weeklyScheduleService->autoCreateUpcomingSchedules($weeksAhead);
            
            if (empty($created)) {
                $this->info('No new weekly schedules needed to be created.');
            } else {
                $this->info('Created ' . count($created) . ' weekly schedules:');
                foreach ($created as $schedule) {
                    $this->line("  - Week of {$schedule->week_start_date->format('M j, Y')} (Status: {$schedule->status})");
                }
            }
            
            return 0;
        } catch (\Exception $e) {
            $this->error('Error creating weekly schedules: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Archive old weekly schedules.
     */
    private function archiveOldSchedules(): int
    {
        $weeksOld = (int) $this->option('archive-older-than');
        $cutoffDate = now()->subWeeks($weeksOld);
        
        $this->info("Archiving weekly schedules older than {$weeksOld} weeks (before {$cutoffDate->format('M j, Y')})...");

        $schedulesToArchive = WeeklySchedule::where('week_end_date', '<', $cutoffDate)
            ->whereIn('status', ['completed', 'published'])
            ->get();

        if ($schedulesToArchive->isEmpty()) {
            $this->info('No schedules found that need archiving.');
            return 0;
        }

        $count = 0;
        foreach ($schedulesToArchive as $schedule) {
            try {
                $this->weeklyScheduleService->archiveSchedule($schedule);
                $this->line("  - Archived: Week of {$schedule->week_start_date->format('M j, Y')}");
                $count++;
            } catch (\Exception $e) {
                $this->error("  - Failed to archive week of {$schedule->week_start_date->format('M j, Y')}: {$e->getMessage()}");
            }
        }

        $this->info("Successfully archived {$count} weekly schedules.");
        return 0;
    }

    /**
     * Show weekly schedule statistics.
     */
    private function showStatistics(): int
    {
        $startDate = $this->option('date') 
            ? Carbon::parse($this->option('date'))->subMonths(3)
            : now()->subMonths(3);
        $endDate = now();

        $this->info("Weekly Schedule Statistics ({$startDate->format('M j, Y')} - {$endDate->format('M j, Y')})");
        $this->line(str_repeat('=', 70));

        try {
            $stats = $this->weeklyScheduleService->getStatistics($startDate, $endDate);

            $this->table(['Metric', 'Value'], [
                ['Total Weeks Managed', $stats['total_weeks_managed']],
                ['Total Shifts', number_format($stats['total_shifts'])],
                ['Total Hours', number_format($stats['total_hours'], 1)],
                ['Total Labor Cost', '$' . number_format($stats['total_labor_cost'], 2)],
                ['Average Shifts per Week', $stats['average_shifts_per_week']],
                ['Average Hours per Week', number_format($stats['average_hours_per_week'], 1)],
                ['Average Labor Cost per Week', '$' . number_format($stats['average_labor_cost_per_week'], 2)],
                ['Most Used Template', $stats['most_used_template'] ? $stats['most_used_template']->name : 'None'],
                ['Most Used Template Count', $stats['most_used_template_count']],
            ]);

            // Show status breakdown
            $this->line('');
            $this->info('Schedule Status Breakdown:');
            $statusCounts = WeeklySchedule::inDateRange($startDate, $endDate)
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();

            foreach (['draft', 'published', 'active', 'completed', 'archived'] as $status) {
                $count = $statusCounts[$status] ?? 0;
                $this->line("  {$status}: {$count}");
            }

            return 0;
        } catch (\Exception $e) {
            $this->error('Error generating statistics: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Cleanup very old archived schedules.
     */
    private function cleanupOldSchedules(): int
    {
        $weeksOld = (int) $this->option('delete-older-than');
        $cutoffDate = now()->subWeeks($weeksOld);
        
        $this->info("Cleaning up archived weekly schedules older than {$weeksOld} weeks (before {$cutoffDate->format('M j, Y')})...");
        
        if (!$this->confirm('This will permanently delete old weekly schedules and their assignment links. Continue?')) {
            $this->info('Cleanup cancelled.');
            return 0;
        }

        $schedulesToDelete = WeeklySchedule::where('week_end_date', '<', $cutoffDate)
            ->where('status', 'archived')
            ->get();

        if ($schedulesToDelete->isEmpty()) {
            $this->info('No archived schedules found that need cleanup.');
            return 0;
        }

        $count = 0;
        foreach ($schedulesToDelete as $schedule) {
            try {
                // Delete assignment links first (cascade should handle this, but being explicit)
                $schedule->assignments()->delete();
                
                // Delete the schedule
                $schedule->forceDelete();
                
                $this->line("  - Deleted: Week of {$schedule->week_start_date->format('M j, Y')}");
                $count++;
            } catch (\Exception $e) {
                $this->error("  - Failed to delete week of {$schedule->week_start_date->format('M j, Y')}: {$e->getMessage()}");
            }
        }

        $this->info("Successfully deleted {$count} archived weekly schedules.");
        return 0;
    }
}
