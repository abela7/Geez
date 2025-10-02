<?php

namespace App\Policies;

use App\Models\Staff;
use App\Models\StaffTimeOffRequest;

class StaffTimeOffRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Staff $staff): bool
    {
        // All staff can view time-off requests (their own or all if manager)
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Staff $staff, StaffTimeOffRequest $request): bool
    {
        // Staff can view their own requests, managers can view all
        return $this->isManagerOrAbove($staff) || $request->staff_id === $staff->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Staff $staff): bool
    {
        // All staff can create time-off requests
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Staff $staff, StaffTimeOffRequest $request): bool
    {
        // Staff can only update their own pending requests
        if ($request->staff_id === $staff->id) {
            return $request->status === 'pending';
        }

        // Managers can update any request (for approval/denial)
        return $this->isManagerOrAbove($staff);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Staff $staff, StaffTimeOffRequest $request): bool
    {
        // Staff can delete their own pending requests
        if ($request->staff_id === $staff->id) {
            return $request->status === 'pending';
        }

        // Managers can delete any request
        return $this->isManagerOrAbove($staff);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Staff $staff, StaffTimeOffRequest $request): bool
    {
        // Only managers can restore deleted requests
        return $this->isManagerOrAbove($staff);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Staff $staff, StaffTimeOffRequest $request): bool
    {
        // Only system admins can permanently delete requests
        return $this->isSystemAdmin($staff);
    }

    /**
     * Determine whether the user can approve time-off requests.
     */
    public function approve(Staff $staff, StaffTimeOffRequest $request): bool
    {
        // Only managers can approve requests
        if (! $this->isManagerOrAbove($staff)) {
            return false;
        }

        // Cannot approve your own request
        if ($request->staff_id === $staff->id) {
            return false;
        }

        // Can only approve pending requests
        return $request->status === 'pending';
    }

    /**
     * Determine whether the user can deny time-off requests.
     */
    public function deny(Staff $staff, StaffTimeOffRequest $request): bool
    {
        // Only managers can deny requests
        if (! $this->isManagerOrAbove($staff)) {
            return false;
        }

        // Cannot deny your own request
        if ($request->staff_id === $staff->id) {
            return false;
        }

        // Can only deny pending requests
        return $request->status === 'pending';
    }

    /**
     * Determine whether the user can cancel their own request.
     */
    public function cancel(Staff $staff, StaffTimeOffRequest $request): bool
    {
        // Only the requesting staff member can cancel their own request
        if ($request->staff_id !== $staff->id) {
            return false;
        }

        // Can only cancel pending or approved requests (not if already started)
        if (! in_array($request->status, ['pending', 'approved'])) {
            return false;
        }

        // Cannot cancel if time-off has already started
        return $request->start_date->isFuture();
    }

    /**
     * Determine whether the user can view approval history.
     */
    public function viewApprovalHistory(Staff $staff, StaffTimeOffRequest $request): bool
    {
        // Staff can view their own approval history, managers can view all
        return $this->isManagerOrAbove($staff) || $request->staff_id === $staff->id;
    }

    /**
     * Determine whether the user can view impact analysis.
     */
    public function viewImpactAnalysis(Staff $staff, StaffTimeOffRequest $request): bool
    {
        // Only managers can view impact analysis
        return $this->isManagerOrAbove($staff);
    }

    /**
     * Determine whether the user can override approval requirements.
     */
    public function overrideApproval(Staff $staff, StaffTimeOffRequest $request): bool
    {
        // Only system admins can override approval requirements
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
