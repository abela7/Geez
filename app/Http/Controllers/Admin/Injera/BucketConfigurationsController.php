<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Injera;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class BucketConfigurationsController extends Controller
{
    /**
     * Display the bucket configurations page.
     */
    public function index(Request $request): View
    {
        // Get bucket configurations data
        $buckets = $this->getBucketConfigurations($request);

        // Get available flours for recipe building
        $availableFlours = $this->getAvailableFlours();

        // Get summary statistics
        $statistics = $this->getBucketStatistics();

        return view('admin.injera.bucket-configurations.index', compact(
            'buckets',
            'availableFlours',
            'statistics'
        ));
    }

    /**
     * Store a new bucket configuration.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'capacity' => 'required|numeric|min:1',
            'flour_recipe' => 'required|array|min:1',
            'flour_recipe.*.flour_type' => 'required|string',
            'flour_recipe.*.quantity' => 'required|numeric|min:0.1',
            'cold_water' => 'required|numeric|min:0',
            'hot_water' => 'required|numeric|min:0',
            'expected_yield' => 'required|integer|min:1',
            'electricity_cost' => 'required|numeric|min:0',
            'labor_cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Calculate total flour cost
            $totalFlourCost = 0;
            foreach ($request->flour_recipe as $flour) {
                $flourPrice = $this->getFlourPrice($flour['flour_type']);
                $totalFlourCost += $flour['quantity'] * $flourPrice;
            }

            // Calculate total cost
            $totalCost = $totalFlourCost + $request->electricity_cost + $request->labor_cost;
            $costPerInjera = $totalCost / $request->expected_yield;

            // Create bucket configuration (we'll implement the actual model later)
            $bucketData = [
                'name' => $request->name,
                'capacity' => $request->capacity,
                'flour_recipe' => json_encode($request->flour_recipe),
                'cold_water' => $request->cold_water,
                'hot_water' => $request->hot_water,
                'expected_yield' => $request->expected_yield,
                'electricity_cost' => $request->electricity_cost,
                'labor_cost' => $request->labor_cost,
                'total_cost' => $totalCost,
                'cost_per_injera' => $costPerInjera,
                'notes' => $request->notes,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // TODO: Replace with actual model creation
            // BucketConfiguration::create($bucketData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('injera.bucket_configurations.bucket_created_success'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create bucket configuration: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update bucket configuration.
     */
    public function update(Request $request, int $bucketId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'capacity' => 'required|numeric|min:1',
            'flour_recipe' => 'required|array|min:1',
            'flour_recipe.*.flour_type' => 'required|string',
            'flour_recipe.*.quantity' => 'required|numeric|min:0.1',
            'cold_water' => 'required|numeric|min:0',
            'hot_water' => 'required|numeric|min:0',
            'expected_yield' => 'required|integer|min:1',
            'electricity_cost' => 'required|numeric|min:0',
            'labor_cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // TODO: Implement actual update logic
            return response()->json([
                'success' => true,
                'message' => __('injera.bucket_configurations.bucket_updated_success'),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update bucket configuration: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete bucket configuration.
     */
    public function destroy(Request $request, int $bucketId): JsonResponse
    {
        try {
            // TODO: Implement actual deletion logic
            return response()->json([
                'success' => true,
                'message' => __('injera.bucket_configurations.bucket_deleted_success'),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete bucket configuration: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Duplicate bucket configuration.
     */
    public function duplicate(Request $request, int $bucketId): JsonResponse
    {
        try {
            // TODO: Implement actual duplication logic
            return response()->json([
                'success' => true,
                'message' => __('injera.bucket_configurations.bucket_duplicated_success'),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to duplicate bucket configuration: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get bucket configurations data (temporary mock data).
     */
    private function getBucketConfigurations(Request $request): array
    {
        // Mock data - replace with actual database queries later
        return [
            [
                'id' => 1,
                'name' => 'Large Production Bucket',
                'capacity' => 90.0,
                'flour_recipe' => [
                    ['flour_type' => 'Teff', 'quantity' => 15.0, 'cost' => 27.00],
                    ['flour_type' => 'Wheat', 'quantity' => 5.0, 'cost' => 4.25],
                ],
                'total_flour' => 20.0,
                'total_flour_cost' => 31.25,
                'cold_water' => 25.0,
                'hot_water' => 8.0,
                'total_water' => 33.0,
                'expected_yield' => 130,
                'electricity_cost' => 2.50,
                'labor_cost' => 15.00,
                'total_cost' => 48.75,
                'cost_per_injera' => 0.375,
                'notes' => 'Standard production recipe for high-volume days',
                'created_at' => '2025-01-10',
                'is_active' => true,
            ],
            [
                'id' => 2,
                'name' => 'Medium Batch',
                'capacity' => 60.0,
                'flour_recipe' => [
                    ['flour_type' => 'Teff', 'quantity' => 10.0, 'cost' => 18.00],
                    ['flour_type' => 'Wheat', 'quantity' => 3.0, 'cost' => 2.55],
                ],
                'total_flour' => 13.0,
                'total_flour_cost' => 20.55,
                'cold_water' => 16.0,
                'hot_water' => 5.0,
                'total_water' => 21.0,
                'expected_yield' => 85,
                'electricity_cost' => 1.75,
                'labor_cost' => 10.00,
                'total_cost' => 32.30,
                'cost_per_injera' => 0.380,
                'notes' => 'Medium batch for regular production',
                'created_at' => '2025-01-08',
                'is_active' => true,
            ],
            [
                'id' => 3,
                'name' => 'Premium Teff Only',
                'capacity' => 45.0,
                'flour_recipe' => [
                    ['flour_type' => 'Teff', 'quantity' => 12.0, 'cost' => 21.60],
                ],
                'total_flour' => 12.0,
                'total_flour_cost' => 21.60,
                'cold_water' => 15.0,
                'hot_water' => 4.0,
                'total_water' => 19.0,
                'expected_yield' => 70,
                'electricity_cost' => 1.50,
                'labor_cost' => 8.00,
                'total_cost' => 31.10,
                'cost_per_injera' => 0.444,
                'notes' => 'Pure teff recipe for special occasions',
                'created_at' => '2025-01-05',
                'is_active' => false,
            ],
        ];
    }

    /**
     * Get available flours for recipe building.
     */
    private function getAvailableFlours(): array
    {
        return [
            ['type' => 'Teff', 'price_per_kg' => 1.80, 'available_stock' => 150.0],
            ['type' => 'Wheat', 'price_per_kg' => 0.85, 'available_stock' => 200.0],
            ['type' => 'Barley', 'price_per_kg' => 1.20, 'available_stock' => 30.0],
            ['type' => 'Sorghum', 'price_per_kg' => 1.35, 'available_stock' => 75.0],
            ['type' => 'Mixed', 'price_per_kg' => 1.10, 'available_stock' => 50.0],
        ];
    }

    /**
     * Get flour price by type.
     */
    private function getFlourPrice(string $flourType): float
    {
        $prices = [
            'Teff' => 1.80,
            'Wheat' => 0.85,
            'Barley' => 1.20,
            'Sorghum' => 1.35,
            'Mixed' => 1.10,
        ];

        return $prices[$flourType] ?? 1.00;
    }

    /**
     * Get bucket statistics for summary cards.
     */
    private function getBucketStatistics(): array
    {
        // Mock statistics - replace with actual calculations
        return [
            'total_configurations' => 3,
            'active_configurations' => 2,
            'avg_cost_per_injera' => 0.399,
            'total_capacity' => 195.0,
            'avg_yield_per_kg' => 6.2,
            'most_used_flour' => 'Teff',
        ];
    }
}
