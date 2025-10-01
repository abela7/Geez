<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Injera;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FlourManagementController extends Controller
{
    /**
     * Display the flour management page.
     */
    public function index(Request $request): View
    {
        // Get flour inventory data (we'll create the model later)
        $flours = $this->getFlourInventory($request);
        
        // Get filter options
        $flourTypes = ['Teff', 'Wheat', 'Barley', 'Sorghum', 'Mixed'];
        $suppliers = $this->getSuppliers();
        
        // Get summary statistics
        $statistics = $this->getFlourStatistics();

        return view('admin.injera.flour-management.index', compact(
            'flours',
            'flourTypes',
            'suppliers',
            'statistics'
        ));
    }

    /**
     * Store a new flour entry.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|in:Teff,Wheat,Barley,Sorghum,Mixed',
            'package_size' => 'required|numeric|min:0.1',
            'price_per_package' => 'required|numeric|min:0',
            'supplier_name' => 'required|string|max:255',
            'current_stock' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Create flour entry (we'll implement the actual model later)
            $flourData = [
                'name' => $request->name,
                'type' => $request->type,
                'package_size' => $request->package_size,
                'price_per_package' => $request->price_per_package,
                'price_per_kg' => $request->price_per_package / $request->package_size,
                'supplier_name' => $request->supplier_name,
                'current_stock' => $request->current_stock,
                'notes' => $request->notes,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // TODO: Replace with actual model creation
            // Flour::create($flourData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('injera.flour_management.flour_added_success')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to add flour: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update flour stock levels.
     */
    public function updateStock(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'flour_id' => 'required|integer',
            'adjustment_type' => 'required|in:purchase,usage,adjustment',
            'quantity' => 'required|numeric',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // TODO: Implement actual stock update logic
            return response()->json([
                'success' => true,
                'message' => __('injera.flour_management.stock_updated_success')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update stock: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete flour entry.
     */
    public function destroy(Request $request, int $flourId): JsonResponse
    {
        try {
            // TODO: Implement actual deletion logic
            return response()->json([
                'success' => true,
                'message' => __('injera.flour_management.flour_deleted_success')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete flour: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get flour inventory data (temporary mock data).
     */
    private function getFlourInventory(Request $request): array
    {
        // Mock data - replace with actual database queries later
        return [
            [
                'id' => 1,
                'name' => 'Premium Teff Flour',
                'type' => 'Teff',
                'package_size' => 25.0,
                'price_per_package' => 45.00,
                'price_per_kg' => 1.80,
                'supplier_name' => 'Ethiopian Import Co.',
                'current_stock' => 150.0,
                'low_stock_threshold' => 50.0,
                'last_purchase' => '2025-01-10',
                'notes' => 'High quality, dark teff',
                'status' => 'in_stock'
            ],
            [
                'id' => 2,
                'name' => 'White Wheat Flour',
                'type' => 'Wheat',
                'package_size' => 50.0,
                'price_per_package' => 42.50,
                'price_per_kg' => 0.85,
                'supplier_name' => 'Local Mill Supply',
                'current_stock' => 200.0,
                'low_stock_threshold' => 75.0,
                'last_purchase' => '2025-01-08',
                'notes' => 'All-purpose wheat flour',
                'status' => 'in_stock'
            ],
            [
                'id' => 3,
                'name' => 'Organic Barley Flour',
                'type' => 'Barley',
                'package_size' => 20.0,
                'price_per_package' => 24.00,
                'price_per_kg' => 1.20,
                'supplier_name' => 'Organic Grains Ltd',
                'current_stock' => 30.0,
                'low_stock_threshold' => 40.0,
                'last_purchase' => '2025-01-05',
                'notes' => 'Certified organic',
                'status' => 'low_stock'
            ]
        ];
    }

    /**
     * Get suppliers list.
     */
    private function getSuppliers(): array
    {
        return [
            'Ethiopian Import Co.',
            'Local Mill Supply',
            'Organic Grains Ltd',
            'Premium Flour Distributors',
            'Traditional Grains Co.'
        ];
    }

    /**
     * Get flour statistics for summary cards.
     */
    private function getFlourStatistics(): array
    {
        // Mock statistics - replace with actual calculations
        return [
            'total_flour_types' => 3,
            'total_stock_kg' => 380.0,
            'low_stock_items' => 1,
            'total_value' => 567.50,
            'avg_price_per_kg' => 1.28,
            'suppliers_count' => 3,
        ];
    }
}
