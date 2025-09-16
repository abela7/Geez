<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Todos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class ProgressController extends Controller
{
    /**
     * Display the progress tracking dashboard.
     */
    public function index(): View
    {
        // Mock data for progress tracking
        $progressData = $this->getProgressData();
        $performanceMetrics = $this->getPerformanceMetrics();
        $completionTrends = $this->getCompletionTrends();
        $staffPerformance = $this->getStaffPerformance();
        $categoryBreakdown = $this->getCategoryBreakdown();
        $recentActivity = $this->getRecentActivity();
        $upcomingDeadlines = $this->getUpcomingDeadlines();

        return view('admin.todos.progress.index', compact(
            'progressData',
            'performanceMetrics',
            'completionTrends',
            'staffPerformance',
            'categoryBreakdown',
            'recentActivity',
            'upcomingDeadlines'
        ));
    }

    /**
     * Generate progress report.
     */
    public function generateReport(Request $request): JsonResponse
    {
        $request->validate([
            'report_type' => 'required|in:daily,weekly,monthly,custom',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'staff_ids' => 'nullable|array',
            'categories' => 'nullable|array',
            'format' => 'required|in:pdf,excel,csv'
        ]);

        // Simulate report generation
        $reportData = $this->generateReportData($request->all());

        return response()->json([
            'success' => true,
            'message' => __('todos.progress.report_generated_successfully'),
            'report_url' => '/admin/todos/progress/reports/' . $reportData['filename'],
            'report_data' => $reportData
        ]);
    }

    /**
     * Export progress data.
     */
    public function export(Request $request): JsonResponse
    {
        $request->validate([
            'format' => 'required|in:pdf,excel,csv',
            'data_type' => 'required|in:overview,staff,categories,trends'
        ]);

        // Simulate export
        return response()->json([
            'success' => true,
            'message' => __('todos.progress.export_completed_successfully'),
            'download_url' => '/admin/todos/progress/exports/progress_' . $request->data_type . '_' . date('Y-m-d') . '.' . $request->format
        ]);
    }

    private function getProgressData(): array
    {
        return [
            'total_todos' => 1247,
            'completed_todos' => 1089,
            'pending_todos' => 98,
            'overdue_todos' => 60,
            'completion_rate' => 87.3,
            'average_completion_time' => 2.4, // hours
            'on_time_completion_rate' => 92.1,
            'total_staff' => 12,
            'active_templates' => 8,
            'active_schedules' => 5,
            'this_week_completed' => 156,
            'this_week_target' => 180,
            'weekly_progress' => 86.7,
            'productivity_score' => 8.4,
            'quality_score' => 9.1,
            'efficiency_trend' => 'up', // up, down, stable
            'efficiency_change' => 12.5 // percentage
        ];
    }

    private function getPerformanceMetrics(): array
    {
        return [
            'daily_average' => [
                'completed' => 24.3,
                'created' => 28.1,
                'completion_rate' => 86.5
            ],
            'weekly_average' => [
                'completed' => 170.1,
                'created' => 196.7,
                'completion_rate' => 86.5
            ],
            'monthly_average' => [
                'completed' => 738.4,
                'created' => 854.2,
                'completion_rate' => 86.5
            ],
            'peak_hours' => [
                ['hour' => '09:00', 'completion_count' => 45],
                ['hour' => '14:00', 'completion_count' => 38],
                ['hour' => '11:00', 'completion_count' => 35],
                ['hour' => '16:00', 'completion_count' => 32],
                ['hour' => '10:00', 'completion_count' => 29]
            ],
            'response_times' => [
                'average_start_time' => 1.2, // hours after assignment
                'average_completion_time' => 2.4, // hours from start to completion
                'fastest_completion' => 0.3,
                'slowest_completion' => 8.7
            ]
        ];
    }

    private function getCompletionTrends(): array
    {
        return [
            'last_30_days' => [
                ['date' => '2024-01-01', 'completed' => 23, 'created' => 28, 'rate' => 82.1],
                ['date' => '2024-01-02', 'completed' => 31, 'created' => 35, 'rate' => 88.6],
                ['date' => '2024-01-03', 'completed' => 28, 'created' => 32, 'rate' => 87.5],
                ['date' => '2024-01-04', 'completed' => 35, 'created' => 38, 'rate' => 92.1],
                ['date' => '2024-01-05', 'completed' => 29, 'created' => 33, 'rate' => 87.9],
                ['date' => '2024-01-06', 'completed' => 26, 'created' => 31, 'rate' => 83.9],
                ['date' => '2024-01-07', 'completed' => 32, 'created' => 36, 'rate' => 88.9],
                ['date' => '2024-01-08', 'completed' => 38, 'created' => 42, 'rate' => 90.5],
                ['date' => '2024-01-09', 'completed' => 34, 'created' => 39, 'rate' => 87.2],
                ['date' => '2024-01-10', 'completed' => 41, 'created' => 45, 'rate' => 91.1],
                ['date' => '2024-01-11', 'completed' => 37, 'created' => 41, 'rate' => 90.2],
                ['date' => '2024-01-12', 'completed' => 33, 'created' => 38, 'rate' => 86.8],
                ['date' => '2024-01-13', 'completed' => 39, 'created' => 43, 'rate' => 90.7],
                ['date' => '2024-01-14', 'completed' => 42, 'created' => 46, 'rate' => 91.3],
                ['date' => '2024-01-15', 'completed' => 36, 'created' => 40, 'rate' => 90.0]
            ],
            'weekly_comparison' => [
                ['week' => 'This Week', 'completed' => 156, 'target' => 180, 'rate' => 86.7],
                ['week' => 'Last Week', 'completed' => 174, 'target' => 180, 'rate' => 96.7],
                ['week' => '2 Weeks Ago', 'completed' => 162, 'target' => 175, 'rate' => 92.6],
                ['week' => '3 Weeks Ago', 'completed' => 148, 'target' => 170, 'rate' => 87.1]
            ]
        ];
    }

    private function getStaffPerformance(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Alemayehu Tadesse',
                'role' => 'Waiter',
                'total_assigned' => 89,
                'completed' => 82,
                'completion_rate' => 92.1,
                'average_time' => 2.1,
                'on_time_rate' => 95.1,
                'quality_score' => 9.2,
                'trend' => 'up',
                'recent_activity' => '2 hours ago'
            ],
            [
                'id' => 2,
                'name' => 'Meron Gebremedhin',
                'role' => 'Kitchen Staff',
                'total_assigned' => 76,
                'completed' => 71,
                'completion_rate' => 93.4,
                'average_time' => 1.8,
                'on_time_rate' => 97.2,
                'quality_score' => 9.5,
                'trend' => 'up',
                'recent_activity' => '1 hour ago'
            ],
            [
                'id' => 3,
                'name' => 'Yonas Assefa',
                'role' => 'Manager',
                'total_assigned' => 124,
                'completed' => 108,
                'completion_rate' => 87.1,
                'average_time' => 3.2,
                'on_time_rate' => 89.8,
                'quality_score' => 8.9,
                'trend' => 'stable',
                'recent_activity' => '30 minutes ago'
            ],
            [
                'id' => 4,
                'name' => 'Sara Hailu',
                'role' => 'Cashier',
                'total_assigned' => 67,
                'completed' => 58,
                'completion_rate' => 86.6,
                'average_time' => 2.7,
                'on_time_rate' => 91.4,
                'quality_score' => 8.7,
                'trend' => 'down',
                'recent_activity' => '4 hours ago'
            ],
            [
                'id' => 5,
                'name' => 'Dawit Bekele',
                'role' => 'Kitchen Staff',
                'total_assigned' => 82,
                'completed' => 76,
                'completion_rate' => 92.7,
                'average_time' => 2.0,
                'on_time_rate' => 94.7,
                'quality_score' => 9.1,
                'trend' => 'up',
                'recent_activity' => '1 hour ago'
            ]
        ];
    }

    private function getCategoryBreakdown(): array
    {
        return [
            [
                'category' => 'Opening Procedures',
                'total' => 156,
                'completed' => 148,
                'completion_rate' => 94.9,
                'average_time' => 1.8,
                'color' => '#10B981'
            ],
            [
                'category' => 'Equipment Checks',
                'total' => 234,
                'completed' => 198,
                'completion_rate' => 84.6,
                'average_time' => 2.1,
                'color' => '#3B82F6'
            ],
            [
                'category' => 'Cleaning Tasks',
                'total' => 189,
                'completed' => 167,
                'completion_rate' => 88.4,
                'average_time' => 3.2,
                'color' => '#8B5CF6'
            ],
            [
                'category' => 'Inventory Management',
                'total' => 98,
                'completed' => 89,
                'completion_rate' => 90.8,
                'average_time' => 4.1,
                'color' => '#F59E0B'
            ],
            [
                'category' => 'Customer Service',
                'total' => 145,
                'completed' => 132,
                'completion_rate' => 91.0,
                'average_time' => 1.5,
                'color' => '#EF4444'
            ],
            [
                'category' => 'Administrative',
                'total' => 67,
                'completed' => 58,
                'completion_rate' => 86.6,
                'average_time' => 2.8,
                'color' => '#6B7280'
            ]
        ];
    }

    private function getRecentActivity(): array
    {
        return [
            [
                'id' => 1,
                'type' => 'completed',
                'staff_name' => 'Alemayehu Tadesse',
                'todo_title' => 'Check coffee machine temperature',
                'category' => 'Equipment Checks',
                'completed_at' => '2024-01-16 14:30:00',
                'duration' => 15, // minutes
                'quality_rating' => 5
            ],
            [
                'id' => 2,
                'type' => 'started',
                'staff_name' => 'Meron Gebremedhin',
                'todo_title' => 'Prepare daily inventory report',
                'category' => 'Inventory Management',
                'started_at' => '2024-01-16 14:15:00',
                'estimated_duration' => 45
            ],
            [
                'id' => 3,
                'type' => 'overdue',
                'staff_name' => 'Sara Hailu',
                'todo_title' => 'Update customer feedback log',
                'category' => 'Administrative',
                'due_at' => '2024-01-16 13:00:00',
                'overdue_by' => 90 // minutes
            ],
            [
                'id' => 4,
                'type' => 'completed',
                'staff_name' => 'Yonas Assefa',
                'todo_title' => 'Review weekly sales report',
                'category' => 'Administrative',
                'completed_at' => '2024-01-16 13:45:00',
                'duration' => 35,
                'quality_rating' => 4
            ],
            [
                'id' => 5,
                'type' => 'assigned',
                'staff_name' => 'Dawit Bekele',
                'todo_title' => 'Deep clean kitchen equipment',
                'category' => 'Cleaning Tasks',
                'assigned_at' => '2024-01-16 13:30:00',
                'due_at' => '2024-01-16 16:00:00'
            ]
        ];
    }

    private function getUpcomingDeadlines(): array
    {
        return [
            [
                'id' => 1,
                'title' => 'Weekly inventory count',
                'staff_name' => 'Yonas Assefa',
                'category' => 'Inventory Management',
                'due_at' => '2024-01-16 17:00:00',
                'priority' => 'high',
                'estimated_duration' => 60,
                'status' => 'pending'
            ],
            [
                'id' => 2,
                'title' => 'Equipment safety inspection',
                'staff_name' => 'Meron Gebremedhin',
                'category' => 'Equipment Checks',
                'due_at' => '2024-01-16 18:00:00',
                'priority' => 'high',
                'estimated_duration' => 30,
                'status' => 'in_progress'
            ],
            [
                'id' => 3,
                'title' => 'Customer feedback review',
                'staff_name' => 'Sara Hailu',
                'category' => 'Customer Service',
                'due_at' => '2024-01-17 09:00:00',
                'priority' => 'medium',
                'estimated_duration' => 45,
                'status' => 'pending'
            ],
            [
                'id' => 4,
                'title' => 'Monthly deep cleaning',
                'staff_name' => 'All Staff',
                'category' => 'Cleaning Tasks',
                'due_at' => '2024-01-17 10:00:00',
                'priority' => 'medium',
                'estimated_duration' => 180,
                'status' => 'scheduled'
            ]
        ];
    }

    private function generateReportData(array $params): array
    {
        return [
            'filename' => 'progress_report_' . date('Y-m-d_H-i-s') . '.' . $params['format'],
            'type' => $params['report_type'],
            'period' => [
                'start' => $params['start_date'] ?? date('Y-m-01'),
                'end' => $params['end_date'] ?? date('Y-m-d')
            ],
            'summary' => [
                'total_todos' => 1247,
                'completed' => 1089,
                'completion_rate' => 87.3,
                'average_time' => 2.4
            ],
            'generated_at' => now()->toISOString()
        ];
    }
}
