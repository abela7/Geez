@extends('layouts.admin')

@section('title', $task->title . ' - ' . config('app.name'))
@section('page_title', __('staff.tasks.task_details'))

@section('content')
<div class="task-show-page">
    <!-- Page Header with Navigation -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="header-navigation">
                <a href="{{ route('admin.staff.tasks.index') }}" class="nav-back-link">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Tasks
                </a>
            </div>

            <div class="page-actions">
                <a href="{{ route('admin.staff.tasks.edit', $task) }}" class="action-button action-button-primary">
                    <div class="action-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div class="action-content">
                        <div class="action-title">Edit Task</div>
                        <div class="action-description">Modify task details</div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Task Header Card -->
    <div class="task-header-card">
        <div class="task-header-content">
            <div class="task-main-info">
                <div class="task-title-section">
                    <h1 class="task-main-title">{{ $task->title }}</h1>
                    @if($task->taskType)
                        <div class="task-type-indicator">
                            <span class="task-type-badge" style="background: {{ $task->taskType->color ?? 'var(--color-primary)' }}20; color: {{ $task->taskType->color ?? 'var(--color-primary)' }}; border-color: {{ $task->taskType->color ?? 'var(--color-primary)' }};">
                                <svg class="badge-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                {{ $task->taskType->name }}
                            </span>
                        </div>
                    @endif
                </div>

                @if($task->description)
                    <div class="task-description-preview">
                        <p class="task-description-text">{{ Str::limit($task->description, 200) }}</p>
                    </div>
                @endif
            </div>

            <div class="task-status-panel">
                @if($task->taskPriority)
                    <div class="status-item">
                        <div class="status-label">Priority</div>
                        <span class="priority-badge priority-{{ $task->taskPriority->slug }}">
                            <svg class="badge-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            {{ $task->taskPriority->name }}
                        </span>
                    </div>
                @endif

                <div class="status-item">
                    <div class="status-label">Status</div>
                    <span class="status-badge status-{{ $task->is_active ? 'active' : 'inactive' }}">
                        {{ $task->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                @if($task->scheduled_date)
                    <div class="status-item">
                        <div class="status-label">Scheduled</div>
                        <div class="scheduled-info">
                            <div class="scheduled-date">{{ $task->scheduled_date->format('M j, Y') }}</div>
                            @if($task->scheduled_time)
                                <div class="scheduled-time">at {{ $task->scheduled_time->format('g:i A') }}</div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Task Content Grid -->
    <div class="task-content-grid">
        <!-- Task Details Card -->
        <div class="content-section">
            <div class="task-detail-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <svg class="card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Task Details
                    </h3>
                </div>
                <div class="card-content">
                    <div class="detail-grid">
                        @if($task->description)
                            <div class="detail-row">
                                <div class="detail-label">Description</div>
                                <div class="detail-value detail-description">{{ $task->description }}</div>
                            </div>
                        @endif

                        @if($task->instructions)
                            <div class="detail-row">
                                <div class="detail-label">Instructions</div>
                                <div class="detail-value detail-instructions">{{ $task->instructions }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Task Classification Card -->
            <div class="task-detail-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <svg class="card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Classification
                    </h3>
                </div>
                <div class="card-content">
                    <div class="classification-grid">
                        @if($task->taskCategory)
                            <div class="classification-item">
                                <div class="item-label">Category</div>
                                <span class="category-badge">
                                    <svg class="badge-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    {{ $task->taskCategory->name }}
                                </span>
                            </div>
                        @endif

                        <div class="classification-item">
                            <div class="item-label">Status</div>
                            <span class="status-badge status-{{ $task->is_active ? 'active' : 'inactive' }}">
                                {{ $task->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <div class="classification-item">
                            <div class="item-label">Approval Required</div>
                            <span class="status-indicator {{ $task->requires_approval ? 'status-active' : 'status-inactive' }}">
                                {{ $task->requires_approval ? 'Yes' : 'No' }}
                            </span>
                        </div>

                        @if($task->is_template)
                            <div class="classification-item">
                                <div class="item-label">Template</div>
                                <span class="template-badge">
                                    <svg class="badge-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                                    </svg>
                                    {{ $task->template_name ?? 'Unnamed Template' }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tags Card -->
            @if($task->tags && count($task->tags) > 0)
            <div class="task-detail-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <svg class="card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Tags
                        <span class="tag-count">{{ count($task->tags) }}</span>
                    </h3>
                </div>
                <div class="card-content">
                    <div class="tags-container">
                        @foreach($task->tags as $tag)
                            <span class="tag-pill">
                                <svg class="tag-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                {{ $tag }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Timing Card -->
            <div class="task-detail-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <svg class="card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Timing & Estimates
                    </h3>
                </div>
                <div class="card-content">
                    <div class="timing-grid">
                        @if($task->estimated_hours)
                            <div class="timing-item">
                                <div class="timing-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="timing-content">
                                    <div class="timing-label">Estimated Hours</div>
                                    <div class="timing-value">{{ $task->estimated_hours }} hours</div>
                                </div>
                            </div>
                        @endif

                        @if($task->duration_minutes)
                            <div class="timing-item">
                                <div class="timing-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div class="timing-content">
                                    <div class="timing-label">Duration</div>
                                    <div class="timing-value">{{ $task->duration_minutes }} minutes</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Audit Information Card -->
            <div class="task-detail-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <svg class="card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Audit Information
                    </h3>
                </div>
                <div class="card-content">
                    <div class="metadata-grid">
                        <div class="metadata-item">
                            <div class="metadata-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div class="metadata-content">
                                <div class="metadata-label">Created By</div>
                                <div class="metadata-value">
                                    {{ $task->creator->full_name ?? 'System' }}<br>
                                    <span class="metadata-date">{{ $task->created_at ? $task->created_at->format('M j, Y g:i A') : 'Unknown' }}</span>
                                </div>
                            </div>
                        </div>

                        @if($task->updated_at && $task->updated_at != $task->created_at)
                            <div class="metadata-item">
                                <div class="metadata-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </div>
                                <div class="metadata-content">
                                    <div class="metadata-label">Last Updated</div>
                                    <div class="metadata-value">
                                        {{ $task->updater->full_name ?? 'System' }}<br>
                                        <span class="metadata-date">{{ $task->updated_at->format('M j, Y g:i A') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Assignments Section -->
        <div class="content-section">
            @if($task->assignments->count() > 0)
                <div class="task-detail-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg class="card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Assignments
                            <span class="assignment-count">{{ $task->assignments->count() }}</span>
                        </h3>
                    </div>
                    <div class="card-content">
                        <div class="assignments-grid">
                            @foreach($task->assignments as $assignment)
                                <div class="assignment-card">
                                    <div class="assignment-header">
                                        <div class="assignee-avatar">
                                            {{ substr($assignment->staff->first_name ?? 'U', 0, 1) }}{{ substr($assignment->staff->last_name ?? 'U', 0, 1) }}
                                        </div>
                                        <div class="assignee-details">
                                            <div class="assignee-name">{{ $assignment->staff->full_name ?? 'Unknown' }}</div>
                                            <div class="assignee-type">{{ $assignment->staff->staffType->display_name ?? 'No Type' }}</div>
                                        </div>
                                        <div class="assignment-status">
                                            <span class="status-badge status-{{ $assignment->status }}">
                                                {{ ucfirst(str_replace('_', ' ', $assignment->status)) }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="assignment-meta">
                                        @if($assignment->due_date)
                                            <div class="meta-item">
                                                <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <span>{{ $assignment->due_date->format('M j, Y') }}</span>
                                                @if($assignment->scheduled_time)
                                                    <span class="time-text">{{ $assignment->scheduled_time->format('g:i A') }}</span>
                                                @endif
                                            </div>
                                        @endif

                                        @if($assignment->progress_percentage > 0)
                                            <div class="meta-item">
                                                <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                                </svg>
                                                <span>{{ $assignment->progress_percentage }}% complete</span>
                                            </div>
                                        @endif

                                        @if($assignment->notes)
                                            <div class="meta-item">
                                                <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                <span>{{ Str::limit($assignment->notes, 50) }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    @if($assignment->status !== 'completed')
                                        <div class="assignment-actions">
                                            <form method="POST"
                                                  action="{{ route('admin.staff.task-assignments.update-status', $assignment) }}"
                                                  class="inline-form"
                                                  onsubmit="return handleAssignmentStatusUpdate(this)">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit"
                                                        class="action-button action-button-success"
                                                        onclick="return confirm('Are you sure you want to mark this assignment as complete?')">
                                                    <div class="action-icon">
                                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                    </div>
                                                    <div class="action-content">
                                                        <div class="action-title">Mark Complete</div>
                                                        <div class="action-description">Complete this assignment</div>
                                                    </div>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="task-detail-card">
                    <div class="card-content">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <h3 class="empty-state-title">No Assignments Yet</h3>
                            <p class="empty-state-description">This task hasn't been assigned to any staff members yet.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* ==========================================================================
   Task Show Page Styles - Professional Card-Based Design
   ========================================================================== */

.task-show-page {
    padding: var(--page-padding);
    max-width: 1200px;
    margin: 0 auto;
    background: var(--color-bg-primary);
    min-height: 100vh;
}

/* ==========================================================================
   Page Header
   ========================================================================== */

.page-header {
    margin-bottom: 2rem;
}

.page-header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    background: var(--color-surface-card);
    border: 1px solid var(--color-surface-card-border);
    border-radius: 1rem;
    box-shadow: var(--color-surface-card-shadow);
}

.header-navigation {
    display: flex;
    align-items: center;
}

.nav-back-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    background: var(--color-bg-tertiary);
    border: 1px solid var(--color-border-base);
    border-radius: 0.75rem;
    color: var(--color-text-primary);
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition-all);
}

.nav-back-link:hover {
    background: var(--color-primary);
    color: var(--button-primary-text);
    border-color: var(--color-primary);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.nav-icon {
    width: 1.25rem;
    height: 1.25rem;
    color: inherit;
}

.page-actions {
    display: flex;
    gap: 1rem;
}

.action-button {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.875rem 1.5rem;
    border: 1px solid var(--color-border-base);
    border-radius: 0.75rem;
    background: var(--color-bg-tertiary);
    color: var(--color-text-primary);
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition-all);
}

.action-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    border-color: var(--color-primary);
}

.action-button-primary {
    background: var(--color-primary);
    color: var(--button-primary-text);
    border-color: var(--color-primary);
}

.action-button-primary:hover {
    background: var(--color-primary-hover);
    border-color: var(--color-primary-hover);
}

.action-icon {
    width: 1.25rem;
    height: 1.25rem;
    color: inherit;
}

.action-content {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.action-title {
    font-size: 0.875rem;
    font-weight: 600;
    line-height: 1.2;
}

.action-description {
    font-size: 0.75rem;
    opacity: 0.8;
    line-height: 1.2;
}

/* ==========================================================================
   Task Header Card
   ========================================================================== */

.task-header-card {
    margin-bottom: 2rem;
    background: var(--color-surface-card);
    border: 1px solid var(--color-surface-card-border);
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: var(--color-surface-card-shadow);
}

.task-header-content {
    display: flex;
    gap: 2rem;
    padding: 2rem;
}

.task-main-info {
    flex: 1;
}

.task-title-section {
    margin-bottom: 1.5rem;
}

.task-main-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--color-text-primary);
    margin: 0 0 1rem 0;
    line-height: 1.2;
}

.task-type-indicator {
    margin-bottom: 1rem;
}

.task-type-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 0.75rem;
    font-size: 0.875rem;
    font-weight: 500;
    border: 2px solid;
    transition: var(--transition-all);
}

.task-description-preview {
    margin-bottom: 1.5rem;
}

.task-description-text {
    color: var(--color-text-secondary);
    font-size: 1.125rem;
    line-height: 1.6;
    margin: 0;
}

.task-status-panel {
    min-width: 300px;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.status-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.status-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--color-text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.priority-badge, .status-badge, .category-badge, .template-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 0.75rem;
    font-size: 0.875rem;
    font-weight: 500;
    border: 2px solid;
    transition: var(--transition-all);
}

.badge-icon {
    width: 1rem;
    height: 1rem;
    color: inherit;
}

.scheduled-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.scheduled-date {
    font-weight: 600;
    color: var(--color-text-primary);
}

.scheduled-time {
    font-size: 0.875rem;
    color: var(--color-text-secondary);
}

/* ==========================================================================
   Content Grid
   ========================================================================== */

.task-content-grid {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 2rem;
}

.content-section {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

/* ==========================================================================
   Task Detail Cards
   ========================================================================== */

.task-detail-card {
    background: var(--color-surface-card);
    border: 1px solid var(--color-surface-card-border);
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: var(--color-surface-card-shadow);
    transition: var(--transition-all);
}

.task-detail-card:hover {
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
    transform: translateY(-1px);
}

.card-header {
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--color-border-base);
    background: linear-gradient(135deg, var(--color-bg-tertiary) 0%, var(--color-surface-card) 100%);
}

.card-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--color-text-primary);
}

.card-icon {
    width: 1.5rem;
    height: 1.5rem;
    color: var(--color-primary);
}

.assignment-count, .tag-count {
    background: var(--color-primary);
    color: var(--button-primary-text);
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.75rem;
    font-weight: 600;
    margin-left: 0.5rem;
}

.card-content {
    padding: 2rem;
}

/* ==========================================================================
   Detail Grids and Items
   ========================================================================== */

.detail-grid {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.detail-row {
    display: flex;
    gap: 1rem;
}

.detail-label {
    min-width: 120px;
    font-weight: 500;
    color: var(--color-text-secondary);
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.detail-value {
    flex: 1;
    color: var(--color-text-primary);
    line-height: 1.6;
}

.detail-description {
    font-size: 1rem;
    margin: 0;
}

.detail-instructions {
    font-family: 'Monaco', 'Menlo', monospace;
    background: var(--color-bg-tertiary);
    padding: 1rem;
    border-radius: 0.5rem;
    border: 1px solid var(--color-border-base);
    white-space: pre-line;
    font-size: 0.875rem;
    line-height: 1.6;
}

/* ==========================================================================
   Classification Grid
   ========================================================================== */

.classification-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.classification-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.item-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--color-text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-indicator {
    display: inline-flex;
    align-items: center;
    padding: 0.375rem 0.75rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
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

/* ==========================================================================
   Tags Container
   ========================================================================== */

.tags-container {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
}

.tag-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: var(--color-primary);
    color: var(--button-primary-text);
    border: 2px solid var(--color-primary);
    border-radius: 2rem;
    font-size: 0.875rem;
    font-weight: 500;
    transition: var(--transition-all);
}

.tag-pill:hover {
    background: var(--color-primary-hover);
    border-color: var(--color-primary-hover);
    transform: translateY(-1px);
}

.tag-icon {
    width: 1rem;
    height: 1rem;
    color: inherit;
}

/* ==========================================================================
   Timing Grid
   ========================================================================== */

.timing-grid {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.timing-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: var(--color-bg-tertiary);
    border: 1px solid var(--color-border-base);
    border-radius: 0.75rem;
    transition: var(--transition-all);
}

.timing-item:hover {
    background: var(--color-surface-card);
    border-color: var(--color-primary);
}

.timing-icon {
    width: 2rem;
    height: 2rem;
    color: var(--color-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--color-primary-light);
    border-radius: 0.5rem;
}

.timing-icon svg {
    width: 1.25rem;
    height: 1.25rem;
}

.timing-content {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.timing-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--color-text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.timing-value {
    font-size: 1rem;
    font-weight: 600;
    color: var(--color-text-primary);
}

/* ==========================================================================
   Metadata Grid
   ========================================================================== */

.metadata-grid {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.metadata-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    background: var(--color-bg-tertiary);
    border: 1px solid var(--color-border-base);
    border-radius: 0.75rem;
    transition: var(--transition-all);
}

.metadata-item:hover {
    background: var(--color-surface-card);
    border-color: var(--color-primary);
}

.metadata-icon {
    width: 2rem;
    height: 2rem;
    color: var(--color-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--color-primary-light);
    border-radius: 0.5rem;
    flex-shrink: 0;
}

.metadata-icon svg {
    width: 1.25rem;
    height: 1.25rem;
}

.metadata-content {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    flex: 1;
}

.metadata-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--color-text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.metadata-value {
    color: var(--color-text-primary);
    line-height: 1.5;
}

.metadata-date {
    color: var(--color-text-secondary);
    font-size: 0.875rem;
}

/* ==========================================================================
   Assignments Grid
   ========================================================================== */

.assignments-grid {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.assignment-card {
    background: var(--color-bg-tertiary);
    border: 1px solid var(--color-border-base);
    border-radius: 0.75rem;
    padding: 1.5rem;
    transition: var(--transition-all);
}

.assignment-card:hover {
    background: var(--color-surface-card);
    border-color: var(--color-primary);
    transform: translateY(-1px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
}

.assignment-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.assignee-avatar {
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    background: var(--color-primary);
    color: var(--button-primary-text);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1rem;
    flex-shrink: 0;
}

.assignee-details {
    flex: 1;
}

.assignee-name {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--color-text-primary);
    margin: 0;
}

.assignee-type {
    font-size: 0.875rem;
    color: var(--color-text-secondary);
    margin: 0.25rem 0 0 0;
}

.assignment-status {
    flex-shrink: 0;
}

.assignment-meta {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: var(--color-text-secondary);
}

.meta-icon {
    width: 1rem;
    height: 1rem;
    color: var(--color-primary);
    flex-shrink: 0;
}

.time-text {
    margin-left: 0.5rem;
    color: var(--color-text-primary);
    font-weight: 500;
}

.assignment-actions {
    display: flex;
    justify-content: flex-end;
}

.action-button-success {
    background: var(--task-status-completed-bg);
    color: var(--task-status-completed);
    border-color: var(--task-status-completed);
}

.action-button-success:hover {
    background: var(--task-status-completed);
    color: white;
    border-color: var(--task-status-completed);
}

/* ==========================================================================
   Empty State
   ========================================================================== */

.empty-state {
    text-align: center;
    padding: 3rem 2rem;
}

.empty-state-icon {
    width: 4rem;
    height: 4rem;
    margin: 0 auto 1.5rem;
    color: var(--color-text-muted);
    opacity: 0.6;
}

.empty-state-icon svg {
    width: 100%;
    height: 100%;
}

.empty-state-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--color-text-primary);
    margin: 0 0 0.5rem 0;
}

.empty-state-description {
    font-size: 1rem;
    color: var(--color-text-secondary);
    margin: 0;
    max-width: 400px;
    margin: 0 auto;
}

/* ==========================================================================
   Priority and Status Colors
   ========================================================================== */

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

.status-pending, .status-inactive {
    background: var(--task-status-pending-bg);
    color: var(--task-status-pending);
    border-color: var(--task-status-pending);
}

.status-in_progress {
    background: var(--task-status-in-progress-bg);
    color: var(--task-status-in-progress);
    border-color: var(--task-status-in-progress);
}

.status-completed, .status-active {
    background: var(--task-status-completed-bg);
    color: var(--task-status-completed);
    border-color: var(--task-status-completed);
}

.status-overdue {
    background: var(--task-status-overdue-bg);
    color: var(--task-status-overdue);
    border-color: var(--task-status-overdue);
}

/* ==========================================================================
   Responsive Design
   ========================================================================== */

@media (max-width: 1024px) {
    .task-content-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .task-header-content {
        flex-direction: column;
        gap: 1.5rem;
    }

    .task-status-panel {
        min-width: unset;
    }

    .task-main-title {
        font-size: 2rem;
    }
}

@media (max-width: 768px) {
    .task-show-page {
        padding: 1rem;
    }

    .task-header-card,
    .task-detail-card {
        margin-bottom: 1rem;
    }

    .task-header-content,
    .card-content {
        padding: 1.5rem;
    }

    .card-header {
        padding: 1rem 1.5rem;
    }

    .task-main-title {
        font-size: 1.75rem;
    }

    .task-content-grid {
        gap: 1rem;
    }

    .content-section {
        gap: 1rem;
    }

    .page-header-content {
        flex-direction: column;
        gap: 1rem;
        padding: 1rem;
    }

    .classification-grid {
        grid-template-columns: 1fr;
    }

    .assignment-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .timing-grid,
    .metadata-grid {
        gap: 1rem;
    }

    .detail-row {
        flex-direction: column;
        gap: 0.5rem;
    }

    .detail-label {
        min-width: unset;
    }
}

@media (max-width: 480px) {
    .task-main-title {
        font-size: 1.5rem;
    }

    .card-title {
        font-size: 1.125rem;
    }

    .task-header-content,
    .card-content {
        padding: 1rem;
    }

    .card-header {
        padding: 0.75rem 1rem;
    }

    .assignment-card {
        padding: 1rem;
    }

    .assignments-grid {
        gap: 1rem;
    }
}

/* ==========================================================================
   Toast Notifications
   ========================================================================== */

.toast {
    position: fixed;
    top: var(--space-lg);
    right: var(--space-lg);
    z-index: var(--z-toast);
    padding: var(--space-lg) var(--space-xl);
    border-radius: 0.5rem;
    background: var(--alert-success-bg);
    color: var(--alert-success-text);
    border: 1px solid var(--alert-success-border);
    display: flex;
    align-items: center;
    gap: var(--space-md);
    font-weight: 500;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    animation: slideInRight 0.3s ease-out;
    max-width: 400px;
    word-wrap: break-word;
}

.toast svg {
    width: var(--space-lg);
    height: var(--space-lg);
    flex-shrink: 0;
}

.toast-success {
    background: var(--alert-success-bg);
    color: var(--alert-success-text);
    border: 1px solid var(--alert-success-border);
}

.toast-error {
    background: var(--alert-error-bg);
    color: var(--alert-error-text);
    border: 1px solid var(--alert-error-border);
}

.toast-warning {
    background: var(--alert-warning-bg);
    color: var(--alert-warning-text);
    border: 1px solid var(--alert-warning-border);
}

.toast-info {
    background: var(--alert-info-bg);
    color: var(--alert-info-text);
    border: 1px solid var(--alert-info-border);
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
</style>
@endpush

@push('scripts')
<script>
/**
 * Show toast notification
 */
function showToast(message, type = 'info') {
    // Remove existing toasts
    const existingToasts = document.querySelectorAll('.toast');
    existingToasts.forEach(toast => toast.remove());

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;

    const icon = type === 'success' ? '✓' : type === 'error' ? '✗' : 'ℹ';
    toast.innerHTML = `
        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
            <text x="10" y="15" text-anchor="middle" font-size="14">${icon}</text>
        </svg>
        <span>${message}</span>
    `;

    document.body.appendChild(toast);

    // Auto remove after 5 seconds
    setTimeout(() => {
        toast.remove();
    }, 5000);
}

/**
 * Handle assignment status update
 */
function handleAssignmentStatusUpdate(form) {
    const formData = new FormData(form);
    const button = form.querySelector('button[type="submit"]');
    const originalContent = button.innerHTML;

    // Show loading state
    button.disabled = true;
    button.innerHTML = '<div class="action-icon"><svg class="animate-spin" width="16" height="16" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" stroke-dasharray="31.416" stroke-dashoffset="31.416"><animate attributeName="stroke-dashoffset" dur="1s" repeatCount="indefinite" values="31.416;0"/></circle></svg></div><div class="action-content"><div class="action-title">Updating...</div></div>';

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message || 'Assignment status updated successfully', 'success');

            // Update the assignment card
            const assignmentCard = form.closest('.assignment-card');
            if (assignmentCard) {
                const statusBadge = assignmentCard.querySelector('.status-badge');
                if (statusBadge) {
                    statusBadge.className = 'status-badge status-completed';
                    statusBadge.textContent = 'Completed';
                }

                // Remove the actions section since it's completed
                const actionsSection = assignmentCard.querySelector('.assignment-actions');
                if (actionsSection) {
                    actionsSection.remove();
                }
            }

            // Refresh the page data after a short delay to show updated data
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showToast(data.message || 'Failed to update assignment status', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred while updating the assignment status', 'error');
    })
    .finally(() => {
        // Restore button state
        button.disabled = false;
        button.innerHTML = originalContent;
    });

    return false; // Prevent default form submission
}
</script>
@endpush
