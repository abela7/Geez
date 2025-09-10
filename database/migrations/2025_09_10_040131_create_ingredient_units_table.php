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
        Schema::create('ingredient_units', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "kilogram", "liter", "spoon", "can"
            $table->string('symbol'); // e.g., "kg", "L", "spoon", "can"
            $table->string('type'); // e.g., "weight", "volume", "count", "custom"
            $table->text('description')->nullable();
            $table->decimal('base_conversion_factor', 10, 4)->nullable(); // For converting to base unit
            $table->string('base_unit')->nullable(); // Reference to base unit (e.g., "gram" for "kg")
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'type']);
            $table->index(['sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredient_units');
    }
};