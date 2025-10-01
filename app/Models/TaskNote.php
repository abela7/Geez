<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskNote extends Model
{
    use HasUlid;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'staff_task_id',
        'staff_task_assignment_id',
        'staff_id',
        'note_type',
        'content',
        'is_private',
        'is_important',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_private' => 'boolean',
        'is_important' => 'boolean',
    ];

    /**
     * Get the task this note belongs to.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(StaffTask::class, 'staff_task_id');
    }

    /**
     * Get the task assignment this note belongs to.
     */
    public function assignment(): BelongsTo
    {
        return $this->belongsTo(StaffTaskAssignment::class, 'staff_task_assignment_id');
    }

    /**
     * Get the staff member who created this note.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    /**
     * Scope to get only important notes.
     */
    public function scopeImportant($query)
    {
        return $query->where('is_important', true);
    }

    /**
     * Scope to get notes by type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('note_type', $type);
    }

    /**
     * Scope to get public notes (not private).
     */
    public function scopePublic($query)
    {
        return $query->where('is_private', false);
    }
}