<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffPayrollBonus extends Model
{
    use HasUlid, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'staff_id',
        'pay_period_id',
        'bonus_type',
        'name',
        'description',
        'amount',
        'currency',
        'is_taxable',
        'is_pensionable',
        'status',
        'approved_at',
        'approved_by',
        'paid_at',
        'payroll_record_id',
        'reference_number',
        'notes',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'is_taxable' => 'boolean',
        'is_pensionable' => 'boolean',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the staff member receiving this bonus.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the pay period for this bonus.
     */
    public function payPeriod(): BelongsTo
    {
        return $this->belongsTo(StaffPayrollPeriod::class, 'pay_period_id');
    }

    /**
     * Get the staff member who approved this bonus.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'approved_by');
    }

    /**
     * Get the payroll record this bonus was paid in.
     */
    public function payrollRecord(): BelongsTo
    {
        return $this->belongsTo(StaffPayrollRecord::class);
    }

    /**
     * Get the staff member who created this bonus.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Get the staff member who last updated this bonus.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'updated_by');
    }

    /**
     * Scope for pending bonuses.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved bonuses.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for paid bonuses.
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope for specific staff member.
     */
    public function scopeForStaff($query, string $staffId)
    {
        return $query->where('staff_id', $staffId);
    }

    /**
     * Scope for specific bonus type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('bonus_type', $type);
    }

    /**
     * Scope for unpaid bonuses.
     */
    public function scopeUnpaid($query)
    {
        return $query->whereIn('status', ['pending', 'approved']);
    }

    /**
     * Check if bonus is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if bonus is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if bonus is paid.
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Approve the bonus.
     */
    public function approve(?string $approvedBy = null): void
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $approvedBy ?? auth()->id(),
        ]);
    }

    /**
     * Mark bonus as paid.
     */
    public function markAsPaid(string $payrollRecordId): void
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payroll_record_id' => $payrollRecordId,
        ]);
    }

    /**
     * Cancel the bonus.
     */
    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }
}

