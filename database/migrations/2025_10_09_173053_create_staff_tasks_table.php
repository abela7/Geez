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
        Schema::create('staff_tasks', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            // Basic task information
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('instructions')->nullable()->comment('Detailed instructions for completing the task');
            
            // Task classification
            $table->string('task_type')->nullable();
            $table->string('priority')->nullable();
            $table->string('category')->nullable();
            
            // Time estimation and scheduling
            $table->decimal('estimated_hours', 5, 2)->nullable();
            $table->integer('duration_minutes')->nullable()->comment('Expected duration in minutes');
            $table->date('scheduled_date')->nullable()->comment('Default date when task should be performed');
            $table->time('scheduled_time')->nullable()->comment('Default time when task should be performed');
            
            // Template settings
            $table->boolean('is_template')->default(false);
            $table->string('template_name')->nullable();
            
            // Recurrence settings
            $table->string('recurrence_pattern')->nullable();
            $table->integer('recurrence_interval')->nullable();
            $table->date('recurrence_end_date')->nullable();
            $table->enum('recurrence_type', ['none', 'daily', 'weekly', 'monthly', 'custom'])->default('none');
            $table->json('recurrence_config')->nullable()->comment('Detailed recurrence configuration');
            
            // Approval workflow
            $table->boolean('requires_approval')->default(false);
            $table->json('approval_workflow')->nullable();
            
            // Assignment settings
            $table->boolean('auto_assign')->default(false)->comment('Whether to auto-assign to staff');
            $table->json('default_assignees')->nullable()->comment('Default staff members to assign this task to');
            
            // Tags and metadata
            $table->json('tags')->nullable();
            
            // Status and audit fields
            $table->boolean('is_active')->default(true);
            $table->foreignUlid('created_by')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('updated_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for performance
            $table->index(['is_active', 'task_type']);
            $table->index(['scheduled_date', 'scheduled_time'], 'idx_task_schedule');
            $table->index('auto_assign', 'idx_auto_assign');
            $table->index(['is_template', 'is_active']);
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_tasks');
    }
};
