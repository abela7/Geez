<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WeeklySchedule extends Model
{
    use HasUlid, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'week_start_date',
        'week_end_date',
        'year',
        'week_number',
        'name',
        'description',
        'template_id',
        'is_template_applied',
        'status',
        'total_shifts',
        'total_staff_assignments',
        'total_scheduled_hours',
        'estimated_labor_cost',
        'published_at',
        'published_by',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'week_start_date' => 'date',
        'week_end_date' => 'date',
        'year' => 'integer',
        'week_number' => 'integer',
        'is_template_applied' => 'boolean',
        'total_shifts' => 'integer',
        'total_staff_assignments' => 'integer',
        'total_scheduled_hours' => 'decimal:2',
        'estimated_labor_cost' => 'decimal:2',
        'published_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the template used for this weekly schedule.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(WeeklyRotaTemplate::class, 'template_id');
    }

    /**
     * Get the staff member who created this schedule.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Get the staff member who last updated this schedule.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'updated_by');
    }

    /**
     * Get the staff member who published this schedule.
     */
    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'published_by');
    }

    /**
     * Get all assignments for this weekly schedule.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(WeeklyScheduleAssignment::class, 'weekly_schedule_id');
    }

    /**
     * Get all staff shift assignments for this week through the linking table.
     */
    public function staffShiftAssignments()
    {
        return $this->hasManyThrough(
            StaffShiftAssignment::class,
            WeeklyScheduleAssignment::class,
            'weekly_schedule_id',
            'id',
            'id',
            'staff_shift_assignment_id'
        );
    }

    /**
     * Create a weekly schedule for a specific week.
     */
    public static function createForWeek(Carbon $date, ?string $templateId = null, ?string $createdBy = null): self
    {
        $weekStart = $date->copy()->startOfWeek(Carbon::MONDAY);
        $weekEnd = $date->copy()->endOfWeek(Carbon::SUNDAY);
        
        return self::create([
            'week_start_date' => $weekStart,
            'week_end_date' => $weekEnd,
            'year' => $weekStart->year,
            'week_number' => $weekStart->weekOfYear,
            'template_id' => $templateId,
            'created_by' => $createdBy,
            'status' => 'draft',
        ]);
    }

    /**
     * Get the weekly schedule for a specific date.
     */
    public static function forDate(Carbon $date): ?self
    {
        $weekStart = $date->copy()->startOfWeek(Carbon::MONDAY);
        
        return self::where('week_start_date', $weekStart)->first();
    }

    /**
     * Get or create a weekly schedule for a specific date.
     */
    public static function getOrCreateForDate(Carbon $date, ?string $createdBy = null): self
    {
        $existing = self::forDate($date);
        
        if ($existing) {
            return $existing;
        }
        
        return self::createForWeek($date, null, $createdBy);
    }

    /**
     * Apply a template to this weekly schedule.
     */
    public function applyTemplate(WeeklyRotaTemplate $template): void
    {
        if ($this->is_template_applied) {
            throw new \Exception('Template has already been applied to this weekly schedule');
        }

        $this->template_id = $template->id;
        $this->is_template_applied = true;
        $this->save();

        // Create staff shift assignments based on template
        foreach ($template->assignments as $templateAssignment) {
            $assignmentDate = $this->week_start_date->copy()->addDays($templateAssignment->day_of_week === 0 ? 6 : $templateAssignment->day_of_week - 1);
            
            // Create the actual staff shift assignment
            $staffShiftAssignment = StaffShiftAssignment::create([
                'staff_shift_id' => $templateAssignment->staff_shift_id,
                'staff_id' => $templateAssignment->staff_id,
                'assigned_date' => $assignmentDate,
                'status' => $templateAssignment->status,
                'notes' => $templateAssignment->notes,
                'assigned_by' => $this->created_by,
            ]);
            
            // Link it to this weekly schedule
            WeeklyScheduleAssignment::create([
                'weekly_schedule_id' => $this->id,
                'staff_shift_assignment_id' => $staffShiftAssignment->id,
                'staff_id' => $templateAssignment->staff_id,
                'staff_shift_id' => $templateAssignment->staff_shift_id,
                'assigned_date' => $assignmentDate,
                'day_of_week' => $templateAssignment->day_of_week,
                'assignment_status' => $templateAssignment->status,
            ]);
        }

        // Update template usage
        $template->increment('usage_count');
        $template->update(['last_used_at' => now()]);
        
        // Recalculate statistics
        $this->recalculateStatistics();
    }

    /**
     * Recalculate weekly statistics.
     */
    public function recalculateStatistics(): void
    {
        $assignments = $this->assignments()->with(['staffShiftAssignment.shift', 'staffShiftAssignment.staff.profile'])->get();
        
        $totalShifts = $assignments->count();
        $totalStaffAssignments = $assignments->count();
        $totalScheduledHours = 0;
        $estimatedLaborCost = 0;
        
        foreach ($assignments as $assignment) {
            $shift = $assignment->staffShiftAssignment->shift;
            $staffProfile = $assignment->staffShiftAssignment->staff->profile;
            
            if ($shift && $staffProfile) {
                // Calculate shift duration in hours
                $startTime = Carbon::parse($shift->start_time);
                $endTime = Carbon::parse($shift->end_time);
                
                // Handle overnight shifts
                if ($endTime->lt($startTime)) {
                    $endTime->addDay();
                }
                
                $shiftHours = $endTime->diffInMinutes($startTime) / 60;
                
                // Subtract break time if applicable
                if ($shift->break_minutes) {
                    $shiftHours -= ($shift->break_minutes / 60);
                }
                
                $totalScheduledHours += $shiftHours;
                
                // Calculate labor cost
                $hourlyRate = $staffProfile->hourly_rate ?? 0;
                $estimatedLaborCost += ($shiftHours * $hourlyRate);
            }
        }
        
        $this->update([
            'total_shifts' => $totalShifts,
            'total_staff_assignments' => $totalStaffAssignments,
            'total_scheduled_hours' => $totalScheduledHours,
            'estimated_labor_cost' => $estimatedLaborCost,
        ]);
    }

    /**
     * Publish this schedule to staff.
     */
    public function publish(?string $publishedBy = null): void
    {
        $this->update([
            'status' => 'published',
            'published_at' => now(),
            'published_by' => $publishedBy,
        ]);
    }

    /**
     * Get the display name for this weekly schedule.
     */
    public function getDisplayName(): string
    {
        if ($this->name) {
            return $this->name;
        }
        
        return __('shifts.weekly_schedule.week_of', [
            'date' => $this->week_start_date->format('M j, Y')
        ]);
    }

    /**
     * Check if this schedule can be edited.
     */
    public function canBeEdited(): bool
    {
        return in_array($this->status, ['draft', 'published']);
    }

    /**
     * Get assignments grouped by day of week.
     */
    public function getAssignmentsByDay(): array
    {
        $assignmentsByDay = [
            1 => [], // Monday
            2 => [], // Tuesday
            3 => [], // Wednesday
            4 => [], // Thursday
            5 => [], // Friday
            6 => [], // Saturday
            0 => [], // Sunday
        ];
        
        $assignments = $this->assignments()
            ->with(['staffShiftAssignment.shift', 'staffShiftAssignment.staff'])
            ->orderBy('day_of_week')
            ->get();
        
        foreach ($assignments as $assignment) {
            $assignmentsByDay[$assignment->day_of_week][] = $assignment;
        }
        
        return $assignmentsByDay;
    }

    /**
     * Scope for current week.
     */
    public function scopeCurrentWeek($query)
    {
        $weekStart = now()->startOfWeek(Carbon::MONDAY);
        return $query->where('week_start_date', $weekStart);
    }

    /**
     * Scope for specific status.
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for date range.
     */
    public function scopeInDateRange($query, Carbon $startDate, Carbon $endDate)
    {
        return $query->where('week_start_date', '>=', $startDate->startOfWeek(Carbon::MONDAY))
                    ->where('week_end_date', '<=', $endDate->endOfWeek(Carbon::SUNDAY));
    }
}
