@extends('layouts.admin')

@section('title', __('admin.shift_types.create_title'))

@section('content')
<div class="shift-types-page">
    <!-- Flash Messages -->
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

    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <div class="breadcrumb">
                    <a href="{{ route('admin.settings.shift-types.index') }}" class="breadcrumb-link">{{ __('admin.shift_types.title') }}</a>
                    <span class="breadcrumb-separator">/</span>
                    <span class="breadcrumb-current">{{ __('admin.shift_types.create_new') }}</span>
                </div>
                <h1 class="page-title">{{ __('admin.shift_types.create_title') }}</h1>
                <p class="page-subtitle">{{ __('admin.shift_types.create_description') }}</p>
            </div>
        </div>
    </div>

    <!-- Create Form -->
    <div class="shift-types-form-section">
        <form action="{{ route('admin.settings.shift-types.store') }}" method="POST" class="form-layout">
            @csrf
            
            <div class="form-section">
                <div class="form-section-header">
                    <h2 class="form-section-title">{{ __('admin.shift_types.basic_information') }}</h2>
                    <p class="form-section-description">{{ __('admin.shift_types.basic_info_description') }}</p>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name" class="form-label required">{{ __('admin.shift_types.name') }}</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               class="form-input @error('name') form-input-error @enderror" 
                               value="{{ old('name') }}" 
                               placeholder="{{ __('admin.shift_types.name_placeholder') }}" 
                               required>
                        @error('name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">{{ __('admin.shift_types.name_help') }}</div>
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">{{ __('admin.shift_types.description') }}</label>
                        <textarea id="description" 
                                  name="description" 
                                  class="form-textarea @error('description') form-input-error @enderror" 
                                  rows="3"
                                  placeholder="{{ __('admin.shift_types.description_placeholder') }}">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">{{ __('admin.shift_types.description_help') }}</div>
                    </div>

                    <div class="form-group">
                        <label for="color" class="form-label required">{{ __('admin.shift_types.color') }}</label>
                        <div class="color-input-container">
                            <input type="color" 
                                   id="color" 
                                   name="color" 
                                   class="form-color @error('color') form-input-error @enderror" 
                                   value="{{ old('color', '#3B82F6') }}" 
                                   required>
                            <input type="text" 
                                   id="color-text" 
                                   name="color_text" 
                                   class="form-input form-color-text @error('color') form-input-error @enderror" 
                                   value="{{ old('color', '#3B82F6') }}" 
                                   placeholder="#000000">
                        </div>
                        @error('color')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">{{ __('admin.shift_types.color_help') }}</div>
                    </div>

                    <div class="form-group">
                        <label for="sort_order" class="form-label">{{ __('admin.shift_types.sort_order') }}</label>
                        <input type="number"
                               id="sort_order"
                               name="sort_order"
                               class="form-input @error('sort_order') form-input-error @enderror"
                               value="{{ old('sort_order', 0) }}"
                               min="0"
                               step="1">
                        @error('sort_order')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">{{ __('admin.shift_types.sort_order_help') }}</div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-header">
                    <h2 class="form-section-title">{{ __('admin.shift_types.rate_settings') }}</h2>
                    <p class="form-section-description">{{ __('admin.shift_types.rate_settings_description') }}</p>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="default_hourly_rate" class="form-label">{{ __('admin.shift_types.default_hourly_rate') }}</label>
                        <div class="currency-input-container">
                            <span class="currency-symbol">£</span>
                            <input type="number" 
                                   id="default_hourly_rate" 
                                   name="default_hourly_rate" 
                                   class="form-input currency-input @error('default_hourly_rate') form-input-error @enderror" 
                                   value="{{ old('default_hourly_rate') }}" 
                                   min="0" 
                                   max="999.99" 
                                   step="0.01">
                        </div>
                        @error('default_hourly_rate')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">{{ __('admin.shift_types.default_hourly_rate_help') }}</div>
                    </div>

                    <div class="form-group">
                        <label for="default_overtime_rate" class="form-label">{{ __('admin.shift_types.default_overtime_rate') }}</label>
                        <div class="currency-input-container">
                            <span class="currency-symbol">£</span>
                            <input type="number" 
                                   id="default_overtime_rate" 
                                   name="default_overtime_rate" 
                                   class="form-input currency-input @error('default_overtime_rate') form-input-error @enderror" 
                                   value="{{ old('default_overtime_rate') }}" 
                                   min="0" 
                                   max="999.99" 
                                   step="0.01">
                        </div>
                        @error('default_overtime_rate')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">{{ __('admin.shift_types.default_overtime_rate_help') }}</div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-header">
                    <h2 class="form-section-title">{{ __('admin.shift_types.settings') }}</h2>
                    <p class="form-section-description">{{ __('admin.shift_types.settings_description') }}</p>
                </div>
                
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" 
                               name="is_active" 
                               value="1" 
                               class="form-checkbox @error('is_active') form-input-error @enderror" 
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <span class="checkbox-text">{{ __('admin.shift_types.is_active') }}</span>
                    </label>
                    @error('is_active')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                    <div class="form-help">{{ __('admin.shift_types.is_active_help') }}</div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('admin.settings.shift-types.index') }}" class="btn btn-secondary">
                    {{ __('admin.common.cancel') }}
                </a>
                <button type="submit" class="btn btn-primary">
                    {{ __('admin.shift_types.create_shift_type') }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
.shift-types-page {
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

.page-title-section {
    flex: 1;
}

.page-title {
    font-size: 1.875rem;
    font-weight: 700;
    color: var(--color-text-primary);
    margin: 0;
}

.page-subtitle {
    color: var(--color-text-secondary);
    margin: 0.25rem 0 0 0;
}

.shift-types-form-section {
    background: var(--color-surface-card);
    border: 1px solid var(--color-surface-card-border);
    border-radius: 0.125rem;
    box-shadow: var(--color-surface-card-shadow);
    overflow: hidden;
}

.form-layout {
    padding: 2rem;
}

.form-section {
    margin-bottom: 2rem;
}

.form-section:last-child {
    margin-bottom: 0;
}

.form-section-header {
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--color-surface-card-border);
}

.form-section-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--color-text-primary);
    margin: 0 0 0.5rem 0;
}

