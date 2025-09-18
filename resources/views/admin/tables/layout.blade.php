@extends('layouts.admin')

@section('title', __('tables.layout.title') . ' - ' . config('app.name'))
@section('page_title', __('tables.layout.title'))

@push('styles')
    {{-- CSS styles will be loaded via main layout --}}
@endpush

@push('scripts')
    @vite('resources/js/admin/tables/table-layout.js')
@endpush

@section('content')
<div class="layout-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('tables.layout.title') }}</h1>
                <p class="page-subtitle">{{ __('tables.layout.subtitle') }}</p>
            </div>
            <div class="page-actions">
                <button type="button" class="btn btn-secondary export-layout-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ __('tables.layout.export_layout') }}
                </button>
                <button type="button" class="btn btn-primary save-layout-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ __('tables.layout.save_layout') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Layout Designer -->
    <div class="layout-designer">
        <!-- Left Sidebar - Controls -->
        <div class="layout-sidebar">
            <!-- Room Selection -->
            <div class="control-section">
                <h3 class="control-title">{{ __('tables.layout.select_room') }}</h3>
                <select id="room-selector" class="control-select">
                    <option value="">{{ __('tables.layout.select_room') }}...</option>
                    <!-- Rooms will be populated by JavaScript -->
                </select>
            </div>

            <!-- Table Creation -->
            <div class="control-section" id="table-creation-section" style="display: none;">
                <h3 class="control-title">{{ __('tables.layout.add_table') }}</h3>
                
                <div class="form-group">
                    <label for="table-type-selector" class="form-label">{{ __('tables.layout.table_type') }}</label>
                    <select id="table-type-selector" class="control-select">
                        <option value="">{{ __('common.select') }} {{ __('tables.type') }}...</option>
                        <!-- Types will be populated by JavaScript -->
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="table-capacity-selector" class="form-label">{{ __('tables.capacity') }}</label>
                    <select id="table-capacity-selector" class="control-select">
                        <option value="">{{ __('common.select') }} {{ __('tables.capacity') }}...</option>
                        <!-- Capacity options will be populated based on type -->
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="table-name-input" class="form-label">{{ __('tables.layout.table_name') }}</label>
                    <input type="text" id="table-name-input" class="control-input" 
                           placeholder="{{ __('tables.layout.table_name_placeholder') }}">
                </div>
                
                <div class="form-group">
                    <label for="table-number-input" class="form-label required">{{ __('tables.layout.table_number') }}</label>
                    <input type="text" id="table-number-input" class="control-input" required
                           placeholder="{{ __('tables.layout.table_number_placeholder') }}">
                    <div class="table-id-preview">
                        <span class="id-label">{{ __('common.table_id') }}: </span>
                        <span class="id-value" id="table-id-preview">---</span>
                    </div>
                </div>
                
                <button type="button" class="btn btn-primary add-table-to-layout-btn" disabled>
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('tables.layout.add_table') }}
                </button>
            </div>

            <!-- Layout Tools -->
            <div class="control-section" id="layout-tools-section" style="display: none;">
                <h3 class="control-title">{{ __('common.tools') }}</h3>
                
                <div class="tool-buttons">
                    <button type="button" class="tool-btn active" data-tool="select" title="{{ __('tables.layout.select_tool') }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                        </svg>
                        {{ __('tables.layout.select_tool') }}
                    </button>
                    
                    <button type="button" class="tool-btn" data-tool="move" title="{{ __('tables.layout.move_tool') }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l4-4m0 0l4 4m-4-4v12m6-8a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ __('tables.layout.move_tool') }}
                    </button>
                </div>
                
                <div class="layout-options">
                    <div class="option-group">
                        <label class="option-label">
                            <input type="checkbox" id="show-grid" class="option-checkbox" checked>
                            {{ __('tables.layout.show_grid') }}
                        </label>
                    </div>
                    
                    <div class="option-group">
                        <label class="option-label">
                            <input type="checkbox" id="show-numbers" class="option-checkbox" checked>
                            {{ __('tables.layout.show_numbers') }}
                        </label>
                    </div>
                    
                    <div class="option-group">
                        <label class="option-label">
                            <input type="checkbox" id="show-capacity" class="option-checkbox">
                            {{ __('tables.layout.show_capacity') }}
                        </label>
                    </div>
                    
                    <div class="option-group">
                        <label class="option-label">
                            <input type="checkbox" id="grid-snap" class="option-checkbox" checked>
                            {{ __('tables.layout.grid_snap') }}
                        </label>
                    </div>
                </div>
            </div>

            <!-- Table Properties (when table selected) -->
            <div class="control-section" id="table-properties-section" style="display: none;">
                <h3 class="control-title">{{ __('common.table_properties') }}</h3>
                
                <div class="property-group">
                    <label class="property-label">{{ __('tables.layout.table_number') }}</label>
                    <input type="text" id="selected-table-number" class="property-input">
                </div>
                
                <div class="property-group">
                    <label class="property-label">{{ __('tables.layout.table_name') }}</label>
                    <input type="text" id="selected-table-name" class="property-input">
                </div>
                
                <div class="property-group">
                    <label class="property-label">{{ __('tables.capacity') }}</label>
                    <input type="number" id="selected-table-capacity" class="property-input" min="1" max="20">
                </div>
                
                <div class="property-group">
                    <label class="property-label">{{ __('tables.types.width') }} (cm)</label>
                    <input type="number" id="selected-table-width" class="property-input" min="50" max="300" step="5">
                </div>
                
                <div class="property-group">
                    <label class="property-label">{{ __('tables.types.height') }} (cm)</label>
                    <input type="number" id="selected-table-height" class="property-input" min="50" max="300" step="5">
                </div>
                
                <div class="property-group">
                    <label class="property-label">{{ __('tables.layout.table_rotation') }} (°)</label>
                    <input type="range" id="selected-table-rotation" class="property-range" min="0" max="360" step="15" value="0">
                    <span class="rotation-value" id="rotation-value">0°</span>
                </div>
                
                <div class="property-actions">
                    <button type="button" class="btn btn-sm btn-secondary update-table-btn">
                        {{ __('common.update') }}
                    </button>
                    <button type="button" class="btn btn-sm btn-danger delete-table-btn">
                        {{ __('common.delete') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Canvas Area -->
        <div class="layout-canvas-container">
            <!-- Canvas Toolbar -->
            <div class="canvas-toolbar">
                <div class="toolbar-left">
                    <div class="room-info" id="current-room-info" style="display: none;">
                        <span class="room-name" id="current-room-name">---</span>
                        <span class="room-capacity">{{ __('tables.capacity') }}: <span id="current-room-capacity">0</span></span>
                    </div>
                </div>
                
                <div class="toolbar-center">
                    <div class="zoom-controls">
                        <button type="button" class="zoom-btn" id="zoom-out-btn" title="{{ __('tables.layout.zoom_out') }}">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"/>
                            </svg>
                        </button>
                        <span class="zoom-level" id="zoom-level">100%</span>
                        <button type="button" class="zoom-btn" id="zoom-in-btn" title="{{ __('tables.layout.zoom_in') }}">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                            </svg>
                        </button>
                        <button type="button" class="zoom-btn" id="zoom-fit-btn" title="{{ __('tables.layout.zoom_fit') }}">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div class="toolbar-right">
                    <div class="table-count">
                        {{ __('common.tables') }}: <span id="table-count">0</span>
                    </div>
                </div>
            </div>

            <!-- Canvas Area -->
            <div class="canvas-area" id="canvas-area">
                <div class="canvas-wrapper" id="canvas-wrapper">
                    <div class="room-canvas" id="room-canvas">
                        <!-- Room layout will be rendered here -->
                        <div class="canvas-grid" id="canvas-grid"></div>
                        <div class="canvas-tables" id="canvas-tables">
                            <!-- Tables will be positioned here -->
                        </div>
                        <div class="selection-overlay" id="selection-overlay" style="display: none;"></div>
                    </div>
                </div>
                
                <!-- Empty State -->
                <div class="canvas-empty-state" id="canvas-empty-state">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <h3>{{ __('tables.layout.select_room_first') }}</h3>
                    <p>{{ __('tables.layout.layout_instructions') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Layout Panel (slides up on mobile) -->
    <div class="mobile-layout-panel" id="mobile-layout-panel">
        <div class="mobile-panel-header">
            <h3 class="mobile-panel-title">{{ __('common.table_layout') }}</h3>
            <button type="button" class="mobile-panel-toggle" id="mobile-panel-toggle">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                </svg>
            </button>
        </div>
        <div class="mobile-panel-content">
            <div class="mobile-room-selector">
                <select id="mobile-room-selector" class="mobile-select">
                    <option value="">{{ __('tables.layout.select_room') }}...</option>
                    <!-- Rooms will be populated by JavaScript -->
                </select>
            </div>
            <div class="mobile-table-list" id="mobile-table-list">
                <!-- Tables will be listed here for mobile interaction -->
            </div>
        </div>
    </div>
</div>

<!-- Add Table Modal -->
<div class="add-table-modal" id="add-table-modal" style="display: none;" role="dialog" aria-labelledby="add-table-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="add-table-title" class="modal-title">{{ __('tables.layout.add_table') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('tables.layout.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form id="add-table-form" class="add-table-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="modal-table-type" class="form-label required">{{ __('tables.layout.table_type') }}</label>
                        <select id="modal-table-type" name="table_type" class="form-select" required>
                            <option value="">{{ __('common.select') }} {{ __('tables.type') }}...</option>
                            <!-- Types will be populated by JavaScript -->
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="modal-table-capacity" class="form-label required">{{ __('tables.capacity') }}</label>
                        <select id="modal-table-capacity" name="capacity" class="form-select" required>
                            <option value="">{{ __('common.select') }} {{ __('tables.capacity') }}...</option>
                            <!-- Capacity options will be populated based on type -->
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="modal-table-name" class="form-label">{{ __('tables.layout.table_name') }}</label>
                        <input type="text" id="modal-table-name" name="table_name" class="form-input" 
                               placeholder="{{ __('tables.layout.table_name_placeholder') }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="modal-table-number" class="form-label required">{{ __('tables.layout.table_number') }}</label>
                        <input type="text" id="modal-table-number" name="table_number" class="form-input" required
                               placeholder="{{ __('tables.layout.table_number_placeholder') }}">
                        <div class="table-id-preview">
                            <span class="id-label">{{ __('common.table_id') }}: </span>
                            <span class="id-value" id="modal-table-id-preview">---</span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary cancel-add-table-btn">
                {{ __('common.cancel') }}
            </button>
            <button type="submit" form="add-table-form" class="btn btn-primary save-add-table-btn">
                {{ __('tables.layout.add_table') }}
            </button>
        </div>
    </div>
</div>

<!-- Table Context Menu -->
<div class="table-context-menu" id="table-context-menu" style="display: none;">
    <div class="context-menu-item" data-action="edit">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        {{ __('common.edit') }}
    </div>
    <div class="context-menu-item" data-action="duplicate">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
        </svg>
        {{ __('tables.layout.duplicate_table') }}
    </div>
    <div class="context-menu-item danger" data-action="delete">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
        </svg>
        {{ __('common.delete') }}
    </div>
</div>
@endsection
