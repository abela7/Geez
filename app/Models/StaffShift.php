<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffShift extends Model
{
    use HasUlid, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'break_duration',
        'days_of_week',
        'is_active',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'break_duration' => 'integer',
        'days_of_week' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the staff member who created this shift.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Get all assignments for this shift.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(StaffShiftAssignment::class);
    }

    /**
     * Get active assignments for this shift.
     */
    public function activeAssignments(): HasMany
    {
        return $this->assignments()->where('status', '!=', 'cancelled');
    }

    /**
     * Calculate shift duration in hours.
     */
    public function getDurationInHours(): float
    {
        $start = \Carbon\Carbon::createFromFormat('H:i', $this->start_time);
        $end = \Carbon\Carbon::createFromFormat('H:i', $this->end_time);
        
        // Handle overnight shifts
        if ($end->lessThan($start)) {
            $end->addDay();
        }
        
        $totalMinutes = $end->diffInMinutes($start);
        $breakMinutes = $this->break_duration ?? 0;
        
        return round(($totalMinutes - $breakMinutes) / 60, 2);
    }

    /**
     * Check if shift is scheduled for a specific day of week.
     */
    public function isScheduledForDay(int $dayOfWeek): bool
    {
        return in_array($dayOfWeek, $this->days_of_week ?? []);
    }

    /**
     * Scope for active shifts only.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for shifts on specific day of week.
     */
    public function scopeForDayOfWeek($query, int $dayOfWeek)
    {
        return $query->whereJsonContains('days_of_week', $dayOfWeek);
    }
}