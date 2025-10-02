<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffTimeOffRequest extends Model
{
    use HasUlid, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'staff_id',
        'start_date',
        'end_date',
        'type',
        'status',
        'reason',
        'notes',
        'affects_shifts',
        'replacement_needed',
        'approved_by',
        'approved_at',
        'approval_notes',
        'affected_shifts_count',
        'affected_shift_ids',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'affects_shifts' => 'boolean',
        'replacement_needed' => 'boolean',
        'approved_at' => 'datetime',
        'affected_shift_ids' => 'array',
        'affected_shifts_count' => 'integer',
    ];

    /**
     * Get the staff member who made this request.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the staff member who approved this request.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'approved_by');
    }

    /**
     * Check if request is pending approval.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if request is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if request is denied.
     */
    public function isDenied(): bool
    {
        return $this->status === 'denied';
    }

    /**
     * Get the duration of the time off in days.
     */
    public function getDurationInDays(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    /**
     * Check if request overlaps with a given date range.
     */
    public function overlaps(\Carbon\Carbon $startDate, \Carbon\Carbon $endDate): bool
    {
        return $this->start_date <= $endDate && $this->end_date >= $startDate;
    }

    /**
     * Scope for pending requests.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved requests.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for requests by type.
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for requests in date range.
     */
    public function scopeInDateRange($query, \Carbon\Carbon $startDate, \Carbon\Carbon $endDate)
    {
        return $query->where(function ($q) use ($startDate, $endDate) {
            $q->whereBetween('start_date', [$startDate, $endDate])
              ->orWhereBetween('end_date', [$startDate, $endDate])
              ->orWhere(function ($q2) use ($startDate, $endDate) {
                  $q2->where('start_date', '<=', $startDate)
                     ->where('end_date', '>=', $endDate);
              });
        });
    }
}
