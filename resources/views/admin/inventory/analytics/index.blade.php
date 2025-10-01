@extends('layouts.admin')

@section('title', __('inventory.analytics.title') . ' - ' . config('app.name'))
@section('page_title', __('inventory.analytics.title'))

@push('styles')
@vite(['resources/css/admin/inventory-analytics.css'])
@endpush

@section('content')
<div class="analytics-container" x-data="analyticsPage()">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">{{ __('inventory.analytics.title') }}</h1>
            <p class="page-subtitle">{{ __('inventory.analytics.subtitle') }}</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-secondary" @click="exportData()">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                {{ __('inventory.analytics.filters.export_data') }}
            </button>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-section">
        <div class="filters-header">
            <button class="filters-toggle" @click="showFilters = !showFilters">
                <svg class="filter-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"/>
                </svg>
                {{ __('inventory.analytics.filters.date_range') }}
                <svg class="chevron" :class="{ 'rotate-180': showFilters }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div class="current-filters">
                <span class="filter-chip" x-text="selectedDateRange"></span>
                <span class="filter-chip" x-show="selectedCategory !== 'all'" x-text="selectedCategory"></span>
                <span class="filter-chip" x-show="selectedSupplier !== 'all'" x-text="selectedSupplier"></span>
            </div>
        </div>

        <div class="filters-content" x-show="showFilters" x-collapse>
            <div class="filter-group">
                <label>{{ __('inventory.analytics.filters.date_range') }}</label>
                <select x-model="selectedDateRange" @change="updateAnalytics()">
                    <option value="today">{{ __('inventory.analytics.date_ranges.today') }}</option>
                    <option value="this_week">{{ __('inventory.analytics.date_ranges.this_week') }}</option>
                    <option value="this_month">{{ __('inventory.analytics.date_ranges.this_month') }}</option>
                    <option value="custom">{{ __('inventory.analytics.date_ranges.custom') }}</option>
                </select>
            </div>

            <div class="filter-group">
                <label>{{ __('inventory.analytics.filters.category') }}</label>
                <select x-model="selectedCategory" @change="updateAnalytics()">
                    <option value="all">All Categories</option>
                    <option value="meat">{{ __('inventory.analytics.categories.meat') }}</option>
                    <option value="vegetables">{{ __('inventory.analytics.categories.vegetables') }}</option>
                    <option value="dairy">{{ __('inventory.analytics.categories.dairy') }}</option>
                    <option value="grains">{{ __('inventory.analytics.categories.grains') }}</option>
                    <option value="beverages">{{ __('inventory.analytics.categories.beverages') }}</option>
                    <option value="spices">{{ __('inventory.analytics.categories.spices') }}</option>
                    <option value="oils">{{ __('inventory.analytics.categories.oils') }}</option>
                    <option value="other">{{ __('inventory.analytics.categories.other') }}</option>
                </select>
            </div>

            <div class="filter-group">
                <label>{{ __('inventory.analytics.filters.supplier') }}</label>
                <select x-model="selectedSupplier" @change="updateAnalytics()">
                    <option value="all">All Suppliers</option>
                    <option value="fresh_farms">Fresh Farms Co.</option>
                    <option value="quality_meats">Quality Meats Ltd.</option>
                    <option value="dairy_direct">Dairy Direct</option>
                    <option value="spice_world">Spice World</option>
                    <option value="ocean_catch">Ocean Catch</option>
                </select>
            </div>

            <div class="filter-actions">
                <button type="button" class="btn btn-secondary" @click="clearFilters()">
                    {{ __('inventory.analytics.filters.clear_filters') }}
                </button>
                <button type="button" class="btn btn-primary" @click="applyFilters()">
                    {{ __('inventory.analytics.filters.apply_filters') }}
                </button>
            </div>
        </div>
    </div>

    <!-- KPI Summary Cards -->
    <div class="kpi-cards">
        <div class="kpi-card">
            <div class="kpi-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                </svg>
            </div>
            <div class="kpi-content">
                <div class="kpi-value" x-text="formatCurrency(totalInventoryValue)">$45,280</div>
                <div class="kpi-label">{{ __('inventory.analytics.kpi_cards.total_inventory_value') }}</div>
                <div class="kpi-change positive">
                    <span class="change-icon">↗</span>
                    <span>+12.5%</span>
                </div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
            <div class="kpi-content">
                <div class="kpi-value">5</div>
                <div class="kpi-label">{{ __('inventory.analytics.kpi_cards.top_usage_items') }}</div>
                <div class="kpi-sublabel">Tomatoes, Rice, Chicken, Oil, Onions</div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <div class="kpi-content">
                <div class="kpi-value">5</div>
                <div class="kpi-label">{{ __('inventory.analytics.kpi_cards.top_cost_items') }}</div>
                <div class="kpi-sublabel">Beef, Salmon, Cheese, Wine, Truffles</div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <div class="kpi-content">
                <div class="kpi-value">8.2%</div>
                <div class="kpi-label">{{ __('inventory.analytics.kpi_cards.waste_percentage') }}</div>
                <div class="kpi-change negative">
                    <span class="change-icon">↘</span>
                    <span>-2.1%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-section">
        <!-- Usage Trends Chart -->
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title-group">
                    <h3 class="chart-title">{{ __('inventory.analytics.charts.usage_trends') }}</h3>
                    <p class="chart-subtitle">{{ __('inventory.analytics.charts.usage_trends_subtitle') }}</p>
                </div>
                <div class="chart-controls">
                    <select class="chart-period" x-model="usagePeriod" @change="updateUsageChart()">
                        <option value="daily">{{ __('inventory.analytics.time_formats.daily') }}</option>
                        <option value="weekly">{{ __('inventory.analytics.time_formats.weekly') }}</option>
                        <option value="monthly">{{ __('inventory.analytics.time_formats.monthly') }}</option>
                    </select>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="usageTrendsChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Category Breakdown Chart -->
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title-group">
                    <h3 class="chart-title">{{ __('inventory.analytics.charts.category_breakdown') }}</h3>
                    <p class="chart-subtitle">{{ __('inventory.analytics.charts.category_breakdown_subtitle') }}</p>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="categoryBreakdownChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Waste vs Actual Chart -->
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title-group">
                    <h3 class="chart-title">{{ __('inventory.analytics.charts.waste_vs_actual') }}</h3>
                    <p class="chart-subtitle">{{ __('inventory.analytics.charts.waste_vs_actual_subtitle') }}</p>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="wasteVsActualChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Supplier Performance Chart -->
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title-group">
                    <h3 class="chart-title">{{ __('inventory.analytics.charts.supplier_performance') }}</h3>
                    <p class="chart-subtitle">{{ __('inventory.analytics.charts.supplier_performance_subtitle') }}</p>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="supplierPerformanceChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Detailed Tables Section -->
    <div class="tables-section">
        <!-- High-Usage Items Table -->
        <div class="table-card">
            <div class="card-header">
                <div class="header-content">
                    <h3 class="card-title">{{ __('inventory.analytics.high_usage_table.title') }}</h3>
                    <p class="card-subtitle">{{ __('inventory.analytics.high_usage_table.subtitle') }}</p>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table analytics-table">
                        <thead>
                            <tr>
                                <th>{{ __('inventory.analytics.high_usage_table.item') }}</th>
                                <th>{{ __('inventory.analytics.high_usage_table.qty_used') }}</th>
                                <th>{{ __('inventory.analytics.high_usage_table.avg_cost') }}</th>
                                <th>{{ __('inventory.analytics.high_usage_table.supplier') }}</th>
                                <th>{{ __('inventory.analytics.high_usage_table.trend') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="item-cell">
                                    <div class="item-info">
                                        <div class="item-name">Tomatoes</div>
                                        <div class="item-code">TOM-001</div>
                                    </div>
                                </td>
                                <td class="quantity-cell">
                                    <div class="quantity-info">
                                        <span class="quantity-amount">245</span>
                                        <span class="quantity-unit">kg</span>
                                    </div>
                                </td>
                                <td class="cost-cell">
                                    <div class="cost-info">
                                        <span class="cost-amount">$3.25</span>
                                        <span class="cost-unit">per kg</span>
                                    </div>
                                </td>
                                <td class="supplier-cell">
                                    <span class="supplier-name">Fresh Farms Co.</span>
                                </td>
                                <td class="trend-cell">
                                    <div class="trend-indicator trend-up">
                                        <span class="trend-icon">↗</span>
                                        <span class="trend-text">+15%</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="item-cell">
                                    <div class="item-info">
                                        <div class="item-name">Rice</div>
                                        <div class="item-code">RIC-001</div>
                                    </div>
                                </td>
                                <td class="quantity-cell">
                                    <div class="quantity-info">
                                        <span class="quantity-amount">180</span>
                                        <span class="quantity-unit">kg</span>
                                    </div>
                                </td>
                                <td class="cost-cell">
                                    <div class="cost-info">
                                        <span class="cost-amount">$2.80</span>
                                        <span class="cost-unit">per kg</span>
                                    </div>
                                </td>
                                <td class="supplier-cell">
                                    <span class="supplier-name">Quality Grains</span>
                                </td>
                                <td class="trend-cell">
                                    <div class="trend-indicator trend-stable">
                                        <span class="trend-icon">→</span>
                                        <span class="trend-text">0%</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="item-cell">
                                    <div class="item-info">
                                        <div class="item-name">Chicken Breast</div>
                                        <div class="item-code">CHK-001</div>
                                    </div>
                                </td>
                                <td class="quantity-cell">
                                    <div class="quantity-info">
                                        <span class="quantity-amount">125</span>
                                        <span class="quantity-unit">kg</span>
                                    </div>
                                </td>
                                <td class="cost-cell">
                                    <div class="cost-info">
                                        <span class="cost-amount">$8.50</span>
                                        <span class="cost-unit">per kg</span>
                                    </div>
                                </td>
                                <td class="supplier-cell">
                                    <span class="supplier-name">Quality Meats Ltd.</span>
                                </td>
                                <td class="trend-cell">
                                    <div class="trend-indicator trend-up">
                                        <span class="trend-icon">↗</span>
                                        <span class="trend-text">+8%</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Wastage Analysis Table -->
        <div class="table-card">
            <div class="card-header">
                <div class="header-content">
                    <h3 class="card-title">{{ __('inventory.analytics.wastage_table.title') }}</h3>
                    <p class="card-subtitle">{{ __('inventory.analytics.wastage_table.subtitle') }}</p>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table analytics-table">
                        <thead>
                            <tr>
                                <th>{{ __('inventory.analytics.wastage_table.item') }}</th>
                                <th>{{ __('inventory.analytics.wastage_table.qty_wasted') }}</th>
                                <th>{{ __('inventory.analytics.wastage_table.cost_wasted') }}</th>
                                <th>{{ __('inventory.analytics.wastage_table.waste_percentage') }}</th>
                                <th>{{ __('inventory.analytics.wastage_table.reason') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="item-cell">
                                    <div class="item-info">
                                        <div class="item-name">Lettuce</div>
                                        <div class="item-code">LET-001</div>
                                    </div>
                                </td>
                                <td class="quantity-cell">
                                    <div class="quantity-info">
                                        <span class="quantity-amount waste">15</span>
                                        <span class="quantity-unit">kg</span>
                                    </div>
                                </td>
                                <td class="cost-cell">
                                    <div class="cost-info">
                                        <span class="cost-amount waste">$45.00</span>
                                    </div>
                                </td>
                                <td class="percentage-cell">
                                    <div class="percentage-badge high">12.5%</div>
                                </td>
                                <td class="reason-cell">
                                    <span class="reason-text">Spoilage</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="item-cell">
                                    <div class="item-info">
                                        <div class="item-name">Bread</div>
                                        <div class="item-code">BRD-001</div>
                                    </div>
                                </td>
                                <td class="quantity-cell">
                                    <div class="quantity-info">
                                        <span class="quantity-amount waste">8</span>
                                        <span class="quantity-unit">loaves</span>
                                    </div>
                                </td>
                                <td class="cost-cell">
                                    <div class="cost-info">
                                        <span class="cost-amount waste">$24.00</span>
                                    </div>
                                </td>
                                <td class="percentage-cell">
                                    <div class="percentage-badge medium">8.3%</div>
                                </td>
                                <td class="reason-cell">
                                    <span class="reason-text">Expiration</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="item-cell">
                                    <div class="item-info">
                                        <div class="item-name">Milk</div>
                                        <div class="item-code">MLK-001</div>
                                    </div>
                                </td>
                                <td class="quantity-cell">
                                    <div class="quantity-info">
                                        <span class="quantity-amount waste">5</span>
                                        <span class="quantity-unit">L</span>
                                    </div>
                                </td>
                                <td class="cost-cell">
                                    <div class="cost-info">
                                        <span class="cost-amount waste">$15.00</span>
                                    </div>
                                </td>
                                <td class="percentage-cell">
                                    <div class="percentage-badge low">5.2%</div>
                                </td>
                                <td class="reason-cell">
                                    <span class="reason-text">Overstock</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Supplier Performance Table -->
        <div class="table-card">
            <div class="card-header">
                <div class="header-content">
                    <h3 class="card-title">{{ __('inventory.analytics.supplier_table.title') }}</h3>
                    <p class="card-subtitle">{{ __('inventory.analytics.supplier_table.subtitle') }}</p>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table analytics-table">
                        <thead>
                            <tr>
                                <th>{{ __('inventory.analytics.supplier_table.supplier') }}</th>
                                <th>{{ __('inventory.analytics.supplier_table.orders') }}</th>
                                <th>{{ __('inventory.analytics.supplier_table.on_time_percentage') }}</th>
                                <th>{{ __('inventory.analytics.supplier_table.avg_price_trend') }}</th>
                                <th>{{ __('inventory.analytics.supplier_table.rating') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="supplier-cell">
                                    <div class="supplier-info">
                                        <div class="supplier-name">Fresh Farms Co.</div>
                                        <div class="supplier-category">Vegetables & Fruits</div>
                                    </div>
                                </td>
                                <td class="orders-cell">
                                    <span class="orders-count">24</span>
                                </td>
                                <td class="percentage-cell">
                                    <div class="percentage-badge excellent">95%</div>
                                </td>
                                <td class="trend-cell">
                                    <div class="trend-indicator trend-down">
                                        <span class="trend-icon">↘</span>
                                        <span class="trend-text">-3%</span>
                                    </div>
                                </td>
                                <td class="rating-cell">
                                    <div class="rating-stars">
                                        <span class="star filled">★</span>
                                        <span class="star filled">★</span>
                                        <span class="star filled">★</span>
                                        <span class="star filled">★</span>
                                        <span class="star">★</span>
                                        <span class="rating-value">4.2</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="supplier-cell">
                                    <div class="supplier-info">
                                        <div class="supplier-name">Quality Meats Ltd.</div>
                                        <div class="supplier-category">Meat & Poultry</div>
                                    </div>
                                </td>
                                <td class="orders-cell">
                                    <span class="orders-count">18</span>
                                </td>
                                <td class="percentage-cell">
                                    <div class="percentage-badge good">88%</div>
                                </td>
                                <td class="trend-cell">
                                    <div class="trend-indicator trend-up">
                                        <span class="trend-icon">↗</span>
                                        <span class="trend-text">+2%</span>
                                    </div>
                                </td>
                                <td class="rating-cell">
                                    <div class="rating-stars">
                                        <span class="star filled">★</span>
                                        <span class="star filled">★</span>
                                        <span class="star filled">★</span>
                                        <span class="star filled">★</span>
                                        <span class="star filled">★</span>
                                        <span class="rating-value">4.8</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="supplier-cell">
                                    <div class="supplier-info">
                                        <div class="supplier-name">Dairy Direct</div>
                                        <div class="supplier-category">Dairy Products</div>
                                    </div>
                                </td>
                                <td class="orders-cell">
                                    <span class="orders-count">12</span>
                                </td>
                                <td class="percentage-cell">
                                    <div class="percentage-badge average">75%</div>
                                </td>
                                <td class="trend-cell">
                                    <div class="trend-indicator trend-stable">
                                        <span class="trend-icon">→</span>
                                        <span class="trend-text">0%</span>
                                    </div>
                                </td>
                                <td class="rating-cell">
                                    <div class="rating-stars">
                                        <span class="star filled">★</span>
                                        <span class="star filled">★</span>
                                        <span class="star filled">★</span>
                                        <span class="star">★</span>
                                        <span class="star">★</span>
                                        <span class="rating-value">3.5</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading State -->
    <div x-show="loading" class="loading-state">
        <div class="loading-spinner"></div>
        <p>{{ __('inventory.analytics.messages.loading_analytics') }}</p>
    </div>

    <!-- Empty State -->
    <div x-show="!loading && isEmpty" class="empty-state">
        <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
        </svg>
        <h3 class="empty-state-title">No Analytics Data</h3>
        <p class="empty-state-description">{{ __('inventory.analytics.messages.no_data_available') }}</p>
        <button class="btn btn-primary" @click="refreshData()">
            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Refresh Data
        </button>
    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/admin/inventory-analytics.js'])
@endpush
