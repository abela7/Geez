@extends('layouts.admin')

@section('title', __('menu.categories.title') . ' - ' . config('app.name'))
@section('page_title', __('menu.categories.title'))

@push('styles')
    @vite('resources/css/admin/menu-categories.css')
@endpush

@push('scripts')
    @vite('resources/js/admin/menu-categories.js')
@endpush

@section('content')
<div class="categories-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('menu.categories.title') }}</h1>
                <p class="page-subtitle">{{ __('menu.categories.subtitle') }}</p>
            </div>
            <div class="page-actions">
                <button type="button" class="btn btn-secondary import-categories-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                    </svg>
                    {{ __('menu.categories.import_categories') }}
                </button>
                <button type="button" class="btn btn-secondary export-categories-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ __('menu.categories.export_categories') }}
                </button>
                <button type="button" class="btn btn-primary add-category-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('menu.categories.add_category') }}
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="total-categories">0</div>
                    <div class="stat-label">{{ __('menu.categories.total_categories') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon active">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="active-categories">0</div>
                    <div class="stat-label">{{ __('menu.categories.active_categories') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon dishes">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="total-dishes">0</div>
                    <div class="stat-label">{{ __('menu.categories.total_dishes') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon popular">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="popular-category">-</div>
                    <div class="stat-label">{{ __('menu.categories.most_popular') }}</div>
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
                           placeholder="{{ __('menu.categories.search_categories') }}"
                           id="category-search">
                </div>
            </div>
            
            <div class="filter-group">
                <select class="filter-select" id="status-filter">
                    <option value="">{{ __('menu.categories.all_status') }}</option>
                    <option value="active">{{ __('menu.categories.active') }}</option>
                    <option value="inactive">{{ __('menu.categories.inactive') }}</option>
                </select>
            </div>
            
            <div class="filter-group">
                <select class="filter-select" id="sort-filter">
                    <option value="name">{{ __('menu.categories.sort_by_name') }}</option>
                    <option value="dishes">{{ __('menu.categories.sort_by_dishes') }}</option>
                    <option value="created">{{ __('menu.categories.sort_by_created') }}</option>
                    <option value="updated">{{ __('menu.categories.sort_by_updated') }}</option>
                </select>
            </div>
            
            <button type="button" class="btn btn-secondary clear-filters-btn">
                {{ __('menu.categories.clear_filters') }}
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

    <!-- Categories Content -->
    <div class="categories-content">
        <!-- Grid View -->
        <div class="categories-grid" id="categories-grid">
            <!-- Loading skeleton -->
            <div class="category-card loading">
                <div class="category-header">
                    <div class="skeleton-color"></div>
                    <div class="skeleton-title"></div>
                </div>
                <div class="skeleton-description"></div>
                <div class="skeleton-stats"></div>
            </div>
            <div class="category-card loading">
                <div class="category-header">
                    <div class="skeleton-color"></div>
                    <div class="skeleton-title"></div>
                </div>
                <div class="skeleton-description"></div>
                <div class="skeleton-stats"></div>
            </div>
            <div class="category-card loading">
                <div class="category-header">
                    <div class="skeleton-color"></div>
                    <div class="skeleton-title"></div>
                </div>
                <div class="skeleton-description"></div>
                <div class="skeleton-stats"></div>
            </div>
        </div>

        <!-- List View -->
        <div class="categories-list" id="categories-list" style="display: none;">
            <div class="categories-table-wrapper">
                <table class="categories-table" role="table">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('menu.categories.category') }}</th>
                            <th scope="col">{{ __('menu.categories.description') }}</th>
                            <th scope="col">{{ __('menu.categories.dishes_count') }}</th>
                            <th scope="col">{{ __('menu.categories.display_order') }}</th>
                            <th scope="col">{{ __('menu.categories.status') }}</th>
                            <th scope="col">{{ __('menu.categories.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="categories-table-body">
                        <!-- Loading skeleton rows -->
                        <tr class="loading-row">
                            <td><div class="skeleton-category-info"></div></td>
                            <td><div class="skeleton-text"></div></td>
                            <td><div class="skeleton-text"></div></td>
                            <td><div class="skeleton-text"></div></td>
                            <td><div class="skeleton-badge"></div></td>
                            <td><div class="skeleton-actions"></div></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Empty State -->
        <div class="empty-state" style="display: none;">
            <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            <h3>{{ __('menu.categories.no_categories_found') }}</h3>
            <p>{{ __('menu.categories.no_categories_description') }}</p>
            <button type="button" class="btn btn-primary add-category-btn">
                {{ __('menu.categories.add_first_category') }}
            </button>
        </div>
    </div>
</div>

<!-- Add/Edit Category Modal -->
<div class="category-modal" id="category-modal" style="display: none;" role="dialog" aria-labelledby="category-modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="category-modal-title" class="modal-title">{{ __('menu.categories.add_category') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('menu.categories.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form class="category-form" id="category-form">
                <div class="form-grid grid grid-cols-1 md:grid-cols-2">
                    <!-- Category Name -->
                    <div class="form-group md:col-span-2">
                        <label for="category-name" class="form-label required">
                            {{ __('menu.categories.category_name') }}
                        </label>
                        <input type="text" id="category-name" name="name" class="form-input" required
                               placeholder="{{ __('menu.categories.category_name_placeholder') }}">
                    </div>

                    <!-- Category Color -->
                    <div class="form-group">
                        <label for="category-color" class="form-label">
                            {{ __('menu.categories.category_color') }}
                        </label>
                        <div class="color-picker-group">
                            <input type="color" id="category-color" name="color" class="color-input" value="#3b82f6">
                            <div class="color-presets">
                                <button type="button" class="color-preset" data-color="#ef4444" style="background: #ef4444;" title="Red"></button>
                                <button type="button" class="color-preset" data-color="#f59e0b" style="background: #f59e0b;" title="Orange"></button>
                                <button type="button" class="color-preset" data-color="#10b981" style="background: #10b981;" title="Green"></button>
                                <button type="button" class="color-preset" data-color="#3b82f6" style="background: #3b82f6;" title="Blue"></button>
                                <button type="button" class="color-preset" data-color="#8b5cf6" style="background: #8b5cf6;" title="Purple"></button>
                                <button type="button" class="color-preset" data-color="#ec4899" style="background: #ec4899;" title="Pink"></button>
                                <button type="button" class="color-preset" data-color="#06b6d4" style="background: #06b6d4;" title="Cyan"></button>
                                <button type="button" class="color-preset" data-color="#84cc16" style="background: #84cc16;" title="Lime"></button>
                            </div>
                        </div>
                    </div>

                    <!-- Display Order -->
                    <div class="form-group">
                        <label for="display-order" class="form-label">
                            {{ __('menu.categories.display_order') }}
                        </label>
                        <input type="number" id="display-order" name="display_order" class="form-input" 
                               min="0" step="1" placeholder="0">
                        <small class="form-hint">{{ __('menu.categories.display_order_hint') }}</small>
                    </div>

                    <!-- Description -->
                    <div class="form-group md:col-span-2">
                        <label for="category-description" class="form-label">
                            {{ __('menu.categories.description') }}
                        </label>
                        <textarea id="category-description" name="description" class="form-textarea" rows="3"
                                  placeholder="{{ __('menu.categories.description_placeholder') }}"></textarea>
                    </div>

                    <!-- Icon Selection -->
                    <div class="form-group md:col-span-2">
                        <label class="form-label">{{ __('menu.categories.category_icon') }}</label>
                        <div class="icon-selection">
                            <div class="icon-grid">
                                <button type="button" class="icon-option active" data-icon="utensils">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6"/>
                                    </svg>
                                </button>
                                <button type="button" class="icon-option" data-icon="coffee">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                    </svg>
                                </button>
                                <button type="button" class="icon-option" data-icon="cake">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0A2.704 2.704 0 003 15.546V12c0-.55.45-1 1-1h16c.55 0 1 .45 1 1v3.546z"/>
                                    </svg>
                                </button>
                                <button type="button" class="icon-option" data-icon="pizza">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                </button>
                                <button type="button" class="icon-option" data-icon="fish">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/>
                                    </svg>
                                </button>
                                <button type="button" class="icon-option" data-icon="salad">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                                    </svg>
                                </button>
                            </div>
                            <input type="hidden" id="selected-icon" name="icon" value="utensils">
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="form-group md:col-span-2">
                        <label class="form-label">{{ __('menu.categories.status') }}</label>
                        <div class="toggle-group">
                            <label class="toggle-switch">
                                <input type="checkbox" name="active" value="1" checked>
                                <span class="toggle-slider"></span>
                                <span class="toggle-label">{{ __('menu.categories.active') }}</span>
                            </label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary cancel-btn">
                {{ __('menu.categories.cancel') }}
            </button>
            <button type="submit" form="category-form" class="btn btn-primary save-btn">
                {{ __('menu.categories.save_category') }}
            </button>
        </div>
    </div>
</div>

<!-- Category Details Modal -->
<div class="category-details-modal" id="category-details-modal" style="display: none;" role="dialog" aria-labelledby="category-details-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="category-details-title" class="modal-title">{{ __('menu.categories.category_details') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('menu.categories.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="category-details-content" id="category-details-content">
                <!-- Category details will be populated here -->
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary close-details-btn">
                {{ __('menu.categories.close') }}
            </button>
            <button type="button" class="btn btn-primary edit-category-btn">
                {{ __('menu.categories.edit_category') }}
            </button>
        </div>
    </div>
</div>
@endsection
