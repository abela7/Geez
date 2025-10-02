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
            
            // Add is_template flag to distinguish templates from legacy shifts
            $table->boolean('is_template')->default(true)->after('is_active')
                ->comment('Template shifts are reusable patterns, not scheduled shifts');
            
            // Add position/role field for clarity
            $table->string('position_name')->nullable()->after('name')
                ->comment('Job position/role name (e.g., Head Chef, Waiter, Bartender)');
            
            // Add index for better querying
            $table->index(['department', 'shift_type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_shifts', function (Blueprint $table) {
            // Remove new columns
            $table->dropColumn('is_template');
            $table->dropColumn('position_name');
            
            // Remove index
            $table->dropIndex(['department', 'shift_type', 'is_active']);
            
            // Revert days_of_week to required (original state)
            $table->json('days_of_week')->nullable(false)->change();
        });
    }
};
