@extends('layouts.admin')

@section('title', 'Shift Management')

@section('content')
<div class="shifts-manage-page">
    <!-- Simple Header -->
    <div class="simple-header">
        <a href="{{ route('admin.shifts.manage.create') }}" class="btn btn-primary">
            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create New Shift
        </a>
    </div>

    <!-- Stats Grid -->
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

    <!-- Filters Section -->
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
            <a href="{{ route('admin.shifts.manage.index') }}" class="btn btn-ghost-sm">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Reset All
            </a>
        </div>

        <form method="GET" action="{{ route('admin.shifts.manage.index') }}">
            <div class="filters-grid-modern">
                <div class="filter-modern">
                    <label for="search" class="filter-label-modern">
                        <svg class="label-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Search Shifts
                    </label>
                    <input type="text" id="search" name="search" value="{{ request('search', '') }}" class="filter-input-modern" placeholder="Search by name, description...">
                </div>

                <div class="filter-modern">
                    <label for="department" class="filter-label-modern">
                        <svg class="label-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Department
                    </label>
                    <select id="department" name="department" class="filter-select-modern">
                        <option value="all" {{ request('department', 'all') == 'all' ? 'selected' : '' }}>All Departments</option>
                        @foreach(\App\Models\Department::active()->ordered()->get() as $dept)
                        <option value="{{ $dept->name }}" {{ request('department') == $dept->name ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-modern">
                    <label for="status" class="filter-label-modern">
                        <svg class="label-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Status
                    </label>
                    <select id="status" name="status" class="filter-select-modern">
                        <option value="all" {{ request('status', 'all') == 'all' ? 'selected' : '' }}>All Statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="filter-modern">
                    <label for="type" class="filter-label-modern">
                        <svg class="label-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Shift Type
                    </label>
                    <select id="type" name="type" class="filter-select-modern">
                        <option value="all" {{ request('type', 'all') == 'all' ? 'selected' : '' }}>All Types</option>
                        @foreach(\App\Models\ShiftType::active()->ordered()->get() as $type)
                        <option value="{{ $type->slug }}" {{ request('type') == $type->slug ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-modern">
                    <button type="submit" class="btn btn-primary">
                        Apply Filters
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Shifts Table -->
    <div class="shifts-table-modern">
        <div class="table-header-modern">
            <h3 class="table-title">All Shifts</h3>
            <div class="table-info">
                <span class="showing-count">Showing {{ $shifts->firstItem() }} - {{ $shifts->lastItem() }} of {{ $shifts->total() }} shifts</span>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="table-modern">
                <thead>
                    <tr>
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
                        <td class="td-shift">
                            <div class="shift-info">
                                <div class="shift-name">{{ $shift->name }}</div>
                                @if($shift->description)
                                <div class="shift-desc">{{ Str::limit($shift->description, 60) }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="td-department">
                            <span class="badge badge-department">{{ $shift->department }}</span>
                        </td>
                        <td class="td-time">
                            <div class="time-display">
                                <div class="time-range">{{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}</div>
                                <div class="time-duration">{{ round($shift->getDurationInHours(), 1) }} hours</div>
                            </div>
                        </td>
                        <td class="td-staffing">
                            <div class="staffing-display">
                                <div class="staffing-ratio">
                                    @php
                                        // Count assignments for today only
                                        $todayAssignments = $shift->assignments()
                                            ->where('status', '!=', 'cancelled')
                                            ->whereDate('assigned_date', now()->toDateString())
                                            ->count();
                                        
                                        // If no assignments today, show total upcoming assignments
                                        if ($todayAssignments === 0) {
                                            $upcomingAssignments = $shift->assignments()
                                                ->where('status', '!=', 'cancelled')
                                                ->where('assigned_date', '>=', now()->toDateString())
                                                ->count();
                                            $displayAssigned = $upcomingAssignments > 0 ? $upcomingAssignments : 0;
                                        } else {
                                            $displayAssigned = $todayAssignments;
                                        }
                                    @endphp
                                    <span class="assigned">{{ $displayAssigned }}</span>
                                    <span class="divider">/</span>
                                    <span class="required">{{ $shift->min_staff_required }}</span>
                                </div>
                                <div class="staffing-progress">
                                    @php
                                        $percentage = $shift->min_staff_required > 0 ? ($displayAssigned / $shift->min_staff_required) * 100 : 0;
                                        $statusClass = $percentage >= 90 ? 'success' : ($percentage >= 70 ? 'warning' : 'danger');
                                    @endphp
                                    <div class="progress-bar-mini">
                                        <div class="progress-fill-{{ $statusClass }}" style="width: {{ min($percentage, 100) }}%"></div>
                                    </div>
                                </div>
                                <div class="staffing-info">
                                    @if($todayAssignments > 0)
                                        <small class="text-success">Today: {{ $todayAssignments }}</small>
                                    @else
                                        <small class="text-muted">Upcoming: {{ $shift->assignments()->where('status', '!=', 'cancelled')->where('assigned_date', '>=', now()->toDateString())->count() }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="td-type">
                            <span class="badge badge-type badge-type-{{ $shift->shift_type ?? 'regular' }}">
                                {{ ucfirst($shift->shift_type ?? 'Regular') }}
                            </span>
                        </td>
                        <td class="td-status">
                            <span class="badge badge-status badge-status-{{ $shift->is_active ? 'active' : 'inactive' }}">
                                {{ $shift->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="td-actions">
                            <div class="action-buttons">
                                <a href="{{ route('admin.shifts.manage.edit', $shift->id) }}" class="btn-action btn-action-edit" title="Edit Shift">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.shifts.manage.destroy', $shift->id) }}" class="inline-form" onsubmit="return confirm('Are you sure you want to delete this shift? This will also delete all related staff assignments.')">
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
                        <td colspan="7" class="empty-state">
                            <div class="empty-content">
                                <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <h3>No shifts found</h3>
                                <p>
                                    @if(request()->hasAny(['search', 'department', 'status', 'type']))
                                        No shifts match your current filters. <a href="{{ route('admin.shifts.manage.index') }}">Reset</a>.
                                    @else
                                        Get started by creating your first shift.
                                    @endif
                                </p>
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

    <!-- Pagination -->
    {{ $shifts->links() }}
</div>

@push('styles')
@vite(['resources/css/admin/shifts/manage-modern.css'])
<style>
.staffing-info {
    margin-top: 0.25rem;
}

.staffing-info small {
    font-size: 0.75rem;
    font-weight: 500;
}

.text-success {
    color: #10B981;
}

.text-muted {
    color: #6B7280;
}
</style>
@endpush

@endsection
