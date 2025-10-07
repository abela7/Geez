<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Staff Attendance Interval Model
 *
 * Tracks precise pause/resume intervals for attendance tracking.
 * Enables accurate calculation of working time vs break time.
 */
class StaffAttendanceInterval extends Model
{
    use HasUlid, SoftDeletes;

    protected $table = 'staff_attendance_intervals';

    protected $fillable = [
        'staff_attendance_id',
        'staff_id',
        'interval_type',
        'break_category',
        'start_time',
        'end_time',
        'duration_minutes',
        'reason',
        'is_approved',
        'approved_by',
        'approved_at',
        'approval_notes',
        'start_device_info',
        'end_device_info',
        'created_by',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'approved_at' => 'datetime',
        'is_approved' => 'boolean',
        'start_device_info' => 'array',
        'end_device_info' => 'array',
    ];

    /**
     * Get the parent attendance record.
     */
    public function attendance(): BelongsTo
    {
        return $this->belongsTo(StaffAttendance::class, 'staff_attendance_id');
    }

    /**
     * Get the staff member.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the user who approved this interval.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'approved_by');
    }

    /**
     * Get the user who created this interval.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Close this interval and calculate duration.
     */
    public function close(?Carbon $endTime = null): void
    {
        $this->end_time = $endTime ?? now();
        $this->duration_minutes = $this->calculateDuration();
        $this->save();
    }

    /**
     * Calculate duration in minutes between start and end time.
     */
    public function calculateDuration(): ?int
    {
        if (! $this->start_time || ! $this->end_time) {
            return null;
        }

        return (int) $this->start_time->diffInMinutes($this->end_time);
    }

    /**
     * Check if this interval is currently active (not ended).
     */
    public function isActive(): bool
    {
        return $this->end_time === null;
    }

    /**
     * Check if this is a work interval.
     */
    public function isWork(): bool
    {
        return $this->interval_type === 'work';
    }

    /**
     * Check if this is a break interval.
     */
    public function isBreak(): bool
    {
        return in_array($this->interval_type, ['break', 'emergency', 'unauthorized']);
    }

    /**
     * Scope to get only work intervals.
     */
    public function scopeWorkIntervals($query)
    {
        return $query->where('interval_type', 'work');
    }

    /**
     * Scope to get only break intervals.
     */
    public function scopeBreakIntervals($query)
    {
        return $query->whereIn('interval_type', ['break', 'emergency', 'unauthorized']);
    }

    /**
     * Scope to get active (ongoing) intervals.
     */
    public function scopeActive($query)
    {
        return $query->whereNull('end_time');
    }

    /**
     * Scope to get completed intervals.
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('end_time');
    }

    /**
     * Scope for intervals needing approval.
     */
    public function scopeNeedsApproval($query)
    {
        return $query->where('is_approved', false)
            ->whereIn('interval_type', ['emergency', 'unauthorized']);
    }

    /**
     * Automatically calculate duration when interval is closed.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::saving(function (StaffAttendanceInterval $interval) {
            if ($interval->end_time && $interval->start_time) {
                $interval->duration_minutes = $interval->calculateDuration();
            }
        });
    }
}
