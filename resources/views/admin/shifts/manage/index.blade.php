@extends('layouts.admin')

@section('title', __('shifts.manage.title'))

@section('content')
<div class="shifts-manage-page" x-data="shiftsManageData()">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-header-left">
                <h1 class="page-title">{{ __('shifts.manage.title') }}</h1>
                <p class="page-description">{{ __('shifts.manage.description') }}</p>
            </div>
            <div class="page-header-right">
                <div class="header-actions">
                    <button class="btn btn-secondary" @click="showBulkActions = !showBulkActions">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        {{ __('shifts.manage.bulk_actions') }}
                    </button>
                    <a href="{{ route('admin.shifts.manage.create') }}" class="btn btn-primary">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        {{ __('shifts.manage.create_shift') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-section">
        <div class="summary-grid">
            <div class="summary-card summary-card-primary">
                <div class="summary-icon">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="summary-content">
                    <div class="summary-value">{{ $totalShifts }}</div>
                    <div class="summary-label">{{ __('shifts.manage.total_shifts') }}</div>
                </div>
            </div>

            <div class="summary-card summary-card-success">
                <div class="summary-icon">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="summary-content">
                    <div class="summary-value">{{ $activeShifts }}</div>
                    <div class="summary-label">{{ __('shifts.manage.active_shifts') }}</div>
                </div>
            </div>

            <div class="summary-card summary-card-info">
                <div class="summary-icon">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                </div>
                <div class="summary-content">
                    <div class="summary-value">{{ $totalRequiredStaff }}</div>
                    <div class="summary-label">{{ __('shifts.manage.required_staff') }}</div>
                </div>
            </div>

            <div class="summary-card summary-card-{{ $staffingPercentage >= 90 ? 'success' : ($staffingPercentage >= 70 ? 'warning' : 'danger') }}">
                <div class="summary-icon">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="summary-content">
                    <div class="summary-value">{{ $staffingPercentage }}%</div>
                    <div class="summary-label">{{ __('shifts.manage.staffing_level') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Department Breakdown -->
    <div class="department-breakdown-section">
        <div class="section-header">
            <h2 class="section-title">{{ __('shifts.manage.department_breakdown') }}</h2>
        </div>
        <div class="department-grid">
            @foreach($departments as $department)
            <div class="department-card">
                <div class="department-header">
                    <h3 class="department-name">{{ __('shifts.departments.' . strtolower(str_replace(' ', '_', $department['name']))) }}</h3>
                    <span class="department-shifts-count">{{ $department['shifts'] }} {{ __('shifts.common.shifts') }}</span>
                </div>
                <div class="department-stats">
                    <div class="stat-item">
                        <span class="stat-label">{{ __('shifts.manage.required') }}</span>
                        <span class="stat-value">{{ $department['required_staff'] }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">{{ __('shifts.manage.assigned') }}</span>
                        <span class="stat-value">{{ $department['assigned_staff'] }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">{{ __('shifts.manage.coverage') }}</span>
                        <span class="stat-value {{ $department['required_staff'] > 0 && ($department['assigned_staff'] / $department['required_staff']) >= 0.9 ? 'text-success' : (($department['assigned_staff'] / $department['required_staff']) >= 0.7 ? 'text-warning' : 'text-danger') }}">
                            {{ $department['required_staff'] > 0 ? round(($department['assigned_staff'] / $department['required_staff']) * 100) : 0 }}%
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="filters-section">
        <div class="filters-header">
            <h2 class="section-title">{{ __('shifts.manage.all_shifts') }}</h2>
            <div class="filters-actions">
                <button class="btn btn-ghost" @click="resetFilters()">
                    {{ __('shifts.common.reset_filters') }}
                </button>
            </div>
        </div>
        <div class="filters-grid">
            <div class="filter-group">
                <label class="filter-label">{{ __('shifts.common.search') }}</label>
                <input type="text" x-model="searchQuery" @input="applyFilters()" class="filter-input" placeholder="{{ __('shifts.manage.search_placeholder') }}">
            </div>
            <div class="filter-group">
                <label class="filter-label">{{ __('shifts.common.department') }}</label>
                <select x-model="filterDepartment" @change="applyFilters()" class="filter-select">
                    <option value="all">{{ __('shifts.departments.all_departments') }}</option>
                    <option value="Kitchen">{{ __('shifts.departments.kitchen') }}</option>
                    <option value="Front of House">{{ __('shifts.departments.front_of_house') }}</option>
                    <option value="Bar">{{ __('shifts.departments.bar') }}</option>
                    <option value="Management">{{ __('shifts.departments.management') }}</option>
                    <option value="Maintenance">{{ __('shifts.departments.maintenance') }}</option>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">{{ __('shifts.common.status') }}</label>
                <select x-model="filterStatus" @change="applyFilters()" class="filter-select">
                    <option value="all">{{ __('shifts.common.all_statuses') }}</option>
                    <option value="active">{{ __('shifts.statuses.active') }}</option>
                    <option value="draft">{{ __('shifts.statuses.draft') }}</option>
                    <option value="inactive">{{ __('shifts.statuses.inactive') }}</option>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">{{ __('shifts.common.type') }}</label>
                <select x-model="filterType" @change="applyFilters()" class="filter-select">
                    <option value="all">{{ __('shifts.common.all_types') }}</option>
                    <option value="regular">{{ __('shifts.types.regular') }}</option>
                    <option value="weekend">{{ __('shifts.types.weekend') }}</option>
                    <option value="overtime">{{ __('shifts.types.overtime') }}</option>
                    <option value="training">{{ __('shifts.types.training') }}</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Bulk Actions Bar -->
    <div x-show="showBulkActions" x-transition class="bulk-actions-bar">
        <div class="bulk-actions-content">
            <div class="bulk-actions-left">
                <label class="bulk-select-all">
                    <input type="checkbox" x-model="selectAll" @change="toggleSelectAll()">
                    <span>{{ __('shifts.manage.select_all') }}</span>
                </label>
                <span class="selected-count" x-text="`${selectedShifts.length} ${selectedShifts.length === 1 ? '{{ __('shifts.common.shift') }}' : '{{ __('shifts.common.shifts') }}'} {{ __('shifts.manage.selected') }}`"></span>
            </div>
            <div class="bulk-actions-right">
                <button class="btn btn-sm btn-secondary" @click="bulkActivate()" :disabled="selectedShifts.length === 0">
                    {{ __('shifts.manage.activate') }}
                </button>
                <button class="btn btn-sm btn-secondary" @click="bulkDeactivate()" :disabled="selectedShifts.length === 0">
                    {{ __('shifts.manage.deactivate') }}
                </button>
                <button class="btn btn-sm btn-danger" @click="bulkDelete()" :disabled="selectedShifts.length === 0">
                    {{ __('shifts.manage.delete') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Shifts Table -->
    <div class="shifts-table-section">
        <div class="table-container">
            <table class="shifts-table">
                <thead>
                    <tr>
                        <th class="checkbox-column">
                            <input type="checkbox" x-model="selectAll" @change="toggleSelectAll()">
                        </th>
                        <th class="sortable" @click="sortBy('name')">
                            {{ __('shifts.manage.shift_name') }}
                            <svg class="sort-icon" :class="{ 'active': sortField === 'name' }" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"/>
                            </svg>
                        </th>
                        <th class="sortable" @click="sortBy('department')">{{ __('shifts.common.department') }}</th>
                        <th class="sortable" @click="sortBy('start_time')">{{ __('shifts.manage.time') }}</th>
                        <th class="sortable" @click="sortBy('required_staff')">{{ __('shifts.manage.staffing') }}</th>
                        <th class="sortable" @click="sortBy('type')">{{ __('shifts.common.type') }}</th>
                        <th class="sortable" @click="sortBy('status')">{{ __('shifts.common.status') }}</th>
                        <th class="actions-column">{{ __('shifts.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shifts as $shift)
                    <tr class="shift-row" data-shift-id="{{ $shift['id'] }}">
                        <td class="checkbox-column">
                            <input type="checkbox" x-model="selectedShifts" value="{{ $shift['id'] }}">
                        </td>
                        <td class="shift-name-cell">
                            <div class="shift-name-content">
                                <div class="shift-name">{{ $shift['name'] }}</div>
                                <div class="shift-description">{{ Str::limit($shift['description'], 50) }}</div>
                            </div>
                        </td>
                        <td class="department-cell">
                            <span class="department-badge department-{{ strtolower(str_replace(' ', '-', $shift['department'])) }}">
                                {{ __('shifts.departments.' . strtolower(str_replace(' ', '_', $shift['department']))) }}
                            </span>
                        </td>
                        <td class="time-cell">
                            <div class="time-info">
                                <div class="shift-time">{{ $shift['start_time'] }} - {{ $shift['end_time'] }}</div>
                                <div class="shift-duration">{{ $shift['duration_hours'] }}h</div>
                            </div>
                        </td>
                        <td class="staffing-cell">
                            <div class="staffing-info">
                                <div class="staffing-numbers">
                                    <span class="assigned">{{ $shift['assigned_staff'] }}</span>
                                    <span class="separator">/</span>
                                    <span class="required">{{ $shift['required_staff'] }}</span>
                                </div>
                                <div class="staffing-bar">
                                    <div class="staffing-fill" style="width: {{ $shift['required_staff'] > 0 ? ($shift['assigned_staff'] / $shift['required_staff']) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="type-cell">
                            <span class="type-badge type-{{ $shift['type'] }}">
                                {{ __('shifts.types.' . $shift['type']) }}
                            </span>
                        </td>
                        <td class="status-cell">
                            <span class="status-badge status-{{ $shift['status'] }}">
                                {{ __('shifts.statuses.' . $shift['status']) }}
                            </span>
                        </td>
                        <td class="actions-cell">
                            <div class="actions-dropdown" x-data="{ open: false }">
                                <button class="actions-trigger" @click="open = !open">
                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" x-transition class="actions-menu">
                                    <a href="{{ route('admin.shifts.manage.edit', $shift['id']) }}" class="action-item">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        {{ __('shifts.common.edit') }}
                                    </a>
                                    <button class="action-item" @click="duplicateShift({{ $shift['id'] }})">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                        {{ __('shifts.manage.duplicate') }}
                                    </button>
                                    <button class="action-item" @click="toggleShiftStatus({{ $shift['id'] }}, '{{ $shift['status'] }}')">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                        </svg>
                                        {{ $shift['status'] === 'active' ? __('shifts.manage.deactivate') : __('shifts.manage.activate') }}
                                    </button>
                                    <div class="action-separator"></div>
                                    <button class="action-item action-danger" @click="deleteShift({{ $shift['id'] }})">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        {{ __('shifts.common.delete') }}
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="pagination-section">
        <div class="pagination-info">
            {{ __('shifts.manage.showing_results', ['start' => 1, 'end' => count($shifts), 'total' => count($shifts)]) }}
        </div>
        <div class="pagination-controls">
            <button class="btn btn-ghost btn-sm" disabled>
                {{ __('shifts.common.previous') }}
            </button>
            <span class="pagination-current">1</span>
            <button class="btn btn-ghost btn-sm" disabled>
                {{ __('shifts.common.next') }}
            </button>
        </div>
    </div>
</div>

@push('styles')
@vite(['resources/css/admin/shifts/manage.css'])
@endpush

@push('scripts')
@vite(['resources/js/admin/shifts/manage.js'])
@endpush
@endsection
