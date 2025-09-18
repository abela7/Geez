<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ShiftsAssignmentsController extends Controller
{
    public function index(): View
    {
        $totalShifts = 0;
        $fullyCovered = 0;
        $partiallyCovered = 0;
        $notCovered = 0;
        $coveragePercentage = 0;
        $shifts = [];
        $availableStaff = 0;
        $totalStaff = 0;
        $staff = [];
        $recentActivity = [];

        return view('admin.shifts.assignments.index', compact(
            'totalShifts',
            'fullyCovered',
            'partiallyCovered',
            'notCovered',
            'coveragePercentage',
            'shifts',
            'availableStaff',
            'totalStaff',
            'staff',
            'recentActivity'
        ));
    }
}
