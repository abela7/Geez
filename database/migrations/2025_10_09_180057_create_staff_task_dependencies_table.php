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
        Schema::create('staff_task_dependencies', function (Blueprint $table) {
            $table->ulid('id')->primary();

            // Foreign keys
            $table->foreignUlid('task_id')->constrained('staff_tasks')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUlid('depends_on_task_id')->constrained('staff_tasks')->cascadeOnUpdate()->restrictOnDelete();

            // Dependency configuration
            $table->enum('dependency_type', ['finish_to_start', 'start_to_start', 'finish_to_finish', 'start_to_finish'])->default('finish_to_start');
            $table->integer('lag_days')->default(0)->comment('Number of days to wait after dependency is met');

            // Audit fields
            $table->foreignUlid('created_by')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('updated_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index(['task_id', 'depends_on_task_id']);
            $table->index(['depends_on_task_id', 'task_id']);
            $table->index('dependency_type');
            $table->index('created_by');

            // Unique constraint to prevent duplicate dependencies
            $table->unique(['task_id', 'depends_on_task_id'], 'unique_task_dependency');
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
