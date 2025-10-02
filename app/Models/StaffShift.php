<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffShift extends Model
{
    use HasUlid, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'department',
        'shift_type',
        'start_time',
        'end_time',
        'break_minutes',
        'min_staff_required',
        'max_staff_allowed',
        'required_roles',
        'hourly_rate_multiplier',
        'is_active',
        'is_holiday_shift',
        'color_code',
        'description',
        'days_of_week',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'break_minutes' => 'integer',
        'min_staff_required' => 'integer',
        'max_staff_allowed' => 'integer',
        'required_roles' => 'array',
        'hourly_rate_multiplier' => 'decimal:2',
        'is_active' => 'boolean',
        'is_holiday_shift' => 'boolean',
        'days_of_week' => 'array',
    ];

    /**
     * Get the staff member who created this shift.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Get the staff member who last updated this shift.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'updated_by');
    }

    /**
     * Get all assignments for this shift.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(StaffShiftAssignment::class, 'staff_shift_id');
    }

    /**
     * Get all patterns for this shift.
     */
    public function patterns(): HasMany
    {
        return $this->hasMany(StaffShiftPattern::class, 'shift_id');
    }

    /**
     * Get all swap requests involving this shift.
     */
    public function swapRequests(): HasMany
    {
        return $this->hasMany(StaffShiftSwap::class, 'original_assignment_id');
    }

    /**
     * Get active assignments for this shift.
     */
    public function activeAssignments(): HasMany
    {
        return $this->assignments()->where('status', '!=', 'cancelled');
    }

    /**
     * Calculate shift duration in hours.
     */
    public function getDurationInHours(): float
    {
        $start = \Carbon\Carbon::createFromFormat('H:i', $this->start_time);
        $end = \Carbon\Carbon::createFromFormat('H:i', $this->end_time);
        
        // Handle overnight shifts
        if ($end->lessThan($start)) {
            $end->addDay();
        }
        
        $totalMinutes = $end->diffInMinutes($start);
        $breakMinutes = $this->break_minutes ?? 0;
        
        return round(($totalMinutes - $breakMinutes) / 60, 2);
    }

    /**
     * Check if shift is scheduled for a specific day of week.
     */
    public function isScheduledForDay(int $dayOfWeek): bool
    {
        return in_array($dayOfWeek, $this->days_of_week ?? []);
    }

    /**
     * Scope for active shifts only.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for shifts on specific day of week.
     */
    public function scopeForDayOfWeek($query, int $dayOfWeek)
    {
        return $query->whereJsonContains('days_of_week', $dayOfWeek);
    }

    /**
     * Scope for shifts by department.
     */
    public function scopeByDepartment($query, string $department)
    {
        return $query->where('department', $department);
    }

    /**
     * Scope for shifts by type.
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('shift_type', $type);
    }

    /**
     * Check if this shift requires a specific role.
     */
    public function requiresRole(string $role): bool
    {
        return in_array($role, $this->required_roles ?? []);
    }

    /**
     * Get the shift's color for calendar display.
     */
    public function getDisplayColor(): string
    {
        return $this->color_code ?? '#3B82F6';
    }

    /**
     * Check if shift is understaffed for a given date.
     */
    public function isUnderstaffed(\Carbon\Carbon $date): bool
    {
        $assignedCount = $this->assignments()
            ->where('date', $date->format('Y-m-d'))
            ->whereIn('status', ['scheduled', 'checked_in', 'active', 'completed'])
            ->count();

        return $assignedCount < $this->min_staff_required;
    }

    /**
     * Check if shift is overstaffed for a given date.
     */
    public function isOverstaffed(\Carbon\Carbon $date): bool
    {
        $assignedCount = $this->assignments()
            ->where('date', $date->format('Y-m-d'))
            ->whereIn('status', ['scheduled', 'checked_in', 'active', 'completed'])
            ->count();

        return $assignedCount > $this->max_staff_allowed;
    }

    /**
     * Get available staff count for a given date.
     */
    public function getAvailableSlots(\Carbon\Carbon $date): int
    {
        $assignedCount = $this->assignments()
            ->where('date', $date->format('Y-m-d'))
            ->whereIn('status', ['scheduled', 'checked_in', 'active', 'completed'])
            ->count();

        return max(0, $this->max_staff_allowed - $assignedCount);
    }
}