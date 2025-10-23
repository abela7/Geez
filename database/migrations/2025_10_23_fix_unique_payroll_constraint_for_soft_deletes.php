<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Fix the unique constraint on staff_payroll_records to allow
     * re-creation of payroll after soft deletion.
     */
    public function up(): void
    {
        if (!Schema::hasTable('staff_payroll_records')) {
            return;
        }

        // Drop the existing unique constraint that doesn't account for soft deletes
        Schema::table('staff_payroll_records', function (Blueprint $table) {
            try {
                $table->dropUnique('unique_payroll_per_period');
            } catch (\Exception $e) {
                // Constraint might not exist
                \Log::info('unique_payroll_per_period constraint not found or already dropped');
            }
        });

        // MySQL doesn't support WHERE clause in unique constraints like PostgreSQL
        // So we need to use a different approach:
        // 1. For MySQL 8.0+: Use a functional index (not supported in older versions)
        // 2. For all MySQL: Handle uniqueness in application logic (already done in Livewire component)
        // 3. Best approach: Create a trigger or use generated columns
        
        // For maximum compatibility, we'll add a unique index that Laravel will check
        // but we'll rely on application-level checking (already implemented)
        
        // Add a comment to the table for documentation
        DB::statement("ALTER TABLE staff_payroll_records COMMENT = 'Uniqueness check for staff_id+pay_period handled at application level to support soft deletes'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('staff_payroll_records')) {
            return;
        }

        // Recreate the original constraint
        Schema::table('staff_payroll_records', function (Blueprint $table) {
            try {
                $table->unique(['staff_id`', 'pay_period_start', 'pay_period_end'], 'unique_payroll_per_period');
            } catch (\Exception $e) {
                \Log::warning('Could not recreate unique_payroll_per_period constraint: ' . $e->getMessage());
            }
        });
    }
};

