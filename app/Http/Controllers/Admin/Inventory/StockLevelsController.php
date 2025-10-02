<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StockLevelsController extends Controller
{
    /**
     * Display the stock levels index page
     */
    public function index(Request $request): View
    {
        $query = InventoryItem::with(['supplier', 'recentMovements'])
            ->active();

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->byCategory($request->get('category'));
        }

        if ($request->filled('location')) {
            $query->byLocation($request->get('location'));
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->get('supplier_id'));
        }

        if ($request->filled('status_filter')) {
            $statusFilter = $request->get('status_filter');
            switch ($statusFilter) {
                case 'low':
                    $query->lowStock();
                    break;
                case 'out':
                    $query->outOfStock();
                    break;
                case 'critical':
                    $query->whereRaw('current_stock <= (reorder_level * 0.5)');
                    break;
            }
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');

        $validSortColumns = ['name', 'current_stock', 'reorder_level', 'category', 'location', 'last_updated'];
        if (in_array($sortBy, $validSortColumns)) {
            $query->orderBy($sortBy, $sortDirection);
        }

        $items = $query->paginate(20)->withQueryString();

        // Get filter options
        $suppliers = Supplier::active()->orderBy('name')->get();
        $categories = InventoryItem::distinct('category')->pluck('category');
        $locations = InventoryItem::distinct('location')->pluck('location');

        // Get summary statistics
        $stats = $this->getStockStatistics();

        return view('admin.inventory.stock-levels.index', compact(
            'items', 'suppliers', 'categories', 'locations', 'stats'
        ));
    }

    /**
     * Get item details for the drawer
     */
    public function show(InventoryItem $item): JsonResponse
    {
        $item->load(['supplier', 'stockMovements' => function ($query) {
            $query->with('user')->limit(20);
        }]);

        return response()->json([
            'item' => $item,
            'movements' => $item->stockMovements,
            'stock_status' => $item->stock_status,
            'available_stock' => $item->available_stock,
            'total_value' => $item->total_value,
            'days_remaining' => $item->days_remaining,
        ]);
    }

    /**
     * Update stock level
     */
    public function updateStock(Request $request, InventoryItem $item): JsonResponse
    {
        $request->validate([
            'quantity' => 'required|numeric',
            'type' => 'required|in:received,issued,adjusted,transferred,wasted,returned',
            'reason' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'reference_number' => 'nullable|string|max:100',
        ]);

        try {
            $movement = $item->updateStock(
                $request->get('quantity'),
                $request->get('type'),
                [
                    'reason' => $request->get('reason'),
                    'notes' => $request->get('notes'),
                    'reference_number' => $request->get('reference_number'),
                    'user_id' => auth()->id(),
                ]
            );

            return response()->json([
                'success' => true,
                'message' => __('inventory.stock_levels.stock_updated'),
                'item' => $item->fresh(),
                'movement' => $movement,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating stock: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk update stock levels
     */
    public function bulkUpdate(Request $request): JsonResponse
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:inventory_items,id',
            'items.*.quantity' => 'required|numeric',
            'type' => 'required|in:received,issued,adjusted,transferred,wasted,returned',
            'reason' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $updatedItems = [];

            foreach ($request->get('items') as $itemData) {
                $item = InventoryItem::find($itemData['id']);
                if ($item) {
                    $movement = $item->updateStock(
                        $itemData['quantity'],
                        $request->get('type'),
                        [
                            'reason' => $request->get('reason'),
                            'notes' => $request->get('notes'),
                            'user_id' => auth()->id(),
                        ]
                    );
                    $updatedItems[] = $item->fresh();
                }
            }

            return response()->json([
                'success' => true,
                'message' => __('inventory.stock_levels.bulk_action_completed'),
                'updated_items' => $updatedItems,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating stock: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Export stock levels data
     */
    public function export(Request $request)
    {
        // This would implement CSV/Excel export functionality
        // For now, return a simple response
        return response()->json([
            'message' => 'Export functionality will be implemented',
        ]);
    }

    /**
     * Get stock statistics for dashboard cards
     */
    private function getStockStatistics(): array
    {
        $totalItems = InventoryItem::active()->count();
        $lowStockItems = InventoryItem::active()->lowStock()->count();
        $outOfStockItems = InventoryItem::active()->outOfStock()->count();
        $totalValue = InventoryItem::active()->sum(DB::raw('current_stock * cost_per_unit'));

        $recentMovements = StockMovement::with(['inventoryItem', 'user'])
            ->recent(7)
            ->orderBy('movement_date', 'desc')
            ->limit(5)
            ->get();

        return [
            'total_items' => $totalItems,
            'low_stock_count' => $lowStockItems,
            'out_of_stock_count' => $outOfStockItems,
            'total_value' => $totalValue,
            'recent_movements' => $recentMovements,
        ];
    }
}
