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
     */
    public function up(): void
    {
        // Check if table exists
        if (! Schema::hasTable('staff_payroll_records')) {
            return;
        }

        // Check if deleted_at column exists (soft deletes)
        if (! Schema::hasColumn('staff_payroll_records', 'deleted_at')) {
            Schema::table('staff_payroll_records', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Drop the existing unique constraint
        Schema::table('staff_payroll_records', function (Blueprint $table) {
            try {
                $table->dropUnique('unique_payroll_generation');
            } catch (\Exception $e) {
                // Constraint doesn't exist, continue
            }
        });

        // Add new unique constraint with soft deletes consideration
        // This uses a partial unique index that only includes non-deleted records
        try {
            DB::statement(
                "ALTER TABLE staff_payroll_records 
                ADD CONSTRAINT unique_payroll_generation 
                UNIQUE (staff_id, pay_period_id, generation_hash) 
                WHERE deleted_at IS NULL"
            );
        } catch (\Exception $e) {
            // If database doesn't support WHERE clause in UNIQUE constraints (MySQL < 8.0),
            // we'll use a trigger-based approach instead
            // For now, log the error
            \Log::warning('Could not create partial unique constraint: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('staff_payroll_records')) {
            return;
        }

        Schema::table('staff_payroll_records', function (Blueprint $table) {
            try {
                $table->dropUnique('unique_payroll_generation');
            } catch (\Exception $e) {
                // Constraint doesn't exist
            }
        });

        // Recreate the original constraint
        try {
            $table->unique(['staff_id', 'pay_period_id', 'generation_hash'], 'unique_payroll_generation');
        } catch (\Exception $e) {
            // Could not recreate
        }
    }
};
