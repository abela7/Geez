<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\IngredientCategory;
use App\Models\IngredientUnit;
use App\Models\IngredientType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class InventorySettingsController extends Controller
{
    public function index(): View
    {
        $categories = IngredientCategory::ordered()->get();
        $units = IngredientUnit::ordered()->get();
        $types = IngredientType::ordered()->get();

        return view('admin.inventory.settings.index', compact('categories', 'units', 'types'));
    }

    // Categories Management
    public function storeCategory(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:ingredient_categories,name',
            'description' => 'nullable|string',
            'color_code' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'icon' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $category = IngredientCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'color_code' => $request->color_code,
            'icon' => $request->icon,
            'sort_order' => IngredientCategory::max('sort_order') + 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => __('inventory.settings.category_created'),
            'category' => $category
        ]);
    }

    public function updateCategory(Request $request, IngredientCategory $category): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:ingredient_categories,name,' . $category->id,
            'description' => 'nullable|string',
            'color_code' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'color_code' => $request->color_code,
            'icon' => $request->icon,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return response()->json([
            'success' => true,
            'message' => __('inventory.settings.category_updated'),
            'category' => $category
        ]);
    }

    public function deleteCategory(IngredientCategory $category): JsonResponse
    {
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => __('inventory.settings.category_deleted')
        ]);
    }

    // Units Management
    public function storeUnit(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:10|unique:ingredient_units,symbol',
            'type' => 'required|string|in:weight,volume,count,custom',
            'description' => 'nullable|string',
            'base_conversion_factor' => 'nullable|numeric|min:0',
            'base_unit' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $unit = IngredientUnit::create([
            'name' => $request->name,
            'symbol' => $request->symbol,
            'type' => $request->type,
            'description' => $request->description,
            'base_conversion_factor' => $request->base_conversion_factor,
            'base_unit' => $request->base_unit,
            'sort_order' => IngredientUnit::max('sort_order') + 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => __('inventory.settings.unit_created'),
            'unit' => $unit
        ]);
    }

    public function updateUnit(Request $request, IngredientUnit $unit): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:10|unique:ingredient_units,symbol,' . $unit->id,
            'type' => 'required|string|in:weight,volume,count,custom',
            'description' => 'nullable|string',
            'base_conversion_factor' => 'nullable|numeric|min:0',
            'base_unit' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $unit->update($request->only([
            'name', 'symbol', 'type', 'description', 
            'base_conversion_factor', 'base_unit'
        ]) + ['is_active' => $request->boolean('is_active', true)]);

        return response()->json([
            'success' => true,
            'message' => __('inventory.settings.unit_updated'),
            'unit' => $unit
        ]);
    }

    public function deleteUnit(IngredientUnit $unit): JsonResponse
    {
        $unit->delete();

        return response()->json([
            'success' => true,
            'message' => __('inventory.settings.unit_deleted')
        ]);
    }

    // Types Management
    public function storeType(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:ingredient_types,name',
            'description' => 'nullable|string',
            'measurement_type' => 'required|string|in:weight,volume,count',
            'compatible_units' => 'nullable|array',
            'compatible_units.*' => 'exists:ingredient_units,id',
            'color_code' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'properties' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $type = IngredientType::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'measurement_type' => $request->measurement_type,
            'compatible_units' => $request->compatible_units,
            'color_code' => $request->color_code,
            'properties' => $request->properties,
            'sort_order' => IngredientType::max('sort_order') + 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => __('inventory.settings.type_created'),
            'type' => $type
        ]);
    }

    public function updateType(Request $request, IngredientType $type): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:ingredient_types,name,' . $type->id,
            'description' => 'nullable|string',
            'measurement_type' => 'required|string|in:weight,volume,count',
            'compatible_units' => 'nullable|array',
            'compatible_units.*' => 'exists:ingredient_units,id',
            'color_code' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'properties' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $type->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'measurement_type' => $request->measurement_type,
            'compatible_units' => $request->compatible_units,
            'color_code' => $request->color_code,
            'properties' => $request->properties,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return response()->json([
            'success' => true,
            'message' => __('inventory.settings.type_updated'),
            'type' => $type
        ]);
    }

    public function deleteType(IngredientType $type): JsonResponse
    {
        $type->delete();

        return response()->json([
            'success' => true,
            'message' => __('inventory.settings.type_deleted')
        ]);
    }
}