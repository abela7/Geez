<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffTask extends Model
{
    use HasUlid, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'description',
        'task_type',
        'priority',
        'is_active',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the staff member who created this task.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Get all assignments for this task.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(StaffTaskAssignment::class);
    }

    /**
     * Get active assignments for this task.
     */
    public function activeAssignments(): HasMany
    {
        return $this->assignments()->where('status', '!=', 'cancelled');
    }

    /**
     * Get pending assignments for this task.
     */
    public function pendingAssignments(): HasMany
    {
        return $this->assignments()->where('status', 'pending');
    }

    /**
     * Get completed assignments for this task.
     */
    public function completedAssignments(): HasMany
    {
        return $this->assignments()->where('status', 'completed');
    }

    /**
     * Check if task is recurring.
     */
    public function isRecurring(): bool
    {
        return in_array($this->task_type, ['daily', 'weekly', 'monthly']);
    }

    /**
     * Get priority level as integer for sorting.
     */
    public function getPriorityLevel(): int
    {
        return match ($this->priority) {
            'urgent' => 4,
            'high' => 3,
            'medium' => 2,
            'low' => 1,
            default => 0,
        };
    }

    /**
     * Scope for active tasks only.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for tasks by priority.
     */
    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope for tasks by type.
     */
    public function scopeByType($query, string $taskType)
    {
        return $query->where('task_type', $taskType);
    }

    /**
     * Scope for urgent tasks.
     */
    public function scopeUrgent($query)
    {
        return $query->where('priority', 'urgent');
    }

    /**
     * Scope for recurring tasks.
     */
    public function scopeRecurring($query)
    {
        return $query->whereIn('task_type', ['daily', 'weekly', 'monthly']);
    }
}