<div class="modal-header">
    <h3 class="modal-title">{{ __('staff.tasks.assign_task') }}</h3>
    <button @click="closeAssignModal()" class="modal-close-btn">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>

<form @submit.prevent="saveAssignment()" class="modal-form">
    <!-- Task Info -->
    <div class="assignment-task-info">
        <div class="task-info-card">
            <h4 class="task-info-title" x-text="assigningTask?.title || 'Task'"></h4>
            <p class="task-info-description" x-text="assigningTask?.description || ''"></p>
            <div class="task-info-meta">
                <span class="task-type" x-text="assigningTask?.task_type || ''"></span>
                <span class="task-priority" x-text="assigningTask?.priority || ''"></span>
                <span class="task-category" x-text="assigningTask?.category || ''"></span>
            </div>
        </div>
    </div>

    <div class="form-grid">
        <!-- Staff Selection -->
        <div class="form-group form-group-full">
            <label for="assign-staff" class="form-label">{{ __('staff.tasks.select_staff') }} <span class="required">*</span></label>
            <div class="staff-selection">
                @foreach($staffMembers as $staff)
                    <label class="staff-option">
                        <input type="checkbox" 
                               x-model="assignmentForm.staff_ids" 
                               value="{{ $staff->id }}"
                               class="staff-checkbox">
                        <div class="staff-card">
                            <div class="staff-avatar">
                                {{ substr($staff->first_name, 0, 1) }}{{ substr($staff->last_name, 0, 1) }}
                            </div>
                            <div class="staff-info">
                                <div class="staff-name">{{ $staff->full_name }}</div>
                                <div class="staff-type">{{ $staff->staffType->display_name ?? 'No Type' }}</div>
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>
            <div class="form-help">{{ __('staff.tasks.staff_required') }}</div>
        </div>

        <!-- Due Date -->
        <div class="form-group">
            <label for="assign-due-date" class="form-label">{{ __('staff.tasks.due_date') }}</label>
            <input type="datetime-local" 
                   id="assign-due-date"
                   x-model="assignmentForm.due_date"
                   class="form-input"
                   :min="new Date().toISOString().slice(0, 16)">
        </div>

        <!-- Priority Override -->
        <div class="form-group">
            <label for="assign-priority" class="form-label">{{ __('staff.tasks.priority_override') }}</label>
            <select id="assign-priority" x-model="assignmentForm.priority_override" class="form-select">
                <option value="">{{ __('common.no_override') }}</option>
                <option value="low">{{ __('staff.tasks.low') }}</option>
                <option value="medium">{{ __('staff.tasks.medium') }}</option>
                <option value="high">{{ __('staff.tasks.high') }}</option>
                <option value="urgent">{{ __('staff.tasks.urgent') }}</option>
            </select>
        </div>

        <!-- Assignment Notes -->
        <div class="form-group form-group-full">
            <label for="assign-notes" class="form-label">{{ __('staff.tasks.assignment_notes') }}</label>
            <textarea id="assign-notes"
                      x-model="assignmentForm.notes"
                      class="form-textarea"
                      rows="3"
                      placeholder="{{ __('staff.tasks.assignment_notes_placeholder') }}"></textarea>
        </div>
    </div>

    <!-- Selected Staff Summary -->
    <div x-show="assignmentForm.staff_ids.length > 0" class="selected-staff-summary">
        <h4 class="summary-title">{{ __('common.selected') }} (<span x-text="assignmentForm.staff_ids.length"></span>)</h4>
        <div class="selected-staff-list">
            <template x-for="staffId in assignmentForm.staff_ids" :key="staffId">
                <div class="selected-staff-item">
                    <span x-text="getStaffName(staffId)"></span>
                    <button type="button" @click="removeStaff(staffId)" class="remove-staff-btn">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </template>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="modal-footer">
        <button type="button" @click="closeAssignModal()" class="btn btn-secondary">
            {{ __('common.cancel') }}
        </button>
        <button type="submit" 
                class="btn btn-primary" 
                :disabled="loading || assignmentForm.staff_ids.length === 0">
            <span x-show="loading" class="btn-spinner"></span>
            <span>{{ __('staff.tasks.assign') }}</span>
        </button>
    </div>
</form>
