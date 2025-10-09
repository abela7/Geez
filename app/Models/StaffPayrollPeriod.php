<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffPayrollPeriod extends Model
{
    use HasUlid, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'period_type',
        'period_start',
        'period_end',
        'status',
        'closed_at',
        'closed_by',
        'payroll_setting_id',
        'total_staff_count',
        'total_gross_pay',
        'total_net_pay',
        'total_deductions',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'closed_at' => 'datetime',
        'total_staff_count' => 'integer',
        'total_gross_pay' => 'decimal:2',
        'total_net_pay' => 'decimal:2',
        'total_deductions' => 'decimal:2',
    ];

    /**
     * Get the payroll setting for this period.
     */
    public function payrollSetting(): BelongsTo
    {
        return $this->belongsTo(StaffPayrollSetting::class);
    }

    /**
     * Get the staff member who closed this period.
     */
    public function closedByStaff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'closed_by');
    }

    /**
     * Get the staff member who created this period.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Get the staff member who last updated this period.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'updated_by');
    }

    /**
     * Get all payroll records for this period.
     */
    public function payrollRecords(): HasMany
    {
        return $this->hasMany(StaffPayrollRecord::class, 'pay_period_id');
    }

    /**
     * Get all bonuses for this period.
     */
    public function bonuses(): HasMany
    {
        return $this->hasMany(StaffPayrollBonus::class, 'pay_period_id');
    }

    /**
     * Scope for open periods.
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Scope for closed periods.
     */
    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    /**
     * Scope for processing periods.
     */
    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    /**
     * Scope for current period (contains today).
     */
    public function scopeCurrent($query)
    {
        $today = now()->toDateString();
        
        return $query->where('period_start', '<=', $today)
            ->where('period_end', '>=', $today);
    }

    /**
     * Check if period is open.
     */
    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    /**
     * Check if period is closed.
     */
    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }

    /**
     * Check if period is processing.
     */
    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    /**
     * Close the period.
     */
    public function close(?string $closedBy = null): void
    {
        $this->update([
            'status' => 'closed',
            'closed_at' => now(),
            'closed_by' => $closedBy ?? auth()->id(),
        ]);
    }

    /**
     * Reopen the period.
     */
    public function reopen(): void
    {
        $this->update([
            'status' => 'open',
            'closed_at' => null,
            'closed_by' => null,
        ]);
    }

    /**
     * Update cached totals from payroll records.
     */
    public function updateTotals(): void
    {
        $records = $this->payrollRecords()
            ->whereIn('status', ['approved', 'paid'])
            ->get();

        $this->update([
            'total_staff_count' => $records->count(),
            'total_gross_pay' => $records->sum('gross_pay'),
            'total_net_pay' => $records->sum('net_pay'),
            'total_deductions' => $records->sum('deductions'),
        ]);
    }

    /**
     * Get period duration in days.
     */
    public function getDurationDays(): int
    {
        return $this->period_start->diffInDays($this->period_end) + 1;
    }
}

