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
        Schema::table('staff_shifts', function (Blueprint $table) {
            // Add missing template fields
            $table->boolean('is_template')->default(true)->after('is_active')
                ->comment('Template shifts are reusable patterns, not scheduled shifts');
            
            $table->string('position_name')->nullable()->after('name')
                ->comment('Job position/role name (e.g., Head Chef, Waiter, Bartender)');
            
            // Add index for better querying
            $table->index(['department', 'shift_type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_shifts', function (Blueprint $table) {
            // Drop the columns
            $table->dropColumn(['is_template', 'position_name']);
            
            // Drop index
            $table->dropIndex(['department', 'shift_type', 'is_active']);
        });
    }
};
