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

    // Recipes / BOM Subsection
    'recipes' => [
        'title' => 'Recipes & Bill of Materials',
        'nav_title' => 'Recipes / BOM',
        'subtitle' => 'Manage recipes, ingredient lists, and bill of materials for menu items',
        
        // Navigation & Actions
        'add_recipe' => 'Add Recipe',
        'edit_recipe' => 'Edit Recipe',
        'duplicate_recipe' => 'Duplicate Recipe',
        'delete_recipe' => 'Delete Recipe',
        'view_recipe' => 'View Recipe',
        
        // Recipe Fields
        'recipe_name' => 'Recipe Name',
        'recipe_code' => 'Recipe Code',
        'description' => 'Description',
        'category' => 'Category',
        'serving_size' => 'Serving Size',
        'prep_time' => 'Prep Time',
        'cook_time' => 'Cook Time',
        'total_time' => 'Total Time',
        'difficulty' => 'Difficulty Level',
        'cost_per_serving' => 'Cost per Serving',
        'total_cost' => 'Total Recipe Cost',
        'yield' => 'Recipe Yield',
        'status' => 'Status',
        
        // Recipe Categories
        'categories' => [
            'appetizer' => 'Appetizer',
            'main_course' => 'Main Course',
            'dessert' => 'Dessert',
            'beverage' => 'Beverage',
            'sauce' => 'Sauce',
            'side_dish' => 'Side Dish',
            'soup' => 'Soup',
            'salad' => 'Salad',
        ],
        
        // Difficulty Levels
        'difficulty_levels' => [
            'easy' => 'Easy',
            'medium' => 'Medium',
            'hard' => 'Hard',
            'expert' => 'Expert',
        ],
        
        // Recipe Status
        'statuses' => [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'draft' => 'Draft',
            'testing' => 'Testing',
        ],
        
        // Ingredients Section
        'ingredients' => 'Ingredients',
        'ingredient_name' => 'Ingredient',
        'quantity' => 'Quantity',
        'unit' => 'Unit',
        'cost' => 'Cost',
        'notes' => 'Notes',
        'add_ingredient' => 'Add Ingredient',
        'remove_ingredient' => 'Remove Ingredient',
        
        // Instructions
        'instructions' => 'Instructions',
        'step' => 'Step',
        'add_step' => 'Add Step',
        'remove_step' => 'Remove Step',
        
        // Nutrition (Optional)
        'nutrition' => 'Nutrition Information',
        'calories' => 'Calories',
        'protein' => 'Protein (g)',
        'carbs' => 'Carbohydrates (g)',
        'fat' => 'Fat (g)',
        'fiber' => 'Fiber (g)',
        'sodium' => 'Sodium (mg)',
        
        // Table Headers
        'recipe_details' => 'Recipe Details',
        'ingredient_list' => 'Ingredient List',
        'recipe_instructions' => 'Recipe Instructions',
        'cost_analysis' => 'Cost Analysis',
        
        // Filters
        'all_categories' => 'All Categories',
        'all_difficulties' => 'All Difficulties',
        'all_statuses' => 'All Statuses',
        'filter_by_category' => 'Filter by Category',
        'filter_by_difficulty' => 'Filter by Difficulty',
        'filter_by_status' => 'Filter by Status',
        'search_recipes' => 'Search recipes...',
        
        // Messages
        'recipe_created' => 'Recipe created successfully',
        'recipe_updated' => 'Recipe updated successfully',
        'recipe_deleted' => 'Recipe deleted successfully',
        'recipe_duplicated' => 'Recipe duplicated successfully',
        'no_recipes' => 'No recipes found',
        'no_recipes_message' => 'Start by creating your first recipe to build your menu.',
        
        // Validation
        'name_required' => 'Recipe name is required',
        'category_required' => 'Category is required',
        'serving_size_required' => 'Serving size is required',
        'ingredients_required' => 'At least one ingredient is required',
        'instructions_required' => 'Recipe instructions are required',
        
        // Actions
        'actions' => 'Actions',
        'bulk_actions' => 'Bulk Actions',
        'export_recipe' => 'Export Recipe',
        'print_recipe' => 'Print Recipe',
        'calculate_cost' => 'Calculate Cost',
        
        // Empty States
        'no_ingredients' => 'No ingredients added',
        'no_instructions' => 'No instructions added',
        'add_first_ingredient' => 'Add your first ingredient',
        'add_first_instruction' => 'Add your first instruction step',
        
        // Tooltips
        'recipe_code_help' => 'Unique identifier for this recipe',
        'serving_size_help' => 'Number of servings this recipe makes',
        'yield_help' => 'Total quantity produced by this recipe',
        'cost_calculation_help' => 'Automatically calculated based on ingredient costs',
    ],

    // Movements Subsection
    'movements' => [
        'title' => 'Inventory Movements',
        'nav_title' => 'Movements',
        'subtitle' => 'Track all inventory movements, transfers, adjustments, and stock changes',
        
        // Navigation & Actions
        'add_movement' => 'Add Movement',
        'edit_movement' => 'Edit Movement',
        'delete_movement' => 'Delete Movement',
        'view_movement' => 'View Movement Details',
        
        // Movement Types
        'movement_types' => [
            'receive' => 'Receive',
            'transfer' => 'Transfer',
            'adjust' => 'Adjust',
            'waste' => 'Waste',
            'return' => 'Return',
            'sale' => 'Sale',
            'production' => 'Production',
        ],
        
        // Table Headers
        'item' => 'Item',
        'quantity' => 'Quantity',
        'movement_type' => 'Movement Type',
        'location' => 'Location',
        'from_location' => 'From Location',
        'to_location' => 'To Location',
        'staff' => 'Staff Member',
        'date_time' => 'Date & Time',
        'notes' => 'Notes',
        'reference' => 'Reference',
        
        // Filters
        'filter_by_type' => 'Filter by Movement Type',
        'filter_by_location' => 'Filter by Location',
        'filter_by_staff' => 'Filter by Staff',
        'filter_by_date' => 'Filter by Date Range',
        'all_types' => 'All Movement Types',
        'all_locations' => 'All Locations',
        'all_staff' => 'All Staff',
        'date_from' => 'From Date',
        'date_to' => 'To Date',
        'search_items' => 'Search items...',
        
        // Form Fields
        'select_item' => 'Select Item',
        'select_movement_type' => 'Select Movement Type',
        'enter_quantity' => 'Enter Quantity',
        'select_location' => 'Select Location',
        'select_from_location' => 'Select From Location',
        'select_to_location' => 'Select To Location',
        'select_staff' => 'Select Staff Member',
        'movement_date' => 'Movement Date',
        'movement_time' => 'Movement Time',
        'add_notes' => 'Add notes (optional)',
        'reference_number' => 'Reference Number',
        
        // Movement Details
        'movement_details' => 'Movement Details',
        'item_details' => 'Item Details',
        'movement_info' => 'Movement Information',
        'location_info' => 'Location Information',
        'staff_info' => 'Staff Information',
        'additional_info' => 'Additional Information',
        
        // Messages
        'movement_created' => 'Movement recorded successfully',
        'movement_updated' => 'Movement updated successfully',
        'movement_deleted' => 'Movement deleted successfully',
        'no_movements' => 'No movements found',
        'no_movements_message' => 'No inventory movements yet. Add your first one to start tracking stock changes.',
        'loading_movements' => 'Loading movements...',
        'error_loading' => 'Error loading movements',
        'retry' => 'Retry',
        
        // Validation
        'item_required' => 'Item is required',
        'type_required' => 'Movement type is required',
        'quantity_required' => 'Quantity is required',
        'quantity_positive' => 'Quantity must be positive',
        'location_required' => 'Location is required',
        'from_location_required' => 'From location is required',
        'to_location_required' => 'To location is required',
        'staff_required' => 'Staff member is required',
        'date_required' => 'Date is required',
        'invalid_date' => 'Invalid date format',
        
        // Actions
        'save_movement' => 'Save Movement',
        'cancel' => 'Cancel',
        'close' => 'Close',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'confirm_delete' => 'Are you sure you want to delete this movement?',
        'delete_warning' => 'This action cannot be undone.',
        
        // Drawer/Modal
        'movement_drawer_title' => 'Movement Details',
        'add_movement_title' => 'Add New Movement',
        'edit_movement_title' => 'Edit Movement',
        'close_drawer' => 'Close Drawer',
        
        // Summary & Stats
        'total_movements' => 'Total Movements',
        'recent_movements' => 'Recent Movements',
        'movements_today' => 'Movements Today',
        'movements_this_week' => 'This Week',
        'movements_this_month' => 'This Month',
        
        // Locations
        'locations' => [
            'warehouse' => 'Main Warehouse',
            'kitchen' => 'Kitchen',
            'storage' => 'Storage Room',
            'freezer' => 'Freezer',
            'dry_storage' => 'Dry Storage',
            'bar' => 'Bar Area',
            'prep_area' => 'Prep Area',
        ],
        
        // Staff Roles
        'staff_roles' => [
            'manager' => 'Manager',
            'chef' => 'Chef',
            'cook' => 'Cook',
            'server' => 'Server',
            'inventory_clerk' => 'Inventory Clerk',
        ],
        
        // Time Formats
        'time_ago' => ':time ago',
        'just_now' => 'Just now',
        'minutes_ago' => ':count minutes ago',
        'hours_ago' => ':count hours ago',
        'days_ago' => ':count days ago',
        
        // Tooltips
        'click_row_details' => 'Click row to view details',
        'movement_type_help' => 'Select the type of inventory movement',
        'quantity_help' => 'Enter the quantity moved (positive number)',
        'location_help' => 'Select the location affected by this movement',
        'notes_help' => 'Add any additional notes about this movement',
    ],

    // Purchasing Subsection
    'purchasing' => [
        'title' => 'Purchase Orders',
        'nav_title' => 'Purchasing (POs)',
        'subtitle' => 'Manage purchase orders, suppliers, and procurement processes',
        
        // Navigation & Actions
        'new_purchase_order' => 'New Purchase Order',
        'edit_purchase_order' => 'Edit Purchase Order',
        'delete_purchase_order' => 'Delete Purchase Order',
        'view_purchase_order' => 'View Purchase Order Details',
        'duplicate_po' => 'Duplicate PO',
        
        // PO Status
        'po_statuses' => [
            'draft' => 'Draft',
            'sent' => 'Sent',
            'received' => 'Received',
            'cancelled' => 'Cancelled',
            'partial' => 'Partial',
            'overdue' => 'Overdue',
        ],
        
        // Table Headers
        'po_number' => 'PO Number',
        'supplier' => 'Supplier',
        'order_date' => 'Order Date',
        'delivery_date' => 'Expected Delivery',
        'status' => 'Status',
        'total' => 'Total Amount',
        'items_count' => 'Items',
        'created_by' => 'Created By',
        
        // Filters
        'filter_by_supplier' => 'Filter by Supplier',
        'filter_by_status' => 'Filter by Status',
        'filter_by_date' => 'Filter by Date Range',
        'all_suppliers' => 'All Suppliers',
        'all_statuses' => 'All Statuses',
        'order_date_from' => 'Order Date From',
        'order_date_to' => 'Order Date To',
        'delivery_date_from' => 'Delivery Date From',
        'delivery_date_to' => 'Delivery Date To',
        'search_po' => 'Search PO number, items...',
        
        // Form Fields
        'select_supplier' => 'Select Supplier',
        'po_number_auto' => 'PO Number (Auto-generated)',
        'order_date_field' => 'Order Date',
        'expected_delivery' => 'Expected Delivery Date',
        'special_instructions' => 'Special Instructions',
        'payment_terms' => 'Payment Terms',
        'delivery_address' => 'Delivery Address',
        
        // Line Items
        'line_items' => 'Line Items',
        'add_line_item' => 'Add Line Item',
        'remove_line_item' => 'Remove Line Item',
        'item_name' => 'Item',
        'quantity' => 'Quantity',
        'unit_price' => 'Unit Price',
        'line_total' => 'Line Total',
        'select_item' => 'Select Item',
        'enter_quantity' => 'Enter Quantity',
        'enter_unit_price' => 'Enter Unit Price',
        
        // Totals
        'subtotal' => 'Subtotal',
        'tax_rate' => 'Tax Rate',
        'tax_amount' => 'Tax Amount',
        'shipping_cost' => 'Shipping Cost',
        'discount' => 'Discount',
        'grand_total' => 'Grand Total',
        
        // PO Details
        'po_details' => 'Purchase Order Details',
        'supplier_info' => 'Supplier Information',
        'order_info' => 'Order Information',
        'delivery_info' => 'Delivery Information',
        'financial_summary' => 'Financial Summary',
        'line_items_summary' => 'Line Items Summary',
        
        // Messages
        'po_created' => 'Purchase order created successfully',
        'po_updated' => 'Purchase order updated successfully',
        'po_deleted' => 'Purchase order deleted successfully',
        'po_sent' => 'Purchase order sent to supplier',
        'po_received' => 'Purchase order marked as received',
        'po_cancelled' => 'Purchase order cancelled',
        'no_purchase_orders' => 'No purchase orders found',
        'no_purchase_orders_message' => 'No purchase orders yet. Create your first one to start managing procurement.',
        'loading_purchase_orders' => 'Loading purchase orders...',
        'error_loading' => 'Error loading purchase orders',
        'retry' => 'Retry',
        
        // Validation
        'supplier_required' => 'Supplier is required',
        'order_date_required' => 'Order date is required',
        'delivery_date_required' => 'Expected delivery date is required',
        'delivery_date_future' => 'Delivery date must be in the future',
        'line_items_required' => 'At least one line item is required',
        'item_required' => 'Item is required',
        'quantity_required' => 'Quantity is required',
        'quantity_positive' => 'Quantity must be positive',
        'unit_price_required' => 'Unit price is required',
        'unit_price_positive' => 'Unit price must be positive',
        'invalid_date' => 'Invalid date format',
        
        // Actions
        'save_po' => 'Save Purchase Order',
        'send_po' => 'Send to Supplier',
        'mark_received' => 'Mark as Received',
        'cancel_po' => 'Cancel PO',
        'cancel' => 'Cancel',
        'close' => 'Close',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'duplicate' => 'Duplicate',
        'print_po' => 'Print PO',
        'export_pdf' => 'Export PDF',
        'confirm_delete' => 'Are you sure you want to delete this purchase order?',
        'confirm_cancel' => 'Are you sure you want to cancel this purchase order?',
        'delete_warning' => 'This action cannot be undone.',
        'cancel_warning' => 'Cancelled purchase orders cannot be modified.',
        
        // Drawer/Modal
        'po_drawer_title' => 'Purchase Order Details',
        'add_po_title' => 'Create New Purchase Order',
        'edit_po_title' => 'Edit Purchase Order',
        'close_drawer' => 'Close Drawer',
        
        // Summary & Stats
        'total_purchase_orders' => 'Total Purchase Orders',
        'draft_pos' => 'Draft POs',
        'sent_pos' => 'Sent POs',
        'received_pos' => 'Received POs',
        'pending_pos' => 'Pending POs',
        'total_value' => 'Total Value',
        'this_month_value' => 'This Month Value',
        'avg_po_value' => 'Average PO Value',
        
        // Suppliers
        'supplier_details' => [
            'name' => 'Supplier Name',
            'contact_person' => 'Contact Person',
            'email' => 'Email',
            'phone' => 'Phone',
            'address' => 'Address',
            'payment_terms' => 'Payment Terms',
        ],
        
        // Payment Terms
        'payment_terms_options' => [
            'net_30' => 'Net 30 Days',
            'net_15' => 'Net 15 Days',
            'net_7' => 'Net 7 Days',
            'cod' => 'Cash on Delivery',
            'prepaid' => 'Prepaid',
            'net_60' => 'Net 60 Days',
        ],
        
        // Time Formats
        'days_ago' => ':count days ago',
        'due_in_days' => 'Due in :count days',
        'overdue_by_days' => 'Overdue by :count days',
        
        // Tooltips
        'click_row_details' => 'Click row to view details',
        'po_number_help' => 'Unique purchase order identifier',
        'supplier_help' => 'Select the supplier for this purchase order',
        'delivery_date_help' => 'Expected date when items will be delivered',
        'line_items_help' => 'Add items to purchase with quantities and prices',
        'special_instructions_help' => 'Any special delivery or handling instructions',
        
        // Status Descriptions
        'status_descriptions' => [
            'draft' => 'Purchase order is being prepared',
            'sent' => 'Purchase order has been sent to supplier',
            'received' => 'Items have been received and verified',
            'cancelled' => 'Purchase order has been cancelled',
            'partial' => 'Some items received, others pending',
            'overdue' => 'Expected delivery date has passed',
        ],
        
        // Calculations
        'auto_calculate' => 'Auto-calculate',
        'manual_entry' => 'Manual entry',
        'tax_included' => 'Tax included',
        'tax_excluded' => 'Tax excluded',
        'currency_symbol' => '$',
        
        // Line Item Actions
        'move_up' => 'Move Up',
        'move_down' => 'Move Down',
        'duplicate_line' => 'Duplicate Line',
        'clear_line' => 'Clear Line',
    ],

    // Locations Subsection
    'locations' => [
        'title' => 'Stock Locations',
        'nav_title' => 'Stock Locations',
        'subtitle' => 'Manage storage locations, capacity, and inventory organization',
        
        // Navigation & Actions
        'add_location' => 'Add Location',
        'edit_location' => 'Edit Location',
        'delete_location' => 'Delete Location',
        'view_location' => 'View Location Details',
        'activate_location' => 'Activate Location',
        'deactivate_location' => 'Deactivate Location',
        
        // Location Types
        'location_types' => [
            'fridge' => 'Fridge',
            'freezer' => 'Freezer',
            'pantry' => 'Pantry',
            'bar' => 'Bar',
            'storage_room' => 'Storage Room',
            'warehouse' => 'Warehouse',
            'kitchen' => 'Kitchen',
            'prep_area' => 'Prep Area',
            'dry_storage' => 'Dry Storage',
            'cold_storage' => 'Cold Storage',
            'wine_cellar' => 'Wine Cellar',
            'office' => 'Office',
        ],
        
        // Location Status
        'location_statuses' => [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'maintenance' => 'Maintenance',
            'full' => 'Full',
            'reserved' => 'Reserved',
        ],
        
        // Table Headers
        'location_name' => 'Location Name',
        'type' => 'Type',
        'items_stored' => 'Items Stored',
        'capacity' => 'Capacity',
        'status' => 'Status',
        'description' => 'Description',
        'temperature' => 'Temperature',
        'created_date' => 'Created Date',
        'last_updated' => 'Last Updated',
        
        // Filters
        'filter_by_type' => 'Filter by Type',
        'filter_by_status' => 'Filter by Status',
        'all_types' => 'All Types',
        'all_statuses' => 'All Statuses',
        'search_locations' => 'Search locations...',
        
        // Form Fields
        'location_name_field' => 'Location Name',
        'select_type' => 'Select Type',
        'location_description' => 'Location Description',
        'select_status' => 'Select Status',
        'capacity_percentage' => 'Capacity Percentage',
        'max_capacity' => 'Maximum Capacity',
        'current_capacity' => 'Current Capacity',
        'temperature_range' => 'Temperature Range',
        'special_requirements' => 'Special Requirements',
        
        // Location Details
        'location_details' => 'Location Details',
        'basic_info' => 'Basic Information',
        'capacity_info' => 'Capacity Information',
        'items_info' => 'Items Information',
        'storage_conditions' => 'Storage Conditions',
        'location_stats' => 'Location Statistics',
        
        // Messages
        'location_created' => 'Location created successfully',
        'location_updated' => 'Location updated successfully',
        'location_deleted' => 'Location deleted successfully',
        'location_activated' => 'Location activated successfully',
        'location_deactivated' => 'Location deactivated successfully',
        'no_locations' => 'No locations found',
        'no_locations_message' => 'No stock locations yet. Add your first one to start organizing inventory.',
        'loading_locations' => 'Loading locations...',
        'error_loading' => 'Error loading locations',
        'retry' => 'Retry',
        
        // Validation
        'name_required' => 'Location name is required',
        'type_required' => 'Location type is required',
        'status_required' => 'Status is required',
        'capacity_invalid' => 'Capacity must be between 0 and 100',
        'name_unique' => 'Location name must be unique',
        'name_min_length' => 'Location name must be at least 2 characters',
        'description_max_length' => 'Description cannot exceed 500 characters',
        
        // Actions
        'save_location' => 'Save Location',
        'cancel' => 'Cancel',
        'close' => 'Close',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'activate' => 'Activate',
        'deactivate' => 'Deactivate',
        'confirm_delete' => 'Are you sure you want to delete this location?',
        'confirm_deactivate' => 'Are you sure you want to deactivate this location?',
        'delete_warning' => 'This action cannot be undone. Items in this location will need to be moved.',
        'deactivate_warning' => 'Items in this location will need to be moved to active locations.',
        
        // Drawer/Modal
        'location_drawer_title' => 'Location Details',
        'add_location_title' => 'Add New Location',
        'edit_location_title' => 'Edit Location',
        'close_drawer' => 'Close Drawer',
        
        // Summary & Stats
        'total_locations' => 'Total Locations',
        'active_locations' => 'Active Locations',
        'inactive_locations' => 'Inactive Locations',
        'locations_at_capacity' => 'At Capacity',
        'average_capacity' => 'Average Capacity',
        'total_items_stored' => 'Total Items Stored',
        
        // Capacity
        'capacity_full' => 'Full',
        'capacity_high' => 'High',
        'capacity_medium' => 'Medium',
        'capacity_low' => 'Low',
        'capacity_empty' => 'Empty',
        'capacity_unknown' => 'Unknown',
        'capacity_percentage_format' => ':percentage% full',
        'items_count_format' => ':count items',
        'no_items' => 'No items',
        
        // Items in Location
        'items_in_location' => 'Items in this Location',
        'no_items_in_location' => 'No items currently stored in this location',
        'item_name' => 'Item Name',
        'item_quantity' => 'Quantity',
        'item_unit' => 'Unit',
        'date_added' => 'Date Added',
        'move_item' => 'Move Item',
        'view_item' => 'View Item',
        
        // Location Conditions
        'temperature_controlled' => 'Temperature Controlled',
        'humidity_controlled' => 'Humidity Controlled',
        'climate_controlled' => 'Climate Controlled',
        'secure_access' => 'Secure Access',
        'refrigerated' => 'Refrigerated',
        'frozen' => 'Frozen',
        'dry_conditions' => 'Dry Conditions',
        
        // Tooltips
        'click_row_details' => 'Click row to view details',
        'location_name_help' => 'Enter a unique name for this location',
        'type_help' => 'Select the type of storage location',
        'capacity_help' => 'Enter the current capacity percentage (0-100)',
        'description_help' => 'Add any additional details about this location',
        'status_help' => 'Set whether this location is currently in use',
        
        // Status Descriptions
        'status_descriptions' => [
            'active' => 'Location is available for storing items',
            'inactive' => 'Location is not currently in use',
            'maintenance' => 'Location is under maintenance',
            'full' => 'Location has reached maximum capacity',
            'reserved' => 'Location is reserved for specific items',
        ],
        
        // Location Analytics
        'utilization_rate' => 'Utilization Rate',
        'turnover_rate' => 'Turnover Rate',
        'last_activity' => 'Last Activity',
        'most_stored_item' => 'Most Stored Item',
        'location_efficiency' => 'Location Efficiency',
        
        // Quick Actions
        'quick_actions' => 'Quick Actions',
        'view_items' => 'View Items',
        'add_item' => 'Add Item',
        'move_items' => 'Move Items',
        'check_capacity' => 'Check Capacity',
        'location_report' => 'Location Report',
        
        // Bulk Actions
        'bulk_actions' => 'Bulk Actions',
        'activate_selected' => 'Activate Selected',
        'deactivate_selected' => 'Deactivate Selected',
        'delete_selected' => 'Delete Selected',
        'export_selected' => 'Export Selected',
        'locations_selected' => ':count locations selected',
        
        // Import/Export
        'import_locations' => 'Import Locations',
        'export_locations' => 'Export Locations',
        'download_template' => 'Download Template',
        'upload_file' => 'Upload File',
        
        // Alerts
        'capacity_alert' => 'Location is at :percentage% capacity',
        'over_capacity' => 'Location is over capacity',
        'maintenance_due' => 'Maintenance is due for this location',
        'inactive_warning' => 'This location is inactive',
        
        // Time Formats
        'created_ago' => 'Created :time ago',
        'updated_ago' => 'Updated :time ago',
        'last_used' => 'Last used :time ago',
        'never_used' => 'Never used',
    ],

    // Suppliers Subsection
    'suppliers' => [
        'title' => 'Suppliers',
        'nav_title' => 'Suppliers',
        'subtitle' => 'Manage supplier information, contacts, and procurement relationships',
        
        // Navigation & Actions
        'add_supplier' => 'Add Supplier',
        'edit_supplier' => 'Edit Supplier',
        'delete_supplier' => 'Delete Supplier',
        'view_supplier' => 'View Supplier Details',
        'activate_supplier' => 'Activate Supplier',
        'deactivate_supplier' => 'Deactivate Supplier',
        'create_po_for_supplier' => 'Create Purchase Order',
        
        // Supplier Status
        'supplier_statuses' => [
            'active' => 'Active',
            'inactive' => 'Inactive',
        ],
        
        // Table Headers
        'supplier_name' => 'Supplier Name',
        'contact_person' => 'Contact Person',
        'phone_email' => 'Phone / Email',
        'items_supplied' => 'Items Supplied',
        'status' => 'Status',
        'created_date' => 'Created Date',
        'last_updated' => 'Last Updated',
        'actions' => 'Actions',
        
        // Filters
        'filters' => 'Filters',
        'clear_filters' => 'Clear Filters',
        'apply_filters' => 'Apply Filters',
        'filter_by_status' => 'Filter by Status',
        'all_statuses' => 'All Statuses',
        'search_suppliers' => 'Search suppliers...',
        'search_placeholder' => 'Search by name, contact, or email...',
        
        // Form Fields
        'supplier_name_field' => 'Supplier Name',
        'contact_person_field' => 'Contact Person',
        'phone_field' => 'Phone Number',
        'email_field' => 'Email Address',
        'address_field' => 'Address',
        'notes_field' => 'Notes',
        'select_status' => 'Select Status',
        'payment_terms' => 'Payment Terms',
        
        // Supplier Details
        'supplier_details' => 'Supplier Details',
        'basic_information' => 'Basic Information',
        'contact_information' => 'Contact Information',
        'business_information' => 'Business Information',
        'items_information' => 'Items Information',
        'purchase_history' => 'Purchase History',
        'recent_orders' => 'Recent Purchase Orders',
        'additional_info' => 'Additional Information',
        
        // Supplier Information
        'supplier_code' => 'Supplier Code',
        'business_name' => 'Business Name',
        'tax_id' => 'Tax ID',
        'website' => 'Website',
        'established_date' => 'Established Date',
        'credit_limit' => 'Credit Limit',
        'credit_terms' => 'Credit Terms',
        'delivery_terms' => 'Delivery Terms',
        'minimum_order' => 'Minimum Order Amount',
        'lead_time' => 'Lead Time (days)',
        
        // Payment Terms
        'payment_terms_options' => [
            'net_30' => 'Net 30 Days',
            'net_15' => 'Net 15 Days',
            'net_7' => 'Net 7 Days',
            'cod' => 'Cash on Delivery',
            'prepaid' => 'Prepaid',
            'net_60' => 'Net 60 Days',
        ],
        
        // Items Supplied
        'items_supplied_count' => 'Items Supplied Count',
        'no_items_supplied' => 'No items supplied',
        'no_contact' => 'No contact',
        'no_contact_info' => 'No contact info',
        'items' => 'items',
        'item_name' => 'Item Name',
        'item_code' => 'Item Code',
        'item_unit' => 'Unit',
        'last_price' => 'Last Price',
        'last_order_date' => 'Last Order Date',
        'view_item_details' => 'View Item Details',
        
        // Purchase Orders
        'purchase_orders' => 'Purchase Orders',
        'no_purchase_orders' => 'No purchase orders',
        'po_number' => 'PO Number',
        'po_date' => 'Order Date',
        'po_status' => 'Status',
        'po_total' => 'Total Amount',
        'view_po_details' => 'View PO Details',
        
        // Messages
        'supplier_created' => 'Supplier created successfully',
        'supplier_updated' => 'Supplier updated successfully',
        'supplier_deleted' => 'Supplier deleted successfully',
        'supplier_activated' => 'Supplier activated successfully',
        'supplier_deactivated' => 'Supplier deactivated successfully',
        'cannot_delete_has_items' => 'Cannot delete supplier with associated inventory items',
        'no_suppliers' => 'No suppliers found',
        'no_suppliers_message' => 'No suppliers yet. Add your first supplier to start managing procurement.',
        'loading_suppliers' => 'Loading suppliers...',
        'error_loading' => 'Error loading suppliers',
        'retry' => 'Retry',
        
        // Validation
        'name_required' => 'Supplier name is required',
        'name_unique' => 'Supplier name must be unique',
        'email_valid' => 'Email must be a valid email address',
        'phone_valid' => 'Phone number format is invalid',
        'status_required' => 'Status is required',
        'name_min_length' => 'Supplier name must be at least 2 characters',
        'name_max_length' => 'Supplier name cannot exceed 255 characters',
        'contact_person_max_length' => 'Contact person name cannot exceed 255 characters',
        'phone_max_length' => 'Phone number cannot exceed 20 characters',
        'email_max_length' => 'Email cannot exceed 255 characters',
        'address_max_length' => 'Address cannot exceed 500 characters',
        'notes_max_length' => 'Notes cannot exceed 1000 characters',
        
        // Actions
        'save_supplier' => 'Save Supplier',
        'cancel' => 'Cancel',
        'close' => 'Close',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'activate' => 'Activate',
        'deactivate' => 'Deactivate',
        'confirm_delete' => 'Are you sure you want to delete this supplier?',
        'confirm_deactivate' => 'Are you sure you want to deactivate this supplier?',
        'delete_warning' => 'This action cannot be undone. Make sure no inventory items are linked to this supplier.',
        'deactivate_warning' => 'Deactivated suppliers cannot receive new purchase orders.',
        
        // Drawer/Modal
        'supplier_drawer_title' => 'Supplier Details',
        'add_supplier_title' => 'Add New Supplier',
        'edit_supplier_title' => 'Edit Supplier',
        'close_drawer' => 'Close Drawer',
        
        // Summary & Stats
        'total_suppliers' => 'Total Suppliers',
        'active_suppliers' => 'Active Suppliers',
        'inactive_suppliers' => 'Inactive Suppliers',
        'suppliers_with_orders' => 'Suppliers with Orders',
        'average_lead_time' => 'Average Lead Time',
        'total_purchase_value' => 'Total Purchase Value',
        
        // Quick Actions
        'quick_actions' => 'Quick Actions',
        'view_items' => 'View Items',
        'create_po' => 'Create PO',
        'view_orders' => 'View Orders',
        'contact_supplier' => 'Contact Supplier',
        'supplier_report' => 'Supplier Report',
        
        // Bulk Actions
        'bulk_actions' => 'Bulk Actions',
        'activate_selected' => 'Activate Selected',
        'deactivate_selected' => 'Deactivate Selected',
        'delete_selected' => 'Delete Selected',
        'export_selected' => 'Export Selected',
        'suppliers_selected' => ':count suppliers selected',
        
        // Import/Export
        'import_suppliers' => 'Import Suppliers',
        'export_suppliers' => 'Export Suppliers',
        'download_template' => 'Download Template',
        'upload_file' => 'Upload File',
        
        // Empty States
        'no_suppliers_found' => 'No Suppliers Found',
        'no_suppliers_description' => 'No suppliers match your current filters. Try adjusting your search criteria or add your first supplier.',
        'add_first_supplier' => 'Add First Supplier',
        'no_active_suppliers' => 'No Active Suppliers',
        'no_active_suppliers_description' => 'All suppliers are currently inactive.',
        
        // Pagination
        'showing' => 'Showing',
        'of' => 'of',
        'suppliers' => 'suppliers',
        'previous' => 'Previous',
        'next' => 'Next',
        'per_page' => 'per page',
        
        // Time Formats
        'created_ago' => 'Created :time ago',
        'updated_ago' => 'Updated :time ago',
        'last_contact' => 'Last contact :time ago',
        'never_contacted' => 'Never contacted',
        
        // Tooltips
        'click_row_details' => 'Click row to view details',
        'supplier_name_help' => 'Enter the full business name of the supplier',
        'contact_person_help' => 'Primary contact person at the supplier',
        'phone_help' => 'Primary phone number for orders and inquiries',
        'email_help' => 'Primary email address for communications',
        'address_help' => 'Full business address of the supplier',
        'notes_help' => 'Any additional notes about this supplier',
        'status_help' => 'Set whether this supplier is currently active',
        
        // Status Descriptions
        'status_descriptions' => [
            'active' => 'Supplier is available for new orders',
            'inactive' => 'Supplier is not currently accepting orders',
        ],
        
        // Contact Methods
        'contact_methods' => [
            'phone' => 'Phone',
            'email' => 'Email',
            'fax' => 'Fax',
            'website' => 'Website',
        ],
        
        // Supplier Categories
        'supplier_categories' => [
            'food_beverage' => 'Food & Beverage',
            'equipment' => 'Equipment',
            'supplies' => 'Supplies',
            'packaging' => 'Packaging',
            'cleaning' => 'Cleaning',
            'maintenance' => 'Maintenance',
            'services' => 'Services',
            'other' => 'Other',
        ],
        
        // Performance Metrics
        'performance_metrics' => 'Performance Metrics',
        'on_time_delivery' => 'On-Time Delivery',
        'quality_rating' => 'Quality Rating',
        'response_time' => 'Response Time',
        'order_accuracy' => 'Order Accuracy',
        'price_competitiveness' => 'Price Competitiveness',
        
        // Alerts
        'inactive_supplier_alert' => 'This supplier is inactive',
        'no_contact_info' => 'Missing contact information',
        'no_recent_orders' => 'No recent orders from this supplier',
        'overdue_payment' => 'Overdue payment to this supplier',
        
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
        'select_supplier' => 'Select supplier',
        'supplier_selected' => 'Supplier selected',
        'sort_by_name' => 'Sort by supplier name',
        'sort_by_status' => 'Sort by status',
        'sort_by_date' => 'Sort by date',
        'supplier_details' => 'View supplier details',
        'edit_supplier_details' => 'Edit supplier details',
        'delete_supplier_confirm' => 'Delete this supplier',
    ],

    // Alerts Subsection
    'alerts' => [
        'title' => 'Inventory Alerts',
        'nav_title' => 'Low-stock Alerts',
        'subtitle' => 'Manage stock level alerts and monitor inventory thresholds',
        
        // Navigation & Actions
        'add_alert_rule' => 'Add Alert Rule',
        'edit_alert_rule' => 'Edit Alert Rule',
        'delete_alert_rule' => 'Delete Alert Rule',
        'view_alert_rule' => 'View Alert Rule Details',
        'activate_rule' => 'Activate Rule',
        'deactivate_rule' => 'Deactivate Rule',
        
        // Alert Status
        'alert_statuses' => [
            'ok' => 'OK',
            'low' => 'Low Stock',
            'out' => 'Out of Stock',
            'inactive' => 'Inactive',
        ],
        
        // Overview Cards
        'items_ok' => 'Items OK',
        'items_low' => 'Items Low',
        'items_out' => 'Items Out',
        'total_rules' => 'Total Rules',
        'active_rules' => 'Active Rules',
        'triggered_rules' => 'Triggered Rules',
        
        // Table Headers
        'item' => 'Item',
        'current_stock' => 'Current Stock',
        'minimum_threshold' => 'Minimum Threshold',
        'status' => 'Status',
        'location' => 'Location',
        'last_triggered' => 'Last Triggered',
        'actions' => 'Actions',
        
        // Filters
        'filters' => 'Filters',
        'clear_filters' => 'Clear Filters',
        'apply_filters' => 'Apply Filters',
        'filter_by_status' => 'Filter by Status',
        'filter_by_location' => 'Filter by Location',
        'all_statuses' => 'All Statuses',
        'all_locations' => 'All Locations',
        'search_placeholder' => 'Search by item name or code...',
        
        // Form Fields
        'select_item' => 'Select Item',
        'item_field' => 'Inventory Item',
        'threshold_field' => 'Minimum Threshold',
        'location_field' => 'Location (Optional)',
        'status_field' => 'Rule Status',
        'select_location' => 'Select Location',
        'enter_threshold' => 'Enter minimum stock level',
        'rule_active' => 'Rule Active',
        'rule_inactive' => 'Rule Inactive',
        
        // Alert Rule Details
        'rule_details' => 'Alert Rule Details',
        'item_information' => 'Item Information',
        'rule_configuration' => 'Rule Configuration',
        'alert_history' => 'Alert History',
        'current_status' => 'Current Status',
        
        // Item Information
        'item_name' => 'Item Name',
        'item_code' => 'Item Code',
        'item_category' => 'Category',
        'item_unit' => 'Unit',
        'current_stock_level' => 'Current Stock Level',
        
        // Rule Configuration
        'threshold_value' => 'Threshold Value',
        'rule_location' => 'Location',
        'rule_status' => 'Rule Status',
        'created_date' => 'Created Date',
        'last_updated' => 'Last Updated',
        
        // Active Alerts Section
        'active_alerts' => 'Active Alerts',
        'active_alerts_subtitle' => 'Items currently below their threshold levels',
        'no_active_alerts' => 'No Active Alerts',
        'no_active_alerts_message' => 'All monitored items are currently above their threshold levels.',
        'alert_triggered_at' => 'Triggered',
        'view_all_alerts' => 'View All Alerts',
        'create_po_for_item' => 'Create Purchase Order',
        
        // Alert History
        'no_alert_history' => 'No alert history available',
        'alert_triggered' => 'Alert Triggered',
        'alert_resolved' => 'Alert Resolved',
        'stock_replenished' => 'Stock Replenished',
        
        // Messages
        'rule_created' => 'Alert rule created successfully',
        'rule_updated' => 'Alert rule updated successfully',
        'rule_deleted' => 'Alert rule deleted successfully',
        'rule_activated' => 'Alert rule activated successfully',
        'rule_deactivated' => 'Alert rule deactivated successfully',
        'rule_already_exists' => 'Alert rule already exists for this item and location',
        'no_rules' => 'No alert rules found',
        'no_rules_message' => 'No alert rules yet. Add your first rule to start monitoring stock levels.',
        'loading_rules' => 'Loading alert rules...',
        'loading_alerts' => 'Loading active alerts...',
        'error_loading' => 'Error loading alert data',
        'retry' => 'Retry',
        
        // Validation
        'item_required' => 'Item is required',
        'threshold_required' => 'Minimum threshold is required',
        'threshold_positive' => 'Threshold must be a positive number',
        'threshold_numeric' => 'Threshold must be a valid number',
        'location_max_length' => 'Location cannot exceed 255 characters',
        
        // Actions
        'save_rule' => 'Save Alert Rule',
        'cancel' => 'Cancel',
        'close' => 'Close',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'activate' => 'Activate',
        'deactivate' => 'Deactivate',
        'confirm_delete' => 'Are you sure you want to delete this alert rule?',
        'confirm_deactivate' => 'Are you sure you want to deactivate this alert rule?',
        'delete_warning' => 'This action cannot be undone.',
        'deactivate_warning' => 'Deactivated rules will not trigger alerts.',
        
        // Drawer/Modal
        'rule_drawer_title' => 'Alert Rule Details',
        'add_rule_title' => 'Add New Alert Rule',
        'edit_rule_title' => 'Edit Alert Rule',
        'close_drawer' => 'Close Drawer',
        
        // Status Indicators
        'status_ok_description' => 'Stock level is above threshold',
        'status_low_description' => 'Stock level is at or below threshold',
        'status_out_description' => 'Item is out of stock',
        'status_inactive_description' => 'Alert rule is inactive',
        
        // Quick Actions
        'quick_actions' => 'Quick Actions',
        'view_item' => 'View Item',
        'create_po' => 'Create PO',
        'adjust_stock' => 'Adjust Stock',
        'edit_threshold' => 'Edit Threshold',
        
        // Bulk Actions
        'bulk_actions' => 'Bulk Actions',
        'activate_selected' => 'Activate Selected',
        'deactivate_selected' => 'Deactivate Selected',
        'delete_selected' => 'Delete Selected',
        'export_selected' => 'Export Selected',
        'rules_selected' => ':count rules selected',
        
        // Empty States
        'no_rules_found' => 'No Alert Rules Found',
        'no_rules_description' => 'No alert rules match your current filters. Try adjusting your search criteria or add your first alert rule.',
        'add_first_rule' => 'Add First Rule',
        'no_triggered_rules' => 'No Triggered Rules',
        'no_triggered_rules_description' => 'All monitored items are currently at healthy stock levels.',
        
        // Pagination
        'showing' => 'Showing',
        'of' => 'of',
        'rules' => 'rules',
        'previous' => 'Previous',
        'next' => 'Next',
        'per_page' => 'per page',
        
        // Time Formats
        'triggered_ago' => 'Triggered :time ago',
        'never_triggered' => 'Never triggered',
        'recently_triggered' => 'Recently triggered',
        
        // Tooltips
        'click_row_details' => 'Click row to view details',
        'item_help' => 'Select the inventory item to monitor',
        'threshold_help' => 'Alert will trigger when stock falls to or below this level',
        'location_help' => 'Optional: Monitor stock at a specific location',
        'status_help' => 'Enable or disable this alert rule',
        
        // Alert Levels
        'critical_alert' => 'Critical Alert',
        'warning_alert' => 'Warning Alert',
        'info_alert' => 'Info Alert',
        
        // Notifications
        'alert_notification_title' => 'Low Stock Alert',
        'alert_notification_message' => ':item is running low (Current: :current, Threshold: :threshold)',
        'out_of_stock_notification' => ':item is out of stock',
        
        // Export/Import
        'export_rules' => 'Export Rules',
        'import_rules' => 'Import Rules',
        'download_template' => 'Download Template',
        'upload_file' => 'Upload File',
        
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
        'select_rule' => 'Select alert rule',
        'rule_selected' => 'Alert rule selected',
        'sort_by_item' => 'Sort by item name',
        'sort_by_status' => 'Sort by status',
        'sort_by_threshold' => 'Sort by threshold',
        'rule_details' => 'View rule details',
        'edit_rule_details' => 'Edit rule details',
        'delete_rule_confirm' => 'Delete this alert rule',
        
        // Integration
        'create_po_from_alert' => 'Create Purchase Order from Alert',
        'restock_item' => 'Restock Item',
        'contact_supplier' => 'Contact Supplier',
        'view_supplier' => 'View Supplier',
        'stock_movement_history' => 'Stock Movement History',
        
        // Analytics
        'alert_frequency' => 'Alert Frequency',
        'most_triggered_items' => 'Most Triggered Items',
        'alert_response_time' => 'Average Response Time',
        'stock_out_duration' => 'Average Stock-out Duration',
    ],

    // Analytics Section
    'analytics' => [
        'title' => 'Inventory Analytics',
        'nav_title' => 'Analytics',
        'subtitle' => 'Insights and trends for inventory management',
        
        // Date Range Filters
        'date_ranges' => [
            'today' => 'Today',
            'this_week' => 'This Week',
            'this_month' => 'This Month',
            'custom' => 'Custom Range',
        ],
        
        // KPI Cards
        'kpi_cards' => [
            'total_inventory_value' => 'Total Inventory Value',
            'top_usage_items' => 'Top 5 Items by Usage',
            'top_cost_items' => 'Top 5 Items by Cost',
            'waste_percentage' => 'Waste Percentage',
        ],
        
        // Chart Titles
        'charts' => [
            'usage_trends' => 'Usage Trends',
            'usage_trends_subtitle' => 'Ingredient consumption over time',
            'category_breakdown' => 'Category Breakdown',
            'category_breakdown_subtitle' => 'Spending by category',
            'waste_vs_actual' => 'Waste vs Actual Use',
            'waste_vs_actual_subtitle' => 'Expected vs actual consumption',
            'supplier_performance' => 'Supplier Performance',
            'supplier_performance_subtitle' => 'On-time delivery rates',
        ],
        
        // Table Headers
        'high_usage_table' => [
            'title' => 'High-Usage Items',
            'subtitle' => 'Most consumed ingredients',
            'item' => 'Item',
            'qty_used' => 'Qty Used',
            'avg_cost' => 'Avg Cost',
            'supplier' => 'Supplier',
            'trend' => 'Trend',
        ],
        
        'wastage_table' => [
            'title' => 'Wastage Analysis',
            'subtitle' => 'Items with highest waste',
            'item' => 'Item',
            'qty_wasted' => 'Qty Wasted',
            'cost_wasted' => 'Cost Wasted',
            'waste_percentage' => 'Waste %',
            'reason' => 'Primary Reason',
        ],
        
        'supplier_table' => [
            'title' => 'Supplier Performance',
            'subtitle' => 'Delivery and pricing trends',
            'supplier' => 'Supplier',
            'orders' => 'Orders',
            'on_time_percentage' => 'On-Time %',
            'avg_price_trend' => 'Avg Price Trend',
            'rating' => 'Rating',
        ],
        
        // Filters
        'filters' => [
            'date_range' => 'Date Range',
            'category' => 'Category',
            'supplier' => 'Supplier',
            'location' => 'Location',
            'apply_filters' => 'Apply Filters',
            'clear_filters' => 'Clear Filters',
            'export_data' => 'Export Data',
        ],
        
        // Chart Labels
        'chart_labels' => [
            'usage' => 'Usage',
            'waste' => 'Waste',
            'expected' => 'Expected',
            'actual' => 'Actual',
            'on_time' => 'On Time',
            'late' => 'Late',
            'cost' => 'Cost',
            'quantity' => 'Quantity',
            'percentage' => 'Percentage',
        ],
        
        // Status Messages
        'messages' => [
            'loading_analytics' => 'Loading analytics data...',
            'no_data_available' => 'No analytics data available for the selected date range.',
            'export_success' => 'Analytics data exported successfully',
            'export_failed' => 'Failed to export analytics data',
        ],
        
        // Empty States
        'empty_states' => [
            'no_usage_data' => 'No usage data available for the selected period.',
            'no_waste_data' => 'No waste data recorded for the selected period.',
            'no_supplier_data' => 'No supplier performance data available.',
        ],
        
        // Tooltips
        'tooltips' => [
            'total_value' => 'Total monetary value of all inventory items',
            'usage_trend' => 'Consumption pattern over the selected period',
            'waste_percentage' => 'Percentage of inventory wasted vs consumed',
            'supplier_rating' => 'Overall supplier performance rating',
        ],
        
        // Time Formats
        'time_formats' => [
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'monthly' => 'Monthly',
            'quarterly' => 'Quarterly',
        ],
        
        // Currency and Units
        'currency' => [
            'symbol' => '$',
            'format' => '$%s',
        ],
        
        'units' => [
            'kg' => 'kg',
            'lbs' => 'lbs',
            'liters' => 'L',
            'pieces' => 'pcs',
        ],
        
        // Trend Indicators
        'trends' => [
            'increasing' => 'Increasing',
            'decreasing' => 'Decreasing',
            'stable' => 'Stable',
            'up' => '↗',
            'down' => '↘',
            'flat' => '→',
        ],
        
        // Categories
        'categories' => [
            'meat' => 'Meat',
            'vegetables' => 'Vegetables',
            'dairy' => 'Dairy',
            'grains' => 'Grains',
            'beverages' => 'Beverages',
            'spices' => 'Spices',
            'oils' => 'Oils',
            'other' => 'Other',
        ],
    ],

    // Stocktakes Section
    'stocktakes' => [
        'title' => 'Stocktakes',
        'nav_title' => 'Stocktakes',
        'subtitle' => 'Physical inventory counts and variance tracking',
        
        // Actions
        'new_stocktake' => 'New Stocktake',
        'edit_stocktake' => 'Edit Stocktake',
        'finalize_stocktake' => 'Finalize Stocktake',
        'cancel_stocktake' => 'Cancel Stocktake',
        'delete_stocktake' => 'Delete Stocktake',
        'save_stocktake' => 'Save Stocktake',
        'save_draft' => 'Save as Draft',
        
        // Table Headers
        'stocktake_id' => 'Stocktake ID',
        'date' => 'Date',
        'performed_by' => 'Performed By',
        'location' => 'Location',
        'status' => 'Status',
        'variance' => 'Variance',
        'total_value' => 'Total Value',
        'items_counted' => 'Items Counted',
        'duration' => 'Duration',
        
        // Stocktake Details
        'stocktake_details' => 'Stocktake Details',
        'stocktake_summary' => 'Stocktake Summary',
        'item_counts' => 'Item Counts',
        'expected_qty' => 'Expected Qty',
        'counted_qty' => 'Counted Qty',
        'variance_qty' => 'Variance',
        'variance_value' => 'Variance Value',
        'unit_cost' => 'Unit Cost',
        'total_variance' => 'Total Variance',
        'total_items' => 'Total Items',
        'items_with_variance' => 'Items with Variance',
        
        // Form Fields
        'stocktake_date' => 'Stocktake Date',
        'staff_member' => 'Staff Member',
        'stocktake_location' => 'Location',
        'notes' => 'Notes',
        'item' => 'Item',
        'expected_quantity' => 'Expected Quantity',
        'actual_quantity' => 'Actual Quantity',
        'add_item' => 'Add Item',
        'remove_item' => 'Remove Item',
        
        // Statuses
        'statuses' => [
            'draft' => 'Draft',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ],
        
        // Locations
        'locations' => [
            'main_kitchen' => 'Main Kitchen',
            'cold_storage' => 'Cold Storage',
            'freezer' => 'Freezer',
            'dry_storage' => 'Dry Storage',
            'bar' => 'Bar',
            'prep_area' => 'Prep Area',
            'all_locations' => 'All Locations',
        ],
        
        // Filters
        'filters' => [
            'filter_by_date' => 'Filter by Date',
            'filter_by_location' => 'Filter by Location',
            'filter_by_status' => 'Filter by Status',
            'date_range' => 'Date Range',
            'all_statuses' => 'All Statuses',
            'all_locations' => 'All Locations',
            'apply_filters' => 'Apply Filters',
            'clear_filters' => 'Clear Filters',
        ],
        
        // Variance Indicators
        'variance_types' => [
            'positive' => 'Surplus',
            'negative' => 'Shortage',
            'zero' => 'No Variance',
        ],
        
        // Messages
        'messages' => [
            'stocktake_saved' => 'Stocktake saved successfully',
            'stocktake_finalized' => 'Stocktake finalized successfully',
            'stocktake_deleted' => 'Stocktake deleted successfully',
            'stocktake_cancelled' => 'Stocktake cancelled successfully',
            'loading_stocktakes' => 'Loading stocktakes...',
            'loading_details' => 'Loading stocktake details...',
            'confirm_finalize' => 'Are you sure you want to finalize this stocktake? This action cannot be undone.',
            'confirm_delete' => 'Are you sure you want to delete this stocktake?',
            'confirm_cancel' => 'Are you sure you want to cancel this stocktake?',
        ],
        
        // Validation
        'validation' => [
            'date_required' => 'Stocktake date is required',
            'staff_required' => 'Staff member is required',
            'location_required' => 'Location is required',
            'items_required' => 'At least one item must be counted',
            'quantity_numeric' => 'Quantity must be a valid number',
            'quantity_positive' => 'Quantity must be positive or zero',
        ],
        
        // Empty States
        'empty_states' => [
            'no_stocktakes' => 'No stocktakes recorded yet.',
            'no_stocktakes_description' => 'Start your first stocktake to track inventory accuracy.',
            'no_items' => 'No items added to this stocktake yet.',
            'no_variance' => 'All items match expected quantities.',
        ],
        
        // Drawer Titles
        'drawer_titles' => [
            'new_stocktake' => 'New Stocktake',
            'edit_stocktake' => 'Edit Stocktake',
            'stocktake_details' => 'Stocktake Details',
        ],
        
        // Summary Stats
        'summary_stats' => [
            'total_stocktakes' => 'Total Stocktakes',
            'completed_stocktakes' => 'Completed',
            'draft_stocktakes' => 'Drafts',
            'average_variance' => 'Avg Variance',
            'last_stocktake' => 'Last Stocktake',
        ],
        
        // Quick Actions
        'quick_actions' => [
            'view_details' => 'View Details',
            'edit_counts' => 'Edit Counts',
            'finalize' => 'Finalize',
            'duplicate' => 'Duplicate',
            'export' => 'Export',
        ],
        
        // Bulk Actions
        'bulk_actions' => [
            'select_all' => 'Select All',
            'bulk_finalize' => 'Bulk Finalize',
            'bulk_delete' => 'Bulk Delete',
            'export_selected' => 'Export Selected',
        ],
        
        // Pagination
        'pagination' => [
            'showing' => 'Showing',
            'to' => 'to',
            'of' => 'of',
            'stocktakes' => 'stocktakes',
            'previous' => 'Previous',
            'next' => 'Next',
        ],
        
        // Time Formats
        'time_formats' => [
            'started_at' => 'Started at',
            'completed_at' => 'Completed at',
            'duration_minutes' => 'minutes',
            'duration_hours' => 'hours',
        ],
        
        // Tooltips
        'tooltips' => [
            'variance_explanation' => 'Difference between expected and counted quantities',
            'finalize_explanation' => 'Lock this stocktake and update system inventory',
            'draft_explanation' => 'Save progress without finalizing',
            'positive_variance' => 'More stock found than expected',
            'negative_variance' => 'Less stock found than expected',
        ],
        
        // Instructions
        'instructions' => [
            'count_instructions' => 'Count each item physically and enter the actual quantity found.',
            'variance_auto_calculated' => 'Variance will be calculated automatically.',
            'finalize_warning' => 'Once finalized, counts cannot be changed.',
            'draft_save_info' => 'Save as draft to continue counting later.',
        ],
        
        // Staff Members (Mock Data)
        'staff_members' => [
            'john_doe' => 'John Doe',
            'jane_smith' => 'Jane Smith',
            'mike_johnson' => 'Mike Johnson',
            'sarah_wilson' => 'Sarah Wilson',
            'current_user' => 'Current User',
        ],
        
        // Item Categories for Counting
        'item_categories' => [
            'vegetables' => 'Vegetables',
            'meat' => 'Meat & Poultry',
            'dairy' => 'Dairy Products',
            'grains' => 'Grains & Cereals',
            'beverages' => 'Beverages',
            'spices' => 'Spices & Seasonings',
            'oils' => 'Oils & Fats',
            'frozen' => 'Frozen Items',
            'canned' => 'Canned Goods',
            'other' => 'Other Items',
        ],
    ],
];
