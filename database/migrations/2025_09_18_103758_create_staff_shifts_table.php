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
        Schema::create('staff_shifts', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->string('name')->comment('Shift name (e.g., Morning Shift)');
            $table->time('start_time')->comment('Shift start time');
            $table->time('end_time')->comment('Shift end time');
            $table->integer('break_duration')->nullable()->comment('Break duration in minutes');
            $table->json('days_of_week')->nullable()->comment('Array of weekdays [1,2,3,4,5]');
            $table->boolean('is_active')->default(true)->comment('Whether shift is active');

            $table->foreignUlid('created_by')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();

            // Indexes
            $table->index('is_active');
            $table->index('created_by');
            $table->index(['start_time', 'end_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_shifts');
    }
};
