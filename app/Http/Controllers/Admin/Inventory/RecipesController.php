<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class RecipesController extends Controller
{
    /**
     * Display the recipes index page
     */
    public function index(Request $request): View
    {
        // Sample recipe data for UI demo
        $sampleRecipes = collect([
            (object) [
                'id' => 1,
                'name' => 'Ethiopian Doro Wat',
                'code' => 'RCP001',
                'description' => 'Traditional Ethiopian chicken stew with berbere spice',
                'category' => 'main_course',
                'serving_size' => 6,
                'prep_time' => 30,
                'cook_time' => 90,
                'total_time' => 120,
                'difficulty' => 'medium',
                'cost_per_serving' => 8.50,
                'total_cost' => 51.00,
                'status' => 'active',
                'difficulty_badge_class' => 'difficulty-medium',
                'status_badge_class' => 'status-active',
                'formatted_total_time' => '2h',
            ],
            (object) [
                'id' => 2,
                'name' => 'Injera Bread',
                'code' => 'RCP002',
                'description' => 'Traditional Ethiopian sourdough flatbread',
                'category' => 'side_dish',
                'serving_size' => 8,
                'prep_time' => 15,
                'cook_time' => 20,
                'total_time' => 35,
                'difficulty' => 'easy',
                'cost_per_serving' => 1.25,
                'total_cost' => 10.00,
                'status' => 'active',
                'difficulty_badge_class' => 'difficulty-easy',
                'status_badge_class' => 'status-active',
                'formatted_total_time' => '35m',
            ],
            (object) [
                'id' => 3,
                'name' => 'Tibs (Sautéed Beef)',
                'code' => 'RCP003',
                'description' => 'Spiced sautéed beef with onions and peppers',
                'category' => 'main_course',
                'serving_size' => 4,
                'prep_time' => 20,
                'cook_time' => 25,
                'total_time' => 45,
                'difficulty' => 'medium',
                'cost_per_serving' => 12.75,
                'total_cost' => 51.00,
                'status' => 'active',
                'difficulty_badge_class' => 'difficulty-medium',
                'status_badge_class' => 'status-active',
                'formatted_total_time' => '45m',
            ],
            (object) [
                'id' => 4,
                'name' => 'Shiro Wat',
                'code' => 'RCP004',
                'description' => 'Chickpea flour stew with spices',
                'category' => 'main_course',
                'serving_size' => 5,
                'prep_time' => 10,
                'cook_time' => 30,
                'total_time' => 40,
                'difficulty' => 'easy',
                'cost_per_serving' => 3.20,
                'total_cost' => 16.00,
                'status' => 'draft',
                'difficulty_badge_class' => 'difficulty-easy',
                'status_badge_class' => 'status-draft',
                'formatted_total_time' => '40m',
            ],
            (object) [
                'id' => 5,
                'name' => 'Ethiopian Coffee',
                'code' => 'RCP005',
                'description' => 'Traditional Ethiopian coffee ceremony brew',
                'category' => 'beverage',
                'serving_size' => 3,
                'prep_time' => 45,
                'cook_time' => 15,
                'total_time' => 60,
                'difficulty' => 'hard',
                'cost_per_serving' => null,
                'total_cost' => null,
                'status' => 'testing',
                'difficulty_badge_class' => 'difficulty-hard',
                'status_badge_class' => 'status-testing',
                'formatted_total_time' => '1h',
            ],
        ]);

        // Apply filters to sample data
        $filteredRecipes = $sampleRecipes;

        if ($request->filled('category') && $request->category !== 'all') {
            $filteredRecipes = $filteredRecipes->where('category', $request->category);
        }

        if ($request->filled('difficulty') && $request->difficulty !== 'all') {
            $filteredRecipes = $filteredRecipes->where('difficulty', $request->difficulty);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $filteredRecipes = $filteredRecipes->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $filteredRecipes = $filteredRecipes->filter(function ($recipe) use ($search) {
                return str_contains(strtolower($recipe->name), $search) ||
                       str_contains(strtolower($recipe->code), $search) ||
                       str_contains(strtolower($recipe->description), $search);
            });
        }

        // Create a simple paginator-like object
        $recipes = (object) [
            'data' => $filteredRecipes->values(),
            'hasPages' => function() { return false; },
            'links' => function() { return ''; },
        ];

        // Static filter options
        $categories = ['appetizer', 'main_course', 'dessert', 'beverage', 'sauce', 'side_dish', 'soup', 'salad'];
        $difficulties = ['easy', 'medium', 'hard', 'expert'];
        $statuses = ['active', 'inactive', 'draft', 'testing'];

        // Calculate summary statistics from sample data
        $totalRecipes = $sampleRecipes->count();
        $activeRecipes = $sampleRecipes->where('status', 'active')->count();
        $draftRecipes = $sampleRecipes->where('status', 'draft')->count();
        $avgCostPerServing = $sampleRecipes->whereNotNull('cost_per_serving')->avg('cost_per_serving') ?? 0;

        return view('admin.inventory.recipes.index', compact(
            'recipes',
            'categories',
            'difficulties',
            'statuses',
            'totalRecipes',
            'activeRecipes',
            'draftRecipes',
            'avgCostPerServing'
        ));
    }

    /**
     * Show the form for creating a new recipe
     */
    public function create(): View
    {
        $ingredients = Ingredient::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('admin.inventory.recipes.create', compact('ingredients'));
    }

    /**
     * Store a newly created recipe
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:recipes,code',
            'description' => 'nullable|string',
            'category' => 'required|in:appetizer,main_course,dessert,beverage,sauce,side_dish,soup,salad',
            'serving_size' => 'required|integer|min:1',
            'prep_time' => 'nullable|integer|min:0',
            'cook_time' => 'nullable|integer|min:0',
            'difficulty' => 'required|in:easy,medium,hard,expert',
            'yield' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive,draft,testing',
            'notes' => 'nullable|string',
            'tags' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();

            // Calculate total time
            $validated['total_time'] = ($validated['prep_time'] ?? 0) + ($validated['cook_time'] ?? 0);

            $recipe = Recipe::create($validated);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('inventory.recipes.recipe_created'),
                'recipe' => $recipe,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating recipe: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified recipe
     */
    public function show(Recipe $recipe): View
    {
        $recipe->load(['recipeIngredients.ingredient', 'instructions']);
        
        return view('admin.inventory.recipes.show', compact('recipe'));
    }

    /**
     * Show the form for editing the specified recipe
     */
    public function edit(Recipe $recipe): View
    {
        $recipe->load(['recipeIngredients.ingredient', 'instructions']);
        
        $ingredients = Ingredient::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('admin.inventory.recipes.edit', compact('recipe', 'ingredients'));
    }

    /**
     * Update the specified recipe
     */
    public function update(Request $request, Recipe $recipe): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:recipes,code,' . $recipe->id,
            'description' => 'nullable|string',
            'category' => 'required|in:appetizer,main_course,dessert,beverage,sauce,side_dish,soup,salad',
            'serving_size' => 'required|integer|min:1',
            'prep_time' => 'nullable|integer|min:0',
            'cook_time' => 'nullable|integer|min:0',
            'difficulty' => 'required|in:easy,medium,hard,expert',
            'yield' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive,draft,testing',
            'notes' => 'nullable|string',
            'tags' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();

            // Calculate total time
            $validated['total_time'] = ($validated['prep_time'] ?? 0) + ($validated['cook_time'] ?? 0);

            $recipe->update($validated);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('inventory.recipes.recipe_updated'),
                'recipe' => $recipe,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating recipe: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified recipe
     */
    public function destroy(Recipe $recipe): JsonResponse
    {
        try {
            $recipe->delete();

            return response()->json([
                'success' => true,
                'message' => __('inventory.recipes.recipe_deleted'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting recipe: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Duplicate a recipe
     */
    public function duplicate(Recipe $recipe): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Create new recipe with modified name and code
            $newRecipe = $recipe->replicate();
            $newRecipe->name = $recipe->name . ' (Copy)';
            $newRecipe->code = $recipe->code . '_COPY_' . time();
            $newRecipe->status = 'draft';
            $newRecipe->save();

            // Copy ingredients
            foreach ($recipe->recipeIngredients as $ingredient) {
                $newIngredient = $ingredient->replicate();
                $newIngredient->recipe_id = $newRecipe->id;
                $newIngredient->save();
            }

            // Copy instructions
            foreach ($recipe->instructions as $instruction) {
                $newInstruction = $instruction->replicate();
                $newInstruction->recipe_id = $newRecipe->id;
                $newInstruction->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('inventory.recipes.recipe_duplicated'),
                'recipe' => $newRecipe,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error duplicating recipe: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Calculate recipe costs
     */
    public function calculateCosts(Recipe $recipe): JsonResponse
    {
        try {
            $totalCost = $recipe->calculateTotalCost();
            $costPerServing = $recipe->calculateCostPerServing();

            $recipe->update([
                'total_cost' => $totalCost,
                'cost_per_serving' => $costPerServing,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Costs calculated successfully',
                'total_cost' => $totalCost,
                'cost_per_serving' => $costPerServing,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error calculating costs: ' . $e->getMessage(),
            ], 500);
        }
    }
}