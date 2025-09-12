<?php

return [
    // Navigation
    'nav_title' => 'Injera Management',
    
    // Subsection Titles
    'overview' => [
        'title' => 'Overview',
    ],
    'flour_management' => [
        'title' => 'Flour Management',
        'subtitle' => 'Manage flour inventory, track stock levels, and monitor costs for injera production',
        
        // Summary Cards
        'total_flour_types' => 'Total Flour Types',
        'total_stock' => 'Total Stock',
        'low_stock_items' => 'Low Stock Items',
        'total_value' => 'Total Value',
        'avg_price_per_kg' => 'Average Price/KG',
        'suppliers_count' => 'Active Suppliers',
        
        // Actions
        'add_flour' => 'Add Flour',
        'export_data' => 'Export Data',
        'refresh' => 'Refresh',
        'clear_filters' => 'Clear Filters',
        'save_flour' => 'Save Flour',
        'cancel' => 'Cancel',
        
        // Table Headers
        'flour_inventory' => 'Flour Inventory',
        'flour_name' => 'Flour Name',
        'type' => 'Type',
        'current_stock' => 'Current Stock',
        'package_size' => 'Package Size',
        'price_per_kg' => 'Price per KG',
        'price_per_package' => 'Price per Package',
        'supplier' => 'Supplier',
        'status' => 'Status',
        'actions' => 'Actions',
        
        // Filters
        'search_placeholder' => 'Search flour by name or supplier...',
        'all_types' => 'All Types',
        'all_statuses' => 'All Statuses',
        'select_type' => 'Select Type',
        
        // Statuses
        'in_stock' => 'In Stock',
        'low_stock' => 'Low Stock',
        'out_of_stock' => 'Out of Stock',
        
        // Form Fields
        'notes' => 'Notes',
        'adjustment_type' => 'Adjustment Type',
        'quantity' => 'Quantity',
        'purchase' => 'Purchase',
        'usage' => 'Usage',
        'adjustment' => 'Adjustment',
        
        // Actions
        'update_stock' => 'Update Stock',
        'edit' => 'Edit',
        'delete' => 'Delete',
        
        // Messages
        'flour_added_success' => 'Flour added successfully',
        'stock_updated_success' => 'Stock updated successfully',
        'flour_deleted_success' => 'Flour deleted successfully',
    ],
    'bucket_configurations' => [
        'title' => 'Bucket Configurations',
    ],
    'production_batches' => [
        'title' => 'Production Batches',
    ],
    'stock_levels' => [
        'title' => 'Stock Levels',
    ],
    'cost_analysis' => [
        'title' => 'Cost Analysis',
    ],
    'orders' => [
        'title' => 'Orders & Allocation',
    ],
    
    // Main Management
    'management' => [
        'title' => 'Injera Management',
        'subtitle' => 'Manage injera production cycle, sales analysis, and intelligent recommendations',
        'nav_title' => 'Injera Management',
        
        // Statistics
        'daily_production' => 'Daily Production',
        'injera_remaining' => 'Injera Remaining',
        'injera_per_kg' => 'Injera per KG Flour',
        'flour_efficiency' => 'Flour Efficiency',
        'selling_recommendation' => 'Selling Recommendation',
        'vs_yesterday' => 'vs Yesterday',
        'estimated_hours' => 'Est. Hours Remaining',
        'based_on_analysis' => 'Based on Analysis',
        'good' => 'GOOD',
        'caution' => 'CAUTION',
        'avoid' => 'AVOID',
        
        // Actions
        'sales_analytics' => 'Sales Analytics',
        'start_new_batch' => 'Start New Batch',
        'get_recommendation' => 'Get Recommendation',
        'start_new_production' => 'Start New Production',
        'start_production' => 'Start Production',
        
        // Tab Navigation
        'production_cycle' => 'Production Cycle',
        'inventory_tracking' => 'Inventory Tracking',
        'sales_analysis' => 'Sales Analysis',
        'recommendations' => 'Recommendations',
        
        // Production Steps
        'current_production_status' => 'Current Production Status',
        'production_steps' => 'Production Steps',
        
        // Step 1: Ingredients
        'buy_ingredients' => 'Buy Ingredients',
        'buy_ingredients_description' => 'Purchase teff flour, wheat flour, and water for injera production',
        'teff_flour' => 'Teff Flour',
        'wheat_flour' => 'Wheat Flour',
        'water' => 'Water',
        'record_purchase' => 'Record Purchase',
        
        // Step 2: Mixing
        'mix_flour' => 'Mix Flour',
        'mix_flour_description' => 'Mix teff and wheat flour with water to create dough',
        'mixing_time' => 'Mixing Time',
        'fermentation_time' => 'Fermentation Time',
        'start_mixing' => 'Start Mixing',
        
        // Step 3: Hot Water
        'add_hot_water' => 'Add Hot Water',
        'add_hot_water_description' => 'Add hot water to the fermented dough before baking',
        'water_temperature' => 'Water Temperature',
        'resting_time' => 'Resting Time',
        
        // Step 4: Baking
        'bake_injera' => 'Bake Injera',
        'bake_injera_description' => 'Bake the prepared dough into fresh injera',
        'baking_time' => 'Baking Time per Injera',
        'expected_yield' => 'Expected Yield',
        'start_baking' => 'Start Baking',
        
        // Inventory
        'flour_inventory' => 'Flour Inventory',
        'update_inventory' => 'Update Inventory',
        'cost_per_kg' => 'Cost per KG',
        'last_purchase' => 'Last Purchase',
        'reorder_level' => 'Reorder Level',
        'in_stock' => 'In Stock',
        'low_stock' => 'Low Stock',
        'ago' => 'ago',
        
        // Current Batches
        'current_batches' => 'Current Batches',
        'finished_injera_stock' => 'Finished Injera Stock',
        'fresh_today' => 'Fresh Today',
        'yesterday' => 'Yesterday',
        'count_stock' => 'Count Stock',
        'adjust_stock' => 'Adjust Stock',
        'injeras' => 'Injeras',
        
        // Sales Performance
        'sales_performance' => 'Sales Performance',
        'used_for_food_service' => 'Used for Food Service',
        'sold_directly' => 'Sold Directly to Customers',
        'wasted_injera' => 'Wasted Injera',
        'efficiency_rate' => 'Efficiency Rate',
        'target' => 'Target',
        'daily_sales_pattern' => 'Daily Sales Pattern',
        'this_week' => 'This Week',
        'this_month' => 'This Month',
        'quarter' => 'Quarter',
        
        // Recommendations
        'intelligent_recommendations' => 'Intelligent Recommendations',
        'system_analysis' => 'System Analysis',
        'production_recommendation' => 'Production Recommendation',
        'sales_recommendation' => 'Sales Recommendation',
        'waste_reduction' => 'Waste Reduction',
        'production_recommendation_text' => 'Based on historical demand and current inventory, we recommend starting a new production batch.',
        'sales_recommendation_text' => 'Analysis indicates favorable conditions for injera sales today.',
        'waste_reduction_text' => 'Implement these strategies to reduce injera waste and improve profitability.',
        
        // Recommendation Details
        'recommended_batch_size' => 'Recommended Batch Size',
        'flour' => 'Flour',
        'demand_trend' => 'Demand Trend',
        'vs_last_week' => 'vs Last Week',
        'day_comparison' => 'Day Comparison',
        'similar_to_last_friday' => 'Similar to Last Friday',
        'weather_factor' => 'Weather Factor',
        'favorable' => 'Favorable',
        'current_waste_rate' => 'Current Waste Rate',
        'target_waste_rate' => 'Target Waste Rate',
        'potential_savings' => 'Potential Savings',
        
        // Recommendation Actions
        'accept_recommendation' => 'Accept Recommendation',
        'enable_injera_sales' => 'Enable Injera Sales',
        'implement_strategy' => 'Implement Strategy',
        
        // Historical Comparison
        'historical_comparison' => 'Historical Comparison',
        
        // Production Wizard
        'ingredients' => 'Ingredients',
        'mixing' => 'Mixing',
        'fermentation' => 'Fermentation',
        'baking' => 'Baking',
        'select_ingredients' => 'Select Ingredients',
        'mixing_schedule' => 'Mixing Schedule',
        'hot_water_addition' => 'Hot Water Addition',
        'baking_schedule' => 'Baking Schedule',
        
        // Wizard Form Fields
        'mixing_date' => 'Mixing Date',
        'mixing_duration' => 'Mixing Duration',
        'fermentation_period' => 'Fermentation Period',
        'water_addition_date' => 'Water Addition Date',
        'hot_water_amount' => 'Hot Water Amount',
        'baking_start_date' => 'Baking Start Date',
        'baker_assigned' => 'Baker Assigned',
        'select_baker' => 'Select Baker',
        'target_quantity' => 'Target Quantity',
        
        // Production Summary
        'production_summary' => 'Production Summary',
        'total_flour' => 'Total Flour',
        'total_cost' => 'Total Cost',
        'cost_per_injera' => 'Cost per Injera',
        'completion_date' => 'Completion Date',
    ],
];
