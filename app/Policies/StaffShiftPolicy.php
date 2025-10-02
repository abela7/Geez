<?php

namespace App\Policies;

use App\Models\Staff;
use App\Models\StaffShift;

class StaffShiftPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Staff $staff): bool
    {
        // All staff can view shift templates (to see available shifts)
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Staff $staff, StaffShift $staffShift): bool
    {
        // All staff can view individual shift details
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Staff $staff): bool
    {
        // Only managers and above can create shift templates
        return $this->isManagerOrAbove($staff);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Staff $staff, StaffShift $staffShift): bool
    {
        // Only managers and above can update shift templates
        return $this->isManagerOrAbove($staff);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Staff $staff, StaffShift $staffShift): bool
    {
        // Only managers and above can delete shift templates
        // But not if there are active assignments
        if (! $this->isManagerOrAbove($staff)) {
            return false;
        }

        // Check if shift has any active assignments
        $hasActiveAssignments = $staffShift->assignments()
            ->where('date', '>=', now()->format('Y-m-d'))
            ->whereIn('status', ['scheduled', 'checked_in', 'active'])
            ->exists();

        return ! $hasActiveAssignments;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Staff $staff, StaffShift $staffShift): bool
    {
        // Only managers and above can restore deleted shifts
        return $this->isManagerOrAbove($staff);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Staff $staff, StaffShift $staffShift): bool
    {
        // Only system admins can permanently delete shifts
        return $this->isSystemAdmin($staff);
    }

    /**
     * Determine whether the user can assign staff to shifts.
     */
    public function assign(Staff $staff): bool
    {
        // Only managers and above can assign staff to shifts
        return $this->isManagerOrAbove($staff);
    }

    /**
     * Determine whether the user can manage shift coverage.
     */
    public function manageCoverage(Staff $staff): bool
    {
        // Only managers and above can manage shift coverage
        return $this->isManagerOrAbove($staff);
    }

    /**
     * Determine whether the user can view shift analytics.
     */
    public function viewAnalytics(Staff $staff): bool
    {
        // Only managers and above can view shift analytics
        return $this->isManagerOrAbove($staff);
    }

    /**
     * Check if staff member is manager or above.
     */
    private function isManagerOrAbove(Staff $staff): bool
    {
        return in_array($staff->staff_type->name, [
            'System Admin',
            'Administrator',
            'Management',
            'Chief',
        ]);
    }

    /**
     * Check if staff member is system admin.
     */
    private function isSystemAdmin(Staff $staff): bool
    {
        return $staff->staff_type->name === 'System Admin';
    }

    /**
     * Check if staff member can manage specific department.
     */
    private function canManageDepartment(Staff $staff, string $department): bool
    {
        // System admins and administrators can manage all departments
        if (in_array($staff->staff_type->name, ['System Admin', 'Administrator'])) {
            return true;
        }

        // Chiefs can manage kitchen department
        if ($staff->staff_type->name === 'Chief' && $department === 'kitchen') {
            return true;
        }

        // Management can manage service and bar departments
        if ($staff->staff_type->name === 'Management' && in_array($department, ['service', 'bar'])) {
            return true;
        }

        return false;
    }
}
