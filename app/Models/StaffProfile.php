<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffProfile extends Model
{
    use HasUlid, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'staff_id',
        'address',
        'emergency_contacts',
        'date_of_birth',
        'photo_url',
        'hourly_rate',
        'employee_id',
        'notes',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'emergency_contacts' => 'array',
        'date_of_birth' => 'date',
        'hourly_rate' => 'decimal:2',
    ];

    /**
     * Get the staff member that owns this profile.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the staff member who created this profile.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Get the staff member who last updated this profile.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'updated_by');
    }

    /**
     * Generate the next employee ID.
     */
    public static function generateEmployeeId(): string
    {
        $lastProfile = static::whereNotNull('employee_id')
            ->orderBy('employee_id', 'desc')
            ->first();

        if (!$lastProfile) {
            return 'EMP-0001';
        }

        $lastNumber = (int) substr($lastProfile->employee_id, 4);
        $nextNumber = $lastNumber + 1;

        return 'EMP-' . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (StaffProfile $profile) {
            if (empty($profile->employee_id)) {
                $profile->employee_id = static::generateEmployeeId();
            }
        });
    }
}
