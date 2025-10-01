<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Injera;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InjeraStockLevelsController extends Controller
{
    /**
     * Display the injera stock levels page.
     */
    public function index(Request $request): View
    {
        // Get injera stock levels data
        $stockLevels = $this->getInjeraStockLevels($request);
        
        // Get summary statistics
        $statistics = $this->getStockStatistics();

        return view('admin.injera.injera-stock-levels.index', compact(
            'stockLevels',
            'statistics'
        ));
    }

    /**
     * Update injera stock levels.
     */
    public function update(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'stock_levels' => 'required|array',
            'stock_levels.*.id' => 'required|integer',
            'stock_levels.*.current_stock' => 'required|integer|min:0',
            'stock_levels.*.reserved_stock' => 'required|integer|min:0',
            'stock_levels.*.available_stock' => 'required|integer|min:0',
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

            foreach ($request->stock_levels as $stockData) {
                // Update stock level in database
                // This would be actual database operations in a real implementation
                $this->updateStockLevel($stockData);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stock levels updated successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update stock levels: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add new injera stock.
     */
    public function addStock(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'batch_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'quality_grade' => 'required|string|in:A,B,C',
            'storage_location' => 'required|string|max:255',
            'expiry_date' => 'required|date|after:today',
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

            // Add new stock to database
            $this->addNewStock($request->all());

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stock added successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to add stock: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reserve injera stock for orders.
     */
    public function reserveStock(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'stock_id' => 'required|integer',
            'order_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'reservation_notes' => 'nullable|string|max:500',
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

            // Reserve stock for order
            $this->reserveStockForOrder($request->all());

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stock reserved successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to reserve stock: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get injera stock levels data.
     */
    private function getInjeraStockLevels(Request $request): array
    {
        // Mock data - replace with actual database queries later
        return [
            [
                'id' => 1,
                'batch_number' => 'INJ-2025-001',
                'quality_grade' => 'A',
                'current_stock' => 45,
                'reserved_stock' => 12,
                'available_stock' => 33,
                'storage_location' => 'Cold Storage A1',
                'production_date' => '2025-01-15',
                'expiry_date' => '2025-01-22',
                'days_until_expiry' => 3,
                'status' => 'fresh',
                'cost_per_injera' => 0.45,
                'total_value' => 20.25,
                'notes' => 'Premium quality batch'
            ],
            [
                'id' => 2,
                'batch_number' => 'INJ-2025-002',
                'quality_grade' => 'A',
                'current_stock' => 28,
                'reserved_stock' => 8,
                'available_stock' => 20,
                'storage_location' => 'Cold Storage A2',
                'production_date' => '2025-01-14',
                'expiry_date' => '2025-01-21',
                'days_until_expiry' => 2,
                'status' => 'fresh',
                'cost_per_injera' => 0.44,
                'total_value' => 12.32,
                'notes' => 'Special order batch'
            ],
            [
                'id' => 3,
                'batch_number' => 'INJ-2025-003',
                'quality_grade' => 'B',
                'current_stock' => 15,
                'reserved_stock' => 5,
                'available_stock' => 10,
                'storage_location' => 'Cold Storage B1',
                'production_date' => '2025-01-13',
                'expiry_date' => '2025-01-20',
                'days_until_expiry' => 1,
                'status' => 'expiring_soon',
                'cost_per_injera' => 0.42,
                'total_value' => 6.30,
                'notes' => 'Regular quality batch'
            ],
            [
                'id' => 4,
                'batch_number' => 'INJ-2025-004',
                'quality_grade' => 'C',
                'current_stock' => 8,
                'reserved_stock' => 0,
                'available_stock' => 8,
                'storage_location' => 'Cold Storage B2',
                'production_date' => '2025-01-12',
                'expiry_date' => '2025-01-19',
                'days_until_expiry' => 0,
                'status' => 'expired',
                'cost_per_injera' => 0.38,
                'total_value' => 3.04,
                'notes' => 'Lower quality - use for staff meals'
            ]
        ];
    }

    /**
     * Get stock statistics.
     */
    private function getStockStatistics(): array
    {
        return [
            'total_injera' => 96,
            'available_injera' => 71,
            'reserved_injera' => 25,
            'expiring_today' => 8,
            'expiring_this_week' => 23,
            'total_value' => 41.91,
            'avg_cost_per_injera' => 0.44,
            'quality_distribution' => [
                'A' => 73,
                'B' => 15,
                'C' => 8
            ]
        ];
    }

    /**
     * Update stock level in database.
     */
    private function updateStockLevel(array $stockData): void
    {
        // Mock implementation - replace with actual database update
        // DB::table('injera_stock_levels')
        //     ->where('id', $stockData['id'])
        //     ->update([
        //         'current_stock' => $stockData['current_stock'],
        //         'reserved_stock' => $stockData['reserved_stock'],
        //         'available_stock' => $stockData['available_stock'],
        //         'updated_at' => now()
        //     ]);
    }

    /**
     * Add new stock to database.
     */
    private function addNewStock(array $stockData): void
    {
        // Mock implementation - replace with actual database insert
        // DB::table('injera_stock_levels')->insert([
        //     'batch_id' => $stockData['batch_id'],
        //     'quantity' => $stockData['quantity'],
        //     'quality_grade' => $stockData['quality_grade'],
        //     'storage_location' => $stockData['storage_location'],
        //     'expiry_date' => $stockData['expiry_date'],
        //     'notes' => $stockData['notes'],
        //     'created_at' => now(),
        //     'updated_at' => now()
        // ]);
    }

    /**
     * Reserve stock for order.
     */
    private function reserveStockForOrder(array $reservationData): void
    {
        // Mock implementation - replace with actual database operations
        // DB::table('injera_stock_reservations')->insert([
        //     'stock_id' => $reservationData['stock_id'],
        //     'order_id' => $reservationData['order_id'],
        //     'quantity' => $reservationData['quantity'],
        //     'reservation_notes' => $reservationData['reservation_notes'],
        //     'created_at' => now(),
        //     'updated_at' => now()
        // ]);
    }
}
