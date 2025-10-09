<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffPayrollTaxBracket extends Model
{
    use HasUlid, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'tax_type',
        'tax_rate',
        'brackets',
        'minimum_income',
        'maximum_income',
        'tax_year',
        'effective_from',
        'effective_to',
        'is_active',
        'is_default',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'tax_rate' => 'decimal:4',
        'brackets' => 'array',
        'minimum_income' => 'decimal:2',
        'maximum_income' => 'decimal:2',
        'tax_year' => 'integer',
        'effective_from' => 'date',
        'effective_to' => 'date',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    /**
     * Get the staff member who created this bracket.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Get the staff member who last updated this bracket.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'updated_by');
    }

    /**
     * Get all templates using this tax bracket.
     */
    public function templates(): HasMany
    {
        return $this->hasMany(StaffPayrollTemplate::class, 'tax_policy_id');
    }

    /**
     * Scope for active brackets only.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for default bracket.
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Scope for specific tax year.
     */
    public function scopeForYear($query, int $year)
    {
        return $query->where('tax_year', $year);
    }

    /**
     * Scope for currently effective brackets.
     */
    public function scopeEffective($query, ?\DateTimeInterface $date = null)
    {
        $date = $date ?? now();
        
        return $query->where(function ($q) use ($date) {
            $q->where(function ($q2) use ($date) {
                $q2->where('effective_from', '<=', $date)
                    ->where(function ($q3) use ($date) {
                        $q3->whereNull('effective_to')
                            ->orWhere('effective_to', '>=', $date);
                    });
            });
        });
    }

    /**
     * Calculate tax for given income.
     */
    public function calculateTax(float $income): float
    {
        // Check minimum income threshold
        if ($this->minimum_income && $income < $this->minimum_income) {
            return 0;
        }

        return match ($this->tax_type) {
            'percentage' => $this->calculatePercentageTax($income),
            'fixed' => $this->tax_rate ?? 0,
            'progressive' => $this->calculateProgressiveTax($income),
            'formula' => $this->calculateFormulaTax($income),
            default => 0,
        };
    }

    /**
     * Calculate percentage-based tax.
     */
    protected function calculatePercentageTax(float $income): float
    {
        return $income * ($this->tax_rate ?? 0);
    }

    /**
     * Calculate progressive tax using brackets.
     */
    protected function calculateProgressiveTax(float $income): float
    {
        if (! $this->brackets || ! is_array($this->brackets)) {
            return 0;
        }

        $totalTax = 0;
        $remainingIncome = $income;

        foreach ($this->brackets as $bracket) {
            $min = $bracket['min'] ?? 0;
            $max = $bracket['max'] ?? PHP_FLOAT_MAX;
            $rate = $bracket['rate'] ?? 0;

            if ($remainingIncome <= 0) {
                break;
            }

            $bracketIncome = min($remainingIncome, $max - $min);
            $totalTax += $bracketIncome * $rate;
            $remainingIncome -= $bracketIncome;
        }

        return $totalTax;
    }

    /**
     * Calculate formula-based tax (placeholder for custom formulas).
     */
    protected function calculateFormulaTax(float $income): float
    {
        // This would be implemented based on specific tax formula requirements
        return 0;
    }

    /**
     * Check if bracket is currently effective.
     */
    public function isEffective(?\DateTimeInterface $date = null): bool
    {
        $date = $date ?? now();
        
        $afterStart = ! $this->effective_from || $date >= $this->effective_from;
        $beforeEnd = ! $this->effective_to || $date <= $this->effective_to;
        
        return $afterStart && $beforeEnd;
    }
}

