<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffPayrollDeductionType extends Model
{
    use HasUlid, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'calculation_type',
        'default_rate',
        'calculation_rules',
        'applies_to',
        'is_pre_tax',
        'is_mandatory',
        'minimum_amount',
        'maximum_amount',
        'annual_limit',
        'display_label',
        'show_on_payslip',
        'sort_order',
        'is_active',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'default_rate' => 'decimal:4',
        'calculation_rules' => 'array',
        'is_pre_tax' => 'boolean',
        'is_mandatory' => 'boolean',
        'minimum_amount' => 'decimal:2',
        'maximum_amount' => 'decimal:2',
        'annual_limit' => 'decimal:2',
        'show_on_payslip' => 'boolean',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the staff member who created this deduction type.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Get the staff member who last updated this deduction type.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'updated_by');
    }

    /**
     * Get all deductions of this type.
     */
    public function deductions(): HasMany
    {
        return $this->hasMany(StaffPayrollDeduction::class, 'deduction_type_id');
    }

    /**
     * Scope for active deduction types only.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for mandatory deductions.
     */
    public function scopeMandatory($query)
    {
        return $query->where('is_mandatory', true);
    }

    /**
     * Scope for pre-tax deductions.
     */
    public function scopePreTax($query)
    {
        return $query->where('is_pre_tax', true);
    }

    /**
     * Scope for post-tax deductions.
     */
    public function scopePostTax($query)
    {
        return $query->where('is_pre_tax', false);
    }

    /**
     * Scope ordered by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Calculate deduction amount for given base.
     */
    public function calculateAmount(float $baseAmount, ?float $customRate = null): float
    {
        $rate = $customRate ?? $this->default_rate;

        $amount = match ($this->calculation_type) {
            'percentage' => $baseAmount * ($rate ?? 0),
            'fixed' => $rate ?? 0,
            'tiered' => $this->calculateTieredAmount($baseAmount),
            'formula' => $this->calculateFormulaAmount($baseAmount),
            default => 0,
        };

        // Apply limits
        if ($this->minimum_amount && $amount < $this->minimum_amount) {
            $amount = $this->minimum_amount;
        }

        if ($this->maximum_amount && $amount > $this->maximum_amount) {
            $amount = $this->maximum_amount;
        }

        return $amount;
    }

    /**
     * Calculate tiered deduction amount.
     */
    protected function calculateTieredAmount(float $baseAmount): float
    {
        if (! $this->calculation_rules || ! is_array($this->calculation_rules)) {
            return 0;
        }

        foreach ($this->calculation_rules as $tier) {
            $min = $tier['min'] ?? 0;
            $max = $tier['max'] ?? PHP_FLOAT_MAX;
            $rate = $tier['rate'] ?? 0;

            if ($baseAmount >= $min && $baseAmount <= $max) {
                return $baseAmount * $rate;
            }
        }

        return 0;
    }

    /**
     * Calculate formula-based deduction (placeholder).
     */
    protected function calculateFormulaAmount(float $baseAmount): float
    {
        // Implement custom formula logic here
        return 0;
    }

    /**
     * Get display label or fallback to name.
     */
    public function getDisplayLabel(): string
    {
        return $this->display_label ?? $this->name;
    }
}

