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
        Schema::create('staff_task_comments', function (Blueprint $table) {
            // Primary key
            $table->char('id', 26)->primary()->comment('ULID primary key');
            
            // Task assignment relationship
            $table->char('task_assignment_id', 26)->comment('Related task assignment');
            $table->char('staff_id', 26)->comment('Staff member who commented');
            
            // Comment content
            $table->text('comment')->comment('Comment content');
            $table->string('comment_type', 20)->default('comment')
                  ->comment('comment, update, status_change, attachment');
            $table->boolean('is_internal')->default(false)
                  ->comment('Internal comment (not visible to assignee)');
            
            // Audit fields
            $table->char('created_by', 26)->comment('Staff member who created comment');
            $table->char('updated_by', 26)->nullable()->comment('Staff member who last updated');
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign key constraints
            $table->foreign('task_assignment_id', 'fk_task_comments_assignment_id')
                  ->references('id')->on('staff_task_assignments')
                  ->cascadeOnUpdate()->restrictOnDelete();
                  
            $table->foreign('staff_id', 'fk_task_comments_staff_id')
                  ->references('id')->on('staff')
                  ->cascadeOnUpdate()->restrictOnDelete();
                  
            $table->foreign('created_by', 'fk_task_comments_created_by')
                  ->references('id')->on('staff')
                  ->cascadeOnUpdate()->restrictOnDelete();
                  
            $table->foreign('updated_by', 'fk_task_comments_updated_by')
                  ->references('id')->on('staff')
                  ->cascadeOnUpdate()->restrictOnDelete();
            
            // Indexes for performance
            $table->index('task_assignment_id', 'idx_task_comments_assignment_id');
            $table->index('staff_id', 'idx_task_comments_staff_id');
            $table->index('comment_type', 'idx_task_comments_type');
            $table->index('is_internal', 'idx_task_comments_internal');
            $table->index('created_by', 'idx_task_comments_created_by');
            $table->index('created_at', 'idx_task_comments_created_at');
            $table->index('deleted_at', 'idx_task_comments_deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_task_comments');
    }
};