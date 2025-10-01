@extends('layouts.admin')

@section('title', $task->title . ' - ' . config('app.name'))
@section('page_title', __('staff.tasks.task_details'))

@section('content')
<div class="show-task-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ $task->title }}</h1>
                <p class="page-subtitle">{{ __('staff.tasks.task_details') }}</p>
            </div>
            
            <div class="page-actions">
                <a href="{{ route('admin.staff.tasks.index') }}" class="btn btn-outline">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('common.back') }}
                </a>
                <a href="{{ route('admin.staff.tasks.edit', $task) }}" class="btn btn-primary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    {{ __('staff.tasks.edit') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Task Details -->
    <div class="task-details-container">
        <div class="task-info-section">
            <div class="task-info-card">
                <h2 class="section-title">{{ __('staff.tasks.task_information') }}</h2>
                
                <div class="info-grid">
                    <div class="info-item">
                        <label class="info-label">{{ __('staff.tasks.task_title') }}</label>
                        <div class="info-value">{{ $task->title }}</div>
                    </div>

                    @if($task->description)
                        <div class="info-item info-item-full">
                            <label class="info-label">{{ __('staff.tasks.task_description') }}</label>
                            <div class="info-value">{{ $task->description }}</div>
                        </div>
                    @endif

                    <div class="info-item">
                        <label class="info-label">{{ __('staff.tasks.task_type') }}</label>
                        <div class="info-value">
                            <span class="badge badge-info">{{ $task->taskType->name ?? $task->task_type }}</span>
                        </div>
                    </div>

                    <div class="info-item">
                        <label class="info-label">{{ __('staff.tasks.priority') }}</label>
                        <div class="info-value">
                            <span class="priority-badge priority-{{ $task->priority }}">
                                {{ $task->taskPriority->name ?? $task->priority }}
                            </span>
                        </div>
                    </div>

                    <div class="info-item">
                        <label class="info-label">{{ __('staff.tasks.category') }}</label>
                        <div class="info-value">
                            <span class="badge badge-secondary">{{ $task->taskCategory->name ?? $task->category }}</span>
                        </div>
                    </div>

                    @if($task->estimated_hours)
                        <div class="info-item">
                            <label class="info-label">{{ __('staff.tasks.estimated_hours') }}</label>
                            <div class="info-value">{{ $task->estimated_hours }} {{ __('common.hours') }}</div>
                        </div>
                    @endif

                    @if($task->tags && count($task->tags) > 0)
                        <div class="info-item info-item-full">
                            <label class="info-label">{{ __('staff.tasks.tags') }}</label>
                            <div class="info-value">
                                <div class="tags-list">
                                    @foreach($task->tags as $tag)
                                        <span class="tag">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="info-item">
                        <label class="info-label">{{ __('common.status') }}</label>
                        <div class="info-value">
                            <span class="status-badge status-{{ $task->is_active ? 'active' : 'inactive' }}">
                                {{ $task->is_active ? __('common.active') : __('common.inactive') }}
                            </span>
                        </div>
                    </div>

                    @if($task->is_template)
                        <div class="info-item">
                            <label class="info-label">{{ __('staff.tasks.template_name') }}</label>
                            <div class="info-value">{{ $task->template_name ?? __('common.no_name') }}</div>
                        </div>
                    @endif

                    <div class="info-item">
                        <label class="info-label">{{ __('staff.tasks.requires_approval') }}</label>
                        <div class="info-value">
                            <span class="badge {{ $task->requires_approval ? 'badge-warning' : 'badge-success' }}">
                                {{ $task->requires_approval ? __('common.yes') : __('common.no') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="audit-info">
                    <div class="audit-item">
                        <label class="audit-label">{{ __('common.created_by') }}</label>
                        <div class="audit-value">{{ $task->creator->full_name ?? 'Unknown' }} • {{ $task->created_at->format('M j, Y H:i') }}</div>
                    </div>
                    @if($task->updated_at && $task->updated_at != $task->created_at)
                        <div class="audit-item">
                            <label class="audit-label">{{ __('common.updated_by') }}</label>
                            <div class="audit-value">{{ $task->updater->full_name ?? 'Unknown' }} • {{ $task->updated_at->format('M j, Y H:i') }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Assignments Section -->
        @if($task->assignments->count() > 0)
            <div class="assignments-section">
                <h2 class="section-title">{{ __('staff.tasks.assignments') }} ({{ $task->assignments->count() }})</h2>
                
                <div class="assignments-list">
                    @foreach($task->assignments as $assignment)
                        <div class="assignment-card">
                            <div class="assignment-header">
                                <div class="assignee-info">
                                    <div class="assignee-avatar">
                                        {{ substr($assignment->staff->first_name ?? 'U', 0, 1) }}{{ substr($assignment->staff->last_name ?? 'U', 0, 1) }}
                                    </div>
                                    <div class="assignee-details">
                                        <div class="assignee-name">{{ $assignment->staff->full_name ?? 'Unknown' }}</div>
                                        <div class="assignee-type">{{ $assignment->staff->staffType->display_name ?? 'No Type' }}</div>
                                    </div>
                                </div>
                                
                                <div class="assignment-status">
                                    <span class="status-badge status-{{ $assignment->status }}">
                                        {{ __('staff.tasks.' . $assignment->status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="assignment-details">
                                @if($assignment->assigned_date)
                                    <div class="detail-item">
                                        <span class="detail-label">{{ __('staff.tasks.assigned_date') }}:</span>
                                        <span class="detail-value">{{ $assignment->assigned_date->format('M j, Y') }}</span>
                                    </div>
                                @endif

                                @if($assignment->due_date)
                                    <div class="detail-item">
                                        <span class="detail-label">{{ __('staff.tasks.due_date') }}:</span>
                                        <span class="detail-value {{ $assignment->due_date < now() && $assignment->status !== 'completed' ? 'overdue' : '' }}">
                                            {{ $assignment->due_date->format('M j, Y') }}
                                        </span>
                                    </div>
                                @endif

                                @if($assignment->priority_override)
                                    <div class="detail-item">
                                        <span class="detail-label">{{ __('staff.tasks.priority_override') }}:</span>
                                        <span class="detail-value">
                                            <span class="priority-badge priority-{{ $assignment->priority_override }}">
                                                {{ __('staff.tasks.' . $assignment->priority_override) }}
                                            </span>
                                        </span>
                                    </div>
                                @endif

                                @if($assignment->progress_percentage)
                                    <div class="detail-item">
                                        <span class="detail-label">{{ __('staff.tasks.progress') }}:</span>
                                        <span class="detail-value">
                                            <div class="progress-bar">
                                                <div class="progress-fill" style="width: {{ $assignment->progress_percentage }}%"></div>
                                                <span class="progress-text">{{ $assignment->progress_percentage }}%</span>
                                            </div>
                                        </span>
                                    </div>
                                @endif

                                @if($assignment->notes)
                                    <div class="detail-item detail-item-full">
                                        <span class="detail-label">{{ __('staff.tasks.assignment_notes') }}:</span>
                                        <span class="detail-value">{{ $assignment->notes }}</span>
                                    </div>
                                @endif
                            </div>

                            @if($assignment->status !== 'completed')
                                <div class="assignment-actions">
                                    <form method="POST" action="{{ route('admin.staff.task-assignments.update-status', $assignment) }}" class="inline-form">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" 
                                                class="btn btn-sm btn-success"
                                                onclick="return confirm('{{ __('staff.tasks.confirm_complete') }}')">
                                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ __('staff.tasks.mark_complete') }}
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="no-assignments">
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="empty-state-title">{{ __('staff.tasks.no_assignments_title') }}</h3>
                    <p class="empty-state-description">{{ __('staff.tasks.no_assignments_description') }}</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.show-task-page {
    padding: var(--page-padding);
    max-width: 1000px;
    margin: 0 auto;
    background: var(--color-bg-primary);
}

.task-details-container {
    display: grid;
    gap: var(--section-spacing);
}

.task-info-card, .assignments-section {
    background: var(--color-surface-card);
    border: 1px solid var(--color-surface-card-border);
    border-radius: 0.75rem;
    padding: var(--section-spacing);
    box-shadow: var(--color-surface-card-shadow);
}

.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--color-text-primary);
    margin: 0 0 var(--card-spacing) 0;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--grid-gap);
    margin-bottom: var(--section-spacing);
}

.info-item-full {
    grid-column: 1 / -1;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.info-label {
    font-weight: 500;
    color: var(--color-text-secondary);
    font-size: 0.875rem;
}

.info-value {
    color: var(--color-text-primary);
}

.badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.75rem;
    border-radius: 0.5rem;
    font-size: 0.75rem;
    font-weight: 500;
    border: 1px solid;
}

.badge-info { 
    background: var(--task-priority-medium-bg); 
    color: var(--task-priority-medium);
    border-color: var(--task-priority-medium);
}
.badge-secondary { 
    background: var(--color-bg-tertiary); 
    color: var(--color-text-secondary);
    border-color: var(--color-border-base);
}
.badge-warning { 
    background: var(--task-priority-high-bg); 
    color: var(--task-priority-high);
    border-color: var(--task-priority-high);
}
.badge-success { 
    background: var(--task-priority-low-bg); 
    color: var(--task-priority-low);
    border-color: var(--task-priority-low);
}

.priority-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.75rem;
    border-radius: 0.5rem;
    font-size: 0.75rem;
    font-weight: 500;
    border: 1px solid;
}

