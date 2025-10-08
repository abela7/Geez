@extends('layouts.admin')

@section('title', 'Shift Assignments - Weekly Rota')

@push('styles')
@vite(['resources/css/admin/shifts/assignments.css'])
@endpush

@push('scripts')
@vite(['resources/js/admin/shifts/assignments.js'])
@endpush

@section('content')
<div class="assignments-page" x-data="shiftsAssignmentsData()">

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
            <h3 class="bulk-actions-title">Actions</h3>
            <div class="bulk-actions-buttons">
                <!-- Template Actions -->
                <button @click="openApplyTemplateModal()" class="btn btn-secondary" :disabled="isLoading">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Apply Template
                </button>
                <button @click="showSaveTemplateModal = true" class="btn btn-success" :disabled="isLoading">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                    </svg>
                    Save as Template
                </button>
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
                
                <!-- Divider -->
                <div class="actions-divider"></div>
                
                <!-- Bulk Actions -->
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
                            @if($assignment->staff)
                            <div class="assigned-staff" 
                                 data-assignment-id="{{ $assignment->id }}"
                                 x-data="{ removing: false }">
                                
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
                                    <div class="staff-name">{{ $assignment->staff->first_name }}</div>
                                    <div class="staff-type">{{ $assignment->staff->staffType?->display_name ?? 'No Type' }}</div>
                                </div>
                                
                                <div class="assignment-actions">
                                    <button onclick="openAssignmentDetails('{{ $assignment->id }}')" class="btn-action btn-action-view" title="View Details">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                    <button @click="removing = true; removeAssignment('{{ $assignment->id }}')" 
                                            class="btn-action btn-action-delete" 
                                            title="Remove"
                                            x-bind:disabled="removing">
                                        <svg x-show="!removing" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        <svg x-show="removing" class="animate-spin" style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            @endif
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

    <!-- Save as Template Modal -->
    <div x-show="showSaveTemplateModal" x-transition class="modal-overlay" @click="showSaveTemplateModal = false">
        <div class="modal-content" @click.stop>
            <div class="modal-header">
                <h3 class="modal-title">Save as Template</h3>
                <button @click="showSaveTemplateModal = false" class="btn-close-modal">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label for="templateName" class="form-label">Template Name</label>
                    <input type="text" id="templateName" x-model="templateForm.name" class="form-input" placeholder="e.g., Standard Restaurant Week">
                </div>
                
                <div class="form-group">
                    <label for="templateDescription" class="form-label">Description (Optional)</label>
                    <textarea id="templateDescription" x-model="templateForm.description" class="form-textarea" rows="3" placeholder="Describe this template..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="templateType" class="form-label">Template Type</label>
                    <select id="templateType" x-model="templateForm.type" class="form-select">
                        <option value="standard">Standard</option>
                        <option value="holiday">Holiday</option>
                        <option value="seasonal">Seasonal</option>
                        <option value="custom">Custom</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" x-model="templateForm.setAsDefault">
                        <span class="checkbox-text">Set as default template</span>
                    </label>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-ghost" @click="showSaveTemplateModal = false">Cancel</button>
                <button class="btn btn-success" @click="saveTemplate()" :disabled="!templateForm.name || isLoading">
                    <span x-show="!isLoading">Save Template</span>
                    <span x-show="isLoading">Saving...</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Apply Template Modal -->
    <div x-show="showApplyTemplateModal" x-transition class="modal-overlay" @click="showApplyTemplateModal = false">
        <div class="modal-content-large" @click.stop>
            <div class="modal-header">
                <h3 class="modal-title">Apply Template</h3>
                <button @click="showApplyTemplateModal = false" class="btn-close-modal">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="modal-body-scroll">
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" x-model="applyTemplateForm.overwriteExisting">
                        <span class="checkbox-text">Overwrite existing assignments</span>
                    </label>
                    <p class="form-help">If unchecked, existing assignments will be skipped</p>
                </div>

                <div class="templates-list" x-show="templates.length > 0">
                    <h4>Available Templates</h4>
                    <div class="template-cards">
                        <template x-for="template in templates" :key="template.id">
                            <div class="template-card" :class="{ 'selected': applyTemplateForm.templateId === template.id }" @click="applyTemplateForm.templateId = template.id">
                                <div class="template-header">
                                    <div class="template-name" x-text="template.name"></div>
                                    <div class="template-badge" x-text="template.type" :class="'badge-' + template.type"></div>
                                </div>
                                <div class="template-description" x-text="template.description || 'No description'"></div>
                                <div class="template-stats">
                                    <span class="stat">
                                        <svg class="stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <span x-text="template.unique_staff_count + ' staff'"></span>
                                    </span>
                                    <span class="stat">
                                        <svg class="stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span x-text="template.shifts_count + ' shifts'"></span>
                                    </span>
                                    <span class="stat">
                                        <svg class="stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        <span x-text="template.total_assignments + ' assignments'"></span>
                                    </span>
                                </div>
                                <div class="template-meta">
                                    <span>Used <span x-text="template.usage_count"></span> times</span>
                                    <span x-show="template.is_default" class="default-badge">Default</span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div x-show="templates.length === 0 && !isLoading" class="empty-state">
                    <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p>No templates available</p>
                    <p class="empty-subtitle">Create your first template by saving the current week's assignments</p>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-ghost" @click="showApplyTemplateModal = false">Cancel</button>
                <button class="btn btn-primary" @click="applyTemplate()" :disabled="!applyTemplateForm.templateId || isLoading">
                    <span x-show="!isLoading">Apply Template</span>
                    <span x-show="isLoading">Applying...</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
