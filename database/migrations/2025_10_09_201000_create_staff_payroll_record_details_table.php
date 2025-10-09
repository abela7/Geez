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
        if (Schema::hasTable('staff_payroll_record_details')) {
            return;
        }

        Schema::create('staff_payroll_record_details', function (Blueprint $table) {
            $table->ulid('id')->primary();

            // Link to Payroll Record
            $table->foreignUlid('payroll_record_id')
                ->constrained('staff_payroll_records')->cascadeOnUpdate()->cascadeOnDelete()
                ->comment('Parent payroll record');
            
            // Line Item Details
            $table->enum('item_type', [
                'regular_hours',
                'overtime_hours',
                'bonus',
                'commission',
                'tip',
                'allowance',
                'deduction',
                'tax',
                'adjustment',
                'other'
            ])->comment('Type of line item');
            
            $table->string('description')->comment('Line item description');
            $table->text('notes')->nullable()->comment('Additional notes');
            
            // Amounts
            $table->decimal('quantity', 10, 2)->nullable()->comment('Quantity (hours, items, etc.)');
            $table->decimal('rate', 12, 2)->nullable()->comment('Rate or unit price');
            $table->decimal('amount', 12, 2)->comment('Total amount (can be positive or negative)');
            $table->string('currency', 3)->default('USD');
            
            // Classification
            $table->enum('affects', ['gross', 'net', 'both'])->default('gross')
                ->comment('How this item affects pay calculation');
            $table->boolean('is_taxable')->default(true)->comment('Is this item taxable?');
            $table->boolean('is_pensionable')->default(false)->comment('Counts toward pension?');
            
            // References to Source Data
            $table->string('source_type', 50)->nullable()
                ->comment('Source model type (StaffAttendance, StaffPayrollBonus, etc.)');
            $table->ulid('source_id')->nullable()
                ->comment('ID of source record');
            
            // Display Order
            $table->integer('sort_order')->default(0)->comment('Display order on payslip');
            $table->boolean('show_on_payslip')->default(true)->comment('Show on payslip?');
            
            // Calculation Metadata
            $table->json('calculation_data')->nullable()
                ->comment('Metadata about how this was calculated');
            
            // Audit Fields
            $table->foreignUlid('created_by')->nullable()
                ->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('payroll_record_id');
            $table->index('item_type');
            $table->index(['source_type', 'source_id']);
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_payroll_record_details');
    }
};

