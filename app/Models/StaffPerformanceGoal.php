<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffPerformanceGoal extends Model
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
        'staff_id',
        'goal_title',
        'goal_description',
        'target_value',
        'current_value',
        'measurement_unit',
        'goal_type',
        'priority',
        'start_date',
        'target_date',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'start_date' => 'date',
        'target_date' => 'date',
        'target_value' => 'decimal:2',
        'current_value' => 'decimal:2',
    ];

    /**
     * Get the staff member this goal belongs to.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the staff member who created this goal.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Get the staff member who last updated this goal.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'updated_by');
    }

    /**
     * Calculate progress percentage.
     */
    public function getProgressPercentage(): float
    {
        if (! $this->target_value || $this->target_value == 0) {
            return 0;
        }

        return min(100, ($this->current_value / $this->target_value) * 100);
    }

    /**
     * Check if goal is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->target_date && $this->target_date->isPast() && $this->status !== 'completed';
    }

    /**
     * Scope for active goals.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for completed goals.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for overdue goals.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
            ->orWhere(function ($query) {
                $query->where('status', 'active')
                    ->where('target_date', '<', now());
            });
    }

    /**
     * Scope for goals by priority.
     */
    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope for goals by type.
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('goal_type', $type);
    }
}
