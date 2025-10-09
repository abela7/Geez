<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'scheduled_date',
        'scheduled_time',
        'scheduled_datetime',
        'status',
        'quality_rating',
        'quality_rating_by',
        'quality_rating_at',
        'quality_rating_notes',
        'is_overdue',
        'overdue_since',
        'urgency_level',
        'started_at',
        'completed_at',
        'actual_start_time',
        'actual_end_time',
        'notes',
        'assignment_notes',
        'completion_notes',
        'assigned_by',
        'completed_by',
        'updated_by',
        'progress_percentage',
        'estimated_hours',
        'actual_hours',
        'break_minutes',
        'priority_override',
        'reminder_sent_at',
        'reminder_schedule',
        'last_reminder_sent',
        'reminder_count',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'assigned_date' => 'date',
        'due_date' => 'date',
        'scheduled_date' => 'date',
        'scheduled_time' => 'datetime:H:i',
        'scheduled_datetime' => 'datetime',
        'quality_rating_at' => 'datetime',
        'is_overdue' => 'boolean',
        'overdue_since' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'actual_start_time' => 'datetime',
        'actual_end_time' => 'datetime',
        'estimated_hours' => 'decimal:2',
        'actual_hours' => 'decimal:2',
        'break_minutes' => 'integer',
        'reminder_sent_at' => 'datetime',
        'reminder_schedule' => 'array',
        'last_reminder_sent' => 'datetime',
        'reminder_count' => 'integer',
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
     * Get the staff member who last updated this assignment.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'updated_by');
    }

    /**
     * Get the staff member who rated the quality of this assignment.
     */
    public function qualityRatedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'quality_rating_by');
    }

    /**
     * Get all comments for this task assignment.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(StaffTaskComment::class, 'task_assignment_id');
    }

    /**
     * Get public comments (visible to assignee).
     */
    public function publicComments(): HasMany
    {
        return $this->comments()->where('is_internal', false);
    }

    /**
     * Get internal comments (not visible to assignee).
     */
    public function internalComments(): HasMany
    {
        return $this->comments()->where('is_internal', true);
    }

    /**
     * Get all attachments for this task assignment.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(StaffTaskAttachment::class, 'task_assignment_id');
    }

    /**
     * Get image attachments only.
     */
    public function imageAttachments(): HasMany
    {
        return $this->attachments()->where('mime_type', 'like', 'image/%');
    }

    /**
     * Get document attachments only.
     */
    public function documentAttachments(): HasMany
    {
        return $this->attachments()->whereIn('mime_type', [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/plain',
        ]);
    }

    /**
     * Get all time entries for this task assignment.
     */
    public function timeEntries(): HasMany
    {
        return $this->hasMany(StaffTaskTimeEntry::class, 'task_assignment_id');
    }

    /**
     * Get active time entries (currently running).
     */
    public function activeTimeEntries(): HasMany
    {
        return $this->timeEntries()->whereNull('end_time');
    }

    /**
     * Get completed time entries.
     */
    public function completedTimeEntries(): HasMany
    {
        return $this->timeEntries()->whereNotNull('end_time');
    }

    /**
     * Get billable time entries only.
     */
    public function billableTimeEntries(): HasMany
    {
        return $this->timeEntries()->where('is_billable', true);
    }

    /**
     * Get all notifications for this task assignment.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(StaffTaskNotification::class, 'task_assignment_id');
    }

    /**
     * Get all notes for this assignment.
     */
    public function notes(): HasMany
    {
        return $this->hasMany(TaskNote::class, 'staff_task_assignment_id');
    }

    /**
     * Get all reminders for this assignment.
     */
    public function reminders(): HasMany
    {
        return $this->hasMany(TaskReminder::class, 'staff_task_assignment_id');
    }

    /**
     * Get unread notifications for this assignment.
     */
    public function unreadNotifications(): HasMany
    {
        return $this->notifications()->where('is_read', false);
    }

    /**
     * Get urgent notifications for this assignment.
     */
    public function urgentNotifications(): HasMany
    {
        return $this->notifications()->whereIn('notification_type', [
            StaffTaskNotification::TYPE_OVERDUE,
            StaffTaskNotification::TYPE_DUE_SOON,
        ]);
    }

    /**
     * Check if task is overdue.
     */
    public function isOverdue(): bool
    {
        if (in_array($this->status, ['completed', 'cancelled'])) {
            return false;
        }

        // If we have a scheduled datetime, use that for precise comparison
        if ($this->scheduled_datetime) {
            return $this->scheduled_datetime->isPast();
        }

        // If we have a due date with scheduled time, combine them
        if ($this->due_date && $this->scheduled_time) {
            $dueDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', 
                $this->due_date->format('Y-m-d') . ' ' . $this->scheduled_time->format('H:i:s')
            );
            return $dueDateTime->isPast();
        }

        // If we only have a due date, consider it overdue at end of day
        if ($this->due_date) {
            return $this->due_date->endOfDay()->isPast();
        }

        return false;
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
        if (! $this->started_at || ! $this->completed_at) {
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
        return $query->whereNotIn('status', ['completed', 'cancelled'])
            ->where(function ($q) {
                // Check scheduled_datetime first
                $q->where(function ($sq) {
                    $sq->whereNotNull('scheduled_datetime')
                       ->where('scheduled_datetime', '<', now());
                })
                // Or check due_date with scheduled_time
                ->orWhere(function ($sq) {
                    $sq->whereNull('scheduled_datetime')
                       ->whereNotNull('due_date')
                       ->whereNotNull('scheduled_time')
                       ->whereRaw("CONCAT(due_date, ' ', scheduled_time) < ?", [now()]);
                })
                // Or check due_date only (end of day)
                ->orWhere(function ($sq) {
                    $sq->whereNull('scheduled_datetime')
                       ->whereNull('scheduled_time')
                       ->whereNotNull('due_date')
                       ->where('due_date', '<', now()->startOfDay());
                });
            });
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
     * Get effective priority (override or task priority).
     */
    public function getEffectivePriority(): string
    {
        return $this->priority_override ?? $this->task->priority ?? 'medium';
    }

    /**
     * Update progress percentage.
     */
    public function updateProgress(int $percentage): void
    {
        $this->update([
            'progress_percentage' => max(0, min(100, $percentage)),
            'updated_by' => auth()->id(),
        ]);
    }

    /**
     * Check if reminder should be sent.
     */
    public function shouldSendReminder(): bool
    {
        if (! $this->due_date || $this->isCompleted()) {
            return false;
        }

        // Send reminder if due in 24 hours and no reminder sent yet
        $reminderThreshold = $this->due_date->subDay();

        return now()->gte($reminderThreshold) &&
               (! $this->reminder_sent_at || $this->reminder_sent_at->lt($reminderThreshold));
    }

    /**
     * Mark reminder as sent.
     */
    public function markReminderSent(): void
    {
        $this->update(['reminder_sent_at' => now()]);
    }

    /**
     * Scope for assignments needing reminders.
     */
    public function scopeNeedingReminders($query)
    {
        return $query->whereNotIn('status', ['completed', 'cancelled'])
            ->whereNotNull('due_date')
            ->where('due_date', '<=', now()->addDay())
            ->where(function ($q) {
                $q->whereNull('reminder_sent_at')
                    ->orWhere('reminder_sent_at', '<', now()->subDays(1));
            });
    }

    /**
     * Get total time spent on this assignment.
     */
    public function getTotalTimeSpent(): float
    {
        return $this->timeEntries()
            ->whereNotNull('end_time')
            ->sum('duration_minutes') / 60; // Convert to hours
    }

    /**
     * Get total billable time for this assignment.
     */
    public function getTotalBillableTime(): float
    {
        return $this->timeEntries()
            ->whereNotNull('end_time')
            ->where('is_billable', true)
            ->sum('duration_minutes') / 60; // Convert to hours
    }

    /**
     * Check if there's an active time entry.
     */
    public function hasActiveTimeEntry(): bool
    {
        return $this->activeTimeEntries()->exists();
    }

    /**
     * Start time tracking for this assignment.
     */
    public function startTimeTracking(?string $description = null, bool $isBillable = true): StaffTaskTimeEntry
    {
        // Stop any existing active time entries first
        $this->stopActiveTimeTracking();

        return StaffTaskTimeEntry::startTracking(
            $this->id,
            $this->staff_id,
            $description,
            $isBillable
        );
    }

    /**
     * Stop active time tracking for this assignment.
     */
    public function stopActiveTimeTracking(?string $description = null): void
    {
        $activeEntries = $this->activeTimeEntries()->get();

        foreach ($activeEntries as $entry) {
            $entry->stop($description);
        }
    }

    /**
     * Send notification to assignee.
     */
    public function sendNotification(
        string $type,
        string $title,
        string $message,
        ?string $createdBy = null
    ): StaffTaskNotification {
        $notification = StaffTaskNotification::create([
            'task_assignment_id' => $this->id,
            'staff_id' => $this->staff_id,
            'notification_type' => $type,
            'title' => $title,
            'message' => $message,
            'created_by' => $createdBy ?? auth()->id(),
        ]);

        $notification->send();

        return $notification;
    }

    /**
     * Send reminder notification.
     */
    public function sendReminderNotification(): ?StaffTaskNotification
    {
        if (! $this->shouldSendReminder()) {
            return null;
        }

        $notification = StaffTaskNotification::createReminderNotification(
            $this->id,
            $this->staff_id,
            $this->task->title,
            $this->due_date->format('M j, Y')
        );

        $notification->send();
        $this->markReminderSent();

        return $notification;
    }

    /**
     * Send overdue notification.
     */
    public function sendOverdueNotification(): ?StaffTaskNotification
    {
        if (! $this->isOverdue()) {
            return null;
        }

        $notification = StaffTaskNotification::createOverdueNotification(
            $this->id,
            $this->staff_id,
            $this->task->title
        );

        $notification->send();

        return $notification;
    }

    /**
     * Send completion notification to relevant staff.
     */
    public function sendCompletionNotification(): void
    {
        // Notify the assignee
        if ($this->completed_by !== $this->staff_id) {
            $completedByStaff = Staff::find($this->completed_by);

            StaffTaskNotification::createCompletionNotification(
                $this->id,
                $this->staff_id,
                $this->task->title,
                $completedByStaff->full_name ?? 'Unknown'
            )->send();
        }

        // Notify the assigner if different from assignee and completer
        if ($this->assigned_by !== $this->staff_id && $this->assigned_by !== $this->completed_by) {
            $completedByStaff = Staff::find($this->completed_by);

            StaffTaskNotification::createCompletionNotification(
                $this->id,
                $this->assigned_by,
                $this->task->title,
                $completedByStaff->full_name ?? 'Unknown'
            )->send();
        }
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

    /**
     * Check if assignment is overdue and update status.
     */
    public function checkAndUpdateOverdueStatus(): bool
    {
        $isOverdue = $this->isOverdue();

        if ($isOverdue && ! $this->is_overdue) {
            $this->update([
                'is_overdue' => true,
                'overdue_since' => now(),
            ]);
        } elseif (! $isOverdue && $this->is_overdue) {
            $this->update([
                'is_overdue' => false,
                'overdue_since' => null,
            ]);
        }

        return $isOverdue;
    }

    /**
     * Calculate total time worked on this assignment.
     */
    public function getTotalTimeWorked(): int
    {
        return $this->timeEntries()->where('entry_type', 'work')->sum('duration_minutes');
    }

    /**
     * Get formatted scheduled datetime.
     */
    public function getFormattedScheduleAttribute(): ?string
    {
        if ($this->scheduled_datetime) {
            return $this->scheduled_datetime->format('M j, Y \a\t g:i A');
        }

        if ($this->scheduled_date) {
            $dateStr = $this->scheduled_date->format('M j, Y');
            if ($this->scheduled_time) {
                $dateStr .= ' at '.$this->scheduled_time->format('g:i A');
            }

            return $dateStr;
        }

        return null;
    }

    /**
     * Add a note to this assignment.
     */
    public function addNote(string $content, string $type = 'general', bool $isImportant = false): TaskNote
    {
        return $this->notes()->create([
            'staff_task_id' => $this->staff_task_id,
            'staff_id' => auth()->id(),
            'note_type' => $type,
            'content' => $content,
            'is_important' => $isImportant,
        ]);
    }

    /**
     * Rate the quality of this task assignment.
     */
    public function rateQuality(string $rating, ?string $notes = null, ?string $ratedBy = null): void
    {
        $validRatings = ['bad', 'good', 'excellent'];
        
        if (!in_array($rating, $validRatings)) {
            throw new \InvalidArgumentException("Invalid quality rating. Must be one of: " . implode(', ', $validRatings));
        }

        $this->update([
            'quality_rating' => $rating,
            'quality_rating_by' => $ratedBy ?? auth()->id(),
            'quality_rating_at' => now(),
            'quality_rating_notes' => $notes,
        ]);
    }

    /**
     * Check if the task has been quality rated.
     */
    public function hasQualityRating(): bool
    {
        return !is_null($this->quality_rating_at);
    }

    /**
     * Get quality rating display name.
     */
    public function getQualityRatingDisplayAttribute(): string
    {
        return match($this->quality_rating) {
            'bad' => 'Bad',
            'good' => 'Good', 
            'excellent' => 'Excellent',
            default => 'Not Rated'
        };
    }

    /**
     * Get quality rating color for UI.
     */
    public function getQualityRatingColorAttribute(): string
    {
        return match($this->quality_rating) {
            'bad' => '#EF4444',      // Red
            'good' => '#10B981',     // Green  
            'excellent' => '#8B5CF6', // Purple
            default => '#6B7280'     // Gray
        };
    }

    /**
     * Get quality rating score for performance calculations.
     */
    public function getQualityRatingScoreAttribute(): int
    {
        return match($this->quality_rating) {
            'bad' => 1,
            'good' => 2,
            'excellent' => 3,
            default => 0
        };
    }

    /**
     * Scope for assignments with specific quality rating.
     */
    public function scopeWithQualityRating($query, string $rating)
    {
        return $query->where('quality_rating', $rating);
    }

    /**
     * Scope for assignments that have been quality rated.
     */
    public function scopeQualityRated($query)
    {
        return $query->whereNotNull('quality_rating_at');
    }

    /**
     * Scope for assignments that need quality rating (completed but not rated).
     */
    public function scopeNeedingQualityRating($query)
    {
        return $query->where('status', 'completed')
                    ->whereNull('quality_rating_at');
    }
}
