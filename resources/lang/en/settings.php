<?php

return [
    // Main Settings
    'title' => 'Restaurant Settings',
    'subtitle' => 'Configure restaurant operations, hours, contact information, and preferences',

    // Actions
    'save_settings' => 'Save Settings',
    'reset_defaults' => 'Reset to Defaults',
    'cancel' => 'Cancel',
    'close' => 'Close',

    // Tab Navigation
    'general' => [
        'title' => 'General Settings',
    ],
    'operating_hours' => 'Operating Hours',
    'contact_info' => 'Contact Info',
    'preferences' => 'Preferences',
    'notifications' => 'Notifications',

    // Shift Management Section
    'shift_management' => [
        'section_title' => 'Shift Management',
    ],

    // Restaurant Branding
    'restaurant_branding' => 'Restaurant Branding',
    'branding_description' => 'Manage your restaurant\'s name, logo, and brand identity',
    'restaurant_name' => 'Restaurant Name',
    'restaurant_tagline' => 'Tagline/Slogan',
    'tagline_placeholder' => 'Enter a catchy tagline or slogan...',
    'restaurant_type' => 'Restaurant Type',
    'restaurant_logo' => 'Restaurant Logo',
    'upload_logo' => 'Upload Logo',
    'remove_logo' => 'Remove Logo',
    'logo_guidelines' => 'Logo Guidelines',
    'logo_size_guide' => 'Recommended size: 200×200 pixels (square format)',
    'logo_format_guide' => 'Accepted formats: PNG, JPG, SVG',
    'logo_file_size_guide' => 'Maximum file size: 2MB',
    'logo_quality_guide' => 'Use high-quality images for best results',
    'recommended_size' => 'Recommended',
    'drag_drop_hint' => 'Drag and drop your logo here, or click to browse',

    // Restaurant Types
    'casual_dining' => 'Casual Dining',
    'fine_dining' => 'Fine Dining',
    'fast_casual' => 'Fast Casual',
    'cafe' => 'Café',
    'bar_grill' => 'Bar & Grill',
    'ethnic_cuisine' => 'Ethnic Cuisine',

    // Restaurant Details
    'restaurant_details' => 'Restaurant Details',
    'details_description' => 'Configure basic restaurant information and operational details',
    'cuisine_type' => 'Cuisine Type',
    'seating_capacity' => 'Seating Capacity',
    'price_range' => 'Price Range',
    'default_language' => 'Default Language',
    'restaurant_description' => 'Restaurant Description',
    'description_placeholder' => 'Describe your restaurant\'s atmosphere, specialties, and unique features...',

    // Cuisine Types
    'ethiopian' => 'Ethiopian',
    'italian' => 'Italian',
    'american' => 'American',
    'asian' => 'Asian',
    'mediterranean' => 'Mediterranean',
    'fusion' => 'Fusion',
    'international' => 'International',

    // Price Ranges
    'budget_friendly' => 'Budget Friendly ($)',
    'moderate' => 'Moderate ($$)',
    'upscale' => 'Upscale ($$$)',
    'fine_dining_price' => 'Fine Dining ($$$$)',

    // Languages
    'english' => 'English',
    'amharic' => 'Amharic',
    'tigrinya' => 'Tigrinya',

    // Operating Hours
    'hours_description' => 'Set your restaurant\'s operating hours for each day of the week',
    'monday' => 'Monday',
    'tuesday' => 'Tuesday',
    'wednesday' => 'Wednesday',
    'thursday' => 'Thursday',
    'friday' => 'Friday',
    'saturday' => 'Saturday',
    'sunday' => 'Sunday',
    'opening_time' => 'Opening Time',
    'closing_time' => 'Closing Time',
    'open' => 'Open',
    'closed' => 'Closed',
    'copy_to_all' => 'Copy to All Days',
    'reset_hours' => 'Reset Hours',

    // Contact Information
    'contact_information' => 'Contact Information',
    'contact_description' => 'Provide contact details for customers and business communications',
    'address' => 'Address',
    'address_placeholder' => 'Enter your restaurant\'s full address...',
    'phone_number' => 'Phone Number',
    'phone_placeholder' => '+251-11-123-4567',
    'email_address' => 'Email Address',
    'email_placeholder' => 'info@yourrestaurant.com',
    'website' => 'Website',
    'website_placeholder' => 'https://www.yourrestaurant.com',
    'social_media' => 'Social Media',
    'social_placeholder' => 'https://www.facebook.com/yourrestaurant',

    // Location Settings
    'location_settings' => 'Location & Currency',
    'location_description' => 'Configure timezone, currency, and local business settings',
    'timezone' => 'Timezone',
    'currency' => 'Currency',
    'tax_rate' => 'Tax Rate',
    'service_charge' => 'Service Charge',

    // Timezones
    'addis_ababa' => 'Africa/Addis Ababa (EAT)',
    'utc' => 'UTC (Coordinated Universal Time)',
    'london' => 'Europe/London (GMT/BST)',
    'new_york' => 'America/New_York (EST/EDT)',

    // Currencies
    'ethiopian_birr' => 'Ethiopian Birr (ETB)',
    'us_dollar' => 'US Dollar (USD)',
    'euro' => 'Euro (EUR)',
    'british_pound' => 'British Pound (GBP)',

    // Reservation Settings
    'reservation_settings' => 'Reservation Settings',
    'reservation_description' => 'Configure reservation policies and booking preferences',
    'max_party_size' => 'Maximum Party Size',
    'advance_booking_days' => 'Advance Booking (Days)',
    'default_reservation_duration' => 'Default Reservation Duration',
    'allow_walk_ins' => 'Allow Walk-in Customers',
    'require_confirmation' => 'Require Reservation Confirmation',
    'auto_confirm_reservations' => 'Auto-confirm Reservations',

    // Time Units
    'hour' => 'hour',
    'hours' => 'hours',
    'minutes' => 'minutes',

    // Order Settings
    'order_settings' => 'Order Settings',
    'order_description' => 'Configure order policies and customer requirements',
    'order_timeout' => 'Order Timeout',
    'minimum_order_amount' => 'Minimum Order Amount',
    'allow_special_requests' => 'Allow Special Requests',
    'require_phone_orders' => 'Require Phone for Orders',

    // Email Notifications
    'email_notifications' => 'Email Notifications',
    'email_description' => 'Configure which events should trigger email notifications',
    'notification_email' => 'Notification Email Address',
    'notification_email_placeholder' => 'manager@yourrestaurant.com',

    // Notification Categories
    'reservation_notifications' => 'Reservation Notifications',
    'order_notifications' => 'Order Notifications',
    'inventory_notifications' => 'Inventory Notifications',
    'review_notifications' => 'Review Notifications',

    // Notification Types
    'new_reservations' => 'New Reservations',
    'cancelled_reservations' => 'Cancelled Reservations',
    'no_shows' => 'No-shows',
    'new_orders' => 'New Orders',
    'cancelled_orders' => 'Cancelled Orders',
    'low_stock_alerts' => 'Low Stock Alerts',
    'expired_items' => 'Expired Items',
    'new_reviews' => 'New Reviews',
    'negative_reviews' => 'Negative Reviews (1-2 stars)',

    // Messages
    'settings_saved' => 'Settings saved successfully',
    'settings_reset' => 'Settings reset to defaults',
    'logo_uploaded' => 'Logo uploaded successfully',
    'logo_removed' => 'Logo removed successfully',
    'hours_copied' => 'Hours copied to all days',
    'hours_reset' => 'Hours reset to defaults',
    'unsaved_changes' => 'You have unsaved changes',
    'save_before_leave' => 'Would you like to save your changes before leaving?',
    'reset_confirmation' => 'Reset all settings to default values? This action cannot be undone.',
    'remove_logo_confirmation' => 'Are you sure you want to remove the logo?',
    'copy_hours_confirmation' => 'Copy Monday hours to all other days?',
    'reset_hours_confirmation' => 'Reset all hours to default schedule?',
    'at_least_one_day' => 'Restaurant must be open at least one day per week',
    'fill_required_fields' => 'Please fill in all required fields',
    'invalid_email' => 'Please enter a valid email address',
    'invalid_phone' => 'Please enter a valid phone number',
    'invalid_website' => 'Please enter a valid website URL',
    'logo_too_large' => 'Logo file must be less than 5MB',
    'invalid_image' => 'Please select a valid image file',

    // Validation
    'restaurant_name_required' => 'Restaurant name is required',
    'address_required' => 'Address is required',
    'phone_required' => 'Phone number is required',
];
