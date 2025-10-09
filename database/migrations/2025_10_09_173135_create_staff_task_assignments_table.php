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
        Schema::create('staff_task_assignments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            // Foreign keys
            $table->foreignUlid('staff_task_id')->constrained('staff_tasks')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUlid('staff_id')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            
            // Assignment dates and scheduling
            $table->date('assigned_date');
            $table->date('due_date')->nullable();
            $table->date('scheduled_date')->nullable()->comment('Specific date this assignment should be completed');
            $table->time('scheduled_time')->nullable()->comment('Specific time this assignment should be completed');
            $table->dateTime('scheduled_datetime')->nullable()->comment('Combined scheduled date and time');
            
            // Status and tracking
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled', 'overdue'])->default('pending');
            $table->boolean('is_overdue')->default(false);
            $table->dateTime('overdue_since')->nullable();
            $table->enum('urgency_level', ['low', 'normal', 'high', 'critical'])->default('normal');
            
            // Time tracking
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('actual_start_time')->nullable();
            $table->dateTime('actual_end_time')->nullable();
            
            // Progress and effort
            $table->integer('progress_percentage')->default(0);
            $table->decimal('estimated_hours', 5, 2)->nullable();
            $table->decimal('actual_hours', 5, 2)->nullable();
            $table->integer('break_minutes')->default(0);
            
            // Notes and communication
            $table->text('notes')->nullable();
            $table->text('assignment_notes')->nullable()->comment('Notes specific to this assignment');
            $table->text('completion_notes')->nullable()->comment('Notes added when task is completed');
            
            // Priority and reminders
            $table->string('priority_override')->nullable();
            $table->dateTime('reminder_sent_at')->nullable();
            $table->json('reminder_schedule')->nullable();
            $table->dateTime('last_reminder_sent')->nullable();
            $table->integer('reminder_count')->default(0);
            
            // Audit fields
            $table->foreignUlid('assigned_by')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('completed_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('updated_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for performance
            $table->index(['staff_id', 'status']);
            $table->index(['staff_task_id', 'status']);
            $table->index(['assigned_date', 'due_date']);
            $table->index(['scheduled_date', 'scheduled_time']);
            $table->index(['status', 'is_overdue']);
            $table->index('assigned_by');
            
            // Unique constraint to prevent duplicate assignments
            $table->unique(['staff_task_id', 'staff_id', 'assigned_date'], 'unique_task_staff_assignment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_task_assignments');
    }
};