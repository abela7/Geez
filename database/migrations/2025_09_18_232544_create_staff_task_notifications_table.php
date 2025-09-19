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
        Schema::create('staff_task_notifications', function (Blueprint $table) {
            // Primary key
            $table->char('id', 26)->primary()->comment('ULID primary key');
            
            // Task assignment relationship
            $table->char('task_assignment_id', 26)->comment('FK -> staff_task_assignments.id');
            $table->char('staff_id', 26)->comment('Notification recipient');
            
            // Notification content
            $table->string('notification_type', 32)->comment('assignment, reminder, overdue, due_soon, completed, comment');
            $table->string('title', 255)->comment('Notification title');
            $table->text('message')->comment('Notification message');
            
            // Notification state
            $table->boolean('is_read')->default(false)->comment('Whether notification was read');
            $table->timestamp('sent_at')->nullable()->comment('When notification was sent');
            $table->timestamp('read_at')->nullable()->comment('When notification was read');
            
            // Audit fields
            $table->char('created_by', 26)->comment('Staff member who created notification');
            $table->char('updated_by', 26)->nullable()->comment('Staff member who last updated');
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for performance
            $table->index('task_assignment_id', 'idx_notifications_assignment_id');
            $table->index('staff_id', 'idx_notifications_staff_id');
            $table->index('notification_type', 'idx_notifications_type');
            $table->index('is_read', 'idx_notifications_read');
            $table->index('sent_at', 'idx_notifications_sent_at');
            $table->index('created_by', 'idx_notifications_created_by');
            $table->index('deleted_at', 'idx_notifications_deleted_at');
            
            // Foreign key constraints (CASCADE update, RESTRICT delete)
            $table->foreign('task_assignment_id', 'fk_notifications_assignment_id')
                  ->references('id')->on('staff_task_assignments')
                  ->cascadeOnUpdate()->restrictOnDelete();
                  
            $table->foreign('staff_id', 'fk_notifications_staff_id')
                  ->references('id')->on('staff')
                  ->cascadeOnUpdate()->restrictOnDelete();
                  
            $table->foreign('created_by', 'fk_notifications_created_by')
                  ->references('id')->on('staff')
                  ->cascadeOnUpdate()->restrictOnDelete();
                  
            $table->foreign('updated_by', 'fk_notifications_updated_by')
                  ->references('id')->on('staff')
                  ->cascadeOnUpdate()->restrictOnDelete();
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