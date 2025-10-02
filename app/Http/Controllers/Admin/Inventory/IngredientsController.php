<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class IngredientsController extends Controller
{
    /**
     * Display a listing of ingredients.
     */
    public function index(Request $request): View
    {
        $query = Ingredient::with(['supplier'])
            ->select([
                'id', 'name', 'code', 'category', 'unit', 'cost_per_unit',
                'supplier_id', 'allergen_info', 'status', 'shelf_life_days',
                'storage_requirements', 'last_updated', 'created_at',
            ]);

        // Apply filters
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        if ($request->filled('supplier_id')) {
            $query->bySupplier((int) $request->supplier_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('allergen')) {
            if ($request->allergen === 'allergen_free') {
                $query->allergenFree();
            } else {
                $query->withAllergen($request->allergen);
            }
        }

        if ($request->filled('storage')) {
            $query->byStorage($request->storage);
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');

        $allowedSorts = ['name', 'category', 'cost_per_unit', 'created_at', 'last_updated'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Paginate results
        $ingredients = $query->paginate(20)->withQueryString();

        // Get filter options
        $suppliers = Supplier::active()->orderBy('name')->get(['id', 'name']);
        $categories = Ingredient::distinct()->pluck('category')->filter()->sort()->values();
        $storageTypes = Ingredient::distinct()->pluck('storage_requirements')->filter()->sort()->values();

        // Get summary statistics
        $statistics = $this->getIngredientStatistics();

        return view('admin.inventory.ingredients.index', compact(
            'ingredients',
            'suppliers',
            'categories',
            'storageTypes',
            'statistics'
        ));
    }

    /**
     * Display the specified ingredient.
     */
    public function show(Request $request, Ingredient $ingredient): JsonResponse
    {
        $ingredient->load(['supplier']);

        return response()->json([
            'success' => true,
            'ingredient' => [
                'id' => $ingredient->id,
                'name' => $ingredient->name,
                'code' => $ingredient->code,
                'description' => $ingredient->description,
                'category' => $ingredient->category,
                'subcategory' => $ingredient->subcategory,
                'unit' => $ingredient->unit,
                'cost_per_unit' => $ingredient->cost_per_unit,
                'formatted_cost' => $ingredient->formatted_cost,
                'minimum_order_qty' => $ingredient->minimum_order_qty,
                'supplier' => $ingredient->supplier ? [
                    'id' => $ingredient->supplier->id,
                    'name' => $ingredient->supplier->name,
                    'contact_person' => $ingredient->supplier->contact_person,
                    'email' => $ingredient->supplier->email,
                    'phone' => $ingredient->supplier->phone,
                ] : null,
                'lead_time_days' => $ingredient->lead_time_days,
                'storage_requirements' => $ingredient->storage_requirements,
                'shelf_life_days' => $ingredient->shelf_life_days,
                'origin_country' => $ingredient->origin_country,
                'nutritional_info' => $ingredient->nutritional_info,
                'allergen_info' => $ingredient->allergen_info,
                'allergens_string' => $ingredient->allergens_string,
                'is_allergen_free' => $ingredient->isAllergenFree(),
                'status' => $ingredient->status,
                'notes' => $ingredient->notes,
                'is_perishable' => $ingredient->isPerishable(),
                'needs_refrigeration' => $ingredient->needsRefrigeration(),
                'last_updated' => $ingredient->last_updated?->format('Y-m-d H:i:s'),
                'created_at' => $ingredient->created_at->format('Y-m-d H:i:s'),
            ],
        ]);
    }

    /**
     * Handle bulk actions on ingredients.
     */
    public function bulkAction(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,discontinue,delete',
            'ingredient_ids' => 'required|array|min:1',
            'ingredient_ids.*' => 'exists:ingredients,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data provided',
                'errors' => $validator->errors(),
            ], 422);
        }

        $action = $request->action;
        $ingredientIds = $request->ingredient_ids;

        try {
            DB::beginTransaction();

            switch ($action) {
                case 'activate':
                    Ingredient::whereIn('id', $ingredientIds)->update(['status' => 'active']);
                    break;
                case 'deactivate':
                    Ingredient::whereIn('id', $ingredientIds)->update(['status' => 'inactive']);
                    break;
                case 'discontinue':
                    Ingredient::whereIn('id', $ingredientIds)->update(['status' => 'discontinued']);
                    break;
                case 'delete':
                    Ingredient::whereIn('id', $ingredientIds)->delete();
                    break;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('inventory.ingredients.bulk_update_success'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Bulk action failed: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Export ingredients data.
     */
    public function export(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => __('inventory.ingredients.export_success'),
            'download_url' => '#',
        ]);
    }

    /**
     * Get ingredient statistics for summary cards.
     */
    private function getIngredientStatistics(): array
    {
        $totalIngredients = Ingredient::count();
        $activeIngredients = Ingredient::active()->count();
        $categoriesCount = Ingredient::distinct()->count('category');
        $avgCost = Ingredient::active()->avg('cost_per_unit') ?? 0;
        $suppliersCount = Ingredient::distinct()->whereNotNull('supplier_id')->count('supplier_id');
        $allergenFreeCount = Ingredient::allergenFree()->count();

        return [
            'total_ingredients' => $totalIngredients,
            'active_ingredients' => $activeIngredients,
            'categories_count' => $categoriesCount,
            'avg_cost' => number_format($avgCost, 2),
            'suppliers_count' => $suppliersCount,
            'allergen_free' => $allergenFreeCount,
        ];
    }
}
