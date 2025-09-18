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
        Schema::create('staff_shift_assignments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->foreignUlid('staff_id')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('staff_shift_id')->constrained('staff_shifts')->cascadeOnUpdate()->restrictOnDelete();
            
            $table->date('assigned_date')->comment('Date this shift is assigned for');
            $table->enum('status', ['scheduled', 'confirmed', 'cancelled', 'completed'])->default('scheduled');
            $table->foreignUlid('assigned_by')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->text('notes')->nullable()->comment('Assignment notes');
            
            $table->timestamps();
            
            // Indexes
            $table->index(['staff_id', 'assigned_date']);
            $table->index('staff_shift_id');
            $table->index('status');
            $table->index('assigned_by');
            
            // Unique constraint to prevent double-booking
            $table->unique(['staff_id', 'assigned_date', 'staff_shift_id'], 'unique_staff_shift_assignment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_shift_assignments');
    }
};
