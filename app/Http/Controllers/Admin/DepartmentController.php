<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments.
     */
    public function index(): View
    {
        $departments = Department::ordered()->paginate(15);

        return view('admin.settings.departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new department.
     */
    public function create(): View
    {
        return view('admin.settings.departments.create');
    }

    /**
     * Store a newly created department in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'description' => 'nullable|string|max:1000',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        // Generate slug from name
        $validated['slug'] = Str::slug($validated['name']);

        // Ensure slug is unique
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Department::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug.'-'.$counter;
            $counter++;
        }

        Department::create($validated);

        return redirect()->route('admin.settings.departments.index')
            ->with('success', 'Department created successfully.');
    }

    /**
     * Display the specified department.
     */
    public function show(Department $department): View
    {
        return view('admin.settings.departments.show', compact('department'));
    }

    /**
     * Show the form for editing the specified department.
     */
    public function edit(Department $department): View
    {
        return view('admin.settings.departments.edit', compact('department'));
    }

    /**
     * Update the specified department in storage.
     */
    public function update(Request $request, Department $department): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,'.$department->id,
            'description' => 'nullable|string|max:1000',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        // Update slug if name changed
        if ($department->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);

            // Ensure slug is unique
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (Department::where('slug', $validated['slug'])
                ->where('id', '!=', $department->id)
                ->exists()) {
                $validated['slug'] = $originalSlug.'-'.$counter;
                $counter++;
            }
        }

        $department->update($validated);

        return redirect()->route('admin.settings.departments.index')
            ->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the specified department from storage.
     */
    public function destroy(Department $department): RedirectResponse
    {
        try {
            $department->delete();

            return redirect()->route('admin.settings.departments.index')
                ->with('success', 'Department deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.settings.departments.index')
                ->with('error', 'Cannot delete department. It may be in use by existing shifts.');
        }
    }

    /**
     * Toggle department status (active/inactive).
     */
    public function toggleStatus(Department $department): RedirectResponse
    {
        $department->update(['is_active' => ! $department->is_active]);

        $status = $department->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.settings.departments.index')
            ->with('success', "Department {$status} successfully.");
    }
}
