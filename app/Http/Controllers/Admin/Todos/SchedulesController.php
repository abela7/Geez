<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Todos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class SchedulesController extends Controller
{
    /**
     * Display a listing of schedules.
     */
    public function index(): View
    {
        // Mock data for schedules
        $schedules = $this->getMockSchedules();
        
        // Mock data for filters
        $frequencies = $this->getFrequencies();
        $statuses = $this->getStatuses();

        return view('admin.todos.schedules.index', compact('schedules', 'frequencies', 'statuses'));
    }

    /**
     * Show the form for creating a new schedule.
     */
    public function create(): View
    {
        $frequencies = $this->getFrequencies();
        $templates = $this->getMockTemplates();
        $staff = $this->getMockStaff();

        return view('admin.todos.schedules.create', compact('frequencies', 'templates', 'staff'));
    }

    /**
     * Store a newly created schedule.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'frequency_type' => 'required|in:hourly,daily,weekly,monthly,custom',
            'frequency_value' => 'required|integer|min:1',
            'specific_time' => 'nullable|string',
            'days_of_week' => 'nullable|array',
            'days_of_month' => 'nullable|array',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'template_id' => 'nullable|integer',
            'assigned_staff' => 'nullable|array',
            'auto_assign' => 'boolean',
            'is_active' => 'boolean'
        ]);

        return response()->json([
            'success' => true,
            'message' => __('todos.schedules.schedule_created_successfully'),
            'schedule' => [
                'id' => rand(100, 999),
                'name' => $request->name,
                'description' => $request->description,
                'frequency_type' => $request->frequency_type,
                'frequency_value' => $request->frequency_value,
                'is_active' => $request->is_active ?? true,
                'created_at' => now()->toISOString()
            ]
        ]);
    }

    /**
     * Display the specified schedule.
     */
    public function show(int $schedule): View
    {
        $scheduleData = $this->getMockSchedule($schedule);
        return view('admin.todos.schedules.show', compact('scheduleData'));
    }

    /**
     * Show the form for editing the specified schedule.
     */
    public function edit(int $schedule): View
    {
        $scheduleData = $this->getMockSchedule($schedule);
        $frequencies = $this->getFrequencies();
        $templates = $this->getMockTemplates();
        $staff = $this->getMockStaff();

        return view('admin.todos.schedules.edit', compact('scheduleData', 'frequencies', 'templates', 'staff'));
    }

    /**
     * Update the specified schedule.
     */
    public function update(Request $request, int $schedule): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'frequency_type' => 'required|in:hourly,daily,weekly,monthly,custom',
            'frequency_value' => 'required|integer|min:1',
            'specific_time' => 'nullable|string',
            'days_of_week' => 'nullable|array',
            'days_of_month' => 'nullable|array',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'template_id' => 'nullable|integer',
            'assigned_staff' => 'nullable|array',
            'auto_assign' => 'boolean',
            'is_active' => 'boolean'
        ]);

        return response()->json([
            'success' => true,
            'message' => __('todos.schedules.schedule_updated_successfully')
        ]);
    }

    /**
     * Remove the specified schedule.
     */
    public function destroy(int $schedule): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => __('todos.schedules.schedule_deleted_successfully')
        ]);
    }

    /**
     * Activate the specified schedule.
     */
    public function activate(int $schedule): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => __('todos.schedules.schedule_activated_successfully')
        ]);
    }

    /**
     * Deactivate the specified schedule.
     */
    public function deactivate(int $schedule): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => __('todos.schedules.schedule_deactivated_successfully')
        ]);
    }

    private function getMockSchedules(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Every 2 Hours - Equipment Check',
                'description' => 'Check all kitchen equipment every 2 hours during operating hours',
                'frequency_type' => 'hourly',
                'frequency_value' => 2,
                'frequency_display' => 'Every 2 hours',
                'specific_time' => null,
                'days_of_week' => [],
                'days_of_month' => [],
                'start_date' => '2024-01-01',
                'end_date' => null,
                'template_id' => 1,
                'template_name' => 'Equipment Safety Check',
                'assigned_staff' => [1, 2],
                'staff_names' => ['Alemayehu Tadesse', 'Meron Gebremedhin'],
                'auto_assign' => true,
                'is_active' => true,
                'created_at' => '2024-01-10 08:00:00',
                'updated_at' => '2024-01-15 10:30:00',
                'next_run' => '2024-01-16 14:00:00',
                'last_run' => '2024-01-16 12:00:00',
                'total_runs' => 156,
                'successful_runs' => 148
            ],
            [
                'id' => 2,
                'name' => 'Daily Opening - 8:00 AM',
                'description' => 'Daily opening procedures every morning at 8:00 AM',
                'frequency_type' => 'daily',
                'frequency_value' => 1,
                'frequency_display' => 'Daily at 8:00 AM',
                'specific_time' => '08:00',
                'days_of_week' => [],
                'days_of_month' => [],
                'start_date' => '2024-01-01',
                'end_date' => null,
                'template_id' => 2,
                'template_name' => 'Daily Opening Checklist',
                'assigned_staff' => [1],
                'staff_names' => ['Alemayehu Tadesse'],
                'auto_assign' => true,
                'is_active' => true,
                'created_at' => '2024-01-08 07:00:00',
                'updated_at' => '2024-01-14 09:15:00',
                'next_run' => '2024-01-17 08:00:00',
                'last_run' => '2024-01-16 08:00:00',
                'total_runs' => 15,
                'successful_runs' => 14
            ],
            [
                'id' => 3,
                'name' => 'Weekly Inventory - Mondays 9:00 AM',
                'description' => 'Weekly inventory check every Monday morning',
                'frequency_type' => 'weekly',
                'frequency_value' => 1,
                'frequency_display' => 'Weekly on Mondays at 9:00 AM',
                'specific_time' => '09:00',
                'days_of_week' => [1], // Monday
                'days_of_month' => [],
                'start_date' => '2024-01-01',
                'end_date' => null,
                'template_id' => 3,
                'template_name' => 'Weekly Inventory Review',
                'assigned_staff' => [3],
                'staff_names' => ['Yonas Assefa'],
                'auto_assign' => true,
                'is_active' => true,
                'created_at' => '2024-01-05 15:00:00',
                'updated_at' => '2024-01-12 16:45:00',
                'next_run' => '2024-01-22 09:00:00',
                'last_run' => '2024-01-15 09:00:00',
                'total_runs' => 3,
                'successful_runs' => 3
            ],
            [
                'id' => 4,
                'name' => 'Monthly Deep Clean - 1st Sunday',
                'description' => 'Monthly deep cleaning on the first Sunday of each month',
                'frequency_type' => 'monthly',
                'frequency_value' => 1,
                'frequency_display' => 'Monthly on 1st Sunday at 10:00 AM',
                'specific_time' => '10:00',
                'days_of_week' => [0], // Sunday
                'days_of_month' => [1], // First occurrence
                'start_date' => '2024-01-01',
                'end_date' => null,
                'template_id' => 4,
                'template_name' => 'Monthly Deep Clean',
                'assigned_staff' => [1, 2, 3, 4],
                'staff_names' => ['All Staff'],
                'auto_assign' => true,
                'is_active' => true,
                'created_at' => '2023-12-15 10:00:00',
                'updated_at' => '2024-01-05 11:30:00',
                'next_run' => '2024-02-04 10:00:00',
                'last_run' => '2024-01-07 10:00:00',
                'total_runs' => 2,
                'successful_runs' => 2
            ],
            [
                'id' => 5,
                'name' => 'Custom - Every 3 Days at 6:00 PM',
                'description' => 'Custom schedule for special cleaning every 3 days',
                'frequency_type' => 'custom',
                'frequency_value' => 3,
                'frequency_display' => 'Every 3 days at 6:00 PM',
                'specific_time' => '18:00',
                'days_of_week' => [],
                'days_of_month' => [],
                'start_date' => '2024-01-01',
                'end_date' => '2024-03-31',
                'template_id' => 5,
                'template_name' => 'Special Equipment Maintenance',
                'assigned_staff' => [2],
                'staff_names' => ['Meron Gebremedhin'],
                'auto_assign' => false,
                'is_active' => false,
                'created_at' => '2024-01-01 20:00:00',
                'updated_at' => '2024-01-10 14:20:00',
                'next_run' => '2024-01-19 18:00:00',
                'last_run' => '2024-01-16 18:00:00',
                'total_runs' => 5,
                'successful_runs' => 4
            ]
        ];
    }

    private function getMockSchedule(int $id): array
    {
        return [
            'id' => $id,
            'name' => 'Every 2 Hours - Equipment Check',
            'description' => 'Check all kitchen equipment every 2 hours during operating hours',
            'frequency_type' => 'hourly',
            'frequency_value' => 2,
            'frequency_display' => 'Every 2 hours',
            'specific_time' => null,
            'days_of_week' => [],
            'days_of_month' => [],
            'start_date' => '2024-01-01',
            'end_date' => null,
            'template_id' => 1,
            'template_name' => 'Equipment Safety Check',
            'assigned_staff' => [1, 2],
            'staff_names' => ['Alemayehu Tadesse', 'Meron Gebremedhin'],
            'auto_assign' => true,
            'is_active' => true,
            'created_at' => '2024-01-10 08:00:00',
            'updated_at' => '2024-01-15 10:30:00',
            'next_run' => '2024-01-16 14:00:00',
            'last_run' => '2024-01-16 12:00:00',
            'total_runs' => 156,
            'successful_runs' => 148,
            'recent_runs' => [
                ['date' => '2024-01-16 12:00:00', 'status' => 'completed', 'staff' => 'Alemayehu Tadesse', 'duration' => 15],
                ['date' => '2024-01-16 10:00:00', 'status' => 'completed', 'staff' => 'Meron Gebremedhin', 'duration' => 18],
                ['date' => '2024-01-16 08:00:00', 'status' => 'completed', 'staff' => 'Alemayehu Tadesse', 'duration' => 12],
                ['date' => '2024-01-16 06:00:00', 'status' => 'missed', 'staff' => null, 'duration' => null],
                ['date' => '2024-01-16 04:00:00', 'status' => 'completed', 'staff' => 'Meron Gebremedhin', 'duration' => 20]
            ]
        ];
    }

    private function getFrequencies(): array
    {
        return [
            'hourly' => 'Hourly',
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'monthly' => 'Monthly',
            'custom' => 'Custom'
        ];
    }

    private function getStatuses(): array
    {
        return [
            'all' => 'All',
            'active' => 'Active',
            'inactive' => 'Inactive'
        ];
    }

    private function getMockTemplates(): array
    {
        return [
            ['id' => 1, 'name' => 'Equipment Safety Check'],
            ['id' => 2, 'name' => 'Daily Opening Checklist'],
            ['id' => 3, 'name' => 'Weekly Inventory Review'],
            ['id' => 4, 'name' => 'Monthly Deep Clean'],
            ['id' => 5, 'name' => 'Special Equipment Maintenance']
        ];
    }

    private function getMockStaff(): array
    {
        return [
            ['id' => 1, 'name' => 'Alemayehu Tadesse', 'role' => 'Waiter'],
            ['id' => 2, 'name' => 'Meron Gebremedhin', 'role' => 'Kitchen Staff'],
            ['id' => 3, 'name' => 'Yonas Assefa', 'role' => 'Manager'],
            ['id' => 4, 'name' => 'Sara Hailu', 'role' => 'Cashier']
        ];
    }
}
