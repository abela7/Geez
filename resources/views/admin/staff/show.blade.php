@extends('layouts.admin')

@section('title', $staff->full_name . ' - ' . __('staff.show') . ' - ' . config('app.name'))
@section('page_title', $staff->full_name)

@push('styles')
@vite(['resources/css/admin/staff.css'])
@endpush

@push('scripts')
@vite(['resources/js/admin/staff.js'])
@endpush

@section('content')
<div class="staff-profile-page">
    <!-- Breadcrumbs -->
    <nav class="breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb-list">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">
                    <i class="fas fa-home"></i>{{ __('common.dashboard') }}
                </a>
            </li>
            <li class="breadcrumb-item">
                <i class="fas fa-chevron-right breadcrumb-separator"></i>
                <a href="{{ route('admin.staff.directory.index') }}" class="breadcrumb-link">{{ __('staff.nav_directory') }}</a>
            </li>
            <li class="breadcrumb-item breadcrumb-current" aria-current="page">
                <i class="fas fa-chevron-right breadcrumb-separator"></i>
                <span>{{ $staff->full_name }}</span>
            </li>
        </ol>
    </nav>

    <!-- Staff Header -->
    <div class="staff-profile-header">
        <div class="staff-header-content">
            <div class="staff-header-info">
                <!-- Profile Photo -->
                <div class="staff-avatar-large">
                    @if ($staff->profile && $staff->profile->photo_url)
                        <img src="{{ $staff->profile->photo_url }}" alt="{{ $staff->full_name }}" class="staff-avatar-image" />
                    @else
                        <i class="fas fa-user staff-avatar-icon"></i>
    @endif
        </div>
                
                <!-- Basic Info -->
                <div class="staff-header-details">
                    <h1 class="staff-name">{{ $staff->full_name }}</h1>
                    <div class="staff-meta">
                        <span class="staff-meta-item">
                            <i class="fas fa-id-badge"></i>
                            {{ $staff->staffType?->display_name ?? __('staff.staff_type') }}
                        </span>
                        @if ($staff->profile && $staff->profile->employee_id)
                        <span class="staff-meta-item">
                            <i class="fas fa-hashtag"></i>
                            {{ $staff->profile->employee_id }}
                        </span>
                        @endif
                        <span class="staff-meta-item">
                            <i class="fas fa-calendar"></i>
                            {{ $stats['years_of_service'] }} {{ __('staff.years_of_service') }}
                        </span>
                    </div>
                    
                    <!-- Status Badge -->
                    <span class="employee-status {{ $staff->status }}">
                        <i class="fas fa-circle"></i>
                            {{ __('staff.status_values.' . $staff->status) }}
                        </span>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="staff-header-actions">
                <a href="{{ route('admin.staff.edit', $staff) }}" class="btn-secondary">
                    <i class="fas fa-edit"></i>{{ __('staff.edit_staff') }}
                </a>
                <button type="button" class="btn-primary" onclick="alert('{{ __('common.coming_soon') }}')">
                    <i class="fas fa-tasks"></i>{{ __('staff.assign_task') }}
                </button>
                </div>
            </div>
        </div>

    <!-- Statistics Cards -->
    <div class="staff-stats-grid">
        <!-- Hours This Month -->
        <div class="stat-card staff-stat-hours">
            <div class="stat-card-content">
                <div class="stat-card-info">
                    <p class="stat-card-label">{{ __('staff.total_hours') }} ({{ __('common.this_month') }})</p>
                    <p class="stat-card-value">{{ number_format($stats['total_hours_this_month'], 1) }}h</p>
                    </div>
                <div class="stat-card-icon">
                    <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>

        <!-- Attendance Rate -->
        <div class="stat-card staff-stat-attendance">
            <div class="stat-card-content">
                <div class="stat-card-info">
                    <p class="stat-card-label">{{ __('staff.attendance_rate') }}</p>
                    <p class="stat-card-value">{{ $stats['attendance_rate'] }}%</p>
                    </div>
                <div class="stat-card-icon">
                    <i class="fas fa-user-check"></i>
                    </div>
                </div>
            </div>

        <!-- Task Completion -->
        <div class="stat-card staff-stat-tasks">
            <div class="stat-card-content">
                <div class="stat-card-info">
                    <p class="stat-card-label">{{ __('staff.task_completion_rate') }}</p>
                    <p class="stat-card-value">{{ $stats['task_completion_rate'] }}%</p>
                    </div>
                <div class="stat-card-icon">
                    <i class="fas fa-tasks"></i>
                    </div>
                </div>
            </div>

        <!-- Performance Rating -->
        <div class="stat-card staff-stat-performance">
            <div class="stat-card-content">
                <div class="stat-card-info">
                    <p class="stat-card-label">{{ __('staff.performance_review') }}</p>
                    <p class="stat-card-value">
                        @if ($stats['average_performance_rating'])
                            {{ number_format($stats['average_performance_rating'], 1) }}/5
                            @else
                            —
                            @endif
                    </p>
                    </div>
                <div class="stat-card-icon">
                    <i class="fas fa-star"></i>
                    </div>
                    </div>
                </div>
            </div>

    <!-- Main Content Tabs -->
    <div x-data="{ activeTab: 'overview' }" class="staff-profile-tabs">
        <!-- Tab Navigation -->
        <div class="staff-nav-tabs">
            <nav class="staff-nav-list" aria-label="Tabs">
                <button @click="activeTab = 'overview'" :class="{ 'active': activeTab === 'overview' }" class="staff-nav-tab">
                    <i class="fas fa-user"></i>{{ __('staff.overview') }}
                </button>
                <button @click="activeTab = 'attendance'" :class="{ 'active': activeTab === 'attendance' }" class="staff-nav-tab">
                    <i class="fas fa-calendar-check"></i>{{ __('staff.nav_attendance') }}
                </button>
                <button @click="activeTab = 'tasks'" :class="{ 'active': activeTab === 'tasks' }" class="staff-nav-tab">
                    <i class="fas fa-tasks"></i>{{ __('staff.nav_tasks') }}
                </button>
                <button @click="activeTab = 'payroll'" :class="{ 'active': activeTab === 'payroll' }" class="staff-nav-tab">
                    <i class="fas fa-money-bill-wave"></i>{{ __('staff.nav_payroll') }}
                </button>
                <button @click="activeTab = 'performance'" :class="{ 'active': activeTab === 'performance' }" class="staff-nav-tab">
                    <i class="fas fa-chart-line"></i>{{ __('staff.nav_performance') }}
                </button>
                <button @click="activeTab = 'shifts'" :class="{ 'active': activeTab === 'shifts' }" class="staff-nav-tab">
                    <i class="fas fa-clock"></i>{{ __('staff.nav_shifts') }}
                </button>
            </nav>
                        </div>

        <!-- Tab Content -->
        <div class="staff-tab-content">
            <!-- Overview Tab -->
            <div x-show="activeTab === 'overview'" x-transition>
                @include('admin.staff.partials.profile-overview')
                        </div>

            <!-- Attendance Tab -->
            <div x-show="activeTab === 'attendance'" x-transition>
                @include('admin.staff.partials.profile-attendance')
                    </div>

            <!-- Tasks Tab -->
            <div x-show="activeTab === 'tasks'" x-transition>
                <div class="tasks-section">
                    <div class="section-header">
                        <h3 class="section-title">{{ __('staff.tasks.assigned_tasks') }}</h3>
                        <div class="section-actions">
                            <span class="task-count">{{ $activeTasks->count() }} {{ __('staff.tasks.active_tasks') }}</span>
                            <div class="task-filter-dropdown">
                                <select id="taskFilter" class="filter-select" onchange="filterTasks(this.value)">
                                    <option value="today" {{ request('task_filter', 'today') === 'today' ? 'selected' : '' }}>
                                        {{ __('staff.tasks.today') }}
                                    </option>
                                    <option value="this_week" {{ request('task_filter') === 'this_week' ? 'selected' : '' }}>
                                        {{ __('staff.tasks.this_week') }}
                                    </option>
                                    <option value="pending" {{ request('task_filter') === 'pending' ? 'selected' : '' }}>
                                        {{ __('staff.tasks.pending') }}
                                    </option>
                                    <option value="in_progress" {{ request('task_filter') === 'in_progress' ? 'selected' : '' }}>
                                        {{ __('staff.tasks.in_progress') }}
                                    </option>
                                    <option value="completed" {{ request('task_filter') === 'completed' ? 'selected' : '' }}>
                                        {{ __('staff.tasks.completed') }}
                                    </option>
                                    <option value="overdue" {{ request('task_filter') === 'overdue' ? 'selected' : '' }}>
                                        {{ __('staff.tasks.overdue') }}
                                    </option>
                                    <option value="all" {{ request('task_filter') === 'all' ? 'selected' : '' }}>
                                        {{ __('staff.tasks.all_tasks') }}
                                    </option>
                                </select>
                                <svg class="filter-dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    @if($activeTasks->count() > 0)
                        <div class="tasks-list">
                            @foreach($activeTasks as $assignment)
                                <div class="task-card" data-assignment-id="{{ $assignment->id }}">
                                    <div class="task-header">
                                        <div class="task-title-section">
                                            <h4 class="task-title">{{ $assignment->task->title }}</h4>
                                            <div class="task-meta">
                                                <span class="task-type">{{ $assignment->task->category ?? 'General' }}</span>
                                                <span class="priority priority-{{ $assignment->task->priority ?? 'medium' }}">
                                                    {{ ucfirst($assignment->task->priority ?? 'medium') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="task-status-section">
                                            <span class="status-badge status-{{ $assignment->status }}">
                                                {{ ucfirst(str_replace('_', ' ', $assignment->status)) }}
                                            </span>
                                            @if($assignment->isOverdue())
                                                <span class="overdue-indicator">
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Overdue
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="task-details">
                                        @if($assignment->task->description)
                                            <p class="task-description">{{ Str::limit($assignment->task->description, 150) }}</p>
                                        @endif
                                        
                                        <div class="task-timing">
                                            <div class="due-date">
                                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <span>
                                                    {{ $assignment->due_date ? $assignment->due_date->format('M j, Y') : 'No due date' }}
                                                    @if($assignment->scheduled_time)
                                                        at {{ $assignment->scheduled_time->format('g:i A') }}
                                                    @endif
                                                </span>
                                            </div>
                                            
                                            @if($assignment->estimated_hours)
                                                <div class="estimated-hours">
                                                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span>{{ $assignment->estimated_hours }}h estimated</span>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        @if($assignment->progress_percentage > 0)
                                            <div class="progress-section">
                                                <div class="progress-label">
                                                    <span>Progress</span>
                                                    <span class="progress-percentage">{{ $assignment->progress_percentage }}%</span>
                                                </div>
                                                <div class="progress-bar">
                                                    <div class="progress-fill" style="width: {{ $assignment->progress_percentage }}%"></div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="task-actions">
                                        @if($assignment->status === 'pending')
                                            <button type="button" class="btn btn-sm btn-primary" onclick="startTask('{{ $assignment->id }}')">
                                                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h1m4 0h1m-6-8h8a2 2 0 012 2v8a2 2 0 01-2 2H8a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                                                </svg>
                                                {{ __('staff.tasks.start_task') }}
                                            </button>
                                        @elseif($assignment->status === 'in_progress')
                                            <button type="button" class="btn btn-sm btn-success" onclick="completeTask('{{ $assignment->id }}')">
                                                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ __('staff.tasks.complete_task') }}
                                            </button>
                                        @endif
                                        
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="viewTaskDetails('{{ $assignment->id }}')">
                                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            {{ __('staff.tasks.view_details') }}
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <h3 class="empty-state-title">{{ __('staff.tasks.no_active_tasks') }}</h3>
                            <p class="empty-state-description">{{ __('staff.tasks.no_active_tasks_description') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payroll Tab -->
            <div x-show="activeTab === 'payroll'" x-transition>
                @include('admin.staff.partials.profile-payroll')
                    </div>

            <!-- Performance Tab -->
            <div x-show="activeTab === 'performance'" x-transition>
                @include('admin.staff.partials.profile-performance')
                </div>

            <!-- Shifts Tab -->
            <div x-show="activeTab === 'shifts'" x-transition>
                @include('admin.staff.partials.profile-shifts')
            </div>
        </div>
    </div>

        <!-- Simple Delete Button at Bottom -->
        @if($staff->id !== Auth::id())
        <div class="mt-8 p-4 text-center border-t border-gray-200 dark:border-gray-700">
            <form action="{{ route('admin.staff.destroy', $staff) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('staff.confirm_delete') }} - {{ $staff->full_name }}?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger inline-flex items-center px-6 py-3 rounded-lg font-medium transition-colors bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white">
                    <i class="fas fa-trash-alt mr-2"></i>{{ __('staff.delete_staff') }}
                </button>
            </form>
        </div>
        @endif

</div>
@endsection

@push('styles')
<style>
/* Tasks Section Styles */
.tasks-section {
    padding: 1.5rem 0;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--color-border-base);
}

.section-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.task-filter-dropdown {
    position: relative;
    display: inline-block;
}

.filter-select {
    appearance: none;
    background: var(--color-bg-secondary);
    border: 1px solid var(--color-border-base);
    border-radius: 0.5rem;
    padding: 0.5rem 2.5rem 0.5rem 1rem;
    font-size: 0.875rem;
    color: var(--color-text-primary);
    cursor: pointer;
    transition: all 0.2s ease;
    min-width: 120px;
}

.filter-select:hover {
    border-color: var(--color-primary);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.filter-select:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(var(--color-primary-rgb), 0.1);
}

.filter-dropdown-icon {
    position: absolute;
    right: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    width: 1rem;
    height: 1rem;
    color: var(--color-text-secondary);
    pointer-events: none;
    transition: transform 0.2s ease;
}

.filter-select:focus + .filter-dropdown-icon {
    transform: translateY(-50%) rotate(180deg);
}

.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--color-text-primary);
    margin: 0;
}

