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
        Schema::create('staff_task_time_entries', function (Blueprint $table) {
            $table->ulid('id')->primary();

            // Foreign keys
            $table->foreignUlid('task_assignment_id')->constrained('staff_task_assignments')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUlid('staff_id')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();

            // Time tracking
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->integer('duration_minutes')->nullable();

            // Metadata
            $table->text('description')->nullable();
            $table->boolean('is_billable')->default(true);

            // Audit fields
            $table->foreignUlid('created_by')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('updated_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index(['task_assignment_id', 'start_time']);
            $table->index(['staff_id', 'start_time']);
            $table->index(['start_time', 'end_time']);
            $table->index('is_billable');
            $table->index('created_by');
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
