<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Todos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffListsController extends Controller
{
    /**
     * Display a listing of staff and their to-dos.
     */
    public function index(): View
    {
        // Mock data for staff lists
        $staffMembers = [
            [
                'id' => 1,
                'name' => 'Alemayehu Tadesse',
                'role' => 'Waiter',
                'department' => 'Service',
                'avatar' => 'https://ui-avatars.com/api/?name=Alemayehu+Tadesse&background=3B82F6&color=fff',
                'status' => 'active',
                'total_todos' => 8,
                'completed_todos' => 6,
                'overdue_todos' => 1,
                'completion_rate' => 75,
                'last_active' => '2024-01-15 14:30:00',
                'todos' => [
                    [
                        'id' => 1,
                        'title' => 'Turn on delivery devices',
                        'description' => 'Ensure all delivery tablets and phones are charged and ready',
                        'priority' => 'high',
                        'status' => 'completed',
                        'due_date' => '2024-01-15 09:00:00',
                        'completed_at' => '2024-01-15 08:45:00',
                        'recurring' => true,
                        'frequency' => 'daily',
                        'template_id' => 1,
                    ],
                    [
                        'id' => 2,
                        'title' => 'Check table cleanliness',
                        'description' => 'Inspect all tables before opening',
                        'priority' => 'medium',
                        'status' => 'in_progress',
                        'due_date' => '2024-01-15 10:00:00',
                        'completed_at' => null,
                        'recurring' => true,
                        'frequency' => 'daily',
                        'template_id' => 2,
                    ],
                    [
                        'id' => 3,
                        'title' => 'Update menu specials',
                        'description' => 'Update the daily specials board',
                        'priority' => 'normal',
                        'status' => 'pending',
                        'due_date' => '2024-01-15 11:00:00',
                        'completed_at' => null,
                        'recurring' => true,
                        'frequency' => 'daily',
                        'template_id' => 3,
                    ],
                ],
            ],
            [
                'id' => 2,
                'name' => 'Meron Gebremedhin',
                'role' => 'Kitchen Staff',
                'department' => 'Kitchen',
                'avatar' => 'https://ui-avatars.com/api/?name=Meron+Gebremedhin&background=10B981&color=fff',
                'status' => 'active',
                'total_todos' => 12,
                'completed_todos' => 9,
                'overdue_todos' => 0,
                'completion_rate' => 90,
                'last_active' => '2024-01-15 14:25:00',
                'todos' => [
                    [
                        'id' => 4,
                        'title' => 'Check ingredient inventory',
                        'description' => 'Verify all ingredients are available for today\'s menu',
                        'priority' => 'high',
                        'status' => 'completed',
                        'due_date' => '2024-01-15 08:00:00',
                        'completed_at' => '2024-01-15 07:55:00',
                        'recurring' => true,
                        'frequency' => 'daily',
                        'template_id' => 4,
                    ],
                    [
                        'id' => 5,
                        'title' => 'Prepare injera batter',
                        'description' => 'Mix and prepare injera batter for lunch service',
                        'priority' => 'high',
                        'status' => 'completed',
                        'due_date' => '2024-01-15 09:00:00',
                        'completed_at' => '2024-01-15 08:50:00',
                        'recurring' => true,
                        'frequency' => 'daily',
                        'template_id' => 5,
                    ],
                    [
                        'id' => 6,
                        'title' => 'Clean cooking stations',
                        'description' => 'Deep clean all cooking stations and equipment',
                        'priority' => 'medium',
                        'status' => 'in_progress',
                        'due_date' => '2024-01-15 15:00:00',
                        'completed_at' => null,
                        'recurring' => true,
                        'frequency' => 'daily',
                        'template_id' => 6,
                    ],
                ],
            ],
            [
                'id' => 3,
                'name' => 'Yonas Assefa',
                'role' => 'Manager',
                'department' => 'Management',
                'avatar' => 'https://ui-avatars.com/api/?name=Yonas+Assefa&background=8B5CF6&color=fff',
                'status' => 'active',
                'total_todos' => 6,
                'completed_todos' => 4,
                'overdue_todos' => 1,
                'completion_rate' => 67,
                'last_active' => '2024-01-15 14:20:00',
                'todos' => [
                    [
                        'id' => 7,
                        'title' => 'Review daily sales report',
                        'description' => 'Analyze yesterday\'s sales performance',
                        'priority' => 'high',
                        'status' => 'completed',
                        'due_date' => '2024-01-15 10:00:00',
                        'completed_at' => '2024-01-15 09:45:00',
                        'recurring' => true,
                        'frequency' => 'daily',
                        'template_id' => 7,
                    ],
                    [
                        'id' => 8,
                        'title' => 'Staff schedule review',
                        'description' => 'Review and approve next week\'s staff schedule',
                        'priority' => 'medium',
                        'status' => 'overdue',
                        'due_date' => '2024-01-14 17:00:00',
                        'completed_at' => null,
                        'recurring' => true,
                        'frequency' => 'weekly',
                        'template_id' => 8,
                    ],
                    [
                        'id' => 9,
                        'title' => 'Inventory order placement',
                        'description' => 'Place weekly inventory order with suppliers',
                        'priority' => 'normal',
                        'status' => 'pending',
                        'due_date' => '2024-01-16 12:00:00',
                        'completed_at' => null,
                        'recurring' => true,
                        'frequency' => 'weekly',
                        'template_id' => 9,
                    ],
                ],
            ],
            [
                'id' => 4,
                'name' => 'Sara Hailu',
                'role' => 'Cashier',
                'department' => 'Service',
                'avatar' => 'https://ui-avatars.com/api/?name=Sara+Hailu&background=F59E0B&color=fff',
                'status' => 'active',
                'total_todos' => 5,
                'completed_todos' => 5,
                'overdue_todos' => 0,
                'completion_rate' => 100,
                'last_active' => '2024-01-15 14:35:00',
                'todos' => [
                    [
                        'id' => 10,
                        'title' => 'Cash register setup',
                        'description' => 'Set up cash register and count starting cash',
                        'priority' => 'high',
                        'status' => 'completed',
                        'due_date' => '2024-01-15 08:30:00',
                        'completed_at' => '2024-01-15 08:25:00',
                        'recurring' => true,
                        'frequency' => 'daily',
                        'template_id' => 10,
                    ],
                    [
                        'id' => 11,
                        'title' => 'Update payment methods',
                        'description' => 'Ensure all payment methods are working',
                        'priority' => 'medium',
                        'status' => 'completed',
                        'due_date' => '2024-01-15 09:00:00',
                        'completed_at' => '2024-01-15 08:55:00',
                        'recurring' => true,
                        'frequency' => 'daily',
                        'template_id' => 11,
                    ],
                ],
            ],
        ];

        // Mock data for filters
        $departments = ['All', 'Service', 'Kitchen', 'Management'];
        $roles = ['All', 'Waiter', 'Kitchen Staff', 'Manager', 'Cashier'];
        $statuses = ['All', 'Active', 'Inactive'];

        return view('admin.todos.staff-lists.index', compact('staffMembers', 'departments', 'roles', 'statuses'));
    }

    /**
     * Display the specified staff member's to-dos.
     */
    public function show(int $staff): View
    {
        // Mock data for individual staff member
        $staffMember = [
            'id' => $staff,
            'name' => 'Alemayehu Tadesse',
            'role' => 'Waiter',
            'department' => 'Service',
            'avatar' => 'https://ui-avatars.com/api/?name=Alemayehu+Tadesse&background=3B82F6&color=fff',
            'status' => 'active',
            'email' => 'alemayehu@restaurant.com',
            'phone' => '+251 91 123 4567',
            'hire_date' => '2023-06-15',
            'total_todos' => 8,
            'completed_todos' => 6,
            'overdue_todos' => 1,
            'completion_rate' => 75,
            'last_active' => '2024-01-15 14:30:00',
            'performance_score' => 85,
            'todos' => [
                [
                    'id' => 1,
                    'title' => 'Turn on delivery devices',
                    'description' => 'Ensure all delivery tablets and phones are charged and ready',
                    'priority' => 'high',
                    'status' => 'completed',
                    'due_date' => '2024-01-15 09:00:00',
                    'completed_at' => '2024-01-15 08:45:00',
                    'recurring' => true,
                    'frequency' => 'daily',
                    'template_id' => 1,
                    'estimated_duration' => 15,
                    'actual_duration' => 12,
                ],
                [
                    'id' => 2,
                    'title' => 'Check table cleanliness',
                    'description' => 'Inspect all tables before opening',
                    'priority' => 'medium',
                    'status' => 'in_progress',
                    'due_date' => '2024-01-15 10:00:00',
                    'completed_at' => null,
                    'recurring' => true,
                    'frequency' => 'daily',
                    'template_id' => 2,
                    'estimated_duration' => 30,
                    'actual_duration' => null,
                ],
                [
                    'id' => 3,
                    'title' => 'Update menu specials',
                    'description' => 'Update the daily specials board',
                    'priority' => 'normal',
                    'status' => 'pending',
                    'due_date' => '2024-01-15 11:00:00',
                    'completed_at' => null,
                    'recurring' => true,
                    'frequency' => 'daily',
                    'template_id' => 3,
                    'estimated_duration' => 20,
                    'actual_duration' => null,
                ],
            ],
        ];

        return view('admin.todos.staff-lists.show', compact('staffMember'));
    }

    /**
     * Assign a new to-do to a staff member.
     */
    public function assign(Request $request, int $staff)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:high,medium,normal',
            'due_date' => 'required|date',
            'template_id' => 'nullable|integer',
            'estimated_duration' => 'nullable|integer|min:1',
        ]);

        // Mock response - in real implementation, this would save to database
        return response()->json([
            'success' => true,
            'message' => __('todos.staff_lists.todo_assigned_successfully'),
            'todo' => [
                'id' => rand(100, 999),
                'title' => $request->title,
                'description' => $request->description,
                'priority' => $request->priority,
                'status' => 'pending',
                'due_date' => $request->due_date,
                'estimated_duration' => $request->estimated_duration ?? 30,
            ],
        ]);
    }

    /**
     * Update the status of a to-do.
     */
    public function updateStatus(Request $request, int $todo)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        // Mock response - in real implementation, this would update database
        return response()->json([
            'success' => true,
            'message' => __('todos.staff_lists.status_updated_successfully'),
            'status' => $request->status,
        ]);
    }
}