.task-count {
    background: var(--color-primary);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.tasks-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.task-card {
    background: var(--color-bg-secondary);
    border: 1px solid var(--color-border-base);
    border-radius: 0.75rem;
    padding: 1.5rem;
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
}

.task-card:hover {
    border-color: var(--color-primary);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.task-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.task-title-section {
    flex: 1;
}

.task-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--color-text-primary);
    margin: 0 0 0.5rem 0;
    line-height: 1.4;
}

.task-meta {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.task-type {
    background: var(--color-surface-card);
    color: var(--color-text-secondary);
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 500;
}

.priority {
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.priority-low {
    background: #dcfce7;
    color: #166534;
}

.priority-medium {
    background: #dbeafe;
    color: #1e40af;
}

.priority-high {
    background: #fef3c7;
    color: #92400e;
}

.priority-urgent {
    background: #fee2e2;
    color: #991b1b;
}

.task-status-section {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.5rem;
}

.status-badge {
    padding: 0.375rem 0.75rem;
    border-radius: 0.5rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-in_progress {
    background: #dbeafe;
    color: #1e40af;
}

.status-completed {
    background: #dcfce7;
    color: #166534;
}

.status-overdue {
    background: #fee2e2;
    color: #991b1b;
}

.overdue-indicator {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    color: #dc2626;
    font-size: 0.75rem;
    font-weight: 500;
}

.overdue-indicator svg {
    width: 0.875rem;
    height: 0.875rem;
}

.task-details {
    margin-bottom: 1.5rem;
}

.task-description {
    color: var(--color-text-secondary);
    font-size: 0.875rem;
    line-height: 1.5;
    margin: 0 0 1rem 0;
}

.task-timing {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1rem;
}

.due-date,
.estimated-hours {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--color-text-secondary);
    font-size: 0.875rem;
}

.due-date svg,
.estimated-hours svg {
    width: 1rem;
    height: 1rem;
    flex-shrink: 0;
}

.progress-section {
    margin-top: 1rem;
}

.progress-label {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
    color: var(--color-text-secondary);
}

.progress-percentage {
    font-weight: 600;
    color: var(--color-text-primary);
}

.progress-bar {
    height: 0.5rem;
    background: var(--color-surface-card);
    border-radius: 0.25rem;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--color-primary), var(--color-secondary));
    border-radius: 0.25rem;
    transition: width 0.3s ease;
}

