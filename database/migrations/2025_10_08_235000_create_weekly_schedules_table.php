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
        // Create weekly schedules master table
        Schema::create('weekly_schedules', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            // Week identification
            $table->date('week_start_date')->comment('Monday of the week (ISO week)');
            $table->date('week_end_date')->comment('Sunday of the week');
            $table->integer('year')->comment('Year of the schedule');
            $table->integer('week_number')->comment('ISO week number (1-53)');
            
            // Schedule metadata
            $table->string('name')->nullable()->comment('Custom name for this week (e.g., "Holiday Week", "Summer Schedule")');
            $table->text('description')->nullable()->comment('Notes about this week\'s schedule');
            
            // Template relationship
            $table->foreignUlid('template_id')->nullable()->constrained('weekly_rota_templates')->cascadeOnUpdate()->nullOnDelete();
            $table->boolean('is_template_applied')->default(false)->comment('Whether template has been applied to create assignments');
            
            // Status tracking
            $table->enum('status', [
                'draft',        // Being planned
                'published',    // Published to staff
                'active',       // Currently active week
                'completed',    // Week is finished
                'archived'      // Archived for historical purposes
            ])->default('draft');
            
            // Statistics (calculated fields for quick access)
            $table->integer('total_shifts')->default(0)->comment('Total number of shifts this week');
            $table->integer('total_staff_assignments')->default(0)->comment('Total staff assignments this week');
            $table->decimal('total_scheduled_hours', 8, 2)->default(0)->comment('Total scheduled hours this week');
            $table->decimal('estimated_labor_cost', 10, 2)->default(0)->comment('Estimated labor cost for this week');
            
            // Publication tracking
            $table->timestamp('published_at')->nullable()->comment('When this schedule was published to staff');
            $table->foreignUlid('published_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            
            // Audit fields
            $table->foreignUlid('created_by')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('updated_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for performance
            $table->unique(['year', 'week_number'], 'unique_year_week');
            $table->unique('week_start_date', 'unique_week_start');
            $table->index(['status', 'week_start_date']);
            $table->index('template_id');
            $table->index('created_by');
        });
        
        // Create weekly schedule assignments linking table
        Schema::create('weekly_schedule_assignments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            // Links to weekly schedule and staff shift assignment
            $table->foreignUlid('weekly_schedule_id')->constrained('weekly_schedules')->cascadeOnDelete();
            $table->foreignUlid('staff_shift_assignment_id')->constrained('staff_shift_assignments')->cascadeOnDelete();
            
            // Quick reference fields (denormalized for performance)
            $table->foreignUlid('staff_id')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('staff_shift_id')->constrained('staff_shifts')->cascadeOnUpdate()->restrictOnDelete();
            $table->date('assigned_date')->comment('Date of the shift assignment');
            $table->tinyInteger('day_of_week')->comment('0=Sunday, 1=Monday, ..., 6=Saturday');
            
            // Assignment metadata
            $table->enum('assignment_status', ['scheduled', 'confirmed', 'cancelled', 'completed'])->default('scheduled');
            $table->decimal('scheduled_hours', 5, 2)->nullable()->comment('Hours scheduled for this assignment');
            $table->decimal('hourly_rate', 8, 2)->nullable()->comment('Hourly rate at time of assignment');
            
            $table->timestamps();
            
            // Indexes
            $table->index(['weekly_schedule_id', 'day_of_week']);
            $table->index(['staff_id', 'assigned_date']);
            $table->index('staff_shift_assignment_id');
            $table->index('assignment_status');
            
            // Prevent duplicate assignments
            $table->unique(['weekly_schedule_id', 'staff_shift_assignment_id'], 'unique_weekly_assignment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_schedule_assignments');
        Schema::dropIfExists('weekly_schedules');
    }
};
