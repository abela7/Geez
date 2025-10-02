@extends('layouts.admin')

@section('title', __('admin.staff.shifts.schedule_title'))
@section('page-title', __('admin.staff.shifts.schedule_title'))

@push('styles')
    @vite(['resources/css/admin/staff-attendance.css'])
@endpush

@section('content')
<div class="attendance-page">
    <!-- Header Section -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-text">
                <h1>{{ __('admin.staff.shifts.schedule_title') }}</h1>
                <p>{{ __('admin.staff.shifts.schedule_description') }}</p>
            </div>
            <div class="header-actions">
                <button type="button" class="btn btn-primary" onclick="showAssignModal()">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('admin.staff.shifts.assign_staff') }}
                </button>
                <button type="button" class="btn btn-secondary" onclick="generateFromPatterns()">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    {{ __('admin.staff.shifts.generate_from_patterns') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Week Navigation -->
    <div class="week-navigation">
        <div class="week-controls">
            <button type="button" class="btn btn-outline" onclick="changeWeek(-1)">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                {{ __('common.previous') }}
            </button>
            <div class="week-display">
                <h3>{{ $weekStart->format('M j') }} - {{ $weekEnd->format('M j, Y') }}</h3>
                <p>{{ __('admin.staff.shifts.week_of', ['date' => $weekStart->format('F j, Y')]) }}</p>
            </div>
            <button type="button" class="btn btn-outline" onclick="changeWeek(1)">
                {{ __('common.next') }}
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
        <div class="view-toggles">
            <a href="{{ route('admin.shifts.schedule.index') }}" class="btn btn-sm {{ request()->routeIs('*.schedule.index') ? 'btn-primary' : 'btn-outline' }}">
                {{ __('admin.staff.shifts.week_view') }}
            </a>
            <a href="{{ route('admin.shifts.schedule.calendar') }}" class="btn btn-sm {{ request()->routeIs('*.schedule.calendar') ? 'btn-primary' : 'btn-outline' }}">
                {{ __('admin.staff.shifts.calendar_view') }}
            </a>
        </div>
    </div>

    <!-- Coverage Statistics -->
    <div class="stats-grid">
        @php
            $totalNeeded = collect($coverageStats)->sum('total_staff_needed');
            $totalAssigned = collect($coverageStats)->sum('total_staff_assigned');
            $totalUnderstaffed = collect($coverageStats)->sum('understaffed_shifts');
            $coveragePercentage = $totalNeeded > 0 ? round(($totalAssigned / $totalNeeded) * 100) : 0;
        @endphp
        
        <div class="stat-card">
            <div class="stat-icon stat-icon-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $coveragePercentage }}%</div>
                <div class="stat-label">{{ __('admin.staff.shifts.coverage_percentage') }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-success">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $totalAssigned }}</div>
                <div class="stat-label">{{ __('admin.staff.shifts.staff_assigned') }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-warning">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $totalUnderstaffed }}</div>
                <div class="stat-label">{{ __('admin.staff.shifts.understaffed_shifts') }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-info">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $totalNeeded }}</div>
                <div class="stat-label">{{ __('admin.staff.shifts.total_needed') }}</div>
            </div>
        </div>
    </div>

    <!-- Schedule Grid -->
    <div class="schedule-container">
        <div class="schedule-grid">
            <!-- Days Header -->
            <div class="schedule-header">
                @for($i = 0; $i < 7; $i++)
                    @php $day = $weekStart->copy()->addDays($i) @endphp
                    <div class="day-header {{ $day->isToday() ? 'today' : '' }}">
                        <div class="day-name">{{ $day->format('D') }}</div>
                        <div class="day-date">{{ $day->format('M j') }}</div>
                        @if(isset($coverageStats[$day->format('Y-m-d')]))
                            @php $dayStats = $coverageStats[$day->format('Y-m-d')] @endphp
                            <div class="day-coverage {{ $dayStats['understaffed_shifts'] > 0 ? 'understaffed' : 'covered' }}">
                                {{ $dayStats['total_staff_assigned'] }}/{{ $dayStats['total_staff_needed'] }}
                            </div>
                        @endif
                    </div>
                @endfor
            </div>

            <!-- Schedule Content -->
            <div class="schedule-content">
                @for($i = 0; $i < 7; $i++)
                    @php 
                        $day = $weekStart->copy()->addDays($i);
                        $dayAssignments = $assignments->get($day->format('Y-m-d'), collect());
                    @endphp
                    <div class="day-column {{ $day->isToday() ? 'today' : '' }}" data-date="{{ $day->format('Y-m-d') }}">
                        @if($dayAssignments->count() > 0)
                            @foreach($dayAssignments->groupBy('shift_id') as $shiftId => $shiftAssignments)
                                @php $shift = $shiftAssignments->first()->shift @endphp
                                <div class="shift-block" style="background-color: {{ $shift->color_code }}20; border-left-color: {{ $shift->color_code }}">
                                    <div class="shift-header">
                                        <span class="shift-name">{{ $shift->name }}</span>
                                        <span class="shift-time">{{ $shift->start_time }} - {{ $shift->end_time }}</span>
                                    </div>
                                    <div class="shift-assignments">
                                        @foreach($shiftAssignments as $assignment)
                                            <div class="assignment-item status-{{ $assignment->status }}">
                                                <div class="staff-avatar">{{ substr($assignment->staff->full_name, 0, 2) }}</div>
                                                <div class="staff-info">
                                                    <div class="staff-name">{{ $assignment->staff->full_name }}</div>
                                                    @if($assignment->role_assigned)
                                                        <div class="staff-role">{{ $assignment->role_assigned }}</div>
                                                    @endif
                                                </div>
                                                <div class="assignment-actions">
                                                    <button type="button" class="btn-icon-sm" onclick="editAssignment('{{ $assignment->id }}')" title="{{ __('common.edit') }}">
                                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-day">
                                <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                <p>{{ __('admin.staff.shifts.no_assignments') }}</p>
                                <button type="button" class="btn btn-sm btn-outline" onclick="quickAssign('{{ $day->format('Y-m-d') }}')">
                                    {{ __('admin.staff.shifts.assign_staff') }}
                                </button>
                            </div>
                        @endif
                    </div>
                @endfor
            </div>
        </div>
    </div>

    <!-- Pending Requests -->
    @if($pendingRequests['time_off_requests']->count() > 0 || $pendingRequests['shift_swaps']->count() > 0)
        <div class="pending-requests">
            <h3>{{ __('admin.staff.shifts.pending_requests') }}</h3>
            
            <div class="requests-grid">
                @if($pendingRequests['time_off_requests']->count() > 0)
                    <div class="request-section">
                        <h4>{{ __('admin.staff.shifts.time_off_requests') }}</h4>
                        @foreach($pendingRequests['time_off_requests'] as $request)
                            <div class="request-item">
                                <div class="request-info">
                                    <div class="staff-name">{{ $request->staff->full_name }}</div>
                                    <div class="request-details">{{ $request->type }} - {{ $request->start_date->format('M j') }} to {{ $request->end_date->format('M j') }}</div>
                                </div>
                                <div class="request-actions">
                                    <button type="button" class="btn btn-sm btn-success">{{ __('common.approve') }}</button>
                                    <button type="button" class="btn btn-sm btn-danger">{{ __('common.deny') }}</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($pendingRequests['shift_swaps']->count() > 0)
                    <div class="request-section">
                        <h4>{{ __('admin.staff.shifts.shift_swaps') }}</h4>
                        @foreach($pendingRequests['shift_swaps'] as $swap)
                            <div class="request-item">
                                <div class="request-info">
                                    <div class="staff-name">{{ $swap->requestingStaff->full_name }}</div>
                                    <div class="request-details">{{ __('admin.staff.shifts.wants_to_swap') }}</div>
                                </div>
                                <div class="request-actions">
                                    <button type="button" class="btn btn-sm btn-success">{{ __('common.approve') }}</button>
                                    <button type="button" class="btn btn-sm btn-danger">{{ __('common.deny') }}</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

<!-- Assignment Modal -->
<div id="assignmentModal" class="modal-overlay" style="display: none;">
    <div class="modal-container">
        <div class="modal-header">
            <h3 id="assignmentModalTitle">{{ __('admin.staff.shifts.assign_staff') }}</h3>
            <button type="button" class="modal-close" onclick="hideAssignModal()">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form id="assignmentForm" onsubmit="submitAssignment(event)">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="assignDate">{{ __('admin.staff.shifts.date') }} *</label>
                        <input type="date" id="assignDate" name="date" required>
                    </div>
                    <div class="form-group">
                        <label for="assignShift">{{ __('admin.staff.shifts.shift') }} *</label>
                        <select id="assignShift" name="shift_id" required>
                            <option value="">{{ __('admin.staff.shifts.select_shift') }}</option>
                            @foreach($shifts as $shift)
                                <option value="{{ $shift->id }}" data-color="{{ $shift->color_code }}">
                                    {{ $shift->name }} ({{ $shift->start_time }} - {{ $shift->end_time }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="assignStaff">{{ __('admin.staff.shifts.staff_member') }} *</label>
                        <select id="assignStaff" name="staff_id" required>
                            <option value="">{{ __('admin.staff.shifts.select_staff') }}</option>
                            @foreach($staff as $member)
                                <option value="{{ $member->id }}">{{ $member->full_name }} ({{ $member->staffType?->name ?? 'No Type' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="assignRole">{{ __('admin.staff.shifts.role') }}</label>
                        <input type="text" id="assignRole" name="role_assigned" placeholder="{{ __('admin.staff.shifts.role_placeholder') }}">
                    </div>
                    <div class="form-group full-width">
                        <label for="assignNotes">{{ __('admin.staff.shifts.notes') }}</label>
                        <textarea id="assignNotes" name="notes" rows="3" placeholder="{{ __('admin.staff.shifts.notes_placeholder') }}"></textarea>
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="hideAssignModal()">{{ __('common.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('admin.staff.shifts.assign_staff') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Global variables
let currentWeek = '{{ $weekStart->format('Y-m-d') }}';

// Modal functions
function showAssignModal(date = null) {
    const modal = document.getElementById('assignmentModal');
    const dateInput = document.getElementById('assignDate');
    
    if (date) {
        dateInput.value = date;
    }
    
    modal.style.display = 'flex';
}

function hideAssignModal() {
    const modal = document.getElementById('assignmentModal');
    modal.style.display = 'none';
    document.getElementById('assignmentForm').reset();
}

function quickAssign(date) {
    showAssignModal(date);
}

// Week navigation
function changeWeek(direction) {
    const current = new Date(currentWeek);
    current.setDate(current.getDate() + (direction * 7));
    const newWeek = current.toISOString().split('T')[0];
    
    window.location.href = `{{ route('admin.shifts.schedule.index') }}?week=${newWeek}`;
}

// Form submission
function submitAssignment(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData.entries());
    
    fetch('{{ route('admin.shifts.assign') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            hideAssignModal();
            location.reload(); // Refresh to show new assignment
        } else {
            alert(data.message || 'Error assigning staff');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error assigning staff');
    });
}

// Generate from patterns
function generateFromPatterns() {
    if (!confirm('{{ __('admin.staff.shifts.confirm_generate_patterns') }}')) {
        return;
    }
    
    const startDate = currentWeek;
    const endDate = new Date(currentWeek);
    endDate.setDate(endDate.getDate() + 6);
    
    fetch('{{ route('admin.shifts.generate-patterns') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            start_date: startDate,
            end_date: endDate.toISOString().split('T')[0]
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message || 'Error generating assignments');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error generating assignments');
    });
}

// Edit assignment
function editAssignment(assignmentId) {
    // TODO: Implement edit assignment modal
    console.log('Edit assignment:', assignmentId);
}

// Close modal on outside click
document.addEventListener('click', function(event) {
    const modal = document.getElementById('assignmentModal');
    if (event.target === modal) {
        hideAssignModal();
    }
});
</script>
@endpush
