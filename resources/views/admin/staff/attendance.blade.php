@extends('layouts.admin')

@section('title', __('staff.attendance.title') . ' - ' . config('app.name'))
@section('page_title', __('staff.attendance.title'))

@section('content')
<div class="attendance-page">

    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('staff.attendance.title') }}</h1>
                <p class="page-subtitle">{{ __('staff.attendance.subtitle') }}</p>
            </div>
            
            <div class="page-actions">
                <button type="button" onclick="showAddModal()" class="btn btn-primary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('staff.attendance.add_attendance') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-section">
        <form method="GET" action="{{ route('admin.staff.attendance.index') }}" id="filterForm" class="filters-form">
            <div class="filters-row">
                <div class="filter-group">
                    <label for="filter-date" class="filter-label">Date</label>
                    <input type="date" 
                           id="filter-date"
                           name="date" 
                           value="{{ $date }}" 
                           class="filter-input"
                           onchange="this.form.submit()">
                </div>
                
                <div class="filter-group">
                    <label for="filter-staff-type" class="filter-label">Staff Type</label>
                    <select id="filter-staff-type" name="staff_type_id" class="filter-select" onchange="this.form.submit()">
                        <option value="">{{ __('staff.all_types') }}</option>
                        @foreach($staffTypes as $type)
                            <option value="{{ $type->id }}" {{ $staffTypeId === $type->id ? 'selected' : '' }}>
                                {{ $type->display_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="filter-status" class="filter-label">Status</label>
                    <select id="filter-status" name="status" class="filter-select" onchange="this.form.submit()">
                        <option value="">{{ __('staff.attendance.all_statuses') }}</option>
                        <option value="present" {{ $status === 'present' ? 'selected' : '' }}>{{ __('staff.attendance.present') }}</option>
                        <option value="absent" {{ $status === 'absent' ? 'selected' : '' }}>{{ __('staff.attendance.absent') }}</option>
                        <option value="late" {{ $status === 'late' ? 'selected' : '' }}>{{ __('staff.attendance.late') }}</option>
                        <option value="overtime" {{ $status === 'overtime' ? 'selected' : '' }}>{{ __('staff.attendance.overtime') }}</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="filter-search" class="filter-label">Search</label>
                    <input type="text" 
                           id="filter-search"
                           name="search" 
                           value="{{ $search }}" 
                           placeholder="{{ __('staff.attendance.search_staff') }}" 
                           class="filter-input">
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn btn-secondary">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('admin.staff.attendance.index') }}" class="btn btn-outline">Clear</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Dashboard Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon stat-icon-success">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value" id="currently-working-count">{{ $todayStats['currently_working'] }}</div>
                <div class="stat-label">{{ __('staff.attendance.currently_working') }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-warning">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value" id="on-break-count">{{ $todayStats['on_break'] }}</div>
                <div class="stat-label">{{ __('staff.attendance.on_break') }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value" id="completed-today-count">{{ $todayStats['completed_today'] }}</div>
                <div class="stat-label">{{ __('staff.attendance.completed_today') }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-danger">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value" id="needs-review-count">{{ $todayStats['needs_review'] }}</div>
                <div class="stat-label">{{ __('staff.attendance.needs_review') }}</div>
            </div>
        </div>
    </div>

    <!-- Attendance Records Table (Full Width Priority) -->
    <div class="records-card">
        <div class="card-header">
            <div>
                <h3 class="card-title">{{ __('staff.attendance.attendance_records') }}</h3>
                <p class="card-subtitle">{{ $attendanceRecords->total() }} {{ __('staff.attendance.records') }} {{ __('found') }}</p>
            </div>
        </div>
        
        <div class="card-body">
            @if($attendanceRecords->count() > 0)
                <div class="table-wrapper">
                    <table class="attendance-table">
                        <thead>
                            <tr>
                                <th>{{ __('staff.attendance.staff_member') }}</th>
                                <th>{{ __('staff.attendance.clock_in') }}</th>
                                <th>{{ __('staff.attendance.clock_out') }}</th>
                                <th>{{ __('staff.attendance.current_state') }}</th>
                                <th>{{ __('staff.attendance.hours_worked') }}</th>
                                <th>{{ __('staff.attendance.net_hours') }}</th>
                                <th>{{ __('staff.attendance.status') }}</th>
                                <th>{{ __('common.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendanceRecords as $record)
                                @if($record->staff) {{-- Only display if staff exists --}}
                                <tr class="attendance-row" data-attendance-id="{{ $record->id }}">
                                    <td>
                                        <div class="staff-cell">
                                            <div class="staff-avatar">
                                                {{ strtoupper(substr($record->staff->first_name, 0, 1) . substr($record->staff->last_name, 0, 1)) }}
                                            </div>
                                            <div class="staff-info">
                                                <div class="staff-name">
                                                    <a href="{{ route('admin.staff.attendance.show', $record->id) }}" class="staff-name-link">
                                                        {{ $record->staff->full_name }}
                                                    </a>
                                                </div>
                                                <div class="staff-type">{{ $record->staff->staffType->display_name ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $record->clock_in->format('H:i') }}</td>
                                    <td>{{ $record->clock_out ? $record->clock_out->format('H:i') : '-' }}</td>
                                    <td>
                                        <div class="state-cell">
                                            <span class="state-badge state-{{ $record->current_state }}">
                                                @if($record->current_state === 'clocked_in')
                                                    <svg class="state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ __('staff.attendance.working') }}
                                                @elseif($record->current_state === 'on_break')
                                                    <svg class="state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ __('staff.attendance.on_break') }}
                                                @elseif($record->current_state === 'clocked_out')
                                                    <svg class="state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    {{ __('staff.attendance.completed') }}
                                                @elseif($record->current_state === 'auto_closed')
                                                    <svg class="state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                                    </svg>
                                                    {{ __('staff.attendance.auto_closed') }}
                                                @endif
                                            </span>
                                            @if($record->is_currently_on_break && $record->current_break_start)
                                                <div class="break-timer" data-start-time="{{ $record->current_break_start->toISOString() }}">
                                                    <small class="break-duration">{{ $record->current_break_start->diffForHumans() }}</small>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $record->hours_worked ? number_format($record->hours_worked, 1) . 'h' : '-' }}</td>
                                    <td>
                                        @if($record->net_hours_worked)
                                            <span class="net-hours">{{ number_format($record->net_hours_worked, 1) }}h</span>
                                            @if($record->total_break_minutes > 0)
                                                <small class="break-info">({{ $record->total_break_minutes }}m breaks)</small>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <span class="status-badge status-{{ $record->status }}">
                                            {{ __('staff.attendance.' . $record->status) }}
                                        </span>
                                        @if($record->review_needed)
                                            <span class="review-flag" title="{{ $record->review_reason }}">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                                </svg>
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            @if($record->current_state === 'clocked_in')
                                                <!-- Working: Can start break or clock out -->
                                                <button type="button" 
                                                        onclick="showStartBreakModal('{{ $record->id }}', '{{ $record->staff->full_name }}')"
                                                        class="btn btn-sm btn-warning"
                                                        title="{{ __('staff.attendance.start_break') }}">
                                                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                </button>
                                                <button type="button" 
                                                        onclick="clockOut('{{ $record->id }}')"
                                                        class="btn btn-sm btn-success"
                                                        title="{{ __('staff.attendance.clock_out') }}">
                                                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                                    </svg>
                                                </button>
                                            @elseif($record->current_state === 'on_break')
                                                <!-- On Break: Can resume work -->
                                                <button type="button" 
                                                        onclick="resumeWork('{{ $record->id }}')"
                                                        class="btn btn-sm btn-primary"
                                                        title="{{ __('staff.attendance.resume_work') }}">
                                                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1M9 16h6m-7 4h8a2 2 0 002-2V6a2 2 0 00-2-2H8a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </button>
                                            @endif
                                            
                                            <!-- View Details -->
                                            <a href="{{ route('admin.staff.attendance.show', $record->id) }}" 
                                               class="btn btn-sm btn-primary"
                                               title="View Details">
                                                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                            
                                            <!-- View intervals -->
                                            <button type="button" 
                                                    onclick="showIntervalsModal('{{ $record->id }}', '{{ $record->staff->full_name }}', '{{ $record->clock_in->format('M d, Y') }}')"
                                                    class="btn btn-sm btn-outline"
                                                    title="{{ __('staff.attendance.view_intervals') }}">
                                                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                                </svg>
                                            </button>
                                            
                                            <!-- Standard edit/delete -->
                                            <button type="button" 
                                                    onclick="showEditModal('{{ $record->id }}', '{{ $record->staff->full_name }}', '{{ $record->clock_in->format('Y-m-d\TH:i') }}', '{{ $record->clock_out ? $record->clock_out->format('Y-m-d\TH:i') : '' }}', '{{ $record->status }}', '{{ $record->notes }}')"
                                                    class="btn btn-sm btn-secondary"
                                                    title="{{ __('common.edit') }}">
                                                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>
                                            <button type="button" 
                                                    onclick="deleteAttendance('{{ $record->id }}', '{{ $record->staff->full_name }}')"
                                                    class="btn btn-sm btn-danger"
                                                    title="{{ __('common.delete') }}">
                                                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endif {{-- End if staff exists --}}
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="table-pagination">
                    {{ $attendanceRecords->links() }}
                </div>
            @else
                <div class="empty-state">
                    <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <div class="empty-state-title">{{ __('staff.attendance.no_records') }}</div>
                    <div class="empty-state-text">{{ __('staff.attendance.no_records_description') }}</div>
                </div>
            @endif
        </div>
    </div>

    <!-- Enhanced Info Cards Grid -->
    <div class="secondary-cards-grid">
        <!-- Currently Active Staff -->
        <div class="sidebar-card">
            <div class="sidebar-card-header">
                <h3 class="sidebar-card-title">{{ __('staff.attendance.currently_active') }}</h3>
                <span class="sidebar-card-badge">{{ $currentlyClocked->count() }} {{ __('staff.attendance.active') }}</span>
            </div>
            <div class="sidebar-card-body">
                @if($currentlyClocked->count() > 0)
                    <div class="active-staff-list">
                        @foreach($currentlyClocked as $record)
                            @if($record->staff) {{-- Only display if staff exists --}}
                            <div class="active-staff-item" data-attendance-id="{{ $record->id }}">
                                <div class="active-staff-info">
                                    <div class="staff-indicator state-{{ $record->current_state }}">
                                        <div class="staff-dot"></div>
                                    </div>
                                    <div class="staff-details">
                                        <div class="staff-name">{{ $record->staff->full_name }}</div>
                                        <div class="staff-role">{{ $record->staff->staffType->display_name ?? 'N/A' }}</div>
                                        <div class="staff-state">
                                            @if($record->current_state === 'clocked_in')
                                                <span class="state-working">{{ __('staff.attendance.working') }}</span>
                                            @elseif($record->current_state === 'on_break')
                                                <span class="state-break">{{ __('staff.attendance.on_break') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="active-staff-time">
                                    <div class="clock-in-time">{{ $record->clock_in->format('H:i') }}</div>
                                    <div class="duration" data-start-time="{{ $record->clock_in->toISOString() }}">
                                        @php
                                            $workingMinutes = abs($record->clock_in->diffInMinutes(now()));
                                            $workingHours = floor($workingMinutes / 60);
                                            $remainingMinutes = $workingMinutes % 60;
                                            
                                            if ($record->clock_in->isFuture()) {
                                                // Clock-in time is in the future
                                                if ($workingHours > 0) {
                                                    echo $workingHours . 'h ' . $remainingMinutes . 'm until start';
                                                } else {
                                                    echo $remainingMinutes . ' minutes until start';
                                                }
                                            } else {
                                                // Normal case - clock-in time is in the past
                                                if ($workingHours > 0) {
                                                    echo $workingHours . 'h ' . $remainingMinutes . 'm working';
                                                } else {
                                                    echo $remainingMinutes . ' minutes working';
                                                }
                                            }
                                        @endphp
                                    </div>
                                    @if($record->is_currently_on_break && $record->current_break_start)
                                        <div class="break-duration">
                                            <small>Break: {{ $record->current_break_start->diffForHumans() }}</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endif {{-- End if staff exists --}}
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="empty-state-text">{{ __('staff.attendance.no_active_sessions') }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Staff On Break -->
        <div class="sidebar-card">
            <div class="sidebar-card-header">
                <h3 class="sidebar-card-title">{{ __('staff.attendance.staff_on_break') }}</h3>
                <span class="sidebar-card-badge">{{ $staffOnBreak->count() }}</span>
            </div>
            <div class="sidebar-card-body">
                @if($staffOnBreak->count() > 0)
                    <div class="break-staff-list">
                        @foreach($staffOnBreak as $record)
                            @if($record->staff) {{-- Only display if staff exists --}}
                            <div class="break-staff-item">
                                <div class="break-staff-info">
                                    <div class="break-indicator">
                                        <div class="break-dot"></div>
                                    </div>
                                    <div class="staff-details">
                                        <div class="staff-name">{{ $record->staff->full_name }}</div>
                                        <div class="staff-role">{{ $record->staff->staffType->display_name ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="break-time-info">
                                    <div class="break-start">{{ $record->current_break_start->format('H:i') }}</div>
                                    <div class="break-duration" data-start-time="{{ $record->current_break_start->toISOString() }}">
                                        {{ $record->current_break_start->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                            @endif {{-- End if staff exists --}}
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="empty-state-text">{{ __('staff.attendance.no_staff_on_break') }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Needs Review -->
        <div class="sidebar-card">
            <div class="sidebar-card-header">
                <h3 class="sidebar-card-title">{{ __('staff.attendance.needs_review') }}</h3>
                <span class="sidebar-card-badge sidebar-card-badge-warning">{{ $needsReview->count() }}</span>
            </div>
            <div class="sidebar-card-body">
                @if($needsReview->count() > 0)
                    <div class="review-list">
                        @foreach($needsReview->take(5) as $record)
                            @if($record->staff) {{-- Only display if staff exists --}}
                            <div class="review-item">
                                <div class="review-info">
                                    <div class="review-icon">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                    </div>
                                    <div class="review-details">
                                        <div class="staff-name">{{ $record->staff->full_name }}</div>
                                        <div class="review-reason">{{ $record->review_reason }}</div>
                                        <div class="review-time">{{ $record->clock_in->format('M j, H:i') }}</div>
                                    </div>
                                </div>
                                <button type="button" 
                                        onclick="reviewAttendance('{{ $record->id }}')"
                                        class="btn btn-sm btn-warning">
                                    {{ __('staff.attendance.review') }}
                                </button>
                            </div>
                            @endif {{-- End if staff exists --}}
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="empty-state-text">{{ __('staff.attendance.no_reviews_needed') }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('modals')
<!-- Add Attendance Modal -->
<div id="addAttendanceModal" class="modal-overlay hidden">
    <div class="modal-container modal-modern">
        <div class="modal-header-modern">
            <div class="modal-header-content-modern">
                <div class="modal-icon-modern">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
                <div class="modal-title-section-modern">
                    <h3 class="modal-title-modern">{{ __('staff.attendance.add_attendance') }}</h3>
                    <p class="modal-subtitle-modern">{{ __('staff.attendance.add_attendance_description') }}</p>
                </div>
            </div>
            <button type="button" onclick="hideAddModal()" class="modal-close-modern">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form method="POST" action="{{ route('admin.staff.attendance.store') }}" id="addAttendanceForm">
            @csrf
            <div class="modal-body-modern">
                <!-- Form Grid Layout -->
                <div class="form-grid-modern">
                    <!-- Staff Information Card -->
                    <div class="form-card-modern">
                        <div class="form-card-header-modern">
                            <div class="form-card-icon-modern">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div class="form-card-title-modern">
                                <h4>{{ __('staff.attendance.staff_information') }}</h4>
                                <p>{{ __('staff.attendance.select_staff_description') }}</p>
                            </div>
                        </div>
                        <div class="form-card-content-modern">
                            <div class="form-field-modern">
                                <label for="staff_id" class="form-label-modern">
                                    {{ __('staff.attendance.select_staff') }} <span class="required-modern">*</span>
                                </label>
                                <div class="form-input-container-modern">
                                    <select name="staff_id" id="staff_id" class="form-select-modern" required>
                                        <option value="">{{ __('staff.attendance.choose_staff') }}</option>
                                        @foreach($allStaff as $staff)
                                            <option value="{{ $staff->id }}" data-staff-type="{{ $staff->staffType->display_name ?? 'N/A' }}">
                                                {{ $staff->full_name }} - {{ $staff->staffType->display_name ?? 'N/A' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-input-icon-modern">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shift Information Card -->
                    <div class="form-card-modern">
                        <div class="form-card-header-modern">
                            <div class="form-card-icon-modern">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="form-card-title-modern">
                                <h4>{{ __('staff.attendance.shift_information') }}</h4>
                                <p>{{ __('staff.attendance.shift_description') }}</p>
                            </div>
                        </div>
                        <div class="form-card-content-modern">
                            <div class="form-field-modern">
                                <label for="shift_template_id" class="form-label-modern">
                                    {{ __('staff.attendance.shift_template') }} <span class="optional-modern">({{ __('common.optional') }})</span>
                                </label>
                                <div class="form-input-container-modern">
                                    <select name="shift_template_id" id="shift_template_id" class="form-select-modern">
                                        <option value="">{{ __('staff.attendance.no_shift_template') }}</option>
                                        @foreach($activeShifts as $shift)
                                            <option value="{{ $shift->id }}" 
                                                    data-shift-name="{{ $shift->name }}"
                                                    data-start-time="{{ $shift->start_time }}"
                                                    data-end-time="{{ $shift->end_time }}"
                                                    data-department="{{ $shift->department }}">
                                                {{ $shift->name }} 
                                                ({{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }})
                                                @if($shift->department)
                                                    - {{ $shift->department }}
                                                @endif
                                            </option>
                                        @endforeach
                                        @if($activeShifts->count() === 0)
                                            <option disabled>{{ __('staff.attendance.no_shift_templates') }}</option>
                                        @endif
                                    </select>
                                    <div class="form-input-icon-modern">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="form-help-modern">
                                    {{ __('staff.attendance.shift_template_help') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Time Information Card -->
                    <div class="form-card-modern form-card-wide">
                        <div class="form-card-header-modern">
                            <div class="form-card-icon-modern">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="form-card-title-modern">
                                <h4>{{ __('staff.attendance.time_information') }}</h4>
                                <p>{{ __('staff.attendance.time_description') }}</p>
                            </div>
                        </div>
                        <div class="form-card-content-modern">
                            <div class="form-row-modern">
                                <div class="form-field-modern">
                                    <label for="clockIn" class="form-label-modern">
                                        {{ __('staff.attendance.clock_in_time') }} <span class="required-modern">*</span>
                                    </label>
                                    <div class="form-input-container-modern">
                                        <input type="datetime-local" name="clock_in" id="clockIn" class="form-input-modern" required>
                                        <div class="form-input-icon-modern">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-field-modern">
                                    <label for="clockOut" class="form-label-modern">
                                        {{ __('staff.attendance.clock_out_time') }}
                                    </label>
                                    <div class="form-input-container-modern">
                                        <input type="datetime-local" name="clock_out" id="clockOut" class="form-input-modern">
                                        <div class="form-input-icon-modern">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Information Card -->
                    <div class="form-card-modern">
                        <div class="form-card-header-modern">
                            <div class="form-card-icon-modern">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="form-card-title-modern">
                                <h4>{{ __('staff.attendance.status_information') }}</h4>
                                <p>{{ __('staff.attendance.status_description') }}</p>
                            </div>
                        </div>
                        <div class="form-card-content-modern">
                            <div class="form-field-modern">
                                <label for="status" class="form-label-modern">
                                    {{ __('staff.attendance.status') }} <span class="required-modern">*</span>
                                </label>
                                <div class="form-input-container-modern">
                                    <select name="status" id="status" class="form-select-modern" required>
                                        <option value="present" data-status-color="success">{{ __('staff.attendance.present') }}</option>
                                        <option value="absent" data-status-color="danger">{{ __('staff.attendance.absent') }}</option>
                                        <option value="late" data-status-color="warning">{{ __('staff.attendance.late') }}</option>
                                        <option value="overtime" data-status-color="info">{{ __('staff.attendance.overtime') }}</option>
                                        <option value="early_leave" data-status-color="secondary">{{ __('staff.attendance.early_leave') }}</option>
                                    </select>
                                    <div class="form-input-icon-modern">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information Card -->
                    <div class="form-card-modern">
                        <div class="form-card-header-modern">
                            <div class="form-card-icon-modern">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            <div class="form-card-title-modern">
                                <h4>{{ __('staff.attendance.additional_information') }}</h4>
                                <p>{{ __('staff.attendance.notes_description') }}</p>
                            </div>
                        </div>
                        <div class="form-card-content-modern">
                            <div class="form-field-modern">
                                <label for="notes" class="form-label-modern">
                                    {{ __('staff.attendance.notes') }}
                                </label>
                                <div class="form-textarea-container-modern">
                                    <textarea name="notes" id="notes" class="form-textarea-modern" rows="4" placeholder="{{ __('staff.attendance.notes_placeholder') }}" maxlength="500"></textarea>
                                    <div class="form-textarea-footer-modern">
                                        <div class="form-textarea-counter-modern">
                                            <span id="notesCounter">0</span> / 500 {{ __('common.characters') }}
                                        </div>
                                        <button type="button" onclick="clearNotes()" class="form-textarea-clear-modern">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Clear
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer-modern">
                <div class="modal-footer-content-modern">
                    <div class="modal-footer-info-modern">
                        <div class="modal-footer-icon-modern">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="modal-footer-text-modern">
                            <div class="modal-footer-title-modern">{{ __('common.required_fields') }}</div>
                            <div class="modal-footer-subtitle-modern">{{ __('common.fill_required_fields') }}</div>
                        </div>
                    </div>
                    <div class="modal-footer-actions-modern">
                        <button type="button" onclick="hideAddModal()" class="btn-secondary-modern">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            {{ __('common.cancel') }}
                        </button>
                        <button type="submit" class="btn-primary-modern">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ __('staff.attendance.save') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Attendance Modal -->
<div id="editAttendanceModal" class="modal-overlay hidden">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">{{ __('staff.attendance.edit_attendance') }}</h3>
            <button type="button" onclick="hideEditModal()" class="modal-close">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form method="POST" id="editAttendanceForm">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">{{ __('staff.attendance.staff_member') }}</label>
                    <div id="editStaffName" class="staff-display-field"></div>
                </div>

                <div class="form-group">
                    <label for="editClockIn" class="form-label">{{ __('staff.attendance.clock_in_time') }} <span class="required">*</span></label>
                    <input type="datetime-local" name="clock_in" id="editClockIn" class="form-input" required>
                </div>

                <div class="form-group">
                    <label for="editClockOut" class="form-label">{{ __('staff.attendance.clock_out_time') }}</label>
                    <input type="datetime-local" name="clock_out" id="editClockOut" class="form-input">
                </div>

                <div class="form-group">
                    <label for="editStatus" class="form-label">{{ __('staff.attendance.status') }} <span class="required">*</span></label>
                    <select name="status" id="editStatus" class="form-select" required>
                        <option value="present">{{ __('staff.attendance.present') }}</option>
                        <option value="absent">{{ __('staff.attendance.absent') }}</option>
                        <option value="late">{{ __('staff.attendance.late') }}</option>
                        <option value="overtime">{{ __('staff.attendance.overtime') }}</option>
                        <option value="early_leave">{{ __('staff.attendance.early_leave') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="editNotes" class="form-label">{{ __('staff.attendance.notes') }}</label>
                    <textarea name="notes" id="editNotes" class="form-textarea" placeholder="{{ __('staff.attendance.notes_placeholder') }}"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="hideEditModal()" class="btn-secondary-enhanced">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    {{ __('common.cancel') }}
                </button>
                <button type="submit" class="btn-primary-enhanced">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ __('staff.attendance.update_attendance') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Start Break Modal -->
<div id="startBreakModal" class="modal-overlay hidden">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">{{ __('staff.attendance.start_break') }}</h3>
            <button type="button" onclick="hideStartBreakModal()" class="modal-close">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form id="startBreakForm">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">{{ __('staff.attendance.staff_member') }}</label>
                    <div id="breakStaffName" class="staff-display-field"></div>
                </div>

                <div class="form-group">
                    <label for="breakCategory" class="form-label">{{ __('staff.attendance.break_type') }} <span class="required">*</span></label>
                    <select name="break_category" id="breakCategory" class="form-select" required>
                        <option value="scheduled">{{ __('staff.attendance.scheduled_break') }}</option>
                        <option value="restroom">{{ __('staff.attendance.restroom_break') }}</option>
                        <option value="personal">{{ __('staff.attendance.personal_break') }}</option>
                        <option value="emergency">{{ __('staff.attendance.emergency_break') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="breakReason" class="form-label">{{ __('staff.attendance.reason') }}</label>
                    <textarea name="reason" id="breakReason" class="form-textarea" placeholder="{{ __('staff.attendance.break_reason_placeholder') }}"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="hideStartBreakModal()" class="btn-secondary-enhanced">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    {{ __('common.cancel') }}
                </button>
                <button type="submit" class="btn-primary-enhanced">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ __('staff.attendance.start_break') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Intervals Modal -->
<div id="intervalsModal" class="modal-overlay hidden">
    <div class="modal-container modal-intervals">
        <div class="modal-header-intervals">
            <div class="modal-header-content">
                <div class="modal-icon-container">
                    <svg class="modal-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="modal-title-content">
                    <h3 class="modal-title">{{ __('staff.attendance.attendance_intervals') }}</h3>
                    <p class="modal-subtitle">{{ __('staff.attendance.view_detailed_time_breakdown') }}</p>
                </div>
            </div>
            <button type="button" onclick="hideIntervalsModal()" class="modal-close-intervals">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <div class="modal-body-intervals">
            <!-- Staff Info Section -->
            <div class="intervals-staff-section">
                <div class="staff-info-card">
                    <div class="staff-avatar-large">
                        <span id="intervalsStaffInitials" class="staff-initials"></span>
                    </div>
                    <div class="staff-details">
                        <h4 id="intervalsStaffName" class="staff-name-large"></h4>
                        <p id="intervalsDate" class="date-info-large"></p>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="intervals-summary-grid">
                <div class="summary-card work-time">
                    <div class="summary-card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="summary-card-content">
                        <div class="summary-card-label">{{ __('staff.attendance.total_work_time') }}</div>
                        <div id="totalWorkTime" class="summary-card-value">-</div>
                    </div>
                </div>
                
                <div class="summary-card break-time">
                    <div class="summary-card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="summary-card-content">
                        <div class="summary-card-label">{{ __('staff.attendance.total_break_time') }}</div>
                        <div id="totalBreakTime" class="summary-card-value">-</div>
                    </div>
                </div>
                
                <div class="summary-card break-count">
                    <div class="summary-card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    <div class="summary-card-content">
                        <div class="summary-card-label">{{ __('staff.attendance.break_count') }}</div>
                        <div id="breakCount" class="summary-card-value">-</div>
                    </div>
                </div>
            </div>

            <!-- Timeline Section -->
            <div class="intervals-timeline-section">
                <div class="timeline-header">
                    <h4 class="timeline-title">{{ __('staff.attendance.time_breakdown') }}</h4>
                    <p class="timeline-subtitle">{{ __('staff.attendance.detailed_work_break_intervals') }}</p>
                </div>
                <div class="intervals-timeline" id="intervalsTimeline">
                    <!-- Timeline will be populated by JavaScript -->
                </div>
            </div>
        </div>

        <div class="modal-footer-intervals">
            <button type="button" onclick="hideIntervalsModal()" class="btn-secondary-enhanced">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                {{ __('common.close') }}
            </button>
        </div>
    </div>
</div>

<!-- Review Attendance Modal -->
<div id="reviewAttendanceModal" class="modal-overlay hidden">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">{{ __('staff.attendance.review_attendance') }}</h3>
            <button type="button" onclick="hideReviewAttendanceModal()" class="modal-close">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form id="reviewAttendanceForm">
            <div class="modal-body">
                <div class="review-attendance-info">
                    <div id="reviewStaffName" class="staff-name"></div>
                    <div id="reviewReason" class="review-reason"></div>
                    <div id="reviewTime" class="review-time"></div>
                </div>

                <div class="form-group">
                    <label for="reviewNotes" class="form-label">{{ __('staff.attendance.review_notes') }}</label>
                    <textarea name="review_notes" id="reviewNotes" class="form-textarea" placeholder="{{ __('staff.attendance.review_notes_placeholder') }}"></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('staff.attendance.review_action') }}</label>
                    <div class="radio-group">
                        <label class="radio-option">
                            <input type="radio" name="review_action" value="approve" checked>
                            <span class="radio-text">{{ __('staff.attendance.approve') }}</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="review_action" value="reject">
                            <span class="radio-text">{{ __('staff.attendance.reject') }}</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="review_action" value="modify">
                            <span class="radio-text">{{ __('staff.attendance.modify') }}</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="hideReviewAttendanceModal()" class="btn-secondary-enhanced">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    {{ __('common.cancel') }}
                </button>
                <button type="submit" class="btn-primary-enhanced">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ __('staff.attendance.complete_review') }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
    @vite('resources/css/admin/staff-attendance.css')
@endpush

@push('scripts')
    @vite('resources/js/admin/staff-attendance.js')
@endpush
@endpush
