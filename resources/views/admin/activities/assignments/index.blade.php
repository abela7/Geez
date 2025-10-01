@extends('layouts.admin')

@section('title', __('activities.assignments.title'))

@section('content')
<div class="activities-assignments-page" x-data="assignmentsData()">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-header-left">
                <h1 class="page-title">{{ __('activities.assignments.title') }}</h1>
                <p class="page-description">{{ __('activities.assignments.subtitle') }}</p>
            </div>
            <div class="page-header-right">
                <button class="btn btn-secondary" @click="showBulkAssignModal = true">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    {{ __('activities.assignments.bulk_assignment') }}
                </button>
                <button class="btn btn-primary" @click="showAssignModal = true">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('activities.assignments.assign_activities') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Assignment Stats -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon stat-icon-assignments">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $assignmentStats['total_assignments'] }}</div>
                    <div class="stat-label">{{ __('activities.assignments.total_assignments') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon stat-icon-staff">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $assignmentStats['staff_with_assignments'] }}</div>
                    <div class="stat-label">{{ __('activities.assignments.staff_with_assignments') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon stat-icon-activities">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $assignmentStats['activities_assigned'] }}</div>
                    <div class="stat-label">{{ __('activities.assignments.activities_assigned') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon stat-icon-average">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ number_format($assignmentStats['avg_assignments_per_staff'], 1) }}</div>
                    <div class="stat-label">{{ __('activities.assignments.avg_per_staff') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Current Assignments Table -->
        <div class="assignments-section">
            <div class="section-header">
                <h2 class="section-title">{{ __('activities.assignments.current_assignments') }}</h2>
                <div class="section-controls">
                    <div class="filter-group">
                        <select x-model="filterDepartment" @change="applyFilters()" class="filter-select">
                            <option value="all">{{ __('activities.assignments.all_departments') }}</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept['name'] }}">{{ $dept['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <select x-model="filterActivity" @change="applyFilters()" class="filter-select">
                            <option value="all">{{ __('activities.assignments.all_activities') }}</option>
                            @foreach($availableActivities as $activity)
                                <option value="{{ $activity['id'] }}">{{ $activity['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="assignments-table-container">
                <table class="assignments-table">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" x-model="selectAll" @change="toggleSelectAll()" class="checkbox">
                            </th>
                            <th>{{ __('activities.assignments.staff_member') }}</th>
                            <th>{{ __('activities.assignments.activity') }}</th>
                            <th>{{ __('activities.assignments.department') }}</th>
                            <th>{{ __('activities.assignments.assigned_date') }}</th>
                            <th>{{ __('activities.assignments.completion_count') }}</th>
                            <th>{{ __('activities.assignments.last_completed') }}</th>
                            <th>{{ __('activities.assignments.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($currentAssignments as $assignment)
                        <tr x-show="isAssignmentVisible({{ json_encode($assignment) }})"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100">
                            <td>
                                <input type="checkbox" 
                                       x-model="selectedAssignments" 
                                       value="{{ $assignment['id'] }}" 
                                       class="checkbox">
                            </td>
                            <td>
                                <div class="staff-info">
                                    <div class="staff-name">{{ $assignment['staff_name'] }}</div>
                                    <div class="staff-role">{{ $assignment['staff_role'] }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="activity-info">
                                    <div class="activity-name">{{ $assignment['activity_name'] }}</div>
                                    <div class="activity-category">{{ $assignment['activity_category'] }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="department-badge department-{{ strtolower(str_replace(' ', '-', $assignment['department'])) }}">
                                    {{ $assignment['department'] }}
                                </div>
                            </td>
                            <td>
                                <div class="date-info">
                                    <div class="date-value">{{ \Carbon\Carbon::parse($assignment['assigned_at'])->format('M d, Y') }}</div>
                                    <div class="date-relative">{{ \Carbon\Carbon::parse($assignment['assigned_at'])->diffForHumans() }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="completion-count">{{ $assignment['completion_count'] }}</div>
                            </td>
                            <td>
                                @if($assignment['last_completed'])
                                    <div class="date-info">
                                        <div class="date-value">{{ \Carbon\Carbon::parse($assignment['last_completed'])->format('M d, H:i') }}</div>
                                        <div class="date-relative">{{ \Carbon\Carbon::parse($assignment['last_completed'])->diffForHumans() }}</div>
                                    </div>
                                @else
                                    <span class="text-muted">{{ __('activities.assignments.never') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-danger" @click="unassignActivity({{ $assignment['id'] }})">
                                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        {{ __('activities.assignments.unassign') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Bulk Actions -->
            <div class="bulk-actions" x-show="selectedAssignments.length > 0">
                <div class="bulk-actions-content">
                    <span class="bulk-count" x-text="`${selectedAssignments.length} selected`"></span>
                    <button class="btn btn-sm btn-danger" @click="bulkUnassign()">
                        {{ __('activities.assignments.unassign_selected') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Assignment Rules Section -->
        <div class="rules-section">
            <div class="section-header">
                <h2 class="section-title">{{ __('activities.assignments.assignment_rules') }}</h2>
                <button class="btn btn-secondary" @click="showRuleModal = true">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('activities.assignments.create_assignment_rule') }}
                </button>
            </div>
            
            <div class="rules-grid">
                @foreach($assignmentRules as $rule)
                <div class="rule-card">
                    <div class="rule-header">
                        <div class="rule-info">
                            <h3 class="rule-name">{{ $rule['name'] }}</h3>
                            <div class="rule-type">{{ __('activities.assignments.' . $rule['type']) }}</div>
                        </div>
                        <div class="rule-status">
                            <div class="status-indicator status-{{ $rule['is_active'] ? 'active' : 'inactive' }}">
                                {{ $rule['is_active'] ? __('activities.common.active') : __('activities.common.inactive') }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="rule-stats">
                        <div class="rule-stat">
                            <div class="stat-value">{{ $rule['activity_count'] }}</div>
                            <div class="stat-label">{{ __('activities.assignments.activities') }}</div>
                        </div>
                        <div class="rule-stat">
                            <div class="stat-value">{{ $rule['affected_staff'] }}</div>
                            <div class="stat-label">{{ __('activities.assignments.staff_affected') }}</div>
                        </div>
                    </div>
                    
                    <div class="rule-actions">
                        <button class="btn btn-sm btn-secondary" @click="editRule({{ json_encode($rule) }})">
                            {{ __('activities.common.edit') }}
                        </button>
                        <button class="btn btn-sm btn-danger" @click="deleteRule({{ $rule['id'] }})">
                            {{ __('activities.common.delete') }}
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Assignment Modal -->
    <div x-show="showAssignModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="modal-overlay" 
         @click="closeAssignModal()"
         style="display: none;">
        <div class="modal-content modal-content-large" @click.stop>
            <div class="modal-header">
                <h3 class="modal-title">{{ __('activities.assignments.assign_activities') }}</h3>
                <button class="modal-close" @click="closeAssignModal()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form @submit.prevent="assignActivities()" class="modal-form">
                <div class="form-tabs">
                    <button type="button" 
                            class="tab-button" 
                            :class="{ 'active': assignmentTab === 'individual' }"
                            @click="assignmentTab = 'individual'">
                        {{ __('activities.assignments.individual_assignment') }}
                    </button>
                    <button type="button" 
                            class="tab-button" 
                            :class="{ 'active': assignmentTab === 'role' }"
                            @click="assignmentTab = 'role'">
                        {{ __('activities.assignments.role_based_assignment') }}
                    </button>
                    <button type="button" 
                            class="tab-button" 
                            :class="{ 'active': assignmentTab === 'department' }"
                            @click="assignmentTab = 'department'">
                        {{ __('activities.assignments.department_based_assignment') }}
                    </button>
                </div>
                
                <!-- Individual Assignment Tab -->
                <div x-show="assignmentTab === 'individual'" class="tab-content">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">{{ __('activities.assignments.select_activities') }}</label>
                            <div class="checkbox-group">
                                @foreach($availableActivities as $activity)
                                <label class="checkbox-label">
                                    <input type="checkbox" 
                                           x-model="assignForm.activityIds" 
                                           value="{{ $activity['id'] }}" 
                                           class="checkbox">
                                    <span class="checkbox-text">
                                        <strong>{{ $activity['name'] }}</strong>
                                        <small>{{ $activity['category'] }} • {{ $activity['estimated_duration'] }}min</small>
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">{{ __('activities.assignments.select_staff') }}</label>
                            <div class="checkbox-group">
                                @foreach($staffMembers as $staff)
                                <label class="checkbox-label">
                                    <input type="checkbox" 
                                           x-model="assignForm.staffIds" 
                                           value="{{ $staff['id'] }}" 
                                           class="checkbox">
                                    <span class="checkbox-text">
                                        <strong>{{ $staff['name'] }}</strong>
                                        <small>{{ $staff['role'] }} • {{ $staff['department'] }}</small>
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Role-based Assignment Tab -->
                <div x-show="assignmentTab === 'role'" class="tab-content">
                    <div class="form-group">
                        <label class="form-label">{{ __('activities.assignments.select_role') }}</label>
                        <select x-model="assignForm.role" class="form-select">
                            <option value="">{{ __('activities.assignments.choose_role') }}</option>
                            <option value="Head Chef">Head Chef</option>
                            <option value="Kitchen Staff">Kitchen Staff</option>
                            <option value="Server">Server</option>
                            <option value="Bartender">Bartender</option>
                            <option value="Manager">Manager</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">{{ __('activities.assignments.select_activities') }}</label>
                        <div class="checkbox-group">
                            @foreach($availableActivities as $activity)
                            <label class="checkbox-label">
                                <input type="checkbox" 
                                       x-model="assignForm.activityIds" 
                                       value="{{ $activity['id'] }}" 
                                       class="checkbox">
                                <span class="checkbox-text">
                                    <strong>{{ $activity['name'] }}</strong>
                                    <small>{{ $activity['category'] }} • {{ $activity['estimated_duration'] }}min</small>
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Department-based Assignment Tab -->
                <div x-show="assignmentTab === 'department'" class="tab-content">
                    <div class="form-group">
                        <label class="form-label">{{ __('activities.assignments.select_department') }}</label>
                        <select x-model="assignForm.department" class="form-select">
                            <option value="">{{ __('activities.assignments.choose_department') }}</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept['name'] }}">{{ $dept['name'] }} ({{ $dept['staff_count'] }} staff)</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">{{ __('activities.assignments.select_activities') }}</label>
                        <div class="checkbox-group">
                            @foreach($availableActivities as $activity)
                            <label class="checkbox-label">
                                <input type="checkbox" 
                                       x-model="assignForm.activityIds" 
                                       value="{{ $activity['id'] }}" 
                                       class="checkbox">
                                <span class="checkbox-text">
                                    <strong>{{ $activity['name'] }}</strong>
                                    <small>{{ $activity['category'] }} • {{ $activity['estimated_duration'] }}min</small>
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" @click="closeAssignModal()">
                        {{ __('activities.common.cancel') }}
                    </button>
                    <button type="submit" 
                            class="btn btn-primary"
                            :disabled="isAssigning"
                            :class="{ 'loading': isAssigning }">
                        <span x-show="!isAssigning">{{ __('activities.assignments.assign') }}</span>
                        <span x-show="isAssigning">{{ __('activities.assignments.assigning') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
@vite('resources/css/admin/activities/assignments.css')
@endpush

@push('scripts')
@vite('resources/js/admin/activities/assignments.js')
<script>
function assignmentsData() {
    return {
        filterDepartment: 'all',
        filterActivity: 'all',
        selectAll: false,
        selectedAssignments: [],
        showAssignModal: false,
        showBulkAssignModal: false,
        showRuleModal: false,
        assignmentTab: 'individual',
        isAssigning: false,
        
        assignForm: {
            activityIds: [],
            staffIds: [],
            role: '',
            department: '',
            assignmentType: 'individual'
        },
        
        applyFilters() {
            // In real implementation, this would filter the assignments table
            console.log('Applying filters:', this.filterDepartment, this.filterActivity);
        },
        
        isAssignmentVisible(assignment) {
            if (this.filterDepartment !== 'all' && assignment.department !== this.filterDepartment) {
                return false;
            }
            if (this.filterActivity !== 'all' && assignment.activity_id != this.filterActivity) {
                return false;
            }
            return true;
        },
        
        toggleSelectAll() {
            if (this.selectAll) {
                // Select all visible assignments
                this.selectedAssignments = @json($currentAssignments).map(a => a.id);
            } else {
                this.selectedAssignments = [];
            }
        },
        
        closeAssignModal() {
            this.showAssignModal = false;
            this.assignForm = {
                activityIds: [],
                staffIds: [],
                role: '',
                department: '',
                assignmentType: 'individual'
            };
        },
        
        assignActivities() {
            this.isAssigning = true;
            this.assignForm.assignmentType = this.assignmentTab;
            
            // Simulate assignment process
            setTimeout(() => {
                this.showNotification('Activities assigned successfully!', 'success');
                this.closeAssignModal();
                this.isAssigning = false;
                // In real implementation, refresh the assignments table
                setTimeout(() => window.location.reload(), 1000);
            }, 2000);
        },
        
        unassignActivity(assignmentId) {
            if (!confirm('Are you sure you want to unassign this activity?')) {
                return;
            }
            
            this.showNotification('Activity unassigned successfully!', 'success');
            // In real implementation, make API call and update table
        },
        
        bulkUnassign() {
            if (!confirm(`Are you sure you want to unassign ${this.selectedAssignments.length} activities?`)) {
                return;
            }
            
            this.showNotification(`${this.selectedAssignments.length} activities unassigned successfully!`, 'success');
            this.selectedAssignments = [];
            this.selectAll = false;
            // In real implementation, make API call and update table
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
