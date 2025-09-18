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
        Schema::create('staff_tasks', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->string('title')->comment('Task title');
            $table->text('description')->nullable()->comment('Detailed task description');
            $table->enum('task_type', ['daily', 'weekly', 'monthly', 'one_time'])->comment('How often task repeats');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->boolean('is_active')->default(true)->comment('Whether task is active');
            
            $table->foreignUlid('created_by')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();
            
            // Indexes
            $table->index('task_type');
            $table->index('priority');
            $table->index('is_active');
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_tasks');
    }
};
