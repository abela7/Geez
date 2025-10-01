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
        // Fix column sizes in staff_tasks table
        Schema::table('staff_tasks', function (Blueprint $table) {
            // Increase task_type column size to handle longer slugs like 'one-time-tasks'
            $table->string('task_type', 100)->change();
            
            // Increase priority column size to handle longer slugs
            $table->string('priority', 50)->change();
            
            // Increase category column size to handle longer slugs
            $table->string('category', 100)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_tasks', function (Blueprint $table) {
            // Revert to original smaller sizes (if they were smaller)
            $table->string('task_type', 50)->change();
            $table->string('priority', 20)->change();
            $table->string('category', 50)->nullable()->change();
        });
    }
};