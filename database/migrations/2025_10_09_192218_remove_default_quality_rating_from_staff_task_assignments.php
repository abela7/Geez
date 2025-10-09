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
        Schema::table('staff_task_assignments', function (Blueprint $table) {
            // Remove the default value from quality_rating column
            $table->enum('quality_rating', ['bad', 'good', 'excellent'])
                  ->nullable()
                  ->change()
                  ->comment('Admin rating of task completion quality');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_task_assignments', function (Blueprint $table) {
            // Restore the default value
            $table->enum('quality_rating', ['bad', 'good', 'excellent'])
                  ->default('good')
                  ->change()
                  ->comment('Admin rating of task completion quality');
        });
    }
};