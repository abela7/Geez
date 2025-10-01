<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Recipe extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'category',
        'serving_size',
        'prep_time',
        'cook_time',
        'total_time',
        'difficulty',
        'cost_per_serving',
        'total_cost',
        'yield',
        'status',
        'calories',
        'protein',
        'carbs',
        'fat',
        'fiber',
        'sodium',
        'notes',
        'tags',
        'image_path',
    ];

    protected $casts = [
        'serving_size' => 'integer',
        'prep_time' => 'integer',
        'cook_time' => 'integer',
        'total_time' => 'integer',
        'cost_per_serving' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'calories' => 'integer',
        'protein' => 'decimal:2',
        'carbs' => 'decimal:2',
        'fat' => 'decimal:2',
        'fiber' => 'decimal:2',
        'sodium' => 'integer',
        'tags' => 'array',
    ];

    /**
     * Get the recipe ingredients
     */
    public function recipeIngredients(): HasMany
    {
        return $this->hasMany(RecipeIngredient::class)->orderBy('sort_order');
    }

    /**
     * Get the ingredients through the pivot table
     */
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'recipe_ingredients')
            ->withPivot(['quantity', 'unit', 'cost', 'notes', 'sort_order', 'is_optional', 'preparation'])
            ->withTimestamps()
            ->orderBy('recipe_ingredients.sort_order');
    }

    /**
     * Get the recipe instructions
     */
    public function instructions(): HasMany
    {
        return $this->hasMany(RecipeInstruction::class)->orderBy('step_number');
    }

    /**
     * Scope for active recipes
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for recipes by category
     */
    public function scopeCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for recipes by difficulty
     */
    public function scopeDifficulty(Builder $query, string $difficulty): Builder
    {
        return $query->where('difficulty', $difficulty);
    }

    /**
     * Calculate total recipe cost based on ingredients
     */
    public function calculateTotalCost(): float
    {
        return $this->recipeIngredients()->sum('cost') ?? 0.0;
    }

    /**
     * Calculate cost per serving
     */
    public function calculateCostPerServing(): float
    {
        $totalCost = $this->calculateTotalCost();
        return $this->serving_size > 0 ? $totalCost / $this->serving_size : 0.0;
    }

    /**
     * Get formatted total time
     */
    public function getFormattedTotalTimeAttribute(): string
    {
        if (!$this->total_time) {
            return 'N/A';
        }

        $hours = intval($this->total_time / 60);
        $minutes = $this->total_time % 60;

        if ($hours > 0) {
            return $minutes > 0 ? "{$hours}h {$minutes}m" : "{$hours}h";
        }

        return "{$minutes}m";
    }

    /**
     * Get difficulty badge class
     */
    public function getDifficultyBadgeClassAttribute(): string
    {
        return match ($this->difficulty) {
            'easy' => 'difficulty-easy',
            'medium' => 'difficulty-medium',
            'hard' => 'difficulty-hard',
            'expert' => 'difficulty-expert',
            default => 'difficulty-medium',
        };
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'active' => 'status-active',
            'inactive' => 'status-inactive',
            'draft' => 'status-draft',
            'testing' => 'status-testing',
            default => 'status-draft',
        };
    }
}