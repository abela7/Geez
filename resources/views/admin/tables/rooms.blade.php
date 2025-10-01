@extends('layouts.admin')

@section('title', __('tables.rooms.title') . ' - ' . config('app.name'))
@section('page_title', __('tables.rooms.title'))

@push('styles')
    {{-- CSS styles will be loaded via main layout --}}
@endpush

@push('scripts')
    @vite('resources/js/admin/tables/rooms-management.js')
@endpush

@section('content')
<div class="rooms-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('tables.rooms.title') }}</h1>
                <p class="page-subtitle">{{ __('tables.rooms.subtitle') }}</p>
            </div>
            <div class="page-actions">
                <button type="button" class="btn btn-secondary export-rooms-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ __('common.export') }}
                </button>
                <button type="button" class="btn btn-primary add-room-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('tables.rooms.add_room') }}
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="total-rooms">0</div>
                    <div class="stat-label">{{ __('tables.total_items') }}</div>
                </div>
            </div>
            
            <div class="stat-card active">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="active-rooms">0</div>
                    <div class="stat-label">{{ __('tables.active_items') }}</div>
                </div>
            </div>
            
            <div class="stat-card capacity">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="total-capacity">0</div>
                    <div class="stat-label">{{ __('tables.capacity') }}</div>
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
                       placeholder="{{ __('tables.search_placeholder') }}"
                       id="rooms-search">
            </div>
        </div>
        
        <div class="filters-container">
            <select class="filter-select" id="type-filter">
                <option value="">{{ __('tables.filter_by') }} {{ __('tables.type') }}</option>
                <option value="main_dining">{{ __('tables.rooms.main_dining') }}</option>
                <option value="private_dining">{{ __('tables.rooms.private_dining') }}</option>
                <option value="bar_area">{{ __('tables.rooms.bar_area') }}</option>
                <option value="outdoor_patio">{{ __('tables.rooms.outdoor_patio') }}</option>
                <option value="vip_section">{{ __('tables.rooms.vip_section') }}</option>
                <option value="banquet_hall">{{ __('tables.rooms.banquet_hall') }}</option>
                <option value="terrace">{{ __('tables.rooms.terrace') }}</option>
                <option value="lounge">{{ __('tables.rooms.lounge') }}</option>
            </select>
            
            <select class="filter-select" id="status-filter">
                <option value="">{{ __('tables.filter_by') }} {{ __('tables.status') }}</option>
                <option value="active">{{ __('tables.rooms.active') }}</option>
                <option value="inactive">{{ __('tables.rooms.inactive') }}</option>
                <option value="maintenance">{{ __('tables.rooms.maintenance') }}</option>
            </select>
            
            <button type="button" class="btn btn-secondary clear-filters-btn">
                {{ __('common.clear_filters') }}
            </button>
        </div>
    </div>

    <!-- Rooms Grid -->
    <div class="rooms-content">
        <div class="rooms-grid" id="rooms-grid">
            <!-- Rooms will be populated by JavaScript -->
        </div>
    </div>
</div>

<!-- Add/Edit Room Modal -->
<div class="room-modal" id="room-modal" style="display: none;" role="dialog" aria-labelledby="room-modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="room-modal-title" class="modal-title">{{ __('tables.rooms.add_room') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('tables.rooms.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form id="room-form" class="room-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="room-name" class="form-label required">{{ __('tables.rooms.room_name') }}</label>
                        <input type="text" id="room-name" name="room_name" class="form-input" required
                               placeholder="{{ __('tables.rooms.room_name_placeholder') }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="room-code" class="form-label required">{{ __('tables.rooms.room_code') }}</label>
                        <input type="text" id="room-code" name="room_code" class="form-input" required
                               placeholder="{{ __('tables.rooms.room_code_placeholder') }}" maxlength="5">
                    </div>
                    
                    <div class="form-group">
                        <label for="room-type" class="form-label required">{{ __('tables.rooms.room_type') }}</label>
                        <select id="room-type" name="room_type" class="form-select" required>
                            <option value="">{{ __('common.select') }}...</option>
                            <option value="main_dining">{{ __('tables.rooms.main_dining') }}</option>
                            <option value="private_dining">{{ __('tables.rooms.private_dining') }}</option>
                            <option value="bar_area">{{ __('tables.rooms.bar_area') }}</option>
                            <option value="outdoor_patio">{{ __('tables.rooms.outdoor_patio') }}</option>
                            <option value="vip_section">{{ __('tables.rooms.vip_section') }}</option>
                            <option value="banquet_hall">{{ __('tables.rooms.banquet_hall') }}</option>
                            <option value="terrace">{{ __('tables.rooms.terrace') }}</option>
                            <option value="lounge">{{ __('tables.rooms.lounge') }}</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="room-capacity" class="form-label required">{{ __('tables.rooms.capacity') }}</label>
                        <input type="number" id="room-capacity" name="capacity" class="form-input" required
                               min="1" max="500" placeholder="60">
                    </div>
                    
                    <div class="form-group">
                        <label for="room-status" class="form-label">{{ __('tables.rooms.status') }}</label>
                        <select id="room-status" name="status" class="form-select">
                            <option value="active" selected>{{ __('tables.rooms.active') }}</option>
                            <option value="inactive">{{ __('tables.rooms.inactive') }}</option>
                            <option value="maintenance">{{ __('tables.rooms.maintenance') }}</option>
                        </select>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="room-description" class="form-label">{{ __('tables.rooms.description') }}</label>
                        <textarea id="room-description" name="description" class="form-textarea" rows="3"
                                  placeholder="{{ __('tables.rooms.room_description_placeholder') }}"></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary cancel-room-btn">
                {{ __('tables.rooms.cancel') }}
            </button>
            <button type="submit" form="room-form" class="btn btn-primary save-room-btn">
                {{ __('tables.rooms.save_room') }}
            </button>
        </div>
    </div>
</div>
@endsection
