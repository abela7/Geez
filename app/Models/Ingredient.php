<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ingredient extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'category',
        'subcategory',
        'unit',
        'conversion_rates',
        'cost_per_unit',
        'minimum_order_qty',
        'supplier_id',
        'lead_time_days',
        'storage_requirements',
        'shelf_life_days',
        'origin_country',
        'nutritional_info',
        'allergen_info',
        'status',
        'notes',
        'last_updated',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'conversion_rates' => 'array',
        'cost_per_unit' => 'decimal:4',
        'minimum_order_qty' => 'decimal:2',
        'lead_time_days' => 'integer',
        'shelf_life_days' => 'integer',
        'nutritional_info' => 'array',
        'allergen_info' => 'array',
        'last_updated' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the supplier that owns the ingredient.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the formatted cost per unit with currency.
     */
    public function getFormattedCostAttribute(): string
    {
        return '$'.number_format((float) $this->cost_per_unit, 2);
    }

    /**
     * Get the total estimated value based on minimum order quantity.
     */
    public function getMinimumOrderValueAttribute(): float
    {
        return (float) ($this->cost_per_unit * $this->minimum_order_qty);
    }

    /**
     * Get the formatted minimum order value.
     */
    public function getFormattedMinimumOrderValueAttribute(): string
    {
        return '$'.number_format($this->minimum_order_value, 2);
    }

    /**
     * Check if ingredient has specific allergen.
     */
    public function hasAllergen(string $allergen): bool
    {
        return in_array($allergen, $this->allergen_info ?? []);
    }

    /**
     * Get allergens as comma-separated string.
     */
    public function getAllergensStringAttribute(): string
    {
        return empty($this->allergen_info) ? 'None' : implode(', ', $this->allergen_info);
    }

    /**
     * Check if ingredient is allergen-free.
     */
    public function isAllergenFree(): bool
    {
        return empty($this->allergen_info);
    }

    /**
     * Get nutritional value by key.
     */
    public function getNutritionalValue(string $key): ?float
    {
        return $this->nutritional_info[$key] ?? null;
    }

    /**
     * Get calories per 100g.
     */
    public function getCaloriesPer100gAttribute(): ?float
    {
        return $this->getNutritionalValue('calories');
    }

    /**
     * Get protein per 100g.
     */
    public function getProteinPer100gAttribute(): ?float
    {
        return $this->getNutritionalValue('protein');
    }

    /**
     * Check if ingredient needs refrigeration.
     */
    public function needsRefrigeration(): bool
    {
        return in_array($this->storage_requirements, ['refrigerated', 'frozen']);
    }

    /**
     * Check if ingredient is perishable (has shelf life).
     */
    public function isPerishable(): bool
    {
        return ! is_null($this->shelf_life_days) && $this->shelf_life_days > 0;
    }

    /**
     * Get days until expiry (if applicable).
     */
    public function getDaysUntilExpiryAttribute(): ?int
    {
        if (! $this->isPerishable() || ! $this->last_updated) {
            return null;
        }

        $expiryDate = $this->last_updated->addDays($this->shelf_life_days);

        return $expiryDate->diffInDays(now(), false);
    }

    /**
     * Check if ingredient is expired.
     */
    public function isExpired(): bool
    {
        $daysUntilExpiry = $this->days_until_expiry;

        return $daysUntilExpiry !== null && $daysUntilExpiry < 0;
    }

    /**
     * Check if ingredient is expiring soon (within 7 days).
     */
    public function isExpiringSoon(): bool
    {
        $daysUntilExpiry = $this->days_until_expiry;

        return $daysUntilExpiry !== null && $daysUntilExpiry <= 7 && $daysUntilExpiry >= 0;
    }

    /**
     * Scope a query to only include active ingredients.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include inactive ingredients.
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope a query to only include discontinued ingredients.
     */
    public function scopeDiscontinued(Builder $query): Builder
    {
        return $query->where('status', 'discontinued');
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    /**
     * Scope a query to filter by supplier.
     */
    public function scopeBySupplier(Builder $query, int $supplierId): Builder
    {
        return $query->where('supplier_id', $supplierId);
    }

    /**
     * Scope a query to filter by allergen.
     */
    public function scopeWithAllergen(Builder $query, string $allergen): Builder
    {
        return $query->whereJsonContains('allergen_info', $allergen);
    }

    /**
     * Scope a query to filter allergen-free ingredients.
     */
    public function scopeAllergenFree(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereNull('allergen_info')
                ->orWhereJsonLength('allergen_info', 0);
        });
    }

    /**
     * Scope a query to filter by storage requirements.
     */
    public function scopeByStorage(Builder $query, string $storage): Builder
    {
        return $query->where('storage_requirements', $storage);
    }

    /**
     * Scope a query to filter perishable ingredients.
     */
    public function scopePerishable(Builder $query): Builder
    {
        return $query->whereNotNull('shelf_life_days')
            ->where('shelf_life_days', '>', 0);
    }

    /**
     * Scope a query to filter expired ingredients.
     */
    public function scopeExpired(Builder $query): Builder
    {
        return $query->whereNotNull('shelf_life_days')
            ->whereNotNull('last_updated')
            ->whereRaw('DATE_ADD(last_updated, INTERVAL shelf_life_days DAY) < NOW()');
    }

    /**
     * Scope a query to filter ingredients expiring soon.
     */
    public function scopeExpiringSoon(Builder $query, int $days = 7): Builder
    {
        return $query->whereNotNull('shelf_life_days')
            ->whereNotNull('last_updated')
            ->whereRaw('DATE_ADD(last_updated, INTERVAL shelf_life_days DAY) BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL ? DAY)', [$days]);
    }

    /**
     * Scope a query to search ingredients by name, code, or description.
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    }

    /**
     * Update the last_updated timestamp.
     */
    public function touchLastUpdated(): void
    {
        $this->update(['last_updated' => now()]);
    }
}
