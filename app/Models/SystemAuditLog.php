<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemAuditLog extends Model
{
    use HasUlid;

    /**
     * The table associated with the model.
     */
    protected $table = 'system_audit_log';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'table_name',
        'record_id',
        'action',
        'old_values',
        'new_values',
        'changed_fields',
        'performed_by',
        'performed_at',
        'ip_address',
        'user_agent',
        'request_method',
        'request_url',
        'description',
        'metadata',
        'event_type',
        'severity',
        'requires_review',
        'reviewed_at',
        'reviewed_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'changed_fields' => 'array',
        'performed_at' => 'datetime',
        'metadata' => 'array',
        'requires_review' => 'boolean',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get the staff member who performed this action.
     */
    public function performer(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'performed_by');
    }

    /**
     * Get the staff member who reviewed this log.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'reviewed_by');
    }

    /**
     * Scope for specific table.
     */
    public function scopeForTable($query, string $tableName)
    {
        return $query->where('table_name', $tableName);
    }

    /**
     * Scope for specific record.
     */
    public function scopeForRecord($query, string $tableName, string $recordId)
    {
        return $query->where('table_name', $tableName)
            ->where('record_id', $recordId);
    }

    /**
     * Scope for specific action.
     */
    public function scopeForAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope for specific performer.
     */
    public function scopeByPerformer($query, string $staffId)
    {
        return $query->where('performed_by', $staffId);
    }

    /**
     * Scope for logs requiring review.
     */
    public function scopeRequiresReview($query)
    {
        return $query->where('requires_review', true)
            ->whereNull('reviewed_at');
    }

    /**
     * Scope for specific severity.
     */
    public function scopeBySeverity($query, string $severity)
    {
        return $query->where('severity', $severity);
    }

    /**
     * Scope for high priority logs.
     */
    public function scopeHighPriority($query)
    {
        return $query->whereIn('severity', ['high', 'critical']);
    }

    /**
     * Scope for date range.
     */
    public function scopeDateRange($query, \DateTimeInterface $start, \DateTimeInterface $end)
    {
        return $query->whereBetween('performed_at', [$start, $end]);
    }

    /**
     * Log an action to the audit trail.
     */
    public static function logAction(
        string $tableName,
        string $recordId,
        string $action,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $description = null,
        string $severity = 'low'
    ): self {
        $changedFields = [];
        
        if ($oldValues && $newValues) {
            $changedFields = array_keys(array_diff_assoc($newValues, $oldValues));
        }

        return static::create([
            'table_name' => $tableName,
            'record_id' => $recordId,
            'action' => $action,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'changed_fields' => $changedFields,
            'performed_by' => auth()->id(),
            'performed_at' => now(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'request_method' => request()->method(),
            'request_url' => request()->fullUrl(),
            'description' => $description,
            'severity' => $severity,
        ]);
    }

    /**
     * Mark as reviewed.
     */
    public function markAsReviewed(?string $reviewedBy = null): void
    {
        $this->update([
            'reviewed_at' => now(),
            'reviewed_by' => $reviewedBy ?? auth()->id(),
        ]);
    }

    /**
     * Get a summary of changes.
     */
    public function getChangesSummary(): string
    {
        if (empty($this->changed_fields)) {
            return 'No changes detected';
        }

        return count($this->changed_fields) . ' field(s) changed: ' . implode(', ', $this->changed_fields);
    }
}

