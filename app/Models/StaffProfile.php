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
     * This method ensures uniqueness by checking all existing IDs and finding the highest number.
     */
    public static function generateEmployeeId(): string
    {
        // Get all employee IDs that match the EMP-XXXX pattern
        $allProfiles = static::whereNotNull('employee_id')
            ->where('employee_id', 'like', 'EMP-%')
            ->pluck('employee_id');

        // If no profiles exist, start from 0001
        if ($allProfiles->isEmpty()) {
            return 'EMP-0001';
        }

        // Extract all numeric parts and find the maximum
        $maxNumber = 0;
        foreach ($allProfiles as $employeeId) {
            // Extract the numeric part after 'EMP-'
            $numericPart = substr($employeeId, 4);
            $number = (int) $numericPart;
            
            if ($number > $maxNumber) {
                $maxNumber = $number;
            }
        }

        // Generate the next number
        $nextNumber = $maxNumber + 1;

        // Format as EMP-XXXX (4 digits, zero-padded)
        return 'EMP-'.str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (StaffProfile $profile) {
            if (empty($profile->employee_id)) {
                // Try to generate a unique employee ID with retry logic
                $maxAttempts = 10;
                $attempts = 0;
                
                while ($attempts < $maxAttempts) {
                    $employeeId = static::generateEmployeeId();
                    
                    // Check if this ID already exists
                    $exists = static::where('employee_id', $employeeId)
                        ->whereNull('deleted_at')
                        ->exists();
                    
                    if (!$exists) {
                        $profile->employee_id = $employeeId;
                        break;
                    }
                    
                    $attempts++;
                    
                    // If we've tried too many times, throw an exception
                    if ($attempts >= $maxAttempts) {
                        throw new \RuntimeException('Unable to generate unique employee ID after ' . $maxAttempts . ' attempts');
                    }
                }
            }
        });
    }
}