.priority-low { 
    background: var(--task-priority-low-bg); 
    color: var(--task-priority-low);
    border-color: var(--task-priority-low);
}
.priority-medium { 
    background: var(--task-priority-medium-bg); 
    color: var(--task-priority-medium);
    border-color: var(--task-priority-medium);
}
.priority-high { 
    background: var(--task-priority-high-bg); 
    color: var(--task-priority-high);
    border-color: var(--task-priority-high);
}
.priority-urgent { 
    background: var(--task-priority-urgent-bg); 
    color: var(--task-priority-urgent);
    border-color: var(--task-priority-urgent);
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.75rem;
    border-radius: 0.5rem;
    font-size: 0.75rem;
    font-weight: 500;
    border: 1px solid;
}

.status-active { 
    background: var(--task-status-completed-bg); 
    color: var(--task-status-completed);
    border-color: var(--task-status-completed);
}
.status-inactive { 
    background: var(--task-status-pending-bg); 
    color: var(--task-status-pending);
    border-color: var(--task-status-pending);
}
.status-pending { 
    background: var(--task-status-pending-bg); 
    color: var(--task-status-pending);
    border-color: var(--task-status-pending);
}
.status-in_progress { 
    background: var(--task-status-in-progress-bg); 
    color: var(--task-status-in-progress);
    border-color: var(--task-status-in-progress);
}
.status-completed { 
    background: var(--task-status-completed-bg); 
    color: var(--task-status-completed);
    border-color: var(--task-status-completed);
}
.status-overdue { 
    background: var(--task-status-overdue-bg); 
    color: var(--task-status-overdue);
    border-color: var(--task-status-overdue);
}

