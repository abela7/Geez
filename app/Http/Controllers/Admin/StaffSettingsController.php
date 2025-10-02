<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class StaffSettingsController extends Controller
{
    /**
     * Display the staff settings page.
     */
    public function index()
    {
        // Simple UI-only data - no backend logic needed
        $staffTypesCount = 0;
        $activeStaffTypesCount = 0;
        $inactiveStaffTypesCount = 0;

        return view('admin.staff.settings.index', compact(
            'staffTypesCount',
            'activeStaffTypesCount',
            'inactiveStaffTypesCount'
        ));
    }
}
