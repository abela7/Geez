<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffPayrollDeduction extends Model
{
    use HasUlid, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'staff_id',
        'deduction_type_id',
        'custom_rate',
        'custom_amount',
        'effective_from',
        'effective_to',
        'total_amount',
        'amount_deducted_to_date',
        'installment_count',
        'installments_completed',
        'status',
        'completed_at',
        'notes',
        'reference_number',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'custom_rate' => 'decimal:4',
        'custom_amount' => 'decimal:2',
        'effective_from' => 'date',
        'effective_to' => 'date',
        'total_amount' => 'decimal:2',
        'amount_deducted_to_date' => 'decimal:2',
        'installment_count' => 'integer',
        'installments_completed' => 'integer',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the staff member this deduction applies to.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the deduction type.
     */
    public function deductionType(): BelongsTo
    {
        return $this->belongsTo(StaffPayrollDeductionType::class);
    }

    /**
     * Get the staff member who created this deduction.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Get the staff member who last updated this deduction.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'updated_by');
    }

    /**
     * Scope for active deductions.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for paused deductions.
     */
    public function scopePaused($query)
    {
        return $query->where('status', 'paused');
    }

    /**
     * Scope for completed deductions.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for effective deductions (within date range).
     */
    public function scopeEffective($query, ?\DateTimeInterface $date = null)
    {
        $date = $date ?? now();
        
        return $query->where('effective_from', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereNull('effective_to')
                    ->orWhere('effective_to', '>=', $date);
            });
    }

    /**
     * Scope for specific staff member.
     */
    public function scopeForStaff($query, string $staffId)
    {
        return $query->where('staff_id', $staffId);
    }

    /**
     * Check if deduction is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if deduction is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if deduction is effective for given date.
     */
    public function isEffective(?\DateTimeInterface $date = null): bool
    {
        $date = $date ?? now();
        
        $afterStart = $date >= $this->effective_from;
        $beforeEnd = ! $this->effective_to || $date <= $this->effective_to;
        
        return $afterStart && $beforeEnd && $this->isActive();
    }

    /**
     * Get remaining balance.
     */
    public function getRemainingBalance(): float
    {
        if (! $this->total_amount) {
            return 0;
        }

        return max(0, $this->total_amount - $this->amount_deducted_to_date);
    }

    /**
     * Get installment amount.
     */
    public function getInstallmentAmount(): float
    {
        if (! $this->installment_count || ! $this->total_amount) {
            return 0;
        }

        return $this->total_amount / $this->installment_count;
    }

    /**
     * Record deduction payment.
     */
    public function recordPayment(float $amount): void
    {
        $this->amount_deducted_to_date += $amount;
        $this->installments_completed++;

        // Check if completed
        if ($this->total_amount && $this->amount_deducted_to_date >= $this->total_amount) {
            $this->status = 'completed';
            $this->completed_at = now();
        }

        $this->save();
    }

    /**
     * Pause the deduction.
     */
    public function pause(): void
    {
        $this->update(['status' => 'paused']);
    }

    /**
     * Resume the deduction.
     */
    public function resume(): void
    {
        $this->update(['status' => 'active']);
    }

    /**
     * Cancel the deduction.
     */
    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }
}

