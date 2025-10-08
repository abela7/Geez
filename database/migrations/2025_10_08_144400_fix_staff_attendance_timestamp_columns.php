<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remove the automatic timestamp behavior from clock_in column
        DB::statement('ALTER TABLE `staff_attendance` MODIFY `clock_in` TIMESTAMP NULL DEFAULT NULL');
        
        // Also fix clock_out if it has the same issue
        DB::statement('ALTER TABLE `staff_attendance` MODIFY `clock_out` TIMESTAMP NULL DEFAULT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore the original behavior if needed
        DB::statement('ALTER TABLE `staff_attendance` MODIFY `clock_in` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        DB::statement('ALTER TABLE `staff_attendance` MODIFY `clock_out` TIMESTAMP NULL DEFAULT NULL');
    }
};
