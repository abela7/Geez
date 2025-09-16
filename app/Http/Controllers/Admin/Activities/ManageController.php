<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Activities;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class ManageController extends Controller
{
    /**
     * Display a listing of activities.
     */
    public function index(): View
    {
        // Mock data for activities
        $activities = $this->getMockActivities();
        $categories = $this->getCategories();
        $departments = $this->getDepartments();

        return view('admin.activities.manage.index', compact('activities', 'categories', 'departments'));
    }

    /**
     * Show the form for creating a new activity.
     */
    public function create(): View
    {
        $categories = $this->getCategories();
        $departments = $this->getDepartments();

        return view('admin.activities.manage.create', compact('categories', 'departments'));
    }

    /**
     * Store a newly created activity.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'department' => 'required|string',
            'estimated_duration' => 'required|integer|min:1',
            'difficulty_level' => 'required|in:easy,medium,hard',
            'requires_equipment' => 'boolean',
            'equipment_list' => 'nullable|string',
            'instructions' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        return response()->json([
            'success' => true,
            'message' => __('activities.manage.activity_created_successfully'),
            'activity' => [
                'id' => rand(100, 999),
                'name' => $request->name,
                'description' => $request->description,
                'category' => $request->category,
                'department' => $request->department,
                'estimated_duration' => $request->estimated_duration,
                'difficulty_level' => $request->difficulty_level,
                'is_active' => $request->is_active ?? true,
                'created_at' => now()->toISOString()
            ]
        ]);
    }

    /**
     * Display the specified activity.
     */
    public function show(int $activity): View
    {
        $activityData = $this->getMockActivity($activity);
        return view('admin.activities.manage.show', compact('activityData'));
    }

    /**
     * Show the form for editing the specified activity.
     */
    public function edit(int $activity): View
    {
        $activityData = $this->getMockActivity($activity);
        $categories = $this->getCategories();
        $departments = $this->getDepartments();

        return view('admin.activities.manage.edit', compact('activityData', 'categories', 'departments'));
    }

    /**
     * Update the specified activity.
     */
    public function update(Request $request, int $activity): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'department' => 'required|string',
            'estimated_duration' => 'required|integer|min:1',
            'difficulty_level' => 'required|in:easy,medium,hard',
            'requires_equipment' => 'boolean',
            'equipment_list' => 'nullable|string',
            'instructions' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        return response()->json([
            'success' => true,
            'message' => __('activities.manage.activity_updated_successfully')
        ]);
    }

    /**
     * Remove the specified activity.
     */
    public function destroy(int $activity): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => __('activities.manage.activity_deleted_successfully')
        ]);
    }

    /**
     * Duplicate the specified activity.
     */
    public function duplicate(int $activity): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => __('activities.manage.activity_duplicated_successfully'),
            'activity' => [
                'id' => rand(100, 999),
                'name' => 'Copy of Activity',
                'created_at' => now()->toISOString()
            ]
        ]);
    }

    /**
     * Show the settings page for managing categories and departments.
     */
    public function settings(): View
    {
        // Mock data for categories
        $categories = [
            ['id' => 1, 'name' => 'Food Preparation', 'key' => 'food_preparation', 'description' => 'Activities related to cooking and food preparation', 'activities_count' => 15, 'is_active' => true],
            ['id' => 2, 'name' => 'Equipment Maintenance', 'key' => 'equipment_maintenance', 'description' => 'Cleaning and maintaining kitchen equipment', 'activities_count' => 8, 'is_active' => true],
            ['id' => 3, 'name' => 'Service Preparation', 'key' => 'service_preparation', 'description' => 'Setting up for customer service', 'activities_count' => 12, 'is_active' => true],
            ['id' => 4, 'name' => 'Customer Service', 'key' => 'customer_service', 'description' => 'Direct customer interaction activities', 'activities_count' => 6, 'is_active' => true],
            ['id' => 5, 'name' => 'Cleaning', 'key' => 'cleaning', 'description' => 'General cleaning and sanitation tasks', 'activities_count' => 10, 'is_active' => true],
            ['id' => 6, 'name' => 'Inventory', 'key' => 'inventory', 'description' => 'Stock management and inventory tasks', 'activities_count' => 4, 'is_active' => true],
            ['id' => 7, 'name' => 'Administrative', 'key' => 'admin', 'description' => 'Administrative and management tasks', 'activities_count' => 3, 'is_active' => true],
        ];

        // Mock data for departments
        $departments = [
            ['id' => 1, 'name' => 'Kitchen', 'key' => 'kitchen', 'description' => 'Food preparation and cooking area', 'activities_count' => 25, 'staff_count' => 8, 'is_active' => true],
            ['id' => 2, 'name' => 'Front of House', 'key' => 'front_of_house', 'description' => 'Customer service and dining area', 'activities_count' => 18, 'staff_count' => 12, 'is_active' => true],
            ['id' => 3, 'name' => 'Bar', 'key' => 'bar', 'description' => 'Beverage preparation and service', 'activities_count' => 10, 'staff_count' => 4, 'is_active' => true],
            ['id' => 4, 'name' => 'Management', 'key' => 'management', 'description' => 'Administrative and supervisory tasks', 'activities_count' => 5, 'staff_count' => 3, 'is_active' => true],
        ];

        return view('admin.activities.manage.settings', compact('categories', 'departments'));
    }

    /**
     * Store a new category.
     */
    public function storeCategory(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500'
        ]);

        return response()->json([
            'success' => true,
            'message' => __('activities.manage.category_created_successfully'),
            'category' => [
                'id' => rand(100, 999),
                'name' => $request->name,
                'key' => strtolower(str_replace(' ', '_', $request->name)),
                'description' => $request->description,
                'activities_count' => 0,
                'is_active' => true
            ]
        ]);
    }

    /**
     * Update a category.
     */
    public function updateCategory(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500'
        ]);

        return response()->json([
            'success' => true,
            'message' => __('activities.manage.category_updated_successfully')
        ]);
    }

    /**
     * Delete a category.
     */
    public function deleteCategory(string $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => __('activities.manage.category_deleted_successfully')
        ]);
    }

    /**
     * Store a new department.
     */
    public function storeDepartment(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500'
        ]);

        return response()->json([
            'success' => true,
            'message' => __('activities.manage.department_created_successfully'),
            'department' => [
                'id' => rand(100, 999),
                'name' => $request->name,
                'key' => strtolower(str_replace(' ', '_', $request->name)),
                'description' => $request->description,
                'activities_count' => 0,
                'staff_count' => 0,
                'is_active' => true
            ]
        ]);
    }

    /**
     * Update a department.
     */
    public function updateDepartment(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500'
        ]);

        return response()->json([
            'success' => true,
            'message' => __('activities.manage.department_updated_successfully')
        ]);
    }

    /**
     * Delete a department.
     */
    public function deleteDepartment(string $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => __('activities.manage.department_deleted_successfully')
        ]);
    }

    private function getMockActivities(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Making Beyaynet',
                'description' => 'Prepare traditional Ethiopian mixed vegetable platter with various lentils and vegetables',
                'category' => 'Food Preparation',
                'department' => 'Kitchen',
                'estimated_duration' => 120, // minutes
                'difficulty_level' => 'medium',
                'requires_equipment' => true,
                'equipment_list' => 'Large pot, wooden spoon, cutting board, knives',
                'instructions' => '1. Prepare all vegetables\n2. Cook lentils separately\n3. Combine and season\n4. Serve hot',
                'is_active' => true,
                'created_at' => '2024-01-10 08:00:00',
                'updated_at' => '2024-01-15 10:30:00',
                'total_logs' => 45,
                'average_actual_duration' => 115,
                'last_performed' => '2024-01-16 14:30:00',
                'assigned_staff_count' => 3
            ],
            [
                'id' => 2,
                'name' => 'Washing Coffee Filter',
                'description' => 'Clean and maintain coffee brewing equipment for optimal taste',
                'category' => 'Equipment Maintenance',
                'department' => 'Kitchen',
                'estimated_duration' => 90, // minutes
                'difficulty_level' => 'easy',
                'requires_equipment' => true,
                'equipment_list' => 'Cleaning solution, brushes, clean water',
                'instructions' => '1. Disassemble filter\n2. Soak in cleaning solution\n3. Scrub thoroughly\n4. Rinse and dry',
                'is_active' => true,
                'created_at' => '2024-01-08 07:00:00',
                'updated_at' => '2024-01-14 09:15:00',
                'total_logs' => 28,
                'average_actual_duration' => 85,
                'last_performed' => '2024-01-16 10:15:00',
                'assigned_staff_count' => 2
            ],
            [
                'id' => 3,
                'name' => 'Roasting Coffee Beans',
                'description' => 'Traditional Ethiopian coffee roasting process for fresh, aromatic coffee',
                'category' => 'Food Preparation',
                'department' => 'Kitchen',
                'estimated_duration' => 180, // minutes
                'difficulty_level' => 'hard',
                'requires_equipment' => true,
                'equipment_list' => 'Roasting pan, wooden spoon, timer, storage containers',
                'instructions' => '1. Heat roasting pan\n2. Add green beans\n3. Stir continuously\n4. Monitor color changes\n5. Cool and store',
                'is_active' => true,
                'created_at' => '2024-01-05 15:00:00',
                'updated_at' => '2024-01-12 16:45:00',
                'total_logs' => 12,
                'average_actual_duration' => 175,
                'last_performed' => '2024-01-15 16:00:00',
                'assigned_staff_count' => 1
            ],
            [
                'id' => 4,
                'name' => 'Table Service Setup',
                'description' => 'Prepare dining tables with proper place settings and decorations',
                'category' => 'Service Preparation',
                'department' => 'Front of House',
                'estimated_duration' => 45, // minutes
                'difficulty_level' => 'easy',
                'requires_equipment' => false,
                'equipment_list' => null,
                'instructions' => '1. Clean tables\n2. Set place settings\n3. Add decorations\n4. Check lighting',
                'is_active' => true,
                'created_at' => '2024-01-01 20:00:00',
                'updated_at' => '2024-01-10 14:20:00',
                'total_logs' => 67,
                'average_actual_duration' => 42,
                'last_performed' => '2024-01-16 17:30:00',
                'assigned_staff_count' => 4
            ],
            [
                'id' => 5,
                'name' => 'Inventory Count',
                'description' => 'Count and record all kitchen ingredients and supplies',
                'category' => 'Administrative',
                'department' => 'Kitchen',
                'estimated_duration' => 60, // minutes
                'difficulty_level' => 'medium',
                'requires_equipment' => false,
                'equipment_list' => null,
                'instructions' => '1. Count all items\n2. Record quantities\n3. Note low stock\n4. Update system',
                'is_active' => true,
                'created_at' => '2023-12-15 10:00:00',
                'updated_at' => '2024-01-05 11:30:00',
                'total_logs' => 89,
                'average_actual_duration' => 58,
                'last_performed' => '2024-01-16 09:00:00',
                'assigned_staff_count' => 2
            ],
            [
                'id' => 6,
                'name' => 'Customer Order Taking',
                'description' => 'Take customer orders accurately and efficiently',
                'category' => 'Customer Service',
                'department' => 'Front of House',
                'estimated_duration' => 15, // minutes per order
                'difficulty_level' => 'easy',
                'requires_equipment' => false,
                'equipment_list' => null,
                'instructions' => '1. Greet customer\n2. Present menu\n3. Take order\n4. Confirm details\n5. Submit to kitchen',
                'is_active' => true,
                'created_at' => '2024-01-01 08:00:00',
                'updated_at' => '2024-01-08 12:00:00',
                'total_logs' => 234,
                'average_actual_duration' => 12,
                'last_performed' => '2024-01-16 19:45:00',
                'assigned_staff_count' => 5
            ]
        ];
    }

    private function getMockActivity(int $id): array
    {
        return [
            'id' => $id,
            'name' => 'Making Beyaynet',
            'description' => 'Prepare traditional Ethiopian mixed vegetable platter with various lentils and vegetables',
            'category' => 'Food Preparation',
            'department' => 'Kitchen',
            'estimated_duration' => 120,
            'difficulty_level' => 'medium',
            'requires_equipment' => true,
            'equipment_list' => 'Large pot, wooden spoon, cutting board, knives',
            'instructions' => '1. Prepare all vegetables\n2. Cook lentils separately\n3. Combine and season\n4. Serve hot',
            'is_active' => true,
            'created_at' => '2024-01-10 08:00:00',
            'updated_at' => '2024-01-15 10:30:00',
            'total_logs' => 45,
            'average_actual_duration' => 115,
            'last_performed' => '2024-01-16 14:30:00',
            'assigned_staff_count' => 3,
            'assigned_staff' => [
                ['id' => 1, 'name' => 'Alemayehu Tadesse', 'role' => 'Head Chef'],
                ['id' => 2, 'name' => 'Meron Gebremedhin', 'role' => 'Kitchen Staff'],
                ['id' => 3, 'name' => 'Dawit Bekele', 'role' => 'Kitchen Staff']
            ],
            'recent_logs' => [
                ['date' => '2024-01-16 14:30:00', 'staff' => 'Alemayehu Tadesse', 'duration' => 118, 'status' => 'completed'],
                ['date' => '2024-01-16 11:15:00', 'staff' => 'Meron Gebremedhin', 'duration' => 125, 'status' => 'completed'],
                ['date' => '2024-01-15 16:00:00', 'staff' => 'Dawit Bekele', 'duration' => 110, 'status' => 'completed'],
                ['date' => '2024-01-15 13:30:00', 'staff' => 'Alemayehu Tadesse', 'duration' => 115, 'status' => 'completed'],
                ['date' => '2024-01-14 17:45:00', 'staff' => 'Meron Gebremedhin', 'duration' => 120, 'status' => 'completed']
            ]
        ];
    }

    private function getCategories(): array
    {
        return [
            'Food Preparation' => 'Food Preparation',
            'Equipment Maintenance' => 'Equipment Maintenance',
            'Service Preparation' => 'Service Preparation',
            'Customer Service' => 'Customer Service',
            'Administrative' => 'Administrative',
            'Cleaning' => 'Cleaning',
            'Inventory' => 'Inventory'
        ];
    }

    private function getDepartments(): array
    {
        return [
            'Kitchen' => 'Kitchen',
            'Front of House' => 'Front of House',
            'Bar' => 'Bar',
            'Management' => 'Management'
        ];
    }
}
