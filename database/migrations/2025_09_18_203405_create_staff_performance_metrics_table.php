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
        Schema::create('staff_performance_metrics', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            // Foreign keys
            $table->foreignUlid('staff_id')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete()->name('fk_perf_metrics_staff_id');
            
            // Metric details
            $table->string('metric_name')->comment('e.g., punctuality, orders_per_hour');
            $table->decimal('metric_value', 12, 2);
            $table->enum('measurement_period', ['daily', 'weekly', 'monthly']);
            $table->date('recorded_date');
            $table->enum('data_source', ['manual', 'attendance', 'tasks', 'reviews'])->default('manual');
            $table->text('notes')->nullable();
            
            // Audit fields
            $table->foreignUlid('created_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete()->name('fk_perf_metrics_created_by');
            $table->foreignUlid('updated_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete()->name('fk_perf_metrics_updated_by');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['staff_id', 'recorded_date']);
            $table->index('measurement_period');
            $table->index('data_source');
            $table->index(['metric_name', 'recorded_date']);
            
            // Unique constraints
            $table->unique(['staff_id', 'metric_name', 'recorded_date', 'measurement_period'], 'unique_staff_metric_per_period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_performance_metrics');
    }
};