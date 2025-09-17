<?php

return [
    // Navigation
    'nav_title' => 'Finance Management',
    
    // Tips Management
    'tips' => [
        'title' => 'Tip Management',
        'subtitle' => 'Manage tip collection, distribution, and staff allocations',
        'nav_title' => 'Tips',
        
        // Statistics
        'total_tips_today' => 'Total Tips Today',
        'pending_distribution' => 'Pending Distribution',
        'distributed_today' => 'Distributed Today',
        'avg_per_staff' => 'Average per Staff',
        'transactions' => 'transactions',
        'staff_members' => 'staff members',
        'per_hour' => 'per hour',
        
        // Distribution Rules
        'distribution_rules' => 'Distribution Rules',
        'edit_rules' => 'Edit Rules',
        'active' => 'Active',
        'inactive' => 'Inactive',
        
        // Rule Types
        'direct_to_server' => 'Direct to Server',
        'shared_equally' => 'Shared Equally',
        'custom_distribution' => 'Custom Distribution',
        'direct_description' => 'Tips go directly to the receiving staff member',
        'shared_description' => 'Tips are shared between front-of-house and kitchen staff',
        'custom_description' => 'Tips are distributed based on custom percentages by role',
        'to_receiver' => 'to receiver',
        
        // Staff Roles
        'front_of_house' => 'Front of House',
        'kitchen_staff' => 'Kitchen Staff',
        'servers' => 'Servers',
        'bartenders' => 'Bartenders',
        'kitchen' => 'Kitchen',
        'management' => 'Management',
        
        // Time Filters
        'all_shifts' => 'All Shifts',
        'morning_shift' => 'Morning Shift',
        'afternoon_shift' => 'Afternoon Shift',
        'evening_shift' => 'Evening Shift',
        'night_shift' => 'Night Shift',
        'custom_range' => 'Custom Range',
        
        // Status
        'all_statuses' => 'All Statuses',
        'pending' => 'Pending',
        'distributed' => 'Distributed',
        'disputed' => 'Disputed',
        
        // Table Headers
        'tip_transactions' => 'Tip Transactions',
        'transaction_id' => 'Transaction ID',
        'amount' => 'Amount',
        'payment_method' => 'Payment Method',
        'received_by' => 'Received By',
        'shift' => 'Shift',
        'time' => 'Time',
        
        // Payment Methods
        'cash' => 'Cash',
        'card' => 'Card',
        'mobile_payment' => 'Mobile Payment',
        
        // Staff Distribution
        'staff_distribution' => 'Staff Distribution',
        
        // Actions
        'tip_calculator' => 'Tip Calculator',
        'distribute_tips' => 'Distribute Tips',
        'export_tips' => 'Export Tips',
        'bulk_distribute' => 'Bulk Distribute',
        'process_tip' => 'Process Tip',
        'save_rules' => 'Save Rules',
        
        // Calculator Modal
        'tip_details' => 'Tip Details',
        'tip_amount' => 'Tip Amount',
        'select_staff' => 'Select Staff',
        'distribution_rule' => 'Distribution Rule',
        'staff_on_shift' => 'Staff on Shift',
        'distribution_preview' => 'Distribution Preview',
        
        // Rules Modal
        'edit_distribution_rules' => 'Edit Distribution Rules',
        'shared_rules' => 'Shared Rules',
        'custom_rules' => 'Custom Rules',
        'front_of_house_percentage' => 'Front of House Percentage',
        'kitchen_percentage' => 'Kitchen Percentage',
        'total_percentage' => 'Total Percentage',
        'total' => 'Total',
    ],
    
    // Sales Reports
    'sales_reports' => [
        'title' => 'Sales Dashboard',
        'nav_title' => 'Sales',
    ],
    
    // Financial Reports
    'financial_reports' => [
        'title' => 'Financial Reports',
        'subtitle' => 'Generate comprehensive financial reports and analysis',
        'nav_title' => 'Reports',
    ],
    
    // Settings
    'settings' => [
        'title' => 'Finance Settings',
        'subtitle' => 'Configure financial preferences and accounting settings',
        'nav_title' => 'Settings',
    ],
    
    // Budgeting
    'budgeting' => [
        'title' => 'Budgeting',
        'subtitle' => 'Plan and manage restaurant budgets and forecasts',
        'nav_title' => 'Budgeting',
    ],
    
    // Expenses
    'expenses' => [
        'title' => 'Expenses',
        'subtitle' => 'Track and manage restaurant expenses and costs',
        'nav_title' => 'Expenses',
    ],
    
    // Common Finance Terms
    'export' => 'Export',
    'add_expense' => 'Add Expense',
    'total_expenses' => 'Total Expenses',
    'this_month' => 'This Month',
    'pending_approvals' => 'Pending Approvals',
    'pending' => 'Pending',
    'top_category' => 'Top Category',
    'no_data' => 'No Data',
    'spent' => 'Spent',
    'filters' => 'Filters',
    'date_range' => 'Date Range',
    'today' => 'Today',
    'this_week' => 'This Week',
    'this_quarter' => 'This Quarter',
    'this_year' => 'This Year',
    'custom_range' => 'Custom Range',
    'category' => 'Category',
    'food_supplies' => 'Food Supplies',
    'utilities' => 'Utilities',
    'rent' => 'Rent',
    'marketing' => 'Marketing',
    'equipment' => 'Equipment',
    'maintenance' => 'Maintenance',
    'other' => 'Other',
    'status' => 'Status',
    'all_statuses' => 'All Statuses',
    'approved' => 'Approved',
    'paid' => 'Paid',
    'rejected' => 'Rejected',
    'search' => 'Search',
    'search_expenses_placeholder' => 'Search by description, vendor, or reference...',
    'start_date' => 'Start Date',
    'end_date' => 'End Date',
    'apply_filters' => 'Apply Filters',
    'clear_filters' => 'Clear Filters',
    'expense_records' => 'Expense Records',
    'table_view' => 'Table View',
    'card_view' => 'Card View',
    'date' => 'Date',
    'description' => 'Description',
    'amount' => 'Amount',
    'actions' => 'Actions',
    'no_expenses_found' => 'No Expenses Found',
    'no_expenses_description' => 'You haven\'t added any expenses yet. Start by adding your first expense.',
    'add_first_expense' => 'Add First Expense',
    'error_loading' => 'Error Loading Expenses',
    'error_description' => 'There was an error loading your expenses. Please try again.',
    'retry' => 'Retry',
    'close' => 'Close',
    'expense_description_placeholder' => 'Enter expense description...',
    'select_category' => 'Select Category',
    'payment_method' => 'Payment Method',
    'select_payment_method' => 'Select Payment Method',
    'cash' => 'Cash',
    'card' => 'Card',
    'bank_transfer' => 'Bank Transfer',
    'check' => 'Check',
    'notes' => 'Notes',
    'expense_notes_placeholder' => 'Additional notes about this expense...',
    'receipt' => 'Receipt',
    'upload_receipt' => 'Drop files here or click to upload',
    'supported_formats' => 'PNG, JPG, PDF up to 10MB',
    'cancel' => 'Cancel',
    'save_expense' => 'Save Expense',
    
    // Reports
    'reports' => [
        'title' => 'Financial Reports',
        'subtitle' => 'Generate comprehensive financial reports and analysis',
        'nav_title' => 'Reports',
    ],
    
    // Settings
    'settings' => [
        'title' => 'Finance Settings',
        'subtitle' => 'Configure financial preferences and accounting settings',
        'nav_title' => 'Settings',
    ],
];