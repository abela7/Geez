<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Shifts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class ManageController extends Controller
{
    /**
     * Display a listing of shifts
     */
    public function index(): View
    {
        // Mock data for existing shifts
        $shifts = [
            [
                'id' => 1,
                'name' => 'Morning Kitchen',
                'department' => 'Kitchen',
                'start_time' => '06:00',
                'end_time' => '14:00',
                'duration_hours' => 8,
                'required_staff' => 4,
                'assigned_staff' => 3,
                'status' => 'active',
                'type' => 'regular',
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(2),
                'description' => 'Morning kitchen preparation and breakfast service',
                'break_duration' => 60, // minutes
                'hourly_rate' => 18.50,
                'overtime_rate' => 27.75,
                'days_of_week' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
            ],
            [
                'id' => 2,
                'name' => 'Evening Service',
                'department' => 'Front of House',
                'start_time' => '17:00',
                'end_time' => '23:00',
                'duration_hours' => 6,
                'required_staff' => 6,
                'assigned_staff' => 6,
                'status' => 'active',
                'type' => 'regular',
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(1),
                'description' => 'Dinner service and customer support',
                'break_duration' => 30,
                'hourly_rate' => 16.00,
                'overtime_rate' => 24.00,
                'days_of_week' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'],
            ],
            [
                'id' => 3,
                'name' => 'Weekend Brunch',
                'department' => 'Kitchen',
                'start_time' => '09:00',
                'end_time' => '15:00',
                'duration_hours' => 6,
                'required_staff' => 3,
                'assigned_staff' => 2,
                'status' => 'active',
                'type' => 'weekend',
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now(),
                'description' => 'Weekend brunch preparation and service',
                'break_duration' => 45,
                'hourly_rate' => 20.00,
                'overtime_rate' => 30.00,
                'days_of_week' => ['saturday', 'sunday'],
            ],
            [
                'id' => 4,
                'name' => 'Bar Evening',
                'department' => 'Bar',
                'start_time' => '18:00',
                'end_time' => '02:00',
                'duration_hours' => 8,
                'required_staff' => 2,
                'assigned_staff' => 2,
                'status' => 'active',
                'type' => 'regular',
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(3),
                'description' => 'Evening bar service and cocktail preparation',
                'break_duration' => 60,
                'hourly_rate' => 17.50,
                'overtime_rate' => 26.25,
                'days_of_week' => ['thursday', 'friday', 'saturday'],
            ],
            [
                'id' => 5,
                'name' => 'Cleaning Crew',
                'department' => 'Maintenance',
                'start_time' => '23:00',
                'end_time' => '03:00',
                'duration_hours' => 4,
                'required_staff' => 2,
                'assigned_staff' => 1,
                'status' => 'draft',
                'type' => 'regular',
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subHours(2),
                'description' => 'Deep cleaning and maintenance tasks',
                'break_duration' => 15,
                'hourly_rate' => 15.00,
                'overtime_rate' => 22.50,
                'days_of_week' => ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday'],
            ],
        ];

        // Calculate summary statistics
        $totalShifts = count($shifts);
        $activeShifts = count(array_filter($shifts, fn($shift) => $shift['status'] === 'active'));
        $totalRequiredStaff = array_sum(array_column($shifts, 'required_staff'));
        $totalAssignedStaff = array_sum(array_column($shifts, 'assigned_staff'));
        $staffingPercentage = $totalRequiredStaff > 0 ? round(($totalAssignedStaff / $totalRequiredStaff) * 100) : 0;

        // Department breakdown
        $departments = [];
        foreach ($shifts as $shift) {
            $dept = $shift['department'];
            if (!isset($departments[$dept])) {
                $departments[$dept] = [
                    'name' => $dept,
                    'shifts' => 0,
                    'required_staff' => 0,
                    'assigned_staff' => 0,
                ];
            }
            $departments[$dept]['shifts']++;
            $departments[$dept]['required_staff'] += $shift['required_staff'];
            $departments[$dept]['assigned_staff'] += $shift['assigned_staff'];
        }

        return view('admin.shifts.manage.index', compact(
            'shifts',
            'totalShifts',
            'activeShifts',
            'totalRequiredStaff',
            'totalAssignedStaff',
            'staffingPercentage',
            'departments'
        ));
    }

    /**
     * Show the form for creating a new shift
     */
    public function create(): View
    {
        // Mock data for form options
        $departments = [
            'Kitchen' => 'Kitchen',
            'Front of House' => 'Front of House',
            'Bar' => 'Bar',
            'Management' => 'Management',
            'Maintenance' => 'Maintenance',
        ];

        $shiftTypes = [
            'regular' => 'Regular',
            'weekend' => 'Weekend',
            'overtime' => 'Overtime',
            'training' => 'Training',
            'special_event' => 'Special Event',
        ];

        $daysOfWeek = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
        ];

        return view('admin.shifts.manage.create', compact('departments', 'shiftTypes', 'daysOfWeek'));
    }

    /**
     * Store a newly created shift
     */
    public function store(Request $request): RedirectResponse
    {
        // In a real application, you would validate and store the shift
        // For now, we'll just redirect with a success message
        
        return redirect()->route('admin.shifts.manage.index')
            ->with('success', __('shifts.manage.shift_created_successfully'));
    }

    /**
     * Show the form for editing a shift
     */
    public function edit(int $id): View
    {
        // Mock shift data for editing
        $shift = [
            'id' => $id,
            'name' => 'Morning Kitchen',
            'department' => 'Kitchen',
            'start_time' => '06:00',
            'end_time' => '14:00',
            'required_staff' => 4,
            'status' => 'active',
            'type' => 'regular',
            'description' => 'Morning kitchen preparation and breakfast service',
            'break_duration' => 60,
            'hourly_rate' => 18.50,
            'overtime_rate' => 27.75,
            'days_of_week' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
        ];

        $departments = [
            'Kitchen' => 'Kitchen',
            'Front of House' => 'Front of House',
            'Bar' => 'Bar',
            'Management' => 'Management',
            'Maintenance' => 'Maintenance',
        ];

        $shiftTypes = [
            'regular' => 'Regular',
            'weekend' => 'Weekend',
            'overtime' => 'Overtime',
            'training' => 'Training',
            'special_event' => 'Special Event',
        ];

        $daysOfWeek = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
        ];

        return view('admin.shifts.manage.edit', compact('shift', 'departments', 'shiftTypes', 'daysOfWeek'));
    }

    /**
     * Update the specified shift
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        // In a real application, you would validate and update the shift
        // For now, we'll just redirect with a success message
        
        return redirect()->route('admin.shifts.manage.index')
            ->with('success', __('shifts.manage.shift_updated_successfully'));
    }

    /**
     * Remove the specified shift
     */
    public function destroy(int $id): RedirectResponse
    {
        // In a real application, you would delete the shift
        // For now, we'll just redirect with a success message
        
        return redirect()->route('admin.shifts.manage.index')
            ->with('success', __('shifts.manage.shift_deleted_successfully'));
    }
}
