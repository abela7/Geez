<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecipeInstruction extends Model
{
    protected $fillable = [
        'recipe_id',
        'step_number',
        'instruction',
        'duration',
        'temperature',
        'tips',
        'image_path',
    ];

    protected $casts = [
        'step_number' => 'integer',
        'duration' => 'integer',
    ];

    /**
     * Get the recipe that owns this instruction
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDurationAttribute(): string
    {
        if (! $this->duration) {
            return '';
        }

        return $this->duration.' min';
    }

    /**
     * Get step label
     */
    public function getStepLabelAttribute(): string
    {
        return 'Step '.$this->step_number;
    }
}
