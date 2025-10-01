@extends('layouts.admin')

@section('title', __('customers.reservations.title') . ' - ' . config('app.name'))
@section('page_title', __('customers.reservations.title'))

@push('styles')
    @vite('resources/css/admin/customer-reservations.css')
@endpush

@push('scripts')
    @vite('resources/js/admin/customer-reservations.js')
@endpush

@section('content')
<div class="reservations-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('customers.reservations.title') }}</h1>
                <p class="page-subtitle">{{ __('customers.reservations.subtitle') }}</p>
            </div>
            <div class="page-actions">
                <button type="button" class="btn btn-secondary table-layout-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                    </svg>
                    {{ __('customers.reservations.table_layout') }}
                </button>
                <button type="button" class="btn btn-secondary export-reservations-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ __('customers.reservations.export_reservations') }}
                </button>
                <button type="button" class="btn btn-primary add-reservation-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('customers.reservations.add_reservation') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon today-reservations">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="today-reservations">0</div>
                    <div class="stat-label">{{ __('customers.reservations.today_reservations') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon upcoming-reservations">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="upcoming-reservations">0</div>
                    <div class="stat-label">{{ __('customers.reservations.upcoming_reservations') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon table-occupancy">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="table-occupancy">0%</div>
                    <div class="stat-label">{{ __('customers.reservations.table_occupancy') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon no-shows">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="no-shows">0</div>
                    <div class="stat-label">{{ __('customers.reservations.no_shows') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-section">
        <div class="reservations-tabs" x-data="{ activeTab: 'calendar' }">
            <div class="tab-nav">
                <button type="button" 
                        class="tab-btn" 
                        :class="{ 'active': activeTab === 'calendar' }"
                        @click="activeTab = 'calendar'">
                    <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ __('customers.reservations.calendar_view') }}
                </button>
                <button type="button" 
                        class="tab-btn" 
                        :class="{ 'active': activeTab === 'list' }"
                        @click="activeTab = 'list'">
                    <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    {{ __('customers.reservations.list_view') }}
                </button>
                <button type="button" 
                        class="tab-btn" 
                        :class="{ 'active': activeTab === 'tables' }"
                        @click="activeTab = 'tables'">
                    <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                    </svg>
                    {{ __('customers.reservations.table_management') }}
                </button>
                <button type="button" 
                        class="tab-btn" 
                        :class="{ 'active': activeTab === 'waitlist' }"
                        @click="activeTab = 'waitlist'">
                    <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ __('customers.reservations.waitlist') }}
                </button>
            </div>

            <!-- Calendar View Tab -->
            <div class="tab-panel" x-show="activeTab === 'calendar'" x-transition>
                <div class="calendar-section">
                    <div class="calendar-header">
                        <div class="calendar-navigation">
                            <button type="button" class="btn btn-secondary calendar-prev">
                                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <h3 class="calendar-title" id="calendar-title">{{ __('customers.reservations.current_month') }}</h3>
                            <button type="button" class="btn btn-secondary calendar-next">
                                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                        <div class="calendar-actions">
                            <button type="button" class="btn btn-secondary today-btn">
                                {{ __('customers.reservations.today') }}
                            </button>
                            <select class="calendar-view-select" id="calendar-view">
                                <option value="month">{{ __('customers.reservations.month_view') }}</option>
                                <option value="week">{{ __('customers.reservations.week_view') }}</option>
                                <option value="day">{{ __('customers.reservations.day_view') }}</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="calendar-container" id="calendar-container">
                        <!-- Calendar will be populated by JavaScript -->
                    </div>
                </div>
            </div>

            <!-- List View Tab -->
            <div class="tab-panel" x-show="activeTab === 'list'" x-transition>
                <div class="list-section">
                    <!-- Search and Filters -->
                    <div class="search-filters-container">
                        <div class="search-bar">
                            <div class="search-input-wrapper">
                                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <input type="text" 
                                       class="search-input" 
                                       placeholder="{{ __('customers.reservations.search_reservations') }}"
                                       id="reservations-search">
                            </div>
                        </div>
                        
                        <div class="filters-container">
                            <select class="filter-select" id="status-filter">
                                <option value="">{{ __('customers.reservations.all_status') }}</option>
                                <option value="confirmed">{{ __('customers.reservations.confirmed') }}</option>
                                <option value="pending">{{ __('customers.reservations.pending') }}</option>
                                <option value="seated">{{ __('customers.reservations.seated') }}</option>
                                <option value="completed">{{ __('customers.reservations.completed') }}</option>
                                <option value="cancelled">{{ __('customers.reservations.cancelled') }}</option>
                                <option value="no_show">{{ __('customers.reservations.no_show') }}</option>
                            </select>
                            
                            <input type="date" class="filter-input" id="date-filter" placeholder="{{ __('customers.reservations.filter_date') }}">
                            
                            <select class="filter-select" id="table-filter">
                                <option value="">{{ __('customers.reservations.all_tables') }}</option>
                                <!-- Tables will be populated by JavaScript -->
                            </select>
                            
                            <button type="button" class="btn btn-secondary clear-filters-btn">
                                {{ __('customers.reservations.clear_filters') }}
                            </button>
                        </div>
                    </div>

                    <!-- Reservations List -->
                    <div class="reservations-list-container">
                        <div class="reservations-table-wrapper">
                            <table class="reservations-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('customers.reservations.date_time') }}</th>
                                        <th>{{ __('customers.reservations.customer') }}</th>
                                        <th>{{ __('customers.reservations.party_size') }}</th>
                                        <th>{{ __('customers.reservations.table') }}</th>
                                        <th>{{ __('customers.reservations.status') }}</th>
                                        <th>{{ __('customers.reservations.special_requests') }}</th>
                                        <th>{{ __('customers.reservations.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="reservations-table-body">
                                    <!-- Reservations will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Management Tab -->
            <div class="tab-panel" x-show="activeTab === 'tables'" x-transition>
                <div class="tables-section">
                    <div class="tables-header">
                        <h3 class="section-title">{{ __('customers.reservations.restaurant_layout') }}</h3>
                        <div class="table-actions">
                            <button type="button" class="btn btn-secondary edit-layout-btn">
                                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                {{ __('customers.reservations.edit_layout') }}
                            </button>
                            <button type="button" class="btn btn-primary add-table-btn">
                                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                {{ __('customers.reservations.add_table') }}
                            </button>
                        </div>
                    </div>
                    
                    <div class="restaurant-layout" id="restaurant-layout">
                        <!-- Restaurant layout will be populated by JavaScript -->
                    </div>
                    
                    <div class="tables-list">
                        <h4 class="tables-list-title">{{ __('customers.reservations.tables_list') }}</h4>
                        <div class="tables-grid" id="tables-grid">
                            <!-- Tables list will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Waitlist Tab -->
            <div class="tab-panel" x-show="activeTab === 'waitlist'" x-transition>
                <div class="waitlist-section">
                    <div class="waitlist-header">
                        <h3 class="section-title">{{ __('customers.reservations.current_waitlist') }}</h3>
                        <button type="button" class="btn btn-primary add-to-waitlist-btn">
                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            {{ __('customers.reservations.add_to_waitlist') }}
                        </button>
                    </div>
                    
                    <div class="waitlist-container" id="waitlist-container">
                        <!-- Waitlist will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Reservation Modal -->
<div class="reservation-modal" id="reservation-modal" style="display: none;" role="dialog" aria-labelledby="reservation-modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="reservation-modal-title" class="modal-title">{{ __('customers.reservations.add_reservation') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('customers.reservations.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form id="reservation-form" class="reservation-form">
                <div class="form-tabs" x-data="{ activeFormTab: 'details' }">
                    <div class="form-tab-nav">
                        <button type="button" 
                                class="form-tab-btn" 
                                :class="{ 'active': activeFormTab === 'details' }"
                                @click="activeFormTab = 'details'">
                            {{ __('customers.reservations.reservation_details') }}
                        </button>
                        <button type="button" 
                                class="form-tab-btn" 
                                :class="{ 'active': activeFormTab === 'customer' }"
                                @click="activeFormTab = 'customer'">
                            {{ __('customers.reservations.customer_info') }}
                        </button>
                        <button type="button" 
                                class="form-tab-btn" 
                                :class="{ 'active': activeFormTab === 'preferences' }"
                                @click="activeFormTab = 'preferences'">
                            {{ __('customers.reservations.preferences') }}
                        </button>
                    </div>

                    <!-- Reservation Details Tab -->
                    <div class="form-tab-panel" x-show="activeFormTab === 'details'" x-transition>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="reservation-date" class="form-label required">{{ __('customers.reservations.date') }}</label>
                                <input type="date" id="reservation-date" name="date" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label for="reservation-time" class="form-label required">{{ __('customers.reservations.time') }}</label>
                                <select id="reservation-time" name="time" class="form-select" required>
                                    <option value="">{{ __('customers.reservations.select_time') }}</option>
                                    <!-- Time slots will be populated by JavaScript -->
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="party-size" class="form-label required">{{ __('customers.reservations.party_size') }}</label>
                                <select id="party-size" name="party_size" class="form-select" required>
                                    <option value="">{{ __('customers.reservations.select_party_size') }}</option>
                                    <option value="1">1 {{ __('customers.reservations.person') }}</option>
                                    <option value="2">2 {{ __('customers.reservations.people') }}</option>
                                    <option value="3">3 {{ __('customers.reservations.people') }}</option>
                                    <option value="4">4 {{ __('customers.reservations.people') }}</option>
                                    <option value="5">5 {{ __('customers.reservations.people') }}</option>
                                    <option value="6">6 {{ __('customers.reservations.people') }}</option>
                                    <option value="7">7 {{ __('customers.reservations.people') }}</option>
                                    <option value="8">8 {{ __('customers.reservations.people') }}</option>
                                    <option value="9">9+ {{ __('customers.reservations.people') }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="table-selection" class="form-label">{{ __('customers.reservations.preferred_table') }}</label>
                                <select id="table-selection" name="table_id" class="form-select">
                                    <option value="">{{ __('customers.reservations.auto_assign') }}</option>
                                    <!-- Tables will be populated by JavaScript -->
                                </select>
                            </div>
                            <div class="form-group full-width">
                                <label for="special-requests" class="form-label">{{ __('customers.reservations.special_requests') }}</label>
                                <textarea id="special-requests" name="special_requests" class="form-textarea" rows="3" 
                                          placeholder="{{ __('customers.reservations.special_requests_placeholder') }}"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Info Tab -->
                    <div class="form-tab-panel" x-show="activeFormTab === 'customer'" x-transition>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="customer-name" class="form-label required">{{ __('customers.reservations.customer_name') }}</label>
                                <input type="text" id="customer-name" name="customer_name" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label for="customer-phone" class="form-label required">{{ __('customers.reservations.phone_number') }}</label>
                                <input type="tel" id="customer-phone" name="customer_phone" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label for="customer-email" class="form-label">{{ __('customers.reservations.email_address') }}</label>
                                <input type="email" id="customer-email" name="customer_email" class="form-input">
                            </div>
                            <div class="form-group">
                                <label for="reservation-status" class="form-label">{{ __('customers.reservations.status') }}</label>
                                <select id="reservation-status" name="status" class="form-select">
                                    <option value="pending">{{ __('customers.reservations.pending') }}</option>
                                    <option value="confirmed" selected>{{ __('customers.reservations.confirmed') }}</option>
                                    <option value="cancelled">{{ __('customers.reservations.cancelled') }}</option>
                                </select>
                            </div>
                            <div class="form-group full-width">
                                <label for="customer-notes" class="form-label">{{ __('customers.reservations.customer_notes') }}</label>
                                <textarea id="customer-notes" name="customer_notes" class="form-textarea" rows="3" 
                                          placeholder="{{ __('customers.reservations.customer_notes_placeholder') }}"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Preferences Tab -->
                    <div class="form-tab-panel" x-show="activeFormTab === 'preferences'" x-transition>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">{{ __('customers.reservations.seating_preferences') }}</label>
                                <div class="checkbox-group">
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="seating_preferences[]" value="window">
                                        <span class="checkbox-label">{{ __('customers.reservations.window_seat') }}</span>
                                    </label>
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="seating_preferences[]" value="booth">
                                        <span class="checkbox-label">{{ __('customers.reservations.booth') }}</span>
                                    </label>
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="seating_preferences[]" value="quiet">
                                        <span class="checkbox-label">{{ __('customers.reservations.quiet_area') }}</span>
                                    </label>
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="seating_preferences[]" value="outdoor">
                                        <span class="checkbox-label">{{ __('customers.reservations.outdoor_seating') }}</span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{ __('customers.reservations.occasion') }}</label>
                                <div class="radio-group">
                                    <label class="radio-item">
                                        <input type="radio" name="occasion" value="">
                                        <span class="radio-label">{{ __('customers.reservations.regular_dining') }}</span>
                                    </label>
                                    <label class="radio-item">
                                        <input type="radio" name="occasion" value="birthday">
                                        <span class="radio-label">{{ __('customers.reservations.birthday') }}</span>
                                    </label>
                                    <label class="radio-item">
                                        <input type="radio" name="occasion" value="anniversary">
                                        <span class="radio-label">{{ __('customers.reservations.anniversary') }}</span>
                                    </label>
                                    <label class="radio-item">
                                        <input type="radio" name="occasion" value="business">
                                        <span class="radio-label">{{ __('customers.reservations.business_meeting') }}</span>
                                    </label>
                                    <label class="radio-item">
                                        <input type="radio" name="occasion" value="celebration">
                                        <span class="radio-label">{{ __('customers.reservations.celebration') }}</span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group full-width">
                                <label for="dietary-requirements" class="form-label">{{ __('customers.reservations.dietary_requirements') }}</label>
                                <textarea id="dietary-requirements" name="dietary_requirements" class="form-textarea" rows="2" 
                                          placeholder="{{ __('customers.reservations.dietary_requirements_placeholder') }}"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary cancel-reservation-btn">
                {{ __('customers.reservations.cancel') }}
            </button>
            <button type="submit" form="reservation-form" class="btn btn-primary save-reservation-btn">
                {{ __('customers.reservations.save_reservation') }}
            </button>
        </div>
    </div>
</div>

<!-- Add Table Modal -->
<div class="table-modal" id="table-modal" style="display: none;" role="dialog" aria-labelledby="table-modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="table-modal-title" class="modal-title">{{ __('customers.reservations.add_table') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('customers.reservations.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form id="table-form" class="table-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="table-number" class="form-label required">{{ __('customers.reservations.table_number') }}</label>
                        <input type="text" id="table-number" name="number" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="table-capacity" class="form-label required">{{ __('customers.reservations.capacity') }}</label>
                        <select id="table-capacity" name="capacity" class="form-select" required>
                            <option value="">{{ __('customers.reservations.select_capacity') }}</option>
                            <option value="2">2 {{ __('customers.reservations.people') }}</option>
                            <option value="4">4 {{ __('customers.reservations.people') }}</option>
                            <option value="6">6 {{ __('customers.reservations.people') }}</option>
                            <option value="8">8 {{ __('customers.reservations.people') }}</option>
                            <option value="10">10 {{ __('customers.reservations.people') }}</option>
                            <option value="12">12+ {{ __('customers.reservations.people') }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="table-type" class="form-label">{{ __('customers.reservations.table_type') }}</label>
                        <select id="table-type" name="type" class="form-select">
                            <option value="regular">{{ __('customers.reservations.regular_table') }}</option>
                            <option value="booth">{{ __('customers.reservations.booth') }}</option>
                            <option value="bar">{{ __('customers.reservations.bar_seating') }}</option>
                            <option value="outdoor">{{ __('customers.reservations.outdoor_table') }}</option>
                            <option value="private">{{ __('customers.reservations.private_dining') }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="table-location" class="form-label">{{ __('customers.reservations.location') }}</label>
                        <select id="table-location" name="location" class="form-select">
                            <option value="main">{{ __('customers.reservations.main_dining') }}</option>
                            <option value="window">{{ __('customers.reservations.window_area') }}</option>
                            <option value="patio">{{ __('customers.reservations.patio') }}</option>
                            <option value="bar_area">{{ __('customers.reservations.bar_area') }}</option>
                            <option value="private_room">{{ __('customers.reservations.private_room') }}</option>
                        </select>
                    </div>
                    <div class="form-group full-width">
                        <label for="table-notes" class="form-label">{{ __('customers.reservations.table_notes') }}</label>
                        <textarea id="table-notes" name="notes" class="form-textarea" rows="2" 
                                  placeholder="{{ __('customers.reservations.table_notes_placeholder') }}"></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary cancel-table-btn">
                {{ __('customers.reservations.cancel') }}
            </button>
            <button type="submit" form="table-form" class="btn btn-primary save-table-btn">
                {{ __('customers.reservations.save_table') }}
            </button>
        </div>
    </div>
</div>

<!-- Reservation Details Modal -->
<div class="reservation-details-modal" id="reservation-details-modal" style="display: none;" role="dialog" aria-labelledby="reservation-details-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content large">
        <div class="modal-header">
            <h2 id="reservation-details-title" class="modal-title">{{ __('customers.reservations.reservation_details') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('customers.reservations.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="reservation-details-content" id="reservation-details-content">
                <!-- Reservation details will be populated by JavaScript -->
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary close-details-btn">
                {{ __('customers.reservations.close') }}
            </button>
            <button type="button" class="btn btn-warning cancel-reservation-action-btn">
                {{ __('customers.reservations.cancel_reservation') }}
            </button>
            <button type="button" class="btn btn-success confirm-reservation-btn">
                {{ __('customers.reservations.confirm_reservation') }}
            </button>
            <button type="button" class="btn btn-primary edit-reservation-btn">
                {{ __('customers.reservations.edit_reservation') }}
            </button>
        </div>
    </div>
</div>
@endsection
