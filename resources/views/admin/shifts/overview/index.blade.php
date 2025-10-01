@extends('layouts.admin')

@section('title', __('shifts.overview.title'))

@section('content')
<div class="shifts-overview-page" x-data="shiftOverviewData()">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-header-left">
                <h1 class="page-title">{{ __('shifts.overview.title') }}</h1>
                <p class="page-description">{{ __('shifts.overview.subtitle') }}</p>
            </div>
            <div class="page-header-right">
                <div class="week-navigation">
                    <a href="{{ request()->fullUrlWithQuery(['week' => $weekNavigation['previous_week']->format('Y-m-d')]) }}" 
                       class="btn btn-outline">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        {{ __('shifts.overview.previous_week') }}
                    </a>
                    <div class="current-week-display">
                        <span class="week-label">{{ __('shifts.overview.week_of', ['date' => $weekStart->format('M d, Y')]) }}</span>
                        @if($weekNavigation['is_current_week'])
                            <span class="current-week-badge">{{ __('shifts.overview.today') }}</span>
                        @endif
                    </div>
                    <a href="{{ request()->fullUrlWithQuery(['week' => $weekNavigation['next_week']->format('Y-m-d')]) }}" 
                       class="btn btn-outline">
                        {{ __('shifts.overview.next_week') }}
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
                <div class="quick-actions">
                    <a href="/admin/shifts/manage/create" class="btn btn-primary">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        {{ __('shifts.overview.add_shift') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Shift Summary Cards -->
    <div class="summary-section">
        <div class="summary-grid">
            <div class="summary-card summary-card-primary">
                <div class="summary-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="summary-content">
                    <div class="summary-value">{{ $shiftSummary['total_shifts'] }}</div>
                    <div class="summary-label">{{ __('shifts.overview.total_shifts') }}</div>
                </div>
            </div>
            
            <div class="summary-card summary-card-success">
                <div class="summary-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="summary-content">
                    <div class="summary-value">{{ $shiftSummary['total_staff_scheduled'] }}</div>
                    <div class="summary-label">{{ __('shifts.overview.staff_scheduled') }}</div>
                </div>
            </div>
            
            <div class="summary-card summary-card-info">
                <div class="summary-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="summary-content">
                    <div class="summary-value">{{ $shiftSummary['total_hours'] }}h</div>
                    <div class="summary-label">{{ __('shifts.overview.total_hours') }}</div>
                </div>
            </div>
            
            <div class="summary-card summary-card-warning">
                <div class="summary-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <div class="summary-content">
                    <div class="summary-value">{{ $shiftSummary['coverage_gaps'] }}</div>
                    <div class="summary-label">{{ __('shifts.overview.coverage_gaps') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Content (Above Calendar) -->
    <div class="sidebar-content-top">
        <!-- Current Shifts -->
        @if(!empty($currentShifts))
        <div class="current-shifts-section">
            <div class="section-header">
                <h3 class="section-title">{{ __('shifts.overview.current_shifts') }}</h3>
            </div>
            <div class="current-shifts-list">
                @foreach($currentShifts as $shift)
                <div class="current-shift-card">
                    <div class="shift-progress">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $shift['progress_percentage'] }}%"></div>
                        </div>
                        <div class="progress-text">{{ $shift['progress_percentage'] }}% {{ __('shifts.common.complete') }}</div>
                    </div>
                    <div class="shift-details">
                        <div class="shift-name">{{ $shift['name'] }}</div>
                        <div class="shift-time">{{ $shift['time_remaining'] }} {{ __('shifts.common.remaining') }}</div>
                        <div class="shift-staff">
                            @foreach($shift['assigned_staff'] as $staff)
                            <div class="staff-member {{ $staff['checked_in'] ? 'checked-in' : 'not-checked-in' }}">
                                <span class="staff-name">{{ $staff['name'] }}</span>
                                <span class="check-status">
                                    @if($staff['checked_in'])
                                        <svg class="check-icon" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    @else
                                        <svg class="x-icon" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Quick Info Grid -->
        <div class="quick-info-grid">
            <!-- Upcoming Shifts -->
            <div class="upcoming-shifts-section">
                <div class="section-header">
                    <h3 class="section-title">{{ __('shifts.overview.upcoming_shifts') }}</h3>
                </div>
                <div class="upcoming-shifts-list">
                    @foreach($upcomingShifts as $shift)
                    <div class="upcoming-shift-card">
                        <div class="shift-time-info">
                            <div class="shift-date">{{ $shift['date']->format('M d') }}</div>
                            <div class="shift-time">{{ $shift['start_time'] }} - {{ $shift['end_time'] }}</div>
                            <div class="time-until">{{ __('shifts.common.in') }} {{ $shift['hours_until'] }}h</div>
                        </div>
                        <div class="shift-details">
                            <div class="shift-name">{{ $shift['name'] }}</div>
                            <div class="shift-department">{{ $shift['department'] }}</div>
                            <div class="shift-staff-count">{{ count($shift['assigned_staff']) }} {{ __('shifts.common.staff') }}</div>
                        </div>
                        <div class="shift-status">
                            <span class="status-indicator status-{{ $shift['status'] }}">
                                {{ __('shifts.assignments.' . $shift['status']) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Coverage Gaps -->
            @if(!empty($coverageGaps))
            <div class="coverage-gaps-section">
                <div class="section-header">
                    <h3 class="section-title">{{ __('shifts.overview.coverage_gaps') }}</h3>
                </div>
                <div class="coverage-gaps-list">
                    @foreach($coverageGaps as $gap)
                    <div class="coverage-gap-card priority-{{ $gap['priority'] }}">
                        <div class="gap-header">
                            <div class="gap-priority">
                                <span class="priority-indicator priority-{{ $gap['priority'] }}">
                                    {{ __('shifts.common.' . $gap['priority']) }}
                                </span>
                            </div>
                            <div class="gap-count">-{{ $gap['gap_count'] }} {{ __('shifts.common.staff') }}</div>
                        </div>
                        <div class="gap-details">
                            <div class="gap-shift">{{ $gap['shift_name'] }}</div>
                            <div class="gap-time">{{ $gap['time'] }}</div>
                            <div class="gap-date">{{ $gap['date']->format('M d, Y') }}</div>
                        </div>
                        <div class="gap-actions">
                            <button class="btn btn-sm btn-primary" @click="assignStaffToGap({{ json_encode($gap) }})">
                                {{ __('shifts.common.assign') }}
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="main-content-grid">
        <!-- Weekly Schedule -->
        <div class="schedule-section">
            <div class="section-header">
                <h2 class="section-title">{{ __('shifts.overview.week_of', ['date' => $weekStart->format('M d')]) }}</h2>
                <div class="section-filters">
                    <div class="filter-group">
                        <select x-model="filterDepartment" @change="applyFilters()" class="filter-select">
                            <option value="all">{{ __('shifts.departments.all_departments') }}</option>
                            <option value="Kitchen">{{ __('shifts.departments.kitchen') }}</option>
                            <option value="Front of House">{{ __('shifts.departments.front_of_house') }}</option>
                            <option value="Bar">{{ __('shifts.departments.bar') }}</option>
                            <option value="Management">{{ __('shifts.departments.management') }}</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <select x-model="filterStatus" @change="applyFilters()" class="filter-select">
                            <option value="all">{{ __('shifts.common.all_statuses') }}</option>
                            <option value="fully_covered">{{ __('shifts.assignments.fully_covered') }}</option>
                            <option value="partially_covered">{{ __('shifts.assignments.partially_covered') }}</option>
                            <option value="not_covered">{{ __('shifts.assignments.not_covered') }}</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <select x-model="filterType" @change="applyFilters()" class="filter-select">
                            <option value="all">{{ __('shifts.common.all_types') }}</option>
                            <option value="regular">{{ __('shifts.types.regular') }}</option>
                            <option value="weekend">{{ __('shifts.types.weekend') }}</option>
                            <option value="overtime">{{ __('shifts.types.overtime') }}</option>
                            <option value="training">{{ __('shifts.types.training') }}</option>
                        </select>
                    </div>
                </div>
                <div class="section-actions">
                    <button class="btn btn-sm btn-outline" @click="resetFilters()">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        {{ __('shifts.common.reset_filters') }}
                    </button>
                    <button class="btn btn-sm btn-secondary" @click="showExportModal = true">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        {{ __('shifts.overview.export_schedule') }}
                    </button>
                </div>
            </div>
            
            <div class="calendar-container">
                <div class="weekly-calendar">
                    @foreach($weeklySchedule as $day)
                <div class="calendar-day {{ $day['is_today'] ? 'today' : '' }} {{ $day['is_weekend'] ? 'weekend' : '' }}">
                    <div class="day-header">
                        <div class="day-name">{{ $day['day_short'] }}</div>
                        <div class="day-date">{{ $day['date']->format('d') }}</div>
                        <div class="day-stats">
                            <span class="shift-count">{{ $day['total_shifts'] }} {{ __('shifts.common.shifts') }}</span>
                            <span class="staff-count">{{ $day['total_staff'] }} {{ __('shifts.common.staff') }}</span>
                        </div>
                    </div>
                    
                    <div class="day-shifts">
                        @if(empty($day['shifts']))
                            <div class="no-shifts">{{ __('shifts.overview.no_shifts_scheduled') }}</div>
                        @else
                            @foreach($day['shifts'] as $shift)
                            <div class="shift-block shift-{{ $shift['status'] }}" 
                                 style="border-left-color: {{ $shift['color'] }}"
                                 @click="showShiftDetails({{ json_encode($shift) }})">
                                <div class="shift-header">
                                    <div class="shift-name">{{ $shift['name'] }}</div>
                                    <div class="shift-time">{{ $shift['start_time'] }} - {{ $shift['end_time'] }}</div>
                                </div>
                                <div class="shift-info">
                                    <div class="shift-department">{{ $shift['department'] }}</div>
                                    <div class="shift-staffing">
                                        <span class="staffing-count {{ $shift['assigned_staff_count'] < $shift['required_staff'] ? 'understaffed' : 'fully-staffed' }}">
                                            {{ $shift['assigned_staff_count'] }}/{{ $shift['required_staff'] }}
                                        </span>
                                        <span class="staffing-label">{{ __('shifts.common.staff') }}</span>
                                    </div>
                                </div>
                                <div class="shift-status">
                                    <span class="status-indicator status-{{ $shift['status'] }}">
                                        {{ __('shifts.assignments.' . $shift['status']) }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Shift Details Modal -->
    <div x-show="showShiftModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="modal-overlay" 
         @click="closeShiftModal()"
         style="display: none;">
        <div class="modal-content" @click.stop>
            <div class="modal-header">
                <h3 class="modal-title" x-text="selectedShift?.name || 'Shift Details'"></h3>
                <button class="modal-close" @click="closeShiftModal()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <div class="modal-body" x-show="selectedShift">
                <div class="shift-detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">{{ __('shifts.common.time') }}</div>
                        <div class="detail-value" x-text="`${selectedShift?.start_time} - ${selectedShift?.end_time}`"></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">{{ __('shifts.manage.department') }}</div>
                        <div class="detail-value" x-text="selectedShift?.department"></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">{{ __('shifts.manage.duration') }}</div>
                        <div class="detail-value" x-text="`${selectedShift?.duration_hours}h`"></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">{{ __('shifts.assignments.coverage_status') }}</div>
                        <div class="detail-value">
                            <span class="status-indicator" :class="`status-${selectedShift?.status}`" x-text="selectedShift?.status"></span>
                        </div>
                    </div>
                </div>
                
                <div class="assigned-staff-section">
                    <h4 class="staff-section-title">{{ __('shifts.assignments.assigned_staff') }}</h4>
                    <div class="staff-list">
                        <template x-for="staff in selectedShift?.assigned_staff || []" :key="staff.id">
                            <div class="staff-item">
                                <div class="staff-info">
                                    <div class="staff-name" x-text="staff.name"></div>
                                    <div class="staff-role" x-text="staff.role"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            
            <div class="modal-actions">
                <a :href="`/admin/shifts/manage/${selectedShift?.id}/edit`" class="btn btn-secondary">
                    {{ __('shifts.common.edit') }}
                </a>
                <a :href="`/admin/shifts/assignments?shift=${selectedShift?.id}`" class="btn btn-primary">
                    {{ __('shifts.assignments.assign_staff') }}
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
@vite('resources/css/admin/shifts/overview.css')
@endpush

@push('scripts')
@vite('resources/js/admin/shifts/overview.js')
<script>
function shiftOverviewData() {
    return {
        showShiftModal: false,
        showExportModal: false,
        selectedShift: null,
        filterDepartment: 'all',
        filterStatus: 'all',
        filterType: 'all',
        
        showShiftDetails(shift) {
            this.selectedShift = shift;
            this.showShiftModal = true;
        },
        
        closeShiftModal() {
            this.showShiftModal = false;
            this.selectedShift = null;
        },
        
        assignStaffToGap(gap) {
            window.location.href = `/admin/shifts/assignments?gap=${gap.shift_name}&date=${gap.date}`;
        },
        
        exportSchedule() {
            this.showExportModal = true;
        },
        
        applyFilters() {
            const shiftBlocks = document.querySelectorAll('.shift-block');
            shiftBlocks.forEach(block => {
                const department = block.querySelector('.shift-department')?.textContent?.trim();
                const status = this.getShiftStatus(block);
                const type = this.getShiftType(block);
                
                let visible = true;
                
                if (this.filterDepartment !== 'all' && department !== this.filterDepartment) {
                    visible = false;
                }
                
                if (this.filterStatus !== 'all' && status !== this.filterStatus) {
                    visible = false;
                }
                
                if (this.filterType !== 'all' && type !== this.filterType) {
                    visible = false;
                }
                
                block.style.display = visible ? 'block' : 'none';
            });
            
            this.updateDayStats();
            this.showFilterNotification();
        },
        
        resetFilters() {
            this.filterDepartment = 'all';
            this.filterStatus = 'all';
            this.filterType = 'all';
            
            const shiftBlocks = document.querySelectorAll('.shift-block');
            shiftBlocks.forEach(block => {
                block.style.display = 'block';
            });
            
            this.updateDayStats();
            this.showNotification('Filters reset successfully!', 'info');
        },
        
        getShiftStatus(block) {
            if (block.classList.contains('shift-fully_covered')) return 'fully_covered';
            if (block.classList.contains('shift-partially_covered')) return 'partially_covered';
            if (block.classList.contains('shift-not_covered')) return 'not_covered';
            return 'unknown';
        },
        
        getShiftType(block) {
            // In a real implementation, this would get the type from data attributes
            const shiftName = block.querySelector('.shift-name')?.textContent?.toLowerCase() || '';
            if (shiftName.includes('weekend')) return 'weekend';
            if (shiftName.includes('overtime')) return 'overtime';
            if (shiftName.includes('training')) return 'training';
            return 'regular';
        },
        
        updateDayStats() {
            const days = document.querySelectorAll('.calendar-day');
            days.forEach(day => {
                const visibleShifts = day.querySelectorAll('.shift-block[style="display: block"], .shift-block:not([style*="display: none"])');
                const shiftCount = day.querySelector('.shift-count');
                const staffCount = day.querySelector('.staff-count');
                
                if (shiftCount) {
                    shiftCount.textContent = `${visibleShifts.length} shifts`;
                }
                
                // Calculate total staff for visible shifts
                let totalStaff = 0;
                visibleShifts.forEach(shift => {
                    const staffing = shift.querySelector('.staffing-count')?.textContent || '0/0';
                    const assigned = parseInt(staffing.split('/')[0]) || 0;
                    totalStaff += assigned;
                });
                
                if (staffCount) {
                    staffCount.textContent = `${totalStaff} staff`;
                }
            });
        },
        
        showFilterNotification() {
            const activeFilters = [];
            if (this.filterDepartment !== 'all') activeFilters.push(`Department: ${this.filterDepartment}`);
            if (this.filterStatus !== 'all') activeFilters.push(`Status: ${this.filterStatus.replace('_', ' ')}`);
            if (this.filterType !== 'all') activeFilters.push(`Type: ${this.filterType}`);
            
            if (activeFilters.length > 0) {
                this.showNotification(`Filters applied: ${activeFilters.join(', ')}`, 'info');
            }
        },
        
        showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.innerHTML = `
                <div class="notification-content">
                    <span class="notification-message">${message}</span>
                    <button class="notification-close" onclick="this.parentElement.parentElement.remove()">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            `;
            
            // Add to page
            document.body.appendChild(notification);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }
    };
}
</script>
@endpush
