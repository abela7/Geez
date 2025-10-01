<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display the inventory overview page.
     */
    public function index(Request $request)
    {
        // Sample data for your UI
        $stats = [
            'total_items' => 156,
            'low_stock' => 12,
            'out_of_stock' => 3,
            'total_value' => 125000.50,
        ];

        $recentMovements = [
            [
                'id' => 1,
                'item' => 'Flour (All Purpose)',
                'type' => 'in',
                'quantity' => 25.0,
                'date' => now()->subHours(2),
            ],
            [
                'id' => 2,
                'item' => 'Tomatoes',
                'type' => 'out',
                'quantity' => 5.0,
                'date' => now()->subHours(1),
            ],
        ];

        return view('admin.inventory.index', compact('stats', 'recentMovements'));
    }
}
