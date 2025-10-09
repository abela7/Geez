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
        if (Schema::hasTable('staff_payroll_settings')) {
            return;
        }

        Schema::create('staff_payroll_settings', function (Blueprint $table) {
            $table->ulid('id')->primary();

            // Payroll Configuration
            $table->string('name')->comment('Configuration name/label');
            $table->enum('pay_frequency', ['weekly', 'biweekly', 'monthly'])->default('monthly')
                ->comment('Default pay frequency');
            
            // Overtime Rules
            $table->decimal('overtime_threshold_hours', 5, 2)->default(40.00)
                ->comment('Hours before overtime applies');
            $table->decimal('overtime_multiplier', 3, 2)->default(1.50)
                ->comment('Overtime rate multiplier (1.50 = 150%)');
            
            // Currency & Locale
            $table->string('currency_code', 3)->default('USD')->comment('ISO currency code');
            $table->string('locale', 10)->default('en_US')->comment('Locale for formatting');
            
            // Tax Configuration
            $table->integer('tax_year')->nullable()->comment('Applicable tax year');
            $table->boolean('auto_calculate_tax')->default(true)
                ->comment('Automatically calculate taxes');
            
            // Rounding Policy
            $table->enum('rounding_mode', ['up', 'down', 'nearest'])->default('nearest')
                ->comment('How to round monetary values');
            $table->integer('rounding_precision')->default(2)
                ->comment('Decimal places for rounding');
            
            // Status
            $table->boolean('is_active')->default(true)->comment('Is this configuration active?');
            $table->boolean('is_default')->default(false)->comment('Is this the default configuration?');
            
            // Audit Fields
            $table->foreignUlid('created_by')->nullable()
                ->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('updated_by')->nullable()
                ->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('is_active');
            $table->index('is_default');
            $table->index('pay_frequency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_payroll_settings');
    }
};

