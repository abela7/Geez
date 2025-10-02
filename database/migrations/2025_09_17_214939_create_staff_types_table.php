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
        Schema::create('staff_types', function (Blueprint $table) {
            // Primary Key - Using ULID for offline/online sync compatibility
            $table->ulid('id')->primary();

            // Staff Type Information
            $table->string('name')->unique()->comment('Internal name (system_admin, administrator, etc.)');
            $table->string('display_name')->comment('Human readable name');
            $table->text('description')->nullable()->comment('Role description and responsibilities');

            // Status and Configuration
            $table->boolean('is_active')->default(true)->comment('Whether this staff type is active');
            $table->integer('priority')->default(0)->comment('Priority level for access control (higher = more access)');

            // Metadata
            $table->timestamps();

            // Indexes for performance
            $table->index(['name']);
            $table->index(['is_active']);
            $table->index(['priority']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_types');
    }
};
