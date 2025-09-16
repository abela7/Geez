@extends('layouts.admin')

@section('title', __('activities.manage.create_activity'))

@section('content')
<div class="activities-create-page" x-data="createActivityData()">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-header-left">
                <div class="breadcrumb">
                    <a href="{{ route('admin.activities.manage.index') }}" class="breadcrumb-link">
                        {{ __('activities.manage.title') }}
                    </a>
                    <svg class="breadcrumb-separator" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="breadcrumb-current">{{ __('activities.manage.create_activity') }}</span>
                </div>
                <h1 class="page-title">{{ __('activities.manage.create_activity') }}</h1>
                <p class="page-description">{{ __('activities.manage.create_activity_description') }}</p>
            </div>
        </div>
    </div>

    <!-- Create Form -->
    <div class="create-form-container">
        <form @submit.prevent="submitForm()" class="create-form">
            <!-- Basic Information Section -->
            <div class="form-section">
                <div class="section-header">
                    <h2 class="section-title">{{ __('activities.manage.basic_information') }}</h2>
                    <p class="section-description">{{ __('activities.manage.basic_information_description') }}</p>
                </div>
                
                <div class="form-grid">
                    <!-- Activity Name -->
                    <div class="form-group col-span-2">
                        <label for="activity_name" class="form-label required">
                            {{ __('activities.manage.activity_name') }}
                        </label>
                        <input type="text" 
                               id="activity_name" 
                               name="activity_name"
                               x-model="form.name"
                               class="form-input"
                               :class="{ 'error': errors.name }"
                               placeholder="{{ __('activities.manage.activity_name_placeholder') }}"
                               required>
                        <div x-show="errors.name" class="form-error" x-text="errors.name"></div>
                    </div>
                    
                    <!-- Category -->
                    <div class="form-group">
                        <label for="category" class="form-label required">
                            {{ __('activities.manage.category') }}
                        </label>
                        <select id="category" 
                                name="category"
                                x-model="form.category"
                                class="form-select"
                                :class="{ 'error': errors.category }"
                                required>
                            <option value="">{{ __('activities.manage.select_category') }}</option>
                            <option value="food_preparation">{{ __('activities.manage.category_food_prep') }}</option>
                            <option value="equipment_maintenance">{{ __('activities.manage.category_equipment_maintenance') }}</option>
                            <option value="service_preparation">{{ __('activities.manage.category_service_preparation') }}</option>
                            <option value="customer_service">{{ __('activities.manage.category_customer_service') }}</option>
                            <option value="cleaning">{{ __('activities.manage.category_cleaning') }}</option>
                            <option value="inventory">{{ __('activities.manage.category_inventory') }}</option>
                            <option value="admin">{{ __('activities.manage.category_admin') }}</option>
                        </select>
                        <div x-show="errors.category" class="form-error" x-text="errors.category"></div>
                    </div>
                    
                    <!-- Department -->
                    <div class="form-group">
                        <label for="department" class="form-label required">
                            {{ __('activities.manage.department') }}
                        </label>
                        <select id="department" 
                                name="department"
                                x-model="form.department"
                                class="form-select"
                                :class="{ 'error': errors.department }"
                                required>
                            <option value="">{{ __('activities.manage.select_department') }}</option>
                            <option value="kitchen">{{ __('activities.manage.department_kitchen') }}</option>
                            <option value="front_of_house">{{ __('activities.manage.department_front_of_house') }}</option>
                            <option value="bar">{{ __('activities.manage.department_bar') }}</option>
                            <option value="management">{{ __('activities.manage.department_management') }}</option>
                        </select>
                        <div x-show="errors.department" class="form-error" x-text="errors.department"></div>
                    </div>
                </div>
                
                <!-- Description -->
                <div class="form-group">
                    <label for="description" class="form-label required">
                        {{ __('activities.manage.description') }}
                    </label>
                    <textarea id="description" 
                              name="description"
                              x-model="form.description"
                              class="form-textarea"
                              :class="{ 'error': errors.description }"
                              rows="3"
                              placeholder="{{ __('activities.manage.description_placeholder') }}"
                              required></textarea>
                    <div x-show="errors.description" class="form-error" x-text="errors.description"></div>
                </div>
            </div>

            <!-- Time & Difficulty Section -->
            <div class="form-section">
                <div class="section-header">
                    <h2 class="section-title">{{ __('activities.manage.time_and_difficulty') }}</h2>
                    <p class="section-description">{{ __('activities.manage.time_and_difficulty_description') }}</p>
                </div>
                
                <div class="form-grid">
                    <!-- Estimated Duration -->
                    <div class="form-group">
                        <label for="estimated_duration" class="form-label required">
                            {{ __('activities.manage.estimated_duration') }}
                        </label>
                        <div class="input-with-suffix">
                            <input type="number" 
                                   id="estimated_duration" 
                                   name="estimated_duration"
                                   x-model="form.estimated_duration"
                                   class="form-input"
                                   :class="{ 'error': errors.estimated_duration }"
                                   min="1"
                                   max="480"
                                   placeholder="120"
                                   required>
                            <span class="input-suffix">{{ __('activities.common.minutes') }}</span>
                        </div>
                        <div class="form-help">{{ __('activities.manage.estimated_duration_help') }}</div>
                        <div x-show="errors.estimated_duration" class="form-error" x-text="errors.estimated_duration"></div>
                    </div>
                    
                    <!-- Difficulty -->
                    <div class="form-group">
                        <label for="difficulty" class="form-label required">
                            {{ __('activities.manage.difficulty') }}
                        </label>
                        <select id="difficulty" 
                                name="difficulty"
                                x-model="form.difficulty"
                                class="form-select"
                                :class="{ 'error': errors.difficulty }"
                                required>
                            <option value="">{{ __('activities.manage.select_difficulty') }}</option>
                            <option value="easy">{{ __('activities.manage.difficulty_easy') }}</option>
                            <option value="medium">{{ __('activities.manage.difficulty_medium') }}</option>
                            <option value="hard">{{ __('activities.manage.difficulty_hard') }}</option>
                        </select>
                        <div x-show="errors.difficulty" class="form-error" x-text="errors.difficulty"></div>
                    </div>
                </div>
            </div>

            <!-- Equipment & Requirements Section -->
            <div class="form-section">
                <div class="section-header">
                    <h2 class="section-title">{{ __('activities.manage.equipment_and_requirements') }}</h2>
                    <p class="section-description">{{ __('activities.manage.equipment_and_requirements_description') }}</p>
                </div>
                
                <!-- Requires Equipment Toggle -->
                <div class="form-group">
                    <label class="toggle-label">
                        <input type="checkbox" 
                               x-model="form.requires_equipment"
                               class="toggle-input">
                        <span class="toggle-slider"></span>
                        <span class="toggle-text">{{ __('activities.manage.requires_equipment') }}</span>
                    </label>
                </div>
                
                <!-- Equipment List (shown when requires_equipment is true) -->
                <div x-show="form.requires_equipment" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 max-h-0"
                     x-transition:enter-end="opacity-100 max-h-40"
                     class="form-group">
                    <label for="equipment_list" class="form-label">
                        {{ __('activities.manage.equipment_required') }}
                    </label>
                    <input type="text" 
                           id="equipment_list" 
                           name="equipment_list"
                           x-model="form.equipment_list"
                           class="form-input"
                           placeholder="{{ __('activities.manage.equipment_required_placeholder') }}">
                    <div class="form-help">{{ __('activities.manage.equipment_list_help') }}</div>
                </div>
            </div>

            <!-- Instructions Section -->
            <div class="form-section">
                <div class="section-header">
                    <h2 class="section-title">{{ __('activities.manage.instructions') }}</h2>
                    <p class="section-description">{{ __('activities.manage.instructions_description') }}</p>
                </div>
                
                <div class="form-group">
                    <label for="instructions" class="form-label">
                        {{ __('activities.manage.instructions') }}
                    </label>
                    <textarea id="instructions" 
                              name="instructions"
                              x-model="form.instructions"
                              class="form-textarea"
                              rows="6"
                              placeholder="{{ __('activities.manage.instructions_placeholder') }}"></textarea>
                    <div class="form-help">{{ __('activities.manage.instructions_help') }}</div>
                </div>
            </div>

            <!-- Status Section -->
            <div class="form-section">
                <div class="section-header">
                    <h2 class="section-title">{{ __('activities.manage.status_settings') }}</h2>
                    <p class="section-description">{{ __('activities.manage.status_settings_description') }}</p>
                </div>
                
                <div class="form-group">
                    <label class="toggle-label">
                        <input type="checkbox" 
                               x-model="form.is_active"
                               class="toggle-input"
                               checked>
                        <span class="toggle-slider"></span>
                        <span class="toggle-text">{{ __('activities.manage.activate_activity') }}</span>
                    </label>
                    <div class="form-help">{{ __('activities.manage.activate_activity_description') }}</div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('admin.activities.manage.index') }}" class="btn btn-secondary">
                    {{ __('activities.common.cancel') }}
                </a>
                <button type="submit" 
                        class="btn btn-primary"
                        :disabled="isSubmitting"
                        :class="{ 'loading': isSubmitting }">
                    <span x-show="!isSubmitting">{{ __('activities.manage.save_activity') }}</span>
                    <span x-show="isSubmitting">{{ __('activities.manage.saving') }}</span>
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('styles')
@vite('resources/css/admin/activities/manage.css')
@endpush