// Global variables for assignment management
let assignmentModal = null;
let currentAssignmentId = null;

// Assignment Details Modal Functions
async function openAssignmentDetails(assignmentId) {
    try {
        showLoading(true);
        const response = await fetch(`/admin/shifts/assignments/${assignmentId}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            currentAssignmentId = assignmentId;
            showAssignmentModal(data.assignment);
        } else {
            showNotification('Failed to load assignment details', 'error');
        }
    } catch (error) {
        console.error('Error loading assignment:', error);
        showNotification('Failed to load assignment details', 'error');
    } finally {
        showLoading(false);
    }
}

function showAssignmentModal(assignment) {
    if (document.getElementById('assignmentDetailsModal')) {
        document.getElementById('assignmentDetailsModal').remove();
    }

    const modalHTML = `
        <div id="assignmentDetailsModal" class="modal-overlay" style="display: flex;">
            <div class="modal-content-large">
                <div class="modal-header">
                    <div>
                        <h2 class="modal-title">Assignment Details</h2>
                        <p class="modal-subtitle">Manage shift assignment for ${formatDate(assignment.date)}</p>
                    </div>
                    <button onclick="closeAssignmentModal()" class="btn-close-modal" aria-label="Close modal">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <div class="modal-body-scroll">
                    <!-- Assignment Overview -->
                    <div class="form-group">
                        <h3 style="margin-bottom: var(--spacing-md); color: var(--color-text-primary);">Overview</h3>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-lg);">
                            <!-- Staff Info -->
                            <div>
                                <h4 style="margin-bottom: var(--spacing-sm); color: var(--color-text-primary);">Staff Information</h4>
                                <div class="staff-avatar" style="width: 4rem; height: 4rem; margin-bottom: var(--spacing-sm);">
                                    ${assignment.staff.photo ? `<img src="${assignment.staff.photo}" alt="${assignment.staff.name}">` : `<div class="avatar-placeholder-large">${assignment.staff.name.charAt(0).toUpperCase()}</div>`}
                                </div>
                                <p><strong>Name:</strong> ${assignment.staff.name}</p>
                                <p><strong>Type:</strong> ${assignment.staff.type || 'N/A'}</p>
                                <p><strong>Status:</strong> 
                                    <select id="assignmentStatus" class="form-select" style="margin-left: 0.5rem; width: auto; display: inline-block;">
                                        <option value="scheduled" ${assignment.status === 'scheduled' ? 'selected' : ''}>Scheduled</option>
                                        <option value="confirmed" ${assignment.status === 'confirmed' ? 'selected' : ''}>Confirmed</option>
                                        <option value="cancelled" ${assignment.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                                        <option value="completed" ${assignment.status === 'completed' ? 'selected' : ''}>Completed</option>
                                    </select>
                                </p>
                                <button onclick="updateAssignmentStatus()" class="btn btn-primary" style="margin-top: 0.5rem;">Update Status</button>
                            </div>
                            
                            <!-- Shift Info -->
                            <div>
                                <h4 style="margin-bottom: var(--spacing-sm); color: var(--color-text-primary);">Shift Details</h4>
                                <p><strong>Shift:</strong> ${assignment.shift.name}</p>
                                <p><strong>Department:</strong> ${assignment.shift.department}</p>
                                <p><strong>Time:</strong> ${assignment.shift.start_time} - ${assignment.shift.end_time}</p>
                                <p><strong>Break:</strong> ${assignment.shift.break_minutes} minutes</p>
                                <p><strong>Min Staff Required:</strong> ${assignment.shift.min_staff_required}</p>
                                ${assignment.notes ? `<p><strong>Notes:</strong> ${assignment.notes}</p>` : ''}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Existing Exceptions -->
                    ${assignment.exceptions && assignment.exceptions.length > 0 ? `
                        <div class="form-group">
                            <h3 style="margin-bottom: var(--spacing-md); color: var(--color-text-primary);">Exceptions History</h3>
                            <div style="display: flex; flex-direction: column; gap: var(--spacing-md);">
                                ${assignment.exceptions.map(ex => `
                                    <div style="background: var(--color-info-bg); padding: var(--spacing-md); border-radius: var(--border-radius); border-left: 4px solid var(--color-info);">
                                        <p><strong>Type:</strong> ${ex.type}</p>
                                        <p><strong>Minutes Affected:</strong> ${ex.minutes_affected || 'N/A'}</p>
                                        <p><strong>Description:</strong> ${ex.description}</p>
                                        ${ex.replacement ? `<p><strong>Replacement:</strong> ${ex.replacement_staff_name}</p>` : ''}
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    ` : ''}
                    
                    <hr style="margin: 2rem 0; opacity: 0.2;">
                    
                    <!-- Actions Grid -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                        <!-- Record Exception -->
                        <div>
                            <h4 style="margin-bottom: 1rem; color: var(--color-text-primary);">Record Exception</h4>
                            <div style="background: var(--color-warning-bg); padding: 1rem; border-radius: var(--border-radius); border-left: 4px solid var(--color-warning);">
                                <div class="form-group">
                                    <label class="form-label">Exception Type:</label>
                                    <select id="exceptionType" class="form-select">
                                        <option value="">Select...</option>
                                        <option value="late_arrival">Late Arrival</option>
                                        <option value="early_departure">Early Departure</option>
                                        <option value="extended_break">Extended Break</option>
                                        <option value="no_show">No-show</option>
                                        <option value="sick_call_out">Sick Call-out</option>
                                        <option value="emergency_leave">Emergency Leave</option>
                                        <option value="overtime">Overtime</option>
                                        <option value="role_change">Role Change</option>
                                        <option value="replacement">Replacement</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Minutes Affected:</label>
                                    <input type="number" id="exceptionMinutes" min="0" class="form-select" placeholder="e.g., 30">
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Description:</label>
                                    <textarea id="exceptionDescription" class="form-textarea" rows="3" placeholder="Describe the exception..."></textarea>
                                </div>
                                
                                <button onclick="submitException()" class="btn btn-warning-outline" style="width: 100%;">Record Exception</button>
                            </div>
                        </div>
                        
                        <!-- Replace Staff -->
                        <div>
                            <h4 style="margin-bottom: 1rem; color: var(--color-text-primary);">Replace Staff</h4>
                            <div style="background: var(--color-error-bg); padding: 1rem; border-radius: var(--border-radius); border-left: 4px solid var(--color-error);">
                                <div class="form-group">
                                    <label class="form-label">Replacement Staff:</label>
                                    <select id="replacementStaff" class="form-select">
                                        <option value="">Select staff...</option>
                                        ${getStaffOptions()}
                                    </select>
                                </div>
                                <button onclick="replaceStaff()" class="btn btn-outline" style="background: var(--color-error); color: white; width: 100%;">Replace Staff</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button onclick="closeAssignmentModal()" class="btn btn-ghost">Close</button>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
}

function getStaffOptions() {
    // Get staff from PHP data - this would be better if we had it as JSON
    const staffOptions = @json($staff->map(function($s) { return ['id' => $s->id, 'name' => $s->full_name, 'type' => $s->staffType?->display_name ?? 'No Type']; }));
    return staffOptions.map(staff => `<option value="${staff.id}">${staff.name} (${staff.type})</option>`).join('');
}

function closeAssignmentModal() {
    const modal = document.getElementById('assignmentDetailsModal');
    if (modal) {
        modal.remove();
    }
    currentAssignmentId = null;
}

async function updateAssignmentStatus() {
    if (!currentAssignmentId) return;
    
    const status = document.getElementById('assignmentStatus').value;
    
    try {
        showLoading(true);
        const response = await fetch(`/admin/shifts/assignments/${currentAssignmentId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: status })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification('Status updated successfully', 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showNotification('Failed to update status', 'error');
        }
    } catch (error) {
        showNotification('Failed to update status', 'error');
    } finally {
        showLoading(false);
    }
}

async function submitException() {
    if (!currentAssignmentId) return;
    
    const exceptionType = document.getElementById('exceptionType').value;
    const minutes = document.getElementById('exceptionMinutes').value;
    const description = document.getElementById('exceptionDescription').value;
    
    if (!exceptionType) {
        showNotification('Please select an exception type', 'warning');
        return;
    }
    
    try {
        showLoading(true);
        const response = await fetch(`/admin/shifts/assignments/${currentAssignmentId}/exceptions`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                exception_type: exceptionType,
                minutes_affected: parseInt(minutes) || 0,
                description: description || 'Exception recorded'
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification('Exception recorded successfully', 'success');
            closeAssignmentModal();
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showNotification('Failed to record exception', 'error');
        }
    } catch (error) {
        showNotification('Failed to record exception', 'error');
    } finally {
        showLoading(false);
    }
}

async function replaceStaff() {
    if (!currentAssignmentId) return;
    
    const replacementId = document.getElementById('replacementStaff').value;
    
    if (!replacementId) {
        showNotification('Please select a replacement staff member', 'warning');
        return;
    }
    
    try {
        showLoading(true);
        const response = await fetch(`/admin/shifts/assignments/${currentAssignmentId}/replace`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                replacement_staff_id: replacementId,
                notes: 'Staff replaced via rota interface'
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification('Staff replaced successfully', 'success');
            closeAssignmentModal();
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showNotification('Failed to replace staff', 'error');
        }
    } catch (error) {
        showNotification('Failed to replace staff', 'error');
    } finally {
        showLoading(false);
    }
}

async function removeAssignmentConfirm(assignmentId) {
    if (!confirm('Are you sure you want to remove this assignment?')) {
        return;
    }
    
    try {
        showLoading(true);
        const response = await fetch(`/admin/shifts/assignments/${assignmentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification('Assignment removed successfully', 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showNotification('Failed to remove assignment', 'error');
        }
    } catch (error) {
        showNotification('Failed to remove assignment', 'error');
    } finally {
        showLoading(false);
    }
}

// Utility functions
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-GB', { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
}

function showLoading(show) {
    let loader = document.getElementById('globalLoader');
    if (show) {
        if (!loader) {
            loader = document.createElement('div');
            loader.id = 'globalLoader';
            loader.innerHTML = `
                <div style="position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);display:flex;align-items:center;justify-content:center;z-index:9999;">
                    <div style="background:white;padding:2rem;border-radius:8px;text-align:center;">
                        <div style="width:40px;height:40px;border:4px solid #f3f3f3;border-top:4px solid #3498db;border-radius:50%;animation:spin 1s linear infinite;margin:0 auto 1rem;"></div>
                        <p>Loading...</p>
                    </div>
                </div>
                <style>
                    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
                </style>
            `;
            document.body.appendChild(loader);
        }
    } else {
        if (loader) {
            loader.remove();
        }
    }
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 10000;
        max-width: 400px;
        padding: 1rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        color: white;
        font-weight: 500;
        animation: slideIn 0.3s ease-out;
        background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : type === 'warning' ? '#f59e0b' : '#3b82f6'};
    `;
    
    notification.innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" style="background:none;border:none;color:white;font-size:1.5rem;cursor:pointer;margin-left:1rem;">&times;</button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}

// Add slide in animation
if (!document.getElementById('notificationStyles')) {
    const style = document.createElement('style');
    style.id = 'notificationStyles';
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    `;
    document.head.appendChild(style);
}
</script>

<!-- Keep existing Alpine.js scripts -->
<script>
document.addEventListener('alpine:init', () => {
    if (typeof window.shiftsAssignmentsData === 'function') {
        const original = window.shiftsAssignmentsData;
        window.shiftsAssignmentsData = function() {
            const state = original();
            return Object.assign(state, {
                showAssignmentModal: false,
                assignmentDetails: null,
                replacementStaffId: '',
                assignmentStatus: '',
                exceptionType: '',
                exceptionMinutes: 0,
                exceptionDescription: '',
                
                // Template functionality
                showSaveTemplateModal: false,
                showApplyTemplateModal: false,
                templates: [],
                templateForm: {
                    name: '',
                    description: '',
                    type: 'standard',
                    setAsDefault: false
                },
                applyTemplateForm: {
                    templateId: '',
                    overwriteExisting: false
                },
                
                async openAssignmentModal(assignmentId) {
                    this.isLoading = true;
                    try {
                        const res = await fetch(`/admin/shifts/assignments/${assignmentId}`);
                        const data = await res.json();
                        if (data.success) {
                            this.assignmentDetails = data.assignment;
                            this.assignmentStatus = data.assignment.status;
                            this.showAssignmentModal = true;
                        }
                    } catch (e) {
                        alert('Failed to load assignment details');
                    } finally {
                        this.isLoading = false;
                    }
                },
                closeAssignmentModal() {
                    this.showAssignmentModal = false;
                    this.assignmentDetails = null;
                    this.replacementStaffId = '';
                    this.exceptionType = '';
                    this.exceptionMinutes = 0;
                    this.exceptionDescription = '';
                },
                async saveAssignmentStatus() {
                    if (!this.assignmentDetails) return;
                    try {
                        const res = await fetch(`/admin/shifts/assignments/${this.assignmentDetails.id}`, {
                            method: 'PUT',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                            body: JSON.stringify({ status: this.assignmentStatus })
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.showNotification('Assignment updated', 'success');
                            this.closeAssignmentModal();
                            this.refreshAssignments();
                        }
                    } catch (e) { this.showNotification('Update failed', 'error'); }
                },
                async submitException() {
                    if (!this.assignmentDetails || !this.exceptionType) return;
                    try {
                        const res = await fetch(`/admin/shifts/assignments/${this.assignmentDetails.id}/exceptions`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                            body: JSON.stringify({
                                exception_type: this.exceptionType,
                                minutes_affected: this.exceptionMinutes || 0,
                                description: this.exceptionDescription || 'Exception reported'
                            })
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.showNotification('Exception recorded', 'success');
                            this.closeAssignmentModal();
                            this.refreshAssignments();
                        }
                    } catch (e) { this.showNotification('Failed to record exception', 'error'); }
                },
                async replaceAssignedStaff() {
                    if (!this.assignmentDetails || !this.replacementStaffId) return;
                    try {
                        const res = await fetch(`/admin/shifts/assignments/${this.assignmentDetails.id}/replace`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                            body: JSON.stringify({
                                replacement_staff_id: this.replacementStaffId,
                                notes: 'Replacement via rota UI'
                            })
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.showNotification('Staff replaced', 'success');
                            this.closeAssignmentModal();
                            this.refreshAssignments();
                        }
                    } catch (e) { this.showNotification('Replacement failed', 'error'); }
                },

                // Template Methods
                async loadTemplates() {
                    try {
                        const response = await fetch('/admin/shifts/assignments/templates');
                        const data = await response.json();
                        if (data.success) {
                            this.templates = data.templates;
                        }
                    } catch (e) {
                        console.error('Failed to load templates:', e);
                    }
                },

                async saveTemplate() {
                    if (!this.templateForm.name.trim()) return;
                    
                    this.isLoading = true;
                    try {
                        const response = await fetch('/admin/shifts/assignments/save-as-template', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                name: this.templateForm.name,
                                description: this.templateForm.description,
                                type: this.templateForm.type,
                                week_start: '{{ $weekStart->format("Y-m-d") }}',
                                set_as_default: this.templateForm.setAsDefault
                            })
                        });

                        const data = await response.json();
                        if (data.success) {
                            this.showNotification(data.message, 'success');
                            this.showSaveTemplateModal = false;
                            this.resetTemplateForm();
                            this.loadTemplates(); // Refresh templates list
                        } else {
                            this.showNotification(data.message, 'error');
                        }
                    } catch (e) {
                        this.showNotification('Failed to save template', 'error');
                    } finally {
                        this.isLoading = false;
                    }
                },

                async applyTemplate() {
                    if (!this.applyTemplateForm.templateId) return;
                    
                    this.isLoading = true;
                    try {
                        const response = await fetch('/admin/shifts/assignments/apply-template', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                template_id: this.applyTemplateForm.templateId,
                                week_start: '{{ $weekStart->format("Y-m-d") }}',
                                overwrite_existing: this.applyTemplateForm.overwriteExisting
                            })
                        });

                        const data = await response.json();
                        if (data.success) {
                            this.showNotification(data.message, 'success');
                            this.showApplyTemplateModal = false;
                            this.resetApplyTemplateForm();
                            // Refresh the page to show new assignments
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            this.showNotification(data.message, 'error');
                        }
                    } catch (e) {
                        this.showNotification('Failed to apply template', 'error');
                    } finally {
                        this.isLoading = false;
                    }
                },

                resetTemplateForm() {
                    this.templateForm = {
                        name: '',
                        description: '',
                        type: 'standard',
                        setAsDefault: false
                    };
                },

                resetApplyTemplateForm() {
                    this.applyTemplateForm = {
                        templateId: '',
                        overwriteExisting: false
                    };
                },

                // Initialize templates when apply modal opens
                async openApplyTemplateModal() {
                    this.showApplyTemplateModal = true;
                    await this.loadTemplates();
                }
            });
        };
    }
});
</script>

<!-- Assignment Details Modal -->
<div x-show="showAssignmentModal" x-transition x-cloak class="modal-overlay" @click="closeAssignmentModal()">
    <div class="modal-content-large" @click.stop>
        <div class="modal-header">
            <div>
                <h3 class="modal-title">Assignment Details</h3>
                <p class="modal-subtitle" x-text="assignmentDetails ? (assignmentDetails.shift.name + ' • ' + assignmentDetails.date) : ''"></p>
            </div>
            <button @click="closeAssignmentModal()" class="btn-close-modal">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="modal-body-scroll">
            <div class="grid" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div>
                    <h4>Shift</h4>
                    <p><strong>Time:</strong> <span x-text="assignmentDetails ? (assignmentDetails.shift.start_time + ' - ' + assignmentDetails.shift.end_time) : ''"></span></p>
                    <p><strong>Department:</strong> <span x-text="assignmentDetails?.shift.department"></span></p>
                    <p><strong>Staff Needed:</strong> <span x-text="assignmentDetails?.shift.min_staff_required"></span></p>
                </div>
                <div>
                    <h4>Staff</h4>
                    <p><strong>Name:</strong> <span x-text="assignmentDetails?.staff.name"></span></p>
                    <p><strong>Type:</strong> <span x-text="assignmentDetails?.staff.type || 'N/A'"></span></p>
                    <p><strong>Status:</strong>
                        <select x-model="assignmentStatus" class="filter-select">
                            <option value="scheduled">Scheduled</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="completed">Completed</option>
                        </select>
                    </p>
                    <button class="btn btn-outline" @click="saveAssignmentStatus()">Save Status</button>
                </div>
            </div>

            <hr style="margin:1rem 0;opacity:.2;">

            <div class="grid" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div>
                    <h4>Exceptions</h4>
                    <label>Type</label>
                    <select x-model="exceptionType" class="filter-select">
                        <option value="">Select...</option>
                        <option value="late_arrival">Late Arrival</option>
                        <option value="early_departure">Early Departure</option>
                        <option value="no_show">No-show</option>
                        <option value="replacement">Replacement</option>
                        <option value="overtime">Overtime</option>
                        <option value="other">Other</option>
                    </select>
                    <label>Minutes Affected</label>
                    <input type="number" x-model.number="exceptionMinutes" min="0" class="filter-input-modern">
                    <label>Description</label>
                    <textarea x-model="exceptionDescription" rows="3" class="filter-input-modern"></textarea>
                    <button class="btn btn-primary" @click="submitException()">Record Exception</button>
                </div>
                <div>
                    <h4>Replace Staff</h4>
                    <label>Replacement Staff</label>
                    <select x-model="replacementStaffId" class="filter-select">
                        <option value="">Select staff...</option>
                        @foreach($staff as $s)
                        <option value="{{ $s->id }}">{{ $s->full_name }} ({{ $s->staffType?->display_name ?? 'No Type' }})</option>
                        @endforeach
                    </select>
                    <button class="btn btn-warning-outline" style="margin-top:.5rem" @click="replaceAssignedStaff()">Replace</button>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button class="btn btn-ghost" @click="closeAssignmentModal()">Close</button>
        </div>
    </div>
</div>

@endpush