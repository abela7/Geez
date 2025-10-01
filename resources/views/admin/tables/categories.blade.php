@extends('layouts.admin')

@section('title', __('tables.categories.title') . ' - ' . config('app.name'))
@section('page_title', __('tables.categories.title'))

@push('styles')
    {{-- CSS styles will be loaded via main layout --}}
@endpush

@push('scripts')
    @vite('resources/js/admin/tables/table-categories.js')
@endpush

@section('content')
<div class="categories-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('tables.categories.title') }}</h1>
                <p class="page-subtitle">{{ __('tables.categories.subtitle') }}</p>
            </div>
            <div class="page-actions">
                <button type="button" class="btn btn-secondary export-categories-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ __('common.export') }}
                </button>
                <button type="button" class="btn btn-primary add-category-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('tables.categories.add_category') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-card total">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="total-categories">0</div>
                    <div class="stat-label">{{ __('tables.total_items') }}</div>
                </div>
            </div>
            
            <div class="stat-card active">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="active-categories">0</div>
                    <div class="stat-label">{{ __('tables.active_items') }}</div>
                </div>
            </div>
            
            <div class="stat-card capacity">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="avg-capacity">0</div>
                    <div class="stat-label">{{ __('common.average') }} {{ __('tables.capacity') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="search-filters-container">
        <div class="search-bar">
            <div class="search-input-wrapper">
                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" 
                       class="search-input" 
                       placeholder="{{ __('tables.search_placeholder') }}"
                       id="categories-search">
            </div>
        </div>
        
        <div class="filters-container">
            <select class="filter-select" id="capacity-filter">
                <option value="">{{ __('tables.filter_by') }} {{ __('tables.capacity') }}</option>
                <option value="small">1-2 {{ __('common.people') }}</option>
                <option value="medium">3-4 {{ __('common.people') }}</option>
                <option value="large">5-8 {{ __('common.people') }}</option>
                <option value="xlarge">9+ {{ __('common.people') }}</option>
            </select>
            
            <button type="button" class="btn btn-secondary clear-filters-btn">
                {{ __('common.clear_filters') }}
            </button>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="categories-content">
        <div class="categories-grid" id="categories-grid">
            <!-- Categories will be populated by JavaScript -->
        </div>
    </div>
</div>

<!-- Add/Edit Category Modal -->
<div class="category-modal" id="category-modal" style="display: none;" role="dialog" aria-labelledby="category-modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="category-modal-title" class="modal-title">{{ __('tables.categories.add_category') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('tables.categories.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form id="category-form" class="category-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="category-name" class="form-label required">{{ __('tables.categories.category_name') }}</label>
                        <input type="text" id="category-name" name="category_name" class="form-input" required
                               placeholder="{{ __('tables.categories.category_name_placeholder') }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="category-code" class="form-label required">{{ __('tables.categories.category_code') }}</label>
                        <input type="text" id="category-code" name="category_code" class="form-input" required
                               placeholder="{{ __('tables.categories.category_code_placeholder') }}" maxlength="5">
                    </div>
                    
                    <div class="form-group">
                        <label for="default-capacity" class="form-label required">{{ __('tables.categories.default_capacity') }}</label>
                        <input type="number" id="default-capacity" name="default_capacity" class="form-input" required
                               min="1" max="20" value="4">
                    </div>
                    
                    <div class="form-group">
                        <label for="pricing-multiplier" class="form-label">{{ __('tables.categories.pricing_multiplier') }}</label>
                        <div class="input-group">
                            <input type="number" id="pricing-multiplier" name="pricing_multiplier" class="form-input" 
                                   min="0.1" max="5.0" step="0.1" value="1.0">
                            <span class="input-suffix">x</span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="category-color" class="form-label required">{{ __('tables.categories.color') }}</label>
                        <div class="color-selector">
                            <input type="hidden" id="category-color" name="color" value="#3b82f6">
                            <div class="color-options" id="color-options">
                                <div class="color-option" data-color="#3b82f6" style="background: #3b82f6;" title="Blue"></div>
                                <div class="color-option" data-color="#10b981" style="background: #10b981;" title="Green"></div>
                                <div class="color-option" data-color="#f59e0b" style="background: #f59e0b;" title="Amber"></div>
                                <div class="color-option" data-color="#ef4444" style="background: #ef4444;" title="Red"></div>
                                <div class="color-option" data-color="#8b5cf6" style="background: #8b5cf6;" title="Purple"></div>
                                <div class="color-option" data-color="#06b6d4" style="background: #06b6d4;" title="Cyan"></div>
                                <div class="color-option" data-color="#84cc16" style="background: #84cc16;" title="Lime"></div>
                                <div class="color-option" data-color="#f97316" style="background: #f97316;" title="Orange"></div>
                                <div class="color-option" data-color="#ec4899" style="background: #ec4899;" title="Pink"></div>
                                <div class="color-option" data-color="#6b7280" style="background: #6b7280;" title="Gray"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="category-icon" class="form-label required">{{ __('tables.categories.icon') }}</label>
                        <div class="icon-selector">
                            <input type="hidden" id="category-icon" name="icon" value="table">
                            <div class="icon-options" id="icon-options">
                                <div class="icon-option active" data-icon="table" title="Table">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0V7a2 2 0 012-2h16a2 2 0 012 2v11a2 2 0 01-2 2H5a2 2 0 01-2-2V10z"/>
                                    </svg>
                                </div>
                                <div class="icon-option" data-icon="chair" title="Chair">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0h10m-9 0v16l2-2m7 2l2 2V4"/>
                                    </svg>
                                </div>
                                <div class="icon-option" data-icon="booth" title="Booth">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div class="icon-option" data-icon="bar" title="Bar">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18l-2 9H5L3 3zm0 0l-.5-2M7 13v8a2 2 0 002 2h6a2 2 0 002-2v-8"/>
                                    </svg>
                                </div>
                                <div class="icon-option" data-icon="outdoor" title="Outdoor">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                    </svg>
                                </div>
                                <div class="icon-option" data-icon="vip" title="VIP">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                    </svg>
                                </div>
                                <div class="icon-option" data-icon="counter" title="Counter">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                    </svg>
                                </div>
                                <div class="icon-option" data-icon="communal" title="Communal">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="category-description" class="form-label">{{ __('tables.categories.description') }}</label>
                        <textarea id="category-description" name="description" class="form-textarea" rows="3"
                                  placeholder="{{ __('tables.categories.description_placeholder') }}"></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary cancel-category-btn">
                {{ __('tables.categories.cancel') }}
            </button>
            <button type="submit" form="category-form" class="btn btn-primary save-category-btn">
                {{ __('tables.categories.save_category') }}
            </button>
        </div>
    </div>
</div>
@endsection