.form-section-description {
    color: var(--color-text-secondary);
    font-size: 0.875rem;
    margin: 0;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
    color: var(--color-error-500);
}

.form-input,
.form-textarea,
.form-color-text {
    padding: 0.75rem;
    border: 1px solid var(--color-surface-card-border);
    border-radius: 0.375rem;
    background: var(--color-bg-primary);
    color: var(--color-text-primary);
    font-size: 0.875rem;
    transition: var(--transition-all);
}

.form-input:focus,
.form-textarea:focus,
.form-color-text:focus {
    outline: none;
    border-color: var(--color-primary-600);
    box-shadow: 0 0 0 3px var(--color-primary-100);
}

.form-checkbox {
    accent-color: var(--color-primary-600);
}

.currency-input-container {
    position: relative;
    display: flex;
    align-items: center;
}

.currency-symbol {
    position: absolute;
    left: 0.75rem;
    color: var(--color-text-secondary);
    font-weight: 500;
    z-index: 10;
}

.currency-input {
    padding-left: 2rem;
}

.color-input-container {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-color {
    width: 3rem;
    height: 2.5rem;
    border: 1px solid var(--color-surface-card-border);
    border-radius: 0.375rem;
    cursor: pointer;
}

.form-color-text {
    flex: 1;
    font-family: monospace;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
}

.checkbox-text {
    color: var(--color-text-primary);
    font-size: 0.875rem;
}

.form-help {
    font-size: 0.75rem;
    color: var(--color-text-tertiary);
}

.form-error {
    font-size: 0.75rem;
    color: var(--color-error-500);
}

.form-actions {
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
    padding-top: 1.5rem;
    border-top: 1px solid var(--color-surface-card-border);
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 0.375rem;
    font-weight: 500;
    font-size: 0.875rem;
    transition: var(--transition-all);
    cursor: pointer;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
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
    color: var(--color-text-secondary);
    border: 1px solid var(--color-surface-card-border);
}

.btn-secondary:hover {
    background: var(--color-bg-tertiary);
    color: var(--color-text-primary);
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.breadcrumb-link {
    color: var(--color-primary-600);
    text-decoration: none;
    font-weight: 500;
}

.breadcrumb-link:hover {
    color: var(--color-primary-700);
}

.breadcrumb-separator {
    color: var(--color-text-tertiary);
}

.breadcrumb-current {
    color: var(--color-text-secondary);
}
</style>
@endpush

<script>
// Sync color picker with text input
document.getElementById('color').addEventListener('input', function() {
    document.getElementById('color-text').value = this.value;
});

document.getElementById('color-text').addEventListener('input', function() {
    const colorInput = document.getElementById('color');
    const textValue = this.value;

    // Validate hex color
    if (/^#[0-9A-Fa-f]{6}$/.test(textValue)) {
        colorInput.value = textValue;
    }
});

// Ensure sort_order has a value before form submission
document.querySelector('form').addEventListener('submit', function(e) {
    const sortOrderField = document.getElementById('sort_order');
    if (!sortOrderField.value || sortOrderField.value === '') {
        sortOrderField.value = '0';
    }
});
</script>
@endsection
