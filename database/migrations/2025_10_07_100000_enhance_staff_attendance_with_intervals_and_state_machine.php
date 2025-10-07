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
     * This migration enhances the attendance system with:
     * - State machine for precise status tracking
     * - Break/pause tracking fields
     * - Payroll integration
     * - Auto-close and review flags
     * - Device/location tracking
     * - Performance-optimized indices
     */
    public function up(): void
    {
        Schema::table('staff_attendance', function (Blueprint $table) {
            // === STATE MACHINE ===
            $table->enum('current_state', [
                'clocked_in',       // Currently working
                'on_break',         // Temporarily paused
                'clocked_out',      // Completed shift
                'auto_closed',      // System auto-closed (forgot to clock out)
                'cancelled',        // Cancelled by admin
            ])->default('clocked_in')->after('status');

            $table->enum('previous_state', [
                'clocked_in',
                'on_break',
                'clocked_out',
                'auto_closed',
                'cancelled',
            ])->nullable()->after('current_state')
                ->comment('Previous state before transition');

            $table->timestamp('state_changed_at')->nullable()->after('previous_state')
                ->comment('When state last changed');

            // === BREAK TRACKING ===
            $table->integer('total_break_minutes')->default(0)->after('hours_worked')
                ->comment('Total break time in minutes');

            $table->decimal('net_hours_worked', 5, 2)->nullable()->after('total_break_minutes')
                ->comment('Hours worked minus breaks');

            $table->boolean('is_currently_on_break')->default(false)->after('net_hours_worked')
                ->comment('Is staff currently on break?');

            $table->timestamp('current_break_start')->nullable()->after('is_currently_on_break')
                ->comment('When current break started');

            $table->integer('break_count')->default(0)->after('current_break_start')
                ->comment('Number of breaks taken');

            // === PAYROLL INTEGRATION ===
            $table->foreignUlid('payroll_record_id')->nullable()->after('shift_assignment_id')
                ->constrained('staff_payroll_records')->onDelete('set null')
                ->comment('Link to payroll record');

            $table->boolean('is_paid')->default(false)->after('payroll_record_id')
                ->comment('Has this attendance been paid?');

            $table->timestamp('paid_at')->nullable()->after('is_paid')
                ->comment('When this attendance was paid');

            // === REVIEW & AUTO-CLOSE FLAGS ===
            $table->boolean('review_needed')->default(false)->after('variance_minutes')
                ->comment('Needs manager review (overtime, auto-close, etc.)');

            $table->text('review_reason')->nullable()->after('review_needed')
                ->comment('Why review is needed');

            $table->foreignUlid('reviewed_by')->nullable()->after('review_reason')
                ->constrained('staff')->onDelete('set null')
                ->comment('Who reviewed this record');

            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by')
                ->comment('When record was reviewed');

            $table->boolean('was_auto_closed')->default(false)->after('reviewed_at')
                ->comment('Was this session auto-closed by system?');

            $table->timestamp('auto_closed_at')->nullable()->after('was_auto_closed')
                ->comment('When system auto-closed this session');

            // === DEVICE & LOCATION TRACKING ===
            $table->json('device_info')->nullable()->after('notes')
                ->comment('Device used for clock in/out (browser, OS, IP)');

            $table->decimal('clock_in_lat', 10, 8)->nullable()->after('device_info')
                ->comment('Latitude of clock-in location');

            $table->decimal('clock_in_lng', 11, 8)->nullable()->after('clock_in_lat')
                ->comment('Longitude of clock-in location');

            $table->decimal('clock_out_lat', 10, 8)->nullable()->after('clock_in_lng')
                ->comment('Latitude of clock-out location');

            $table->decimal('clock_out_lng', 11, 8)->nullable()->after('clock_out_lat')
                ->comment('Longitude of clock-out location');

            // === PERFORMANCE INDICES ===
            // For state machine queries
            $table->index('current_state', 'idx_current_state');
            $table->index(['staff_id', 'current_state'], 'idx_staff_state');

            // For break tracking queries
            $table->index('is_currently_on_break', 'idx_on_break');

            // For payroll queries
            $table->index(['payroll_record_id', 'is_paid'], 'idx_payroll_status');
            $table->index(['staff_id', 'clock_in', 'is_paid'], 'idx_staff_unpaid');

            // For review workflow
            $table->index(['review_needed', 'reviewed_at'], 'idx_needs_review');

            // For auto-close monitoring
            $table->index(['current_state', 'clock_in'], 'idx_active_sessions');

            // For reporting (date range + staff queries)
            $table->index(['staff_id', 'clock_in', 'clock_out'], 'idx_date_range_queries');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_attendance', function (Blueprint $table) {
            // Drop indices
            $table->dropIndex('idx_current_state');
            $table->dropIndex('idx_staff_state');
            $table->dropIndex('idx_on_break');
            $table->dropIndex('idx_payroll_status');
            $table->dropIndex('idx_staff_unpaid');
            $table->dropIndex('idx_needs_review');
            $table->dropIndex('idx_active_sessions');
            $table->dropIndex('idx_date_range_queries');

            // Drop foreign keys
            $table->dropForeign(['payroll_record_id']);
            $table->dropForeign(['reviewed_by']);

            // Drop columns
            $table->dropColumn([
                // State machine
                'current_state',
                'previous_state',
                'state_changed_at',

                // Break tracking
                'total_break_minutes',
                'net_hours_worked',
                'is_currently_on_break',
                'current_break_start',
                'break_count',

                // Payroll integration
                'payroll_record_id',
                'is_paid',
                'paid_at',

                // Review & auto-close
                'review_needed',
                'review_reason',
                'reviewed_by',
                'reviewed_at',
                'was_auto_closed',
                'auto_closed_at',

                // Device & location
                'device_info',
                'clock_in_lat',
                'clock_in_lng',
                'clock_out_lat',
                'clock_out_lng',
            ]);
        });
    }
};
