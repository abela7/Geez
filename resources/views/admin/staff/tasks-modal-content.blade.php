<!-- Task Header Card -->
<div class="task-modal-header-card">
    <div class="task-header-content">
        <div class="task-title-section">
            <h1 class="task-modal-title-main">{{ $task->title }}</h1>
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

        @if($task->taskPriority)
            <div class="task-priority-indicator">
                <span class="priority-badge priority-{{ $task->taskPriority->slug }}">
                    <svg class="badge-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"/>
                    </svg>
                    {{ $task->taskPriority->name }}
                </span>
            </div>
        @endif
    </div>
</div>

<!-- Task Description Card -->
@if($task->description || $task->instructions)
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
        @if($task->description)
            <div class="detail-row">
                <div class="detail-label">Description</div>
                <div class="detail-value">{{ $task->description }}</div>
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
@endif

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
            @if($task->taskPriority)
                <div class="classification-item">
                    <div class="item-label">Priority</div>
                    <span class="priority-badge priority-{{ $task->taskPriority->slug }}">
                        <svg class="badge-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        {{ $task->taskPriority->name }}
                    </span>
                </div>
            @endif

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
        </div>
    </div>
</div>

<!-- Task Assignments Card -->
@if($task->assignments && $task->assignments->count() > 0)
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
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Task Timing Card -->
<div class="task-detail-card">
    <div class="card-header">
        <h3 class="card-title">
            <svg class="card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Timing & Schedule
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

            @if($task->scheduled_date || $task->scheduled_time)
                <div class="timing-item timing-item-full">
                    <div class="timing-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="timing-content">
                        <div class="timing-label">Scheduled For</div>
                        <div class="timing-value">
                            @if($task->scheduled_date)
                                {{ $task->scheduled_date->format('M j, Y') }}
                            @endif
                            @if($task->scheduled_time)
                                <span class="schedule-time">at {{ $task->scheduled_time->format('g:i A') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Task Tags Card -->
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

<!-- Task Settings Card -->
<div class="task-detail-card">
    <div class="card-header">
        <h3 class="card-title">
            <svg class="card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Settings & Configuration
        </h3>
    </div>
    <div class="card-content">
        <div class="settings-grid">
            <div class="setting-item">
                <div class="setting-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="setting-content">
                    <div class="setting-label">Requires Approval</div>
                    <div class="setting-value">
                        @if($task->requires_approval)
                            <span class="status-indicator status-active">Yes</span>
                        @else
                            <span class="status-indicator status-inactive">No</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="setting-item">
                <div class="setting-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="setting-content">
                    <div class="setting-label">Auto-assign to staff</div>
                    <div class="setting-value">
                        @if($task->auto_assign)
                            <span class="status-indicator status-active">Yes</span>
                        @else
                            <span class="status-indicator status-inactive">No</span>
                        @endif
                    </div>
                </div>
            </div>

            @if($task->template_name)
                <div class="setting-item setting-item-full">
                    <div class="setting-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                        </svg>
                    </div>
                    <div class="setting-content">
                        <div class="setting-label">Template Name</div>
                        <div class="setting-value">{{ $task->template_name }}</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Task Metadata Card -->
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
                        @if(isset($task->creator))
                            {{ $task->creator->full_name ?? 'System' }}
                        @else
                            System
                        @endif
                    </div>
                </div>
            </div>

            <div class="metadata-item">
                <div class="metadata-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="metadata-content">
                    <div class="metadata-label">Created At</div>
                    <div class="metadata-value">{{ $task->created_at ? $task->created_at->format('M j, Y g:i A') : 'Unknown' }}</div>
                </div>
            </div>

            @if($task->updated_at && $task->updated_at != $task->created_at)
                <div class="metadata-item">
                    <div class="metadata-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    <div class="metadata-content">
                        <div class="metadata-label">Last Updated</div>
                        <div class="metadata-value">{{ $task->updated_at->format('M j, Y g:i A') }}</div>
                    </div>
                </div>

                @if(isset($task->updater) && $task->updater)
                    <div class="metadata-item">
                        <div class="metadata-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <div class="metadata-content">
                            <div class="metadata-label">Updated By</div>
                            <div class="metadata-value">{{ $task->updater->full_name }}</div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

<!-- Action Buttons Card -->
<div class="task-detail-card">
    <div class="card-header">
        <h3 class="card-title">
            <svg class="card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            Actions
        </h3>
    </div>
    <div class="card-content">
        <div class="action-buttons-grid">
            <a href="{{ route('admin.staff.tasks.edit', $task->id) }}"
               class="action-button action-button-primary">
                <div class="action-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div class="action-content">
                    <div class="action-title">Edit Task</div>
                    <div class="action-description">Modify task details and assignments</div>
                </div>
                <div class="action-arrow">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <a href="{{ route('admin.staff.tasks.show', $task->id) }}"
               class="action-button action-button-secondary">
                <div class="action-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <div class="action-content">
                    <div class="action-title">View Full Page</div>
                    <div class="action-description">View complete task details</div>
                </div>
                <div class="action-arrow">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </div>
            </a>
        </div>
    </div>
</div>
