@extends('layouts.admin')

@section('title', __('inventory.suppliers.title') . ' - ' . config('app.name'))
@section('page_title', __('inventory.suppliers.title'))

@push('styles')
@vite(['resources/css/admin/inventory-suppliers.css'])
@endpush

@section('content')
<div class="suppliers-container" x-data="suppliersPage()">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">{{ __('inventory.suppliers.title') }}</h1>
            <p class="page-subtitle">{{ __('inventory.suppliers.subtitle') }}</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary" @click="openAddSupplierDrawer()">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                {{ __('inventory.suppliers.add_supplier') }}
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card">
            <div class="card-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div class="card-content">
                <div class="card-value">{{ number_format($totalSuppliers) }}</div>
                <div class="card-label">{{ __('inventory.suppliers.total_suppliers') }}</div>
            </div>
        </div>

        <div class="summary-card">
            <div class="card-icon active">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="card-content">
                <div class="card-value">{{ number_format($activeSuppliers) }}</div>
                <div class="card-label">{{ __('inventory.suppliers.active_suppliers') }}</div>
            </div>
        </div>

        <div class="summary-card">
            <div class="card-icon inactive">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="card-content">
                <div class="card-value">{{ number_format($inactiveSuppliers) }}</div>
                <div class="card-label">{{ __('inventory.suppliers.inactive_suppliers') }}</div>
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
                {{ __('inventory.suppliers.filters') }}
                <svg class="chevron" :class="{ 'rotate-180': showFilters }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div class="search-box">
                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" 
                       placeholder="{{ __('inventory.suppliers.search_placeholder') }}" 
                       value="{{ request('search') }}"
                       @input="debounceSearch($event.target.value)">
            </div>
        </div>

        <div class="filters-content" x-show="showFilters" x-collapse>
            <div class="filter-group">
                <label>{{ __('inventory.suppliers.filter_by_status') }}</label>
                <select id="status-filter">
                    <option value="all">{{ __('inventory.suppliers.all_statuses') }}</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                        {{ __('inventory.suppliers.supplier_statuses.active') }}
                    </option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                        {{ __('inventory.suppliers.supplier_statuses.inactive') }}
                    </option>
                </select>
            </div>

            <div class="filter-actions">
                <button type="button" class="btn btn-secondary" onclick="clearFilters()">
                    {{ __('inventory.suppliers.clear_filters') }}
                </button>
                <button type="button" class="btn btn-primary" onclick="applyFilters()">
                    {{ __('inventory.suppliers.apply_filters') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Suppliers Table -->
    <div class="table-card">
        <div class="card-header">
            <h3 class="card-title">{{ __('inventory.suppliers.suppliers') }}</h3>
            <div class="table-info">
                <span class="info-text">
                    {{ __('inventory.suppliers.showing') }} {{ $suppliers->firstItem() ?? 0 }} - {{ $suppliers->lastItem() ?? 0 }} 
                    {{ __('inventory.suppliers.of') }} {{ $suppliers->total() }} {{ __('inventory.suppliers.suppliers') }}
                </span>
            </div>
        </div>

        <div class="card-body">
            @if($suppliers->count() > 0)
                <div class="table-responsive">
                    <table class="table suppliers-table">
                        <thead>
                            <tr>
                                <th>{{ __('inventory.suppliers.supplier_name') }}</th>
                                <th>{{ __('inventory.suppliers.contact_person') }}</th>
                                <th>{{ __('inventory.suppliers.phone_email') }}</th>
                                <th>{{ __('inventory.suppliers.items_supplied') }}</th>
                                <th>{{ __('inventory.suppliers.status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($suppliers as $supplier)
                                <tr class="clickable-row" @click="openSupplierDetails({{ $supplier->id }})">
                                    <td class="supplier-cell">
                                        <div class="supplier-info">
                                            <div class="supplier-name">{{ $supplier->name }}</div>
                                            @if($supplier->address)
                                                <div class="supplier-address">{{ Str::limit($supplier->address, 50) }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="contact-cell">
                                        @if($supplier->contact_person)
                                            <div class="contact-info">
                                                <div class="contact-name">{{ $supplier->contact_person }}</div>
                                            </div>
                                        @else
                                            <span class="text-muted">{{ __('inventory.suppliers.no_contact') }}</span>
                                        @endif
                                    </td>
                                    <td class="contact-details-cell">
                                        <div class="contact-details">
                                            @if($supplier->phone)
                                                <div class="contact-phone">{{ $supplier->phone }}</div>
                                            @endif
                                            @if($supplier->email)
                                                <div class="contact-email">{{ $supplier->email }}</div>
                                            @endif
                                            @if(!$supplier->phone && !$supplier->email)
                                                <span class="text-muted">{{ __('inventory.suppliers.no_contact_info') }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="items-cell">
                                        <div class="items-count">
                                            <span class="count-badge">{{ $supplier->inventory_items_count ?? 0 }}</span>
                                            <span class="count-label">{{ __('inventory.suppliers.items') }}</span>
                                        </div>
                                    </td>
                                    <td class="status-cell">
                                        <span class="status-badge status-{{ $supplier->status }}">
                                            {{ __('inventory.suppliers.supplier_statuses.' . $supplier->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($suppliers->hasPages())
                    <div class="pagination-wrapper">
                        {{ $suppliers->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="empty-state">
                    <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <h3 class="empty-state-title">{{ __('inventory.suppliers.no_suppliers_found') }}</h3>
                    <p class="empty-state-description">{{ __('inventory.suppliers.no_suppliers_description') }}</p>
                    <button class="btn btn-primary" @click="openAddSupplierDrawer()">
                        {{ __('inventory.suppliers.add_first_supplier') }}
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Supplier Details Drawer -->
    <div class="drawer-overlay" x-show="showSupplierDrawer" x-cloak @click="closeSupplierDrawer()">
        <div class="drawer-content" @click.stop>
            <div class="drawer-header">
                <h2 class="drawer-title">{{ __('inventory.suppliers.supplier_drawer_title') }}</h2>
                <button class="drawer-close" @click="closeSupplierDrawer()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="drawer-body">
                <div x-show="loadingSupplier" class="supplier-detail-loading">
                    <div class="loading-spinner"></div>
                    <p>{{ __('inventory.suppliers.loading_suppliers') }}</p>
                </div>

                <div x-show="!loadingSupplier && selectedSupplier" class="supplier-details">
                    <!-- Details will be populated by JavaScript -->
                </div>
            </div>

            <div class="drawer-footer" x-show="!loadingSupplier && selectedSupplier">
                <button class="btn btn-secondary" @click="editSupplier()">
                    {{ __('inventory.suppliers.edit') }}
                </button>
                <button class="btn btn-primary" @click="createPOForSupplier()">
                    {{ __('inventory.suppliers.create_po_for_supplier') }}
                </button>
                <button class="btn btn-danger" @click="deleteSupplier()">
                    {{ __('inventory.suppliers.delete') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Add/Edit Supplier Drawer -->
    <div class="drawer-overlay" x-show="showAddSupplierDrawer" x-cloak @click="closeAddSupplierDrawer()">
        <div class="drawer-content" @click.stop>
            <div class="drawer-header">
                <h2 class="drawer-title" x-text="editingSupplier ? '{{ __('inventory.suppliers.edit_supplier_title') }}' : '{{ __('inventory.suppliers.add_supplier_title') }}'"></h2>
                <button class="drawer-close" @click="closeAddSupplierDrawer()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="drawer-body">
                <form class="supplier-form" @submit.prevent="saveSupplier()">
                    <div class="form-group">
                        <label for="supplier-name">{{ __('inventory.suppliers.supplier_name_field') }} *</label>
                        <input type="text" 
                               id="supplier-name" 
                               x-model="supplierForm.name" 
                               placeholder="{{ __('inventory.suppliers.supplier_name_help') }}"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="contact-person">{{ __('inventory.suppliers.contact_person_field') }}</label>
                        <input type="text" 
                               id="contact-person" 
                               x-model="supplierForm.contact_person" 
                               placeholder="{{ __('inventory.suppliers.contact_person_help') }}">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">{{ __('inventory.suppliers.phone_field') }}</label>
                            <input type="tel" 
                                   id="phone" 
                                   x-model="supplierForm.phone" 
                                   placeholder="{{ __('inventory.suppliers.phone_help') }}">
                        </div>

                        <div class="form-group">
                            <label for="email">{{ __('inventory.suppliers.email_field') }}</label>
                            <input type="email" 
                                   id="email" 
                                   x-model="supplierForm.email" 
                                   placeholder="{{ __('inventory.suppliers.email_help') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">{{ __('inventory.suppliers.address_field') }}</label>
                        <textarea id="address" 
                                  x-model="supplierForm.address" 
                                  rows="3"
                                  placeholder="{{ __('inventory.suppliers.address_help') }}"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="status">{{ __('inventory.suppliers.status') }} *</label>
                        <select id="status" x-model="supplierForm.status" required>
                            <option value="">{{ __('inventory.suppliers.select_status') }}</option>
                            <option value="active">{{ __('inventory.suppliers.supplier_statuses.active') }}</option>
                            <option value="inactive">{{ __('inventory.suppliers.supplier_statuses.inactive') }}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="notes">{{ __('inventory.suppliers.notes_field') }}</label>
                        <textarea id="notes" 
                                  x-model="supplierForm.notes" 
                                  rows="4"
                                  placeholder="{{ __('inventory.suppliers.notes_help') }}"></textarea>
                    </div>
                </form>
            </div>

            <div class="drawer-footer">
                <button type="button" class="btn btn-secondary" @click="closeAddSupplierDrawer()">
                    {{ __('inventory.suppliers.cancel') }}
                </button>
                <button type="button" class="btn btn-primary" @click="saveSupplier()">
                    {{ __('inventory.suppliers.save_supplier') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/admin/inventory-suppliers.js'])
@endpush
