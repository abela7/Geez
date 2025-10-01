@extends('layouts.admin')

@section('title', __('staff.tasks.create_task') . ' - ' . config('app.name'))
@section('page_title', __('staff.tasks.create_task'))

@section('content')
<div class="create-task-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('staff.tasks.create_task') }}</h1>
                <p class="page-subtitle">{{ __('staff.tasks.create_task_subtitle') }}</p>
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

        <form method="POST" action="{{ route('admin.staff.tasks.store') }}" class="task-form" role="form" aria-label="{{ __('staff.tasks.create_task') }}">
            @csrf
            
            <div class="form-grid">
                <!-- Task Title -->
                <div class="form-group form-group-full">
                    <label for="title" class="form-label">{{ __('staff.tasks.task_title') }} <span class="required">*</span></label>
                    <input type="text" 
                           id="title"
                           name="title"
                           value="{{ old('title') }}"
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
                              placeholder="{{ __('staff.tasks.task_description_placeholder') }}">{{ old('description') }}</textarea>
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
                              placeholder="{{ __('staff.tasks.task_instructions_placeholder') }}">{{ old('instructions') }}</textarea>
                    <div class="form-help">{{ __('staff.tasks.instructions_help') }}</div>
                    @error('instructions')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Task Type -->
                <div class="form-group">
                    <label for="task_type" class="form-label">{{ __('staff.tasks.task_type') }} <span class="required">*</span></label>
                    <select id="task_type" name="task_type" class="form-select @error('task_type') form-select-error @enderror" required>
                        <option value="">{{ __('common.select') }}</option>
                        @foreach($taskTypes as $type)
                            <option value="{{ $type->slug }}" 
                                    {{ old('task_type') === $type->slug ? 'selected' : '' }}
                                    style="color: {{ $type->color }};">
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
                    <select id="priority" name="priority" class="form-select @error('priority') form-select-error @enderror" required>
                        <option value="">{{ __('common.select') }}</option>
                        @foreach($taskPriorities as $priority)
                            <option value="{{ $priority->slug }}" 
                                    {{ old('priority') === $priority->slug ? 'selected' : '' }}
                                    style="color: {{ $priority->color }};">
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
                    <select id="category" name="category" class="form-select @error('category') form-select-error @enderror" required>
                        <option value="">{{ __('common.select') }}</option>
                        @foreach($taskCategories as $category)
                            <option value="{{ $category->slug }}" 
                                    {{ old('category') === $category->slug ? 'selected' : '' }}
                                    style="color: {{ $category->color }};">
                                {{ $category->name }}
                                @if($category->parent_id)
                                    ({{ $category->parent->name ?? 'Subcategory' }})
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
                           value="{{ old('estimated_hours') }}"
                           class="form-input @error('estimated_hours') form-input-error @enderror"
                           step="0.25"
                           min="0"
                           max="999.99"
                           placeholder="0.00">
                    @error('estimated_hours')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Duration in Minutes -->
                <div class="form-group">
                    <label for="duration_minutes" class="form-label">{{ __('staff.tasks.duration_minutes') }}</label>
                    <input type="number" 
                           id="duration_minutes"
                           name="duration_minutes"
                           value="{{ old('duration_minutes') }}"
                           class="form-input @error('duration_minutes') form-input-error @enderror"
                           min="1"
                           max="1440"
                           placeholder="30">
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
                           value="{{ old('scheduled_date') }}"
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
                           value="{{ old('scheduled_time') }}"
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
                           value="{{ old('tags') }}"
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
                                   {{ old('is_template') ? 'checked' : '' }}
                                   class="checkbox-input"
                                   onchange="toggleTemplateField(this)">
                            <span class="checkbox-text">{{ __('staff.tasks.is_template') }}</span>
                        </label>
                    </div>
                </div>

                <!-- Template Name (Hidden by default) -->
                <div class="form-group form-group-full" id="template-name-group" style="display: none;">
                    <label for="template_name" class="form-label">{{ __('staff.tasks.template_name') }}</label>
                    <input type="text" 
                           id="template_name"
                           name="template_name"
                           value="{{ old('template_name') }}"
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
                                   {{ old('requires_approval') ? 'checked' : '' }}
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
                                   {{ old('auto_assign') ? 'checked' : '' }}
                                   class="checkbox-input"
                                   onchange="toggleAssignmentSection(this)">
                            <span class="checkbox-text">{{ __('staff.tasks.auto_assign') }}</span>
                        </label>
                    </div>
                    <div class="form-help">{{ __('staff.tasks.auto_assign_help') }}</div>
                </div>

                <!-- Staff Assignment Section -->
                <div class="form-group form-group-full" id="assignment-section" style="display: none;">
                    <label class="form-label">{{ __('staff.tasks.assign_to_staff') }}</label>
                    <div class="staff-assignment-grid">
                        @foreach($staffMembers as $staff)
                            <div class="staff-assignment-item">
                                <label class="staff-checkbox-label">
                                    <input type="checkbox" 
                                           name="assigned_staff[]" 
                                           value="{{ $staff->id }}"
                                           {{ in_array($staff->id, old('assigned_staff', [])) ? 'checked' : '' }}
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
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ __('staff.tasks.create_task') }}
                </button>
                <a href="{{ route('admin.staff.tasks.index') }}" class="btn btn-secondary">
                    {{ __('common.cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function toggleTemplateField(checkbox) {
    const templateGroup = document.getElementById('template-name-group');
    if (checkbox.checked) {
        templateGroup.style.display = 'block';
    } else {
        templateGroup.style.display = 'none';
        document.getElementById('template_name').value = '';
    }
}

function toggleAssignmentSection(checkbox) {
    const assignmentSection = document.getElementById('assignment-section');
    if (checkbox.checked) {
        assignmentSection.style.display = 'block';
    } else {
        assignmentSection.style.display = 'none';
        // Uncheck all staff assignments
        const staffCheckboxes = document.querySelectorAll('input[name="assigned_staff[]"]');
        staffCheckboxes.forEach(cb => cb.checked = false);
    }
}

function addTag(tagName) {
    const tagsInput = document.getElementById('tags');
    const currentTags = tagsInput.value.trim();
    
    // Check if tag already exists
    const existingTags = currentTags ? currentTags.split(',').map(tag => tag.trim()) : [];
    if (existingTags.includes(tagName)) {
        return; // Tag already exists
    }
    
    // Add the new tag
    if (currentTags) {
        tagsInput.value = currentTags + ', ' + tagName;
    } else {
        tagsInput.value = tagName;
    }
    
    // Add visual feedback
    const button = event.target;
    const originalText = button.textContent;
    button.textContent = '✓ Added';
    button.style.opacity = '0.6';
    
    setTimeout(() => {
        button.textContent = originalText;
        button.style.opacity = '1';
    }, 1000);
}

// Show template field and assignment section if they were checked on page load
document.addEventListener('DOMContentLoaded', function() {
    const templateCheckbox = document.querySelector('input[name="is_template"]');
    if (templateCheckbox && templateCheckbox.checked) {
        toggleTemplateField(templateCheckbox);
    }
    
    const autoAssignCheckbox = document.querySelector('input[name="auto_assign"]');
    if (autoAssignCheckbox && autoAssignCheckbox.checked) {
        toggleAssignmentSection(autoAssignCheckbox);
    }
});
</script>
@endsection

@push('styles')
<style>
/* Mobile-First Design for Task Creation */
.create-task-page {
    padding: 1rem;
    background: var(--color-bg-primary);
    min-height: 100vh;
}

/* Mobile-First Header */
.page-header {
    margin-bottom: 1.5rem;
    text-align: center;
}

.page-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--color-text-primary);
    margin: 0 0 0.5rem 0;
}

