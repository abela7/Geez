@extends('layouts.admin')

@section('title', __('injera.injera_stock_levels.title') . ' - ' . config('app.name'))
@section('page_title', __('injera.injera_stock_levels.title'))

@section('content')
<div class="admin-content">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <h1 class="page-title">{{ __('injera.injera_stock_levels.title') }}</h1>
            <p class="page-subtitle">{{ __('injera.injera_stock_levels.subtitle') }}</p>
        </div>
        <div class="page-header-actions">
            <button class="btn btn-secondary" onclick="exportStockLevels()">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                {{ __('injera.injera_stock_levels.export_stock') }}
            </button>
            <button class="btn btn-primary" onclick="openAddStockModal()">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                {{ __('injera.injera_stock_levels.add_stock') }}
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card">
            <div class="summary-card-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div class="summary-card-content">
                <h3 class="summary-card-title">{{ __('injera.injera_stock_levels.total_injera') }}</h3>
                <div class="summary-card-value">{{ $statistics['total_injera'] }}</div>
                <div class="summary-card-subtitle">{{ __('injera.injera_stock_levels.pieces') }}</div>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-card-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="summary-card-content">
                <h3 class="summary-card-title">{{ __('injera.injera_stock_levels.available_injera') }}</h3>
                <div class="summary-card-value">{{ $statistics['available_injera'] }}</div>
                <div class="summary-card-subtitle">{{ __('injera.injera_stock_levels.pieces') }}</div>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-card-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="summary-card-content">
                <h3 class="summary-card-title">{{ __('injera.injera_stock_levels.expiring_today') }}</h3>
                <div class="summary-card-value">{{ $statistics['expiring_today'] }}</div>
                <div class="summary-card-subtitle">{{ __('injera.injera_stock_levels.pieces') }}</div>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-card-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                </svg>
            </div>
            <div class="summary-card-content">
                <h3 class="summary-card-title">{{ __('injera.injera_stock_levels.total_value') }}</h3>
                <div class="summary-card-value">${{ number_format($statistics['total_value'], 2) }}</div>
                <div class="summary-card-subtitle">{{ __('injera.injera_stock_levels.inventory_value') }}</div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-container">
        <div class="filters-content">
            <div class="search-filter">
                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" 
                       id="stockSearch" 
                       class="search-input" 
                       placeholder="{{ __('injera.injera_stock_levels.search_placeholder') }}"
                       onkeyup="searchStock()">
            </div>

            <div class="filter-group">
                <select id="qualityFilter" class="filter-select" onchange="filterStock()">
                    <option value="">{{ __('injera.injera_stock_levels.all_qualities') }}</option>
                    <option value="A">{{ __('injera.injera_stock_levels.grade_a') }}</option>
                    <option value="B">{{ __('injera.injera_stock_levels.grade_b') }}</option>
                    <option value="C">{{ __('injera.injera_stock_levels.grade_c') }}</option>
                </select>

                <select id="statusFilter" class="filter-select" onchange="filterStock()">
                    <option value="">{{ __('injera.injera_stock_levels.all_statuses') }}</option>
                    <option value="fresh">{{ __('injera.injera_stock_levels.fresh') }}</option>
                    <option value="expiring_soon">{{ __('injera.injera_stock_levels.expiring_soon') }}</option>
                    <option value="expired">{{ __('injera.injera_stock_levels.expired') }}</option>
                </select>

                <button class="btn btn-secondary" onclick="clearFilters()">
                    {{ __('injera.injera_stock_levels.clear_filters') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Stock Levels Table -->
    <div class="table-container">
        <div class="table-header">
            <h2 class="table-title">{{ __('injera.injera_stock_levels.stock_inventory') }}</h2>
            <div class="table-actions">
                <button class="btn btn-outline" onclick="refreshData()">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    {{ __('injera.injera_stock_levels.refresh') }}
                </button>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="sortable" data-sort="batch_number">
                            {{ __('injera.injera_stock_levels.batch_number') }}
                            <svg class="sort-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                            </svg>
                        </th>
                        <th>{{ __('injera.injera_stock_levels.quality_grade') }}</th>
                        <th class="sortable" data-sort="current_stock">
                            {{ __('injera.injera_stock_levels.current_stock') }}
                            <svg class="sort-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                            </svg>
                        </th>
                        <th>{{ __('injera.injera_stock_levels.reserved_stock') }}</th>
                        <th>{{ __('injera.injera_stock_levels.available_stock') }}</th>
                        <th>{{ __('injera.injera_stock_levels.storage_location') }}</th>
                        <th class="sortable" data-sort="expiry_date">
                            {{ __('injera.injera_stock_levels.expiry_date') }}
                            <svg class="sort-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                            </svg>
                        </th>
                        <th>{{ __('injera.injera_stock_levels.status') }}</th>
                        <th>{{ __('injera.injera_stock_levels.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stockLevels as $stock)
                    <tr class="table-row" data-stock-id="{{ $stock['id'] }}" data-quality="{{ $stock['quality_grade'] }}" data-status="{{ $stock['status'] }}">
                        <td class="batch-name">
                            <div class="batch-info">
                                <span class="batch-title">{{ $stock['batch_number'] }}</span>
                                <span class="batch-notes">{{ \Carbon\Carbon::parse($stock['production_date'])->format('M j, Y') }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="quality-badge quality-{{ strtolower($stock['quality_grade']) }}">
                                {{ $stock['quality_grade'] }}
                            </span>
                        </td>
                        <td class="stock-cell">
                            <div class="stock-info">
                                <span class="stock-value">{{ $stock['current_stock'] }}</span>
                                <span class="stock-unit">{{ __('injera.injera_stock_levels.pieces') }}</span>
                            </div>
                        </td>
                        <td class="stock-cell">
                            <div class="stock-info">
                                <span class="stock-value reserved">{{ $stock['reserved_stock'] }}</span>
                                <span class="stock-unit">{{ __('injera.injera_stock_levels.pieces') }}</span>
                            </div>
                        </td>
                        <td class="stock-cell">
                            <div class="stock-info">
                                <span class="stock-value available">{{ $stock['available_stock'] }}</span>
                                <span class="stock-unit">{{ __('injera.injera_stock_levels.pieces') }}</span>
                            </div>
                        </td>
                        <td class="location-cell">
                            <div class="location-info">
                                <span class="location-name">{{ $stock['storage_location'] }}</span>
                                <span class="location-cost">${{ number_format($stock['cost_per_injera'], 2) }}/{{ __('injera.injera_stock_levels.piece') }}</span>
                            </div>
                        </td>
                        <td class="expiry-cell">
                            <div class="expiry-info">
                                <span class="expiry-date">{{ \Carbon\Carbon::parse($stock['expiry_date'])->format('M j, Y') }}</span>
                                <span class="expiry-countdown {{ $stock['days_until_expiry'] <= 1 ? 'critical' : ($stock['days_until_expiry'] <= 3 ? 'warning' : 'normal') }}">
                                    {{ $stock['days_until_expiry'] }} {{ __('injera.injera_stock_levels.days_left') }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <span class="status-badge status-{{ $stock['status'] }}">
                                {{ __('injera.injera_stock_levels.' . $stock['status']) }}
                            </span>
                        </td>
                        <td class="actions-cell">
                            <div class="action-buttons">
                                <button class="action-btn edit" onclick="updateStock({{ $stock['id'] }})" title="{{ __('injera.injera_stock_levels.update_stock') }}">
                                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button class="action-btn reserve" onclick="reserveStock({{ $stock['id'] }})" title="{{ __('injera.injera_stock_levels.reserve_stock') }}">
                                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </button>
                                <button class="action-btn view" onclick="viewStockDetails({{ $stock['id'] }})" title="{{ __('injera.injera_stock_levels.view_details') }}">
                                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quality Distribution Chart -->
    <div class="chart-section">
        <div class="chart-header">
            <h3>{{ __('injera.injera_stock_levels.quality_distribution') }}</h3>
            <p>{{ __('injera.injera_stock_levels.quality_distribution_subtitle') }}</p>
        </div>
        <div class="quality-chart">
            <div class="quality-bar">
                <div class="quality-label">{{ __('injera.injera_stock_levels.grade_a') }}</div>
                <div class="quality-progress">
                    <div class="quality-fill grade-a" style="width: {{ ($statistics['quality_distribution']['A'] / $statistics['total_injera']) * 100 }}%"></div>
                </div>
                <div class="quality-value">{{ $statistics['quality_distribution']['A'] }}</div>
            </div>
            <div class="quality-bar">
                <div class="quality-label">{{ __('injera.injera_stock_levels.grade_b') }}</div>
                <div class="quality-progress">
                    <div class="quality-fill grade-b" style="width: {{ ($statistics['quality_distribution']['B'] / $statistics['total_injera']) * 100 }}%"></div>
                </div>
                <div class="quality-value">{{ $statistics['quality_distribution']['B'] }}</div>
            </div>
            <div class="quality-bar">
                <div class="quality-label">{{ __('injera.injera_stock_levels.grade_c') }}</div>
                <div class="quality-progress">
                    <div class="quality-fill grade-c" style="width: {{ ($statistics['quality_distribution']['C'] / $statistics['total_injera']) * 100 }}%"></div>
                </div>
                <div class="quality-value">{{ $statistics['quality_distribution']['C'] }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Add Stock Modal -->
<div id="addStockModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>{{ __('injera.injera_stock_levels.add_stock') }}</h3>
            <button class="modal-close" onclick="closeAddStockModal()">&times;</button>
        </div>
        <form id="addStockForm" onsubmit="addStock(event)">
            <div class="modal-body">
                <div class="form-group">
                    <label for="batchId">{{ __('injera.injera_stock_levels.batch_id') }} *</label>
                    <select id="batchId" name="batch_id" required>
                        <option value="">{{ __('injera.injera_stock_levels.select_batch') }}</option>
                        <option value="1">INJ-2025-001 - Weekend Production</option>
                        <option value="2">INJ-2025-002 - Premium Teff Batch</option>
                        <option value="3">INJ-2025-003 - Medium Daily Batch</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="quantity">{{ __('injera.injera_stock_levels.quantity') }} *</label>
                    <input type="number" id="quantity" name="quantity" min="1" required>
                </div>
                <div class="form-group">
                    <label for="qualityGrade">{{ __('injera.injera_stock_levels.quality_grade') }} *</label>
                    <select id="qualityGrade" name="quality_grade" required>
                        <option value="">{{ __('injera.injera_stock_levels.select_quality') }}</option>
                        <option value="A">{{ __('injera.injera_stock_levels.grade_a') }}</option>
                        <option value="B">{{ __('injera.injera_stock_levels.grade_b') }}</option>
                        <option value="C">{{ __('injera.injera_stock_levels.grade_c') }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="storageLocation">{{ __('injera.injera_stock_levels.storage_location') }} *</label>
                    <input type="text" id="storageLocation" name="storage_location" required>
                </div>
                <div class="form-group">
                    <label for="expiryDate">{{ __('injera.injera_stock_levels.expiry_date') }} *</label>
                    <input type="date" id="expiryDate" name="expiry_date" required>
                </div>
                <div class="form-group">
                    <label for="notes">{{ __('injera.injera_stock_levels.notes') }}</label>
                    <textarea id="notes" name="notes" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeAddStockModal()">
                    {{ __('injera.injera_stock_levels.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary">
                    {{ __('injera.injera_stock_levels.add_stock') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Update Stock Modal -->
<div id="updateStockModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>{{ __('injera.injera_stock_levels.update_stock') }}</h3>
            <button class="modal-close" onclick="closeUpdateStockModal()">&times;</button>
        </div>
        <form id="updateStockForm" onsubmit="updateStockSubmit(event)">
            <div class="modal-body">
                <div class="form-group">
                    <label for="updateCurrentStock">{{ __('injera.injera_stock_levels.current_stock') }} *</label>
                    <input type="number" id="updateCurrentStock" name="current_stock" min="0" required>
                </div>
                <div class="form-group">
                    <label for="updateReservedStock">{{ __('injera.injera_stock_levels.reserved_stock') }} *</label>
                    <input type="number" id="updateReservedStock" name="reserved_stock" min="0" required>
                </div>
                <div class="form-group">
                    <label for="updateAvailableStock">{{ __('injera.injera_stock_levels.available_stock') }} *</label>
                    <input type="number" id="updateAvailableStock" name="available_stock" min="0" required>
                </div>
                <div class="form-group">
                    <label for="updateStorageLocation">{{ __('injera.injera_stock_levels.storage_location') }} *</label>
                    <input type="text" id="updateStorageLocation" name="storage_location" required>
                </div>
                <div class="form-group">
                    <label for="updateNotes">{{ __('injera.injera_stock_levels.notes') }}</label>
                    <textarea id="updateNotes" name="notes" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeUpdateStockModal()">
                    {{ __('injera.injera_stock_levels.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary">
                    {{ __('injera.injera_stock_levels.update_stock') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reserve Stock Modal -->
<div id="reserveStockModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>{{ __('injera.injera_stock_levels.reserve_stock') }}</h3>
            <button class="modal-close" onclick="closeReserveStockModal()">&times;</button>
        </div>
        <form id="reserveStockForm" onsubmit="reserveStockSubmit(event)">
            <div class="modal-body">
                <div class="form-group">
                    <label for="orderId">{{ __('injera.injera_stock_levels.order_id') }} *</label>
                    <input type="number" id="orderId" name="order_id" required>
                </div>
                <div class="form-group">
                    <label for="reserveQuantity">{{ __('injera.injera_stock_levels.quantity_to_reserve') }} *</label>
                    <input type="number" id="reserveQuantity" name="quantity" min="1" required>
                </div>
                <div class="form-group">
                    <label for="reservationNotes">{{ __('injera.injera_stock_levels.reservation_notes') }}</label>
                    <textarea id="reservationNotes" name="reservation_notes" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeReserveStockModal()">
                    {{ __('injera.injera_stock_levels.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary">
                    {{ __('injera.injera_stock_levels.reserve_stock') }}
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('styles')
@vite(['resources/css/admin/injera/injera-stock-levels.css'])
@endpush

@push('scripts')
@vite(['resources/js/admin/injera/injera-stock-levels.js'])
@endpush
