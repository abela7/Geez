@extends('layouts.admin')

@section('title', __('tables.types.title') . ' - ' . config('app.name'))
@section('page_title', __('tables.types.title'))

@push('styles')
    {{-- CSS styles will be loaded via main layout --}}
@endpush

@push('scripts')
    @vite('resources/js/admin/tables/table-types.js')
@endpush

@section('content')
<div class="types-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('tables.types.title') }}</h1>
                <p class="page-subtitle">{{ __('tables.types.subtitle') }}</p>
            </div>
            <div class="page-actions">
                <button type="button" class="btn btn-secondary export-types-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ __('common.export') }}
                </button>
                <button type="button" class="btn btn-primary add-type-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('tables.types.add_type') }}
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="total-types">0</div>
                    <div class="stat-label">{{ __('tables.total_items') }}</div>
                </div>
            </div>
            
            <div class="stat-card shapes">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="unique-shapes">0</div>
                    <div class="stat-label">{{ __('common.unique') }} {{ __('tables.types.shape') }}s</div>
                </div>
            </div>
            
            <div class="stat-card capacity">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="max-capacity">0</div>
                    <div class="stat-label">{{ __('common.max') }} {{ __('tables.capacity') }}</div>
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
                       id="types-search">
            </div>
        </div>
        
        <div class="filters-container">
            <select class="filter-select" id="shape-filter">
                <option value="">{{ __('tables.filter_by') }} {{ __('tables.types.shape') }}</option>
                <option value="rectangle">{{ __('tables.types.rectangle') }}</option>
                <option value="circle">{{ __('tables.types.circle') }}</option>
                <option value="square">{{ __('tables.types.square') }}</option>
                <option value="oval">{{ __('tables.types.oval') }}</option>
            </select>
            
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

    <!-- Types Grid -->
    <div class="types-content">
        <div class="types-grid" id="types-grid">
            <!-- Types will be populated by JavaScript -->
        </div>
    </div>
</div>

<!-- Add/Edit Type Modal -->
<div class="type-modal" id="type-modal" style="display: none;" role="dialog" aria-labelledby="type-modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="type-modal-title" class="modal-title">{{ __('tables.types.add_type') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('tables.types.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form id="type-form" class="type-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="type-name" class="form-label required">{{ __('tables.types.type_name') }}</label>
                        <input type="text" id="type-name" name="type_name" class="form-input" required
                               placeholder="{{ __('tables.types.type_name_placeholder') }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="type-code" class="form-label required">{{ __('tables.types.type_code') }}</label>
                        <input type="text" id="type-code" name="type_code" class="form-input" required
                               placeholder="{{ __('tables.types.type_code_placeholder') }}" maxlength="5">
                    </div>
                    
                    <div class="form-group">
                        <label for="table-shape" class="form-label required">{{ __('tables.types.shape') }}</label>
                        <div class="shape-selector">
                            <div class="shape-options" id="shape-options">
                                <div class="shape-option active" data-shape="rectangle" title="{{ __('tables.types.rectangle') }}">
                                    <div class="shape-preview rectangle"></div>
                                    <span class="shape-label">{{ __('tables.types.rectangle') }}</span>
                                </div>
                                <div class="shape-option" data-shape="circle" title="{{ __('tables.types.circle') }}">
                                    <div class="shape-preview circle"></div>
                                    <span class="shape-label">{{ __('tables.types.circle') }}</span>
                                </div>
                                <div class="shape-option" data-shape="square" title="{{ __('tables.types.square') }}">
                                    <div class="shape-preview square"></div>
                                    <span class="shape-label">{{ __('tables.types.square') }}</span>
                                </div>
                                <div class="shape-option" data-shape="oval" title="{{ __('tables.types.oval') }}">
                                    <div class="shape-preview oval"></div>
                                    <span class="shape-label">{{ __('tables.types.oval') }}</span>
                                </div>
                            </div>
                            <input type="hidden" id="selected-shape" name="shape" value="rectangle">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="min-capacity" class="form-label required">{{ __('tables.types.min_capacity') }}</label>
                        <input type="number" id="min-capacity" name="min_capacity" class="form-input" required
                               min="1" max="20" value="2">
                    </div>
                    
                    <div class="form-group">
                        <label for="max-capacity" class="form-label required">{{ __('tables.types.max_capacity') }}</label>
                        <input type="number" id="max-capacity" name="max_capacity" class="form-input" required
                               min="1" max="20" value="4">
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="type-description" class="form-label">{{ __('tables.types.description') }}</label>
                        <textarea id="type-description" name="description" class="form-textarea" rows="3"
                                  placeholder="{{ __('tables.types.description_placeholder') }}"></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary cancel-type-btn">
                {{ __('tables.types.cancel') }}
            </button>
            <button type="submit" form="type-form" class="btn btn-primary save-type-btn">
                {{ __('tables.types.save_type') }}
            </button>
        </div>
    </div>
</div>
@endsection
