@extends('layouts.admin')

@section('title', __('todos.templates.create_template'))

@section('content')
<div class="template-create-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-header-left">
                <div class="breadcrumb">
                    <a href="{{ route('admin.todos.templates.index') }}" class="breadcrumb-link">
                        {{ __('todos.templates.title') }}
                    </a>
                    <span class="breadcrumb-separator">/</span>
                    <span class="breadcrumb-current">{{ __('todos.templates.create_template') }}</span>
                </div>
                <h1 class="page-title">{{ __('todos.templates.create_template') }}</h1>
                <p class="page-description">{{ __('todos.templates.create_description') }}</p>
            </div>
        </div>
    </div>

    <!-- Create Form -->
    <div class="template-form-container" x-data="initializeTemplateForm()">
        <form class="template-form" @submit.prevent="submitForm()">
            <!-- Basic Information -->
            <div class="form-section">
                <h3 class="section-title">{{ __('todos.templates.basic_information') }}</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">{{ __('todos.templates.template_name') }}</label>
                        <input type="text" 
                               class="form-input" 
                               x-model="form.name"
                               :placeholder="__('todos.templates.template_name_placeholder')"
                               required>
                        <div x-show="errors.name" class="form-error" x-text="errors.name"></div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">{{ __('todos.templates.category') }}</label>
                        <select class="form-select" x-model="form.category" required>
                            @foreach($categories as $key => $value)
                                <option value="{{ $key }}">{{ __('todos.templates.' . $key) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">{{ __('todos.templates.description') }}</label>
                    <textarea class="form-textarea" 
                              x-model="form.description"
                              :placeholder="__('todos.templates.description_placeholder')"
                              rows="3"></textarea>
                </div>
            </div>

            <!-- Assignment & Scheduling -->
            <div class="form-section">
                <h3 class="section-title">{{ __('todos.templates.assignment_scheduling') }}</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">{{ __('todos.templates.assigned_role') }}</label>
                        <select class="form-select" x-model="form.assigned_role" required>
                            @foreach($roles as $key => $value)
                                <option value="{{ $key }}">{{ __('todos.templates.' . $key) }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">{{ __('todos.templates.recurring_type') }}</label>
                        <select class="form-select" x-model="form.recurring_type" required>
                            @foreach($recurringTypes as $key => $value)
                                <option value="{{ $key }}">{{ __('todos.templates.' . $key) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">{{ __('todos.templates.estimated_duration') }} ({{ __('todos.templates.minutes') }})</label>
                        <input type="number" 
                               class="form-input" 
                               x-model="form.estimated_duration"
                               min="1" 
                               max="480"
                               :placeholder="__('todos.templates.duration_placeholder')"
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">{{ __('todos.templates.priority') }}</label>
                        <select class="form-select" x-model="form.priority" required>
                            <option value="normal">{{ __('todos.templates.priority_normal') }}</option>
                            <option value="medium">{{ __('todos.templates.priority_medium') }}</option>
                            <option value="high">{{ __('todos.templates.priority_high') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="form-section">
                <h3 class="section-title">{{ __('todos.templates.instructions') }}</h3>
                <p class="section-description">{{ __('todos.templates.instructions_description') }}</p>
                
                <div class="instructions-container">
                    <template x-for="(instruction, index) in form.instructions" :key="index">
                        <div class="instruction-item">
                            <div class="instruction-number" x-text="index + 1"></div>
                            <input type="text" 
                                   class="form-input instruction-input" 
                                   x-model="form.instructions[index]"
                                   :placeholder="__('todos.templates.instruction_placeholder', {step: index + 1})">
                            <button type="button" 
                                    class="btn btn-sm btn-danger instruction-remove"
                                    @click="removeInstruction(index)"
                                    x-show="form.instructions.length > 1">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </template>
                    
                    <button type="button" 
                            class="btn btn-secondary add-instruction-btn"
                            @click="addInstruction()">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        {{ __('todos.templates.add_instruction') }}
                    </button>
                </div>
            </div>

            <!-- Tags -->
            <div class="form-section">
                <h3 class="section-title">{{ __('todos.templates.tags') }}</h3>
                <p class="section-description">{{ __('todos.templates.tags_description') }}</p>
                
                <div class="tags-container">
                    <template x-for="(tag, index) in form.tags" :key="index">
                        <div class="tag-item">
                            <input type="text" 
                                   class="form-input tag-input" 
                                   x-model="form.tags[index]"
                                   :placeholder="__('todos.templates.tag_placeholder')">
                            <button type="button" 
                                    class="btn btn-sm btn-danger tag-remove"
                                    @click="removeTag(index)"
                                    x-show="form.tags.length > 1">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </template>
                    
                    <button type="button" 
                            class="btn btn-secondary add-tag-btn"
                            @click="addTag()">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        {{ __('todos.templates.add_tag') }}
                    </button>
                </div>
            </div>

            <!-- Template Status -->
            <div class="form-section">
                <h3 class="section-title">{{ __('todos.templates.template_status') }}</h3>
                
                <div class="form-group">
                    <label class="form-checkbox-label">
                        <input type="checkbox" 
                               class="form-checkbox" 
                               x-model="form.is_active">
                        <span class="checkbox-text">{{ __('todos.templates.activate_immediately') }}</span>
                        <span class="checkbox-description">{{ __('todos.templates.activate_description') }}</span>
                    </label>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="button" 
                        class="btn btn-secondary"
                        @click="cancelForm()"
                        :disabled="isSubmitting">
                    {{ __('todos.common.cancel') }}
                </button>
                
                <button type="submit" 
                        class="btn btn-primary"
                        :disabled="isSubmitting">
                    <span x-show="!isSubmitting">{{ __('todos.templates.save_template') }}</span>
                    <span x-show="isSubmitting" class="loading-text">
                        <svg class="loading-spinner" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        {{ __('todos.templates.saving') }}
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('styles')
@vite('resources/css/admin/todos/templates.css')
@endpush

@push('scripts')
@vite('resources/js/admin/todos/templates.js')
@endpush
