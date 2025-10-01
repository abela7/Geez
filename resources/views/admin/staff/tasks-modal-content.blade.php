<!-- Task Title and Basic Info -->
<div class="task-detail-section">
    <div class="task-detail-label">{{ __('staff.tasks.task_title') }}</div>
    <div class="task-detail-value">
        <h2 class="task-modal-title-main">{{ $task->title }}</h2>
    </div>
</div>

@if($task->description)
<div class="task-detail-section">
    <div class="task-detail-label">{{ __('staff.tasks.description') }}</div>
    <div class="task-detail-value">{{ $task->description }}</div>
</div>
@endif

@if($task->instructions)
<div class="task-detail-section">
    <div class="task-detail-label">{{ __('staff.tasks.task_instructions') }}</div>
    <div class="task-detail-value">{{ $task->instructions }}</div>
</div>
@endif

<!-- Task Status and Priority -->
<div class="task-detail-section">
    <div class="task-detail-label">{{ __('staff.tasks.status_and_priority') }}</div>
    <div class="task-detail-badges">
        @if($task->taskPriority)
            <span class="task-detail-badge priority-{{ $task->taskPriority->slug }}">
                {{ $task->taskPriority->name }} {{ __('staff.tasks.priority') }}
            </span>
        @endif
        
        @if($task->taskType)
            <span class="task-detail-badge task-type-badge" style="background: {{ $task->taskType->color ?? 'var(--task-modal-text-secondary)' }}20; color: {{ $task->taskType->color ?? 'var(--task-modal-text-secondary)' }};">
                {{ $task->taskType->name }}
            </span>
        @endif
        
        @if($task->taskCategory)
            <span class="task-detail-badge task-category-badge">
                {{ $task->taskCategory->name }}
            </span>
        @endif
    </div>
</div>

<!-- Task Assignments -->
@if($task->assignments && $task->assignments->count() > 0)
<div class="task-detail-section">
    <div class="task-detail-label">{{ __('staff.tasks.assigned_to') }} ({{ $task->assignments->count() }})</div>
    <div class="task-assignees">
        @foreach($task->assignments as $assignment)
            <div class="task-assignee">
                <div class="task-assignee-avatar">
                    {{ substr($assignment->staff->first_name ?? 'U', 0, 1) }}{{ substr($assignment->staff->last_name ?? 'U', 0, 1) }}
                </div>
                <div class="task-assignee-info">
                    <div class="task-assignee-name">{{ $assignment->staff->full_name ?? 'Unknown' }}</div>
                    <div class="task-assignee-type">{{ $assignment->staff->staffType->display_name ?? 'No Type' }}</div>
                </div>
                <div class="task-detail-badges">
                    <span class="task-detail-badge status-{{ $assignment->status }}">
                        {{ __('staff.tasks.' . $assignment->status) }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

<!-- Task Timing -->
<div class="task-detail-section">
    <div class="task-detail-label">{{ __('staff.tasks.timing_information') }}</div>
    <div class="task-detail-value">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 0.5rem;">
            @if($task->estimated_hours)
                <div>
                    <strong>{{ __('staff.tasks.estimated_hours') }}:</strong><br>
                    <span class="task-detail-muted">{{ $task->estimated_hours }} {{ __('common.hours') }}</span>
                </div>
            @endif
            
            @if($task->duration_minutes)
                <div>
                    <strong>{{ __('staff.tasks.duration_minutes') }}:</strong><br>
                    <span class="task-detail-muted">{{ $task->duration_minutes }} {{ __('common.minutes') }}</span>
                </div>
            @endif
        </div>
        
        @if($task->scheduled_date || $task->scheduled_time)
            <div style="margin-top: 0.5rem;">
                <strong>{{ __('staff.tasks.scheduled_for') }}:</strong><br>
                <span class="task-detail-muted">
                    @if($task->scheduled_date)
                        {{ $task->scheduled_date->format('M j, Y') }}
                    @endif
                    @if($task->scheduled_time)
                        {{ __('common.at') }} {{ $task->scheduled_time->format('g:i A') }}
                    @endif
                </span>
            </div>
        @endif
    </div>
</div>

<!-- Task Tags -->
@if($task->tags && count($task->tags) > 0)
<div class="task-detail-section">
    <div class="task-detail-label">{{ __('staff.tasks.tags') }}</div>
    <div class="task-tags">
        @foreach($task->tags as $tag)
            <span class="task-tag">{{ $tag }}</span>
        @endforeach
    </div>
</div>
@endif

<!-- Task Settings -->
<div class="task-detail-section">
    <div class="task-detail-label">{{ __('staff.tasks.task_settings') }}</div>
    <div class="task-detail-value">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div>
                <strong>{{ __('staff.tasks.requires_approval') }}:</strong><br>
                <span class="task-detail-muted">
                    {{ $task->requires_approval ? __('common.yes') : __('common.no') }}
                </span>
            </div>
            
            <div>
                <strong>{{ __('staff.tasks.auto_assign') }}:</strong><br>
                <span class="task-detail-muted">
                    {{ $task->auto_assign ? __('common.yes') : __('common.no') }}
                </span>
            </div>
        </div>
        
        @if($task->template_name)
            <div style="margin-top: 0.5rem;">
                <strong>{{ __('staff.tasks.template_name') }}:</strong><br>
                <span class="task-detail-muted">{{ $task->template_name }}</span>
            </div>
        @endif
    </div>
</div>

<!-- Task Metadata -->
<div class="task-detail-section">
    <div class="task-detail-label">{{ __('common.created_information') }}</div>
    <div class="task-detail-value">
        <div class="task-metadata-grid">
            <div>
                <strong>{{ __('common.created_by') }}:</strong><br>
                @if(isset($task->creator))
                    {{ $task->creator->full_name ?? 'System' }}
                @else
                    System
                @endif
            </div>
            
            <div>
                <strong>{{ __('common.created_at') }}:</strong><br>
                {{ $task->created_at ? $task->created_at->format('M j, Y g:i A') : 'Unknown' }}
            </div>
            
            @if($task->updated_at && $task->updated_at != $task->created_at)
                <div>
                    <strong>{{ __('common.last_updated') }}:</strong><br>
                    {{ $task->updated_at->format('M j, Y g:i A') }}
                </div>
                
                @if(isset($task->updater) && $task->updater)
                    <div>
                        <strong>{{ __('common.updated_by') }}:</strong><br>
                        {{ $task->updater->full_name }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="task-detail-section">
    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
        <a href="{{ route('admin.staff.tasks.edit', $task->id) }}" 
           class="btn btn-primary task-modal-btn-primary" 
           style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; color: white; text-decoration: none; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 500; transition: all 0.2s ease;">
            <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            {{ __('staff.tasks.edit') }}
        </a>
        
        <a href="{{ route('admin.staff.tasks.show', $task->id) }}" 
           class="btn btn-secondary task-modal-btn-secondary" 
           style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; color: var(--task-modal-text-primary); text-decoration: none; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 500; transition: all 0.2s ease;">
            <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            {{ __('staff.tasks.view_full_page') }}
        </a>
    </div>
</div>
