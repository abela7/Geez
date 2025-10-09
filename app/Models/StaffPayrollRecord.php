<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffPayrollRecord extends Model
{
    use HasUlid, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'staff_id',
        'pay_period_id',
        'template_id',
        'pay_period_start',
        'pay_period_end',
        'staff_name_snapshot',
        'position_snapshot',
        'hourly_rate_snapshot',
        'department_snapshot',
        'generated_from',
        'source_period_start',
        'source_period_end',
        'generation_hash',
        'currency',
        'hourly_rate',
        'overtime_rate',
        'regular_hours',
        'regular_pay',
        'overtime_hours',
        'overtime_pay',
        'bonus_total',
        'gross_pay',
        'deductions',
        'tax_deductions',
        'other_deductions',
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
        'source_period_start' => 'datetime',
        'source_period_end' => 'datetime',
        'processed_at' => 'datetime',
        'hourly_rate_snapshot' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'overtime_rate' => 'decimal:2',
        'regular_hours' => 'decimal:2',
        'regular_pay' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'overtime_pay' => 'decimal:2',
        'bonus_total' => 'decimal:2',
        'gross_pay' => 'decimal:2',
        'deductions' => 'decimal:2',
        'tax_deductions' => 'decimal:2',
        'other_deductions' => 'decimal:2',
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
     * Get the pay period for this record.
     */
    public function payPeriod(): BelongsTo
    {
        return $this->belongsTo(StaffPayrollPeriod::class, 'pay_period_id');
    }

    /**
     * Get the template used for this record.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(StaffPayrollTemplate::class, 'template_id');
    }

    /**
     * Get the staff member who processed this payroll.
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'processed_by');
    }

    /**
     * Get the staff member who created this record.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Get the staff member who last updated this record.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'updated_by');
    }

    /**
     * Get all detail line items for this record.
     */
    public function details(): HasMany
    {
        return $this->hasMany(StaffPayrollRecordDetail::class, 'payroll_record_id');
    }

    /**
     * Get all payment methods for this record.
     */
    public function paymentMethods(): HasMany
    {
        return $this->hasMany(StaffPayrollPaymentMethod::class, 'payroll_record_id');
    }

    /**
     * Get all bonuses included in this record.
     */
    public function bonuses(): HasMany
    {
        return $this->hasMany(StaffPayrollBonus::class, 'payroll_record_id');
    }

    /**
     * Get attendance records linked to this payroll.
     */
    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(StaffAttendance::class, 'payroll_record_id');
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
     * Scope for calculated payroll records.
     */
    public function scopeCalculated($query)
    {
        return $query->where('status', 'calculated');
    }

    /**
     * Scope for records needing review.
     */
    public function scopeNeedsReview($query)
    {
        return $query->where('status', 'needs_review');
    }

    /**
     * Scope for specific period ID.
     */
    public function scopeForPeriod($query, string $periodId)
    {
        return $query->where('pay_period_id', $periodId);
    }

    /**
     * Check if record is in draft status.
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Check if record is calculated.
     */
    public function isCalculated(): bool
    {
        return $this->status === 'calculated';
    }

    /**
     * Check if record is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if record needs review.
     */
    public function needsReview(): bool
    {
        return $this->status === 'needs_review';
    }

    /**
     * Mark as calculated.
     */
    public function markAsCalculated(): void
    {
        $this->update(['status' => 'calculated']);
    }

    /**
     * Mark as needs review.
     */
    public function markAsNeedsReview(string $reason): void
    {
        $this->update([
            'status' => 'needs_review',
            'notes' => ($this->notes ? $this->notes . "\n\n" : '') . "Review needed: {$reason}",
        ]);
    }

    /**
     * Get snapshot data as array.
     */
    public function getSnapshot(): array
    {
        return [
            'staff_name' => $this->staff_name_snapshot,
            'position' => $this->position_snapshot,
            'hourly_rate' => $this->hourly_rate_snapshot,
            'department' => $this->department_snapshot,
        ];
    }

    /**
     * Capture staff snapshot from current staff data.
     */
    public function captureStaffSnapshot(): void
    {
        $staff = $this->staff()->with('profile', 'staffType')->first();
        
        if (! $staff) {
            return;
        }

        $this->update([
            'staff_name_snapshot' => $staff->full_name,
            'position_snapshot' => $staff->staffType?->display_name ?? 'Unknown',
            'hourly_rate_snapshot' => $staff->profile?->hourly_rate ?? 0,
            'department_snapshot' => $staff->profile?->department ?? 'General',
        ]);
    }

    /**
     * Generate idempotency hash.
     */
    public function generateHash(): string
    {
        return hash('sha256', implode('|', [
            $this->staff_id,
            $this->pay_period_id ?? '',
            $this->source_period_start?->toDateTimeString() ?? '',
            $this->source_period_end?->toDateTimeString() ?? '',
        ]));
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