.task-actions {
    display: flex;
    gap: 0.75rem;
    align-items: center;
    flex-wrap: wrap;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    border: 1px solid transparent;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.75rem;
}

.btn-primary {
    background: var(--color-primary);
    color: white;
    border-color: var(--color-primary);
}

.btn-primary:hover:not(:disabled) {
    background: var(--color-secondary);
    border-color: var(--color-secondary);
    transform: translateY(-1px);
}

.btn-success {
    background: #10b981;
    color: white;
    border-color: #10b981;
}

.btn-success:hover:not(:disabled) {
    background: #059669;
    border-color: #059669;
    transform: translateY(-1px);
}

.btn-outline-secondary {
    background: transparent;
    color: var(--color-text-secondary);
    border-color: var(--color-border-base);
}

.btn-outline-secondary:hover:not(:disabled) {
    background: var(--color-surface-card);
    color: var(--color-text-primary);
    border-color: var(--color-primary);
}

.btn-icon {
    width: 1rem;
    height: 1rem;
    flex-shrink: 0;
}

/* Toast Notifications */
.toast {
    position: fixed;
    top: 1rem;
    right: 1rem;
    background: var(--color-bg-secondary);
    border: 1px solid var(--color-border-base);
    border-radius: 0.5rem;
    padding: 1rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    z-index: 1000;
    transform: translateX(100%);
    transition: transform 0.3s ease;
    min-width: 300px;
}

