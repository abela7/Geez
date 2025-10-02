<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Injera;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class OrdersController extends Controller
{
    /**
     * Display the orders and allocation page.
     */
    public function index(Request $request): View
    {
        // Get filter parameters
        $status = $request->get('status', '');
        $priority = $request->get('priority', '');
        $dateFrom = $request->get('date_from', '');
        $dateTo = $request->get('date_to', '');

        // Get orders data
        $orders = $this->getOrders($status, $priority, $dateFrom, $dateTo);

        // Get order statistics
        $statistics = $this->getOrderStatistics();

        // Get available stock for allocation
        $availableStock = $this->getAvailableStock();

        return view('admin.injera.orders.index', compact(
            'orders',
            'statistics',
            'availableStock',
            'status',
            'priority',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Create a new order.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'order_type' => 'required|in:pickup,delivery,dine_in',
            'quality_grade' => 'required|in:A,B,C,mixed',
            'quantity' => 'required|integer|min:1',
            'delivery_date' => 'required|date|after_or_equal:today',
            'delivery_time' => 'nullable|string',
            'delivery_address' => 'nullable|string|max:500',
            'special_instructions' => 'nullable|string|max:1000',
            'priority' => 'required|in:normal,high,urgent',
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

            // Create order
            $orderId = $this->createOrder($request->all());

            // Attempt automatic allocation
            $allocated = $this->attemptAutoAllocation($orderId, $request->quality_grade, $request->quantity);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'order_id' => $orderId,
                'auto_allocated' => $allocated,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create order: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, int $orderId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,confirmed,preparing,ready,completed,cancelled',
            'notes' => 'nullable|string|max:500',
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

            $this->updateOrderStatus($orderId, $request->status, $request->notes);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update order status: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Allocate injera to order.
     */
    public function allocate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer',
            'allocations' => 'required|array',
            'allocations.*.stock_id' => 'required|integer',
            'allocations.*.quantity' => 'required|integer|min:1',
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

            $this->allocateInjeraToOrder($request->order_id, $request->allocations);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Injera allocated successfully',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to allocate injera: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cancel order.
     */
    public function cancel(Request $request, int $orderId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'cancellation_reason' => 'required|string|max:500',
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

            $this->cancelOrder($orderId, $request->cancellation_reason);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel order: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get orders data.
     */
    private function getOrders(string $status, string $priority, string $dateFrom, string $dateTo): array
    {
        // Mock data - replace with actual database queries later
        return [
            [
                'id' => 1,
                'order_number' => 'ORD-2025-001',
                'customer_name' => 'Almaz Tadesse',
                'customer_phone' => '+251911234567',
                'customer_email' => 'almaz@email.com',
                'order_type' => 'delivery',
                'quality_grade' => 'A',
                'quantity' => 25,
                'allocated_quantity' => 25,
                'remaining_quantity' => 0,
                'unit_price' => 0.75,
                'total_amount' => 18.75,
                'delivery_date' => '2025-01-20',
                'delivery_time' => '14:00',
                'delivery_address' => 'Bole, Addis Ababa',
                'status' => 'ready',
                'priority' => 'normal',
                'created_at' => '2025-01-18 10:30:00',
                'special_instructions' => 'Please call before delivery',
                'allocation_details' => [
                    ['stock_id' => 1, 'batch_number' => 'INJ-2025-001', 'quantity' => 15, 'quality' => 'A'],
                    ['stock_id' => 2, 'batch_number' => 'INJ-2025-002', 'quantity' => 10, 'quality' => 'A'],
                ],
            ],
            [
                'id' => 2,
                'order_number' => 'ORD-2025-002',
                'customer_name' => 'Meseret Alemu',
                'customer_phone' => '+251922345678',
                'customer_email' => null,
                'order_type' => 'pickup',
                'quality_grade' => 'mixed',
                'quantity' => 40,
                'allocated_quantity' => 30,
                'remaining_quantity' => 10,
                'unit_price' => 0.65,
                'total_amount' => 26.00,
                'delivery_date' => '2025-01-19',
                'delivery_time' => '16:30',
                'delivery_address' => null,
                'status' => 'preparing',
                'priority' => 'high',
                'created_at' => '2025-01-17 15:45:00',
                'special_instructions' => 'Mix of A and B grade injera',
                'allocation_details' => [
                    ['stock_id' => 1, 'batch_number' => 'INJ-2025-001', 'quantity' => 20, 'quality' => 'A'],
                    ['stock_id' => 3, 'batch_number' => 'INJ-2025-003', 'quantity' => 10, 'quality' => 'B'],
                ],
            ],
            [
                'id' => 3,
                'order_number' => 'ORD-2025-003',
                'customer_name' => 'Tigist Bekele',
                'customer_phone' => '+251933456789',
                'customer_email' => 'tigist.bekele@email.com',
                'order_type' => 'dine_in',
                'quality_grade' => 'B',
                'quantity' => 15,
                'allocated_quantity' => 0,
                'remaining_quantity' => 15,
                'unit_price' => 0.60,
                'total_amount' => 9.00,
                'delivery_date' => '2025-01-21',
                'delivery_time' => '12:00',
                'delivery_address' => null,
                'status' => 'pending',
                'priority' => 'urgent',
                'created_at' => '2025-01-18 09:15:00',
                'special_instructions' => 'For lunch service',
                'allocation_details' => [],
            ],
            [
                'id' => 4,
                'order_number' => 'ORD-2025-004',
                'customer_name' => 'Dawit Haile',
                'customer_phone' => '+251944567890',
                'customer_email' => 'dawit@email.com',
                'order_type' => 'delivery',
                'quality_grade' => 'A',
                'quantity' => 50,
                'allocated_quantity' => 50,
                'remaining_quantity' => 0,
                'unit_price' => 0.75,
                'total_amount' => 37.50,
                'delivery_date' => '2025-01-22',
                'delivery_time' => '18:00',
                'delivery_address' => 'Kazanchis, Addis Ababa',
                'status' => 'confirmed',
                'priority' => 'normal',
                'created_at' => '2025-01-18 11:20:00',
                'special_instructions' => 'Large order for event',
                'allocation_details' => [
                    ['stock_id' => 1, 'batch_number' => 'INJ-2025-001', 'quantity' => 30, 'quality' => 'A'],
                    ['stock_id' => 2, 'batch_number' => 'INJ-2025-002', 'quantity' => 20, 'quality' => 'A'],
                ],
            ],
        ];
    }

    /**
     * Get order statistics.
     */
    private function getOrderStatistics(): array
    {
        return [
            'total_orders' => 24,
            'pending_orders' => 6,
            'confirmed_orders' => 8,
            'ready_orders' => 4,
            'completed_orders' => 5,
            'cancelled_orders' => 1,
            'total_injera_ordered' => 485,
            'allocated_injera' => 378,
            'pending_allocation' => 107,
            'total_revenue' => 327.50,
            'average_order_size' => 20.2,
            'urgent_orders' => 3,
            'high_priority_orders' => 7,
        ];
    }

    /**
     * Get available stock for allocation.
     */
    private function getAvailableStock(): array
    {
        return [
            [
                'stock_id' => 1,
                'batch_number' => 'INJ-2025-001',
                'quality_grade' => 'A',
                'available_quantity' => 33,
                'expiry_date' => '2025-01-22',
                'days_until_expiry' => 3,
                'storage_location' => 'Cold Storage A1',
            ],
            [
                'stock_id' => 2,
                'batch_number' => 'INJ-2025-002',
                'quality_grade' => 'A',
                'available_quantity' => 20,
                'expiry_date' => '2025-01-21',
                'days_until_expiry' => 2,
                'storage_location' => 'Cold Storage A2',
            ],
            [
                'stock_id' => 3,
                'batch_number' => 'INJ-2025-003',
                'quality_grade' => 'B',
                'available_quantity' => 10,
                'expiry_date' => '2025-01-20',
                'days_until_expiry' => 1,
                'storage_location' => 'Cold Storage B1',
            ],
            [
                'stock_id' => 4,
                'batch_number' => 'INJ-2025-004',
                'quality_grade' => 'C',
                'available_quantity' => 8,
                'expiry_date' => '2025-01-19',
                'days_until_expiry' => 0,
                'storage_location' => 'Cold Storage B2',
            ],
        ];
    }

    /**
     * Create new order.
     */
    private function createOrder(array $orderData): int
    {
        // Mock implementation - replace with actual database insert
        return rand(1000, 9999);
    }

    /**
     * Attempt automatic allocation.
     */
    private function attemptAutoAllocation(int $orderId, string $qualityGrade, int $quantity): bool
    {
        // Mock implementation - replace with actual allocation logic
        // This would check available stock and automatically allocate if possible
        return rand(0, 1) === 1; // 50% chance of auto allocation
    }

    /**
     * Update order status.
     */
    private function updateOrderStatus(int $orderId, string $status, ?string $notes): void
    {
        // Mock implementation - replace with actual database update
        // DB::table('orders')->where('id', $orderId)->update([
        //     'status' => $status,
        //     'status_notes' => $notes,
        //     'updated_at' => now()
        // ]);
    }

    /**
     * Allocate injera to order.
     */
    private function allocateInjeraToOrder(int $orderId, array $allocations): void
    {
        // Mock implementation - replace with actual allocation logic
        foreach ($allocations as $allocation) {
            // DB::table('order_allocations')->insert([
            //     'order_id' => $orderId,
            //     'stock_id' => $allocation['stock_id'],
            //     'quantity' => $allocation['quantity'],
            //     'allocated_at' => now()
            // ]);

            // Update stock levels
            // DB::table('injera_stock')->where('id', $allocation['stock_id'])
            //     ->decrement('available_quantity', $allocation['quantity']);
        }
    }

    /**
     * Cancel order.
     */
    private function cancelOrder(int $orderId, string $reason): void
    {
        // Mock implementation - replace with actual cancellation logic
        // Release any allocated stock back to inventory
        // Update order status to cancelled
        // Log cancellation reason
    }
}
