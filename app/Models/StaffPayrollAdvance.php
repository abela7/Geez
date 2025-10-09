<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffPayrollAdvance extends Model
{
    use HasUlid, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'staff_id',
        'advance_type',
        'requested_amount',
        'approved_amount',
        'disbursed_amount',
        'outstanding_balance',
        'currency',
        'repayment_installments',
        'installment_amount',
        'first_deduction_date',
        'installments_paid',
        'interest_rate',
        'total_interest',
        'status',
        'requested_at',
        'approved_at',
        'approved_by',
        'disbursed_at',
        'completed_at',
        'deduction_id',
        'reason',
        'approval_notes',
        'notes',
        'reference_number',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'requested_amount' => 'decimal:2',
        'approved_amount' => 'decimal:2',
        'disbursed_amount' => 'decimal:2',
        'outstanding_balance' => 'decimal:2',
        'repayment_installments' => 'integer',
        'installment_amount' => 'decimal:2',
        'first_deduction_date' => 'date',
        'installments_paid' => 'integer',
        'interest_rate' => 'decimal:2',
        'total_interest' => 'decimal:2',
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'disbursed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the staff member requesting this advance.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the staff member who approved this advance.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'approved_by');
    }

    /**
     * Get the deduction record for repayment.
     */
    public function deduction(): BelongsTo
    {
        return $this->belongsTo(StaffPayrollDeduction::class);
    }

    /**
     * Get the staff member who created this advance.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Get the staff member who last updated this advance.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'updated_by');
    }

    /**
     * Scope for pending advances.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved advances.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for disbursed advances.
     */
    public function scopeDisbursed($query)
    {
        return $query->where('status', 'disbursed');
    }

    /**
     * Scope for repaying advances.
     */
    public function scopeRepaying($query)
    {
        return $query->where('status', 'repaying');
    }

    /**
     * Scope for completed advances.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for specific staff member.
     */
    public function scopeForStaff($query, string $staffId)
    {
        return $query->where('staff_id', $staffId);
    }

    /**
     * Check if advance is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if advance is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if advance is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Approve the advance.
     */
    public function approve(float $approvedAmount, ?string $approvedBy = null, ?string $notes = null): void
    {
        $this->update([
            'status' => 'approved',
            'approved_amount' => $approvedAmount,
            'approved_at' => now(),
            'approved_by' => $approvedBy ?? auth()->id(),
            'approval_notes' => $notes,
        ]);
    }

    /**
     * Reject the advance.
     */
    public function reject(?string $reason = null): void
    {
        $this->update([
            'status' => 'rejected',
            'approval_notes' => $reason,
        ]);
    }

    /**
     * Mark as disbursed.
     */
    public function markAsDisbursed(float $disbursedAmount): void
    {
        $this->update([
            'status' => 'disbursed',
            'disbursed_amount' => $disbursedAmount,
            'disbursed_at' => now(),
            'outstanding_balance' => $disbursedAmount + ($this->total_interest ?? 0),
        ]);
    }

    /**
     * Start repayment.
     */
    public function startRepayment(): void
    {
        $this->update(['status' => 'repaying']);
    }

    /**
     * Record repayment.
     */
    public function recordRepayment(float $amount): void
    {
        $this->outstanding_balance -= $amount;
        $this->installments_paid++;

        if ($this->outstanding_balance <= 0) {
            $this->status = 'completed';
            $this->completed_at = now();
            $this->outstanding_balance = 0;
        }

        $this->save();
    }

    /**
     * Get repayment progress percentage.
     */
    public function getRepaymentProgress(): float
    {
        if (! $this->repayment_installments) {
            return 0;
        }

        return ($this->installments_paid / $this->repayment_installments) * 100;
    }

    /**
     * Calculate total repayment amount (principal + interest).
     */
    public function getTotalRepaymentAmount(): float
    {
        return ($this->disbursed_amount ?? 0) + ($this->total_interest ?? 0);
    }
}

