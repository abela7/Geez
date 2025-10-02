<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\View\View;

class ShiftsOverviewController extends Controller
{
    public function index(): View
    {
        $weekStart = Carbon::now()->startOfWeek();
        $weekNavigation = [
            'previous_week' => (clone $weekStart)->subWeek(),
            'next_week' => (clone $weekStart)->addWeek(),
            'is_current_week' => true,
        ];

        $shiftSummary = [
            'total_shifts' => 0,
            'total_staff_scheduled' => 0,
            'total_hours' => 0,
            'coverage_gaps' => 0,
        ];

        $currentShifts = [];
        $upcomingShifts = [];
        $coverageGaps = [];
        $weeklySchedule = [];

        return view('admin.shifts.overview.index', compact(
            'weekStart',
            'weekNavigation',
            'shiftSummary',
            'currentShifts',
            'upcomingShifts',
            'coverageGaps',
            'weeklySchedule'
        ));
    }
}
