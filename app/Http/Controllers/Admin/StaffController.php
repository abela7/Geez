<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\StaffType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    /**
     * Display a listing of staff members.
     */
    public function index(Request $request)
    {
        $this->authorizeStaffAccess();

        $baseQuery = Staff::with(['staffType', 'profile']);

        // Apply filters to base query for counts
        $filteredQuery = clone $baseQuery;
        $filteredQuery->when($request->search, function ($q, $search) {
            $q->where(function ($inner) use ($search) {
                $inner->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        })
        ->when($request->staff_type_id, function ($q, $staffTypeId) {
            $q->where('staff_type_id', $staffTypeId);
        })
        ->when($request->status, function ($q, $status) {
            $q->where('status', $status);
        });

        // Total stats (filtered, calculated in PHP for safety)
        $filteredStaff = $filteredQuery->get();  // Fetch once for efficiency (small dataset)
        $totalStats = [
            'total' => $filteredStaff->count(),
            'active' => $filteredStaff->where('status', 'active')->count(),
            'avg_tenure' => $filteredStaff->avg(function ($staff) {
                return $staff->hire_date ? $staff->hire_date->diffInYears(now(), false) : 0;
            }) ?? 0.0,  // PHP avg on collection—safe, accurate years
            'recent_hires' => (clone $filteredQuery)->where('hire_date', '>=', now()->subDays(30))->count(),
        ];

        // Staff preview (filtered, all, alphabetical)
        $staffPreview = $filteredQuery
            ->with(['staffType', 'profile'])
            ->orderBy('first_name', 'asc')
            ->orderBy('last_name', 'asc')
            ->get();

        $staffTypes = StaffType::where('is_active', true)->orderBy('display_name')->get();

        return view('admin.staff.index', compact('totalStats', 'staffPreview', 'staffTypes'));
    }

    /**
     * Show the form for creating a new staff member.
     */
    public function create()
    {
        $this->authorizeStaffAccess();

        $staffTypes = StaffType::where('is_active', true)->orderBy('priority', 'desc')->orderBy('display_name')->get();

        return view('admin.staff.create', compact('staffTypes'));
    }

    /**
     * Store a newly created staff member in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeStaffAccess();

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:staff,username',
            'password' => 'required|string|min:8|confirmed',
            'email' => 'nullable|email|unique:staff,email',
            'phone' => 'nullable|string|max:20',
            'staff_type_id' => 'required|exists:staff_types,id',
            'status' => 'required|in:active,inactive,suspended',
            'hire_date' => 'nullable|date',
        ]);

        // Hash password
        $validated['password'] = Hash::make($validated['password']);

        // Set audit fields
        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        $staff = Staff::create($validated);

        return redirect()
            ->route('admin.staff.show', $staff)
            ->with('success', __('staff.created_successfully', ['name' => $staff->full_name]));
    }

    /**
     * Display the specified staff member.
     */
    public function show(Staff $staff)
    {
        $this->authorizeStaffAccess();

        // Load all related data for comprehensive profile
        $staff->load([
            'staffType',
            'profile',
        ]);

        // Get recent attendance records (last 30 days) - with fallback
        try {
            $recentAttendance = $staff->attendance()
                ->where('clock_in', '>=', now()->subDays(30))
                ->orderBy('clock_in', 'desc')
                ->limit(10)
                ->get();
        } catch (\Exception $e) {
            $recentAttendance = collect();
        }

        // Get recent payroll records (last 6 months) - with fallback
        try {
            $recentPayroll = $staff->payrollRecords()
                ->where('pay_period_start', '>=', now()->subMonths(6))
                ->orderBy('pay_period_start', 'desc')
                ->limit(6)
                ->get();
        } catch (\Exception $e) {
            $recentPayroll = collect();
        }

        // Get active task assignments - with fallback
        try {
            $taskFilter = request('task_filter', 'today');
            $taskQuery = $staff->taskAssignments()->with(['task']);
            
            // Apply filters based on selection
            switch ($taskFilter) {
                case 'today':
                    $taskQuery->where(function ($q) {
                        $q->whereDate('due_date', today())
                          ->orWhereDate('scheduled_date', today());
                    });
                    break;
                case 'pending':
                    $taskQuery->where('status', 'pending');
                    break;
                case 'in_progress':
                    $taskQuery->where('status', 'in_progress');
                    break;
                case 'completed':
                    $taskQuery->where('status', 'completed');
                    break;
                case 'overdue':
                    $taskQuery->where(function ($q) {
                        $q->where('due_date', '<', now())
                          ->whereNotIn('status', ['completed', 'cancelled']);
                    });
                    break;
                case 'this_week':
                    $taskQuery->where(function ($q) {
                        $q->whereBetween('due_date', [now()->startOfWeek(), now()->endOfWeek()])
                          ->orWhereBetween('scheduled_date', [now()->startOfWeek(), now()->endOfWeek()]);
                    });
                    break;
                case 'all':
                default:
                    // No additional filters for 'all'
                    break;
            }
            
            $activeTasks = $taskQuery->orderBy('due_date')
                ->orderBy('scheduled_time')
                ->limit(50)
                ->get();
        } catch (\Exception $e) {
            $activeTasks = collect();
        }

        // Get recent performance reviews - with fallback
        try {
            $performanceReviews = $staff->performanceReviews()
                ->with(['reviewer'])
                ->orderBy('review_date', 'desc')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            $performanceReviews = collect();
        }

        // Get upcoming shift assignments (next 14 days) - with fallback
        try {
            $upcomingShifts = $staff->shiftAssignments()
                ->with(['shift'])
                ->where('assigned_date', '>=', now()->toDateString())
                ->where('assigned_date', '<=', now()->addDays(14)->toDateString())
                ->where('status', '!=', 'cancelled')
                ->orderBy('assigned_date')
                ->get();
        } catch (\Exception $e) {
            $upcomingShifts = collect();
        }

        // Calculate statistics - with safe fallbacks
        $stats = [
            'total_hours_this_month' => $recentAttendance->where('clock_in', '>=', now()->startOfMonth())->sum('hours_worked') ?: 0,
            'attendance_rate' => $this->calculateAttendanceRate($staff),
            'task_completion_rate' => $this->calculateTaskCompletionRate($staff),
            'average_performance_rating' => $performanceReviews->avg('overall_rating') ?: null,
            'years_of_service' => $staff->hire_date ? $staff->hire_date->diffInYears(now()) : 0,
        ];

        return view('admin.staff.show', compact(
            'staff',
            'recentAttendance',
            'recentPayroll',
            'activeTasks',
            'performanceReviews',
            'upcomingShifts',
            'stats'
        ));
    }

    /**
     * Show the form for editing the specified staff member.
     */
    public function edit(Staff $staff)
    {
        $this->authorizeStaffAccess();

        $staffTypes = StaffType::where('is_active', true)->orderBy('priority', 'desc')->orderBy('display_name')->get();

        return view('admin.staff.edit', compact('staff', 'staffTypes'));
    }

    /**
     * Update the specified staff member in storage.
     */
    public function update(Request $request, Staff $staff)
    {
        $this->authorizeStaffAccess();

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'username' => ['required', 'string', 'max:50', Rule::unique('staff')->ignore($staff->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'email' => ['nullable', 'email', Rule::unique('staff')->ignore($staff->id)],
            'phone' => 'nullable|string|max:20',
            'staff_type_id' => 'required|exists:staff_types,id',
            'status' => 'required|in:active,inactive,suspended',
            'hire_date' => 'nullable|date',
        ]);

        // Handle password update
        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Set audit fields
        $validated['updated_by'] = Auth::id();

        $staff->update($validated);

        return redirect()
            ->route('admin.staff.show', $staff)
            ->with('success', __('staff.updated_successfully', ['name' => $staff->full_name]));
    }

    /**
     * Remove the specified staff member from storage (soft delete).
     */
    public function destroy(Staff $staff)
    {
        $this->authorizeStaffAccess();

        // Prevent self-deletion
        if ($staff->id === Auth::id()) {
            return redirect()
                ->route('admin.staff.index')
                ->with('error', __('staff.cannot_delete_self'));
        }

        $staff->delete();

        return redirect()
            ->route('admin.staff.index')
            ->with('success', __('staff.deleted_successfully', ['name' => $staff->full_name]));
    }

    /**
     * Show trashed staff members.
     */
    public function trashed(Request $request)
    {
        $this->authorizeStaffAccess();

        $query = Staff::onlyTrashed()->with(['staffType'])
            ->when($request->search, function ($q, $search) {
                $q->where(function ($query) use ($search) {
                    $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%");
                });
            })
            ->orderBy('deleted_at', 'desc');

        $trashedStaff = $query->paginate(15)->withQueryString();

        return view('admin.staff.trashed', compact('trashedStaff'));
    }

    /**
     * Restore the specified staff member from trash.
     */
    public function restore(string $id)
    {
        $this->authorizeStaffAccess();

        $staff = Staff::onlyTrashed()->findOrFail($id);
        $staff->restore();

        return redirect()
            ->route('admin.staff.show', $staff)
            ->with('success', __('staff.restored_successfully', ['name' => $staff->full_name]));
    }

    /**
     * Permanently delete the specified staff member.
     */
    public function forceDelete(string $id)
    {
        $this->authorizeStaffAccess();

        $staff = Staff::onlyTrashed()->findOrFail($id);

        // Prevent permanent deletion of current user
        if ($staff->id === Auth::id()) {
            return redirect()
                ->route('admin.staff.trashed')
                ->with('error', __('staff.cannot_force_delete_self'));
        }

        $name = $staff->full_name;
        $staff->forceDelete();

        return redirect()
            ->route('admin.staff.trashed')
            ->with('success', __('staff.force_deleted_successfully', ['name' => $name]));
    }

    /**
     * Toggle staff status (active/inactive).
     */
    public function toggleStatus(Staff $staff)
    {
        $this->authorizeStaffAccess();

        // Prevent deactivating self
        if ($staff->id === Auth::id() && $staff->status === 'active') {
            return redirect()
                ->route('admin.staff.show', $staff)
                ->with('error', __('staff.cannot_deactivate_self'));
        }

        $newStatus = $staff->status === 'active' ? 'inactive' : 'active';
        $staff->update([
            'status' => $newStatus,
            'updated_by' => Auth::id(),
        ]);

        $message = $newStatus === 'active'
            ? __('staff.activated_successfully', ['name' => $staff->full_name])
            : __('staff.deactivated_successfully', ['name' => $staff->full_name]);

        return redirect()
            ->route('admin.staff.show', $staff)
            ->with('success', $message);
    }

    /**
     * Check if current user can access staff management.
     */
    private function authorizeStaffAccess(): void
    {
        $user = Auth::user();
        $allowedRoles = ['system_admin', 'administrator'];

        if (! $user || ! in_array($user->staffType->name, $allowedRoles)) {
            abort(403, __('staff.unauthorized_access'));
        }
    }

    /**
     * Calculate attendance rate for a staff member.
     */
    private function calculateAttendanceRate(Staff $staff): float
    {
        try {
            $totalDays = now()->subDays(30)->diffInDays(now());
            $attendedDays = $staff->attendance()
                ->where('clock_in', '>=', now()->subDays(30))
                ->whereIn('status', ['present', 'late', 'overtime'])
                ->count();

            return $totalDays > 0 ? round(($attendedDays / $totalDays) * 100, 1) : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Calculate task completion rate for a staff member.
     */
    private function calculateTaskCompletionRate(Staff $staff): float
    {
        try {
            $totalTasks = $staff->taskAssignments()
                ->where('assigned_date', '>=', now()->subDays(30))
                ->count();

            $completedTasks = $staff->taskAssignments()
                ->where('assigned_date', '>=', now()->subDays(30))
                ->where('status', 'completed')
                ->count();

            return $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
}
