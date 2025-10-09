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
            $table->enum('quality_rating', ['bad', 'good', 'excellent'])
                  ->default('good')
                  ->after('status')
                  ->comment('Admin rating of task completion quality');
            $table->string('quality_rating_by')->nullable()->after('quality_rating');
            $table->timestamp('quality_rating_at')->nullable()->after('quality_rating_by');
            $table->text('quality_rating_notes')->nullable()->after('quality_rating_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_task_assignments', function (Blueprint $table) {
            $table->dropColumn(['quality_rating', 'quality_rating_by', 'quality_rating_at', 'quality_rating_notes']);
        });
    }
};
