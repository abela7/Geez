<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

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

    /**
     * Show the form for creating a new shift.
     */
    public function create(): View
    {
        $departments = [
            'Kitchen',
            'Front of House',
            'Bar',
            'Management',
            'Cleaning',
            'Security',
            'Maintenance'
        ];

        $shiftTypes = [
            'regular' => 'Regular',
            'weekend' => 'Weekend',
            'overtime' => 'Overtime',
            'training' => 'Training',
            'meeting' => 'Meeting',
            'event' => 'Event',
            'maintenance' => 'Maintenance'
        ];

        $daysOfWeek = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday'
        ];

        return view('admin.shifts.manage.create', compact('departments', 'shiftTypes', 'daysOfWeek'));
    }

    /**
     * Store a newly created shift in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department' => 'required|string',
            'type' => 'required|string',
            'description' => 'nullable|string|max:1000',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'break_duration' => 'nullable|integer|min:0|max:480',
            'days_of_week' => 'required|array|min:1',
            'days_of_week.*' => 'string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'required_staff' => 'required|integer|min:1|max:50',
            'hourly_rate' => 'nullable|numeric|min:0',
            'overtime_rate' => 'nullable|numeric|min:0',
            'status' => 'required|string|in:draft,active'
        ]);

        // Calculate duration
        $startTime = \Carbon\Carbon::createFromFormat('H:i', $validated['start_time']);
        $endTime = \Carbon\Carbon::createFromFormat('H:i', $validated['end_time']);
        
        if ($endTime->lessThan($startTime)) {
            $endTime->addDay(); // Handle overnight shifts
        }
        
        $durationMinutes = $endTime->diffInMinutes($startTime);
        $validated['duration_minutes'] = $durationMinutes;

        // For demo purposes, we'll just store in session and redirect
        // In a real application, you would save to database
        session()->flash('shift_data', $validated);
        
        return redirect()->route('admin.shifts.manage.index')
            ->with('success', 'Shift "' . $validated['name'] . '" created successfully!');
    }

    /**
     * Show the form for editing the specified shift.
     */
    public function edit(string $id): View
    {
        $departments = [
            'Kitchen',
            'Front of House',
            'Bar',
            'Management',
            'Cleaning',
            'Security',
            'Maintenance'
        ];

        $shiftTypes = [
            'regular' => 'Regular',
            'weekend' => 'Weekend',
            'overtime' => 'Overtime',
            'training' => 'Training',
            'meeting' => 'Meeting',
            'event' => 'Event',
            'maintenance' => 'Maintenance'
        ];

        // For now, return dummy data
        $shift = (object) [
            'id' => $id,
            'name' => 'Sample Shift',
            'department' => 'Kitchen',
            'shift_type' => 'regular',
            'start_time' => '09:00',
            'end_time' => '17:00',
            'break_minutes' => 30,
            'min_staff_required' => 2,
            'max_staff_allowed' => 5,
            'hourly_rate_multiplier' => 1.0,
            'is_active' => true,
            'description' => 'Sample shift description'
        ];

        return view('admin.shifts.manage.edit', compact('shift', 'departments', 'shiftTypes'));
    }

    /**
     * Update the specified shift in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        // For now, just redirect back with success message
        // TODO: Implement actual shift update logic
        return redirect()->route('admin.shifts.manage.index')
            ->with('success', 'Shift updated successfully!');
    }

    /**
     * Remove the specified shift from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        // For now, just redirect back with success message
        // TODO: Implement actual shift deletion logic
        return redirect()->route('admin.shifts.manage.index')
            ->with('success', 'Shift deleted successfully!');
    }
}
