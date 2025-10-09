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
        if (Schema::hasTable('staff_payroll_deduction_types')) {
            return;
        }

        Schema::create('staff_payroll_deduction_types', function (Blueprint $table) {
            $table->ulid('id')->primary();

            // Deduction Type Definition
            $table->string('name')->comment('Deduction name (e.g., "Social Security")');
            $table->string('code', 50)->unique()->comment('Unique deduction code');
            $table->text('description')->nullable();
            
            // Calculation Method
            $table->enum('calculation_type', ['percentage', 'fixed', 'formula', 'tiered'])
                ->default('percentage')->comment('How deduction is calculated');
            $table->decimal('default_rate', 8, 4)->nullable()
                ->comment('Default rate (percentage or fixed amount)');
            
            // Tiered/Formula Configuration (JSON)
            $table->json('calculation_rules')->nullable()
                ->comment('Complex rules for tiered or formula calculations');
            
            // Applicability
            $table->enum('applies_to', ['gross', 'net', 'taxable_income'])
                ->default('gross')->comment('What amount the deduction applies to');
            $table->boolean('is_pre_tax')->default(false)
                ->comment('Is this deduction pre-tax or post-tax?');
            $table->boolean('is_mandatory')->default(false)
                ->comment('Is this deduction mandatory for all staff?');
            
            // Limits
            $table->decimal('minimum_amount', 12, 2)->nullable()
                ->comment('Minimum deduction amount per payroll');
            $table->decimal('maximum_amount', 12, 2)->nullable()
                ->comment('Maximum deduction amount per payroll');
            $table->decimal('annual_limit', 12, 2)->nullable()
                ->comment('Annual deduction limit');
            
            // Display & Reporting
            $table->string('display_label')->nullable()
                ->comment('Label to show on payslip');
            $table->boolean('show_on_payslip')->default(true)
                ->comment('Show on payslip?');
            $table->integer('sort_order')->default(0)->comment('Display order on payslip');
            
            // Status
            $table->boolean('is_active')->default(true)->comment('Is deduction type active?');
            
            // Audit Fields
            $table->foreignUlid('created_by')->nullable()
                ->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('updated_by')->nullable()
                ->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('is_active');
            $table->index('is_mandatory');
            $table->index('calculation_type');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_payroll_deduction_types');
    }
};

