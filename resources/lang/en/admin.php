<?php

return [
    'common' => [
        'geez_logo_alt' => 'Geez Restaurant Management System Logo',
        'actions' => 'Actions',
        'view' => 'View',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'cancel' => 'Cancel',
        'save' => 'Save',
        'create' => 'Create',
        'update' => 'Update',
        'active' => 'Active',
        'inactive' => 'Inactive',
        'activate' => 'Activate',
        'deactivate' => 'Deactivate',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
        'status' => 'Status',
    ],

    'departments' => [
        'title' => 'Departments',
        'description' => 'Manage restaurant departments and their settings',
        'create_new' => 'Create Department',
        'create_title' => 'Create New Department',
        'create_description' => 'Add a new department to organize your staff and shifts',
        'edit_title' => 'Edit Department',
        'edit_description' => 'Update department information and settings',
        'edit' => 'Edit',
        'details' => 'Department Details',
        'activity' => 'Activity Information',
        'no_departments' => 'No Departments Found',
        'no_departments_description' => 'Get started by creating your first department',
        'create_first' => 'Create First Department',
        'confirm_delete' => 'Are you sure you want to delete this department?',

        // Stats
        'total_departments' => 'Total Departments',
        'active_departments' => 'Active Departments',
        'inactive_departments' => 'Inactive Departments',

        // Form fields
        'name' => 'Name',
        'name_placeholder' => 'e.g., Kitchen, Front of House',
        'name_help' => 'Enter a descriptive name for this department',
        'slug' => 'Slug',
        'description' => 'Description',
        'description_placeholder' => 'Brief description of this department\'s role',
        'description_help' => 'Optional description explaining the department\'s responsibilities',
        'color' => 'Color',
        'color_help' => 'Choose a color to identify this department in the interface',
        'sort_order' => 'Sort Order',
        'sort_order_help' => 'Lower numbers appear first in lists',
        'is_active' => 'Active Department',
        'is_active_help' => 'Only active departments can be used for new shifts',

        // Settings sections
        'basic_information' => 'Basic Information',
        'basic_info_description' => 'Essential department details and identification',
        'settings' => 'Department Settings',
        'settings_description' => 'Configure department behavior and display options',

        // Actions
        'create_department' => 'Create Department',
        'update_department' => 'Update Department',
    ],

    'shift_types' => [
        'title' => 'Shift Types',
        'description' => 'Manage different types of shifts and their default rates',
        'create_new' => 'Create Shift Type',
        'create_title' => 'Create New Shift Type',
        'create_description' => 'Add a new shift type with default rates and settings',
        'edit_title' => 'Edit Shift Type',
        'edit_description' => 'Update shift type information and default rates',
        'edit' => 'Edit',
        'details' => 'Shift Type Details',
        'activity' => 'Activity Information',
        'no_shift_types' => 'No Shift Types Found',
        'no_shift_types_description' => 'Get started by creating your first shift type',
        'create_first' => 'Create First Shift Type',
        'confirm_delete' => 'Are you sure you want to delete this shift type?',

        // Stats
        'total_shift_types' => 'Total Shift Types',
        'active_shift_types' => 'Active Shift Types',
        'inactive_shift_types' => 'Inactive Shift Types',
        'with_rates' => 'With Default Rates',

        // Form fields
        'name' => 'Name',
        'name_placeholder' => 'e.g., Regular, Weekend, Overtime',
        'name_help' => 'Enter a descriptive name for this shift type',
        'slug' => 'Slug',
        'description' => 'Description',
        'description_placeholder' => 'Brief description of this shift type',
        'description_help' => 'Optional description explaining when this shift type is used',
        'color' => 'Color',
        'color_help' => 'Choose a color to identify this shift type in schedules',
        'sort_order' => 'Sort Order',
        'sort_order_help' => 'Lower numbers appear first in lists',
        'is_active' => 'Active Shift Type',
        'is_active_help' => 'Only active shift types can be used for new shifts',
        'default_hourly_rate' => 'Default Hourly Rate',
        'default_hourly_rate_help' => 'Default hourly rate for this shift type (can be overridden)',
        'default_overtime_rate' => 'Default Overtime Rate',
        'default_overtime_rate_help' => 'Default overtime rate for this shift type (can be overridden)',
        'default_rates' => 'Default Rates',
        'status' => 'Status',

        // Settings sections
        'basic_information' => 'Basic Information',
        'basic_info_description' => 'Essential shift type details and identification',
        'rate_settings' => 'Rate Settings',
        'rate_settings_description' => 'Configure default hourly and overtime rates',
        'settings' => 'Shift Type Settings',
        'settings_description' => 'Configure shift type behavior and display options',

        // Actions
        'create_shift_type' => 'Create Shift Type',
        'update_shift_type' => 'Update Shift Type',
    ],

    'auth' => [
        'login' => [
            'title' => 'Admin Login',
            'heading' => 'Sign in to Admin Panel',
            'subtitle' => 'Access your restaurant management dashboard',
            'username' => 'Username',
            'username_placeholder' => 'Enter your username',
            'password' => 'Password',
            'password_placeholder' => 'Enter your password',
            'remember_me' => 'Remember me',
            'sign_in' => 'Sign In',
            'signing_in' => 'Signing in...',
            'security_notice' => 'This is a secure area. All access is logged and monitored.',
        ],
    ],

    'staff' => [
        'types' => [
            'title' => 'Staff Types',
            'list' => 'Staff Types List',
            'trashed' => 'Deleted Staff Types',
            'add_new' => 'Add New Staff Type',
            'create' => 'Create Staff Type',
            'edit' => 'Edit Staff Type',
            'show' => 'Staff Type Details',
            'details' => 'Staff Type Details',
            'assigned_staff' => 'Assigned Staff',
            'no_types_found' => 'No Staff Types Found',
            'no_trashed_types' => 'No Deleted Staff Types',
            'back_to_list' => 'Back to Staff Types',
        ],
    ],

    'demo' => [
        'logo_animations' => [
            'title' => 'Logo Animation Demo',
            'description' => 'Interactive demonstration of GSAP-powered animations for the Geez logo. Test different animation styles and effects.',
            'basic_animations' => 'Basic Animations',
            'creative_animations' => 'Creative Animations',
            'interactive_effects' => 'Interactive Effects',
            'master_controls' => 'Master Controls',
            'settings' => 'Animation Settings',
            'code_examples' => 'Code Examples',

            // Animation names
            'draw_in' => 'Draw In',
            'fade_scale' => 'Fade & Scale',
            'bounce' => 'Bounce',
            'typewriter' => 'Typewriter',
            'glow' => 'Glow Effect',
            'liquid' => 'Liquid Flow',
            'particle_burst' => 'Particle Burst',

            // Interactive controls
            'toggle_hover' => 'Toggle Hover',
            'toggle_pulse' => 'Toggle Pulse',
            'play_master' => 'Play Master Animation',
            'play_random' => 'Play Random',
            'reset' => 'Reset Logo',

            // Settings
            'duration' => 'Duration',
            'delay' => 'Delay',
            'easing' => 'Easing Function',
        ],
    ],
];
