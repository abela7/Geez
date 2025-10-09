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
        if (Schema::hasTable('staff_payroll_templates')) {
            return;
        }

        Schema::create('staff_payroll_templates', function (Blueprint $table) {
            $table->ulid('id')->primary();

            // Template Identification
            $table->string('name')->comment('Template name (e.g., "Standard Monthly")');
            $table->string('code', 50)->unique()->comment('Unique template code');
            $table->text('description')->nullable()->comment('Template description');
            
            // Rate Configuration
            $table->enum('allocation_method', ['hourly', 'salaried', 'commission', 'hybrid'])
                ->default('hourly')->comment('How pay is calculated');
            $table->decimal('base_hourly_rate', 8, 2)->nullable()
                ->comment('Default base rate if not set on staff');
            $table->decimal('overtime_rate', 5, 2)->default(1.50)
                ->comment('Overtime multiplier (1.50 = 150%)');
            
            // Tax & Deduction Policy
            $table->foreignUlid('tax_policy_id')->nullable()
                ->constrained('staff_payroll_tax_brackets')->cascadeOnUpdate()->nullOnDelete()
                ->comment('Default tax bracket to apply');
            $table->boolean('auto_apply_deductions')->default(true)
                ->comment('Automatically apply standard deductions');
            
            // Currency
            $table->string('currency', 3)->default('USD')->comment('Currency code');
            
            // Template Metadata
            $table->boolean('is_active')->default(true)->comment('Is template active?');
            $table->boolean('is_default')->default(false)->comment('Is this the default template?');
            $table->integer('sort_order')->default(0)->comment('Display order');
            
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
            $table->index('allocation_method');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_payroll_templates');
    }
};

