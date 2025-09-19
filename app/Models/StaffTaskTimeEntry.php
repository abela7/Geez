<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class StaffTaskTimeEntry extends Model
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
    protected $table = 'staff_task_time_entries';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'task_assignment_id',
        'staff_id',
        'start_time',
        'end_time',
        'duration_minutes',
        'description',
        'is_billable',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_billable' => 'boolean',
        'duration_minutes' => 'integer',
    ];

    /**
     * Get the task assignment this time entry belongs to.
     */
    public function assignment(): BelongsTo
    {
        return $this->belongsTo(StaffTaskAssignment::class, 'task_assignment_id');
    }

    /**
     * Get the staff member who logged this time.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    /**
     * Get the staff member who created this time entry.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Get the staff member who last updated this time entry.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'updated_by');
    }

    /**
     * Ensure duration is calculated and cached when end_time is present.
     */
    public function finalizeDuration(): void
    {
        if ($this->end_time && !$this->duration_minutes) {
            $this->duration_minutes = $this->start_time->diffInMinutes($this->end_time);
            $this->save();
        }
    }

    /**
     * Calculate duration in minutes (live calculation).
     */
    public function calculateDuration(): ?int
    {
        if (!$this->end_time) {
            return null;
        }
        
        return $this->start_time->diffInMinutes($this->end_time);
    }

    /**
     * Get duration in hours (decimal format).
     */
    public function getDurationInHours(): ?float
    {
        $minutes = $this->duration_minutes ?? $this->calculateDuration();
        
        return $minutes ? round($minutes / 60, 2) : null;
    }

    /**
     * Get formatted duration string (e.g., "2h 30m").
     */
    public function getFormattedDuration(): string
    {
        $minutes = $this->duration_minutes ?? $this->calculateDuration();
        
        if (!$minutes) {
            return 'In progress';
        }
        
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        
        if ($hours > 0) {
            return $remainingMinutes > 0 ? "{$hours}h {$remainingMinutes}m" : "{$hours}h";
        }
        
        return "{$remainingMinutes}m";
    }

    /**
     * Check if time entry is currently active (no end time).
     */
    public function isActive(): bool
    {
        return $this->end_time === null;
    }

    /**
     * Stop the time entry and calculate duration.
     */
    public function stop(?string $description = null): void
    {
        if ($this->isActive()) {
            $this->end_time = now();
            
            if ($description) {
                $this->description = $description;
            }
            
            $this->finalizeDuration();
            $this->updated_by = auth()->id();
            $this->save();
        }
    }

    /**
     * Resume a stopped time entry (create new entry).
     */
    public function resume(?string $description = null): self
    {
        return static::create([
            'task_assignment_id' => $this->task_assignment_id,
            'staff_id' => $this->staff_id,
            'start_time' => now(),
            'description' => $description ?? $this->description,
            'is_billable' => $this->is_billable,
            'created_by' => auth()->id(),
        ]);
    }

    /**
     * Scope for time entries on specific task assignment.
     */
    public function scopeForAssignment($query, string $assignmentId)
    {
        return $query->where('task_assignment_id', $assignmentId);
    }

    /**
     * Scope for time entries by specific staff member.
     */
    public function scopeByStaff($query, string $staffId)
    {
        return $query->where('staff_id', $staffId);
    }

    /**
     * Scope for billable time entries only.
     */
    public function scopeBillable($query)
    {
        return $query->where('is_billable', true);
    }

    /**
     * Scope for non-billable time entries only.
     */
    public function scopeNonBillable($query)
    {
        return $query->where('is_billable', false);
    }

    /**
     * Scope for active time entries (no end time).
     */
    public function scopeActive($query)
    {
        return $query->whereNull('end_time');
    }

    /**
     * Scope for completed time entries (has end time).
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('end_time');
    }

    /**
     * Scope for time entries within date range.
     */
    public function scopeBetweenDates($query, Carbon $startDate, Carbon $endDate)
    {
        return $query->whereBetween('start_time', [$startDate, $endDate]);
    }

    /**
     * Scope for today's time entries.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('start_time', today());
    }

    /**
     * Scope for this week's time entries.
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('start_time', [
            now()->startOfWeek(),
            now()->endOfWeek(),
        ]);
    }

    /**
     * Create a new time entry and start tracking.
     */
    public static function startTracking(
        string $assignmentId,
        ?string $staffId = null,
        ?string $description = null,
        bool $isBillable = true
    ): self {
        return static::create([
            'task_assignment_id' => $assignmentId,
            'staff_id' => $staffId ?? auth()->id(),
            'start_time' => now(),
            'description' => $description,
            'is_billable' => $isBillable,
            'created_by' => auth()->id(),
        ]);
    }

    /**
     * Get total hours for a collection of time entries.
     */
    public static function getTotalHours($timeEntries): float
    {
        return $timeEntries->sum(function ($entry) {
            return $entry->getDurationInHours() ?? 0;
        });
    }

    /**
     * Boot method to handle model events.
     */
    protected static function boot(): void
    {
        parent::boot();
        
        // Automatically calculate duration when saving if end_time is set
        static::saving(function ($timeEntry) {
            if ($timeEntry->end_time && !$timeEntry->duration_minutes) {
                $timeEntry->duration_minutes = $timeEntry->calculateDuration();
            }
        });
    }
}