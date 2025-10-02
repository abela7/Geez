<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Enhance staff_tasks table
        Schema::table('staff_tasks', function (Blueprint $table) {
            // Add scheduling fields
            $table->date('scheduled_date')->nullable()->after('estimated_hours')->comment('Default date when task should be performed');
            $table->time('scheduled_time')->nullable()->after('scheduled_date')->comment('Default time when task should be performed');
            $table->integer('duration_minutes')->nullable()->after('scheduled_time')->comment('Expected duration in minutes');

            // Add notes field for task instructions
            $table->text('instructions')->nullable()->after('description')->comment('Detailed instructions for completing the task');

            // Add assignment settings
            $table->boolean('auto_assign')->default(false)->after('requires_approval')->comment('Whether to auto-assign to staff');
            $table->json('default_assignees')->nullable()->after('auto_assign')->comment('Default staff members to assign this task to');

            // Add recurrence settings for better scheduling
            $table->enum('recurrence_type', ['none', 'daily', 'weekly', 'monthly', 'custom'])->default('none')->after('recurrence_pattern');
            $table->json('recurrence_config')->nullable()->after('recurrence_type')->comment('Detailed recurrence configuration');

            // Add indexes for performance
            $table->index(['scheduled_date', 'scheduled_time'], 'idx_task_schedule');
            $table->index('auto_assign', 'idx_auto_assign');
        });

        // 2. Enhance staff_task_assignments table
        Schema::table('staff_task_assignments', function (Blueprint $table) {
            // Add specific scheduling for this assignment
            $table->date('scheduled_date')->nullable()->after('due_date')->comment('Specific date this assignment should be completed');
            $table->time('scheduled_time')->nullable()->after('scheduled_date')->comment('Specific time this assignment should be completed');
            $table->dateTime('scheduled_datetime')->nullable()->after('scheduled_time')->comment('Combined scheduled date and time');

            // Enhanced notes system
            $table->text('assignment_notes')->nullable()->after('notes')->comment('Notes specific to this assignment');
            $table->text('completion_notes')->nullable()->after('assignment_notes')->comment('Notes added when task is completed');

            // Time tracking enhancements
            $table->dateTime('actual_start_time')->nullable()->after('started_at')->comment('Actual time work started');
            $table->dateTime('actual_end_time')->nullable()->after('completed_at')->comment('Actual time work ended');
            $table->integer('break_minutes')->default(0)->after('actual_hours')->comment('Break time taken during task');

            // Status tracking enhancements
            $table->boolean('is_overdue')->default(false)->after('status')->comment('Whether this assignment is overdue');
            $table->dateTime('overdue_since')->nullable()->after('is_overdue')->comment('When this assignment became overdue');
            $table->enum('urgency_level', ['low', 'normal', 'high', 'critical'])->default('normal')->after('priority_override');

            // Notification and reminder system
            $table->json('reminder_schedule')->nullable()->after('reminder_sent_at')->comment('Schedule for sending reminders');
            $table->dateTime('last_reminder_sent')->nullable()->after('reminder_schedule')->comment('When last reminder was sent');
            $table->integer('reminder_count')->default(0)->after('last_reminder_sent')->comment('Number of reminders sent');

            // Add indexes for performance
            $table->index(['scheduled_date', 'scheduled_time'], 'idx_assignment_schedule');
            $table->index(['is_overdue', 'status'], 'idx_overdue_status');
            $table->index('urgency_level', 'idx_urgency');
            $table->index('scheduled_datetime', 'idx_scheduled_datetime');
        });

        // 3. Create task_notes table for detailed note tracking
        Schema::create('task_notes', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('staff_task_id')->constrained('staff_tasks')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUlid('staff_task_assignment_id')->nullable()->constrained('staff_task_assignments')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUlid('staff_id')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();

            $table->enum('note_type', ['instruction', 'progress', 'issue', 'completion', 'general'])->default('general');
            $table->text('content')->comment('Note content');
            $table->boolean('is_private')->default(false)->comment('Whether note is private to creator');
            $table->boolean('is_important')->default(false)->comment('Whether note is marked as important');

            $table->timestamps();

            // Indexes
            $table->index(['staff_task_id', 'created_at'], 'idx_task_notes_timeline');
            $table->index(['staff_task_assignment_id', 'created_at'], 'idx_assignment_notes_timeline');
            $table->index('note_type', 'idx_note_type');
            $table->index('is_important', 'idx_important_notes');
        });

        // 4. Create task_time_entries table for detailed time tracking
        Schema::create('task_time_entries', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('staff_task_assignment_id')->constrained('staff_task_assignments')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUlid('staff_id')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();

            $table->dateTime('start_time')->comment('When work started');
            $table->dateTime('end_time')->nullable()->comment('When work ended');
            $table->integer('duration_minutes')->nullable()->comment('Duration in minutes');
            $table->text('description')->nullable()->comment('Description of work done');
            $table->enum('entry_type', ['work', 'break', 'interruption'])->default('work');

            $table->timestamps();

            // Indexes
            $table->index(['staff_task_assignment_id', 'start_time'], 'idx_time_entries_timeline');
            $table->index(['staff_id', 'start_time'], 'idx_staff_time_entries');
            $table->index('entry_type', 'idx_entry_type');
        });

        // 5. Create task_reminders table for automated reminders
        Schema::create('task_reminders', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('staff_task_assignment_id')->constrained('staff_task_assignments')->cascadeOnUpdate()->cascadeOnDelete();

            $table->enum('reminder_type', ['due_soon', 'overdue', 'scheduled', 'custom'])->default('due_soon');
            $table->dateTime('scheduled_for')->comment('When reminder should be sent');
            $table->dateTime('sent_at')->nullable()->comment('When reminder was actually sent');
            $table->enum('status', ['pending', 'sent', 'failed', 'cancelled'])->default('pending');
            $table->text('message')->nullable()->comment('Custom reminder message');
            $table->json('delivery_methods')->nullable()->comment('How reminder should be delivered (email, notification, etc.)');

            $table->timestamps();

            // Indexes
            $table->index(['scheduled_for', 'status'], 'idx_reminder_schedule');
            $table->index('reminder_type', 'idx_reminder_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop new tables first
        Schema::dropIfExists('task_reminders');
        Schema::dropIfExists('task_time_entries');
        Schema::dropIfExists('task_notes');

        // Remove columns from existing tables
        Schema::table('staff_task_assignments', function (Blueprint $table) {
            $table->dropIndex('idx_assignment_schedule');
            $table->dropIndex('idx_overdue_status');
            $table->dropIndex('idx_urgency');
            $table->dropIndex('idx_scheduled_datetime');

            $table->dropColumn([
                'scheduled_date',
                'scheduled_time',
                'scheduled_datetime',
                'assignment_notes',
                'completion_notes',
                'actual_start_time',
                'actual_end_time',
                'break_minutes',
                'is_overdue',
                'overdue_since',
                'urgency_level',
                'reminder_schedule',
                'last_reminder_sent',
                'reminder_count',
            ]);
        });

        Schema::table('staff_tasks', function (Blueprint $table) {
            $table->dropIndex('idx_task_schedule');
            $table->dropIndex('idx_auto_assign');

            $table->dropColumn([
                'scheduled_date',
                'scheduled_time',
                'duration_minutes',
                'instructions',
                'auto_assign',
                'default_assignees',
                'recurrence_type',
                'recurrence_config',
            ]);
        });
    }
};
