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
        Schema::create('staff_payroll_records', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->foreignUlid('staff_id')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();

            $table->date('pay_period_start')->comment('Start of pay period');
            $table->date('pay_period_end')->comment('End of pay period');

            // Hours and rates
            $table->decimal('regular_hours', 5, 2)->nullable()->comment('Regular hours worked');
            $table->decimal('overtime_hours', 5, 2)->nullable()->comment('Overtime hours worked');

            // Money fields (using decimal for accuracy)
            $table->decimal('gross_pay', 10, 2)->comment('Gross pay amount');
            $table->decimal('deductions', 10, 2)->nullable()->comment('Total deductions');
            $table->decimal('net_pay', 10, 2)->comment('Net pay amount');

            $table->enum('status', ['draft', 'calculated', 'approved', 'paid'])->default('draft');

            $table->foreignUlid('processed_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamp('processed_at')->nullable()->comment('When payroll was processed');
            $table->text('notes')->nullable()->comment('Payroll notes');

            $table->timestamps();

            // Indexes
            $table->index(['staff_id', 'pay_period_start']);
            $table->index('status');
            $table->index('processed_by');
            $table->index(['pay_period_start', 'pay_period_end']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_payroll_records');
    }
};
