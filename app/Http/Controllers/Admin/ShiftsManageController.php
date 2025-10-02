<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShiftsManageController extends Controller
{
    public function index(): View
    {
        // Build shifts list from session (demo-only - persistent storage)
        $allShifts = session('all_shifts', []);

        $shifts = [];
        foreach ($allShifts as $index => $created) {
            $durationHours = isset($created['duration_minutes'])
                ? abs(round(($created['duration_minutes'] / 60), 2))
                : 0;

            $shifts[] = [
                'id' => $index + 1,
                'name' => $created['name'] ?? 'New Shift',
                'department' => $created['department'] ?? 'Unknown',
                'start_time' => $created['start_time'] ?? '',
                'end_time' => $created['end_time'] ?? '',
                'duration_hours' => $durationHours,
                'required_staff' => (int) ($created['required_staff'] ?? 1),
                'assigned_staff' => 0,
                'status' => $created['status'] ?? 'draft',
                'type' => $created['type'] ?? 'regular',
                'description' => $created['description'] ?? '',
                'break_duration' => (int) ($created['break_duration'] ?? 0),
                'hourly_rate' => (float) ($created['hourly_rate'] ?? 0),
                'overtime_rate' => (float) ($created['overtime_rate'] ?? 0),
                'days_of_week' => $created['days_of_week'] ?? [],
            ];
        }

        $totalShifts = count($shifts);
        $activeShifts = count(array_filter($shifts, fn ($s) => ($s['status'] ?? 'draft') === 'active'));
        $totalRequiredStaff = array_sum(array_map(fn ($s) => (int) $s['required_staff'], $shifts));
        $totalAssignedStaff = array_sum(array_map(fn ($s) => (int) $s['assigned_staff'], $shifts));
        $staffingPercentage = $totalRequiredStaff > 0
            ? (int) round(($totalAssignedStaff / $totalRequiredStaff) * 100)
            : 0;

        // Department breakdown
        $departments = [];
        foreach ($shifts as $shift) {
            $dept = $shift['department'] ?? 'Unknown';
            if (! isset($departments[$dept])) {
                $departments[$dept] = [
                    'name' => $dept,
                    'shifts' => 0,
                    'required_staff' => 0,
                    'assigned_staff' => 0,
                ];
            }
            $departments[$dept]['shifts']++;
            $departments[$dept]['required_staff'] += (int) $shift['required_staff'];
            $departments[$dept]['assigned_staff'] += (int) $shift['assigned_staff'];
        }

        return view('admin.shifts.manage.index', compact(
            'totalShifts',
            'activeShifts',
            'totalRequiredStaff',
            'totalAssignedStaff',
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
        $departments = \App\Models\Department::getSelectOptions();
        $shiftTypes = \App\Models\ShiftType::getSelectOptions();

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
     * Store a newly created shift in storage.
     */
    public function store(Request $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department' => 'required|string',
            'type' => 'required|string',
            'description' => 'nullable|string|max:1000',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'break_duration' => 'nullable|integer|min:0|max:480',
            'days_of_week' => 'required|array|min:1',
            'days_of_week.*' => 'string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'required_staff' => 'required|integer|min:1|max:50',
            'hourly_rate' => 'nullable|numeric|min:0',
            'overtime_rate' => 'nullable|numeric|min:0',
            'status' => 'required|string|in:draft,active',
        ]);

        // Validate that start and end times are different
        if ($validated['start_time'] === $validated['end_time']) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'End time must be different from start time.',
                    'errors' => ['end_time' => ['End time must be different from start time.']],
                ], 422);
            }

            return redirect()->back()
                ->withErrors(['end_time' => 'End time must be different from start time.'])
                ->withInput();
        }

        // Calculate duration
        $startTime = \Carbon\Carbon::createFromFormat('H:i', $validated['start_time']);
        $endTime = \Carbon\Carbon::createFromFormat('H:i', $validated['end_time']);

        if ($endTime->lessThan($startTime)) {
            $endTime->addDay(); // Handle overnight shifts
        }

        $durationMinutes = $endTime->diffInMinutes($startTime);
        $validated['duration_minutes'] = $durationMinutes;

        // For demo purposes, store in persistent session (not flash)
        // In a real application, you would save to database
        $allShifts = session('all_shifts', []);
        $allShifts[] = $validated;
        session(['all_shifts' => $allShifts]);

        $successMessage = 'Shift "'.$validated['name'].'" created successfully!';

        // Return JSON response for AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'redirect' => route('admin.shifts.manage.index'),
            ]);
        }

        return redirect()->route('admin.shifts.manage.index')
            ->with('success', $successMessage);
    }

    /**
     * Show the form for editing the specified shift.
     */
    public function edit(string $id): View
    {
        $departments = \App\Models\Department::getSelectOptions();
        $shiftTypes = \App\Models\ShiftType::getSelectOptions();

        $daysOfWeek = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
        ];

        // Get shift data from session (for demo)
        $allShifts = session('all_shifts', []);

        // Find the shift by ID
        $shiftIndex = (int) $id - 1; // Convert to array index
        if (! isset($allShifts[$shiftIndex])) {
            // If shift not found, create dummy data
            $shift = [
                'id' => $id,
                'name' => 'Sample Shift',
                'department' => 'Kitchen',
                'type' => 'regular',
                'start_time' => '09:00',
                'end_time' => '17:00',
                'break_duration' => 30,
                'required_staff' => 2,
                'hourly_rate' => 15.00,
                'overtime_rate' => 22.50,
                'status' => 'draft',
                'description' => 'Sample shift description',
                'days_of_week' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
            ];
        } else {
            // Use actual shift data from session
            $shift = $allShifts[$shiftIndex];
            $shift['id'] = $id; // Ensure ID is set
        }

        return view('admin.shifts.manage.edit', compact('shift', 'departments', 'shiftTypes', 'daysOfWeek'));
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
        try {
            // Find the shift by ID
            $shift = \App\Models\StaffShift::findOrFail($id);
            
            // Check if shift has any active assignments
            $activeAssignments = $shift->activeAssignments()->count();
            
            if ($activeAssignments > 0) {
                return redirect()->route('admin.shifts.manage.index')
                    ->with('error', 'Cannot delete shift with active assignments. Please cancel or reassign staff first.');
            }
            
            // Delete the shift (soft delete)
            $shift->delete();
            
            return redirect()->route('admin.shifts.manage.index')
                ->with('success', 'Shift deleted successfully!');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.shifts.manage.index')
                ->with('error', 'Failed to delete shift. Please try again.');
        }
    }
}
