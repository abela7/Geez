@extends('layouts.admin')

@section('title', __('admin.departments.edit_title') . ' - ' . config('app.name'))
@section('page_title', __('admin.departments.edit_title'))

@section('content')
<div class="departments-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <div class="breadcrumb">
                    <a href="{{ route('admin.settings.departments.index') }}" class="breadcrumb-link">{{ __('admin.departments.title') }}</a>
                    <span class="breadcrumb-separator">/</span>
                    <span class="breadcrumb-current">{{ __('admin.departments.edit') }} {{ $department->name }}</span>
                </div>
                <h1 class="page-title">{{ __('admin.departments.edit_title') }}</h1>
                <p class="page-subtitle">{{ __('admin.departments.edit_description') }}</p>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="departments-form-section">
        <form action="{{ route('admin.settings.departments.update', $department) }}" method="POST" class="form-layout">
            @csrf
            @method('PUT')
            
            <div class="form-section">
                <div class="form-section-header">
                    <h2 class="form-section-title">{{ __('admin.departments.basic_information') }}</h2>
                    <p class="form-section-description">{{ __('admin.departments.basic_info_description') }}</p>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name" class="form-label required">{{ __('admin.departments.name') }}</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               class="form-input @error('name') form-input-error @enderror" 
                               value="{{ old('name', $department->name) }}" 
                               placeholder="{{ __('admin.departments.name_placeholder') }}" 
                               required>
                        @error('name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">{{ __('admin.departments.name_help') }}</div>
                    </div>

                    <div class="form-group">
                        <label for="color" class="form-label required">{{ __('admin.departments.color') }}</label>
                        <div class="color-input-container">
                            <input type="color" 
                                   id="color" 
                                   name="color" 
                                   class="form-color-input @error('color') form-input-error @enderror" 
                                   value="{{ old('color', $department->color) }}" 
                                   required>
                            <input type="text" 
                                   class="form-input color-text-input" 
                                   value="{{ old('color', $department->color) }}" 
                                   readonly>
                        </div>
                        @error('color')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">{{ __('admin.departments.color_help') }}</div>
                    </div>

                    <div class="form-group form-group-full">
                        <label for="description" class="form-label">{{ __('admin.departments.description') }}</label>
                        <textarea id="description" 
                                  name="description" 
                                  class="form-textarea @error('description') form-input-error @enderror" 
                                  rows="3"
                                  placeholder="{{ __('admin.departments.description_placeholder') }}">{{ old('description', $department->description) }}</textarea>
                        @error('description')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">{{ __('admin.departments.description_help') }}</div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-header">
                    <h2 class="form-section-title">{{ __('admin.departments.settings') }}</h2>
                    <p class="form-section-description">{{ __('admin.departments.settings_description') }}</p>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="sort_order" class="form-label">{{ __('admin.departments.sort_order') }}</label>
                        <input type="number" 
                               id="sort_order" 
                               name="sort_order" 
                               class="form-input @error('sort_order') form-input-error @enderror" 
                               value="{{ old('sort_order', $department->sort_order) }}" 
                               min="0"
                               placeholder="0">
                        @error('sort_order')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">{{ __('admin.departments.sort_order_help') }}</div>
                    </div>

                    <div class="form-group">
                        <div class="form-checkbox-group">
                            <label class="form-checkbox">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', $department->is_active) ? 'checked' : '' }}>
                                <span class="form-checkbox-indicator"></span>
                                <span class="form-checkbox-label">{{ __('admin.departments.is_active') }}</span>
                            </label>
                            <div class="form-help">{{ __('admin.departments.is_active_help') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.settings.departments.index') }}" class="btn btn-secondary">
                    {{ __('admin.common.cancel') }}
                </a>
                <button type="submit" class="btn btn-primary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ __('admin.departments.update_department') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const colorInput = document.getElementById('color');
    const colorTextInput = document.querySelector('.color-text-input');
    
    colorInput.addEventListener('input', function() {
        colorTextInput.value = this.value.toUpperCase();
    });
    
    colorTextInput.addEventListener('input', function() {
        const value = this.value;
        if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
            colorInput.value = value;
        }
    });
});
</script>
@endsection

