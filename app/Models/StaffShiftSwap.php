<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffShiftSwap extends Model
{
    use HasUlid, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'requesting_staff_id',
        'target_staff_id',
        'original_assignment_id',
        'proposed_assignment_id',
        'swap_type',
        'status',
        'reason',
        'message_to_target',
        'notes',
        'approved_by',
        'approved_at',
        'approval_notes',
        'target_responded_at',
        'target_response_notes',
        'urgency',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'approved_at' => 'datetime',
        'target_responded_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the staff member requesting the swap.
     */
    public function requestingStaff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'requesting_staff_id');
    }

    /**
     * Get the target staff member for the swap.
     */
    public function targetStaff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'target_staff_id');
    }

    /**
     * Get the original assignment being swapped.
     */
    public function originalAssignment(): BelongsTo
    {
        return $this->belongsTo(StaffShiftAssignment::class, 'original_assignment_id');
    }

    /**
     * Get the proposed assignment for the swap.
     */
    public function proposedAssignment(): BelongsTo
    {
        return $this->belongsTo(StaffShiftAssignment::class, 'proposed_assignment_id');
    }

    /**
     * Get the staff member who approved the swap.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'approved_by');
    }

    /**
     * Check if swap is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if swap is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if swap has expired.
     */
    public function hasExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if swap is urgent.
     */
    public function isUrgent(): bool
    {
        return in_array($this->urgency, ['high', 'emergency']);
    }

    /**
     * Scope for pending swaps.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved swaps.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for urgent swaps.
     */
    public function scopeUrgent($query)
    {
        return $query->whereIn('urgency', ['high', 'emergency']);
    }

    /**
     * Scope for non-expired swaps.
     */
    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
                ->orWhere('expires_at', '>', now());
        });
    }
}
