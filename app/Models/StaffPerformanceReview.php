<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffPerformanceReview extends Model
{
    use HasUlid, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'staff_id',
        'review_period_start',
        'review_period_end',
        'overall_rating',
        'punctuality_rating',
        'quality_rating',
        'teamwork_rating',
        'customer_service_rating',
        'strengths',
        'areas_for_improvement',
        'goals',
        'reviewer_id',
        'review_date',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'review_period_start' => 'date',
        'review_period_end' => 'date',
        'review_date' => 'date',
        'overall_rating' => 'decimal:2',
        'punctuality_rating' => 'decimal:2',
        'quality_rating' => 'decimal:2',
        'teamwork_rating' => 'decimal:2',
        'customer_service_rating' => 'decimal:2',
    ];

    /**
     * Get the staff member being reviewed.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the staff member who conducted the review.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'reviewer_id');
    }
    
    /**
     * Get the acknowledgements for this review.
     */
    public function acknowledgements(): HasMany
    {
        return $this->hasMany(StaffPerformanceReviewAcknowledgement::class, 'performance_review_id');
    }

    /**
     * Calculate average rating from all individual ratings.
     */
    public function getAverageRating(): float
    {
        $ratings = array_filter([
            $this->punctuality_rating,
            $this->quality_rating,
            $this->teamwork_rating,
            $this->customer_service_rating,
        ]);

        if (empty($ratings)) {
            return (float) $this->overall_rating;
        }

        return round(array_sum($ratings) / count($ratings), 2);
    }

    /**
     * Get rating as percentage (for display).
     */
    public function getRatingPercentage(): int
    {
        return (int) (($this->overall_rating / 5) * 100);
    }

    /**
     * Get performance level based on overall rating.
     */
    public function getPerformanceLevel(): string
    {
        return match (true) {
            $this->overall_rating >= 4.5 => 'Excellent',
            $this->overall_rating >= 3.5 => 'Good',
            $this->overall_rating >= 2.5 => 'Satisfactory',
            $this->overall_rating >= 1.5 => 'Needs Improvement',
            default => 'Unsatisfactory'
        };
    }

    /**
     * Check if review is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if review is acknowledged by staff.
     */
    public function isAcknowledged(): bool
    {
        return $this->status === 'acknowledged';
    }

    /**
     * Get review period duration in days.
     */
    public function getReviewPeriodDays(): int
    {
        return $this->review_period_start->diffInDays($this->review_period_end);
    }

    /**
     * Scope for reviews in specific year.
     */
    public function scopeForYear($query, int $year)
    {
        return $query->whereYear('review_date', $year);
    }

    /**
     * Scope for specific staff member.
     */
    public function scopeForStaff($query, string $staffId)
    {
        return $query->where('staff_id', $staffId);
    }

    /**
     * Scope for completed reviews.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for reviews by specific reviewer.
     */
    public function scopeByReviewer($query, string $reviewerId)
    {
        return $query->where('reviewer_id', $reviewerId);
    }

    /**
     * Scope for reviews in date range.
     */
    public function scopeBetweenDates($query, string $startDate, string $endDate)
    {
        return $query->whereBetween('review_date', [$startDate, $endDate]);
    }
}