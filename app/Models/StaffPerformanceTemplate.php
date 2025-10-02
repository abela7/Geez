<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffPerformanceTemplate extends Model
{
    use HasUlid, SoftDeletes;

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = true;

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = false;

    /**
     * The data type of the auto-incrementing ID.
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'staff_type_id',
        'template_name',
        'review_frequency',
        'rating_criteria',
        'version',
        'is_active',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'rating_criteria' => 'array',
        'version' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the staff type this template belongs to.
     */
    public function staffType(): BelongsTo
    {
        return $this->belongsTo(StaffType::class);
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
     * Get total weight of all criteria.
     */
    public function getTotalWeight(): int
    {
        if (! $this->rating_criteria) {
            return 0;
        }

        return collect($this->rating_criteria)->sum('weight');
    }

    /**
     * Validate that criteria weights sum to 100.
     */
    public function hasValidWeights(): bool
    {
        return $this->getTotalWeight() === 100;
    }

    /**
     * Get criteria by key.
     */
    public function getCriteriaByKey(string $key): ?array
    {
        if (! $this->rating_criteria) {
            return null;
        }

        return collect($this->rating_criteria)->firstWhere('key', $key);
    }

    /**
     * Scope for active templates.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for templates by staff type.
     */
    public function scopeForStaffType($query, string $staffTypeId)
    {
        return $query->where('staff_type_id', $staffTypeId);
    }

    /**
     * Scope for templates by frequency.
     */
    public function scopeByFrequency($query, string $frequency)
    {
        return $query->where('review_frequency', $frequency);
    }

    /**
     * Scope for latest version of templates.
     */
    public function scopeLatestVersion($query)
    {
        return $query->orderBy('version', 'desc');
    }
}
