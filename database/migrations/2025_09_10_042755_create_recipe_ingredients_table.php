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
        Schema::create('recipe_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained()->onDelete('cascade');
            $table->foreignId('ingredient_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity', 10, 4)->comment('Amount of ingredient needed');
            $table->string('unit')->comment('Unit of measurement (kg, L, cups, etc.)');
            $table->decimal('cost', 10, 2)->nullable()->comment('Cost of this ingredient in this recipe');
            $table->text('notes')->nullable()->comment('Special notes for this ingredient');
            $table->integer('sort_order')->default(0)->comment('Order of ingredients in recipe');
            $table->boolean('is_optional')->default(false)->comment('Whether ingredient is optional');
            $table->string('preparation')->nullable()->comment('How to prepare ingredient (diced, chopped, etc.)');
            $table->timestamps();
            
            // Indexes
            $table->index(['recipe_id', 'sort_order']);
            $table->index('ingredient_id');
            
            // Unique constraint to prevent duplicate ingredients in same recipe
            $table->unique(['recipe_id', 'ingredient_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_ingredients');
    }
};