<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecipeIngredient extends Model
{
    protected $fillable = [
        'recipe_id',
        'ingredient_id',
        'quantity',
        'unit',
        'cost',
        'notes',
        'sort_order',
        'is_optional',
        'preparation',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'cost' => 'decimal:2',
        'sort_order' => 'integer',
        'is_optional' => 'boolean',
    ];

    /**
     * Get the recipe that owns this ingredient
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    /**
     * Get the ingredient
     */
    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }

    /**
     * Get formatted quantity with unit
     */
    public function getFormattedQuantityAttribute(): string
    {
        return $this->quantity.' '.$this->unit;
    }

    /**
     * Get formatted cost
     */
    public function getFormattedCostAttribute(): string
    {
        return $this->cost ? '$'.number_format($this->cost, 2) : 'N/A';
    }
}
