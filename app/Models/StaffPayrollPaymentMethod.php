<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffPayrollPaymentMethod extends Model
{
    use HasUlid, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'payroll_record_id',
        'payment_method',
        'amount_paid',
        'currency',
        'payment_date',
        'transaction_reference',
        'bank_name',
        'account_number_last4',
        'status',
        'processed_at',
        'failed_at',
        'failure_reason',
        'batch_id',
        'batch_sequence',
        'notes',
        'metadata',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'amount_paid' => 'decimal:2',
        'payment_date' => 'date',
        'processed_at' => 'datetime',
        'failed_at' => 'datetime',
        'batch_sequence' => 'integer',
        'metadata' => 'array',
    ];

    /**
     * Get the payroll record this payment is for.
     */
    public function payrollRecord(): BelongsTo
    {
        return $this->belongsTo(StaffPayrollRecord::class);
    }

    /**
     * Get the staff member who created this payment.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Get the staff member who last updated this payment.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'updated_by');
    }

    /**
     * Scope for pending payments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for processed payments.
     */
    public function scopeProcessed($query)
    {
        return $query->where('status', 'processed');
    }

    /**
     * Scope for failed payments.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for specific payment method.
     */
    public function scopeByMethod($query, string $method)
    {
        return $query->where('payment_method', $method);
    }

    /**
     * Scope for specific batch.
     */
    public function scopeInBatch($query, string $batchId)
    {
        return $query->where('batch_id', $batchId);
    }

    /**
     * Check if payment is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if payment is processed.
     */
    public function isProcessed(): bool
    {
        return $this->status === 'processed';
    }

    /**
     * Check if payment failed.
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Mark payment as processed.
     */
    public function markAsProcessed(?string $transactionReference = null): void
    {
        $this->update([
            'status' => 'processed',
            'processed_at' => now(),
            'transaction_reference' => $transactionReference ?? $this->transaction_reference,
        ]);
    }

    /**
     * Mark payment as failed.
     */
    public function markAsFailed(string $reason): void
    {
        $this->update([
            'status' => 'failed',
            'failed_at' => now(),
            'failure_reason' => $reason,
        ]);
    }

    /**
     * Retry failed payment.
     */
    public function retry(): void
    {
        $this->update([
            'status' => 'pending',
            'failed_at' => null,
            'failure_reason' => null,
        ]);
    }

    /**
     * Refund payment.
     */
    public function refund(): void
    {
        $this->update(['status' => 'refunded']);
    }
}

