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
        Schema::table('staff_shifts', function (Blueprint $table) {
            // Add missing columns based on the form data we received
            
            // Position and department info
            if (!Schema::hasColumn('staff_shifts', 'position_name')) {
                $table->string('position_name')->nullable()->after('name')
                    ->comment('Position name (e.g., Chef, Waiter)');
            }
            
            if (!Schema::hasColumn('staff_shifts', 'department')) {
                $table->string('department')->nullable()->after('position_name')
                    ->comment('Department (e.g., Kitchen, Bar, Front of House)');
            }
            
            // Shift type
            if (!Schema::hasColumn('staff_shifts', 'shift_type')) {
                $table->string('shift_type')->nullable()->after('department')
                    ->comment('Shift type slug (e.g., main-chef, waiter)');
            }
            
            // Description
            if (!Schema::hasColumn('staff_shifts', 'description')) {
                $table->text('description')->nullable()->after('shift_type')
                    ->comment('Shift description');
            }
            
            // Rename break_duration to break_minutes if needed
            if (Schema::hasColumn('staff_shifts', 'break_duration') && !Schema::hasColumn('staff_shifts', 'break_minutes')) {
                $table->renameColumn('break_duration', 'break_minutes');
            } elseif (!Schema::hasColumn('staff_shifts', 'break_minutes')) {
                $table->integer('break_minutes')->default(0)->after('end_time')
                    ->comment('Break duration in minutes');
            }
            
            // Staffing requirements
            if (!Schema::hasColumn('staff_shifts', 'min_staff_required')) {
                $table->integer('min_staff_required')->default(1)->after('break_minutes')
                    ->comment('Minimum staff required for this shift');
            }
            
            if (!Schema::hasColumn('staff_shifts', 'max_staff_allowed')) {
                $table->integer('max_staff_allowed')->nullable()->after('min_staff_required')
                    ->comment('Maximum staff allowed for this shift');
            }
            
            // Rate multiplier
            if (!Schema::hasColumn('staff_shifts', 'hourly_rate_multiplier')) {
                $table->decimal('hourly_rate_multiplier', 5, 2)->default(1.00)->after('max_staff_allowed')
                    ->comment('Hourly rate multiplier (e.g., 1.5 for overtime)');
            }
            
            // Template flag
            if (!Schema::hasColumn('staff_shifts', 'is_template')) {
                $table->boolean('is_template')->default(false)->after('is_active')
                    ->comment('Whether this is a template shift');
            }
            
            // Audit fields
            if (!Schema::hasColumn('staff_shifts', 'updated_by')) {
                $table->foreignUlid('updated_by')->nullable()->after('created_by')
                    ->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            }
            
            if (!Schema::hasColumn('staff_shifts', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_shifts', function (Blueprint $table) {
            $table->dropColumn([
                'position_name',
                'shift_type',
                'description',
                'min_staff_required',
                'max_staff_allowed',
                'hourly_rate_multiplier',
                'is_template',
                'updated_by',
            ]);
            
            if (Schema::hasColumn('staff_shifts', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
            
            // Note: department column removal is handled by its own migration
        });
    }
};
