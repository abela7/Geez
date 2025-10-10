@extends('layouts.admin')

@section('content')
<div class="staff-create">
    <!-- Page Actions -->
    <div class="page-actions">
        <a href="{{ route('admin.staff.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i>
            {{ __('common.back') }}
        </a>
    </div>

    <!-- Main Form Card -->
    <div class="content-card">
        <div class="card-header">
            <i class="fas fa-user-plus"></i>
            {{ __('staff.create') }}
        </div>

        <form action="{{ route('admin.staff.store') }}" method="POST" id="staffForm" class="form-layout">
            @csrf
            
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
                               value="{{ old('first_name') }}" 
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
                               value="{{ old('last_name') }}" 
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
                               value="{{ old('username') }}" 
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
                                        {{ old('staff_type_id') == $staffType->id ? 'selected' : '' }}
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
                        <label for="password" class="form-label required">
                            {{ __('staff.password') }}
                        </label>
                        <div class="password-input-wrapper">
                            <input type="password" 
                                   class="form-input @error('password') error @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="••••••••"
                                   required
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
                        <label for="password_confirmation" class="form-label required">
                            {{ __('staff.password_confirmation') }}
                        </label>
                        <div class="password-input-wrapper">
                            <input type="password" 
                                   class="form-input @error('password_confirmation') error @enderror" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   placeholder="••••••••"
                                   required
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
                               value="{{ old('email') }}" 
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
                               value="{{ old('phone') }}" 
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
                                required>
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                {{ __('staff.status_values.active') }}
                            </option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                {{ __('staff.status_values.inactive') }}
                            </option>
                            <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>
                                {{ __('staff.status_values.suspended') }}
                            </option>
                        </select>
                        <div class="form-help">{{ __('staff.status_help') }}</div>
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
                               value="{{ old('hire_date', now()->format('Y-m-d')) }}"
                               max="{{ now()->format('Y-m-d') }}">
                        <div class="form-help">{{ __('staff.hire_date_help') }}</div>
                        @error('hire_date')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="hourly_rate" class="form-label required">
                            <i class="fas fa-pound-sign"></i>
                            {{ __('staff.hourly_rate') }}
                        </label>
                        <div class="currency-input-wrapper">
                            <span class="currency-symbol">£</span>
                            <input type="number" 
                                   class="form-input currency-input @error('hourly_rate') error @enderror" 
                                   id="hourly_rate" 
                                   name="hourly_rate" 
                                   value="{{ old('hourly_rate') }}" 
                                   placeholder="15.00"
                                   step="0.01"
                                   min="0"
                                   max="999.99"
                                   required
                                   autocomplete="off">
                        </div>
                        <div class="form-help">
                            <i class="fas fa-info-circle"></i>
                            {{ __('staff.hourly_rate_help') }}
                        </div>
                        @error('hourly_rate')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="employee_id" class="form-label">
                            <i class="fas fa-id-badge"></i>
                            {{ __('staff.employee_id') }}
                        </label>
                        <input type="text" 
                               class="form-input @error('employee_id') error @enderror" 
                               id="employee_id" 
                               name="employee_id" 
                               value="{{ old('employee_id') }}" 
                               placeholder="EMP-0001"
                               maxlength="20"
                               pattern="EMP-[0-9]{4}"
                               autocomplete="off">
                        <div class="form-help">
                            <i class="fas fa-magic"></i>
                            {{ __('staff.employee_id_help') }}
                        </div>
                        @error('employee_id')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i>
                    {{ __('common.save') }}
                </button>
                <a href="{{ route('admin.staff.index') }}" class="btn-secondary">
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
.staff-create {
    padding: var(--nav-item-padding);
}

.page-actions {
    display: flex;
    justify-content: flex-start;
    margin-bottom: 1.5rem;
}

.btn-primary, .btn-secondary {
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

.currency-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.currency-symbol {
    position: absolute;
    left: 0.75rem;
    color: var(--color-text-muted);
    font-weight: 500;
    z-index: 1;
    pointer-events: none;
}

.currency-input {
    padding-left: 1.5rem !important;
    text-align: right;
}

.currency-input:focus {
    padding-left: 1.5rem !important;
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

// Auto-generate username from first and last name
document.addEventListener('DOMContentLoaded', function() {
    const firstNameField = document.getElementById('first_name');
    const lastNameField = document.getElementById('last_name');
    const usernameField = document.getElementById('username');
    const hourlyRateField = document.getElementById('hourly_rate');
    const employeeIdField = document.getElementById('employee_id');
    
    function generateUsername() {
        const firstName = firstNameField.value.trim().toLowerCase();
        const lastName = lastNameField.value.trim().toLowerCase();
        
        if (firstName && lastName) {
            const username = firstName + '_' + lastName;
            // Only set if username field is empty
            if (!usernameField.value) {
                usernameField.value = username.replace(/[^a-z0-9_]/g, '');
            }
        }
    }
    
    // Smart hourly rate formatting
    function formatHourlyRate() {
        let value = hourlyRateField.value;
        
        // Remove any non-numeric characters except decimal point
        value = value.replace(/[^0-9.]/g, '');
        
        // Ensure only one decimal point
        const parts = value.split('.');
        if (parts.length > 2) {
            value = parts[0] + '.' + parts.slice(1).join('');
        }
        
        // Limit to 2 decimal places
        if (parts.length === 2 && parts[1].length > 2) {
            value = parts[0] + '.' + parts[1].substring(0, 2);
        }
        
        // Set minimum value
        if (value && parseFloat(value) < 0) {
            value = '0';
        }
        
        // Set maximum value
        if (value && parseFloat(value) > 999.99) {
            value = '999.99';
        }
        
        hourlyRateField.value = value;
    }
    
    // Auto-generate employee ID if empty
    function generateEmployeeId() {
        if (!employeeIdField.value.trim()) {
            // This will be handled by the backend, but we can show a preview
            employeeIdField.placeholder = 'EMP-XXXX (Auto-generated)';
        }
    }
    
    firstNameField.addEventListener('blur', generateUsername);
    lastNameField.addEventListener('blur', generateUsername);
    
    hourlyRateField.addEventListener('input', formatHourlyRate);
    hourlyRateField.addEventListener('blur', formatHourlyRate);
    
    employeeIdField.addEventListener('blur', generateEmployeeId);
    
    // Set default hourly rate if empty
    if (!hourlyRateField.value) {
        hourlyRateField.value = '15.00';
    }
});

// Form validation
document.getElementById('staffForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const passwordConfirmation = document.getElementById('password_confirmation').value;
    
    if (password !== passwordConfirmation) {
        e.preventDefault();
        alert('{{ __("validation.confirmed", ["attribute" => __("staff.password")]) }}');
        return false;
    }
});
</script>
@endpush
