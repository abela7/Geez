<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Todos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class TemplatesController extends Controller
{
    /**
     * Display a listing of recurring templates.
     */
    public function index(): View
    {
        // Mock data for templates
        $templates = $this->getMockTemplates();
        
        // Mock data for filters
        $categories = $this->getCategories();
        $roles = $this->getRoles();
        $recurringTypes = $this->getRecurringTypes();

        return view('admin.todos.templates.index', compact('templates', 'categories', 'roles', 'recurringTypes'));
    }

    /**
     * Show the form for creating a new template.
     */
    public function create(): View
    {
        $categories = $this->getCategories();
        $roles = $this->getRoles();
        $recurringTypes = $this->getRecurringTypes();

        return view('admin.todos.templates.create', compact('categories', 'roles', 'recurringTypes'));
    }

    /**
     * Store a newly created template.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'assigned_role' => 'required|string',
            'recurring_type' => 'required|string',
            'estimated_duration' => 'required|integer|min:1',
            'priority' => 'required|in:normal,medium,high',
            'instructions' => 'nullable|array',
            'instructions.*' => 'string',
            'tags' => 'nullable|array',
            'tags.*' => 'string'
        ]);

        return response()->json([
            'success' => true,
            'message' => __('todos.templates.template_created_successfully'),
            'template' => [
                'id' => rand(100, 999),
                'name' => $request->name,
                'description' => $request->description,
                'category' => $request->category,
                'assigned_role' => $request->assigned_role,
                'recurring_type' => $request->recurring_type,
                'estimated_duration' => $request->estimated_duration,
                'priority' => $request->priority,
                'is_active' => true,
                'created_at' => now()->toISOString(),
                'usage_count' => 0,
                'completion_rate' => 0,
                'instructions' => $request->instructions ?? [],
                'tags' => $request->tags ?? []
            ]
        ]);
    }

    /**
     * Display the specified template.
     */
    public function show(int $template): View
    {
        $templateData = $this->getMockTemplate($template);
        return view('admin.todos.templates.show', compact('templateData'));
    }

    /**
     * Show the form for editing the specified template.
     */
    public function edit(int $template): View
    {
        $templateData = $this->getMockTemplate($template);
        $categories = $this->getCategories();
        $roles = $this->getRoles();
        $recurringTypes = $this->getRecurringTypes();

        return view('admin.todos.templates.edit', compact('templateData', 'categories', 'roles', 'recurringTypes'));
    }

    /**
     * Update the specified template.
     */
    public function update(Request $request, int $template): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'assigned_role' => 'required|string',
            'recurring_type' => 'required|string',
            'estimated_duration' => 'required|integer|min:1',
            'priority' => 'required|in:normal,medium,high',
            'instructions' => 'nullable|array',
            'instructions.*' => 'string',
            'tags' => 'nullable|array',
            'tags.*' => 'string',
            'is_active' => 'boolean'
        ]);

        return response()->json([
            'success' => true,
            'message' => __('todos.templates.template_updated_successfully')
        ]);
    }

    /**
     * Remove the specified template.
     */
    public function destroy(int $template): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => __('todos.templates.template_deleted_successfully')
        ]);
    }

    /**
     * Duplicate the specified template.
     */
    public function duplicate(int $template): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => __('todos.templates.template_duplicated_successfully'),
            'template' => [
                'id' => rand(100, 999),
                'name' => 'Copy of Daily Opening Checklist',
                'is_active' => false
            ]
        ]);
    }

    private function getMockTemplates(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Daily Opening Checklist',
                'description' => 'Essential tasks to be completed at the start of each day',
                'category' => 'opening_tasks',
                'assigned_role' => 'waiter',
                'recurring_type' => 'daily',
                'estimated_duration' => 30,
                'priority' => 'high',
                'is_active' => true,
                'created_at' => '2024-01-10 08:00:00',
                'updated_at' => '2024-01-15 10:30:00',
                'usage_count' => 45,
                'completion_rate' => 92,
                'instructions' => [
                    'Turn on all delivery devices and ensure they are charged',
                    'Check table cleanliness and arrange seating',
                    'Verify POS system is working properly',
                    'Update daily specials on menu boards',
                    'Check inventory levels for immediate needs'
                ],
                'tags' => ['opening', 'daily', 'essential']
            ],
            [
                'id' => 2,
                'name' => 'Kitchen Prep Morning Routine',
                'description' => 'Morning preparation tasks for kitchen staff',
                'category' => 'kitchen_prep',
                'assigned_role' => 'chef',
                'recurring_type' => 'daily',
                'estimated_duration' => 45,
                'priority' => 'high',
                'is_active' => true,
                'created_at' => '2024-01-08 07:00:00',
                'updated_at' => '2024-01-14 09:15:00',
                'usage_count' => 38,
                'completion_rate' => 95,
                'instructions' => [
                    'Check ingredient inventory and note shortages',
                    'Prepare injera batter for the day',
                    'Clean and sanitize all cooking stations',
                    'Verify equipment functionality',
                    'Prepare mise en place for lunch service'
                ],
                'tags' => ['kitchen', 'prep', 'morning']
            ],
            [
                'id' => 3,
                'name' => 'Weekly Inventory Review',
                'description' => 'Comprehensive weekly inventory check and ordering',
                'category' => 'administrative',
                'assigned_role' => 'manager',
                'recurring_type' => 'weekly',
                'estimated_duration' => 120,
                'priority' => 'medium',
                'is_active' => true,
                'created_at' => '2024-01-05 15:00:00',
                'updated_at' => '2024-01-12 16:45:00',
                'usage_count' => 12,
                'completion_rate' => 88,
                'instructions' => [
                    'Review current inventory levels across all categories',
                    'Identify items running low or out of stock',
                    'Calculate usage patterns and forecast needs',
                    'Place orders with approved suppliers',
                    'Update inventory management system',
                    'Schedule delivery confirmations'
                ],
                'tags' => ['inventory', 'weekly', 'management']
            ]
        ];
    }

    private function getMockTemplate(int $id): array
    {
        return [
            'id' => $id,
            'name' => 'Daily Opening Checklist',
            'description' => 'Essential tasks to be completed at the start of each day',
            'category' => 'opening_tasks',
            'assigned_role' => 'waiter',
            'recurring_type' => 'daily',
            'estimated_duration' => 30,
            'priority' => 'high',
            'is_active' => true,
            'created_at' => '2024-01-10 08:00:00',
            'updated_at' => '2024-01-15 10:30:00',
            'usage_count' => 45,
            'completion_rate' => 92,
            'average_completion_time' => 28,
            'instructions' => [
                'Turn on all delivery devices and ensure they are charged',
                'Check table cleanliness and arrange seating',
                'Verify POS system is working properly',
                'Update daily specials on menu boards',
                'Check inventory levels for immediate needs'
            ],
            'tags' => ['opening', 'daily', 'essential'],
            'recent_usage' => [
                ['date' => '2024-01-15', 'staff' => 'Alemayehu Tadesse', 'completion_time' => 25, 'status' => 'completed'],
                ['date' => '2024-01-14', 'staff' => 'Sara Hailu', 'completion_time' => 32, 'status' => 'completed'],
                ['date' => '2024-01-13', 'staff' => 'Alemayehu Tadesse', 'completion_time' => 28, 'status' => 'completed']
            ]
        ];
    }

    private function getCategories(): array
    {
        return [
            'all' => 'All Categories',
            'opening_tasks' => 'Opening Tasks',
            'closing_tasks' => 'Closing Tasks',
            'kitchen_prep' => 'Kitchen Prep',
            'customer_service' => 'Customer Service',
            'administrative' => 'Administrative',
            'maintenance' => 'Maintenance'
        ];
    }

    private function getRoles(): array
    {
        return [
            'all_roles' => 'All Roles',
            'waiter' => 'Waiter',
            'chef' => 'Chef',
            'cashier' => 'Cashier',
            'manager' => 'Manager',
            'barista' => 'Barista'
        ];
    }

    private function getRecurringTypes(): array
    {
        return [
            'all' => 'All Types',
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'monthly' => 'Monthly',
            'custom' => 'Custom'
        ];
    }
}