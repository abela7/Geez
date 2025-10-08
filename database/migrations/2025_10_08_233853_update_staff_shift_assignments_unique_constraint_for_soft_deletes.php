<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Since MySQL/MariaDB doesn't support partial unique indexes,
     * we remove the unique constraint and handle duplicates in application code.
     * The controller already checks for existing assignments before creating.
     */
    public function up(): void
    {
        // Check if the unique index exists before trying to drop it
        $indexes = DB::select("SHOW INDEXES FROM staff_shift_assignments WHERE Key_name = 'unique_staff_shift_assignment'");
        
        if (count($indexes) > 0) {
            Schema::table('staff_shift_assignments', function (Blueprint $table) {
                // Drop the unique constraint that causes issues with soft deletes
                $table->dropUnique('unique_staff_shift_assignment');
            });
        }

        // Check if our index already exists
        $existingIndex = DB::select("SHOW INDEXES FROM staff_shift_assignments WHERE Key_name = 'idx_staff_shift_assignment'");
        
        if (count($existingIndex) == 0) {
            // Add a regular index for performance (non-unique)
            Schema::table('staff_shift_assignments', function (Blueprint $table) {
                $table->index(['staff_id', 'assigned_date', 'staff_shift_id'], 'idx_staff_shift_assignment');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the index
        Schema::table('staff_shift_assignments', function (Blueprint $table) {
            $table->dropIndex('idx_staff_shift_assignment');
        });

        // Restore the old unique constraint
        Schema::table('staff_shift_assignments', function (Blueprint $table) {
            $table->unique(['staff_id', 'assigned_date', 'staff_shift_id'], 'unique_staff_shift_assignment');
        });
    }
};
