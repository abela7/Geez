<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WeeklyRotaTemplate extends Model
{
    use HasUlid, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
        'type',
        'is_active',
        'is_default',
        'usage_count',
        'last_used_at',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'usage_count' => 'integer',
        'last_used_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the staff member who created this template.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Get the staff member who last updated this template.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'updated_by');
    }

    /**
     * Get all assignments for this template.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(WeeklyRotaTemplateAssignment::class, 'template_id');
    }

    /**
     * Get assignments grouped by day of week.
     */
    public function getAssignmentsByDay(): array
    {
        $assignmentsByDay = [];

        // Start with Monday (1) and go through Sunday (0)
        $dayOrder = [1, 2, 3, 4, 5, 6, 0];

        foreach ($dayOrder as $day) {
            $assignmentsByDay[$day] = $this->assignments()
                ->where('day_of_week', $day)
                ->with(['staff.staffType', 'staff.profile', 'shift'])
                ->get();
        }

        return $assignmentsByDay;
    }

    /**
     * Scope for active templates.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for default template.
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Scope by type.
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Mark this template as used.
     */
    public function markAsUsed(): void
    {
        $this->increment('usage_count');
        $this->update(['last_used_at' => now()]);
    }

    /**
     * Set this as the default template (and unset others).
     */
    public function setAsDefault(): void
    {
        // Unset all other defaults
        static::where('is_default', true)->update(['is_default' => false]);
        
        // Set this as default
        $this->update(['is_default' => true]);
    }

    /**
     * Get total staff assignments in this template.
     */
    public function getTotalAssignments(): int
    {
        return $this->assignments()->count();
    }

    /**
     * Get unique staff count in this template.
     */
    public function getUniqueStaffCount(): int
    {
        return $this->assignments()->distinct('staff_id')->count('staff_id');
    }

    /**
     * Get shifts count in this template.
     */
    public function getShiftsCount(): int
    {
        return $this->assignments()->distinct('staff_shift_id')->count('staff_shift_id');
    }

    /**
     * Calculate the real weekly cost of this template based on actual shift durations.
     * Uses a standard hourly rate since each shift type has its own payment structure.
     */
    public function calculateRealWeeklyCost(): float
    {
        $totalCost = 0.0;
        $defaultHourlyRate = 15.00; // £15/hour as standard restaurant rate

        // Get all assignments with shift relationships loaded
        $assignments = $this->assignments()
            ->with(['shift'])
            ->get();

        foreach ($assignments as $assignment) {
            // Get actual shift duration in hours (accounts for break time)
            // This gives the real working hours for each shift
            $shiftDuration = $assignment->shift?->getDurationInHours() ?? 8.0;

            // Calculate daily cost for this assignment
            $dailyCost = $defaultHourlyRate * $shiftDuration;

            // Add to total cost
            $totalCost += $dailyCost;
        }

        return round($totalCost, 2);
    }

    /**
     * Get detailed cost breakdown by day and shift.
     */
    public function getCostBreakdown(): array
    {
        $breakdown = [];

        $assignments = $this->assignments()
            ->with(['staff', 'shift'])
            ->orderByRaw('CASE
                WHEN day_of_week = 0 THEN 7  -- Sunday becomes 7 (last)
                ELSE day_of_week             -- Monday-Saturday stay 1-6
            END')
            ->get();

        foreach ($assignments as $assignment) {
            $dayName = $assignment->getDayName();
            $staffName = $assignment->staff?->full_name ?? 'Unknown Staff';
            $shiftName = $assignment->shift?->name ?? 'Unknown Shift';
            $hourlyRate = $assignment->shift?->getEffectiveHourlyRate() ?? 15.0;
            $shiftDuration = $assignment->shift?->getDurationInHours() ?? 8.0;
            $dailyCost = $hourlyRate * $shiftDuration;

            if (!isset($breakdown[$dayName])) {
                $breakdown[$dayName] = [
                    'total_cost' => 0,
                    'assignments' => []
                ];
            }

            $breakdown[$dayName]['assignments'][] = [
                'staff_name' => $staffName,
                'shift_name' => $shiftName,
                'shift_type' => ($assignment->shift?->getShiftTypeModel()?->name) ?? 'Unknown Type',
                'hourly_rate' => $hourlyRate,
                'hours_worked' => $shiftDuration,
                'daily_cost' => round($dailyCost, 2)
            ];

            $breakdown[$dayName]['total_cost'] += $dailyCost;
            $breakdown[$dayName]['total_cost'] = round($breakdown[$dayName]['total_cost'], 2);
        }

        return $breakdown;
    }
}