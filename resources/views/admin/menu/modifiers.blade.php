@extends('layouts.admin')

@section('title', __('menu.modifiers.title') . ' - ' . config('app.name'))
@section('page_title', __('menu.modifiers.title'))

@push('styles')
    @vite('resources/css/admin/menu-modifiers.css')
@endpush

@push('scripts')
    @vite('resources/js/admin/menu-modifiers.js')
@endpush

@section('content')
<div class="modifiers-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('menu.modifiers.title') }}</h1>
                <p class="page-subtitle">{{ __('menu.modifiers.subtitle') }}</p>
            </div>
            <div class="page-actions">
                <button type="button" class="btn btn-secondary import-modifiers-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                    </svg>
                    {{ __('menu.modifiers.import_modifiers') }}
                </button>
                <button type="button" class="btn btn-secondary export-modifiers-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ __('menu.modifiers.export_modifiers') }}
                </button>
                <button type="button" class="btn btn-primary add-modifier-group-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('menu.modifiers.add_modifier_group') }}
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="total-groups">0</div>
                    <div class="stat-label">{{ __('menu.modifiers.total_groups') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon modifiers">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="total-modifiers">0</div>
                    <div class="stat-label">{{ __('menu.modifiers.total_modifiers') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon active">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="active-groups">0</div>
                    <div class="stat-label">{{ __('menu.modifiers.active_groups') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon revenue">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="avg-price">$0.00</div>
                    <div class="stat-label">{{ __('menu.modifiers.avg_modifier_price') }}</div>
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
                           placeholder="{{ __('menu.modifiers.search_modifiers') }}"
                           id="modifier-search">
                </div>
            </div>
            
            <div class="filter-group">
                <select class="filter-select" id="type-filter">
                    <option value="">{{ __('menu.modifiers.all_types') }}</option>
                    <option value="single">{{ __('menu.modifiers.single_select') }}</option>
                    <option value="multiple">{{ __('menu.modifiers.multiple_select') }}</option>
                </select>
            </div>
            
            <div class="filter-group">
                <select class="filter-select" id="status-filter">
                    <option value="">{{ __('menu.modifiers.all_status') }}</option>
                    <option value="active">{{ __('menu.modifiers.active') }}</option>
                    <option value="inactive">{{ __('menu.modifiers.inactive') }}</option>
                </select>
            </div>
            
            <div class="filter-group">
                <select class="filter-select" id="sort-filter">
                    <option value="name">{{ __('menu.modifiers.sort_by_name') }}</option>
                    <option value="type">{{ __('menu.modifiers.sort_by_type') }}</option>
                    <option value="modifiers">{{ __('menu.modifiers.sort_by_modifiers') }}</option>
                    <option value="created">{{ __('menu.modifiers.sort_by_created') }}</option>
                </select>
            </div>
            
            <button type="button" class="btn btn-secondary clear-filters-btn">
                {{ __('menu.modifiers.clear_filters') }}
            </button>
        </div>
    </div>

    <!-- Modifiers Content -->
    <div class="modifiers-content">
        <!-- Modifier Groups Grid -->
        <div class="modifier-groups-grid" id="modifier-groups-grid">
            <!-- Loading skeleton -->
            <div class="modifier-group-card loading">
                <div class="group-header">
                    <div class="skeleton-title"></div>
                    <div class="skeleton-badge"></div>
                </div>
                <div class="skeleton-description"></div>
                <div class="skeleton-stats"></div>
            </div>
            <div class="modifier-group-card loading">
                <div class="group-header">
                    <div class="skeleton-title"></div>
                    <div class="skeleton-badge"></div>
                </div>
                <div class="skeleton-description"></div>
                <div class="skeleton-stats"></div>
            </div>
            <div class="modifier-group-card loading">
                <div class="group-header">
                    <div class="skeleton-title"></div>
                    <div class="skeleton-badge"></div>
                </div>
                <div class="skeleton-description"></div>
                <div class="skeleton-stats"></div>
            </div>
        </div>

        <!-- Empty State -->
        <div class="empty-state" style="display: none;">
            <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
            </svg>
            <h3>{{ __('menu.modifiers.no_modifiers_found') }}</h3>
            <p>{{ __('menu.modifiers.no_modifiers_description') }}</p>
            <button type="button" class="btn btn-primary add-modifier-group-btn">
                {{ __('menu.modifiers.add_first_group') }}
            </button>
        </div>
    </div>
</div>

<!-- Add/Edit Modifier Group Modal -->
<div class="modifier-group-modal" id="modifier-group-modal" style="display: none;" role="dialog" aria-labelledby="modifier-group-modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modifier-group-modal-title" class="modal-title">{{ __('menu.modifiers.add_modifier_group') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('menu.modifiers.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form class="modifier-group-form" id="modifier-group-form">
                <div class="form-tabs">
                    <div class="tab-nav">
                        <button type="button" class="tab-btn active" data-tab="basic-info">
                            {{ __('menu.modifiers.basic_info') }}
                        </button>
                        <button type="button" class="tab-btn" data-tab="modifiers">
                            {{ __('menu.modifiers.modifiers') }}
                        </button>
                        <button type="button" class="tab-btn" data-tab="settings">
                            {{ __('menu.modifiers.settings') }}
                        </button>
                    </div>

                    <!-- Basic Info Tab -->
                    <div class="tab-content active" data-tab="basic-info">
                        <div class="form-grid grid grid-cols-1 md:grid-cols-2">
                            <!-- Group Name -->
                            <div class="form-group md:col-span-2">
                                <label for="group-name" class="form-label required">
                                    {{ __('menu.modifiers.group_name') }}
                                </label>
                                <input type="text" id="group-name" name="name" class="form-input" required
                                       placeholder="{{ __('menu.modifiers.group_name_placeholder') }}">
                            </div>

                            <!-- Selection Type -->
                            <div class="form-group">
                                <label for="selection-type" class="form-label required">
                                    {{ __('menu.modifiers.selection_type') }}
                                </label>
                                <select id="selection-type" name="selection_type" class="form-select" required>
                                    <option value="">{{ __('menu.modifiers.select_type') }}</option>
                                    <option value="single">{{ __('menu.modifiers.single_select') }}</option>
                                    <option value="multiple">{{ __('menu.modifiers.multiple_select') }}</option>
                                </select>
                                <small class="form-hint">{{ __('menu.modifiers.selection_type_hint') }}</small>
                            </div>

                            <!-- Display Order -->
                            <div class="form-group">
                                <label for="group-display-order" class="form-label">
                                    {{ __('menu.modifiers.display_order') }}
                                </label>
                                <input type="number" id="group-display-order" name="display_order" class="form-input" 
                                       min="0" step="1" placeholder="0">
                                <small class="form-hint">{{ __('menu.modifiers.display_order_hint') }}</small>
                            </div>

                            <!-- Description -->
                            <div class="form-group md:col-span-2">
                                <label for="group-description" class="form-label">
                                    {{ __('menu.modifiers.description') }}
                                </label>
                                <textarea id="group-description" name="description" class="form-textarea" rows="3"
                                          placeholder="{{ __('menu.modifiers.description_placeholder') }}"></textarea>
                            </div>

                            <!-- Status -->
                            <div class="form-group md:col-span-2">
                                <label class="form-label">{{ __('menu.modifiers.status') }}</label>
                                <div class="toggle-group">
                                    <label class="toggle-switch">
                                        <input type="checkbox" name="active" value="1" checked>
                                        <span class="toggle-slider"></span>
                                        <span class="toggle-label">{{ __('menu.modifiers.active') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modifiers Tab -->
                    <div class="tab-content" data-tab="modifiers">
                        <div class="modifiers-section">
                            <div class="modifiers-header">
                                <h3>{{ __('menu.modifiers.modifier_options') }}</h3>
                                <button type="button" class="btn btn-secondary add-modifier-btn">
                                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    {{ __('menu.modifiers.add_modifier') }}
                                </button>
                            </div>
                            
                            <div class="modifiers-list" id="modifiers-list">
                                <!-- Modifiers will be added here dynamically -->
                            </div>
                            
                            <div class="modifiers-empty" id="modifiers-empty">
                                <p>{{ __('menu.modifiers.no_modifiers_added') }}</p>
                                <button type="button" class="btn btn-primary add-modifier-btn">
                                    {{ __('menu.modifiers.add_first_modifier') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Settings Tab -->
                    <div class="tab-content" data-tab="settings">
                        <div class="form-grid grid grid-cols-1 md:grid-cols-2">
                            <!-- Required Selection -->
                            <div class="form-group">
                                <label class="form-label">{{ __('menu.modifiers.required_selection') }}</label>
                                <div class="toggle-group">
                                    <label class="toggle-switch">
                                        <input type="checkbox" name="required" value="1">
                                        <span class="toggle-slider"></span>
                                        <span class="toggle-label">{{ __('menu.modifiers.required') }}</span>
                                    </label>
                                </div>
                                <small class="form-hint">{{ __('menu.modifiers.required_hint') }}</small>
                            </div>

                            <!-- Minimum Selections -->
                            <div class="form-group">
                                <label for="min-selections" class="form-label">
                                    {{ __('menu.modifiers.min_selections') }}
                                </label>
                                <input type="number" id="min-selections" name="min_selections" class="form-input" 
                                       min="0" step="1" value="0">
                                <small class="form-hint">{{ __('menu.modifiers.min_selections_hint') }}</small>
                            </div>

                            <!-- Maximum Selections -->
                            <div class="form-group">
                                <label for="max-selections" class="form-label">
                                    {{ __('menu.modifiers.max_selections') }}
                                </label>
                                <input type="number" id="max-selections" name="max_selections" class="form-input" 
                                       min="1" step="1" value="1">
                                <small class="form-hint">{{ __('menu.modifiers.max_selections_hint') }}</small>
                            </div>

                            <!-- Default Selection -->
                            <div class="form-group">
                                <label for="default-selection" class="form-label">
                                    {{ __('menu.modifiers.default_selection') }}
                                </label>
                                <select id="default-selection" name="default_selection" class="form-select">
                                    <option value="">{{ __('menu.modifiers.no_default') }}</option>
                                </select>
                                <small class="form-hint">{{ __('menu.modifiers.default_selection_hint') }}</small>
                            </div>

                            <!-- Price Display -->
                            <div class="form-group md:col-span-2">
                                <label class="form-label">{{ __('menu.modifiers.price_display') }}</label>
                                <div class="radio-group">
                                    <label class="radio-option">
                                        <input type="radio" name="price_display" value="show" checked>
                                        <span class="radio-indicator"></span>
                                        <span class="radio-label">{{ __('menu.modifiers.show_prices') }}</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="price_display" value="hide">
                                        <span class="radio-indicator"></span>
                                        <span class="radio-label">{{ __('menu.modifiers.hide_prices') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary cancel-btn">
                {{ __('menu.modifiers.cancel') }}
            </button>
            <button type="submit" form="modifier-group-form" class="btn btn-primary save-btn">
                {{ __('menu.modifiers.save_group') }}
            </button>
        </div>
    </div>
</div>

<!-- Modifier Item Modal -->
<div class="modifier-item-modal" id="modifier-item-modal" style="display: none;" role="dialog" aria-labelledby="modifier-item-modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modifier-item-modal-title" class="modal-title">{{ __('menu.modifiers.add_modifier') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('menu.modifiers.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form class="modifier-item-form" id="modifier-item-form">
                <div class="form-grid grid grid-cols-1 md:grid-cols-2">
                    <!-- Modifier Name -->
                    <div class="form-group md:col-span-2">
                        <label for="modifier-name" class="form-label required">
                            {{ __('menu.modifiers.modifier_name') }}
                        </label>
                        <input type="text" id="modifier-name" name="name" class="form-input" required
                               placeholder="{{ __('menu.modifiers.modifier_name_placeholder') }}">
                    </div>

                    <!-- Price -->
                    <div class="form-group">
                        <label for="modifier-price" class="form-label">
                            {{ __('menu.modifiers.price') }}
                        </label>
                        <div class="price-input-wrapper">
                            <span class="price-currency">$</span>
                            <input type="number" id="modifier-price" name="price" class="form-input price-input" 
                                   min="0" step="0.01" value="0.00">
                        </div>
                        <small class="form-hint">{{ __('menu.modifiers.price_hint') }}</small>
                    </div>

                    <!-- Display Order -->
                    <div class="form-group">
                        <label for="modifier-display-order" class="form-label">
                            {{ __('menu.modifiers.display_order') }}
                        </label>
                        <input type="number" id="modifier-display-order" name="display_order" class="form-input" 
                               min="0" step="1" placeholder="0">
                    </div>

                    <!-- Description -->
                    <div class="form-group md:col-span-2">
                        <label for="modifier-description" class="form-label">
                            {{ __('menu.modifiers.description') }}
                        </label>
                        <textarea id="modifier-description" name="description" class="form-textarea" rows="2"
                                  placeholder="{{ __('menu.modifiers.modifier_description_placeholder') }}"></textarea>
                    </div>

                    <!-- Status -->
                    <div class="form-group md:col-span-2">
                        <label class="form-label">{{ __('menu.modifiers.status') }}</label>
                        <div class="toggle-group">
                            <label class="toggle-switch">
                                <input type="checkbox" name="active" value="1" checked>
                                <span class="toggle-slider"></span>
                                <span class="toggle-label">{{ __('menu.modifiers.active') }}</span>
                            </label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary cancel-modifier-btn">
                {{ __('menu.modifiers.cancel') }}
            </button>
            <button type="submit" form="modifier-item-form" class="btn btn-primary save-modifier-btn">
                {{ __('menu.modifiers.save_modifier') }}
            </button>
        </div>
    </div>
</div>

<!-- Group Details Modal -->
<div class="group-details-modal" id="group-details-modal" style="display: none;" role="dialog" aria-labelledby="group-details-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="group-details-title" class="modal-title">{{ __('menu.modifiers.group_details') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('menu.modifiers.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="group-details-content" id="group-details-content">
                <!-- Group details will be populated here -->
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary close-details-btn">
                {{ __('menu.modifiers.close') }}
            </button>
            <button type="button" class="btn btn-primary edit-group-btn">
                {{ __('menu.modifiers.edit_group') }}
            </button>
        </div>
    </div>
</div>
@endsection
