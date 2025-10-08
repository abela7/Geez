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
        Schema::create('staff_shift_exceptions', function (Blueprint $table) {
            // Primary Key
            $table->ulid('id')->primary();

            // Exception Details
            $table->foreignUlid('assignment_id')->constrained('staff_shift_assignments')->onDelete('cascade');
            $table->enum('exception_type', [
                'late_arrival',     // Staff arrived late
                'early_departure',  // Staff left early
                'extended_break',   // Break was longer than scheduled
                'no_show',          // Staff didn't show up
                'sick_call_out',    // Called in sick
                'emergency_leave',  // Emergency during shift
                'overtime',         // Worked beyond scheduled time
                'role_change',      // Changed role during shift
                'replacement',      // Someone else covered the shift
                'other',             // Other exception
            ]);

            // Exception Impact
            $table->integer('minutes_affected')->default(0); // How many minutes were impacted
            $table->decimal('financial_impact', 8, 2)->default(0.00); // Cost impact (overtime, lost productivity)

            // Replacement Staff (if applicable)
            $table->foreignUlid('replacement_staff_id')->nullable()->constrained('staff')->onDelete('set null');
            $table->dateTime('replacement_start_time')->nullable();
            $table->dateTime('replacement_end_time')->nullable();

            // Documentation
            $table->text('description'); // What happened
            $table->text('action_taken')->nullable(); // What was done about it
            $table->json('evidence')->nullable(); // Photos, documents, etc. (file paths)

            // Approval & Review
            $table->enum('status', [
                'reported',     // Exception was reported
                'under_review', // Being reviewed by management
                'approved',     // Exception approved/accepted
                'disputed',     // Staff disputes the exception
                'resolved',     // Dispute resolved
                'closed',        // Exception closed
            ])->default('reported');

            $table->foreignUlid('reported_by')->constrained('staff')->onDelete('cascade');
            $table->foreignUlid('approved_by')->nullable()->constrained('staff')->onDelete('set null');
            $table->dateTime('approved_at')->nullable();

            // Follow-up Actions
            $table->boolean('requires_disciplinary_action')->default(false);
            $table->boolean('affects_payroll')->default(false);
            $table->text('follow_up_notes')->nullable();

            // Timestamps & Soft Deletes
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['assignment_id', 'exception_type']);
            $table->index(['status', 'requires_disciplinary_action']);
            $table->index('reported_by');
            $table->index('approved_by');
            $table->index('replacement_staff_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_shift_exceptions');
    }
};
