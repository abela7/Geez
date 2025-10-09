<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffPayrollSetting extends Model
{
    use HasUlid, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'pay_frequency',
        'overtime_threshold_hours',
        'overtime_multiplier',
        'currency_code',
        'locale',
        'tax_year',
        'auto_calculate_tax',
        'rounding_mode',
        'rounding_precision',
        'is_active',
        'is_default',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'overtime_threshold_hours' => 'decimal:2',
        'overtime_multiplier' => 'decimal:2',
        'tax_year' => 'integer',
        'auto_calculate_tax' => 'boolean',
        'rounding_precision' => 'integer',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    /**
     * Get the staff member who created this setting.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Get the staff member who last updated this setting.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'updated_by');
    }

    /**
     * Get all payroll periods using this setting.
     */
    public function periods(): HasMany
    {
        return $this->hasMany(StaffPayrollPeriod::class, 'payroll_setting_id');
    }

    /**
     * Scope for active settings only.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for default setting.
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Get the default payroll setting.
     */
    public static function getDefault(): ?self
    {
        return static::default()->first();
    }

    /**
     * Round a monetary value according to this setting's rounding policy.
     */
    public function roundAmount(float $amount): float
    {
        $precision = $this->rounding_precision ?? 2;
        
        return match ($this->rounding_mode) {
            'up' => ceil($amount * pow(10, $precision)) / pow(10, $precision),
            'down' => floor($amount * pow(10, $precision)) / pow(10, $precision),
            default => round($amount, $precision),
        };
    }

    /**
     * Check if overtime applies for given hours.
     */
    public function isOvertime(float $hours): bool
    {
        return $hours > ($this->overtime_threshold_hours ?? 40.00);
    }

    /**
     * Calculate overtime hours.
     */
    public function calculateOvertimeHours(float $totalHours): float
    {
        $threshold = $this->overtime_threshold_hours ?? 40.00;
        
        return max(0, $totalHours - $threshold);
    }

    /**
     * Calculate regular hours (capped at threshold).
     */
    public function calculateRegularHours(float $totalHours): float
    {
        $threshold = $this->overtime_threshold_hours ?? 40.00;
        
        return min($totalHours, $threshold);
    }
}

