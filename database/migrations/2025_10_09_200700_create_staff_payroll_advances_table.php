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
        if (Schema::hasTable('staff_payroll_advances')) {
            return;
        }

        Schema::create('staff_payroll_advances', function (Blueprint $table) {
            $table->ulid('id')->primary();

            // Staff & Advance Details
            $table->foreignUlid('staff_id')
                ->constrained('staff')->cascadeOnUpdate()->restrictOnDelete()
                ->comment('Staff member receiving advance');
            $table->enum('advance_type', ['salary_advance', 'loan', 'emergency', 'other'])
                ->default('salary_advance')->comment('Type of advance');
            
            // Amounts
            $table->decimal('requested_amount', 12, 2)->comment('Amount requested');
            $table->decimal('approved_amount', 12, 2)->nullable()->comment('Amount approved (may differ from requested)');
            $table->decimal('disbursed_amount', 12, 2)->nullable()->comment('Amount actually disbursed');
            $table->decimal('outstanding_balance', 12, 2)->default(0)->comment('Remaining balance to repay');
            $table->string('currency', 3)->default('USD');
            
            // Repayment Terms
            $table->integer('repayment_installments')->comment('Number of installments for repayment');
            $table->decimal('installment_amount', 12, 2)->nullable()->comment('Amount per installment');
            $table->date('first_deduction_date')->nullable()->comment('When first repayment starts');
            $table->integer('installments_paid')->default(0)->comment('Installments completed');
            
            // Interest (if applicable)
            $table->decimal('interest_rate', 5, 2)->default(0)->comment('Interest rate (0 = interest-free)');
            $table->decimal('total_interest', 12, 2)->default(0)->comment('Total interest charged');
            
            // Approval Workflow
            $table->enum('status', ['pending', 'approved', 'rejected', 'disbursed', 'repaying', 'completed', 'cancelled'])
                ->default('pending')->comment('Advance status');
            $table->timestamp('requested_at')->useCurrent()->comment('When advance was requested');
            $table->timestamp('approved_at')->nullable();
            $table->foreignUlid('approved_by')->nullable()
                ->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamp('disbursed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            // Link to Deduction
            $table->foreignUlid('deduction_id')->nullable()
                ->constrained('staff_payroll_deductions')->cascadeOnUpdate()->nullOnDelete()
                ->comment('Deduction record created for repayment');
            
            // Reason & Notes
            $table->text('reason')->nullable()->comment('Reason for advance request');
            $table->text('approval_notes')->nullable()->comment('Notes from approver');
            $table->text('notes')->nullable()->comment('General notes');
            $table->string('reference_number', 100)->nullable()->comment('Advance reference number');
            
            // Audit Fields
            $table->foreignUlid('created_by')->nullable()
                ->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('updated_by')->nullable()
                ->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['staff_id', 'status']);
            $table->index('status');
            $table->index('approved_by');
            $table->index('deduction_id');
            $table->index('advance_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_payroll_advances');
    }
};

