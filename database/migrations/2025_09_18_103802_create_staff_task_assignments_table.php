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
        Schema::create('staff_task_assignments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->foreignUlid('staff_task_id')->constrained('staff_tasks')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('staff_id')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            
            $table->date('assigned_date')->comment('Date task was assigned');
            $table->date('due_date')->nullable()->comment('When task should be completed');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled', 'overdue'])->default('pending');
            
            $table->timestamp('started_at')->nullable()->comment('When staff started the task');
            $table->timestamp('completed_at')->nullable()->comment('When task was completed');
            $table->text('notes')->nullable()->comment('Task completion notes');
            
            $table->foreignUlid('assigned_by')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('completed_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['staff_id', 'assigned_date']);
            $table->index('staff_task_id');
            $table->index('status');
            $table->index('due_date');
            $table->index('assigned_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_task_assignments');
    }
};
