<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('task_tags', function (Blueprint $table) {
            // Drop the unique constraint on name field to allow duplicate names
            // Laravel typically names unique constraints as {table}_{column}_unique
            try {
                $table->dropUnique('task_tags_name_unique');
            } catch (\Exception $e) {
                // If the above fails, try alternative constraint names
                try {
                    $table->dropUnique(['name']);
                } catch (\Exception $e2) {
                    // Log the error but don't fail the migration
                    \Log::warning('Could not drop unique constraint on task_tags.name: ' . $e2->getMessage());
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_tags', function (Blueprint $table) {
            // Add back the unique constraint on name field
            $table->unique('name');
        });
    }
};
