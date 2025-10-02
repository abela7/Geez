<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\StaffTaskAssignment;
use Illuminate\Console\Command;

class CheckOverdueTasks extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'tasks:check-overdue {--send-notifications : Send overdue notifications}';

    /**
     * The console command description.
     */
    protected $description = 'Check for overdue task assignments and update their status';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking for overdue task assignments...');

        // Get all assignments that might be overdue
        $assignments = StaffTaskAssignment::with(['task', 'staff'])
            ->whereIn('status', ['pending', 'in_progress'])
            ->where(function ($query) {
                $query->where('scheduled_datetime', '<', now())
                    ->orWhere(function ($q) {
                        $q->whereNull('scheduled_datetime')
                            ->where('due_date', '<', now()->toDateString());
                    });
            })
            ->get();

        $overdueCount = 0;
        $notificationsSent = 0;

        foreach ($assignments as $assignment) {
            $wasOverdue = $assignment->is_overdue;
            $isNowOverdue = $assignment->checkAndUpdateOverdueStatus();

            if ($isNowOverdue && ! $wasOverdue) {
                $overdueCount++;
                $this->line("Task '{$assignment->task->title}' assigned to {$assignment->staff->first_name} {$assignment->staff->last_name} is now overdue.");

                // Send notification if requested
                if ($this->option('send-notifications')) {
                    try {
                        $assignment->sendOverdueNotification();
                        $notificationsSent++;
                        $this->line('  → Overdue notification sent.');
                    } catch (\Exception $e) {
                        $this->error("  → Failed to send notification: {$e->getMessage()}");
                    }
                }
            }
        }

        $this->info("Processed {$assignments->count()} assignments.");
        $this->info("Found {$overdueCount} newly overdue assignments.");

        if ($this->option('send-notifications')) {
            $this->info("Sent {$notificationsSent} overdue notifications.");
        }

        return Command::SUCCESS;
    }
}
