<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'shift_assignment_id',
        'payroll_record_id',
        'clock_in',
        'clock_out',
        'status',
        'current_state',
        'previous_state',
        'state_changed_at',
        'hours_worked',
        'net_hours_worked',
        'total_break_minutes',
        'is_currently_on_break',
        'current_break_start',
        'break_count',
        'is_paid',
        'paid_at',
        'was_scheduled',
        'shift_compliance',
        'scheduled_minutes',
        'actual_minutes',
        'variance_minutes',
        'review_needed',
        'review_reason',
        'reviewed_by',
        'reviewed_at',
        'was_auto_closed',
        'auto_closed_at',
        'notes',
        'device_info',
        'clock_in_lat',
        'clock_in_lng',
        'clock_out_lat',
        'clock_out_lng',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
        'state_changed_at' => 'datetime',
        'current_break_start' => 'datetime',
        'paid_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'auto_closed_at' => 'datetime',
        'hours_worked' => 'decimal:2',
        'net_hours_worked' => 'decimal:2',
        'is_currently_on_break' => 'boolean',
        'is_paid' => 'boolean',
        'was_scheduled' => 'boolean',
        'review_needed' => 'boolean',
        'was_auto_closed' => 'boolean',
        'device_info' => 'array',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the staff member for this attendance record.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the shift assignment for this attendance.
     */
    public function shiftAssignment(): BelongsTo
    {
        return $this->belongsTo(StaffShiftAssignment::class);
    }

    /**
     * Get the payroll record this attendance is linked to.
     */
    public function payrollRecord(): BelongsTo
    {
        return $this->belongsTo(StaffPayrollRecord::class);
    }

    /**
     * Get all intervals for this attendance.
     */
    public function intervals(): HasMany
    {
        return $this->hasMany(StaffAttendanceInterval::class, 'staff_attendance_id');
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
     * Get the user who reviewed this attendance.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'reviewed_by');
    }

    // ==================== STATE MACHINE METHODS ====================

    /**
     * Start a break/pause.
     */
    public function startBreak(string $breakCategory = 'personal', ?string $reason = null): StaffAttendanceInterval
    {
        // Close any active work interval
        $activeInterval = $this->intervals()->active()->latest()->first();
        if ($activeInterval && $activeInterval->isWork()) {
            $activeInterval->close();
        }

        // Update attendance state
        $this->updateState('on_break');
        $this->is_currently_on_break = true;
        $this->current_break_start = now();
        $this->break_count++;
        $this->save();

        // Create new break interval
        return $this->intervals()->create([
            'staff_id' => $this->staff_id,
            'interval_type' => 'break',
            'break_category' => $breakCategory,
            'start_time' => now(),
            'reason' => $reason,
            'start_device_info' => $this->captureDeviceInfo(),
            'created_by' => auth()->id(),
        ]);
    }

    /**
     * Resume work after break.
     */
    public function resumeWork(): StaffAttendanceInterval
    {
        // Close active break interval
        $activeBreak = $this->intervals()->active()->breakIntervals()->latest()->first();
        if ($activeBreak) {
            $activeBreak->close();
            
            // Update total break time
            $this->total_break_minutes += $activeBreak->duration_minutes;
        }

        // Update attendance state
        $this->updateState('clocked_in');
        $this->is_currently_on_break = false;
        $this->current_break_start = null;
        $this->save();

        // Recalculate net hours
        $this->recalculateNetHours();

        // Create new work interval
        return $this->intervals()->create([
            'staff_id' => $this->staff_id,
            'interval_type' => 'work',
            'start_time' => now(),
            'start_device_info' => $this->captureDeviceInfo(),
            'created_by' => auth()->id(),
        ]);
    }

    /**
     * Clock out (end shift).
     */
    public function clockOut(?Carbon $clockOutTime = null): void
    {
        $clockOutTime = $clockOutTime ?? now();

        // Close any active interval
        $activeInterval = $this->intervals()->active()->latest()->first();
        if ($activeInterval) {
            $activeInterval->close($clockOutTime);
            
            if ($activeInterval->isBreak()) {
                $this->total_break_minutes += $activeInterval->duration_minutes;
            }
        }

        // Update attendance record
        $this->clock_out = $clockOutTime;
        $this->updateState('clocked_out');
        $this->is_currently_on_break = false;
        $this->current_break_start = null;
        
        // Calculate final hours
        $this->hours_worked = $this->calculateHoursWorked();
        $this->recalculateNetHours();
        
        // Calculate shift variance if scheduled
        if ($this->shift_assignment_id && $this->scheduled_minutes) {
            $this->actual_minutes = $this->clock_in->diffInMinutes($this->clock_out);
            $this->variance_minutes = $this->actual_minutes - $this->scheduled_minutes;
            
            // Determine shift compliance
            $this->determineShiftCompliance();
        }

        $this->save();
    }

    /**
     * Auto-close attendance (when staff forgets to clock out).
     */
    public function autoClose(Carbon $autoCloseTime, string $reason): void
    {
        $this->clockOut($autoCloseTime);
        
        $this->was_auto_closed = true;
        $this->auto_closed_at = now();
        $this->review_needed = true;
        $this->review_reason = "Auto-closed: {$reason}";
        
        $this->save();
    }

    /**
     * Update state with history tracking.
     */
    private function updateState(string $newState): void
    {
        $this->previous_state = $this->current_state;
        $this->current_state = $newState;
        $this->state_changed_at = now();
    }

    // ==================== CALCULATION METHODS ====================

    /**
     * Calculate hours worked based on clock in/out times.
     */
    public function calculateHoursWorked(): ?float
    {
        if (! $this->clock_in || ! $this->clock_out) {
            return null;
        }

        $diffInMinutes = $this->clock_in->diffInMinutes($this->clock_out);

        return round($diffInMinutes / 60, 2);
    }

    /**
     * Recalculate net hours worked (hours - breaks).
     */
    public function recalculateNetHours(): void
    {
        if (! $this->hours_worked) {
            $this->net_hours_worked = null;
            return;
        }

        $totalHoursInMinutes = $this->hours_worked * 60;
        $netMinutes = $totalHoursInMinutes - $this->total_break_minutes;
        
        $this->net_hours_worked = round($netMinutes / 60, 2);
    }

    /**
     * Recalculate total break time from intervals.
     */
    public function recalculateBreakTime(): void
    {
        $this->total_break_minutes = $this->intervals()
            ->breakIntervals()
            ->completed()
            ->sum('duration_minutes');
            
        $this->save();
        
        $this->recalculateNetHours();
    }

    /**
     * Determine shift compliance based on timing.
     */
    private function determineShiftCompliance(): void
    {
        if (! $this->shift_assignment_id) {
            $this->shift_compliance = 'unscheduled';
            return;
        }

        $assignment = $this->shiftAssignment;
        if (! $assignment) {
            return;
        }

        // Compare actual vs scheduled times
        $scheduledStart = Carbon::parse($assignment->assigned_date . ' ' . $assignment->staffShift->start_time);
        $scheduledEnd = Carbon::parse($assignment->assigned_date . ' ' . $assignment->staffShift->end_time);
        
        $lateThresholdMinutes = 15;
        $earlyLeaveThresholdMinutes = 15;
        
        $arrivedLate = $this->clock_in->diffInMinutes($scheduledStart, false) > $lateThresholdMinutes;
        $leftEarly = $this->clock_out->diffInMinutes($scheduledEnd, false) < -$earlyLeaveThresholdMinutes;
        $workedOvertime = $this->clock_out->greaterThan($scheduledEnd->addMinutes($earlyLeaveThresholdMinutes));
        
        if ($arrivedLate) {
            $this->shift_compliance = 'late_arrival';
        } elseif ($leftEarly) {
            $this->shift_compliance = 'early_departure';
        } elseif ($workedOvertime) {
            $this->shift_compliance = 'overtime';
        } else {
            $this->shift_compliance = 'on_time';
        }
    }

    // ==================== HELPER METHODS ====================

    /**
     * Capture device info for tracking.
     */
    private function captureDeviceInfo(): array
    {
        $request = request();
        
        return [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toISOString(),
        ];
    }

    /**
     * Get current active interval.
     */
    public function getCurrentInterval(): ?StaffAttendanceInterval
    {
        return $this->intervals()->active()->latest()->first();
    }

    /**
     * Check if attendance session is active (not clocked out).
     */
    public function isActive(): bool
    {
        return in_array($this->current_state, ['clocked_in', 'on_break']);
    }

    /**
     * Mark for review with reason.
     */
    public function markForReview(string $reason): void
    {
        $this->review_needed = true;
        $this->review_reason = $reason;
        $this->save();
    }

    // ==================== QUERY SCOPES ====================

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
        return $query->whereIn('current_state', ['clocked_in', 'on_break']);
    }

    /**
     * Scope for completed attendance.
     */
    public function scopeCompleted($query)
    {
        return $query->where('current_state', 'clocked_out');
    }

    /**
     * Scope for attendance needing review.
     */
    public function scopeNeedsReview($query)
    {
        return $query->where('review_needed', true)->whereNull('reviewed_at');
    }

    /**
     * Scope for unpaid attendance.
     */
    public function scopeUnpaid($query)
    {
        return $query->where('is_paid', false);
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
                $attendance->recalculateNetHours();
            }
        });

        // Create initial work interval when attendance is created
        static::created(function (StaffAttendance $attendance) {
            if ($attendance->clock_in) {
                $attendance->intervals()->create([
                    'staff_id' => $attendance->staff_id,
                    'interval_type' => 'work',
                    'start_time' => $attendance->clock_in,
                    'start_device_info' => $attendance->device_info ?? [],
                    'created_by' => $attendance->created_by,
                ]);
            }
        });
    }
}
