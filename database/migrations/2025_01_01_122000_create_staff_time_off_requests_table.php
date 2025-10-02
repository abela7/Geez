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
        Schema::create('staff_time_off_requests', function (Blueprint $table) {
            // Primary Key
            $table->ulid('id')->primary();

            // Request Details
            $table->foreignUlid('staff_id')->constrained('staff')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('type', [
                'vacation',     // Planned time off
                'sick',         // Sick leave
                'personal',     // Personal day
                'emergency',    // Emergency leave
                'bereavement',  // Bereavement leave
                'medical',      // Medical appointment
                'other',         // Other reason
            ]);

            // Request Status
            $table->enum('status', [
                'pending',      // Awaiting approval
                'approved',     // Approved by manager
                'denied',       // Denied by manager
                'cancelled',    // Cancelled by staff
                'expired',       // Request expired
            ])->default('pending');

            // Details & Justification
            $table->text('reason')->nullable(); // Why they need time off
            $table->text('notes')->nullable(); // Additional notes from staff
            $table->boolean('affects_shifts')->default(true); // Does this impact scheduled shifts?
            $table->boolean('replacement_needed')->default(true); // Do we need to find coverage?

            // Approval Process
            $table->foreignUlid('approved_by')->nullable()->constrained('staff')->onDelete('set null');
            $table->dateTime('approved_at')->nullable();
            $table->text('approval_notes')->nullable(); // Manager's notes on approval/denial

            // Impact Assessment
            $table->integer('affected_shifts_count')->default(0); // How many shifts this impacts
            $table->json('affected_shift_ids')->nullable(); // Array of shift assignment IDs

            // Timestamps & Soft Deletes
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['staff_id', 'status']);
            $table->index(['start_date', 'end_date']);
            $table->index(['type', 'status']);
            $table->index('approved_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_time_off_requests');
    }
};
