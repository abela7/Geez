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
        Schema::table('staff_tasks', function (Blueprint $table) {
            // Task categorization and organization
            $table->string('category', 50)->default('general')->comment('Task category (kitchen, service, admin, maintenance)');
            $table->decimal('estimated_hours', 12, 2)->nullable()->comment('Estimated time to complete');
            
            // Template system
            $table->boolean('is_template')->default(false)->comment('Whether this is a reusable template');
            $table->string('template_name')->nullable()->comment('Template name for reusable tasks');
            
            // Recurrence system
            $table->string('recurrence_pattern', 20)->default('none')->comment('none, daily, weekly, monthly');
            $table->integer('recurrence_interval')->default(1)->comment('Every N days/weeks/months');
            $table->date('recurrence_end_date')->nullable()->comment('When to stop recurring');
            
            // Approval workflow
            $table->boolean('requires_approval')->default(false)->comment('Whether completion requires approval');
            $table->json('approval_workflow')->nullable()->comment('Approval workflow configuration');
            
            // Organization and metadata
            $table->json('tags')->nullable()->comment('Task tags for better organization');
            
            // Audit fields
            $table->char('updated_by', 26)->nullable()->comment('Last updated by staff member');
            
            // Soft deletes (if not already present)
            if (!Schema::hasColumn('staff_tasks', 'deleted_at')) {
                $table->softDeletes();
            }
            
            // Foreign key constraints
            $table->foreign('updated_by', 'fk_staff_tasks_updated_by')
                  ->references('id')->on('staff')
                  ->cascadeOnUpdate()->restrictOnDelete();
            
            // Indexes for performance
            $table->index('category', 'idx_staff_tasks_category');
            $table->index('is_template', 'idx_staff_tasks_template');
            $table->index('recurrence_pattern', 'idx_staff_tasks_recurrence');
            $table->index('updated_by', 'idx_staff_tasks_updated_by');
            $table->index('deleted_at', 'idx_staff_tasks_deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_tasks', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign('fk_staff_tasks_updated_by');
            
            // Drop indexes
            $table->dropIndex('idx_staff_tasks_category');
            $table->dropIndex('idx_staff_tasks_template');
            $table->dropIndex('idx_staff_tasks_recurrence');
            $table->dropIndex('idx_staff_tasks_updated_by');
            $table->dropIndex('idx_staff_tasks_deleted_at');
            
            // Drop columns
            $table->dropColumn([
                'category',
                'estimated_hours',
                'is_template',
                'template_name',
                'recurrence_pattern',
                'recurrence_interval',
                'recurrence_end_date',
                'requires_approval',
                'approval_workflow',
                'tags',
                'updated_by',
            ]);
            
            // Note: We don't drop deleted_at as it might be used elsewhere
        });
    }
};