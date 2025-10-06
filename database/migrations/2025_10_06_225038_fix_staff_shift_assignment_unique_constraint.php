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
        Schema::table('staff_shift_assignments', function (Blueprint $table) {
            // Drop the overly restrictive constraint
            $table->dropUnique('unique_staff_shift_per_day');
            
            // Add a new constraint that only prevents duplicate assignments to the same shift on the same day
            // This allows staff to work multiple different shifts on the same day (e.g., Main Chef + Helper Chef)
            $table->unique(['staff_id', 'staff_shift_id', 'assigned_date'], 'unique_staff_shift_assignment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_shift_assignments', function (Blueprint $table) {
            // Revert back to the old constraint
            $table->dropUnique('unique_staff_shift_assignment');
            $table->unique(['staff_id', 'assigned_date', 'staff_shift_id'], 'unique_staff_shift_per_day');
        });
    }
};