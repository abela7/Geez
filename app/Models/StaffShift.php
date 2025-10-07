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
        'position_name',
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
        'is_template',
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
        'break_minutes' => 'integer',
        'min_staff_required' => 'integer',
        'max_staff_allowed' => 'integer',
        'required_roles' => 'array',
        'hourly_rate_multiplier' => 'decimal:2',
        'is_active' => 'boolean',
        'is_template' => 'boolean',
        'is_holiday_shift' => 'boolean',
        'days_of_week' => 'array',
        'deleted_at' => 'datetime',
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
     * Get the shift type for this shift.
     * Try to match by slug first, fallback to name or id
     */
    public function getShiftTypeModel()
    {
        if (empty($this->shift_type)) {
            return null;
        }

        try {
            // First try by slug
            $shiftType = ShiftType::where('slug', $this->shift_type)->first();

            // If not found, try by name
            if (!$shiftType) {
                $shiftType = ShiftType::where('name', $this->shift_type)->first();
            }

            // If not found and it's numeric, try by id
            if (!$shiftType && is_numeric($this->shift_type)) {
                $shiftType = ShiftType::find($this->shift_type);
            }

            return $shiftType;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get the effective hourly rate for this shift.
     * Uses shift type default rate if available, otherwise returns 0.
     */
    public function getEffectiveHourlyRate(): float
    {
        try {
            $shiftType = $this->getShiftTypeModel();
            return $shiftType ? (float) $shiftType->default_hourly_rate : 15.0;
        } catch (\Exception $e) {
            return 15.0; // Safe fallback
        }
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
     * Scope to get only shift templates.
     */
    public function scopeTemplates($query)
    {
        return $query->where('is_template', true);
    }

    /**
     * Scope to get templates by department.
     */
    public function scopeByDepartment($query, string $department)
    {
        return $query->where('department', $department);
    }

    /**
     * Scope to get templates by shift type.
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('shift_type', $type);
    }

    /**
     * Calculate shift duration in hours.
     */
    public function getDurationInHours(): float
    {
        // Parse time strings - handle both H:i:s and H:i formats
        $startTime = is_string($this->start_time) ? $this->start_time : $this->start_time->format('H:i:s');
        $endTime = is_string($this->end_time) ? $this->end_time : $this->end_time->format('H:i:s');
        
        $start = \Carbon\Carbon::parse($startTime);
        $end = \Carbon\Carbon::parse($endTime);

        // Handle overnight shifts
        if ($end->lessThan($start)) {
            $end->addDay();
        }

        $totalMinutes = $start->diffInMinutes($end);
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

    // scopeByDepartment and scopeByType already defined above (line 116 and 124)

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
