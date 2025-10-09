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
        if (Schema::hasTable('staff_payroll_bonuses')) {
            return;
        }

        Schema::create('staff_payroll_bonuses', function (Blueprint $table) {
            $table->ulid('id')->primary();

            // Staff & Period
            $table->foreignUlid('staff_id')
                ->constrained('staff')->cascadeOnUpdate()->restrictOnDelete()
                ->comment('Staff member receiving bonus');
            $table->foreignUlid('pay_period_id')->nullable()
                ->constrained('staff_payroll_periods')->cascadeOnUpdate()->nullOnDelete()
                ->comment('Pay period to include bonus in');
            
            // Bonus Details
            $table->enum('bonus_type', ['performance', 'commission', 'tip', 'holiday', 'one_time', 'referral', 'other'])
                ->default('one_time')->comment('Type of bonus');
            $table->string('name')->comment('Bonus name/description');
            $table->text('description')->nullable();
            
            // Amount
            $table->decimal('amount', 12, 2)->comment('Bonus amount');
            $table->string('currency', 3)->default('USD');
            
            // Tax Treatment
            $table->boolean('is_taxable')->default(true)->comment('Is bonus subject to tax?');
            $table->boolean('is_pensionable')->default(false)
                ->comment('Does bonus count toward pension calculations?');
            
            // Payment Status
            $table->enum('status', ['pending', 'approved', 'paid', 'cancelled'])->default('pending')
                ->comment('Bonus status');
            $table->timestamp('approved_at')->nullable();
            $table->foreignUlid('approved_by')->nullable()
                ->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamp('paid_at')->nullable();
            
            // Link to Payroll Record
            $table->foreignUlid('payroll_record_id')->nullable()
                ->constrained('staff_payroll_records')->cascadeOnUpdate()->nullOnDelete()
                ->comment('Payroll record this bonus was paid in');
            
            // Reference & Notes
            $table->string('reference_number', 100)->nullable()
                ->comment('External reference number');
            $table->text('notes')->nullable()->comment('Internal notes');
            
            // Audit Fields
            $table->foreignUlid('created_by')->nullable()
                ->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('updated_by')->nullable()
                ->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['staff_id', 'status']);
            $table->index('pay_period_id');
            $table->index('payroll_record_id');
            $table->index('bonus_type');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_payroll_bonuses');
    }
};

