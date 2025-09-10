@extends('layouts.admin')

@section('title', __('inventory.movements.title') . ' - ' . config('app.name'))
@section('page_title', __('inventory.movements.title'))

@push('styles')
@vite(['resources/css/admin/inventory-movements.css'])
@endpush

@section('content')
<div class="movements-container" x-data="movementsPage()">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">{{ __('inventory.movements.title') }}</h1>
            <p class="page-subtitle">{{ __('inventory.movements.subtitle') }}</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary" @click="openAddMovementDrawer()">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                {{ __('inventory.movements.add_movement') }}
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card">
            <div class="card-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div class="card-content">
                <div class="card-value">{{ number_format($totalMovements) }}</div>
                <div class="card-label">{{ __('inventory.movements.total_movements') }}</div>
            </div>
        </div>

        <div class="summary-card">
            <div class="card-icon today">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="card-content">
                <div class="card-value">{{ number_format($movementsToday) }}</div>
                <div class="card-label">{{ __('inventory.movements.movements_today') }}</div>
            </div>
        </div>

        <div class="summary-card">
            <div class="card-icon week">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="card-content">
                <div class="card-value">{{ number_format($movementsThisWeek) }}</div>
                <div class="card-label">{{ __('inventory.movements.movements_this_week') }}</div>
            </div>
        </div>
    </div>

    <!-- Filters Panel -->
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
                       placeholder="{{ __('inventory.movements.search_items') }}" 
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
                <label>{{ __('inventory.movements.filter_by_type') }}</label>
                <select onchange="applyFilters()">
                    <option value="all">{{ __('inventory.movements.all_types') }}</option>
                    @foreach($movementTypes as $type)
                        <option value="{{ $type }}" {{ request('movement_type') === $type ? 'selected' : '' }}>
                            {{ __('inventory.movements.movement_types.' . $type) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label>{{ __('inventory.movements.filter_by_location') }}</label>
                <select onchange="applyFilters()">
                    <option value="all">{{ __('inventory.movements.all_locations') }}</option>
                    @foreach($locations as $location)
                        <option value="{{ $location }}" {{ request('location') === $location ? 'selected' : '' }}>
                            {{ __('inventory.movements.locations.' . $location) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label>{{ __('inventory.movements.filter_by_staff') }}</label>
                <select onchange="applyFilters()">
                    <option value="all">{{ __('inventory.movements.all_staff') }}</option>
                    @foreach($staffMembers as $staff)
                        <option value="{{ $staff }}" {{ request('staff') === $staff ? 'selected' : '' }}>
                            {{ $staff }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label>{{ __('inventory.movements.filter_by_date') }}</label>
                <div class="date-range">
                    <input type="date" 
                           placeholder="{{ __('inventory.movements.date_from') }}"
                           value="{{ request('date_from') }}"
                           onchange="applyFilters()">
                    <input type="date" 
                           placeholder="{{ __('inventory.movements.date_to') }}"
                           value="{{ request('date_to') }}"
                           onchange="applyFilters()">
                </div>
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

    <!-- Movements Table -->
    <div class="card table-card">
        <div class="card-header">
            <h3 class="card-title">{{ __('inventory.movements.recent_movements') }}</h3>
            <div class="table-info">
                <span class="info-text">{{ __('inventory.movements.click_row_details') }}</span>
            </div>
        </div>
        <div class="card-body">
            @if(count($movements->data) > 0)
                <div class="table-responsive">
                    <table class="table movements-table">
                        <thead>
                            <tr>
                                <th>{{ __('inventory.movements.item') }}</th>
                                <th>{{ __('inventory.movements.quantity') }}</th>
                                <th>{{ __('inventory.movements.movement_type') }}</th>
                                <th>{{ __('inventory.movements.location') }}</th>
                                <th>{{ __('inventory.movements.staff') }}</th>
                                <th>{{ __('inventory.movements.date_time') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($movements->data as $movement)
                                <tr class="table-row clickable-row" 
                                    data-movement-id="{{ $movement->id }}"
                                    @click="openMovementDetails({{ $movement->id }})">
                                    <td class="item-cell">
                                        <div class="item-info">
                                            <div class="item-name">{{ $movement->item_name }}</div>
                                            <div class="item-code">{{ $movement->item_code }}</div>
                                        </div>
                                    </td>
                                    <td class="quantity-cell">
                                        <div class="quantity-info">
                                            <div class="quantity-amount">{{ number_format($movement->quantity, 1) }} {{ $movement->unit }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="movement-badge {{ $movement->movement_badge_class }}">
                                            {{ __('inventory.movements.movement_types.' . $movement->movement_type) }}
                                        </span>
                                    </td>
                                    <td class="location-cell">
                                        <div class="location-info">{{ $movement->location_display }}</div>
                                    </td>
                                    <td class="staff-cell">
                                        <div class="staff-info">
                                            <div class="staff-name">{{ $movement->staff_name }}</div>
                                            <div class="staff-role">{{ $movement->staff_role }}</div>
                                        </div>
                                    </td>
                                    <td class="date-cell">
                                        <div class="date-info">
                                            <div class="date-time">{{ $movement->formatted_date }}</div>
                                            <div class="time-ago">{{ $movement->time_ago }}</div>
                                        </div>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <h3 class="empty-state-title">{{ __('inventory.movements.no_movements') }}</h3>
                    <p class="empty-state-description">{{ __('inventory.movements.no_movements_message') }}</p>
                    <button @click="openAddMovementDrawer()" class="btn btn-primary">
                        {{ __('inventory.movements.add_movement') }}
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Movement Detail Drawer -->
    <div x-show="showMovementDrawer" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="drawer-overlay"
         @click="closeMovementDrawer()">
        <div class="drawer-content" @click.stop>
            <div class="drawer-header">
                <h3 class="drawer-title">{{ __('inventory.movements.movement_drawer_title') }}</h3>
                <button @click="closeMovementDrawer()" class="drawer-close">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="drawer-body" x-show="selectedMovement">
                <!-- Movement details will be loaded here -->
                <div class="movement-detail-loading" x-show="loadingMovement">
                    <div class="loading-spinner"></div>
                    <p>{{ __('inventory.movements.loading_movements') }}</p>
                </div>
                
                <div x-show="!loadingMovement && selectedMovement" class="movement-details">
                    <!-- Content will be populated by JavaScript -->
                </div>
            </div>
            <div class="drawer-footer" x-show="selectedMovement && !loadingMovement">
                <button @click="editMovement()" class="btn btn-secondary">
                    {{ __('inventory.movements.edit') }}
                </button>
                <button @click="deleteMovement()" class="btn btn-danger">
                    {{ __('inventory.movements.delete') }}
                </button>
                <button @click="closeMovementDrawer()" class="btn btn-primary">
                    {{ __('inventory.movements.close') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Add/Edit Movement Drawer -->
    <div x-show="showAddMovementDrawer" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="drawer-overlay"
         @click="closeAddMovementDrawer()">
        <div class="drawer-content" @click.stop>
            <div class="drawer-header">
                <h3 class="drawer-title" x-text="editingMovement ? '{{ __('inventory.movements.edit_movement_title') }}' : '{{ __('inventory.movements.add_movement_title') }}'"></h3>
                <button @click="closeAddMovementDrawer()" class="drawer-close">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="drawer-body">
                <form @submit.prevent="saveMovement()" class="movement-form">
                    <!-- Form fields will be added in the next part -->
                    <div class="form-group">
                        <label>{{ __('inventory.movements.select_item') }}</label>
                        <select x-model="movementForm.item_id" required>
                            <option value="">{{ __('inventory.movements.select_item') }}</option>
                            <option value="1">Ethiopian Coffee Beans (COFFEE001)</option>
                            <option value="2">Berbere Spice Mix (SPICE002)</option>
                            <option value="3">Injera Flour (FLOUR001)</option>
                            <option value="4">Teff Grain (GRAIN001)</option>
                            <option value="5">Red Lentils (LENTIL001)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>{{ __('inventory.movements.select_movement_type') }}</label>
                        <select x-model="movementForm.movement_type" required>
                            <option value="">{{ __('inventory.movements.select_movement_type') }}</option>
                            @foreach($movementTypes as $type)
                                <option value="{{ $type }}">{{ __('inventory.movements.movement_types.' . $type) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>{{ __('inventory.movements.enter_quantity') }}</label>
                        <input type="number" 
                               x-model="movementForm.quantity" 
                               step="0.1" 
                               min="0.1" 
                               placeholder="{{ __('inventory.movements.enter_quantity') }}" 
                               required>
                    </div>

                    <div class="form-group" x-show="movementForm.movement_type === 'transfer'">
                        <label>{{ __('inventory.movements.select_from_location') }}</label>
                        <select x-model="movementForm.from_location">
                            <option value="">{{ __('inventory.movements.select_from_location') }}</option>
                            @foreach($locations as $location)
                                <option value="{{ $location }}">{{ __('inventory.movements.locations.' . $location) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label x-text="movementForm.movement_type === 'transfer' ? '{{ __('inventory.movements.select_to_location') }}' : '{{ __('inventory.movements.select_location') }}'"></label>
                        <select x-model="movementForm.to_location" required>
                            <option value="" x-text="movementForm.movement_type === 'transfer' ? '{{ __('inventory.movements.select_to_location') }}' : '{{ __('inventory.movements.select_location') }}'"></option>
                            @foreach($locations as $location)
                                <option value="{{ $location }}">{{ __('inventory.movements.locations.' . $location) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>{{ __('inventory.movements.add_notes') }}</label>
                        <textarea x-model="movementForm.notes" 
                                  rows="3" 
                                  placeholder="{{ __('inventory.movements.add_notes') }}"></textarea>
                    </div>
                </form>
            </div>
            <div class="drawer-footer">
                <button @click="closeAddMovementDrawer()" type="button" class="btn btn-secondary">
                    {{ __('inventory.movements.cancel') }}
                </button>
                <button @click="saveMovement()" type="button" class="btn btn-primary">
                    {{ __('inventory.movements.save_movement') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/admin/inventory-movements.js'])
@endpush
