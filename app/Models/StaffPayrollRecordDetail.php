<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffPayrollRecordDetail extends Model
{
    use HasUlid, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'payroll_record_id',
        'item_type',
        'description',
        'notes',
        'quantity',
        'rate',
        'amount',
        'currency',
        'affects',
        'is_taxable',
        'is_pensionable',
        'source_type',
        'source_id',
        'sort_order',
        'show_on_payslip',
        'calculation_data',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'quantity' => 'decimal:2',
        'rate' => 'decimal:2',
        'amount' => 'decimal:2',
        'is_taxable' => 'boolean',
        'is_pensionable' => 'boolean',
        'sort_order' => 'integer',
        'show_on_payslip' => 'boolean',
        'calculation_data' => 'array',
    ];

    /**
     * Get the parent payroll record.
     */
    public function payrollRecord(): BelongsTo
    {
        return $this->belongsTo(StaffPayrollRecord::class);
    }

    /**
     * Get the staff member who created this detail.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Scope for specific item type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('item_type', $type);
    }

    /**
     * Scope for earnings (positive amounts).
     */
    public function scopeEarnings($query)
    {
        return $query->whereIn('item_type', [
            'regular_hours',
            'overtime_hours',
            'bonus',
            'commission',
            'tip',
            'allowance',
        ]);
    }

    /**
     * Scope for deductions (negative amounts).
     */
    public function scopeDeductions($query)
    {
        return $query->whereIn('item_type', ['deduction', 'tax']);
    }

    /**
     * Scope for taxable items.
     */
    public function scopeTaxable($query)
    {
        return $query->where('is_taxable', true);
    }

    /**
     * Scope for items visible on payslip.
     */
    public function scopeVisibleOnPayslip($query)
    {
        return $query->where('show_on_payslip', true);
    }

    /**
     * Scope ordered by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }

    /**
     * Check if this is an earning item.
     */
    public function isEarning(): bool
    {
        return in_array($this->item_type, [
            'regular_hours',
            'overtime_hours',
            'bonus',
            'commission',
            'tip',
            'allowance',
        ]);
    }

    /**
     * Check if this is a deduction item.
     */
    public function isDeduction(): bool
    {
        return in_array($this->item_type, ['deduction', 'tax']);
    }

    /**
     * Get formatted amount with currency.
     */
    public function getFormattedAmount(): string
    {
        $symbol = match ($this->currency) {
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            default => $this->currency . ' ',
        };

        return $symbol . number_format($this->amount, 2);
    }
}

