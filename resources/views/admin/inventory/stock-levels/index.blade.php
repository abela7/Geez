@extends('layouts.admin')

@section('title', __('inventory.stock_levels.title') . ' - ' . config('app.name'))
@section('page_title', __('inventory.stock_levels.title'))

@push('styles')
@vite(['resources/css/admin/inventory-stock-levels.css'])
@endpush

@section('content')
<div class="stock-levels-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">{{ __('inventory.stock_levels.title') }}</h1>
            <p class="page-subtitle">{{ __('inventory.stock_levels.subtitle') }}</p>
        </div>
        <div class="header-actions">
            <button type="button" class="btn btn-secondary" onclick="refreshData()">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                {{ __('inventory.stock_levels.refresh_data') }}
            </button>
            <button type="button" class="btn btn-secondary" onclick="exportData()">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                {{ __('inventory.stock_levels.export_data') }}
            </button>
            <button type="button" class="btn btn-primary" onclick="showAddItemModal()">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                {{ __('inventory.stock_levels.add_new_item') }}
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="card summary-card">
            <div class="card-body">
                <div class="summary-icon summary-icon-primary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div class="summary-content">
                    <div class="summary-value">{{ number_format($stats['total_items']) }}</div>
                    <div class="summary-label">{{ __('inventory.stock_levels.total_items') }}</div>
                </div>
            </div>
        </div>

        <div class="card summary-card">
            <div class="card-body">
                <div class="summary-icon summary-icon-warning">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="summary-content">
                    <div class="summary-value">{{ number_format($stats['low_stock_count']) }}</div>
                    <div class="summary-label">{{ __('inventory.stock_levels.low_stock_count') }}</div>
                </div>
            </div>
        </div>

        <div class="card summary-card">
            <div class="card-body">
                <div class="summary-icon summary-icon-danger">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <div class="summary-content">
                    <div class="summary-value">{{ number_format($stats['out_of_stock_count']) }}</div>
                    <div class="summary-label">{{ __('inventory.stock_levels.out_of_stock_count') }}</div>
                </div>
            </div>
        </div>

        <div class="card summary-card">
            <div class="card-body">
                <div class="summary-icon summary-icon-success">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="summary-content">
                    <div class="summary-value">${{ number_format($stats['total_value'], 2) }}</div>
                    <div class="summary-label">{{ __('inventory.stock_levels.total_value') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card filters-card">
        <div class="card-header">
            <h3 class="card-title">{{ __('inventory.stock_levels.filters') }}</h3>
            <button type="button" class="btn btn-link btn-sm" onclick="clearFilters()">
                {{ __('inventory.stock_levels.clear_filters') }}
            </button>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.inventory.stock-levels.index') }}" class="filters-form">
                <div class="filters-grid">
                    <!-- Search -->
                    <div class="filter-group">
                        <label for="search" class="filter-label">{{ __('inventory.stock_levels.search_items') }}</label>
                        <div class="search-input-wrapper">
                            <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input type="text" id="search" name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="{{ __('inventory.stock_levels.search_placeholder') }}"
                                   class="form-input search-input">
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="filter-group">
                        <label for="category" class="filter-label">{{ __('inventory.stock_levels.filter_category') }}</label>
                        <select id="category" name="category" class="form-select">
                            <option value="">{{ __('inventory.stock_levels.all_categories') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                    {{ __('inventory.stock_levels.category_' . $category) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Location Filter -->
                    <div class="filter-group">
                        <label for="location" class="filter-label">{{ __('inventory.stock_levels.filter_location') }}</label>
                        <select id="location" name="location" class="form-select">
                            <option value="">{{ __('inventory.stock_levels.all_locations') }}</option>
                            @foreach($locations as $location)
                                <option value="{{ $location }}" {{ request('location') == $location ? 'selected' : '' }}>
                                    {{ __('inventory.stock_levels.location_' . $location) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Supplier Filter -->
                    <div class="filter-group">
                        <label for="supplier_id" class="filter-label">{{ __('inventory.stock_levels.filter_supplier') }}</label>
                        <select id="supplier_id" name="supplier_id" class="form-select">
                            <option value="">{{ __('inventory.stock_levels.all_suppliers') }}</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="filter-group">
                        <label for="status_filter" class="filter-label">{{ __('inventory.stock_levels.filter_status') }}</label>
                        <select id="status_filter" name="status_filter" class="form-select">
                            <option value="">{{ __('inventory.stock_levels.all_statuses') }}</option>
                            <option value="low" {{ request('status_filter') == 'low' ? 'selected' : '' }}>
                                {{ __('inventory.stock_levels.status_low') }}
                            </option>
                            <option value="out" {{ request('status_filter') == 'out' ? 'selected' : '' }}>
                                {{ __('inventory.stock_levels.status_out') }}
                            </option>
                            <option value="critical" {{ request('status_filter') == 'critical' ? 'selected' : '' }}>
                                {{ __('inventory.stock_levels.status_critical') }}
                            </option>
                        </select>
                    </div>

                    <!-- Apply Filters Button -->
                    <div class="filter-group filter-actions">
                        <button type="submit" class="btn btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                            </svg>
                            {{ __('common.apply_filters') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions Bar (Hidden by default) -->
    <div class="bulk-actions-bar" id="bulkActionsBar" style="display: none;">
        <div class="bulk-actions-content">
            <span class="bulk-selection-count">
                <span id="selectedCount">0</span> {{ __('inventory.stock_levels.selected_items') }}
            </span>
            <div class="bulk-actions-buttons">
                <button type="button" class="btn btn-secondary btn-sm" onclick="bulkAdjustStock()">
                    {{ __('inventory.stock_levels.bulk_adjust') }}
                </button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="bulkTransferStock()">
                    {{ __('inventory.stock_levels.bulk_transfer') }}
                </button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="bulkExport()">
                    {{ __('inventory.stock_levels.bulk_export') }}
                </button>
                <button type="button" class="btn btn-link btn-sm" onclick="clearSelection()">
                    {{ __('common.clear_selection') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Stock Levels Table -->
    <div class="card table-card">
        <div class="card-header">
            <h3 class="card-title">{{ __('inventory.stock_levels.view_all_items') }}</h3>
            <div class="table-controls">
                <div class="sort-controls">
                    <label for="sort_by" class="sr-only">{{ __('inventory.stock_levels.sort_by') }}</label>
                    <select id="sort_by" name="sort_by" class="form-select form-select-sm" onchange="updateSort()">
                        <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>{{ __('inventory.stock_levels.item_name') }}</option>
                        <option value="current_stock" {{ request('sort_by') == 'current_stock' ? 'selected' : '' }}>{{ __('inventory.stock_levels.current_stock') }}</option>
                        <option value="reorder_level" {{ request('sort_by') == 'reorder_level' ? 'selected' : '' }}>{{ __('inventory.stock_levels.reorder_level') }}</option>
                        <option value="category" {{ request('sort_by') == 'category' ? 'selected' : '' }}>{{ __('inventory.stock_levels.category') }}</option>
                        <option value="location" {{ request('sort_by') == 'location' ? 'selected' : '' }}>{{ __('inventory.stock_levels.location') }}</option>
                    </select>
                    <button type="button" class="btn btn-link btn-sm" onclick="toggleSortDirection()" 
                            title="{{ request('sort_direction') == 'desc' ? __('inventory.stock_levels.sort_ascending') : __('inventory.stock_levels.sort_descending') }}">
                        @if(request('sort_direction') == 'desc')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                            </svg>
                        @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        @endif
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($items->count() > 0)
                <div class="table-responsive">
                    <table class="table stock-levels-table">
                        <thead>
                            <tr>
                                <th class="table-checkbox">
                                    <input type="checkbox" id="selectAll" class="form-checkbox" onchange="toggleSelectAll()">
                                </th>
                                <th>{{ __('inventory.stock_levels.item_name') }}</th>
                                <th>{{ __('inventory.stock_levels.category') }}</th>
                                <th>{{ __('inventory.stock_levels.current_stock') }}</th>
                                <th>{{ __('inventory.stock_levels.reorder_level') }}</th>
                                <th>{{ __('inventory.stock_levels.location') }}</th>
                                <th>{{ __('inventory.stock_levels.supplier') }}</th>
                                <th>{{ __('inventory.stock_levels.status') }}</th>
                                <th>{{ __('inventory.stock_levels.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr class="table-row" data-item-id="{{ $item->id }}">
                                    <td class="table-checkbox">
                                        <input type="checkbox" class="form-checkbox item-checkbox" 
                                               value="{{ $item->id }}" onchange="updateBulkActions()">
                                    </td>
                                    <td class="item-name-cell">
                                        <div class="item-info">
                                            <div class="item-name">{{ $item->name }}</div>
                                            <div class="item-code">{{ $item->code }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="category-badge category-{{ $item->category }}">
                                            {{ __('inventory.stock_levels.category_' . $item->category) }}
                                        </span>
                                    </td>
                                    <td class="stock-cell">
                                        <div class="stock-info">
                                            <div class="stock-amount">{{ number_format($item->current_stock, 2) }} {{ $item->unit }}</div>
                                            @if($item->reserved_stock > 0)
                                                <div class="reserved-stock">{{ number_format($item->reserved_stock, 2) }} {{ __('inventory.stock_levels.reserved_stock') }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ number_format($item->reorder_level, 2) }} {{ $item->unit }}</td>
                                    <td>
                                        <span class="location-badge">
                                            {{ __('inventory.stock_levels.location_' . $item->location) }}
                                        </span>
                                    </td>
                                    <td>{{ $item->supplier->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="status-badge status-{{ $item->stock_status }}">
                                            {{ __('inventory.stock_levels.status_' . $item->stock_status) }}
                                        </span>
                                    </td>
                                    <td class="actions-cell">
                                        <div class="action-buttons">
                                            <button type="button" class="btn btn-link btn-sm" 
                                                    onclick="showItemDetails({{ $item->id }})"
                                                    title="{{ __('inventory.stock_levels.view_details') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </button>
                                            <button type="button" class="btn btn-link btn-sm" 
                                                    onclick="showAdjustStockModal({{ $item->id }})"
                                                    title="{{ __('inventory.stock_levels.adjust_stock') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    <div class="pagination-info">
                        {{ __('inventory.stock_levels.showing') }} 
                        {{ $items->firstItem() }} - {{ $items->lastItem() }} 
                        {{ __('inventory.stock_levels.of') }} 
                        {{ $items->total() }} 
                        {{ __('inventory.stock_levels.items') }}
                    </div>
                    {{ $items->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h3 class="empty-state-title">{{ __('inventory.stock_levels.no_items_found') }}</h3>
                    <p class="empty-state-description">{{ __('inventory.stock_levels.no_items_description') }}</p>
                    <button type="button" class="btn btn-primary" onclick="showAddItemModal()">
                        {{ __('inventory.stock_levels.add_first_item') }}
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Item Details Drawer -->
<div class="drawer" id="itemDetailsDrawer">
    <div class="drawer-overlay" onclick="closeDrawer()"></div>
    <div class="drawer-content">
        <div class="drawer-header">
            <h3 class="drawer-title">{{ __('inventory.stock_levels.item_details') }}</h3>
            <button type="button" class="drawer-close" onclick="closeDrawer()" 
                    aria-label="{{ __('inventory.stock_levels.close_drawer') }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="drawer-body" id="drawerContent">
            <!-- Content will be loaded dynamically -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/admin/inventory-stock-levels.js'])
@endpush
