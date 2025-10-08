<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adjust staff_shifts table to work as TEMPLATES instead of scheduled shifts.
     * Shifts become reusable templates that can be assigned to staff via assignments.
     */
    public function up(): void
    {
        Schema::table('staff_shifts', function (Blueprint $table) {
            // Make days_of_week nullable - templates don't need specific days
            // Days are now handled in staff_shift_assignments
            $table->json('days_of_week')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_shifts', function (Blueprint $table) {
            // Revert days_of_week to required (original state)
            $table->json('days_of_week')->nullable(false)->change();
        });
    }
};
