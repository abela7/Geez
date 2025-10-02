<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Todos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OverviewController extends Controller
{
    /**
     * Display the to-do overview dashboard.
     */
    public function index(Request $request): View
    {
        // Get dashboard statistics
        $statistics = $this->getDashboardStatistics();

        // Get today's to-dos by staff
        $todaysTodos = $this->getTodaysTodos();

        // Get overdue to-dos
        $overdueTodos = $this->getOverdueTodos();

        // Get completion trends
        $completionTrends = $this->getCompletionTrends();

        // Get staff performance
        $staffPerformance = $this->getStaffPerformance();

        // Get upcoming recurring tasks
        $upcomingTasks = $this->getUpcomingTasks();

        return view('admin.todos.overview.index', compact(
            'statistics',
            'todaysTodos',
            'overdueTodos',
            'completionTrends',
            'staffPerformance',
            'upcomingTasks'
        ));
    }

    /**
     * Get dashboard statistics.
     */
    private function getDashboardStatistics(): array
    {
        return [
            'total_active_todos' => 156,
            'completed_today' => 42,
            'overdue_todos' => 8,
            'staff_with_todos' => 12,
            'completion_rate_today' => 87.5,
            'completion_rate_week' => 92.3,
            'completion_rate_month' => 89.7,
            'average_completion_time' => 2.3, // hours
            'most_common_category' => 'Opening Tasks',
            'total_templates' => 24,
            'active_schedules' => 18,
            'recurring_tasks_today' => 67,
        ];
    }

    /**
     * Get today's to-dos by staff.
     */
    private function getTodaysTodos(): array
    {
        return [
            [
                'staff_id' => 1,
                'staff_name' => 'Almaz Tadesse',
                'role' => 'Waiter',
                'avatar' => null,
                'total_todos' => 8,
                'completed_todos' => 6,
                'overdue_todos' => 1,
                'completion_rate' => 75.0,
                'current_todos' => [
                    [
                        'id' => 1,
                        'title' => 'Turn on delivery devices',
                        'category' => 'Opening Tasks',
                        'priority' => 'high',
                        'due_time' => '13:00',
                        'status' => 'overdue',
                        'estimated_duration' => 5, // minutes
                        'recurring_type' => 'daily',
                    ],
                    [
                        'id' => 2,
                        'title' => 'Check table settings',
                        'category' => 'Opening Tasks',
                        'priority' => 'normal',
                        'due_time' => '13:15',
                        'status' => 'pending',
                        'estimated_duration' => 10,
                        'recurring_type' => 'daily',
                    ],
                ],
            ],
            [
                'staff_id' => 2,
                'staff_name' => 'Dawit Haile',
                'role' => 'Chef',
                'avatar' => null,
                'total_todos' => 12,
                'completed_todos' => 10,
                'overdue_todos' => 0,
                'completion_rate' => 83.3,
                'current_todos' => [
                    [
                        'id' => 3,
                        'title' => 'Prep vegetables for lunch service',
                        'category' => 'Kitchen Prep',
                        'priority' => 'high',
                        'due_time' => '11:00',
                        'status' => 'completed',
                        'estimated_duration' => 45,
                        'recurring_type' => 'daily',
                    ],
                    [
                        'id' => 4,
                        'title' => 'Check inventory levels',
                        'category' => 'Kitchen Management',
                        'priority' => 'normal',
                        'due_time' => '14:00',
                        'status' => 'in_progress',
                        'estimated_duration' => 15,
                        'recurring_type' => 'daily',
                    ],
                ],
            ],
            [
                'staff_id' => 3,
                'staff_name' => 'Tigist Bekele',
                'role' => 'Cashier',
                'avatar' => null,
                'total_todos' => 6,
                'completed_todos' => 5,
                'overdue_todos' => 0,
                'completion_rate' => 83.3,
                'current_todos' => [
                    [
                        'id' => 5,
                        'title' => 'Count cash register',
                        'category' => 'Opening Tasks',
                        'priority' => 'high',
                        'due_time' => '12:45',
                        'status' => 'completed',
                        'estimated_duration' => 10,
                        'recurring_type' => 'daily',
                    ],
                    [
                        'id' => 6,
                        'title' => 'Print daily reports',
                        'category' => 'Administrative',
                        'priority' => 'normal',
                        'due_time' => '21:30',
                        'status' => 'pending',
                        'estimated_duration' => 5,
                        'recurring_type' => 'daily',
                    ],
                ],
            ],
        ];
    }

    /**
     * Get overdue to-dos.
     */
    private function getOverdueTodos(): array
    {
        return [
            [
                'id' => 1,
                'title' => 'Turn on delivery devices',
                'staff_name' => 'Almaz Tadesse',
                'role' => 'Waiter',
                'category' => 'Opening Tasks',
                'priority' => 'high',
                'due_time' => '13:00',
                'overdue_minutes' => 45,
                'estimated_duration' => 5,
                'recurring_type' => 'daily',
            ],
            [
                'id' => 7,
                'title' => 'Clean coffee machine',
                'staff_name' => 'Meseret Alemu',
                'role' => 'Barista',
                'category' => 'Equipment Maintenance',
                'priority' => 'normal',
                'due_time' => '12:00',
                'overdue_minutes' => 105,
                'estimated_duration' => 20,
                'recurring_type' => 'daily',
            ],
            [
                'id' => 8,
                'title' => 'Update menu boards',
                'staff_name' => 'Yohannes Tesfaye',
                'role' => 'Manager',
                'category' => 'Daily Setup',
                'priority' => 'normal',
                'due_time' => '11:30',
                'overdue_minutes' => 135,
                'estimated_duration' => 15,
                'recurring_type' => 'weekly',
            ],
        ];
    }

    /**
     * Get completion trends for the last 7 days.
     */
    private function getCompletionTrends(): array
    {
        return [
            ['date' => '2025-01-13', 'completed' => 45, 'total' => 52, 'rate' => 86.5],
            ['date' => '2025-01-14', 'completed' => 48, 'total' => 54, 'rate' => 88.9],
            ['date' => '2025-01-15', 'completed' => 42, 'total' => 49, 'rate' => 85.7],
            ['date' => '2025-01-16', 'completed' => 51, 'total' => 55, 'rate' => 92.7],
            ['date' => '2025-01-17', 'completed' => 47, 'total' => 51, 'rate' => 92.2],
            ['date' => '2025-01-18', 'completed' => 49, 'total' => 53, 'rate' => 92.5],
            ['date' => '2025-01-19', 'completed' => 42, 'total' => 48, 'rate' => 87.5],
        ];
    }

    /**
     * Get staff performance data.
     */
    private function getStaffPerformance(): array
    {
        return [
            [
                'staff_id' => 1,
                'staff_name' => 'Almaz Tadesse',
                'role' => 'Waiter',
                'completion_rate_week' => 89.2,
                'completion_rate_month' => 91.5,
                'total_todos_week' => 56,
                'completed_todos_week' => 50,
                'average_completion_time' => 1.8, // hours
                'streak_days' => 5,
                'performance_trend' => 'improving',
            ],
            [
                'staff_id' => 2,
                'staff_name' => 'Dawit Haile',
                'role' => 'Chef',
                'completion_rate_week' => 95.7,
                'completion_rate_month' => 94.3,
                'total_todos_week' => 84,
                'completed_todos_week' => 80,
                'average_completion_time' => 2.1,
                'streak_days' => 12,
                'performance_trend' => 'stable',
            ],
            [
                'staff_id' => 3,
                'staff_name' => 'Tigist Bekele',
                'role' => 'Cashier',
                'completion_rate_week' => 92.3,
                'completion_rate_month' => 90.8,
                'total_todos_week' => 42,
                'completed_todos_week' => 39,
                'average_completion_time' => 1.5,
                'streak_days' => 8,
                'performance_trend' => 'improving',
            ],
        ];
    }

    /**
     * Get upcoming recurring tasks.
     */
    private function getUpcomingTasks(): array
    {
        return [
            [
                'id' => 10,
                'title' => 'Weekly inventory count',
                'category' => 'Inventory Management',
                'assigned_to' => 'Kitchen Staff',
                'priority' => 'high',
                'next_due' => '2025-01-20 09:00',
                'recurring_type' => 'weekly',
                'estimated_duration' => 60,
                'last_completed' => '2025-01-13 09:30',
            ],
            [
                'id' => 11,
                'title' => 'Deep clean equipment',
                'category' => 'Maintenance',
                'assigned_to' => 'All Staff',
                'priority' => 'normal',
                'next_due' => '2025-01-21 20:00',
                'recurring_type' => 'weekly',
                'estimated_duration' => 90,
                'last_completed' => '2025-01-14 20:15',
            ],
            [
                'id' => 12,
                'title' => 'Monthly staff meeting',
                'category' => 'Administrative',
                'assigned_to' => 'All Staff',
                'priority' => 'normal',
                'next_due' => '2025-02-01 16:00',
                'recurring_type' => 'monthly',
                'estimated_duration' => 45,
                'last_completed' => '2025-01-01 16:00',
            ],
        ];
    }
}
