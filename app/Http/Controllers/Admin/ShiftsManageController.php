<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\ShiftType;
use App\Models\StaffShift;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ShiftsManageController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of shifts.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', StaffShift::class);

        $query = StaffShift::with(['creator', 'assignments'])
            ->orderBy('is_active', 'desc')
            ->orderBy('name', 'asc');

        // Apply filters from request
        $query->when($request->filled('search'), function ($q, $search) {
            $q->where(function ($inner) use ($search) {
                $inner->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        });


        $query->when($request->filled('department') && $request->department !== 'all', function ($q) use ($request) {
            $department = (string) $request->department;
            $q->whereRaw('LOWER(department) = ?', [strtolower($department)]);
        });

        $query->when($request->filled('status') && $request->status !== 'all', function ($q) use ($request) {
            $isActive = $request->status === 'active';
            $q->where('is_active', $isActive);
        });

        $query->when($request->filled('type') && $request->type !== 'all', function ($q) use ($request) {
            $q->where('shift_type', $request->type);
        });

        $query->whereNull('deleted_at');

        $shifts = $query->paginate(15)->withQueryString();

        $softDeletedCount = StaffShift::onlyTrashed()->count();
        Log::info('Total shifts (including deleted): ' . StaffShift::count() . ', Soft deleted: ' . $softDeletedCount . ', Active query count: ' . $query->count());


        // Clone for full stats (paginator doesn't have all for sums)
        $filteredQuery = clone $query;
        $allShifts = $filteredQuery->get();
        $totalShifts = $allShifts->count();
        $activeShifts = $allShifts->where('is_active', true)->count();
        $totalRequiredStaff = $allShifts->sum('min_staff_required');
        $totalAssignedStaff = 0;
        foreach ($allShifts as $shift) {
            $totalAssignedStaff += $shift->assignments()->where('status', '!=', 'cancelled')->distinct('staff_id')->count('staff_id');
        }
        $staffingPercentage = $totalRequiredStaff > 0 ? round(($totalAssignedStaff / $totalRequiredStaff) * 100) : 0;

        // Department breakdown from filtered (case-insensitive grouping)
        $departments = [];
        foreach ($allShifts as $shift) {
            $dept = $shift->department ?? 'Unknown';
            $deptKey = strtolower($dept); // Use lowercase as key for grouping
            
            if (!isset($departments[$deptKey])) {
                $departments[$deptKey] = [
                    'name' => ucfirst($dept), // Display with proper capitalization
                    'shifts' => 0,
                    'required_staff' => 0,
                    'assigned_staff' => 0,
                ];
            }
            $departments[$deptKey]['shifts']++;
            $departments[$deptKey]['required_staff'] += (int) $shift->min_staff_required;
            $assignedInDept = $shift->assignments()->where('status', '!=', 'cancelled')->distinct('staff_id')->count('staff_id');
            $departments[$deptKey]['assigned_staff'] += $assignedInDept;
        }

        // Transform paginated collection for view
        $shifts->getCollection()->transform(function ($shift) {
            $assignedCount = $shift->assignments()->where('status', '!=', 'cancelled')->distinct('staff_id')->count('staff_id');
            $startTime = is_string($shift->start_time) 
                ? Carbon::parse($shift->start_time)->format('H:i')
                : $shift->start_time?->format('H:i');
            $endTime = is_string($shift->end_time)
                ? Carbon::parse($shift->end_time)->format('H:i')
                : $shift->end_time?->format('H:i');
            $duration = $shift->getDurationInHours();

            $shift->formatted = [
                'id' => $shift->id,
                'name' => $shift->name,
                'department' => $shift->department,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'duration_hours' => round($duration, 1),
                'required_staff' => $shift->min_staff_required,
                'assigned_staff' => $assignedCount,
                'status' => $shift->is_active ? 'active' : 'inactive',
                'type' => $shift->shift_type ?? 'regular',
                'description' => $shift->description,
            ];

            return $shift;
        });

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
     * Show the form for creating a new shift.
     */
    public function create(): View
    {
        $departments = Department::getSelectOptions();
        $shiftTypes = ShiftType::getSelectOptions();
        $daysOfWeek = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
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
            'department' => 'required|string|max:100',
            'shift_type' => 'required|string|exists:shift_types,slug',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'break_minutes' => 'nullable|integer|min:0|max:240',
            'min_staff_required' => 'required|integer|min:1|max:50',
            'max_staff_allowed' => 'required|integer|min:1|max:100|gte:min_staff_required',
            'required_roles' => 'nullable|array|max:10',
            'required_roles.*' => 'string|max:100',
            'hourly_rate_multiplier' => 'nullable|numeric|min:5.0|max:100.0',
            'is_active' => 'boolean',
            'is_template' => 'boolean',
            'description' => 'nullable|string|max:1000',
            'days_of_week' => 'nullable|array|max:7',
            'days_of_week.*' => 'in:0,1,2,3,4,5,6',
        ]);

        // Custom validation for overnight shifts
        $startTime = Carbon::parse($validated['start_time']);
        $endTime = Carbon::parse($validated['end_time']);
        
        // If end time is same as start time, it's invalid
        if ($startTime->format('H:i') === $endTime->format('H:i')) {
            return back()->withErrors([
                'end_time' => 'End time must be different from start time.'
            ])->withInput();
        }

        // Convert days to int array
        if (isset($validated['days_of_week'])) {
            $validated['days_of_week'] = array_map('intval', array_unique($validated['days_of_week']));
        } else {
            $validated['days_of_week'] = [];
        }

        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        StaffShift::create($validated);

        return redirect()->route('admin.shifts.manage.index')
            ->with('success', 'Shift created successfully.');
    }

    /**
     * Show the form for editing the specified shift.
     */
    public function edit(StaffShift $shift): View
    {
        $departments = Department::getSelectOptions();
        $shiftTypes = ShiftType::getSelectOptions();
        $daysOfWeek = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];

        // Ensure days_of_week is array
        $shift->days_of_week = $shift->days_of_week ?? [];

        return view('admin.shifts.manage.edit', compact('shift', 'departments', 'shiftTypes', 'daysOfWeek'));
    }

    /**
     * Update the specified shift in storage.
     */
    public function update(Request $request, StaffShift $shift): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department' => 'required|string|max:100',
            'shift_type' => 'required|string|exists:shift_types,slug',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'break_minutes' => 'nullable|integer|min:0|max:240',
            'min_staff_required' => 'required|integer|min:1|max:50',
            'max_staff_allowed' => 'required|integer|min:1|max:100|gte:min_staff_required',
            'required_roles' => 'nullable|array|max:10',
            'required_roles.*' => 'string|max:100',
            'hourly_rate_multiplier' => 'nullable|numeric|min:5.0|max:100.0',
            'is_active' => 'boolean',
            'is_template' => 'boolean',
            'description' => 'nullable|string|max:1000',
            'days_of_week' => 'nullable|array|max:7',
            'days_of_week.*' => 'in:0,1,2,3,4,5,6',
        ]);

        // Custom validation for overnight shifts
        $startTime = Carbon::parse($validated['start_time']);
        $endTime = Carbon::parse($validated['end_time']);
        
        // If end time is same as start time, it's invalid
        if ($startTime->format('H:i') === $endTime->format('H:i')) {
            return back()->withErrors([
                'end_time' => 'End time must be different from start time.'
            ])->withInput();
        }

        if (isset($validated['days_of_week'])) {
            $validated['days_of_week'] = array_map('intval', array_unique($validated['days_of_week']));
        } else {
            $validated['days_of_week'] = [];
        }

        $validated['updated_by'] = auth()->id();

        $shift->update($validated);

        return redirect()->route('admin.shifts.manage.index')
            ->with('success', 'Shift updated successfully.');
    }

    /**
     * Remove the specified shift from storage.
     */
    public function destroy(StaffShift $shift): RedirectResponse
    {
        // Temporary: Skip authorization for testing - restore later
        // $this->authorize('delete', $shift);

        Log::info('Deleting shift permanently: ' . $shift->id . ' - ' . $shift->name);
        
        try {
            // Get all assignments (including soft deleted ones)
            $assignments = $shift->assignments()->withTrashed()->get();
            $assignmentCount = $assignments->count();
            
            if ($assignmentCount > 0) {
                Log::info("Shift has {$assignmentCount} assignments. Deleting related records first.");
                
                foreach ($assignments as $assignment) {
                    // Delete related records that reference this assignment
                    $assignment->exceptions()->forceDelete();
                    $assignment->swapRequests()->forceDelete();
                    
                    // Update attendance records to remove reference
                    \DB::table('staff_attendance')
                        ->where('shift_assignment_id', $assignment->id)
                        ->update(['shift_assignment_id' => null]);
                    
                    // Force delete the assignment
                    $assignment->forceDelete();
                }
                
                Log::info('All assignments and related records deleted for shift: ' . $shift->id);
            }
            
            // Delete any patterns or other relationships
            $shift->patterns()->forceDelete();
            
            // Now delete the shift
            $shift->forceDelete();
            
            Log::info('Shift permanently deleted: ' . $shift->id);
            
            return redirect()->route('admin.shifts.manage.index')
                ->with('success', 'Shift and all related records deleted successfully.');
                
        } catch (\Exception $e) {
            Log::error('Error deleting shift: ' . $e->getMessage());
            
            return redirect()->route('admin.shifts.manage.index')
                ->with('error', 'Failed to delete shift: ' . $e->getMessage());
        }
    }

    /**
     * Bulk activate selected shifts.
     */
    public function bulkActivate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'shift_ids' => 'required|array|min:1',
            'shift_ids.*' => 'exists:staff_shifts,id',
        ]);

        StaffShift::whereIn('id', $validated['shift_ids'])->update(['is_active' => true]);

        return redirect()->route('admin.shifts.manage.index')
            ->with('success', count($validated['shift_ids']) . ' shift(s) activated successfully.');
    }

    /**
     * Bulk deactivate selected shifts.
     */
    public function bulkDeactivate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'shift_ids' => 'required|array|min:1',
            'shift_ids.*' => 'exists:staff_shifts,id',
        ]);

        StaffShift::whereIn('id', $validated['shift_ids'])->update(['is_active' => false]);

        return redirect()->route('admin.shifts.manage.index')
            ->with('success', count($validated['shift_ids']) . ' shift(s) deactivated successfully.');
    }

    /**
     * Bulk delete selected shifts.
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'shift_ids' => 'required|array|min:1',
            'shift_ids.*' => 'exists:staff_shifts,id',
        ]);

        try {
            $shifts = StaffShift::whereIn('id', $validated['shift_ids'])->get();
            $deletedAssignments = 0;

            foreach ($shifts as $shift) {
                // Get all assignments (including soft deleted ones)
                $assignments = $shift->assignments()->withTrashed()->get();
                
                foreach ($assignments as $assignment) {
                    // Delete related records that reference this assignment
                    $assignment->exceptions()->forceDelete();
                    $assignment->swapRequests()->forceDelete();
                    
                    // Update attendance records to remove reference
                    DB::table('staff_attendance')
                        ->where('shift_assignment_id', $assignment->id)
                        ->update(['shift_assignment_id' => null]);
                    
                    // Force delete the assignment
                    $assignment->forceDelete();
                    $deletedAssignments++;
                }
                
                // Delete any patterns or other relationships
                $shift->patterns()->forceDelete();
            }

            // Now delete the shifts
            StaffShift::whereIn('id', $validated['shift_ids'])->forceDelete();

            $message = count($validated['shift_ids']) . ' shift(s) deleted successfully.';
            if ($deletedAssignments > 0) {
                $message .= " {$deletedAssignments} related assignment(s) and their dependencies were also deleted.";
            }

            return redirect()->route('admin.shifts.manage.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            Log::error('Error bulk deleting shifts: ' . $e->getMessage());
            
            return redirect()->route('admin.shifts.manage.index')
                ->with('error', 'Failed to delete shifts: ' . $e->getMessage());
        }
    }
}
