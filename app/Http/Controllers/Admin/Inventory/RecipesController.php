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
        $query = Recipe::with(['recipeIngredients.ingredient', 'instructions']);

        // Apply filters
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('difficulty') && $request->difficulty !== 'all') {
            $query->where('difficulty', $request->difficulty);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');

        $allowedSorts = ['name', 'code', 'category', 'difficulty', 'total_cost', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDirection);
        } else {
            $query->orderBy('name', 'asc');
        }

        $recipes = $query->paginate(20)->withQueryString();

        // Get filter options
        $categories = Recipe::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->toArray();

        $difficulties = ['easy', 'medium', 'hard', 'expert'];
        $statuses = ['active', 'inactive', 'draft', 'testing'];

        // Calculate summary statistics
        $totalRecipes = Recipe::count();
        $activeRecipes = Recipe::where('status', 'active')->count();
        $draftRecipes = Recipe::where('status', 'draft')->count();
        $avgCostPerServing = Recipe::where('status', 'active')
            ->whereNotNull('cost_per_serving')
            ->avg('cost_per_serving') ?? 0;

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