@extends('layouts.admin')

@section('title', __('admin.shifts.templates.edit_template') . ': ' . $template->name)

@push('styles')
@vite(['resources/css/admin/shifts/templates.css'])
@endpush

@push('scripts')
@vite(['resources/js/admin/shifts/templates.js'])
@endpush

@section('content')
<div class="template-edit-page" x-data="templateEditData({{ $template->id }}, @js($existingAssignments))">
    <!-- Page Header -->
    <div class="page-header-modern">
        <div class="page-header-content">
            <div class="page-header-left">
                <div class="page-title-section">
                    <h1 class="page-title">
                        <svg class="page-title-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        {{ __('admin.shifts.templates.edit_template') }}
                    </h1>
                    <p class="page-description">{{ __('admin.shifts.templates.edit_template_desc', ['name' => $template->name]) }}</p>
                </div>
            </div>
            <div class="page-header-right">
                <div class="header-actions">
                    <a href="{{ route('admin.shifts.shifts.templates.show', $template->id) }}" class="btn btn-secondary">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        {{ __('admin.shifts.templates.view_template') }}
                    </a>
                    <a href="{{ route('admin.shifts.shifts.templates.index') }}" class="btn btn-secondary">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        {{ __('admin.shifts.templates.back_to_templates') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <form @submit.prevent="submitTemplate()" class="template-edit-form">
        <!-- Template Basic Info -->
        <div class="form-section">
            <div class="form-section-header">
                <h3 class="section-title">{{ __('admin.shifts.templates.basic_info') }}</h3>
                <p class="section-description">{{ __('admin.shifts.templates.basic_info_desc') }}</p>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="name" class="form-label required">{{ __('admin.shifts.templates.template_name') }}</label>
                    <input type="text"
                           id="name"
                           x-model="templateData.name"
                           required
                           class="form-input"
                           placeholder="{{ __('admin.shifts.templates.template_name_placeholder') }}">
                    <div class="form-help">{{ __('admin.shifts.templates.template_name_help') }}</div>
                </div>

                <div class="form-group">
                    <label for="type" class="form-label required">{{ __('admin.shifts.templates.template_type') }}</label>
                    <select id="type" x-model="templateData.type" required class="form-select">
                        <option value="">{{ __('admin.shifts.templates.select_type') }}</option>
                        @foreach($templateTypeOptions as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <div class="form-help">{{ __('admin.shifts.templates.template_type_help') }}</div>
                </div>

                <div class="form-group full-width">
                    <label for="description" class="form-label">{{ __('admin.shifts.templates.description') }}</label>
                    <textarea id="description"
                              x-model="templateData.description"
                              rows="3"
                              class="form-textarea"
                              placeholder="{{ __('admin.shifts.templates.description_placeholder') }}"></textarea>
                    <div class="form-help">{{ __('admin.shifts.templates.description_help') }}</div>
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('admin.shifts.templates.settings') }}</label>
                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" x-model="templateData.is_active" class="checkbox-input">
                            <span class="checkbox-mark"></span>
                            {{ __('admin.shifts.templates.active_template') }}
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" x-model="templateData.is_default" class="checkbox-input">
                            <span class="checkbox-mark"></span>
                            {{ __('admin.shifts.templates.default_template') }}
                        </label>
                    </div>
                    <div class="form-help">{{ __('admin.shifts.templates.settings_help') }}</div>
                </div>
            </div>
        </div>

        <!-- Shift Assignments -->
        <div class="form-section">
            <div class="form-section-header">
                <h3 class="section-title">{{ __('admin.shifts.templates.shift_assignments') }}</h3>
                <p class="section-description">{{ __('admin.shifts.templates.shift_assignments_desc') }}</p>
            </div>

            <!-- Assignment Builder -->
            <div class="assignment-builder">
                <!-- Days of Week Tabs -->
                <div class="days-tabs">
                    @foreach($daysOfWeek as $dayNum => $dayName)
                        <button type="button"
                                class="day-tab"
                                :class="{ 'active': activeDay === {{ $dayNum }} }"
                                @click="activeDay = {{ $dayNum }}">
                            <span class="day-name">{{ $dayName }}</span>
                            <span class="assignment-count"
                                  x-text="getAssignmentsForDay({{ $dayNum }}).length"></span>
                        </button>
                    @endforeach
                </div>

                <!-- Current Day Assignments -->
                <div class="day-assignments">
                    <div class="assignments-header">
                        <h4 class="assignments-title" x-text="getDayName(activeDay) + ' Assignments'"></h4>
                        <button type="button" @click="showAddAssignmentModal = true" class="btn btn-primary btn-sm">
                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            {{ __('admin.shifts.templates.add_assignment') }}
                        </button>
                    </div>

                    <!-- Assignments List -->
                    <div class="assignments-list">
                        <template x-for="(assignment, index) in getAssignmentsForDay(activeDay)" :key="index">
                            <div class="assignment-item">
                                <div class="assignment-content">
                                    <div class="assignment-shift">
                                        <span class="shift-name" x-text="getShiftName(assignment.staff_shift_id)"></span>
                                        <span class="shift-time" x-text="getShiftTime(assignment.staff_shift_id)"></span>
                                    </div>
                                    <div class="assignment-staff">
                                        <span class="staff-name" x-text="getStaffName(assignment.staff_id)"></span>
                                        <span class="staff-type" x-text="getStaffType(assignment.staff_id)"></span>
                                    </div>
                                    <div class="assignment-status">
                                        <span class="status-badge" :class="'status-' + assignment.status" x-text="assignment.status"></span>
                                    </div>
                                </div>
                                <div class="assignment-actions">
                                    <button type="button" @click="editAssignment(activeDay, index)" class="btn-icon">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button type="button" @click="removeAssignment(activeDay, index)" class="btn-icon btn-danger">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>

                        <!-- Empty State -->
                        <div class="empty-assignments" x-show="getAssignmentsForDay(activeDay).length === 0">
                            <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-5.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="empty-text">{{ __('admin.shifts.templates.no_assignments') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Summary Stats -->
                <div class="assignment-summary">
                    <div class="summary-item">
                        <span class="summary-label">{{ __('admin.shifts.templates.total_assignments') }}</span>
                        <span class="summary-value" x-text="getTotalAssignments()"></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">{{ __('admin.shifts.templates.unique_staff') }}</span>
                        <span class="summary-value" x-text="getUniqueStaffCount()"></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">{{ __('admin.shifts.templates.unique_shifts') }}</span>
                        <span class="summary-value" x-text="getUniqueShiftsCount()"></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">{{ __('admin.shifts.templates.estimated_cost') }}</span>
                        <span class="summary-value" x-text="'£' + calculateEstimatedCost()"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <div class="form-actions-left">
                <button type="button"
                        @click="duplicateTemplate({{ $template->id }}, '{{ addslashes($template->name) }}')"
                        class="btn btn-outline">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    {{ __('admin.shifts.templates.duplicate_template') }}
                </button>
                <button type="button"
                        @click="clearAllAssignments()"
                        class="btn btn-outline btn-danger">
                    {{ __('admin.shifts.templates.clear_all') }}
                </button>
            </div>
            <div class="form-actions-right">
                <a href="{{ route('admin.shifts.templates.index') }}" class="btn btn-secondary">
                    {{ __('admin.shifts.templates.cancel') }}
                </a>
                <button type="submit"
                        :disabled="!isFormValid() || isSubmitting"
                        class="btn btn-primary">
                    <span x-show="!isSubmitting">{{ __('admin.shifts.templates.update_template') }}</span>
                    <span x-show="isSubmitting" class="loading-spinner">{{ __('admin.shifts.templates.updating') }}</span>
                </button>
            </div>
        </div>
    </form>

    <!-- Add/Edit Assignment Modal -->
    <div class="modal-overlay" x-show="showAddAssignmentModal" x-transition>
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" x-text="editingAssignment !== null ? 'Edit Assignment' : 'Add Assignment'"></h3>
                <button @click="closeAssignmentModal()" class="modal-close">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form @submit.prevent="saveAssignment()" class="modal-form">
                <div class="form-group">
                    <label class="form-label required">{{ __('admin.shifts.templates.select_shift') }}</label>
                    <select x-model="assignmentForm.staff_shift_id" required class="form-select">
                        <option value="">{{ __('admin.shifts.templates.choose_shift') }}</option>
                        @foreach($shifts as $department => $departmentShifts)
                            <optgroup label="{{ $department }}">
                                @foreach($departmentShifts as $shift)
                                    <option value="{{ $shift->id }}">
                                        {{ $shift->name }} ({{ $shift->start_time }}-{{ $shift->end_time }})
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label required">{{ __('admin.shifts.templates.select_staff') }}</label>
                    <select x-model="assignmentForm.staff_id" required class="form-select">
                        <option value="">{{ __('admin.shifts.templates.choose_staff') }}</option>
                        @foreach($staff as $staffMember)
                            <option value="{{ $staffMember->id }}">
                                {{ $staffMember->full_name }} - {{ $staffMember->staffType?->display_name ?? 'No Type' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label required">{{ __('admin.shifts.templates.assignment_status') }}</label>
                    <select x-model="assignmentForm.status" required class="form-select">
                        <option value="scheduled">{{ __('admin.shifts.templates.scheduled') }}</option>
                        <option value="confirmed">{{ __('admin.shifts.templates.confirmed') }}</option>
                        <option value="optional">{{ __('admin.shifts.templates.optional') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('admin.shifts.templates.notes') }}</label>
                    <textarea x-model="assignmentForm.notes"
                              rows="2"
                              class="form-textarea"
                              placeholder="{{ __('admin.shifts.templates.notes_placeholder') }}"></textarea>
                </div>

                <div class="modal-actions">
                    <button type="button" @click="closeAssignmentModal()" class="btn btn-secondary">
                        {{ __('admin.shifts.templates.cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <span x-text="editingAssignment !== null ? 'Update Assignment' : 'Add Assignment'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
