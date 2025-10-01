@extends('layouts.admin')

@section('title', __('bar.suppliers.title') . ' - ' . config('app.name'))
@section('page_title', __('bar.suppliers.title'))

@push('styles')
    {{-- CSS styles will be loaded via main layout --}}
@endpush

@push('scripts')
    @vite('resources/js/admin/bar/suppliers.js')
@endpush

@section('content')
<div class="suppliers-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('bar.suppliers.title') }}</h1>
                <p class="page-subtitle">{{ __('bar.suppliers.subtitle') }}</p>
            </div>
            <div class="page-actions">
                <button type="button" class="btn btn-secondary import-suppliers-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                    </svg>
                    {{ __('bar.suppliers.import_suppliers') }}
                </button>
                <button type="button" class="btn btn-secondary export-suppliers-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ __('bar.suppliers.export_suppliers') }}
                </button>
                <button type="button" class="btn btn-primary add-supplier-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('bar.suppliers.add_supplier') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon total">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h4a1 1 0 011 1v5m-6 0V9a1 1 0 011-1h4a1 1 0 011 1v11"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="total-suppliers">0</div>
                    <div class="stat-label">{{ __('bar.suppliers.total_suppliers') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon active">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="active-suppliers">0</div>
                    <div class="stat-label">{{ __('bar.suppliers.active_suppliers') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon orders">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="total-orders">0</div>
                    <div class="stat-label">{{ __('bar.suppliers.total_orders') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon rating">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="avg-rating">0.0</div>
                    <div class="stat-label">{{ __('bar.suppliers.avg_rating') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="filters-section">
        <div class="filters-panel">
            <div class="search-group">
                <div class="search-input-wrapper">
                    <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" 
                           class="search-input" 
                           placeholder="{{ __('bar.suppliers.search_suppliers') }}"
                           id="supplier-search">
                </div>
            </div>
            
            <div class="filter-group">
                <select class="filter-select" id="specialty-filter">
                    <option value="">{{ __('bar.suppliers.all_specialties') }}</option>
                    <option value="wine_distributor">{{ __('bar.suppliers.wine_distributor') }}</option>
                    <option value="beer_distributor">{{ __('bar.suppliers.beer_distributor') }}</option>
                    <option value="spirits_distributor">{{ __('bar.suppliers.spirits_distributor') }}</option>
                    <option value="soft_drinks_supplier">{{ __('bar.suppliers.soft_drinks_supplier') }}</option>
                    <option value="coffee_supplier">{{ __('bar.suppliers.coffee_supplier') }}</option>
                    <option value="general_beverage">{{ __('bar.suppliers.general_beverage') }}</option>
                </select>
            </div>
            
            <div class="filter-group">
                <select class="filter-select" id="rating-filter">
                    <option value="">{{ __('bar.suppliers.all_ratings') }}</option>
                    <option value="5">5 {{ __('common.stars') }}</option>
                    <option value="4">4+ {{ __('common.stars') }}</option>
                    <option value="3">3+ {{ __('common.stars') }}</option>
                    <option value="2">2+ {{ __('common.stars') }}</option>
                </select>
            </div>
            
            <div class="filter-group">
                <select class="filter-select" id="sort-filter">
                    <option value="name">{{ __('bar.suppliers.sort_by_name') }}</option>
                    <option value="rating">{{ __('bar.suppliers.sort_by_rating') }}</option>
                    <option value="orders">{{ __('bar.suppliers.sort_by_orders') }}</option>
                    <option value="delivery_time">{{ __('bar.suppliers.sort_by_delivery') }}</option>
                </select>
            </div>
            
            <button type="button" class="btn btn-secondary clear-filters-btn">
                {{ __('bar.suppliers.clear_filters') }}
            </button>
        </div>
        
        <div class="view-toggle">
            <button type="button" class="view-btn active" data-view="grid">
                <svg class="view-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
            </button>
            <button type="button" class="view-btn" data-view="list">
                <svg class="view-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Suppliers Content -->
    <div class="suppliers-content">
        <!-- Grid View -->
        <div class="suppliers-grid" id="suppliers-grid">
            <!-- Loading skeleton -->
            <div class="supplier-card loading">
                <div class="supplier-header">
                    <div class="skeleton-title"></div>
                    <div class="skeleton-specialty"></div>
                </div>
                <div class="supplier-body">
                    <div class="skeleton-contact"></div>
                    <div class="skeleton-rating"></div>
                </div>
            </div>
            <div class="supplier-card loading">
                <div class="supplier-header">
                    <div class="skeleton-title"></div>
                    <div class="skeleton-specialty"></div>
                </div>
                <div class="supplier-body">
                    <div class="skeleton-contact"></div>
                    <div class="skeleton-rating"></div>
                </div>
            </div>
            <div class="supplier-card loading">
                <div class="supplier-header">
                    <div class="skeleton-title"></div>
                    <div class="skeleton-specialty"></div>
                </div>
                <div class="supplier-body">
                    <div class="skeleton-contact"></div>
                    <div class="skeleton-rating"></div>
                </div>
            </div>
        </div>

        <!-- List View -->
        <div class="suppliers-list" id="suppliers-list" style="display: none;">
            <div class="suppliers-table-wrapper">
                <table class="suppliers-table" role="table">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('bar.suppliers.supplier_name') }}</th>
                            <th scope="col">{{ __('bar.suppliers.specialty') }}</th>
                            <th scope="col">{{ __('bar.suppliers.contact_person') }}</th>
                            <th scope="col">{{ __('bar.suppliers.phone_number') }}</th>
                            <th scope="col">{{ __('bar.suppliers.delivery_rating') }}</th>
                            <th scope="col">{{ __('bar.suppliers.last_order_date') }}</th>
                            <th scope="col">{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="suppliers-table-body">
                        <!-- Loading skeleton rows -->
                        <tr class="loading-row">
                            <td><div class="skeleton-title"></div></td>
                            <td><div class="skeleton-specialty"></div></td>
                            <td><div class="skeleton-contact"></div></td>
                            <td><div class="skeleton-contact"></div></td>
                            <td><div class="skeleton-rating"></div></td>
                            <td><div class="skeleton-date"></div></td>
                            <td><div class="skeleton-actions"></div></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Empty State -->
        <div class="empty-state" style="display: none;">
            <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h4a1 1 0 011 1v5m-6 0V9a1 1 0 011-1h4a1 1 0 011 1v11"/>
            </svg>
            <h3>{{ __('bar.suppliers.no_suppliers_found') }}</h3>
            <p>{{ __('bar.suppliers.no_suppliers_description') }}</p>
            <button type="button" class="btn btn-primary add-supplier-btn">
                {{ __('bar.suppliers.add_first_supplier') }}
            </button>
        </div>
    </div>
</div>

<!-- Add/Edit Supplier Modal -->
<div class="supplier-modal" id="supplier-modal" style="display: none;" role="dialog" aria-labelledby="supplier-modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content large">
        <div class="modal-header">
            <h2 id="supplier-modal-title" class="modal-title">{{ __('bar.suppliers.add_supplier') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('common.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form class="supplier-form" id="supplier-form">
                <div class="form-tabs">
                    <div class="tab-nav">
                        <button type="button" class="tab-btn active" data-tab="basic">
                            {{ __('common.basic_information') }}
                        </button>
                        <button type="button" class="tab-btn" data-tab="contact">
                            {{ __('bar.suppliers.contact_details') }}
                        </button>
                        <button type="button" class="tab-btn" data-tab="terms">
                            {{ __('bar.suppliers.business_terms') }}
                        </button>
                        <button type="button" class="tab-btn" data-tab="performance">
                            {{ __('bar.suppliers.performance') }}
                        </button>
                    </div>
                    
                    <div class="tab-content">
                        <!-- Basic Info Tab -->
                        <div class="tab-panel active" data-tab="basic">
                            <div class="form-grid grid grid-cols-1 md:grid-cols-2">
                                <!-- Supplier Name -->
                                <div class="form-group md:col-span-2">
                                    <label for="supplier-name" class="form-label required">
                                        {{ __('bar.suppliers.supplier_name') }}
                                    </label>
                                    <input type="text" id="supplier-name" name="supplier_name" class="form-input" required
                                           placeholder="{{ __('bar.suppliers.supplier_name_placeholder') }}">
                                </div>

                                <!-- Specialty -->
                                <div class="form-group">
                                    <label for="specialty" class="form-label required">
                                        {{ __('bar.suppliers.specialty') }}
                                    </label>
                                    <select id="specialty" name="specialty" class="form-select" required>
                                        <option value="">{{ __('common.select') }}...</option>
                                        <option value="wine_distributor">{{ __('bar.suppliers.wine_distributor') }}</option>
                                        <option value="beer_distributor">{{ __('bar.suppliers.beer_distributor') }}</option>
                                        <option value="spirits_distributor">{{ __('bar.suppliers.spirits_distributor') }}</option>
                                        <option value="soft_drinks_supplier">{{ __('bar.suppliers.soft_drinks_supplier') }}</option>
                                        <option value="coffee_supplier">{{ __('bar.suppliers.coffee_supplier') }}</option>
                                        <option value="general_beverage">{{ __('bar.suppliers.general_beverage') }}</option>
                                    </select>
                                </div>

                                <!-- Status -->
                                <div class="form-group">
                                    <label class="form-label">{{ __('common.status') }}</label>
                                    <div class="toggle-group">
                                        <label class="toggle-switch">
                                            <input type="checkbox" name="active" value="1" checked>
                                            <span class="toggle-slider"></span>
                                            <span class="toggle-label">{{ __('bar.suppliers.active') }}</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="form-group md:col-span-2">
                                    <label for="supplier-description" class="form-label">
                                        {{ __('common.description') }}
                                    </label>
                                    <textarea id="supplier-description" name="description" class="form-textarea" rows="3"
                                              placeholder="{{ __('bar.suppliers.description_placeholder') }}"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Details Tab -->
                        <div class="tab-panel" data-tab="contact">
                            <div class="form-grid grid grid-cols-1 md:grid-cols-2">
                                <!-- Contact Person -->
                                <div class="form-group">
                                    <label for="contact-person" class="form-label">
                                        {{ __('bar.suppliers.contact_person') }}
                                    </label>
                                    <input type="text" id="contact-person" name="contact_person" class="form-input"
                                           placeholder="{{ __('bar.suppliers.contact_person_placeholder') }}">
                                </div>

                                <!-- Phone Number -->
                                <div class="form-group">
                                    <label for="phone-number" class="form-label">
                                        {{ __('bar.suppliers.phone_number') }}
                                    </label>
                                    <input type="tel" id="phone-number" name="phone_number" class="form-input"
                                           placeholder="{{ __('bar.suppliers.phone_placeholder') }}">
                                </div>

                                <!-- Email Address -->
                                <div class="form-group">
                                    <label for="email-address" class="form-label">
                                        {{ __('bar.suppliers.email_address') }}
                                    </label>
                                    <input type="email" id="email-address" name="email_address" class="form-input"
                                           placeholder="{{ __('bar.suppliers.email_placeholder') }}">
                                </div>

                                <!-- Website -->
                                <div class="form-group">
                                    <label for="website" class="form-label">
                                        {{ __('bar.suppliers.website') }}
                                    </label>
                                    <input type="url" id="website" name="website" class="form-input"
                                           placeholder="{{ __('bar.suppliers.website_placeholder') }}">
                                </div>

                                <!-- Address -->
                                <div class="form-group md:col-span-2">
                                    <label for="address" class="form-label">
                                        {{ __('bar.suppliers.address') }}
                                    </label>
                                    <textarea id="address" name="address" class="form-textarea" rows="3"
                                              placeholder="{{ __('bar.suppliers.address_placeholder') }}"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Business Terms Tab -->
                        <div class="tab-panel" data-tab="terms">
                            <div class="form-grid grid grid-cols-1 md:grid-cols-2">
                                <!-- Payment Terms -->
                                <div class="form-group">
                                    <label for="payment-terms" class="form-label">
                                        {{ __('bar.suppliers.payment_terms') }}
                                    </label>
                                    <select id="payment-terms" name="payment_terms" class="form-select">
                                        <option value="net_30">{{ __('bar.suppliers.net_30') }}</option>
                                        <option value="net_15">{{ __('bar.suppliers.net_15') }}</option>
                                        <option value="cod">{{ __('bar.suppliers.cod') }}</option>
                                        <option value="prepaid">{{ __('bar.suppliers.prepaid') }}</option>
                                    </select>
                                </div>

                                <!-- Delivery Days -->
                                <div class="form-group">
                                    <label for="delivery-days" class="form-label">
                                        {{ __('bar.suppliers.delivery_days') }}
                                    </label>
                                    <div class="input-with-suffix">
                                        <input type="number" id="delivery-days" name="delivery_days" class="form-input" 
                                               min="1" max="30" value="3">
                                        <span class="input-suffix">{{ __('common.days') }}</span>
                                    </div>
                                </div>

                                <!-- Minimum Order -->
                                <div class="form-group">
                                    <label for="minimum-order" class="form-label">
                                        {{ __('bar.suppliers.minimum_order') }}
                                    </label>
                                    <div class="input-with-prefix">
                                        <span class="input-prefix">$</span>
                                        <input type="number" id="minimum-order" name="minimum_order" class="form-input" 
                                               min="0" step="0.01" placeholder="0.00">
                                    </div>
                                </div>

                                <!-- Credit Limit -->
                                <div class="form-group">
                                    <label for="credit-limit" class="form-label">
                                        {{ __('bar.suppliers.credit_limit') }}
                                    </label>
                                    <div class="input-with-prefix">
                                        <span class="input-prefix">$</span>
                                        <input type="number" id="credit-limit" name="credit_limit" class="form-input" 
                                               min="0" step="0.01" placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Performance Tab -->
                        <div class="tab-panel" data-tab="performance">
                            <div class="form-grid grid grid-cols-1 md:grid-cols-3">
                                <!-- Delivery Rating -->
                                <div class="form-group">
                                    <label for="delivery-rating" class="form-label">
                                        {{ __('bar.suppliers.delivery_rating') }}
                                    </label>
                                    <div class="rating-input">
                                        <input type="number" id="delivery-rating" name="delivery_rating" class="form-input" 
                                               min="1" max="5" step="0.1" value="5.0">
                                        <span class="rating-suffix">/5</span>
                                    </div>
                                </div>

                                <!-- Quality Rating -->
                                <div class="form-group">
                                    <label for="quality-rating" class="form-label">
                                        {{ __('bar.suppliers.quality_rating') }}
                                    </label>
                                    <div class="rating-input">
                                        <input type="number" id="quality-rating" name="quality_rating" class="form-input" 
                                               min="1" max="5" step="0.1" value="5.0">
                                        <span class="rating-suffix">/5</span>
                                    </div>
                                </div>

                                <!-- Price Rating -->
                                <div class="form-group">
                                    <label for="price-rating" class="form-label">
                                        {{ __('bar.suppliers.price_rating') }}
                                    </label>
                                    <div class="rating-input">
                                        <input type="number" id="price-rating" name="price_rating" class="form-input" 
                                               min="1" max="5" step="0.1" value="5.0">
                                        <span class="rating-suffix">/5</span>
                                    </div>
                                </div>

                                <!-- Notes -->
                                <div class="form-group md:col-span-3">
                                    <label for="performance-notes" class="form-label">
                                        {{ __('bar.suppliers.performance_notes') }}
                                    </label>
                                    <textarea id="performance-notes" name="performance_notes" class="form-textarea" rows="4"
                                              placeholder="{{ __('bar.suppliers.performance_notes_placeholder') }}"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary cancel-btn">
                {{ __('common.cancel') }}
            </button>
            <button type="submit" form="supplier-form" class="btn btn-primary save-btn">
                {{ __('bar.suppliers.save_supplier') }}
            </button>
        </div>
    </div>
</div>

<!-- Supplier Details Modal -->
<div class="supplier-details-modal" id="supplier-details-modal" style="display: none;" role="dialog" aria-labelledby="supplier-details-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="supplier-details-title" class="modal-title">{{ __('bar.suppliers.supplier_details') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('common.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="supplier-details-content" id="supplier-details-content">
                <!-- Supplier details will be populated here -->
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary close-details-btn">
                {{ __('common.close') }}
            </button>
            <button type="button" class="btn btn-secondary contact-supplier-btn">
                {{ __('bar.suppliers.contact_supplier') }}
            </button>
            <button type="button" class="btn btn-primary place-order-btn">
                {{ __('bar.suppliers.place_order') }}
            </button>
        </div>
    </div>
</div>
@endsection
