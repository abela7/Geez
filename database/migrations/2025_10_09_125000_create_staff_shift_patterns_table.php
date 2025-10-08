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
        Schema::create('staff_shift_patterns', function (Blueprint $table) {
            // Primary Key
            $table->ulid('id')->primary();

            // Pattern Definition
            $table->foreignUlid('staff_id')->constrained('staff')->onDelete('cascade');
            $table->foreignUlid('shift_id')->constrained('staff_shifts')->onDelete('cascade');

            // Scheduling Pattern
            $table->tinyInteger('day_of_week'); // 0 = Sunday, 1 = Monday, etc.
            $table->enum('frequency', [
                'weekly',       // Every week
                'biweekly',     // Every other week
                'monthly',      // Once per month
                'custom',        // Custom frequency
            ])->default('weekly');

            // Pattern Validity
            $table->date('effective_from'); // When this pattern starts
            $table->date('effective_until')->nullable(); // When this pattern ends (null = indefinite)
            $table->boolean('is_active')->default(true);

            // Exceptions & Overrides
            $table->json('excluded_dates')->nullable(); // Dates to skip this pattern
            $table->integer('priority')->default(1); // If multiple patterns conflict, higher priority wins

            // Pattern Metadata
            $table->string('pattern_name')->nullable(); // "John's Monday Kitchen Shift"
            $table->text('notes')->nullable(); // Notes about this pattern

            // Auto-generation Settings
            $table->boolean('auto_generate')->default(true); // Should this create assignments automatically?
            $table->integer('generate_days_ahead')->default(14); // How many days in advance to generate

            // Audit Trail
            $table->foreignUlid('created_by')->constrained('staff')->onDelete('cascade');
            $table->foreignUlid('updated_by')->nullable()->constrained('staff')->onDelete('set null');

            // Timestamps & Soft Deletes
            $table->timestamps();
            $table->softDeletes();

            // Constraints & Indexes
            $table->unique(['staff_id', 'shift_id', 'day_of_week'], 'unique_staff_shift_day');
            $table->index(['day_of_week', 'is_active']);
            $table->index(['effective_from', 'effective_until']);
            $table->index('auto_generate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_shift_patterns');
    }
};
