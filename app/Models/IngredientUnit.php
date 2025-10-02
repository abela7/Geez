<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class IngredientUnit extends Model
{
    protected $fillable = [
        'name',
        'symbol',
        'type',
        'description',
        'base_conversion_factor',
        'base_unit',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'base_conversion_factor' => 'decimal:4',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Accessors
    public function getDisplayNameAttribute(): string
    {
        return "{$this->name} ({$this->symbol})";
    }

    // Methods
    public function convertToBase(float $value): float
    {
        if ($this->base_conversion_factor) {
            return $value * $this->base_conversion_factor;
        }

        return $value;
    }

    public function convertFromBase(float $baseValue): float
    {
        if ($this->base_conversion_factor) {
            return $baseValue / $this->base_conversion_factor;
        }

        return $baseValue;
    }
}
