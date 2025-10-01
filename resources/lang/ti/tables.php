<?php

return [
    // Main Navigation
    'nav_title' => 'Table & Room Management',
    'title' => 'Table & Room Management',
    'subtitle' => 'Manage restaurant rooms, table categories, types, and layout design',
    
    // Rooms Management
    'rooms' => [
        'title' => 'Rooms Management',
        'subtitle' => 'Create and manage restaurant rooms and dining areas',
        'nav_title' => 'Rooms',
        
        // Actions
        'add_room' => 'Add Room',
        'edit_room' => 'Edit Room',
        'delete_room' => 'Delete Room',
        'save_room' => 'Save Room',
        'cancel' => 'Cancel',
        'close' => 'Close',
        
        // Room Details
        'room_name' => 'Room Name',
        'room_type' => 'Room Type',
        'capacity' => 'Capacity',
        'description' => 'Description',
        'status' => 'Status',
        'room_code' => 'Room Code',
        
        // Room Types
        'main_dining' => 'Main Dining Room',
        'private_dining' => 'Private Dining Room',
        'bar_area' => 'Bar Area',
        'outdoor_patio' => 'Outdoor Patio',
        'vip_section' => 'VIP Section',
        'banquet_hall' => 'Banquet Hall',
        'terrace' => 'Terrace',
        'lounge' => 'Lounge',
        
        // Status
        'active' => 'Active',
        'inactive' => 'Inactive',
        'maintenance' => 'Under Maintenance',
        
        // Form Fields
        'room_name_placeholder' => 'Enter room name...',
        'room_description_placeholder' => 'Describe the room\'s features and atmosphere...',
        'room_code_placeholder' => 'e.g., MDR, PDR, BAR',
        
        // Messages
        'room_added' => 'Room added successfully',
        'room_updated' => 'Room updated successfully',
        'room_deleted' => 'Room deleted successfully',
        'delete_room_confirmation' => 'Are you sure you want to delete this room?',
        
        // Validation
        'room_name_required' => 'Room name is required',
        'room_type_required' => 'Room type is required',
        'capacity_required' => 'Capacity is required',
    ],
    
    // Table Categories
    'categories' => [
        'title' => 'Table Categories',
        'subtitle' => 'Define different table categories and their characteristics',
        'nav_title' => 'Categories',
        
        // Actions
        'add_category' => 'Add Category',
        'edit_category' => 'Edit Category',
        'delete_category' => 'Delete Category',
        'save_category' => 'Save Category',
        'cancel' => 'Cancel',
        'close' => 'Close',
        
        // Category Details
        'category_name' => 'Category Name',
        'category_code' => 'Category Code',
        'description' => 'Description',
        'color' => 'Color',
        'icon' => 'Icon',
        'default_capacity' => 'Default Capacity',
        'pricing_multiplier' => 'Pricing Multiplier',
        
        // Default Categories
        'standard' => 'Standard Tables',
        'premium' => 'Premium Tables',
        'vip' => 'VIP Tables',
        'outdoor' => 'Outdoor Tables',
        'bar_seating' => 'Bar Seating',
        'booth' => 'Booth Seating',
        'counter' => 'Counter Seating',
        'communal' => 'Communal Tables',
        
        // Form Fields
        'category_name_placeholder' => 'Enter category name...',
        'category_code_placeholder' => 'e.g., STD, PRM, VIP',
        'description_placeholder' => 'Describe this table category...',
        'select_color' => 'Select Color',
        'select_icon' => 'Select Icon',
        
        // Messages
        'category_added' => 'Table category added successfully',
        'category_updated' => 'Table category updated successfully',
        'category_deleted' => 'Table category deleted successfully',
        'delete_category_confirmation' => 'Are you sure you want to delete this category?',
        
        // Validation
        'category_name_required' => 'Category name is required',
        'category_code_required' => 'Category code is required',
    ],
    
    // Table Types
    'types' => [
        'title' => 'Table Types',
        'subtitle' => 'Configure different table types and their properties',
        'nav_title' => 'Types',
        
        // Actions
        'add_type' => 'Add Type',
        'edit_type' => 'Edit Type',
        'delete_type' => 'Delete Type',
        'save_type' => 'Save Type',
        'cancel' => 'Cancel',
        'close' => 'Close',
        
        // Type Details
        'type_name' => 'Type Name',
        'type_code' => 'Type Code',
        'shape' => 'Table Shape',
        'min_capacity' => 'Minimum Capacity',
        'max_capacity' => 'Maximum Capacity',
        'description' => 'Description',
        'dimensions' => 'Dimensions',
        'is_moveable' => 'Moveable',
        'requires_reservation' => 'Requires Reservation',
        
        // Table Shapes
        'rectangle' => 'Rectangle',
        'circle' => 'Circle',
        'square' => 'Square',
        'oval' => 'Oval',
        'custom' => 'Custom Shape',
        
        // Default Types
        'two_seater' => '2-Seater Table',
        'four_seater' => '4-Seater Table',
        'six_seater' => '6-Seater Table',
        'eight_seater' => '8-Seater Table',
        'bar_stool' => 'Bar Stool',
        'booth_table' => 'Booth Table',
        'counter_seat' => 'Counter Seat',
        'communal_table' => 'Communal Table',
        
        // Form Fields
        'type_name_placeholder' => 'Enter type name...',
        'type_code_placeholder' => 'e.g., T2, T4, T6, BS',
        'description_placeholder' => 'Describe this table type...',
        'width' => 'Width (cm)',
        'height' => 'Height (cm)',
        'diameter' => 'Diameter (cm)',
        
        // Messages
        'type_added' => 'Table type added successfully',
        'type_updated' => 'Table type updated successfully',
        'type_deleted' => 'Table type deleted successfully',
        'delete_type_confirmation' => 'Are you sure you want to delete this type?',
        
        // Validation
        'type_name_required' => 'Type name is required',
        'type_code_required' => 'Type code is required',
        'min_capacity_required' => 'Minimum capacity is required',
        'max_capacity_required' => 'Maximum capacity is required',
    ],
    
    // Table Layout
    'layout' => [
        'title' => 'Table Layout Designer',
        'subtitle' => 'Design and manage restaurant table layouts with visual editor',
        'nav_title' => 'Layout Designer',
        
        // Actions
        'save_layout' => 'Save Layout',
        'reset_layout' => 'Reset Layout',
        'export_layout' => 'Export Layout',
        'import_layout' => 'Import Layout',
        'preview_layout' => 'Preview Layout',
        'add_table' => 'Add Table',
        'edit_table' => 'Edit Table',
        'delete_table' => 'Delete Table',
        'duplicate_table' => 'Duplicate Table',
        
        // Layout Tools
        'select_room' => 'Select Room',
        'zoom_in' => 'Zoom In',
        'zoom_out' => 'Zoom Out',
        'zoom_fit' => 'Fit to Screen',
        'grid_snap' => 'Grid Snap',
        'show_grid' => 'Show Grid',
        'show_numbers' => 'Show Table Numbers',
        'show_capacity' => 'Show Capacity',
        
        // Table Properties
        'table_number' => 'Table Number',
        'table_name' => 'Table Name',
        'table_category' => 'Category',
        'table_type' => 'Type',
        'table_capacity' => 'Capacity',
        'table_shape' => 'Shape',
        'table_color' => 'Color',
        'table_rotation' => 'Rotation',
        'table_notes' => 'Notes',
        
        // Layout Properties
        'layout_name' => 'Layout Name',
        'room_selection' => 'Room',
        'canvas_width' => 'Canvas Width',
        'canvas_height' => 'Canvas Height',
        'scale' => 'Scale',
        
        // Drawing Tools
        'select_tool' => 'Select Tool',
        'move_tool' => 'Move Tool',
        'add_rectangle_table' => 'Add Rectangle Table',
        'add_circle_table' => 'Add Circle Table',
        'add_text' => 'Add Text Label',
        'add_wall' => 'Add Wall',
        'add_door' => 'Add Door',
        'add_window' => 'Add Window',
        
        // Table States
        'available' => 'Available',
        'occupied' => 'Occupied',
        'reserved' => 'Reserved',
        'out_of_order' => 'Out of Order',
        'cleaning' => 'Cleaning',
        
        // Form Fields
        'table_number_placeholder' => 'e.g., T1, T2, VIP1',
        'table_name_placeholder' => 'Optional table name...',
        'layout_name_placeholder' => 'Enter layout name...',
        'table_notes_placeholder' => 'Any special notes about this table...',
        
        // Messages
        'layout_saved' => 'Table layout saved successfully',
        'layout_reset' => 'Layout reset successfully',
        'layout_exported' => 'Layout exported successfully',
        'layout_imported' => 'Layout imported successfully',
        'table_added' => 'Table added to layout',
        'table_updated' => 'Table updated successfully',
        'table_deleted' => 'Table removed from layout',
        'table_duplicated' => 'Table duplicated successfully',
        'save_layout_confirmation' => 'Save current layout?',
        'reset_layout_confirmation' => 'Reset layout? All unsaved changes will be lost.',
        'delete_table_confirmation' => 'Remove this table from the layout?',
        
        // Validation
        'table_number_required' => 'Table number is required',
        'table_capacity_required' => 'Table capacity is required',
        'layout_name_required' => 'Layout name is required',
        'room_required' => 'Room selection is required',
        'table_number_exists' => 'Table number already exists in this room',
        
        // Instructions
        'layout_instructions' => 'Click and drag to move tables. Right-click for more options.',
        'no_tables' => 'No tables in this layout. Click "Add Table" to start.',
        'select_room_first' => 'Please select a room first to start designing the layout.',
    ],
    
    // Common Terms
    'search_placeholder' => 'Search...',
    'filter_by' => 'Filter by',
    'all' => 'All',
    'actions' => 'Actions',
    'name' => 'Name',
    'code' => 'Code',
    'type' => 'Type',
    'capacity' => 'Capacity',
    'status' => 'Status',
    'created_at' => 'Created',
    'updated_at' => 'Updated',
    'total_items' => 'Total Items',
    'active_items' => 'Active Items',
    'inactive_items' => 'Inactive Items',
];
