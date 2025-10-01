@extends('layouts.admin')

@section('title', __('todos.schedules.create_schedule'))

@section('content')
<div class="schedule-create-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-header-left">
                <div class="breadcrumb">
                    <a href="{{ route('admin.todos.schedules.index') }}" class="breadcrumb-link">
                        {{ __('todos.schedules.title') }}
                    </a>
                    <span class="breadcrumb-separator">/</span>
                    <span class="breadcrumb-current">{{ __('todos.schedules.create_schedule') }}</span>
                </div>
                <h1 class="page-title">{{ __('todos.schedules.create_schedule') }}</h1>
                <p class="page-description">{{ __('todos.schedules.create_description') }}</p>
            </div>
        </div>
    </div>

    <!-- Create Form -->
    <div class="schedule-form-container" x-data="initializeScheduleForm()">
        <form class="schedule-form" @submit.prevent="submitForm()">
            <!-- Basic Information -->
            <div class="form-section">
                <h3 class="section-title">{{ __('todos.schedules.basic_information') }}</h3>
                
                <div class="form-group">
                    <label class="form-label required">{{ __('todos.schedules.schedule_name') }}</label>
                    <input type="text" 
                           class="form-input" 
                           x-model="form.name"
                           placeholder="{{ __('todos.schedules.schedule_name_placeholder') }}"
                           required>
                    <div x-show="errors.name" class="form-error" x-text="errors.name"></div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">{{ __('todos.schedules.description') }}</label>
                    <textarea class="form-textarea" 
                              x-model="form.description"
                              placeholder="{{ __('todos.schedules.description_placeholder') }}"
                              rows="3"></textarea>
                </div>
            </div>

            <!-- Frequency Configuration -->
            <div class="form-section">
                <h3 class="section-title">{{ __('todos.schedules.frequency_configuration') }}</h3>
                <p class="section-description">{{ __('todos.schedules.frequency_description') }}</p>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">{{ __('todos.schedules.frequency_type') }}</label>
                        <select class="form-select" x-model="form.frequency_type" @change="updateFrequencyOptions()" required>
                            @foreach($frequencies as $key => $value)
                                <option value="{{ $key }}">{{ __('todos.schedules.' . $key) }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label required">{{ __('todos.schedules.frequency_value') }}</label>
                        <div class="frequency-input-group">
                            <input type="number" 
                                   class="form-input" 
                                   x-model="form.frequency_value"
                                   min="1" 
                                   :max="getMaxFrequencyValue()"
                                   required>
                            <span class="frequency-unit" x-text="getFrequencyUnit()"></span>
                        </div>
                        <div class="form-help" x-text="getFrequencyHelp()"></div>
                    </div>
                </div>
                
                <!-- Specific Time -->
                <div class="form-group" x-show="form.frequency_type !== 'hourly'">
                    <label class="form-label">{{ __('todos.schedules.specific_time') }}</label>
                    <input type="time" 
                           class="form-input" 
                           x-model="form.specific_time">
                    <div class="form-help">{{ __('todos.schedules.specific_time_help') }}</div>
                </div>
                
                <!-- Days of Week (for weekly) -->
                <div class="form-group" x-show="form.frequency_type === 'weekly'">
                    <label class="form-label">{{ __('todos.schedules.days_of_week') }}</label>
                    <div class="days-selector">
                        <label class="day-checkbox" x-for="(day, index) in daysOfWeek" :key="index">
                            <input type="checkbox" 
                                   :value="index"
                                   x-model="form.days_of_week">
                            <span class="day-label" x-text="day"></span>
                        </label>
                    </div>
                    <div class="form-help">{{ __('todos.schedules.days_of_week_help') }}</div>
                </div>
                
                <!-- Days of Month (for monthly) -->
                <div class="form-group" x-show="form.frequency_type === 'monthly'">
                    <label class="form-label">{{ __('todos.schedules.days_of_month') }}</label>
                    <div class="month-options">
                        <label class="month-option">
                            <input type="radio" 
                                   name="month_type" 
                                   value="specific_dates"
                                   x-model="monthType"
                                   @change="form.days_of_month = []">
                            <span>{{ __('todos.schedules.specific_dates') }}</span>
                        </label>
                        <label class="month-option">
                            <input type="radio" 
                                   name="month_type" 
                                   value="week_day"
                                   x-model="monthType"
                                   @change="form.days_of_month = []">
                            <span>{{ __('todos.schedules.week_day') }}</span>
                        </label>
                    </div>
                    
                    <!-- Specific Dates -->
                    <div x-show="monthType === 'specific_dates'" class="dates-selector">
                        <div class="dates-grid">
                            <template x-for="date in Array.from({length: 31}, (_, i) => i + 1)" :key="date">
                                <label class="date-checkbox">
                                    <input type="checkbox" 
                                           :value="date"
                                           x-model="form.days_of_month">
                                    <span class="date-label" x-text="date"></span>
                                </label>
                            </template>
                        </div>
                    </div>
                    
                    <!-- Week Day -->
                    <div x-show="monthType === 'week_day'" class="week-day-selector">
                        <div class="form-row">
                            <div class="form-group">
                                <select class="form-select" x-model="weekOccurrence">
                                    <option value="1">{{ __('todos.schedules.first') }}</option>
                                    <option value="2">{{ __('todos.schedules.second') }}</option>
                                    <option value="3">{{ __('todos.schedules.third') }}</option>
                                    <option value="4">{{ __('todos.schedules.fourth') }}</option>
                                    <option value="-1">{{ __('todos.schedules.last') }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <select class="form-select" x-model="weekDay">
                                    <option value="0">{{ __('todos.schedules.sunday') }}</option>
                                    <option value="1">{{ __('todos.schedules.monday') }}</option>
                                    <option value="2">{{ __('todos.schedules.tuesday') }}</option>
                                    <option value="3">{{ __('todos.schedules.wednesday') }}</option>
                                    <option value="4">{{ __('todos.schedules.thursday') }}</option>
                                    <option value="5">{{ __('todos.schedules.friday') }}</option>
                                    <option value="6">{{ __('todos.schedules.saturday') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Date Range -->
            <div class="form-section">
                <h3 class="section-title">{{ __('todos.schedules.date_range') }}</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">{{ __('todos.schedules.start_date') }}</label>
                        <input type="date" 
                               class="form-input" 
                               x-model="form.start_date"
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">{{ __('todos.schedules.end_date') }}</label>
                        <input type="date" 
                               class="form-input" 
                               x-model="form.end_date"
                               :min="form.start_date">
                        <div class="form-help">{{ __('todos.schedules.end_date_help') }}</div>
                    </div>
                </div>
            </div>

            <!-- Template & Assignment -->
            <div class="form-section">
                <h3 class="section-title">{{ __('todos.schedules.template_assignment') }}</h3>
                
                <div class="form-group">
                    <label class="form-label">{{ __('todos.schedules.template') }}</label>
                    <select class="form-select" x-model="form.template_id">
                        <option value="">{{ __('todos.schedules.no_template') }}</option>
                        @foreach($templates as $template)
                            <option value="{{ $template['id'] }}">{{ $template['name'] }}</option>
                        @endforeach
                    </select>
                    <div class="form-help">{{ __('todos.schedules.template_help') }}</div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">{{ __('todos.schedules.assigned_staff') }}</label>
                    <div class="staff-selector">
                        @foreach($staff as $member)
                        <label class="staff-checkbox">
                            <input type="checkbox" 
                                   value="{{ $member['id'] }}"
                                   x-model="form.assigned_staff">
                            <span class="staff-info">
                                <span class="staff-name">{{ $member['name'] }}</span>
                                <span class="staff-role">{{ $member['role'] }}</span>
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-checkbox-label">
                        <input type="checkbox" 
                               class="form-checkbox" 
                               x-model="form.auto_assign">
                        <span class="checkbox-text">{{ __('todos.schedules.auto_assign') }}</span>
                        <span class="checkbox-description">{{ __('todos.schedules.auto_assign_description') }}</span>
                    </label>
                </div>
            </div>

            <!-- Schedule Status -->
            <div class="form-section">
                <h3 class="section-title">{{ __('todos.schedules.schedule_status') }}</h3>
                
                <div class="form-group">
                    <label class="form-checkbox-label">
                        <input type="checkbox" 
                               class="form-checkbox" 
                               x-model="form.is_active">
                        <span class="checkbox-text">{{ __('todos.schedules.activate_immediately') }}</span>
                        <span class="checkbox-description">{{ __('todos.schedules.activate_schedule_description') }}</span>
                    </label>
                </div>
            </div>

            <!-- Schedule Preview -->
            <div class="form-section" x-show="form.name && form.frequency_type && form.frequency_value">
                <h3 class="section-title">{{ __('todos.schedules.schedule_preview') }}</h3>
                <div class="schedule-preview">
                    <div class="preview-item">
                        <span class="preview-label">{{ __('todos.schedules.schedule_summary') }}:</span>
                        <span class="preview-value" x-text="getScheduleSummary()"></span>
                    </div>
                    <div class="preview-item">
                        <span class="preview-label">{{ __('todos.schedules.next_execution') }}:</span>
                        <span class="preview-value" x-text="getNextExecution()"></span>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="button" 
                        class="btn btn-secondary"
                        @click="cancelForm()"
                        :disabled="isSubmitting">
                    {{ __('todos.common.cancel') }}
                </button>
                
                <button type="submit" 
                        class="btn btn-primary"
                        :disabled="isSubmitting">
                    <span x-show="!isSubmitting">{{ __('todos.schedules.save_schedule') }}</span>
                    <span x-show="isSubmitting" class="loading-text">
                        <svg class="loading-spinner" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        {{ __('todos.schedules.saving') }}
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('styles')
@vite('resources/css/admin/todos/schedules.css')
@endpush

@push('scripts')
@vite('resources/js/admin/todos/schedules.js')
@endpush
