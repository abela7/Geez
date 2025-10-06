<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WeeklyRotaTemplate extends Model
{
    use HasUlid, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
        'type',
        'is_active',
        'is_default',
        'usage_count',
        'last_used_at',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'usage_count' => 'integer',
        'last_used_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the staff member who created this template.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Get the staff member who last updated this template.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'updated_by');
    }

    /**
     * Get all assignments for this template.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(WeeklyRotaTemplateAssignment::class, 'template_id');
    }

    /**
     * Get assignments grouped by day of week.
     */
    public function getAssignmentsByDay(): array
    {
        $assignmentsByDay = [];
        
        for ($day = 0; $day < 7; $day++) {
            $assignmentsByDay[$day] = $this->assignments()
                ->where('day_of_week', $day)
                ->with(['staff.staffType', 'staff.profile', 'shift'])
                ->get();
        }
        
        return $assignmentsByDay;
    }

    /**
     * Scope for active templates.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for default template.
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Scope by type.
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Mark this template as used.
     */
    public function markAsUsed(): void
    {
        $this->increment('usage_count');
        $this->update(['last_used_at' => now()]);
    }

    /**
     * Set this as the default template (and unset others).
     */
    public function setAsDefault(): void
    {
        // Unset all other defaults
        static::where('is_default', true)->update(['is_default' => false]);
        
        // Set this as default
        $this->update(['is_default' => true]);
    }

    /**
     * Get total staff assignments in this template.
     */
    public function getTotalAssignments(): int
    {
        return $this->assignments()->count();
    }

    /**
     * Get unique staff count in this template.
     */
    public function getUniqueStaffCount(): int
    {
        return $this->assignments()->distinct('staff_id')->count('staff_id');
    }

    /**
     * Get shifts count in this template.
     */
    public function getShiftsCount(): int
    {
        return $this->assignments()->distinct('staff_shift_id')->count('staff_shift_id');
    }
}