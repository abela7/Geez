@extends('layouts.admin')

@section('title', __('inventory.purchasing.title') . ' - ' . config('app.name'))
@section('page_title', __('inventory.purchasing.title'))

@push('styles')
@vite(['resources/css/admin/inventory-purchasing.css'])
@endpush

@section('content')
<div class="purchasing-container" x-data="purchasingPage()">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">{{ __('inventory.purchasing.title') }}</h1>
            <p class="page-subtitle">{{ __('inventory.purchasing.subtitle') }}</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary" @click="openAddPODrawer()">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                {{ __('inventory.purchasing.new_purchase_order') }}
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
                <div class="card-value">{{ number_format($totalPOs) }}</div>
                <div class="card-label">{{ __('inventory.purchasing.total_purchase_orders') }}</div>
            </div>
        </div>

        <div class="summary-card">
            <div class="card-icon draft">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
            </div>
            <div class="card-content">
                <div class="card-value">{{ number_format($draftPOs) }}</div>
                <div class="card-label">{{ __('inventory.purchasing.draft_pos') }}</div>
            </div>
        </div>

        <div class="summary-card">
            <div class="card-icon sent">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </div>
            <div class="card-content">
                <div class="card-value">{{ number_format($sentPOs) }}</div>
                <div class="card-label">{{ __('inventory.purchasing.sent_pos') }}</div>
            </div>
        </div>

        <div class="summary-card">
            <div class="card-icon value">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                </svg>
            </div>
            <div class="card-content">
                <div class="card-value">${{ number_format($totalValue, 0) }}</div>
                <div class="card-label">{{ __('inventory.purchasing.total_value') }}</div>
            </div>
        </div>
    </div>

    <!-- Filters Panel -->
    <div class="filters-section" x-data="{ showFilters: false }">
        <div class="filters-header">
            <button @click="showFilters = !showFilters" class="filters-toggle">
                <svg class="filter-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"/>
                </svg>
                {{ __('common.filters') }}
                <svg class="chevron" :class="{ 'rotate-180': showFilters }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div class="search-box">
                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" 
                       placeholder="{{ __('inventory.purchasing.search_po') }}" 
                       value="{{ request('search') }}"
                       onchange="applyPOFilters()">
            </div>
        </div>

        <div x-show="showFilters" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 max-h-0"
             x-transition:enter-end="opacity-100 max-h-40"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 max-h-40"
             x-transition:leave-end="opacity-0 max-h-0"
             class="filters-content">
            <div class="filter-group">
                <label>{{ __('inventory.purchasing.filter_by_supplier') }}</label>
                <select onchange="applyPOFilters()">
                    <option value="all">{{ __('inventory.purchasing.all_suppliers') }}</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier }}" {{ request('supplier') === $supplier ? 'selected' : '' }}>
                            {{ $supplier }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label>{{ __('inventory.purchasing.filter_by_status') }}</label>
                <select onchange="applyPOFilters()">
                    <option value="all">{{ __('inventory.purchasing.all_statuses') }}</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                            {{ __('inventory.purchasing.po_statuses.' . $status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label>{{ __('inventory.purchasing.filter_by_date') }}</label>
                <div class="date-range">
                    <input type="date" 
                           placeholder="{{ __('inventory.purchasing.order_date_from') }}"
                           value="{{ request('order_date_from') }}"
                           onchange="applyPOFilters()">
                    <input type="date" 
                           placeholder="{{ __('inventory.purchasing.order_date_to') }}"
                           value="{{ request('order_date_to') }}"
                           onchange="applyPOFilters()">
                </div>
            </div>

            <div class="filter-actions">
                <button onclick="clearPOFilters()" class="btn btn-secondary">
                    {{ __('common.clear_filters') }}
                </button>
                <button onclick="applyPOFilters()" class="btn btn-primary">
                    {{ __('common.apply_filters') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Purchase Orders Table -->
    <div class="card table-card">
        <div class="card-header">
            <h3 class="card-title">{{ __('inventory.purchasing.title') }}</h3>
            <div class="table-info">
                <span class="info-text">{{ __('inventory.purchasing.click_row_details') }}</span>
            </div>
        </div>
        <div class="card-body">
            @if(count($purchaseOrders->data) > 0)
                <div class="table-responsive">
                    <table class="table purchasing-table">
                        <thead>
                            <tr>
                                <th>{{ __('inventory.purchasing.po_number') }}</th>
                                <th>{{ __('inventory.purchasing.supplier') }}</th>
                                <th>{{ __('inventory.purchasing.order_date') }}</th>
                                <th>{{ __('inventory.purchasing.status') }}</th>
                                <th>{{ __('inventory.purchasing.total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchaseOrders->data as $po)
                                <tr class="table-row clickable-row" 
                                    data-po-id="{{ $po->id }}"
                                    @click="openPODetails({{ $po->id }})">
                                    <td class="po-number-cell">
                                        <div class="po-info">
                                            <div class="po-number">{{ $po->po_number }}</div>
                                            <div class="po-items">{{ $po->items_count }} {{ __('common.items') }}</div>
                                        </div>
                                    </td>
                                    <td class="supplier-cell">
                                        <div class="supplier-name">{{ $po->supplier_name }}</div>
                                    </td>
                                    <td class="date-cell">
                                        <div class="date-info">
                                            <div class="order-date">{{ $po->formatted_order_date }}</div>
                                            <div class="delivery-date">{{ __('inventory.purchasing.delivery_date') }}: {{ $po->formatted_delivery_date }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="po-status-badge {{ $po->status_badge_class }}">
                                            {{ __('inventory.purchasing.po_statuses.' . $po->status) }}
                                        </span>
                                    </td>
                                    <td class="total-cell">
                                        <div class="total-amount">${{ number_format($po->total_amount, 2) }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="empty-state-title">{{ __('inventory.purchasing.no_purchase_orders') }}</h3>
                    <p class="empty-state-description">{{ __('inventory.purchasing.no_purchase_orders_message') }}</p>
                    <button @click="openAddPODrawer()" class="btn btn-primary">
                        {{ __('inventory.purchasing.new_purchase_order') }}
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- PO Detail Drawer -->
    <div x-show="showPODrawer" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="drawer-overlay"
         @click="closePODrawer()">
        <div class="drawer-content" @click.stop>
            <div class="drawer-header">
                <h3 class="drawer-title">{{ __('inventory.purchasing.po_drawer_title') }}</h3>
                <button @click="closePODrawer()" class="drawer-close">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="drawer-body" x-show="selectedPO">
                <!-- PO details will be loaded here -->
                <div class="po-detail-loading" x-show="loadingPO">
                    <div class="loading-spinner"></div>
                    <p>{{ __('inventory.purchasing.loading_purchase_orders') }}</p>
                </div>
                
                <div x-show="!loadingPO && selectedPO" class="po-details">
                    <!-- Content will be populated by JavaScript -->
                </div>
            </div>
            <div class="drawer-footer" x-show="selectedPO && !loadingPO">
                <button @click="editPO()" class="btn btn-secondary">
                    {{ __('inventory.purchasing.edit') }}
                </button>
                <button @click="markPOReceived()" class="btn btn-success">
                    {{ __('inventory.purchasing.mark_received') }}
                </button>
                <button @click="deletePO()" class="btn btn-danger">
                    {{ __('inventory.purchasing.delete') }}
                </button>
                <button @click="closePODrawer()" class="btn btn-primary">
                    {{ __('inventory.purchasing.close') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Add/Edit PO Drawer -->
    <div x-show="showAddPODrawer" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="drawer-overlay"
         @click="closeAddPODrawer()">
        <div class="drawer-content large-drawer" @click.stop>
            <div class="drawer-header">
                <h3 class="drawer-title" x-text="editingPO ? '{{ __('inventory.purchasing.edit_po_title') }}' : '{{ __('inventory.purchasing.add_po_title') }}'"></h3>
                <button @click="closeAddPODrawer()" class="drawer-close">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="drawer-body">
                <form @submit.prevent="savePO()" class="po-form">
                    <div class="form-section">
                        <h4 class="section-title">{{ __('inventory.purchasing.supplier_info') }}</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label>{{ __('inventory.purchasing.select_supplier') }}</label>
                                <select x-model="poForm.supplier_id" required>
                                    <option value="">{{ __('inventory.purchasing.select_supplier') }}</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier }}">{{ $supplier }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h4 class="section-title">{{ __('inventory.purchasing.order_info') }}</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label>{{ __('inventory.purchasing.order_date_field') }}</label>
                                <input type="date" x-model="poForm.order_date" required>
                            </div>
                            <div class="form-group">
                                <label>{{ __('inventory.purchasing.expected_delivery') }}</label>
                                <input type="date" x-model="poForm.delivery_date" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h4 class="section-title">{{ __('inventory.purchasing.line_items') }}</h4>
                        <div class="line-items-container">
                            <template x-for="(item, index) in poForm.line_items" :key="index">
                                <div class="line-item-row">
                                    <div class="form-group item-select">
                                        <select x-model="item.item_id" required>
                                            <option value="">{{ __('inventory.purchasing.select_item') }}</option>
                                            <option value="1">Premium Ethiopian Coffee Beans (COFFEE001)</option>
                                            <option value="2">Berbere Spice Mix (SPICE001)</option>
                                            <option value="3">Teff Grain (GRAIN001)</option>
                                        </select>
                                    </div>
                                    <div class="form-group quantity-input">
                                        <input type="number" 
                                               x-model="item.quantity" 
                                               step="0.1" 
                                               min="0.1" 
                                               placeholder="{{ __('inventory.purchasing.enter_quantity') }}" 
                                               @input="calculateLineTotal(index)"
                                               required>
                                    </div>
                                    <div class="form-group price-input">
                                        <input type="number" 
                                               x-model="item.unit_price" 
                                               step="0.01" 
                                               min="0.01" 
                                               placeholder="{{ __('inventory.purchasing.enter_unit_price') }}" 
                                               @input="calculateLineTotal(index)"
                                               required>
                                    </div>
                                    <div class="line-total" x-text="'$' + (item.line_total || 0).toFixed(2)"></div>
                                    <button type="button" @click="removeLineItem(index)" class="btn btn-danger btn-sm">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                            <button type="button" @click="addLineItem()" class="btn btn-secondary btn-sm">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                {{ __('inventory.purchasing.add_line_item') }}
                            </button>
                        </div>
                    </div>

                    <div class="form-section">
                        <h4 class="section-title">{{ __('inventory.purchasing.financial_summary') }}</h4>
                        <div class="totals-section">
                            <div class="total-row">
                                <span>{{ __('inventory.purchasing.subtotal') }}</span>
                                <span x-text="'$' + poForm.subtotal.toFixed(2)"></span>
                            </div>
                            <div class="total-row">
                                <span>{{ __('inventory.purchasing.tax_amount') }}</span>
                                <span x-text="'$' + poForm.tax_amount.toFixed(2)"></span>
                            </div>
                            <div class="total-row">
                                <span>{{ __('inventory.purchasing.shipping_cost') }}</span>
                                <input type="number" 
                                       x-model="poForm.shipping_cost" 
                                       step="0.01" 
                                       min="0" 
                                       @input="calculateTotals()"
                                       class="total-input">
                            </div>
                            <div class="total-row grand-total">
                                <span>{{ __('inventory.purchasing.grand_total') }}</span>
                                <span x-text="'$' + poForm.grand_total.toFixed(2)"></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-group">
                            <label>{{ __('inventory.purchasing.special_instructions') }}</label>
                            <textarea x-model="poForm.notes" 
                                      rows="3" 
                                      placeholder="{{ __('inventory.purchasing.special_instructions') }}"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="drawer-footer">
                <button @click="closeAddPODrawer()" type="button" class="btn btn-secondary">
                    {{ __('inventory.purchasing.cancel') }}
                </button>
                <button @click="savePO()" type="button" class="btn btn-primary">
                    {{ __('inventory.purchasing.save_po') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/admin/inventory-purchasing.js'])
@endpush
