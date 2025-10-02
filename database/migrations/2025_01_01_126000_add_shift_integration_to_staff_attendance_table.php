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
        Schema::table('staff_attendance', function (Blueprint $table) {
            // Link attendance to shift assignments
            $table->foreignUlid('shift_assignment_id')->nullable()->after('staff_id')
                ->constrained('staff_shift_assignments')->onDelete('set null');

            // Shift-specific tracking
            $table->boolean('was_scheduled')->default(false)->after('notes'); // Was this person scheduled to work?
            $table->enum('shift_compliance', [
                'on_time',      // Arrived on time, left on time
                'late_arrival', // Arrived late
                'early_departure', // Left early
                'overtime',     // Worked longer than scheduled
                'unscheduled',  // Worked but wasn't scheduled
                'no_show',       // Scheduled but didn't show
            ])->nullable()->after('was_scheduled');

            // Performance metrics for shifts
            $table->integer('scheduled_minutes')->nullable()->after('shift_compliance'); // How long they were supposed to work
            $table->integer('actual_minutes')->nullable()->after('scheduled_minutes'); // How long they actually worked
            $table->integer('variance_minutes')->default(0)->after('actual_minutes'); // Difference (+ = overtime, - = short)

            // Add indexes for performance
            $table->index('shift_assignment_id');
            $table->index(['was_scheduled', 'shift_compliance']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_attendance', function (Blueprint $table) {
            $table->dropForeign(['shift_assignment_id']);
            $table->dropIndex(['shift_assignment_id']);
            $table->dropIndex(['was_scheduled', 'shift_compliance']);

            $table->dropColumn([
                'shift_assignment_id',
                'was_scheduled',
                'shift_compliance',
                'scheduled_minutes',
                'actual_minutes',
                'variance_minutes',
            ]);
        });
    }
};
