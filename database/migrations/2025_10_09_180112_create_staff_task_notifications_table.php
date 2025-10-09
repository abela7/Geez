<?php

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
        Schema::create('staff_task_notifications', function (Blueprint $table) {
            $table->ulid('id')->primary();

            // Foreign keys
            $table->foreignUlid('task_assignment_id')->constrained('staff_task_assignments')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUlid('staff_id')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();

            // Notification content
            $table->enum('notification_type', [
                'task_assigned',
                'task_updated',
                'task_completed',
                'due_date_approaching',
                'overdue_task',
                'status_changed',
                'comment_added',
                'attachment_added'
            ]);
            $table->string('title');
            $table->text('message');

            // Read status
            $table->boolean('is_read')->default(false);
            $table->dateTime('sent_at');
            $table->dateTime('read_at')->nullable();

            // Audit fields
            $table->foreignUlid('created_by')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('updated_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index(['task_assignment_id', 'sent_at']);
            $table->index(['staff_id', 'sent_at']);
            $table->index(['staff_id', 'is_read']);
            $table->index('notification_type');
            $table->index('sent_at');
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_task_notifications');
    }
};
