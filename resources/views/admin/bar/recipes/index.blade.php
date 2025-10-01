@extends('layouts.admin')

@section('title', __('bar.recipes.title') . ' - ' . config('app.name'))
@section('page_title', __('bar.recipes.title'))

@push('styles')
    {{-- CSS styles will be loaded via main layout --}}
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
            <div class="stat-card">
                <div class="stat-icon total">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="total-recipes">0</div>
                    <div class="stat-label">{{ __('bar.recipes.total_recipes') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon signature">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="signature-recipes">0</div>
                    <div class="stat-label">{{ __('bar.recipes.signature_recipes') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon popular">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="popular-recipes">0</div>
                    <div class="stat-label">{{ __('bar.recipes.popular_recipes') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon cost">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="avg-cost">$0.00</div>
                    <div class="stat-label">{{ __('bar.recipes.avg_cost_per_drink') }}</div>
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
                           placeholder="{{ __('bar.recipes.search_recipes') }}"
                           id="recipe-search">
                </div>
            </div>
            
            <div class="filter-group">
                <select class="filter-select" id="status-filter">
                    <option value="">{{ __('bar.recipes.all_status') }}</option>
                    <option value="active">{{ __('bar.recipes.active') }}</option>
                    <option value="inactive">{{ __('bar.recipes.inactive') }}</option>
                </select>
            </div>
            
            <div class="filter-group">
                <select class="filter-select" id="sort-filter">
                    <option value="name">{{ __('bar.recipes.sort_by_name') }}</option>
                    <option value="cost">{{ __('bar.recipes.sort_by_cost') }}</option>
                    <option value="difficulty">{{ __('bar.recipes.sort_by_difficulty') }}</option>
                    <option value="popularity">{{ __('bar.recipes.sort_by_popularity') }}</option>
                    <option value="created">{{ __('bar.recipes.sort_by_created') }}</option>
                </select>
            </div>
            
            <button type="button" class="btn btn-secondary clear-filters-btn">
                {{ __('bar.recipes.clear_filters') }}
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

    <!-- Recipes Content -->
    <div class="recipes-content">
        <!-- Grid View -->
        <div class="recipes-grid" id="recipes-grid">
            <!-- Loading skeleton -->
            <div class="recipe-card loading">
                <div class="recipe-header">
                    <div class="recipe-badges">
                        <div class="skeleton-title"></div>
                    </div>
                </div>
                <div class="recipe-body">
                    <div class="skeleton-title"></div>
                    <div class="skeleton-description"></div>
                    <div class="skeleton-description"></div>
                    <div class="skeleton-meta"></div>
                </div>
                <div class="recipe-actions">
                    <div class="skeleton-cost"></div>
                </div>
            </div>
            <div class="recipe-card loading">
                <div class="recipe-header">
                    <div class="recipe-badges">
                        <div class="skeleton-title"></div>
                    </div>
                </div>
                <div class="recipe-body">
                    <div class="skeleton-title"></div>
                    <div class="skeleton-description"></div>
                    <div class="skeleton-description"></div>
                    <div class="skeleton-meta"></div>
                </div>
                <div class="recipe-actions">
                    <div class="skeleton-cost"></div>
                </div>
            </div>
            <div class="recipe-card loading">
                <div class="recipe-header">
                    <div class="recipe-badges">
                        <div class="skeleton-title"></div>
                    </div>
                </div>
                <div class="recipe-body">
                    <div class="skeleton-title"></div>
                    <div class="skeleton-description"></div>
                    <div class="skeleton-description"></div>
                    <div class="skeleton-meta"></div>
                </div>
                <div class="recipe-actions">
                    <div class="skeleton-cost"></div>
                </div>
            </div>
        </div>

        <!-- List View -->
        <div class="recipes-list" id="recipes-list" style="display: none;">
            <div class="recipes-table-wrapper">
                <table class="recipes-table" role="table">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('bar.recipes.recipe_name') }}</th>
                            <th scope="col">{{ __('bar.recipes.recipe_type') }}</th>
                            <th scope="col">{{ __('bar.recipes.difficulty') }}</th>
                            <th scope="col">{{ __('bar.recipes.glass_type') }}</th>
                            <th scope="col">{{ __('bar.recipes.cost_per_drink') }}</th>
                            <th scope="col">{{ __('bar.recipes.ingredients') }}</th>
                            <th scope="col">{{ __('bar.recipes.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="recipes-table-body">
                        <!-- Loading skeleton rows -->
                        <tr class="loading-row">
                            <td><div class="skeleton-title"></div></td>
                            <td><div class="skeleton-description"></div></td>
                            <td><div class="skeleton-description"></div></td>
                            <td><div class="skeleton-description"></div></td>
                            <td><div class="skeleton-cost"></div></td>
                            <td><div class="skeleton-description"></div></td>
                            <td><div class="skeleton-description"></div></td>
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
            <h3>{{ __('bar.recipes.no_recipes_found') }}</h3>
            <p>{{ __('bar.recipes.no_recipes_description') }}</p>
            <button type="button" class="btn btn-primary add-recipe-btn">
                {{ __('bar.recipes.add_first_recipe') }}
            </button>
        </div>
    </div>
</div>

<!-- Add/Edit Recipe Modal -->
<div class="recipe-modal" id="recipe-modal" style="display: none;" role="dialog" aria-labelledby="recipe-modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content large">
        <div class="modal-header">
            <h2 id="recipe-modal-title" class="modal-title">{{ __('bar.recipes.add_recipe') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('common.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form class="recipe-form" id="recipe-form">
                <div class="form-tabs">
                    <div class="tab-nav">
                        <button type="button" class="tab-btn active" data-tab="basic">
                            {{ __('menu.food_items.basic_info') }}
                        </button>
                        <button type="button" class="tab-btn" data-tab="languages">
                            {{ __('bar.recipes.languages') }}
                        </button>
                        <button type="button" class="tab-btn" data-tab="ingredients">
                            {{ __('bar.recipes.ingredients') }}
                        </button>
                        <button type="button" class="tab-btn" data-tab="pricing">
                            {{ __('menu.food_items.pricing') }}
                        </button>
                    </div>
                    
                    <div class="tab-content">
                        <!-- Basic Info Tab -->
                        <div class="tab-panel active" data-tab="basic">
                            <div class="form-grid grid grid-cols-1 md:grid-cols-2">
                                <!-- Recipe Type -->
                                <div class="form-group">
                                    <label for="recipe-type" class="form-label required">
                                        {{ __('bar.recipes.recipe_type') }}
                                    </label>
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

                                <!-- Difficulty -->
                                <div class="form-group">
                                    <label for="difficulty" class="form-label">
                                        {{ __('bar.recipes.difficulty') }}
                                    </label>
                                    <select id="difficulty" name="difficulty" class="form-select">
                                        <option value="easy" selected>{{ __('bar.recipes.easy') }}</option>
                                        <option value="medium">{{ __('bar.recipes.medium') }}</option>
                                        <option value="hard">{{ __('bar.recipes.hard') }}</option>
                                        <option value="expert">{{ __('bar.recipes.expert') }}</option>
                                    </select>
                                </div>

                                <!-- Glass Type -->
                                <div class="form-group">
                                    <label for="glass-type" class="form-label">
                                        {{ __('bar.recipes.glass_type') }}
                                    </label>
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

                                <!-- Preparation Time -->
                                <div class="form-group">
                                    <label for="preparation-time" class="form-label">
                                        {{ __('bar.recipes.preparation_time') }}
                                    </label>
                                    <div class="input-with-suffix">
                                        <input type="number" id="preparation-time" name="preparation_time" class="form-input" 
                                               min="1" max="60" value="5">
                                        <span class="input-suffix">{{ __('common.minutes') }}</span>
                                    </div>
                                </div>

                                <!-- Serving Size -->
                                <div class="form-group">
                                    <label for="serving-size" class="form-label">
                                        {{ __('bar.recipes.serving_size') }}
                                    </label>
                                    <div class="input-with-suffix">
                                        <input type="number" id="serving-size" name="serving_size" class="form-input" 
                                               min="25" max="1000" value="250">
                                        <span class="input-suffix">ml</span>
                                    </div>
                                </div>

                                <!-- Garnish -->
                                <div class="form-group md:col-span-2">
                                    <label for="garnish" class="form-label">
                                        {{ __('bar.recipes.garnish') }}
                                    </label>
                                    <input type="text" id="garnish" name="garnish" class="form-input"
                                           placeholder="{{ __('bar.recipes.garnish_placeholder') }}">
                                </div>

                                <!-- Status -->
                                <div class="form-group md:col-span-2">
                                    <label class="form-label">{{ __('common.status') }}</label>
                                    <div class="toggle-group">
                                        <label class="toggle-switch">
                                            <input type="checkbox" name="active" value="1" checked>
                                            <span class="toggle-slider"></span>
                                            <span class="toggle-label">{{ __('bar.recipes.active') }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Languages Tab -->
                        <div class="tab-panel" data-tab="languages">
                            <div class="language-tabs">
                                <div class="language-nav">
                                    <button type="button" class="lang-btn active" data-lang="en">
                                        <span class="lang-flag">🇺🇸</span>
                                        {{ __('settings.english') }}
                                    </button>
                                    <button type="button" class="lang-btn" data-lang="am">
                                        <span class="lang-flag">🇪🇹</span>
                                        {{ __('settings.amharic') }}
                                    </button>
                                    <button type="button" class="lang-btn" data-lang="ti">
                                        <span class="lang-flag">🇪🇷</span>
                                        {{ __('settings.tigrinya') }}
                                    </button>
                                </div>
                                
                                <div class="language-content">
                                    <!-- English Fields -->
                                    <div class="lang-panel active" data-lang="en">
                                        <div class="form-grid grid grid-cols-1">
                                            <div class="form-group">
                                                <label for="recipe-name-en" class="form-label required">
                                                    {{ __('bar.recipes.recipe_name') }} ({{ __('settings.english') }})
                                                </label>
                                                <input type="text" id="recipe-name-en" name="name[en]" class="form-input" required
                                                       placeholder="{{ __('bar.recipes.recipe_name_placeholder') }}">
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="description-en" class="form-label">
                                                    {{ __('common.description') }} ({{ __('settings.english') }})
                                                </label>
                                                <textarea id="description-en" name="description[en]" class="form-textarea" rows="3"
                                                          placeholder="{{ __('bar.recipes.description_placeholder') }}"></textarea>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="instructions-en" class="form-label">
                                                    {{ __('bar.recipes.instructions') }} ({{ __('settings.english') }})
                                                </label>
                                                <textarea id="instructions-en" name="instructions[en]" class="form-textarea" rows="4"
                                                          placeholder="{{ __('bar.recipes.instructions_placeholder') }}"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Amharic Fields -->
                                    <div class="lang-panel" data-lang="am">
                                        <div class="form-grid grid grid-cols-1">
                                            <div class="form-group">
                                                <label for="recipe-name-am" class="form-label">
                                                    {{ __('bar.recipes.recipe_name') }} ({{ __('settings.amharic') }})
                                                </label>
                                                <input type="text" id="recipe-name-am" name="name[am]" class="form-input font-ethiopic"
                                                       placeholder="{{ __('bar.recipes.recipe_name_placeholder') }}">
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="description-am" class="form-label">
                                                    {{ __('common.description') }} ({{ __('settings.amharic') }})
                                                </label>
                                                <textarea id="description-am" name="description[am]" class="form-textarea font-ethiopic" rows="3"
                                                          placeholder="{{ __('bar.recipes.description_placeholder') }}"></textarea>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="instructions-am" class="form-label">
                                                    {{ __('bar.recipes.instructions') }} ({{ __('settings.amharic') }})
                                                </label>
                                                <textarea id="instructions-am" name="instructions[am]" class="form-textarea font-ethiopic" rows="4"
                                                          placeholder="{{ __('bar.recipes.instructions_placeholder') }}"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tigrinya Fields -->
                                    <div class="lang-panel" data-lang="ti">
                                        <div class="form-grid grid grid-cols-1">
                                            <div class="form-group">
                                                <label for="recipe-name-ti" class="form-label">
                                                    {{ __('bar.recipes.recipe_name') }} ({{ __('settings.tigrinya') }})
                                                </label>
                                                <input type="text" id="recipe-name-ti" name="name[ti]" class="form-input font-ethiopic"
                                                       placeholder="{{ __('bar.recipes.recipe_name_placeholder') }}">
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="description-ti" class="form-label">
                                                    {{ __('common.description') }} ({{ __('settings.tigrinya') }})
                                                </label>
                                                <textarea id="description-ti" name="description[ti]" class="form-textarea font-ethiopic" rows="3"
                                                          placeholder="{{ __('bar.recipes.description_placeholder') }}"></textarea>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="instructions-ti" class="form-label">
                                                    {{ __('bar.recipes.instructions') }} ({{ __('settings.tigrinya') }})
                                                </label>
                                                <textarea id="instructions-ti" name="instructions[ti]" class="form-textarea font-ethiopic" rows="4"
                                                          placeholder="{{ __('bar.recipes.instructions_placeholder') }}"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ingredients Tab -->
                        <div class="tab-panel" data-tab="ingredients">
                            <div class="ingredients-section">
                                <div class="section-header">
                                    <h4>{{ __('bar.recipes.recipe_ingredients') }}</h4>
                                    <button type="button" class="btn btn-sm btn-secondary add-ingredient-btn">
                                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        {{ __('bar.recipes.add_ingredient') }}
                                    </button>
                                </div>
                                
                                <div class="ingredients-list" id="ingredients-list">
                                    <!-- Ingredient items will be added here dynamically -->
                                    <div class="ingredient-item">
                                        <div class="ingredient-select-wrapper">
                                            <select class="form-select ingredient-select" name="ingredients[0][beverage_id]">
                                                <option value="">{{ __('bar.recipes.select_beverage') }}</option>
                                                <option value="1">Gin</option>
                                                <option value="2">Vodka</option>
                                                <option value="3">Whiskey</option>
                                                <option value="4">Rum</option>
                                                <option value="5">Tequila</option>
                                                <option value="6">Vermouth</option>
                                                <option value="7">Triple Sec</option>
                                                <option value="8">Simple Syrup</option>
                                                <option value="9">Lime Juice</option>
                                                <option value="10">Lemon Juice</option>
                                            </select>
                                        </div>
                                        <div class="quantity-input-wrapper">
                                            <input type="number" class="form-input quantity-input" 
                                                   name="ingredients[0][quantity]" 
                                                   placeholder="0" min="0" step="0.01">
                                        </div>
                                        <div class="unit-select-wrapper">
                                            <select class="form-select unit-select" name="ingredients[0][unit]">
                                                <option value="ml">ml</option>
                                                <option value="oz">oz</option>
                                                <option value="dash">dash</option>
                                                <option value="splash">splash</option>
                                                <option value="drops">drops</option>
                                                <option value="piece">piece</option>
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
                                <!-- Cost per Drink -->
                                <div class="form-group">
                                    <label for="cost-per-drink" class="form-label">
                                        {{ __('bar.recipes.cost_per_drink') }}
                                    </label>
                                    <div class="input-with-prefix">
                                        <span class="input-prefix">$</span>
                                        <input type="number" id="cost-per-drink" name="cost_per_drink" class="form-input" 
                                               min="0" step="0.01" placeholder="0.00">
                                    </div>
                                    <small class="form-hint">{{ __('bar.recipes.cost_hint') }}</small>
                                </div>

                                <!-- Selling Price -->
                                <div class="form-group">
                                    <label for="selling-price" class="form-label">
                                        {{ __('bar.recipes.selling_price') }}
                                    </label>
                                    <div class="input-with-prefix">
                                        <span class="input-prefix">$</span>
                                        <input type="number" id="selling-price" name="selling_price" class="form-input" 
                                               min="0" step="0.01" placeholder="0.00">
                                    </div>
                                </div>

                                <!-- Margin Display -->
                                <div class="form-group md:col-span-2">
                                    <div class="margin-display">
                                        <div class="margin-item">
                                            <span class="margin-label">{{ __('bar.recipes.profit_margin') }}</span>
                                            <span class="margin-value" id="profit-margin">$0.00 (0%)</span>
                                        </div>
                                        <div class="margin-item">
                                            <span class="margin-label">{{ __('bar.recipes.markup') }}</span>
                                            <span class="margin-value" id="markup">0%</span>
                                        </div>
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
                {{ __('common.cancel') }}
            </button>
            <button type="submit" form="recipe-form" class="btn btn-primary save-btn">
                {{ __('bar.recipes.save_recipe') }}
            </button>
        </div>
    </div>
</div>
@endsection