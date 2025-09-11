@extends('layouts.admin')

@section('title', __('bar.recipes.title') . ' - ' . config('app.name'))
@section('page_title', __('bar.recipes.title'))

@push('styles')
    @vite('resources/css/admin/bar/cocktail-recipes.css')
@endpush

@push('scripts')
    @vite('resources/js/admin/bar/cocktail-recipes.js')
@endpush

@section('content')
<div class="recipes-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('bar.recipes.title') }}</h1>
                <p class="page-subtitle">{{ __('bar.recipes.subtitle') }}</p>
            </div>
            <div class="page-actions">
                <button type="button" class="btn btn-secondary export-recipes-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ __('bar.recipes.export_recipes') }}
                </button>
                <button type="button" class="btn btn-primary add-recipe-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('bar.recipes.add_recipe') }}
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
                    <div class="stat-value" id="total-recipes">0</div>
                    <div class="stat-label">{{ __('bar.recipes.total_recipes') }}</div>
                </div>
            </div>
            
            <div class="stat-card signature">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="signature-recipes">0</div>
                    <div class="stat-label">{{ __('bar.recipes.signature_recipes') }}</div>
                </div>
            </div>
            
            <div class="stat-card popular">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="popular-recipes">0</div>
                    <div class="stat-label">{{ __('bar.recipes.popular_recipes') }}</div>
                </div>
            </div>
            
            <div class="stat-card cost">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="avg-cost">$0.00</div>
                    <div class="stat-label">{{ __('bar.recipes.avg_cost_per_drink') }}</div>
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
                       placeholder="{{ __('bar.recipes.search_recipes') }}"
                       id="recipes-search">
            </div>
        </div>
        
        <div class="filters-container">
            <select class="filter-select" id="type-filter">
                <option value="">{{ __('bar.recipes.all_types') }}</option>
                <option value="classic_cocktail">{{ __('bar.recipes.classic_cocktail') }}</option>
                <option value="signature_cocktail">{{ __('bar.recipes.signature_cocktail') }}</option>
                <option value="mocktail">{{ __('bar.recipes.mocktail') }}</option>
                <option value="shot">{{ __('bar.recipes.shot') }}</option>
                <option value="mixed_drink">{{ __('bar.recipes.mixed_drink') }}</option>
                <option value="frozen_drink">{{ __('bar.recipes.frozen_drink') }}</option>
                <option value="hot_drink">{{ __('bar.recipes.hot_drink') }}</option>
            </select>
            
            <select class="filter-select" id="difficulty-filter">
                <option value="">{{ __('bar.recipes.all_difficulties') }}</option>
                <option value="easy">{{ __('bar.recipes.easy') }}</option>
                <option value="medium">{{ __('bar.recipes.medium') }}</option>
                <option value="hard">{{ __('bar.recipes.hard') }}</option>
                <option value="expert">{{ __('bar.recipes.expert') }}</option>
            </select>
            
            <select class="filter-select" id="glass-filter">
                <option value="">{{ __('bar.recipes.all_glasses') }}</option>
                <option value="highball">{{ __('bar.recipes.highball') }}</option>
                <option value="lowball">{{ __('bar.recipes.lowball') }}</option>
                <option value="martini">{{ __('bar.recipes.martini') }}</option>
                <option value="wine_glass">{{ __('bar.recipes.wine_glass') }}</option>
                <option value="champagne_flute">{{ __('bar.recipes.champagne_flute') }}</option>
                <option value="beer_mug">{{ __('bar.recipes.beer_mug') }}</option>
                <option value="shot_glass">{{ __('bar.recipes.shot_glass') }}</option>
                <option value="hurricane">{{ __('bar.recipes.hurricane') }}</option>
            </select>
            
            <button type="button" class="btn btn-secondary clear-filters-btn">
                {{ __('common.clear_filters') }}
            </button>
        </div>
    </div>

    <!-- View Toggle -->
    <div class="view-controls">
        <div class="view-toggle">
            <button type="button" class="view-btn active" data-view="grid" title="{{ __('common.grid_view') }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
            </button>
            <button type="button" class="view-btn" data-view="list" title="{{ __('common.list_view') }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
            </button>
        </div>
        
        <div class="sort-controls">
            <select id="sort-select" class="sort-select">
                <option value="name">{{ __('bar.recipes.recipe_name') }}</option>
                <option value="type">{{ __('bar.recipes.recipe_type') }}</option>
                <option value="difficulty">{{ __('bar.recipes.difficulty') }}</option>
                <option value="cost">{{ __('bar.recipes.cost_per_drink') }}</option>
                <option value="popularity">{{ __('bar.recipes.popularity') }}</option>
            </select>
        </div>
    </div>

    <!-- Recipes Content -->
    <div class="recipes-content">
        <div class="recipes-grid" id="recipes-grid">
            <!-- Recipes will be populated by JavaScript -->
        </div>
    </div>
</div>

