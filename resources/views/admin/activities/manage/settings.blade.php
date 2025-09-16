@extends('layouts.admin')

@section('title', __('activities.manage.settings_title'))

@section('content')
<div class="activities-settings-page" x-data="settingsData()">
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
                    <span class="breadcrumb-current">{{ __('activities.manage.settings_title') }}</span>
                </div>
                <h1 class="page-title">{{ __('activities.manage.settings_title') }}</h1>
                <p class="page-description">{{ __('activities.manage.settings_description') }}</p>
            </div>
            <div class="page-header-right">
                <a href="{{ route('admin.activities.manage.index') }}" class="btn btn-secondary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('activities.common.back_to_activities') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Settings Tabs -->
    <div class="settings-tabs">
        <button class="tab-button" 
                :class="{ 'active': activeTab === 'categories' }"
                @click="activeTab = 'categories'">
            <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            {{ __('activities.manage.categories') }}
            <span class="tab-count">{{ count($categories) }}</span>
        </button>
        
        <button class="tab-button" 
                :class="{ 'active': activeTab === 'departments' }"
                @click="activeTab = 'departments'">
            <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            {{ __('activities.manage.departments') }}
            <span class="tab-count">{{ count($departments) }}</span>
        </button>
    </div>

    <!-- Categories Tab -->
    <div x-show="activeTab === 'categories'" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="tab-content">
        
        <!-- Categories Header -->
        <div class="section-header">
            <div class="section-header-left">
                <h2 class="section-title">{{ __('activities.manage.activity_categories') }}</h2>
                <p class="section-description">{{ __('activities.manage.categories_description') }}</p>
            </div>
            <div class="section-header-right">
                <button class="btn btn-primary" @click="openCategoryModal()">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('activities.manage.add_category') }}
                </button>
            </div>
        </div>

        <!-- Categories Grid -->
        <div class="settings-grid">
            @foreach($categories as $category)
            <div class="settings-card">
                <div class="card-header">
                    <div class="card-info">
                        <h3 class="card-title">{{ $category['name'] }}</h3>
                        <p class="card-description">{{ $category['description'] }}</p>
                    </div>
                    <div class="card-status">
                        <div class="status-indicator status-{{ $category['is_active'] ? 'active' : 'inactive' }}">
                            {{ $category['is_active'] ? __('activities.common.active') : __('activities.common.inactive') }}
                        </div>
                    </div>
                </div>
                
                <div class="card-stats">
                    <div class="stat-item">
                        <div class="stat-value">{{ $category['activities_count'] }}</div>
                        <div class="stat-label">{{ __('activities.manage.activities') }}</div>
                    </div>
                </div>
                
                <div class="card-actions">
                    <button class="btn btn-sm btn-secondary" @click="editCategory({{ json_encode($category) }})">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        {{ __('activities.common.edit') }}
                    </button>
                    
                    <button class="btn btn-sm btn-danger" 
                            @click="deleteCategory({{ $category['id'] }})"
                            :disabled="{{ $category['activities_count'] > 0 ? 'true' : 'false' }}">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        {{ __('activities.common.delete') }}
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Departments Tab -->
    <div x-show="activeTab === 'departments'" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="tab-content">
        
        <!-- Departments Header -->
        <div class="section-header">
            <div class="section-header-left">
                <h2 class="section-title">{{ __('activities.manage.activity_departments') }}</h2>
                <p class="section-description">{{ __('activities.manage.departments_description') }}</p>
            </div>
            <div class="section-header-right">
                <button class="btn btn-primary" @click="openDepartmentModal()">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('activities.manage.add_department') }}
                </button>
            </div>
        </div>

        <!-- Departments Grid -->
        <div class="settings-grid">
            @foreach($departments as $department)
            <div class="settings-card">
                <div class="card-header">
                    <div class="card-info">
                        <h3 class="card-title">{{ $department['name'] }}</h3>
                        <p class="card-description">{{ $department['description'] }}</p>
                    </div>
                    <div class="card-status">
                        <div class="status-indicator status-{{ $department['is_active'] ? 'active' : 'inactive' }}">
                            {{ $department['is_active'] ? __('activities.common.active') : __('activities.common.inactive') }}
                        </div>
                    </div>
                </div>
                
                <div class="card-stats">
                    <div class="stat-item">
                        <div class="stat-value">{{ $department['activities_count'] }}</div>
                        <div class="stat-label">{{ __('activities.manage.activities') }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ $department['staff_count'] }}</div>
                        <div class="stat-label">{{ __('activities.manage.staff_members') }}</div>
                    </div>
                </div>
                
                <div class="card-actions">
                    <button class="btn btn-sm btn-secondary" @click="editDepartment({{ json_encode($department) }})">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        {{ __('activities.common.edit') }}
                    </button>
                    
                    <button class="btn btn-sm btn-danger" 
                            @click="deleteDepartment({{ $department['id'] }})"
                            :disabled="{{ $department['activities_count'] > 0 ? 'true' : 'false' }}">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        {{ __('activities.common.delete') }}
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Category Modal -->
    <div x-show="showCategoryModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="modal-overlay" 
         @click="closeCategoryModal()"
         style="display: none;">
        <div class="modal-content" @click.stop>
            <div class="modal-header">
                <h3 class="modal-title" x-text="categoryForm.id ? '{{ __('activities.manage.edit_category') }}' : '{{ __('activities.manage.add_category') }}'"></h3>
                <button class="modal-close" @click="closeCategoryModal()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form @submit.prevent="saveCategoryForm()" class="modal-form">
                <div class="form-group">
                    <label for="category_name" class="form-label required">
                        {{ __('activities.manage.category_name') }}
                    </label>
                    <input type="text" 
                           id="category_name" 
                           x-model="categoryForm.name"
                           class="form-input"
                           :class="{ 'error': categoryErrors.name }"
                           placeholder="{{ __('activities.manage.category_name_placeholder') }}"
                           required>
                    <div x-show="categoryErrors.name" class="form-error" x-text="categoryErrors.name"></div>
                </div>
                
                <div class="form-group">
                    <label for="category_description" class="form-label">
                        {{ __('activities.manage.description') }}
                    </label>
                    <textarea id="category_description" 
                              x-model="categoryForm.description"
                              class="form-textarea"
                              rows="3"
                              placeholder="{{ __('activities.manage.category_description_placeholder') }}"></textarea>
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" @click="closeCategoryModal()">
                        {{ __('activities.common.cancel') }}
                    </button>
                    <button type="submit" 
                            class="btn btn-primary"
                            :disabled="isSubmitting"
                            :class="{ 'loading': isSubmitting }">
                        <span x-show="!isSubmitting" x-text="categoryForm.id ? '{{ __('activities.common.update') }}' : '{{ __('activities.common.create') }}'"></span>
                        <span x-show="isSubmitting">{{ __('activities.manage.saving') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Department Modal -->
    <div x-show="showDepartmentModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="modal-overlay" 
         @click="closeDepartmentModal()"
         style="display: none;">
        <div class="modal-content" @click.stop>
            <div class="modal-header">
                <h3 class="modal-title" x-text="departmentForm.id ? '{{ __('activities.manage.edit_department') }}' : '{{ __('activities.manage.add_department') }}'"></h3>
                <button class="modal-close" @click="closeDepartmentModal()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form @submit.prevent="saveDepartmentForm()" class="modal-form">
                <div class="form-group">
                    <label for="department_name" class="form-label required">
                        {{ __('activities.manage.department_name') }}
                    </label>
                    <input type="text" 
                           id="department_name" 
                           x-model="departmentForm.name"
                           class="form-input"
                           :class="{ 'error': departmentErrors.name }"
                           placeholder="{{ __('activities.manage.department_name_placeholder') }}"
                           required>
                    <div x-show="departmentErrors.name" class="form-error" x-text="departmentErrors.name"></div>
                </div>
                
                <div class="form-group">
                    <label for="department_description" class="form-label">
                        {{ __('activities.manage.description') }}
                    </label>
                    <textarea id="department_description" 
                              x-model="departmentForm.description"
                              class="form-textarea"
                              rows="3"
                              placeholder="{{ __('activities.manage.department_description_placeholder') }}"></textarea>
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" @click="closeDepartmentModal()">
                        {{ __('activities.common.cancel') }}
                    </button>
                    <button type="submit" 
                            class="btn btn-primary"
                            :disabled="isSubmitting"
                            :class="{ 'loading': isSubmitting }">
                        <span x-show="!isSubmitting" x-text="departmentForm.id ? '{{ __('activities.common.update') }}' : '{{ __('activities.common.create') }}'"></span>
                        <span x-show="isSubmitting">{{ __('activities.manage.saving') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
@vite('resources/css/admin/activities/manage.css')
@endpush

@push('scripts')
@vite('resources/js/admin/activities/manage.js')
<script>
function settingsData() {
    return {
        activeTab: 'categories',
        showCategoryModal: false,
        showDepartmentModal: false,
        isSubmitting: false,
        
        categoryForm: {
            id: null,
            name: '',
            description: ''
        },
        
        departmentForm: {
            id: null,
            name: '',
            description: ''
        },
        
        categoryErrors: {},
        departmentErrors: {},
        
        openCategoryModal() {
            this.categoryForm = { id: null, name: '', description: '' };
            this.categoryErrors = {};
            this.showCategoryModal = true;
        },
        
        closeCategoryModal() {
            this.showCategoryModal = false;
            this.categoryForm = { id: null, name: '', description: '' };
            this.categoryErrors = {};
        },
        
        editCategory(category) {
            this.categoryForm = {
                id: category.id,
                name: category.name,
                description: category.description
            };
            this.categoryErrors = {};
            this.showCategoryModal = true;
        },
        
        saveCategoryForm() {
            this.categoryErrors = {};
            this.isSubmitting = true;
            
            if (!this.categoryForm.name.trim()) {
                this.categoryErrors.name = 'Category name is required';
                this.isSubmitting = false;
                return;
            }
            
            const url = this.categoryForm.id 
                ? `/admin/activities/manage/settings/categories/${this.categoryForm.id}`
                : '/admin/activities/manage/settings/categories';
            const method = this.categoryForm.id ? 'PUT' : 'POST';
            
            setTimeout(() => {
                this.showNotification(
                    this.categoryForm.id 
                        ? 'Category updated successfully!' 
                        : 'Category created successfully!', 
                    'success'
                );
                this.closeCategoryModal();
                this.isSubmitting = false;
                // In real implementation, refresh the page or update the list
                setTimeout(() => window.location.reload(), 1000);
            }, 1000);
        },
        
        deleteCategory(categoryId) {
            if (!confirm('Are you sure you want to delete this category?')) {
                return;
            }
            
            this.showNotification('Category deleted successfully!', 'success');
            // In real implementation, make API call and refresh list
        },
        
        openDepartmentModal() {
            this.departmentForm = { id: null, name: '', description: '' };
            this.departmentErrors = {};
            this.showDepartmentModal = true;
        },
        
        closeDepartmentModal() {
            this.showDepartmentModal = false;
            this.departmentForm = { id: null, name: '', description: '' };
            this.departmentErrors = {};
        },
        
        editDepartment(department) {
            this.departmentForm = {
                id: department.id,
                name: department.name,
                description: department.description
            };
            this.departmentErrors = {};
            this.showDepartmentModal = true;
        },
        
        saveDepartmentForm() {
            this.departmentErrors = {};
            this.isSubmitting = true;
            
            if (!this.departmentForm.name.trim()) {
                this.departmentErrors.name = 'Department name is required';
                this.isSubmitting = false;
                return;
            }
            
            const url = this.departmentForm.id 
                ? `/admin/activities/manage/settings/departments/${this.departmentForm.id}`
                : '/admin/activities/manage/settings/departments';
            const method = this.departmentForm.id ? 'PUT' : 'POST';
            
            setTimeout(() => {
                this.showNotification(
                    this.departmentForm.id 
                        ? 'Department updated successfully!' 
                        : 'Department created successfully!', 
                    'success'
                );
                this.closeDepartmentModal();
                this.isSubmitting = false;
                // In real implementation, refresh the page or update the list
                setTimeout(() => window.location.reload(), 1000);
            }, 1000);
        },
        
        deleteDepartment(departmentId) {
            if (!confirm('Are you sure you want to delete this department?')) {
                return;
            }
            
            this.showNotification('Department deleted successfully!', 'success');
            // In real implementation, make API call and refresh list
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
