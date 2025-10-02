<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Staff extends Authenticatable
{
    use HasUlid, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'password',
        'staff_type_id',
        'email',
        'phone',
        'hire_date',
        'status',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'hire_date' => 'date',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'id';
    }

    /**
     * Get the staff type that owns the staff member.
     */
    public function staffType(): BelongsTo
    {
        return $this->belongsTo(StaffType::class);
    }

    /**
     * Get the staff profile.
     */
    public function profile(): HasOne
    {
        return $this->hasOne(StaffProfile::class);
    }

    /**
     * Get the staff attendance records.
     */
    public function attendance(): HasMany
    {
        return $this->hasMany(StaffAttendance::class);
    }

    /**
     * Get the staff payroll records.
     */
    public function payrollRecords(): HasMany
    {
        return $this->hasMany(StaffPayrollRecord::class);
    }

    /**
     * Get the staff task assignments.
     */
    public function taskAssignments(): HasMany
    {
        return $this->hasMany(StaffTaskAssignment::class);
    }

    /**
     * Get the staff performance reviews.
     */
    public function performanceReviews(): HasMany
    {
        return $this->hasMany(StaffPerformanceReview::class);
    }

    /**
     * Get the staff shift assignments.
     */
    public function shiftAssignments(): HasMany
    {
        return $this->hasMany(StaffShiftAssignment::class);
    }

    /**
     * Get the staff performance goals.
     */
    public function performanceGoals(): HasMany
    {
        return $this->hasMany(StaffPerformanceGoal::class);
    }

    /**
     * Get the staff performance metrics.
     */
    public function performanceMetrics(): HasMany
    {
        return $this->hasMany(StaffPerformanceMetric::class);
    }

    /**
     * Get the performance review acknowledgements made by this staff member.
     */
    public function reviewAcknowledgements(): HasMany
    {
        return $this->hasMany(StaffPerformanceReviewAcknowledgement::class, 'acknowledged_by');
    }

    /**
     * Get the staff member's full name.
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name.' '.$this->last_name;
    }

    /**
     * Get the staff member's display name with staff type.
     */
    public function getDisplayNameWithTypeAttribute(): string
    {
        return $this->full_name.' ('.($this->staffType?->display_name ?? 'No Type').')';
    }

    /**
     * Check if staff member is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if staff member has specific staff type.
     */
    public function hasStaffType(string $typeName): bool
    {
        return $this->staffType?->name === $typeName;
    }

    /**
     * Update last login information.
     */
    public function updateLastLogin(?string $ipAddress = null): void
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $ipAddress ?? request()->ip(),
        ]);
    }

    /**
     * Get years of service.
     */
    public function getYearsOfServiceAttribute(): ?float
    {
        if (! $this->hire_date) {
            return null;
        }

        return $this->hire_date->diffInYears(now(), true);
    }

    /**
     * Scope for active staff only.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for staff with specific type.
     */
    public function scopeWithStaffType($query, string $typeName)
    {
        return $query->whereHas('staffType', function ($q) use ($typeName) {
            $q->where('name', $typeName);
        });
    }

    /**
     * Scope for staff hired in specific year.
     */
    public function scopeHiredInYear($query, int $year)
    {
        return $query->whereYear('hire_date', $year);
    }

    /**
     * Scope for search by name or username.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('username', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        });
    }

    /**
     * Get all time entries logged by this staff member.
     */
    public function timeEntries(): HasMany
    {
        return $this->hasMany(StaffTaskTimeEntry::class, 'staff_id');
    }

    /**
     * Get active time entries for this staff member.
     */
    public function activeTimeEntries(): HasMany
    {
        return $this->timeEntries()->whereNull('end_time');
    }

    /**
     * Get all notifications for this staff member.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(StaffTaskNotification::class, 'staff_id');
    }

    /**
     * Get unread notifications for this staff member.
     */
    public function unreadNotifications(): HasMany
    {
        return $this->notifications()->where('is_read', false);
    }

    /**
     * Get urgent notifications for this staff member.
     */
    public function urgentNotifications(): HasMany
    {
        return $this->notifications()->whereIn('notification_type', [
            StaffTaskNotification::TYPE_OVERDUE,
            StaffTaskNotification::TYPE_DUE_SOON,
        ]);
    }

    /**
     * Get total time logged today.
     */
    public function getTotalTimeToday(): float
    {
        return $this->timeEntries()
            ->whereDate('start_time', today())
            ->whereNotNull('end_time')
            ->sum('duration_minutes') / 60; // Convert to hours
    }

    /**
     * Get total time logged this week.
     */
    public function getTotalTimeThisWeek(): float
    {
        return $this->timeEntries()
            ->whereBetween('start_time', [now()->startOfWeek(), now()->endOfWeek()])
            ->whereNotNull('end_time')
            ->sum('duration_minutes') / 60; // Convert to hours
    }

    /**
     * Check if staff member has any active time entries.
     */
    public function hasActiveTimeEntry(): bool
    {
        return $this->activeTimeEntries()->exists();
    }

    /**
     * Get unread notification count.
     */
    public function getUnreadNotificationCount(): int
    {
        return $this->unreadNotifications()->count();
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllNotificationsAsRead(): int
    {
        return StaffTaskNotification::markAllAsRead($this->id);
    }
}
