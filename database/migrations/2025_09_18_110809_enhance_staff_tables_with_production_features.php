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
        // 1. Add soft deletes to all staff tables
        $this->addSoftDeletes();
        
        // 2. Update money precision to decimal(12,2)
        $this->updateMoneyPrecision();
        
        // 3. Add unique constraints to prevent duplicates
        $this->addUniqueConstraints();
        
        // 4. Add composite indexes for common queries
        $this->addCompositeIndexes();
        
        // 5. Add audit fields (created_by/updated_by) to operational tables
        $this->addAuditFields();
        
        // 6. Ensure proper engine and charset
        $this->ensureEngineAndCharset();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove audit fields
        $this->removeAuditFields();
        
        // Remove composite indexes
        $this->removeCompositeIndexes();
        
        // Remove unique constraints
        $this->removeUniqueConstraints();
        
        // Revert money precision
        $this->revertMoneyPrecision();
        
        // Remove soft deletes
        $this->removeSoftDeletes();
    }

    /**
     * Add soft deletes to all staff tables.
     */
    private function addSoftDeletes(): void
    {
        $tables = [
            'staff_types',
            'staff', 
            'staff_profiles',
            'staff_attendance',
            'staff_shifts',
            'staff_shift_assignments',
            'staff_tasks',
            'staff_task_assignments',
            'staff_performance_reviews',
            'staff_payroll_records'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    /**
     * Update money fields to decimal(12,2) precision.
     */
    private function updateMoneyPrecision(): void
    {
        // Update staff_profiles hourly_rate
        Schema::table('staff_profiles', function (Blueprint $table) {
            $table->decimal('hourly_rate', 12, 2)->nullable()->change();
        });

        // Update staff_payroll_records money fields
        Schema::table('staff_payroll_records', function (Blueprint $table) {
            $table->decimal('gross_pay', 12, 2)->change();
            $table->decimal('deductions', 12, 2)->nullable()->change();
            $table->decimal('net_pay', 12, 2)->change();
        });
    }

    /**
     * Add unique constraints to prevent duplicates.
     */
    private function addUniqueConstraints(): void
    {
        // Staff shift assignments: one assignment per person per day per shift
        Schema::table('staff_shift_assignments', function (Blueprint $table) {
            $table->dropUnique('unique_staff_shift_assignment');
            $table->unique(['staff_id', 'assigned_date', 'staff_shift_id'], 'unique_staff_shift_per_day');
        });

        // Staff task assignments: don't assign same task twice same day
        Schema::table('staff_task_assignments', function (Blueprint $table) {
            $table->unique(['staff_task_id', 'staff_id', 'assigned_date'], 'unique_task_assignment_per_day');
        });

        // Performance reviews: one review per person per period
        Schema::table('staff_performance_reviews', function (Blueprint $table) {
            $table->unique(['staff_id', 'review_period_start', 'review_period_end'], 'unique_review_per_period');
        });

        // Payroll records: one record per person per period
        Schema::table('staff_payroll_records', function (Blueprint $table) {
            $table->unique(['staff_id', 'pay_period_start', 'pay_period_end'], 'unique_payroll_per_period');
        });
    }

    /**
     * Add composite indexes for common query patterns.
     */
    private function addCompositeIndexes(): void
    {
        // Staff attendance indexes
        Schema::table('staff_attendance', function (Blueprint $table) {
            $table->index(['staff_id', 'clock_in'], 'idx_staff_clock_in');
            $table->index('status', 'idx_attendance_status');
        });

        // Staff task assignments indexes
        Schema::table('staff_task_assignments', function (Blueprint $table) {
            $table->index(['status', 'due_date'], 'idx_status_due_date');
        });

        // Staff shift assignments indexes
        Schema::table('staff_shift_assignments', function (Blueprint $table) {
            $table->index('assigned_date', 'idx_assigned_date');
            $table->index('status', 'idx_assignment_status');
        });

        // Staff payroll records indexes
        Schema::table('staff_payroll_records', function (Blueprint $table) {
            $table->index(['pay_period_start', 'pay_period_end'], 'idx_pay_period');
        });
    }

    /**
     * Add audit fields to operational tables.
     */
    private function addAuditFields(): void
    {
        // Staff attendance
        Schema::table('staff_attendance', function (Blueprint $table) {
            $table->foreignUlid('created_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('updated_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
        });

        // Staff shift assignments
        Schema::table('staff_shift_assignments', function (Blueprint $table) {
            $table->foreignUlid('updated_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
        });

        // Staff task assignments
        Schema::table('staff_task_assignments', function (Blueprint $table) {
            $table->foreignUlid('updated_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
        });

        // Staff performance reviews
        Schema::table('staff_performance_reviews', function (Blueprint $table) {
            $table->foreignUlid('created_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('updated_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
        });

        // Staff payroll records
        Schema::table('staff_payroll_records', function (Blueprint $table) {
            $table->foreignUlid('created_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('updated_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Ensure proper engine and charset for all tables.
     */
    private function ensureEngineAndCharset(): void
    {
        $tables = [
            'staff_types',
            'staff', 
            'staff_profiles',
            'staff_attendance',
            'staff_shifts',
            'staff_shift_assignments',
            'staff_tasks',
            'staff_task_assignments',
            'staff_performance_reviews',
            'staff_payroll_records'
        ];

        foreach ($tables as $tableName) {
            DB::statement("ALTER TABLE {$tableName} ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        }
    }

    /**
     * Remove soft deletes from all staff tables.
     */
    private function removeSoftDeletes(): void
    {
        $tables = [
            'staff_types',
            'staff', 
            'staff_profiles',
            'staff_attendance',
            'staff_shifts',
            'staff_shift_assignments',
            'staff_tasks',
            'staff_task_assignments',
            'staff_performance_reviews',
            'staff_payroll_records'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }

    /**
     * Revert money precision changes.
     */
    private function revertMoneyPrecision(): void
    {
        Schema::table('staff_profiles', function (Blueprint $table) {
            $table->decimal('hourly_rate', 8, 2)->nullable()->change();
        });

        Schema::table('staff_payroll_records', function (Blueprint $table) {
            $table->decimal('gross_pay', 10, 2)->change();
            $table->decimal('deductions', 10, 2)->nullable()->change();
            $table->decimal('net_pay', 10, 2)->change();
        });
    }

    /**
     * Remove unique constraints.
     */
    private function removeUniqueConstraints(): void
    {
        Schema::table('staff_shift_assignments', function (Blueprint $table) {
            $table->dropUnique('unique_staff_shift_per_day');
            $table->unique(['staff_id', 'assigned_date', 'staff_shift_id'], 'unique_staff_shift_assignment');
        });

        Schema::table('staff_task_assignments', function (Blueprint $table) {
            $table->dropUnique('unique_task_assignment_per_day');
        });

        Schema::table('staff_performance_reviews', function (Blueprint $table) {
            $table->dropUnique('unique_review_per_period');
        });

        Schema::table('staff_payroll_records', function (Blueprint $table) {
            $table->dropUnique('unique_payroll_per_period');
        });
    }

    /**
     * Remove composite indexes.
     */
    private function removeCompositeIndexes(): void
    {
        Schema::table('staff_attendance', function (Blueprint $table) {
            $table->dropIndex('idx_staff_clock_in');
            $table->dropIndex('idx_attendance_status');
        });

        Schema::table('staff_task_assignments', function (Blueprint $table) {
            $table->dropIndex('idx_status_due_date');
        });

        Schema::table('staff_shift_assignments', function (Blueprint $table) {
            $table->dropIndex('idx_assigned_date');
            $table->dropIndex('idx_assignment_status');
        });

        Schema::table('staff_payroll_records', function (Blueprint $table) {
            $table->dropIndex('idx_pay_period');
        });
    }

    /**
     * Remove audit fields.
     */
    private function removeAuditFields(): void
    {
        Schema::table('staff_attendance', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['created_by', 'updated_by']);
        });

        Schema::table('staff_shift_assignments', function (Blueprint $table) {
            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');
        });

        Schema::table('staff_task_assignments', function (Blueprint $table) {
            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');
        });

        Schema::table('staff_performance_reviews', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['created_by', 'updated_by']);
        });

        Schema::table('staff_payroll_records', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['created_by', 'updated_by']);
        });
    }
};