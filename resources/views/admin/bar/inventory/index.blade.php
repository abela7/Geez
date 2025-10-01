@extends('layouts.admin')

@section('title', __('bar.inventory.title') . ' - ' . config('app.name'))
@section('page_title', __('bar.inventory.title'))

@push('styles')
{{-- CSS styles will be loaded via main layout --}}
@endpush

@section('content')
<div class="bar-inventory-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">{{ __('bar.inventory.title') }}</h1>
            <p class="page-subtitle">{{ __('bar.inventory.subtitle') }}</p>
        </div>
        <div class="header-actions">
            <button type="button" class="btn btn-secondary" onclick="refreshData()">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                {{ __('common.refresh_data') }}
            </button>
            <button type="button" class="btn btn-secondary" onclick="exportData()">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                {{ __('bar.inventory.export_beverages') }}
            </button>
            <button type="button" class="btn btn-primary" onclick="showAddBeverageModal()">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                {{ __('bar.inventory.add_beverage') }}
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="card summary-card">
            <div class="card-body">
                <div class="summary-icon summary-icon-primary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div class="summary-content">
                    <div class="summary-value">{{ number_format($stats['total_beverages'] ?? 0) }}</div>
                    <div class="summary-label">{{ __('bar.inventory.total_beverages') }}</div>
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
                    <div class="summary-value">{{ number_format($stats['low_stock_count'] ?? 0) }}</div>
                    <div class="summary-label">{{ __('bar.low_stock') }}</div>
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
                    <div class="summary-value">{{ number_format($stats['out_of_stock_count'] ?? 0) }}</div>
                    <div class="summary-label">{{ __('bar.out_of_stock') }}</div>
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
                    <div class="summary-value">${{ number_format($stats['total_value'] ?? 0, 2) }}</div>
                    <div class="summary-label">{{ __('bar.inventory.total_value') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card filters-card">
        <div class="card-header">
            <h3 class="card-title">{{ __('common.filters') }}</h3>
            <button type="button" class="btn btn-link btn-sm" onclick="clearFilters()">
                {{ __('bar.clear_filters') }}
            </button>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.bar.inventory.index') }}" class="filters-form">
                <div class="filters-grid">
                    <!-- Search -->
                    <div class="filter-group">
                        <label for="search" class="filter-label">{{ __('bar.search_beverages') }}</label>
                        <div class="search-input-wrapper">
                            <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input type="text" id="search" name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="{{ __('bar.search_beverages') }}"
                                   class="form-input search-input">
                        </div>
                    </div>

                    <!-- Beverage Type Filter -->
                    <div class="filter-group">
                        <label for="beverage_type" class="filter-label">{{ __('bar.filter_by_type') }}</label>
                        <select id="beverage_type" name="beverage_type" class="form-select">
                            <option value="">{{ __('bar.all_types') }}</option>
                            <option value="spirits" {{ request('beverage_type') == 'spirits' ? 'selected' : '' }}>
                                {{ __('bar.inventory.spirits') }}
                            </option>
                            <option value="beer" {{ request('beverage_type') == 'beer' ? 'selected' : '' }}>
                                {{ __('bar.inventory.beer') }}
                            </option>
                            <option value="wine" {{ request('beverage_type') == 'wine' ? 'selected' : '' }}>
                                {{ __('bar.inventory.wine') }}
                            </option>
                            <option value="cocktail_mixers" {{ request('beverage_type') == 'cocktail_mixers' ? 'selected' : '' }}>
                                {{ __('bar.inventory.cocktail_mixers') }}
                            </option>
                            <option value="soft_drinks" {{ request('beverage_type') == 'soft_drinks' ? 'selected' : '' }}>
                                {{ __('bar.inventory.soft_drinks') }}
                            </option>
                            <option value="coffee" {{ request('beverage_type') == 'coffee' ? 'selected' : '' }}>
                                {{ __('bar.inventory.coffee') }}
                            </option>
                        </select>
                    </div>

                    <!-- Storage Location Filter -->
                    <div class="filter-group">
                        <label for="storage_location" class="filter-label">{{ __('bar.inventory.storage_location') }}</label>
                        <select id="storage_location" name="storage_location" class="form-select">
                            <option value="">{{ __('common.all_locations') }}</option>
                            <option value="main_bar" {{ request('storage_location') == 'main_bar' ? 'selected' : '' }}>
                                {{ __('bar.inventory.main_bar') }}
                            </option>
                            <option value="back_bar" {{ request('storage_location') == 'back_bar' ? 'selected' : '' }}>
                                {{ __('bar.inventory.back_bar') }}
                            </option>
                            <option value="wine_cellar" {{ request('storage_location') == 'wine_cellar' ? 'selected' : '' }}>
                                {{ __('bar.inventory.wine_cellar') }}
                            </option>
                            <option value="beer_cooler" {{ request('storage_location') == 'beer_cooler' ? 'selected' : '' }}>
                                {{ __('bar.inventory.beer_cooler') }}
                            </option>
                            <option value="spirit_cabinet" {{ request('storage_location') == 'spirit_cabinet' ? 'selected' : '' }}>
                                {{ __('bar.inventory.spirit_cabinet') }}
                            </option>
                        </select>
                    </div>

                    <!-- Stock Status Filter -->
                    <div class="filter-group">
                        <label for="stock_status" class="filter-label">{{ __('bar.filter_by_stock') }}</label>
                        <select id="stock_status" name="stock_status" class="form-select">
                            <option value="">{{ __('common.all_statuses') }}</option>
                            <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>
                                {{ __('bar.in_stock') }}
                            </option>
                            <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>
                                {{ __('bar.low_stock') }}
                            </option>
                            <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>
                                {{ __('bar.out_of_stock') }}
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
                <span id="selectedCount">0</span> {{ __('common.selected_items') }}
            </span>
            <div class="bulk-actions-buttons">
                <button type="button" class="btn btn-secondary btn-sm" onclick="bulkUpdateStock()">
                    {{ __('bar.inventory.stock_take') }}
                </button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="bulkExport()">
                    {{ __('bar.export') }}
                </button>
                <button type="button" class="btn btn-link btn-sm" onclick="clearSelection()">
                    {{ __('common.clear_selection') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Beverages Table -->
    <div class="card table-card">
        <div class="card-header">
            <h3 class="card-title">{{ __('bar.beverages') }}</h3>
            <div class="table-controls">
                <div class="sort-controls">
                    <label for="sort_by" class="sr-only">{{ __('common.sort_by') }}</label>
                    <select id="sort_by" name="sort_by" class="form-select form-select-sm" onchange="updateSort()">
                        <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>{{ __('bar.inventory.beverage_name') }}</option>
                        <option value="beverage_type" {{ request('sort_by') == 'beverage_type' ? 'selected' : '' }}>{{ __('bar.inventory.beverage_type') }}</option>
                        <option value="current_stock" {{ request('sort_by') == 'current_stock' ? 'selected' : '' }}>{{ __('bar.inventory.current_stock') }}</option>
                        <option value="brand" {{ request('sort_by') == 'brand' ? 'selected' : '' }}>{{ __('bar.inventory.brand') }}</option>
                        <option value="storage_location" {{ request('sort_by') == 'storage_location' ? 'selected' : '' }}>{{ __('bar.inventory.storage_location') }}</option>
                    </select>
                    <button type="button" class="btn btn-link btn-sm" onclick="toggleSortDirection()" 
                            title="{{ request('sort_direction') == 'desc' ? __('common.sort_ascending') : __('common.sort_descending') }}">
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
            @if(isset($beverages) && $beverages->count() > 0)
                <div class="table-responsive">
                    <table class="table beverages-table">
                        <thead>
                            <tr>
                                <th class="table-checkbox">
                                    <input type="checkbox" id="selectAll" class="form-checkbox" onchange="toggleSelectAll()">
                                </th>
                                <th>{{ __('bar.inventory.beverage_name') }}</th>
                                <th>{{ __('bar.inventory.beverage_type') }}</th>
                                <th>{{ __('bar.inventory.brand') }}</th>
                                <th>{{ __('bar.inventory.current_stock') }}</th>
                                <th>{{ __('bar.inventory.storage_location') }}</th>
                                <th>{{ __('bar.inventory.abv') }}</th>
                                <th>{{ __('common.status') }}</th>
                                <th>{{ __('common.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($beverages as $beverage)
                                <tr class="table-row" data-beverage-id="{{ $beverage->id }}">
                                    <td class="table-checkbox">
                                        <input type="checkbox" class="form-checkbox beverage-checkbox" 
                                               value="{{ $beverage->id }}" onchange="updateBulkActions()">
                                    </td>
                                    <td class="beverage-name-cell">
                                        <div class="beverage-info">
                                            <div class="beverage-name">{{ $beverage->name }}</div>
                                            @if($beverage->barcode)
                                                <div class="beverage-barcode">{{ $beverage->barcode }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="type-badge type-{{ $beverage->beverage_type }}">
                                            {{ __('bar.inventory.' . $beverage->beverage_type) }}
                                        </span>
                                    </td>
                                    <td>{{ $beverage->brand ?? 'N/A' }}</td>
                                    <td class="stock-cell">
                                        <div class="stock-info">
                                            <div class="stock-amount">{{ number_format($beverage->current_stock, 2) }} {{ $beverage->unit }}</div>
                                            @if($beverage->minimum_stock > 0)
                                                <div class="minimum-stock">Min: {{ number_format($beverage->minimum_stock, 2) }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="location-badge">
                                            {{ __('bar.inventory.' . $beverage->storage_location) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($beverage->abv > 0)
                                            <span class="abv-badge">{{ $beverage->abv }}%</span>
                                        @else
                                            <span class="non-alcoholic-badge">{{ __('bar.non_alcoholic') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="status-badge status-{{ $beverage->stock_status }}">
                                            {{ __('bar.' . $beverage->stock_status) }}
                                        </span>
                                    </td>
                                    <td class="actions-cell">
                                        <div class="action-buttons">
                                            <button type="button" class="btn btn-link btn-sm" 
                                                    onclick="showBeverageDetails({{ $beverage->id }})"
                                                    title="{{ __('bar.view_details') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </button>
                                            <button type="button" class="btn btn-link btn-sm" 
                                                    onclick="showEditBeverageModal({{ $beverage->id }})"
                                                    title="{{ __('bar.edit') }}">
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
                @if(method_exists($beverages, 'links'))
                    <div class="pagination-wrapper">
                        <div class="pagination-info">
                            {{ __('common.showing') }} 
                            {{ $beverages->firstItem() }} - {{ $beverages->lastItem() }} 
                            {{ __('common.of') }} 
                            {{ $beverages->total() }} 
                            {{ __('bar.beverages') }}
                        </div>
                        {{ $beverages->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <h3 class="empty-state-title">{{ __('bar.inventory.no_beverages_found') }}</h3>
                    <p class="empty-state-description">{{ __('bar.inventory.no_beverages_description') }}</p>
                    <button type="button" class="btn btn-primary" onclick="showAddBeverageModal()">
                        {{ __('bar.inventory.add_beverage') }}
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Beverage Details Drawer -->
<div class="drawer" id="beverageDetailsDrawer">
    <div class="drawer-overlay" onclick="closeDrawer()"></div>
    <div class="drawer-content">
        <div class="drawer-header">
            <h3 class="drawer-title">{{ __('bar.inventory.beverage_details') }}</h3>
            <button type="button" class="drawer-close" onclick="closeDrawer()" 
                    aria-label="{{ __('common.close_drawer') }}">
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
@vite(['resources/js/admin/bar-inventory.js'])
@endpush
