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
        // 1. Create task_types table
        Schema::create('task_types', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#6B7280'); // Hex color code
            $table->string('icon')->nullable(); // Icon class or name
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->foreignUlid('created_by')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('updated_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['is_active', 'sort_order']);
            $table->index('slug');
        });

        // 2. Create task_priorities table
        Schema::create('task_priorities', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#6B7280'); // Hex color code
            $table->string('icon')->nullable(); // Icon class or name
            $table->integer('level')->unique(); // 1=lowest, 4=highest priority
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->foreignUlid('created_by')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('updated_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['is_active', 'level']);
            $table->index('slug');
        });

        // 3. Create task_categories table
        Schema::create('task_categories', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#6B7280'); // Hex color code
            $table->string('icon')->nullable(); // Icon class or name
            $table->foreignUlid('parent_id')->nullable()->constrained('task_categories')->cascadeOnUpdate()->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->foreignUlid('created_by')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('updated_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['is_active', 'sort_order']);
            $table->index(['parent_id', 'sort_order']);
            $table->index('slug');
        });

        // 4. Create task_tags table
        Schema::create('task_tags', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#6B7280'); // Hex color code
            $table->boolean('is_active')->default(true);
            $table->integer('usage_count')->default(0); // Track how often used
            $table->foreignUlid('created_by')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('updated_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['is_active', 'usage_count']);
            $table->index('slug');
        });

        // 5. Create task_statuses table
        Schema::create('task_statuses', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#6B7280'); // Hex color code
            $table->string('icon')->nullable(); // Icon class or name
            $table->enum('type', ['pending', 'active', 'completed', 'cancelled'])->default('pending');
            $table->boolean('is_final')->default(false); // Cannot change from this status
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->foreignUlid('created_by')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('updated_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['is_active', 'type', 'sort_order']);
            $table->index('slug');
        });

        // 6. Create task_templates table
        Schema::create('task_templates', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->json('template_data'); // JSON structure of the template
            $table->foreignUlid('task_type_id')->nullable()->constrained('task_types')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignUlid('task_category_id')->nullable()->constrained('task_categories')->cascadeOnUpdate()->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_public')->default(false); // Can other staff use this template
            $table->integer('usage_count')->default(0);
            $table->foreignUlid('created_by')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('updated_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['is_active', 'is_public']);
            $table->index(['created_by', 'is_active']);
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_templates');
        Schema::dropIfExists('task_statuses');
        Schema::dropIfExists('task_tags');
        Schema::dropIfExists('task_categories');
        Schema::dropIfExists('task_priorities');
        Schema::dropIfExists('task_types');
    }
};
