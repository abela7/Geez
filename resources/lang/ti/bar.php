<?php

return [
    // Main Navigation
    'nav_title' => 'Bar Management',
    'title' => 'Bar Management',
    'subtitle' => 'Manage beverages, cocktails, pricing, and bar operations',

    // Beverage Inventory
    'inventory' => [
        'title' => 'Beverage Inventory',
        'subtitle' => 'Manage drinks, spirits, beer, wine, and bar supplies',
        'nav_title' => 'Inventory',

        // Summary Stats
        'total_beverages' => 'Total Beverages',
        'total_value' => 'Total Value',
        'beverage_details' => 'Beverage Details',
        'no_beverages_found' => 'No Beverages Found',
        'no_beverages_description' => 'Start by adding your first beverage to the inventory.',

        // Actions
        'add_beverage' => 'Add Beverage',
        'edit_beverage' => 'Edit Beverage',
        'delete_beverage' => 'Delete Beverage',
        'save_beverage' => 'Save Beverage',
        'import_beverages' => 'Import Beverages',
        'export_beverages' => 'Export Beverages',
        'stock_take' => 'Stock Take',
        'reorder' => 'Reorder',

        // Beverage Details
        'beverage_name' => 'Beverage Name',
        'beverage_type' => 'Beverage Type',
        'brand' => 'Brand',
        'abv' => 'ABV (%)',
        'volume' => 'Volume',
        'unit' => 'Unit',
        'cost_per_unit' => 'Cost per Unit',
        'selling_price' => 'Selling Price',
        'current_stock' => 'Current Stock',
        'minimum_stock' => 'Minimum Stock',
        'maximum_stock' => 'Maximum Stock',
        'supplier' => 'Supplier',
        'barcode' => 'Barcode',
        'storage_location' => 'Storage Location',
        'expiry_date' => 'Expiry Date',

        // Beverage Types
        'spirits' => 'Spirits',
        'beer' => 'Beer',
        'wine' => 'Wine',
        'cocktail_mixers' => 'Cocktail Mixers',
        'soft_drinks' => 'Soft Drinks',
        'juices' => 'Juices',
        'coffee' => 'Coffee',
        'tea' => 'Tea',
        'water' => 'Water',
        'energy_drinks' => 'Energy Drinks',

        // Units
        'ml' => 'Milliliters (ml)',
        'liters' => 'Liters (L)',
        'bottles' => 'Bottles',
        'cases' => 'Cases',
        'shots' => 'Shots (25ml)',
        'glasses' => 'Glasses',
        'kegs' => 'Kegs',

        // Storage Locations
        'main_bar' => 'Main Bar',
        'back_bar' => 'Back Bar',
        'wine_cellar' => 'Wine Cellar',
        'beer_cooler' => 'Beer Cooler',
        'spirit_cabinet' => 'Spirit Cabinet',
        'refrigerator' => 'Refrigerator',
        'storage_room' => 'Storage Room',

        // Messages
        'beverage_added' => 'Beverage added successfully',
        'beverage_updated' => 'Beverage updated successfully',
        'beverage_deleted' => 'Beverage deleted successfully',
        'stock_updated' => 'Stock levels updated successfully',
        'low_stock_alert' => 'Low stock alert for',
        'out_of_stock' => 'Out of stock',
        'reorder_required' => 'Reorder required',
    ],

    // Cocktail Recipes
    'recipes' => [
        'title' => 'Cocktail Recipes',
        'subtitle' => 'Create and manage cocktail recipes and drink preparations',
        'nav_title' => 'Recipes',

        // Summary Stats
        'total_recipes' => 'Total Recipes',
        'signature_recipes' => 'Signature Recipes',
        'popular_recipes' => 'Popular Recipes',
        'avg_cost_per_drink' => 'Avg Cost per Drink',
        'recipe_details' => 'Recipe Details',
        'no_recipes_found' => 'No Recipes Found',
        'no_recipes_description' => 'Start by creating your first cocktail recipe.',

        // Search & Filters
        'search_recipes' => 'Search recipes...',
        'all_types' => 'All Types',
        'all_difficulties' => 'All Difficulties',
        'all_glasses' => 'All Glasses',
        'all_status' => 'All Status',
        'active' => 'Active',
        'inactive' => 'Inactive',
        'popularity' => 'Popularity',
        'clear_filters' => 'Clear Filters',

        // Sorting
        'sort_by_name' => 'Sort by Name',
        'sort_by_cost' => 'Sort by Cost',
        'sort_by_difficulty' => 'Sort by Difficulty',
        'sort_by_popularity' => 'Sort by Popularity',
        'sort_by_created' => 'Sort by Created',

        // Additional Labels
        'actions' => 'Actions',
        'add_first_recipe' => 'Add First Recipe',
        'languages' => 'Languages',
        'recipe_ingredients' => 'Recipe Ingredients',
        'select_beverage' => 'Select Beverage',
        'recipe_name_placeholder' => 'Enter recipe name...',
        'description_placeholder' => 'Describe this cocktail recipe...',
        'instructions_placeholder' => 'Enter step-by-step instructions...',
        'garnish_placeholder' => 'e.g., Orange peel, Cherry, Lime wedge',
        'cost_hint' => 'Calculated from ingredient costs',
        'markup' => 'Markup',

        // Actions
        'add_recipe' => 'Add Recipe',
        'edit_recipe' => 'Edit Recipe',
        'delete_recipe' => 'Delete Recipe',
        'save_recipe' => 'Save Recipe',
        'duplicate_recipe' => 'Duplicate Recipe',
        'export_recipes' => 'Export Recipes',
        'print_recipe' => 'Print Recipe',

        // Recipe Details
        'recipe_name' => 'Recipe Name',
        'recipe_type' => 'Recipe Type',
        'difficulty' => 'Difficulty',
        'preparation_time' => 'Preparation Time',
        'serving_size' => 'Serving Size',
        'glass_type' => 'Glass Type',
        'garnish' => 'Garnish',
        'instructions' => 'Instructions',
        'notes' => 'Notes',
        'cost_per_drink' => 'Cost per Drink',
        'selling_price' => 'Selling Price',
        'profit_margin' => 'Profit Margin',

        // Recipe Types
        'classic_cocktail' => 'Classic Cocktail',
        'signature_cocktail' => 'Signature Cocktail',
        'mocktail' => 'Mocktail',
        'shot' => 'Shot',
        'mixed_drink' => 'Mixed Drink',
        'frozen_drink' => 'Frozen Drink',
        'hot_drink' => 'Hot Drink',

        // Difficulty Levels
        'easy' => 'Easy',
        'medium' => 'Medium',
        'hard' => 'Hard',
        'expert' => 'Expert',

        // Glass Types
        'highball' => 'Highball',
        'lowball' => 'Lowball',
        'martini' => 'Martini',
        'wine_glass' => 'Wine Glass',
        'champagne_flute' => 'Champagne Flute',
        'beer_mug' => 'Beer Mug',
        'shot_glass' => 'Shot Glass',
        'hurricane' => 'Hurricane',

        // Ingredients
        'ingredients' => 'Ingredients',
        'add_ingredient' => 'Add Ingredient',
        'ingredient_name' => 'Ingredient',
        'quantity' => 'Quantity',
        'unit' => 'Unit',
        'optional' => 'Optional',

        // Messages
        'recipe_added' => 'Cocktail recipe added successfully',
        'recipe_updated' => 'Recipe updated successfully',
        'recipe_deleted' => 'Recipe deleted successfully',
        'ingredient_added' => 'Ingredient added to recipe',
        'ingredient_removed' => 'Ingredient removed from recipe',
    ],

    // Bar Pricing
    'pricing' => [
        'title' => 'Bar Pricing',
        'subtitle' => 'Manage drink prices, happy hours, and bar promotions',
        'nav_title' => 'Pricing',

        // Actions
        'update_price' => 'Update Price',
        'bulk_update' => 'Bulk Update',
        'create_promotion' => 'Create Promotion',
        'happy_hour_setup' => 'Happy Hour Setup',
        'export_prices' => 'Export Prices',

        // Statistics
        'total_drinks' => 'Total Drinks',
        'avg_price' => 'Average Price',
        'avg_margin' => 'Average Margin',
        'active' => 'Active',
        'inactive' => 'Inactive',

        // Search & Filters
        'search_drinks' => 'Search drinks...',
        'all_categories' => 'All Categories',
        'all_prices' => 'All Prices',
        'under_10' => 'Under $10',
        'over_50' => 'Over $50',
        'clear_filters' => 'Clear Filters',
        'sort_by_name' => 'Sort by Name',
        'sort_by_price' => 'Sort by Price',
        'sort_by_margin' => 'Sort by Margin',
        'sort_by_category' => 'Sort by Category',

        // Empty State
        'no_prices_found' => 'No Pricing Found',
        'no_prices_description' => 'Set up pricing for your drinks and beverages.',
        'setup_pricing' => 'Setup Pricing',

        // Table Headers
        'drink_name' => 'Drink Name',
        'category' => 'Category',

        // Bulk Update Form
        'update_method' => 'Update Method',
        'update_type' => 'Update Type',
        'percentage_increase' => 'Percentage Increase/Decrease',
        'fixed_amount' => 'Fixed Amount Change',
        'target_margin' => 'Target Profit Margin',
        'update_values' => 'Update Values',
        'percentage_value' => 'Percentage Value',
        'percentage_hint' => 'Positive values increase prices, negative values decrease',
        'fixed_value' => 'Fixed Amount',
        'fixed_hint' => 'Amount to add or subtract from current prices',
        'margin_value' => 'Target Margin',
        'margin_hint' => 'Desired profit margin percentage',
        'apply_to_categories' => 'Apply to Categories',
        'apply_changes' => 'Apply Changes',

        // Happy Hour Form
        'enable_happy_hour' => 'Enable Happy Hour',
        'save_happy_hour' => 'Save Happy Hour Settings',

        // Pricing Details
        'base_price' => 'Base Price',
        'happy_hour_price' => 'Happy Hour Price',
        'promotion_price' => 'Promotion Price',
        'cost_price' => 'Cost Price',
        'markup_percentage' => 'Markup %',
        'profit_margin' => 'Profit Margin',

        // Happy Hour
        'happy_hour' => 'Happy Hour',
        'happy_hour_active' => 'Happy Hour Active',
        'start_time' => 'Start Time',
        'end_time' => 'End Time',
        'discount_percentage' => 'Discount %',
        'applicable_days' => 'Applicable Days',

        // Promotions
        'promotions' => 'Promotions',
        'promotion_name' => 'Promotion Name',
        'promotion_type' => 'Promotion Type',
        'buy_x_get_y' => 'Buy X Get Y',
        'percentage_discount' => 'Percentage Discount',
        'fixed_discount' => 'Fixed Discount',
        'minimum_order' => 'Minimum Order',
        'valid_from' => 'Valid From',
        'valid_until' => 'Valid Until',

        // Messages
        'price_updated' => 'Price updated successfully',
        'promotion_created' => 'Promotion created successfully',
        'happy_hour_updated' => 'Happy hour settings updated',
        'bulk_update_completed' => 'Bulk price update completed',
    ],

    // Bar Analytics
    'analytics' => [
        'title' => 'Bar Analytics',
        'subtitle' => 'Analyze beverage sales, popular drinks, and bar performance',
        'nav_title' => 'Analytics',

        // Metrics
        'total_beverage_sales' => 'Total Beverage Sales',
        'drinks_sold' => 'Drinks Sold',
        'average_order_value' => 'Average Order Value',
        'top_selling_drink' => 'Top Selling Drink',
        'most_profitable' => 'Most Profitable',
        'cocktail_vs_beer' => 'Cocktail vs Beer',
        'happy_hour_impact' => 'Happy Hour Impact',
        'peak_hours' => 'Peak Hours',

        // Additional Metrics
        'avg_price' => 'Average Price',
        'avg_margin' => 'Average Margin',
        'consistent' => 'Consistent',

        // Time Periods
        'quarter' => 'Quarter',
        'custom_range' => 'Custom Range',
        'daily' => 'Daily',
        'weekly' => 'Weekly',
        'monthly' => 'Monthly',

        // Date Range
        'date_range' => 'Date Range',
        'start_date' => 'Start Date',
        'end_date' => 'End Date',
        'select_date_range' => 'Select Date Range',
        'quick_presets' => 'Quick Presets',
        'last_7_days' => 'Last 7 Days',
        'last_30_days' => 'Last 30 Days',
        'last_quarter' => 'Last Quarter',
        'last_year' => 'Last Year',
        'apply_date_range' => 'Apply Date Range',
        'export_report' => 'Export Report',

        // Insights
        'performance_insights' => 'Performance Insights',
        'top_selling_description' => 'Best performing drink this period',
        'profitable_description' => 'Highest profit margin drink',
        'happy_hour_description' => 'Sales increase during happy hour',
        'turnover_description' => 'Monthly inventory turnover rate',

        // Quick Reports
        'quick_reports' => 'Quick Reports',
        'daily_report_description' => 'Today\'s bar performance summary',
        'weekly_report_description' => 'Weekly sales and trends analysis',
        'monthly_report_description' => 'Comprehensive monthly bar report',
        'wastage_report_description' => 'Inventory waste and loss analysis',

        // Charts
        'sales_by_category' => 'Sales by Category',
        'popular_drinks' => 'Popular Drinks',
        'hourly_sales' => 'Hourly Sales',
        'profit_analysis' => 'Profit Analysis',
        'seasonal_trends' => 'Seasonal Trends',

        // Reports
        'daily_bar_report' => 'Daily Bar Report',
        'weekly_summary' => 'Weekly Summary',
        'monthly_analysis' => 'Monthly Analysis',
        'inventory_turnover' => 'Inventory Turnover',
        'wastage_report' => 'Wastage Report',
    ],

    // Bar Suppliers
    'suppliers' => [
        'title' => 'Bar Suppliers',
        'subtitle' => 'Manage beverage suppliers and distributors',
        'nav_title' => 'Suppliers',

        // Actions
        'add_supplier' => 'Add Supplier',
        'edit_supplier' => 'Edit Supplier',
        'delete_supplier' => 'Delete Supplier',
        'contact_supplier' => 'Contact Supplier',
        'place_order' => 'Place Order',
        'view_catalog' => 'View Catalog',

        // Supplier Details
        'supplier_name' => 'Supplier Name',
        'contact_person' => 'Contact Person',
        'phone_number' => 'Phone Number',
        'email_address' => 'Email Address',
        'address' => 'Address',
        'website' => 'Website',
        'payment_terms' => 'Payment Terms',
        'delivery_days' => 'Delivery Days',
        'minimum_order' => 'Minimum Order',
        'specialty' => 'Specialty',

        // Supplier Types
        'wine_distributor' => 'Wine Distributor',
        'beer_distributor' => 'Beer Distributor',
        'spirits_distributor' => 'Spirits Distributor',
        'soft_drinks_supplier' => 'Soft Drinks Supplier',
        'coffee_supplier' => 'Coffee Supplier',
        'general_beverage' => 'General Beverage',

        // Performance Metrics
        'delivery_rating' => 'Delivery Rating',
        'quality_rating' => 'Quality Rating',
        'price_rating' => 'Price Rating',
        'last_order_date' => 'Last Order',
        'total_orders' => 'Total Orders',
        'average_delivery_time' => 'Avg Delivery Time',

        // Messages
        'supplier_added' => 'Supplier added successfully',
        'supplier_updated' => 'Supplier updated successfully',
        'supplier_deleted' => 'Supplier deleted successfully',
        'order_placed' => 'Order placed successfully',
    ],

    // Bar Settings
    'settings' => [
        'title' => 'Bar Settings',
        'subtitle' => 'Configure bar operations, units, and preferences',
        'nav_title' => 'Settings',

        // General Settings
        'bar_name' => 'Bar Name',
        'operating_hours' => 'Operating Hours',
        'happy_hour_enabled' => 'Enable Happy Hour',
        'age_verification' => 'Age Verification Required',
        'last_call_time' => 'Last Call Time',

        // Measurement Settings
        'standard_shot_size' => 'Standard Shot Size (ml)',
        'double_shot_size' => 'Double Shot Size (ml)',
        'wine_pour_size' => 'Wine Pour Size (ml)',
        'beer_pour_size' => 'Beer Pour Size (ml)',
        'default_markup' => 'Default Markup %',

        // Drink Portions
        'drink_portions' => 'Drink Portions',
        'drink_portions_description' => 'Configure standard pour sizes and container conversion rates for accurate inventory tracking.',
        'standard_pour_sizes' => 'Standard Pour Sizes',
        'container_conversions' => 'Container Conversions',
        'beer_conversions' => 'Beer Conversions',
        'spirit_conversions' => 'Spirit Conversions',
        'wine_conversions' => 'Wine Conversions',

        // Conversion Labels
        'pint_to_glasses' => 'Pint to Glasses',
        'gallon_to_pints' => 'Gallon to Pints',
        'bottle_to_singles' => 'Bottle to Single Shots',
        'bottle_to_doubles' => 'Bottle to Double Shots',

        // Units
        'pint' => 'Pint',
        'gallon' => 'Gallon',
        'glasses' => 'Glasses',
        'single_shots' => 'Single Shots',
        'double_shots' => 'Double Shots',

        // Inventory Alerts
        'inventory_alerts' => 'Inventory Alerts',
        'low_stock_thresholds' => 'Low Stock Thresholds',
        'beer_threshold' => 'Beer Threshold',
        'spirits_threshold' => 'Spirits Threshold',
        'save_settings' => 'Save Settings',
        'reset_defaults' => 'Reset to Defaults',

        // Inventory Settings
        'low_stock_threshold' => 'Low Stock Threshold',
        'reorder_point' => 'Reorder Point',
        'stock_rotation' => 'Stock Rotation (FIFO)',
        'waste_tracking' => 'Waste Tracking',

        // POS Integration
        'pos_integration' => 'POS Integration',
        'auto_inventory_update' => 'Auto Inventory Update',
        'real_time_sync' => 'Real-time Sync',

        // Messages
        'settings_saved' => 'Bar settings saved successfully',
        'settings_reset' => 'Settings reset to defaults',
    ],

    // Common Bar Terms
    'beverage' => 'Beverage',
    'beverages' => 'Beverages',
    'cocktail' => 'Cocktail',
    'cocktails' => 'Cocktails',
    'recipe' => 'Recipe',
    'ingredient' => 'Ingredient',
    'ingredients' => 'Ingredients',
    'stock_level' => 'Stock Level',
    'pour_cost' => 'Pour Cost',
    'bottle_size' => 'Bottle Size',
    'case_size' => 'Case Size',
    'alcohol_content' => 'Alcohol Content',
    'non_alcoholic' => 'Non-Alcoholic',
    'alcoholic' => 'Alcoholic',
    'draft' => 'Draft',
    'bottled' => 'Bottled',
    'canned' => 'Canned',
    'vintage' => 'Vintage',
    'region' => 'Region',
    'distillery' => 'Distillery',
    'brewery' => 'Brewery',
    'winery' => 'Winery',

    // Search & Filters
    'search_beverages' => 'Search beverages...',
    'filter_by_type' => 'Filter by Type',
    'filter_by_brand' => 'Filter by Brand',
    'filter_by_stock' => 'Filter by Stock',
    'all_types' => 'All Types',
    'all_brands' => 'All Brands',
    'in_stock' => 'In Stock',
    'low_stock' => 'Low Stock',
    'out_of_stock' => 'Out of Stock',

    // Status
    'available' => 'Available',
    'unavailable' => 'Unavailable',
    'discontinued' => 'Discontinued',
    'seasonal' => 'Seasonal',

    // Common Actions
    'add' => 'Add',
    'edit' => 'Edit',
    'delete' => 'Delete',
    'save' => 'Save',
    'cancel' => 'Cancel',
    'close' => 'Close',
    'search' => 'Search',
    'filter' => 'Filter',
    'export' => 'Export',
    'import' => 'Import',
    'clear_filters' => 'Clear Filters',
    'view_details' => 'View Details',

    // Validation
    'beverage_name_required' => 'Beverage name is required',
    'beverage_type_required' => 'Beverage type is required',
    'cost_required' => 'Cost is required',
    'price_required' => 'Selling price is required',
    'stock_required' => 'Stock level is required',
    'supplier_required' => 'Supplier is required',
    'invalid_abv' => 'ABV must be between 0 and 100',
    'invalid_volume' => 'Volume must be greater than 0',
    'price_below_cost' => 'Selling price should not be below cost',
];
