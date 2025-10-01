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
        Schema::create('staff_performance_goals', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            // Foreign keys
            $table->foreignUlid('staff_id')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete()->name('fk_perf_goals_staff_id');
            
            // Goal details
            $table->string('goal_title');
            $table->text('goal_description')->nullable();
            $table->decimal('target_value', 12, 2)->nullable();
            $table->decimal('current_value', 12, 2)->nullable()->default(0);
            $table->string('measurement_unit')->comment('e.g., %, hours, ETB, count');
            
            // Goal classification
            $table->enum('goal_type', ['individual', 'team', 'department'])->default('individual');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            
            // Timeline
            $table->date('start_date');
            $table->date('target_date')->nullable();
            
            // Status tracking
            $table->enum('status', ['active', 'completed', 'cancelled', 'overdue'])->default('active');
            
            // Audit fields
            $table->foreignUlid('created_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete()->name('fk_perf_goals_created_by');
            $table->foreignUlid('updated_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete()->name('fk_perf_goals_updated_by');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['staff_id', 'status']);
            $table->index('target_date');
            $table->index('priority');
            $table->index('goal_type');
            
            // Unique constraints
            $table->unique(['staff_id', 'goal_title', 'start_date'], 'unique_staff_goal_per_start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_performance_goals');
    }
};