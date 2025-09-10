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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique()->comment('Unique recipe identifier (e.g., RCP001)');
            $table->text('description')->nullable();
            $table->enum('category', [
                'appetizer', 'main_course', 'dessert', 'beverage', 
                'sauce', 'side_dish', 'soup', 'salad'
            ])->default('main_course');
            $table->integer('serving_size')->comment('Number of servings this recipe makes');
            $table->integer('prep_time')->nullable()->comment('Preparation time in minutes');
            $table->integer('cook_time')->nullable()->comment('Cooking time in minutes');
            $table->integer('total_time')->nullable()->comment('Total time in minutes');
            $table->enum('difficulty', ['easy', 'medium', 'hard', 'expert'])->default('medium');
            $table->decimal('cost_per_serving', 10, 2)->nullable()->comment('Calculated cost per serving');
            $table->decimal('total_cost', 10, 2)->nullable()->comment('Total recipe cost');
            $table->string('yield')->nullable()->comment('Recipe yield (e.g., 2 cups, 1 loaf)');
            $table->enum('status', ['active', 'inactive', 'draft', 'testing'])->default('draft');
            
            // Nutrition information (optional)
            $table->integer('calories')->nullable()->comment('Calories per serving');
            $table->decimal('protein', 8, 2)->nullable()->comment('Protein in grams per serving');
            $table->decimal('carbs', 8, 2)->nullable()->comment('Carbohydrates in grams per serving');
            $table->decimal('fat', 8, 2)->nullable()->comment('Fat in grams per serving');
            $table->decimal('fiber', 8, 2)->nullable()->comment('Fiber in grams per serving');
            $table->integer('sodium')->nullable()->comment('Sodium in mg per serving');
            
            // Additional metadata
            $table->text('notes')->nullable()->comment('Additional recipe notes');
            $table->json('tags')->nullable()->comment('Recipe tags for categorization');
            $table->string('image_path')->nullable()->comment('Path to recipe image');
            
            $table->timestamps();
            
            // Indexes
            $table->index('category');
            $table->index('status');
            $table->index('difficulty');
            $table->index(['status', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};