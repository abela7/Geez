<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($department) {
            if (empty($department->slug)) {
                $department->slug = Str::slug($department->name);
            }
        });

        static::updating(function ($department) {
            if ($department->isDirty('name') && empty($department->slug)) {
                $department->slug = Str::slug($department->name);
            }
        });
    }

    /**
     * Scope for active departments
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered departments
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get departments for select options
     */
    public static function getSelectOptions()
    {
        return static::active()->ordered()->pluck('name', 'slug')->toArray();
    }
}
