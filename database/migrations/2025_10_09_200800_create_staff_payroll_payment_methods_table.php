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
        if (Schema::hasTable('staff_payroll_payment_methods')) {
            return;
        }

        Schema::create('staff_payroll_payment_methods', function (Blueprint $table) {
            $table->ulid('id')->primary();

            // Payment Record Link
            $table->foreignUlid('payroll_record_id')
                ->constrained('staff_payroll_records')->cascadeOnUpdate()->restrictOnDelete()
                ->comment('Payroll record this payment is for');
            
            // Payment Method
            $table->enum('payment_method', ['cash', 'bank_transfer', 'check', 'mobile_money', 'other'])
                ->default('bank_transfer')->comment('How payment was made');
            $table->decimal('amount_paid', 12, 2)->comment('Amount paid via this method');
            $table->string('currency', 3)->default('USD');
            
            // Payment Details
            $table->date('payment_date')->comment('Date payment was made');
            $table->string('transaction_reference', 100)->nullable()
                ->comment('Bank reference, check number, etc.');
            $table->string('bank_name', 100)->nullable()->comment('Bank name if bank transfer');
            $table->string('account_number_last4', 4)->nullable()
                ->comment('Last 4 digits of account for verification');
            
            // Status
            $table->enum('status', ['pending', 'processed', 'failed', 'cancelled', 'refunded'])
                ->default('pending')->comment('Payment status');
            $table->timestamp('processed_at')->nullable()->comment('When payment was processed');
            $table->timestamp('failed_at')->nullable();
            $table->text('failure_reason')->nullable()->comment('Reason if payment failed');
            
            // Batch Processing
            $table->string('batch_id', 100)->nullable()
                ->comment('Payment batch ID if part of batch processing');
            $table->integer('batch_sequence')->nullable()
                ->comment('Sequence number in batch');
            
            // Audit Trail
            $table->text('notes')->nullable()->comment('Internal notes');
            $table->json('metadata')->nullable()
                ->comment('Additional payment metadata (API responses, etc.)');
            
            // Audit Fields
            $table->foreignUlid('created_by')->nullable()
                ->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('updated_by')->nullable()
                ->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('payroll_record_id');
            $table->index(['payment_method', 'status']);
            $table->index('payment_date');
            $table->index('batch_id');
            $table->index('transaction_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_payroll_payment_methods');
    }
};

