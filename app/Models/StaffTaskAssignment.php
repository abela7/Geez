<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffTaskAssignment extends Model
{
    use HasUlid, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'staff_task_id',
        'staff_id',
        'assigned_date',
        'due_date',
        'status',
        'started_at',
        'completed_at',
        'notes',
        'assigned_by',
        'completed_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'assigned_date' => 'date',
        'due_date' => 'date',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the task for this assignment.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(StaffTask::class, 'staff_task_id');
    }

    /**
     * Get the staff member assigned to this task.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the staff member who assigned this task.
     */
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'assigned_by');
    }

    /**
     * Get the staff member who completed this task.
     */
    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'completed_by');
    }

    /**
     * Check if task is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->due_date && 
               $this->due_date->isPast() && 
               !in_array($this->status, ['completed', 'cancelled']);
    }

    /**
     * Check if task is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if task is in progress.
     */
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Mark task as started.
     */
    public function markAsStarted(): void
    {
        $this->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);
    }

    /**
     * Mark task as completed.
     */
    public function markAsCompleted(?string $completedBy = null): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'completed_by' => $completedBy ?? auth()->id(),
        ]);
    }

    /**
     * Calculate duration if task is completed.
     */
    public function getDurationInMinutes(): ?int
    {
        if (!$this->started_at || !$this->completed_at) {
            return null;
        }

        return $this->completed_at->diffInMinutes($this->started_at);
    }

    /**
     * Scope for assignments on specific date.
     */
    public function scopeForDate($query, string $date)
    {
        return $query->whereDate('assigned_date', $date);
    }

    /**
     * Scope for specific staff member.
     */
    public function scopeForStaff($query, string $staffId)
    {
        return $query->where('staff_id', $staffId);
    }

    /**
     * Scope for overdue assignments.
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->whereNotIn('status', ['completed', 'cancelled']);
    }

    /**
     * Scope for pending assignments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for completed assignments.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Automatically update status to overdue if past due date.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::saving(function (StaffTaskAssignment $assignment) {
            if ($assignment->isOverdue() && $assignment->status === 'pending') {
                $assignment->status = 'overdue';
            }
        });
    }
}