<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffAttendance extends Model
{
    use HasUlid, SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'staff_attendance';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'staff_id',
        'clock_in',
        'clock_out',
        'status',
        'hours_worked',
        'notes',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
        'hours_worked' => 'decimal:2',
    ];

    /**
     * Get the staff member for this attendance record.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the user who created this attendance record.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Get the user who last updated this attendance record.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'updated_by');
    }

    /**
     * Calculate hours worked based on clock in/out times.
     */
    public function calculateHoursWorked(): ?float
    {
        if (!$this->clock_in || !$this->clock_out) {
            return null;
        }

        $diffInMinutes = $this->clock_in->diffInMinutes($this->clock_out);
        return round($diffInMinutes / 60, 2);
    }

    /**
     * Automatically calculate hours when clock_out is set.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::saving(function (StaffAttendance $attendance) {
            if ($attendance->clock_out && $attendance->clock_in) {
                $attendance->hours_worked = $attendance->calculateHoursWorked();
            }
        });
    }

    /**
     * Scope for attendance on specific date.
     */
    public function scopeForDate($query, string $date)
    {
        return $query->whereDate('clock_in', $date);
    }

    /**
     * Scope for specific staff member.
     */
    public function scopeForStaff($query, string $staffId)
    {
        return $query->where('staff_id', $staffId);
    }

    /**
     * Scope for active attendance (not clocked out yet).
     */
    public function scopeActive($query)
    {
        return $query->whereNull('clock_out');
    }
}