@extends('layouts.admin')

@section('content')
<div class="staff-types-edit">
    <div class="page-actions">
        <a href="{{ route('admin.staff.types.show', $staffType) }}" class="btn-info">
            <i class="fas fa-eye"></i>
            {{ __('common.view') }}
        </a>
        <a href="{{ route('admin.staff.types.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i>
            {{ __('common.back') }}
        </a>
    </div>

    <div class="content-card">
        <div class="card-header">
            <i class="fas fa-edit"></i>
            {{ __('staff.types.edit_type') }}: {{ $staffType->display_name }}
        </div>

        <form action="{{ route('admin.staff.types.update', $staffType) }}" method="POST" id="staffTypeForm" class="form-layout">
            @csrf
            @method('PUT')
            
            <div class="form-row">
                <div class="form-group">
                    <label for="display_name" class="form-label required">
                        {{ __('staff.types.display_name') }}
                    </label>
                    <input type="text" 
                           class="form-input @error('display_name') error @enderror" 
                           id="display_name" 
                           name="display_name" 
                           value="{{ old('display_name', $staffType->display_name) }}" 
                           placeholder="e.g., System Administrator"
                           required>
                    <div class="form-help">{{ __('staff.types.display_name_help') }}</div>
                    @error('display_name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="name" class="form-label required">
                        {{ __('staff.types.name') }}
                    </label>
                    <input type="text" 
                           class="form-input @error('name') error @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $staffType->name) }}" 
                           placeholder="e.g., system_administrator"
                           pattern="^[a-z_]+$"
                           required>
                    <div class="form-help">{{ __('staff.types.name_help') }}</div>
                    @error('name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="priority" class="form-label required">
                        {{ __('staff.types.priority') }}
                    </label>
                    <input type="number" 
                           class="form-input @error('priority') error @enderror" 
                           id="priority" 
                           name="priority" 
                           value="{{ old('priority', $staffType->priority) }}" 
                           min="0" 
                           max="100"
                           required>
                    <div class="form-help">{{ __('staff.types.priority_help') }}</div>
                    @error('priority')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('common.status') }}</label>
                    <div class="form-switch">
                        <input class="switch-input" 
                               type="checkbox" 
                               id="is_active" 
                               name="is_active" 
                               value="1" 
                               {{ old('is_active', $staffType->is_active) ? 'checked' : '' }}>
                        <label class="switch-label" for="is_active">
                            {{ __('staff.types.is_active') }}
                        </label>
                    </div>
                    <div class="form-help">{{ __('staff.types.is_active_help') }}</div>
                    @error('is_active')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">{{ __('staff.types.description') }}</label>
                <textarea class="form-textarea @error('description') error @enderror" 
                          id="description" 
                          name="description" 
                          rows="3" 
                          placeholder="{{ __('staff.types.description_help') }}">{{ old('description', $staffType->description) }}</textarea>
                @error('description')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('staff.types.priority_level_preview') }}</label>
                <div class="priority-preview" id="priorityPreview">
                    <i class="fas fa-info-circle"></i>
                    <span id="priorityLevelText">{{ $staffType->priority_level }}</span>
                </div>
            </div>

            @if($staffType->staff_count > 0)
                <div class="warning-notice">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>{{ __('common.warning') }}:</strong>
                    {{ __('staff.types.edit_warning', ['count' => $staffType->staff_count]) }}
                </div>
            @endif

            <div class="form-actions">
                <a href="{{ route('admin.staff.types.show', $staffType) }}" class="btn-secondary">
                    <i class="fas fa-times"></i>
                    {{ __('common.cancel') }}
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i>
                    {{ __('common.update') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
.staff-types-edit {
    padding: var(--nav-item-padding);
    max-width: 800px;
    margin: 0 auto;
}

.page-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}

.content-card {
    background: var(--color-surface-card);
    border-radius: var(--nav-item-radius);
    box-shadow: var(--color-surface-card-shadow);
    border: 1px solid var(--color-surface-card-border);
    overflow: hidden;
}

.card-header {
    padding: 1.5rem 2rem;
    background: var(--color-bg-tertiary);
    border-bottom: 1px solid var(--color-border-base);
    color: var(--color-text-primary);
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-layout {
    padding: 2rem;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-label {
    color: var(--color-text-primary);
    font-weight: 500;
    font-size: 0.875rem;
}

.form-label.required::after {
    content: ' *';
    color: var(--color-error);
}

.form-input,
.form-textarea {
    padding: 0.75rem;
    border: 1px solid var(--color-border-base);
    border-radius: var(--nav-item-radius);
    background: var(--color-bg-secondary);
    color: var(--color-text-primary);
    font-size: 0.875rem;
    transition: var(--transition-all);
}

.form-input:focus,
.form-textarea:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px var(--focus-ring);
}

.form-input.error,
.form-textarea.error {
    border-color: var(--color-error);
}

.form-help {
    color: var(--color-text-muted);
    font-size: 0.75rem;
}

.form-error {
    color: var(--color-error);
    font-size: 0.75rem;
}

.form-switch {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.switch-input {
    width: 3rem;
    height: 1.5rem;
    appearance: none;
    background: var(--color-text-muted);
    border-radius: 0.75rem;
    position: relative;
    cursor: pointer;
    transition: var(--transition-all);
}

.switch-input:checked {
    background: var(--color-success);
}

.switch-input::before {
    content: '';
    position: absolute;
    width: 1.25rem;
    height: 1.25rem;
    border-radius: 50%;
    background: var(--color-bg-secondary);
    top: 0.125rem;
    left: 0.125rem;
    transition: var(--transition-all);
}

.switch-input:checked::before {
    transform: translateX(1.5rem);
}

.switch-label {
    color: var(--color-text-primary);
    font-weight: 500;
    cursor: pointer;
}

.priority-preview {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    border-radius: var(--nav-item-radius);
    background: var(--color-info-bg);
    color: var(--color-info);
    border: 1px solid var(--color-info);
}

.warning-notice {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    padding: 1rem;
    border-radius: var(--nav-item-radius);
    background: var(--color-warning-bg);
    color: var(--color-warning);
    border: 1px solid var(--color-warning);
}

.form-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid var(--color-border-base);
}

.btn-primary,
.btn-secondary,
.btn-info {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    border-radius: var(--nav-item-radius);
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition-all);
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: var(--button-primary-bg);
    color: var(--button-primary-text);
    box-shadow: var(--button-primary-shadow);
}

.btn-primary:hover {
    background: var(--button-primary-hover-bg);
    box-shadow: var(--button-primary-hover-shadow);
    transform: var(--hover-scale);
}

.btn-secondary {
    background: var(--button-secondary-bg);
    color: var(--button-secondary-text);
    box-shadow: var(--button-secondary-shadow);
}

.btn-secondary:hover {
    background: var(--button-secondary-hover-bg);
    transform: var(--hover-scale);
}

.btn-info {
    background: var(--color-info-bg);
    color: var(--color-info);
    border: 1px solid var(--color-info);
}

.btn-info:hover {
    background: var(--color-info);
    color: var(--button-primary-text);
    transform: var(--hover-scale);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const priorityInput = document.getElementById('priority');
    const priorityPreview = document.getElementById('priorityPreview');
    const priorityLevelText = document.getElementById('priorityLevelText');

    // Update priority level preview
    function updatePriorityPreview() {
        const priority = parseInt(priorityInput.value) || 0;
        let levelText = '';
        let levelClass = 'operational';

        if (priority >= 100) {
            levelText = 'System Level (100)';
            levelClass = 'system';
        } else if (priority >= 80) {
            levelText = 'Administrative (80-99)';
            levelClass = 'administrative';
        } else if (priority >= 60) {
            levelText = 'Management (60-79)';
            levelClass = 'management';
        } else if (priority >= 40) {
            levelText = 'Supervisory (40-59)';
            levelClass = 'supervisory';
        } else if (priority >= 20) {
            levelText = 'Operational (20-39)';
            levelClass = 'operational';
        } else {
            levelText = 'Basic (0-19)';
            levelClass = 'basic';
        }

        priorityLevelText.textContent = levelText;
        priorityPreview.className = `priority-preview ${levelClass}`;
    }

    priorityInput.addEventListener('input', updatePriorityPreview);
    updatePriorityPreview(); // Initial call

    // Form validation
    document.getElementById('staffTypeForm').addEventListener('submit', function(e) {
        const nameInput = document.getElementById('name');
        const name = nameInput.value;
        const namePattern = /^[a-z_]+$/;
        
        if (!namePattern.test(name)) {
            e.preventDefault();
            nameInput.classList.add('error');
            nameInput.focus();
            return false;
        }
    });
});
</script>
@endpush