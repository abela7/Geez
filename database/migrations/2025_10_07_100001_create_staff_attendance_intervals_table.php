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
     * This table tracks precise pause/resume intervals for attendance.
     * Enables accurate calculation of work time vs break time.
     *
     * Example flow:
     * 1. Staff clocks in at 8:00 AM → interval created (type: work, start: 8:00)
     * 2. Staff takes emergency break at 10:30 AM → previous interval closed (end: 10:30), new interval created (type: break, start: 10:30)
     * 3. Staff resumes work at 10:45 AM → break interval closed (end: 10:45), new work interval created (start: 10:45)
     * 4. Staff clocks out at 4:00 PM → final work interval closed (end: 4:00)
     *
     * Result: Precise tracking of every working segment and break segment
     */
    public function up(): void
    {
        Schema::create('staff_attendance_intervals', function (Blueprint $table) {
            $table->ulid('id')->primary();

            // === RELATIONSHIPS ===
            $table->foreignUlid('staff_attendance_id')
                ->constrained('staff_attendance')->cascadeOnDelete()
                ->comment('Parent attendance record');

            $table->foreignUlid('staff_id')
                ->constrained('staff')->restrictOnDelete()
                ->comment('Staff member (denormalized for queries)');

            // === INTERVAL TYPE ===
            $table->enum('interval_type', [
                'work',             // Active working time
                'break',            // Break/pause time
                'emergency',        // Emergency leave (paid)
                'unauthorized',     // Unauthorized absence (unpaid)
            ])->comment('Type of interval');

            // === BREAK SUBCATEGORY (for breaks only) ===
            $table->enum('break_category', [
                'scheduled',        // Scheduled break (lunch, tea)
                'emergency',        // Emergency (family, medical)
                'restroom',         // Restroom break
                'personal',         // Personal reason
                'unauthorized',     // No reason / not approved
            ])->nullable()->comment('Break reason (if interval_type is break)');

            // === TIME TRACKING ===
            $table->timestamp('start_time')
                ->comment('When this interval started');

            $table->timestamp('end_time')->nullable()
                ->comment('When this interval ended (null = ongoing)');

            $table->integer('duration_minutes')->nullable()
                ->comment('Calculated duration in minutes');

            // === METADATA ===
            $table->text('reason')->nullable()
                ->comment('Reason for break/pause');

            $table->boolean('is_approved')->default(false)
                ->comment('Is this interval approved by manager?');

            $table->foreignUlid('approved_by')->nullable()
                ->constrained('staff')->onDelete('set null')
                ->comment('Who approved this interval');

            $table->timestamp('approved_at')->nullable()
                ->comment('When interval was approved');

            $table->text('approval_notes')->nullable()
                ->comment('Manager notes on approval');

            // === DEVICE TRACKING (for each interval) ===
            $table->json('start_device_info')->nullable()
                ->comment('Device info when interval started');

            $table->json('end_device_info')->nullable()
                ->comment('Device info when interval ended');

            // === AUDIT ===
            $table->foreignUlid('created_by')->nullable()
                ->constrained('staff')->restrictOnDelete()
                ->comment('Who created this interval (system or admin)');

            $table->timestamps();
            $table->softDeletes();

            // === PERFORMANCE INDICES ===
            // For querying all intervals of an attendance record
            $table->index(['staff_attendance_id', 'start_time'], 'idx_attendance_intervals');

            // For querying staff's intervals across date ranges
            $table->index(['staff_id', 'start_time', 'end_time'], 'idx_staff_intervals');

            // For finding ongoing/active intervals
            $table->index(['staff_id', 'end_time'], 'idx_active_intervals');

            // For interval type analysis
            $table->index('interval_type', 'idx_interval_type');

            // For break analysis
            $table->index(['interval_type', 'break_category'], 'idx_break_analysis');

            // For approval workflow
            $table->index(['is_approved', 'approved_at'], 'idx_approval_status');

            // For payroll calculation (work intervals only)
            $table->index(['staff_id', 'interval_type', 'start_time'], 'idx_payroll_calc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_attendance_intervals');
    }
};
