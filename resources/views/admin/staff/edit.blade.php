@extends('layouts.admin')

@section('content')
<div class="staff-edit">
    <!-- Page Actions -->
    <div class="page-actions">
        <a href="{{ route('admin.staff.show', $staff) }}" class="btn-info">
            <i class="fas fa-eye"></i>
            {{ __('common.view') }}
        </a>
        <a href="{{ route('admin.staff.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i>
            {{ __('common.back') }}
        </a>
    </div>

    <!-- Main Form Card -->
    <div class="content-card">
        <div class="card-header">
            <i class="fas fa-user-edit"></i>
            {{ __('staff.edit') }}: {{ $staff->full_name }}
        </div>

        <form action="{{ route('admin.staff.update', $staff) }}" method="POST" id="staffForm" class="form-layout">
            @csrf
            @method('PUT')
            
            <!-- Personal Information Section -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-user"></i>
                    {{ __('staff.personal_info') }}
                </h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name" class="form-label required">
                            {{ __('staff.first_name') }}
                        </label>
                        <input type="text" 
                               class="form-input @error('first_name') error @enderror" 
                               id="first_name" 
                               name="first_name" 
                               value="{{ old('first_name', $staff->first_name) }}" 
                               placeholder="John"
                               required
                               maxlength="100">
                        <div class="form-help">{{ __('staff.first_name_help') }}</div>
                        @error('first_name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="last_name" class="form-label required">
                            {{ __('staff.last_name') }}
                        </label>
                        <input type="text" 
                               class="form-input @error('last_name') error @enderror" 
                               id="last_name" 
                               name="last_name" 
                               value="{{ old('last_name', $staff->last_name) }}" 
                               placeholder="Doe"
                               required
                               maxlength="100">
                        <div class="form-help">{{ __('staff.last_name_help') }}</div>
                        @error('last_name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Account Information Section -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-key"></i>
                    {{ __('staff.account_info') }}
                </h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="username" class="form-label required">
                            {{ __('staff.username') }}
                        </label>
                        <input type="text" 
                               class="form-input @error('username') error @enderror" 
                               id="username" 
                               name="username" 
                               value="{{ old('username', $staff->username) }}" 
                               placeholder="john_doe"
                               required
                               maxlength="50"
                               pattern="[a-zA-Z0-9_]+"
                               autocomplete="username">
                        <div class="form-help">{{ __('staff.username_help') }}</div>
                        @error('username')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="staff_type_id" class="form-label required">
                            {{ __('staff.staff_type') }}
                        </label>
                        <select class="form-input @error('staff_type_id') error @enderror" 
                                id="staff_type_id" 
                                name="staff_type_id" 
                                required>
                            <option value="">{{ __('common.select_option') }}</option>
                            @foreach($staffTypes as $staffType)
                                <option value="{{ $staffType->id }}" 
                                        {{ old('staff_type_id', $staff->staff_type_id) == $staffType->id ? 'selected' : '' }}
                                        data-priority="{{ $staffType->priority }}">
                                    {{ $staffType->display_name }}
                                    @if($staffType->description)
                                        - {{ Str::limit($staffType->description, 50) }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <div class="form-help">{{ __('staff.staff_type_help') }}</div>
                        @error('staff_type_id')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password" class="form-label">
                            {{ __('staff.password') }}
                        </label>
                        <div class="password-input-wrapper">
                            <input type="password" 
                                   class="form-input @error('password') error @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="{{ __('staff.password_help') }}"
                                   minlength="8"
                                   autocomplete="new-password">
                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                <i class="fas fa-eye" id="password-eye"></i>
                            </button>
                        </div>
                        <div class="form-help">{{ __('staff.password_help') }}</div>
                        @error('password')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">
                            {{ __('staff.password_confirmation') }}
                        </label>
                        <div class="password-input-wrapper">
                            <input type="password" 
                                   class="form-input @error('password_confirmation') error @enderror" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   placeholder="••••••••"
                                   minlength="8"
                                   autocomplete="new-password">
                            <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                                <i class="fas fa-eye" id="password_confirmation-eye"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Contact Information Section -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-address-book"></i>
                    {{ __('staff.contact_info') }}
                </h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="email" class="form-label">
                            {{ __('staff.email') }}
                        </label>
                        <input type="email" 
                               class="form-input @error('email') error @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $staff->email) }}" 
                               placeholder="john.doe@example.com"
                               autocomplete="email">
                        <div class="form-help">{{ __('staff.email_help') }}</div>
                        @error('email')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone" class="form-label">
                            {{ __('staff.phone') }}
                        </label>
                        <input type="tel" 
                               class="form-input @error('phone') error @enderror" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone', $staff->phone) }}" 
                               placeholder="+1 (555) 123-4567"
                               maxlength="20"
                               autocomplete="tel">
                        <div class="form-help">{{ __('staff.phone_help') }}</div>
                        @error('phone')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Employment Information Section -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-briefcase"></i>
                    {{ __('staff.employment_info') }}
                </h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="status" class="form-label required">
                            {{ __('staff.status') }}
                        </label>
                        <select class="form-input @error('status') error @enderror" 
                                id="status" 
                                name="status" 
                                required
                                {{ $staff->id === Auth::id() && $staff->status === 'active' ? 'onchange=preventSelfDeactivation(this)' : '' }}>
                            <option value="active" {{ old('status', $staff->status) == 'active' ? 'selected' : '' }}>
                                {{ __('staff.status_values.active') }}
                            </option>
                            <option value="inactive" {{ old('status', $staff->status) == 'inactive' ? 'selected' : '' }}>
                                {{ __('staff.status_values.inactive') }}
                            </option>
                            <option value="suspended" {{ old('status', $staff->status) == 'suspended' ? 'selected' : '' }}>
                                {{ __('staff.status_values.suspended') }}
                            </option>
                        </select>
                        <div class="form-help">{{ __('staff.status_help') }}</div>
                        @if($staff->id === Auth::id())
                            <div class="form-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                {{ __('staff.cannot_deactivate_self') }}
                            </div>
                        @endif
                        @error('status')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="hire_date" class="form-label">
                            {{ __('staff.hire_date') }}
                        </label>
                        <input type="date" 
                               class="form-input @error('hire_date') error @enderror" 
                               id="hire_date" 
                               name="hire_date" 
                               value="{{ old('hire_date', $staff->hire_date?->format('Y-m-d')) }}"
                               max="{{ now()->format('Y-m-d') }}">
                        <div class="form-help">{{ __('staff.hire_date_help') }}</div>
                        @error('hire_date')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i>
                    {{ __('common.update') }}
                </button>
                <a href="{{ route('admin.staff.show', $staff) }}" class="btn-secondary">
                    <i class="fas fa-times"></i>
                    {{ __('common.cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
.staff-edit {
    padding: var(--nav-item-padding);
}

.page-actions {
    display: flex;
    justify-content: flex-start;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}

.btn-primary, .btn-secondary, .btn-info {
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

.content-card {
    background: var(--color-surface-card);
    border: 1px solid var(--color-border-base);
    border-radius: var(--nav-item-radius);
    overflow: hidden;
}

.card-header {
    background: var(--color-bg-tertiary);
    color: var(--color-text-primary);
    padding: 1.5rem;
    font-size: 1.125rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border-bottom: 1px solid var(--color-border-base);
}

.form-layout {
    padding: 2rem;
}

.form-section {
    margin-bottom: 2rem;
}

.form-section:last-of-type {
    margin-bottom: 0;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1rem;
    font-weight: 600;
    color: var(--color-text-primary);
    margin: 0 0 1.5rem 0;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid var(--color-border-base);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.form-row:last-child {
    margin-bottom: 0;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-label {
    font-weight: 500;
    color: var(--color-text-primary);
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.form-label.required::after {
    content: ' *';
    color: var(--color-error);
}

.form-input {
    padding: 0.75rem;
    border: 1px solid var(--color-border-base);
    border-radius: var(--nav-item-radius);
    background: var(--color-bg-secondary);
    color: var(--color-text-primary);
    font-size: 0.875rem;
    transition: var(--transition-all);
    width: 100%;
}

.form-input:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(var(--color-primary-rgb), 0.1);
}

.form-input.error {
    border-color: var(--color-error);
    box-shadow: 0 0 0 3px rgba(var(--color-error), 0.1);
}

.form-help {
    font-size: 0.75rem;
    color: var(--color-text-muted);
    margin-top: 0.25rem;
}

.form-error {
    font-size: 0.75rem;
    color: var(--color-error);
    margin-top: 0.25rem;
    font-weight: 500;
}

.form-warning {
    font-size: 0.75rem;
    color: var(--color-warning);
    margin-top: 0.25rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.password-input-wrapper {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--color-text-muted);
    cursor: pointer;
    padding: 0.25rem;
    transition: var(--transition-all);
}

.password-toggle:hover {
    color: var(--color-primary);
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    padding-top: 2rem;
    border-top: 1px solid var(--color-border-base);
    margin-top: 2rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .form-actions {
        flex-direction: column;
        align-items: stretch;
    }
    
    .page-actions {
        flex-direction: column;
        align-items: stretch;
    }
    
    .form-layout {
        padding: 1.5rem;
    }
    
    .card-header {
        padding: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const eye = document.getElementById(fieldId + '-eye');
    
    if (field.type === 'password') {
        field.type = 'text';
        eye.classList.remove('fa-eye');
        eye.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        eye.classList.remove('fa-eye-slash');
        eye.classList.add('fa-eye');
    }
}

function preventSelfDeactivation(selectElement) {
    if (selectElement.value !== 'active') {
        if (!confirm('{{ __("staff.cannot_deactivate_self") }}')) {
            selectElement.value = 'active';
        }
    }
}

// Form validation
document.getElementById('staffForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const passwordConfirmation = document.getElementById('password_confirmation').value;
    
    // Only validate if password is provided
    if (password && password !== passwordConfirmation) {
        e.preventDefault();
        alert('{{ __("validation.confirmed", ["attribute" => __("staff.password")]) }}');
        return false;
    }
});
</script>
@endpush
