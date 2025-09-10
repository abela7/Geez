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
    <div class="card table-card">
        <div class="card-header">
            <h3 class="card-title">{{ __('inventory.recipes.title') }}</h3>
            <div class="table-controls">
                <div class="sort-controls">
                    <label for="sort_by" class="sr-only">{{ __('common.sort_by') }}</label>
                    <select id="sort_by" name="sort_by" class="form-select form-select-sm" onchange="updateSort()">
                        <option value="name">{{ __('inventory.recipes.recipe_name') }}</option>
                        <option value="category">{{ __('inventory.recipes.category') }}</option>
                        <option value="difficulty">{{ __('inventory.recipes.difficulty') }}</option>
                        <option value="cost_per_serving">{{ __('inventory.recipes.cost_per_serving') }}</option>
                        <option value="status">{{ __('inventory.recipes.status') }}</option>
                    </select>
                    <button type="button" class="btn btn-link btn-sm" onclick="toggleSortDirection()" 
                            title="Toggle sort direction">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(count($recipes->data) > 0)
                <div class="table-responsive">
                    <table class="table recipes-table">
                        <thead>
                            <tr>
                                <th>{{ __('inventory.recipes.recipe_name') }}</th>
                                <th>{{ __('inventory.recipes.category') }}</th>
                                <th>{{ __('inventory.recipes.difficulty') }}</th>
                                <th>{{ __('inventory.recipes.serving_size') }}</th>
                                <th>{{ __('inventory.recipes.total_time') }}</th>
                                <th>{{ __('inventory.recipes.cost_per_serving') }}</th>
                                <th>{{ __('inventory.recipes.status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recipes->data as $recipe)
                                <tr class="table-row" data-recipe-id="{{ $recipe->id }}">
                                    <td class="recipe-name-cell">
                                        <div class="item-info">
                                            <div class="item-name">{{ $recipe->name }}</div>
                                            <div class="item-code">{{ $recipe->code }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="category-badge category-{{ $recipe->category }}">
                                            {{ __('inventory.recipes.categories.' . $recipe->category) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="difficulty-badge difficulty-{{ $recipe->difficulty }}">
                                            {{ __('inventory.recipes.difficulty_levels.' . $recipe->difficulty) }}
                                        </span>
                                    </td>
                                    <td class="serving-cell">
                                        <div class="serving-info">
                                            <div class="serving-amount">{{ $recipe->serving_size }} {{ __('common.servings') }}</div>
                                        </div>
                                    </td>
                                    <td class="time-cell">{{ $recipe->formatted_total_time }}</td>
                                    <td class="cost-cell">
                                        @if($recipe->cost_per_serving)
                                            <div class="cost-info">
                                                <div class="cost-amount">${{ number_format($recipe->cost_per_serving, 2) }}</div>
                                            </div>
                                        @else
                                            <span class="text-muted">{{ __('common.not_calculated') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="status-badge status-{{ $recipe->status }}">
                                            {{ __('inventory.recipes.statuses.' . $recipe->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="empty-state-title">{{ __('inventory.recipes.no_recipes') }}</h3>
                    <p class="empty-state-description">{{ __('inventory.recipes.no_recipes_message') }}</p>
                    <button onclick="openRecipeModal()" class="btn btn-primary">
                        {{ __('inventory.recipes.add_recipe') }}
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/admin/inventory-recipes.js'])
@endpush
