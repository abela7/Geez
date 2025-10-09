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
        // Guard against re-running migration
        if (Schema::hasTable('staff_payroll_periods')) {
            return;
        }

        Schema::create('staff_payroll_periods', function (Blueprint $table) {
            $table->ulid('id')->primary();

            // Period Definition
            $table->string('name')->comment('Period name (e.g., "October 2025 - Week 1")');
            $table->enum('period_type', ['weekly', 'biweekly', 'monthly'])->default('monthly')
                ->comment('Type of pay period');
            $table->date('period_start')->comment('Period start date');
            $table->date('period_end')->comment('Period end date');
            
            // Status & Lifecycle
            $table->enum('status', ['open', 'closed', 'processing'])->default('open')
                ->comment('Period status');
            $table->timestamp('closed_at')->nullable()->comment('When period was closed');
            $table->foreignUlid('closed_by')->nullable()
                ->constrained('staff')->cascadeOnUpdate()->restrictOnDelete()
                ->comment('Who closed the period');
            
            // Link to Settings
            $table->foreignUlid('payroll_setting_id')->nullable()
                ->constrained('staff_payroll_settings')->cascadeOnUpdate()->restrictOnDelete()
                ->comment('Payroll configuration used');
            
            // Statistics (cached)
            $table->integer('total_staff_count')->default(0)->comment('Number of staff in period');
            $table->decimal('total_gross_pay', 12, 2)->default(0)->comment('Total gross pay for period');
            $table->decimal('total_net_pay', 12, 2)->default(0)->comment('Total net pay for period');
            $table->decimal('total_deductions', 12, 2)->default(0)->comment('Total deductions');
            
            // Audit Fields
            $table->foreignUlid('created_by')->nullable()
                ->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('updated_by')->nullable()
                ->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('status');
            $table->index(['period_type', 'period_start', 'period_end']);
            $table->index('payroll_setting_id');
            
            // Prevent overlapping periods of same type
            $table->unique(['period_type', 'period_start', 'period_end'], 'unique_period_dates');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_payroll_periods');
    }
};

