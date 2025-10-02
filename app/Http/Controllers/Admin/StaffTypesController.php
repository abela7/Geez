<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaffType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StaffTypesController extends Controller
{
    /**
     * Display a listing of staff types.
     */
    public function index(): View
    {
        $staffTypes = StaffType::withCount('staff')
            ->orderBy('priority', 'desc')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.staff.types.index', compact('staffTypes'));
    }

    /**
     * Show the form for creating a new staff type.
     */
    public function create(): View
    {
        return view('admin.staff.types.create');
    }

    /**
     * Store a newly created staff type in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:staff_types,name', 'regex:/^[a-z_]+$/'],
            'display_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'priority' => ['required', 'integer', 'min:0', 'max:100'],
            'is_active' => ['boolean'],
        ]);

        // Set audit fields
        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();
        $validated['is_active'] = $request->boolean('is_active', true);

        $staffType = StaffType::create($validated);

        return redirect()
            ->route('admin.staff.types.show', $staffType)
            ->with('success', __('staff.types.created_successfully', ['name' => $staffType->display_name]));
    }

    /**
     * Display the specified staff type.
     */
    public function show(StaffType $staffType): View
    {
        $staffType->load(['staff' => function ($query) {
            $query->select('id', 'first_name', 'last_name', 'username', 'status', 'staff_type_id', 'hire_date')
                ->orderBy('first_name')
                ->orderBy('last_name');
        }]);

        return view('admin.staff.types.show', compact('staffType'));
    }

    /**
     * Show the form for editing the specified staff type.
     */
    public function edit(StaffType $staffType): View
    {
        return view('admin.staff.types.edit', compact('staffType'));
    }

    /**
     * Update the specified staff type in storage.
     */
    public function update(Request $request, StaffType $staffType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('staff_types', 'name')->ignore($staffType->id), 'regex:/^[a-z_]+$/'],
            'display_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'priority' => ['required', 'integer', 'min:0', 'max:100'],
            'is_active' => ['boolean'],
        ]);

        // Set audit fields
        $validated['updated_by'] = Auth::id();
        $validated['is_active'] = $request->boolean('is_active', true);

        $staffType->update($validated);

        return redirect()
            ->route('admin.staff.types.show', $staffType)
            ->with('success', __('staff.types.updated_successfully', ['name' => $staffType->display_name]));
    }

    /**
     * Remove the specified staff type from storage (soft delete).
     */
    public function destroy(StaffType $staffType): RedirectResponse
    {
        // Check if staff type has active staff members
        if ($staffType->staff()->where('status', 'active')->exists()) {
            return redirect()
                ->route('admin.staff.types.show', $staffType)
                ->with('error', __('staff.types.cannot_delete_has_active_staff'));
        }

        $staffType->delete();

        return redirect()
            ->route('admin.staff.types.index')
            ->with('success', __('staff.types.deleted_successfully', ['name' => $staffType->display_name]));
    }

    /**
     * Display trashed staff types.
     */
    public function trashed(): View
    {
        $trashedStaffTypes = StaffType::onlyTrashed()
            ->withCount('staff')
            ->orderBy('deleted_at', 'desc')
            ->paginate(15);

        return view('admin.staff.types.trashed', compact('trashedStaffTypes'));
    }

    /**
     * Restore a trashed staff type.
     */
    public function restore(string $id): RedirectResponse
    {
        $staffType = StaffType::onlyTrashed()->findOrFail($id);

        // Set audit field
        $staffType->updated_by = Auth::id();
        $staffType->save();
        $staffType->restore();

        return redirect()
            ->route('admin.staff.types.show', $staffType)
            ->with('success', __('staff.types.restored_successfully', ['name' => $staffType->display_name]));
    }

    /**
     * Permanently delete a trashed staff type.
     */
    public function forceDelete(string $id): RedirectResponse
    {
        $staffType = StaffType::onlyTrashed()->findOrFail($id);

        // Check if staff type has any staff members (even inactive)
        if ($staffType->staff()->exists()) {
            return redirect()
                ->route('admin.staff.types.trashed')
                ->with('error', __('staff.types.cannot_force_delete_has_staff'));
        }

        $displayName = $staffType->display_name;
        $staffType->forceDelete();

        return redirect()
            ->route('admin.staff.types.trashed')
            ->with('success', __('staff.types.force_deleted_successfully', ['name' => $displayName]));
    }

    /**
     * Toggle active status of staff type.
     */
    public function toggleActive(StaffType $staffType): RedirectResponse
    {
        $staffType->update([
            'is_active' => ! $staffType->is_active,
            'updated_by' => Auth::id(),
        ]);

        $status = $staffType->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->route('admin.staff.types.show', $staffType)
            ->with('success', __("staff.types.{$status}_successfully", ['name' => $staffType->display_name]));
    }
}
