<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeeklyRotaTemplateAssignment extends Model
{
    use HasUlid;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'template_id',
        'staff_shift_id',
        'staff_id',
        'day_of_week',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'day_of_week' => 'integer',
    ];

    /**
     * Get the template this assignment belongs to.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(WeeklyRotaTemplate::class, 'template_id');
    }

    /**
     * Get the shift for this assignment.
     */
    public function shift(): BelongsTo
    {
        return $this->belongsTo(StaffShift::class, 'staff_shift_id');
    }

    /**
     * Get the staff member for this assignment.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
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
     * Scope for specific template.
     */
    public function scopeForTemplate($query, string $templateId)
    {
        return $query->where('template_id', $templateId);
    }

    /**
     * Scope for specific staff.
     */
    public function scopeForStaff($query, string $staffId)
    {
        return $query->where('staff_id', $staffId);
    }

    /**
     * Scope for specific shift.
     */
    public function scopeForShift($query, string $shiftId)
    {
        return $query->where('staff_shift_id', $shiftId);
    }
}