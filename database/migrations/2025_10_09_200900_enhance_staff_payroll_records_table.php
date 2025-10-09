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
        // Check if table exists, if not skip (will be handled by original migration)
        if (! Schema::hasTable('staff_payroll_records')) {
            return;
        }

        Schema::table('staff_payroll_records', function (Blueprint $table) {
            // === SNAPSHOT FIELDS (Historical Data Capture) ===
            if (! Schema::hasColumn('staff_payroll_records', 'staff_name_snapshot')) {
                $table->string('staff_name_snapshot')->nullable()->after('staff_id')
                    ->comment('Staff name at time of payroll generation');
            }
            
            if (! Schema::hasColumn('staff_payroll_records', 'position_snapshot')) {
                $table->string('position_snapshot', 100)->nullable()->after('staff_name_snapshot')
                    ->comment('Position/role at time of generation');
            }
            
            if (! Schema::hasColumn('staff_payroll_records', 'hourly_rate_snapshot')) {
                $table->decimal('hourly_rate_snapshot', 12, 2)->nullable()->after('position_snapshot')
                    ->comment('Hourly rate at time of generation');
            }
            
            if (! Schema::hasColumn('staff_payroll_records', 'department_snapshot')) {
                $table->string('department_snapshot', 100)->nullable()->after('hourly_rate_snapshot')
                    ->comment('Department at time of generation');
            }
            
            // === SOURCE TRACKING ===
            if (! Schema::hasColumn('staff_payroll_records', 'generated_from')) {
                $table->string('generated_from', 50)->nullable()->after('department_snapshot')
                    ->comment('Source of generation: attendance, manual, import, etc.');
            }
            
            if (! Schema::hasColumn('staff_payroll_records', 'source_period_start')) {
                $table->dateTime('source_period_start')->nullable()->after('generated_from')
                    ->comment('Start of source data period');
            }
            
            if (! Schema::hasColumn('staff_payroll_records', 'source_period_end')) {
                $table->dateTime('source_period_end')->nullable()->after('source_period_start')
                    ->comment('End of source data period');
            }
            
            // === IDEMPOTENCY & CONCURRENCY ===
            if (! Schema::hasColumn('staff_payroll_records', 'generation_hash')) {
                $table->string('generation_hash', 64)->nullable()->after('source_period_end')
                    ->comment('SHA-256 hash for idempotent generation');
            }
            
            // === CURRENCY ===
            if (! Schema::hasColumn('staff_payroll_records', 'currency')) {
                $table->string('currency', 3)->default('USD')->after('net_pay')
                    ->comment('Currency code');
            }
            
            // === ENHANCED FINANCIAL FIELDS ===
            if (! Schema::hasColumn('staff_payroll_records', 'hourly_rate')) {
                $table->decimal('hourly_rate', 12, 2)->nullable()->after('currency')
                    ->comment('Effective hourly rate used');
            }
            
            if (! Schema::hasColumn('staff_payroll_records', 'overtime_rate')) {
                $table->decimal('overtime_rate', 12, 2)->nullable()->after('hourly_rate')
                    ->comment('Effective overtime rate used');
            }
            
            if (! Schema::hasColumn('staff_payroll_records', 'regular_pay')) {
                $table->decimal('regular_pay', 12, 2)->nullable()->after('regular_hours')
                    ->comment('Pay for regular hours');
            }
            
            if (! Schema::hasColumn('staff_payroll_records', 'overtime_pay')) {
                $table->decimal('overtime_pay', 12, 2)->nullable()->after('overtime_hours')
                    ->comment('Pay for overtime hours');
            }
            
            if (! Schema::hasColumn('staff_payroll_records', 'bonus_total')) {
                $table->decimal('bonus_total', 12, 2)->default(0)->after('overtime_pay')
                    ->comment('Total bonuses included');
            }
            
            if (! Schema::hasColumn('staff_payroll_records', 'tax_deductions')) {
                $table->decimal('tax_deductions', 12, 2)->default(0)->after('deductions')
                    ->comment('Tax deductions only');
            }
            
            if (! Schema::hasColumn('staff_payroll_records', 'other_deductions')) {
                $table->decimal('other_deductions', 12, 2)->default(0)->after('tax_deductions')
                    ->comment('Non-tax deductions');
            }
            
            // === LINKS TO OTHER TABLES ===
            if (! Schema::hasColumn('staff_payroll_records', 'pay_period_id')) {
                $table->foreignUlid('pay_period_id')->nullable()->after('staff_id')
                    ->constrained('staff_payroll_periods')->cascadeOnUpdate()->restrictOnDelete()
                    ->comment('Pay period this record belongs to');
            }
            
            if (! Schema::hasColumn('staff_payroll_records', 'template_id')) {
                $table->foreignUlid('template_id')->nullable()->after('pay_period_id')
                    ->constrained('staff_payroll_templates')->cascadeOnUpdate()->nullOnDelete()
                    ->comment('Template used for calculations');
            }
            
            // === ENHANCED STATUS ===
            // Check if status column exists and modify it
            $statusColumn = Schema::getColumnType('staff_payroll_records', 'status');
            if ($statusColumn) {
                // Drop the existing status column and recreate with new enum values
                DB::statement("ALTER TABLE staff_payroll_records MODIFY COLUMN status ENUM('draft', 'calculated', 'approved', 'paid', 'cancelled', 'needs_review') DEFAULT 'draft'");
            }
        });

        // Add indexes after all columns are created
        Schema::table('staff_payroll_records', function (Blueprint $table) {
            // Add indexes - Laravel will skip if they already exist
            try {
                $table->index('pay_period_id');
            } catch (\Exception $e) {
                // Index already exists, skip
            }
            
            try {
                $table->index('template_id');
            } catch (\Exception $e) {
                // Index already exists, skip
            }
            
            try {
                $table->index('generation_hash');
            } catch (\Exception $e) {
                // Index already exists, skip
            }
            
            try {
                // Unique constraint for idempotency
                $table->unique(['staff_id', 'pay_period_id', 'generation_hash'], 'unique_payroll_generation');
            } catch (\Exception $e) {
                // Unique constraint already exists, skip
            }
        });
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
            // Drop indexes first
            $table->dropIndex(['pay_period_id']);
            $table->dropIndex(['template_id']);
            $table->dropIndex(['generation_hash']);
            $table->dropUnique('unique_payroll_generation');
            
            // Drop foreign keys
            $table->dropForeign(['pay_period_id']);
            $table->dropForeign(['template_id']);
            
            // Drop columns
            $table->dropColumn([
                'staff_name_snapshot',
                'position_snapshot',
                'hourly_rate_snapshot',
                'department_snapshot',
                'generated_from',
                'source_period_start',
                'source_period_end',
                'generation_hash',
                'currency',
                'hourly_rate',
                'overtime_rate',
                'regular_pay',
                'overtime_pay',
                'bonus_total',
                'tax_deductions',
                'other_deductions',
                'pay_period_id',
                'template_id',
            ]);
        });
    }
};

