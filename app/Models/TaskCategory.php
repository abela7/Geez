<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TaskCategory extends Model
{
    use HasUlid, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'icon',
        'parent_id',
        'is_active',
        'sort_order',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('name') && empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    /**
     * Get the parent category.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(TaskCategory::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children(): HasMany
    {
        return $this->hasMany(TaskCategory::class, 'parent_id');
    }

    /**
     * Get the staff member who created this category.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Get the staff member who last updated this category.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'updated_by');
    }

    /**
     * Get all tasks in this category.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(StaffTask::class, 'category', 'slug');
    }

    /**
     * Get all templates in this category.
     */
    public function templates(): HasMany
    {
        return $this->hasMany(TaskTemplate::class);
    }

    /**
     * Scope to get only active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only root categories (no parent).
     */
    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get the full category path (Parent > Child).
     */
    public function getFullNameAttribute(): string
    {
        if ($this->parent) {
            return $this->parent->full_name.' > '.$this->name;
        }

        return $this->name;
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
