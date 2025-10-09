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
        Schema::create('staff_task_comments', function (Blueprint $table) {
            $table->ulid('id')->primary();

            // Foreign keys
            $table->foreignUlid('task_assignment_id')->constrained('staff_task_assignments')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUlid('staff_id')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();

            // Comment content
            $table->text('comment');

            // Comment metadata
            $table->enum('comment_type', ['comment', 'update', 'status_change', 'attachment'])->default('comment');
            $table->boolean('is_internal')->default(false)->comment('Whether comment is internal (not visible to assignee)');

            // Audit fields
            $table->foreignUlid('created_by')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('updated_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index(['task_assignment_id', 'created_at']);
            $table->index(['staff_id', 'created_at']);
            $table->index(['comment_type', 'is_internal']);
            $table->index('created_by');
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
