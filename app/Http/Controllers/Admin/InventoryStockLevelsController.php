<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InventoryStockLevelsController extends Controller
{
    /**
     * Display the stock levels page.
     */
    public function index(Request $request)
    {
        // Sample data for your UI - replace with real data later
        $stats = [
            'total_items' => 156,
            'low_stock' => 12,
            'out_of_stock' => 3,
            'total_value' => 125000.50,
        ];

        $stockLevels = [
            [
                'id' => 1,
                'name' => 'Flour (All Purpose)',
                'category' => 'Dry Goods',
                'current_stock' => 45.5,
                'min_stock' => 20.0,
                'max_stock' => 100.0,
                'unit' => 'kg',
                'status' => 'good',
                'last_updated' => now()->subHours(2),
            ],
            [
                'id' => 2,
                'name' => 'Tomatoes',
                'category' => 'Fresh Produce',
                'current_stock' => 8.2,
                'min_stock' => 15.0,
                'max_stock' => 50.0,
                'unit' => 'kg',
                'status' => 'low',
                'last_updated' => now()->subHours(1),
            ],
            [
                'id' => 3,
                'name' => 'Cooking Oil',
                'category' => 'Cooking Essentials',
                'current_stock' => 0.0,
                'min_stock' => 10.0,
                'max_stock' => 30.0,
                'unit' => 'liters',
                'status' => 'out',
                'last_updated' => now()->subDays(1),
            ],
        ];

        $categories = [
            'Dry Goods',
            'Fresh Produce',
            'Cooking Essentials',
            'Spices & Seasonings',
            'Dairy Products',
            'Meat & Poultry',
        ];

        return view('admin.inventory.stock-levels.index', compact(
            'stats',
            'stockLevels',
            'categories'
        ));
    }
}
