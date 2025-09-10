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
        Schema::create('recipe_instructions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained()->onDelete('cascade');
            $table->integer('step_number')->comment('Order of this instruction step');
            $table->text('instruction')->comment('The instruction text');
            $table->integer('duration')->nullable()->comment('Time for this step in minutes');
            $table->string('temperature')->nullable()->comment('Temperature if applicable (e.g., 350°F)');
            $table->text('tips')->nullable()->comment('Additional tips for this step');
            $table->string('image_path')->nullable()->comment('Path to step image');
            $table->timestamps();
            
            // Indexes
            $table->index(['recipe_id', 'step_number']);
            
            // Unique constraint to prevent duplicate step numbers in same recipe
            $table->unique(['recipe_id', 'step_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_instructions');
    }
};