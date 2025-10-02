<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskReminder extends Model
{
    use HasUlid;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'staff_task_assignment_id',
        'reminder_type',
        'scheduled_for',
        'sent_at',
        'status',
        'message',
        'delivery_methods',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'scheduled_for' => 'datetime',
        'sent_at' => 'datetime',
        'delivery_methods' => 'array',
    ];

    /**
     * Get the task assignment this reminder belongs to.
     */
    public function assignment(): BelongsTo
    {
        return $this->belongsTo(StaffTaskAssignment::class, 'staff_task_assignment_id');
    }

    /**
     * Scope to get pending reminders.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get reminders due now.
     */
    public function scopeDueNow($query)
    {
        return $query->where('status', 'pending')
            ->where('scheduled_for', '<=', now());
    }

    /**
     * Mark reminder as sent.
     */
    public function markAsSent(): void
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    /**
     * Mark reminder as failed.
     */
    public function markAsFailed(): void
    {
        $this->update(['status' => 'failed']);
    }
}
