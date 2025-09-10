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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_item_id')->constrained()->onDelete('cascade');
            
            // Movement Details
            $table->enum('type', [
                'received', 'issued', 'adjusted', 'transferred', 
                'wasted', 'returned', 'expired'
            ]);
            $table->decimal('quantity', 10, 3); // Can be negative for outgoing
            $table->decimal('unit_cost', 10, 2)->nullable();
            
            // Reference Information
            $table->string('reference_number')->nullable(); // PO number, transfer ID, etc.
            $table->string('reason')->nullable();
            $table->text('notes')->nullable();
            
            // Location Information
            $table->string('from_location')->nullable();
            $table->string('to_location')->nullable();
            
            // Tracking
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamp('movement_date');
            
            // Stock Levels After Movement (for audit trail)
            $table->decimal('stock_before', 10, 3);
            $table->decimal('stock_after', 10, 3);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['inventory_item_id', 'movement_date']);
            $table->index(['type']);
            $table->index(['movement_date']);
            $table->index(['reference_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