.page-subtitle {
    color: var(--color-text-secondary);
    font-size: 0.875rem;
    margin: 0;
}

/* Mobile-First Form Container */
.form-container {
    background: var(--color-surface-card);
    border: 1px solid var(--color-surface-card-border);
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: var(--color-surface-card-shadow);
    margin-bottom: 1rem;
}

/* Mobile-First Form Layout - Single Column by Default */
.form-grid {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-label {
    font-weight: 600;
    color: var(--color-text-primary);
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.required {
    color: var(--task-priority-urgent);
    font-size: 0.75rem;
}

/* Mobile-Optimized Form Controls */
.form-input, .form-textarea, .form-select {
    padding: 1rem;
    border: 2px solid var(--form-input-border);
    border-radius: 0.75rem;
    background: var(--form-input-bg);
    color: var(--form-input-text);
    font-size: 1rem;
    transition: var(--transition-all);
    -webkit-appearance: none;
    appearance: none;
}

.form-input:focus, .form-textarea:focus, .form-select:focus {
    outline: none;
    border-color: var(--form-input-border-focus);
    box-shadow: var(--form-input-shadow-focus);
    transform: scale(1.02);
}

.form-input::placeholder, .form-textarea::placeholder {
    color: var(--form-input-placeholder);
    font-size: 0.9rem;
}

.form-textarea {
    min-height: 100px;
    resize: vertical;
}

.form-select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.75rem center;
    background-repeat: no-repeat;
    background-size: 1.25rem;
    padding-right: 3rem;
}

/* Error States */
.form-input-error, .form-textarea-error, .form-select-error {
    border-color: var(--task-priority-urgent);
    box-shadow: 0 0 0 3px var(--task-priority-urgent-bg);
    animation: shake 0.3s ease-in-out;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.form-error {
    color: var(--task-priority-urgent);
    font-size: 0.8125rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.form-help {
    color: var(--color-text-muted);
    font-size: 0.8125rem;
    line-height: 1.4;
}

/* Mobile-Optimized Checkbox Groups */
.checkbox-group {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: var(--color-bg-tertiary);
    border-radius: 0.75rem;
    border: 2px solid var(--color-border-base);
    transition: var(--transition-all);
}

.checkbox-group:focus-within {
    border-color: var(--color-primary);
    box-shadow: var(--form-input-shadow-focus);
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
    flex: 1;
}

.checkbox-input {
    width: 1.25rem;
    height: 1.25rem;
    accent-color: var(--color-primary);
    cursor: pointer;
}

.checkbox-text {
    color: var(--color-text-primary);
    font-weight: 500;
    font-size: 0.9rem;
}

/* Available Tags Styling */
.available-tags {
    margin-top: 0.75rem;
    padding: 1rem;
    background: var(--color-bg-tertiary);
    border-radius: 0.5rem;
    border: 1px solid var(--color-border-base);
}

.available-tags-label {
    font-size: 0.8125rem;
    font-weight: 500;
    color: var(--color-text-secondary);
    margin-bottom: 0.5rem;
    display: block;
}

.tags-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.tag-button {
    padding: 0.375rem 0.75rem;
    border: 1px solid;
    border-radius: 1rem;
    font-size: 0.75rem;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition-all);
    background: transparent;
}

.tag-button:hover {
    transform: scale(1.05);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.tag-button:active {
    transform: scale(0.98);
}

/* Staff Assignment Styling */
.staff-assignment-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0.75rem;
    margin-top: 0.75rem;
}

.staff-assignment-item {
    border: 2px solid var(--color-border-base);
    border-radius: 0.75rem;
    transition: var(--transition-all);
    overflow: hidden;
}

.staff-assignment-item:hover {
    border-color: var(--color-primary);
    box-shadow: var(--form-input-shadow-focus);
}

.staff-checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    cursor: pointer;
    width: 100%;
    margin: 0;
}

.staff-checkbox {
    width: 1.25rem;
    height: 1.25rem;
    accent-color: var(--color-primary);
    cursor: pointer;
    flex-shrink: 0;
}

.staff-info {
    flex: 1;
}

.staff-name {
    font-weight: 600;
    color: var(--color-text-primary);
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.staff-type {
    font-size: 0.75rem;
    color: var(--color-text-secondary);
    font-weight: 500;
}

.staff-assignment-item:has(.staff-checkbox:checked) {
    border-color: var(--color-primary);
    background: var(--color-primary-bg);
}

.staff-assignment-item:has(.staff-checkbox:checked) .staff-name {
    color: var(--color-primary);
}

/* Mobile-First Action Buttons */
.form-actions {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    padding-top: 1.5rem;
    border-top: 2px solid var(--color-border-base);
    margin-top: 1.5rem;
}

/* Enhanced Button System for Mobile */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 1rem 1.5rem;
    border-radius: 0.75rem;
    font-weight: 600;
    font-size: 1rem;
    text-decoration: none;
    border: 2px solid transparent;
    cursor: pointer;
    transition: var(--transition-all);
    gap: 0.5rem;
    min-height: 48px; /* Touch-friendly minimum */
    position: relative;
    overflow: hidden;
}

.btn:focus {
    outline: none;
    box-shadow: var(--form-input-shadow-focus);
}

.btn:active {
    transform: scale(0.98);
}

.btn-primary {
    background: var(--button-primary-bg);
    color: var(--button-primary-text);
    border-color: var(--button-primary-bg);
    box-shadow: var(--button-primary-shadow);
}

.btn-primary:hover {
    background: var(--button-primary-hover-bg);
    border-color: var(--button-primary-hover-bg);
    box-shadow: var(--button-primary-hover-shadow);
    transform: translateY(-2px);
}

.btn-secondary {
    background: var(--button-secondary-bg);
    color: var(--button-secondary-text);
    border-color: var(--color-border-base);
    box-shadow: var(--button-secondary-shadow);
}

.btn-secondary:hover {
    background: var(--button-secondary-hover-bg);
    border-color: var(--color-text-secondary);
    transform: translateY(-1px);
}

.btn-icon {
    width: 1.125rem;
    height: 1.125rem;
}

/* Responsive Design - Tablet and Up */
@media (min-width: 640px) {
    .create-task-page {
        padding: var(--page-padding);
        max-width: 640px;
        margin: 0 auto;
    }
    
    .page-header {
        text-align: left;
        margin-bottom: var(--section-spacing);
    }
    
    .page-title {
        font-size: 1.875rem;
    }
    
    .page-subtitle {
        font-size: 1rem;
    }
    
    .form-container {
        padding: var(--section-spacing);
    }
    
    .form-actions {
        flex-direction: row;
        justify-content: flex-end;
        gap: var(--card-spacing);
    }
}

/* Desktop Optimizations */
@media (min-width: 768px) {
    .create-task-page {
        max-width: 800px;
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: var(--grid-gap);
    }
    
    .form-group-full {
        grid-column: 1 / -1;
    }
    
    .staff-assignment-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .btn {
        padding: var(--btn-padding);
        font-size: 0.875rem;
        min-height: auto;
    }
}

/* Large Desktop */
@media (min-width: 1024px) {
    .form-grid {
        gap: 2rem;
    }
    
    .staff-assignment-grid {
        grid-template-columns: repeat(3, 1fr);
    }
    
    .form-container {
        padding: 2.5rem;
    }
}

/* Touch Improvements */
@media (hover: none) and (pointer: coarse) {
    .form-input, .form-textarea, .form-select {
        padding: 1.125rem;
        font-size: 1.0625rem; /* Prevent zoom on iOS */
    }
    
    .btn {
        min-height: 52px;
        padding: 1.125rem 1.75rem;
    }
}

/* Dark Mode Enhancements */
@media (prefers-color-scheme: dark) {
    .form-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%9ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    }
}
</style>
@endpush
