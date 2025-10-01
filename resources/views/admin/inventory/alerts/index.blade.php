@extends('layouts.admin')

@section('title', __('inventory.alerts.title') . ' - ' . config('app.name'))
@section('page_title', __('inventory.alerts.title'))

@push('styles')
@vite(['resources/css/admin/inventory-alerts.css'])
@endpush

@section('content')
<div class="alerts-container" x-data="alertsPage()">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">{{ __('inventory.alerts.title') }}</h1>
            <p class="page-subtitle">{{ __('inventory.alerts.subtitle') }}</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary" @click="openAddRuleDrawer()">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                {{ __('inventory.alerts.add_alert_rule') }}
            </button>
        </div>
    </div>

    <!-- Overview Cards -->
    <div class="overview-cards">
        <div class="overview-card clickable" @click="filterByStatus('ok')">
            <div class="card-icon ok">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="card-content">
                <div class="card-value">24</div>
                <div class="card-label">{{ __('inventory.alerts.items_ok') }}</div>
            </div>
        </div>

        <div class="overview-card clickable" @click="filterByStatus('low')">
            <div class="card-icon low">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <div class="card-content">
                <div class="card-value">5</div>
                <div class="card-label">{{ __('inventory.alerts.items_low') }}</div>
            </div>
        </div>

        <div class="overview-card clickable" @click="filterByStatus('out')">
            <div class="card-icon out">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="card-content">
                <div class="card-value">2</div>
                <div class="card-label">{{ __('inventory.alerts.items_out') }}</div>
            </div>
        </div>
    </div>

    <!-- Active Alerts Section -->
    <div class="active-alerts-section">
        <div class="section-header">
            <h2 class="section-title">{{ __('inventory.alerts.active_alerts') }}</h2>
            <p class="section-subtitle">{{ __('inventory.alerts.active_alerts_subtitle') }}</p>
        </div>
        
        <div class="active-alerts-grid">
            <!-- Mock Alert 1 -->
            <div class="alert-card alert-low" @click="openRuleDetails(1)">
                <div class="alert-header">
                    <div class="alert-item">
                        <div class="item-name">Tomatoes</div>
                        <div class="item-code">TOM-001</div>
                    </div>
                    <div class="alert-status">
                        <span class="status-badge status-low">
                            {{ __('inventory.alerts.alert_statuses.low') }}
                        </span>
                    </div>
                </div>
                <div class="alert-details">
                    <div class="stock-info">
                        <span class="current-stock">8</span>
                        <span class="threshold">/ 10</span>
                        <span class="unit">kg</span>
                    </div>
                    <div class="location-info">Main Kitchen</div>
                </div>
                <div class="alert-actions">
                    <button class="btn btn-sm btn-primary" @click.stop="createPOForItem(1)">
                        {{ __('inventory.alerts.create_po_for_item') }}
                    </button>
                </div>
            </div>

            <!-- Mock Alert 2 -->
            <div class="alert-card alert-out" @click="openRuleDetails(2)">
                <div class="alert-header">
                    <div class="alert-item">
                        <div class="item-name">Olive Oil</div>
                        <div class="item-code">OIL-003</div>
                    </div>
                    <div class="alert-status">
                        <span class="status-badge status-out">
                            {{ __('inventory.alerts.alert_statuses.out') }}
                        </span>
                    </div>
                </div>
                <div class="alert-details">
                    <div class="stock-info">
                        <span class="current-stock">0</span>
                        <span class="threshold">/ 5</span>
                        <span class="unit">L</span>
                    </div>
                    <div class="location-info">Storage Room</div>
                </div>
                <div class="alert-actions">
                    <button class="btn btn-sm btn-primary" @click.stop="createPOForItem(2)">
                        {{ __('inventory.alerts.create_po_for_item') }}
                    </button>
                </div>
            </div>

            <!-- Mock Alert 3 -->
            <div class="alert-card alert-low" @click="openRuleDetails(3)">
                <div class="alert-header">
                    <div class="alert-item">
                        <div class="item-name">Chicken Breast</div>
                        <div class="item-code">CHK-001</div>
                    </div>
                    <div class="alert-status">
                        <span class="status-badge status-low">
                            {{ __('inventory.alerts.alert_statuses.low') }}
                        </span>
                    </div>
                </div>
                <div class="alert-details">
                    <div class="stock-info">
                        <span class="current-stock">3</span>
                        <span class="threshold">/ 5</span>
                        <span class="unit">kg</span>
                    </div>
                    <div class="location-info">Cold Storage</div>
                </div>
                <div class="alert-actions">
                    <button class="btn btn-sm btn-primary" @click.stop="createPOForItem(3)">
                        {{ __('inventory.alerts.create_po_for_item') }}
                    </button>
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
                {{ __('inventory.alerts.filters') }}
                <svg class="chevron" :class="{ 'rotate-180': showFilters }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div class="search-box">
                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" 
                       placeholder="{{ __('inventory.alerts.search_placeholder') }}" 
                       value="{{ request('search') }}"
                       @input="debounceSearch($event.target.value)">
            </div>
        </div>

        <div class="filters-content" x-show="showFilters" x-collapse>
            <div class="filter-group">
                <label>{{ __('inventory.alerts.filter_by_status') }}</label>
                <select id="status-filter">
                    <option value="all">{{ __('inventory.alerts.all_statuses') }}</option>
                    <option value="ok" {{ request('status') === 'ok' ? 'selected' : '' }}>
                        {{ __('inventory.alerts.alert_statuses.ok') }}
                    </option>
                    <option value="low" {{ request('status') === 'low' ? 'selected' : '' }}>
                        {{ __('inventory.alerts.alert_statuses.low') }}
                    </option>
                    <option value="out" {{ request('status') === 'out' ? 'selected' : '' }}>
                        {{ __('inventory.alerts.alert_statuses.out') }}
                    </option>
                    <option value="triggered" {{ request('status') === 'triggered' ? 'selected' : '' }}>
                        {{ __('inventory.alerts.triggered_rules') }}
                    </option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                        {{ __('inventory.alerts.active_rules') }}
                    </option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                        {{ __('inventory.alerts.alert_statuses.inactive') }}
                    </option>
                </select>
            </div>

            <div class="filter-group">
                <label>{{ __('inventory.alerts.filter_by_location') }}</label>
                <select id="location-filter">
                    <option value="all">{{ __('inventory.alerts.all_locations') }}</option>
                    <option value="main_kitchen">Main Kitchen</option>
                    <option value="cold_storage">Cold Storage</option>
                    <option value="storage_room">Storage Room</option>
                    <option value="freezer">Freezer</option>
                </select>
            </div>

            <div class="filter-actions">
                <button type="button" class="btn btn-secondary" onclick="clearFilters()">
                    {{ __('inventory.alerts.clear_filters') }}
                </button>
                <button type="button" class="btn btn-primary" onclick="applyFilters()">
                    {{ __('inventory.alerts.apply_filters') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Alert Rules Table -->
    <div class="table-card">
        <div class="card-header">
            <h3 class="card-title">{{ __('inventory.alerts.rule_details') }}</h3>
            <div class="table-info">
                <span class="info-text">
                    {{ __('inventory.alerts.showing') }} 1 - 8 {{ __('inventory.alerts.of') }} 8 {{ __('inventory.alerts.rules') }}
                </span>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table alerts-table">
                    <thead>
                        <tr>
                            <th>{{ __('inventory.alerts.item') }}</th>
                            <th>{{ __('inventory.alerts.current_stock') }}</th>
                            <th>{{ __('inventory.alerts.minimum_threshold') }}</th>
                            <th>{{ __('inventory.alerts.location') }}</th>
                            <th>{{ __('inventory.alerts.status') }}</th>
                            <th>{{ __('inventory.alerts.last_triggered') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Mock Rule 1 - Low Stock -->
                        <tr class="clickable-row alert-row-low" @click="openRuleDetails(1)">
                            <td class="item-cell">
                                <div class="item-info">
                                    <div class="item-name">Tomatoes</div>
                                    <div class="item-code">TOM-001</div>
                                </div>
                            </td>
                            <td class="stock-cell">
                                <div class="stock-info">
                                    <span class="stock-amount stock-low">8</span>
                                    <span class="stock-unit">kg</span>
                                </div>
                            </td>
                            <td class="threshold-cell">
                                <div class="threshold-info">
                                    <span class="threshold-amount">10</span>
                                    <span class="threshold-unit">kg</span>
                                </div>
                            </td>
                            <td class="location-cell">
                                <span class="location-badge">Main Kitchen</span>
                            </td>
                            <td class="status-cell">
                                <span class="status-badge status-low">
                                    {{ __('inventory.alerts.alert_statuses.low') }}
                                </span>
                            </td>
                            <td class="triggered-cell">
                                <div class="triggered-info">
                                    <div class="triggered-date">Dec 10, 2024</div>
                                    <div class="triggered-time">2:30 PM</div>
                                </div>
                            </td>
                        </tr>

                        <!-- Mock Rule 2 - Out of Stock -->
                        <tr class="clickable-row alert-row-out" @click="openRuleDetails(2)">
                            <td class="item-cell">
                                <div class="item-info">
                                    <div class="item-name">Olive Oil</div>
                                    <div class="item-code">OIL-003</div>
                                </div>
                            </td>
                            <td class="stock-cell">
                                <div class="stock-info">
                                    <span class="stock-amount stock-out">0</span>
                                    <span class="stock-unit">L</span>
                                </div>
                            </td>
                            <td class="threshold-cell">
                                <div class="threshold-info">
                                    <span class="threshold-amount">5</span>
                                    <span class="threshold-unit">L</span>
                                </div>
                            </td>
                            <td class="location-cell">
                                <span class="location-badge">Storage Room</span>
                            </td>
                            <td class="status-cell">
                                <span class="status-badge status-out">
                                    {{ __('inventory.alerts.alert_statuses.out') }}
                                </span>
                            </td>
                            <td class="triggered-cell">
                                <div class="triggered-info">
                                    <div class="triggered-date">Dec 9, 2024</div>
                                    <div class="triggered-time">11:15 AM</div>
                                </div>
                            </td>
                        </tr>

                        <!-- Mock Rule 3 - Low Stock -->
                        <tr class="clickable-row alert-row-low" @click="openRuleDetails(3)">
                            <td class="item-cell">
                                <div class="item-info">
                                    <div class="item-name">Chicken Breast</div>
                                    <div class="item-code">CHK-001</div>
                                </div>
                            </td>
                            <td class="stock-cell">
                                <div class="stock-info">
                                    <span class="stock-amount stock-low">3</span>
                                    <span class="stock-unit">kg</span>
                                </div>
                            </td>
                            <td class="threshold-cell">
                                <div class="threshold-info">
                                    <span class="threshold-amount">5</span>
                                    <span class="threshold-unit">kg</span>
                                </div>
                            </td>
                            <td class="location-cell">
                                <span class="location-badge">Cold Storage</span>
                            </td>
                            <td class="status-cell">
                                <span class="status-badge status-low">
                                    {{ __('inventory.alerts.alert_statuses.low') }}
                                </span>
                            </td>
                            <td class="triggered-cell">
                                <div class="triggered-info">
                                    <div class="triggered-date">Dec 10, 2024</div>
                                    <div class="triggered-time">9:45 AM</div>
                                </div>
                            </td>
                        </tr>

                        <!-- Mock Rule 4 - OK Status -->
                        <tr class="clickable-row" @click="openRuleDetails(4)">
                            <td class="item-cell">
                                <div class="item-info">
                                    <div class="item-name">Rice</div>
                                    <div class="item-code">RIC-001</div>
                                </div>
                            </td>
                            <td class="stock-cell">
                                <div class="stock-info">
                                    <span class="stock-amount stock-ok">25</span>
                                    <span class="stock-unit">kg</span>
                                </div>
                            </td>
                            <td class="threshold-cell">
                                <div class="threshold-info">
                                    <span class="threshold-amount">15</span>
                                    <span class="threshold-unit">kg</span>
                                </div>
                            </td>
                            <td class="location-cell">
                                <span class="text-muted">{{ __('inventory.alerts.all_locations') }}</span>
                            </td>
                            <td class="status-cell">
                                <span class="status-badge status-ok">
                                    {{ __('inventory.alerts.alert_statuses.ok') }}
                                </span>
                            </td>
                            <td class="triggered-cell">
                                <span class="text-muted">{{ __('inventory.alerts.never_triggered') }}</span>
                            </td>
                        </tr>

                        <!-- Mock Rule 5 - OK Status -->
                        <tr class="clickable-row" @click="openRuleDetails(5)">
                            <td class="item-cell">
                                <div class="item-info">
                                    <div class="item-name">Onions</div>
                                    <div class="item-code">ONI-001</div>
                                </div>
                            </td>
                            <td class="stock-cell">
                                <div class="stock-info">
                                    <span class="stock-amount stock-ok">12</span>
                                    <span class="stock-unit">kg</span>
                                </div>
                            </td>
                            <td class="threshold-cell">
                                <div class="threshold-info">
                                    <span class="threshold-amount">8</span>
                                    <span class="threshold-unit">kg</span>
                                </div>
                            </td>
                            <td class="location-cell">
                                <span class="location-badge">Main Kitchen</span>
                            </td>
                            <td class="status-cell">
                                <span class="status-badge status-ok">
                                    {{ __('inventory.alerts.alert_statuses.ok') }}
                                </span>
                            </td>
                            <td class="triggered-cell">
                                <div class="triggered-info">
                                    <div class="triggered-date">Dec 5, 2024</div>
                                    <div class="triggered-time">4:20 PM</div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Alert Rule Details Drawer -->
    <div class="drawer-overlay" x-show="showRuleDrawer" x-cloak @click="closeRuleDrawer()">
        <div class="drawer-content" @click.stop>
            <div class="drawer-header">
                <h2 class="drawer-title">{{ __('inventory.alerts.rule_drawer_title') }}</h2>
                <button class="drawer-close" @click="closeRuleDrawer()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="drawer-body">
                <div x-show="loadingRule" class="rule-detail-loading">
                    <div class="loading-spinner"></div>
                    <p>{{ __('inventory.alerts.loading_rules') }}</p>
                </div>

                <div x-show="!loadingRule && selectedRule" class="rule-details">
                    <!-- Details will be populated by JavaScript -->
                </div>
            </div>

            <div class="drawer-footer" x-show="!loadingRule && selectedRule">
                <button class="btn btn-secondary" @click="editRule()">
                    {{ __('inventory.alerts.edit') }}
                </button>
                <button class="btn btn-primary" @click="createPOFromRule()">
                    {{ __('inventory.alerts.create_po') }}
                </button>
                <button class="btn btn-danger" @click="deleteRule()">
                    {{ __('inventory.alerts.delete') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Add/Edit Alert Rule Drawer -->
    <div class="drawer-overlay" x-show="showAddRuleDrawer" x-cloak @click="closeAddRuleDrawer()">
        <div class="drawer-content" @click.stop>
            <div class="drawer-header">
                <h2 class="drawer-title" x-text="editingRule ? '{{ __('inventory.alerts.edit_rule_title') }}' : '{{ __('inventory.alerts.add_rule_title') }}'"></h2>
                <button class="drawer-close" @click="closeAddRuleDrawer()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="drawer-body">
                <form class="rule-form" @submit.prevent="saveRule()">
                    <div class="form-group">
                        <label for="inventory-item">{{ __('inventory.alerts.item_field') }} *</label>
                        <select id="inventory-item" x-model="ruleForm.inventory_item_id" required>
                            <option value="">{{ __('inventory.alerts.select_item') }}</option>
                            <option value="1">Tomatoes (TOM-001)</option>
                            <option value="2">Olive Oil (OIL-003)</option>
                            <option value="3">Chicken Breast (CHK-001)</option>
                            <option value="4">Rice (RIC-001)</option>
                            <option value="5">Onions (ONI-001)</option>
                            <option value="6">Pasta (PAS-001)</option>
                            <option value="7">Cheese (CHE-001)</option>
                            <option value="8">Garlic (GAR-001)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="threshold">{{ __('inventory.alerts.threshold_field') }} *</label>
                        <input type="number" 
                               id="threshold" 
                               x-model="ruleForm.minimum_threshold" 
                               step="0.01"
                               min="0"
                               placeholder="{{ __('inventory.alerts.enter_threshold') }}"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="location">{{ __('inventory.alerts.location_field') }}</label>
                        <select id="location" x-model="ruleForm.location">
                            <option value="">{{ __('inventory.alerts.all_locations') }}</option>
                            <option value="main_kitchen">Main Kitchen</option>
                            <option value="cold_storage">Cold Storage</option>
                            <option value="storage_room">Storage Room</option>
                            <option value="freezer">Freezer</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" x-model="ruleForm.is_active">
                            <span class="checkmark"></span>
                            {{ __('inventory.alerts.rule_active') }}
                        </label>
                    </div>
                </form>
            </div>

            <div class="drawer-footer">
                <button type="button" class="btn btn-secondary" @click="closeAddRuleDrawer()">
                    {{ __('inventory.alerts.cancel') }}
                </button>
                <button type="button" class="btn btn-primary" @click="saveRule()">
                    {{ __('inventory.alerts.save_rule') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/admin/inventory-alerts.js'])
@endpush
