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
        Schema::create('staff_performance_reviews', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->foreignUlid('staff_id')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();

            $table->date('review_period_start')->comment('Start of review period');
            $table->date('review_period_end')->comment('End of review period');

            // Rating fields (1.00 to 5.00 scale)
            $table->decimal('overall_rating', 3, 2)->comment('Overall performance rating');
            $table->decimal('punctuality_rating', 3, 2)->nullable()->comment('Punctuality rating');
            $table->decimal('quality_rating', 3, 2)->nullable()->comment('Work quality rating');
            $table->decimal('teamwork_rating', 3, 2)->nullable()->comment('Teamwork rating');
            $table->decimal('customer_service_rating', 3, 2)->nullable()->comment('Customer service rating');

            // Review content
            $table->text('strengths')->nullable()->comment('Employee strengths');
            $table->text('areas_for_improvement')->nullable()->comment('Areas needing improvement');
            $table->text('goals')->nullable()->comment('Goals for next period');

            $table->foreignUlid('reviewer_id')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->date('review_date')->comment('Date review was conducted');
            $table->enum('status', ['draft', 'completed', 'acknowledged'])->default('draft');

            $table->timestamps();

            // Indexes
            $table->index(['staff_id', 'review_date']);
            $table->index('reviewer_id');
            $table->index('status');
            $table->index(['review_period_start', 'review_period_end'], 'idx_review_period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_performance_reviews');
    }
};
