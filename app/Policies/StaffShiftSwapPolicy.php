<?php

namespace App\Policies;

use App\Models\Staff;
use App\Models\StaffShiftSwap;

class StaffShiftSwapPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Staff $staff): bool
    {
        // All staff can view swap requests (their own or available ones)
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Staff $staff, StaffShiftSwap $swap): bool
    {
        // Staff can view swaps they're involved in, managers can view all
        if ($this->isManagerOrAbove($staff)) {
            return true;
        }

        // Staff can view if they're the requester or target
        return in_array($staff->id, [$swap->requesting_staff_id, $swap->target_staff_id]);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Staff $staff): bool
    {
        // All staff can create swap requests
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Staff $staff, StaffShiftSwap $swap): bool
    {
        // Requesting staff can update their own pending requests
        if ($swap->requesting_staff_id === $staff->id) {
            return $swap->status === 'pending';
        }

        // Target staff can respond to requests directed at them
        if ($swap->target_staff_id === $staff->id) {
            return $swap->status === 'pending';
        }

        // Managers can update any swap for approval/management
        return $this->isManagerOrAbove($staff);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Staff $staff, StaffShiftSwap $swap): bool
    {
        // Requesting staff can delete their own pending requests
        if ($swap->requesting_staff_id === $staff->id) {
            return $swap->status === 'pending';
        }

        // Managers can delete any swap request
        return $this->isManagerOrAbove($staff);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Staff $staff, StaffShiftSwap $swap): bool
    {
        // Only managers can restore deleted swap requests
        return $this->isManagerOrAbove($staff);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Staff $staff, StaffShiftSwap $swap): bool
    {
        // Only system admins can permanently delete swap requests
        return $this->isSystemAdmin($staff);
    }

    /**
     * Determine whether the user can accept a swap request.
     */
    public function accept(Staff $staff, StaffShiftSwap $swap): bool
    {
        // Only the target staff member can accept
        if ($swap->target_staff_id !== $staff->id) {
            return false;
        }

        // Can only accept pending requests
        return $swap->status === 'pending';
    }

    /**
     * Determine whether the user can decline a swap request.
     */
    public function decline(Staff $staff, StaffShiftSwap $swap): bool
    {
        // Only the target staff member can decline
        if ($swap->target_staff_id !== $staff->id) {
            return false;
        }

        // Can only decline pending requests
        return $swap->status === 'pending';
    }

    /**
     * Determine whether the user can approve a swap (manager approval).
     */
    public function approve(Staff $staff, StaffShiftSwap $swap): bool
    {
        // Only managers can approve swaps
        if (! $this->isManagerOrAbove($staff)) {
            return false;
        }

        // Can only approve swaps that have been accepted by target
        return $swap->status === 'target_accepted';
    }

    /**
     * Determine whether the user can deny a swap (manager denial).
     */
    public function deny(Staff $staff, StaffShiftSwap $swap): bool
    {
        // Only managers can deny swaps
        if (! $this->isManagerOrAbove($staff)) {
            return false;
        }

        // Can deny pending or target-accepted swaps
        return in_array($swap->status, ['pending', 'target_accepted']);
    }

    /**
     * Determine whether the user can cancel their own swap request.
     */
    public function cancel(Staff $staff, StaffShiftSwap $swap): bool
    {
        // Only the requesting staff member can cancel
        if ($swap->requesting_staff_id !== $staff->id) {
            return false;
        }

        // Can only cancel pending or target-accepted requests
        return in_array($swap->status, ['pending', 'target_accepted']);
    }

    /**
     * Determine whether the user can create open swap requests.
     */
    public function createOpenRequest(Staff $staff): bool
    {
        // All staff can create open swap requests
        return true;
    }

    /**
     * Determine whether the user can respond to open swap requests.
     */
    public function respondToOpenRequest(Staff $staff, StaffShiftSwap $swap): bool
    {
        // Staff cannot respond to their own open requests
        if ($swap->requesting_staff_id === $staff->id) {
            return false;
        }

        // Can only respond to open, pending requests
        return $swap->swap_type === 'open' && $swap->status === 'pending';
    }

    /**
     * Determine whether the user can mark swap as urgent.
     */
    public function markUrgent(Staff $staff, StaffShiftSwap $swap): bool
    {
        // Only the requesting staff member can mark as urgent
        if ($swap->requesting_staff_id !== $staff->id) {
            return false;
        }

        // Can only mark pending requests as urgent
        return $swap->status === 'pending';
    }

    /**
     * Determine whether the user can override swap restrictions.
     */
    public function overrideRestrictions(Staff $staff, StaffShiftSwap $swap): bool
    {
        // Only system admins can override restrictions
        return $this->isSystemAdmin($staff);
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
}
