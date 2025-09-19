<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffTaskDependency extends Model
{
    use HasUlid, SoftDeletes;

    /**
     * Indicates if the model's ID is auto-incrementing.
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
        'task_id',
        'depends_on_task_id',
        'dependency_type',
        'lag_days',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'lag_days' => 'integer',
    ];

    /**
     * Get the task that has the dependency.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(StaffTask::class, 'task_id');
    }

    /**
     * Get the task that this task depends on.
     */
    public function dependsOnTask(): BelongsTo
    {
        return $this->belongsTo(StaffTask::class, 'depends_on_task_id');
    }

    /**
     * Get the staff member who created this dependency.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Get the staff member who last updated this dependency.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'updated_by');
    }

    /**
     * Check if the dependency is satisfied.
     */
    public function isSatisfied(): bool
    {
        $dependsOnTask = $this->dependsOnTask;
        
        if (!$dependsOnTask) {
            return true; // If dependency task doesn't exist, consider satisfied
        }

        // Get the latest assignment for the dependency task
        $latestAssignment = $dependsOnTask->assignments()
            ->whereNotIn('status', ['cancelled'])
            ->orderBy('assigned_date', 'desc')
            ->first();

        if (!$latestAssignment) {
            return false; // No assignment means not satisfied
        }

        return match ($this->dependency_type) {
            'finish_to_start' => $latestAssignment->isCompleted(),
            'start_to_start' => $latestAssignment->started_at !== null,
            'finish_to_finish' => $latestAssignment->isCompleted(),
            'start_to_finish' => $latestAssignment->started_at !== null,
            default => false,
        };
    }

    /**
     * Get the earliest date this task can start based on dependency.
     */
    public function getEarliestStartDate(): ?\Carbon\Carbon
    {
        $dependsOnTask = $this->dependsOnTask;
        
        if (!$dependsOnTask || $this->isSatisfied()) {
            return now()->addDays($this->lag_days);
        }

        $latestAssignment = $dependsOnTask->assignments()
            ->whereNotIn('status', ['cancelled'])
            ->orderBy('assigned_date', 'desc')
            ->first();

        if (!$latestAssignment || !$latestAssignment->due_date) {
            return null; // Can't determine start date
        }

        return match ($this->dependency_type) {
            'finish_to_start' => $latestAssignment->due_date->addDays($this->lag_days + 1),
            'start_to_start' => $latestAssignment->assigned_date->addDays($this->lag_days),
            'finish_to_finish' => $latestAssignment->due_date->addDays($this->lag_days),
            'start_to_finish' => $latestAssignment->assigned_date->addDays($this->lag_days),
            default => null,
        };
    }

    /**
     * Scope for dependencies of a specific task.
     */
    public function scopeForTask($query, string $taskId)
    {
        return $query->where('task_id', $taskId);
    }

    /**
     * Scope for tasks that depend on a specific task.
     */
    public function scopeDependingOn($query, string $taskId)
    {
        return $query->where('depends_on_task_id', $taskId);
    }

    /**
     * Scope for specific dependency type.
     */
    public function scopeByType($query, string $dependencyType)
    {
        return $query->where('dependency_type', $dependencyType);
    }

    /**
     * Scope for satisfied dependencies.
     */
    public function scopeSatisfied($query)
    {
        return $query->whereHas('dependsOnTask.assignments', function ($q) {
            $q->where('status', 'completed');
        });
    }

    /**
     * Scope for unsatisfied dependencies.
     */
    public function scopeUnsatisfied($query)
    {
        return $query->whereDoesntHave('dependsOnTask.assignments', function ($q) {
            $q->where('status', 'completed');
        });
    }

    /**
     * Get dependency type display name.
     */
    public function getDependencyTypeDisplayName(): string
    {
        return match ($this->dependency_type) {
            'finish_to_start' => 'Finish to Start',
            'start_to_start' => 'Start to Start',
            'finish_to_finish' => 'Finish to Finish',
            'start_to_finish' => 'Start to Finish',
            default => 'Unknown',
        };
    }

    /**
     * Validate that dependency doesn't create circular reference.
     */
    public function validateNoCycles(): bool
    {
        return !$this->wouldCreateCycle($this->task_id, $this->depends_on_task_id, []);
    }

    /**
     * Recursively check for circular dependencies.
     */
    private function wouldCreateCycle(string $taskId, string $dependsOnTaskId, array $visited): bool
    {
        if ($taskId === $dependsOnTaskId) {
            return true; // Direct cycle
        }

        if (in_array($dependsOnTaskId, $visited)) {
            return true; // Indirect cycle
        }

        $visited[] = $dependsOnTaskId;

        // Check if the depends_on_task has any dependencies that could create a cycle
        $dependencies = static::where('task_id', $dependsOnTaskId)->get();
        
        foreach ($dependencies as $dependency) {
            if ($this->wouldCreateCycle($taskId, $dependency->depends_on_task_id, $visited)) {
                return true;
            }
        }

        return false;
    }
}