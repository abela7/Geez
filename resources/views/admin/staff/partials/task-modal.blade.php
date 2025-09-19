<div class="modal-header">
    <h3 class="modal-title" x-text="editingTask ? '{{ __('staff.tasks.edit') }} ' + taskForm.title : '{{ __('staff.tasks.create_task') }}'"></h3>
    <button @click="closeTaskModal()" class="modal-close-btn">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>

<form @submit.prevent="saveTask()" class="modal-form">
    <div class="form-grid">
        <!-- Task Title -->
        <div class="form-group form-group-full">
            <label for="task-title" class="form-label">{{ __('staff.tasks.task_title') }} <span class="required">*</span></label>
            <input type="text" 
                   id="task-title"
                   x-model="taskForm.title"
                   class="form-input"
                   placeholder="{{ __('staff.tasks.task_title_placeholder') }}"
                   required>
        </div>

        <!-- Task Description -->
        <div class="form-group form-group-full">
            <label for="task-description" class="form-label">{{ __('staff.tasks.task_description') }}</label>
            <textarea id="task-description"
                      x-model="taskForm.description"
                      class="form-textarea"
                      rows="3"
                      placeholder="{{ __('staff.tasks.task_description_placeholder') }}"></textarea>
        </div>

        <!-- Task Type -->
        <div class="form-group">
            <label for="task-type" class="form-label">{{ __('staff.tasks.task_type') }} <span class="required">*</span></label>
            <select id="task-type" x-model="taskForm.task_type" class="form-select" required>
                <option value="">{{ __('common.select') }}</option>
                <option value="daily">{{ __('staff.tasks.daily') }}</option>
                <option value="weekly">{{ __('staff.tasks.weekly') }}</option>
                <option value="monthly">{{ __('staff.tasks.monthly') }}</option>
                <option value="project">{{ __('staff.tasks.project') }}</option>
                <option value="maintenance">{{ __('staff.tasks.maintenance') }}</option>
            </select>
        </div>

        <!-- Priority -->
        <div class="form-group">
            <label for="task-priority" class="form-label">{{ __('staff.tasks.priority') }} <span class="required">*</span></label>
            <select id="task-priority" x-model="taskForm.priority" class="form-select" required>
                <option value="">{{ __('common.select') }}</option>
                <option value="low">{{ __('staff.tasks.low') }}</option>
                <option value="medium">{{ __('staff.tasks.medium') }}</option>
                <option value="high">{{ __('staff.tasks.high') }}</option>
                <option value="urgent">{{ __('staff.tasks.urgent') }}</option>
            </select>
        </div>

        <!-- Category -->
        <div class="form-group">
            <label for="task-category" class="form-label">{{ __('staff.tasks.category') }} <span class="required">*</span></label>
            <select id="task-category" x-model="taskForm.category" class="form-select" required>
                <option value="">{{ __('common.select') }}</option>
                <option value="kitchen">{{ __('staff.tasks.kitchen') }}</option>
                <option value="service">{{ __('staff.tasks.service') }}</option>
                <option value="cleaning">{{ __('staff.tasks.cleaning') }}</option>
                <option value="administration">{{ __('staff.tasks.administration') }}</option>
                <option value="maintenance">{{ __('staff.tasks.maintenance_cat') }}</option>
                <option value="inventory">{{ __('staff.tasks.inventory') }}</option>
            </select>
        </div>

        <!-- Estimated Hours -->
        <div class="form-group">
            <label for="estimated-hours" class="form-label">{{ __('staff.tasks.estimated_hours') }}</label>
            <input type="number" 
                   id="estimated-hours"
                   x-model="taskForm.estimated_hours"
                   class="form-input"
                   min="0"
                   max="999.99"
                   step="0.25"
                   placeholder="0.00">
        </div>

        <!-- Template Options -->
        <div class="form-group form-group-full">
            <div class="form-checkbox-group">
                <label class="form-checkbox">
                    <input type="checkbox" x-model="taskForm.is_template">
                    <span class="checkbox-mark"></span>
                    <span class="checkbox-label">{{ __('staff.tasks.is_template') }}</span>
                </label>
            </div>
        </div>

        <!-- Template Name (shown only if is_template is checked) -->
        <div x-show="taskForm.is_template" class="form-group form-group-full">
            <label for="template-name" class="form-label">{{ __('staff.tasks.template_name') }}</label>
            <input type="text" 
                   id="template-name"
                   x-model="taskForm.template_name"
                   class="form-input"
                   placeholder="{{ __('staff.tasks.template_name') }}">
        </div>

        <!-- Approval Required -->
        <div class="form-group form-group-full">
            <div class="form-checkbox-group">
                <label class="form-checkbox">
                    <input type="checkbox" x-model="taskForm.requires_approval">
                    <span class="checkbox-mark"></span>
                    <span class="checkbox-label">{{ __('staff.tasks.requires_approval') }}</span>
                </label>
            </div>
        </div>

        <!-- Tags -->
        <div class="form-group form-group-full">
            <label for="task-tags" class="form-label">{{ __('staff.tasks.tags') }}</label>
            <input type="text" 
                   id="task-tags"
                   x-model="taskForm.tagsInput"
                   class="form-input"
                   placeholder="Enter tags separated by commas">
            <div class="form-help">{{ __('common.separate_with_commas') }}</div>
        </div>

        <!-- Active Status (only for editing) -->
        <div x-show="editingTask" class="form-group form-group-full">
            <div class="form-checkbox-group">
                <label class="form-checkbox">
                    <input type="checkbox" x-model="taskForm.is_active">
                    <span class="checkbox-mark"></span>
                    <span class="checkbox-label">{{ __('staff.tasks.is_active') }}</span>
                </label>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="modal-footer">
        <button type="button" @click="closeTaskModal()" class="btn btn-secondary">
            {{ __('common.cancel') }}
        </button>
        <button type="submit" class="btn btn-primary" :disabled="loading">
            <span x-show="loading" class="btn-spinner"></span>
            <span x-text="editingTask ? '{{ __('common.update') }}' : '{{ __('common.create') }}'"></span>
        </button>
    </div>
</form>
