<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaffShift;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShiftsManageController extends Controller
{
    /**
     * Display a listing of shifts.
     */
    public function index(): View
    {
        // Fetch all shifts from database with their relationships
        $dbShifts = StaffShift::with(['creator', 'assignments'])
            ->orderBy('is_active', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        // Format shifts for the view
        $shifts = $dbShifts->map(function ($shift) {
            $assignedCount = $shift->assignments()
                ->where('status', '!=', 'cancelled')
                ->distinct('staff_id')
                ->count('staff_id');

            // Format times to show only hours and minutes (HH:MM)
            $startTime = is_string($shift->start_time) 
                ? \Carbon\Carbon::parse($shift->start_time)->format('H:i')
                : $shift->start_time;
            
            $endTime = is_string($shift->end_time)
                ? \Carbon\Carbon::parse($shift->end_time)->format('H:i')
                : $shift->end_time;

            // Get duration and ensure it's always positive
            $duration = $shift->getDurationInHours();
            $duration = abs($duration); // Ensure positive value

            return [
                'id' => $shift->id,
                'name' => $shift->name,
                'department' => $shift->department ?? 'Unknown',
                'start_time' => $startTime,
                'end_time' => $endTime,
                'duration_hours' => $duration,
                'required_staff' => $shift->min_staff_required,
                'assigned_staff' => $assignedCount,
                'status' => $shift->is_active ? 'active' : 'inactive',
                'type' => $shift->shift_type ?? 'regular',
                'description' => $shift->description ?? '',
                'break_duration' => $shift->break_minutes ?? 0,
                'hourly_rate' => $shift->hourly_rate_multiplier ?? 1.00,
                'overtime_rate' => ($shift->hourly_rate_multiplier ?? 1.00) * 1.5,
                'days_of_week' => $shift->days_of_week ?? [],
            ];
        })->toArray();

        // Calculate statistics
        $totalShifts = count($shifts);
        $activeShifts = count(array_filter($shifts, fn ($s) => $s['status'] === 'active'));
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
            'position_name' => 'nullable|string|max:255',
            'department' => 'required|string',
            'type' => 'required|string',
            'description' => 'nullable|string|max:1000',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'break_duration' => 'nullable|integer|min:0|max:480',
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

        // Get the authenticated staff member (assuming auth:sanctum or similar)
        $createdBy = auth()->user()->id ?? null;

        // Create shift template in database
        $shift = StaffShift::create([
            'name' => $validated['name'],
            'position_name' => $validated['position_name'] ?? null,
            'department' => $validated['department'],
            'shift_type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'break_minutes' => $validated['break_duration'] ?? 0,
            'days_of_week' => null, // Templates don't have specific days
            'min_staff_required' => $validated['required_staff'],
            'max_staff_allowed' => $validated['required_staff'] * 2, // Default to 2x min
            'hourly_rate_multiplier' => $validated['hourly_rate'] ?? 1.00,
            'is_active' => $validated['status'] === 'active',
            'is_template' => true, // Mark as template
            'color_code' => $this->getColorForDepartment($validated['department']),
            'created_by' => $createdBy,
        ]);

        $successMessage = 'Shift template "'.$shift->name.'" created successfully! You can now assign it to staff via the weekly rota.';

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

        // Fetch shift from database
        $dbShift = StaffShift::findOrFail($id);

        // Format shift data for the view (times without seconds)
        $startTime = is_string($dbShift->start_time)
            ? \Carbon\Carbon::parse($dbShift->start_time)->format('H:i')
            : $dbShift->start_time;
        
        $endTime = is_string($dbShift->end_time)
            ? \Carbon\Carbon::parse($dbShift->end_time)->format('H:i')
            : $dbShift->end_time;

        $shift = [
            'id' => $dbShift->id,
            'name' => $dbShift->name,
            'position_name' => $dbShift->position_name ?? '',
            'department' => $dbShift->department,
            'shift_type' => $dbShift->shift_type,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'break_minutes' => $dbShift->break_minutes ?? 0,
            'min_staff_required' => $dbShift->min_staff_required,
            'hourly_rate_multiplier' => $dbShift->hourly_rate_multiplier ?? 1.00,
            'overtime_rate' => ($dbShift->hourly_rate_multiplier ?? 1.00) * 1.5,
            'is_active' => $dbShift->is_active,
            'description' => $dbShift->description ?? '',
            'days_of_week' => $dbShift->days_of_week ?? [],
            'duration_hours' => $dbShift->getDurationInHours(),
        ];

        return view('admin.shifts.manage.edit', compact('shift', 'departments', 'shiftTypes'));
    }

    /**
     * Update the specified shift in storage.
     */
    public function update(Request $request, string $id): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $shift = StaffShift::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position_name' => 'nullable|string|max:255',
            'department' => 'required|string',
            'type' => 'required|string',
            'description' => 'nullable|string|max:1000',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'break_duration' => 'nullable|integer|min:0|max:480',
            'required_staff' => 'required|integer|min:1|max:50',
            'hourly_rate' => 'nullable|numeric|min:0',
            'overtime_rate' => 'nullable|numeric|min:0',
            'status' => 'required|string|in:draft,active',
        ]);

        // Validate that end time is different from start time
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

        // Get the authenticated staff member
        $updatedBy = auth()->user()->id ?? null;

        // Update shift template in database
        $shift->update([
            'name' => $validated['name'],
            'position_name' => $validated['position_name'] ?? null,
            'department' => $validated['department'],
            'shift_type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'break_minutes' => $validated['break_duration'] ?? 0,
            'days_of_week' => null, // Templates don't have specific days
            'min_staff_required' => $validated['required_staff'],
            'max_staff_allowed' => $validated['required_staff'] * 2, // Default to 2x min
            'hourly_rate_multiplier' => $validated['hourly_rate'] ?? 1.00,
            'is_active' => $validated['status'] === 'active',
            'color_code' => $this->getColorForDepartment($validated['department']),
            'updated_by' => $updatedBy,
        ]);

        $successMessage = 'Shift template "'.$shift->name.'" updated successfully!';

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
     * Remove the specified shift from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        try {
            $shift = StaffShift::findOrFail($id);
            $shiftName = $shift->name;

            // Soft delete the shift
            $shift->delete();

            return redirect()->route('admin.shifts.manage.index')
                ->with('success', "Shift '{$shiftName}' deleted successfully!");
        } catch (\Exception $e) {
            return redirect()->route('admin.shifts.manage.index')
                ->with('error', 'Failed to delete shift. Please try again.');
        }
    }

    /**
     * Get a default color code for a department.
     */
    private function getColorForDepartment(string $department): string
    {
        $colors = [
            'Kitchen' => '#10B981', // Green
            'Front of House' => '#3B82F6', // Blue
            'Bar' => '#8B5CF6', // Purple
            'Management' => '#F59E0B', // Amber
            'Maintenance' => '#6B7280', // Gray
            'Cleaning' => '#EC4899', // Pink
            'Security' => '#EF4444', // Red
        ];

        return $colors[$department] ?? '#3B82F6'; // Default to blue
    }
}
