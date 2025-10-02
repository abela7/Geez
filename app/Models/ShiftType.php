<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ShiftType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'is_active',
        'default_hourly_rate',
        'default_overtime_rate',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'default_hourly_rate' => 'decimal:2',
        'default_overtime_rate' => 'decimal:2',
        'sort_order' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($shiftType) {
            if (empty($shiftType->slug)) {
                $shiftType->slug = Str::slug($shiftType->name);
            }
        });

        static::updating(function ($shiftType) {
            if ($shiftType->isDirty('name') && empty($shiftType->slug)) {
                $shiftType->slug = Str::slug($shiftType->name);
            }
        });
    }

    /**
     * Scope for active shift types
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered shift types
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get shift types for select options
     */
    public static function getSelectOptions()
    {
        return static::active()->ordered()->pluck('name', 'slug')->toArray();
    }
}
