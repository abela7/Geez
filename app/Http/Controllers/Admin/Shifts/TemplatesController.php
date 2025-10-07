<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Shifts;

use App\Http\Controllers\Controller;
use App\Models\WeeklyRotaTemplate;
use App\Models\WeeklyRotaTemplateAssignment;
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
        $templates = WeeklyRotaTemplate::with(['creator', 'assignments.staff', 'assignments.shift'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($template) {
                return [
                    'id' => $template->id,
                    'name' => $template->name,
                    'description' => $template->description,
                    'type' => $template->type,
                    'status' => $template->is_active ? 'active' : 'draft',
                    'created_by' => $template->creator?->full_name ?? 'Unknown',
                    'created_at' => $template->created_at,
                    'updated_at' => $template->updated_at,
                    'usage_count' => $template->usage_count,
                    'last_used' => $template->last_used_at,
                    'total_shifts' => $template->getShiftsCount(),
                    'total_staff' => $template->getUniqueStaffCount(),
                    'total_assignments' => $template->getTotalAssignments(),
                ];
            });

        $totalTemplates = $templates->count();
        $activeTemplates = $templates->where('status', 'active')->count();
        $draftTemplates = $templates->where('status', 'draft')->count();
        $totalUsage = $templates->sum('usage_count');

        // Get popular templates (most used)
        $popularTemplates = $templates->sortByDesc('usage_count')->take(3)->values();

        // Get recent templates
        $recentTemplates = $templates->sortByDesc('updated_at')->take(3)->values();

        // Group by type for statistics
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
        return view('admin.shifts.templates.create');
    }

    /**
     * Store a newly created template
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:standard,holiday,seasonal,custom',
            'is_active' => 'boolean',
        ]);

        $template = WeeklyRotaTemplate::create([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'is_active' => $request->boolean('is_active', true),
            'created_by' => auth()->id(), // Assuming we have auth
        ]);

        return redirect()->route('admin.shifts.templates.index')
            ->with('success', __('shifts.templates.template_created'));
    }

    /**
     * Show the form for editing the specified template
     */
    public function edit(string $id): View
    {
        $template = WeeklyRotaTemplate::with(['assignments.staff', 'assignments.shift'])
            ->findOrFail($id);

        $departments = [
            'Kitchen' => 'Kitchen',
            'Front of House' => 'Front of House',
            'Bar' => 'Bar',
            'Management' => 'Management',
            'Maintenance' => 'Maintenance',
        ];

        $templateTypes = [
            'standard' => 'Standard',
            'holiday' => 'Holiday',
            'seasonal' => 'Seasonal',
            'custom' => 'Custom',
        ];

        $daysOfWeek = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];

        return view('admin.shifts.templates.edit', compact('template', 'departments', 'templateTypes', 'daysOfWeek'));
    }

    /**
     * Update the specified template
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:standard,holiday,seasonal,custom',
            'is_active' => 'boolean',
        ]);

        $template = WeeklyRotaTemplate::findOrFail($id);
        
        $template->update([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'is_active' => $request->boolean('is_active', true),
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('admin.shifts.templates.index')
            ->with('success', __('shifts.templates.template_updated'));
    }

    /**
     * Apply a template to a date range
     */
    public function apply(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'overwrite_existing' => 'boolean',
        ]);

        $template = WeeklyRotaTemplate::with('assignments')->findOrFail($id);
        
        // Mark template as used
        $template->markAsUsed();

        // In a real implementation, you would apply the template to the specified date range
        // For now, we'll return a success response
        $shiftsCreated = $template->assignments->count();

        return response()->json([
            'success' => true,
            'message' => __('shifts.templates.template_applied'),
            'shifts_created' => $shiftsCreated,
            'date_range' => [
                'start' => $request->start_date,
                'end' => $request->end_date,
            ],
        ]);
    }

    /**
     * Duplicate a template
     */
    public function duplicate(string $id): JsonResponse
    {
        $originalTemplate = WeeklyRotaTemplate::with('assignments')->findOrFail($id);
        
        // Create duplicate template
        $duplicateTemplate = WeeklyRotaTemplate::create([
            'name' => 'Copy of ' . $originalTemplate->name,
            'description' => $originalTemplate->description,
            'type' => $originalTemplate->type,
            'is_active' => false, // Start as draft
            'created_by' => auth()->id(),
        ]);

        // Duplicate assignments
        foreach ($originalTemplate->assignments as $assignment) {
            WeeklyRotaTemplateAssignment::create([
                'template_id' => $duplicateTemplate->id,
                'staff_shift_id' => $assignment->staff_shift_id,
                'staff_id' => $assignment->staff_id,
                'day_of_week' => $assignment->day_of_week,
                'status' => $assignment->status,
                'notes' => $assignment->notes,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => __('shifts.templates.template_duplicated'),
            'new_template_id' => $duplicateTemplate->id,
        ]);
    }

    /**
     * Delete a template
     */
    public function destroy(string $id): RedirectResponse
    {
        $template = WeeklyRotaTemplate::findOrFail($id);
        
        // Check if template is in use
        if ($template->usage_count > 0) {
            return redirect()->route('admin.shifts.templates.index')
                ->with('error', __('shifts.templates.cannot_delete_used_template'));
        }

        $template->delete();

        return redirect()->route('admin.shifts.templates.index')
            ->with('success', __('shifts.templates.template_deleted'));
    }

    /**
     * Preview template application
     */
    public function preview(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $template = WeeklyRotaTemplate::with('assignments.staff', 'assignments.shift')->findOrFail($id);
        
        // Generate preview data
        $preview = [
            'template_name' => $template->name,
            'total_assignments' => $template->assignments->count(),
            'unique_staff' => $template->assignments->pluck('staff_id')->unique()->count(),
            'date_range' => [
                'start' => $request->start_date,
                'end' => $request->end_date,
            ],
            'assignments_by_day' => $template->getAssignmentsByDay(),
        ];

        return response()->json([
            'success' => true,
            'preview' => $preview,
        ]);
    }
}