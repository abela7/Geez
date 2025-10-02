<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuppliersController extends Controller
{
    /**
     * Display a listing of suppliers.
     */
    public function index(Request $request): View
    {
        $query = Supplier::query();

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('contact_person', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if ($request->filled('status') && $request->get('status') !== 'all') {
            $query->where('status', $request->get('status'));
        }

        // Get suppliers with pagination
        $suppliers = $query->orderBy('name')->paginate(20);

        // Calculate summary statistics
        $totalSuppliers = Supplier::count();
        $activeSuppliers = Supplier::where('status', 'active')->count();
        $inactiveSuppliers = Supplier::where('status', 'inactive')->count();

        return view('admin.inventory.suppliers.index', compact(
            'suppliers',
            'totalSuppliers',
            'activeSuppliers',
            'inactiveSuppliers'
        ));
    }

    /**
     * Store a newly created supplier.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
        ]);

        $supplier = Supplier::create($validated);

        return response()->json([
            'success' => true,
            'message' => __('inventory.suppliers.supplier_created'),
            'supplier' => $supplier,
        ]);
    }

    /**
     * Display the specified supplier.
     */
    public function show(string $id): JsonResponse
    {
        $supplier = Supplier::with(['inventoryItems'])->findOrFail($id);

        // Get recent purchase orders for this supplier (when PO system is implemented)
        $recentPurchaseOrders = collect(); // Placeholder for now

        // Format supplier data for display
        $supplierData = [
            'id' => $supplier->id,
            'name' => $supplier->name,
            'contact_person' => $supplier->contact_person,
            'phone' => $supplier->phone,
            'email' => $supplier->email,
            'address' => $supplier->address,
            'notes' => $supplier->notes,
            'status' => $supplier->status,
            'items_supplied_count' => $supplier->inventoryItems->count(),
            'items_supplied' => $supplier->inventoryItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'code' => $item->code,
                    'unit' => $item->unit,
                ];
            }),
            'recent_purchase_orders' => $recentPurchaseOrders,
            'created_at' => $supplier->created_at->format('M j, Y'),
            'updated_at' => $supplier->updated_at->format('M j, Y g:i A'),
        ];

        return response()->json([
            'success' => true,
            'supplier' => $supplierData,
        ]);
    }

    /**
     * Update the specified supplier.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $supplier = Supplier::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name,'.$supplier->id,
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
        ]);

        $supplier->update($validated);

        return response()->json([
            'success' => true,
            'message' => __('inventory.suppliers.supplier_updated'),
            'supplier' => $supplier,
        ]);
    }

    /**
     * Remove the specified supplier.
     */
    public function destroy(string $id): JsonResponse
    {
        $supplier = Supplier::findOrFail($id);

        // Check if supplier has associated inventory items
        if ($supplier->inventoryItems()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => __('inventory.suppliers.cannot_delete_has_items'),
            ], 422);
        }

        $supplier->delete();

        return response()->json([
            'success' => true,
            'message' => __('inventory.suppliers.supplier_deleted'),
        ]);
    }

    /**
     * Activate a supplier.
     */
    public function activate(string $id): JsonResponse
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->update(['status' => 'active']);

        return response()->json([
            'success' => true,
            'message' => __('inventory.suppliers.supplier_activated'),
        ]);
    }

    /**
     * Deactivate a supplier.
     */
    public function deactivate(string $id): JsonResponse
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->update(['status' => 'inactive']);

        return response()->json([
            'success' => true,
            'message' => __('inventory.suppliers.supplier_deactivated'),
        ]);
    }
}
