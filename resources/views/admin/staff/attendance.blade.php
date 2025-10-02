@extends('layouts.admin')

@section('title', __('staff.attendance.title') . ' - ' . config('app.name'))
@section('page_title', __('staff.attendance.title'))

@section('content')
<div class="attendance-page">
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-error">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

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
            <div class="stat-icon stat-icon-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $todayStats['total_staff'] }}</div>
                <div class="stat-label">{{ __('staff.attendance.total_staff_today') }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-success">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $todayStats['present_count'] }}</div>
                <div class="stat-label">{{ __('staff.attendance.present') }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-danger">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $todayStats['absent_count'] }}</div>
                <div class="stat-label">{{ __('staff.attendance.absent') }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-warning">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $todayStats['late_count'] }}</div>
                <div class="stat-label">{{ __('staff.attendance.late_arrivals') }}</div>
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
                                <th>{{ __('staff.attendance.hours_worked') }}</th>
                                <th>{{ __('staff.attendance.status') }}</th>
                                <th>{{ __('common.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendanceRecords as $record)
                                <tr>
                                    <td>
                                        <div class="staff-cell">
                                            <div class="staff-avatar">
                                                {{ strtoupper(substr($record->staff->first_name, 0, 1) . substr($record->staff->last_name, 0, 1)) }}
                                            </div>
                                            <div class="staff-info">
                                                <div class="staff-name">{{ $record->staff->full_name }}</div>
                                                <div class="staff-type">{{ $record->staff->staffType->display_name ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $record->clock_in->format('H:i') }}</td>
                                    <td>{{ $record->clock_out ? $record->clock_out->format('H:i') : '-' }}</td>
                                    <td>{{ $record->hours_worked ? number_format($record->hours_worked, 1) . 'h' : '-' }}</td>
                                    <td>
                                        <span class="status-badge status-{{ $record->status }}">
                                            {{ __('staff.attendance.' . $record->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
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

    <!-- Secondary Info Cards (Side by Side) -->
    <div class="secondary-cards-grid">
        <!-- Currently Clocked In -->
        <div class="sidebar-card">
            <div class="sidebar-card-header">
                <h3 class="sidebar-card-title">{{ __('staff.attendance.currently_clocked_in') }}</h3>
                <span class="sidebar-card-badge">{{ $currentlyClocked->count() }} {{ __('staff.attendance.active') }}</span>
            </div>
            <div class="sidebar-card-body">
                @if($currentlyClocked->count() > 0)
                    <div class="clocked-list">
                        @foreach($currentlyClocked as $record)
                            <div class="clocked-item">
                                <div class="clocked-staff">
                                    <div class="clocked-indicator">
                                        <div class="clocked-dot"></div>
                                    </div>
                                    <div class="clocked-staff-info">
                                        <div class="clocked-name">{{ $record->staff->full_name }}</div>
                                        <div class="clocked-role">{{ $record->staff->staffType->display_name ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="clocked-time-info">
                                    <div class="clocked-time">{{ $record->clock_in->format('H:i') }}</div>
                                    <div class="clocked-duration">{{ $record->clock_in->diffForHumans() }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="empty-state-text">{{ __('staff.attendance.no_one_clocked_in') }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="sidebar-card">
            <div class="sidebar-card-header">
                <h3 class="sidebar-card-title">{{ __('staff.attendance.recent_activity') }}</h3>
                <span class="sidebar-card-badge">{{ __('staff.attendance.last_7_days') }}</span>
            </div>
            <div class="sidebar-card-body">
                @if($recentActivity->count() > 0)
                    <div class="activity-list">
                        @foreach($recentActivity->take(8) as $record)
                            <div class="activity-item">
                                <div class="activity-staff">
                                    <div class="activity-icon activity-icon-{{ $record->status }}">
                                        @if($record->status === 'present')
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        @elseif($record->status === 'late')
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @else
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="activity-details">
                                        <div class="activity-name">{{ $record->staff->full_name }}</div>
                                        <div class="activity-time">{{ $record->clock_in->format('M j, H:i') }}</div>
                                    </div>
                                </div>
                                <span class="status-badge status-{{ $record->status }}">
                                    {{ __('staff.attendance.' . $record->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="empty-state-text">{{ __('staff.attendance.no_recent_activity') }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Attendance Modal -->
<div id="addAttendanceModal" class="modal-overlay hidden">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">{{ __('staff.attendance.add_attendance') }}</h3>
            <button type="button" onclick="hideAddModal()" class="modal-close">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form method="POST" action="{{ route('admin.staff.attendance.store') }}" id="addAttendanceForm">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="staff_id" class="form-label">{{ __('staff.attendance.select_staff') }} <span class="required">*</span></label>
                    <select name="staff_id" id="staff_id" class="form-select" required>
                        <option value="">{{ __('staff.attendance.choose_staff') }}</option>
                        @foreach($allStaff as $staff)
                            <option value="{{ $staff->id }}">{{ $staff->full_name }} - {{ $staff->staffType->display_name ?? 'N/A' }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="clockIn" class="form-label">{{ __('staff.attendance.clock_in_time') }} <span class="required">*</span></label>
                    <input type="datetime-local" name="clock_in" id="clockIn" class="form-input" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                </div>

                <div class="form-group">
                    <label for="clockOut" class="form-label">{{ __('staff.attendance.clock_out_time') }}</label>
                    <input type="datetime-local" name="clock_out" id="clockOut" class="form-input">
                </div>

                <div class="form-group">
                    <label for="status" class="form-label">{{ __('staff.attendance.status') }} <span class="required">*</span></label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="present">{{ __('staff.attendance.present') }}</option>
                        <option value="absent">{{ __('staff.attendance.absent') }}</option>
                        <option value="late">{{ __('staff.attendance.late') }}</option>
                        <option value="overtime">{{ __('staff.attendance.overtime') }}</option>
                        <option value="early_leave">{{ __('staff.attendance.early_leave') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="notes" class="form-label">{{ __('staff.attendance.notes') }}</label>
                    <textarea name="notes" id="notes" class="form-textarea" placeholder="{{ __('staff.attendance.notes_placeholder') }}"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="hideAddModal()" class="btn btn-secondary">{{ __('common.cancel') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('staff.attendance.save') }}</button>
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
                <button type="button" onclick="hideEditModal()" class="btn btn-secondary">{{ __('common.cancel') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('staff.attendance.update_attendance') }}</button>
            </div>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="toast toast-success">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="toast toast-error">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('error') }}
    </div>
@endif

@push('styles')
    @vite('resources/css/admin/staff-attendance.css')
@endpush

@push('scripts')
    @vite('resources/js/admin/staff-attendance.js')
@endpush
@endsection
