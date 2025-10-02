<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Shifts;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TemplatesController extends Controller
{
    /**
     * Display shift templates overview
     */
    public function index(): View
    {
        // Mock data for shift templates
        $templates = [
            [
                'id' => 1,
                'name' => 'Standard Weekday',
                'description' => 'Monday to Friday regular operations schedule',
                'type' => 'weekly',
                'status' => 'active',
                'created_by' => 'Admin User',
                'created_at' => Carbon::now()->subDays(30),
                'updated_at' => Carbon::now()->subDays(5),
                'usage_count' => 15,
                'last_used' => Carbon::now()->subDays(2),
                'shifts' => [
                    [
                        'name' => 'Morning Kitchen',
                        'department' => 'Kitchen',
                        'start_time' => '06:00',
                        'end_time' => '14:00',
                        'required_staff' => 4,
                        'days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
                    ],
                    [
                        'name' => 'Evening Service',
                        'department' => 'Front of House',
                        'start_time' => '17:00',
                        'end_time' => '23:00',
                        'required_staff' => 6,
                        'days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
                    ],
                    [
                        'name' => 'Bar Evening',
                        'department' => 'Bar',
                        'start_time' => '18:00',
                        'end_time' => '02:00',
                        'required_staff' => 2,
                        'days' => ['thursday', 'friday'],
                    ],
                ],
                'total_shifts' => 3,
                'total_staff_required' => 12,
                'estimated_cost' => 2400.00,
            ],
            [
                'id' => 2,
                'name' => 'Weekend Special',
                'description' => 'Saturday and Sunday brunch and dinner service',
                'type' => 'weekend',
                'status' => 'active',
                'created_by' => 'Sarah Johnson',
                'created_at' => Carbon::now()->subDays(20),
                'updated_at' => Carbon::now()->subDays(1),
                'usage_count' => 8,
                'last_used' => Carbon::now()->subDays(1),
                'shifts' => [
                    [
                        'name' => 'Weekend Brunch',
                        'department' => 'Kitchen',
                        'start_time' => '09:00',
                        'end_time' => '15:00',
                        'required_staff' => 3,
                        'days' => ['saturday', 'sunday'],
                    ],
                    [
                        'name' => 'Weekend Dinner',
                        'department' => 'Front of House',
                        'start_time' => '17:00',
                        'end_time' => '23:00',
                        'required_staff' => 8,
                        'days' => ['saturday', 'sunday'],
                    ],
                    [
                        'name' => 'Weekend Bar',
                        'department' => 'Bar',
                        'start_time' => '16:00',
                        'end_time' => '02:00',
                        'required_staff' => 3,
                        'days' => ['saturday', 'sunday'],
                    ],
                ],
                'total_shifts' => 3,
                'total_staff_required' => 14,
                'estimated_cost' => 1680.00,
            ],
            [
                'id' => 3,
                'name' => 'Holiday Rush',
                'description' => 'Extended hours for holiday periods and special events',
                'type' => 'special',
                'status' => 'active',
                'created_by' => 'Admin User',
                'created_at' => Carbon::now()->subDays(45),
                'updated_at' => Carbon::now()->subDays(10),
                'usage_count' => 4,
                'last_used' => Carbon::now()->subDays(30),
                'shifts' => [
                    [
                        'name' => 'Extended Kitchen',
                        'department' => 'Kitchen',
                        'start_time' => '05:00',
                        'end_time' => '16:00',
                        'required_staff' => 6,
                        'days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'],
                    ],
                    [
                        'name' => 'Holiday Service',
                        'department' => 'Front of House',
                        'start_time' => '11:00',
                        'end_time' => '01:00',
                        'required_staff' => 10,
                        'days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'],
                    ],
                    [
                        'name' => 'Holiday Bar',
                        'department' => 'Bar',
                        'start_time' => '15:00',
                        'end_time' => '03:00',
                        'required_staff' => 4,
                        'days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'],
                    ],
                ],
                'total_shifts' => 3,
                'total_staff_required' => 20,
                'estimated_cost' => 4200.00,
            ],
            [
                'id' => 4,
                'name' => 'Minimal Operations',
                'description' => 'Reduced staff for slow periods or maintenance days',
                'type' => 'minimal',
                'status' => 'active',
                'created_by' => 'Michael Chen',
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(3),
                'usage_count' => 6,
                'last_used' => Carbon::now()->subDays(7),
                'shifts' => [
                    [
                        'name' => 'Basic Kitchen',
                        'department' => 'Kitchen',
                        'start_time' => '08:00',
                        'end_time' => '16:00',
                        'required_staff' => 2,
                        'days' => ['monday', 'tuesday'],
                    ],
                    [
                        'name' => 'Limited Service',
                        'department' => 'Front of House',
                        'start_time' => '12:00',
                        'end_time' => '20:00',
                        'required_staff' => 3,
                        'days' => ['monday', 'tuesday'],
                    ],
                ],
                'total_shifts' => 2,
                'total_staff_required' => 5,
                'estimated_cost' => 640.00,
            ],
            [
                'id' => 5,
                'name' => 'Training Week',
                'description' => 'Template for new staff training periods with mentors',
                'type' => 'training',
                'status' => 'draft',
                'created_by' => 'Emma Rodriguez',
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(1),
                'usage_count' => 0,
                'last_used' => null,
                'shifts' => [
                    [
                        'name' => 'Training Kitchen',
                        'department' => 'Kitchen',
                        'start_time' => '09:00',
                        'end_time' => '15:00',
                        'required_staff' => 3,
                        'days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
                    ],
                    [
                        'name' => 'Training Service',
                        'department' => 'Front of House',
                        'start_time' => '16:00',
                        'end_time' => '22:00',
                        'required_staff' => 4,
                        'days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
                    ],
                ],
                'total_shifts' => 2,
                'total_staff_required' => 7,
                'estimated_cost' => 1260.00,
            ],
        ];

        // Calculate summary statistics
        $totalTemplates = count($templates);
        $activeTemplates = count(array_filter($templates, fn ($t) => $t['status'] === 'active'));
        $draftTemplates = count(array_filter($templates, fn ($t) => $t['status'] === 'draft'));
        $totalUsage = array_sum(array_column($templates, 'usage_count'));

        // Popular templates (most used)
        $popularTemplates = collect($templates)
            ->sortByDesc('usage_count')
            ->take(3)
            ->values()
            ->toArray();

        // Recent templates (recently updated)
        $recentTemplates = collect($templates)
            ->sortByDesc('updated_at')
            ->take(3)
            ->values()
            ->toArray();

        // Template types breakdown
        $templateTypes = [];
        foreach ($templates as $template) {
            $type = $template['type'];
            if (! isset($templateTypes[$type])) {
                $templateTypes[$type] = [
                    'name' => ucfirst($type),
                    'count' => 0,
                    'total_usage' => 0,
                ];
            }
            $templateTypes[$type]['count']++;
            $templateTypes[$type]['total_usage'] += $template['usage_count'];
        }

        return view('admin.shifts.templates.index', compact(
            'templates',
            'totalTemplates',
            'activeTemplates',
            'draftTemplates',
            'totalUsage',
            'popularTemplates',
            'recentTemplates',
            'templateTypes'
        ));
    }

    /**
     * Show the form for creating a new template
     */
    public function create(): View
    {
        // Mock data for form options
        $departments = [
            'Kitchen' => 'Kitchen',
            'Front of House' => 'Front of House',
            'Bar' => 'Bar',
            'Management' => 'Management',
            'Maintenance' => 'Maintenance',
        ];

        $templateTypes = [
            'weekly' => 'Weekly Pattern',
            'weekend' => 'Weekend Only',
            'special' => 'Special Events',
            'minimal' => 'Minimal Operations',
            'training' => 'Training Period',
            'seasonal' => 'Seasonal',
        ];

        $daysOfWeek = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
        ];

        return view('admin.shifts.templates.create', compact('departments', 'templateTypes', 'daysOfWeek'));
    }

    /**
     * Store a newly created template
     */
    public function store(Request $request): RedirectResponse
    {
        // In a real application, you would validate and store the template
        // For now, we'll just redirect with a success message

        return redirect()->route('admin.shifts.templates.index')
            ->with('success', __('shifts.templates.template_created'));
    }

    /**
     * Show the form for editing a template
     */
    public function edit(int $id): View
    {
        // Mock template data for editing
        $template = [
            'id' => $id,
            'name' => 'Standard Weekday',
            'description' => 'Monday to Friday regular operations schedule',
            'type' => 'weekly',
            'status' => 'active',
            'shifts' => [
                [
                    'name' => 'Morning Kitchen',
                    'department' => 'Kitchen',
                    'start_time' => '06:00',
                    'end_time' => '14:00',
                    'required_staff' => 4,
                    'days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
                ],
                [
                    'name' => 'Evening Service',
                    'department' => 'Front of House',
                    'start_time' => '17:00',
                    'end_time' => '23:00',
                    'required_staff' => 6,
                    'days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
                ],
            ],
        ];

        $departments = [
            'Kitchen' => 'Kitchen',
            'Front of House' => 'Front of House',
            'Bar' => 'Bar',
            'Management' => 'Management',
            'Maintenance' => 'Maintenance',
        ];

        $templateTypes = [
            'weekly' => 'Weekly Pattern',
            'weekend' => 'Weekend Only',
            'special' => 'Special Events',
            'minimal' => 'Minimal Operations',
            'training' => 'Training Period',
            'seasonal' => 'Seasonal',
        ];

        $daysOfWeek = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
        ];

        return view('admin.shifts.templates.edit', compact('template', 'departments', 'templateTypes', 'daysOfWeek'));
    }

    /**
     * Update the specified template
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        // In a real application, you would validate and update the template
        // For now, we'll just redirect with a success message

        return redirect()->route('admin.shifts.templates.index')
            ->with('success', __('shifts.templates.template_updated'));
    }

    /**
     * Apply a template to a date range
     */
    public function apply(Request $request, int $id): JsonResponse
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $overwriteExisting = $request->input('overwrite_existing', false);

        // Mock template application
        $shiftsCreated = rand(15, 45); // Random number for demo

        return response()->json([
            'success' => true,
            'message' => __('shifts.templates.template_applied'),
            'shifts_created' => $shiftsCreated,
            'date_range' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
        ]);
    }

    /**
     * Duplicate a template
     */
    public function duplicate(int $id): JsonResponse
    {
        // Mock template duplication
        return response()->json([
            'success' => true,
            'message' => __('shifts.templates.template_duplicated'),
            'new_template_id' => rand(100, 999),
        ]);
    }

    /**
     * Delete a template
     */
    public function destroy(int $id): RedirectResponse
    {
        // In a real application, you would delete the template
        // For now, we'll just redirect with a success message

        return redirect()->route('admin.shifts.templates.index')
            ->with('success', __('shifts.templates.template_deleted'));
    }

    /**
     * Preview template application
     */
    public function preview(Request $request, int $id): JsonResponse
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Mock preview data
        $preview = [
            'total_shifts' => rand(20, 60),
            'total_staff_required' => rand(50, 150),
            'estimated_cost' => rand(5000, 15000),
            'date_conflicts' => rand(0, 5),
            'staff_conflicts' => rand(0, 3),
            'weeks_affected' => ceil((strtotime($endDate) - strtotime($startDate)) / (7 * 24 * 60 * 60)),
        ];

        return response()->json([
            'success' => true,
            'preview' => $preview,
        ]);
    }
}
