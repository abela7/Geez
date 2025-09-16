<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Activities;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class AnalyticsController extends Controller
{
    /**
     * Display the activity analytics dashboard.
     */
    public function index(): View
    {
        $overviewStats = $this->getOverviewStats();
        $staffPerformance = $this->getStaffPerformance();
        $activityBreakdown = $this->getActivityBreakdown();
        $departmentComparison = $this->getDepartmentComparison();
        $timeAnalysis = $this->getTimeAnalysis();
        $trendsData = $this->getTrendsData();
        $peakHours = $this->getPeakHours();

        return view('admin.activities.analytics.index', compact(
            'overviewStats',
            'staffPerformance',
            'activityBreakdown',
            'departmentComparison',
            'timeAnalysis',
            'trendsData',
            'peakHours'
        ));
    }

    /**
     * Get detailed analytics for a specific staff member.
     */
    public function staffAnalytics(Request $request, int $staffId): View
    {
        $staffMember = $this->getStaffMember($staffId);
        $staffStats = $this->getStaffStats($staffId);
        $staffActivities = $this->getStaffActivities($staffId);
        $staffTrends = $this->getStaffTrends($staffId);

        return view('admin.activities.analytics.staff', compact(
            'staffMember',
            'staffStats',
            'staffActivities',
            'staffTrends'
        ));
    }

    /**
     * Export analytics data.
     */
    public function export(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|in:overview,staff,activities,departments',
            'format' => 'required|in:csv,excel,pdf',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from'
        ]);

        // In a real implementation, this would generate and return the export file
        return response()->json([
            'success' => true,
            'message' => __('activities.analytics.export_completed_successfully'),
            'download_url' => '/admin/activities/analytics/download/' . rand(1000, 9999)
        ]);
    }

    private function getOverviewStats(): array
    {
        return [
            'total_activities_logged' => 1247,
            'total_time_tracked' => 18420, // minutes (307 hours)
            'average_efficiency' => 89, // percentage
            'total_staff_active' => 24,
            'activities_completed_today' => 156,
            'time_tracked_today' => 1140, // minutes (19 hours)
            'efficiency_today' => 92, // percentage
            'most_productive_department' => 'Kitchen',
            'least_productive_department' => 'Management',
            'peak_productivity_hour' => '14:00',
            'average_activity_duration' => 67 // minutes
        ];
    }

    private function getStaffPerformance(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Alemayehu Tadesse',
                'role' => 'Head Chef',
                'department' => 'Kitchen',
                'activities_logged' => 89,
                'total_time' => 2340, // minutes
                'avg_time_per_activity' => 26,
                'efficiency_score' => 94,
                'on_time_completion' => 87,
                'trend' => 'up',
                'last_activity' => '2024-01-16 18:30:00'
            ],
            [
                'id' => 2,
                'name' => 'Meron Gebremedhin',
                'role' => 'Kitchen Staff',
                'department' => 'Kitchen',
                'activities_logged' => 76,
                'total_time' => 1980, // minutes
                'avg_time_per_activity' => 26,
                'efficiency_score' => 91,
                'on_time_completion' => 82,
                'trend' => 'up',
                'last_activity' => '2024-01-16 17:45:00'
            ],
            [
                'id' => 3,
                'name' => 'Dawit Bekele',
                'role' => 'Kitchen Staff',
                'department' => 'Kitchen',
                'activities_logged' => 72,
                'total_time' => 1890, // minutes
                'avg_time_per_activity' => 26,
                'efficiency_score' => 88,
                'on_time_completion' => 79,
                'trend' => 'stable',
                'last_activity' => '2024-01-16 16:20:00'
            ],
            [
                'id' => 4,
                'name' => 'Sara Ahmed',
                'role' => 'Server',
                'department' => 'Front of House',
                'activities_logged' => 134,
                'total_time' => 1560, // minutes
                'avg_time_per_activity' => 12,
                'efficiency_score' => 96,
                'on_time_completion' => 93,
                'trend' => 'up',
                'last_activity' => '2024-01-16 19:15:00'
            ],
            [
                'id' => 5,
                'name' => 'Yohannes Tesfaye',
                'role' => 'Bartender',
                'department' => 'Bar',
                'activities_logged' => 45,
                'total_time' => 1200, // minutes
                'avg_time_per_activity' => 27,
                'efficiency_score' => 85,
                'on_time_completion' => 76,
                'trend' => 'down',
                'last_activity' => '2024-01-16 20:00:00'
            ],
            [
                'id' => 6,
                'name' => 'Hanan Osman',
                'role' => 'Manager',
                'department' => 'Management',
                'activities_logged' => 23,
                'total_time' => 780, // minutes
                'avg_time_per_activity' => 34,
                'efficiency_score' => 78,
                'on_time_completion' => 70,
                'trend' => 'stable',
                'last_activity' => '2024-01-16 15:30:00'
            ]
        ];
    }

    private function getActivityBreakdown(): array
    {
        return [
            [
                'name' => 'Customer Order Taking',
                'category' => 'Customer Service',
                'total_logs' => 234,
                'total_time' => 2808, // minutes
                'avg_duration' => 12,
                'estimated_duration' => 15,
                'efficiency' => 125,
                'completion_rate' => 98
            ],
            [
                'name' => 'Making Beyaynet',
                'category' => 'Food Preparation',
                'total_logs' => 45,
                'total_time' => 5175, // minutes
                'avg_duration' => 115,
                'estimated_duration' => 120,
                'efficiency' => 104,
                'completion_rate' => 96
            ],
            [
                'name' => 'Table Service Setup',
                'category' => 'Service Preparation',
                'total_logs' => 67,
                'total_time' => 2814, // minutes
                'avg_duration' => 42,
                'estimated_duration' => 45,
                'efficiency' => 107,
                'completion_rate' => 94
            ],
            [
                'name' => 'Washing Coffee Filter',
                'category' => 'Equipment Maintenance',
                'total_logs' => 28,
                'total_time' => 2380, // minutes
                'avg_duration' => 85,
                'estimated_duration' => 90,
                'efficiency' => 106,
                'completion_rate' => 100
            ],
            [
                'name' => 'Roasting Coffee Beans',
                'category' => 'Food Preparation',
                'total_logs' => 12,
                'total_time' => 2100, // minutes
                'avg_duration' => 175,
                'estimated_duration' => 180,
                'efficiency' => 103,
                'completion_rate' => 92
            ],
            [
                'name' => 'Inventory Count',
                'category' => 'Administrative',
                'total_logs' => 89,
                'total_time' => 5162, // minutes
                'avg_duration' => 58,
                'estimated_duration' => 60,
                'efficiency' => 103,
                'completion_rate' => 91
            ]
        ];
    }

    private function getDepartmentComparison(): array
    {
        return [
            [
                'name' => 'Kitchen',
                'staff_count' => 8,
                'activities_logged' => 456,
                'total_time' => 12480, // minutes
                'avg_efficiency' => 91,
                'completion_rate' => 89,
                'most_common_activity' => 'Making Beyaynet',
                'peak_hours' => '11:00-14:00'
            ],
            [
                'name' => 'Front of House',
                'staff_count' => 12,
                'activities_logged' => 678,
                'total_time' => 8136, // minutes
                'avg_efficiency' => 94,
                'completion_rate' => 95,
                'most_common_activity' => 'Customer Order Taking',
                'peak_hours' => '18:00-21:00'
            ],
            [
                'name' => 'Bar',
                'staff_count' => 4,
                'activities_logged' => 89,
                'total_time' => 2403, // minutes
                'avg_efficiency' => 87,
                'completion_rate' => 83,
                'most_common_activity' => 'Beverage Preparation',
                'peak_hours' => '19:00-22:00'
            ],
            [
                'name' => 'Management',
                'staff_count' => 3,
                'activities_logged' => 24,
                'total_time' => 816, // minutes
                'avg_efficiency' => 76,
                'completion_rate' => 75,
                'most_common_activity' => 'Staff Supervision',
                'peak_hours' => '09:00-12:00'
            ]
        ];
    }

    private function getTimeAnalysis(): array
    {
        return [
            'hourly_distribution' => [
                '08:00' => 45,
                '09:00' => 67,
                '10:00' => 89,
                '11:00' => 123,
                '12:00' => 156,
                '13:00' => 178,
                '14:00' => 189,
                '15:00' => 167,
                '16:00' => 145,
                '17:00' => 134,
                '18:00' => 167,
                '19:00' => 189,
                '20:00' => 156,
                '21:00' => 123,
                '22:00' => 89
            ],
            'daily_trends' => [
                'Monday' => ['activities' => 234, 'efficiency' => 87],
                'Tuesday' => ['activities' => 267, 'efficiency' => 91],
                'Wednesday' => ['activities' => 289, 'efficiency' => 89],
                'Thursday' => ['activities' => 298, 'efficiency' => 93],
                'Friday' => ['activities' => 312, 'efficiency' => 95],
                'Saturday' => ['activities' => 345, 'efficiency' => 88],
                'Sunday' => ['activities' => 298, 'efficiency' => 86]
            ],
            'monthly_comparison' => [
                'current_month' => ['activities' => 1247, 'efficiency' => 89],
                'previous_month' => ['activities' => 1156, 'efficiency' => 86],
                'growth' => ['activities' => 7.9, 'efficiency' => 3.5]
            ]
        ];
    }

    private function getTrendsData(): array
    {
        return [
            'last_30_days' => [
                ['date' => '2024-01-01', 'activities' => 45, 'efficiency' => 87],
                ['date' => '2024-01-02', 'activities' => 52, 'efficiency' => 89],
                ['date' => '2024-01-03', 'activities' => 48, 'efficiency' => 91],
                ['date' => '2024-01-04', 'activities' => 56, 'efficiency' => 88],
                ['date' => '2024-01-05', 'activities' => 61, 'efficiency' => 92],
                ['date' => '2024-01-06', 'activities' => 58, 'efficiency' => 90],
                ['date' => '2024-01-07', 'activities' => 63, 'efficiency' => 93],
                ['date' => '2024-01-08', 'activities' => 59, 'efficiency' => 89],
                ['date' => '2024-01-09', 'activities' => 67, 'efficiency' => 91],
                ['date' => '2024-01-10', 'activities' => 64, 'efficiency' => 94],
                ['date' => '2024-01-11', 'activities' => 71, 'efficiency' => 92],
                ['date' => '2024-01-12', 'activities' => 68, 'efficiency' => 90],
                ['date' => '2024-01-13', 'activities' => 73, 'efficiency' => 95],
                ['date' => '2024-01-14', 'activities' => 69, 'efficiency' => 88],
                ['date' => '2024-01-15', 'activities' => 76, 'efficiency' => 93],
                ['date' => '2024-01-16', 'activities' => 72, 'efficiency' => 91]
            ]
        ];
    }

    private function getPeakHours(): array
    {
        return [
            ['hour' => '14:00', 'activities' => 189, 'efficiency' => 94],
            ['hour' => '19:00', 'activities' => 189, 'efficiency' => 91],
            ['hour' => '13:00', 'activities' => 178, 'efficiency' => 93],
            ['hour' => '15:00', 'activities' => 167, 'efficiency' => 89],
            ['hour' => '18:00', 'activities' => 167, 'efficiency' => 92]
        ];
    }

    private function getStaffMember(int $id): array
    {
        return [
            'id' => $id,
            'name' => 'Alemayehu Tadesse',
            'role' => 'Head Chef',
            'department' => 'Kitchen',
            'hire_date' => '2023-03-15',
            'email' => 'alemayehu@restaurant.com',
            'phone' => '+251-911-123456'
        ];
    }

    private function getStaffStats(int $staffId): array
    {
        return [
            'total_activities' => 89,
            'total_time' => 2340, // minutes
            'avg_efficiency' => 94,
            'completion_rate' => 87,
            'best_activity' => 'Making Beyaynet',
            'improvement_area' => 'Inventory Count',
            'weekly_hours' => 39,
            'monthly_activities' => 356
        ];
    }

    private function getStaffActivities(int $staffId): array
    {
        return [
            [
                'name' => 'Making Beyaynet',
                'logs' => 23,
                'total_time' => 2645,
                'avg_time' => 115,
                'efficiency' => 96
            ],
            [
                'name' => 'Roasting Coffee Beans',
                'logs' => 8,
                'total_time' => 1400,
                'avg_time' => 175,
                'efficiency' => 103
            ],
            [
                'name' => 'Inventory Count',
                'logs' => 12,
                'total_time' => 696,
                'avg_time' => 58,
                'efficiency' => 83
            ]
        ];
    }

    private function getStaffTrends(int $staffId): array
    {
        return [
            'last_7_days' => [
                ['date' => '2024-01-10', 'activities' => 6, 'efficiency' => 92],
                ['date' => '2024-01-11', 'activities' => 8, 'efficiency' => 94],
                ['date' => '2024-01-12', 'activities' => 7, 'efficiency' => 91],
                ['date' => '2024-01-13', 'activities' => 9, 'efficiency' => 96],
                ['date' => '2024-01-14', 'activities' => 5, 'efficiency' => 89],
                ['date' => '2024-01-15', 'activities' => 8, 'efficiency' => 95],
                ['date' => '2024-01-16', 'activities' => 7, 'efficiency' => 93]
            ]
        ];
    }
}
