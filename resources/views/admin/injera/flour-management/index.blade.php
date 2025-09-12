@extends('layouts.admin')

@section('title', __('injera.flour_management.title') . ' - ' . config('app.name'))
@section('page_title', __('injera.flour_management.title'))

@push('styles')
@vite(['resources/css/admin/injera/flour-management.css'])
@endpush

@section('content')
<div class="flour-management-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">{{ __('injera.flour_management.title') }}</h1>
            <p class="page-subtitle">{{ __('injera.flour_management.subtitle') }}</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-secondary" onclick="exportFlours()">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                {{ __('injera.flour_management.export_data') }}
            </button>
            <button class="btn btn-primary" onclick="addFlour()">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                {{ __('injera.flour_management.add_flour') }}
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card">
            <div class="summary-card-header">
                <h3 class="summary-card-title">{{ __('injera.flour_management.total_flour_types') }}</h3>
                <svg class="summary-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <p class="summary-card-value">{{ $statistics['total_flour_types'] }}</p>
        </div>

        <div class="summary-card">
            <div class="summary-card-header">
                <h3 class="summary-card-title">{{ __('injera.flour_management.total_stock') }}</h3>
                <svg class="summary-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <p class="summary-card-value">{{ number_format($statistics['total_stock_kg'], 1) }} KG</p>
        </div>

        <div class="summary-card {{ $statistics['low_stock_items'] > 0 ? 'warning' : '' }}">
            <div class="summary-card-header">
                <h3 class="summary-card-title">{{ __('injera.flour_management.low_stock_items') }}</h3>
                <svg class="summary-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <p class="summary-card-value">{{ $statistics['low_stock_items'] }}</p>
        </div>

        <div class="summary-card">
            <div class="summary-card-header">
                <h3 class="summary-card-title">{{ __('injera.flour_management.total_value') }}</h3>
                <svg class="summary-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                </svg>
            </div>
            <p class="summary-card-value">${{ number_format($statistics['total_value'], 2) }}</p>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="filters-section">
        <div class="filters-container">
            <div class="search-filter">
                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" 
                       id="flourSearch" 
                       class="search-input" 
                       placeholder="{{ __('injera.flour_management.search_placeholder') }}"
                       value="{{ request('search') }}">
            </div>

            <div class="filter-group">
                <select id="flourTypeFilter" class="filter-select">
                    <option value="">{{ __('injera.flour_management.all_types') }}</option>
                    @foreach($flourTypes as $type)
                        <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>

                <select id="stockStatusFilter" class="filter-select">
                    <option value="">{{ __('injera.flour_management.all_statuses') }}</option>
                    <option value="in_stock" {{ request('status') === 'in_stock' ? 'selected' : '' }}>
                        {{ __('injera.flour_management.in_stock') }}
                    </option>
                    <option value="low_stock" {{ request('status') === 'low_stock' ? 'selected' : '' }}>
                        {{ __('injera.flour_management.low_stock') }}
                    </option>
                    <option value="out_of_stock" {{ request('status') === 'out_of_stock' ? 'selected' : '' }}>
                        {{ __('injera.flour_management.out_of_stock') }}
                    </option>
                </select>

                <button class="btn btn-secondary" onclick="clearFilters()">
                    {{ __('injera.flour_management.clear_filters') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Flour Inventory Table -->
    <div class="table-container">
        <div class="table-header">
            <h2 class="table-title">{{ __('injera.flour_management.flour_inventory') }}</h2>
            <div class="table-actions">
                <button class="btn btn-outline" onclick="refreshData()">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    {{ __('injera.flour_management.refresh') }}
                </button>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="sortable" data-sort="name">
                            {{ __('injera.flour_management.flour_name') }}
                            <svg class="sort-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                            </svg>
                        </th>
                        <th>{{ __('injera.flour_management.type') }}</th>
                        <th class="sortable" data-sort="current_stock">
                            {{ __('injera.flour_management.current_stock') }}
                            <svg class="sort-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                            </svg>
                        </th>
                        <th>{{ __('injera.flour_management.package_size') }}</th>
                        <th class="sortable" data-sort="price_per_kg">
                            {{ __('injera.flour_management.price_per_kg') }}
                            <svg class="sort-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                            </svg>
                        </th>
                        <th>{{ __('injera.flour_management.supplier') }}</th>
                        <th>{{ __('injera.flour_management.status') }}</th>
                        <th>{{ __('injera.flour_management.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($flours as $flour)
                    <tr class="table-row" data-flour-id="{{ $flour['id'] }}">
                        <td class="flour-name">
                            <div class="flour-info">
                                <span class="flour-title">{{ $flour['name'] }}</span>
                                @if($flour['notes'])
                                    <span class="flour-notes">{{ $flour['notes'] }}</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="flour-type-badge flour-type-{{ strtolower($flour['type']) }}">
                                {{ $flour['type'] }}
                            </span>
                        </td>
                        <td class="stock-info">
                            <div class="stock-value">
                                {{ number_format($flour['current_stock'], 1) }} KG
                            </div>
                            @if($flour['status'] === 'low_stock')
                                <span class="stock-warning">{{ __('injera.flour_management.low_stock') }}</span>
                            @endif
                        </td>
                        <td>{{ $flour['package_size'] }} KG</td>
                        <td class="price-info">
                            <div class="price-primary">${{ number_format($flour['price_per_kg'], 2) }}</div>
                            <div class="price-secondary">${{ number_format($flour['price_per_package'], 2) }}/pkg</div>
                        </td>
                        <td>{{ $flour['supplier_name'] }}</td>
                        <td>
                            <span class="status-badge status-{{ $flour['status'] }}">
                                {{ __('injera.flour_management.' . $flour['status']) }}
                            </span>
                        </td>
                        <td class="actions-cell">
                            <div class="action-buttons">
                                <button class="action-btn" onclick="updateStock({{ $flour['id'] }})" title="{{ __('injera.flour_management.update_stock') }}">
                                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                    </svg>
                                </button>
                                <button class="action-btn" onclick="editFlour({{ $flour['id'] }})" title="{{ __('injera.flour_management.edit') }}">
                                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button class="action-btn danger" onclick="deleteFlour({{ $flour['id'] }})" title="{{ __('injera.flour_management.delete') }}">
                                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
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
</div>

<!-- Add/Edit Flour Modal -->
<div id="flourModal" class="modal" style="display: none;">
    <div class="modal-overlay" onclick="closeFlourModal()"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">{{ __('injera.flour_management.add_flour') }}</h3>
            <button class="modal-close" onclick="closeFlourModal()">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form id="flourForm" class="modal-form">
            <div class="form-grid">
                <div class="form-group">
                    <label for="flourName">{{ __('injera.flour_management.flour_name') }} *</label>
                    <input type="text" id="flourName" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="flourType">{{ __('injera.flour_management.type') }} *</label>
                    <select id="flourType" name="type" required>
                        <option value="">{{ __('injera.flour_management.select_type') }}</option>
                        @foreach($flourTypes as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="packageSize">{{ __('injera.flour_management.package_size') }} (KG) *</label>
                    <input type="number" id="packageSize" name="package_size" step="0.1" min="0.1" required>
                </div>
                
                <div class="form-group">
                    <label for="pricePerPackage">{{ __('injera.flour_management.price_per_package') }} ($) *</label>
                    <input type="number" id="pricePerPackage" name="price_per_package" step="0.01" min="0" required>
                </div>
                
                <div class="form-group">
                    <label for="supplierName">{{ __('injera.flour_management.supplier') }} *</label>
                    <input type="text" id="supplierName" name="supplier_name" list="suppliersList" required>
                    <datalist id="suppliersList">
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier }}">
                        @endforeach
                    </datalist>
                </div>
                
                <div class="form-group">
                    <label for="currentStock">{{ __('injera.flour_management.current_stock') }} (KG) *</label>
                    <input type="number" id="currentStock" name="current_stock" step="0.1" min="0" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="flourNotes">{{ __('injera.flour_management.notes') }}</label>
                <textarea id="flourNotes" name="notes" rows="3"></textarea>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeFlourModal()">
                    {{ __('injera.flour_management.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary">
                    {{ __('injera.flour_management.save_flour') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Stock Update Modal -->
<div id="stockModal" class="modal" style="display: none;">
    <div class="modal-overlay" onclick="closeStockModal()"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3>{{ __('injera.flour_management.update_stock') }}</h3>
            <button class="modal-close" onclick="closeStockModal()">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form id="stockForm" class="modal-form">
            <input type="hidden" id="stockFlourId" name="flour_id">
            
            <div class="form-group">
                <label for="adjustmentType">{{ __('injera.flour_management.adjustment_type') }} *</label>
                <select id="adjustmentType" name="adjustment_type" required>
                    <option value="purchase">{{ __('injera.flour_management.purchase') }}</option>
                    <option value="usage">{{ __('injera.flour_management.usage') }}</option>
                    <option value="adjustment">{{ __('injera.flour_management.adjustment') }}</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="stockQuantity">{{ __('injera.flour_management.quantity') }} (KG) *</label>
                <input type="number" id="stockQuantity" name="quantity" step="0.1" required>
            </div>
            
            <div class="form-group">
                <label for="stockNotes">{{ __('injera.flour_management.notes') }}</label>
                <textarea id="stockNotes" name="notes" rows="2"></textarea>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeStockModal()">
                    {{ __('injera.flour_management.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary">
                    {{ __('injera.flour_management.update_stock') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/admin/injera/flour-management.js'])
@endpush
