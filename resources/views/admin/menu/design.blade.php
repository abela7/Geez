@extends('layouts.admin')

@section('title', __('menu.design.title') . ' - ' . config('app.name'))
@section('page_title', __('menu.design.title'))

@push('styles')
    @vite('resources/css/admin/menu-design.css')
@endpush

@push('scripts')
    @vite('resources/js/admin/menu-design.js')
@endpush

@section('content')
<div class="menu-design-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('menu.design.title') }}</h1>
                <p class="page-subtitle">{{ __('menu.design.subtitle') }}</p>
            </div>
            <div class="page-actions">
                <button type="button" class="btn btn-secondary preview-menu-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    {{ __('menu.design.preview_menu') }}
                </button>
                <button type="button" class="btn btn-secondary export-design-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ __('menu.design.export_design') }}
                </button>
                <button type="button" class="btn btn-primary save-design-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    {{ __('menu.design.save_design') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="design-content">
        <div class="design-layout">
            <!-- Left Panel - Design Controls -->
            <div class="design-controls-panel">
                <!-- Design Tabs -->
                <div class="design-tabs" x-data="{ activeTab: 'branding' }">
                    <div class="tab-nav">
                        <button type="button" 
                                class="tab-btn" 
                                :class="{ 'active': activeTab === 'branding' }"
                                @click="activeTab = 'branding'">
                            <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            {{ __('menu.design.branding') }}
                        </button>
                        <button type="button" 
                                class="tab-btn" 
                                :class="{ 'active': activeTab === 'layout' }"
                                @click="activeTab = 'layout'">
                            <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                            </svg>
                            {{ __('menu.design.layout') }}
                        </button>
                        <button type="button" 
                                class="tab-btn" 
                                :class="{ 'active': activeTab === 'colors' }"
                                @click="activeTab = 'colors'">
                            <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM7 3H5v12a2 2 0 002 2 2 2 0 002-2V3zM21 21l-6-6m6 6v-4.5m0 4.5h-4.5"/>
                            </svg>
                            {{ __('menu.design.colors') }}
                        </button>
                        <button type="button" 
                                class="tab-btn" 
                                :class="{ 'active': activeTab === 'typography' }"
                                @click="activeTab = 'typography'">
                            <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 20l0 -10M10 20l0 -10M6 15l4 0M21 5l-2 14l-4 -1l-2 -14l8 1z"/>
                            </svg>
                            {{ __('menu.design.typography') }}
                        </button>
                        <button type="button" 
                                class="tab-btn" 
                                :class="{ 'active': activeTab === 'content' }"
                                @click="activeTab = 'content'">
                            <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            {{ __('menu.design.content') }}
                        </button>
                    </div>

                    <!-- Branding Tab -->
                    <div class="tab-panel" x-show="activeTab === 'branding'" x-transition>
                        <div class="design-section">
                            <h3 class="section-title">{{ __('menu.design.restaurant_branding') }}</h3>
                            
                            <!-- Logo Upload -->
                            <div class="form-group">
                                <label class="form-label">{{ __('menu.design.logo') }}</label>
                                <div class="logo-upload-area" id="logo-upload">
                                    <div class="upload-placeholder">
                                        <svg class="upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                        <p>{{ __('menu.design.upload_logo') }}</p>
                                        <span class="upload-hint">{{ __('menu.design.logo_hint') }}</span>
                                    </div>
                                    <input type="file" id="logo-input" accept="image/*" style="display: none;">
                                </div>
                                <div class="logo-preview" id="logo-preview" style="display: none;">
                                    <img id="logo-image" src="" alt="Logo">
                                    <button type="button" class="remove-logo-btn" id="remove-logo">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Restaurant Name -->
                            <div class="form-group">
                                <label for="restaurant-name" class="form-label">{{ __('menu.design.restaurant_name') }}</label>
                                <input type="text" id="restaurant-name" class="form-input" 
                                       placeholder="{{ __('menu.design.restaurant_name_placeholder') }}"
                                       value="Geez Restaurant">
                            </div>

                            <!-- Restaurant Address -->
                            <div class="form-group">
                                <label for="restaurant-address" class="form-label">{{ __('menu.design.restaurant_address') }}</label>
                                <textarea id="restaurant-address" class="form-textarea" rows="3"
                                          placeholder="{{ __('menu.design.address_placeholder') }}">123 Main Street, City, Country
