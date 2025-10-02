<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class StaffTaskAttachment extends Model
{
    use HasUlid, SoftDeletes;

    /**
     * Indicates if the model's ID is auto-incrementing.
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
        'task_assignment_id',
        'staff_id',
        'file_name',
        'file_path',
        'file_size',
        'mime_type',
        'description',
        'storage_disk',
        'is_public',
        'downloaded_at',
        'download_count',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_public' => 'boolean',
        'downloaded_at' => 'datetime',
        'download_count' => 'integer',
        'file_size' => 'integer',
    ];

    /**
     * Get the task assignment this attachment belongs to.
     */
    public function taskAssignment(): BelongsTo
    {
        return $this->belongsTo(StaffTaskAssignment::class, 'task_assignment_id');
    }

    /**
     * Get the staff member who uploaded this attachment.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    /**
     * Get the staff member who created this attachment.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Get the staff member who last updated this attachment.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'updated_by');
    }

    /**
     * Check if file exists in storage.
     */
    public function fileExists(): bool
    {
        return Storage::disk($this->storage_disk)->exists($this->file_path);
    }

    /**
     * Get file URL for download.
     */
    public function getFileUrl(): string
    {
        if ($this->is_public) {
            return Storage::disk($this->storage_disk)->url($this->file_path);
        }

        // For private files, return a route that handles authentication
        return route('staff.tasks.attachments.download', $this->id);
    }

    /**
     * Get file size in human readable format.
     */
    public function getHumanReadableSize(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2).' '.$units[$i];
    }

    /**
     * Get file extension.
     */
    public function getFileExtension(): string
    {
        return strtolower(pathinfo($this->file_name, PATHINFO_EXTENSION));
    }

    /**
     * Check if file is an image.
     */
    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    /**
     * Check if file is a document.
     */
    public function isDocument(): bool
    {
        $documentMimes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'text/plain',
            'text/csv',
        ];

        return in_array($this->mime_type, $documentMimes);
    }

    /**
     * Get file type icon class.
     */
    public function getFileTypeIcon(): string
    {
        if ($this->isImage()) {
            return 'fas fa-image';
        }

        if ($this->isDocument()) {
            return match ($this->getFileExtension()) {
                'pdf' => 'fas fa-file-pdf',
                'doc', 'docx' => 'fas fa-file-word',
                'xls', 'xlsx' => 'fas fa-file-excel',
                'ppt', 'pptx' => 'fas fa-file-powerpoint',
                'txt' => 'fas fa-file-alt',
                'csv' => 'fas fa-file-csv',
                default => 'fas fa-file',
            };
        }

        return match ($this->getFileExtension()) {
            'zip', 'rar', '7z' => 'fas fa-file-archive',
            'mp3', 'wav', 'ogg' => 'fas fa-file-audio',
            'mp4', 'avi', 'mov' => 'fas fa-file-video',
            default => 'fas fa-file',
        };
    }

    /**
     * Record a download.
     */
    public function recordDownload(): void
    {
        $this->increment('download_count');
        $this->update(['downloaded_at' => now()]);
    }

    /**
     * Scope for attachments on specific task assignment.
     */
    public function scopeForAssignment($query, string $assignmentId)
    {
        return $query->where('task_assignment_id', $assignmentId);
    }

    /**
     * Scope for attachments by specific staff member.
     */
    public function scopeByStaff($query, string $staffId)
    {
        return $query->where('staff_id', $staffId);
    }

    /**
     * Scope for specific file type.
     */
    public function scopeByMimeType($query, string $mimeType)
    {
        return $query->where('mime_type', $mimeType);
    }

    /**
     * Scope for images only.
     */
    public function scopeImages($query)
    {
        return $query->where('mime_type', 'like', 'image/%');
    }

    /**
     * Scope for documents only.
     */
    public function scopeDocuments($query)
    {
        return $query->whereIn('mime_type', [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/plain',
        ]);
    }

    /**
     * Scope for public attachments.
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope for private attachments.
     */
    public function scopePrivate($query)
    {
        return $query->where('is_public', false);
    }

    /**
     * Scope for recent attachments.
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Delete file from storage when model is deleted.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::deleted(function ($attachment) {
            // Only delete file if it's a soft delete (not force delete)
            if ($attachment->trashed() && $attachment->fileExists()) {
                Storage::disk($attachment->storage_disk)->delete($attachment->file_path);
            }
        });

        static::forceDeleted(function ($attachment) {
            // Always delete file on force delete
            if ($attachment->fileExists()) {
                Storage::disk($attachment->storage_disk)->delete($attachment->file_path);
            }
        });
    }

    /**
     * Get thumbnail URL for images.
     */
    public function getThumbnailUrl(): ?string
    {
        if (! $this->isImage()) {
            return null;
        }

        // This would typically generate or return a cached thumbnail
        // For now, return the original image URL
        return $this->getFileUrl();
    }

    /**
     * Check if file can be previewed in browser.
     */
    public function canPreview(): bool
    {
        $previewableMimes = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'application/pdf',
            'text/plain',
        ];

        return in_array($this->mime_type, $previewableMimes);
    }
}
