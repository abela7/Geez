<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffShiftAssignment extends Model
{
    use HasUlid, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'staff_shift_id',
        'staff_id',
        'assigned_date',
        'role_assigned',
        'position_details',
        'status',
        'actual_start_time',
        'actual_end_time',
        'break_start_time',
        'break_end_time',
        'late_minutes',
        'overtime_minutes',
        'tips_declared',
        'performance_rating',
        'notes',
        'staff_notes',
        'assigned_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'assigned_date' => 'date',
        'actual_start_time' => 'datetime',
        'actual_end_time' => 'datetime',
        'break_start_time' => 'datetime',
        'break_end_time' => 'datetime',
        'late_minutes' => 'integer',
        'overtime_minutes' => 'integer',
        'tips_declared' => 'decimal:2',
        'performance_rating' => 'integer',
    ];

    /**
     * Get the staff member assigned to this shift.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the shift template for this assignment.
     */
    public function shift(): BelongsTo
    {
        return $this->belongsTo(StaffShift::class, 'staff_shift_id');
    }

    /**
     * Get the staff member who made this assignment.
     */
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'assigned_by');
    }

    /**
     * Get the staff member who last updated this assignment.
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'updated_by');
    }

    /**
     * Get related attendance record.
     */
    public function attendance(): BelongsTo
    {
        return $this->belongsTo(StaffAttendance::class, 'id', 'shift_assignment_id');
    }

    /**
     * Get exceptions for this assignment.
     */
    public function exceptions(): HasMany
    {
        return $this->hasMany(StaffShiftException::class, 'assignment_id');
    }

    /**
     * Get swap requests for this assignment.
     */
    public function swapRequests(): HasMany
    {
        return $this->hasMany(StaffShiftSwap::class, 'original_assignment_id');
    }

    /**
     * Check if assignment is active (not cancelled).
     */
    public function isActive(): bool
    {
        return $this->status !== 'cancelled';
    }

    /**
     * Check if assignment is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if staff member is currently on shift.
     */
    public function isCurrentlyWorking(): bool
    {
        return in_array($this->status, ['checked_in', 'active', 'on_break']);
    }

    /**
     * Check if staff member was late.
     */
    public function wasLate(): bool
    {
        return $this->late_minutes > 0;
    }

    /**
     * Check if staff member worked overtime.
     */
    public function workedOvertime(): bool
    {
        return $this->overtime_minutes > 0;
    }

    /**
     * Calculate total hours worked.
     */
    public function getTotalHoursWorked(): float
    {
        if (! $this->actual_start_time || ! $this->actual_end_time) {
            return 0;
        }

        $totalMinutes = $this->actual_end_time->diffInMinutes($this->actual_start_time);

        // Subtract break time if recorded
        if ($this->break_start_time && $this->break_end_time) {
            $breakMinutes = $this->break_end_time->diffInMinutes($this->break_start_time);
            $totalMinutes -= $breakMinutes;
        }

        return round($totalMinutes / 60, 2);
    }

    /**
     * Scope for assignments on specific date.
     */
    public function scopeForDate($query, string $date)
    {
        return $query->whereDate('date', $date);
    }

    /**
     * Scope for specific staff member.
     */
    public function scopeForStaff($query, string $staffId)
    {
        return $query->where('staff_id', $staffId);
    }

    /**
     * Scope for active assignments (not cancelled).
     */
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'cancelled');
    }

    /**
     * Scope for assignments in date range.
     */
    public function scopeBetweenDates($query, string $startDate, string $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Scope for assignments by status.
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for assignments by role.
     */
    public function scopeByRole($query, string $role)
    {
        return $query->where('role_assigned', $role);
    }

    /**
     * Scope for current week assignments.
     */
    public function scopeCurrentWeek($query)
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();

        return $query->whereBetween('date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')]);
    }

    /**
     * Scope for today's assignments.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('date', now()->format('Y-m-d'));
    }
}
