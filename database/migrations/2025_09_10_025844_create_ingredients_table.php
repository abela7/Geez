<?php

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
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            
            // Basic Information
            $table->string('name');
            $table->string('code')->unique()->comment('Unique ingredient code/SKU');
            $table->text('description')->nullable();
            $table->string('category');
            $table->string('subcategory')->nullable();
            
            // Measurement & Costing
            $table->string('unit'); // kg, g, l, ml, pieces, etc.
            $table->json('conversion_rates')->nullable()->comment('Unit conversion rates');
            $table->decimal('cost_per_unit', 10, 4)->default(0);
            $table->decimal('minimum_order_qty', 10, 2)->default(1);
            
            // Supplier Information
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('set null');
            $table->integer('lead_time_days')->default(0);
            
            // Storage & Shelf Life
            $table->string('storage_requirements')->default('ambient');
            $table->integer('shelf_life_days')->nullable();
            $table->string('origin_country')->nullable();
            
            // Nutritional Information (per 100g)
            $table->json('nutritional_info')->nullable()->comment('Calories, protein, carbs, fat, etc.');
            
            // Allergen Information
            $table->json('allergen_info')->nullable()->comment('List of allergens present');
            
            // Status & Metadata
            $table->enum('status', ['active', 'inactive', 'discontinued'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamp('last_updated')->nullable();
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['status']);
            $table->index(['category']);
            $table->index(['supplier_id']);
            $table->index(['name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
