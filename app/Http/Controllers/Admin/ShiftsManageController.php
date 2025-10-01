<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ShiftsManageController extends Controller
{
    public function index(): View
    {
        $totalShifts = 0;
        $activeShifts = 0;
        $totalRequiredStaff = 0;
        $staffingPercentage = 0;
        $departments = [];
        $shifts = [];

        return view('admin.shifts.manage.index', compact(
            'totalShifts',
            'activeShifts',
            'totalRequiredStaff',
            'staffingPercentage',
            'departments',
            'shifts'
        ));
    }
}
