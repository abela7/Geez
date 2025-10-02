<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffType extends Model
{
    use HasUlid, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'is_active',
        'priority',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer',
    ];

    /**
     * Get the staff members for this staff type.
     */
    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class);
    }

    /**
     * Get the performance templates for this staff type.
     */
    public function performanceTemplates(): HasMany
    {
        return $this->hasMany(StaffPerformanceTemplate::class);
    }

    /**
     * Get active staff members for this staff type.
     */
    public function activeStaff(): HasMany
    {
        return $this->staff()->where('status', 'active');
    }

    /**
     * Get the staff member who created this staff type.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Get the staff member who last updated this staff type.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'updated_by');
    }

    /**
     * Scope for active staff types only.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for staff types ordered by priority.
     */
    public function scopeByPriority($query, string $direction = 'desc')
    {
        return $query->orderBy('priority', $direction);
    }

    /**
     * Get priority level description.
     */
    public function getPriorityLevelAttribute(): string
    {
        return match (true) {
            $this->priority >= 100 => 'System Level',
            $this->priority >= 80 => 'Administrative',
            $this->priority >= 60 => 'Management',
            $this->priority >= 40 => 'Supervisory',
            $this->priority >= 20 => 'Operational',
            default => 'Basic'
        };
    }

    /**
     * Create default staff types.
     */
    public static function createDefaultTypes(): void
    {
        $defaultTypes = [
            [
                'name' => 'system_admin',
                'display_name' => 'System Admin',
                'description' => 'Full system access and configuration',
                'priority' => 100,
            ],
            [
                'name' => 'administrator',
                'display_name' => 'Administrator',
                'description' => 'Administrative access to restaurant operations',
                'priority' => 80,
            ],
            [
                'name' => 'management',
                'display_name' => 'Management',
                'description' => 'Management level access and oversight',
                'priority' => 60,
            ],
            [
                'name' => 'chief',
                'display_name' => 'Chief',
                'description' => 'Head chef with kitchen management responsibilities',
                'priority' => 50,
            ],
            [
                'name' => 'kitchen_porter',
                'display_name' => 'Kitchen Porter',
                'description' => 'Kitchen support and cleaning duties',
                'priority' => 25,
            ],
            [
                'name' => 'injera_maker',
                'display_name' => 'Injera Maker',
                'description' => 'Specialized injera production and baking',
                'priority' => 30,
            ],
            [
                'name' => 'waiter',
                'display_name' => 'Waiter',
                'description' => 'Front-of-house customer service and order taking',
                'priority' => 35,
            ],
        ];

        foreach ($defaultTypes as $typeData) {
            self::firstOrCreate(
                ['name' => $typeData['name']],
                $typeData
            );
        }
    }
}
