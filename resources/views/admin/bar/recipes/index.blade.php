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

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="card summary-card">
            <div class="card-body">
                <div class="summary-icon summary-icon-primary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div class="summary-content">
                    <div class="summary-value">{{ number_format($stats['total_recipes'] ?? 0) }}</div>
                    <div class="summary-label">{{ __('bar.recipes.total_recipes') }}</div>
                </div>
            </div>
        </div>

        <div class="card summary-card">
            <div class="card-body">
                <div class="summary-icon summary-icon-info">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                </div>
                <div class="summary-content">
                    <div class="summary-value">{{ number_format($stats['signature_recipes'] ?? 0) }}</div>
                    <div class="summary-label">{{ __('bar.recipes.signature_recipes') }}</div>
                </div>
            </div>
        </div>

        <div class="card summary-card">
            <div class="card-body">
                <div class="summary-icon summary-icon-success">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="summary-content">
                    <div class="summary-value">{{ number_format($stats['popular_recipes'] ?? 0) }}</div>
                    <div class="summary-label">{{ __('bar.recipes.popular_recipes') }}</div>
                </div>
            </div>
        </div>

        <div class="card summary-card">
            <div class="card-body">
                <div class="summary-icon summary-icon-warning">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="summary-content">
                    <div class="summary-value">${{ number_format($stats['avg_cost_per_drink'] ?? 0, 2) }}</div>
                    <div class="summary-label">{{ __('bar.recipes.avg_cost_per_drink') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card filters-card">
        <div class="card-header">
            <h3 class="card-title">{{ __('common.filters') }}</h3>
            <button type="button" class="btn btn-link btn-sm" onclick="clearFilters()">
                {{ __('bar.clear_filters') }}
            </button>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.bar.recipes.index') }}" class="filters-form">
                <div class="filters-grid">
                    <!-- Search -->
                    <div class="filter-group">
                        <label for="search" class="filter-label">{{ __('bar.recipes.search_recipes') }}</label>
                        <div class="search-input-wrapper">
                            <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input type="text" id="search" name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="{{ __('bar.recipes.search_recipes') }}"
                                   class="form-input search-input">
                        </div>
                    </div>

                    <!-- Recipe Type Filter -->
                    <div class="filter-group">
                        <label for="recipe_type" class="filter-label">{{ __('bar.recipes.recipe_type') }}</label>
                        <select id="recipe_type" name="recipe_type" class="form-select">
                            <option value="">{{ __('bar.recipes.all_types') }}</option>
                            <option value="classic_cocktail" {{ request('recipe_type') == 'classic_cocktail' ? 'selected' : '' }}>
                                {{ __('bar.recipes.classic_cocktail') }}
                            </option>
                            <option value="signature_cocktail" {{ request('recipe_type') == 'signature_cocktail' ? 'selected' : '' }}>
                                {{ __('bar.recipes.signature_cocktail') }}
                            </option>
                            <option value="mocktail" {{ request('recipe_type') == 'mocktail' ? 'selected' : '' }}>
                                {{ __('bar.recipes.mocktail') }}
                            </option>
                            <option value="shot" {{ request('recipe_type') == 'shot' ? 'selected' : '' }}>
                                {{ __('bar.recipes.shot') }}
                            </option>
                            <option value="mixed_drink" {{ request('recipe_type') == 'mixed_drink' ? 'selected' : '' }}>
                                {{ __('bar.recipes.mixed_drink') }}
                            </option>
                        </select>
                    </div>

                    <!-- Difficulty Filter -->
                    <div class="filter-group">
                        <label for="difficulty" class="filter-label">{{ __('bar.recipes.difficulty') }}</label>
                        <select id="difficulty" name="difficulty" class="form-select">
                            <option value="">{{ __('bar.recipes.all_difficulties') }}</option>
                            <option value="easy" {{ request('difficulty') == 'easy' ? 'selected' : '' }}>
                                {{ __('bar.recipes.easy') }}
                            </option>
                            <option value="medium" {{ request('difficulty') == 'medium' ? 'selected' : '' }}>
                                {{ __('bar.recipes.medium') }}
                            </option>
                            <option value="hard" {{ request('difficulty') == 'hard' ? 'selected' : '' }}>
                                {{ __('bar.recipes.hard') }}
                            </option>
                            <option value="expert" {{ request('difficulty') == 'expert' ? 'selected' : '' }}>
                                {{ __('bar.recipes.expert') }}
                            </option>
                        </select>
                    </div>

                    <!-- Glass Type Filter -->
                    <div class="filter-group">
                        <label for="glass_type" class="filter-label">{{ __('bar.recipes.glass_type') }}</label>
                        <select id="glass_type" name="glass_type" class="form-select">
                            <option value="">{{ __('bar.recipes.all_glasses') }}</option>
                            <option value="highball" {{ request('glass_type') == 'highball' ? 'selected' : '' }}>
                                {{ __('bar.recipes.highball') }}
                            </option>
                            <option value="lowball" {{ request('glass_type') == 'lowball' ? 'selected' : '' }}>
                                {{ __('bar.recipes.lowball') }}
                            </option>
                            <option value="martini" {{ request('glass_type') == 'martini' ? 'selected' : '' }}>
                                {{ __('bar.recipes.martini') }}
                            </option>
                            <option value="wine_glass" {{ request('glass_type') == 'wine_glass' ? 'selected' : '' }}>
                                {{ __('bar.recipes.wine_glass') }}
                            </option>
                        </select>
                    </div>

                    <!-- Apply Filters Button -->
                    <div class="filter-group filter-actions">
                        <button type="submit" class="btn btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                            </svg>
                            {{ __('common.apply_filters') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions Bar (Hidden by default) -->
    <div class="bulk-actions-bar" id="bulkActionsBar" style="display: none;">
        <div class="bulk-actions-content">
            <span class="bulk-selection-count">
                <span id="selectedCount">0</span> {{ __('common.selected_items') }}
            </span>
            <div class="bulk-actions-buttons">
                <button type="button" class="btn btn-secondary btn-sm" onclick="bulkExportRecipes()">
                    {{ __('bar.export') }}
                </button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="bulkPrintRecipes()">
                    {{ __('bar.recipes.print_recipe') }}
                </button>
                <button type="button" class="btn btn-link btn-sm" onclick="clearSelection()">
                    {{ __('common.clear_selection') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Recipes Grid/Table -->
    <div class="card recipes-card">
        <div class="card-header">
            <h3 class="card-title">{{ __('bar.recipes') }}</h3>
            <div class="view-controls">
                <div class="view-toggle">
                    <button type="button" class="view-btn view-btn--grid active" onclick="switchView('grid')" title="Grid View">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                    </button>
                    <button type="button" class="view-btn view-btn--list" onclick="switchView('list')" title="List View">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
                <div class="sort-controls">
                    <select id="sort_by" name="sort_by" class="form-select form-select-sm" onchange="updateSort()">
                        <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>{{ __('bar.recipes.recipe_name') }}</option>
                        <option value="recipe_type" {{ request('sort_by') == 'recipe_type' ? 'selected' : '' }}>{{ __('bar.recipes.recipe_type') }}</option>
                        <option value="difficulty" {{ request('sort_by') == 'difficulty' ? 'selected' : '' }}>{{ __('bar.recipes.difficulty') }}</option>
                        <option value="cost_per_drink" {{ request('sort_by') == 'cost_per_drink' ? 'selected' : '' }}>{{ __('bar.recipes.cost_per_drink') }}</option>
                        <option value="popularity" {{ request('sort_by') == 'popularity' ? 'selected' : '' }}>{{ __('bar.recipes.popularity') }}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(isset($recipes) && $recipes->count() > 0)
                <!-- Grid View -->
                <div class="recipes-grid" id="recipesGrid">
                    @foreach($recipes as $recipe)
                        <div class="recipe-card" data-recipe-id="{{ $recipe->id }}">
                            <div class="recipe-card-header">
                                <input type="checkbox" class="recipe-checkbox" value="{{ $recipe->id }}" onchange="updateBulkActions()">
                                <div class="recipe-badges">
                                    <span class="difficulty-badge difficulty-{{ $recipe->difficulty }}">
                                        {{ __('bar.recipes.' . $recipe->difficulty) }}
                                    </span>
                                    <span class="type-badge type-{{ $recipe->recipe_type }}">
                                        {{ __('bar.recipes.' . $recipe->recipe_type) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="recipe-content" onclick="showRecipeDetails({{ $recipe->id }})">
                                <h3 class="recipe-name">{{ $recipe->name }}</h3>
                                <p class="recipe-description">{{ Str::limit($recipe->description ?? '', 100) }}</p>
                                
                                <div class="recipe-meta">
                                    <div class="recipe-meta-item">
                                        <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>{{ $recipe->preparation_time ?? '5' }} min</span>
                                    </div>
                                    <div class="recipe-meta-item">
                                        <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                        <span>{{ $recipe->glass_type ? __('bar.recipes.' . $recipe->glass_type) : 'N/A' }}</span>
                                    </div>
                                    <div class="recipe-meta-item">
                                        <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                        <span>${{ number_format($recipe->cost_per_drink ?? 0, 2) }}</span>
                                    </div>
                                </div>
                                
                                @if($recipe->ingredients_count ?? 0 > 0)
                                    <div class="recipe-ingredients-preview">
                                        <span class="ingredients-count">{{ $recipe->ingredients_count }} {{ __('bar.ingredients') }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="recipe-actions">
                                <button type="button" class="btn btn-link btn-sm" onclick="showRecipeDetails({{ $recipe->id }})" title="{{ __('bar.view_details') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                                <button type="button" class="btn btn-link btn-sm" onclick="showEditRecipeModal({{ $recipe->id }})" title="{{ __('bar.edit') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button type="button" class="btn btn-link btn-sm" onclick="printRecipe({{ $recipe->id }})" title="{{ __('bar.recipes.print_recipe') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- List View (Hidden by default) -->
                <div class="recipes-list" id="recipesList" style="display: none;">
                    <div class="table-responsive">
                        <table class="table recipes-table">
                            <thead>
                                <tr>
                                    <th class="table-checkbox">
                                        <input type="checkbox" id="selectAll" class="form-checkbox" onchange="toggleSelectAll()">
                                    </th>
                                    <th>{{ __('bar.recipes.recipe_name') }}</th>
                                    <th>{{ __('bar.recipes.recipe_type') }}</th>
                                    <th>{{ __('bar.recipes.difficulty') }}</th>
                                    <th>{{ __('bar.recipes.glass_type') }}</th>
                                    <th>{{ __('bar.recipes.cost_per_drink') }}</th>
                                    <th>{{ __('bar.ingredients') }}</th>
                                    <th>{{ __('common.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recipes as $recipe)
                                    <tr class="table-row" data-recipe-id="{{ $recipe->id }}">
                                        <td class="table-checkbox">
                                            <input type="checkbox" class="form-checkbox recipe-checkbox" value="{{ $recipe->id }}" onchange="updateBulkActions()">
                                        </td>
                                        <td class="recipe-name-cell">
                                            <div class="recipe-info">
                                                <div class="recipe-name">{{ $recipe->name }}</div>
                                                @if($recipe->description)
                                                    <div class="recipe-description">{{ Str::limit($recipe->description, 60) }}</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="type-badge type-{{ $recipe->recipe_type }}">
                                                {{ __('bar.recipes.' . $recipe->recipe_type) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="difficulty-badge difficulty-{{ $recipe->difficulty }}">
                                                {{ __('bar.recipes.' . $recipe->difficulty) }}
                                            </span>
                                        </td>
                                        <td>{{ $recipe->glass_type ? __('bar.recipes.' . $recipe->glass_type) : 'N/A' }}</td>
                                        <td>${{ number_format($recipe->cost_per_drink ?? 0, 2) }}</td>
                                        <td>{{ $recipe->ingredients_count ?? 0 }}</td>
                                        <td class="actions-cell">
                                            <div class="action-buttons">
                                                <button type="button" class="btn btn-link btn-sm" onclick="showRecipeDetails({{ $recipe->id }})" title="{{ __('bar.view_details') }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </button>
                                                <button type="button" class="btn btn-link btn-sm" onclick="showEditRecipeModal({{ $recipe->id }})" title="{{ __('bar.edit') }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>
                                                <button type="button" class="btn btn-link btn-sm" onclick="printRecipe({{ $recipe->id }})" title="{{ __('bar.recipes.print_recipe') }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if(method_exists($recipes, 'links'))
                    <div class="pagination-wrapper">
                        <div class="pagination-info">
                            {{ __('common.showing') }} 
                            {{ $recipes->firstItem() }} - {{ $recipes->lastItem() }} 
                            {{ __('common.of') }} 
                            {{ $recipes->total() }} 
                            {{ __('bar.recipes') }}
                        </div>
                        {{ $recipes->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <h3 class="empty-state-title">{{ __('bar.recipes.no_recipes_found') }}</h3>
                    <p class="empty-state-description">{{ __('bar.recipes.no_recipes_description') }}</p>
                    <button type="button" class="btn btn-primary" onclick="showAddRecipeModal()">
                        {{ __('bar.recipes.add_recipe') }}
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Recipe Details Drawer -->
<div class="drawer" id="recipeDetailsDrawer">
    <div class="drawer-overlay" onclick="closeDrawer()"></div>
    <div class="drawer-content">
        <div class="drawer-header">
            <h3 class="drawer-title">{{ __('bar.recipes.recipe_details') }}</h3>
            <button type="button" class="drawer-close" onclick="closeDrawer()" aria-label="{{ __('common.close_drawer') }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="drawer-body" id="drawerContent">
            <!-- Content will be loaded dynamically -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/admin/bar-recipes.js'])
@endpush