Phone: +1 234 567 8900
Email: info@geezrestaurant.com</textarea>
                            </div>

                            <!-- Restaurant Description -->
                            <div class="form-group">
                                <label for="restaurant-description" class="form-label">{{ __('menu.design.restaurant_description') }}</label>
                                <textarea id="restaurant-description" class="form-textarea" rows="4"
                                          placeholder="{{ __('menu.design.description_placeholder') }}">Experience authentic flavors and exceptional dining in our warm, welcoming atmosphere. We pride ourselves on using fresh, locally-sourced ingredients to create memorable culinary experiences.</textarea>
                            </div>

                            <!-- Social Media Links -->
                            <div class="form-group">
                                <label class="form-label">{{ __('menu.design.social_media') }}</label>
                                <div class="social-links-grid">
                                    <div class="social-link-input">
                                        <label for="facebook-url">Facebook</label>
                                        <input type="url" id="facebook-url" class="form-input" placeholder="https://facebook.com/restaurant">
                                    </div>
                                    <div class="social-link-input">
                                        <label for="instagram-url">Instagram</label>
                                        <input type="url" id="instagram-url" class="form-input" placeholder="https://instagram.com/restaurant">
                                    </div>
                                    <div class="social-link-input">
                                        <label for="twitter-url">Twitter</label>
                                        <input type="url" id="twitter-url" class="form-input" placeholder="https://twitter.com/restaurant">
                                    </div>
                                    <div class="social-link-input">
                                        <label for="website-url">Website</label>
                                        <input type="url" id="website-url" class="form-input" placeholder="https://restaurant.com">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Layout Tab -->
                    <div class="tab-panel" x-show="activeTab === 'layout'" x-transition>
                        <div class="design-section">
                            <h3 class="section-title">{{ __('menu.design.menu_layout') }}</h3>
                            
                            <!-- Layout Templates -->
                            <div class="form-group">
                                <label class="form-label">{{ __('menu.design.layout_template') }}</label>
                                <div class="layout-templates">
                                    <div class="layout-option" data-layout="classic">
                                        <div class="layout-preview classic-layout">
                                            <div class="preview-header"></div>
                                            <div class="preview-content">
                                                <div class="preview-section"></div>
                                                <div class="preview-section"></div>
                                            </div>
                                        </div>
                                        <span class="layout-name">{{ __('menu.design.classic_layout') }}</span>
                                    </div>
                                    <div class="layout-option active" data-layout="modern">
                                        <div class="layout-preview modern-layout">
                                            <div class="preview-header"></div>
                                            <div class="preview-content">
                                                <div class="preview-grid">
                                                    <div class="preview-item"></div>
                                                    <div class="preview-item"></div>
                                                    <div class="preview-item"></div>
                                                    <div class="preview-item"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="layout-name">{{ __('menu.design.modern_layout') }}</span>
                                    </div>
                                    <div class="layout-option" data-layout="elegant">
                                        <div class="layout-preview elegant-layout">
                                            <div class="preview-header"></div>
                                            <div class="preview-content">
                                                <div class="preview-columns">
                                                    <div class="preview-column"></div>
                                                    <div class="preview-column"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="layout-name">{{ __('menu.design.elegant_layout') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Page Settings -->
                            <div class="form-group">
                                <label class="form-label">{{ __('menu.design.page_settings') }}</label>
                                <div class="settings-grid">
                                    <div class="setting-item">
                                        <label for="page-size">{{ __('menu.design.page_size') }}</label>
                                        <select id="page-size" class="form-select">
                                            <option value="a4">A4 (210 × 297 mm)</option>
                                            <option value="letter">Letter (8.5 × 11 in)</option>
                                            <option value="a3">A3 (297 × 420 mm)</option>
                                            <option value="tabloid">Tabloid (11 × 17 in)</option>
                                        </select>
                                    </div>
                                    <div class="setting-item">
                                        <label for="page-orientation">{{ __('menu.design.orientation') }}</label>
                                        <select id="page-orientation" class="form-select">
                                            <option value="portrait">{{ __('menu.design.portrait') }}</option>
                                            <option value="landscape">{{ __('menu.design.landscape') }}</option>
                                        </select>
                                    </div>
                                    <div class="setting-item">
                                        <label for="columns">{{ __('menu.design.columns') }}</label>
                                        <select id="columns" class="form-select">
                                            <option value="1">1 {{ __('menu.design.column') }}</option>
                                            <option value="2" selected>2 {{ __('menu.design.columns') }}</option>
                                            <option value="3">3 {{ __('menu.design.columns') }}</option>
                                        </select>
                                    </div>
                                    <div class="setting-item">
                                        <label for="spacing">{{ __('menu.design.spacing') }}</label>
                                        <select id="spacing" class="form-select">
                                            <option value="compact">{{ __('menu.design.compact') }}</option>
                                            <option value="normal" selected>{{ __('menu.design.normal') }}</option>
                                            <option value="spacious">{{ __('menu.design.spacious') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Section Order -->
                            <div class="form-group">
                                <label class="form-label">{{ __('menu.design.section_order') }}</label>
                                <div class="section-order-list" id="section-order">
                                    <div class="section-item" data-section="appetizers">
                                        <div class="section-handle">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                                            </svg>
                                        </div>
                                        <span class="section-name">{{ __('menu.design.appetizers') }}</span>
                                        <label class="section-toggle">
                                            <input type="checkbox" checked>
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                    <div class="section-item" data-section="main_courses">
                                        <div class="section-handle">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                                            </svg>
                                        </div>
                                        <span class="section-name">{{ __('menu.design.main_courses') }}</span>
                                        <label class="section-toggle">
                                            <input type="checkbox" checked>
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                    <div class="section-item" data-section="desserts">
                                        <div class="section-handle">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                                            </svg>
                                        </div>
                                        <span class="section-name">{{ __('menu.design.desserts') }}</span>
                                        <label class="section-toggle">
                                            <input type="checkbox" checked>
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                    <div class="section-item" data-section="beverages">
                                        <div class="section-handle">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                                            </svg>
                                        </div>
                                        <span class="section-name">{{ __('menu.design.beverages') }}</span>
                                        <label class="section-toggle">
                                            <input type="checkbox" checked>
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Colors Tab -->
                    <div class="tab-panel" x-show="activeTab === 'colors'" x-transition>
                        <div class="design-section">
                            <h3 class="section-title">{{ __('menu.design.color_scheme') }}</h3>
                            
                            <!-- Color Presets -->
                            <div class="form-group">
                                <label class="form-label">{{ __('menu.design.color_presets') }}</label>
                                <div class="color-presets">
                                    <div class="color-preset active" data-preset="classic">
                                        <div class="preset-colors">
                                            <div class="color-dot" style="background: #1f2937;"></div>
                                            <div class="color-dot" style="background: #f9fafb;"></div>
                                            <div class="color-dot" style="background: #dc2626;"></div>
                                        </div>
                                        <span class="preset-name">{{ __('menu.design.classic') }}</span>
                                    </div>
                                    <div class="color-preset" data-preset="elegant">
                                        <div class="preset-colors">
                                            <div class="color-dot" style="background: #374151;"></div>
                                            <div class="color-dot" style="background: #f3f4f6;"></div>
                                            <div class="color-dot" style="background: #d97706;"></div>
                                        </div>
                                        <span class="preset-name">{{ __('menu.design.elegant') }}</span>
                                    </div>
                                    <div class="color-preset" data-preset="modern">
                                        <div class="preset-colors">
                                            <div class="color-dot" style="background: #111827;"></div>
                                            <div class="color-dot" style="background: #ffffff;"></div>
                                            <div class="color-dot" style="background: #3b82f6;"></div>
                                        </div>
                                        <span class="preset-name">{{ __('menu.design.modern') }}</span>
                                    </div>
                                    <div class="color-preset" data-preset="warm">
                                        <div class="preset-colors">
                                            <div class="color-dot" style="background: #451a03;"></div>
                                            <div class="color-dot" style="background: #fef7ed;"></div>
                                            <div class="color-dot" style="background: #ea580c;"></div>
                                        </div>
                                        <span class="preset-name">{{ __('menu.design.warm') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Custom Colors -->
                            <div class="form-group">
                                <label class="form-label">{{ __('menu.design.custom_colors') }}</label>
                                <div class="color-inputs">
                                    <div class="color-input-group">
                                        <label for="primary-color">{{ __('menu.design.primary_color') }}</label>
                                        <div class="color-input-wrapper">
                                            <input type="color" id="primary-color" value="#1f2937">
                                            <input type="text" class="color-hex" value="#1f2937" readonly>
                                        </div>
                                    </div>
                                    <div class="color-input-group">
                                        <label for="background-color">{{ __('menu.design.background_color') }}</label>
                                        <div class="color-input-wrapper">
                                            <input type="color" id="background-color" value="#ffffff">
                                            <input type="text" class="color-hex" value="#ffffff" readonly>
                                        </div>
                                    </div>
                                    <div class="color-input-group">
                                        <label for="accent-color">{{ __('menu.design.accent_color') }}</label>
                                        <div class="color-input-wrapper">
                                            <input type="color" id="accent-color" value="#dc2626">
                                            <input type="text" class="color-hex" value="#dc2626" readonly>
                                        </div>
                                    </div>
                                    <div class="color-input-group">
                                        <label for="text-color">{{ __('menu.design.text_color') }}</label>
                                        <div class="color-input-wrapper">
                                            <input type="color" id="text-color" value="#374151">
                                            <input type="text" class="color-hex" value="#374151" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Background Options -->
                            <div class="form-group">
                                <label class="form-label">{{ __('menu.design.background_options') }}</label>
                                <div class="background-options">
                                    <label class="background-option">
                                        <input type="radio" name="background_type" value="solid" checked>
                                        <span class="option-indicator"></span>
                                        <span class="option-text">{{ __('menu.design.solid_color') }}</span>
                                    </label>
                                    <label class="background-option">
                                        <input type="radio" name="background_type" value="gradient">
                                        <span class="option-indicator"></span>
                                        <span class="option-text">{{ __('menu.design.gradient') }}</span>
                                    </label>
                                    <label class="background-option">
                                        <input type="radio" name="background_type" value="pattern">
                                        <span class="option-indicator"></span>
                                        <span class="option-text">{{ __('menu.design.pattern') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Typography Tab -->
                    <div class="tab-panel" x-show="activeTab === 'typography'" x-transition>
                        <div class="design-section">
                            <h3 class="section-title">{{ __('menu.design.typography_settings') }}</h3>
                            
                            <!-- Font Selection -->
                            <div class="form-group">
                                <label for="font-family" class="form-label">{{ __('menu.design.font_family') }}</label>
                                <select id="font-family" class="form-select">
                                    <option value="Inter">Inter (Modern Sans-serif)</option>
                                    <option value="Playfair Display">Playfair Display (Elegant Serif)</option>
                                    <option value="Roboto">Roboto (Clean Sans-serif)</option>
                                    <option value="Merriweather">Merriweather (Readable Serif)</option>
                                    <option value="Montserrat">Montserrat (Geometric Sans-serif)</option>
                                    <option value="Lora">Lora (Contemporary Serif)</option>
                                    <option value="Open Sans">Open Sans (Friendly Sans-serif)</option>
                                    <option value="Crimson Text">Crimson Text (Classic Serif)</option>
                                </select>
                            </div>

                            <!-- Font Sizes -->
                            <div class="form-group">
                                <label class="form-label">{{ __('menu.design.font_sizes') }}</label>
                                <div class="font-size-controls">
                                    <div class="font-size-item">
                                        <label for="title-size">{{ __('menu.design.title_size') }}</label>
                                        <div class="size-input-group">
                                            <input type="range" id="title-size" min="24" max="48" value="32">
                                            <span class="size-value">32px</span>
                                        </div>
                                    </div>
                                    <div class="font-size-item">
                                        <label for="heading-size">{{ __('menu.design.heading_size') }}</label>
                                        <div class="size-input-group">
                                            <input type="range" id="heading-size" min="18" max="32" value="24">
                                            <span class="size-value">24px</span>
                                        </div>
                                    </div>
                                    <div class="font-size-item">
                                        <label for="body-size">{{ __('menu.design.body_size') }}</label>
                                        <div class="size-input-group">
                                            <input type="range" id="body-size" min="12" max="20" value="16">
                                            <span class="size-value">16px</span>
                                        </div>
                                    </div>
                                    <div class="font-size-item">
                                        <label for="price-size">{{ __('menu.design.price_size') }}</label>
                                        <div class="size-input-group">
                                            <input type="range" id="price-size" min="14" max="24" value="18">
                                            <span class="size-value">18px</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Text Styling -->
                            <div class="form-group">
                                <label class="form-label">{{ __('menu.design.text_styling') }}</label>
                                <div class="text-style-options">
                                    <div class="style-option">
                                        <label class="style-label">
                                            <input type="checkbox" id="bold-headings">
                                            <span class="checkbox-indicator"></span>
                                            <span>{{ __('menu.design.bold_headings') }}</span>
                                        </label>
                                    </div>
                                    <div class="style-option">
                                        <label class="style-label">
                                            <input type="checkbox" id="italic-descriptions">
                                            <span class="checkbox-indicator"></span>
                                            <span>{{ __('menu.design.italic_descriptions') }}</span>
                                        </label>
                                    </div>
                                    <div class="style-option">
                                        <label class="style-label">
                                            <input type="checkbox" id="uppercase-categories">
                                            <span class="checkbox-indicator"></span>
                                            <span>{{ __('menu.design.uppercase_categories') }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Line Height & Spacing -->
                            <div class="form-group">
                                <label class="form-label">{{ __('menu.design.spacing_settings') }}</label>
                                <div class="spacing-controls">
                                    <div class="spacing-item">
                                        <label for="line-height">{{ __('menu.design.line_height') }}</label>
                                        <div class="spacing-input-group">
                                            <input type="range" id="line-height" min="1" max="2" step="0.1" value="1.5">
                                            <span class="spacing-value">1.5</span>
                                        </div>
                                    </div>
                                    <div class="spacing-item">
                                        <label for="paragraph-spacing">{{ __('menu.design.paragraph_spacing') }}</label>
                                        <div class="spacing-input-group">
                                            <input type="range" id="paragraph-spacing" min="8" max="32" value="16">
                                            <span class="spacing-value">16px</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Tab -->
                    <div class="tab-panel" x-show="activeTab === 'content'" x-transition>
                        <div class="design-section">
                            <h3 class="section-title">{{ __('menu.design.content_settings') }}</h3>
                            
                            <!-- Display Options -->
                            <div class="form-group">
                                <label class="form-label">{{ __('menu.design.display_options') }}</label>
                                <div class="display-options">
                                    <div class="display-option">
                                        <label class="option-label">
                                            <input type="checkbox" id="show-prices" checked>
                                            <span class="checkbox-indicator"></span>
                                            <span>{{ __('menu.design.show_prices') }}</span>
                                        </label>
                                    </div>
                                    <div class="display-option">
                                        <label class="option-label">
                                            <input type="checkbox" id="show-descriptions" checked>
                                            <span class="checkbox-indicator"></span>
                                            <span>{{ __('menu.design.show_descriptions') }}</span>
                                        </label>
                                    </div>
                                    <div class="display-option">
                                        <label class="option-label">
                                            <input type="checkbox" id="show-images">
                                            <span class="checkbox-indicator"></span>
                                            <span>{{ __('menu.design.show_images') }}</span>
                                        </label>
                                    </div>
                                    <div class="display-option">
                                        <label class="option-label">
                                            <input type="checkbox" id="show-dietary-info">
                                            <span class="checkbox-indicator"></span>
                                            <span>{{ __('menu.design.show_dietary_info') }}</span>
                                        </label>
                                    </div>
                                    <div class="display-option">
                                        <label class="option-label">
                                            <input type="checkbox" id="show-spice-level">
                                            <span class="checkbox-indicator"></span>
                                            <span>{{ __('menu.design.show_spice_level') }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Price Format -->
                            <div class="form-group">
                                <label class="form-label">{{ __('menu.design.price_format') }}</label>
                                <div class="price-format-options">
                                    <div class="format-option">
                                        <label class="format-label">
                                            <input type="radio" name="price_format" value="currency_symbol" checked>
                                            <span class="radio-indicator"></span>
                                            <span>£12.99</span>
                                        </label>
                                    </div>
                                    <div class="format-option">
                                        <label class="format-label">
                                            <input type="radio" name="price_format" value="currency_code">
                                            <span class="radio-indicator"></span>
                                            <span>GBP 12.99</span>
                                        </label>
                                    </div>
                                    <div class="format-option">
                                        <label class="format-label">
                                            <input type="radio" name="price_format" value="no_currency">
                                            <span class="radio-indicator"></span>
                                            <span>12.99</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Language Settings -->
                            <div class="form-group">
                                <label for="menu-language" class="form-label">{{ __('menu.design.menu_language') }}</label>
                                <select id="menu-language" class="form-select">
                                    <option value="en">{{ __('menu.design.english') }}</option>
                                    <option value="am">{{ __('menu.design.amharic') }}</option>
                                    <option value="ti">{{ __('menu.design.tigrinya') }}</option>
                                </select>
                            </div>

                            <!-- Footer Content -->
                            <div class="form-group">
                                <label for="footer-content" class="form-label">{{ __('menu.design.footer_content') }}</label>
                                <textarea id="footer-content" class="form-textarea" rows="3"
                                          placeholder="{{ __('menu.design.footer_placeholder') }}">Thank you for dining with us! Please inform your server of any allergies or dietary requirements.</textarea>
                            </div>

                            <!-- QR Code Settings -->
                            <div class="form-group">
                                <label class="form-label">{{ __('menu.design.qr_code_settings') }}</label>
                                <div class="qr-settings">
                                    <div class="qr-option">
                                        <label class="option-label">
                                            <input type="checkbox" id="include-qr-code">
                                            <span class="checkbox-indicator"></span>
                                            <span>{{ __('menu.design.include_qr_code') }}</span>
                                        </label>
                                    </div>
                                    <div class="qr-url-input" style="display: none;">
                                        <label for="qr-url">{{ __('menu.design.qr_url') }}</label>
                                        <input type="url" id="qr-url" class="form-input" placeholder="https://restaurant.com/menu">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel - Live Preview -->
            <div class="design-preview-panel">
                <div class="preview-header">
                    <h3 class="preview-title">{{ __('menu.design.live_preview') }}</h3>
                    <div class="preview-controls">
                        <button type="button" class="preview-zoom-btn" data-zoom="75%">75%</button>
                        <button type="button" class="preview-zoom-btn active" data-zoom="100%">100%</button>
                        <button type="button" class="preview-zoom-btn" data-zoom="125%">125%</button>
                    </div>
                </div>
                <div class="preview-container">
                    <div class="menu-preview" id="menu-preview">
                        <!-- Live preview will be rendered here -->
                        <div class="preview-menu">
                            <div class="menu-header">
                                <div class="menu-logo">
                                    <div class="logo-placeholder">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="menu-title">Geez Restaurant</div>
                                <div class="menu-address">123 Main Street, City, Country<br>Phone: +1 234 567 8900</div>
                            </div>
                            <div class="menu-content">
                                <div class="menu-section">
                                    <h2 class="section-title">Appetizers</h2>
                                    <div class="menu-items">
                                        <div class="menu-item">
                                            <div class="item-info">
                                                <h3 class="item-name">Caesar Salad</h3>
                                                <p class="item-description">Crisp romaine lettuce with parmesan, croutons, and caesar dressing</p>
                                            </div>
                                            <div class="item-price">£8.50</div>
                                        </div>
                                        <div class="menu-item">
                                            <div class="item-info">
                                                <h3 class="item-name">Garlic Bread</h3>
                                                <p class="item-description">Toasted bread with garlic butter and herbs</p>
                                            </div>
                                            <div class="item-price">£4.99</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="menu-section">
                                    <h2 class="section-title">Main Courses</h2>
                                    <div class="menu-items">
                                        <div class="menu-item">
                                            <div class="item-info">
                                                <h3 class="item-name">Margherita Pizza</h3>
                                                <p class="item-description">Classic pizza with tomato sauce, mozzarella, and fresh basil</p>
                                            </div>
                                            <div class="item-price">£12.99</div>
                                        </div>
                                        <div class="menu-item">
                                            <div class="item-info">
                                                <h3 class="item-name">Grilled Salmon</h3>
                                                <p class="item-description">Atlantic salmon grilled to perfection with lemon herb butter</p>
                                            </div>
                                            <div class="item-price">£18.99</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="menu-footer">
                                <p>Thank you for dining with us! Please inform your server of any allergies or dietary requirements.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="preview-modal" id="preview-modal" style="display: none;" role="dialog" aria-labelledby="preview-modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="preview-modal-title" class="modal-title">{{ __('menu.design.menu_preview') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('menu.design.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="full-preview-container">
                <!-- Full menu preview will be rendered here -->
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary close-preview-btn">
                {{ __('menu.design.close') }}
            </button>
            <button type="button" class="btn btn-primary download-pdf-btn">
                {{ __('menu.design.download_pdf') }}
            </button>
        </div>
    </div>
</div>
@endsection
