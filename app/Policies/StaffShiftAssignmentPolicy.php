<?php

namespace App\Policies;

use App\Models\Staff;
use App\Models\StaffShiftAssignment;
use Illuminate\Auth\Access\Response;

class StaffShiftAssignmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Staff $staff): bool
    {
        // Managers can view all assignments, staff can view their own
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Staff $staff, StaffShiftAssignment $assignment): bool
    {
        // Staff can view their own assignments, managers can view all
        return $this->isManagerOrAbove($staff) || $assignment->staff_id === $staff->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Staff $staff): bool
    {
        // Only managers and above can create assignments
        return $this->isManagerOrAbove($staff);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Staff $staff, StaffShiftAssignment $assignment): bool
    {
        // Managers can update any assignment
        if ($this->isManagerOrAbove($staff)) {
            return true;
        }

        // Staff can only update their own assignments and only certain fields
        if ($assignment->staff_id === $staff->id) {
            // Staff can only update if assignment is not completed/cancelled
            return !in_array($assignment->status, ['completed', 'cancelled', 'missed']);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Staff $staff, StaffShiftAssignment $assignment): bool
    {
        // Only managers can delete assignments
        if (!$this->isManagerOrAbove($staff)) {
            return false;
        }

        // Cannot delete assignments that are in progress or completed
        return !in_array($assignment->status, ['checked_in', 'active', 'on_break', 'completed']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Staff $staff, StaffShiftAssignment $assignment): bool
    {
        // Only managers and above can restore assignments
        return $this->isManagerOrAbove($staff);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Staff $staff, StaffShiftAssignment $assignment): bool
    {
        // Only system admins can permanently delete assignments
        return $this->isSystemAdmin($staff);
    }

    /**
     * Determine whether the user can check in to a shift.
     */
    public function checkIn(Staff $staff, StaffShiftAssignment $assignment): bool
    {
        // Only the assigned staff member can check in
        if ($assignment->staff_id !== $staff->id) {
            return false;
        }

        // Can only check in if status is scheduled
        return $assignment->status === 'scheduled';
    }

    /**
     * Determine whether the user can check out of a shift.
     */
    public function checkOut(Staff $staff, StaffShiftAssignment $assignment): bool
    {
        // Only the assigned staff member can check out
        if ($assignment->staff_id !== $staff->id) {
            return false;
        }

        // Can only check out if currently working
        return in_array($assignment->status, ['checked_in', 'active', 'on_break']);
    }

    /**
     * Determine whether the user can start/end breaks.
     */
    public function manageBreaks(Staff $staff, StaffShiftAssignment $assignment): bool
    {
        // Only the assigned staff member can manage their breaks
        if ($assignment->staff_id !== $staff->id) {
            return false;
        }

        // Can only manage breaks if currently working
        return in_array($assignment->status, ['checked_in', 'active', 'on_break']);
    }

    /**
     * Determine whether the user can add performance ratings.
     */
    public function addPerformanceRating(Staff $staff, StaffShiftAssignment $assignment): bool
    {
        // Only managers can add performance ratings
        return $this->isManagerOrAbove($staff);
    }

    /**
     * Determine whether the user can view performance data.
     */
    public function viewPerformanceData(Staff $staff, StaffShiftAssignment $assignment): bool
    {
        // Managers can view all performance data
        if ($this->isManagerOrAbove($staff)) {
            return true;
        }

        // Staff can view their own performance data
        return $assignment->staff_id === $staff->id;
    }

    /**
     * Determine whether the user can manage tips.
     */
    public function manageTips(Staff $staff, StaffShiftAssignment $assignment): bool
    {
        // Only the assigned staff member can manage their tips
        if ($assignment->staff_id !== $staff->id) {
            return false;
        }

        // Can only manage tips for service roles
        return in_array($assignment->role_assigned, ['waiter', 'bartender', 'host']);
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
            'Chief'
        ]);
    }

    /**
     * Check if staff member is system admin.
     */
    private function isSystemAdmin(Staff $staff): bool
    {
        return $staff->staff_type->name === 'System Admin';
    }
}