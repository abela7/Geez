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
        Schema::create('ingredient_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "liquid", "solid", "gas", "powder"
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->json('properties')->nullable(); // Store type-specific properties
            $table->string('measurement_type'); // e.g., "volume", "weight", "count"
            $table->json('compatible_units')->nullable(); // Array of compatible unit IDs
            $table->string('color_code', 7)->default('#6b7280');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'measurement_type']);
            $table->index(['sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredient_types');
    }
};