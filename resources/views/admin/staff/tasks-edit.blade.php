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
                                {{ $type->display_name }}
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
                                {{ $priority->display_name }} (Level {{ $priority->level }})
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
                                {{ $category->display_name }}
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
    @vite('resources/css/admin/staff/tasks.css')
@endpush

@push('scripts')
    @vite('resources/js/admin/staff-tasks.js')
@endpush

