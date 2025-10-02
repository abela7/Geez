<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\StaffTask;
use App\Models\StaffTaskAssignment;
use Illuminate\Console\Command;

class CleanupOrphanedTasks extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'tasks:cleanup-orphaned';

    /**
     * The console command description.
     */
    protected $description = 'Clean up orphaned tasks and task assignments';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting cleanup of orphaned tasks and assignments...');

        // Clean up tasks without assignments
        $orphanedTasksCount = StaffTask::whereDoesntHave('assignments')->count();
        if ($orphanedTasksCount > 0) {
            StaffTask::whereDoesntHave('assignments')->delete();
            $this->info("Deleted {$orphanedTasksCount} orphaned task(s).");
        }

        // Clean up assignments without tasks
        $orphanedAssignmentsCount = StaffTaskAssignment::whereDoesntHave('task')->count();
        if ($orphanedAssignmentsCount > 0) {
            StaffTaskAssignment::whereDoesntHave('task')->delete();
            $this->info("Deleted {$orphanedAssignmentsCount} orphaned assignment(s).");
        }

        if ($orphanedTasksCount === 0 && $orphanedAssignmentsCount === 0) {
            $this->info('No orphaned records found. Database is clean!');
        } else {
            $total = $orphanedTasksCount + $orphanedAssignmentsCount;
            $this->info("Cleanup completed! Removed {$total} orphaned record(s) total.");
        }

        return Command::SUCCESS;
    }
}
