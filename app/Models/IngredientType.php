<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class IngredientType extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'properties',
        'measurement_type',
        'compatible_units',
        'color_code',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'properties' => 'array',
        'compatible_units' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($type) {
            if (empty($type->slug)) {
                $type->slug = Str::slug($type->name);
            }
        });
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeByMeasurementType(Builder $query, string $measurementType): Builder
    {
        return $query->where('measurement_type', $measurementType);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Accessors
    public function getColorStyleAttribute(): string
    {
        return "background-color: {$this->color_code}20; color: {$this->color_code};";
    }

    // Methods
    public function isUnitCompatible(int $unitId): bool
    {
        return in_array($unitId, $this->compatible_units ?? []);
    }

    public function getCompatibleUnits()
    {
        if (empty($this->compatible_units)) {
            return collect();
        }
        
        return IngredientUnit::whereIn('id', $this->compatible_units)
            ->active()
            ->ordered()
            ->get();
    }
}