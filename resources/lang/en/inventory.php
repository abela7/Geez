<?php

return [
    // Page Titles
    'title' => 'Inventory Management',
    'subtitle' => 'Manage your restaurant inventory and stock levels',
    
    // Navigation
    'nav_title' => 'Inventory',
    
    // Content Placeholders
    'placeholder_title' => 'Inventory Management',
    'placeholder_description' => 'Inventory features will be implemented here with modular CSS and JavaScript.',
    
    // Stock Management
    'stock_levels' => 'Stock Levels',
    'low_stock' => 'Low Stock',
    'out_of_stock' => 'Out of Stock',
    'in_stock' => 'In Stock',
    'reorder_level' => 'Reorder Level',
    'reorder_quantity' => 'Reorder Quantity',
    'current_stock' => 'Current Stock',
    'available_stock' => 'Available Stock',
    'reserved_stock' => 'Reserved Stock',
    
    // Product Management
    'products' => 'Products',
    'product' => 'Product',
    'product_name' => 'Product Name',
    'product_code' => 'Product Code',
    'sku' => 'SKU',
    'barcode' => 'Barcode',
    'unit' => 'Unit',
    'unit_price' => 'Unit Price',
    'cost_price' => 'Cost Price',
    'selling_price' => 'Selling Price',
    'supplier' => 'Supplier',
    'supplier_name' => 'Supplier Name',
    'supplier_code' => 'Supplier Code',
    
    // Categories
    'categories' => 'Categories',
    'category' => 'Category',
    'category_name' => 'Category Name',
    'main_category' => 'Main Category',
    'subcategory' => 'Subcategory',
    
    // Stock Movements
    'stock_movements' => 'Stock Movements',
    'stock_in' => 'Stock In',
    'stock_out' => 'Stock Out',
    'stock_adjustment' => 'Stock Adjustment',
    'stock_transfer' => 'Stock Transfer',
    'movement_type' => 'Movement Type',
    'movement_date' => 'Movement Date',
    'movement_reason' => 'Movement Reason',
    'reference_number' => 'Reference Number',
    
    // Purchase Orders
    'purchase_orders' => 'Purchase Orders',
    'purchase_order' => 'Purchase Order',
    'po_number' => 'PO Number',
    'order_date' => 'Order Date',
    'expected_date' => 'Expected Date',
    'received_date' => 'Received Date',
    'order_status' => 'Order Status',
    'pending_orders' => 'Pending Orders',
    'received_orders' => 'Received Orders',
    
    // Inventory Reports
    'reports' => 'Inventory Reports',
    'stock_report' => 'Stock Report',
    'movement_report' => 'Movement Report',
    'valuation_report' => 'Valuation Report',
    'low_stock_report' => 'Low Stock Report',
    'expiry_report' => 'Expiry Report',
    
    // Actions
    'add_product' => 'Add Product',
    'edit_product' => 'Edit Product',
    'delete_product' => 'Delete Product',
    'adjust_stock' => 'Adjust Stock',
    'transfer_stock' => 'Transfer Stock',
    'receive_stock' => 'Receive Stock',
    'create_po' => 'Create Purchase Order',
    'receive_po' => 'Receive Purchase Order',
    
    // Status
    'active_products' => 'Active Products',
    'inactive_products' => 'Inactive Products',
    'discontinued' => 'Discontinued',
    'pending_approval' => 'Pending Approval',
    
    // Alerts & Messages
    'low_stock_alert' => 'Low stock alert for :product',
    'out_of_stock_alert' => 'Out of stock: :product',
    'stock_updated' => 'Stock updated successfully',
    'product_added' => 'Product added successfully',
    'product_updated' => 'Product updated successfully',
    'product_deleted' => 'Product deleted successfully',
    'po_created' => 'Purchase order created successfully',
    'po_received' => 'Purchase order received successfully',
    
    // Validation Messages
    'product_name_required' => 'Product name is required',
    'sku_required' => 'SKU is required',
    'sku_unique' => 'SKU must be unique',
    'unit_price_required' => 'Unit price is required',
    'category_required' => 'Category is required',
    'supplier_required' => 'Supplier is required',
    'stock_quantity_invalid' => 'Invalid stock quantity',
    
    // Filters & Search
    'filter_by_category' => 'Filter by Category',
    'filter_by_supplier' => 'Filter by Supplier',
    'filter_by_status' => 'Filter by Status',
    'search_products' => 'Search products...',
    'search_by_name_sku' => 'Search by name or SKU',
    
    // Batch Operations
    'bulk_actions' => 'Bulk Actions',
    'bulk_update_prices' => 'Bulk Update Prices',
    'bulk_adjust_stock' => 'Bulk Adjust Stock',
    'bulk_change_category' => 'Bulk Change Category',
    'bulk_change_supplier' => 'Bulk Change Supplier',
    'export_selected' => 'Export Selected',
    'delete_selected' => 'Delete Selected',
    
    // Import/Export
    'import_products' => 'Import Products',
    'export_products' => 'Export Products',
    'download_template' => 'Download Template',
    'upload_file' => 'Upload File',
    'import_successful' => 'Import completed successfully',
    'export_successful' => 'Export completed successfully',
    
    // Ingredients Subsection
    'ingredients' => [
        'title' => 'Ingredients',
        'subtitle' => 'Manage recipe ingredients, nutritional information, and allergen data',
        'nav_title' => 'Ingredients',
        
        // Navigation & Actions
        'add_ingredient' => 'Add Ingredient',
        'edit_ingredient' => 'Edit Ingredient',
        'delete_ingredient' => 'Delete Ingredient',
        'bulk_actions' => 'Bulk Actions',
        'export_ingredients' => 'Export Ingredients',
        'import_ingredients' => 'Import Ingredients',
        'view_details' => 'View Details',
        
        // Table Headers
        'ingredient_name' => 'Ingredient Name',
        'category' => 'Category',
        'unit' => 'Unit',
        'cost_per_unit' => 'Cost per Unit',
        'supplier' => 'Supplier',
        'allergens' => 'Allergens',
        'nutritional_info' => 'Nutritional Info',
        'status' => 'Status',
        'actions' => 'Actions',
        
        // Categories
        'categories' => [
            'proteins' => 'Proteins',
            'vegetables' => 'Vegetables',
            'fruits' => 'Fruits',
            'grains' => 'Grains',
            'dairy' => 'Dairy',
            'spices' => 'Spices & Herbs',
            'oils' => 'Oils & Fats',
            'beverages' => 'Beverages',
            'condiments' => 'Condiments',
            'sweeteners' => 'Sweeteners',
            'nuts' => 'Nuts & Seeds',
            'other' => 'Other',
        ],
        
        // Units
        'units' => [
            'kg' => 'Kilogram',
            'g' => 'Gram',
            'l' => 'Liter',
            'ml' => 'Milliliter',
            'pieces' => 'Pieces',
            'cups' => 'Cups',
            'tbsp' => 'Tablespoon',
            'tsp' => 'Teaspoon',
            'oz' => 'Ounce',
            'lb' => 'Pound',
        ],
        
        // Status
        'statuses' => [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'discontinued' => 'Discontinued',
        ],
        
        // Allergens
        'common_allergens' => [
            'gluten' => 'Gluten',
            'dairy' => 'Dairy',
            'eggs' => 'Eggs',
            'nuts' => 'Tree Nuts',
            'peanuts' => 'Peanuts',
            'soy' => 'Soy',
            'fish' => 'Fish',
            'shellfish' => 'Shellfish',
            'sesame' => 'Sesame',
        ],
        
        // Filters
        'filter_by_category' => 'Filter by Category',
        'filter_by_supplier' => 'Filter by Supplier',
        'filter_by_status' => 'Filter by Status',
        'filter_by_allergens' => 'Filter by Allergens',
        'search_placeholder' => 'Search by name, code, or description...',
        
        // Nutritional Information
        'nutrition' => [
            'calories' => 'Calories',
            'protein' => 'Protein (g)',
            'carbs' => 'Carbohydrates (g)',
            'fat' => 'Fat (g)',
            'fiber' => 'Fiber (g)',
            'sugar' => 'Sugar (g)',
            'sodium' => 'Sodium (mg)',
            'per_100g' => 'per 100g',
        ],
        
        // Form Fields
        'ingredient_code' => 'Ingredient Code',
        'description' => 'Description',
        'origin_country' => 'Origin Country',
        'shelf_life' => 'Shelf Life (days)',
        'storage_requirements' => 'Storage Requirements',
        'minimum_order' => 'Minimum Order Quantity',
        'lead_time' => 'Lead Time (days)',
        'notes' => 'Notes',
        
        // Storage Requirements
        'storage' => [
            'ambient' => 'Ambient',
            'refrigerated' => 'Refrigerated',
            'frozen' => 'Frozen',
            'dry' => 'Dry Storage',
            'cool_dry' => 'Cool & Dry',
        ],
        
        // Messages
        'ingredient_added' => 'Ingredient added successfully',
        'ingredient_updated' => 'Ingredient updated successfully',
        'ingredient_deleted' => 'Ingredient deleted successfully',
        'bulk_update_success' => 'Ingredients updated successfully',
        'import_success' => 'Ingredients imported successfully',
        'export_success' => 'Ingredients exported successfully',
        
        // Validation
        'name_required' => 'Ingredient name is required',
        'category_required' => 'Category is required',
        'unit_required' => 'Unit is required',
        'cost_required' => 'Cost per unit is required',
        'cost_numeric' => 'Cost must be a valid number',
        'shelf_life_numeric' => 'Shelf life must be a valid number',
        
        // Empty States
        'no_ingredients' => 'No ingredients found',
        'no_ingredients_message' => 'Start by adding your first ingredient to build your recipe database.',
        'no_search_results' => 'No ingredients match your search criteria',
        'try_different_search' => 'Try adjusting your search terms or filters',
        
        // Summary Cards
        'total_ingredients' => 'Total Ingredients',
        'active_ingredients' => 'Active Ingredients',
        'categories_count' => 'Categories',
        'avg_cost' => 'Average Cost',
        'suppliers_count' => 'Suppliers',
        'allergen_free' => 'Allergen-Free',
        
        // Tooltips
        'ingredient_code_help' => 'Unique identifier for this ingredient',
        'shelf_life_help' => 'Number of days ingredient stays fresh',
        'allergen_help' => 'Select all allergens present in this ingredient',
        'nutrition_help' => 'Nutritional values per 100g of ingredient',
        
        // Accessibility
        'select_ingredient' => 'Select ingredient',
        'ingredient_selected' => 'Ingredient selected',
        'sort_by_name' => 'Sort by ingredient name',
        'sort_by_category' => 'Sort by category',
        'sort_by_cost' => 'Sort by cost',
        'ingredient_details' => 'View ingredient details',
        'edit_ingredient_details' => 'Edit ingredient details',
        'delete_ingredient_confirm' => 'Delete this ingredient',
    ],

    // Stock Levels Subsection
    'stock_levels' => [
        'title' => 'Stock Levels',
        'subtitle' => 'Monitor current inventory levels and stock status across all locations',
        
        // Navigation & Actions
        'view_all_items' => 'View All Items',
        'add_new_item' => 'Add New Item',
        'bulk_actions' => 'Bulk Actions',
        'export_data' => 'Export Data',
        'refresh_data' => 'Refresh Data',
        
        // Status Labels
        'status_ok' => 'OK',
        'status_low' => 'Low Stock',
        'status_out' => 'Out of Stock',
        'status_critical' => 'Critical',
        'status_overstocked' => 'Overstocked',
        
        // Table Headers
        'item_name' => 'Item Name',
        'category' => 'Category',
        'current_stock' => 'Current Stock',
        'unit' => 'Unit',
        'reorder_level' => 'Reorder Level',
        'max_level' => 'Max Level',
        'location' => 'Location',
        'supplier' => 'Supplier',
        'last_updated' => 'Last Updated',
        'status' => 'Status',
        'actions' => 'Actions',
        
        // Filters
        'filters' => 'Filters & Search',
        'clear_filters' => 'Clear Filters',
        'search_items' => 'Search Items',
        'search_placeholder' => 'Search by name, SKU, or category...',
        'filter_category' => 'Filter by Category',
        'filter_supplier' => 'Filter by Supplier',
        'filter_location' => 'Filter by Location',
        'filter_status' => 'Filter by Status',
        'all_categories' => 'All Categories',
        'all_suppliers' => 'All Suppliers',
        'all_locations' => 'All Locations',
        'all_statuses' => 'All Statuses',
        
        // Categories
        'category_ingredients' => 'Ingredients',
        'category_beverages' => 'Beverages',
        'category_supplies' => 'Supplies',
        'category_packaging' => 'Packaging',
        'category_cleaning' => 'Cleaning',
        'category_equipment' => 'Equipment',
        
        // Locations
        'location_main_kitchen' => 'Main Kitchen',
        'location_cold_storage' => 'Cold Storage',
        'location_dry_storage' => 'Dry Storage',
        'location_freezer' => 'Freezer',
        'location_bar' => 'Bar',
        'location_prep_area' => 'Prep Area',
        
        // Detail Drawer
        'item_details' => 'Item Details',
        'basic_information' => 'Basic Information',
        'stock_information' => 'Stock Information',
        'supplier_information' => 'Supplier Information',
        'conversion_rates' => 'Conversion Rates',
        'stock_history' => 'Stock History',
        'recent_movements' => 'Recent Movements',
        
        // Item Information
        'item_code' => 'Item Code',
        'barcode' => 'Barcode',
        'description' => 'Description',
        'unit_type' => 'Unit Type',
        'cost_per_unit' => 'Cost per Unit',
        'selling_price' => 'Selling Price',
        'allergen_info' => 'Allergen Information',
        'storage_requirements' => 'Storage Requirements',
        'shelf_life' => 'Shelf Life',
        'minimum_order' => 'Minimum Order Quantity',
        
        // Stock Metrics
        'available_stock' => 'Available Stock',
        'reserved_stock' => 'Reserved Stock',
        'on_order' => 'On Order',
        'total_value' => 'Total Value',
        'average_usage' => 'Average Daily Usage',
        'days_remaining' => 'Days Remaining',
        'turnover_rate' => 'Turnover Rate',
        
        // History & Movements
        'movement_date' => 'Date',
        'movement_type' => 'Type',
        'movement_quantity' => 'Quantity',
        'movement_reason' => 'Reason',
        'movement_reference' => 'Reference',
        'movement_user' => 'User',
        'no_history' => 'No stock history available',
        
        // Movement Types
        'movement_received' => 'Stock Received',
        'movement_issued' => 'Stock Issued',
        'movement_adjusted' => 'Stock Adjusted',
        'movement_transferred' => 'Stock Transferred',
        'movement_wasted' => 'Stock Wasted',
        'movement_returned' => 'Stock Returned',
        
        // Actions
        'view_details' => 'View Details',
        'edit_item' => 'Edit Item',
        'adjust_stock' => 'Adjust Stock',
        'transfer_stock' => 'Transfer Stock',
        'create_po' => 'Create Purchase Order',
        'view_history' => 'View History',
        'print_label' => 'Print Label',
        
        // Bulk Actions
        'select_all' => 'Select All',
        'selected_items' => 'items selected',
        'bulk_adjust' => 'Bulk Adjust',
        'bulk_transfer' => 'Bulk Transfer',
        'bulk_reorder' => 'Bulk Reorder',
        'bulk_export' => 'Export Selected',
        
        // Alerts & Notifications
        'low_stock_items' => 'Low Stock Items',
        'out_of_stock_items' => 'Out of Stock Items',
        'critical_items' => 'Critical Items',
        'reorder_suggestions' => 'Reorder Suggestions',
        'stock_alert' => 'Stock Alert',
        'low_stock_warning' => 'Low stock warning for :item',
        'out_of_stock_alert' => 'Out of stock: :item',
        'critical_stock_alert' => 'Critical stock level: :item',
        
        // Empty States
        'no_items_found' => 'No Items Found',
        'no_items_description' => 'No items match your current filters. Try adjusting your search criteria or add your first inventory item.',
        'add_first_item' => 'Add First Item',
        'no_low_stock' => 'No Low Stock Items',
        'no_low_stock_description' => 'All items are currently at healthy stock levels.',
        
        // Pagination
        'showing' => 'Showing',
        'of' => 'of',
        'items' => 'items',
        'previous' => 'Previous',
        'next' => 'Next',
        'per_page' => 'per page',
        
        // Summary Cards
        'total_items' => 'Total Items',
        'low_stock_count' => 'Low Stock',
        'out_of_stock_count' => 'Out of Stock',
        'total_value' => 'Total Inventory Value',
        'items_need_reorder' => 'Items Need Reorder',
        'recent_movements' => 'Recent Movements',
        
        // Units
        'unit_kg' => 'Kilogram',
        'unit_g' => 'Gram',
        'unit_l' => 'Liter',
        'unit_ml' => 'Milliliter',
        'unit_pieces' => 'Pieces',
        'unit_boxes' => 'Boxes',
        'unit_bottles' => 'Bottles',
        'unit_cans' => 'Cans',
        
        // Messages
        'stock_updated' => 'Stock levels updated successfully',
        'item_added' => 'Item added successfully',
        'item_updated' => 'Item updated successfully',
        'item_deleted' => 'Item deleted successfully',
        'bulk_action_completed' => 'Bulk action completed successfully',
        'export_completed' => 'Export completed successfully',
        'data_refreshed' => 'Stock data refreshed successfully',
        
        // Validation
        'item_name_required' => 'Item name is required',
        'category_required' => 'Category is required',
        'unit_required' => 'Unit is required',
        'reorder_level_required' => 'Reorder level is required',
        'location_required' => 'Location is required',
        'invalid_quantity' => 'Invalid quantity value',
        'quantity_must_be_positive' => 'Quantity must be positive',
        
        // Tooltips & Help
        'reorder_level_help' => 'Stock level at which reordering should be triggered',
        'max_level_help' => 'Maximum recommended stock level',
        'reserved_stock_help' => 'Stock allocated for pending orders',
        'turnover_rate_help' => 'How quickly this item is consumed',
        'days_remaining_help' => 'Estimated days until stock runs out at current usage rate',
        
        // Mobile
        'swipe_for_actions' => 'Swipe for actions',
        'tap_to_view_details' => 'Tap to view details',
        'pull_to_refresh' => 'Pull to refresh',
        
        // Accessibility
        'sort_by' => 'Sort by',
        'sort_ascending' => 'Sort ascending',
        'sort_descending' => 'Sort descending',
        'filter_menu' => 'Filter menu',
        'close_drawer' => 'Close details drawer',
        'open_drawer' => 'Open details drawer',
    ],

    // Settings Subsection
    'settings' => [
        'title' => 'Inventory Settings',
        'subtitle' => 'Manage categories, units, ingredient types, and cost structures',
        'nav_title' => 'Settings',
        
        // Categories
        'categories' => [
            'title' => 'Categories',
            'add_category' => 'Add Category',
            'edit_category' => 'Edit Category',
            'name' => 'Category Name',
            'description' => 'Description',
            'color' => 'Color',
            'icon' => 'Icon',
            'active' => 'Active',
            'actions' => 'Actions',
        ],
        
        // Units
        'units' => [
            'title' => 'Units',
            'add_unit' => 'Add Unit',
            'edit_unit' => 'Edit Unit',
            'name' => 'Unit Name',
            'symbol' => 'Symbol',
            'type' => 'Type',
            'description' => 'Description',
            'base_conversion' => 'Base Conversion Factor',
            'base_unit' => 'Base Unit',
            'types' => [
                'weight' => 'Weight',
                'volume' => 'Volume',
                'count' => 'Count',
                'custom' => 'Custom',
            ],
        ],
        
        // Ingredient Types
        'types' => [
            'title' => 'Ingredient Types',
            'add_type' => 'Add Type',
            'edit_type' => 'Edit Type',
            'name' => 'Type Name',
            'description' => 'Description',
            'measurement_type' => 'Measurement Type',
            'compatible_units' => 'Compatible Units',
            'color' => 'Color',
            'properties' => 'Properties',
        ],
        
        // Messages
        'category_created' => 'Category created successfully',
        'category_updated' => 'Category updated successfully',
        'category_deleted' => 'Category deleted successfully',
        'unit_created' => 'Unit created successfully',
        'unit_updated' => 'Unit updated successfully',
        'unit_deleted' => 'Unit deleted successfully',
        'type_created' => 'Ingredient type created successfully',
        'type_updated' => 'Ingredient type updated successfully',
        'type_deleted' => 'Ingredient type deleted successfully',
        
        // Validation
        'name_required' => 'Name is required',
        'symbol_required' => 'Symbol is required',
        'color_required' => 'Color is required',
        'type_required' => 'Type is required',
        
        // Tabs
        'categories_tab' => 'Categories',
        'units_tab' => 'Units',
        'types_tab' => 'Ingredient Types',
    ],
];
