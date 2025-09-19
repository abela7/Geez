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
        Schema::create('staff_task_time_entries', function (Blueprint $table) {
            // Primary key
            $table->char('id', 26)->primary()->comment('ULID primary key');
            
            // Task assignment relationship
            $table->char('task_assignment_id', 26)->comment('FK -> staff_task_assignments.id');
            $table->char('staff_id', 26)->comment('Staff member who logged the time');
            
            // Time tracking
            $table->timestamp('start_time')->comment('Time entry start');
            $table->timestamp('end_time')->nullable()->comment('Time entry end');
            $table->integer('duration_minutes')->nullable()->comment('Cached duration in minutes');
            $table->text('description')->nullable()->comment('What was worked on');
            $table->boolean('is_billable')->default(true)->comment('Whether time is billable');
            
            // Audit fields
            $table->char('created_by', 26)->comment('Staff member who created entry');
            $table->char('updated_by', 26)->nullable()->comment('Staff member who last updated');
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for performance
            $table->index('task_assignment_id', 'idx_time_entries_assignment_id');
            $table->index('staff_id', 'idx_time_entries_staff_id');
            $table->index('start_time', 'idx_time_entries_start_time');
            $table->index('is_billable', 'idx_time_entries_billable');
            $table->index('created_by', 'idx_time_entries_created_by');
            $table->index('deleted_at', 'idx_time_entries_deleted_at');
            
            // Foreign key constraints (CASCADE update, RESTRICT delete)
            $table->foreign('task_assignment_id', 'fk_time_entries_assignment_id')
                  ->references('id')->on('staff_task_assignments')
                  ->cascadeOnUpdate()->restrictOnDelete();
                  
            $table->foreign('staff_id', 'fk_time_entries_staff_id')
                  ->references('id')->on('staff')
                  ->cascadeOnUpdate()->restrictOnDelete();
                  
            $table->foreign('created_by', 'fk_time_entries_created_by')
                  ->references('id')->on('staff')
                  ->cascadeOnUpdate()->restrictOnDelete();
                  
            $table->foreign('updated_by', 'fk_time_entries_updated_by')
                  ->references('id')->on('staff')
                  ->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_task_time_entries');
    }
};