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

        // Actions
        'program_settings' => 'Program Settings',
        'export_data' => 'Export Data',
        'add_reward' => 'Add Reward',
        'edit_reward' => 'Edit Reward',
        'save_reward' => 'Save Reward',
        'cancel' => 'Cancel',
        'close' => 'Close',
        'edit_tiers' => 'Edit Tiers',
        'adjust_points' => 'Adjust Points',
        'save_adjustment' => 'Save Adjustment',

        // Statistics
        'total_members' => 'Total Members',
        'active_members' => 'Active Members',
        'points_issued' => 'Points Issued',
        'rewards_redeemed' => 'Rewards Redeemed',

        // Tabs
        'members' => 'Members',
        'rewards' => 'Rewards',
        'tiers' => 'Tiers',
        'transactions' => 'Transactions',

        // Members Section
        'search_members' => 'Search members by name or email...',
        'all_tiers' => 'All Tiers',
        'all_status' => 'All Status',
        'clear_filters' => 'Clear Filters',
        'member_details' => 'Member Details',

        // Tiers
        'bronze' => 'Bronze',
        'silver' => 'Silver',
        'gold' => 'Gold',
        'platinum' => 'Platinum',
        'membership_tiers' => 'Membership Tiers',

        // Status
        'active' => 'Active',
        'inactive' => 'Inactive',

        // Rewards Section
        'available_rewards' => 'Available Rewards',
        'reward_name' => 'Reward Name',
        'reward_type' => 'Reward Type',
        'select_type' => 'Select Type',
        'discount' => 'Discount',
        'free_item' => 'Free Item',
        'cashback' => 'Cashback',
        'upgrade' => 'Upgrade',
        'points_required' => 'Points Required',
        'reward_value' => 'Reward Value',
        'description' => 'Description',
        'expiry_days' => 'Expiry Days',
        'status' => 'Status',

        // Transactions Section
        'recent_transactions' => 'Recent Transactions',
        'all_types' => 'All Types',
        'points_earned' => 'Points Earned',
        'points_redeemed' => 'Points Redeemed',
        'points_expired' => 'Points Expired',
        'from_date' => 'From Date',
        'to_date' => 'To Date',
        'date' => 'Date',
        'customer' => 'Customer',
        'type' => 'Type',
        'points' => 'Points',
        'balance' => 'Balance',

        // Points Adjustment
        'current_points' => 'Current Points',
        'adjustment_type' => 'Adjustment Type',
        'add_points' => 'Add Points',
        'subtract_points' => 'Subtract Points',
        'set_points' => 'Set Points',
        'points_amount' => 'Points Amount',
        'reason' => 'Reason',
        'reason_placeholder' => 'Enter reason for points adjustment...',

        // Messages
        'reward_added' => 'Reward added successfully',
        'reward_updated' => 'Reward updated successfully',
        'reward_deleted' => 'Reward deleted successfully',
        'points_adjusted' => 'Points adjusted successfully',
        'data_exported' => 'Loyalty data exported successfully',
        'settings_coming_soon' => 'Program settings feature coming soon',
        'tiers_coming_soon' => 'Tier editing feature coming soon',
        'delete_reward_confirmation' => 'Are you sure you want to delete this reward? This action cannot be undone.',
        'fill_required_fields' => 'Please fill in all required fields',

        // Validation
        'reward_name_required' => 'Reward name is required',
        'reward_type_required' => 'Reward type is required',
        'points_required_invalid' => 'Please enter a valid points amount',
        'reason_required' => 'Reason is required',
    ],

    // Reservations
    'reservations' => [
        'title' => 'Reservations',
        'subtitle' => 'Manage table bookings and reservation system',
        'nav_title' => 'Reservations',

        // Actions
        'table_layout' => 'Table Layout',
        'export_reservations' => 'Export Reservations',
        'add_reservation' => 'Add Reservation',
        'edit_reservation' => 'Edit Reservation',
        'save_reservation' => 'Save Reservation',
        'cancel' => 'Cancel',
        'close' => 'Close',
        'add_table' => 'Add Table',
        'save_table' => 'Save Table',
        'edit_layout' => 'Edit Layout',
        'add_to_waitlist' => 'Add to Waitlist',
        'confirm_reservation' => 'Confirm Reservation',
        'cancel_reservation' => 'Cancel Reservation',

        // Statistics
        'today_reservations' => 'Today\'s Reservations',
        'upcoming_reservations' => 'Upcoming Reservations',
        'table_occupancy' => 'Table Occupancy',
        'no_shows' => 'No Shows',

        // Views & Navigation
        'calendar_view' => 'Calendar View',
        'list_view' => 'List View',
        'table_management' => 'Table Management',
        'waitlist' => 'Waitlist',
        'current_month' => 'Current Month',
        'today' => 'Today',
        'month_view' => 'Month View',
        'week_view' => 'Week View',
        'day_view' => 'Day View',

        // Search & Filters
        'search_reservations' => 'Search reservations by name, phone, or email...',
        'all_status' => 'All Status',
        'all_tables' => 'All Tables',
        'filter_date' => 'Filter by Date',
        'clear_filters' => 'Clear Filters',

        // Status
        'confirmed' => 'Confirmed',
        'pending' => 'Pending',
        'seated' => 'Seated',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        'no_show' => 'No Show',

        // Table Headers
        'date_time' => 'Date & Time',
        'customer' => 'Customer',
        'party_size' => 'Party Size',
        'table' => 'Table',
        'status' => 'Status',
        'special_requests' => 'Special Requests',
        'actions' => 'Actions',

        // Form Fields - Reservation Details
        'reservation_details' => 'Reservation Details',
        'customer_info' => 'Customer Information',
        'preferences' => 'Preferences',
        'date' => 'Date',
        'time' => 'Time',
        'select_time' => 'Select Time',
        'select_party_size' => 'Select Party Size',
        'person' => 'person',
        'people' => 'people',
        'preferred_table' => 'Preferred Table',
        'auto_assign' => 'Auto Assign',
        'special_requests_placeholder' => 'Any special requests or notes...',

        // Form Fields - Customer Info
        'customer_name' => 'Customer Name',
        'phone_number' => 'Phone Number',
        'email_address' => 'Email Address',
        'customer_notes' => 'Customer Notes',
        'customer_notes_placeholder' => 'Additional notes about the customer...',

        // Form Fields - Preferences
        'seating_preferences' => 'Seating Preferences',
        'window_seat' => 'Window Seat',
        'booth' => 'Booth',
        'quiet_area' => 'Quiet Area',
        'outdoor_seating' => 'Outdoor Seating',
        'occasion' => 'Occasion',
        'regular_dining' => 'Regular Dining',
        'birthday' => 'Birthday',
        'anniversary' => 'Anniversary',
        'business_meeting' => 'Business Meeting',
        'celebration' => 'Celebration',
        'dietary_requirements' => 'Dietary Requirements',
        'dietary_requirements_placeholder' => 'Any dietary restrictions or allergies...',

        // Table Management
        'restaurant_layout' => 'Restaurant Layout',
        'tables_list' => 'Tables List',
        'table_number' => 'Table Number',
        'capacity' => 'Capacity',
        'select_capacity' => 'Select Capacity',
        'table_type' => 'Table Type',
        'regular_table' => 'Regular Table',
        'bar_seating' => 'Bar Seating',
        'outdoor_table' => 'Outdoor Table',
        'private_dining' => 'Private Dining',
        'location' => 'Location',
        'main_dining' => 'Main Dining',
        'window_area' => 'Window Area',
        'patio' => 'Patio',
        'bar_area' => 'Bar Area',
        'private_room' => 'Private Room',
        'table_notes' => 'Table Notes',
        'table_notes_placeholder' => 'Any notes about this table...',

        // Table Status
        'available' => 'Available',
        'occupied' => 'Occupied',
        'reserved' => 'Reserved',
        'maintenance' => 'Maintenance',

        // Waitlist
        'current_waitlist' => 'Current Waitlist',
        'estimated_wait' => 'Estimated Wait',
        'seat_now' => 'Seat Now',
        'remove' => 'Remove',

        // Messages
        'reservation_added' => 'Reservation added successfully',
        'reservation_updated' => 'Reservation updated successfully',
        'reservation_cancelled' => 'Reservation cancelled successfully',
        'reservation_confirmed' => 'Reservation confirmed successfully',
        'table_added' => 'Table added successfully',
        'table_updated' => 'Table updated successfully',
        'customer_seated' => 'Customer seated successfully',
        'customer_added_waitlist' => 'Customer added to waitlist',
        'customer_removed_waitlist' => 'Customer removed from waitlist',
        'reservations_exported' => 'Reservations exported successfully',
        'layout_coming_soon' => 'Layout editing feature coming soon',
        'table_layout_coming_soon' => 'Table layout feature coming soon',
        'cancel_reservation_confirmation' => 'Are you sure you want to cancel this reservation?',
        'remove_waitlist_confirmation' => 'Remove customer from waitlist?',

        // Validation
        'customer_name_required' => 'Customer name is required',
        'phone_required' => 'Phone number is required',
        'date_required' => 'Date is required',
        'time_required' => 'Time is required',
        'party_size_required' => 'Party size is required',
        'table_number_required' => 'Table number is required',
        'capacity_required' => 'Capacity is required',
    ],

    // Customer Analytics
    'analytics' => [
        'title' => 'Customer Analytics',
        'subtitle' => 'Track customer flow, analyze service patterns, and generate business insights',
        'nav_title' => 'Customer Analytics',

        // Actions
        'export_report' => 'Export Report',
        'record_service' => 'Record Service',
        'record_now' => 'Record Now',
        'generate_report' => 'Generate Report',
        'refresh' => 'Refresh',
        'cancel' => 'Cancel',
        'close' => 'Close',

        // Statistics
        'customers_today' => 'Customers Today',
        'customers_this_week' => 'This Week',
        'customers_this_month' => 'This Month',
        'average_daily' => 'Daily Average',

        // Time Periods
        'time_period' => 'Time Period',
        'today' => 'Today',
        'this_week' => 'This Week',
        'this_month' => 'This Month',
        'this_quarter' => 'This Quarter',
        'this_year' => 'This Year',
        'custom_range' => 'Custom Range',
        'to' => 'to',
        'last_7_days' => 'Last 7 Days',
        'last_30_days' => 'Last 30 Days',
        'last_90_days' => 'Last 90 Days',

        // Tabs
        'dashboard' => 'Dashboard',
        'service_tracking' => 'Service Tracking',
        'reports' => 'Reports',
        'insights' => 'Business Insights',

        // Charts
        'daily_customer_flow' => 'Daily Customer Flow',
        'hourly_distribution' => 'Hourly Distribution',
        'service_performance' => 'Service Performance by Staff',
        'table_utilization' => 'Table Utilization',

        // Key Metrics
        'key_metrics' => 'Key Performance Metrics',
        'peak_hour' => 'Peak Hour',
        'busiest_time' => 'Busiest time of day',
        'avg_service_time' => 'Avg Service Time',
        'minutes' => 'min',
        'per_customer' => 'per customer',
        'table_turnover' => 'Table Turnover',
        'per_day' => 'per day',
        'customer_satisfaction' => 'Customer Satisfaction',
        'based_on_service' => 'based on service quality',

        // Service Tracking
        'quick_service_record' => 'Quick Service Recording',
        'number_of_customers' => 'Number of Customers',
        'table' => 'Table',
        'waiter' => 'Waiter/Server',
        'select_table' => 'Select Table',
        'select_waiter' => 'Select Waiter',
        'service_time' => 'Service Time',
        'notes' => 'Notes',
        'service_notes_placeholder' => 'Any additional notes about this service...',
        'recent_service_records' => 'Recent Service Records',

        // Reports
        'detailed_reports' => 'Detailed Reports',
        'report_type' => 'Report Type',
        'daily_summary' => 'Daily Summary',
        'weekly_summary' => 'Weekly Summary',
        'monthly_summary' => 'Monthly Summary',
        'custom_period' => 'Custom Period',
        'all_waiters' => 'All Waiters',
        'all_tables' => 'All Tables',

        // Insights
        'business_insights' => 'Business Insights & Analytics',
        'customer_trends' => 'Customer Trends',
        'staff_performance' => 'Staff Performance',
        'recommendations' => 'Recommendations',
        'forecasting' => 'Forecasting',

        // Messages
        'service_recorded' => 'Service recorded successfully',
        'service_updated' => 'Service record updated successfully',
        'service_deleted' => 'Service record deleted successfully',
        'report_generated' => 'Report generated successfully',
        'analytics_exported' => 'Analytics data exported successfully',
        'charts_refreshed' => 'Charts refreshed successfully',
        'select_table_waiter' => 'Please select table and waiter',
        'delete_service_confirmation' => 'Are you sure you want to delete this service record?',
        'edit_coming_soon' => 'Edit functionality coming soon',

        // Validation
        'customers_required' => 'Number of customers is required',
        'table_required' => 'Table selection is required',
        'waiter_required' => 'Waiter selection is required',
        'service_time_required' => 'Service time is required',
    ],

    // Feedback & Reviews
    'feedback' => [
        'title' => 'Feedback & Reviews',
        'subtitle' => 'Collect and manage customer feedback and reviews',
        'nav_title' => 'Feedback & Reviews',

        // Actions
        'export_reviews' => 'Export Reviews',
        'add_review' => 'Add Review',
        'edit_review' => 'Edit Review',
        'save_review' => 'Save Review',
        'cancel' => 'Cancel',
        'close' => 'Close',
        'approve' => 'Approve',
        'reject' => 'Reject',
        'edit' => 'Edit',
        'generate_report' => 'Generate Report',
        'refresh' => 'Refresh',

        // Statistics
        'overall_rating' => 'Overall Rating',
        'total_reviews' => 'Total Reviews',
        'food_rating' => 'Food Rating',
        'service_rating' => 'Service Rating',
        'atmosphere_rating' => 'Atmosphere Rating',

        // Tabs
        'reviews' => 'Reviews',
        'analytics' => 'Analytics',
        'reports' => 'Reports',
        'insights' => 'Customer Insights',

        // Search & Filters
        'search_reviews' => 'Search reviews by customer name, email, or comment...',
        'all_ratings' => 'All Ratings',
        'all_categories' => 'All Categories',
        'all_status' => 'All Status',
        'filter_date' => 'Filter by Date',
        'clear_filters' => 'Clear Filters',
        'stars' => 'Stars',
        'star' => 'Star',

        // Categories
        'food' => 'Food',
        'service' => 'Service',
        'atmosphere' => 'Atmosphere',

        // Status
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',

        // Time Periods
        'time_period' => 'Time Period',
        'this_week' => 'This Week',
        'this_month' => 'This Month',
        'this_quarter' => 'This Quarter',
        'this_year' => 'This Year',
        'last_7_days' => 'Last 7 Days',
        'last_30_days' => 'Last 30 Days',
        'last_90_days' => 'Last 90 Days',

        // Charts
        'rating_distribution' => 'Rating Distribution',
        'category_ratings' => 'Category Ratings Comparison',
        'reviews_over_time' => 'Reviews Over Time',
        'sentiment_analysis' => 'Sentiment Analysis',

        // Reports
        'feedback_reports' => 'Feedback Reports',
        'report_type' => 'Report Type',
        'weekly_report' => 'Weekly Report',
        'monthly_report' => 'Monthly Report',
        'quarterly_report' => 'Quarterly Report',
        'custom_period' => 'Custom Period',
        'category' => 'Category',
        'rating' => 'Rating',

        // Insights
        'customer_insights' => 'Customer Insights & Analysis',
        'satisfaction_trends' => 'Satisfaction Trends',
        'improvement_areas' => 'Areas for Improvement',
        'common_keywords' => 'Common Keywords',
        'action_recommendations' => 'Action Recommendations',

        // Form Fields
        'customer_name' => 'Customer Name',
        'customer_email' => 'Customer Email',
        'review_date' => 'Review Date',
        'status' => 'Status',
        'ratings' => 'Ratings',
        'food_quality' => 'Food Quality',
        'service_quality' => 'Service Quality',
        'atmosphere_quality' => 'Atmosphere Quality',
        'comment' => 'Comment',
        'comment_placeholder' => 'Share your experience with us...',
        'review_details' => 'Review Details',

        // Messages
        'review_added' => 'Review added successfully',
        'review_updated' => 'Review updated successfully',
        'review_approved' => 'Review approved successfully',
        'review_rejected' => 'Review rejected successfully',
        'reviews_exported' => 'Reviews exported successfully',
        'report_generated' => 'Report generated successfully',
        'charts_refreshed' => 'Charts refreshed successfully',
        'reject_review_confirmation' => 'Are you sure you want to reject this review?',

        // Validation
        'customer_name_required' => 'Customer name is required',
        'rating_required' => 'At least one rating is required',
        'comment_required' => 'Comment is required',
    ],
];
