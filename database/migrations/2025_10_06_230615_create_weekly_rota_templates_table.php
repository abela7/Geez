<?php

declare(strict_types=1);

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
        // Create weekly rota templates table
        Schema::create('weekly_rota_templates', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            // Template details
            $table->string('name')->comment('Template name (e.g., "Standard Week", "Holiday Schedule")');
            $table->text('description')->nullable()->comment('Description of this rota template');
            
            // Template metadata
            $table->enum('type', ['standard', 'holiday', 'seasonal', 'custom'])->default('standard');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false)->comment('Default template for new weeks');
            
            // Usage tracking
            $table->integer('usage_count')->default(0)->comment('How many times this template has been applied');
            $table->timestamp('last_used_at')->nullable();
            
            // Audit fields
            $table->foreignUlid('created_by')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('updated_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['is_active', 'type']);
            $table->index('created_by');
        });
        
        // Create weekly rota template assignments table (the actual assignments in the template)
        Schema::create('weekly_rota_template_assignments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            // Link to template and shift
            $table->foreignUlid('template_id')->constrained('weekly_rota_templates')->cascadeOnDelete();
            $table->foreignUlid('staff_shift_id')->constrained('staff_shifts')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('staff_id')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            
            // Day of week (0 = Sunday, 1 = Monday, etc.)
            $table->tinyInteger('day_of_week')->comment('0=Sunday, 1=Monday, ..., 6=Saturday');
            
            // Assignment details
            $table->enum('status', ['scheduled', 'confirmed', 'optional'])->default('scheduled');
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['template_id', 'day_of_week']);
            $table->index('staff_shift_id');
            $table->index('staff_id');
            
            // Prevent duplicate assignments
            $table->unique(['template_id', 'staff_id', 'staff_shift_id', 'day_of_week'], 'unique_template_assignment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_rota_template_assignments');
        Schema::dropIfExists('weekly_rota_templates');
    }
};