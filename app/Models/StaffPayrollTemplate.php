<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffPayrollTemplate extends Model
{
    use HasUlid, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'allocation_method',
        'base_hourly_rate',
        'overtime_rate',
        'tax_policy_id',
        'auto_apply_deductions',
        'currency',
        'is_active',
        'is_default',
        'sort_order',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'base_hourly_rate' => 'decimal:2',
        'overtime_rate' => 'decimal:2',
        'auto_apply_deductions' => 'boolean',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the tax policy for this template.
     */
    public function taxPolicy(): BelongsTo
    {
        return $this->belongsTo(StaffPayrollTaxBracket::class, 'tax_policy_id');
    }

    /**
     * Get the staff member who created this template.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Get the staff member who last updated this template.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'updated_by');
    }

    /**
     * Get all payroll records using this template.
     */
    public function payrollRecords(): HasMany
    {
        return $this->hasMany(StaffPayrollRecord::class, 'template_id');
    }

    /**
     * Scope for active templates only.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for default template.
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Scope by allocation method.
     */
    public function scopeByAllocationMethod($query, string $method)
    {
        return $query->where('allocation_method', $method);
    }

    /**
     * Scope ordered by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get the default template.
     */
    public static function getDefault(): ?self
    {
        return static::default()->first();
    }

    /**
     * Check if template is hourly-based.
     */
    public function isHourly(): bool
    {
        return $this->allocation_method === 'hourly';
    }

    /**
     * Check if template is salary-based.
     */
    public function isSalaried(): bool
    {
        return $this->allocation_method === 'salaried';
    }

    /**
     * Check if template is commission-based.
     */
    public function isCommission(): bool
    {
        return $this->allocation_method === 'commission';
    }

    /**
     * Get effective overtime multiplier.
     */
    public function getOvertimeMultiplier(): float
    {
        return $this->overtime_rate ?? 1.5;
    }
}

