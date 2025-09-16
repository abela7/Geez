@extends('layouts.admin')

@section('title', __('shifts.assignments.title'))

@section('content')
<div class="shifts-assignments-page" x-data="shiftsAssignmentsData()">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-header-left">
                <h1 class="page-title">{{ __('shifts.assignments.title') }}</h1>
                <p class="page-description">{{ __('shifts.assignments.subtitle') }}</p>
            </div>
            <div class="page-header-right">
                <div class="header-actions">
                    <button class="btn btn-secondary" @click="showAutoAssign = true">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        {{ __('shifts.assignments.auto_assign') }}
                    </button>
                    <button class="btn btn-primary" @click="showBulkAssign = true">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        {{ __('shifts.assignments.bulk_assign') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Dashboard -->
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
                    <div class="summary-label">{{ __('shifts.assignments.total_shifts') }}</div>
                </div>
            </div>

            <div class="summary-card summary-card-success">
                <div class="summary-icon">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="summary-content">
                    <div class="summary-value">{{ $fullyCovered }}</div>
                    <div class="summary-label">{{ __('shifts.assignments.fully_covered') }}</div>
                </div>
            </div>

            <div class="summary-card summary-card-warning">
                <div class="summary-icon">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="summary-content">
                    <div class="summary-value">{{ $partiallyCovered }}</div>
                    <div class="summary-label">{{ __('shifts.assignments.partially_covered') }}</div>
                </div>
            </div>

            <div class="summary-card summary-card-danger">
                <div class="summary-icon">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="summary-content">
                    <div class="summary-value">{{ $notCovered }}</div>
                    <div class="summary-label">{{ __('shifts.assignments.not_covered') }}</div>
                </div>
            </div>

            <div class="summary-card summary-card-info">
                <div class="summary-icon">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                </div>
                <div class="summary-content">
                    <div class="summary-value">{{ $coveragePercentage }}%</div>
                    <div class="summary-label">{{ __('shifts.assignments.coverage_rate') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="main-content-grid">
        <!-- Shifts Needing Assignment -->
        <div class="shifts-section">
            <div class="section-header">
                <h2 class="section-title">{{ __('shifts.assignments.shifts_needing_assignment') }}</h2>
                <div class="section-filters">
                    <select x-model="filterDepartment" @change="applyFilters()" class="filter-select">
                        <option value="all">{{ __('shifts.departments.all_departments') }}</option>
                        <option value="Kitchen">{{ __('shifts.departments.kitchen') }}</option>
                        <option value="Front of House">{{ __('shifts.departments.front_of_house') }}</option>
                        <option value="Bar">{{ __('shifts.departments.bar') }}</option>
                        <option value="Management">{{ __('shifts.departments.management') }}</option>
                        <option value="Maintenance">{{ __('shifts.departments.maintenance') }}</option>
                    </select>
                    <select x-model="filterStatus" @change="applyFilters()" class="filter-select">
                        <option value="all">{{ __('shifts.common.all_statuses') }}</option>
                        <option value="not_covered">{{ __('shifts.assignments.not_covered') }}</option>
                        <option value="partially_covered">{{ __('shifts.assignments.partially_covered') }}</option>
                        <option value="fully_covered">{{ __('shifts.assignments.fully_covered') }}</option>
                    </select>
                </div>
            </div>
            
            <div class="shifts-list">
                @foreach($shifts as $shift)
                <div class="shift-assignment-card" data-department="{{ $shift['department'] }}" data-status="{{ $shift['status'] }}">
                    <div class="shift-card-header">
                        <div class="shift-info">
                            <h3 class="shift-name">{{ $shift['name'] }}</h3>
                            <div class="shift-details">
                                <span class="shift-date">{{ $shift['date']->format('M d, Y') }}</span>
                                <span class="shift-time">{{ $shift['start_time'] }} - {{ $shift['end_time'] }}</span>
                                <span class="shift-department">{{ __('shifts.departments.' . strtolower(str_replace(' ', '_', $shift['department']))) }}</span>
                            </div>
                        </div>
                        <div class="shift-status">
                            <span class="status-badge status-{{ $shift['status'] }}">
                                {{ __('shifts.assignments.' . $shift['status']) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="staffing-progress">
                        <div class="staffing-info">
                            <span class="staffing-text">
                                {{ $shift['assigned_staff'] }} / {{ $shift['required_staff'] }} {{ __('shifts.common.staff') }}
                            </span>
                            <span class="staffing-percentage">
                                {{ $shift['required_staff'] > 0 ? round(($shift['assigned_staff'] / $shift['required_staff']) * 100) : 0 }}%
                            </span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $shift['required_staff'] > 0 ? ($shift['assigned_staff'] / $shift['required_staff']) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    @if(!empty($shift['assignments']))
                    <div class="current-assignments">
                        <h4 class="assignments-title">{{ __('shifts.assignments.current_assignments') }}</h4>
                        <div class="assignments-list">
                            @foreach($shift['assignments'] as $assignment)
                            <div class="assignment-item">
                                <div class="assignment-info">
                                    <span class="staff-name">{{ $assignment['staff_name'] }}</span>
                                    <span class="staff-role">{{ $assignment['role'] }}</span>
                                </div>
                                <div class="assignment-actions">
                                    <span class="assignment-status status-{{ $assignment['status'] }}">
                                        {{ __('shifts.assignments.' . $assignment['status']) }}
                                    </span>
                                    <button class="btn-icon-sm btn-danger" @click="unassignStaff({{ $shift['id'] }}, {{ $assignment['staff_id'] }})">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="shift-actions">
                        <button class="btn btn-primary btn-sm" @click="showAssignModal({{ json_encode($shift) }})">
                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            {{ __('shifts.assignments.assign_staff') }}
                        </button>
                        <button class="btn btn-secondary btn-sm" @click="checkAvailability({{ json_encode($shift) }})">
                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ __('shifts.assignments.check_availability') }}
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Staff Availability Sidebar -->
        <div class="staff-sidebar">
            <!-- Available Staff -->
            <div class="staff-section">
                <div class="section-header">
                    <h3 class="section-title">{{ __('shifts.assignments.available_staff') }}</h3>
                    <span class="staff-count">{{ $availableStaff }}/{{ $totalStaff }}</span>
                </div>
                
                <div class="staff-search">
                    <input type="text" x-model="staffSearchQuery" @input="filterStaff()" class="search-input" placeholder="{{ __('shifts.assignments.search_staff') }}">
                </div>

                <div class="staff-filters">
                    <select x-model="staffFilterDepartment" @change="filterStaff()" class="filter-select">
                        <option value="all">{{ __('shifts.departments.all_departments') }}</option>
                        <option value="Kitchen">{{ __('shifts.departments.kitchen') }}</option>
                        <option value="Front of House">{{ __('shifts.departments.front_of_house') }}</option>
                        <option value="Bar">{{ __('shifts.departments.bar') }}</option>
                        <option value="Management">{{ __('shifts.departments.management') }}</option>
                        <option value="Maintenance">{{ __('shifts.departments.maintenance') }}</option>
                    </select>
                </div>
                
                <div class="staff-list">
                    @foreach($staff as $member)
                    <div class="staff-card" data-department="{{ $member['department'] }}" data-name="{{ strtolower($member['name']) }}">
                        <div class="staff-avatar">
                            <span class="avatar-initials">{{ substr($member['name'], 0, 1) }}{{ substr(explode(' ', $member['name'])[1] ?? '', 0, 1) }}</span>
                        </div>
                        <div class="staff-info">
                            <div class="staff-name">{{ $member['name'] }}</div>
                            <div class="staff-role">{{ $member['role'] }}</div>
                            <div class="staff-department">{{ __('shifts.departments.' . strtolower(str_replace(' ', '_', $member['department']))) }}</div>
                        </div>
                        <div class="staff-actions">
                            <button class="btn-icon-sm btn-primary" @click="quickAssign({{ $member['id'] }})" title="{{ __('shifts.assignments.quick_assign') }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </button>
                            <button class="btn-icon-sm btn-secondary" @click="viewStaffSchedule({{ $member['id'] }})" title="{{ __('shifts.assignments.view_schedule') }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="activity-section">
                <div class="section-header">
                    <h3 class="section-title">{{ __('shifts.assignments.recent_activity') }}</h3>
                </div>
                
                <div class="activity-list">
                    @foreach($recentActivity as $activity)
                    <div class="activity-item">
                        <div class="activity-icon activity-{{ $activity['type'] }}">
                            @if($activity['type'] === 'assignment')
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                            </svg>
                            @else
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                            @endif
                        </div>
                        <div class="activity-content">
                            <div class="activity-text">
                                <strong>{{ $activity['staff_name'] }}</strong>
                                {{ $activity['type'] === 'assignment' ? __('shifts.assignments.assigned_to') : __('shifts.assignments.unassigned_from') }}
                                <strong>{{ $activity['shift_name'] }}</strong>
                            </div>
                            <div class="activity-meta">
                                <span class="activity-time">{{ $activity['date']->diffForHumans() }}</span>
                                <span class="activity-by">{{ __('shifts.assignments.by') }} {{ $activity['assigned_by'] }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Assignment Modal -->
    <div x-show="showAssignmentModal" x-transition class="modal-overlay" @click.self="showAssignmentModal = false">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">{{ __('shifts.assignments.assign_staff_to_shift') }}</h3>
                <button class="modal-close" @click="showAssignmentModal = false">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <div class="modal-body">
                <div class="shift-summary" x-show="selectedShift">
                    <h4>{{ __('shifts.assignments.shift_details') }}</h4>
                    <div class="shift-details-grid">
                        <div class="detail-item">
                            <span class="detail-label">{{ __('shifts.manage.shift_name') }}</span>
                            <span class="detail-value" x-text="selectedShift?.name"></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">{{ __('shifts.common.date') }}</span>
                            <span class="detail-value" x-text="formatDate(selectedShift?.date)"></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">{{ __('shifts.common.time') }}</span>
                            <span class="detail-value" x-text="`${selectedShift?.start_time} - ${selectedShift?.end_time}`"></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">{{ __('shifts.common.department') }}</span>
                            <span class="detail-value" x-text="selectedShift?.department"></span>
                        </div>
                    </div>
                </div>

                <div class="staff-selection">
                    <h4>{{ __('shifts.assignments.select_staff_member') }}</h4>
                    <div class="available-staff-list">
                        @foreach($staff as $member)
                        <div class="staff-option" @click="selectStaffForAssignment({{ json_encode($member) }})">
                            <div class="staff-avatar">
                                <span class="avatar-initials">{{ substr($member['name'], 0, 1) }}{{ substr(explode(' ', $member['name'])[1] ?? '', 0, 1) }}</span>
                            </div>
                            <div class="staff-info">
                                <div class="staff-name">{{ $member['name'] }}</div>
                                <div class="staff-role">{{ $member['role'] }}</div>
                                <div class="staff-department">{{ __('shifts.departments.' . strtolower(str_replace(' ', '_', $member['department']))) }}</div>
                            </div>
                            <div class="availability-indicator available">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="modal-actions">
                <button class="btn btn-secondary" @click="showAssignmentModal = false">
                    {{ __('shifts.common.cancel') }}
                </button>
                <button class="btn btn-primary" @click="confirmAssignment()" :disabled="!selectedStaffForAssignment">
                    {{ __('shifts.assignments.assign_staff') }}
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
@vite(['resources/css/admin/shifts/assignments.css'])
@endpush

@push('scripts')
@vite(['resources/js/admin/shifts/assignments.js'])
@endpush
@endsection
