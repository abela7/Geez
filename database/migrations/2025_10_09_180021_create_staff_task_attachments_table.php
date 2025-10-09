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
        Schema::create('staff_task_attachments', function (Blueprint $table) {
            $table->ulid('id')->primary();

            // Foreign keys
            $table->foreignUlid('task_assignment_id')->constrained('staff_task_assignments')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUlid('staff_id')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();

            // File information
            $table->string('file_name');
            $table->string('file_path');
            $table->bigInteger('file_size')->unsigned();
            $table->string('mime_type');

            // Metadata
            $table->text('description')->nullable();
            $table->string('storage_disk')->default('local');
            $table->boolean('is_public')->default(false);

            // Download tracking
            $table->dateTime('downloaded_at')->nullable();
            $table->integer('download_count')->default(0);

            // Audit fields
            $table->foreignUlid('created_by')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('updated_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index(['task_assignment_id', 'created_at']);
            $table->index(['staff_id', 'created_at']);
            $table->index('mime_type');
            $table->index('is_public');
            $table->index('created_by');
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
