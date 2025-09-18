@extends('layouts.admin')

@section('title', __('bar.pricing.title') . ' - ' . config('app.name'))
@section('page_title', __('bar.pricing.title'))

@push('styles')
    {{-- CSS styles will be loaded via main layout --}}
@endpush

@push('scripts')
    @vite('resources/js/admin/bar/pricing.js')
@endpush

@section('content')
<div class="pricing-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('bar.pricing.title') }}</h1>
                <p class="page-subtitle">{{ __('bar.pricing.subtitle') }}</p>
            </div>
            <div class="page-actions">
                <button type="button" class="btn btn-secondary export-pricing-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ __('bar.pricing.export_prices') }}
                </button>
                <button type="button" class="btn btn-secondary happy-hour-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ __('bar.pricing.happy_hour_setup') }}
                </button>
                <button type="button" class="btn btn-primary bulk-update-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    {{ __('bar.pricing.bulk_update') }}
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="total-drinks">0</div>
                    <div class="stat-label">{{ __('bar.pricing.total_drinks') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon average">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="avg-price">$0.00</div>
                    <div class="stat-label">{{ __('bar.pricing.avg_price') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon margin">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="avg-margin">0%</div>
                    <div class="stat-label">{{ __('bar.pricing.avg_margin') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon happy-hour">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="happy-hour-status">{{ __('bar.pricing.inactive') }}</div>
                    <div class="stat-label">{{ __('bar.pricing.happy_hour') }}</div>
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
                           placeholder="{{ __('bar.pricing.search_drinks') }}"
                           id="drink-search">
                </div>
            </div>
            
            <div class="filter-group">
                <select class="filter-select" id="category-filter">
                    <option value="">{{ __('bar.pricing.all_categories') }}</option>
                    <option value="spirits">{{ __('bar.inventory.spirits') }}</option>
                    <option value="beer">{{ __('bar.inventory.beer') }}</option>
                    <option value="wine">{{ __('bar.inventory.wine') }}</option>
                    <option value="cocktails">{{ __('bar.cocktails') }}</option>
                    <option value="mocktails">{{ __('bar.recipes.mocktail') }}</option>
                </select>
            </div>
            
            <div class="filter-group">
                <select class="filter-select" id="pricing-filter">
                    <option value="">{{ __('bar.pricing.all_prices') }}</option>
                    <option value="under-10">{{ __('bar.pricing.under_10') }}</option>
                    <option value="10-20">$10 - $20</option>
                    <option value="20-50">$20 - $50</option>
                    <option value="over-50">{{ __('bar.pricing.over_50') }}</option>
                </select>
            </div>
            
            <div class="filter-group">
                <select class="filter-select" id="sort-filter">
                    <option value="name">{{ __('bar.pricing.sort_by_name') }}</option>
                    <option value="price">{{ __('bar.pricing.sort_by_price') }}</option>
                    <option value="margin">{{ __('bar.pricing.sort_by_margin') }}</option>
                    <option value="category">{{ __('bar.pricing.sort_by_category') }}</option>
                </select>
            </div>
            
            <button type="button" class="btn btn-secondary clear-filters-btn">
                {{ __('bar.pricing.clear_filters') }}
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

    <!-- Pricing Content -->
    <div class="pricing-content">
        <!-- Grid View -->
        <div class="pricing-grid" id="pricing-grid">
            <!-- Loading skeleton -->
            <div class="pricing-card loading">
                <div class="pricing-header">
                    <div class="skeleton-title"></div>
                    <div class="skeleton-category"></div>
                </div>
                <div class="pricing-body">
                    <div class="skeleton-price"></div>
                    <div class="skeleton-margin"></div>
                </div>
            </div>
            <div class="pricing-card loading">
                <div class="pricing-header">
                    <div class="skeleton-title"></div>
                    <div class="skeleton-category"></div>
                </div>
                <div class="pricing-body">
                    <div class="skeleton-price"></div>
                    <div class="skeleton-margin"></div>
                </div>
            </div>
            <div class="pricing-card loading">
                <div class="pricing-header">
                    <div class="skeleton-title"></div>
                    <div class="skeleton-category"></div>
                </div>
                <div class="pricing-body">
                    <div class="skeleton-price"></div>
                    <div class="skeleton-margin"></div>
                </div>
            </div>
        </div>

        <!-- List View -->
        <div class="pricing-list" id="pricing-list" style="display: none;">
            <div class="pricing-table-wrapper">
                <table class="pricing-table" role="table">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('bar.pricing.drink_name') }}</th>
                            <th scope="col">{{ __('bar.pricing.category') }}</th>
                            <th scope="col">{{ __('bar.pricing.base_price') }}</th>
                            <th scope="col">{{ __('bar.pricing.happy_hour_price') }}</th>
                            <th scope="col">{{ __('bar.pricing.cost_price') }}</th>
                            <th scope="col">{{ __('bar.pricing.profit_margin') }}</th>
                            <th scope="col">{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="pricing-table-body">
                        <!-- Loading skeleton rows -->
                        <tr class="loading-row">
                            <td><div class="skeleton-title"></div></td>
                            <td><div class="skeleton-category"></div></td>
                            <td><div class="skeleton-price"></div></td>
                            <td><div class="skeleton-price"></div></td>
                            <td><div class="skeleton-price"></div></td>
                            <td><div class="skeleton-margin"></div></td>
                            <td><div class="skeleton-actions"></div></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Empty State -->
        <div class="empty-state" style="display: none;">
            <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
            </svg>
            <h3>{{ __('bar.pricing.no_prices_found') }}</h3>
            <p>{{ __('bar.pricing.no_prices_description') }}</p>
            <button type="button" class="btn btn-primary bulk-update-btn">
                {{ __('bar.pricing.setup_pricing') }}
            </button>
        </div>
    </div>
</div>

<!-- Bulk Price Update Modal -->
<div class="pricing-modal" id="pricing-modal" style="display: none;" role="dialog" aria-labelledby="pricing-modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="pricing-modal-title" class="modal-title">{{ __('bar.pricing.bulk_update') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('common.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form class="pricing-form" id="pricing-form">
                <div class="form-sections">
                    <!-- Price Update Method -->
                    <div class="form-section">
                        <h3 class="section-title">{{ __('bar.pricing.update_method') }}</h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">{{ __('bar.pricing.update_type') }}</label>
                                <div class="radio-group">
                                    <label class="radio-item">
                                        <input type="radio" name="update_type" value="percentage" checked>
                                        <span class="radio-mark"></span>
                                        {{ __('bar.pricing.percentage_increase') }}
                                    </label>
                                    <label class="radio-item">
                                        <input type="radio" name="update_type" value="fixed">
                                        <span class="radio-mark"></span>
                                        {{ __('bar.pricing.fixed_amount') }}
                                    </label>
                                    <label class="radio-item">
                                        <input type="radio" name="update_type" value="margin">
                                        <span class="radio-mark"></span>
                                        {{ __('bar.pricing.target_margin') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Update Values -->
                    <div class="form-section">
                        <h3 class="section-title">{{ __('bar.pricing.update_values') }}</h3>
                        <div class="form-grid">
                            <div class="form-group" id="percentage-input" style="display: block;">
                                <label for="percentage-value" class="form-label">
                                    {{ __('bar.pricing.percentage_value') }}
                                </label>
                                <div class="input-with-suffix">
                                    <input type="number" id="percentage-value" name="percentage_value" class="form-input" 
                                           min="-50" max="100" step="0.1" placeholder="10">
                                    <span class="input-suffix">%</span>
                                </div>
                                <small class="form-hint">{{ __('bar.pricing.percentage_hint') }}</small>
                            </div>

                            <div class="form-group" id="fixed-input" style="display: none;">
                                <label for="fixed-value" class="form-label">
                                    {{ __('bar.pricing.fixed_value') }}
                                </label>
                                <div class="input-with-prefix">
                                    <span class="input-prefix">$</span>
                                    <input type="number" id="fixed-value" name="fixed_value" class="form-input" 
                                           min="-50" max="50" step="0.01" placeholder="2.00">
                                </div>
                                <small class="form-hint">{{ __('bar.pricing.fixed_hint') }}</small>
                            </div>

                            <div class="form-group" id="margin-input" style="display: none;">
                                <label for="margin-value" class="form-label">
                                    {{ __('bar.pricing.margin_value') }}
                                </label>
                                <div class="input-with-suffix">
                                    <input type="number" id="margin-value" name="margin_value" class="form-input" 
                                           min="10" max="80" step="1" placeholder="60">
                                    <span class="input-suffix">%</span>
                                </div>
                                <small class="form-hint">{{ __('bar.pricing.margin_hint') }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Category Selection -->
                    <div class="form-section">
                        <h3 class="section-title">{{ __('bar.pricing.apply_to_categories') }}</h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <div class="checkbox-group">
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="categories[]" value="all" checked>
                                        <span class="checkmark"></span>
                                        {{ __('bar.pricing.all_categories') }}
                                    </label>
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="categories[]" value="spirits">
                                        <span class="checkmark"></span>
                                        {{ __('bar.inventory.spirits') }}
                                    </label>
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="categories[]" value="beer">
                                        <span class="checkmark"></span>
                                        {{ __('bar.inventory.beer') }}
                                    </label>
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="categories[]" value="wine">
                                        <span class="checkmark"></span>
                                        {{ __('bar.inventory.wine') }}
                                    </label>
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="categories[]" value="cocktails">
                                        <span class="checkmark"></span>
                                        {{ __('bar.cocktails') }}
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
                {{ __('common.cancel') }}
            </button>
            <button type="submit" form="pricing-form" class="btn btn-primary save-btn">
                {{ __('bar.pricing.apply_changes') }}
            </button>
        </div>
    </div>
</div>

<!-- Happy Hour Setup Modal -->
<div class="happy-hour-modal" id="happy-hour-modal" style="display: none;" role="dialog" aria-labelledby="happy-hour-modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="happy-hour-modal-title" class="modal-title">{{ __('bar.pricing.happy_hour_setup') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('common.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form class="happy-hour-form" id="happy-hour-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">{{ __('bar.pricing.happy_hour_active') }}</label>
                        <div class="toggle-group">
                            <label class="toggle-switch">
                                <input type="checkbox" name="happy_hour_enabled" value="1">
                                <span class="toggle-slider"></span>
                                <span class="toggle-label">{{ __('bar.pricing.enable_happy_hour') }}</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="start-time" class="form-label">{{ __('bar.pricing.start_time') }}</label>
                        <input type="time" id="start-time" name="start_time" class="form-input" value="17:00">
                    </div>

                    <div class="form-group">
                        <label for="end-time" class="form-label">{{ __('bar.pricing.end_time') }}</label>
                        <input type="time" id="end-time" name="end_time" class="form-input" value="19:00">
                    </div>

                    <div class="form-group">
                        <label for="discount-percentage" class="form-label">{{ __('bar.pricing.discount_percentage') }}</label>
                        <div class="input-with-suffix">
                            <input type="number" id="discount-percentage" name="discount_percentage" class="form-input" 
                                   min="5" max="50" step="1" value="20">
                            <span class="input-suffix">%</span>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">{{ __('bar.pricing.applicable_days') }}</label>
                        <div class="checkbox-group days-group">
                            <label class="checkbox-item">
                                <input type="checkbox" name="applicable_days[]" value="monday">
                                <span class="checkmark"></span>
                                {{ __('common.monday') }}
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" name="applicable_days[]" value="tuesday">
                                <span class="checkmark"></span>
                                {{ __('common.tuesday') }}
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" name="applicable_days[]" value="wednesday">
                                <span class="checkmark"></span>
                                {{ __('common.wednesday') }}
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" name="applicable_days[]" value="thursday">
                                <span class="checkmark"></span>
                                {{ __('common.thursday') }}
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" name="applicable_days[]" value="friday" checked>
                                <span class="checkmark"></span>
                                {{ __('common.friday') }}
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" name="applicable_days[]" value="saturday">
                                <span class="checkmark"></span>
                                {{ __('common.saturday') }}
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" name="applicable_days[]" value="sunday">
                                <span class="checkmark"></span>
                                {{ __('common.sunday') }}
                            </label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary cancel-btn">
                {{ __('common.cancel') }}
            </button>
            <button type="submit" form="happy-hour-form" class="btn btn-primary save-btn">
                {{ __('bar.pricing.save_happy_hour') }}
            </button>
        </div>
    </div>
</div>
@endsection
