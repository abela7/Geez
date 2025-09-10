@extends('layouts.admin')

@section('title', __('inventory.locations.title') . ' - ' . config('app.name'))
@section('page_title', __('inventory.locations.title'))

@push('styles')
@vite(['resources/css/admin/inventory-locations.css'])
@endpush

@section('content')
<div class="locations-container" x-data="locationsPage()">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">{{ __('inventory.locations.title') }}</h1>
            <p class="page-subtitle">{{ __('inventory.locations.subtitle') }}</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary" @click="openAddLocationDrawer()">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                {{ __('inventory.locations.add_location') }}
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card">
            <div class="card-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div class="card-content">
                <div class="card-value">{{ number_format($totalLocations) }}</div>
                <div class="card-label">{{ __('inventory.locations.total_locations') }}</div>
            </div>
        </div>

        <div class="summary-card">
            <div class="card-icon active">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="card-content">
                <div class="card-value">{{ number_format($activeLocations) }}</div>
                <div class="card-label">{{ __('inventory.locations.active_locations') }}</div>
            </div>
        </div>

        <div class="summary-card">
            <div class="card-icon capacity">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div class="card-content">
                <div class="card-value">{{ number_format($averageCapacity, 0) }}%</div>
                <div class="card-label">{{ __('inventory.locations.average_capacity') }}</div>
            </div>
        </div>

        <div class="summary-card">
            <div class="card-icon items">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
            </div>
            <div class="card-content">
                <div class="card-value">{{ number_format($totalItemsStored) }}</div>
                <div class="card-label">{{ __('inventory.locations.total_items_stored') }}</div>
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
                       placeholder="{{ __('inventory.locations.search_locations') }}" 
                       value="{{ request('search') }}"
                       onchange="applyLocationFilters()">
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
                <label>{{ __('inventory.locations.filter_by_type') }}</label>
                <select onchange="applyLocationFilters()">
                    <option value="all">{{ __('inventory.locations.all_types') }}</option>
                    @foreach($locationTypes as $type)
                        <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>
                            {{ __('inventory.locations.location_types.' . $type) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label>{{ __('inventory.locations.filter_by_status') }}</label>
                <select onchange="applyLocationFilters()">
                    <option value="all">{{ __('inventory.locations.all_statuses') }}</option>
                    @foreach($locationStatuses as $status)
                        <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                            {{ __('inventory.locations.location_statuses.' . $status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-actions">
                <button onclick="clearLocationFilters()" class="btn btn-secondary">
                    {{ __('common.clear_filters') }}
                </button>
                <button onclick="applyLocationFilters()" class="btn btn-primary">
                    {{ __('common.apply_filters') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Locations Table -->
    <div class="card table-card">
        <div class="card-header">
            <h3 class="card-title">{{ __('inventory.locations.title') }}</h3>
            <div class="table-info">
                <span class="info-text">{{ __('inventory.locations.click_row_details') }}</span>
            </div>
        </div>
        <div class="card-body">
            @if(count($locations->data) > 0)
                <div class="table-responsive">
                    <table class="table locations-table">
                        <thead>
                            <tr>
                                <th>{{ __('inventory.locations.location_name') }}</th>
                                <th>{{ __('inventory.locations.type') }}</th>
                                <th>{{ __('inventory.locations.items_stored') }}</th>
                                <th>{{ __('inventory.locations.capacity') }}</th>
                                <th>{{ __('inventory.locations.status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($locations->data as $location)
                                <tr class="table-row clickable-row" 
                                    data-location-id="{{ $location->id }}"
                                    @click="openLocationDetails({{ $location->id }})">
                                    <td class="location-name-cell">
                                        <div class="location-info">
                                            <div class="location-name">{{ $location->name }}</div>
                                            <div class="location-description">{{ Str::limit($location->description, 50) }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="location-type-badge {{ $location->type_badge_class }}">
                                            {{ __('inventory.locations.location_types.' . $location->type) }}
                                        </span>
                                    </td>
                                    <td class="items-cell">
                                        <div class="items-info">
                                            <div class="items-count">{{ $location->items_count }} {{ __('common.items') }}</div>
                                        </div>
                                    </td>
                                    <td class="capacity-cell">
                                        <div class="capacity-info">
                                            <div class="capacity-bar">
                                                <div class="capacity-fill capacity-{{ $location->capacity_level }}" 
                                                     style="width: {{ $location->capacity_percentage }}%"></div>
                                            </div>
                                            <div class="capacity-text">{{ $location->capacity_percentage }}% {{ __('inventory.locations.capacity_full') }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="location-status-badge {{ $location->status_badge_class }}">
                                            {{ __('inventory.locations.location_statuses.' . $location->status) }}
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="empty-state-title">{{ __('inventory.locations.no_locations') }}</h3>
                    <p class="empty-state-description">{{ __('inventory.locations.no_locations_message') }}</p>
                    <button @click="openAddLocationDrawer()" class="btn btn-primary">
                        {{ __('inventory.locations.add_location') }}
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Location Detail Drawer -->
    <div x-show="showLocationDrawer" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="drawer-overlay"
         @click="closeLocationDrawer()">
        <div class="drawer-content" @click.stop>
            <div class="drawer-header">
                <h3 class="drawer-title">{{ __('inventory.locations.location_drawer_title') }}</h3>
                <button @click="closeLocationDrawer()" class="drawer-close">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="drawer-body" x-show="selectedLocation">
                <!-- Location details will be loaded here -->
                <div class="location-detail-loading" x-show="loadingLocation">
                    <div class="loading-spinner"></div>
                    <p>{{ __('inventory.locations.loading_locations') }}</p>
                </div>
                
                <div x-show="!loadingLocation && selectedLocation" class="location-details">
                    <!-- Content will be populated by JavaScript -->
                </div>
            </div>
            <div class="drawer-footer" x-show="selectedLocation && !loadingLocation">
                <button @click="editLocation()" class="btn btn-secondary">
                    {{ __('inventory.locations.edit') }}
                </button>
                <button @click="toggleLocationStatus()" class="btn btn-warning" x-text="selectedLocation && selectedLocation.status === 'active' ? '{{ __('inventory.locations.deactivate') }}' : '{{ __('inventory.locations.activate') }}'">
                </button>
                <button @click="deleteLocation()" class="btn btn-danger">
                    {{ __('inventory.locations.delete') }}
                </button>
                <button @click="closeLocationDrawer()" class="btn btn-primary">
                    {{ __('inventory.locations.close') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Add/Edit Location Drawer -->
    <div x-show="showAddLocationDrawer" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="drawer-overlay"
         @click="closeAddLocationDrawer()">
        <div class="drawer-content" @click.stop>
            <div class="drawer-header">
                <h3 class="drawer-title" x-text="editingLocation ? '{{ __('inventory.locations.edit_location_title') }}' : '{{ __('inventory.locations.add_location_title') }}'"></h3>
                <button @click="closeAddLocationDrawer()" class="drawer-close">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="drawer-body">
                <form @submit.prevent="saveLocation()" class="location-form">
                    <div class="form-group">
                        <label>{{ __('inventory.locations.location_name_field') }}</label>
                        <input type="text" 
                               x-model="locationForm.name" 
                               placeholder="{{ __('inventory.locations.location_name_field') }}" 
                               required>
                    </div>

                    <div class="form-group">
                        <label>{{ __('inventory.locations.select_type') }}</label>
                        <select x-model="locationForm.type" required>
                            <option value="">{{ __('inventory.locations.select_type') }}</option>
                            @foreach($locationTypes as $type)
                                <option value="{{ $type }}">{{ __('inventory.locations.location_types.' . $type) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>{{ __('inventory.locations.select_status') }}</label>
                        <select x-model="locationForm.status" required>
                            <option value="">{{ __('inventory.locations.select_status') }}</option>
                            @foreach($locationStatuses as $status)
                                <option value="{{ $status }}">{{ __('inventory.locations.location_statuses.' . $status) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>{{ __('inventory.locations.capacity_percentage') }}</label>
                        <input type="number" 
                               x-model="locationForm.capacity_percentage" 
                               min="0" 
                               max="100" 
                               placeholder="0-100"
                               step="1">
                        <small class="form-help">{{ __('inventory.locations.capacity_help') }}</small>
                    </div>

                    <div class="form-group">
                        <label>{{ __('inventory.locations.location_description') }}</label>
                        <textarea x-model="locationForm.description" 
                                  rows="3" 
                                  placeholder="{{ __('inventory.locations.location_description') }}"></textarea>
                        <small class="form-help">{{ __('inventory.locations.description_help') }}</small>
                    </div>
                </form>
            </div>
            <div class="drawer-footer">
                <button @click="closeAddLocationDrawer()" type="button" class="btn btn-secondary">
                    {{ __('inventory.locations.cancel') }}
                </button>
                <button @click="saveLocation()" type="button" class="btn btn-primary">
                    {{ __('inventory.locations.save_location') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/admin/inventory-locations.js'])
@endpush
