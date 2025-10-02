<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShiftType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ShiftTypeController extends Controller
{
    /**
     * Display a listing of shift types.
     */
    public function index(): View
    {
        $shiftTypes = ShiftType::ordered()->paginate(15);

        return view('admin.settings.shift-types.index', compact('shiftTypes'));
    }

    /**
     * Show the form for creating a new shift type.
     */
    public function create(): View
    {
        return view('admin.settings.shift-types.create');
    }

    /**
     * Store a newly created shift type in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:shift_types,name',
            'description' => 'nullable|string|max:1000',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
            'default_hourly_rate' => 'nullable|numeric|min:0|max:999.99',
            'default_overtime_rate' => 'nullable|numeric|min:0|max:999.99',
            'sort_order' => 'integer|min:0',
        ]);

        // Generate slug from name
        $validated['slug'] = Str::slug($validated['name']);

        // Ensure slug is unique
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (ShiftType::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug.'-'.$counter;
            $counter++;
        }

        ShiftType::create($validated);

        return redirect()->route('admin.settings.shift-types.index')
            ->with('success', 'Shift type created successfully.');
    }

    /**
     * Display the specified shift type.
     */
    public function show(ShiftType $shiftType): View
    {
        return view('admin.settings.shift-types.show', compact('shiftType'));
    }

    /**
     * Show the form for editing the specified shift type.
     */
    public function edit(ShiftType $shiftType): View
    {
        return view('admin.settings.shift-types.edit', compact('shiftType'));
    }

    /**
     * Update the specified shift type in storage.
     */
    public function update(Request $request, ShiftType $shiftType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:shift_types,name,'.$shiftType->id,
            'description' => 'nullable|string|max:1000',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
            'default_hourly_rate' => 'nullable|numeric|min:0|max:999.99',
            'default_overtime_rate' => 'nullable|numeric|min:0|max:999.99',
            'sort_order' => 'integer|min:0',
        ]);

        // Update slug if name changed
        if ($shiftType->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);

            // Ensure slug is unique
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (ShiftType::where('slug', $validated['slug'])
                ->where('id', '!=', $shiftType->id)
                ->exists()) {
                $validated['slug'] = $originalSlug.'-'.$counter;
                $counter++;
            }
        }

        $shiftType->update($validated);

        return redirect()->route('admin.settings.shift-types.index')
            ->with('success', 'Shift type updated successfully.');
    }

    /**
     * Remove the specified shift type from storage.
     */
    public function destroy(ShiftType $shiftType): RedirectResponse
    {
        try {
            $shiftType->delete();

            return redirect()->route('admin.settings.shift-types.index')
                ->with('success', 'Shift type deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.settings.shift-types.index')
                ->with('error', 'Cannot delete shift type. It may be in use by existing shifts.');
        }
    }

    /**
     * Toggle shift type status (active/inactive).
     */
    public function toggleStatus(ShiftType $shiftType): RedirectResponse
    {
        $shiftType->update(['is_active' => ! $shiftType->is_active]);

        $status = $shiftType->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.settings.shift-types.index')
            ->with('success', "Shift type {$status} successfully.");
    }
}
