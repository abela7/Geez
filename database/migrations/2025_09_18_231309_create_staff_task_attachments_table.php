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
        Schema::create('staff_task_attachments', function (Blueprint $table) {
            // Primary key
            $table->char('id', 26)->primary()->comment('ULID primary key');
            
            // Task assignment relationship
            $table->char('task_assignment_id', 26)->comment('Related task assignment');
            $table->char('staff_id', 26)->comment('Staff member who uploaded');
            
            // File information
            $table->string('file_name', 255)->comment('Original file name');
            $table->string('file_path', 500)->comment('Storage path');
            $table->integer('file_size')->comment('File size in bytes');
            $table->string('mime_type', 100)->comment('File MIME type');
            $table->text('description')->nullable()->comment('File description');
            
            // File metadata
            $table->string('storage_disk', 50)->default('local')->comment('Storage disk (local, s3, etc.)');
            $table->boolean('is_public')->default(false)->comment('Whether file is publicly accessible');
            $table->timestamp('downloaded_at')->nullable()->comment('Last download timestamp');
            $table->integer('download_count')->default(0)->comment('Number of downloads');
            
            // Audit fields
            $table->char('created_by', 26)->comment('Staff member who created attachment');
            $table->char('updated_by', 26)->nullable()->comment('Staff member who last updated');
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign key constraints
            $table->foreign('task_assignment_id', 'fk_task_attachments_assignment_id')
                  ->references('id')->on('staff_task_assignments')
                  ->cascadeOnUpdate()->restrictOnDelete();
                  
            $table->foreign('staff_id', 'fk_task_attachments_staff_id')
                  ->references('id')->on('staff')
                  ->cascadeOnUpdate()->restrictOnDelete();
                  
            $table->foreign('created_by', 'fk_task_attachments_created_by')
                  ->references('id')->on('staff')
                  ->cascadeOnUpdate()->restrictOnDelete();
                  
            $table->foreign('updated_by', 'fk_task_attachments_updated_by')
                  ->references('id')->on('staff')
                  ->cascadeOnUpdate()->restrictOnDelete();
            
            // Indexes for performance
            $table->index('task_assignment_id', 'idx_task_attachments_assignment_id');
            $table->index('staff_id', 'idx_task_attachments_staff_id');
            $table->index('mime_type', 'idx_task_attachments_mime_type');
            $table->index('storage_disk', 'idx_task_attachments_storage_disk');
            $table->index('is_public', 'idx_task_attachments_public');
            $table->index('created_by', 'idx_task_attachments_created_by');
            $table->index('created_at', 'idx_task_attachments_created_at');
            $table->index('deleted_at', 'idx_task_attachments_deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_task_attachments');
    }
};