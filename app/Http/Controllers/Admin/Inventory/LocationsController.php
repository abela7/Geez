<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class LocationsController extends Controller
{
    /**
     * Display the locations index page
     */
    public function index(Request $request): View
    {
        // Sample location data for UI demo
        $sampleLocations = collect([
            (object) [
                'id' => 1,
                'name' => 'Main Freezer',
                'type' => 'freezer',
                'description' => 'Primary freezer unit for frozen ingredients and prepared items',
                'status' => 'active',
                'capacity_percentage' => 75,
                'items_count' => 24,
                'created_date' => '2025-08-15',
                'last_updated' => '2025-09-10',
                'formatted_created' => '15 Aug 2025',
                'formatted_updated' => '10 Sep 2025',
                'status_badge_class' => 'location-active',
                'type_badge_class' => 'type-freezer',
                'capacity_level' => 'high',
                'capacity_color' => 'warning',
                'items_stored' => [
                    (object) ['name' => 'Frozen Vegetables', 'quantity' => '15 kg', 'date_added' => '2025-09-08'],
                    (object) ['name' => 'Ice Cream', 'quantity' => '8 L', 'date_added' => '2025-09-09'],
                    (object) ['name' => 'Frozen Meat', 'quantity' => '12 kg', 'date_added' => '2025-09-10'],
                ],
            ],
            (object) [
                'id' => 2,
                'name' => 'Walk-in Fridge',
                'type' => 'fridge',
                'description' => 'Large refrigeration unit for fresh produce and dairy products',
                'status' => 'active',
                'capacity_percentage' => 45,
                'items_count' => 38,
                'created_date' => '2025-08-10',
                'last_updated' => '2025-09-09',
                'formatted_created' => '10 Aug 2025',
                'formatted_updated' => '9 Sep 2025',
                'status_badge_class' => 'location-active',
                'type_badge_class' => 'type-fridge',
                'capacity_level' => 'medium',
                'capacity_color' => 'success',
                'items_stored' => [
                    (object) ['name' => 'Fresh Vegetables', 'quantity' => '25 kg', 'date_added' => '2025-09-09'],
                    (object) ['name' => 'Dairy Products', 'quantity' => '18 L', 'date_added' => '2025-09-08'],
                    (object) ['name' => 'Fresh Herbs', 'quantity' => '3 kg', 'date_added' => '2025-09-10'],
                ],
            ],
            (object) [
                'id' => 3,
                'name' => 'Dry Storage Room',
                'type' => 'dry_storage',
                'description' => 'Climate-controlled room for grains, spices, and non-perishables',
                'status' => 'active',
                'capacity_percentage' => 60,
                'items_count' => 52,
                'created_date' => '2025-08-05',
                'last_updated' => '2025-09-08',
                'formatted_created' => '5 Aug 2025',
                'formatted_updated' => '8 Sep 2025',
                'status_badge_class' => 'location-active',
                'type_badge_class' => 'type-dry-storage',
                'capacity_level' => 'medium',
                'capacity_color' => 'info',
                'items_stored' => [
                    (object) ['name' => 'Rice & Grains', 'quantity' => '100 kg', 'date_added' => '2025-09-05'],
                    (object) ['name' => 'Spices & Seasonings', 'quantity' => '15 kg', 'date_added' => '2025-09-07'],
                    (object) ['name' => 'Canned Goods', 'quantity' => '45 units', 'date_added' => '2025-09-08'],
                ],
            ],
            (object) [
                'id' => 4,
                'name' => 'Bar Storage',
                'type' => 'bar',
                'description' => 'Dedicated storage for beverages, wines, and bar supplies',
                'status' => 'active',
                'capacity_percentage' => 30,
                'items_count' => 18,
                'created_date' => '2025-08-20',
                'last_updated' => '2025-09-07',
                'formatted_created' => '20 Aug 2025',
                'formatted_updated' => '7 Sep 2025',
                'status_badge_class' => 'location-active',
                'type_badge_class' => 'type-bar',
                'capacity_level' => 'low',
                'capacity_color' => 'success',
                'items_stored' => [
                    (object) ['name' => 'Wine Collection', 'quantity' => '24 bottles', 'date_added' => '2025-09-05'],
                    (object) ['name' => 'Spirits', 'quantity' => '12 bottles', 'date_added' => '2025-09-06'],
                    (object) ['name' => 'Beer', 'quantity' => '48 cans', 'date_added' => '2025-09-07'],
                ],
            ],
            (object) [
                'id' => 5,
                'name' => 'Prep Area Storage',
                'type' => 'prep_area',
                'description' => 'Small storage area near food preparation stations',
                'status' => 'maintenance',
                'capacity_percentage' => 0,
                'items_count' => 0,
                'created_date' => '2025-08-25',
                'last_updated' => '2025-09-05',
                'formatted_created' => '25 Aug 2025',
                'formatted_updated' => '5 Sep 2025',
                'status_badge_class' => 'location-maintenance',
                'type_badge_class' => 'type-prep-area',
                'capacity_level' => 'empty',
                'capacity_color' => 'secondary',
                'items_stored' => [],
            ],
        ]);

        // Apply filters to sample data
        $filteredLocations = $sampleLocations;

        if ($request->filled('type') && $request->type !== 'all') {
            $filteredLocations = $filteredLocations->where('type', $request->type);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $filteredLocations = $filteredLocations->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $filteredLocations = $filteredLocations->filter(function ($location) use ($search) {
                return str_contains(strtolower($location->name), $search) ||
                       str_contains(strtolower($location->description), $search);
            });
        }

        // Create a simple paginator-like object
        $locations = (object) [
            'data' => $filteredLocations->values(),
            'hasPages' => function() { return false; },
            'links' => function() { return ''; },
        ];

        // Static filter options
        $locationTypes = ['fridge', 'freezer', 'pantry', 'bar', 'storage_room', 'warehouse', 'kitchen', 'prep_area', 'dry_storage', 'cold_storage'];
        $locationStatuses = ['active', 'inactive', 'maintenance', 'full', 'reserved'];

        // Calculate summary statistics from sample data
        $totalLocations = $sampleLocations->count();
        $activeLocations = $sampleLocations->where('status', 'active')->count();
        $inactiveLocations = $sampleLocations->where('status', 'inactive')->count();
        $locationsAtCapacity = $sampleLocations->where('capacity_percentage', '>=', 90)->count();
        $averageCapacity = $sampleLocations->avg('capacity_percentage');
        $totalItemsStored = $sampleLocations->sum('items_count');

        return view('admin.inventory.locations.index', compact(
            'locations',
            'locationTypes',
            'locationStatuses',
            'totalLocations',
            'activeLocations',
            'inactiveLocations',
            'locationsAtCapacity',
            'averageCapacity',
            'totalItemsStored'
        ));
    }

    /**
     * Store a new location (UI demo)
     */
    public function store(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => __('inventory.locations.location_created'),
        ]);
    }

    /**
     * Show location details (UI demo)
     */
    public function show($id): JsonResponse
    {
        // Sample location detail for UI demo
        $location = (object) [
            'id' => $id,
            'name' => 'Main Freezer',
            'type' => 'freezer',
            'description' => 'Primary freezer unit for frozen ingredients and prepared items',
            'status' => 'active',
            'capacity_percentage' => 75,
            'items_count' => 24,
            'created_date' => '2025-08-15',
            'items_stored' => [
                (object) ['name' => 'Frozen Vegetables', 'quantity' => '15 kg', 'date_added' => '2025-09-08'],
                (object) ['name' => 'Ice Cream', 'quantity' => '8 L', 'date_added' => '2025-09-09'],
                (object) ['name' => 'Frozen Meat', 'quantity' => '12 kg', 'date_added' => '2025-09-10'],
            ],
        ];

        return response()->json([
            'success' => true,
            'location' => $location,
        ]);
    }

    /**
     * Update location (UI demo)
     */
    public function update(Request $request, $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => __('inventory.locations.location_updated'),
        ]);
    }

    /**
     * Delete location (UI demo)
     */
    public function destroy($id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => __('inventory.locations.location_deleted'),
        ]);
    }

    /**
     * Activate location (UI demo)
     */
    public function activate($id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => __('inventory.locations.location_activated'),
        ]);
    }

    /**
     * Deactivate location (UI demo)
     */
    public function deactivate($id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => __('inventory.locations.location_deactivated'),
        ]);
    }
}