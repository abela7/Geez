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
        Schema::create('staff_attendance', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('staff_id')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();

            $table->timestamp('clock_in')->comment('When staff clocked in');
            $table->timestamp('clock_out')->nullable()->comment('When staff clocked out');
            $table->enum('status', ['present', 'absent', 'late', 'early_leave', 'overtime'])->default('present');
            $table->decimal('hours_worked', 5, 2)->nullable()->comment('Calculated work hours');
            $table->text('notes')->nullable()->comment('Manager or staff notes');

            $table->timestamps();

            // Indexes for performance
            $table->index(['staff_id', 'clock_in']);
            $table->index('status');
            $table->index('clock_in');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_attendance');
    }
};
