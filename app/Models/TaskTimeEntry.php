<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskTimeEntry extends Model
{
    use HasUlid;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'staff_task_assignment_id',
        'staff_id',
        'start_time',
        'end_time',
        'duration_minutes',
        'description',
        'entry_type',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'duration_minutes' => 'integer',
    ];

    /**
     * Get the task assignment this time entry belongs to.
     */
    public function assignment(): BelongsTo
    {
        return $this->belongsTo(StaffTaskAssignment::class, 'staff_task_assignment_id');
    }

    /**
     * Get the staff member who logged this time.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    /**
     * Calculate duration in minutes if not set.
     */
    public function calculateDuration(): int
    {
        if ($this->start_time && $this->end_time) {
            return $this->start_time->diffInMinutes($this->end_time);
        }
        return 0;
    }

    /**
     * Get duration in hours.
     */
    public function getDurationHoursAttribute(): float
    {
        return round(($this->duration_minutes ?? 0) / 60, 2);
    }

    /**
     * Scope to get work entries only.
     */
    public function scopeWork($query)
    {
        return $query->where('entry_type', 'work');
    }

    /**
     * Scope to get entries for a specific date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('start_time', $date);
    }
}