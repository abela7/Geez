<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaskCategory;
use App\Models\TaskPriority;
use App\Models\TaskStatus;
use App\Models\TaskTag;
use App\Models\TaskTemplate;
use App\Models\TaskType;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TaskSettingsController extends Controller
{
    /**
     * Display the task settings dashboard.
     */
    public function index(): View
    {
        try {
            $data = [
                'taskTypes' => TaskType::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get(),
                'taskPriorities' => TaskPriority::where('is_active', true)->orderBy('sort_order')->orderBy('level')->get(),
                'taskCategories' => TaskCategory::where('is_active', true)->whereNull('parent_id')->orderBy('sort_order')->orderBy('name')->with('children')->get(),
                'taskTags' => TaskTag::where('is_active', true)->orderByDesc('usage_count')->limit(20)->get(),
                'taskStatuses' => TaskStatus::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get(),
                'taskTemplates' => TaskTemplate::where('is_active', true)->with(['taskType', 'taskCategory'])->limit(10)->get(),
                'stats' => [
                    'total_types' => TaskType::where('is_active', true)->count(),
                    'total_priorities' => TaskPriority::where('is_active', true)->count(),
                    'total_categories' => TaskCategory::where('is_active', true)->count(),
                    'total_tags' => TaskTag::where('is_active', true)->count(),
                    'total_templates' => TaskTemplate::where('is_active', true)->count(),
                ],
            ];

            return view('admin.staff.task-settings', $data);
        } catch (\Exception $e) {
            // If there's any error, return with empty data
            $data = [
                'taskTypes' => collect([]),
                'taskPriorities' => collect([]),
                'taskCategories' => collect([]),
                'taskTags' => collect([]),
                'taskStatuses' => collect([]),
                'taskTemplates' => collect([]),
                'stats' => [
                    'total_types' => 0,
                    'total_priorities' => 0,
                    'total_categories' => 0,
                    'total_tags' => 0,
                    'total_templates' => 0,
                ],
            ];

            return view('admin.staff.task-settings', $data);
        }
    }

    /**
     * Store a new task type.
     */
    public function storeTaskType(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:task_types,name',
            'description' => 'nullable|string|max:1000',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'icon' => 'nullable|string|max:100',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['created_by'] = auth()->user()->id;
        $validated['sort_order'] = TaskType::max('sort_order') + 1;

        TaskType::create($validated);

        return redirect()->back()->with('success', __('staff.tasks.settings.task_type_created'));
    }

    /**
     * Show the edit form for a task type.
     */
    public function editTaskType(TaskType $taskType): View
    {
        return view('admin.staff.task-settings-edit-type', compact('taskType'));
    }

    /**
     * Update a task type.
     */
    public function updateTaskType(Request $request, TaskType $taskType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:task_types,name,'.$taskType->id,
            'description' => 'nullable|string|max:1000',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'icon' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $validated['updated_by'] = auth()->user()->id;

        $taskType->update($validated);

        return redirect()->route('admin.staff.tasks.settings.index')->with('success', __('staff.tasks.settings.task_type_updated'));
    }

    /**
     * Delete a task type.
     */
    public function destroyTaskType(TaskType $taskType): RedirectResponse
    {
        // Check if type is being used
        if ($taskType->tasks()->exists()) {
            return redirect()->back()->with('error', __('staff.tasks.settings.task_type_in_use'));
        }

        $taskType->delete();

        return redirect()->back()->with('success', __('staff.tasks.settings.task_type_deleted'));
    }

    /**
     * Store a new task priority.
     */
    public function storeTaskPriority(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:task_priorities,name',
            'description' => 'nullable|string|max:1000',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'icon' => 'nullable|string|max:100',
            'level' => 'required|integer|min:1|max:10|unique:task_priorities,level',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['created_by'] = auth()->user()->id;
        $validated['sort_order'] = TaskPriority::max('sort_order') + 1;

        TaskPriority::create($validated);

        return redirect()->back()->with('success', __('staff.tasks.settings.task_priority_created'));
    }

    /**
     * Show the edit form for a task priority.
     */
    public function editTaskPriority(TaskPriority $taskPriority): View
    {
        return view('admin.staff.task-settings-edit-priority', compact('taskPriority'));
    }

    /**
     * Update a task priority.
     */
    public function updateTaskPriority(Request $request, TaskPriority $taskPriority): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:task_priorities,name,'.$taskPriority->id,
            'description' => 'nullable|string|max:1000',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'icon' => 'nullable|string|max:100',
            'level' => 'required|integer|min:1|max:10|unique:task_priorities,level,'.$taskPriority->id,
            'is_active' => 'boolean',
        ]);

        $validated['updated_by'] = auth()->user()->id;

        $taskPriority->update($validated);

        return redirect()->route('admin.staff.tasks.settings.index')->with('success', __('staff.tasks.settings.task_priority_updated'));
    }

    /**
     * Delete a task priority.
     */
    public function destroyTaskPriority(TaskPriority $taskPriority): RedirectResponse
    {
        // Check if priority is being used
        if ($taskPriority->tasks()->exists()) {
            return redirect()->back()->with('error', __('staff.tasks.settings.task_priority_in_use'));
        }

        $taskPriority->delete();

        return redirect()->back()->with('success', __('staff.tasks.settings.task_priority_deleted'));
    }

    /**
     * Store a new task category.
     */
    public function storeTaskCategory(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:task_categories,name',
            'description' => 'nullable|string|max:1000',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'icon' => 'nullable|string|max:100',
            'parent_id' => 'nullable|exists:task_categories,id',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['created_by'] = auth()->user()->id;
        $validated['sort_order'] = TaskCategory::max('sort_order') + 1;

        TaskCategory::create($validated);

        return redirect()->back()->with('success', __('staff.tasks.settings.task_category_created'));
    }

    /**
     * Show the edit form for a task category.
     */
    public function editTaskCategory(TaskCategory $taskCategory): View
    {
        $parentCategories = TaskCategory::where('is_active', true)
            ->whereNull('parent_id')
            ->where('id', '!=', $taskCategory->id)
            ->orderBy('name')
            ->get();

        return view('admin.staff.task-settings-edit-category', compact('taskCategory', 'parentCategories'));
    }

    /**
     * Update a task category.
     */
    public function updateTaskCategory(Request $request, TaskCategory $taskCategory): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:task_categories,name,'.$taskCategory->id,
            'description' => 'nullable|string|max:1000',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'icon' => 'nullable|string|max:100',
            'parent_id' => 'nullable|exists:task_categories,id',
            'is_active' => 'boolean',
        ]);

        // Prevent circular reference
        if ($validated['parent_id'] == $taskCategory->id) {
            return redirect()->back()->with('error', __('staff.tasks.settings.category_circular_reference'));
        }

        $validated['updated_by'] = auth()->user()->id;

        $taskCategory->update($validated);

        return redirect()->route('admin.staff.tasks.settings.index')->with('success', __('staff.tasks.settings.task_category_updated'));
    }

    /**
     * Delete a task category.
     */
    public function destroyTaskCategory(TaskCategory $taskCategory): RedirectResponse
    {
        // Check if category has children
        if ($taskCategory->children()->exists()) {
            return redirect()->back()->with('error', __('staff.tasks.settings.category_has_children'));
        }

        $taskCategory->delete();

        return redirect()->back()->with('success', __('staff.tasks.settings.task_category_deleted'));
    }

    /**
     * Store a new task tag.
     */
    public function storeTaskTag(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        // Check if a tag with this name already exists (including soft-deleted)
        $existingTag = TaskTag::where('name', $validated['name'])->withTrashed()->first();

        if ($existingTag) {
            if ($existingTag->trashed()) {
                // If tag exists but is soft-deleted, restore it with updated info
                $existingTag->restore();
                $existingTag->update([
                    'color' => $validated['color'],
                    'description' => $validated['description'] ?? $existingTag->description,
                    'updated_by' => auth()->user()->id,
                    'is_active' => true,
                ]);

                return redirect()->back()->with('success', 'Tag restored and updated successfully!');
            } else {
                // Tag exists and is active
                return redirect()->back()->with('info', 'Tag already exists and is available for use.');
            }
        }

        // Generate unique name by appending a number if needed
        $originalName = $validated['name'];
        $uniqueName = $originalName;
        $nameCounter = 1;

        while (TaskTag::where('name', $uniqueName)->withTrashed()->exists()) {
            $uniqueName = $originalName . ' ' . $nameCounter;
            $nameCounter++;
        }

        // Generate unique slug from the unique name
        $baseSlug = Str::slug($uniqueName);
        $slug = $baseSlug;
        $counter = 1;

        while (TaskTag::where('slug', $slug)->withTrashed()->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $validated['name'] = $uniqueName;
        $validated['slug'] = $slug;
        $validated['created_by'] = auth()->user()->id;
        $validated['updated_by'] = auth()->user()->id;
        $validated['is_active'] = true;
        $validated['usage_count'] = 0;

        TaskTag::create($validated);

        $message = $uniqueName !== $originalName 
            ? "Tag created as \"$uniqueName\" (original name \"$originalName\" already exists)"
            : __('staff.tasks.settings.task_tag_created');

        return redirect()->back()->with('success', $message);
    }

    /**
     * Show the edit form for a task tag.
     */
    public function editTaskTag(TaskTag $taskTag): View
    {
        return view('admin.staff.task-settings-edit-tag', compact('taskTag'));
    }

    /**
     * Update a task tag.
     */
    public function updateTaskTag(Request $request, TaskTag $taskTag): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:task_tags,name,'.$taskTag->id,
            'description' => 'nullable|string|max:1000',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
        ]);

        $validated['updated_by'] = auth()->user()->id;

        $taskTag->update($validated);

        return redirect()->route('admin.staff.tasks.settings.index')->with('success', __('staff.tasks.settings.task_tag_updated'));
    }

    /**
     * Delete a task tag.
     */
    public function destroyTaskTag(TaskTag $taskTag): RedirectResponse
    {
        $taskTag->delete();

        return redirect()->back()->with('success', __('staff.tasks.settings.task_tag_deleted'));
    }

    /**
     * Create a new task tag via AJAX for inline creation.
     */
    public function storeTagAjax(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            ]);

            // Check if a tag with this name already exists (including soft-deleted)
            $existingTag = TaskTag::where('name', $validated['name'])->withTrashed()->first();

            if ($existingTag) {
                if ($existingTag->trashed()) {
                    // If tag exists but is soft-deleted, restore it with updated info
                    $existingTag->restore();
                    $existingTag->update([
                        'color' => $validated['color'],
                        'description' => $validated['description'] ?? $existingTag->description,
                        'updated_by' => auth()->id(),
                        'is_active' => true,
                    ]);

                    return response()->json([
                        'success' => true,
                        'tag' => [
                            'id' => $existingTag->id,
                            'name' => $existingTag->name,
                            'slug' => $existingTag->slug,
                            'color' => $existingTag->color,
                            'description' => $existingTag->description,
                        ],
                        'message' => 'Tag restored and updated successfully!'
                    ]);
                } else {
                    // Tag exists and is active, just return it
                    return response()->json([
                        'success' => true,
                        'tag' => [
                            'id' => $existingTag->id,
                            'name' => $existingTag->name,
                            'slug' => $existingTag->slug,
                            'color' => $existingTag->color,
                            'description' => $existingTag->description,
                        ],
                        'message' => 'Tag already exists and has been selected'
                    ]);
                }
            }

            // If we reach here, create a new tag with a unique name
            $originalName = $validated['name'];
            $uniqueName = $originalName;
            $nameCounter = 1;

            // Generate unique name by appending a number if needed
            while (TaskTag::where('name', $uniqueName)->withTrashed()->exists()) {
                $uniqueName = $originalName . ' ' . $nameCounter;
                $nameCounter++;
            }

            // Generate unique slug from the unique name
            $baseSlug = Str::slug($uniqueName);
            $slug = $baseSlug;
            $counter = 1;

            while (TaskTag::where('slug', $slug)->withTrashed()->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $validated['name'] = $uniqueName;
            $validated['slug'] = $slug;
            $validated['created_by'] = auth()->id();
            $validated['updated_by'] = auth()->id();
            $validated['is_active'] = true;
            $validated['usage_count'] = 0;

            $tag = TaskTag::create($validated);

            // Create appropriate success message
            $message = $uniqueName !== $originalName 
                ? "Tag created as \"$uniqueName\" (original name \"$originalName\" already exists)"
                : 'Tag created successfully!';

            return response()->json([
                'success' => true,
                'tag' => [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                    'color' => $tag->color,
                    'description' => $tag->description,
                ],
                'message' => $message
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => __('staff.tasks.settings.task_tag_creation_failed')
            ], 422);
        } catch (\Exception $e) {
            Log::error('Tag creation failed: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => __('staff.tasks.settings.task_tag_creation_failed') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reorder items.
     */
    public function reorder(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:task_types,task_priorities,task_categories',
            'items' => 'required|array',
            'items.*.id' => 'required|string',
            'items.*.sort_order' => 'required|integer',
        ]);

        $modelClass = match ($validated['type']) {
            'task_types' => TaskType::class,
            'task_priorities' => TaskPriority::class,
            'task_categories' => TaskCategory::class,
        };

        foreach ($validated['items'] as $item) {
            $modelClass::where('id', $item['id'])->update([
                'sort_order' => $item['sort_order'],
                'updated_by' => auth()->user()->id,
            ]);
        }

        return redirect()->back()->with('success', __('staff.tasks.settings.order_updated'));
    }
}
