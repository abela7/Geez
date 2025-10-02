<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffTaskNotification extends Model
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
     * The table associated with the model.
     */
    protected $table = 'staff_task_notifications';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'task_assignment_id',
        'staff_id',
        'notification_type',
        'title',
        'message',
        'is_read',
        'sent_at',
        'read_at',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_read' => 'boolean',
        'sent_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    /**
     * Notification type constants.
     */
    public const TYPE_ASSIGNMENT = 'assignment';

    public const TYPE_REMINDER = 'reminder';

    public const TYPE_OVERDUE = 'overdue';

    public const TYPE_DUE_SOON = 'due_soon';

    public const TYPE_COMPLETED = 'completed';

    public const TYPE_COMMENT = 'comment';

    public const TYPE_STATUS_CHANGE = 'status_change';

    public const TYPE_PROGRESS_UPDATE = 'progress_update';

    /**
     * Get all available notification types.
     */
    public static function getNotificationTypes(): array
    {
        return [
            self::TYPE_ASSIGNMENT,
            self::TYPE_REMINDER,
            self::TYPE_OVERDUE,
            self::TYPE_DUE_SOON,
            self::TYPE_COMPLETED,
            self::TYPE_COMMENT,
            self::TYPE_STATUS_CHANGE,
            self::TYPE_PROGRESS_UPDATE,
        ];
    }

    /**
     * Get the task assignment this notification belongs to.
     */
    public function assignment(): BelongsTo
    {
        return $this->belongsTo(StaffTaskAssignment::class, 'task_assignment_id');
    }

    /**
     * Get the staff member who will receive this notification.
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    /**
     * Get the staff member who created this notification.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Get the staff member who last updated this notification.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'updated_by');
    }

    /**
     * Mark notification as read.
     */
    public function markRead(): void
    {
        if (! $this->is_read) {
            $this->is_read = true;
            $this->read_at = now();
            $this->updated_by = auth()->id();
            $this->save();
        }
    }

    /**
     * Mark notification as unread.
     */
    public function markUnread(): void
    {
        if ($this->is_read) {
            $this->is_read = false;
            $this->read_at = null;
            $this->updated_by = auth()->id();
            $this->save();
        }
    }

    /**
     * Send the notification (mark as sent).
     */
    public function send(): void
    {
        if (! $this->sent_at) {
            $this->sent_at = now();
            $this->updated_by = auth()->id();
            $this->save();
        }
    }

    /**
     * Get notification type display name.
     */
    public function getTypeDisplayName(): string
    {
        return match ($this->notification_type) {
            self::TYPE_ASSIGNMENT => 'Task Assignment',
            self::TYPE_REMINDER => 'Reminder',
            self::TYPE_OVERDUE => 'Overdue Task',
            self::TYPE_DUE_SOON => 'Due Soon',
            self::TYPE_COMPLETED => 'Task Completed',
            self::TYPE_COMMENT => 'New Comment',
            self::TYPE_STATUS_CHANGE => 'Status Change',
            self::TYPE_PROGRESS_UPDATE => 'Progress Update',
            default => 'Notification',
        };
    }

    /**
     * Get notification type icon.
     */
    public function getTypeIcon(): string
    {
        return match ($this->notification_type) {
            self::TYPE_ASSIGNMENT => 'fas fa-tasks',
            self::TYPE_REMINDER => 'fas fa-bell',
            self::TYPE_OVERDUE => 'fas fa-exclamation-triangle',
            self::TYPE_DUE_SOON => 'fas fa-clock',
            self::TYPE_COMPLETED => 'fas fa-check-circle',
            self::TYPE_COMMENT => 'fas fa-comment',
            self::TYPE_STATUS_CHANGE => 'fas fa-exchange-alt',
            self::TYPE_PROGRESS_UPDATE => 'fas fa-chart-line',
            default => 'fas fa-info-circle',
        };
    }

    /**
     * Get notification type color class.
     */
    public function getTypeColorClass(): string
    {
        return match ($this->notification_type) {
            self::TYPE_ASSIGNMENT => 'text-blue-600',
            self::TYPE_REMINDER => 'text-yellow-600',
            self::TYPE_OVERDUE => 'text-red-600',
            self::TYPE_DUE_SOON => 'text-orange-600',
            self::TYPE_COMPLETED => 'text-green-600',
            self::TYPE_COMMENT => 'text-purple-600',
            self::TYPE_STATUS_CHANGE => 'text-indigo-600',
            self::TYPE_PROGRESS_UPDATE => 'text-teal-600',
            default => 'text-gray-600',
        };
    }

    /**
     * Check if notification is urgent.
     */
    public function isUrgent(): bool
    {
        return in_array($this->notification_type, [
            self::TYPE_OVERDUE,
            self::TYPE_DUE_SOON,
        ]);
    }

    /**
     * Scope for notifications to specific staff member.
     */
    public function scopeForStaff($query, string $staffId)
    {
        return $query->where('staff_id', $staffId);
    }

    /**
     * Scope for specific notification type.
     */
    public function scopeByType($query, string $notificationType)
    {
        return $query->where('notification_type', $notificationType);
    }

    /**
     * Scope for unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for read notifications.
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope for sent notifications.
     */
    public function scopeSent($query)
    {
        return $query->whereNotNull('sent_at');
    }

    /**
     * Scope for unsent notifications.
     */
    public function scopeUnsent($query)
    {
        return $query->whereNull('sent_at');
    }

    /**
     * Scope for urgent notifications.
     */
    public function scopeUrgent($query)
    {
        return $query->whereIn('notification_type', [
            self::TYPE_OVERDUE,
            self::TYPE_DUE_SOON,
        ]);
    }

    /**
     * Scope for recent notifications.
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Create a task assignment notification.
     */
    public static function createAssignmentNotification(
        string $assignmentId,
        string $staffId,
        string $taskTitle,
        ?string $createdBy = null
    ): self {
        return static::create([
            'task_assignment_id' => $assignmentId,
            'staff_id' => $staffId,
            'notification_type' => self::TYPE_ASSIGNMENT,
            'title' => 'New Task Assignment',
            'message' => "You have been assigned to task: {$taskTitle}",
            'created_by' => $createdBy ?? auth()->id(),
        ]);
    }

    /**
     * Create a reminder notification.
     */
    public static function createReminderNotification(
        string $assignmentId,
        string $staffId,
        string $taskTitle,
        string $dueDate,
        ?string $createdBy = null
    ): self {
        return static::create([
            'task_assignment_id' => $assignmentId,
            'staff_id' => $staffId,
            'notification_type' => self::TYPE_REMINDER,
            'title' => 'Task Reminder',
            'message' => "Reminder: Task '{$taskTitle}' is due on {$dueDate}",
            'created_by' => $createdBy ?? auth()->id(),
        ]);
    }

    /**
     * Create an overdue notification.
     */
    public static function createOverdueNotification(
        string $assignmentId,
        string $staffId,
        string $taskTitle,
        ?string $createdBy = null
    ): self {
        return static::create([
            'task_assignment_id' => $assignmentId,
            'staff_id' => $staffId,
            'notification_type' => self::TYPE_OVERDUE,
            'title' => 'Overdue Task',
            'message' => "Task '{$taskTitle}' is now overdue. Please complete it as soon as possible.",
            'created_by' => $createdBy ?? auth()->id(),
        ]);
    }

    /**
     * Create a completion notification.
     */
    public static function createCompletionNotification(
        string $assignmentId,
        string $staffId,
        string $taskTitle,
        string $completedBy,
        ?string $createdBy = null
    ): self {
        return static::create([
            'task_assignment_id' => $assignmentId,
            'staff_id' => $staffId,
            'notification_type' => self::TYPE_COMPLETED,
            'title' => 'Task Completed',
            'message' => "Task '{$taskTitle}' has been completed by {$completedBy}",
            'created_by' => $createdBy ?? auth()->id(),
        ]);
    }

    /**
     * Create a comment notification.
     */
    public static function createCommentNotification(
        string $assignmentId,
        string $staffId,
        string $taskTitle,
        string $commenterName,
        ?string $createdBy = null
    ): self {
        return static::create([
            'task_assignment_id' => $assignmentId,
            'staff_id' => $staffId,
            'notification_type' => self::TYPE_COMMENT,
            'title' => 'New Comment',
            'message' => "{$commenterName} commented on task '{$taskTitle}'",
            'created_by' => $createdBy ?? auth()->id(),
        ]);
    }

    /**
     * Bulk mark notifications as read.
     */
    public static function markAllAsRead(string $staffId, ?array $notificationIds = null): int
    {
        $query = static::where('staff_id', $staffId)->where('is_read', false);

        if ($notificationIds) {
            $query->whereIn('id', $notificationIds);
        }

        return $query->update([
            'is_read' => true,
            'read_at' => now(),
            'updated_by' => auth()->id(),
        ]);
    }
}
