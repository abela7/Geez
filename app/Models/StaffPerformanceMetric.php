<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffPerformanceMetric extends Model
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
        'staff_id',
        'metric_name',
        'metric_value',
        'measurement_period',
        'recorded_date',
        'data_source',
        'notes',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'recorded_date' => 'date',
        'metric_value' => 'decimal:2',
    ];

    /**
     * Get the staff member this metric belongs to.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the staff member who created this metric.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Get the staff member who last updated this metric.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'updated_by');
    }

    /**
     * Scope for metrics by staff member.
     */
    public function scopeForStaff($query, string $staffId)
    {
        return $query->where('staff_id', $staffId);
    }

    /**
     * Scope for metrics by name.
     */
    public function scopeByMetric($query, string $metricName)
    {
        return $query->where('metric_name', $metricName);
    }

    /**
     * Scope for metrics by period.
     */
    public function scopeByPeriod($query, string $period)
    {
        return $query->where('measurement_period', $period);
    }

    /**
     * Scope for metrics by data source.
     */
    public function scopeBySource($query, string $source)
    {
        return $query->where('data_source', $source);
    }

    /**
     * Scope for metrics in date range.
     */
    public function scopeBetweenDates($query, string $startDate, string $endDate)
    {
        return $query->whereBetween('recorded_date', [$startDate, $endDate]);
    }

    /**
     * Scope for recent metrics.
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('recorded_date', '>=', now()->subDays($days));
    }

    /**
     * Get formatted metric value with unit if applicable.
     */
    public function getFormattedValue(): string
    {
        $value = number_format($this->metric_value, 2);
        
        // Add common units based on metric name
        if (str_contains(strtolower($this->metric_name), 'percentage') || 
            str_contains(strtolower($this->metric_name), 'rate')) {
            return $value . '%';
        }
        
        if (str_contains(strtolower($this->metric_name), 'hour')) {
            return $value . 'h';
        }
        
        return $value;
    }
}