@push('styles')
<style>
.departments-page {
    padding: var(--page-padding);
    max-width: var(--page-max-width);
    margin: 0 auto;
    background: var(--color-bg-primary);
}

.page-header {
    margin-bottom: var(--section-spacing);
}

.page-header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: var(--card-spacing);
}

.page-title {
    font-size: 1.875rem;
    font-weight: 700;
    color: var(--color-text-primary);
    margin: 0;
}

.page-subtitle {
    color: var(--color-text-secondary);
    margin: 0.25em 0 0 0;
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.breadcrumb-link {
    color: var(--color-text-secondary);
    text-decoration: none;
    transition: var(--transition-all);
}

.breadcrumb-link:hover {
    color: var(--color-text-primary);
}

.breadcrumb-separator {
    color: var(--color-text-tertiary);
}

.breadcrumb-current {
    color: var(--color-text-primary);
    font-weight: 500;
}

.departments-form-section {
    background: var(--color-surface-card);
    border: 1px solid var(--color-surface-card-border);
    border-radius: 0.75rem;
    padding: 2rem;
    box-shadow: var(--color-surface-card-shadow);
}

.form-section-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--color-text-primary);
    margin: 0 0 0.5rem 0;
}

.form-section-description {
    color: var(--color-text-secondary);
    margin: 0;
}

.form-grid {
    display: grid;
    gap: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-label {
    font-weight: 500;
    color: var(--color-text-primary);
    font-size: 0.875rem;
}

.form-label.required::after {
    content: ' *';
    color: var(--color-error-600);
}

.form-input {
    padding: 0.75rem;
    border: 1px solid var(--color-surface-border);
    border-radius: 0.5rem;
    background: var(--color-bg-primary);
    color: var(--color-text-primary);
    font-size: 0.875rem;
    transition: var(--transition-all);
}

.form-input:focus {
    outline: none;
    border-color: var(--color-primary-600);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-input-error {
    border-color: var(--color-error-600);
}

.form-error {
    color: var(--color-error-600);
    font-size: 0.75rem;
}

.form-help {
    color: var(--color-text-secondary);
    font-size: 0.75rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    padding-top: 2rem;
    border-top: 1px solid var(--color-surface-border);
    justify-content: flex-end;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: var(--transition-all);
}

.btn-primary {
    background: var(--color-primary-600);
    color: white;
}

.btn-primary:hover {
    background: var(--color-primary-700);
}

.btn-secondary {
    background: var(--color-bg-secondary);
    color: var(--color-text-primary);
    border: 1px solid var(--color-surface-border);
}

.btn-secondary:hover {
    background: var(--color-bg-tertiary);
}

.btn-icon {
    width: 1rem;
    height: 1rem;
}

.color-input-container {
    display: flex;
    gap: 0.75rem;
    align-items: center;
}

.form-color-input {
    width: 60px;
    height: 40px;
    padding: 4px;
    border: 1px solid var(--color-surface-border);
    border-radius: 0.5rem;
    cursor: pointer;
    background: var(--color-bg-primary);
}

.color-text-input {
    flex: 1;
    font-family: monospace;
    text-transform: uppercase;
}

.form-checkbox-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-checkbox {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
}

.form-checkbox input[type="checkbox"] {
    display: none;
}

.form-checkbox-indicator {
    width: 1.25rem;
    height: 1.25rem;
    border: 2px solid var(--color-surface-border);
    border-radius: 0.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition-all);
    background: var(--color-bg-primary);
}

.form-checkbox input[type="checkbox"]:checked + .form-checkbox-indicator {
    background: var(--color-primary-600);
    border-color: var(--color-primary-600);
}

.form-checkbox input[type="checkbox"]:checked + .form-checkbox-indicator::after {
    content: '✓';
    color: white;
    font-size: 0.75rem;
    font-weight: 700;
}

.form-checkbox-label {
    font-weight: 500;
    color: var(--color-text-primary);
}
</style>
@endpush
