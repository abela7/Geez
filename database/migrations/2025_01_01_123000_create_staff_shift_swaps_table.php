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
        Schema::create('staff_shift_swaps', function (Blueprint $table) {
            // Primary Key
            $table->ulid('id')->primary();
            
            // Swap Participants
            $table->foreignUlid('requesting_staff_id')->constrained('staff')->onDelete('cascade');
            $table->foreignUlid('target_staff_id')->nullable()->constrained('staff')->onDelete('cascade');
            
            // Shift Details
            $table->foreignUlid('original_assignment_id')->constrained('staff_shift_assignments')->onDelete('cascade');
            $table->foreignUlid('proposed_assignment_id')->nullable()->constrained('staff_shift_assignments')->onDelete('cascade');
            
            // Swap Type
            $table->enum('swap_type', [
                'direct',       // Direct swap between two specific people
                'open',         // Open request for anyone to pick up
                'coverage',     // Just need someone to cover (no swap back)
                'trade'         // Trading shifts on different days
            ])->default('direct');
            
            // Request Status
            $table->enum('status', [
                'pending',          // Waiting for target staff response
                'target_accepted',  // Target staff accepted, waiting manager approval
                'approved',         // Manager approved the swap
                'denied',           // Manager or target staff denied
                'cancelled',        // Requesting staff cancelled
                'completed',        // Swap completed successfully
                'expired'           // Request expired
            ])->default('pending');
            
            // Communication
            $table->text('reason')->nullable(); // Why they want to swap
            $table->text('message_to_target')->nullable(); // Message to the target staff
            $table->text('notes')->nullable(); // Additional notes
            
            // Approval Process
            $table->foreignUlid('approved_by')->nullable()->constrained('staff')->onDelete('set null');
            $table->dateTime('approved_at')->nullable();
            $table->text('approval_notes')->nullable();
            
            // Target Response
            $table->dateTime('target_responded_at')->nullable();
            $table->text('target_response_notes')->nullable();
            
            // Urgency & Priority
            $table->enum('urgency', ['low', 'normal', 'high', 'emergency'])->default('normal');
            $table->dateTime('expires_at')->nullable(); // When this request expires
            
            // Timestamps & Soft Deletes
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['requesting_staff_id', 'status']);
            $table->index(['target_staff_id', 'status']);
            $table->index(['status', 'urgency']);
            $table->index('original_assignment_id');
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_shift_swaps');
    }
};
