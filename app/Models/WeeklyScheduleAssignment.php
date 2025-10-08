<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeeklyScheduleAssignment extends Model
{
    use HasUlid;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'weekly_schedule_id',
        'staff_shift_assignment_id',
        'staff_id',
        'staff_shift_id',
        'assigned_date',
        'day_of_week',
        'assignment_status',
        'scheduled_hours',
        'hourly_rate',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'assigned_date' => 'date',
        'day_of_week' => 'integer',
        'scheduled_hours' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
    ];

    /**
     * Get the weekly schedule this assignment belongs to.
     */
    public function weeklySchedule(): BelongsTo
    {
        return $this->belongsTo(WeeklySchedule::class, 'weekly_schedule_id');
    }

    /**
     * Get the staff shift assignment.
     */
    public function staffShiftAssignment(): BelongsTo
    {
        return $this->belongsTo(StaffShiftAssignment::class, 'staff_shift_assignment_id');
    }

    /**
     * Get the staff member for this assignment.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    /**
     * Get the shift for this assignment.
     */
    public function shift(): BelongsTo
    {
        return $this->belongsTo(StaffShift::class, 'staff_shift_id');
    }

    /**
     * Get day name from day_of_week number.
     */
    public function getDayName(): string
    {
        $days = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];

        return $days[$this->day_of_week] ?? 'Unknown';
    }

    /**
     * Scope for specific day of week.
     */
    public function scopeForDay($query, int $dayOfWeek)
    {
        return $query->where('day_of_week', $dayOfWeek);
    }

    /**
     * Scope for specific weekly schedule.
     */
    public function scopeForWeeklySchedule($query, string $weeklyScheduleId)
    {
        return $query->where('weekly_schedule_id', $weeklyScheduleId);
    }

    /**
     * Scope for specific staff member.
     */
    public function scopeForStaff($query, string $staffId)
    {
        return $query->where('staff_id', $staffId);
    }

    /**
     * Scope for specific status.
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('assignment_status', $status);
    }
}
