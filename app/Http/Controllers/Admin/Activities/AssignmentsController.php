<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Activities;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class AssignmentsController extends Controller
{
    /**
     * Display the activity assignments page.
     */
    public function index(): View
    {
        $currentAssignments = $this->getCurrentAssignments();
        $availableActivities = $this->getAvailableActivities();
        $staffMembers = $this->getStaffMembers();
        $departments = $this->getDepartments();
        $assignmentRules = $this->getAssignmentRules();
        $assignmentStats = $this->getAssignmentStats();

        return view('admin.activities.assignments.index', compact(
            'currentAssignments',
            'availableActivities',
            'staffMembers',
            'departments',
            'assignmentRules',
            'assignmentStats'
        ));
    }

    /**
     * Assign activities to staff members.
     */
    public function assignToStaff(Request $request): JsonResponse
    {
        $request->validate([
            'activity_ids' => 'required|array',
            'activity_ids.*' => 'integer',
            'staff_ids' => 'required|array',
            'staff_ids.*' => 'integer',
            'assignment_type' => 'required|in:individual,bulk,role_based,department_based'
        ]);

        // In a real implementation, this would create assignment records
        $assignedCount = count($request->activity_ids) * count($request->staff_ids);
        
        return response()->json([
            'success' => true,
            'message' => __('activities.assignments.assignment_successful'),
            'assignments_created' => $assignedCount,
            'activities' => $request->activity_ids,
            'staff' => $request->staff_ids
        ]);
    }

    /**
     * Remove activity assignments from staff members.
     */
    public function unassignFromStaff(Request $request): JsonResponse
    {
        $request->validate([
            'assignment_ids' => 'required|array',
            'assignment_ids.*' => 'integer'
        ]);

        // In a real implementation, this would delete assignment records
        return response()->json([
            'success' => true,
            'message' => __('activities.assignments.unassignment_successful'),
            'assignments_removed' => count($request->assignment_ids)
        ]);
    }

    /**
     * Auto-assign activities based on roles.
     */
    public function autoAssignByRole(Request $request): JsonResponse
    {
        $request->validate([
            'role' => 'required|string',
            'activity_ids' => 'required|array',
            'activity_ids.*' => 'integer'
        ]);

        // In a real implementation, this would assign activities to all staff with the specified role
        $staffCount = $this->getStaffCountByRole($request->role);
        $assignedCount = count($request->activity_ids) * $staffCount;

        return response()->json([
            'success' => true,
            'message' => __('activities.assignments.auto_assignment_successful'),
            'assignments_created' => $assignedCount,
            'role' => $request->role,
            'staff_affected' => $staffCount
        ]);
    }

    /**
     * Auto-assign activities based on departments.
     */
    public function autoAssignByDepartment(Request $request): JsonResponse
    {
        $request->validate([
            'department' => 'required|string',
            'activity_ids' => 'required|array',
            'activity_ids.*' => 'integer'
        ]);

        // In a real implementation, this would assign activities to all staff in the specified department
        $staffCount = $this->getStaffCountByDepartment($request->department);
        $assignedCount = count($request->activity_ids) * $staffCount;

        return response()->json([
            'success' => true,
            'message' => __('activities.assignments.auto_assignment_successful'),
            'assignments_created' => $assignedCount,
            'department' => $request->department,
            'staff_affected' => $staffCount
        ]);
    }

    /**
     * Create an assignment rule.
     */
    public function createRule(Request $request): JsonResponse
    {
        $request->validate([
            'rule_name' => 'required|string|max:255',
            'rule_type' => 'required|in:role_based,department_based,custom',
            'conditions' => 'required|array',
            'activity_ids' => 'required|array',
            'activity_ids.*' => 'integer'
        ]);

        // In a real implementation, this would create an assignment rule
        return response()->json([
            'success' => true,
            'message' => __('activities.assignments.rule_created_successfully'),
            'rule' => [
                'id' => rand(100, 999),
                'name' => $request->rule_name,
                'type' => $request->rule_type,
                'conditions' => $request->conditions,
                'activity_count' => count($request->activity_ids),
                'is_active' => true,
                'created_at' => now()->toISOString()
            ]
        ]);
    }

    private function getCurrentAssignments(): array
    {
        return [
            [
                'id' => 1,
                'staff_id' => 1,
                'staff_name' => 'Alemayehu Tadesse',
                'staff_role' => 'Head Chef',
                'department' => 'Kitchen',
                'activity_id' => 1,
                'activity_name' => 'Making Beyaynet',
                'activity_category' => 'Food Preparation',
                'assigned_at' => '2024-01-10 08:00:00',
                'assigned_by' => 'Admin',
                'is_active' => true,
                'completion_count' => 23,
                'last_completed' => '2024-01-16 14:30:00'
            ],
            [
                'id' => 2,
                'staff_id' => 1,
                'staff_name' => 'Alemayehu Tadesse',
                'staff_role' => 'Head Chef',
                'department' => 'Kitchen',
                'activity_id' => 3,
                'activity_name' => 'Roasting Coffee Beans',
                'activity_category' => 'Food Preparation',
                'assigned_at' => '2024-01-10 08:00:00',
                'assigned_by' => 'Admin',
                'is_active' => true,
                'completion_count' => 8,
                'last_completed' => '2024-01-15 16:00:00'
            ],
            [
                'id' => 3,
                'staff_id' => 2,
                'staff_name' => 'Meron Gebremedhin',
                'staff_role' => 'Kitchen Staff',
                'department' => 'Kitchen',
                'activity_id' => 1,
                'activity_name' => 'Making Beyaynet',
                'activity_category' => 'Food Preparation',
                'assigned_at' => '2024-01-10 08:00:00',
                'assigned_by' => 'Admin',
                'is_active' => true,
                'completion_count' => 15,
                'last_completed' => '2024-01-16 11:15:00'
            ],
            [
                'id' => 4,
                'staff_id' => 2,
                'staff_name' => 'Meron Gebremedhin',
                'staff_role' => 'Kitchen Staff',
                'department' => 'Kitchen',
                'activity_id' => 2,
                'activity_name' => 'Washing Coffee Filter',
                'activity_category' => 'Equipment Maintenance',
                'assigned_at' => '2024-01-10 08:00:00',
                'assigned_by' => 'Admin',
                'is_active' => true,
                'completion_count' => 12,
                'last_completed' => '2024-01-16 10:15:00'
            ],
            [
                'id' => 5,
                'staff_id' => 4,
                'staff_name' => 'Sara Ahmed',
                'staff_role' => 'Server',
                'department' => 'Front of House',
                'activity_id' => 4,
                'activity_name' => 'Table Service Setup',
                'activity_category' => 'Service Preparation',
                'assigned_at' => '2024-01-10 08:00:00',
                'assigned_by' => 'Admin',
                'is_active' => true,
                'completion_count' => 34,
                'last_completed' => '2024-01-16 17:30:00'
            ],
            [
                'id' => 6,
                'staff_id' => 4,
                'staff_name' => 'Sara Ahmed',
                'staff_role' => 'Server',
                'department' => 'Front of House',
                'activity_id' => 6,
                'activity_name' => 'Customer Order Taking',
                'activity_category' => 'Customer Service',
                'assigned_at' => '2024-01-10 08:00:00',
                'assigned_by' => 'Admin',
                'is_active' => true,
                'completion_count' => 89,
                'last_completed' => '2024-01-16 19:45:00'
            ],
            [
                'id' => 7,
                'staff_id' => 5,
                'staff_name' => 'Yohannes Tesfaye',
                'staff_role' => 'Bartender',
                'department' => 'Bar',
                'activity_id' => 7,
                'activity_name' => 'Beverage Preparation',
                'activity_category' => 'Food Preparation',
                'assigned_at' => '2024-01-10 08:00:00',
                'assigned_by' => 'Admin',
                'is_active' => true,
                'completion_count' => 45,
                'last_completed' => '2024-01-16 20:00:00'
            ],
            [
                'id' => 8,
                'staff_id' => 6,
                'staff_name' => 'Hanan Osman',
                'staff_role' => 'Manager',
                'department' => 'Management',
                'activity_id' => 5,
                'activity_name' => 'Inventory Count',
                'activity_category' => 'Administrative',
                'assigned_at' => '2024-01-10 08:00:00',
                'assigned_by' => 'Admin',
                'is_active' => true,
                'completion_count' => 23,
                'last_completed' => '2024-01-16 09:00:00'
            ]
        ];
    }

    private function getAvailableActivities(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Making Beyaynet',
                'category' => 'Food Preparation',
                'department' => 'Kitchen',
                'estimated_duration' => 120,
                'difficulty_level' => 'medium',
                'assigned_staff_count' => 2
            ],
            [
                'id' => 2,
                'name' => 'Washing Coffee Filter',
                'category' => 'Equipment Maintenance',
                'department' => 'Kitchen',
                'estimated_duration' => 90,
                'difficulty_level' => 'easy',
                'assigned_staff_count' => 1
            ],
            [
                'id' => 3,
                'name' => 'Roasting Coffee Beans',
                'category' => 'Food Preparation',
                'department' => 'Kitchen',
                'estimated_duration' => 180,
                'difficulty_level' => 'hard',
                'assigned_staff_count' => 1
            ],
            [
                'id' => 4,
                'name' => 'Table Service Setup',
                'category' => 'Service Preparation',
                'department' => 'Front of House',
                'estimated_duration' => 45,
                'difficulty_level' => 'easy',
                'assigned_staff_count' => 1
            ],
            [
                'id' => 5,
                'name' => 'Inventory Count',
                'category' => 'Administrative',
                'department' => 'Kitchen',
                'estimated_duration' => 60,
                'difficulty_level' => 'medium',
                'assigned_staff_count' => 1
            ],
            [
                'id' => 6,
                'name' => 'Customer Order Taking',
                'category' => 'Customer Service',
                'department' => 'Front of House',
                'estimated_duration' => 15,
                'difficulty_level' => 'easy',
                'assigned_staff_count' => 1
            ],
            [
                'id' => 7,
                'name' => 'Beverage Preparation',
                'category' => 'Food Preparation',
                'department' => 'Bar',
                'estimated_duration' => 30,
                'difficulty_level' => 'medium',
                'assigned_staff_count' => 1
            ]
        ];
    }

    private function getStaffMembers(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Alemayehu Tadesse',
                'role' => 'Head Chef',
                'department' => 'Kitchen',
                'email' => 'alemayehu@restaurant.com',
                'assigned_activities_count' => 2,
                'is_active' => true
            ],
            [
                'id' => 2,
                'name' => 'Meron Gebremedhin',
                'role' => 'Kitchen Staff',
                'department' => 'Kitchen',
                'email' => 'meron@restaurant.com',
                'assigned_activities_count' => 2,
                'is_active' => true
            ],
            [
                'id' => 3,
                'name' => 'Dawit Bekele',
                'role' => 'Kitchen Staff',
                'department' => 'Kitchen',
                'email' => 'dawit@restaurant.com',
                'assigned_activities_count' => 0,
                'is_active' => true
            ],
            [
                'id' => 4,
                'name' => 'Sara Ahmed',
                'role' => 'Server',
                'department' => 'Front of House',
                'email' => 'sara@restaurant.com',
                'assigned_activities_count' => 2,
                'is_active' => true
            ],
            [
                'id' => 5,
                'name' => 'Yohannes Tesfaye',
                'role' => 'Bartender',
                'department' => 'Bar',
                'email' => 'yohannes@restaurant.com',
                'assigned_activities_count' => 1,
                'is_active' => true
            ],
            [
                'id' => 6,
                'name' => 'Hanan Osman',
                'role' => 'Manager',
                'department' => 'Management',
                'email' => 'hanan@restaurant.com',
                'assigned_activities_count' => 1,
                'is_active' => true
            ]
        ];
    }

    private function getDepartments(): array
    {
        return [
            [
                'name' => 'Kitchen',
                'staff_count' => 3,
                'activities_count' => 4
            ],
            [
                'name' => 'Front of House',
                'staff_count' => 1,
                'activities_count' => 2
            ],
            [
                'name' => 'Bar',
                'staff_count' => 1,
                'activities_count' => 1
            ],
            [
                'name' => 'Management',
                'staff_count' => 1,
                'activities_count' => 1
            ]
        ];
    }

    private function getAssignmentRules(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Kitchen Staff Default Activities',
                'type' => 'role_based',
                'conditions' => ['role' => 'Kitchen Staff'],
                'activity_count' => 3,
                'affected_staff' => 2,
                'is_active' => true,
                'created_at' => '2024-01-10 08:00:00'
            ],
            [
                'id' => 2,
                'name' => 'Server Essential Tasks',
                'type' => 'role_based',
                'conditions' => ['role' => 'Server'],
                'activity_count' => 2,
                'affected_staff' => 1,
                'is_active' => true,
                'created_at' => '2024-01-10 08:00:00'
            ],
            [
                'id' => 3,
                'name' => 'Management Oversight',
                'type' => 'department_based',
                'conditions' => ['department' => 'Management'],
                'activity_count' => 1,
                'affected_staff' => 1,
                'is_active' => true,
                'created_at' => '2024-01-10 08:00:00'
            ]
        ];
    }

    private function getAssignmentStats(): array
    {
        return [
            'total_assignments' => 8,
            'active_assignments' => 8,
            'staff_with_assignments' => 5,
            'activities_assigned' => 7,
            'avg_assignments_per_staff' => 1.6,
            'most_assigned_activity' => 'Making Beyaynet',
            'least_assigned_activity' => 'Roasting Coffee Beans'
        ];
    }

    private function getStaffCountByRole(string $role): int
    {
        $roleCounts = [
            'Head Chef' => 1,
            'Kitchen Staff' => 2,
            'Server' => 1,
            'Bartender' => 1,
            'Manager' => 1
        ];

        return $roleCounts[$role] ?? 0;
    }

    private function getStaffCountByDepartment(string $department): int
    {
        $departmentCounts = [
            'Kitchen' => 3,
            'Front of House' => 1,
            'Bar' => 1,
            'Management' => 1
        ];

        return $departmentCounts[$department] ?? 0;
    }
}
