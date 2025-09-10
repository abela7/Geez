<!-- Task Modal -->
<div x-show="showTaskModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="modal-overlay"
     @click="closeTaskModal()">
    
    <div class="modal-container" @click.stop>
        <div class="modal-header">
            <h2 class="modal-title" x-text="editingTask ? '{{ __('staff.tasks.edit_task') }}' : '{{ __('staff.tasks.create_task') }}'"></h2>
            <button @click="closeTaskModal()" class="modal-close" aria-label="{{ __('staff.tasks.close_modal') }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form @submit.prevent="saveTask()" class="modal-body">
            <div class="form-grid">
                <div class="form-group form-group--full">
                    <label for="task-title" class="form-label">{{ __('staff.tasks.task_title') }} <span class="required">*</span></label>
                    <input id="task-title" 
                           type="text" 
                           x-model="taskForm.title" 
                           class="form-input" 
                           placeholder="{{ __('staff.tasks.title_placeholder') }}"
                           required>
                </div>

                <div class="form-group form-group--full">
                    <label for="task-description" class="form-label">{{ __('staff.tasks.description') }}</label>
                    <textarea id="task-description" 
                              x-model="taskForm.description" 
                              class="form-textarea" 
                              rows="3"
                              placeholder="{{ __('staff.tasks.description_placeholder') }}"></textarea>
                </div>

                <div class="form-group">
                    <label for="task-assignee" class="form-label">{{ __('staff.tasks.assignee') }} <span class="required">*</span></label>
                    <select id="task-assignee" x-model="taskForm.assignee_id" class="form-select" required>
                        <option value="">{{ __('staff.tasks.select_assignee') }}</option>
                        <template x-for="assignee in assignees" :key="assignee.id">
                            <option :value="assignee.id" x-text="assignee.name"></option>
                        </template>
                    </select>
                </div>

                <div class="form-group">
                    <label for="task-priority" class="form-label">{{ __('staff.tasks.priority') }}</label>
                    <select id="task-priority" x-model="taskForm.priority" class="form-select">
                        <option value="low">{{ __('staff.tasks.priority_low') }}</option>
                        <option value="medium">{{ __('staff.tasks.priority_medium') }}</option>
                        <option value="high">{{ __('staff.tasks.priority_high') }}</option>
                        <option value="urgent">{{ __('staff.tasks.priority_urgent') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="task-category" class="form-label">{{ __('staff.tasks.category') }}</label>
                    <select id="task-category" x-model="taskForm.category" class="form-select">
                        <option value="kitchen">{{ __('staff.tasks.category_kitchen') }}</option>
                        <option value="service">{{ __('staff.tasks.category_service') }}</option>
                        <option value="cleaning">{{ __('staff.tasks.category_cleaning') }}</option>
                        <option value="maintenance">{{ __('staff.tasks.category_maintenance') }}</option>
                        <option value="admin">{{ __('staff.tasks.category_admin') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="task-due-date" class="form-label">{{ __('staff.tasks.due_date') }}</label>
                    <input id="task-due-date" 
                           type="datetime-local" 
                           x-model="taskForm.due_date" 
                           class="form-input">
                </div>

                <div class="form-group">
                    <label for="task-estimated-hours" class="form-label">{{ __('staff.tasks.estimated_hours') }}</label>
                    <input id="task-estimated-hours" 
                           type="number" 
                           x-model="taskForm.estimated_hours" 
                           class="form-input"
                           min="0.5"
                           step="0.5"
                           placeholder="2.0">
                </div>

                <div class="form-group">
                    <label for="task-status" class="form-label">{{ __('staff.tasks.status') }}</label>
                    <select id="task-status" x-model="taskForm.status" class="form-select">
                        <option value="todo">{{ __('staff.tasks.status_todo') }}</option>
                        <option value="in_progress">{{ __('staff.tasks.status_in_progress') }}</option>
                        <option value="review">{{ __('staff.tasks.status_review') }}</option>
                        <option value="completed">{{ __('staff.tasks.status_completed') }}</option>
                    </select>
                </div>

                <div class="form-group form-group--full">
                    <label class="form-label">{{ __('staff.tasks.task_options') }}</label>
                    <div class="form-checkboxes">
                        <label class="checkbox-label">
                            <input type="checkbox" x-model="taskForm.is_recurring" class="form-checkbox">
                            <span class="checkbox-text">{{ __('staff.tasks.recurring_task') }}</span>
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" x-model="taskForm.send_notifications" class="form-checkbox">
                            <span class="checkbox-text">{{ __('staff.tasks.send_notifications') }}</span>
                        </label>
                    </div>
                </div>

                <!-- Recurring Task Options -->
                <div x-show="taskForm.is_recurring" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 max-h-0"
                     x-transition:enter-end="opacity-100 max-h-96"
                     class="form-group form-group--full recurring-options">
                    <label for="task-recurrence" class="form-label">{{ __('staff.tasks.recurrence_pattern') }}</label>
                    <select id="task-recurrence" x-model="taskForm.recurrence_pattern" class="form-select">
                        <option value="daily">{{ __('staff.tasks.daily') }}</option>
                        <option value="weekly">{{ __('staff.tasks.weekly') }}</option>
                        <option value="monthly">{{ __('staff.tasks.monthly') }}</option>
                        <option value="custom">{{ __('staff.tasks.custom') }}</option>
                    </select>
                </div>

                <!-- Task Dependencies -->
                <div class="form-group form-group--full">
                    <label for="task-dependencies" class="form-label">{{ __('staff.tasks.dependencies') }}</label>
                    <select id="task-dependencies" x-model="taskForm.dependencies" class="form-select" multiple>
                        <template x-for="task in availableTasks" :key="task.id">
                            <option :value="task.id" x-text="task.title"></option>
                        </template>
                    </select>
                    <small class="form-help">{{ __('staff.tasks.dependencies_help') }}</small>
                </div>

                <!-- Attachments -->
                <div class="form-group form-group--full">
                    <label for="task-attachments" class="form-label">{{ __('staff.tasks.attachments') }}</label>
                    <div class="file-upload-area" 
                         @drop.prevent="handleFileDrop($event)"
                         @dragover.prevent
                         @dragenter.prevent>
                        <input id="task-attachments" 
                               type="file" 
                               multiple 
                               class="file-input"
                               @change="handleFileSelect($event)">
                        <div class="file-upload-content">
                            <svg class="file-upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <p class="file-upload-text">{{ __('staff.tasks.drag_files_or_click') }}</p>
                        </div>
                    </div>
                    
                    <!-- File List -->
                    <div x-show="taskForm.attachments && taskForm.attachments.length > 0" class="file-list">
                        <template x-for="(file, index) in taskForm.attachments" :key="index">
                            <div class="file-item">
                                <span class="file-name" x-text="file.name"></span>
                                <button type="button" @click="removeFile(index)" class="file-remove">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" @click="closeTaskModal()" class="btn btn-secondary">
                    {{ __('staff.tasks.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary" :disabled="!taskForm.title || !taskForm.assignee_id">
                    <span x-text="editingTask ? '{{ __('staff.tasks.update_task') }}' : '{{ __('staff.tasks.create_task') }}'"></span>
                </button>
            </div>
        </form>
    </div>
</div>
