@extends('layouts.admin')

@section('title', __('finance.sales_reports.title') . ' - ' . config('app.name'))
@section('page_title', __('finance.sales_reports.title'))

@push('styles')
    @vite('resources/css/admin/sales.css')
@endpush

@push('scripts')
    @vite('resources/js/admin/sales.js')
@endpush

@section('content')
<div class="sales-reports-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('finance.sales_reports.title') }}</h1>
                <p class="page-subtitle">{{ __('finance.sales_reports.subtitle') }}</p>
            </div>
            <div class="page-actions">
                <div class="date-filter-buttons">
                    <button type="button" class="btn btn-secondary date-filter-btn active" data-period="today">
                        {{ __('finance.sales_reports.today') }}
                    </button>
                    <button type="button" class="btn btn-secondary date-filter-btn" data-period="week">
                        {{ __('finance.sales_reports.this_week') }}
                    </button>
                    <button type="button" class="btn btn-secondary date-filter-btn" data-period="month">
                        {{ __('finance.sales_reports.this_month') }}
                    </button>
                    <button type="button" class="btn btn-secondary date-filter-btn" data-period="custom">
                        {{ __('finance.sales_reports.custom_range') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Summary Cards -->
    <div class="kpi-cards-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <!-- Total Sales Card -->
        <div class="card kpi-card" data-kpi="total-sales">
            <div class="card-body">
                <div class="kpi-header">
                    <div class="kpi-icon">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="kpi-label">{{ __('finance.sales_reports.total_sales') }}</span>
                </div>
                <div class="kpi-value loading-skeleton" data-value="total-sales">$0.00</div>
                <div class="kpi-change positive">
                    <svg class="change-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7H7"></path>
                    </svg>
                    <span>+12.5%</span>
                </div>
            </div>
        </div>

        <!-- Number of Transactions Card -->
        <div class="card kpi-card" data-kpi="transactions">
            <div class="card-body">
                <div class="kpi-header">
                    <div class="kpi-icon">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <span class="kpi-label">{{ __('finance.sales_reports.transactions') }}</span>
                </div>
                <div class="kpi-value loading-skeleton" data-value="transactions">0</div>
                <div class="kpi-change positive">
                    <svg class="change-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7H7"></path>
                    </svg>
                    <span>+8.3%</span>
                </div>
            </div>
        </div>

        <!-- Average Order Value Card -->
        <div class="card kpi-card" data-kpi="avg-order">
            <div class="card-body">
                <div class="kpi-header">
                    <div class="kpi-icon">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <span class="kpi-label">{{ __('finance.sales_reports.avg_order_value') }}</span>
                </div>
                <div class="kpi-value loading-skeleton" data-value="avg-order">$0.00</div>
                <div class="kpi-change negative">
                    <svg class="change-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 7l-9.2 9.2M7 7v10h10"></path>
                    </svg>
                    <span>-2.1%</span>
                </div>
            </div>
        </div>

        <!-- Top Selling Item Card -->
        <div class="card kpi-card" data-kpi="top-item">
            <div class="card-body">
                <div class="kpi-header">
                    <div class="kpi-icon">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </div>
                    <span class="kpi-label">{{ __('finance.sales_reports.top_selling_item') }}</span>
                </div>
                <div class="kpi-value loading-skeleton" data-value="top-item">{{ __('finance.sales_reports.no_data') }}</div>
                <div class="kpi-meta">
                    <span class="top-item-sales">0 {{ __('finance.sales_reports.sold') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Panel -->
    <div class="card filters-panel" data-collapsed="true">
        <div class="card-header">
            <button type="button" class="filters-toggle" aria-expanded="false" aria-controls="filters-content">
                <h3 class="card-title">{{ __('finance.sales_reports.filters') }}</h3>
                <svg class="chevron-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </div>
        <div class="filters-content" id="filters-content" style="display: none;">
            <div class="card-body">
                <div class="filters-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
                    <!-- Payment Method Filter -->
                    <div class="filter-group">
                        <label for="payment-method-filter" class="filter-label">
                            {{ __('finance.sales_reports.payment_method') }}
                        </label>
                        <select id="payment-method-filter" class="form-select" multiple>
                            <option value="cash">{{ __('sales.cash') }}</option>
                            <option value="card">{{ __('sales.card') }}</option>
                            <option value="bank_transfer">{{ __('sales.bank_transfer') }}</option>
                            <option value="mobile_money">{{ __('sales.mobile_money') }}</option>
                        </select>
                    </div>

                    <!-- Staff Filter -->
                    <div class="filter-group">
                        <label for="staff-filter" class="filter-label">
                            {{ __('finance.sales_reports.staff') }}
                        </label>
                        <select id="staff-filter" class="form-select">
                            <option value="">{{ __('finance.sales_reports.all_staff') }}</option>
                            <option value="1">John Doe</option>
                            <option value="2">Jane Smith</option>
                            <option value="3">Mike Johnson</option>
                        </select>
                    </div>

                    <!-- POS Terminal Filter -->
                    <div class="filter-group">
                        <label for="terminal-filter" class="filter-label">
                            {{ __('finance.sales_reports.pos_terminal') }}
                        </label>
                        <select id="terminal-filter" class="form-select">
                            <option value="">{{ __('finance.sales_reports.all_terminals') }}</option>
                            <option value="terminal-1">Terminal 1</option>
                            <option value="terminal-2">Terminal 2</option>
                            <option value="terminal-3">Terminal 3</option>
                        </select>
                    </div>

                    <!-- Search Bar -->
                    <div class="filter-group">
                        <label for="search-filter" class="filter-label">
                            {{ __('finance.sales_reports.search') }}
                        </label>
                        <input type="text" id="search-filter" class="form-input" 
                               placeholder="{{ __('finance.sales_reports.search_placeholder') }}">
                    </div>
                </div>

                <!-- Custom Date Range -->
                <div class="custom-date-range" style="display: none;">
                    <div class="date-range-grid grid grid-cols-1 md:grid-cols-2">
                        <div class="filter-group">
                            <label for="start-date" class="filter-label">
                                {{ __('finance.sales_reports.start_date') }}
                            </label>
                            <input type="date" id="start-date" class="form-input">
                        </div>
                        <div class="filter-group">
                            <label for="end-date" class="filter-label">
                                {{ __('finance.sales_reports.end_date') }}
                            </label>
                            <input type="date" id="end-date" class="form-input">
                        </div>
                    </div>
                </div>

                <!-- Filter Actions -->
                <div class="filter-actions">
                    <button type="button" class="btn btn-primary apply-filters">
                        {{ __('finance.sales_reports.apply_filters') }}
                    </button>
                    <button type="button" class="btn btn-secondary clear-filters">
                        {{ __('finance.sales_reports.clear_filters') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Trends Chart -->
    <div class="card chart-container mb-8">
        <div class="card-header">
            <h3 class="card-title">{{ __('finance.sales_reports.sales_trends') }}</h3>
            <div class="chart-controls">
                <div class="chart-view-toggle">
                    <button type="button" class="btn btn-secondary chart-view-btn active" data-view="daily">
                        {{ __('finance.sales_reports.daily') }}
                    </button>
                    <button type="button" class="btn btn-secondary chart-view-btn" data-view="weekly">
                        {{ __('finance.sales_reports.weekly') }}
                    </button>
                    <button type="button" class="btn btn-secondary chart-view-btn" data-view="monthly">
                        {{ __('finance.sales_reports.monthly') }}
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="chart-wrapper">
                <div class="chart-placeholder loading-skeleton">
                    <div class="chart-loading">
                        <svg class="chart-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <p>{{ __('finance.sales_reports.loading_chart') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Table -->
    <div class="card sales-table-container">
        <div class="card-header">
            <h3 class="card-title">{{ __('finance.sales_reports.sales_data') }}</h3>
            <div class="table-actions">
                <button type="button" class="btn btn-secondary export-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ __('finance.sales_reports.export') }}
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-wrapper">
                <table class="sales-table" role="table" aria-label="{{ __('finance.sales_reports.sales_data') }}">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('finance.sales_reports.order_id') }}</th>
                            <th scope="col">{{ __('finance.sales_reports.date_time') }}</th>
                            <th scope="col">{{ __('finance.sales_reports.staff') }}</th>
                            <th scope="col">{{ __('finance.sales_reports.payment_method') }}</th>
                            <th scope="col">{{ __('finance.sales_reports.total_amount') }}</th>
                        </tr>
                    </thead>
                    <tbody class="sales-table-body">
                        <!-- Loading skeleton rows -->
                        <tr class="loading-row">
                            <td><div class="skeleton-text"></div></td>
                            <td><div class="skeleton-text"></div></td>
                            <td><div class="skeleton-text"></div></td>
                            <td><div class="skeleton-badge"></div></td>
                            <td><div class="skeleton-text"></div></td>
                        </tr>
                        <tr class="loading-row">
                            <td><div class="skeleton-text"></div></td>
                            <td><div class="skeleton-text"></div></td>
                            <td><div class="skeleton-text"></div></td>
                            <td><div class="skeleton-badge"></div></td>
                            <td><div class="skeleton-text"></div></td>
                        </tr>
                        <tr class="loading-row">
                            <td><div class="skeleton-text"></div></td>
                            <td><div class="skeleton-text"></div></td>
                            <td><div class="skeleton-text"></div></td>
                            <td><div class="skeleton-badge"></div></td>
                            <td><div class="skeleton-text"></div></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            <div class="empty-state" style="display: none;">
                <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3>{{ __('finance.sales_reports.no_sales_found') }}</h3>
                <p>{{ __('finance.sales_reports.no_sales_description') }}</p>
            </div>

            <!-- Error State -->
            <div class="error-state" style="display: none;">
                <svg class="error-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3>{{ __('finance.sales_reports.error_loading') }}</h3>
                <p>{{ __('finance.sales_reports.error_description') }}</p>
                <button type="button" class="btn btn-primary retry-btn">
                    {{ __('finance.sales_reports.retry') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Order Details Drawer -->
<div class="order-drawer" id="order-drawer" style="display: none;" role="dialog" aria-labelledby="drawer-title" aria-hidden="true">
    <div class="drawer-overlay"></div>
    <div class="drawer-content">
        <div class="drawer-header">
            <h2 id="drawer-title" class="drawer-title">{{ __('finance.sales_reports.order_details') }}</h2>
            <button type="button" class="drawer-close" aria-label="{{ __('finance.sales_reports.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="drawer-body">
            <div class="order-details-content">
                <!-- Order details will be loaded here -->
                <div class="loading-skeleton">
                    <div class="skeleton-text"></div>
                    <div class="skeleton-text"></div>
                    <div class="skeleton-text"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection