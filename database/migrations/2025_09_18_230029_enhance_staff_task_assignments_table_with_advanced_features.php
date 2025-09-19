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
        Schema::table('staff_task_assignments', function (Blueprint $table) {
            // Progress tracking
            if (!Schema::hasColumn('staff_task_assignments', 'progress_percentage')) {
                $table->integer('progress_percentage')->default(0)->comment('Task completion percentage (0-100)');
            }
            
            // Time estimation and tracking
            if (!Schema::hasColumn('staff_task_assignments', 'estimated_hours')) {
                $table->decimal('estimated_hours', 12, 2)->nullable()->comment('Estimated hours for this assignment');
            }
            if (!Schema::hasColumn('staff_task_assignments', 'actual_hours')) {
                $table->decimal('actual_hours', 12, 2)->nullable()->comment('Actual hours worked');
            }
            
            // Priority override system
            if (!Schema::hasColumn('staff_task_assignments', 'priority_override')) {
                $table->string('priority_override', 20)->nullable()->comment('Override task priority: low, medium, high, urgent');
            }
            
            // Notification system
            if (!Schema::hasColumn('staff_task_assignments', 'reminder_sent_at')) {
                $table->timestamp('reminder_sent_at')->nullable()->comment('When reminder was last sent');
            }
            
            // Audit fields (check if not already exists)
            if (!Schema::hasColumn('staff_task_assignments', 'updated_by')) {
                $table->char('updated_by', 26)->nullable()->comment('Last updated by staff member');
                
                // Foreign key constraints
                $table->foreign('updated_by', 'fk_staff_task_assignments_updated_by')
                      ->references('id')->on('staff')
                      ->cascadeOnUpdate()->restrictOnDelete();
            }
            
            // Soft deletes (if not already present)
            if (!Schema::hasColumn('staff_task_assignments', 'deleted_at')) {
                $table->softDeletes();
            }
        });
        
        // Add indexes separately to avoid conflicts
        Schema::table('staff_task_assignments', function (Blueprint $table) {
            if (Schema::hasColumn('staff_task_assignments', 'progress_percentage')) {
                try {
                    $table->index('progress_percentage', 'idx_task_assignments_progress');
                } catch (\Exception $e) {
                    // Index might already exist
                }
            }
            if (Schema::hasColumn('staff_task_assignments', 'priority_override')) {
                try {
                    $table->index('priority_override', 'idx_task_assignments_priority');
                } catch (\Exception $e) {
                    // Index might already exist
                }
            }
            if (Schema::hasColumn('staff_task_assignments', 'updated_by')) {
                try {
                    $table->index('updated_by', 'idx_task_assignments_updated_by');
                } catch (\Exception $e) {
                    // Index might already exist
                }
            }
            if (Schema::hasColumn('staff_task_assignments', 'deleted_at')) {
                try {
                    $table->index('deleted_at', 'idx_task_assignments_deleted_at');
                } catch (\Exception $e) {
                    // Index might already exist
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_task_assignments', function (Blueprint $table) {
            // Drop foreign keys first (only if we added them)
            if (Schema::hasColumn('staff_task_assignments', 'updated_by')) {
                try {
                    $table->dropForeign('fk_staff_task_assignments_updated_by');
                } catch (\Exception $e) {
                    // Foreign key might not exist or have different name
                }
            }
            
            // Drop indexes
            $table->dropIndex('idx_task_assignments_progress');
            $table->dropIndex('idx_task_assignments_priority');
            $table->dropIndex('idx_task_assignments_updated_by');
            $table->dropIndex('idx_task_assignments_deleted_at');
            
            // Drop columns (except updated_by as it might be managed elsewhere)
            $table->dropColumn([
                'progress_percentage',
                'estimated_hours',
                'actual_hours',
                'priority_override',
                'reminder_sent_at',
            ]);
            
            // Note: We don't drop updated_by or deleted_at as they might be used elsewhere
        });
    }
};