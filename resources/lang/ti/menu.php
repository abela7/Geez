<?php

return [
    // Navigation
    'nav_title' => 'Menu Management',

    // Food Items
    'food_items' => [
        'title' => 'Food Items',
        'subtitle' => 'Manage your restaurant\'s food items and dishes',
        'nav_title' => 'Food Items',

        // Actions
        'add_dish' => 'Add Dish',
        'import_dishes' => 'Import Dishes',
        'export_menu' => 'Export Menu',
        'save_dish' => 'Save Dish',
        'cancel' => 'Cancel',
        'close' => 'Close',
        'actions' => 'Actions',

        // Search and Filters
        'search_dishes' => 'Search dishes...',
        'all_categories' => 'All Categories',
        'all_status' => 'All Status',
        'all_prices' => 'All Prices',
        'clear_filters' => 'Clear Filters',
        'under_50' => 'Under 50 ETB',
        'over_200' => 'Over 200 ETB',

        // Categories
        'appetizers' => 'Appetizers',
        'main_courses' => 'Main Courses',
        'desserts' => 'Desserts',
        'beverages' => 'Beverages',
        'select_category' => 'Select Category',

        // Status
        'active' => 'Active',
        'inactive' => 'Inactive',
        'out_of_stock' => 'Out of Stock',

        // Table Headers
        'dish' => 'Dish',
        'category' => 'Category',
        'price' => 'Price',
        'cost' => 'Cost',
        'margin' => 'Margin',
        'status' => 'Status',

        // Form Fields
        'dish_name' => 'Dish Name',
        'dish_name_placeholder' => 'Enter dish name (e.g., Grilled Chicken)',
        'description' => 'Description',
        'description_placeholder' => 'Describe your dish, ingredients, and preparation...',
        'prep_time' => 'Preparation Time',
        'minutes' => 'minutes',
        'cost_price' => 'Cost Price',
        'cost_price_hint' => 'Total cost of ingredients and preparation',
        'selling_price' => 'Selling Price',
        'profit_margin' => 'Profit Margin',
        'markup' => 'Markup',

        // Form Tabs
        'basic_info' => 'Basic Info',
        'ingredients' => 'Ingredients',
        'pricing' => 'Pricing',
        'media' => 'Media',

        // Dietary Info
        'dietary_info' => 'Dietary Information',
        'vegetarian' => 'Vegetarian',
        'vegan' => 'Vegan',
        'gluten_free' => 'Gluten Free',
        'spicy' => 'Spicy',

        // Ingredients
        'recipe_ingredients' => 'Recipe Ingredients',
        'add_ingredient' => 'Add Ingredient',
        'select_ingredient' => 'Select Ingredient',

        // Media Upload
        'upload_image' => 'Upload Dish Image',
        'upload_image_hint' => 'Drag and drop an image or click to select (Max 5MB)',
        'select_image' => 'Select Image',
        'change_image' => 'Change Image',
        'remove_image' => 'Remove Image',

        // Empty States
        'no_dishes_found' => 'No dishes found',
        'no_dishes_description' => 'Create your first dish to get started with your menu.',
        'add_first_dish' => 'Add First Dish',

        // Messages
        'dish_created' => 'Dish created successfully',
        'dish_updated' => 'Dish updated successfully',
        'dish_deleted' => 'Dish deleted successfully',
        'menu_exported' => 'Menu exported successfully',
        'dishes_imported' => 'Dishes imported successfully',
    ],

    // Categories
    'categories' => [
        'title' => 'Categories',
        'subtitle' => 'Organize your menu items into categories',
        'nav_title' => 'Categories',

        // Actions
        'add_category' => 'Add Category',
        'import_categories' => 'Import Categories',
        'export_categories' => 'Export Categories',
        'save_category' => 'Save Category',
        'edit_category' => 'Edit Category',
        'cancel' => 'Cancel',
        'close' => 'Close',
        'actions' => 'Actions',

        // Statistics
        'total_categories' => 'Total Categories',
        'active_categories' => 'Active Categories',
        'total_dishes' => 'Total Dishes',
        'most_popular' => 'Most Popular',

        // Search and Filters
        'search_categories' => 'Search categories...',
        'all_status' => 'All Status',
        'clear_filters' => 'Clear Filters',
        'sort_by_name' => 'Sort by Name',
        'sort_by_dishes' => 'Sort by Dishes',
        'sort_by_created' => 'Sort by Created',
        'sort_by_updated' => 'Sort by Updated',

        // Status
        'active' => 'Active',
        'inactive' => 'Inactive',

        // Table Headers
        'category' => 'Category',
        'description' => 'Description',
        'dishes_count' => 'Dishes',
        'display_order' => 'Order',
        'status' => 'Status',

        // Form Fields
        'category_name' => 'Category Name',
        'category_name_placeholder' => 'Enter category name (e.g., Appetizers)',
        'category_color' => 'Category Color',
        'category_icon' => 'Category Icon',
        'description_placeholder' => 'Describe this category and what types of dishes it includes...',
        'display_order_hint' => 'Lower numbers appear first in menus',

        // Modal Titles
        'category_details' => 'Category Details',

        // Empty States
        'no_categories_found' => 'No categories found',
        'no_categories_description' => 'Create your first category to organize your menu items.',
        'add_first_category' => 'Add First Category',

        // Messages
        'category_created' => 'Category created successfully',
        'category_updated' => 'Category updated successfully',
        'category_deleted' => 'Category deleted successfully',
        'categories_exported' => 'Categories exported successfully',
        'categories_imported' => 'Categories imported successfully',
    ],

    // Modifiers
    'modifiers' => [
        'title' => 'Modifiers',
        'subtitle' => 'Manage add-ons, extras, and customizations',
        'nav_title' => 'Modifiers',

        // Actions
        'add_modifier_group' => 'Add Modifier Group',
        'add_modifier' => 'Add Modifier',
        'import_modifiers' => 'Import Modifiers',
        'export_modifiers' => 'Export Modifiers',
        'save_group' => 'Save Group',
        'save_modifier' => 'Save Modifier',
        'edit_group' => 'Edit Group',
        'edit_modifier' => 'Edit Modifier',
        'cancel' => 'Cancel',
        'close' => 'Close',
        'actions' => 'Actions',

        // Statistics
        'total_groups' => 'Total Groups',
        'total_modifiers' => 'Total Modifiers',
        'active_groups' => 'Active Groups',
        'avg_modifier_price' => 'Avg. Price',

        // Search and Filters
        'search_modifiers' => 'Search modifier groups...',
        'all_types' => 'All Types',
        'all_status' => 'All Status',
        'clear_filters' => 'Clear Filters',
        'sort_by_name' => 'Sort by Name',
        'sort_by_type' => 'Sort by Type',
        'sort_by_modifiers' => 'Sort by Modifiers',
        'sort_by_created' => 'Sort by Created',

        // Selection Types
        'single_select' => 'Single Select',
        'multiple_select' => 'Multiple Select',
        'selection_type' => 'Selection Type',
        'select_type' => 'Select Type',
        'selection_type_hint' => 'Single: customers can choose one option. Multiple: customers can choose several options.',

        // Status
        'active' => 'Active',
        'inactive' => 'Inactive',
        'status' => 'Status',

        // Form Fields - Group
        'group_name' => 'Group Name',
        'group_name_placeholder' => 'Enter group name (e.g., Size Options)',
        'description' => 'Description',
        'description_placeholder' => 'Describe this modifier group and its purpose...',
        'display_order' => 'Display Order',
        'display_order_hint' => 'Lower numbers appear first in menus',

        // Form Fields - Modifier
        'modifier_name' => 'Modifier Name',
        'modifier_name_placeholder' => 'Enter modifier name (e.g., Large Size)',
        'price' => 'Price',
        'price_hint' => 'Additional cost for this modifier (0.00 for no extra charge)',
        'modifier_description_placeholder' => 'Optional description for this modifier...',

        // Form Tabs
        'basic_info' => 'Basic Info',
        'modifier_options' => 'Modifier Options',
        'settings' => 'Settings',

        // Settings
        'required_selection' => 'Required Selection',
        'required' => 'Required',
        'required_hint' => 'Customers must select at least one option from this group',
        'min_selections' => 'Minimum Selections',
        'min_selections_hint' => 'Minimum number of options customers must select',
        'max_selections' => 'Maximum Selections',
        'max_selections_hint' => 'Maximum number of options customers can select',
        'default_selection' => 'Default Selection',
        'no_default' => 'No Default',
        'default_selection_hint' => 'Pre-selected option when customers view this group',
        'price_display' => 'Price Display',
        'show_prices' => 'Show Prices',
        'hide_prices' => 'Hide Prices',

        // Modifiers List
        'no_modifiers_added' => 'No modifiers added yet.',
        'add_first_modifier' => 'Add First Modifier',

        // Modal Titles
        'group_details' => 'Modifier Group Details',

        // Empty States
        'no_modifiers_found' => 'No modifier groups found',
        'no_modifiers_description' => 'Create your first modifier group to add customization options to your menu items.',
        'add_first_group' => 'Add First Group',

        // Messages
        'group_created' => 'Modifier group created successfully',
        'group_updated' => 'Modifier group updated successfully',
        'group_deleted' => 'Modifier group deleted successfully',
        'modifier_created' => 'Modifier created successfully',
        'modifier_updated' => 'Modifier updated successfully',
        'modifier_deleted' => 'Modifier deleted successfully',
        'modifiers_exported' => 'Modifiers exported successfully',
        'modifiers_imported' => 'Modifiers imported successfully',
    ],

    // Dish Cost
    'dish_cost' => [
        'title' => 'Dish Cost',
        'subtitle' => 'Calculate and manage dish costs and margins',
        'nav_title' => 'Dish Cost',

        // Actions
        'load_existing_dish' => 'Load Existing Dish',
        'save_as_template' => 'Save as Template',
        'new_calculation' => 'New Calculation',
        'reset_calculation' => 'Reset Calculation',
        'save_calculation' => 'Save Calculation',
        'add_ingredient' => 'Add Ingredient',
        'add_first_ingredient' => 'Add First Ingredient',
        'cancel' => 'Cancel',
        'close' => 'Close',
        'actions' => 'Actions',

        // Dish Information
        'dish_information' => 'Dish Information',
        'dish_info_subtitle' => 'Enter basic information about the dish',
        'dish_name' => 'Dish Name',
        'dish_name_placeholder' => 'Enter dish name (e.g., Margherita Pizza)',
        'category' => 'Category',
        'select_category' => 'Select Category',
        'appetizers' => 'Appetizers',
        'main_courses' => 'Main Courses',
        'desserts' => 'Desserts',
        'beverages' => 'Beverages',
        'serving_size' => 'Serving Size',

        // Ingredients
        'ingredients' => 'Ingredients',
        'ingredients_subtitle' => 'Add all ingredients and their costs',
        'ingredient' => 'Ingredient',
        'ingredient_name' => 'Ingredient Name',
        'ingredient_name_placeholder' => 'Enter ingredient name (e.g., Tomatoes)',
        'quantity' => 'Quantity',
        'unit' => 'Unit',
        'select_unit' => 'Select Unit',
        'cost_per_unit' => 'Cost per Unit',
        'total' => 'Total',
        'notes' => 'Notes',
        'notes_placeholder' => 'Optional notes about this ingredient...',

        // Units
        'kg' => 'Kilograms',
        'g' => 'Grams',
        'lb' => 'Pounds',
        'oz' => 'Ounces',
        'l' => 'Liters',
        'ml' => 'Milliliters',
        'cup' => 'Cups',
        'tbsp' => 'Tablespoons',
        'tsp' => 'Teaspoons',
        'piece' => 'Pieces',

        // Overhead Costs
        'overhead_costs' => 'Overhead Costs',
        'overhead_subtitle' => 'Calculate additional costs beyond ingredients',
        'percentage_of_ingredients' => 'Percentage of Ingredients Cost',
        'fixed_amount' => 'Fixed Amount',
        'overhead_breakdown' => 'Overhead Breakdown',
        'labor_cost' => 'Labor Cost',
        'utilities' => 'Utilities',
        'rent_equipment' => 'Rent & Equipment',
        'other_expenses' => 'Other Expenses',

        // Cost Summary
        'cost_summary' => 'Cost Summary',
        'ingredients_cost' => 'Ingredients Cost:',
        'overhead_cost' => 'Overhead Cost:',
        'total_cost' => 'Total Cost:',

        // Pricing
        'pricing' => 'Pricing',
        'profit_margin' => 'Profit Margin (%)',
        'suggested_price' => 'Suggested Price:',
        'final_price' => 'Final Price:',
        'actual_margin' => 'Actual Margin:',
        'profit_per_dish' => 'Profit per Dish:',
        'cost_percentage' => 'Cost %:',

        // Empty States
        'no_ingredients' => 'No ingredients added',
        'no_ingredients_description' => 'Add ingredients to calculate the dish cost.',

        // Quick Tips
        'quick_tips' => 'Quick Tips',
        'tip_1' => 'Aim for food costs between 25-35% of selling price',
        'tip_2' => 'Include all ingredients, even small amounts like spices',
        'tip_3' => 'Update costs regularly based on supplier prices',
        'tip_4' => 'Consider portion sizes when calculating quantities',

        // Messages
        'ingredient_added' => 'Ingredient added successfully',
        'ingredient_updated' => 'Ingredient updated successfully',
        'ingredient_deleted' => 'Ingredient deleted successfully',
        'calculation_saved' => 'Calculation saved successfully',
        'calculation_reset' => 'Calculation reset successfully',
        'template_saved' => 'Template saved successfully',
        'dish_loaded' => 'Dish loaded successfully',
    ],

    // Pricing
    'pricing' => [
        'title' => 'Pricing',
        'subtitle' => 'Set and manage menu item pricing strategies',
        'nav_title' => 'Pricing',

        // Actions
        'export_prices' => 'Export Prices',
        'bulk_update' => 'Bulk Update',
        'price_history' => 'Price History',
        'edit_price' => 'Edit Price',
        'update_price' => 'Update Price',
        'cancel' => 'Cancel',
        'close' => 'Close',
        'actions' => 'Actions',

        // Statistics
        'total_items' => 'Total Items',
        'average_price' => 'Average Price',
        'recent_changes' => 'Recent Changes',
        'price_range' => 'Price Range',

        // Search and Filters
        'search_items' => 'Search menu items...',
        'all_categories' => 'All Categories',
        'all_prices' => 'All Prices',
        'clear_filters' => 'Clear Filters',
        'sort_by_name' => 'Sort by Name',
        'sort_by_price_low' => 'Price: Low to High',
        'sort_by_price_high' => 'Price: High to Low',
        'sort_by_updated' => 'Recently Updated',

        // Categories
        'appetizers' => 'Appetizers',
        'main_courses' => 'Main Courses',
        'desserts' => 'Desserts',
        'beverages' => 'Beverages',

        // Table Headers
        'item' => 'Item',
        'category' => 'Category',
        'current_price' => 'Current Price',
        'cost' => 'Cost',
        'margin' => 'Margin',
        'last_updated' => 'Last Updated',

        // Edit Price Modal
        'new_price' => 'New Price',
        'price_change' => 'Price Change',
        'reason_for_change' => 'Reason for Change',
        'select_reason' => 'Select Reason',
        'cost_increase' => 'Cost Increase',
        'cost_decrease' => 'Cost Decrease',
        'market_adjustment' => 'Market Adjustment',
        'seasonal_pricing' => 'Seasonal Pricing',
        'promotion' => 'Promotion',
        'competitor_pricing' => 'Competitor Pricing',
        'other' => 'Other',
        'notes' => 'Notes',
        'notes_placeholder' => 'Optional notes about this price change...',
        'effective_date' => 'Effective Date',
        'apply_to_similar' => 'Apply to Similar Items',
        'apply_same_category' => 'Apply to same category',

        // Price Analysis
        'price_analysis' => 'Price Analysis',
        'new_margin' => 'New Margin',
        'profit_change' => 'Profit Change',
        'cost_percentage' => 'Cost %',
        'price_position' => 'Price Position',
        'average' => 'Average',

        // Bulk Update Modal
        'bulk_price_update' => 'Bulk Price Update',
        'percentage_change' => 'Percentage Change',
        'fixed_amount' => 'Fixed Amount',
        'apply_to' => 'Apply To',
        'preview_changes' => 'Preview Changes',
        'items_affected' => 'items will be affected',
        'apply_changes' => 'Apply Changes',

        // Price History Modal
        'all_items' => 'All Items',
        'last_7_days' => 'Last 7 Days',
        'last_30_days' => 'Last 30 Days',
        'last_90_days' => 'Last 90 Days',
        'last_year' => 'Last Year',

        // Empty States
        'no_items_found' => 'No menu items found',
        'no_items_description' => 'Try adjusting your search or filter criteria.',

        // Messages
        'price_updated' => 'Price updated successfully',
        'prices_exported' => 'Prices exported successfully',
        'bulk_update_applied' => 'Bulk update applied successfully',
        'invalid_price' => 'Please enter a valid price',
        'select_categories' => 'Please select at least one category',
        'price_change_saved' => 'Price change saved to history',
    ],

    // Menu Design
    'design' => [
        'title' => 'Menu Design',
        'subtitle' => 'Design and customize your menu layout and appearance',
        'nav_title' => 'Menu Design',

        // Actions
        'preview_menu' => 'Preview Menu',
        'export_design' => 'Export Design',
        'save_design' => 'Save Design',
        'download_pdf' => 'Download PDF',
        'close' => 'Close',

        // Tabs
        'branding' => 'Branding',
        'layout' => 'Layout',
        'colors' => 'Colors',
        'typography' => 'Typography',
        'content' => 'Content',

        // Branding
        'restaurant_branding' => 'Restaurant Branding',
        'logo' => 'Logo',
        'upload_logo' => 'Click to upload logo',
        'logo_hint' => 'PNG, JPG up to 5MB',
        'restaurant_name' => 'Restaurant Name',
        'restaurant_name_placeholder' => 'Enter your restaurant name',
        'restaurant_address' => 'Restaurant Address',
        'address_placeholder' => 'Enter your restaurant address, phone, and email',
        'restaurant_description' => 'Restaurant Description',
        'description_placeholder' => 'Describe your restaurant and dining experience',
        'social_media' => 'Social Media Links',

        // Layout
        'menu_layout' => 'Menu Layout',
        'layout_template' => 'Layout Template',
        'classic_layout' => 'Classic',
        'modern_layout' => 'Modern',
        'elegant_layout' => 'Elegant',
        'page_settings' => 'Page Settings',
        'page_size' => 'Page Size',
        'orientation' => 'Orientation',
        'portrait' => 'Portrait',
        'landscape' => 'Landscape',
        'columns' => 'Columns',
        'column' => 'Column',
        'spacing' => 'Spacing',
        'compact' => 'Compact',
        'normal' => 'Normal',
        'spacious' => 'Spacious',
        'section_order' => 'Section Order',
        'appetizers' => 'Appetizers',
        'main_courses' => 'Main Courses',
        'desserts' => 'Desserts',
        'beverages' => 'Beverages',

        // Colors
        'color_scheme' => 'Color Scheme',
        'color_presets' => 'Color Presets',
        'classic' => 'Classic',
        'elegant' => 'Elegant',
        'modern' => 'Modern',
        'warm' => 'Warm',
        'custom_colors' => 'Custom Colors',
        'primary_color' => 'Primary Color',
        'background_color' => 'Background Color',
        'accent_color' => 'Accent Color',
        'text_color' => 'Text Color',
        'background_options' => 'Background Options',
        'solid_color' => 'Solid Color',
        'gradient' => 'Gradient',
        'pattern' => 'Pattern',

        // Typography
        'typography_settings' => 'Typography Settings',
        'font_family' => 'Font Family',
        'font_sizes' => 'Font Sizes',
        'title_size' => 'Title Size',
        'heading_size' => 'Heading Size',
        'body_size' => 'Body Size',
        'price_size' => 'Price Size',
        'text_styling' => 'Text Styling',
        'bold_headings' => 'Bold Headings',
        'italic_descriptions' => 'Italic Descriptions',
        'uppercase_categories' => 'Uppercase Categories',
        'spacing_settings' => 'Spacing Settings',
        'line_height' => 'Line Height',
        'paragraph_spacing' => 'Paragraph Spacing',

        // Content
        'content_settings' => 'Content Settings',
        'display_options' => 'Display Options',
        'show_prices' => 'Show Prices',
        'show_descriptions' => 'Show Descriptions',
        'show_images' => 'Show Images',
        'show_dietary_info' => 'Show Dietary Information',
        'show_spice_level' => 'Show Spice Level',
        'price_format' => 'Price Format',
        'menu_language' => 'Menu Language',
        'english' => 'English',
        'amharic' => 'Amharic',
        'tigrinya' => 'Tigrinya',
        'footer_content' => 'Footer Content',
        'footer_placeholder' => 'Enter footer text (allergies, thank you message, etc.)',
        'qr_code_settings' => 'QR Code Settings',
        'include_qr_code' => 'Include QR Code',
        'qr_url' => 'QR Code URL',

        // Preview
        'live_preview' => 'Live Preview',
        'menu_preview' => 'Menu Preview',

        // Messages
        'design_saved' => 'Menu design saved successfully',
        'design_exported' => 'Menu design exported successfully',
        'logo_uploaded' => 'Logo uploaded successfully',
        'logo_removed' => 'Logo removed successfully',
        'invalid_file' => 'Please select a valid image file',
        'file_too_large' => 'Image file size must be less than 5MB',
        'pdf_coming_soon' => 'PDF download feature coming soon',
    ],
];
