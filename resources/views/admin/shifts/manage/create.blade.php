@extends('layouts.admin')

@section('title', 'Create New Shift')

@section('content')
<div class="shift-create-page" x-data="shiftCreateData()">
    <!-- Enhanced Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-header-left">
                <h1 class="page-title">Create New Shift</h1>
                <p class="page-description">Set up a new shift template with schedule, staffing requirements, and cost calculations</p>
            </div>
            <div class="page-header-right">
                <div class="header-actions">
                    <a href="{{ route('admin.shifts.manage.index') }}" class="btn btn-ghost">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Shifts
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Indicator -->
    <div class="progress-section">
        <div class="progress-steps">
            <div class="progress-step active" data-step="1">
                <div class="step-number">1</div>
                <div class="step-label">Basic Info</div>
            </div>
            <div class="progress-step" data-step="2">
                <div class="step-number">2</div>
                <div class="step-label">Schedule</div>
            </div>
            <div class="progress-step" data-step="3">
                <div class="step-number">3</div>
                <div class="step-label">Staffing</div>
            </div>
            <div class="progress-step" data-step="4">
                <div class="step-number">4</div>
                <div class="step-label">Review</div>
            </div>
        </div>
    </div>

    <!-- Create Form -->
    <div class="form-container">
        <form action="{{ route('admin.shifts.manage.store') }}" method="POST" class="shift-form">
            @csrf
            
            <!-- Enhanced Basic Information -->
            <div class="form-section" data-section="1">
                <div class="section-header">
                    <div class="section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="section-content">
                        <h2 class="section-title">Basic Information</h2>
                        <p class="section-description">Enter the fundamental details for your new shift template</p>
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name" class="form-label required">Shift Name</label>
                        <input type="text" id="name" name="name" x-model="form.name" 
                               class="form-input @error('name') form-input-error @enderror" 
                               placeholder="e.g., Morning Kitchen Shift" required>
                        @error('name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">Choose a descriptive name that clearly identifies this shift</div>
                    </div>

                    <div class="form-group">
                        <label for="department" class="form-label required">Department</label>
                        <select id="department" name="department" x-model="form.department" 
                                class="form-select @error('department') form-input-error @enderror" required>
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                            <option value="{{ $department }}" {{ old('department') == $department ? 'selected' : '' }}>
                                {{ $department }}
                            </option>
                            @endforeach
                        </select>
                        @error('department')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="type" class="form-label required">Shift Type</label>
                        <select id="type" name="type" x-model="form.type" 
                                class="form-select @error('type') form-input-error @enderror" required>
                            <option value="">Select Type</option>
                            @foreach($shiftTypes as $value => $label)
                            <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                        @error('type')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group form-group-full">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" x-model="form.description" 
                                  class="form-textarea @error('description') form-input-error @enderror" 
                                  rows="3" placeholder="Describe the shift responsibilities and requirements...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">Optional: Provide additional details about this shift</div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Schedule Information -->
            <div class="form-section" data-section="2">
                <div class="section-header">
                    <div class="section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="section-content">
                        <h2 class="section-title">Schedule Information</h2>
                        <p class="section-description">Define the timing and schedule for this shift</p>
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="start_time" class="form-label required">Start Time</label>
                        <input type="time" id="start_time" name="start_time" x-model="form.start_time" 
                               @change="calculateDuration()" 
                               class="form-input @error('start_time') form-input-error @enderror" 
                               value="{{ old('start_time') }}" required>
                        @error('start_time')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="end_time" class="form-label required">End Time</label>
                        <input type="time" id="end_time" name="end_time" x-model="form.end_time" 
                               @change="calculateDuration()" 
                               class="form-input @error('end_time') form-input-error @enderror" 
                               value="{{ old('end_time') }}" required>
                        @error('end_time')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Duration</label>
                        <div class="duration-display" x-text="formatDuration(form.duration_hours)">
                            <span class="duration-placeholder">Select start and end times</span>
                        </div>
                        <div class="form-help">Automatically calculated from start and end times</div>
                    </div>

                    <div class="form-group">
                        <label for="break_duration" class="form-label">Break Duration</label>
                        <div class="input-group">
                            <input type="number" id="break_duration" name="break_duration" x-model="form.break_duration" 
                                   class="form-input @error('break_duration') form-input-error @enderror" 
                                   min="0" max="480" step="15" value="{{ old('break_duration', 30) }}">
                            <span class="input-suffix">minutes</span>
                        </div>
                        @error('break_duration')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">Total break time during the shift (in minutes)</div>
                    </div>

                    <div class="form-group form-group-full">
                        <label class="form-label required">Days of Week</label>
                        <div class="days-grid">
                            @foreach($daysOfWeek as $value => $label)
                            <label class="day-checkbox">
                                <input type="checkbox" name="days_of_week[]" value="{{ $value }}" 
                                       x-model="form.days_of_week"
                                       {{ in_array($value, old('days_of_week', [])) ? 'checked' : '' }}>
                                <span class="day-label">{{ substr($label, 0, 3) }}</span>
                                <span class="day-full">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                        @error('days_of_week')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">Select which days this shift will be active</div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Staffing Requirements -->
            <div class="form-section" data-section="3">
                <div class="section-header">
                    <div class="section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="section-content">
                        <h2 class="section-title">Staffing Requirements</h2>
                        <p class="section-description">Define staffing needs and compensation details</p>
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="required_staff" class="form-label required">Required Staff</label>
                        <input type="number" id="required_staff" name="required_staff" x-model="form.required_staff" 
                               class="form-input @error('required_staff') form-input-error @enderror" 
                               min="1" max="50" value="{{ old('required_staff', 1) }}" required>
                        @error('required_staff')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">Number of staff members needed for this shift</div>
                    </div>

                    <div class="form-group">
                        <label for="hourly_rate" class="form-label">Hourly Rate</label>
                        <div class="input-group">
                            <span class="input-prefix">$</span>
                            <input type="number" id="hourly_rate" name="hourly_rate" x-model="form.hourly_rate" 
                                   class="form-input @error('hourly_rate') form-input-error @enderror" 
                                   min="0" step="0.25" value="{{ old('hourly_rate') }}" @input="calculateCosts()">
                        </div>
                        @error('hourly_rate')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">Base hourly rate for this shift</div>
                    </div>

                    <div class="form-group">
                        <label for="overtime_rate" class="form-label">Overtime Rate</label>
                        <div class="input-group">
                            <span class="input-prefix">$</span>
                            <input type="number" id="overtime_rate" name="overtime_rate" x-model="form.overtime_rate" 
                                   class="form-input @error('overtime_rate') form-input-error @enderror" 
                                   min="0" step="0.25" value="{{ old('overtime_rate') }}">
                        </div>
                        @error('overtime_rate')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">Overtime rate (if applicable)</div>
                    </div>

                    <div class="form-group">
                        <label for="status" class="form-label required">Status</label>
                        <select id="status" name="status" x-model="form.status" 
                                class="form-select @error('status') form-input-error @enderror" required>
                            <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        </select>
                        @error('status')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">Draft shifts can be edited, active shifts are available for scheduling</div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Cost Calculation -->
            <div class="form-section" data-section="4" x-show="form.required_staff > 0 && form.hourly_rate > 0">
                <div class="section-header">
                    <div class="section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                    <div class="section-content">
                        <h2 class="section-title">Cost Calculation</h2>
                        <p class="section-description">Estimated costs based on your staffing requirements</p>
                    </div>
                </div>
                
                <div class="cost-grid">
                    <div class="cost-card cost-card-primary">
                        <div class="cost-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="cost-content">
                            <div class="cost-label">Daily Cost</div>
                            <div class="cost-value" x-text="formatCurrency(calculateDailyCost())">$0.00</div>
                        </div>
                    </div>
                    <div class="cost-card cost-card-success">
                        <div class="cost-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="cost-content">
                            <div class="cost-label">Weekly Cost</div>
                            <div class="cost-value" x-text="formatCurrency(calculateWeeklyCost())">$0.00</div>
                        </div>
                    </div>
                    <div class="cost-card cost-card-info">
                        <div class="cost-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div class="cost-content">
                            <div class="cost-label">Monthly Cost</div>
                            <div class="cost-value" x-text="formatCurrency(calculateMonthlyCost())">$0.00</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Form Actions -->
            <div class="form-actions">
                <div class="form-actions-left">
                    <button type="button" class="btn btn-ghost" @click="resetForm()">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset Form
                    </button>
                </div>
                <div class="form-actions-right">
                    <button type="button" class="btn btn-secondary" @click="saveDraft()" x-bind:disabled="!isFormValid()">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                        </svg>
                        Save as Draft
                    </button>
                    <button type="submit" class="btn btn-primary" x-bind:disabled="!isFormValid()">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Create Shift
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('styles')
@vite(['resources/css/admin/shifts/create.css'])
@endpush

@push('scripts')
@vite(['resources/js/admin/shifts/create.js'])
@endpush
@endsection