.toast.show {
    transform: translateX(0);
}

.toast-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.toast-icon {
    width: 1.25rem;
    height: 1.25rem;
    flex-shrink: 0;
}

.toast-success .toast-icon {
    color: #10b981;
}

.toast-error .toast-icon {
    color: #ef4444;
}

.toast-info .toast-icon {
    color: var(--color-primary);
}

.toast-message {
    font-size: 0.875rem;
    color: var(--color-text-primary);
    font-weight: 500;
}

/* Loading Animation */
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Responsive Design */
@media (max-width: 768px) {
    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .section-actions {
        width: 100%;
        justify-content: space-between;
    }
    
    .filter-select {
        min-width: 140px;
    }
    
    .task-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .task-status-section {
        align-items: flex-start;
        flex-direction: row;
        gap: 0.75rem;
    }
    
    .task-timing {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .task-actions {
        flex-direction: column;
        align-items: stretch;
    }
    
    .btn {
        justify-content: center;
    }
}

/* Dark Theme Support */
@media (prefers-color-scheme: dark) {
    .priority-low {
        background: #14532d;
        color: #bbf7d0;
    }
    
    .priority-medium {
        background: #1e3a8a;
        color: #bfdbfe;
    }
    
    .priority-high {
        background: #92400e;
        color: #fef3c7;
    }
    
    .priority-urgent {
        background: #7f1d1d;
        color: #fecaca;
    }
    
    .status-pending {
        background: #92400e;
        color: #fef3c7;
    }
    
    .status-in_progress {
        background: #1e3a8a;
        color: #bfdbfe;
    }
    
    .status-completed {
        background: #14532d;
        color: #bbf7d0;
    }
    
    .status-overdue {
        background: #7f1d1d;
        color: #fecaca;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Task Filter Functions
function filterTasks(filter) {
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('task_filter', filter);
    
    // Keep the current tab active
    currentUrl.hash = '#tasks';
    
    window.location.href = currentUrl.toString();
}

// Auto-activate tasks tab if filter is present
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const taskFilter = urlParams.get('task_filter');
    
    if (taskFilter && window.location.hash !== '#tasks') {
        // Activate tasks tab if a filter is applied
        const tasksTab = document.querySelector('[x-data] button[x-on\\:click*="tasks"]');
        if (tasksTab) {
            tasksTab.click();
        }
    }
});

// Task Management Functions
function startTask(assignmentId) {
    if (confirm('{{ __('staff.tasks.confirm_start') }}')) {
        updateTaskStatus(assignmentId, 'in_progress');
    }
}

function completeTask(assignmentId) {
    if (confirm('{{ __('staff.tasks.confirm_complete') }}')) {
        updateTaskStatus(assignmentId, 'completed');
    }
}

function viewTaskDetails(assignmentId) {
    // Open task details modal or redirect to task page
    window.open(`/admin/staff/tasks?assignment=${assignmentId}`, '_blank');
}

function updateTaskStatus(assignmentId, status) {
    const button = document.querySelector(`[data-assignment-id="${assignmentId}"] .btn`);
    const originalText = button.innerHTML;
    
    // Show loading state
    button.disabled = true;
    button.innerHTML = '<svg class="animate-spin btn-icon" width="16" height="16" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" stroke-dasharray="31.416" stroke-dashoffset="31.416"><animate attributeName="stroke-dashoffset" dur="1s" repeatCount="indefinite" values="31.416;0"/></circle></svg> Updating...';
    
    fetch(`/admin/staff/task-assignments/${assignmentId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            status: status,
            _method: 'PUT'
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showToast(data.message || 'Task status updated successfully', 'success');
            
            // Refresh the page to show updated status
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showToast(data.message || 'Failed to update task status', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred while updating the task status', 'error');
    })
    .finally(() => {
        // Restore button state
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

// Toast notification function
function showToast(message, type = 'info') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            <div class="toast-icon">
                ${type === 'success' ? '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' : 
                  type === 'error' ? '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' :
                  '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'}
            </div>
            <div class="toast-message">${message}</div>
        </div>
    `;
    
    // Add to page
    document.body.appendChild(toast);
    
    // Show toast
    setTimeout(() => toast.classList.add('show'), 100);
    
    // Remove toast after 3 seconds
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => document.body.removeChild(toast), 300);
    }, 3000);
}
</script>
@endpush