<!-- Add/Edit Recipe Modal -->
<div class="recipe-modal" id="recipe-modal" style="display: none;" role="dialog" aria-labelledby="recipe-modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content large">
        <div class="modal-header">
            <h2 id="recipe-modal-title" class="modal-title">{{ __('bar.recipes.add_recipe') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('bar.recipes.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form id="recipe-form" class="recipe-form">
                <div class="form-sections">
                    <!-- Basic Information -->
                    <div class="form-section">
                        <h3 class="section-title">{{ __('common.basic_information') }}</h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="recipe-name" class="form-label required">{{ __('bar.recipes.recipe_name') }}</label>
                                <input type="text" id="recipe-name" name="recipe_name" class="form-input" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="recipe-type" class="form-label required">{{ __('bar.recipes.recipe_type') }}</label>
                                <select id="recipe-type" name="recipe_type" class="form-select" required>
                                    <option value="">{{ __('common.select') }}...</option>
                                    <option value="classic_cocktail">{{ __('bar.recipes.classic_cocktail') }}</option>
                                    <option value="signature_cocktail">{{ __('bar.recipes.signature_cocktail') }}</option>
                                    <option value="mocktail">{{ __('bar.recipes.mocktail') }}</option>
                                    <option value="shot">{{ __('bar.recipes.shot') }}</option>
                                    <option value="mixed_drink">{{ __('bar.recipes.mixed_drink') }}</option>
                                    <option value="frozen_drink">{{ __('bar.recipes.frozen_drink') }}</option>
                                    <option value="hot_drink">{{ __('bar.recipes.hot_drink') }}</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="difficulty" class="form-label">{{ __('bar.recipes.difficulty') }}</label>
                                <select id="difficulty" name="difficulty" class="form-select">
                                    <option value="easy" selected>{{ __('bar.recipes.easy') }}</option>
                                    <option value="medium">{{ __('bar.recipes.medium') }}</option>
                                    <option value="hard">{{ __('bar.recipes.hard') }}</option>
                                    <option value="expert">{{ __('bar.recipes.expert') }}</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="glass-type" class="form-label">{{ __('bar.recipes.glass_type') }}</label>
                                <select id="glass-type" name="glass_type" class="form-select">
                                    <option value="">{{ __('common.select') }}...</option>
                                    <option value="highball">{{ __('bar.recipes.highball') }}</option>
                                    <option value="lowball">{{ __('bar.recipes.lowball') }}</option>
                                    <option value="martini">{{ __('bar.recipes.martini') }}</option>
                                    <option value="wine_glass">{{ __('bar.recipes.wine_glass') }}</option>
                                    <option value="champagne_flute">{{ __('bar.recipes.champagne_flute') }}</option>
                                    <option value="beer_mug">{{ __('bar.recipes.beer_mug') }}</option>
                                    <option value="shot_glass">{{ __('bar.recipes.shot_glass') }}</option>
                                    <option value="hurricane">{{ __('bar.recipes.hurricane') }}</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="preparation-time" class="form-label">{{ __('bar.recipes.preparation_time') }} ({{ __('common.minutes') }})</label>
                                <input type="number" id="preparation-time" name="preparation_time" class="form-input" min="1" max="60" value="5">
                            </div>
                            
                            <div class="form-group">
                                <label for="serving-size" class="form-label">{{ __('bar.recipes.serving_size') }} (ml)</label>
                                <input type="number" id="serving-size" name="serving_size" class="form-input" min="25" max="1000" value="250">
                            </div>
                        </div>
                    </div>

                    <!-- Ingredients -->
                    <div class="form-section">
                        <h3 class="section-title">{{ __('bar.recipes.ingredients') }}</h3>
                        <div class="ingredients-list" id="ingredients-list">
                            <!-- Ingredients will be added dynamically -->
                        </div>
                        <button type="button" class="btn btn-outline add-ingredient-btn">
                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            {{ __('bar.recipes.add_ingredient') }}
                        </button>
                    </div>

                    <!-- Instructions & Details -->
                    <div class="form-section">
                        <h3 class="section-title">{{ __('common.details') }}</h3>
                        <div class="form-grid">
                            <div class="form-group full-width">
                                <label for="instructions" class="form-label">{{ __('bar.recipes.instructions') }}</label>
                                <textarea id="instructions" name="instructions" class="form-textarea" rows="4"></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="garnish" class="form-label">{{ __('bar.recipes.garnish') }}</label>
                                <input type="text" id="garnish" name="garnish" class="form-input">
                            </div>
                            
                            <div class="form-group">
                                <label for="selling-price" class="form-label">{{ __('bar.recipes.selling_price') }}</label>
                                <div class="input-group">
                                    <span class="input-prefix">$</span>
                                    <input type="number" id="selling-price" name="selling_price" class="form-input" min="0" step="0.01">
                                </div>
                            </div>
                            
                            <div class="form-group full-width">
                                <label for="notes" class="form-label">{{ __('bar.recipes.notes') }}</label>
                                <textarea id="notes" name="notes" class="form-textarea" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary cancel-recipe-btn">
                {{ __('bar.recipes.cancel') }}
            </button>
            <button type="submit" form="recipe-form" class="btn btn-primary save-recipe-btn">
                {{ __('bar.recipes.save_recipe') }}
            </button>
        </div>
    </div>
</div>
@endsection
