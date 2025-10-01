@extends('layouts.admin')

@section('title', __('shifts.manage.create_shift'))

@section('content')
<div class="shift-create-page" x-data="shiftCreateData()">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-header-left">
                <h1 class="page-title">{{ __('shifts.manage.create_shift') }}</h1>
                <p class="page-description">{{ __('shifts.manage.create_description') }}</p>
            </div>
            <div class="page-header-right">
                <a href="{{ route('admin.shifts.manage.index') }}" class="btn btn-ghost">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('shifts.common.back_to_list') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Create Form -->
    <div class="form-container">
        <form action="{{ route('admin.shifts.manage.store') }}" method="POST" class="shift-form">
            @csrf
            
            <!-- Basic Information -->
            <div class="form-section">
                <div class="section-header">
                    <h2 class="section-title">{{ __('shifts.manage.basic_information') }}</h2>
                    <p class="section-description">{{ __('shifts.manage.basic_info_description') }}</p>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name" class="form-label required">{{ __('shifts.manage.shift_name') }}</label>
                        <input type="text" id="name" name="name" x-model="form.name" class="form-input" placeholder="{{ __('shifts.manage.name_placeholder') }}" required>
                        <div class="form-help">{{ __('shifts.manage.name_help') }}</div>
                    </div>

                    <div class="form-group">
                        <label for="department" class="form-label required">{{ __('shifts.common.department') }}</label>
                        <select id="department" name="department" x-model="form.department" class="form-select" required>
                            <option value="">{{ __('shifts.manage.select_department') }}</option>
                            @foreach($departments as $value => $label)
                            <option value="{{ $value }}">{{ __('shifts.departments.' . strtolower(str_replace(' ', '_', $value))) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="type" class="form-label required">{{ __('shifts.common.type') }}</label>
                        <select id="type" name="type" x-model="form.type" class="form-select" required>
                            <option value="">{{ __('shifts.manage.select_type') }}</option>
                            @foreach($shiftTypes as $value => $label)
                            <option value="{{ $value }}">{{ __('shifts.types.' . $value) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group form-group-full">
                        <label for="description" class="form-label">{{ __('shifts.manage.description') }}</label>
                        <textarea id="description" name="description" x-model="form.description" class="form-textarea" rows="3" placeholder="{{ __('shifts.manage.description_placeholder') }}"></textarea>
                        <div class="form-help">{{ __('shifts.manage.description_help') }}</div>
                    </div>
                </div>
            </div>

            <!-- Schedule Information -->
            <div class="form-section">
                <div class="section-header">
                    <h2 class="section-title">{{ __('shifts.manage.schedule_information') }}</h2>
                    <p class="section-description">{{ __('shifts.manage.schedule_info_description') }}</p>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="start_time" class="form-label required">{{ __('shifts.manage.start_time') }}</label>
                        <input type="time" id="start_time" name="start_time" x-model="form.start_time" @change="calculateDuration()" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="end_time" class="form-label required">{{ __('shifts.manage.end_time') }}</label>
                        <input type="time" id="end_time" name="end_time" x-model="form.end_time" @change="calculateDuration()" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ __('shifts.manage.duration') }}</label>
                        <div class="duration-display" x-text="formatDuration(form.duration_hours)"></div>
                        <div class="form-help">{{ __('shifts.manage.duration_help') }}</div>
                    </div>

                    <div class="form-group">
                        <label for="break_duration" class="form-label">{{ __('shifts.manage.break_duration') }}</label>
                        <div class="input-group">
                            <input type="number" id="break_duration" name="break_duration" x-model="form.break_duration" class="form-input" min="0" max="480" step="15">
                            <span class="input-suffix">{{ __('shifts.common.minutes') }}</span>
                        </div>
                        <div class="form-help">{{ __('shifts.manage.break_duration_help') }}</div>
                    </div>

                    <div class="form-group form-group-full">
                        <label class="form-label required">{{ __('shifts.manage.days_of_week') }}</label>
                        <div class="days-grid">
                            @foreach($daysOfWeek as $value => $label)
                            <label class="day-checkbox">
                                <input type="checkbox" name="days_of_week[]" value="{{ $value }}" x-model="form.days_of_week">
                                <span class="day-label">{{ __('shifts.days.' . $value) }}</span>
                            </label>
                            @endforeach
                        </div>
                        <div class="form-help">{{ __('shifts.manage.days_help') }}</div>
                    </div>
                </div>
            </div>

            <!-- Staffing Requirements -->
            <div class="form-section">
                <div class="section-header">
                    <h2 class="section-title">{{ __('shifts.manage.staffing_requirements') }}</h2>
                    <p class="section-description">{{ __('shifts.manage.staffing_requirements_description') }}</p>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="required_staff" class="form-label required">{{ __('shifts.manage.required_staff') }}</label>
                        <input type="number" id="required_staff" name="required_staff" x-model="form.required_staff" class="form-input" min="1" max="50" required>
                        <div class="form-help">{{ __('shifts.manage.required_staff_help') }}</div>
                    </div>

                    <div class="form-group">
                        <label for="hourly_rate" class="form-label">{{ __('shifts.manage.hourly_rate') }}</label>
                        <div class="input-group">
                            <span class="input-prefix">$</span>
                            <input type="number" id="hourly_rate" name="hourly_rate" x-model="form.hourly_rate" class="form-input" min="0" step="0.25">
                        </div>
                        <div class="form-help">{{ __('shifts.manage.hourly_rate_help') }}</div>
                    </div>

                    <div class="form-group">
                        <label for="overtime_rate" class="form-label">{{ __('shifts.manage.overtime_rate') }}</label>
                        <div class="input-group">
                            <span class="input-prefix">$</span>
                            <input type="number" id="overtime_rate" name="overtime_rate" x-model="form.overtime_rate" class="form-input" min="0" step="0.25">
                        </div>
                        <div class="form-help">{{ __('shifts.manage.overtime_rate_help') }}</div>
                    </div>

                    <div class="form-group">
                        <label for="status" class="form-label required">{{ __('shifts.common.status') }}</label>
                        <select id="status" name="status" x-model="form.status" class="form-select" required>
                            <option value="draft">{{ __('shifts.statuses.draft') }}</option>
                            <option value="active">{{ __('shifts.statuses.active') }}</option>
                        </select>
                        <div class="form-help">{{ __('shifts.manage.status_help') }}</div>
                    </div>
                </div>
            </div>

            <!-- Cost Calculation -->
            <div class="form-section" x-show="form.required_staff > 0 && form.hourly_rate > 0">
                <div class="section-header">
                    <h2 class="section-title">{{ __('shifts.manage.cost_calculation') }}</h2>
                    <p class="section-description">{{ __('shifts.manage.cost_calculation_description') }}</p>
                </div>
                
                <div class="cost-grid">
                    <div class="cost-card">
                        <div class="cost-label">{{ __('shifts.manage.daily_cost') }}</div>
                        <div class="cost-value" x-text="formatCurrency(calculateDailyCost())"></div>
                    </div>
                    <div class="cost-card">
                        <div class="cost-label">{{ __('shifts.manage.weekly_cost') }}</div>
                        <div class="cost-value" x-text="formatCurrency(calculateWeeklyCost())"></div>
                    </div>
                    <div class="cost-card">
                        <div class="cost-label">{{ __('shifts.manage.monthly_cost') }}</div>
                        <div class="cost-value" x-text="formatCurrency(calculateMonthlyCost())"></div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <div class="form-actions-left">
                    <button type="button" class="btn btn-ghost" @click="resetForm()">
                        {{ __('shifts.common.reset') }}
                    </button>
                </div>
                <div class="form-actions-right">
                    <button type="button" class="btn btn-secondary" @click="saveDraft()">
                        {{ __('shifts.manage.save_draft') }}
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ __('shifts.manage.create_shift') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('styles')
@vite(['resources/css/admin/shifts/manage.css'])
@endpush

@push('scripts')
@vite(['resources/js/admin/shifts/manage.js'])
@endpush
@endsection
