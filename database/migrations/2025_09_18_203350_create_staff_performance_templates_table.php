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
        Schema::create('staff_performance_templates', function (Blueprint $table) {
            $table->ulid('id')->primary();

            // Foreign keys
            $table->foreignUlid('staff_type_id')->constrained('staff_types')->cascadeOnUpdate()->restrictOnDelete()->name('fk_perf_templates_type_id');

            // Template details
            $table->string('template_name');
            $table->enum('review_frequency', ['monthly', 'quarterly', 'annual'])->default('quarterly');
            $table->json('rating_criteria')->comment('Array of criteria with weights, e.g. [{"key":"punctuality","weight":20}]');

            // Versioning
            $table->integer('version')->default(1);
            $table->boolean('is_active')->default(true);

            // Audit fields
            $table->foreignUlid('created_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete()->name('fk_perf_templates_created_by');
            $table->foreignUlid('updated_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete()->name('fk_perf_templates_updated_by');

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['staff_type_id', 'is_active']);
            $table->index('review_frequency');
            $table->index('version');

            // Unique constraints
            $table->unique(['staff_type_id', 'template_name', 'version'], 'unique_template_per_type_version');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_performance_templates');
    }
};
