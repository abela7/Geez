<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffPayrollRecord extends Model
{
    use HasUlid, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'staff_id',
        'pay_period_start',
        'pay_period_end',
        'regular_hours',
        'overtime_hours',
        'gross_pay',
        'deductions',
        'net_pay',
        'status',
        'processed_by',
        'processed_at',
        'notes',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'pay_period_start' => 'date',
        'pay_period_end' => 'date',
        'processed_at' => 'datetime',
        'regular_hours' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'gross_pay' => 'decimal:2',
        'deductions' => 'decimal:2',
        'net_pay' => 'decimal:2',
    ];

    /**
     * Get the staff member for this payroll record.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the staff member who processed this payroll.
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'processed_by');
    }

    /**
     * Calculate total hours worked.
     */
    public function getTotalHours(): float
    {
        return ($this->regular_hours ?? 0) + ($this->overtime_hours ?? 0);
    }

    /**
     * Calculate effective hourly rate.
     */
    public function getEffectiveHourlyRate(): float
    {
        $totalHours = $this->getTotalHours();

        if ($totalHours <= 0) {
            return 0;
        }

        return round($this->gross_pay / $totalHours, 2);
    }

    /**
     * Get deduction percentage.
     */
    public function getDeductionPercentage(): float
    {
        if ($this->gross_pay <= 0) {
            return 0;
        }

        return round((($this->deductions ?? 0) / $this->gross_pay) * 100, 2);
    }

    /**
     * Get pay period duration in days.
     */
    public function getPayPeriodDays(): int
    {
        return $this->pay_period_start->diffInDays($this->pay_period_end) + 1;
    }

    /**
     * Check if payroll is finalized (approved or paid).
     */
    public function isFinalized(): bool
    {
        return in_array($this->status, ['approved', 'paid']);
    }

    /**
     * Check if payroll is paid.
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Mark payroll as approved.
     */
    public function markAsApproved(?string $processedBy = null): void
    {
        $this->update([
            'status' => 'approved',
            'processed_by' => $processedBy ?? auth()->id(),
            'processed_at' => now(),
        ]);
    }

    /**
     * Mark payroll as paid.
     */
    public function markAsPaid(?string $processedBy = null): void
    {
        $this->update([
            'status' => 'paid',
            'processed_by' => $processedBy ?? auth()->id(),
            'processed_at' => now(),
        ]);
    }

    /**
     * Scope for specific staff member.
     */
    public function scopeForStaff($query, string $staffId)
    {
        return $query->where('staff_id', $staffId);
    }

    /**
     * Scope for specific pay period.
     */
    public function scopeForPayPeriod($query, string $startDate, string $endDate)
    {
        return $query->where('pay_period_start', $startDate)
            ->where('pay_period_end', $endDate);
    }

    /**
     * Scope for specific year.
     */
    public function scopeForYear($query, int $year)
    {
        return $query->whereYear('pay_period_start', $year);
    }

    /**
     * Scope for approved payroll records.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for paid payroll records.
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope for draft payroll records.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Automatically calculate net pay when saving.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::saving(function (StaffPayrollRecord $record) {
            $record->net_pay = $record->gross_pay - ($record->deductions ?? 0);
        });
    }
}
