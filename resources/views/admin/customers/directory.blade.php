@extends('layouts.admin')

@section('title', __('customers.directory.title') . ' - ' . config('app.name'))
@section('page_title', __('customers.directory.title'))

@push('styles')
    @vite('resources/css/admin/customer-directory.css')
@endpush

@push('scripts')
    @vite('resources/js/admin/customer-directory.js')
@endpush

@section('content')
<div class="customer-directory-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('customers.directory.title') }}</h1>
                <p class="page-subtitle">{{ __('customers.directory.subtitle') }}</p>
            </div>
            <div class="page-actions">
                <button type="button" class="btn btn-secondary import-customers-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                    </svg>
                    {{ __('customers.directory.import_customers') }}
                </button>
                <button type="button" class="btn btn-secondary export-customers-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ __('customers.directory.export_customers') }}
                </button>
                <button type="button" class="btn btn-primary add-customer-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('customers.directory.add_customer') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon total-customers">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="total-customers">0</div>
                    <div class="stat-label">{{ __('customers.directory.total_customers') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon new-customers">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="new-customers">0</div>
                    <div class="stat-label">{{ __('customers.directory.new_this_month') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon vip-customers">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="vip-customers">0</div>
                    <div class="stat-label">{{ __('customers.directory.vip_customers') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon active-customers">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="active-customers">0</div>
                    <div class="stat-label">{{ __('customers.directory.active_customers') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="search-filters-section">
        <div class="search-filters-container">
            <!-- Search Bar -->
            <div class="search-bar">
                <div class="search-input-wrapper">
                    <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" 
                           class="search-input" 
                           placeholder="{{ __('customers.directory.search_customers') }}"
                           id="customer-search">
                </div>
            </div>
            
            <!-- Filters -->
            <div class="filters-container">
                <div class="filter-group">
                    <select class="filter-select" id="status-filter">
                        <option value="">{{ __('customers.directory.all_status') }}</option>
                        <option value="active">{{ __('customers.directory.active') }}</option>
                        <option value="inactive">{{ __('customers.directory.inactive') }}</option>
                        <option value="vip">{{ __('customers.directory.vip') }}</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <select class="filter-select" id="visit-frequency-filter">
                        <option value="">{{ __('customers.directory.all_frequency') }}</option>
                        <option value="frequent">{{ __('customers.directory.frequent_visitors') }}</option>
                        <option value="regular">{{ __('customers.directory.regular_visitors') }}</option>
                        <option value="occasional">{{ __('customers.directory.occasional_visitors') }}</option>
                        <option value="new">{{ __('customers.directory.first_time') }}</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <select class="filter-select" id="location-filter">
                        <option value="">{{ __('customers.directory.all_locations') }}</option>
                        <option value="local">{{ __('customers.directory.local') }}</option>
                        <option value="nearby">{{ __('customers.directory.nearby') }}</option>
                        <option value="distant">{{ __('customers.directory.distant') }}</option>
                    </select>
                </div>
                
                <button type="button" class="btn btn-secondary clear-filters-btn">
                    {{ __('customers.directory.clear_filters') }}
                </button>
            </div>
        </div>
    </div>

    <!-- View Toggle and Results -->
    <div class="content-section">
        <div class="content-header">
            <div class="results-info">
                <span class="results-count" id="results-count">{{ __('customers.directory.showing_results', ['count' => 0, 'total' => 0]) }}</span>
            </div>
            <div class="view-controls">
                <div class="view-toggle">
                    <button type="button" class="view-btn active" data-view="grid">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                    </button>
                    <button type="button" class="view-btn" data-view="list">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Grid View -->
        <div class="customers-grid" id="customers-grid">
            <!-- Customer cards will be populated by JavaScript -->
        </div>

        <!-- List View -->
        <div class="customers-list" id="customers-list" style="display: none;">
            <div class="customers-table-wrapper">
                <table class="customers-table">
                    <thead>
                        <tr>
                            <th>{{ __('customers.directory.customer') }}</th>
                            <th>{{ __('customers.directory.contact') }}</th>
                            <th>{{ __('customers.directory.status') }}</th>
                            <th>{{ __('customers.directory.visits') }}</th>
                            <th>{{ __('customers.directory.total_spent') }}</th>
                            <th>{{ __('customers.directory.last_visit') }}</th>
                            <th>{{ __('customers.directory.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="customers-table-body">
                        <!-- Customer rows will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Empty State -->
        <div class="empty-state" id="empty-state" style="display: none;">
            <div class="empty-state-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h3 class="empty-state-title">{{ __('customers.directory.no_customers_found') }}</h3>
            <p class="empty-state-message">{{ __('customers.directory.no_customers_message') }}</p>
            <button type="button" class="btn btn-primary add-first-customer-btn">
                {{ __('customers.directory.add_first_customer') }}
            </button>
        </div>

        <!-- Loading State -->
        <div class="loading-state" id="loading-state" style="display: none;">
            <div class="loading-spinner"></div>
            <p class="loading-message">{{ __('customers.directory.loading_customers') }}</p>
        </div>
    </div>
</div>

<!-- Add/Edit Customer Modal -->
<div class="customer-modal" id="customer-modal" style="display: none;" role="dialog" aria-labelledby="customer-modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="customer-modal-title" class="modal-title">{{ __('customers.directory.add_customer') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('customers.directory.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form id="customer-form" class="customer-form">
                <!-- Form Tabs -->
                <div class="form-tabs" x-data="{ activeTab: 'basic' }">
                    <div class="tab-nav">
                        <button type="button" 
                                class="tab-btn" 
                                :class="{ 'active': activeTab === 'basic' }"
                                @click="activeTab = 'basic'">
                            <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ __('customers.directory.basic_info') }}
                        </button>
                        <button type="button" 
                                class="tab-btn" 
                                :class="{ 'active': activeTab === 'contact' }"
                                @click="activeTab = 'contact'">
                            <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            {{ __('customers.directory.contact_info') }}
                        </button>
                        <button type="button" 
                                class="tab-btn" 
                                :class="{ 'active': activeTab === 'preferences' }"
                                @click="activeTab = 'preferences'">
                            <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            {{ __('customers.directory.preferences') }}
                        </button>
                        <button type="button" 
                                class="tab-btn" 
                                :class="{ 'active': activeTab === 'notes' }"
                                @click="activeTab = 'notes'">
                            <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            {{ __('customers.directory.notes') }}
                        </button>
                    </div>

                    <!-- Basic Info Tab -->
                    <div class="tab-panel" x-show="activeTab === 'basic'" x-transition>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="first-name" class="form-label required">{{ __('customers.directory.first_name') }}</label>
                                <input type="text" id="first-name" name="first_name" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label for="last-name" class="form-label required">{{ __('customers.directory.last_name') }}</label>
                                <input type="text" id="last-name" name="last_name" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label for="date-of-birth" class="form-label">{{ __('customers.directory.date_of_birth') }}</label>
                                <input type="date" id="date-of-birth" name="date_of_birth" class="form-input">
                            </div>
                            <div class="form-group">
                                <label for="gender" class="form-label">{{ __('customers.directory.gender') }}</label>
                                <select id="gender" name="gender" class="form-select">
                                    <option value="">{{ __('customers.directory.select_gender') }}</option>
                                    <option value="male">{{ __('customers.directory.male') }}</option>
                                    <option value="female">{{ __('customers.directory.female') }}</option>
                                    <option value="other">{{ __('customers.directory.other') }}</option>
                                    <option value="prefer_not_to_say">{{ __('customers.directory.prefer_not_to_say') }}</option>
                                </select>
                            </div>
                            <div class="form-group full-width">
                                <label for="customer-status" class="form-label">{{ __('customers.directory.customer_status') }}</label>
                                <select id="customer-status" name="status" class="form-select">
                                    <option value="active">{{ __('customers.directory.active') }}</option>
                                    <option value="inactive">{{ __('customers.directory.inactive') }}</option>
                                    <option value="vip">{{ __('customers.directory.vip') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Info Tab -->
                    <div class="tab-panel" x-show="activeTab === 'contact'" x-transition>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="email" class="form-label">{{ __('customers.directory.email') }}</label>
                                <input type="email" id="email" name="email" class="form-input">
                            </div>
                            <div class="form-group">
                                <label for="phone" class="form-label">{{ __('customers.directory.phone') }}</label>
                                <input type="tel" id="phone" name="phone" class="form-input">
                            </div>
                            <div class="form-group full-width">
                                <label for="address" class="form-label">{{ __('customers.directory.address') }}</label>
                                <textarea id="address" name="address" class="form-textarea" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="city" class="form-label">{{ __('customers.directory.city') }}</label>
                                <input type="text" id="city" name="city" class="form-input">
                            </div>
                            <div class="form-group">
                                <label for="postal-code" class="form-label">{{ __('customers.directory.postal_code') }}</label>
                                <input type="text" id="postal-code" name="postal_code" class="form-input">
                            </div>
                        </div>
                    </div>

                    <!-- Preferences Tab -->
                    <div class="tab-panel" x-show="activeTab === 'preferences'" x-transition>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="preferred-seating" class="form-label">{{ __('customers.directory.preferred_seating') }}</label>
                                <select id="preferred-seating" name="preferred_seating" class="form-select">
                                    <option value="">{{ __('customers.directory.no_preference') }}</option>
                                    <option value="window">{{ __('customers.directory.window_seat') }}</option>
                                    <option value="booth">{{ __('customers.directory.booth') }}</option>
                                    <option value="bar">{{ __('customers.directory.bar_seating') }}</option>
                                    <option value="outdoor">{{ __('customers.directory.outdoor') }}</option>
                                    <option value="quiet">{{ __('customers.directory.quiet_area') }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="dietary-restrictions" class="form-label">{{ __('customers.directory.dietary_restrictions') }}</label>
                                <select id="dietary-restrictions" name="dietary_restrictions" class="form-select" multiple>
                                    <option value="vegetarian">{{ __('customers.directory.vegetarian') }}</option>
                                    <option value="vegan">{{ __('customers.directory.vegan') }}</option>
                                    <option value="gluten_free">{{ __('customers.directory.gluten_free') }}</option>
                                    <option value="dairy_free">{{ __('customers.directory.dairy_free') }}</option>
                                    <option value="nut_allergy">{{ __('customers.directory.nut_allergy') }}</option>
                                    <option value="shellfish_allergy">{{ __('customers.directory.shellfish_allergy') }}</option>
                                </select>
                            </div>
                            <div class="form-group full-width">
                                <label for="allergies" class="form-label">{{ __('customers.directory.allergies') }}</label>
                                <textarea id="allergies" name="allergies" class="form-textarea" rows="3" 
                                          placeholder="{{ __('customers.directory.allergies_placeholder') }}"></textarea>
                            </div>
                            <div class="form-group full-width">
                                <label class="form-label">{{ __('customers.directory.communication_preferences') }}</label>
                                <div class="checkbox-group">
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="email_notifications" value="1">
                                        <span class="checkbox-indicator"></span>
                                        {{ __('customers.directory.email_notifications') }}
                                    </label>
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="sms_notifications" value="1">
                                        <span class="checkbox-indicator"></span>
                                        {{ __('customers.directory.sms_notifications') }}
                                    </label>
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="promotional_offers" value="1">
                                        <span class="checkbox-indicator"></span>
                                        {{ __('customers.directory.promotional_offers') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes Tab -->
                    <div class="tab-panel" x-show="activeTab === 'notes'" x-transition>
                        <div class="form-group">
                            <label for="customer-notes" class="form-label">{{ __('customers.directory.customer_notes') }}</label>
                            <textarea id="customer-notes" name="notes" class="form-textarea" rows="6" 
                                      placeholder="{{ __('customers.directory.notes_placeholder') }}"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="internal-notes" class="form-label">{{ __('customers.directory.internal_notes') }}</label>
                            <textarea id="internal-notes" name="internal_notes" class="form-textarea" rows="4" 
                                      placeholder="{{ __('customers.directory.internal_notes_placeholder') }}"></textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary cancel-customer-btn">
                {{ __('customers.directory.cancel') }}
            </button>
            <button type="submit" form="customer-form" class="btn btn-primary save-customer-btn">
                {{ __('customers.directory.save_customer') }}
            </button>
        </div>
    </div>
</div>

<!-- Customer Details Modal -->
<div class="customer-details-modal" id="customer-details-modal" style="display: none;" role="dialog" aria-labelledby="customer-details-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content large">
        <div class="modal-header">
            <h2 id="customer-details-title" class="modal-title">{{ __('customers.directory.customer_details') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('customers.directory.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="customer-details-content" id="customer-details-content">
                <!-- Customer details will be populated by JavaScript -->
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary close-details-btn">
                {{ __('customers.directory.close') }}
            </button>
            <button type="button" class="btn btn-primary edit-customer-btn">
                {{ __('customers.directory.edit_customer') }}
            </button>
        </div>
    </div>
</div>
@endsection
