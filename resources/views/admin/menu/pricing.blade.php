@extends('layouts.admin')

@section('title', __('menu.pricing.title') . ' - ' . config('app.name'))
@section('page_title', __('menu.pricing.title'))

@push('styles')
    @vite('resources/css/admin/menu-pricing.css')
@endpush

@push('scripts')
    @vite('resources/js/admin/menu-pricing.js')
@endpush

@section('content')
<div class="pricing-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('menu.pricing.title') }}</h1>
                <p class="page-subtitle">{{ __('menu.pricing.subtitle') }}</p>
            </div>
            <div class="page-actions">
                <button type="button" class="btn btn-secondary export-prices-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ __('menu.pricing.export_prices') }}
                </button>
                <button type="button" class="btn btn-secondary bulk-update-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    {{ __('menu.pricing.bulk_update') }}
                </button>
                <button type="button" class="btn btn-primary price-history-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ __('menu.pricing.price_history') }}
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
                    <div class="stat-value" id="total-items">0</div>
                    <div class="stat-label">{{ __('menu.pricing.total_items') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon average">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="average-price">£0.00</div>
                    <div class="stat-label">{{ __('menu.pricing.average_price') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon changes">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="recent-changes">0</div>
                    <div class="stat-label">{{ __('menu.pricing.recent_changes') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon revenue">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="price-range">£0 - £0</div>
                    <div class="stat-label">{{ __('menu.pricing.price_range') }}</div>
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
                           placeholder="{{ __('menu.pricing.search_items') }}"
                           id="item-search">
                </div>
            </div>
            
            <div class="filter-group">
                <select class="filter-select" id="category-filter">
                    <option value="">{{ __('menu.pricing.all_categories') }}</option>
                    <option value="appetizers">{{ __('menu.pricing.appetizers') }}</option>
                    <option value="main_courses">{{ __('menu.pricing.main_courses') }}</option>
                    <option value="desserts">{{ __('menu.pricing.desserts') }}</option>
                    <option value="beverages">{{ __('menu.pricing.beverages') }}</option>
                </select>
            </div>
            
            <div class="filter-group">
                <select class="filter-select" id="price-range-filter">
                    <option value="">{{ __('menu.pricing.all_prices') }}</option>
                    <option value="0-10">£0 - £10</option>
                    <option value="10-20">£10 - £20</option>
                    <option value="20-30">£20 - £30</option>
                    <option value="30+">£30+</option>
                </select>
            </div>
            
            <div class="filter-group">
                <select class="filter-select" id="sort-filter">
                    <option value="name">{{ __('menu.pricing.sort_by_name') }}</option>
                    <option value="price_asc">{{ __('menu.pricing.sort_by_price_low') }}</option>
                    <option value="price_desc">{{ __('menu.pricing.sort_by_price_high') }}</option>
                    <option value="updated">{{ __('menu.pricing.sort_by_updated') }}</option>
                </select>
            </div>
            
            <button type="button" class="btn btn-secondary clear-filters-btn">
                {{ __('menu.pricing.clear_filters') }}
            </button>
        </div>
        
        <div class="view-toggle">
            <button type="button" class="view-btn active" data-view="table">
                <svg class="view-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
            </button>
            <button type="button" class="view-btn" data-view="cards">
                <svg class="view-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Pricing Content -->
    <div class="pricing-content">
        <!-- Table View -->
        <div class="pricing-table-view" id="pricing-table-view">
            <div class="pricing-table-wrapper">
                <table class="pricing-table" role="table">
                    <thead>
                        <tr>
                            <th scope="col">
                                <label class="checkbox-wrapper">
                                    <input type="checkbox" id="select-all" class="checkbox-input">
                                    <span class="checkbox-indicator"></span>
                                </label>
                            </th>
                            <th scope="col">{{ __('menu.pricing.item') }}</th>
                            <th scope="col">{{ __('menu.pricing.category') }}</th>
                            <th scope="col">{{ __('menu.pricing.current_price') }}</th>
                            <th scope="col">{{ __('menu.pricing.cost') }}</th>
                            <th scope="col">{{ __('menu.pricing.margin') }}</th>
                            <th scope="col">{{ __('menu.pricing.last_updated') }}</th>
                            <th scope="col">{{ __('menu.pricing.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="pricing-table-body" id="pricing-table-body">
                        <!-- Loading skeleton rows -->
                        <tr class="loading-row">
                            <td><div class="skeleton-checkbox"></div></td>
                            <td><div class="skeleton-item-info"></div></td>
                            <td><div class="skeleton-text"></div></td>
                            <td><div class="skeleton-price"></div></td>
                            <td><div class="skeleton-text"></div></td>
                            <td><div class="skeleton-text"></div></td>
                            <td><div class="skeleton-text"></div></td>
                            <td><div class="skeleton-actions"></div></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Cards View -->
        <div class="pricing-cards-view" id="pricing-cards-view" style="display: none;">
            <div class="pricing-cards-grid">
                <!-- Loading skeleton cards -->
                <div class="pricing-card loading">
                    <div class="card-header">
                        <div class="skeleton-title"></div>
                        <div class="skeleton-price"></div>
                    </div>
                    <div class="skeleton-category"></div>
                    <div class="skeleton-stats"></div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div class="empty-state" style="display: none;">
            <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
            </svg>
            <h3>{{ __('menu.pricing.no_items_found') }}</h3>
            <p>{{ __('menu.pricing.no_items_description') }}</p>
        </div>
    </div>
</div>

<!-- Edit Price Modal -->
<div class="edit-price-modal" id="edit-price-modal" style="display: none;" role="dialog" aria-labelledby="edit-price-modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="edit-price-modal-title" class="modal-title">{{ __('menu.pricing.edit_price') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('menu.pricing.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form class="edit-price-form" id="edit-price-form">
                <div class="item-info-section">
                    <div class="item-details" id="edit-item-details">
                        <!-- Item details will be populated here -->
                    </div>
                </div>

                <div class="price-edit-section">
                    <div class="form-grid grid grid-cols-1 md:grid-cols-2">
                        <!-- Current Price -->
                        <div class="form-group">
                            <label class="form-label">{{ __('menu.pricing.current_price') }}</label>
                            <div class="current-price-display" id="current-price-display">£0.00</div>
                        </div>

                        <!-- New Price -->
                        <div class="form-group">
                            <label for="new-price" class="form-label required">
                                {{ __('menu.pricing.new_price') }}
                            </label>
                            <div class="price-input-wrapper">
                                <span class="price-currency">£</span>
                                <input type="number" id="new-price" name="new_price" class="form-input price-input" 
                                       min="0" step="0.01" required placeholder="0.00">
                            </div>
                        </div>

                        <!-- Price Change -->
                        <div class="form-group md:col-span-2">
                            <label class="form-label">{{ __('menu.pricing.price_change') }}</label>
                            <div class="price-change-display" id="price-change-display">
                                <span class="change-amount">£0.00</span>
                                <span class="change-percentage">(0%)</span>
                            </div>
                        </div>

                        <!-- Reason for Change -->
                        <div class="form-group md:col-span-2">
                            <label for="change-reason" class="form-label required">
                                {{ __('menu.pricing.reason_for_change') }}
                            </label>
                            <select id="change-reason" name="reason" class="form-select" required>
                                <option value="">{{ __('menu.pricing.select_reason') }}</option>
                                <option value="cost_increase">{{ __('menu.pricing.cost_increase') }}</option>
                                <option value="cost_decrease">{{ __('menu.pricing.cost_decrease') }}</option>
                                <option value="market_adjustment">{{ __('menu.pricing.market_adjustment') }}</option>
                                <option value="seasonal_pricing">{{ __('menu.pricing.seasonal_pricing') }}</option>
                                <option value="promotion">{{ __('menu.pricing.promotion') }}</option>
                                <option value="competitor_pricing">{{ __('menu.pricing.competitor_pricing') }}</option>
                                <option value="other">{{ __('menu.pricing.other') }}</option>
                            </select>
                        </div>

                        <!-- Notes -->
                        <div class="form-group md:col-span-2">
                            <label for="change-notes" class="form-label">
                                {{ __('menu.pricing.notes') }}
                            </label>
                            <textarea id="change-notes" name="notes" class="form-textarea" rows="3"
                                      placeholder="{{ __('menu.pricing.notes_placeholder') }}"></textarea>
                        </div>

                        <!-- Effective Date -->
                        <div class="form-group">
                            <label for="effective-date" class="form-label">
                                {{ __('menu.pricing.effective_date') }}
                            </label>
                            <input type="datetime-local" id="effective-date" name="effective_date" class="form-input">
                        </div>

                        <!-- Apply to Similar Items -->
                        <div class="form-group">
                            <label class="form-label">{{ __('menu.pricing.apply_to_similar') }}</label>
                            <div class="toggle-group">
                                <label class="toggle-switch">
                                    <input type="checkbox" name="apply_to_similar" value="1">
                                    <span class="toggle-slider"></span>
                                    <span class="toggle-label">{{ __('menu.pricing.apply_same_category') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Price Analysis -->
                <div class="price-analysis-section">
                    <h3>{{ __('menu.pricing.price_analysis') }}</h3>
                    <div class="analysis-grid">
                        <div class="analysis-item">
                            <div class="analysis-label">{{ __('menu.pricing.new_margin') }}</div>
                            <div class="analysis-value" id="new-margin">0%</div>
                        </div>
                        <div class="analysis-item">
                            <div class="analysis-label">{{ __('menu.pricing.profit_change') }}</div>
                            <div class="analysis-value" id="profit-change">£0.00</div>
                        </div>
                        <div class="analysis-item">
                            <div class="analysis-label">{{ __('menu.pricing.cost_percentage') }}</div>
                            <div class="analysis-value" id="cost-percentage">0%</div>
                        </div>
                        <div class="analysis-item">
                            <div class="analysis-label">{{ __('menu.pricing.price_position') }}</div>
                            <div class="analysis-value" id="price-position">{{ __('menu.pricing.average') }}</div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary cancel-price-btn">
                {{ __('menu.pricing.cancel') }}
            </button>
            <button type="submit" form="edit-price-form" class="btn btn-primary save-price-btn">
                {{ __('menu.pricing.update_price') }}
            </button>
        </div>
    </div>
</div>

<!-- Bulk Update Modal -->
<div class="bulk-update-modal" id="bulk-update-modal" style="display: none;" role="dialog" aria-labelledby="bulk-update-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="bulk-update-title" class="modal-title">{{ __('menu.pricing.bulk_price_update') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('menu.pricing.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form class="bulk-update-form" id="bulk-update-form">
                <div class="bulk-options">
                    <div class="bulk-option">
                        <label class="bulk-label">
                            <input type="radio" name="bulk_type" value="percentage" checked>
                            <span class="radio-indicator"></span>
                            <span class="radio-text">{{ __('menu.pricing.percentage_change') }}</span>
                        </label>
                        <div class="bulk-input-group">
                            <input type="number" id="bulk-percentage" name="percentage" 
                                   class="form-input bulk-input" min="-50" max="100" step="0.1" value="0">
                            <span class="input-suffix">%</span>
                        </div>
                    </div>
                    <div class="bulk-option">
                        <label class="bulk-label">
                            <input type="radio" name="bulk_type" value="fixed">
                            <span class="radio-indicator"></span>
                            <span class="radio-text">{{ __('menu.pricing.fixed_amount') }}</span>
                        </label>
                        <div class="bulk-input-group">
                            <span class="input-prefix">£</span>
                            <input type="number" id="bulk-fixed" name="fixed_amount" 
                                   class="form-input bulk-input" step="0.01" value="0.00" disabled>
                        </div>
                    </div>
                </div>

                <div class="bulk-filters">
                    <h4>{{ __('menu.pricing.apply_to') }}</h4>
                    <div class="filter-options">
                        <label class="filter-option">
                            <input type="checkbox" name="categories[]" value="appetizers">
                            <span class="checkbox-indicator"></span>
                            <span>{{ __('menu.pricing.appetizers') }}</span>
                        </label>
                        <label class="filter-option">
                            <input type="checkbox" name="categories[]" value="main_courses">
                            <span class="checkbox-indicator"></span>
                            <span>{{ __('menu.pricing.main_courses') }}</span>
                        </label>
                        <label class="filter-option">
                            <input type="checkbox" name="categories[]" value="desserts">
                            <span class="checkbox-indicator"></span>
                            <span>{{ __('menu.pricing.desserts') }}</span>
                        </label>
                        <label class="filter-option">
                            <input type="checkbox" name="categories[]" value="beverages">
                            <span class="checkbox-indicator"></span>
                            <span>{{ __('menu.pricing.beverages') }}</span>
                        </label>
                    </div>
                </div>

                <div class="bulk-preview" id="bulk-preview">
                    <h4>{{ __('menu.pricing.preview_changes') }}</h4>
                    <div class="preview-summary">
                        <span id="affected-items">0</span> {{ __('menu.pricing.items_affected') }}
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary cancel-bulk-btn">
                {{ __('menu.pricing.cancel') }}
            </button>
            <button type="submit" form="bulk-update-form" class="btn btn-primary apply-bulk-btn">
                {{ __('menu.pricing.apply_changes') }}
            </button>
        </div>
    </div>
</div>

<!-- Price History Modal -->
<div class="price-history-modal" id="price-history-modal" style="display: none;" role="dialog" aria-labelledby="price-history-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="price-history-title" class="modal-title">{{ __('menu.pricing.price_history') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('menu.pricing.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="history-filters">
                <select class="filter-select" id="history-item-filter">
                    <option value="">{{ __('menu.pricing.all_items') }}</option>
                </select>
                <select class="filter-select" id="history-period-filter">
                    <option value="7">{{ __('menu.pricing.last_7_days') }}</option>
                    <option value="30" selected>{{ __('menu.pricing.last_30_days') }}</option>
                    <option value="90">{{ __('menu.pricing.last_90_days') }}</option>
                    <option value="365">{{ __('menu.pricing.last_year') }}</option>
                </select>
            </div>
            <div class="price-history-content" id="price-history-content">
                <!-- Price history will be populated here -->
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary close-history-btn">
                {{ __('menu.pricing.close') }}
            </button>
        </div>
    </div>
</div>
@endsection
