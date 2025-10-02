<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Shifts;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssignmentsController extends Controller
{
    /**
     * Display shift assignments overview
     */
    public function index(): View
    {
        // Mock data for staff members
        $staff = [
            [
                'id' => 1,
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@restaurant.com',
                'department' => 'Kitchen',
                'role' => 'Head Chef',
                'status' => 'active',
                'availability' => [
                    'monday' => ['09:00', '17:00'],
                    'tuesday' => ['09:00', '17:00'],
                    'wednesday' => ['09:00', '17:00'],
                    'thursday' => ['09:00', '17:00'],
                    'friday' => ['09:00', '17:00'],
                ],
                'skills' => ['cooking', 'management', 'food_safety'],
                'hourly_rate' => 25.00,
                'phone' => '+1 (555) 123-4567',
                'hire_date' => Carbon::now()->subMonths(18),
            ],
            [
                'id' => 2,
                'name' => 'Michael Chen',
                'email' => 'michael.chen@restaurant.com',
                'department' => 'Front of House',
                'role' => 'Server',
                'status' => 'active',
                'availability' => [
                    'monday' => ['17:00', '23:00'],
                    'tuesday' => ['17:00', '23:00'],
                    'wednesday' => ['17:00', '23:00'],
                    'thursday' => ['17:00', '23:00'],
                    'friday' => ['17:00', '23:00'],
                    'saturday' => ['17:00', '23:00'],
                ],
                'skills' => ['customer_service', 'pos_systems', 'wine_knowledge'],
                'hourly_rate' => 18.00,
                'phone' => '+1 (555) 234-5678',
                'hire_date' => Carbon::now()->subMonths(8),
            ],
            [
                'id' => 3,
                'name' => 'Emma Rodriguez',
                'email' => 'emma.rodriguez@restaurant.com',
                'department' => 'Kitchen',
                'role' => 'Line Cook',
                'status' => 'active',
                'availability' => [
                    'tuesday' => ['06:00', '14:00'],
                    'wednesday' => ['06:00', '14:00'],
                    'thursday' => ['06:00', '14:00'],
                    'friday' => ['06:00', '14:00'],
                    'saturday' => ['06:00', '14:00'],
                    'sunday' => ['09:00', '15:00'],
                ],
                'skills' => ['prep_work', 'grill', 'inventory'],
                'hourly_rate' => 20.00,
                'phone' => '+1 (555) 345-6789',
                'hire_date' => Carbon::now()->subMonths(12),
            ],
            [
                'id' => 4,
                'name' => 'James Wilson',
                'email' => 'james.wilson@restaurant.com',
                'department' => 'Bar',
                'role' => 'Bartender',
                'status' => 'active',
                'availability' => [
                    'wednesday' => ['18:00', '02:00'],
                    'thursday' => ['18:00', '02:00'],
                    'friday' => ['18:00', '02:00'],
                    'saturday' => ['18:00', '02:00'],
                ],
                'skills' => ['mixology', 'inventory', 'customer_service'],
                'hourly_rate' => 22.00,
                'phone' => '+1 (555) 456-7890',
                'hire_date' => Carbon::now()->subMonths(6),
            ],
            [
                'id' => 5,
                'name' => 'Lisa Thompson',
                'email' => 'lisa.thompson@restaurant.com',
                'department' => 'Front of House',
                'role' => 'Host',
                'status' => 'active',
                'availability' => [
                    'monday' => ['17:00', '22:00'],
                    'tuesday' => ['17:00', '22:00'],
                    'wednesday' => ['17:00', '22:00'],
                    'thursday' => ['17:00', '22:00'],
                    'friday' => ['17:00', '23:00'],
                    'saturday' => ['17:00', '23:00'],
                    'sunday' => ['16:00', '21:00'],
                ],
                'skills' => ['customer_service', 'reservations', 'phone_systems'],
                'hourly_rate' => 16.00,
                'phone' => '+1 (555) 567-8901',
                'hire_date' => Carbon::now()->subMonths(4),
            ],
        ];

        // Mock data for shifts needing assignments
        $shifts = [
            [
                'id' => 1,
                'name' => 'Morning Kitchen',
                'date' => Carbon::now()->addDays(1),
                'start_time' => '06:00',
                'end_time' => '14:00',
                'department' => 'Kitchen',
                'required_staff' => 4,
                'assigned_staff' => 2,
                'status' => 'partially_covered',
                'assignments' => [
                    ['staff_id' => 1, 'staff_name' => 'Sarah Johnson', 'role' => 'Head Chef', 'status' => 'confirmed'],
                    ['staff_id' => 3, 'staff_name' => 'Emma Rodriguez', 'role' => 'Line Cook', 'status' => 'pending'],
                ],
            ],
            [
                'id' => 2,
                'name' => 'Evening Service',
                'date' => Carbon::now()->addDays(1),
                'start_time' => '17:00',
                'end_time' => '23:00',
                'department' => 'Front of House',
                'required_staff' => 6,
                'assigned_staff' => 4,
                'status' => 'partially_covered',
                'assignments' => [
                    ['staff_id' => 2, 'staff_name' => 'Michael Chen', 'role' => 'Server', 'status' => 'confirmed'],
                    ['staff_id' => 5, 'staff_name' => 'Lisa Thompson', 'role' => 'Host', 'status' => 'confirmed'],
                ],
            ],
            [
                'id' => 3,
                'name' => 'Weekend Brunch',
                'date' => Carbon::now()->addDays(5), // Saturday
                'start_time' => '09:00',
                'end_time' => '15:00',
                'department' => 'Kitchen',
                'required_staff' => 3,
                'assigned_staff' => 3,
                'status' => 'fully_covered',
                'assignments' => [
                    ['staff_id' => 1, 'staff_name' => 'Sarah Johnson', 'role' => 'Head Chef', 'status' => 'confirmed'],
                    ['staff_id' => 3, 'staff_name' => 'Emma Rodriguez', 'role' => 'Line Cook', 'status' => 'confirmed'],
                ],
            ],
            [
                'id' => 4,
                'name' => 'Bar Evening',
                'date' => Carbon::now()->addDays(2),
                'start_time' => '18:00',
                'end_time' => '02:00',
                'department' => 'Bar',
                'required_staff' => 2,
                'assigned_staff' => 1,
                'status' => 'partially_covered',
                'assignments' => [
                    ['staff_id' => 4, 'staff_name' => 'James Wilson', 'role' => 'Bartender', 'status' => 'confirmed'],
                ],
            ],
            [
                'id' => 5,
                'name' => 'Cleaning Crew',
                'date' => Carbon::now()->addDays(3),
                'start_time' => '23:00',
                'end_time' => '03:00',
                'department' => 'Maintenance',
                'required_staff' => 2,
                'assigned_staff' => 0,
                'status' => 'not_covered',
                'assignments' => [],
            ],
        ];

        // Calculate assignment statistics
        $totalShifts = count($shifts);
        $fullyCovered = count(array_filter($shifts, fn ($shift) => $shift['status'] === 'fully_covered'));
        $partiallyCovered = count(array_filter($shifts, fn ($shift) => $shift['status'] === 'partially_covered'));
        $notCovered = count(array_filter($shifts, fn ($shift) => $shift['status'] === 'not_covered'));

        $totalRequiredStaff = array_sum(array_column($shifts, 'required_staff'));
        $totalAssignedStaff = array_sum(array_column($shifts, 'assigned_staff'));
        $coveragePercentage = $totalRequiredStaff > 0 ? round(($totalAssignedStaff / $totalRequiredStaff) * 100) : 0;

        // Staff availability summary
        $availableStaff = count(array_filter($staff, fn ($s) => $s['status'] === 'active'));
        $totalStaff = count($staff);

        // Recent assignment activity
        $recentActivity = [
            [
                'id' => 1,
                'type' => 'assignment',
                'staff_name' => 'Michael Chen',
                'shift_name' => 'Evening Service',
                'date' => Carbon::now()->subHours(2),
                'status' => 'confirmed',
                'assigned_by' => 'Admin User',
            ],
            [
                'id' => 2,
                'type' => 'unassignment',
                'staff_name' => 'Emma Rodriguez',
                'shift_name' => 'Morning Kitchen',
                'date' => Carbon::now()->subHours(4),
                'status' => 'cancelled',
                'assigned_by' => 'Sarah Johnson',
            ],
            [
                'id' => 3,
                'type' => 'assignment',
                'staff_name' => 'James Wilson',
                'shift_name' => 'Bar Evening',
                'date' => Carbon::now()->subHours(6),
                'status' => 'pending',
                'assigned_by' => 'Admin User',
            ],
        ];

        return view('admin.shifts.assignments.index', compact(
            'staff',
            'shifts',
            'totalShifts',
            'fullyCovered',
            'partiallyCovered',
            'notCovered',
            'totalRequiredStaff',
            'totalAssignedStaff',
            'coveragePercentage',
            'availableStaff',
            'totalStaff',
            'recentActivity'
        ));
    }

    /**
     * Assign staff to a shift
     */
    public function assign(Request $request): JsonResponse
    {
        // In a real application, you would validate and create the assignment
        $staffId = $request->input('staff_id');
        $shiftId = $request->input('shift_id');
        $role = $request->input('role', 'Staff');

        // Mock response
        return response()->json([
            'success' => true,
            'message' => __('shifts.assignments.assignment_successful'),
            'assignment' => [
                'id' => rand(100, 999),
                'staff_id' => $staffId,
                'shift_id' => $shiftId,
                'role' => $role,
                'status' => 'pending',
                'assigned_at' => Carbon::now()->toISOString(),
            ],
        ]);
    }

    /**
     * Unassign staff from a shift
     */
    public function unassign(Request $request): JsonResponse
    {
        // In a real application, you would remove the assignment
        $assignmentId = $request->input('assignment_id');

        // Mock response
        return response()->json([
            'success' => true,
            'message' => __('shifts.assignments.unassignment_successful'),
            'assignment_id' => $assignmentId,
        ]);
    }

    /**
     * Update assignment status
     */
    public function updateStatus(Request $request): JsonResponse
    {
        $assignmentId = $request->input('assignment_id');
        $status = $request->input('status');

        // Mock response
        return response()->json([
            'success' => true,
            'message' => __('shifts.assignments.status_updated'),
            'assignment' => [
                'id' => $assignmentId,
                'status' => $status,
                'updated_at' => Carbon::now()->toISOString(),
            ],
        ]);
    }

    /**
     * Get staff availability for a specific time period
     */
    public function getAvailability(Request $request): JsonResponse
    {
        $date = $request->input('date');
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');
        $department = $request->input('department');

        // Mock available staff based on filters
        $availableStaff = [
            [
                'id' => 1,
                'name' => 'Sarah Johnson',
                'role' => 'Head Chef',
                'department' => 'Kitchen',
                'availability_status' => 'available',
                'conflicts' => [],
            ],
            [
                'id' => 2,
                'name' => 'Michael Chen',
                'role' => 'Server',
                'department' => 'Front of House',
                'availability_status' => 'available',
                'conflicts' => [],
            ],
            [
                'id' => 3,
                'name' => 'Emma Rodriguez',
                'role' => 'Line Cook',
                'department' => 'Kitchen',
                'availability_status' => 'conflict',
                'conflicts' => ['Already assigned to Morning Kitchen (06:00-14:00)'],
            ],
        ];

        return response()->json([
            'success' => true,
            'available_staff' => $availableStaff,
            'total_available' => count(array_filter($availableStaff, fn ($s) => $s['availability_status'] === 'available')),
        ]);
    }
}
