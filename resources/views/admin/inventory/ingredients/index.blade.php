@extends('layouts.admin')

@section('title', __('inventory.ingredients.title') . ' - ' . config('app.name'))
@section('page_title', __('inventory.ingredients.title'))

@push('styles')
@vite(['resources/css/admin/inventory-ingredients.css'])
@endpush

@section('content')
<div class="ingredients-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">{{ __('inventory.ingredients.title') }}</h1>
            <p class="page-subtitle">{{ __('inventory.ingredients.subtitle') }}</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-secondary" onclick="exportIngredients()">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                {{ __('inventory.ingredients.export_ingredients') }}
            </button>
            <button class="btn btn-primary" onclick="addIngredient()">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                {{ __('inventory.ingredients.add_ingredient') }}
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card">
            <div class="summary-card-header">
                <h3 class="summary-card-title">{{ __('inventory.ingredients.total_ingredients') }}</h3>
                <svg class="summary-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <p class="summary-card-value">{{ $statistics['total_ingredients'] }}</p>
        </div>

        <div class="summary-card">
            <div class="summary-card-header">
                <h3 class="summary-card-title">{{ __('inventory.ingredients.active_ingredients') }}</h3>
                <svg class="summary-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="summary-card-value">{{ $statistics['active_ingredients'] }}</p>
        </div>

        <div class="summary-card">
            <div class="summary-card-header">
                <h3 class="summary-card-title">{{ __('inventory.ingredients.categories_count') }}</h3>
                <svg class="summary-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
            <p class="summary-card-value">{{ $statistics['categories_count'] }}</p>
        </div>

        <div class="summary-card">
            <div class="summary-card-header">
                <h3 class="summary-card-title">{{ __('inventory.ingredients.avg_cost') }}</h3>
                <svg class="summary-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                </svg>
            </div>
            <p class="summary-card-value">${{ $statistics['avg_cost'] }}</p>
        </div>

        <div class="summary-card">
            <div class="summary-card-header">
                <h3 class="summary-card-title">{{ __('inventory.ingredients.suppliers_count') }}</h3>
                <svg class="summary-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                </svg>
            </div>
            <p class="summary-card-value">{{ $statistics['suppliers_count'] }}</p>
        </div>

        <div class="summary-card">
            <div class="summary-card-header">
                <h3 class="summary-card-title">{{ __('inventory.ingredients.allergen_free') }}</h3>
                <svg class="summary-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <p class="summary-card-value">{{ $statistics['allergen_free'] }}</p>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-section" x-data="{ filtersOpen: false }">
        <div class="filters-header">
            <h2 class="filters-title">{{ __('common.filters') }}</h2>
            <button @click="filtersOpen = !filtersOpen" class="filters-toggle">
                <span x-text="filtersOpen ? '{{ __('common.hide') }}' : '{{ __('common.show') }}'"></span>
                <svg class="icon" :class="{ 'rotate-180': filtersOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
        </div>

        <div x-show="filtersOpen" x-transition class="filters-grid">
            <form method="GET" action="{{ route('admin.inventory.ingredients.index') }}" class="filters-form">
                <div class="filter-group">
                    <label class="filter-label">{{ __('common.search') }}</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="{{ __('inventory.ingredients.search_placeholder') }}" 
                           class="filter-input">
                </div>

                <div class="filter-group">
                    <label class="filter-label">{{ __('inventory.ingredients.filter_by_category') }}</label>
                    <select name="category" class="filter-select">
                        <option value="">{{ __('common.all_categories') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                                {{ __('inventory.ingredients.categories.' . $category) ?: ucfirst($category) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">{{ __('inventory.ingredients.filter_by_supplier') }}</label>
                    <select name="supplier_id" class="filter-select">
                        <option value="">{{ __('common.all_suppliers') }}</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">{{ __('inventory.ingredients.filter_by_status') }}</label>
                    <select name="status" class="filter-select">
                        <option value="">{{ __('common.all_statuses') }}</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                            {{ __('inventory.ingredients.statuses.active') }}
                        </option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                            {{ __('inventory.ingredients.statuses.inactive') }}
                        </option>
                        <option value="discontinued" {{ request('status') === 'discontinued' ? 'selected' : '' }}>
                            {{ __('inventory.ingredients.statuses.discontinued') }}
                        </option>
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">{{ __('inventory.ingredients.filter_by_allergens') }}</label>
                    <select name="allergen" class="filter-select">
                        <option value="">{{ __('common.all') }}</option>
                        <option value="allergen_free" {{ request('allergen') === 'allergen_free' ? 'selected' : '' }}>
                            {{ __('inventory.ingredients.allergen_free') }}
                        </option>
                        @foreach(['gluten', 'dairy', 'eggs', 'nuts', 'peanuts', 'soy', 'fish', 'shellfish', 'sesame'] as $allergen)
                            <option value="{{ $allergen }}" {{ request('allergen') === $allergen ? 'selected' : '' }}>
                                {{ __('inventory.ingredients.common_allergens.' . $allergen) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filters-actions">
                    <button type="submit" class="btn btn-primary">
                        {{ __('common.apply_filters') }}
                    </button>
                    <a href="{{ route('admin.inventory.ingredients.index') }}" class="btn btn-secondary">
                        {{ __('common.clear') }}
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Ingredients Table -->
    <div class="table-card">
        <div class="card-header">
            <h3>{{ __('inventory.ingredients.title') }}</h3>
            <div class="sort-controls">
                <label>{{ __('common.sort_by') }}:</label>
                <select onchange="sortIngredients(this.value)" class="filter-select">
                    <option value="name" {{ request('sort_by') === 'name' ? 'selected' : '' }}>
                        {{ __('inventory.ingredients.ingredient_name') }}
                    </option>
                    <option value="category" {{ request('sort_by') === 'category' ? 'selected' : '' }}>
                        {{ __('inventory.ingredients.category') }}
                    </option>
                    <option value="cost_per_unit" {{ request('sort_by') === 'cost_per_unit' ? 'selected' : '' }}>
                        {{ __('inventory.ingredients.cost_per_unit') }}
                    </option>
                    <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>
                        {{ __('common.date_added') }}
                    </option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="ingredients-table">
                <thead>
                    <tr>
                        <th>{{ __('inventory.ingredients.ingredient_name') }}</th>
                        <th>{{ __('inventory.ingredients.category') }}</th>
                        <th>{{ __('inventory.ingredients.unit') }}</th>
                        <th>{{ __('inventory.ingredients.cost_per_unit') }}</th>
                        <th>{{ __('inventory.ingredients.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ingredients as $ingredient)
                        <tr>
                            <td class="ingredient-name-cell">
                                <div class="ingredient-name">{{ $ingredient->name }}</div>
                                <div class="ingredient-code">{{ $ingredient->code }}</div>
                            </td>
                            <td>
                                <span class="category-badge category-{{ $ingredient->category }}">
                                    {{ __('inventory.ingredients.categories.' . $ingredient->category) ?: ucfirst($ingredient->category) }}
                                </span>
                            </td>
                            <td>{{ $ingredient->unit }}</td>
                            <td class="cost-cell">${{ number_format($ingredient->cost_per_unit, 2) }}</td>
                            <td>
                                <span class="status-badge status-{{ $ingredient->status }}">
                                    {{ __('inventory.ingredients.statuses.' . $ingredient->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-state">
                                <div class="empty-state-content">
                                    <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <h3>{{ __('inventory.ingredients.no_ingredients') }}</h3>
                                    <p>{{ __('inventory.ingredients.no_ingredients_message') }}</p>
                                    <button onclick="addIngredient()" class="btn btn-primary">
                                        {{ __('inventory.ingredients.add_ingredient') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($ingredients->hasPages())
            <div class="pagination-wrapper">
                <div class="pagination-info">
                    {{ __('common.showing') }} {{ $ingredients->firstItem() }} - {{ $ingredients->lastItem() }} 
                    {{ __('common.of') }} {{ $ingredients->total() }} {{ __('common.items') }}
                </div>
                <div class="pagination-controls">
                    {{ $ingredients->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- Detail Drawer removed - not needed for simplified table -->
</div>
@endsection

@push('scripts')
@vite(['resources/js/admin/inventory-ingredients.js'])
@endpush
