<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Activities;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoggingController extends Controller
{
    /**
     * Display the activity logging page.
     */
    public function index(): View
    {
        $availableActivities = $this->getAvailableActivities();
        $currentActivities = $this->getMockCurrentActivities();
        $todaysStats = $this->getTodaysStats();
        $recentActivities = $this->getRecentActivities();

        return view('admin.activities.logging.index', compact(
            'availableActivities',
            'currentActivities',
            'todaysStats',
            'recentActivities'
        ));
    }

    /**
     * Start an activity.
     */
    public function startActivity(Request $request): JsonResponse
    {
        $request->validate([
            'activity_id' => 'required|integer',
            'notes' => 'nullable|string|max:500',
        ]);

        // In a real implementation, this would create a new activity log entry
        $activity = $this->getActivityById($request->activity_id);

        return response()->json([
            'success' => true,
            'message' => __('activities.logging.activity_started'),
            'activity_log' => [
                'id' => rand(1000, 9999),
                'activity_id' => $request->activity_id,
                'activity_name' => $activity['name'],
                'started_at' => now()->toISOString(),
                'notes' => $request->notes,
                'status' => 'in_progress',
            ],
        ]);
    }

    /**
     * Stop an activity.
     */
    public function stopActivity(Request $request): JsonResponse
    {
        $request->validate([
            'activity_log_id' => 'required|integer',
            'notes' => 'nullable|string|max:500',
        ]);

        // In a real implementation, this would update the activity log entry
        return response()->json([
            'success' => true,
            'message' => __('activities.logging.activity_stopped'),
            'activity_log' => [
                'id' => $request->activity_log_id,
                'stopped_at' => now()->toISOString(),
                'duration' => rand(15, 180), // minutes
                'notes' => $request->notes,
                'status' => 'completed',
            ],
        ]);
    }

    /**
     * Pause an activity.
     */
    public function pauseActivity(Request $request): JsonResponse
    {
        $request->validate([
            'activity_log_id' => 'required|integer',
        ]);

        return response()->json([
            'success' => true,
            'message' => __('activities.logging.activity_paused'),
            'activity_log' => [
                'id' => $request->activity_log_id,
                'status' => 'paused',
                'paused_at' => now()->toISOString(),
            ],
        ]);
    }

    /**
     * Resume an activity.
     */
    public function resumeActivity(Request $request): JsonResponse
    {
        $request->validate([
            'activity_log_id' => 'required|integer',
        ]);

        return response()->json([
            'success' => true,
            'message' => __('activities.logging.activity_resumed'),
            'activity_log' => [
                'id' => $request->activity_log_id,
                'status' => 'in_progress',
                'resumed_at' => now()->toISOString(),
            ],
        ]);
    }

    /**
     * Get current activities for the logged-in user.
     */
    public function getCurrentActivities(): JsonResponse
    {
        $currentActivities = $this->getMockCurrentActivities();

        return response()->json([
            'success' => true,
            'current_activities' => $currentActivities,
        ]);
    }

    /**
     * Get activity history for the logged-in user.
     */
    public function getHistory(Request $request): JsonResponse
    {
        $date = $request->get('date', now()->format('Y-m-d'));
        $recentActivities = $this->getRecentActivities($date);

        return response()->json([
            'success' => true,
            'activities' => $recentActivities,
        ]);
    }

    private function getAvailableActivities(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Making Beyaynet',
                'description' => 'Prepare traditional Ethiopian mixed vegetable platter',
                'category' => 'Food Preparation',
                'department' => 'Kitchen',
                'estimated_duration' => 120,
                'difficulty_level' => 'medium',
                'requires_equipment' => true,
                'equipment_list' => 'Large pot, wooden spoon, cutting board, knives',
            ],
            [
                'id' => 2,
                'name' => 'Washing Coffee Filter',
                'description' => 'Clean and maintain coffee brewing equipment',
                'category' => 'Equipment Maintenance',
                'department' => 'Kitchen',
                'estimated_duration' => 90,
                'difficulty_level' => 'easy',
                'requires_equipment' => true,
                'equipment_list' => 'Cleaning solution, brushes, clean water',
            ],
            [
                'id' => 3,
                'name' => 'Roasting Coffee Beans',
                'description' => 'Traditional Ethiopian coffee roasting process',
                'category' => 'Food Preparation',
                'department' => 'Kitchen',
                'estimated_duration' => 180,
                'difficulty_level' => 'hard',
                'requires_equipment' => true,
                'equipment_list' => 'Roasting pan, wooden spoon, timer, storage containers',
            ],
            [
                'id' => 4,
                'name' => 'Table Service Setup',
                'description' => 'Prepare dining tables with proper place settings',
                'category' => 'Service Preparation',
                'department' => 'Front of House',
                'estimated_duration' => 45,
                'difficulty_level' => 'easy',
                'requires_equipment' => false,
                'equipment_list' => null,
            ],
            [
                'id' => 5,
                'name' => 'Customer Order Taking',
                'description' => 'Take customer orders accurately and efficiently',
                'category' => 'Customer Service',
                'department' => 'Front of House',
                'estimated_duration' => 15,
                'difficulty_level' => 'easy',
                'requires_equipment' => false,
                'equipment_list' => null,
            ],
            [
                'id' => 6,
                'name' => 'Inventory Count',
                'description' => 'Count and record all kitchen ingredients and supplies',
                'category' => 'Administrative',
                'department' => 'Kitchen',
                'estimated_duration' => 60,
                'difficulty_level' => 'medium',
                'requires_equipment' => false,
                'equipment_list' => null,
            ],
        ];
    }

    private function getMockCurrentActivities(): array
    {
        return [
            [
                'id' => 1001,
                'activity_id' => 1,
                'activity_name' => 'Making Beyaynet',
                'started_at' => now()->subMinutes(45)->toISOString(),
                'elapsed_time' => 45, // minutes
                'estimated_duration' => 120,
                'status' => 'in_progress',
                'notes' => 'Started preparation of vegetables',
                'progress_percentage' => 37,
            ],
            [
                'id' => 1002,
                'activity_id' => 4,
                'activity_name' => 'Table Service Setup',
                'started_at' => now()->subMinutes(20)->toISOString(),
                'elapsed_time' => 20, // minutes
                'estimated_duration' => 45,
                'status' => 'paused',
                'notes' => 'Paused to help with customer inquiry',
                'progress_percentage' => 44,
            ],
        ];
    }

    private function getTodaysStats(): array
    {
        return [
            'total_time' => 285, // minutes
            'activities_completed' => 8,
            'activities_in_progress' => 2,
            'efficiency_score' => 87, // percentage
            'on_time_completion' => 75, // percentage
            'break_time' => 45, // minutes
        ];
    }

    private function getRecentActivities(?string $date = null): array
    {
        return [
            [
                'id' => 2001,
                'activity_name' => 'Customer Order Taking',
                'started_at' => '2024-01-16 18:30:00',
                'completed_at' => '2024-01-16 18:42:00',
                'duration' => 12, // minutes
                'estimated_duration' => 15,
                'status' => 'completed',
                'efficiency' => 125, // percentage (completed faster than estimated)
                'notes' => 'Large table order, well organized',
            ],
            [
                'id' => 2002,
                'activity_name' => 'Table Service Setup',
                'started_at' => '2024-01-16 17:45:00',
                'completed_at' => '2024-01-16 18:25:00',
                'duration' => 40, // minutes
                'estimated_duration' => 45,
                'status' => 'completed',
                'efficiency' => 112, // percentage
                'notes' => 'Set up for evening service',
            ],
            [
                'id' => 2003,
                'activity_name' => 'Washing Coffee Filter',
                'started_at' => '2024-01-16 16:00:00',
                'completed_at' => '2024-01-16 17:30:00',
                'duration' => 90, // minutes
                'estimated_duration' => 90,
                'status' => 'completed',
                'efficiency' => 100, // percentage
                'notes' => 'Deep cleaning of all equipment',
            ],
            [
                'id' => 2004,
                'activity_name' => 'Inventory Count',
                'started_at' => '2024-01-16 14:30:00',
                'completed_at' => '2024-01-16 15:45:00',
                'duration' => 75, // minutes
                'estimated_duration' => 60,
                'status' => 'completed',
                'efficiency' => 80, // percentage (took longer than estimated)
                'notes' => 'Found discrepancies that needed investigation',
            ],
            [
                'id' => 2005,
                'activity_name' => 'Roasting Coffee Beans',
                'started_at' => '2024-01-16 11:00:00',
                'completed_at' => '2024-01-16 14:15:00',
                'duration' => 195, // minutes
                'estimated_duration' => 180,
                'status' => 'completed',
                'efficiency' => 92, // percentage
                'notes' => 'Large batch for evening service',
            ],
        ];
    }

    private function getActivityById(int $id): array
    {
        $activities = $this->getAvailableActivities();

        return collect($activities)->firstWhere('id', $id) ?? [];
    }
}
