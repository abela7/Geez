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
        if (Schema::hasTable('staff_payroll_tax_brackets')) {
            return;
        }

        Schema::create('staff_payroll_tax_brackets', function (Blueprint $table) {
            $table->ulid('id')->primary();

            // Tax Bracket Definition
            $table->string('name')->comment('Bracket name (e.g., "Standard Tax 2025")');
            $table->string('code', 50)->unique()->comment('Unique bracket code');
            $table->text('description')->nullable();
            
            // Tax Calculation
            $table->enum('tax_type', ['percentage', 'fixed', 'progressive', 'formula'])
                ->default('percentage')->comment('How tax is calculated');
            $table->decimal('tax_rate', 8, 4)->nullable()
                ->comment('Tax rate (e.g., 0.15 = 15% or fixed amount)');
            
            // Progressive Tax Brackets (JSON for flexibility)
            $table->json('brackets')->nullable()
                ->comment('Progressive tax brackets [{min: 0, max: 10000, rate: 0.10}, ...]');
            
            // Thresholds
            $table->decimal('minimum_income', 12, 2)->nullable()
                ->comment('Minimum income before tax applies');
            $table->decimal('maximum_income', 12, 2)->nullable()
                ->comment('Maximum income for this bracket');
            
            // Applicability
            $table->integer('tax_year')->nullable()->comment('Applicable tax year');
            $table->date('effective_from')->nullable()->comment('Effective start date');
            $table->date('effective_to')->nullable()->comment('Effective end date');
            
            // Status
            $table->boolean('is_active')->default(true)->comment('Is bracket active?');
            $table->boolean('is_default')->default(false)->comment('Is this the default bracket?');
            
            // Audit Fields
            $table->foreignUlid('created_by')->nullable()
                ->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('updated_by')->nullable()
                ->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('is_active');
            $table->index('tax_year');
            $table->index(['effective_from', 'effective_to']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_payroll_tax_brackets');
    }
};

