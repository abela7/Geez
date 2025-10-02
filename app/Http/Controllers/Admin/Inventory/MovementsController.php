<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MovementsController extends Controller
{
    /**
     * Display the movements index page
     */
    public function index(Request $request): View
    {
        // Sample movement data for UI demo
        $sampleMovements = collect([
            (object) [
                'id' => 1,
                'item_name' => 'Ethiopian Coffee Beans',
                'item_code' => 'COFFEE001',
                'quantity' => 50.0,
                'unit' => 'kg',
                'movement_type' => 'receive',
                'from_location' => null,
                'to_location' => 'warehouse',
                'location_display' => 'Main Warehouse',
                'staff_name' => 'Ahmed Hassan',
                'staff_role' => 'Inventory Clerk',
                'date_time' => '2025-09-10 08:30:00',
                'formatted_date' => '10 Sep 2025, 8:30 AM',
                'time_ago' => '2 hours ago',
                'notes' => 'Weekly coffee delivery from supplier',
                'reference' => 'PO-2025-001',
                'movement_badge_class' => 'movement-receive',
            ],
            (object) [
                'id' => 2,
                'item_name' => 'Berbere Spice Mix',
                'item_code' => 'SPICE002',
                'quantity' => 5.0,
                'unit' => 'kg',
                'movement_type' => 'transfer',
                'from_location' => 'warehouse',
                'to_location' => 'kitchen',
                'location_display' => 'Warehouse → Kitchen',
                'staff_name' => 'Meron Tadesse',
                'staff_role' => 'Chef',
                'date_time' => '2025-09-10 07:15:00',
                'formatted_date' => '10 Sep 2025, 7:15 AM',
                'time_ago' => '3 hours ago',
                'notes' => 'Transfer for today\'s menu preparation',
                'reference' => 'TRF-2025-045',
                'movement_badge_class' => 'movement-transfer',
            ],
            (object) [
                'id' => 3,
                'item_name' => 'Injera Flour',
                'item_code' => 'FLOUR001',
                'quantity' => 2.5,
                'unit' => 'kg',
                'movement_type' => 'waste',
                'from_location' => 'kitchen',
                'to_location' => null,
                'location_display' => 'Kitchen',
                'staff_name' => 'Dawit Bekele',
                'staff_role' => 'Cook',
                'date_time' => '2025-09-09 18:45:00',
                'formatted_date' => '9 Sep 2025, 6:45 PM',
                'time_ago' => '1 day ago',
                'notes' => 'Expired flour - past use by date',
                'reference' => 'WST-2025-012',
                'movement_badge_class' => 'movement-waste',
            ],
            (object) [
                'id' => 4,
                'item_name' => 'Teff Grain',
                'item_code' => 'GRAIN001',
                'quantity' => 10.0,
                'unit' => 'kg',
                'movement_type' => 'adjust',
                'from_location' => null,
                'to_location' => 'warehouse',
                'location_display' => 'Main Warehouse',
                'staff_name' => 'Sara Alemayehu',
                'staff_role' => 'Manager',
                'date_time' => '2025-09-09 16:20:00',
                'formatted_date' => '9 Sep 2025, 4:20 PM',
                'time_ago' => '1 day ago',
                'notes' => 'Stock count adjustment - found extra inventory',
                'reference' => 'ADJ-2025-008',
                'movement_badge_class' => 'movement-adjust',
            ],
            (object) [
                'id' => 5,
                'item_name' => 'Red Lentils',
                'item_code' => 'LENTIL001',
                'quantity' => 3.0,
                'unit' => 'kg',
                'movement_type' => 'return',
                'from_location' => 'kitchen',
                'to_location' => 'warehouse',
                'location_display' => 'Kitchen → Warehouse',
                'staff_name' => 'Hanan Yosef',
                'staff_role' => 'Cook',
                'date_time' => '2025-09-09 14:10:00',
                'formatted_date' => '9 Sep 2025, 2:10 PM',
                'time_ago' => '1 day ago',
                'notes' => 'Unused lentils from cancelled order',
                'reference' => 'RET-2025-003',
                'movement_badge_class' => 'movement-return',
            ],
        ]);

        // Apply filters to sample data
        $filteredMovements = $sampleMovements;

        if ($request->filled('movement_type') && $request->movement_type !== 'all') {
            $filteredMovements = $filteredMovements->where('movement_type', $request->movement_type);
        }

        if ($request->filled('location') && $request->location !== 'all') {
            $filteredMovements = $filteredMovements->filter(function ($movement) use ($request) {
                return $movement->from_location === $request->location ||
                       $movement->to_location === $request->location;
            });
        }

        if ($request->filled('staff') && $request->staff !== 'all') {
            $filteredMovements = $filteredMovements->where('staff_name', $request->staff);
        }

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $filteredMovements = $filteredMovements->filter(function ($movement) use ($search) {
                return str_contains(strtolower($movement->item_name), $search) ||
                       str_contains(strtolower($movement->item_code), $search);
            });
        }

        // Create a simple paginator-like object
        $movements = (object) [
            'data' => $filteredMovements->values(),
            'hasPages' => function () {
                return false;
            },
            'links' => function () {
                return '';
            },
        ];

        // Static filter options
        $movementTypes = ['receive', 'transfer', 'adjust', 'waste', 'return', 'sale', 'production'];
        $locations = ['warehouse', 'kitchen', 'storage', 'freezer', 'dry_storage', 'bar', 'prep_area'];
        $staffMembers = ['Ahmed Hassan', 'Meron Tadesse', 'Dawit Bekele', 'Sara Alemayehu', 'Hanan Yosef'];

        // Calculate summary statistics from sample data
        $totalMovements = $sampleMovements->count();
        $movementsToday = $sampleMovements->filter(function ($movement) {
            return date('Y-m-d', strtotime($movement->date_time)) === date('Y-m-d');
        })->count();
        $movementsThisWeek = $sampleMovements->filter(function ($movement) {
            return date('W Y', strtotime($movement->date_time)) === date('W Y');
        })->count();
        $recentMovements = $sampleMovements->take(3);

        return view('admin.inventory.movements.index', compact(
            'movements',
            'movementTypes',
            'locations',
            'staffMembers',
            'totalMovements',
            'movementsToday',
            'movementsThisWeek',
            'recentMovements'
        ));
    }

    /**
     * Store a new movement (UI demo)
     */
    public function store(Request $request): JsonResponse
    {
        // TODO: Validate and store movement
        return response()->json([
            'success' => true,
            'message' => __('inventory.movements.movement_created'),
        ]);
    }

    /**
     * Show movement details (UI demo)
     */
    public function show($id): JsonResponse
    {
        // Sample movement detail for UI demo
        $movement = (object) [
            'id' => $id,
            'item_name' => 'Ethiopian Coffee Beans',
            'item_code' => 'COFFEE001',
            'item_image' => '/images/coffee-beans.jpg',
            'quantity' => 50.0,
            'unit' => 'kg',
            'movement_type' => 'receive',
            'from_location' => null,
            'to_location' => 'warehouse',
            'location_display' => 'Main Warehouse',
            'staff_name' => 'Ahmed Hassan',
            'staff_role' => 'Inventory Clerk',
            'date_time' => '2025-09-10 08:30:00',
            'formatted_date' => '10 Sep 2025, 8:30 AM',
            'notes' => 'Weekly coffee delivery from supplier',
            'reference' => 'PO-2025-001',
        ];

        return response()->json([
            'success' => true,
            'movement' => $movement,
        ]);
    }

    /**
     * Update movement (UI demo)
     */
    public function update(Request $request, $id): JsonResponse
    {
        // TODO: Validate and update movement
        return response()->json([
            'success' => true,
            'message' => __('inventory.movements.movement_updated'),
        ]);
    }

    /**
     * Delete movement (UI demo)
     */
    public function destroy($id): JsonResponse
    {
        // TODO: Delete movement
        return response()->json([
            'success' => true,
            'message' => __('inventory.movements.movement_deleted'),
        ]);
    }
}
