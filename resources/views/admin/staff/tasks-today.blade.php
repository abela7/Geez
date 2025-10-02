@extends('layouts.admin')

@section('title', __("Today's Tasks") . ' - ' . config('app.name'))
@section('page_title', __("Today's Tasks"))

@section('content')
<div class="tasks-today-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __("Today's Tasks") }}</h1>
                <p class="page-subtitle">{{ now()->format('l, F j, Y') }}</p>
            </div>

            <div class="page-actions">
                <a href="{{ route('admin.staff.tasks.create') }}" class="btn btn-primary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('Create Task') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon stat-icon-total">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">{{ __('Total Tasks') }}</div>
                <div class="stat-value">{{ $stats['total'] }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-completed">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">{{ __('Completed') }}</div>
                <div class="stat-value">{{ $stats['completed'] }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-in-progress">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">{{ __('In Progress') }}</div>
                <div class="stat-value">{{ $stats['in_progress'] }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-pending">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">{{ __('Pending') }}</div>
                <div class="stat-value">{{ $stats['pending'] }}</div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-tabs">
            <a href="{{ route('admin.staff.tasks.today', ['show_completed' => 'all']) }}" 
               class="filter-tab {{ $showCompleted === 'all' ? 'active' : '' }}">
                {{ __('All Tasks') }} ({{ $stats['total'] }})
            </a>
            <a href="{{ route('admin.staff.tasks.today', ['show_completed' => 'pending']) }}" 
               class="filter-tab {{ $showCompleted === 'pending' ? 'active' : '' }}">
                {{ __('Active') }} ({{ $stats['pending'] + $stats['in_progress'] }})
            </a>
            <a href="{{ route('admin.staff.tasks.today', ['show_completed' => 'completed']) }}" 
               class="filter-tab {{ $showCompleted === 'completed' ? 'active' : '' }}">
                {{ __('Completed') }} ({{ $stats['completed'] }})
            </a>
        </div>
    </div>

    <!-- Tasks List -->
    <div class="tasks-container">
        @if($todayAssignments->isEmpty())
            <div class="empty-state">
                <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <h3 class="empty-state-title">{{ __('No Tasks Scheduled') }}</h3>
                <p class="empty-state-text">
                    @if($showCompleted === 'completed')
                        {{ __('No tasks have been completed today.') }}
                    @elseif($showCompleted === 'pending')
                        {{ __('No active tasks for today.') }}
                    @else
                        {{ __('There are no tasks scheduled for today.') }}
                    @endif
                </p>
                <a href="{{ route('admin.staff.tasks.create') }}" class="btn btn-primary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('Create New Task') }}
                </a>
            </div>
        @else
            <div class="tasks-list">
                @foreach($todayAssignments as $assignment)
                    <div class="task-card {{ $assignment->status }}">
                        <div class="task-card-header">
                            <div class="task-card-title-section">
                                <h3 class="task-card-title">{{ $assignment->task->title }}</h3>
                                <div class="task-card-meta">
                                    <span class="task-priority priority-{{ $assignment->task->priority }}">
                                        {{ ucfirst($assignment->task->priority) }}
                                    </span>
                                    <span class="task-category">
                                        {{ ucfirst(str_replace('-', ' ', $assignment->task->category)) }}
                                    </span>
                                    @if($assignment->task->scheduled_time)
                                        <span class="task-time">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($assignment->task->scheduled_time)->format('g:i A') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="task-status-badge status-{{ $assignment->status }}">
                                @if($assignment->status === 'completed')
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @elseif($assignment->status === 'in_progress')
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                @else
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @endif
                                {{ ucfirst(str_replace('_', ' ', $assignment->status)) }}
                            </div>
                        </div>

                        @if($assignment->task->description)
                            <p class="task-card-description">{{ Str::limit($assignment->task->description, 150) }}</p>
                        @endif

                        <div class="task-card-footer">
                            <div class="task-assignee">
                                <div class="assignee-avatar">
                                    {{ strtoupper(substr($assignment->staff->first_name, 0, 1) . substr($assignment->staff->last_name, 0, 1)) }}
                                </div>
                                <div class="assignee-info">
                                    <div class="assignee-name">{{ $assignment->staff->first_name }} {{ $assignment->staff->last_name }}</div>
                                    <div class="assignee-type">{{ $assignment->staff->staffType->display_name ?? 'Staff' }}</div>
                                </div>
                            </div>

                            <div class="task-card-actions">
                                @if($assignment->task->estimated_hours)
                                    <span class="task-estimate">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $assignment->task->estimated_hours }}h
                                    </span>
                                @endif
                                <a href="{{ route('admin.staff.tasks.edit', $assignment->task->id) }}" class="btn btn-sm btn-outline">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    {{ __('Edit') }}
                                </a>
                                <button onclick="viewTaskDetails('{{ $assignment->task->id }}')" class="btn btn-sm btn-primary">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    {{ __('View') }}
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<style>
.tasks-today-page {
    min-height: 100vh;
    background-color: var(--color-bg-primary);
}

.page-header {
    background-color: var(--color-bg-primary);
    border-bottom: 1px solid var(--color-border-base);
    padding: 1.5rem;
}

.page-header-content {
    max-width: 1400px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
}

.page-title {
    color: var(--color-text-primary);
    font-size: 1.875rem;
    font-weight: 700;
    margin: 0;
}

.page-subtitle {
    color: var(--color-text-secondary);
    font-size: 1rem;
    margin-top: 0.25rem;
}

.stats-grid {
    max-width: 1400px;
    margin: 2rem auto;
    padding: 0 1.5rem;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
}

.stat-card {
    background-color: var(--color-surface-card);
    border: 1px solid var(--color-surface-card-border);
    border-radius: var(--btn-border-radius);
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    box-shadow: var(--color-surface-card-shadow);
    transition: var(--transition-all);
}

.stat-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.stat-icon {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.stat-icon svg {
    width: 1.25rem;
    height: 1.25rem;
}

.stat-icon-total {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.stat-icon-completed {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.stat-icon-in-progress {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.stat-icon-pending {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
    color: white;
}

.stat-content {
    flex: 1;
}

.stat-label {
    color: var(--color-text-secondary);
    font-size: 0.75rem;
    font-weight: 500;
    margin-bottom: 0.125rem;
}

.stat-value {
    color: var(--color-text-primary);
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1;
}

.filter-section {
    max-width: 1400px;
    margin: 0 auto 1.5rem;
    padding: 0 1.5rem;
}

.filter-tabs {
    display: flex;
    gap: 0.5rem;
    border-bottom: 2px solid var(--color-border-base);
}

.filter-tab {
    padding: 0.75rem 1.5rem;
    color: var(--color-text-secondary);
    text-decoration: none;
    font-weight: 500;
    border-bottom: 2px solid transparent;
    margin-bottom: -2px;
    transition: var(--transition-colors);
}

.filter-tab:hover {
    color: var(--color-text-primary);
}

.filter-tab.active {
    color: var(--color-primary);
    border-bottom-color: var(--color-primary);
}

.tasks-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 1.5rem 2rem;
}

.tasks-list {
    display: grid;
    gap: 1rem;
}

.task-card {
    background-color: var(--color-surface-card);
    border: 1px solid var(--color-surface-card-border);
    border-radius: var(--btn-border-radius);
    padding: 1.25rem;
    box-shadow: var(--color-surface-card-shadow);
    transition: var(--transition-all);
}

.task-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.task-card.completed {
    opacity: 0.8;
}

.task-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1rem;
}

.task-card-title {
    color: var(--color-text-primary);
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0 0 0.5rem 0;
}

.task-card-meta {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.task-priority {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.task-priority.priority-urgent {
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
}

.task-priority.priority-high {
    background: rgba(249, 115, 22, 0.1);
    color: #ea580c;
}

.task-priority.priority-medium {
    background: rgba(251, 191, 36, 0.1);
    color: #d97706;
}

.task-priority.priority-low {
    background: rgba(107, 114, 128, 0.1);
    color: #6b7280;
}

.task-category {
    padding: 0.25rem 0.75rem;
    background: var(--color-bg-tertiary);
    color: var(--color-text-secondary);
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 500;
}

.task-time {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    color: var(--color-text-secondary);
    font-size: 0.875rem;
}

.task-time svg {
    width: 1rem;
    height: 1rem;
}

.task-status-badge {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 600;
    flex-shrink: 0;
}

.task-status-badge svg {
    width: 1.25rem;
    height: 1.25rem;
}

.task-status-badge.status-completed {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

.task-status-badge.status-in_progress {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
}

.task-status-badge.status-pending {
    background: rgba(107, 114, 128, 0.1);
    color: #6b7280;
}

.task-card-description {
    color: var(--color-text-secondary);
    line-height: 1.6;
    margin-bottom: 1rem;
}

.task-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--color-border-base);
}

.task-assignee {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.assignee-avatar {
    width: 2.5rem;
    height: 2.5rem;
    min-width: 2.5rem;
    min-height: 2.5rem;
    border-radius: 50%;
    background: var(--color-primary);
    color: var(--button-primary-text);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.875rem;
    flex-shrink: 0;
}

.assignee-name {
    color: var(--color-text-primary);
    font-weight: 600;
    font-size: 0.875rem;
}

.assignee-type {
    color: var(--color-text-muted);
    font-size: 0.75rem;
}

.task-card-actions {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.task-estimate {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    color: var(--color-text-secondary);
    font-size: 0.875rem;
}

.task-estimate svg {
    width: 1rem;
    height: 1rem;
}

.btn {
    padding: var(--btn-padding);
    border-radius: var(--btn-border-radius);
    font-weight: var(--btn-font-weight);
    transition: var(--btn-transition);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    text-decoration: none;
    border: none;
    font-size: 0.875rem;
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.813rem;
}

.btn-primary {
    background-color: var(--button-primary-bg);
    color: var(--button-primary-text);
    border: 1px solid var(--button-primary-border);
    box-shadow: var(--button-primary-shadow);
}

.btn-primary:hover {
    background-color: var(--button-primary-hover-bg);
    box-shadow: var(--button-primary-hover-shadow);
    transform: translateY(-1px);
}

.btn-outline {
    background-color: transparent;
    color: var(--color-text-primary);
    border: 1px solid var(--color-border-base);
}

.btn-outline:hover {
    background-color: var(--color-bg-tertiary);
    border-color: var(--color-primary);
    color: var(--color-primary);
}

.btn-icon {
    width: 1.125rem;
    height: 1.125rem;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background-color: var(--color-surface-card);
    border: 1px solid var(--color-surface-card-border);
    border-radius: var(--btn-border-radius);
    box-shadow: var(--color-surface-card-shadow);
}

.empty-state-icon {
    width: 4rem;
    height: 4rem;
    color: var(--color-text-muted);
    margin: 0 auto 1rem;
}

.empty-state-title {
    color: var(--color-text-primary);
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0 0 0.5rem 0;
}

.empty-state-text {
    color: var(--color-text-secondary);
    margin-bottom: 1.5rem;
}

/* Tablet */
@media (max-width: 1024px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Mobile */
@media (max-width: 768px) {
    .page-header {
        padding: 1rem;
    }

    .page-header-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .page-title {
        font-size: 1.5rem;
    }

    .page-subtitle {
        font-size: 0.875rem;
    }

    .stats-grid {
        grid-template-columns: 1fr;
        margin: 1rem auto;
        padding: 0 1rem;
        gap: 0.75rem;
    }

    .stat-card {
        padding: 0.875rem;
    }

    .filter-section {
        padding: 0 1rem;
        margin-bottom: 1rem;
    }

    .filter-tabs {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .filter-tabs::-webkit-scrollbar {
        display: none;
    }

    .filter-tab {
        padding: 0.75rem 1rem;
        white-space: nowrap;
        font-size: 0.875rem;
    }

    .tasks-container {
        padding: 0 1rem 1rem;
    }

    .task-card {
        padding: 1rem;
    }

    .task-card-header {
        flex-direction: column;
        gap: 0.75rem;
    }

    .task-card-title {
        font-size: 1.125rem;
    }

    .task-card-meta {
        gap: 0.5rem;
    }

    .task-priority,
    .task-category {
        font-size: 0.688rem;
        padding: 0.188rem 0.5rem;
    }

    .task-time {
        font-size: 0.75rem;
    }

    .task-status-badge {
        align-self: flex-start;
        padding: 0.375rem 0.75rem;
        font-size: 0.75rem;
    }

    .task-status-badge svg {
        width: 1rem;
        height: 1rem;
    }

    .task-card-description {
        font-size: 0.875rem;
    }

    .task-card-footer {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .task-card-actions {
        width: 100%;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .task-card-actions .btn {
        flex: 1;
        justify-content: center;
        min-width: 0;
    }

    .task-estimate {
        font-size: 0.75rem;
    }

    .empty-state {
        padding: 2rem 1rem;
    }

    .empty-state-icon {
        width: 3rem;
        height: 3rem;
    }

    .empty-state-title {
        font-size: 1.25rem;
    }

    .empty-state-text {
        font-size: 0.875rem;
    }
}

/* Small mobile */
@media (max-width: 480px) {
    .stats-grid {
        gap: 0.5rem;
    }

    .stat-icon {
        width: 2rem;
        height: 2rem;
    }

    .stat-icon svg {
        width: 1rem;
        height: 1rem;
    }

    .stat-label {
        font-size: 0.688rem;
    }

    .stat-value {
        font-size: 1.25rem;
    }

    .task-card-actions .btn {
        font-size: 0.75rem;
        padding: 0.5rem 0.75rem;
    }

    .btn-icon {
        width: 0.875rem;
        height: 0.875rem;
    }
}
</style>

<script>
function viewTaskDetails(taskId) {
    // You can implement modal view or redirect to task details
    window.location.href = `/admin/staff/tasks/${taskId}/edit`;
}
</script>
@endsection

