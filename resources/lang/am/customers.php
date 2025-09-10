<?php

return [
    // Navigation
    'nav_title' => 'Customer Management',
    
    // Customer Directory
    'directory' => [
        'title' => 'Customer Directory',
        'subtitle' => 'Manage customer profiles, contact information, and preferences',
        'nav_title' => 'Customer Directory',
        
        // Actions
        'add_customer' => 'Add Customer',
        'import_customers' => 'Import Customers',
        'export_customers' => 'Export Customers',
        'edit_customer' => 'Edit Customer',
        'delete_customer' => 'Delete Customer',
        'save_customer' => 'Save Customer',
        'cancel' => 'Cancel',
        'close' => 'Close',
        'add_first_customer' => 'Add Your First Customer',
        
        // Statistics
        'total_customers' => 'Total Customers',
        'new_this_month' => 'New This Month',
        'vip_customers' => 'VIP Customers',
        'active_customers' => 'Active Customers',
        
        // Search & Filters
        'search_customers' => 'Search customers by name, email, or phone...',
        'all_status' => 'All Status',
        'all_frequency' => 'All Visit Frequency',
        'all_locations' => 'All Locations',
        'clear_filters' => 'Clear Filters',
        'showing_results' => 'Showing :count of :total customers',
        
        // Status
        'active' => 'Active',
        'inactive' => 'Inactive',
        'vip' => 'VIP',
        
        // Visit Frequency
        'frequent_visitors' => 'Frequent Visitors',
        'regular_visitors' => 'Regular Visitors',
        'occasional_visitors' => 'Occasional Visitors',
        'first_time' => 'First Time',
        
        // Location
        'local' => 'Local',
        'nearby' => 'Nearby',
        'distant' => 'Distant',
        
        // Table Headers
        'customer' => 'Customer',
        'contact' => 'Contact',
        'status' => 'Status',
        'visits' => 'Visits',
        'total_spent' => 'Total Spent',
        'last_visit' => 'Last Visit',
        'actions' => 'Actions',
        
        // Form Fields - Basic Info
        'basic_info' => 'Basic Info',
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'date_of_birth' => 'Date of Birth',
        'gender' => 'Gender',
        'select_gender' => 'Select Gender',
        'male' => 'Male',
        'female' => 'Female',
        'other' => 'Other',
        'prefer_not_to_say' => 'Prefer not to say',
        'customer_status' => 'Customer Status',
        
        // Form Fields - Contact Info
        'contact_info' => 'Contact Info',
        'email' => 'Email Address',
        'phone' => 'Phone Number',
        'address' => 'Address',
        'city' => 'City',
        'postal_code' => 'Postal Code',
        
        // Form Fields - Preferences
        'preferences' => 'Preferences',
        'preferred_seating' => 'Preferred Seating',
        'no_preference' => 'No Preference',
        'window_seat' => 'Window Seat',
        'booth' => 'Booth',
        'bar_seating' => 'Bar Seating',
        'outdoor' => 'Outdoor',
        'quiet_area' => 'Quiet Area',
        'dietary_restrictions' => 'Dietary Restrictions',
        'vegetarian' => 'Vegetarian',
        'vegan' => 'Vegan',
        'gluten_free' => 'Gluten Free',
        'dairy_free' => 'Dairy Free',
        'nut_allergy' => 'Nut Allergy',
        'shellfish_allergy' => 'Shellfish Allergy',
        'allergies' => 'Allergies & Special Requirements',
        'allergies_placeholder' => 'Please describe any allergies or special dietary requirements...',
        'communication_preferences' => 'Communication Preferences',
        'email_notifications' => 'Email Notifications',
        'sms_notifications' => 'SMS Notifications',
        'promotional_offers' => 'Promotional Offers',
        
        // Form Fields - Notes
        'notes' => 'Notes',
        'customer_notes' => 'Customer Notes',
        'notes_placeholder' => 'Add any notes about the customer\'s preferences, special occasions, etc...',
        'internal_notes' => 'Internal Notes',
        'internal_notes_placeholder' => 'Add internal staff notes (not visible to customer)...',
        
        // Customer Details Modal
        'customer_details' => 'Customer Details',
        'personal_information' => 'Personal Information',
        'full_name' => 'Full Name',
        'contact_information' => 'Contact Information',
        'visit_history' => 'Visit History',
        'total_visits' => 'Total Visits',
        'average_per_visit' => 'Average per Visit',
        'visit_frequency' => 'Visit Frequency',
        
        // Empty States
        'no_customers_found' => 'No Customers Found',
        'no_customers_message' => 'No customers match your current search and filter criteria. Try adjusting your filters or add a new customer.',
        'loading_customers' => 'Loading customers...',
        
        // Messages
        'customer_added' => 'Customer added successfully',
        'customer_updated' => 'Customer updated successfully',
        'customer_deleted' => 'Customer deleted successfully',
        'customers_imported' => 'Customers imported successfully',
        'customers_exported' => 'Customers exported successfully',
        'delete_confirmation' => 'Are you sure you want to delete this customer? This action cannot be undone.',
        'import_coming_soon' => 'Import functionality coming soon',
        
        // Validation
        'first_name_required' => 'First name is required',
        'last_name_required' => 'Last name is required',
        'email_invalid' => 'Please enter a valid email address',
        'phone_invalid' => 'Please enter a valid phone number',
    ],
    
    // Loyalty Program
    'loyalty' => [
        'title' => 'Loyalty Program',
        'subtitle' => 'Manage customer loyalty points, rewards, and membership tiers',
        'nav_title' => 'Loyalty Program',
    ],
    
    // Reservations
    'reservations' => [
        'title' => 'Reservations',
        'subtitle' => 'Manage table bookings and reservation system',
        'nav_title' => 'Reservations',
    ],
    
    // Customer Analytics
    'analytics' => [
        'title' => 'Customer Analytics',
        'subtitle' => 'Analyze customer behavior, trends, and insights',
        'nav_title' => 'Customer Analytics',
    ],
    
    // Feedback & Reviews
    'feedback' => [
        'title' => 'Feedback & Reviews',
        'subtitle' => 'Collect and manage customer feedback and reviews',
        'nav_title' => 'Feedback & Reviews',
    ],
];