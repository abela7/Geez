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
        if (Schema::hasTable('staff_payroll_deductions')) {
            return;
        }

        Schema::create('staff_payroll_deductions', function (Blueprint $table) {
            $table->ulid('id')->primary();

            // Staff & Deduction Type
            $table->foreignUlid('staff_id')
                ->constrained('staff')->cascadeOnUpdate()->restrictOnDelete()
                ->comment('Staff member this deduction applies to');
            $table->foreignUlid('deduction_type_id')
                ->constrained('staff_payroll_deduction_types')->cascadeOnUpdate()->restrictOnDelete()
                ->comment('Type of deduction');
            
            // Override Default Values
            $table->decimal('custom_rate', 8, 4)->nullable()
                ->comment('Custom rate (overrides default from type)');
            $table->decimal('custom_amount', 12, 2)->nullable()
                ->comment('Custom fixed amount (overrides rate calculation)');
            
            // Validity Period
            $table->date('effective_from')->comment('When deduction starts');
            $table->date('effective_to')->nullable()->comment('When deduction ends (null = ongoing)');
            
            // Tracking for Installment Deductions
            $table->decimal('total_amount', 12, 2)->nullable()
                ->comment('Total amount to deduct (for loans/advances)');
            $table->decimal('amount_deducted_to_date', 12, 2)->default(0)
                ->comment('Amount deducted so far');
            $table->integer('installment_count')->nullable()
                ->comment('Number of installments');
            $table->integer('installments_completed')->default(0)
                ->comment('Installments completed');
            
            // Status
            $table->enum('status', ['active', 'paused', 'completed', 'cancelled'])->default('active')
                ->comment('Deduction status');
            $table->timestamp('completed_at')->nullable()
                ->comment('When deduction was fully paid/completed');
            
            // Notes & Reference
            $table->text('notes')->nullable()->comment('Internal notes');
            $table->string('reference_number', 100)->nullable()
                ->comment('External reference (loan ID, court order number, etc.)');
            
            // Audit Fields
            $table->foreignUlid('created_by')->nullable()
                ->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('updated_by')->nullable()
                ->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['staff_id', 'status']);
            $table->index(['effective_from', 'effective_to']);
            $table->index('deduction_type_id');
            $table->index('status');
            
            // Prevent duplicate active deductions of same type for same staff
            // Note: This is a partial unique index (only active records)
            // Will need to be handled in application logic since Laravel doesn't support partial indexes natively
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_payroll_deductions');
    }
};

