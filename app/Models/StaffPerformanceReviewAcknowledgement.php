<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffPerformanceReviewAcknowledgement extends Model
{
    use HasUlid, SoftDeletes;

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = true;

    /**
     * Indicates if the IDs are auto-incrementing.
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
        'performance_review_id',
        'acknowledged_by',
        'acknowledged_at',
        'notes',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'acknowledged_at' => 'datetime',
    ];

    /**
     * Get the performance review this acknowledgement belongs to.
     */
    public function performanceReview(): BelongsTo
    {
        return $this->belongsTo(StaffPerformanceReview::class);
    }

    /**
     * Get the staff member who acknowledged the review.
     */
    public function acknowledger(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'acknowledged_by');
    }

    /**
     * Get the staff member who created this acknowledgement.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Get the staff member who last updated this acknowledgement.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'updated_by');
    }

    /**
     * Scope for acknowledgements by review.
     */
    public function scopeForReview($query, string $reviewId)
    {
        return $query->where('performance_review_id', $reviewId);
    }

    /**
     * Scope for acknowledgements by staff member.
     */
    public function scopeByStaff($query, string $staffId)
    {
        return $query->where('acknowledged_by', $staffId);
    }

    /**
     * Scope for recent acknowledgements.
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('acknowledged_at', '>=', now()->subDays($days));
    }

    /**
     * Check if acknowledgement is recent (within 24 hours).
     */
    public function isRecent(): bool
    {
        return $this->acknowledged_at && $this->acknowledged_at->isAfter(now()->subDay());
    }
}