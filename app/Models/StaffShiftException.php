<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffShiftException extends Model
{
    use HasUlid, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'assignment_id',
        'exception_type',
        'minutes_affected',
        'financial_impact',
        'replacement_staff_id',
        'replacement_start_time',
        'replacement_end_time',
        'description',
        'action_taken',
        'evidence',
        'status',
        'reported_by',
        'approved_by',
        'approved_at',
        'requires_disciplinary_action',
        'affects_payroll',
        'follow_up_notes',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'minutes_affected' => 'integer',
        'financial_impact' => 'decimal:2',
        'replacement_start_time' => 'datetime',
        'replacement_end_time' => 'datetime',
        'evidence' => 'array',
        'approved_at' => 'datetime',
        'requires_disciplinary_action' => 'boolean',
        'affects_payroll' => 'boolean',
    ];

    /**
     * Get the assignment this exception is for.
     */
    public function assignment(): BelongsTo
    {
        return $this->belongsTo(StaffShiftAssignment::class, 'assignment_id');
    }

    /**
     * Get the replacement staff member.
     */
    public function replacementStaff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'replacement_staff_id');
    }

    /**
     * Get the staff member who reported this exception.
     */
    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'reported_by');
    }

    /**
     * Get the staff member who approved this exception.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'approved_by');
    }

    /**
     * Check if exception is under review.
     */
    public function isUnderReview(): bool
    {
        return $this->status === 'under_review';
    }

    /**
     * Check if exception is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if exception requires disciplinary action.
     */
    public function requiresDisciplinaryAction(): bool
    {
        return $this->requires_disciplinary_action;
    }

    /**
     * Check if exception affects payroll.
     */
    public function affectsPayroll(): bool
    {
        return $this->affects_payroll;
    }

    /**
     * Get the duration of replacement coverage in hours.
     */
    public function getReplacementDurationInHours(): float
    {
        if (!$this->replacement_start_time || !$this->replacement_end_time) {
            return 0;
        }

        return $this->replacement_end_time->diffInMinutes($this->replacement_start_time) / 60;
    }

    /**
     * Scope for exceptions by type.
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('exception_type', $type);
    }

    /**
     * Scope for exceptions requiring disciplinary action.
     */
    public function scopeRequiringDisciplinaryAction($query)
    {
        return $query->where('requires_disciplinary_action', true);
    }

    /**
     * Scope for exceptions affecting payroll.
     */
    public function scopeAffectingPayroll($query)
    {
        return $query->where('affects_payroll', true);
    }

    /**
     * Scope for exceptions by status.
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
