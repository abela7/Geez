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
        Schema::create('staff_performance_review_acknowledgements', function (Blueprint $table) {
            $table->ulid('id')->primary();

            // Foreign keys
            $table->foreignUlid('performance_review_id')->constrained('staff_performance_reviews')->cascadeOnUpdate()->restrictOnDelete()->name('fk_review_ack_review_id');
            $table->foreignUlid('acknowledged_by')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete()->name('fk_review_ack_staff_id');

            // Acknowledgement details
            $table->timestamp('acknowledged_at');
            $table->text('notes')->nullable();

            // Audit fields
            $table->foreignUlid('created_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete()->name('fk_review_ack_created_by');
            $table->foreignUlid('updated_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete()->name('fk_review_ack_updated_by');

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('performance_review_id', 'idx_review_ack_review_id');
            $table->index('acknowledged_by', 'idx_review_ack_staff_id');
            $table->index('acknowledged_at', 'idx_review_ack_date');

            // Unique constraints
            $table->unique(['performance_review_id', 'acknowledged_by'], 'unique_review_acknowledgement');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_performance_review_acknowledgements');
    }
};
