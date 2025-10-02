<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Staff;
use App\Models\StaffAttendance;
use Illuminate\Auth\Access\Response;

class StaffAttendancePolicy
{
    /**
     * Determine whether the user can view any attendance records.
     * Admins and managers can view all, staff can view only their own.
     */
    public function viewAny(Staff $staff): bool
    {
        // All authenticated staff can view (filtering happens in controller)
        return true;
    }

    /**
     * Determine whether the user can view the attendance record.
     * Staff can view their own, managers/admins can view all.
     */
    public function view(Staff $staff, StaffAttendance $staffAttendance): bool
    {
        // Check if admin or manager
        if ($this->isAdminOrManager($staff)) {
            return true;
        }

        // Staff can view their own attendance
        return $staff->id === $staffAttendance->staff_id;
    }

    /**
     * Determine whether the user can create attendance records.
     * All staff can create (clock in), admins/managers can create for others.
     */
    public function create(Staff $staff): bool
    {
        // All active staff can create attendance records
        return $staff->status === 'active';
    }

    /**
     * Determine whether the user can update the attendance record.
     * Staff can update their own (clock out), managers/admins can override.
     */
    public function update(Staff $staff, StaffAttendance $staffAttendance): bool
    {
        // Admins and managers can update any attendance
        if ($this->isAdminOrManager($staff)) {
            return true;
        }

        // Staff can update their own attendance (for clock out)
        return $staff->id === $staffAttendance->staff_id;
    }

    /**
     * Determine whether the user can delete the attendance record.
     * Only admins and managers can delete attendance records.
     */
    public function delete(Staff $staff, StaffAttendance $staffAttendance): bool
    {
        // Only admins and managers can delete
        return $this->isAdminOrManager($staff);
    }

    /**
     * Determine whether the user can restore the attendance record.
     */
    public function restore(Staff $staff, StaffAttendance $staffAttendance): bool
    {
        return $this->isAdminOrManager($staff);
    }

    /**
     * Determine whether the user can permanently delete the attendance record.
     */
    public function forceDelete(Staff $staff, StaffAttendance $staffAttendance): bool
    {
        return $this->isAdminOrManager($staff);
    }

    /**
     * Check if staff is admin or manager.
     */
    private function isAdminOrManager(Staff $staff): bool
    {
        $staffType = $staff->staffType;
        
        if (!$staffType) {
            return false;
        }

        return in_array($staffType->name, ['Administrator', 'Manager', 'System Admin', 'system_admin'], true) 
            || in_array($staffType->slug, ['administrator', 'manager', 'system_admin'], true);
    }
}
