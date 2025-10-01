@extends('layouts.admin')

@section('title', __('menu.food_items.title') . ' - ' . config('app.name'))
@section('page_title', __('menu.food_items.title'))

@push('styles')
    @vite('resources/css/admin/food-items.css')
@endpush

@push('scripts')
    @vite('resources/js/admin/food-items.js')
@endpush

@section('content')
<div class="food-items-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('menu.food_items.title') }}</h1>
                <p class="page-subtitle">{{ __('menu.food_items.subtitle') }}</p>
            </div>
            <div class="page-actions">
                <button type="button" class="btn btn-secondary import-dishes-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                    </svg>
                    {{ __('menu.food_items.import_dishes') }}
                </button>
                <button type="button" class="btn btn-secondary export-menu-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ __('menu.food_items.export_menu') }}
                </button>
                <button type="button" class="btn btn-primary add-dish-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('menu.food_items.add_dish') }}
                </button>
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
                           placeholder="{{ __('menu.food_items.search_dishes') }}"
                           id="dish-search">
                </div>
            </div>
            
            <div class="filter-group">
                <select class="filter-select" id="category-filter">
                    <option value="">{{ __('menu.food_items.all_categories') }}</option>
                    <option value="appetizers">{{ __('menu.food_items.appetizers') }}</option>
                    <option value="main-courses">{{ __('menu.food_items.main_courses') }}</option>
                    <option value="desserts">{{ __('menu.food_items.desserts') }}</option>
                    <option value="beverages">{{ __('menu.food_items.beverages') }}</option>
                </select>
            </div>
            
            <div class="filter-group">
                <select class="filter-select" id="status-filter">
                    <option value="">{{ __('menu.food_items.all_status') }}</option>
                    <option value="active">{{ __('menu.food_items.active') }}</option>
                    <option value="inactive">{{ __('menu.food_items.inactive') }}</option>
                    <option value="out-of-stock">{{ __('menu.food_items.out_of_stock') }}</option>
                </select>
            </div>
            
            <div class="filter-group">
                <select class="filter-select" id="price-filter">
                    <option value="">{{ __('menu.food_items.all_prices') }}</option>
                    <option value="0-50">{{ __('menu.food_items.under_50') }}</option>
                    <option value="50-100">50 - 100 ETB</option>
                    <option value="100-200">100 - 200 ETB</option>
                    <option value="200+">{{ __('menu.food_items.over_200') }}</option>
                </select>
            </div>
            
            <button type="button" class="btn btn-secondary clear-filters-btn">
                {{ __('menu.food_items.clear_filters') }}
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

    <!-- Dishes Grid/List -->
    <div class="dishes-content">
        <!-- Grid View -->
        <div class="dishes-grid" id="dishes-grid">
            <!-- Loading skeleton -->
            <div class="dish-card loading">
                <div class="dish-image-skeleton"></div>
                <div class="dish-content">
                    <div class="skeleton-title"></div>
                    <div class="skeleton-description"></div>
                    <div class="skeleton-price"></div>
                </div>
            </div>
            <div class="dish-card loading">
                <div class="dish-image-skeleton"></div>
                <div class="dish-content">
                    <div class="skeleton-title"></div>
                    <div class="skeleton-description"></div>
                    <div class="skeleton-price"></div>
                </div>
            </div>
            <div class="dish-card loading">
                <div class="dish-image-skeleton"></div>
                <div class="dish-content">
                    <div class="skeleton-title"></div>
                    <div class="skeleton-description"></div>
                    <div class="skeleton-price"></div>
                </div>
            </div>
        </div>

        <!-- List View -->
        <div class="dishes-list" id="dishes-list" style="display: none;">
            <div class="dishes-table-wrapper">
                <table class="dishes-table" role="table">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('menu.food_items.dish') }}</th>
                            <th scope="col">{{ __('menu.food_items.category') }}</th>
                            <th scope="col">{{ __('menu.food_items.price') }}</th>
                            <th scope="col">{{ __('menu.food_items.cost') }}</th>
                            <th scope="col">{{ __('menu.food_items.margin') }}</th>
                            <th scope="col">{{ __('menu.food_items.status') }}</th>
                            <th scope="col">{{ __('menu.food_items.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="dishes-table-body">
                        <!-- Loading skeleton rows -->
                        <tr class="loading-row">
                            <td><div class="skeleton-dish-info"></div></td>
                            <td><div class="skeleton-badge"></div></td>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <h3>{{ __('menu.food_items.no_dishes_found') }}</h3>
            <p>{{ __('menu.food_items.no_dishes_description') }}</p>
            <button type="button" class="btn btn-primary add-dish-btn">
                {{ __('menu.food_items.add_first_dish') }}
            </button>
        </div>
    </div>
</div>

<!-- Add/Edit Dish Modal -->
<div class="dish-modal" id="dish-modal" style="display: none;" role="dialog" aria-labelledby="dish-modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="dish-modal-title" class="modal-title">{{ __('menu.food_items.add_dish') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('menu.food_items.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form class="dish-form" id="dish-form">
                <div class="form-tabs">
                    <div class="tab-nav">
                        <button type="button" class="tab-btn active" data-tab="basic">
                            {{ __('menu.food_items.basic_info') }}
                        </button>
                        <button type="button" class="tab-btn" data-tab="ingredients">
                            {{ __('menu.food_items.ingredients') }}
                        </button>
                        <button type="button" class="tab-btn" data-tab="pricing">
                            {{ __('menu.food_items.pricing') }}
                        </button>
                        <button type="button" class="tab-btn" data-tab="media">
                            {{ __('menu.food_items.media') }}
                        </button>
                    </div>
                    
                    <div class="tab-content">
                        <!-- Basic Info Tab -->
                        <div class="tab-panel active" data-tab="basic">
                            <div class="form-grid grid grid-cols-1 md:grid-cols-2">
                                <!-- Dish Name -->
                                <div class="form-group md:col-span-2">
                                    <label for="dish-name" class="form-label required">
                                        {{ __('menu.food_items.dish_name') }}
                                    </label>
                                    <input type="text" id="dish-name" name="name" class="form-input" required
                                           placeholder="{{ __('menu.food_items.dish_name_placeholder') }}">
                                </div>

                                <!-- Category -->
                                <div class="form-group">
                                    <label for="dish-category" class="form-label required">
                                        {{ __('menu.food_items.category') }}
                                    </label>
                                    <select id="dish-category" name="category" class="form-select" required>
                                        <option value="">{{ __('menu.food_items.select_category') }}</option>
                                        <option value="appetizers">{{ __('menu.food_items.appetizers') }}</option>
                                        <option value="main-courses">{{ __('menu.food_items.main_courses') }}</option>
                                        <option value="desserts">{{ __('menu.food_items.desserts') }}</option>
                                        <option value="beverages">{{ __('menu.food_items.beverages') }}</option>
                                    </select>
                                </div>

                                <!-- Preparation Time -->
                                <div class="form-group">
                                    <label for="prep-time" class="form-label">
                                        {{ __('menu.food_items.prep_time') }}
                                    </label>
                                    <div class="input-with-suffix">
                                        <input type="number" id="prep-time" name="prep_time" class="form-input" min="0"
                                               placeholder="15">
                                        <span class="input-suffix">{{ __('menu.food_items.minutes') }}</span>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="form-group md:col-span-2">
                                    <label for="dish-description" class="form-label">
                                        {{ __('menu.food_items.description') }}
                                    </label>
                                    <textarea id="dish-description" name="description" class="form-textarea" rows="4"
                                              placeholder="{{ __('menu.food_items.description_placeholder') }}"></textarea>
                                </div>

                                <!-- Dietary Info -->
                                <div class="form-group md:col-span-2">
                                    <label class="form-label">{{ __('menu.food_items.dietary_info') }}</label>
                                    <div class="checkbox-group">
                                        <label class="checkbox-item">
                                            <input type="checkbox" name="dietary[]" value="vegetarian">
                                            <span class="checkmark"></span>
                                            {{ __('menu.food_items.vegetarian') }}
                                        </label>
                                        <label class="checkbox-item">
                                            <input type="checkbox" name="dietary[]" value="vegan">
                                            <span class="checkmark"></span>
                                            {{ __('menu.food_items.vegan') }}
                                        </label>
                                        <label class="checkbox-item">
                                            <input type="checkbox" name="dietary[]" value="gluten-free">
                                            <span class="checkmark"></span>
                                            {{ __('menu.food_items.gluten_free') }}
                                        </label>
                                        <label class="checkbox-item">
                                            <input type="checkbox" name="dietary[]" value="spicy">
                                            <span class="checkmark"></span>
                                            {{ __('menu.food_items.spicy') }}
                                        </label>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="form-group md:col-span-2">
                                    <label class="form-label">{{ __('menu.food_items.status') }}</label>
                                    <div class="radio-group">
                                        <label class="radio-item">
                                            <input type="radio" name="status" value="active" checked>
                                            <span class="radio-mark"></span>
                                            {{ __('menu.food_items.active') }}
                                        </label>
                                        <label class="radio-item">
                                            <input type="radio" name="status" value="inactive">
                                            <span class="radio-mark"></span>
                                            {{ __('menu.food_items.inactive') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ingredients Tab -->
                        <div class="tab-panel" data-tab="ingredients">
                            <div class="ingredients-section">
                                <div class="section-header">
                                    <h4>{{ __('menu.food_items.recipe_ingredients') }}</h4>
                                    <button type="button" class="btn btn-sm btn-secondary add-ingredient-btn">
                                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        {{ __('menu.food_items.add_ingredient') }}
                                    </button>
                                </div>
                                
                                <div class="ingredients-list" id="ingredients-list">
                                    <!-- Ingredient items will be added here dynamically -->
                                    <div class="ingredient-item">
                                        <div class="ingredient-select-wrapper">
                                            <select class="form-select ingredient-select" name="ingredients[0][id]">
                                                <option value="">{{ __('menu.food_items.select_ingredient') }}</option>
                                                <option value="1">Tomatoes</option>
                                                <option value="2">Onions</option>
                                                <option value="3">Chicken Breast</option>
                                                <option value="4">Rice</option>
                                            </select>
                                        </div>
                                        <div class="quantity-input-wrapper">
                                            <input type="number" class="form-input quantity-input" 
                                                   name="ingredients[0][quantity]" 
                                                   placeholder="0" min="0" step="0.01">
                                        </div>
                                        <div class="unit-select-wrapper">
                                            <select class="form-select unit-select" name="ingredients[0][unit]">
                                                <option value="kg">kg</option>
                                                <option value="g">g</option>
                                                <option value="l">l</option>
                                                <option value="ml">ml</option>
                                                <option value="pcs">pcs</option>
                                            </select>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-danger remove-ingredient-btn">
                                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pricing Tab -->
                        <div class="tab-panel" data-tab="pricing">
                            <div class="form-grid grid grid-cols-1 md:grid-cols-2">
                                <!-- Cost Price -->
                                <div class="form-group">
                                    <label for="cost-price" class="form-label">
                                        {{ __('menu.food_items.cost_price') }}
                                    </label>
                                    <div class="input-with-prefix">
                                        <span class="input-prefix">ETB</span>
                                        <input type="number" id="cost-price" name="cost_price" class="form-input" 
                                               min="0" step="0.01" placeholder="0.00">
                                    </div>
                                    <small class="form-hint">{{ __('menu.food_items.cost_price_hint') }}</small>
                                </div>

                                <!-- Selling Price -->
                                <div class="form-group">
                                    <label for="selling-price" class="form-label required">
                                        {{ __('menu.food_items.selling_price') }}
                                    </label>
                                    <div class="input-with-prefix">
                                        <span class="input-prefix">ETB</span>
                                        <input type="number" id="selling-price" name="selling_price" class="form-input" 
                                               min="0" step="0.01" placeholder="0.00" required>
                                    </div>
                                </div>

                                <!-- Margin Display -->
                                <div class="form-group md:col-span-2">
                                    <div class="margin-display">
                                        <div class="margin-item">
                                            <span class="margin-label">{{ __('menu.food_items.profit_margin') }}</span>
                                            <span class="margin-value" id="profit-margin">0 ETB (0%)</span>
                                        </div>
                                        <div class="margin-item">
                                            <span class="margin-label">{{ __('menu.food_items.markup') }}</span>
                                            <span class="margin-value" id="markup">0%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Media Tab -->
                        <div class="tab-panel" data-tab="media">
                            <div class="media-upload-section">
                                <div class="image-upload-area" id="image-upload-area">
                                    <div class="upload-placeholder">
                                        <svg class="upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <h4>{{ __('menu.food_items.upload_image') }}</h4>
                                        <p>{{ __('menu.food_items.upload_image_hint') }}</p>
                                        <button type="button" class="btn btn-secondary select-image-btn">
                                            {{ __('menu.food_items.select_image') }}
                                        </button>
                                        <input type="file" id="dish-image" name="image" accept="image/*" style="display: none;">
                                    </div>
                                </div>
                                
                                <div class="image-preview" id="image-preview" style="display: none;">
                                    <img id="preview-image" src="" alt="Dish preview">
                                    <div class="image-actions">
                                        <button type="button" class="btn btn-sm btn-secondary change-image-btn">
                                            {{ __('menu.food_items.change_image') }}
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger remove-image-btn">
                                            {{ __('menu.food_items.remove_image') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary cancel-btn">
                {{ __('menu.food_items.cancel') }}
            </button>
            <button type="submit" form="dish-form" class="btn btn-primary save-btn">
                {{ __('menu.food_items.save_dish') }}
            </button>
        </div>
    </div>
</div>
@endsection
