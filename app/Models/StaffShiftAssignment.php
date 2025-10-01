<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffShiftAssignment extends Model
{
    use HasUlid, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'staff_id',
        'staff_shift_id',
        'assigned_date',
        'status',
        'assigned_by',
        'notes',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'assigned_date' => 'date',
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
     * Scope for assignments on specific date.
     */
    public function scopeForDate($query, string $date)
    {
        return $query->whereDate('assigned_date', $date);
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
        return $query->whereBetween('assigned_date', [$startDate, $endDate]);
    }
}