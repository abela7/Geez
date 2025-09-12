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
        'subtitle' => 'Create and manage custom injera production recipes with precise flour mixes, water ratios, and cost calculations',
        
        // Summary Cards
        'total_configurations' => 'Total Configurations',
        'active_configurations' => 'Active Configurations',
        'avg_cost_per_injera' => 'Avg Cost per Injera',
        'total_capacity' => 'Total Capacity',
        'avg_yield_per_kg' => 'Avg Yield per KG',
        'most_used_flour' => 'Most Used Flour',
        
        // Actions
        'create_bucket' => 'Create Bucket',
        'export_configurations' => 'Export Configurations',
        'refresh' => 'Refresh',
        'save_bucket' => 'Save Bucket',
        'cancel' => 'Cancel',
        'use_recipe' => 'Use Recipe',
        'duplicate' => 'Duplicate',
        'edit' => 'Edit',
        'delete' => 'Delete',
        
        // Section Titles
        'bucket_recipes' => 'Bucket Recipes',
        'basic_info' => 'Basic Information',
        'flour_recipe' => 'Flour Recipe',
        'water_requirements' => 'Water Requirements',
        'production_details' => 'Production Details',
        'cost_breakdown' => 'Cost Breakdown',
        
        // Form Fields
        'bucket_name' => 'Bucket Name',
        'capacity' => 'Capacity',
        'liters' => 'Liters',
        'flour_type' => 'Flour Type',
        'quantity' => 'Quantity',
        'cost' => 'Cost',
        'select_flour' => 'Select Flour Type',
        'add_flour' => 'Add Flour',
        'available' => 'Available',
        'total_flour' => 'Total Flour',
        'total_cost' => 'Total Cost',
        'cold_water' => 'Cold Water',
        'hot_water' => 'Hot Water',
        'total_water' => 'Total Water',
        'expected_yield' => 'Expected Yield',
        'injeras' => 'Injeras',
        'cost_per_injera' => 'Cost per Injera',
        'electricity_cost' => 'Electricity Cost',
        'electricity' => 'Electricity',
        'labor_cost' => 'Labor Cost',
        'labor' => 'Labor',
        'flour_cost' => 'Flour Cost',
        'notes' => 'Notes',
        
        // Status
        'active' => 'Active',
        'inactive' => 'Inactive',
        
        // Messages
        'bucket_created_success' => 'Bucket configuration created successfully',
        'bucket_updated_success' => 'Bucket configuration updated successfully',
        'bucket_deleted_success' => 'Bucket configuration deleted successfully',
        'bucket_duplicated_success' => 'Bucket configuration duplicated successfully',
    ],
    'production_batches' => [
        'title' => 'Production Batches',
        'subtitle' => 'Track the complete 5-stage injera production lifecycle from flour purchase to finished injera',
        
        // Summary Cards
        'active_batches' => 'Active Batches',
        'completed_this_week' => 'Completed This Week',
        'total_injera_produced' => 'Total Injera Produced',
        'avg_batch_time' => 'Avg Batch Time',
        'success_rate' => 'Success Rate',
        'total_production_cost' => 'Total Production Cost',
        
        // Filter Tabs
        'all_batches' => 'All Batches',
        'active' => 'Active',
        'completed' => 'Completed',
        'planning' => 'Planning',
        'cancelled' => 'Cancelled',
        
        // Actions
        'start_new_batch' => 'Start New Batch',
        'export_batches' => 'Export Batches',
        'next_stage' => 'Next Stage',
        'complete' => 'Complete',
        'update_stage' => 'Update Stage',
        'view_details' => 'View Details',
        'cancel_batch' => 'Cancel Batch',
        'start_batch' => 'Start Batch',
        'cancel' => 'Cancel',
        
        // Batch Info
        'batch_name' => 'Batch Name',
        'bucket_configuration' => 'Bucket Configuration',
        'select_bucket' => 'Select Bucket Configuration',
        'planned_start_date' => 'Planned Start Date',
        'baker_assigned' => 'Baker Assigned',
        'baker' => 'Baker',
        'not_assigned' => 'Not Assigned',
        'expected_yield' => 'Expected Yield',
        'actual_yield' => 'Actual Yield',
        'injeras' => 'Injeras',
        'cost_per_injera' => 'Cost per Injera',
        'completion_date' => 'Completion Date',
        'not_scheduled' => 'Not Scheduled',
        'recipe_preview' => 'Recipe Preview',
        'notes' => 'Notes',
        
        // Progress
        'progress' => 'Progress',
        'current_stage' => 'Current Stage',
        'day' => 'Day',
        
        // 5 Stages
        'stage_buy_flour' => 'Buy Flour',
        'stage_mixing' => 'Mixing',
        'stage_fermentation' => 'Fermentation',
        'stage_hot_water' => 'Add Hot Water',
        'stage_baking' => 'Baking',
        'stage_completed' => 'Completed',
        
        // Stage Status
        'stage_status' => 'Stage Status',
        'in_progress' => 'In Progress',
        'pending' => 'Pending',
        'stage_notes' => 'Stage Notes',
        
        // Priority
        'high_priority' => 'High Priority',
        'medium_priority' => 'Medium Priority',
        'normal_priority' => 'Normal',
        'low_priority' => 'Low',
        
        // Completion
        'complete_batch' => 'Complete Batch',
        'actual_yield_hint' => 'Enter the actual number of injera produced',
        'quality_notes' => 'Quality Notes',
        'quality_notes_placeholder' => 'Note the quality, texture, taste, and any observations about this batch...',
        
        // Messages
        'batch_created_success' => 'Production batch started successfully',
        'stage_updated_success' => 'Stage updated successfully',
        'batch_completed_success' => 'Production batch completed successfully',
        'batch_cancelled_success' => 'Production batch cancelled successfully',
    ],
    'injera_stock_levels' => [
        'title' => 'Injera Stock Levels',
        'subtitle' => 'Manage finished injera inventory and stock levels',
        'export_stock' => 'Export Stock',
        'add_stock' => 'Add Stock',
        'total_injera' => 'Total Injera',
        'available_injera' => 'Available Injera',
        'expiring_today' => 'Expiring Today',
        'total_value' => 'Total Value',
        'inventory_value' => 'Inventory Value',
        'pieces' => 'pieces',
        'piece' => 'piece',
        'filter_by_quality' => 'Filter by Quality',
        'all_qualities' => 'All Qualities',
        'grade_a' => 'Grade A',
        'grade_b' => 'Grade B',
        'grade_c' => 'Grade C',
        'filter_by_status' => 'Filter by Status',
        'all_statuses' => 'All Statuses',
        'fresh' => 'Fresh',
        'expiring_soon' => 'Expiring Soon',
        'expired' => 'Expired',
        'search_placeholder' => 'Search by batch number, location...',
        'batch_number' => 'Batch Number',
        'quality_grade' => 'Quality Grade',
        'current_stock' => 'Current Stock',
        'reserved_stock' => 'Reserved Stock',
        'available_stock' => 'Available Stock',
        'storage_location' => 'Storage Location',
        'expiry_date' => 'Expiry Date',
        'status' => 'Status',
        'actions' => 'Actions',
        'days_left' => 'days left',
        'update_stock' => 'Update Stock',
        'reserve_stock' => 'Reserve Stock',
        'view_details' => 'View Details',
        'quality_distribution' => 'Quality Distribution',
        'quality_distribution_subtitle' => 'Breakdown of injera by quality grade',
        'batch_id' => 'Batch ID',
        'select_batch' => 'Select Batch',
        'quantity' => 'Quantity',
        'select_quality' => 'Select Quality',
        'notes' => 'Notes',
        'cancel' => 'Cancel',
        'quantity_to_reserve' => 'Quantity to Reserve',
        'reservation_notes' => 'Reservation Notes',
        'order_id' => 'Order ID',
        'clear_filters' => 'Clear Filters',
        'refresh' => 'Refresh',
        'stock_inventory' => 'Stock Inventory',
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
