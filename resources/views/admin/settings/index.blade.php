@extends('layouts.admin')

@section('title', __('settings.title') . ' - ' . config('app.name'))
@section('page_title', __('settings.title'))

@push('styles')
    {{-- CSS styles will be loaded via main layout --}}
@endpush

@push('scripts')
    @vite('resources/js/admin/restaurant-settings.js')
@endpush

@section('content')
<div class="settings-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('settings.title') }}</h1>
                <p class="page-subtitle">{{ __('settings.subtitle') }}</p>
            </div>
            <div class="page-actions">
                <button type="button" class="btn btn-secondary reset-defaults-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    {{ __('settings.reset_defaults') }}
                </button>
                <button type="button" class="btn btn-primary save-settings-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ __('settings.save_settings') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Settings Tabs -->
    <div class="settings-content">
        <div class="settings-tabs" x-data="{ activeTab: 'general' }">
            <div class="tab-nav">
                <button type="button" 
                        class="tab-btn" 
                        :class="{ 'active': activeTab === 'general' }"
                        @click="activeTab = 'general'">
                    <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    {{ __('settings.general.title') }}
                </button>
                <button type="button" 
                        class="tab-btn" 
                        :class="{ 'active': activeTab === 'hours' }"
                        @click="activeTab = 'hours'">
                    <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ __('settings.operating_hours') }}
                </button>
                <button type="button" 
                        class="tab-btn" 
                        :class="{ 'active': activeTab === 'contact' }"
                        @click="activeTab = 'contact'">
                    <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ __('settings.contact_info') }}
                </button>
                <button type="button" 
                        class="tab-btn" 
                        :class="{ 'active': activeTab === 'preferences' }"
                        @click="activeTab = 'preferences'">
                    <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                    </svg>
                    {{ __('settings.preferences') }}
                </button>
                <button type="button" 
                        class="tab-btn" 
                        :class="{ 'active': activeTab === 'notifications' }"
                        @click="activeTab = 'notifications'">
                    <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM12 3C8.686 3 6 5.686 6 9c0 2.829 1.308 5.35 3.359 7.003L7 22l6-2 6 2-2.359-5.997C18.692 14.35 20 11.829 20 9c0-3.314-2.686-6-6-6z"/>
                    </svg>
                    {{ __('settings.notifications') }}
                </button>
            </div>

            <!-- General Settings Tab -->
            <div class="tab-panel" x-show="activeTab === 'general'" x-transition>
                <div class="settings-section">
                    <form id="general-settings-form" class="settings-form">
                        <!-- Restaurant Branding -->
                        <div class="settings-card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('settings.restaurant_branding') }}</h3>
                                <p class="card-description">{{ __('settings.branding_description') }}</p>
                            </div>
                            <div class="card-body">
                                <div class="form-grid">
                                    <div class="form-group full-width">
                                        <label for="restaurant-name" class="form-label required">{{ __('settings.restaurant_name') }}</label>
                                        <input type="text" id="restaurant-name" name="restaurant_name" class="form-input" 
                                               value="Geez Restaurant" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="restaurant-tagline" class="form-label">{{ __('settings.restaurant_tagline') }}</label>
                                        <input type="text" id="restaurant-tagline" name="restaurant_tagline" class="form-input" 
                                               placeholder="{{ __('settings.tagline_placeholder') }}">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="restaurant-type" class="form-label">{{ __('settings.restaurant_type') }}</label>
                                        <select id="restaurant-type" name="restaurant_type" class="form-select">
                                            <option value="casual_dining">{{ __('settings.casual_dining') }}</option>
                                            <option value="fine_dining">{{ __('settings.fine_dining') }}</option>
                                            <option value="fast_casual">{{ __('settings.fast_casual') }}</option>
                                            <option value="cafe">{{ __('settings.cafe') }}</option>
                                            <option value="bar_grill">{{ __('settings.bar_grill') }}</option>
                                            <option value="ethnic">{{ __('settings.ethnic_cuisine') }}</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group full-width">
                                        <label for="restaurant-logo" class="form-label">{{ __('settings.restaurant_logo') }}</label>
                                        <div class="logo-upload-container">
                                            <div class="logo-guidelines">
                                                <div class="guidelines-info">
                                                    <h4 class="guidelines-title">{{ __('settings.logo_guidelines') }}</h4>
                                                    <ul class="guidelines-list">
                                                        <li>{{ __('settings.logo_size_guide') }}</li>
                                                        <li>{{ __('settings.logo_format_guide') }}</li>
                                                        <li>{{ __('settings.logo_file_size_guide') }}</li>
                                                        <li>{{ __('settings.logo_quality_guide') }}</li>
                                                    </ul>
                                                </div>
                                                <div class="size-preview">
                                                    <div class="size-example">
                                                        <span class="size-label">{{ __('settings.recommended_size') }}</span>
                                                        <div class="size-box">200×200px</div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="logo-upload-area" id="logo-upload-area">
                                                <div class="current-logo" id="current-logo">
                                                    <img src="/images/logo-placeholder.svg" alt="Current Logo" id="logo-preview">
                                                    <div class="logo-dimensions" id="logo-dimensions">200×200px</div>
                                                </div>
                                                <div class="upload-controls">
                                                    <input type="file" id="logo-file" accept="image/png,image/jpeg,image/jpg,image/svg+xml" style="display: none;">
                                                    <button type="button" class="btn btn-secondary upload-logo-btn">
                                                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                                        </svg>
                                                        {{ __('settings.upload_logo') }}
                                                    </button>
                                                    <button type="button" class="btn btn-outline remove-logo-btn">
                                                        {{ __('settings.remove_logo') }}
                                                    </button>
                                                </div>
                                                <div class="upload-info">
                                                    <p class="upload-hint">{{ __('settings.drag_drop_hint') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Restaurant Details -->
                        <div class="settings-card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('settings.restaurant_details') }}</h3>
                                <p class="card-description">{{ __('settings.details_description') }}</p>
                            </div>
                            <div class="card-body">
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="cuisine-type" class="form-label">{{ __('settings.cuisine_type') }}</label>
                                        <select id="cuisine-type" name="cuisine_type" class="form-select">
                                            <option value="ethiopian" selected>{{ __('settings.ethiopian') }}</option>
                                            <option value="italian">{{ __('settings.italian') }}</option>
                                            <option value="american">{{ __('settings.american') }}</option>
                                            <option value="asian">{{ __('settings.asian') }}</option>
                                            <option value="mediterranean">{{ __('settings.mediterranean') }}</option>
                                            <option value="fusion">{{ __('settings.fusion') }}</option>
                                            <option value="international">{{ __('settings.international') }}</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="seating-capacity" class="form-label">{{ __('settings.seating_capacity') }}</label>
                                        <input type="number" id="seating-capacity" name="seating_capacity" class="form-input" 
                                               min="1" max="500" value="60">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="price-range" class="form-label">{{ __('settings.price_range') }}</label>
                                        <select id="price-range" name="price_range" class="form-select">
                                            <option value="budget">{{ __('settings.budget_friendly') }}</option>
                                            <option value="moderate" selected>{{ __('settings.moderate') }}</option>
                                            <option value="upscale">{{ __('settings.upscale') }}</option>
                                            <option value="fine_dining">{{ __('settings.fine_dining_price') }}</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="default-language" class="form-label">{{ __('settings.default_language') }}</label>
                                        <select id="default-language" name="default_language" class="form-select">
                                            <option value="en" selected>{{ __('settings.english') }}</option>
                                            <option value="am">{{ __('settings.amharic') }}</option>
                                            <option value="ti">{{ __('settings.tigrinya') }}</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group full-width">
                                        <label for="restaurant-description" class="form-label">{{ __('settings.restaurant_description') }}</label>
                                        <textarea id="restaurant-description" name="restaurant_description" class="form-textarea" rows="4"
                                                  placeholder="{{ __('settings.description_placeholder') }}"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Operating Hours Tab -->
            <div class="tab-panel" x-show="activeTab === 'hours'" x-transition>
                <div class="settings-section">
                    <form id="hours-settings-form" class="settings-form">
                        <div class="settings-card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('settings.operating_hours') }}</h3>
                                <p class="card-description">{{ __('settings.hours_description') }}</p>
                            </div>
                            <div class="card-body">
                                <div class="hours-grid">
                                    <div class="day-schedule" data-day="monday">
                                        <div class="day-header">
                                            <div class="day-info">
                                                <label class="day-label">{{ __('settings.monday') }}</label>
                                                <div class="day-toggle">
                                                    <input type="checkbox" id="monday-enabled" class="toggle-input" checked>
                                                    <label for="monday-enabled" class="toggle-label">{{ __('settings.open') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="time-inputs">
                                            <div class="time-group">
                                                <label class="time-label">{{ __('settings.opening_time') }}</label>
                                                <input type="time" name="monday_open" class="time-input" value="09:00">
                                            </div>
                                            <div class="time-group">
                                                <label class="time-label">{{ __('settings.closing_time') }}</label>
                                                <input type="time" name="monday_close" class="time-input" value="22:00">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="day-schedule" data-day="tuesday">
                                        <div class="day-header">
                                            <div class="day-info">
                                                <label class="day-label">{{ __('settings.tuesday') }}</label>
                                                <div class="day-toggle">
                                                    <input type="checkbox" id="tuesday-enabled" class="toggle-input" checked>
                                                    <label for="tuesday-enabled" class="toggle-label">{{ __('settings.open') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="time-inputs">
                                            <div class="time-group">
                                                <label class="time-label">{{ __('settings.opening_time') }}</label>
                                                <input type="time" name="tuesday_open" class="time-input" value="09:00">
                                            </div>
                                            <div class="time-group">
                                                <label class="time-label">{{ __('settings.closing_time') }}</label>
                                                <input type="time" name="tuesday_close" class="time-input" value="22:00">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="day-schedule" data-day="wednesday">
                                        <div class="day-header">
                                            <div class="day-info">
                                                <label class="day-label">{{ __('settings.wednesday') }}</label>
                                                <div class="day-toggle">
                                                    <input type="checkbox" id="wednesday-enabled" class="toggle-input" checked>
                                                    <label for="wednesday-enabled" class="toggle-label">{{ __('settings.open') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="time-inputs">
                                            <div class="time-group">
                                                <label class="time-label">{{ __('settings.opening_time') }}</label>
                                                <input type="time" name="wednesday_open" class="time-input" value="09:00">
                                            </div>
                                            <div class="time-group">
                                                <label class="time-label">{{ __('settings.closing_time') }}</label>
                                                <input type="time" name="wednesday_close" class="time-input" value="22:00">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="day-schedule" data-day="thursday">
                                        <div class="day-header">
                                            <div class="day-info">
                                                <label class="day-label">{{ __('settings.thursday') }}</label>
                                                <div class="day-toggle">
                                                    <input type="checkbox" id="thursday-enabled" class="toggle-input" checked>
                                                    <label for="thursday-enabled" class="toggle-label">{{ __('settings.open') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="time-inputs">
                                            <div class="time-group">
                                                <label class="time-label">{{ __('settings.opening_time') }}</label>
                                                <input type="time" name="thursday_open" class="time-input" value="09:00">
                                            </div>
                                            <div class="time-group">
                                                <label class="time-label">{{ __('settings.closing_time') }}</label>
                                                <input type="time" name="thursday_close" class="time-input" value="22:00">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="day-schedule" data-day="friday">
                                        <div class="day-header">
                                            <div class="day-info">
                                                <label class="day-label">{{ __('settings.friday') }}</label>
                                                <div class="day-toggle">
                                                    <input type="checkbox" id="friday-enabled" class="toggle-input" checked>
                                                    <label for="friday-enabled" class="toggle-label">{{ __('settings.open') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="time-inputs">
                                            <div class="time-group">
                                                <label class="time-label">{{ __('settings.opening_time') }}</label>
                                                <input type="time" name="friday_open" class="time-input" value="09:00">
                                            </div>
                                            <div class="time-group">
                                                <label class="time-label">{{ __('settings.closing_time') }}</label>
                                                <input type="time" name="friday_close" class="time-input" value="23:00">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="day-schedule" data-day="saturday">
                                        <div class="day-header">
                                            <div class="day-info">
                                                <label class="day-label">{{ __('settings.saturday') }}</label>
                                                <div class="day-toggle">
                                                    <input type="checkbox" id="saturday-enabled" class="toggle-input" checked>
                                                    <label for="saturday-enabled" class="toggle-label">{{ __('settings.open') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="time-inputs">
                                            <div class="time-group">
                                                <label class="time-label">{{ __('settings.opening_time') }}</label>
                                                <input type="time" name="saturday_open" class="time-input" value="09:00">
                                            </div>
                                            <div class="time-group">
                                                <label class="time-label">{{ __('settings.closing_time') }}</label>
                                                <input type="time" name="saturday_close" class="time-input" value="23:00">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="day-schedule" data-day="sunday">
                                        <div class="day-header">
                                            <div class="day-info">
                                                <label class="day-label">{{ __('settings.sunday') }}</label>
                                                <div class="day-toggle">
                                                    <input type="checkbox" id="sunday-enabled" class="toggle-input">
                                                    <label for="sunday-enabled" class="toggle-label">{{ __('settings.closed') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="time-inputs">
                                            <div class="time-group">
                                                <label class="time-label">{{ __('settings.opening_time') }}</label>
                                                <input type="time" name="sunday_open" class="time-input" value="10:00" disabled>
                                            </div>
                                            <div class="time-group">
                                                <label class="time-label">{{ __('settings.closing_time') }}</label>
                                                <input type="time" name="sunday_close" class="time-input" value="21:00" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="hours-actions">
                                    <button type="button" class="btn btn-outline copy-hours-btn">
                                        {{ __('settings.copy_to_all') }}
                                    </button>
                                    <button type="button" class="btn btn-outline reset-hours-btn">
                                        {{ __('settings.reset_hours') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Contact Information Tab -->
            <div class="tab-panel" x-show="activeTab === 'contact'" x-transition>
                <div class="settings-section">
                    <form id="contact-settings-form" class="settings-form">
                        <div class="settings-card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('settings.contact_information') }}</h3>
                                <p class="card-description">{{ __('settings.contact_description') }}</p>
                            </div>
                            <div class="card-body">
                                <div class="form-grid">
                                    <div class="form-group full-width">
                                        <label for="restaurant-address" class="form-label required">{{ __('settings.address') }}</label>
                                        <textarea id="restaurant-address" name="address" class="form-textarea" rows="3" required
                                                  placeholder="{{ __('settings.address_placeholder') }}"></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="restaurant-phone" class="form-label required">{{ __('settings.phone_number') }}</label>
                                        <input type="tel" id="restaurant-phone" name="phone" class="form-input" required
                                               placeholder="{{ __('settings.phone_placeholder') }}">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="restaurant-email" class="form-label">{{ __('settings.email_address') }}</label>
                                        <input type="email" id="restaurant-email" name="email" class="form-input"
                                               placeholder="{{ __('settings.email_placeholder') }}">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="restaurant-website" class="form-label">{{ __('settings.website') }}</label>
                                        <input type="url" id="restaurant-website" name="website" class="form-input"
                                               placeholder="{{ __('settings.website_placeholder') }}">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="restaurant-social" class="form-label">{{ __('settings.social_media') }}</label>
                                        <input type="url" id="restaurant-social" name="social_media" class="form-input"
                                               placeholder="{{ __('settings.social_placeholder') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Location Settings -->
                        <div class="settings-card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('settings.location_settings') }}</h3>
                                <p class="card-description">{{ __('settings.location_description') }}</p>
                            </div>
                            <div class="card-body">
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="timezone" class="form-label">{{ __('settings.timezone') }}</label>
                                        <select id="timezone" name="timezone" class="form-select">
                                            <option value="Africa/Addis_Ababa" selected>{{ __('settings.addis_ababa') }}</option>
                                            <option value="UTC">{{ __('settings.utc') }}</option>
                                            <option value="Europe/London">{{ __('settings.london') }}</option>
                                            <option value="America/New_York">{{ __('settings.new_york') }}</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="currency" class="form-label">{{ __('settings.currency') }}</label>
                                        <select id="currency" name="currency" class="form-select">
                                            <option value="ETB" selected>{{ __('settings.ethiopian_birr') }}</option>
                                            <option value="USD">{{ __('settings.us_dollar') }}</option>
                                            <option value="EUR">{{ __('settings.euro') }}</option>
                                            <option value="GBP">{{ __('settings.british_pound') }}</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="tax-rate" class="form-label">{{ __('settings.tax_rate') }}</label>
                                        <div class="input-group">
                                            <input type="number" id="tax-rate" name="tax_rate" class="form-input" 
                                                   min="0" max="100" step="0.01" value="15.00">
                                            <span class="input-suffix">%</span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="service-charge" class="form-label">{{ __('settings.service_charge') }}</label>
                                        <div class="input-group">
                                            <input type="number" id="service-charge" name="service_charge" class="form-input" 
                                                   min="0" max="100" step="0.01" value="10.00">
                                            <span class="input-suffix">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Preferences Tab -->
            <div class="tab-panel" x-show="activeTab === 'preferences'" x-transition>
                <div class="settings-section">
                    <form id="preferences-settings-form" class="settings-form">
                        <!-- Reservation Settings -->
                        <div class="settings-card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('settings.reservation_settings') }}</h3>
                                <p class="card-description">{{ __('settings.reservation_description') }}</p>
                            </div>
                            <div class="card-body">
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="max-party-size" class="form-label">{{ __('settings.max_party_size') }}</label>
                                        <input type="number" id="max-party-size" name="max_party_size" class="form-input" 
                                               min="1" max="50" value="12">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="advance-booking" class="form-label">{{ __('settings.advance_booking_days') }}</label>
                                        <input type="number" id="advance-booking" name="advance_booking_days" class="form-input" 
                                               min="1" max="365" value="30">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="reservation-duration" class="form-label">{{ __('settings.default_reservation_duration') }}</label>
                                        <select id="reservation-duration" name="reservation_duration" class="form-select">
                                            <option value="60">1 {{ __('settings.hour') }}</option>
                                            <option value="90" selected>1.5 {{ __('settings.hours') }}</option>
                                            <option value="120">2 {{ __('settings.hours') }}</option>
                                            <option value="180">3 {{ __('settings.hours') }}</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="checkbox-group">
                                            <input type="checkbox" id="allow-walk-ins" name="allow_walk_ins" class="form-checkbox" checked>
                                            <label for="allow-walk-ins" class="checkbox-label">{{ __('settings.allow_walk_ins') }}</label>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="checkbox-group">
                                            <input type="checkbox" id="require-confirmation" name="require_confirmation" class="form-checkbox" checked>
                                            <label for="require-confirmation" class="checkbox-label">{{ __('settings.require_confirmation') }}</label>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="checkbox-group">
                                            <input type="checkbox" id="auto-confirm" name="auto_confirm" class="form-checkbox">
                                            <label for="auto-confirm" class="checkbox-label">{{ __('settings.auto_confirm_reservations') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Settings -->
                        <div class="settings-card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('settings.order_settings') }}</h3>
                                <p class="card-description">{{ __('settings.order_description') }}</p>
                            </div>
                            <div class="card-body">
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="order-timeout" class="form-label">{{ __('settings.order_timeout') }}</label>
                                        <select id="order-timeout" name="order_timeout" class="form-select">
                                            <option value="15">15 {{ __('settings.minutes') }}</option>
                                            <option value="30" selected>30 {{ __('settings.minutes') }}</option>
                                            <option value="45">45 {{ __('settings.minutes') }}</option>
                                            <option value="60">1 {{ __('settings.hour') }}</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="min-order-amount" class="form-label">{{ __('settings.minimum_order_amount') }}</label>
                                        <div class="input-group">
                                            <span class="input-prefix">ETB</span>
                                            <input type="number" id="min-order-amount" name="min_order_amount" class="form-input" 
                                                   min="0" step="0.01" value="50.00">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="checkbox-group">
                                            <input type="checkbox" id="allow-special-requests" name="allow_special_requests" class="form-checkbox" checked>
                                            <label for="allow-special-requests" class="checkbox-label">{{ __('settings.allow_special_requests') }}</label>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="checkbox-group">
                                            <input type="checkbox" id="require-phone" name="require_phone" class="form-checkbox" checked>
                                            <label for="require-phone" class="checkbox-label">{{ __('settings.require_phone_orders') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Notifications Tab -->
            <div class="tab-panel" x-show="activeTab === 'notifications'" x-transition>
                <div class="settings-section">
                    <form id="notifications-settings-form" class="settings-form">
                        <!-- Email Notifications -->
                        <div class="settings-card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('settings.email_notifications') }}</h3>
                                <p class="card-description">{{ __('settings.email_description') }}</p>
                            </div>
                            <div class="card-body">
                                <div class="form-grid">
                                    <div class="form-group full-width">
                                        <label for="notification-email" class="form-label">{{ __('settings.notification_email') }}</label>
                                        <input type="email" id="notification-email" name="notification_email" class="form-input"
                                               placeholder="{{ __('settings.notification_email_placeholder') }}">
                                    </div>
                                    
                                    <div class="notification-options">
                                        <div class="notification-group">
                                            <h4 class="notification-title">{{ __('settings.reservation_notifications') }}</h4>
                                            <div class="checkbox-group">
                                                <input type="checkbox" id="notify-new-reservation" name="notify_new_reservation" class="form-checkbox" checked>
                                                <label for="notify-new-reservation" class="checkbox-label">{{ __('settings.new_reservations') }}</label>
                                            </div>
                                            <div class="checkbox-group">
                                                <input type="checkbox" id="notify-cancelled-reservation" name="notify_cancelled_reservation" class="form-checkbox" checked>
                                                <label for="notify-cancelled-reservation" class="checkbox-label">{{ __('settings.cancelled_reservations') }}</label>
                                            </div>
                                            <div class="checkbox-group">
                                                <input type="checkbox" id="notify-no-show" name="notify_no_show" class="form-checkbox" checked>
                                                <label for="notify-no-show" class="checkbox-label">{{ __('settings.no_shows') }}</label>
                                            </div>
                                        </div>
                                        
                                        <div class="notification-group">
                                            <h4 class="notification-title">{{ __('settings.order_notifications') }}</h4>
                                            <div class="checkbox-group">
                                                <input type="checkbox" id="notify-new-order" name="notify_new_order" class="form-checkbox" checked>
                                                <label for="notify-new-order" class="checkbox-label">{{ __('settings.new_orders') }}</label>
                                            </div>
                                            <div class="checkbox-group">
                                                <input type="checkbox" id="notify-cancelled-order" name="notify_cancelled_order" class="form-checkbox">
                                                <label for="notify-cancelled-order" class="checkbox-label">{{ __('settings.cancelled_orders') }}</label>
                                            </div>
                                        </div>
                                        
                                        <div class="notification-group">
                                            <h4 class="notification-title">{{ __('settings.inventory_notifications') }}</h4>
                                            <div class="checkbox-group">
                                                <input type="checkbox" id="notify-low-stock" name="notify_low_stock" class="form-checkbox" checked>
                                                <label for="notify-low-stock" class="checkbox-label">{{ __('settings.low_stock_alerts') }}</label>
                                            </div>
                                            <div class="checkbox-group">
                                                <input type="checkbox" id="notify-expired-items" name="notify_expired_items" class="form-checkbox" checked>
                                                <label for="notify-expired-items" class="checkbox-label">{{ __('settings.expired_items') }}</label>
                                            </div>
                                        </div>
                                        
                                        <div class="notification-group">
                                            <h4 class="notification-title">{{ __('settings.review_notifications') }}</h4>
                                            <div class="checkbox-group">
                                                <input type="checkbox" id="notify-new-review" name="notify_new_review" class="form-checkbox" checked>
                                                <label for="notify-new-review" class="checkbox-label">{{ __('settings.new_reviews') }}</label>
                                            </div>
                                            <div class="checkbox-group">
                                                <input type="checkbox" id="notify-negative-review" name="notify_negative_review" class="form-checkbox" checked>
                                                <label for="notify-negative-review" class="checkbox-label">{{ __('settings.negative_reviews') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