@push('scripts')
@vite('resources/js/admin/activities/manage.js')
<script>
function createActivityData() {
    return {
        form: {
            name: '',
            description: '',
            category: '',
            department: '',
            estimated_duration: '',
            difficulty: '',
            requires_equipment: false,
            equipment_list: '',
            instructions: '',
            is_active: true
        },
        
        errors: {},
        isSubmitting: false,
        
        submitForm() {
            this.errors = {};
            this.isSubmitting = true;
            
            // Basic validation
            if (!this.form.name.trim()) {
                this.errors.name = 'Activity name is required';
            }
            
            if (!this.form.description.trim()) {
                this.errors.description = 'Description is required';
            }
            
            if (!this.form.category) {
                this.errors.category = 'Category is required';
            }
            
            if (!this.form.department) {
                this.errors.department = 'Department is required';
            }
            
            if (!this.form.estimated_duration || this.form.estimated_duration < 1) {
                this.errors.estimated_duration = 'Valid estimated duration is required';
            }
            
            if (!this.form.difficulty) {
                this.errors.difficulty = 'Difficulty level is required';
            }
            
            // If there are errors, stop submission
            if (Object.keys(this.errors).length > 0) {
                this.isSubmitting = false;
                return;
            }
            
            // Simulate form submission
            setTimeout(() => {
                // In real implementation, this would submit to the server
                console.log('Form submitted:', this.form);
                
                // Show success message
                this.showNotification('Activity created successfully!', 'success');
                
                // Redirect to activities list
                setTimeout(() => {
                    window.location.href = '{{ route("admin.activities.manage.index") }}';
                }, 1500);
                
                this.isSubmitting = false;
            }, 2000);
        },
        
        showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.innerHTML = `
                <div class="notification-content">
                    <span class="notification-message">${message}</span>
                    <button class="notification-close" onclick="this.parentElement.parentElement.remove()">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            `;
            
            // Add to page
            document.body.appendChild(notification);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }
    };
}
</script>
@endpush
