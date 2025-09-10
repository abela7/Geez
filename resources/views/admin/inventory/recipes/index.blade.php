@extends('layouts.admin')

@section('title', __('inventory.recipes.title') . ' - ' . config('app.name'))
@section('page_title', __('inventory.recipes.title'))

@push('styles')
@vite(['resources/css/admin/inventory-recipes.css'])
@endpush

@section('content')
<div class="recipes-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">{{ __('inventory.recipes.title') }}</h1>
            <p class="page-subtitle">{{ __('inventory.recipes.subtitle') }}</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary" onclick="openRecipeModal()">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                {{ __('inventory.recipes.add_recipe') }}
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card">
            <div class="card-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="card-content">
                <div class="card-value">{{ number_format($totalRecipes) }}</div>
                <div class="card-label">{{ __('common.total') }} {{ __('inventory.recipes.title') }}</div>
            </div>
        </div>

        <div class="summary-card">
            <div class="card-icon active">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="card-content">
                <div class="card-value">{{ number_format($activeRecipes) }}</div>
                <div class="card-label">{{ __('inventory.recipes.statuses.active') }} {{ __('inventory.recipes.title') }}</div>
            </div>
        </div>

        <div class="summary-card">
            <div class="card-icon draft">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
            </div>
            <div class="card-content">
                <div class="card-value">{{ number_format($draftRecipes) }}</div>
                <div class="card-label">{{ __('inventory.recipes.statuses.draft') }} {{ __('inventory.recipes.title') }}</div>
            </div>
        </div>

        <div class="summary-card">
            <div class="card-icon cost">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                </svg>
            </div>
            <div class="card-content">
                <div class="card-value">${{ number_format($avgCostPerServing, 2) }}</div>
                <div class="card-label">{{ __('common.average') }} {{ __('inventory.recipes.cost_per_serving') }}</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-section" x-data="{ showFilters: false }">
        <div class="filters-header">
            <button @click="showFilters = !showFilters" class="filters-toggle">
                <svg class="filter-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"/>
                </svg>
                {{ __('common.filters') }}
                <svg class="chevron" :class="{ 'rotate-180': showFilters }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div class="search-box">
                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" 
                       placeholder="{{ __('inventory.recipes.search_recipes') }}" 
                       value="{{ request('search') }}"
                       onchange="applyFilters()">
            </div>
        </div>

        <div x-show="showFilters" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 max-h-0"
             x-transition:enter-end="opacity-100 max-h-40"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 max-h-40"
             x-transition:leave-end="opacity-0 max-h-0"
             class="filters-content">
            <div class="filter-group">
                <label>{{ __('inventory.recipes.filter_by_category') }}</label>
                <select onchange="applyFilters()">
                    <option value="all">{{ __('inventory.recipes.all_categories') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                            {{ __('inventory.recipes.categories.' . $category) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label>{{ __('inventory.recipes.filter_by_difficulty') }}</label>
                <select onchange="applyFilters()">
                    <option value="all">{{ __('inventory.recipes.all_difficulties') }}</option>
                    @foreach($difficulties as $difficulty)
                        <option value="{{ $difficulty }}" {{ request('difficulty') === $difficulty ? 'selected' : '' }}>
                            {{ __('inventory.recipes.difficulty_levels.' . $difficulty) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label>{{ __('inventory.recipes.filter_by_status') }}</label>
                <select onchange="applyFilters()">
                    <option value="all">{{ __('inventory.recipes.all_statuses') }}</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                            {{ __('inventory.recipes.statuses.' . $status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-actions">
                <button onclick="clearFilters()" class="btn btn-secondary">
                    {{ __('common.clear_filters') }}
                </button>
                <button onclick="applyFilters()" class="btn btn-primary">
                    {{ __('common.apply_filters') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Recipes Table -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="recipes-table">
                <thead>
                    <tr>
                        <th>{{ __('inventory.recipes.recipe_name') }}</th>
                        <th>{{ __('inventory.recipes.category') }}</th>
                        <th>{{ __('inventory.recipes.difficulty') }}</th>
                        <th>{{ __('inventory.recipes.serving_size') }}</th>
                        <th>{{ __('inventory.recipes.total_time') }}</th>
                        <th>{{ __('inventory.recipes.cost_per_serving') }}</th>
                        <th>{{ __('inventory.recipes.status') }}</th>
                        <th>{{ __('inventory.recipes.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recipes as $recipe)
                        <tr>
                            <td class="recipe-name-cell">
                                <div class="recipe-name">{{ $recipe->name }}</div>
                                <div class="recipe-code">{{ $recipe->code }}</div>
                            </td>
                            <td>
                                <span class="category-badge category-{{ $recipe->category }}">
                                    {{ __('inventory.recipes.categories.' . $recipe->category) }}
                                </span>
                            </td>
                            <td>
                                <span class="difficulty-badge {{ $recipe->difficulty_badge_class }}">
                                    {{ __('inventory.recipes.difficulty_levels.' . $recipe->difficulty) }}
                                </span>
                            </td>
                            <td class="serving-cell">{{ $recipe->serving_size }} {{ __('common.servings') }}</td>
                            <td class="time-cell">{{ $recipe->formatted_total_time }}</td>
                            <td class="cost-cell">
                                @if($recipe->cost_per_serving)
                                    ${{ number_format($recipe->cost_per_serving, 2) }}
                                @else
                                    <span class="text-muted">{{ __('common.not_calculated') }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge {{ $recipe->status_badge_class }}">
                                    {{ __('inventory.recipes.statuses.' . $recipe->status) }}
                                </span>
                            </td>
                            <td class="actions-cell">
                                <button class="action-btn view" onclick="viewRecipe({{ $recipe->id }})" title="{{ __('inventory.recipes.view_recipe') }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                                <button class="action-btn edit" onclick="editRecipe({{ $recipe->id }})" title="{{ __('inventory.recipes.edit_recipe') }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button class="action-btn duplicate" onclick="duplicateRecipe({{ $recipe->id }})" title="{{ __('inventory.recipes.duplicate_recipe') }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </button>
                                <button class="action-btn delete" onclick="deleteRecipe({{ $recipe->id }})" title="{{ __('inventory.recipes.delete_recipe') }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-state">
                                <div class="empty-state-content">
                                    <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <h3>{{ __('inventory.recipes.no_recipes') }}</h3>
                                    <p>{{ __('inventory.recipes.no_recipes_message') }}</p>
                                    <button onclick="openRecipeModal()" class="btn btn-primary">
                                        {{ __('inventory.recipes.add_recipe') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($recipes->hasPages())
            <div class="pagination-wrapper">
                {{ $recipes->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/admin/inventory-recipes.js'])
@endpush
