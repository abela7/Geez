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
        Schema::create('staff_task_dependencies', function (Blueprint $table) {
            // Primary key
            $table->char('id', 26)->primary()->comment('ULID primary key');
            
            // Task relationships
            $table->char('task_id', 26)->comment('Task that depends on another');
            $table->char('depends_on_task_id', 26)->comment('Task that must be completed first');
            
            // Dependency configuration
            $table->string('dependency_type', 30)->default('finish_to_start')
                  ->comment('finish_to_start, start_to_start, finish_to_finish, start_to_finish');
            $table->integer('lag_days')->default(0)->comment('Days to wait after dependency completes');
            
            // Audit fields
            $table->char('created_by', 26)->comment('Staff member who created dependency');
            $table->char('updated_by', 26)->nullable()->comment('Staff member who last updated');
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign key constraints
            $table->foreign('task_id', 'fk_task_deps_task_id')
                  ->references('id')->on('staff_tasks')
                  ->cascadeOnUpdate()->restrictOnDelete();
                  
            $table->foreign('depends_on_task_id', 'fk_task_deps_depends_on_id')
                  ->references('id')->on('staff_tasks')
                  ->cascadeOnUpdate()->restrictOnDelete();
                  
            $table->foreign('created_by', 'fk_task_deps_created_by')
                  ->references('id')->on('staff')
                  ->cascadeOnUpdate()->restrictOnDelete();
                  
            $table->foreign('updated_by', 'fk_task_deps_updated_by')
                  ->references('id')->on('staff')
                  ->cascadeOnUpdate()->restrictOnDelete();
            
            // Indexes for performance
            $table->index('task_id', 'idx_task_deps_task_id');
            $table->index('depends_on_task_id', 'idx_task_deps_depends_on_id');
            $table->index('dependency_type', 'idx_task_deps_type');
            $table->index('created_by', 'idx_task_deps_created_by');
            $table->index('deleted_at', 'idx_task_deps_deleted_at');
            
            // Unique constraint to prevent duplicate dependencies
            $table->unique(['task_id', 'depends_on_task_id', 'deleted_at'], 'unique_task_dependency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_task_dependencies');
    }
};