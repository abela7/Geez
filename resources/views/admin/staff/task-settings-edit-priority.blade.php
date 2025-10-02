@extends('layouts.admin')

@section('title', __('Edit Task Priority') . ' - ' . config('app.name'))
@section('page_title', __('Edit Task Priority'))

@section('content')
<div class="settings-edit-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('Edit Task Priority') }}</h1>
                <p class="page-subtitle">{{ __('Update task priority information') }}</p>
            </div>

            <div class="page-actions">
                <a href="{{ route('admin.staff.tasks.settings.index') }}" class="btn btn-outline">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('common.back') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="form-container">
        <!-- Validation Errors -->
        @if($errors->any())
            <div class="alert alert-error">
                <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h4>{{ __('Please fix the following errors:') }}</h4>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success">
                <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.staff.tasks.settings.priorities.update', $taskPriority->slug) }}" class="settings-form">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <!-- Priority Name -->
                <div class="form-group form-group-full">
                    <label for="name" class="form-label">{{ __('Name') }} <span class="required">*</span></label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name', $taskPriority->name) }}"
                           class="form-input @error('name') form-input-error @enderror"
                           placeholder="{{ __('Enter priority name') }}"
                           required>
                    @error('name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="form-group form-group-full">
                    <label for="description" class="form-label">{{ __('Description') }}</label>
                    <textarea id="description"
                              name="description"
                              class="form-textarea @error('description') form-textarea-error @enderror"
                              rows="3"
                              placeholder="{{ __('Enter priority description') }}">{{ old('description', $taskPriority->description) }}</textarea>
                    @error('description')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Priority Level -->
                <div class="form-group">
                    <label for="level" class="form-label">{{ __('Priority Level') }} <span class="required">*</span></label>
                    <input type="number"
                           id="level"
                           name="level"
                           value="{{ old('level', $taskPriority->level) }}"
                           class="form-input @error('level') form-input-error @enderror"
                           placeholder="{{ __('1-10') }}"
                           min="1"
                           max="10"
                           required>
                    <div class="form-help">{{ __('Higher numbers indicate higher priority (1-10)') }}</div>
                    @error('level')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Color -->
                <div class="form-group">
                    <label for="color" class="form-label">{{ __('Color') }} <span class="required">*</span></label>
                    <div class="color-input-wrapper">
                        <input type="color"
                               id="color"
                               name="color"
                               value="{{ old('color', $taskPriority->color) }}"
                               class="form-color-input @error('color') form-input-error @enderror"
                               required>
                        <input type="text"
                               id="color-text"
                               value="{{ old('color', $taskPriority->color) }}"
                               class="form-input"
                               readonly>
                    </div>
                    @error('color')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Icon -->
                <div class="form-group form-group-full">
                    <label for="icon" class="form-label">{{ __('Icon') }}</label>
                    <input type="text"
                           id="icon"
                           name="icon"
                           value="{{ old('icon', $taskPriority->icon) }}"
                           class="form-input @error('icon') form-input-error @enderror"
                           placeholder="{{ __('e.g., exclamation, flag, star') }}">
                    <div class="form-help">{{ __('Optional: Icon name for display') }}</div>
                    @error('icon')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Status -->
                <div class="form-group form-group-full">
                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox"
                                   name="is_active"
                                   value="1"
                                   {{ old('is_active', $taskPriority->is_active) ? 'checked' : '' }}
                                   class="checkbox-input">
                            <span class="checkbox-text">{{ __('Active') }}</span>
                        </label>
                    </div>
                    <div class="form-help">{{ __('Inactive priorities will not be available for new tasks') }}</div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ __('Update Task Priority') }}
                </button>
                <a href="{{ route('admin.staff.tasks.settings.index') }}" class="btn btn-secondary">
                    {{ __('common.cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>

<style>
.settings-edit-page .page-header {
    background-color: var(--color-bg-primary);
    border-bottom: 1px solid var(--color-border-base);
    padding: var(--page-padding);
    margin-bottom: var(--section-spacing);
}

.settings-edit-page .page-header-content {
    max-width: var(--page-max-width);
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.settings-edit-page .page-title {
    color: var(--color-text-primary);
    font-size: 1.875rem;
    font-weight: 700;
    margin: 0;
}

.settings-edit-page .page-subtitle {
    color: var(--color-text-secondary);
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.settings-edit-page .form-container {
    background-color: var(--color-surface-card);
    border: 1px solid var(--color-surface-card-border);
    border-radius: var(--btn-border-radius);
    padding: var(--page-padding);
    box-shadow: var(--color-surface-card-shadow);
    max-width: var(--page-max-width);
    margin: 0 auto;
}

.settings-edit-page .form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.settings-edit-page .form-group {
    margin-bottom: 0;
}

.settings-edit-page .form-group-full {
    grid-column: 1 / -1;
}

.settings-edit-page .form-label {
    display: block;
    color: var(--color-text-secondary);
    font-size: 0.875rem;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.settings-edit-page .form-input,
.settings-edit-page .form-textarea,
.settings-edit-page .form-select {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid var(--form-input-border);
    border-radius: var(--btn-border-radius);
    background-color: var(--form-input-bg);
    color: var(--form-input-text);
    font-size: 1rem;
    transition: var(--transition-all);
}

.settings-edit-page .form-input:focus,
.settings-edit-page .form-textarea:focus {
    border-color: var(--form-input-border-focus);
    box-shadow: var(--form-input-shadow-focus);
    outline: none;
}

.settings-edit-page .form-input-error,
.settings-edit-page .form-textarea-error {
    border-color: var(--color-error);
}

.settings-edit-page .form-error {
    color: var(--color-error);
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

.settings-edit-page .form-help {
    color: var(--color-text-muted);
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

.settings-edit-page .color-input-wrapper {
    display: flex;
    gap: 0.75rem;
    align-items: center;
}

.settings-edit-page .form-color-input {
    width: 4rem;
    height: 3rem;
    padding: 0.25rem;
    border: 1px solid var(--form-input-border);
    border-radius: var(--btn-border-radius);
    cursor: pointer;
}

.settings-edit-page .checkbox-group {
    display: flex;
    align-items: center;
    margin-top: 0.5rem;
}

.settings-edit-page .checkbox-label {
    display: flex;
    align-items: center;
    cursor: pointer;
    color: var(--color-text-primary);
}

.settings-edit-page .checkbox-input {
    margin-right: 0.5rem;
    width: 1.25rem;
    height: 1.25rem;
    accent-color: var(--color-primary);
}

.settings-edit-page .checkbox-text {
    font-size: 1rem;
}

.settings-edit-page .form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--color-border-base);
}

.settings-edit-page .btn {
    padding: var(--btn-padding);
    border-radius: var(--btn-border-radius);
    font-weight: var(--btn-font-weight);
    transition: var(--btn-transition);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    text-decoration: none;
}

.settings-edit-page .btn-primary {
    background-color: var(--button-primary-bg);
    color: var(--button-primary-text);
    border: 1px solid var(--button-primary-border);
    box-shadow: var(--button-primary-shadow);
}

.settings-edit-page .btn-primary:hover {
    background-color: var(--button-primary-hover-bg);
    box-shadow: var(--button-primary-hover-shadow);
    transform: translateY(-1px);
}

.settings-edit-page .btn-secondary {
    background-color: var(--button-secondary-bg);
    color: var(--button-secondary-text);
    border: 1px solid var(--button-secondary-border);
    box-shadow: var(--button-secondary-shadow);
}

.settings-edit-page .btn-secondary:hover {
    background-color: var(--button-secondary-hover-bg);
    transform: translateY(-1px);
}

.settings-edit-page .btn-outline {
    background-color: transparent;
    color: var(--color-text-primary);
    border: 1px solid var(--color-border-base);
}

.settings-edit-page .btn-outline:hover {
    background-color: var(--color-bg-tertiary);
}

.settings-edit-page .btn-icon {
    width: 1.125rem;
    height: 1.125rem;
}

.settings-edit-page .alert {
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.settings-edit-page .alert-success {
    background: var(--alert-success-bg);
    color: var(--alert-success-text);
    border: 1px solid var(--alert-success-border);
}

.settings-edit-page .alert-error {
    background: var(--alert-error-bg);
    color: var(--alert-error-text);
    border: 1px solid var(--alert-error-border);
}

.settings-edit-page .alert-icon {
    width: 1.25rem;
    height: 1.25rem;
    flex-shrink: 0;
    margin-top: 0.125rem;
}

.settings-edit-page .alert h4 {
    margin: 0 0 0.5rem 0;
    font-weight: 600;
}

.settings-edit-page .alert ul {
    margin: 0;
    padding-left: 1.5rem;
}

.settings-edit-page .required {
    color: var(--color-error);
    margin-left: 0.25rem;
}

/* Color picker synchronization */
.settings-edit-page .form-color-input {
    flex-shrink: 0;
}

@media (max-width: 768px) {
    .settings-edit-page .form-grid {
        grid-template-columns: 1fr;
    }

    .settings-edit-page .page-header-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const colorInput = document.getElementById('color');
    const colorText = document.getElementById('color-text');

    if (colorInput && colorText) {
        // Update text input when color picker changes
        colorInput.addEventListener('input', function() {
            colorText.value = this.value.toUpperCase();
        });
    }
});
</script>
@endsection

