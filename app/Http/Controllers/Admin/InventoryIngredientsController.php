<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InventoryIngredientsController extends Controller
{
    /**
     * Display the ingredients page.
     */
    public function index(Request $request)
    {
        // Sample statistics data for your UI
        $statistics = [
            'total_ingredients' => 156,
            'active_ingredients' => 142,
            'low_stock_ingredients' => 12,
            'out_of_stock_ingredients' => 2,
        ];

        // Sample data for your UI
        $ingredients = [
            [
                'id' => 1,
                'name' => 'Flour (All Purpose)',
                'category' => 'Dry Goods',
                'unit' => 'kg',
                'current_stock' => 45.5,
                'min_stock' => 20.0,
                'status' => 'good',
            ],
            [
                'id' => 2,
                'name' => 'Tomatoes',
                'category' => 'Fresh Produce',
                'unit' => 'kg',
                'current_stock' => 8.2,
                'min_stock' => 15.0,
                'status' => 'low',
            ],
        ];

        $categories = ['Dry Goods', 'Fresh Produce', 'Cooking Essentials'];

        return view('admin.inventory.ingredients.index', compact('statistics', 'ingredients', 'categories'));
    }
}
