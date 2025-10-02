<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffShiftPattern extends Model
{
    use HasUlid, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'staff_id',
        'shift_id',
        'day_of_week',
        'frequency',
        'effective_from',
        'effective_until',
        'is_active',
        'excluded_dates',
        'priority',
        'pattern_name',
        'notes',
        'auto_generate',
        'generate_days_ahead',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'day_of_week' => 'integer',
        'effective_from' => 'date',
        'effective_until' => 'date',
        'is_active' => 'boolean',
        'excluded_dates' => 'array',
        'priority' => 'integer',
        'auto_generate' => 'boolean',
        'generate_days_ahead' => 'integer',
    ];

    /**
     * Get the staff member for this pattern.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the shift for this pattern.
     */
    public function shift(): BelongsTo
    {
        return $this->belongsTo(StaffShift::class, 'shift_id');
    }

    /**
     * Get the staff member who created this pattern.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Get the staff member who last updated this pattern.
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'updated_by');
    }

    /**
     * Check if pattern is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Check if pattern is effective for a given date.
     */
    public function isEffectiveForDate(\Carbon\Carbon $date): bool
    {
        // Check if date is within effective range
        if ($date->lt($this->effective_from)) {
            return false;
        }

        if ($this->effective_until && $date->gt($this->effective_until)) {
            return false;
        }

        // Check if date is excluded
        if (in_array($date->format('Y-m-d'), $this->excluded_dates ?? [])) {
            return false;
        }

        // Check if it's the right day of week
        if ($date->dayOfWeek !== $this->day_of_week) {
            return false;
        }

        return true;
    }

    /**
     * Check if pattern should generate assignments for a given date.
     */
    public function shouldGenerateForDate(\Carbon\Carbon $date): bool
    {
        if (! $this->auto_generate || ! $this->isActive()) {
            return false;
        }

        return $this->isEffectiveForDate($date);
    }

    /**
     * Get the next occurrence of this pattern.
     */
    public function getNextOccurrence(?\Carbon\Carbon $fromDate = null): ?\Carbon\Carbon
    {
        $fromDate = $fromDate ?? now();

        // Find next occurrence of the day of week
        $nextDate = $fromDate->copy();

        while ($nextDate->dayOfWeek !== $this->day_of_week) {
            $nextDate->addDay();
        }

        // Check if it's within effective range and not excluded
        while (! $this->isEffectiveForDate($nextDate)) {
            $nextDate->addWeek();

            // Prevent infinite loop
            if ($this->effective_until && $nextDate->gt($this->effective_until)) {
                return null;
            }
        }

        return $nextDate;
    }

    /**
     * Scope for active patterns.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for patterns by day of week.
     */
    public function scopeByDayOfWeek($query, int $dayOfWeek)
    {
        return $query->where('day_of_week', $dayOfWeek);
    }

    /**
     * Scope for patterns that auto-generate.
     */
    public function scopeAutoGenerate($query)
    {
        return $query->where('auto_generate', true);
    }

    /**
     * Scope for patterns effective on a given date.
     */
    public function scopeEffectiveOn($query, \Carbon\Carbon $date)
    {
        return $query->where('effective_from', '<=', $date->format('Y-m-d'))
            ->where(function ($q) use ($date) {
                $q->whereNull('effective_until')
                    ->orWhere('effective_until', '>=', $date->format('Y-m-d'));
            });
    }
}
