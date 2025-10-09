@extends('layouts.admin')

@section('title', __('staff.tasks.edit_task') . ' - ' . config('app.name'))
@section('page_title', __('staff.tasks.edit_task'))

@section('content')
<div class="edit-task-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('staff.tasks.edit_task') }}</h1>
                <p class="page-subtitle">{{ __('staff.tasks.edit_task_subtitle') }}</p>
            </div>
            
            <div class="page-actions">
                <a href="{{ route('admin.staff.tasks.index') }}" class="btn btn-outline">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('common.back') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Task Status Section -->
    @if($task->hasAssignments())
        <div class="status-section">
            <div class="status-card">
                <div class="status-header">
                    <h3 class="status-title">
                        <svg class="status-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Task Status
                    </h3>
                    <div class="status-indicator {{ $task->isCompleted() ? 'status-completed' : 'status-pending' }}">
                        {{ $task->isCompleted() ? 'Completed' : 'In Progress' }}
                    </div>
                </div>

                <div class="status-actions">
                    @if($task->isCompleted())
                        <button type="button"
                                class="status-action-btn status-action-undo"
                                onclick="updateTaskStatus('mark_undone')"
                                id="mark-undone-btn">
                            <svg class="action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span>Mark as Undone</span>
                        </button>
                    @else
                        <button type="button"
                                class="status-action-btn status-action-done"
                                onclick="updateTaskStatus('mark_done')"
                                id="mark-done-btn">
                            <svg class="action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Mark as Done</span>
                        </button>
                    @endif
                </div>

                <div class="status-summary">
                    <div class="summary-item">
                        <span class="summary-label">Total Assignments:</span>
                        <span class="summary-value">{{ $task->assignments->count() }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Completed:</span>
                        <span class="summary-value">{{ $task->completedAssignments()->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Task Form -->
    <div class="form-container">
        <!-- Validation Errors -->
        @if($errors->any())
            <div class="alert alert-error" style="background: #EF4444; color: white; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                <h4 style="margin: 0 0 0.5rem 0; font-weight: 600;">Please fix the following errors:</h4>
                <ul style="margin: 0; padding-left: 1.5rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success" style="background: #10B981; color: white; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error" style="background: #EF4444; color: white; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.staff.tasks.update', $task) }}" class="task-form" role="form" aria-label="{{ __('staff.tasks.edit_task') }}">
            @csrf
            @method('PUT')
            
            <div class="form-grid">
                <!-- Task Title -->
                <div class="form-group form-group-full">
                    <label for="title" class="form-label">{{ __('staff.tasks.task_title') }} <span class="required">*</span></label>
                    <input type="text" 
                           id="title"
                           name="title"
                           value="{{ old('title', $task->title) }}"
                           class="form-input @error('title') form-input-error @enderror"
                           placeholder="{{ __('staff.tasks.task_title_placeholder') }}"
                           required>
                    @error('title')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Task Description -->
                <div class="form-group form-group-full">
                    <label for="description" class="form-label">{{ __('staff.tasks.task_description') }}</label>
                    <textarea id="description"
                              name="description"
                              class="form-textarea @error('description') form-textarea-error @enderror"
                              rows="3"
                              placeholder="{{ __('staff.tasks.task_description_placeholder') }}">{{ old('description', $task->description) }}</textarea>
                    @error('description')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Task Instructions -->
                <div class="form-group form-group-full">
                    <label for="instructions" class="form-label">{{ __('staff.tasks.task_instructions') }}</label>
                    <textarea id="instructions"
                              name="instructions"
                              class="form-textarea @error('instructions') form-textarea-error @enderror"
                              rows="4"
                              placeholder="{{ __('staff.tasks.task_instructions_placeholder') }}">{{ old('instructions', $task->instructions) }}</textarea>
                    <div class="form-help">{{ __('staff.tasks.instructions_help') }}</div>
                    @error('instructions')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Task Type -->
                <div class="form-group">
                    <label for="task_type" class="form-label">{{ __('staff.tasks.task_type') }} <span class="required">*</span></label>
                    <select id="task_type" 
                            name="task_type" 
                            class="form-select @error('task_type') form-select-error @enderror" 
                            required>
                        <option value="">{{ __('common.select') }}</option>
                        @foreach($taskTypes as $type)
                            <option value="{{ $type->slug }}" {{ old('task_type', $task->task_type) == $type->slug ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('task_type')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Priority -->
                <div class="form-group">
                    <label for="priority" class="form-label">{{ __('staff.tasks.priority') }} <span class="required">*</span></label>
                    <select id="priority" 
                            name="priority" 
                            class="form-select @error('priority') form-select-error @enderror" 
                            required>
                        <option value="">{{ __('common.select') }}</option>
                        @foreach($taskPriorities as $priority)
                            <option value="{{ $priority->slug }}" {{ old('priority', $task->priority) == $priority->slug ? 'selected' : '' }}>
                                {{ $priority->name }} (Level {{ $priority->level }})
                            </option>
                        @endforeach
                    </select>
                    @error('priority')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Category -->
                <div class="form-group">
                    <label for="category" class="form-label">{{ __('staff.tasks.category') }} <span class="required">*</span></label>
                    <select id="category" 
                            name="category" 
                            class="form-select @error('category') form-select-error @enderror" 
                            required>
                        <option value="">{{ __('common.select') }}</option>
                        @foreach($taskCategories as $category)
                            <option value="{{ $category->slug }}" {{ old('category', $task->category) == $category->slug ? 'selected' : '' }}>
                                {{ $category->name }}
                                @if($category->parent_category)
                                    ({{ $category->parent_category }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('category')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Estimated Hours -->
                <div class="form-group">
                    <label for="estimated_hours" class="form-label">{{ __('staff.tasks.estimated_hours') }}</label>
                    <input type="number" 
                           id="estimated_hours"
                           name="estimated_hours"
                           value="{{ old('estimated_hours', $task->estimated_hours) }}"
                           class="form-input @error('estimated_hours') form-input-error @enderror"
                           min="0"
                           max="999.99"
                           step="0.01">
                    @error('estimated_hours')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Duration (Minutes) -->
                <div class="form-group">
                    <label for="duration_minutes" class="form-label">{{ __('staff.tasks.duration_minutes') }}</label>
                    <input type="number" 
                           id="duration_minutes"
                           name="duration_minutes"
                           value="{{ old('duration_minutes', $task->duration_minutes) }}"
                           class="form-input @error('duration_minutes') form-input-error @enderror"
                           min="1"
                           max="1440">
                    <div class="form-help">{{ __('staff.tasks.duration_help') }}</div>
                    @error('duration_minutes')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Scheduled Date -->
                <div class="form-group">
                    <label for="scheduled_date" class="form-label">{{ __('staff.tasks.scheduled_date') }}</label>
                    <input type="date" 
                           id="scheduled_date"
                           name="scheduled_date"
                           value="{{ old('scheduled_date', $task->scheduled_date ? $task->scheduled_date->format('Y-m-d') : '') }}"
                           class="form-input @error('scheduled_date') form-input-error @enderror"
                           min="{{ date('Y-m-d') }}">
                    @error('scheduled_date')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Scheduled Time -->
                <div class="form-group">
                    <label for="scheduled_time" class="form-label">{{ __('staff.tasks.scheduled_time') }}</label>
                    <input type="time" 
                           id="scheduled_time"
                           name="scheduled_time"
                           value="{{ old('scheduled_time', $task->scheduled_time ? \Carbon\Carbon::parse($task->scheduled_time)->format('H:i') : '') }}"
                           class="form-input @error('scheduled_time') form-input-error @enderror">
                    @error('scheduled_time')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tags -->
                <div class="form-group form-group-full">
                    <label for="tags" class="form-label">{{ __('staff.tasks.tags') }}</label>
                    <input type="text" 
                           id="tags"
                           name="tags"
                           value="{{ old('tags', is_array($task->tags) ? implode(', ', $task->tags) : '') }}"
                           class="form-input @error('tags') form-input-error @enderror"
                           placeholder="{{ __('staff.tasks.tags_placeholder') }}">
                    <div class="form-help">{{ __('staff.tasks.tags_help') }}</div>
                    
                    @if($taskTags->count() > 0)
                        <div class="available-tags">
                            <span class="available-tags-label">{{ __('staff.tasks.available_tags') }}:</span>
                            <div class="tags-list">
                                @foreach($taskTags as $tag)
                                    <button type="button" 
                                            class="tag-button" 
                                            onclick="addTag('{{ $tag->name }}')"
                                            style="background-color: {{ $tag->color }}20; border-color: {{ $tag->color }}; color: {{ $tag->color }};">
                                        {{ $tag->name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    @error('tags')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Template Options -->
                <div class="form-group form-group-full">
                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" 
                                   name="is_template" 
                                   value="1"
                                   {{ old('is_template', $task->is_template) ? 'checked' : '' }}
                                   class="checkbox-input"
                                   onchange="toggleTemplateField(this)">
                            <span class="checkbox-text">{{ __('staff.tasks.is_template') }}</span>
                        </label>
                    </div>
                </div>

                <!-- Template Name (Hidden by default) -->
                <div class="form-group form-group-full" id="template-name-group" style="display: {{ old('is_template', $task->is_template) ? 'block' : 'none' }};">
                    <label for="template_name" class="form-label">{{ __('staff.tasks.template_name') }}</label>
                    <input type="text" 
                           id="template_name"
                           name="template_name"
                           value="{{ old('template_name', $task->template_name) }}"
                           class="form-input @error('template_name') form-input-error @enderror"
                           placeholder="{{ __('staff.tasks.template_name_placeholder') }}">
                    @error('template_name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Approval Required -->
                <div class="form-group form-group-full">
                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" 
                                   name="requires_approval" 
                                   value="1"
                                   {{ old('requires_approval', $task->requires_approval) ? 'checked' : '' }}
                                   class="checkbox-input">
                            <span class="checkbox-text">{{ __('staff.tasks.requires_approval') }}</span>
                        </label>
                    </div>
                </div>

                <!-- Auto Assignment -->
                <div class="form-group form-group-full">
                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" 
                                   name="auto_assign" 
                                   value="1"
                                   {{ old('auto_assign', $task->assignments->count() > 0) ? 'checked' : '' }}
                                   class="checkbox-input"
                                   id="auto-assign-checkbox"
                                   onchange="toggleAssignmentSection(this)">
                            <span class="checkbox-text">{{ __('staff.tasks.auto_assign') }}</span>
                        </label>
                    </div>
                    <div class="form-help">{{ __('staff.tasks.auto_assign_help') }}</div>
                </div>

                <!-- Staff Assignment Section -->
                <div class="form-group form-group-full" id="assignment-section" style="display: {{ old('auto_assign', $task->assignments->count() > 0) ? 'block' : 'none' }};">
                    <label class="form-label">{{ __('staff.tasks.assign_to_staff') }}</label>
                    <div class="staff-assignment-grid">
                        @php
                            $assignedStaffIds = $task->assignments->pluck('staff_id')->toArray();
                        @endphp
                        @foreach($staffMembers as $staff)
                            <div class="staff-assignment-item">
                                <label class="staff-checkbox-label">
                                    <input type="checkbox" 
                                           name="assigned_staff[]" 
                                           value="{{ $staff->id }}"
                                           {{ in_array($staff->id, old('assigned_staff', $assignedStaffIds)) ? 'checked' : '' }}
                                           class="staff-checkbox">
                                    <div class="staff-info">
                                        <div class="staff-name">{{ $staff->first_name }} {{ $staff->last_name }}</div>
                                        <div class="staff-type">{{ $staff->staffType->display_name ?? 'Staff' }}</div>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('assigned_staff')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Active Status -->
                <div class="form-group form-group-full">
                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', $task->is_active ?? true) ? 'checked' : '' }}
                                   class="checkbox-input">
                            <span class="checkbox-text">{{ __('staff.tasks.is_active') }}</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ __('staff.tasks.update_task') }}
                </button>
                <a href="{{ route('admin.staff.tasks.index') }}" class="btn btn-secondary">
                    {{ __('common.cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
/* ==========================================================================
   Task Status Section - Edit Page
   ========================================================================== */

.status-section {
    margin-bottom: 2rem;
}

.status-card {
    background: var(--color-surface-card);
    border: 1px solid var(--color-surface-card-border);
    border-radius: 1rem;
    padding: 2rem;
    box-shadow: var(--color-surface-card-shadow);
    transition: var(--transition-all);
}

.status-card:hover {
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
    transform: translateY(-1px);
}

.status-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
}

.status-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--color-text-primary);
}

.status-icon {
    width: 1.5rem;
    height: 1.5rem;
    color: var(--color-primary);
}

.status-indicator {
    padding: 0.5rem 1rem;
    border-radius: 0.75rem;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-indicator.status-completed {
    background: var(--task-status-completed-bg);
    color: var(--task-status-completed);
    border: 2px solid var(--task-status-completed);
}

.status-indicator.status-pending {
    background: var(--task-status-pending-bg);
    color: var(--task-status-pending);
    border: 2px solid var(--task-status-pending);
}

.status-actions {
    margin-bottom: 1.5rem;
}

.status-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.875rem 1.5rem;
    border: 2px solid;
    border-radius: 0.75rem;
    background: transparent;
    color: inherit;
    font-weight: 500;
    font-size: 0.875rem;
    cursor: pointer;
    transition: var(--transition-all);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
}

.status-action-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.status-action-done {
    background: var(--task-status-completed-bg);
    color: var(--task-status-completed);
    border-color: var(--task-status-completed);
}

.status-action-done:hover {
    background: var(--task-status-completed);
    color: white;
    border-color: var(--task-status-completed);
}

.status-action-undo {
    background: var(--task-status-overdue-bg);
    color: var(--task-status-overdue);
    border-color: var(--task-status-overdue);
}

.status-action-undo:hover {
    background: var(--task-status-overdue);
    color: white;
    border-color: var(--task-status-overdue);
}

.action-icon {
    width: 1.25rem;
    height: 1.25rem;
    flex-shrink: 0;
}

.status-summary {
    display: flex;
    gap: 2rem;
    padding-top: 1rem;
    border-top: 1px solid var(--color-border-base);
}

.summary-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.summary-label {
    font-size: 0.875rem;
    color: var(--color-text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-weight: 500;
}

.summary-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--color-text-primary);
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

/* ==========================================================================
   Responsive Design
   ========================================================================== */

@media (max-width: 768px) {
    .status-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .status-summary {
        flex-direction: column;
        gap: 1rem;
    }

    .status-card {
        padding: 1.5rem;
    }

    .status-title {
        font-size: 1.25rem;
    }
}

@media (max-width: 480px) {
    .status-card {
        padding: 1rem;
    }

    .status-action-btn {
        padding: 0.75rem 1rem;
        font-size: 0.8rem;
    }

    .status-summary {
        gap: 1rem;
    }
}
</style>
    @vite('resources/css/admin/staff/tasks.css')
@endpush

@push('scripts')
<script>
/**
 * Update task completion status (mark as done/undone)
 */
function updateTaskStatus(action) {
    const button = document.getElementById(action === 'mark_done' ? 'mark-done-btn' : 'mark-undone-btn');
    const originalContent = button.innerHTML;

    // Show loading state
    button.disabled = true;
    button.innerHTML = '<svg class="animate-spin" width="16" height="16" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" stroke-dasharray="31.416" stroke-dashoffset="31.416"><animate attributeName="stroke-dashoffset" dur="1s" repeatCount="indefinite" values="31.416;0"/></circle></svg><span>Updating...</span>';

    fetch(`{{ route('admin.staff.tasks.update-status', $task) }}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            action: action
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message || 'Task status updated successfully', 'success');

            // Update the status indicator
            const statusIndicator = document.querySelector('.status-indicator');
            const statusActions = document.querySelector('.status-actions');
            const statusSummary = document.querySelector('.status-summary');

            if (statusIndicator && statusActions) {
                if (data.is_completed) {
                    statusIndicator.className = 'status-indicator status-completed';
                    statusIndicator.textContent = 'Completed';
                    statusActions.innerHTML = `
                        <button type="button"
                                class="status-action-btn status-action-undo"
                                onclick="updateTaskStatus('mark_undone')"
                                id="mark-undone-btn">
                            <svg class="action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span>Mark as Undone</span>
                        </button>
                    `;
                } else {
                    statusIndicator.className = 'status-indicator status-pending';
                    statusIndicator.textContent = 'In Progress';
                    statusActions.innerHTML = `
                        <button type="button"
                                class="status-action-btn status-action-done"
                                onclick="updateTaskStatus('mark_done')"
                                id="mark-done-btn">
                            <svg class="action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Mark as Done</span>
                        </button>
                    `;
                }
            }

            // Update summary if it exists
            if (statusSummary && data.task) {
                const completedCount = data.task.assignments ? data.task.assignments.filter(a => a.status === 'completed').length : 0;
                const totalCount = data.task.assignments ? data.task.assignments.length : 0;

                statusSummary.innerHTML = `
                    <div class="summary-item">
                        <span class="summary-label">Total Assignments:</span>
                        <span class="summary-value">${totalCount}</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Completed:</span>
                        <span class="summary-value">${completedCount}</span>
                    </div>
                `;
            }

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
        button.innerHTML = originalContent;
    });
}

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
</script>
    @vite('resources/js/admin/staff-tasks.js')
@endpush

