<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffTaskComment extends Model
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
        'task_assignment_id',
        'staff_id',
        'comment',
        'comment_type',
        'is_internal',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_internal' => 'boolean',
    ];

    /**
     * Get the task assignment this comment belongs to.
     */
    public function taskAssignment(): BelongsTo
    {
        return $this->belongsTo(StaffTaskAssignment::class, 'task_assignment_id');
    }

    /**
     * Get the staff member who made this comment.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    /**
     * Get the staff member who created this comment.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Get the staff member who last updated this comment.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'updated_by');
    }

    /**
     * Check if comment is visible to the assignee.
     */
    public function isVisibleToAssignee(): bool
    {
        return ! $this->is_internal;
    }

    /**
     * Check if comment is a system-generated update.
     */
    public function isSystemUpdate(): bool
    {
        return in_array($this->comment_type, ['status_change', 'update']);
    }

    /**
     * Get comment type display name.
     */
    public function getCommentTypeDisplayName(): string
    {
        return match ($this->comment_type) {
            'comment' => 'Comment',
            'update' => 'Update',
            'status_change' => 'Status Change',
            'attachment' => 'Attachment',
            default => 'Unknown',
        };
    }

    /**
     * Get comment type icon.
     */
    public function getCommentTypeIcon(): string
    {
        return match ($this->comment_type) {
            'comment' => 'fas fa-comment',
            'update' => 'fas fa-edit',
            'status_change' => 'fas fa-exchange-alt',
            'attachment' => 'fas fa-paperclip',
            default => 'fas fa-comment',
        };
    }

    /**
     * Scope for comments on specific task assignment.
     */
    public function scopeForAssignment($query, string $assignmentId)
    {
        return $query->where('task_assignment_id', $assignmentId);
    }

    /**
     * Scope for comments by specific staff member.
     */
    public function scopeByStaff($query, string $staffId)
    {
        return $query->where('staff_id', $staffId);
    }

    /**
     * Scope for specific comment type.
     */
    public function scopeByType($query, string $commentType)
    {
        return $query->where('comment_type', $commentType);
    }

    /**
     * Scope for public comments (visible to assignee).
     */
    public function scopePublic($query)
    {
        return $query->where('is_internal', false);
    }

    /**
     * Scope for internal comments (not visible to assignee).
     */
    public function scopeInternal($query)
    {
        return $query->where('is_internal', true);
    }

    /**
     * Scope for recent comments.
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Create a status change comment.
     */
    public static function createStatusChange(
        string $assignmentId,
        string $oldStatus,
        string $newStatus,
        ?string $staffId = null
    ): self {
        return static::create([
            'task_assignment_id' => $assignmentId,
            'staff_id' => $staffId ?? auth()->id(),
            'comment' => "Status changed from '{$oldStatus}' to '{$newStatus}'",
            'comment_type' => 'status_change',
            'is_internal' => false,
            'created_by' => auth()->id(),
        ]);
    }

    /**
     * Create a progress update comment.
     */
    public static function createProgressUpdate(
        string $assignmentId,
        int $oldProgress,
        int $newProgress,
        ?string $staffId = null
    ): self {
        return static::create([
            'task_assignment_id' => $assignmentId,
            'staff_id' => $staffId ?? auth()->id(),
            'comment' => "Progress updated from {$oldProgress}% to {$newProgress}%",
            'comment_type' => 'update',
            'is_internal' => false,
            'created_by' => auth()->id(),
        ]);
    }

    /**
     * Create an attachment comment.
     */
    public static function createAttachmentComment(
        string $assignmentId,
        string $fileName,
        ?string $staffId = null
    ): self {
        return static::create([
            'task_assignment_id' => $assignmentId,
            'staff_id' => $staffId ?? auth()->id(),
            'comment' => "Attached file: {$fileName}",
            'comment_type' => 'attachment',
            'is_internal' => false,
            'created_by' => auth()->id(),
        ]);
    }

    /**
     * Get formatted comment for display.
     */
    public function getFormattedComment(): string
    {
        $comment = $this->comment;

        // Add timestamp for system updates
        if ($this->isSystemUpdate()) {
            $comment .= ' at '.$this->created_at->format('M j, Y H:i');
        }

        return $comment;
    }
}
