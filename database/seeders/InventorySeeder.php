<?php

namespace Database\Seeders;

use App\Models\InventoryItem;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create suppliers
        $suppliers = [
            [
                'name' => 'Fresh Foods Ltd',
                'code' => 'FF001',
                'contact_person' => 'John Smith',
                'email' => 'john@freshfoods.com',
                'phone' => '+1-555-0101',
                'address' => '123 Market Street, City',
                'status' => 'active',
            ],
            [
                'name' => 'Beverage Distributors Inc',
                'code' => 'BD002',
                'contact_person' => 'Sarah Johnson',
                'email' => 'sarah@beveragedist.com',
                'phone' => '+1-555-0102',
                'address' => '456 Industrial Ave, City',
                'status' => 'active',
            ],
            [
                'name' => 'Kitchen Supplies Co',
                'code' => 'KS003',
                'contact_person' => 'Mike Wilson',
                'email' => 'mike@kitchensupplies.com',
                'phone' => '+1-555-0103',
                'address' => '789 Supply Road, City',
                'status' => 'active',
            ],
        ];

        foreach ($suppliers as $supplierData) {
            Supplier::create($supplierData);
        }

        $suppliersCreated = Supplier::all();

        // Create inventory items
        $inventoryItems = [
            // Ingredients
            [
                'name' => 'Tomatoes (Fresh)',
                'code' => 'ING001',
                'barcode' => '1234567890123',
                'description' => 'Fresh red tomatoes for cooking',
                'category' => 'ingredients',
                'subcategory' => 'vegetables',
                'unit' => 'kg',
                'current_stock' => 25.5,
                'reserved_stock' => 2.0,
                'reorder_level' => 10.0,
                'max_level' => 50.0,
                'minimum_order_qty' => 20.0,
                'cost_per_unit' => 3.50,
                'selling_price' => 5.25,
                'location' => 'cold_storage',
                'storage_requirements' => 'Keep refrigerated at 2-4°C',
                'shelf_life_days' => 7,
                'supplier_id' => $suppliersCreated[0]->id,
                'allergen_info' => null,
                'status' => 'active',
                'average_daily_usage' => 3.2,
            ],
            [
                'name' => 'Ground Beef (80/20)',
                'code' => 'ING002',
                'barcode' => '1234567890124',
                'description' => 'Fresh ground beef, 80% lean',
                'category' => 'ingredients',
                'subcategory' => 'meat',
                'unit' => 'kg',
                'current_stock' => 8.0,
                'reserved_stock' => 1.5,
                'reorder_level' => 15.0,
                'max_level' => 30.0,
                'minimum_order_qty' => 10.0,
                'cost_per_unit' => 12.00,
                'selling_price' => 18.00,
                'location' => 'freezer',
                'storage_requirements' => 'Keep frozen at -18°C',
                'shelf_life_days' => 90,
                'supplier_id' => $suppliersCreated[0]->id,
                'allergen_info' => null,
                'status' => 'active',
                'average_daily_usage' => 2.8,
            ],
            [
                'name' => 'Flour (All Purpose)',
                'code' => 'ING003',
                'barcode' => '1234567890125',
                'description' => 'All-purpose white flour for baking',
                'category' => 'ingredients',
                'subcategory' => 'dry goods',
                'unit' => 'kg',
                'current_stock' => 45.0,
                'reserved_stock' => 0.0,
                'reorder_level' => 20.0,
                'max_level' => 100.0,
                'minimum_order_qty' => 50.0,
                'cost_per_unit' => 1.20,
                'selling_price' => 2.00,
                'location' => 'dry_storage',
                'storage_requirements' => 'Keep dry and cool',
                'shelf_life_days' => 365,
                'supplier_id' => $suppliersCreated[0]->id,
                'allergen_info' => ['gluten'],
                'status' => 'active',
                'average_daily_usage' => 1.5,
            ],
            // Beverages
            [
                'name' => 'Coca Cola (Cans)',
                'code' => 'BEV001',
                'barcode' => '1234567890126',
                'description' => 'Coca Cola 330ml cans',
                'category' => 'beverages',
                'subcategory' => 'soft drinks',
                'unit' => 'pieces',
                'current_stock' => 0.0,
                'reserved_stock' => 0.0,
                'reorder_level' => 50.0,
                'max_level' => 500.0,
                'minimum_order_qty' => 100.0,
                'cost_per_unit' => 0.75,
                'selling_price' => 1.50,
                'location' => 'cold_storage',
                'storage_requirements' => 'Keep refrigerated',
                'shelf_life_days' => 180,
                'supplier_id' => $suppliersCreated[1]->id,
                'allergen_info' => null,
                'status' => 'active',
                'average_daily_usage' => 25.0,
            ],
            [
                'name' => 'Orange Juice (Fresh)',
                'code' => 'BEV002',
                'barcode' => '1234567890127',
                'description' => 'Fresh squeezed orange juice',
                'category' => 'beverages',
                'subcategory' => 'juices',
                'unit' => 'L',
                'current_stock' => 12.5,
                'reserved_stock' => 2.0,
                'reorder_level' => 20.0,
                'max_level' => 50.0,
                'minimum_order_qty' => 25.0,
                'cost_per_unit' => 4.00,
                'selling_price' => 7.00,
                'location' => 'cold_storage',
                'storage_requirements' => 'Keep refrigerated at 2-4°C',
                'shelf_life_days' => 5,
                'supplier_id' => $suppliersCreated[1]->id,
                'allergen_info' => null,
                'status' => 'active',
                'average_daily_usage' => 4.2,
            ],
            // Supplies
            [
                'name' => 'Paper Napkins',
                'code' => 'SUP001',
                'barcode' => '1234567890128',
                'description' => 'White paper napkins for dining',
                'category' => 'supplies',
                'subcategory' => 'dining',
                'unit' => 'boxes',
                'current_stock' => 15.0,
                'reserved_stock' => 0.0,
                'reorder_level' => 5.0,
                'max_level' => 30.0,
                'minimum_order_qty' => 10.0,
                'cost_per_unit' => 8.50,
                'selling_price' => null,
                'location' => 'dry_storage',
                'storage_requirements' => 'Keep dry',
                'shelf_life_days' => null,
                'supplier_id' => $suppliersCreated[2]->id,
                'allergen_info' => null,
                'status' => 'active',
                'average_daily_usage' => 0.8,
            ],
            // Packaging
            [
                'name' => 'Takeout Containers (Large)',
                'code' => 'PKG001',
                'barcode' => '1234567890129',
                'description' => 'Large foam takeout containers',
                'category' => 'packaging',
                'subcategory' => 'containers',
                'unit' => 'pieces',
                'current_stock' => 3.0,
                'reserved_stock' => 0.0,
                'reorder_level' => 25.0,
                'max_level' => 200.0,
                'minimum_order_qty' => 50.0,
                'cost_per_unit' => 0.35,
                'selling_price' => null,
                'location' => 'dry_storage',
                'storage_requirements' => 'Keep dry',
                'shelf_life_days' => null,
                'supplier_id' => $suppliersCreated[2]->id,
                'allergen_info' => null,
                'status' => 'active',
                'average_daily_usage' => 15.0,
            ],
            // Cleaning
            [
                'name' => 'All-Purpose Cleaner',
                'code' => 'CLN001',
                'barcode' => '1234567890130',
                'description' => 'Multi-surface cleaning solution',
                'category' => 'cleaning',
                'subcategory' => 'chemicals',
                'unit' => 'bottles',
                'current_stock' => 8.0,
                'reserved_stock' => 0.0,
                'reorder_level' => 5.0,
                'max_level' => 20.0,
                'minimum_order_qty' => 6.0,
                'cost_per_unit' => 4.25,
                'selling_price' => null,
                'location' => 'dry_storage',
                'storage_requirements' => 'Keep away from food items',
                'shelf_life_days' => 730,
                'supplier_id' => $suppliersCreated[2]->id,
                'allergen_info' => null,
                'status' => 'active',
                'average_daily_usage' => 0.3,
            ],
        ];

        foreach ($inventoryItems as $itemData) {
            $item = InventoryItem::create($itemData);

            // Create some sample stock movements for each item
            $movements = [
                [
                    'inventory_item_id' => $item->id,
                    'type' => 'received',
                    'quantity' => $item->current_stock + $item->reserved_stock + 10,
                    'unit_cost' => $item->cost_per_unit,
                    'reference_number' => 'PO-'.str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                    'reason' => 'Initial stock receipt',
                    'notes' => 'Stock received from supplier',
                    'movement_date' => now()->subDays(rand(1, 30)),
                    'stock_before' => 0,
                    'stock_after' => $item->current_stock + $item->reserved_stock + 10,
                ],
                [
                    'inventory_item_id' => $item->id,
                    'type' => 'issued',
                    'quantity' => -10,
                    'unit_cost' => $item->cost_per_unit,
                    'reason' => 'Kitchen usage',
                    'notes' => 'Used in daily operations',
                    'movement_date' => now()->subDays(rand(1, 15)),
                    'stock_before' => $item->current_stock + $item->reserved_stock + 10,
                    'stock_after' => $item->current_stock + $item->reserved_stock,
                ],
            ];

            foreach ($movements as $movementData) {
                StockMovement::create($movementData);
            }

            // Update the last stock update timestamp
            $item->update(['last_stock_update' => now()]);
        }
    }
}
