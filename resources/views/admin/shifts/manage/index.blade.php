@extends('layouts.admin')

@section('title', 'Shift Management')

@section('content')
<div class="shifts-manage-page" x-data="{ showBulkActions: false, selectAll: false, selectedShifts: [], searchQuery: '', filterDepartment: 'all', filterStatus: 'all', filterType: 'all', sortField: 'name', sortDirection: 'asc' }">
    <!-- Modern Page Header -->
    <div class="page-header-modern">
        <div class="header-content">
            <div class="header-text">
                <h1 class="header-title">
                    <svg class="title-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Shift Management
                </h1>
                <p class="header-description">Create, manage, and organize work shifts for your team</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-outline" @click="showBulkActions = !showBulkActions" x-show="selectedShifts.length > 0">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span x-text="'Bulk Actions (' + selectedShifts.length + ')'"></span>
                </button>
                <a href="{{ route('admin.shifts.manage.create') }}" class="btn btn-primary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create New Shift
                </a>
            </div>
        </div>
    </div>

    <!-- Modern Stats Grid -->
    <div class="stats-grid-modern">
        <div class="stat-card stat-primary">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-label">Total Shifts</div>
                <div class="stat-value">{{ number_format($totalShifts) }}</div>
                <div class="stat-trend">
                    <span class="trend-text">All configured shifts</span>
                </div>
            </div>
        </div>

        <div class="stat-card stat-success">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-label">Active Shifts</div>
                <div class="stat-value">{{ number_format($activeShifts) }}</div>
                <div class="stat-trend">
                    <span class="trend-text">Currently operational</span>
                </div>
            </div>
        </div>

        <div class="stat-card stat-info">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-label">Staff Required</div>
                <div class="stat-value">{{ number_format($totalRequiredStaff) }}</div>
                <div class="stat-trend">
                    <span class="trend-text">Across all shifts</span>
                </div>
            </div>
        </div>

        <div class="stat-card stat-{{ $staffingPercentage >= 90 ? 'success' : ($staffingPercentage >= 70 ? 'warning' : 'danger') }}">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-label">Staffing Level</div>
                <div class="stat-value">{{ $staffingPercentage }}%</div>
                <div class="stat-trend">
                    <span class="trend-text">{{ $staffingPercentage >= 90 ? 'Excellent coverage' : ($staffingPercentage >= 70 ? 'Good coverage' : 'Needs attention') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Department Overview -->
    @if(count($departments) > 0)
    <div class="department-overview">
        <div class="section-header-modern">
            <h2 class="section-title">
                <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Department Breakdown
            </h2>
        </div>
        <div class="department-grid-modern">
            @foreach($departments as $department)
            <div class="department-card-modern">
                <div class="department-header-modern">
                    <div class="department-info">
                        <h3 class="department-title">{{ $department['name'] }}</h3>
                        <span class="department-badge">{{ number_format($department['shifts']) }} shift{{ $department['shifts'] != 1 ? 's' : '' }}</span>
                    </div>
                </div>
                <div class="department-metrics">
                    <div class="metric-item">
                        <div class="metric-label">Required Staff</div>
                        <div class="metric-value">{{ number_format($department['required_staff']) }}</div>
                    </div>
                    <div class="metric-divider"></div>
                    <div class="metric-item">
                        <div class="metric-label">Assigned Staff</div>
                        <div class="metric-value">{{ number_format($department['assigned_staff']) }}</div>
                    </div>
                    <div class="metric-divider"></div>
                    <div class="metric-item">
                        @php
                            $coverage = $department['required_staff'] > 0 ? round(($department['assigned_staff'] / $department['required_staff']) * 100) : 0;
                            $coverageClass = $coverage >= 90 ? 'success' : ($coverage >= 70 ? 'warning' : 'danger');
                        @endphp
                        <div class="metric-label">Coverage</div>
                        <div class="metric-value coverage-{{ $coverageClass }}">{{ $coverage }}%</div>
                    </div>
                </div>
                <div class="department-progress">
                    <div class="progress-bar">
                        <div class="progress-fill progress-{{ $coverageClass }}" style="width: {{ min($coverage, 100) }}%"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Modern Filters Section -->
    <div class="filters-section-modern">
        <div class="filters-header-modern">
            <div class="filters-title-group">
                <h2 class="filters-title">
                    <svg class="title-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filter & Search Shifts
                </h2>
                <p class="filters-subtitle">Find and organize your shifts</p>
            </div>
            <button class="btn btn-ghost-sm" @click="searchQuery = ''; filterDepartment = 'all'; filterStatus = 'all'; filterType = 'all';">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Reset All
            </button>
        </div>
        
        <div class="filters-grid-modern">
            <!-- Search Input -->
            <div class="filter-modern">
                <label class="filter-label-modern">
                    <svg class="label-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Search Shifts
                </label>
                <input 
                    type="text" 
                    x-model="searchQuery" 
                    class="filter-input-modern" 
                    placeholder="Search by name, description...">
            </div>

            <!-- Department Filter -->
            <div class="filter-modern">
                <label class="filter-label-modern">
                    <svg class="label-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Department
                </label>
                <select x-model="filterDepartment" class="filter-select-modern">
                    <option value="all">All Departments</option>
                    @foreach(\App\Models\Department::active()->ordered()->get() as $dept)
                    <option value="{{ $dept->name }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div class="filter-modern">
                <label class="filter-label-modern">
                    <svg class="label-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Status
                </label>
                <select x-model="filterStatus" class="filter-select-modern">
                    <option value="all">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="draft">Draft</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <!-- Type Filter -->
            <div class="filter-modern">
                <label class="filter-label-modern">
                    <svg class="label-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Shift Type
                </label>
                <select x-model="filterType" class="filter-select-modern">
                    <option value="all">All Types</option>
                    @foreach(\App\Models\ShiftType::active()->ordered()->get() as $type)
                    <option value="{{ $type->slug }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Bulk Actions Bar -->
    <div x-show="showBulkActions" x-transition class="bulk-actions-bar-modern">
        <div class="bulk-actions-wrapper">
            <div class="bulk-actions-left">
                <div class="bulk-checkbox">
                    <input type="checkbox" x-model="selectAll" @change="toggleSelectAll()" id="selectAllBulk">
                    <label for="selectAllBulk">Select All</label>
                </div>
                <div class="selection-count">
                    <span class="count-badge" x-text="selectedShifts.length"></span>
                    <span class="count-text" x-text="selectedShifts.length === 1 ? 'shift selected' : 'shifts selected'"></span>
                </div>
            </div>
            <div class="bulk-actions-right">
                <button class="btn-bulk btn-bulk-activate" @click="bulkActivate()" :disabled="selectedShifts.length === 0">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Activate
                </button>
                <button class="btn-bulk btn-bulk-deactivate" @click="bulkDeactivate()" :disabled="selectedShifts.length === 0">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                    Deactivate
                </button>
                <button class="btn-bulk btn-bulk-delete" @click="bulkDelete()" :disabled="selectedShifts.length === 0">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete
                </button>
            </div>
        </div>
    </div>

    <!-- Modern Shifts Table -->
    <div class="shifts-table-modern">
        <div class="table-header-modern">
            <h3 class="table-title">All Shifts</h3>
            <div class="table-info">
                <span class="showing-count">Showing {{ count($shifts) }} shift{{ count($shifts) != 1 ? 's' : '' }}</span>
            </div>
        </div>
        
        <div class="table-wrapper">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th class="th-checkbox">
                            <input type="checkbox" x-model="selectAll" @change="toggleSelectAll()">
                        </th>
                        <th class="th-shift">Shift Details</th>
                        <th class="th-department">Department</th>
                        <th class="th-time">Time & Duration</th>
                        <th class="th-staffing">Staffing</th>
                        <th class="th-type">Type</th>
                        <th class="th-status">Status</th>
                        <th class="th-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shifts as $shift)
                    <tr class="table-row">
                        <td class="td-checkbox">
                            <input type="checkbox" x-model="selectedShifts" value="{{ $shift['id'] }}">
                        </td>
                        <td class="td-shift">
                            <div class="shift-info">
                                <div class="shift-name">{{ $shift['name'] }}</div>
                                @if($shift['description'])
                                <div class="shift-desc">{{ Str::limit($shift['description'], 60) }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="td-department">
                            <span class="badge badge-department">
                                {{ $shift['department'] }}
                            </span>
                        </td>
                        <td class="td-time">
                            <div class="time-display">
                                <div class="time-range">{{ $shift['start_time'] }} - {{ $shift['end_time'] }}</div>
                                <div class="time-duration">{{ $shift['duration_hours'] }} hours</div>
                            </div>
                        </td>
                        <td class="td-staffing">
                            <div class="staffing-display">
                                <div class="staffing-ratio">
                                    <span class="assigned">{{ $shift['assigned_staff'] }}</span>
                                    <span class="divider">/</span>
                                    <span class="required">{{ $shift['required_staff'] }}</span>
                                </div>
                                <div class="staffing-progress">
                                    @php
                                        $percentage = $shift['required_staff'] > 0 ? ($shift['assigned_staff'] / $shift['required_staff']) * 100 : 0;
                                        $statusClass = $percentage >= 90 ? 'success' : ($percentage >= 70 ? 'warning' : 'danger');
                                    @endphp
                                    <div class="progress-bar-mini">
                                        <div class="progress-fill-{{ $statusClass }}" style="width: {{ min($percentage, 100) }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="td-type">
                            <span class="badge badge-type badge-type-{{ $shift['type'] }}">
                                {{ ucfirst($shift['type']) }}
                            </span>
                        </td>
                        <td class="td-status">
                            <span class="badge badge-status badge-status-{{ $shift['status'] }}">
                                {{ ucfirst($shift['status']) }}
                            </span>
                        </td>
                        <td class="td-actions">
                            <div class="action-buttons">
                                <a href="{{ route('admin.shifts.manage.edit', $shift['id']) }}" class="btn-action btn-action-edit" title="Edit Shift">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.shifts.manage.destroy', $shift['id']) }}" class="inline-form" onsubmit="return confirm('Are you sure you want to delete this shift? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-action-delete" title="Delete Shift">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="empty-state">
                            <div class="empty-content">
                                <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <h3>No shifts found</h3>
                                <p>Get started by creating your first shift schedule</p>
                                <a href="{{ route('admin.shifts.manage.create') }}" class="btn btn-primary mt-4">
                                    Create First Shift
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modern Pagination -->
    @if(count($shifts) > 0)
    <div class="pagination-modern">
        <div class="pagination-info">
            <span class="info-text">Showing <strong>{{ count($shifts) }}</strong> shift{{ count($shifts) != 1 ? 's' : '' }}</span>
        </div>
        <div class="pagination-nav">
            <button class="btn-pagination" disabled>
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Previous
            </button>
            <div class="page-number active">1</div>
            <button class="btn-pagination" disabled>
                Next
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
    </div>
    @endif
</div>

@push('styles')
@vite(['resources/css/admin/shifts/manage-modern.css'])
@endpush

<script>
// Placeholder for future Alpine.js functions
function toggleSelectAll() {
    // Logic handled by Alpine.js
}

function bulkActivate() {
    console.log('Bulk activate shifts');
}

function bulkDeactivate() {
    console.log('Bulk deactivate shifts');
}

function bulkDelete() {
    if (confirm('Are you sure you want to delete the selected shifts? This action cannot be undone.')) {
        console.log('Bulk delete shifts');
    }
}
</script>

@endsection
