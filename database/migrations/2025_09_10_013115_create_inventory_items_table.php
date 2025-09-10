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
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique(); // SKU/Item Code
            $table->string('barcode')->nullable();
            $table->text('description')->nullable();
            
            // Category and Classification
            $table->enum('category', [
                'ingredients', 'beverages', 'supplies', 
                'packaging', 'cleaning', 'equipment'
            ]);
            $table->string('subcategory')->nullable();
            
            // Unit and Measurement
            $table->string('unit'); // kg, g, L, ml, pieces, boxes, etc.
            $table->json('conversion_rates')->nullable(); // e.g., {"kg": 1000, "g": 1}
            
            // Stock Levels
            $table->decimal('current_stock', 10, 3)->default(0);
            $table->decimal('reserved_stock', 10, 3)->default(0); // Allocated for orders
            $table->decimal('reorder_level', 10, 3);
            $table->decimal('max_level', 10, 3)->nullable();
            $table->decimal('minimum_order_qty', 10, 3)->nullable();
            
            // Pricing
            $table->decimal('cost_per_unit', 10, 2);
            $table->decimal('selling_price', 10, 2)->nullable();
            
            // Location and Storage
            $table->enum('location', [
                'main_kitchen', 'cold_storage', 'dry_storage', 
                'freezer', 'bar', 'prep_area'
            ]);
            $table->string('storage_requirements')->nullable();
            $table->integer('shelf_life_days')->nullable();
            
            // Supplier Information
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            
            // Additional Information
            $table->json('allergen_info')->nullable(); // e.g., ["gluten", "dairy"]
            $table->enum('status', ['active', 'inactive', 'discontinued'])->default('active');
            
            // Tracking
            $table->timestamp('last_stock_update')->nullable();
            $table->decimal('average_daily_usage', 10, 3)->nullable();
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['category', 'status']);
            $table->index(['location']);
            $table->index(['current_stock', 'reorder_level']);
            $table->index(['name']);
            $table->index(['code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
