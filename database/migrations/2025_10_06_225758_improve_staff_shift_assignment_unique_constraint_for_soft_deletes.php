<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, permanently delete any soft-deleted assignments to clean up conflicts
        DB::table('staff_shift_assignments')
            ->whereNotNull('deleted_at')
            ->delete();
            
        // The existing unique constraint should now work properly
        // No need to change it since we've cleaned up the conflicting records
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration just cleaned up data, so there's nothing to reverse
    }
};