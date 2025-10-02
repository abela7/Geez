<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PurchasingController extends Controller
{
    /**
     * Display the purchasing index page
     */
    public function index(Request $request): View
    {
        // Sample purchase order data for UI demo
        $samplePurchaseOrders = collect([
            (object) [
                'id' => 1,
                'po_number' => 'PO-2025-001',
                'supplier_name' => 'Ethiopian Coffee Suppliers Ltd.',
                'order_date' => '2025-09-08',
                'delivery_date' => '2025-09-15',
                'status' => 'sent',
                'total_amount' => 2745.00,
                'items_count' => 3,
                'formatted_order_date' => '8 Sep 2025',
                'formatted_delivery_date' => '15 Sep 2025',
                'status_badge_class' => 'po-sent',
            ],
            (object) [
                'id' => 2,
                'po_number' => 'PO-2025-002',
                'supplier_name' => 'Addis Spice Trading',
                'order_date' => '2025-09-09',
                'delivery_date' => '2025-09-12',
                'status' => 'received',
                'total_amount' => 960.00,
                'items_count' => 5,
                'formatted_order_date' => '9 Sep 2025',
                'formatted_delivery_date' => '12 Sep 2025',
                'status_badge_class' => 'po-received',
            ],
            (object) [
                'id' => 3,
                'po_number' => 'PO-2025-003',
                'supplier_name' => 'Teff & Grains Wholesale',
                'order_date' => '2025-09-10',
                'delivery_date' => '2025-09-20',
                'status' => 'draft',
                'total_amount' => 1395.00,
                'items_count' => 2,
                'formatted_order_date' => '10 Sep 2025',
                'formatted_delivery_date' => '20 Sep 2025',
                'status_badge_class' => 'po-draft',
            ],
        ]);

        // Apply basic filters
        $filteredPOs = $samplePurchaseOrders;
        if ($request->filled('status') && $request->status !== 'all') {
            $filteredPOs = $filteredPOs->where('status', $request->status);
        }

        // Create paginator-like object
        $purchaseOrders = (object) [
            'data' => $filteredPOs->values(),
            'hasPages' => function () {
                return false;
            },
            'links' => function () {
                return '';
            },
        ];

        // Static data
        $suppliers = ['Ethiopian Coffee Suppliers Ltd.', 'Addis Spice Trading', 'Teff & Grains Wholesale'];
        $statuses = ['draft', 'sent', 'received', 'cancelled'];
        $totalPOs = $samplePurchaseOrders->count();
        $draftPOs = $samplePurchaseOrders->where('status', 'draft')->count();
        $sentPOs = $samplePurchaseOrders->where('status', 'sent')->count();
        $receivedPOs = $samplePurchaseOrders->where('status', 'received')->count();
        $totalValue = $samplePurchaseOrders->sum('total_amount');
        $avgPOValue = $totalValue / max($totalPOs, 1);

        return view('admin.inventory.purchasing.index', compact(
            'purchaseOrders', 'suppliers', 'statuses', 'totalPOs',
            'draftPOs', 'sentPOs', 'receivedPOs', 'totalValue', 'avgPOValue'
        ));
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json(['success' => true, 'message' => __('inventory.purchasing.po_created')]);
    }

    public function show($id): JsonResponse
    {
        $po = (object) [
            'id' => $id,
            'po_number' => 'PO-2025-001',
            'supplier_name' => 'Ethiopian Coffee Suppliers Ltd.',
            'status' => 'sent',
            'total_amount' => 2745.00,
        ];

        return response()->json(['success' => true, 'purchase_order' => $po]);
    }

    public function update(Request $request, $id): JsonResponse
    {
        return response()->json(['success' => true, 'message' => __('inventory.purchasing.po_updated')]);
    }

    public function destroy($id): JsonResponse
    {
        return response()->json(['success' => true, 'message' => __('inventory.purchasing.po_deleted')]);
    }
}
