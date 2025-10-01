@extends('layouts.admin')

@section('title', __('inventory.stocktakes.title') . ' - ' . config('app.name'))
@section('page_title', __('inventory.stocktakes.title'))

@push('styles')
@vite(['resources/css/admin/inventory-stocktakes.css'])
@endpush

@section('content')
<div class="stocktakes-container" x-data="stocktakesPage()">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">{{ __('inventory.stocktakes.title') }}</h1>
            <p class="page-subtitle">{{ __('inventory.stocktakes.subtitle') }}</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary" @click="openNewStocktakeDrawer()">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                {{ __('inventory.stocktakes.new_stocktake') }}
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card">
            <div class="card-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="card-content">
                <div class="card-value">24</div>
                <div class="card-label">{{ __('inventory.stocktakes.summary_stats.total_stocktakes') }}</div>
            </div>
        </div>

        <div class="summary-card">
            <div class="card-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="card-content">
                <div class="card-value">18</div>
                <div class="card-label">{{ __('inventory.stocktakes.summary_stats.completed_stocktakes') }}</div>
            </div>
        </div>

        <div class="summary-card">
            <div class="card-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
            </div>
            <div class="card-content">
                <div class="card-value">3</div>
                <div class="card-label">{{ __('inventory.stocktakes.summary_stats.draft_stocktakes') }}</div>
            </div>
        </div>

        <div class="summary-card">
            <div class="card-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
            <div class="card-content">
                <div class="card-value">-$245</div>
                <div class="card-label">{{ __('inventory.stocktakes.summary_stats.average_variance') }}</div>
                <div class="card-trend negative">
                    <span class="trend-icon">↘</span>
                    <span>2.1%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-section">
        <div class="filters-header">
            <button class="filters-toggle" @click="showFilters = !showFilters">
                <svg class="filter-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"/>
                </svg>
                {{ __('inventory.stocktakes.filters.filter_by_date') }}
                <svg class="chevron" :class="{ 'rotate-180': showFilters }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div class="search-box">
                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" 
                       placeholder="Search stocktakes..." 
                       @input="debounceSearch($event.target.value)">
            </div>
        </div>

        <div class="filters-content" x-show="showFilters" x-collapse>
            <div class="filter-group">
                <label>{{ __('inventory.stocktakes.filters.filter_by_status') }}</label>
                <select id="status-filter">
                    <option value="all">{{ __('inventory.stocktakes.filters.all_statuses') }}</option>
                    <option value="draft">{{ __('inventory.stocktakes.statuses.draft') }}</option>
                    <option value="in_progress">{{ __('inventory.stocktakes.statuses.in_progress') }}</option>
                    <option value="completed">{{ __('inventory.stocktakes.statuses.completed') }}</option>
                    <option value="cancelled">{{ __('inventory.stocktakes.statuses.cancelled') }}</option>
                </select>
            </div>

            <div class="filter-group">
                <label>{{ __('inventory.stocktakes.filters.filter_by_location') }}</label>
                <select id="location-filter">
                    <option value="all">{{ __('inventory.stocktakes.filters.all_locations') }}</option>
                    <option value="main_kitchen">{{ __('inventory.stocktakes.locations.main_kitchen') }}</option>
                    <option value="cold_storage">{{ __('inventory.stocktakes.locations.cold_storage') }}</option>
                    <option value="freezer">{{ __('inventory.stocktakes.locations.freezer') }}</option>
                    <option value="dry_storage">{{ __('inventory.stocktakes.locations.dry_storage') }}</option>
                    <option value="bar">{{ __('inventory.stocktakes.locations.bar') }}</option>
                    <option value="prep_area">{{ __('inventory.stocktakes.locations.prep_area') }}</option>
                </select>
            </div>

            <div class="filter-group">
                <label>{{ __('inventory.stocktakes.filters.date_range') }}</label>
                <select id="date-filter">
                    <option value="all">All Time</option>
                    <option value="today">Today</option>
                    <option value="this_week">This Week</option>
                    <option value="this_month">This Month</option>
                    <option value="last_month">Last Month</option>
                </select>
            </div>

            <div class="filter-actions">
                <button type="button" class="btn btn-secondary" onclick="clearFilters()">
                    {{ __('inventory.stocktakes.filters.clear_filters') }}
                </button>
                <button type="button" class="btn btn-primary" onclick="applyFilters()">
                    {{ __('inventory.stocktakes.filters.apply_filters') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Stocktakes Table -->
    <div class="table-card">
        <div class="card-header">
            <h3 class="card-title">{{ __('inventory.stocktakes.title') }}</h3>
            <div class="table-info">
                <span class="info-text">
                    {{ __('inventory.stocktakes.pagination.showing') }} 1 - 10 {{ __('inventory.stocktakes.pagination.of') }} 24 {{ __('inventory.stocktakes.pagination.stocktakes') }}
                </span>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table stocktakes-table">
                    <thead>
                        <tr>
                            <th>{{ __('inventory.stocktakes.stocktake_id') }}</th>
                            <th>{{ __('inventory.stocktakes.date') }}</th>
                            <th>{{ __('inventory.stocktakes.performed_by') }}</th>
                            <th>{{ __('inventory.stocktakes.location') }}</th>
                            <th>{{ __('inventory.stocktakes.status') }}</th>
                            <th>{{ __('inventory.stocktakes.items_counted') }}</th>
                            <th>{{ __('inventory.stocktakes.variance') }}</th>
                            <th>{{ __('inventory.stocktakes.total_value') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Mock Stocktake 1 - Completed with Negative Variance -->
                        <tr class="clickable-row" @click="openStocktakeDetails(1)">
                            <td class="id-cell">
                                <div class="stocktake-id">
                                    <span class="id-number">ST-001</span>
                                    <span class="id-date">Dec 10, 2024</span>
                                </div>
                            </td>
                            <td class="date-cell">
                                <div class="date-info">
                                    <span class="date-primary">Dec 10, 2024</span>
                                    <span class="date-time">2:30 PM</span>
                                </div>
                            </td>
                            <td class="staff-cell">
                                <div class="staff-info">
                                    <span class="staff-name">John Doe</span>
                                    <span class="staff-role">Kitchen Manager</span>
                                </div>
                            </td>
                            <td class="location-cell">
                                <span class="location-badge">Main Kitchen</span>
                            </td>
                            <td class="status-cell">
                                <span class="status-badge status-completed">
                                    {{ __('inventory.stocktakes.statuses.completed') }}
                                </span>
                            </td>
                            <td class="items-cell">
                                <span class="items-count">24</span>
                            </td>
                            <td class="variance-cell">
                                <div class="variance-info variance-negative">
                                    <span class="variance-amount">-$125.50</span>
                                    <span class="variance-percentage">-2.3%</span>
                                </div>
                            </td>
                            <td class="value-cell">
                                <span class="total-value">$5,420.00</span>
                            </td>
                        </tr>

                        <!-- Mock Stocktake 2 - In Progress -->
                        <tr class="clickable-row" @click="openStocktakeDetails(2)">
                            <td class="id-cell">
                                <div class="stocktake-id">
                                    <span class="id-number">ST-002</span>
                                    <span class="id-date">Dec 11, 2024</span>
                                </div>
                            </td>
                            <td class="date-cell">
                                <div class="date-info">
                                    <span class="date-primary">Dec 11, 2024</span>
                                    <span class="date-time">10:15 AM</span>
                                </div>
                            </td>
                            <td class="staff-cell">
                                <div class="staff-info">
                                    <span class="staff-name">Jane Smith</span>
                                    <span class="staff-role">Inventory Clerk</span>
                                </div>
                            </td>
                            <td class="location-cell">
                                <span class="location-badge">Cold Storage</span>
                            </td>
                            <td class="status-cell">
                                <span class="status-badge status-in-progress">
                                    {{ __('inventory.stocktakes.statuses.in_progress') }}
                                </span>
                            </td>
                            <td class="items-cell">
                                <span class="items-count">12/18</span>
                            </td>
                            <td class="variance-cell">
                                <div class="variance-info variance-pending">
                                    <span class="variance-amount">Pending</span>
                                </div>
                            </td>
                            <td class="value-cell">
                                <span class="total-value">$2,850.00</span>
                            </td>
                        </tr>

                        <!-- Mock Stocktake 3 - Completed with Positive Variance -->
                        <tr class="clickable-row" @click="openStocktakeDetails(3)">
                            <td class="id-cell">
                                <div class="stocktake-id">
                                    <span class="id-number">ST-003</span>
                                    <span class="id-date">Dec 9, 2024</span>
                                </div>
                            </td>
                            <td class="date-cell">
                                <div class="date-info">
                                    <span class="date-primary">Dec 9, 2024</span>
                                    <span class="date-time">4:45 PM</span>
                                </div>
                            </td>
                            <td class="staff-cell">
                                <div class="staff-info">
                                    <span class="staff-name">Mike Johnson</span>
                                    <span class="staff-role">Sous Chef</span>
                                </div>
                            </td>
                            <td class="location-cell">
                                <span class="location-badge">Freezer</span>
                            </td>
                            <td class="status-cell">
                                <span class="status-badge status-completed">
                                    {{ __('inventory.stocktakes.statuses.completed') }}
                                </span>
                            </td>
                            <td class="items-cell">
                                <span class="items-count">15</span>
                            </td>
                            <td class="variance-cell">
                                <div class="variance-info variance-positive">
                                    <span class="variance-amount">+$45.20</span>
                                    <span class="variance-percentage">+1.2%</span>
                                </div>
                            </td>
                            <td class="value-cell">
                                <span class="total-value">$3,765.00</span>
                            </td>
                        </tr>

                        <!-- Mock Stocktake 4 - Draft -->
                        <tr class="clickable-row" @click="openStocktakeDetails(4)">
                            <td class="id-cell">
                                <div class="stocktake-id">
                                    <span class="id-number">ST-004</span>
                                    <span class="id-date">Dec 11, 2024</span>
                                </div>
                            </td>
                            <td class="date-cell">
                                <div class="date-info">
                                    <span class="date-primary">Dec 11, 2024</span>
                                    <span class="date-time">3:20 PM</span>
                                </div>
                            </td>
                            <td class="staff-cell">
                                <div class="staff-info">
                                    <span class="staff-name">Sarah Wilson</span>
                                    <span class="staff-role">Assistant Manager</span>
                                </div>
                            </td>
                            <td class="location-cell">
                                <span class="location-badge">Bar</span>
                            </td>
                            <td class="status-cell">
                                <span class="status-badge status-draft">
                                    {{ __('inventory.stocktakes.statuses.draft') }}
                                </span>
                            </td>
                            <td class="items-cell">
                                <span class="items-count">8/25</span>
                            </td>
                            <td class="variance-cell">
                                <div class="variance-info variance-pending">
                                    <span class="variance-amount">Draft</span>
                                </div>
                            </td>
                            <td class="value-cell">
                                <span class="total-value">$1,240.00</span>
                            </td>
                        </tr>

                        <!-- Mock Stocktake 5 - Completed with No Variance -->
                        <tr class="clickable-row" @click="openStocktakeDetails(5)">
                            <td class="id-cell">
                                <div class="stocktake-id">
                                    <span class="id-number">ST-005</span>
                                    <span class="id-date">Dec 8, 2024</span>
                                </div>
                            </td>
                            <td class="date-cell">
                                <div class="date-info">
                                    <span class="date-primary">Dec 8, 2024</span>
                                    <span class="date-time">11:30 AM</span>
                                </div>
                            </td>
                            <td class="staff-cell">
                                <div class="staff-info">
                                    <span class="staff-name">John Doe</span>
                                    <span class="staff-role">Kitchen Manager</span>
                                </div>
                            </td>
                            <td class="location-cell">
                                <span class="location-badge">Dry Storage</span>
                            </td>
                            <td class="status-cell">
                                <span class="status-badge status-completed">
                                    {{ __('inventory.stocktakes.statuses.completed') }}
                                </span>
                            </td>
                            <td class="items-cell">
                                <span class="items-count">32</span>
                            </td>
                            <td class="variance-cell">
                                <div class="variance-info variance-zero">
                                    <span class="variance-amount">$0.00</span>
                                    <span class="variance-percentage">0.0%</span>
                                </div>
                            </td>
                            <td class="value-cell">
                                <span class="total-value">$4,180.00</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Stocktake Details Drawer -->
    <div class="drawer-overlay" x-show="showDetailsDrawer" x-cloak @click="closeDetailsDrawer()">
        <div class="drawer-content large-drawer" @click.stop>
            <div class="drawer-header">
                <h2 class="drawer-title">{{ __('inventory.stocktakes.drawer_titles.stocktake_details') }}</h2>
                <button class="drawer-close" @click="closeDetailsDrawer()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="drawer-body">
                <div x-show="loadingDetails" class="details-loading">
                    <div class="loading-spinner"></div>
                    <p>{{ __('inventory.stocktakes.messages.loading_details') }}</p>
                </div>

                <div x-show="!loadingDetails && selectedStocktake" class="stocktake-details">
                    <!-- Stocktake Summary -->
                    <div class="details-section">
                        <h3 class="section-title">{{ __('inventory.stocktakes.stocktake_summary') }}</h3>
                        <div class="summary-grid">
                            <div class="summary-item">
                                <label>{{ __('inventory.stocktakes.stocktake_id') }}</label>
                                <span class="summary-value" x-text="selectedStocktake?.id || 'ST-001'">ST-001</span>
                            </div>
                            <div class="summary-item">
                                <label>{{ __('inventory.stocktakes.date') }}</label>
                                <span class="summary-value" x-text="selectedStocktake?.date || 'Dec 10, 2024'">Dec 10, 2024</span>
                            </div>
                            <div class="summary-item">
                                <label>{{ __('inventory.stocktakes.performed_by') }}</label>
                                <span class="summary-value" x-text="selectedStocktake?.staff || 'John Doe'">John Doe</span>
                            </div>
                            <div class="summary-item">
                                <label>{{ __('inventory.stocktakes.location') }}</label>
                                <span class="summary-value" x-text="selectedStocktake?.location || 'Main Kitchen'">Main Kitchen</span>
                            </div>
                            <div class="summary-item">
                                <label>{{ __('inventory.stocktakes.status') }}</label>
                                <span class="status-badge status-completed" x-text="selectedStocktake?.status || 'Completed'">Completed</span>
                            </div>
                            <div class="summary-item">
                                <label>{{ __('inventory.stocktakes.total_variance') }}</label>
                                <span class="variance-amount variance-negative" x-text="selectedStocktake?.variance || '-$125.50'">-$125.50</span>
                            </div>
                        </div>
                    </div>

                    <!-- Item Counts -->
                    <div class="details-section">
                        <h3 class="section-title">{{ __('inventory.stocktakes.item_counts') }}</h3>
                        <div class="items-table-container">
                            <table class="items-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('inventory.stocktakes.item') }}</th>
                                        <th>{{ __('inventory.stocktakes.expected_qty') }}</th>
                                        <th>{{ __('inventory.stocktakes.counted_qty') }}</th>
                                        <th>{{ __('inventory.stocktakes.variance_qty') }}</th>
                                        <th>{{ __('inventory.stocktakes.unit_cost') }}</th>
                                        <th>{{ __('inventory.stocktakes.variance_value') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Mock Item 1 - Negative Variance -->
                                    <tr class="variance-row variance-negative">
                                        <td class="item-cell">
                                            <div class="item-info">
                                                <span class="item-name">Tomatoes</span>
                                                <span class="item-code">TOM-001</span>
                                            </div>
                                        </td>
                                        <td class="qty-cell">
                                            <span class="qty-value">20 kg</span>
                                        </td>
                                        <td class="qty-cell">
                                            <span class="qty-value counted">18 kg</span>
                                        </td>
                                        <td class="variance-cell">
                                            <span class="variance-qty negative">-2 kg</span>
                                        </td>
                                        <td class="cost-cell">
                                            <span class="cost-value">$3.25</span>
                                        </td>
                                        <td class="variance-value-cell">
                                            <span class="variance-amount negative">-$6.50</span>
                                        </td>
                                    </tr>

                                    <!-- Mock Item 2 - Positive Variance -->
                                    <tr class="variance-row variance-positive">
                                        <td class="item-cell">
                                            <div class="item-info">
                                                <span class="item-name">Rice</span>
                                                <span class="item-code">RIC-001</span>
                                            </div>
                                        </td>
                                        <td class="qty-cell">
                                            <span class="qty-value">15 kg</span>
                                        </td>
                                        <td class="qty-cell">
                                            <span class="qty-value counted">16 kg</span>
                                        </td>
                                        <td class="variance-cell">
                                            <span class="variance-qty positive">+1 kg</span>
                                        </td>
                                        <td class="cost-cell">
                                            <span class="cost-value">$2.80</span>
                                        </td>
                                        <td class="variance-value-cell">
                                            <span class="variance-amount positive">+$2.80</span>
                                        </td>
                                    </tr>

                                    <!-- Mock Item 3 - No Variance -->
                                    <tr class="variance-row variance-zero">
                                        <td class="item-cell">
                                            <div class="item-info">
                                                <span class="item-name">Chicken Breast</span>
                                                <span class="item-code">CHK-001</span>
                                            </div>
                                        </td>
                                        <td class="qty-cell">
                                            <span class="qty-value">8 kg</span>
                                        </td>
                                        <td class="qty-cell">
                                            <span class="qty-value counted">8 kg</span>
                                        </td>
                                        <td class="variance-cell">
                                            <span class="variance-qty zero">0 kg</span>
                                        </td>
                                        <td class="cost-cell">
                                            <span class="cost-value">$8.50</span>
                                        </td>
                                        <td class="variance-value-cell">
                                            <span class="variance-amount zero">$0.00</span>
                                        </td>
                                    </tr>

                                    <!-- Mock Item 4 - Large Negative Variance -->
                                    <tr class="variance-row variance-negative">
                                        <td class="item-cell">
                                            <div class="item-info">
                                                <span class="item-name">Olive Oil</span>
                                                <span class="item-code">OIL-003</span>
                                            </div>
                                        </td>
                                        <td class="qty-cell">
                                            <span class="qty-value">12 L</span>
                                        </td>
                                        <td class="qty-cell">
                                            <span class="qty-value counted">8 L</span>
                                        </td>
                                        <td class="variance-cell">
                                            <span class="variance-qty negative">-4 L</span>
                                        </td>
                                        <td class="cost-cell">
                                            <span class="cost-value">$15.75</span>
                                        </td>
                                        <td class="variance-value-cell">
                                            <span class="variance-amount negative">-$63.00</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="drawer-footer" x-show="!loadingDetails && selectedStocktake">
                <button class="btn btn-secondary" @click="editStocktake()" x-show="selectedStocktake?.status !== 'completed'">
                    {{ __('inventory.stocktakes.edit_stocktake') }}
                </button>
                <button class="btn btn-primary" @click="finalizeStocktake()" x-show="selectedStocktake?.status !== 'completed'">
                    {{ __('inventory.stocktakes.finalize_stocktake') }}
                </button>
                <button class="btn btn-danger" @click="deleteStocktake()">
                    {{ __('inventory.stocktakes.delete_stocktake') }}
                </button>
            </div>
        </div>
    </div>

    <!-- New/Edit Stocktake Drawer -->
    <div class="drawer-overlay" x-show="showFormDrawer" x-cloak @click="closeFormDrawer()">
        <div class="drawer-content large-drawer" @click.stop>
            <div class="drawer-header">
                <h2 class="drawer-title" x-text="editingStocktake ? '{{ __('inventory.stocktakes.drawer_titles.edit_stocktake') }}' : '{{ __('inventory.stocktakes.drawer_titles.new_stocktake') }}'">New Stocktake</h2>
                <button class="drawer-close" @click="closeFormDrawer()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="drawer-body">
                <form class="stocktake-form" @submit.prevent="saveStocktake()">
                    <!-- Basic Information -->
                    <div class="form-section">
                        <h3 class="section-title">Basic Information</h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="stocktake-date">{{ __('inventory.stocktakes.stocktake_date') }} *</label>
                                <input type="date" 
                                       id="stocktake-date" 
                                       x-model="stocktakeForm.date" 
                                       required>
                            </div>

                            <div class="form-group">
                                <label for="staff-member">{{ __('inventory.stocktakes.staff_member') }} *</label>
                                <select id="staff-member" x-model="stocktakeForm.staff_member" required>
                                    <option value="">Select Staff Member</option>
                                    <option value="john_doe">{{ __('inventory.stocktakes.staff_members.john_doe') }}</option>
                                    <option value="jane_smith">{{ __('inventory.stocktakes.staff_members.jane_smith') }}</option>
                                    <option value="mike_johnson">{{ __('inventory.stocktakes.staff_members.mike_johnson') }}</option>
                                    <option value="sarah_wilson">{{ __('inventory.stocktakes.staff_members.sarah_wilson') }}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="location">{{ __('inventory.stocktakes.stocktake_location') }} *</label>
                                <select id="location" x-model="stocktakeForm.location" required>
                                    <option value="">Select Location</option>
                                    <option value="main_kitchen">{{ __('inventory.stocktakes.locations.main_kitchen') }}</option>
                                    <option value="cold_storage">{{ __('inventory.stocktakes.locations.cold_storage') }}</option>
                                    <option value="freezer">{{ __('inventory.stocktakes.locations.freezer') }}</option>
                                    <option value="dry_storage">{{ __('inventory.stocktakes.locations.dry_storage') }}</option>
                                    <option value="bar">{{ __('inventory.stocktakes.locations.bar') }}</option>
                                    <option value="prep_area">{{ __('inventory.stocktakes.locations.prep_area') }}</option>
                                </select>
                            </div>

                            <div class="form-group full-width">
                                <label for="notes">{{ __('inventory.stocktakes.notes') }}</label>
                                <textarea id="notes" 
                                          x-model="stocktakeForm.notes" 
                                          rows="3"
                                          placeholder="Optional notes about this stocktake..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Item Counts -->
                    <div class="form-section">
                        <div class="section-header">
                            <h3 class="section-title">{{ __('inventory.stocktakes.item_counts') }}</h3>
                            <button type="button" class="btn btn-secondary btn-sm" @click="addItem()">
                                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                {{ __('inventory.stocktakes.add_item') }}
                            </button>
                        </div>

                        <div class="instructions-box">
                            <p>{{ __('inventory.stocktakes.instructions.count_instructions') }}</p>
                            <p>{{ __('inventory.stocktakes.instructions.variance_auto_calculated') }}</p>
                        </div>

                        <div class="items-form-container">
                            <template x-for="(item, index) in stocktakeForm.items" :key="index">
                                <div class="item-form-row">
                                    <div class="item-form-grid">
                                        <div class="form-group">
                                            <label>{{ __('inventory.stocktakes.item') }}</label>
                                            <select x-model="item.item_id" @change="updateExpectedQuantity(index)">
                                                <option value="">Select Item</option>
                                                <option value="1">Tomatoes (TOM-001)</option>
                                                <option value="2">Rice (RIC-001)</option>
                                                <option value="3">Chicken Breast (CHK-001)</option>
                                                <option value="4">Olive Oil (OIL-003)</option>
                                                <option value="5">Onions (ONI-001)</option>
                                                <option value="6">Pasta (PAS-001)</option>
                                                <option value="7">Cheese (CHE-001)</option>
                                                <option value="8">Garlic (GAR-001)</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ __('inventory.stocktakes.expected_quantity') }}</label>
                                            <input type="number" 
                                                   x-model="item.expected_qty" 
                                                   step="0.01"
                                                   min="0"
                                                   readonly
                                                   placeholder="Auto-filled">
                                        </div>

                                        <div class="form-group">
                                            <label>{{ __('inventory.stocktakes.actual_quantity') }}</label>
                                            <input type="number" 
                                                   x-model="item.actual_qty" 
                                                   step="0.01"
                                                   min="0"
                                                   @input="calculateVariance(index)"
                                                   placeholder="Enter counted qty">
                                        </div>

                                        <div class="form-group">
                                            <label>{{ __('inventory.stocktakes.variance_qty') }}</label>
                                            <input type="text" 
                                                   x-model="item.variance_display" 
                                                   readonly
                                                   class="variance-field"
                                                   :class="getVarianceClass(item.variance)">
                                        </div>

                                        <div class="form-group">
                                            <button type="button" 
                                                    class="btn btn-danger btn-sm remove-item-btn" 
                                                    @click="removeItem(index)"
                                                    title="{{ __('inventory.stocktakes.remove_item') }}">
                                                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <div x-show="stocktakeForm.items.length === 0" class="empty-items-state">
                                <p>{{ __('inventory.stocktakes.empty_states.no_items') }}</p>
                                <button type="button" class="btn btn-primary" @click="addItem()">
                                    {{ __('inventory.stocktakes.add_item') }}
                                </button>
                            </div>
                        </div>

                        <!-- Variance Summary -->
                        <div class="variance-summary" x-show="stocktakeForm.items.length > 0">
                            <div class="summary-row">
                                <span class="summary-label">{{ __('inventory.stocktakes.total_items') }}:</span>
                                <span class="summary-value" x-text="stocktakeForm.items.length">0</span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">{{ __('inventory.stocktakes.items_with_variance') }}:</span>
                                <span class="summary-value" x-text="getItemsWithVarianceCount()">0</span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">{{ __('inventory.stocktakes.total_variance') }}:</span>
                                <span class="summary-value variance-amount" 
                                      :class="getTotalVarianceClass()" 
                                      x-text="getTotalVarianceDisplay()">$0.00</span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="drawer-footer">
                <button type="button" class="btn btn-secondary" @click="closeFormDrawer()">
                    Cancel
                </button>
                <button type="button" class="btn btn-secondary" @click="saveDraft()">
                    {{ __('inventory.stocktakes.save_draft') }}
                </button>
                <button type="button" class="btn btn-primary" @click="saveStocktake()">
                    {{ __('inventory.stocktakes.save_stocktake') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Loading State -->
    <div x-show="loading" class="loading-state">
        <div class="loading-spinner"></div>
        <p>{{ __('inventory.stocktakes.messages.loading_stocktakes') }}</p>
    </div>

    <!-- Empty State -->
    <div x-show="!loading && isEmpty" class="empty-state">
        <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <h3 class="empty-state-title">{{ __('inventory.stocktakes.empty_states.no_stocktakes') }}</h3>
        <p class="empty-state-description">{{ __('inventory.stocktakes.empty_states.no_stocktakes_description') }}</p>
        <button class="btn btn-primary" @click="openNewStocktakeDrawer()">
            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            {{ __('inventory.stocktakes.new_stocktake') }}
        </button>
    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/admin/inventory-stocktakes.js'])
@endpush
