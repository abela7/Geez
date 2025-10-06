@extends('layouts.admin')

@section('title', 'Shift Assignments - Weekly Rota')

@push('styles')
@vite(['resources/css/admin/shifts/assignments.css'])
@endpush

@section('content')
<div class="assignments-page" x-data="shiftsAssignmentsData()">
    <!-- Page Header -->
    <div class="page-header-assignments">
        <div class="header-content">
            <div class="header-text">
                <h1 class="header-title">
                    <svg class="title-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Weekly Shift Assignments
                </h1>
                <p class="header-description">Create and manage staff rota for {{ $weekStart->format('M j') }} - {{ $weekEnd->format('M j, Y') }}</p>
            </div>
            <div class="header-actions">
                <button @click="copyPreviousWeek()" class="btn btn-outline" :disabled="isLoading">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    Copy Previous Week
                </button>
                <a href="{{ route('admin.shifts.manage.create') }}" class="btn btn-primary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Create Shift Template
                </a>
            </div>
        </div>
    </div>

    <!-- Week Navigation -->
    <div class="week-navigation">
        <div class="week-nav-content">
            <div class="week-nav-controls">
                <a href="{{ route('admin.shifts.assignments.index', ['week' => $weekStart->copy()->subWeek()->format('Y-m-d')]) }}" 
                   class="btn-week-nav">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Previous Week
                </a>
                
                <div class="current-week">
                    <h2 class="week-title">{{ $weekStart->format('M j') }} - {{ $weekEnd->format('M j, Y') }}</h2>
                    <p class="week-subtitle">Week {{ $weekStart->weekOfYear }} of {{ $weekStart->year }}</p>
                </div>
                
                <a href="{{ route('admin.shifts.assignments.index', ['week' => $weekStart->copy()->addWeek()->format('Y-m-d')]) }}" 
                   class="btn-week-nav">
                    Next Week
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            
            <div class="week-actions">
                <button @click="showBulkActions = !showBulkActions" class="btn btn-ghost-sm">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                    </svg>
                    Actions
                </button>
            </div>
        </div>
    </div>

    <!-- Bulk Actions Panel -->
    <div x-show="showBulkActions" x-transition class="bulk-actions-panel">
        <div class="bulk-actions-content">
            <h3 class="bulk-actions-title">Bulk Actions</h3>
            <div class="bulk-actions-buttons">
                <button @click="clearWeek()" class="btn btn-warning-outline">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Clear Week
                </button>
                <button @click="publishWeek()" class="btn btn-success-outline">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Publish & Notify Staff
                </button>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <div class="filter-content">
            <div class="filter-group">
                <label class="filter-label">Filter by Department:</label>
                <select x-model="selectedDepartment" @change="filterShifts()" class="filter-select">
                    <option value="">All Departments</option>
                    @foreach($departments as $department)
                    <option value="{{ $department }}">{{ $department }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">Show:</label>
                <select x-model="viewMode" @change="updateView()" class="filter-select">
                    <option value="all">All Shifts</option>
                    <option value="assigned">Assigned Only</option>
                    <option value="unassigned">Unassigned Only</option>
                </select>
            </div>

            <div class="filter-stats">
                <div class="stat-item">
                    <span class="stat-label">Total Shifts:</span>
                    <span class="stat-value" x-text="totalShifts">{{ $shiftTemplates->count() * 7 }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Assigned:</span>
                    <span class="stat-value stat-success" x-text="assignedShifts">{{ $assignments->count() }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Remaining:</span>
                    <span class="stat-value stat-warning" x-text="unassignedShifts">{{ ($shiftTemplates->count() * 7) - $assignments->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly Rota Grid -->
    <div class="rota-container">
        <!-- Days Header -->
        <div class="rota-header">
            <div class="shift-column-header">Shifts</div>
            @foreach($weekDays as $day)
            <div class="day-column-header {{ $day['is_today'] ? 'is-today' : '' }} {{ $day['is_weekend'] ? 'is-weekend' : '' }}">
                <div class="day-name">{{ $day['display'] }}</div>
                <div class="day-date">{{ $day['date']->format('M j') }}</div>
            </div>
            @endforeach
        </div>

        <!-- Shifts Grid -->
        <div class="rota-grid">
            @foreach($shiftTemplates as $shift)
            <div class="shift-row" data-shift-id="{{ $shift->id }}" 
                 data-department="{{ $shift->department }}"
                 x-show="shouldShowShift('{{ $shift->department }}')">
                
                <!-- Shift Info Column -->
                <div class="shift-info-column">
                    <div class="shift-info">
                        <div class="shift-header">
                            <h3 class="shift-name">{{ $shift->name }}</h3>
                            @if($shift->position_name)
                            <span class="shift-position">{{ $shift->position_name }}</span>
                            @endif
                        </div>
                        <div class="shift-meta">
                            <div class="shift-time">
                                <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}
                            </div>
                            <div class="shift-department">
                                <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                {{ $shift->department }}
                            </div>
                            <div class="shift-staff-needed">
                                <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                {{ $shift->min_staff_required }} staff needed
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Day Assignment Columns -->
                @foreach($weekDays as $day)
                <div class="assignment-column {{ $day['is_weekend'] ? 'is-weekend' : '' }}" 
                     data-date="{{ $day['formatted'] }}"
                     data-shift-id="{{ $shift->id }}">
                    
                    @php
                        $dayAssignments = $assignments->get($day['formatted'] . '_' . $shift->id) ?? collect();
                    @endphp

                    <div class="assignment-drop-zone" 
                         @drop="handleDrop($event, '{{ $shift->id }}', '{{ $day['formatted'] }}')"
                         @dragover.prevent
                         @dragenter.prevent>
                        
                        @if($dayAssignments->count() > 0)
                            @foreach($dayAssignments as $assignment)
                            <div class="assigned-staff" 
                                 data-assignment-id="{{ $assignment->id }}">
                                
                                <div class="staff-avatar">
                                    @if($assignment->staff->profile?->photo_url)
                                    <img src="{{ $assignment->staff->profile->photo_url }}" alt="{{ $assignment->staff->full_name }}">
                                    @else
                                    <div class="avatar-placeholder">
                                        {{ substr($assignment->staff->first_name, 0, 1) }}{{ substr($assignment->staff->last_name, 0, 1) }}
                                    </div>
                                    @endif
                                </div>
                                
                                <div class="staff-info">
                                    <div class="staff-name">{{ $assignment->staff->full_name }}</div>
                                    <div class="staff-type">{{ $assignment->staff->staffType?->display_name ?? 'No Type' }}</div>
                                </div>
                                
                                <div class="assignment-actions">
                                    <button @click="removeAssignment('{{ $assignment->id }}')" class="btn-action btn-action-delete" title="Remove">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        @endif
                        
                        @if($dayAssignments->count() < $shift->min_staff_required)
                            <button @click="openAssignStaffModal('{{ $shift->id }}', '{{ $day['formatted'] }}', '{{ $shift->name }}')" 
                                    class="btn-assign-staff">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                <span>Assign Staff</span>
                            </button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach
        </div>
    </div>


    <!-- Loading Overlay -->
    <div x-show="isLoading" class="loading-overlay">
        <div class="loading-spinner">
            <svg class="spinner" viewBox="0 0 50 50">
                <circle class="path" cx="25" cy="25" r="20" fill="none" stroke="currentColor" stroke-width="2" stroke-miterlimit="10"/>
            </svg>
            <p class="loading-text">Processing...</p>
        </div>
    </div>

    <!-- Assign Staff Modal -->
    <div x-show="showAssignStaffModal" x-transition x-cloak class="modal-overlay" @click="closeAssignStaffModal()">
    <div class="modal-content-large" @click.stop>
        <div class="modal-header">
            <div>
                <h3 class="modal-title">Assign Staff to Shift</h3>
                <p class="modal-subtitle" x-text="modalShiftName + ' - ' + formatDate(modalDate)"></p>
            </div>
            <button @click="closeAssignStaffModal()" class="btn-close-modal">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <div class="modal-search">
            <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" x-model="staffSearchQuery" placeholder="Search staff by name or role..." class="modal-search-input">
        </div>
        
        <div class="modal-body-scroll">
            <div class="staff-grid">
                @foreach($staff as $staffMember)
                <div class="staff-card" 
                     x-show="staffMatchesSearch('{{ $staffMember->full_name }}', '{{ $staffMember->staffType?->display_name ?? '' }}')"
                     @click="assignStaffToShift('{{ $staffMember->id }}', '{{ $staffMember->full_name }}')">
                    <div class="staff-card-avatar">
                        @if($staffMember->profile?->photo_url)
                        <img src="{{ $staffMember->profile->photo_url }}" alt="{{ $staffMember->full_name }}">
                        @else
                        <div class="avatar-placeholder-large">
                            {{ substr($staffMember->first_name, 0, 1) }}{{ substr($staffMember->last_name, 0, 1) }}
                        </div>
                        @endif
                    </div>
                    <div class="staff-card-info">
                        <div class="staff-card-name">{{ $staffMember->full_name }}</div>
                        <div class="staff-card-type">{{ $staffMember->staffType?->display_name ?? 'No Type' }}</div>
                        @if($staffMember->profile?->hourly_rate)
                        <div class="staff-card-rate">£{{ number_format($staffMember->profile->hourly_rate, 2) }}/hr</div>
                        @endif
                    </div>
                    <div class="staff-card-action">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <div class="modal-footer">
            <button @click="closeAssignStaffModal()" class="btn btn-ghost">Cancel</button>
        </div>
    </div>
    </div>
</div>
@endsection