.tags-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.tag {
    background: var(--color-primary);
    color: var(--button-primary-text);
    padding: 0.25rem 0.75rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 500;
    border: 1px solid var(--color-primary);
}

.audit-info {
    border-top: 1px solid var(--color-border-base);
    padding-top: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.audit-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem;
    background: var(--color-bg-tertiary);
    border-radius: 0.375rem;
}

.audit-label {
    font-weight: 500;
    color: var(--color-text-secondary);
    font-size: 0.875rem;
}

.audit-value {
    color: var(--color-text-primary);
    font-size: 0.875rem;
}

.assignments-list {
    display: flex;
    flex-direction: column;
    gap: var(--card-spacing);
}

.assignment-card {
    border: 1px solid var(--color-border-base);
    border-radius: 0.75rem;
    padding: var(--card-spacing);
    background: var(--color-bg-tertiary);
    transition: var(--transition-all);
}

.assignment-card:hover {
    background: var(--color-surface-card-hover);
    border-color: var(--color-primary);
}

.assignment-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--card-spacing);
}

.assignee-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.assignee-avatar {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    background: var(--color-primary);
    color: var(--button-primary-text);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

.assignee-name {
    font-weight: 500;
    color: var(--color-text-primary);
}

.assignee-type {
    color: var(--color-text-secondary);
    font-size: 0.875rem;
}

.assignment-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--card-spacing);
    margin-bottom: var(--card-spacing);
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.detail-item-full {
    grid-column: 1 / -1;
}

.detail-label {
    font-weight: 500;
    color: var(--color-text-secondary);
    font-size: 0.875rem;
}

.detail-value {
    color: var(--color-text-primary);
}

.detail-value.overdue {
    color: var(--task-status-overdue);
    font-weight: 500;
}

.progress-bar {
    width: 150px;
    height: 1rem;
    background: var(--progress-bg);
    border-radius: 0.5rem;
    overflow: hidden;
    position: relative;
}

.progress-fill {
    height: 100%;
    background: var(--progress-fill);
    transition: var(--transition-all);
}

.progress-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--color-bg-secondary);
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
}

.assignment-actions {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
}

.inline-form {
    display: inline;
}

.no-assignments {
    background: var(--color-surface-card);
    border: 1px solid var(--color-surface-card-border);
    border-radius: 0.75rem;
    padding: 2rem;
    box-shadow: var(--color-surface-card-shadow);
}

.empty-state {
    text-align: center;
    padding: 2rem;
}

.empty-state-icon {
    width: 3rem;
    height: 3rem;
    margin: 0 auto 1rem;
    color: var(--color-text-muted);
}

.empty-state-icon svg {
    width: 100%;
    height: 100%;
}

.empty-state-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--color-text-primary);
    margin: 0 0 0.5rem 0;
}

.empty-state-description {
    color: var(--color-text-secondary);
    margin: 0;
}

@media (max-width: 768px) {
    .show-task-page {
        padding: 1rem;
    }
    
    .task-info-card, .assignments-section {
        padding: 1.5rem;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .assignment-header {
        flex-direction: column;
        align-items: stretch;
        gap: 1rem;
    }
    
    .assignment-details {
        grid-template-columns: 1fr;
    }
    
    .audit-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
}
</style>
@endpush
