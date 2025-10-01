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
        'instructions',
        'task_type',
        'priority',
        'is_active',
        'created_by',
        'category',
        'estimated_hours',
        'scheduled_date',
        'scheduled_time',
        'duration_minutes',
        'is_template',
        'template_name',
        'recurrence_pattern',
        'recurrence_interval',
        'recurrence_end_date',
        'recurrence_type',
        'recurrence_config',
        'requires_approval',
        'approval_workflow',
        'auto_assign',
        'default_assignees',
        'tags',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'estimated_hours' => 'decimal:2',
        'scheduled_date' => 'date',
        'scheduled_time' => 'datetime:H:i',
        'duration_minutes' => 'integer',
        'is_template' => 'boolean',
        'recurrence_end_date' => 'date',
        'recurrence_config' => 'array',
        'requires_approval' => 'boolean',
        'approval_workflow' => 'array',
        'auto_assign' => 'boolean',
        'default_assignees' => 'array',
        'tags' => 'array',
    ];

    /**
     * Get the staff member who created this task.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Get the staff member who last updated this task.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'updated_by');
    }

    /**
     * Get all assignments for this task.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(StaffTaskAssignment::class);
    }

    /**
     * Get all dependencies for this task (tasks this task depends on).
     */
    public function dependencies(): HasMany
    {
        return $this->hasMany(StaffTaskDependency::class, 'task_id');
    }

    /**
     * Get all tasks that depend on this task.
     */
    public function dependentTasks(): HasMany
    {
        return $this->hasMany(StaffTaskDependency::class, 'depends_on_task_id');
    }

    /**
     * Get all notes for this task.
     */
    public function notes(): HasMany
    {
        return $this->hasMany(TaskNote::class, 'staff_task_id');
    }

    /**
     * Get important notes for this task.
     */
    public function importantNotes(): HasMany
    {
        return $this->hasMany(TaskNote::class, 'staff_task_id')->where('is_important', true);
    }

    /**
     * Get the task type for this task.
     */
    public function taskType(): BelongsTo
    {
        return $this->belongsTo(TaskType::class, 'task_type', 'slug');
    }

    /**
     * Get the task priority for this task.
     */
    public function taskPriority(): BelongsTo
    {
        return $this->belongsTo(TaskPriority::class, 'priority', 'slug');
    }

    /**
     * Get the task category for this task.
     */
    public function taskCategory(): BelongsTo
    {
        return $this->belongsTo(TaskCategory::class, 'category', 'slug');
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

    /**
     * Scope for tasks by category.
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for template tasks.
     */
    public function scopeTemplates($query)
    {
        return $query->where('is_template', true);
    }

    /**
     * Scope for non-template tasks.
     */
    public function scopeNonTemplates($query)
    {
        return $query->where('is_template', false);
    }

    /**
     * Scope for tasks requiring approval.
     */
    public function scopeRequiringApproval($query)
    {
        return $query->where('requires_approval', true);
    }

    /**
     * Check if task has recurrence.
     */
    public function hasRecurrence(): bool
    {
        return $this->recurrence_pattern !== 'none';
    }

    /**
     * Check if task is a template.
     */
    public function isTemplate(): bool
    {
        return $this->is_template;
    }

    /**
     * Check if task requires approval.
     */
    public function requiresApproval(): bool
    {
        return $this->requires_approval;
    }

    /**
     * Check if all dependencies are satisfied.
     */
    public function areDependenciesSatisfied(): bool
    {
        return $this->dependencies()->get()->every(function ($dependency) {
            return $dependency->isSatisfied();
        });
    }

    /**
     * Get unsatisfied dependencies.
     */
    public function getUnsatisfiedDependencies()
    {
        return $this->dependencies()->get()->filter(function ($dependency) {
            return !$dependency->isSatisfied();
        });
    }

    /**
     * Check if task can be started (no blocking dependencies).
     */
    public function canBeStarted(): bool
    {
        return $this->areDependenciesSatisfied();
    }

    /**
     * Get the earliest date this task can start.
     */
    public function getEarliestStartDate(): ?\Carbon\Carbon
    {
        $dependencies = $this->dependencies()->get();
        
        if ($dependencies->isEmpty()) {
            return now(); // No dependencies, can start now
        }

        $earliestDates = $dependencies->map(function ($dependency) {
            return $dependency->getEarliestStartDate();
        })->filter(); // Remove null values

        return $earliestDates->isEmpty() ? null : $earliestDates->max();
    }

    /**
     * Add a dependency to this task.
     */
    public function addDependency(string $dependsOnTaskId, string $dependencyType = 'finish_to_start', int $lagDays = 0): ?StaffTaskDependency
    {
        // Create temporary dependency to validate
        $tempDependency = new StaffTaskDependency([
            'task_id' => $this->id,
            'depends_on_task_id' => $dependsOnTaskId,
            'dependency_type' => $dependencyType,
            'lag_days' => $lagDays,
        ]);

        // Validate no cycles
        if (!$tempDependency->validateNoCycles()) {
            return null; // Would create a cycle
        }

        // Create the actual dependency
        return $this->dependencies()->create([
            'depends_on_task_id' => $dependsOnTaskId,
            'dependency_type' => $dependencyType,
            'lag_days' => $lagDays,
            'created_by' => auth()->id(),
        ]);
    }

    /**
     * Remove a dependency.
     */
    public function removeDependency(string $dependsOnTaskId): bool
    {
        return $this->dependencies()
            ->where('depends_on_task_id', $dependsOnTaskId)
            ->delete() > 0;
    }
}