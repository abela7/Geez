@extends('layouts.admin')

@section('title', 'Edit Shift Template')

@push('styles')
@vite(['resources/css/admin/shifts/create.css'])
@endpush

@push('scripts')
@vite(['resources/js/admin/shifts/create.js'])
<script>
    // Force £ symbol on cost values after page loads
    document.addEventListener('alpine:initialized', () => {
        setTimeout(() => {
            document.querySelectorAll('.cost-value').forEach(el => {
                const observer = new MutationObserver(() => {
                    if (el.textContent && el.textContent.includes('$')) {
                        el.textContent = el.textContent.replace(/\$/g, '£');
                    }
                });
                observer.observe(el, { childList: true, characterData: true, subtree: true });
                
                // Initial replacement
                if (el.textContent && el.textContent.includes('$')) {
                    el.textContent = el.textContent.replace(/\$/g, '£');
                }
            });
        }, 100);
    });
</script>
@endpush

@section('content')
<div class="shift-create-page" x-data="shiftEditData({{ json_encode($shift) }})">
    <!-- Enhanced Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-header-left">
                <h1 class="page-title">Edit Shift Template</h1>
                <p class="page-description">Update shift template details - changes affect future assignments only</p>
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
            <div class="progress-step active" data-step="2">
                <div class="step-number">2</div>
                <div class="step-label">Schedule</div>
            </div>
            <div class="progress-step active" data-step="3">
                <div class="step-number">3</div>
                <div class="step-label">Staffing</div>
            </div>
            <div class="progress-step active" data-step="4">
                <div class="step-number">4</div>
                <div class="step-label">Review</div>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="form-container">
        <form action="{{ route('admin.shifts.manage.update', $shift['id']) }}" method="POST" class="shift-form">
            @csrf
            @method('PUT')
            
            <!-- Enhanced Basic Information -->
            <div class="form-section" data-section="1">
                <div class="section-header">
                    <div class="section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="section-content">
                        <h2 class="section-title">Shift Template Details</h2>
                        <p class="section-description">Update reusable template - assign it to staff via weekly rota</p>
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name" class="form-label required">Shift Template Name</label>
                        <input type="text" id="name" name="name" x-model="form.name" 
                               class="form-input @error('name') form-input-error @enderror" 
                               placeholder="e.g., Main Chef - Evening Shift" required>
                        @error('name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">A clear name for this reusable shift template</div>
                    </div>

                    <div class="form-group">
                        <label for="position_name" class="form-label">Position/Role</label>
                        <input type="text" id="position_name" name="position_name" x-model="form.position_name" 
                               class="form-input @error('position_name') form-input-error @enderror" 
                               placeholder="e.g., Head Chef, Waiter, Bartender">
                        @error('position_name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">The job position or role for this shift (optional)</div>
                    </div>

                    <div class="form-group">
                        <label for="department" class="form-label required">Department</label>
                        <select id="department" name="department" x-model="form.department" 
                                class="form-select @error('department') form-input-error @enderror" required>
                            <option value="">Select Department</option>
                            @foreach($departments as $slug => $name)
                            <option value="{{ $slug }}" x-bind:selected="form.department === '{{ $slug }}'">
                                {{ $name }}
                            </option>
                            @endforeach
                        </select>
                        @error('department')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">Which department this shift belongs to</div>
                    </div>

                    <div class="form-group">
                        <label for="type" class="form-label required">Shift Type</label>
                        <select id="type" name="type" x-model="form.type" 
                                class="form-select @error('type') form-input-error @enderror" required>
                            <option value="">Select Type</option>
                            @foreach($shiftTypes as $slug => $name)
                            <option value="{{ $slug }}" x-bind:selected="form.type === '{{ $slug }}'">
                                {{ $name }}
                            </option>
                            @endforeach
                        </select>
                        @error('type')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">Classification of this shift</div>
                    </div>

                    <div class="form-group form-group-full">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" x-model="form.description" 
                                  class="form-textarea @error('description') form-input-error @enderror" 
                                  rows="3" placeholder="Optional details about this shift template"></textarea>
                        @error('description')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">Additional notes or requirements</div>
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
                        <p class="section-description">Set working hours and break times</p>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="start_time" class="form-label required">Start Time</label>
                        <input type="time" id="start_time" name="start_time" x-model="form.start_time" 
                               @change="calculateDuration()"
                               class="form-input @error('start_time') form-input-error @enderror" required>
                        @error('start_time')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">When the shift begins</div>
                    </div>

                    <div class="form-group">
                        <label for="end_time" class="form-label required">End Time</label>
                        <input type="time" id="end_time" name="end_time" x-model="form.end_time" 
                               @change="calculateDuration()"
                               class="form-input @error('end_time') form-input-error @enderror" required>
                        @error('end_time')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">When the shift ends</div>
                    </div>

                    <div class="form-group">
                        <label for="duration_hours" class="form-label">Duration</label>
                        <input type="text" id="duration_hours" name="duration_hours" x-model="form.duration_hours" 
                               class="form-input" readonly>
                        <div class="form-help">Calculated automatically</div>
                    </div>

                    <div class="form-group">
                        <label for="break_duration" class="form-label">Break Duration (minutes)</label>
                        <input type="number" id="break_duration" name="break_duration" x-model="form.break_duration" 
                               class="form-input @error('break_duration') form-input-error @enderror" 
                               min="0" max="480" step="15" placeholder="30">
                        @error('break_duration')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">Total break time during the shift (in minutes)</div>
                    </div>

                    <!-- Days removed - this is now a template that can be assigned to any day via assignments -->
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
                               min="1" max="50" required>
                        @error('required_staff')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">Minimum number of staff needed</div>
                    </div>

                    <div class="form-group">
                        <label for="hourly_rate" class="form-label">Hourly Rate (£)</label>
                        <input type="number" id="hourly_rate" name="hourly_rate" x-model="form.hourly_rate" 
                               @input="calculateCosts()"
                               class="form-input @error('hourly_rate') form-input-error @enderror" 
                               min="0" step="0.01" placeholder="15.00">
                        @error('hourly_rate')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">Base hourly rate for this shift</div>
                    </div>

                    <div class="form-group">
                        <label for="overtime_rate" class="form-label">Overtime Rate (£)</label>
                        <input type="number" id="overtime_rate" name="overtime_rate" x-model="form.overtime_rate" 
                               class="form-input @error('overtime_rate') form-input-error @enderror" 
                               min="0" step="0.01" placeholder="22.50">
                        @error('overtime_rate')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">Overtime hourly rate (typically 1.5x base)</div>
                    </div>

                    <div class="form-group">
                        <label for="status" class="form-label required">Status</label>
                        <select id="status" name="status" x-model="form.status" 
                                class="form-select @error('status') form-input-error @enderror" required>
                            <option value="draft">Draft</option>
                            <option value="active">Active</option>
                        </select>
                        @error('status')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">Template status</div>
                    </div>
                </div>
            </div>

            <!-- Cost Calculation Section -->
            <div class="form-section" data-section="4" x-show="form.required_staff > 0 && form.hourly_rate > 0">
                <div class="section-header">
                    <div class="section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="section-content">
                        <h2 class="section-title">Cost Calculation</h2>
                        <p class="section-description">Estimated costs for this shift template</p>
                    </div>
                </div>

                <div class="cost-grid">
                    <div class="cost-card">
                        <div class="cost-label">Daily Cost</div>
                        <div class="cost-value" x-text="formatCurrency(calculateDailyCost())"></div>
                        <div class="cost-detail">Per single shift</div>
                    </div>

                    <div class="cost-card">
                        <div class="cost-label">Weekly Cost</div>
                        <div class="cost-value" x-text="formatCurrency(calculateWeeklyCost())"></div>
                        <div class="cost-detail">7-day estimate</div>
                    </div>

                    <div class="cost-card">
                        <div class="cost-label">Monthly Cost</div>
                        <div class="cost-value" x-text="formatCurrency(calculateMonthlyCost())"></div>
                        <div class="cost-detail">4.33 week estimate</div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('admin.shifts.manage.index') }}" class="btn btn-ghost">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary" :disabled="!isFormValid()">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Update Shift Template
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

