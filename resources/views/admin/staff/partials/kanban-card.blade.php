<div class="kanban-card" x-data="{ showDetails: false }">
    <div class="kanban-card-header">
        <div class="task-priority task-priority-{{ $assignment->task->priority ?? 'medium' }}">
            {{ __('staff.tasks.' . ($assignment->task->priority ?? 'medium')) }}
        </div>
        <div class="task-actions">
            <button @click="showDetails = !showDetails" class="task-action-btn" title="{{ __('staff.tasks.view_details') }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </button>
        </div>
    </div>
    
    <div class="kanban-card-body">
        <h4 class="task-title">{{ $assignment->task->title ?? 'Untitled Task' }}</h4>
        <p class="task-description">{{ Str::limit($assignment->task->description ?? '', 80) }}</p>
        
        <div class="task-meta">
            <span class="task-type">{{ __('staff.tasks.' . ($assignment->task->task_type ?? 'project')) }}</span>
            <span class="task-category">{{ __('staff.tasks.' . ($assignment->task->category ?? 'general')) }}</span>
        </div>

        <div class="assignment-info">
            <div class="assignee-info">
                <div class="assignee-avatar">
                    {{ substr($assignment->staff->first_name ?? 'U', 0, 1) }}{{ substr($assignment->staff->last_name ?? 'U', 0, 1) }}
                </div>
                <span class="assignee-name">{{ $assignment->staff->full_name ?? 'Unknown' }}</span>
            </div>
            
            @if($assignment->due_date)
                <div class="due-date {{ $assignment->due_date < now() ? 'overdue' : '' }}">
                    <svg class="due-date-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $assignment->due_date->format('M j') }}
                </div>
            @endif
        </div>

        @if($assignment->progress_percentage)
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ $assignment->progress_percentage }}%"></div>
                <span class="progress-text">{{ $assignment->progress_percentage }}%</span>
            </div>
        @endif
    </div>

    <div x-show="showDetails" x-transition class="kanban-card-details">
        <div class="assignment-details">
            <div class="detail-row">
                <span class="detail-label">{{ __('staff.tasks.assigned') }}:</span>
                <span class="detail-value">{{ $assignment->assigned_date->format('M j, Y') }}</span>
            </div>
            
            @if($assignment->started_at)
                <div class="detail-row">
                    <span class="detail-label">{{ __('staff.tasks.start_time') }}:</span>
                    <span class="detail-value">{{ $assignment->started_at->format('M j, Y H:i') }}</span>
                </div>
            @endif
            
            @if($assignment->notes)
                <div class="detail-row">
                    <span class="detail-label">{{ __('staff.tasks.assignment_notes') }}:</span>
                    <span class="detail-value">{{ $assignment->notes }}</span>
                </div>
            @endif
        </div>

        <div class="card-actions">
            <button @click="updateAssignmentStatus({{ $assignment->id }}, 'in_progress')" 
                    x-show="'{{ $assignment->status }}' === 'assigned'"
                    class="btn btn-sm btn-primary">
                {{ __('staff.tasks.mark_in_progress') }}
            </button>
            
            <button @click="updateAssignmentStatus({{ $assignment->id }}, 'completed')" 
                    x-show="'{{ $assignment->status }}' === 'in_progress'"
                    class="btn btn-sm btn-success">
                {{ __('staff.tasks.mark_complete') }}
            </button>
        </div>
    </div>
</div>
