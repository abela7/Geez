<!-- List View -->
<div x-show="currentView === 'list'" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-y-4"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     id="list-view"
     role="tabpanel">
    
    <!-- List Filters & Search -->
    <div class="list-filters">
        <div class="list-search">
            <label for="task-search" class="sr-only">{{ __('staff.tasks.search_tasks') }}</label>
            <div class="search-input-wrapper">
                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input id="task-search" 
                       type="text" 
                       x-model="searchQuery" 
                       @input="searchTasks()"
                       class="search-input" 
                       placeholder="{{ __('staff.tasks.search_placeholder') }}">
            </div>
        </div>

        <div class="list-filter-controls">
            <select x-model="filters.status" @change="applyFilters()" class="filter-select">
                <option value="">{{ __('staff.tasks.all_statuses') }}</option>
                <option value="todo">{{ __('staff.tasks.status_todo') }}</option>
                <option value="in_progress">{{ __('staff.tasks.status_in_progress') }}</option>
                <option value="review">{{ __('staff.tasks.status_review') }}</option>
                <option value="completed">{{ __('staff.tasks.status_completed') }}</option>
            </select>

            <select x-model="filters.priority" @change="applyFilters()" class="filter-select">
                <option value="">{{ __('staff.tasks.all_priorities') }}</option>
                <option value="low">{{ __('staff.tasks.priority_low') }}</option>
                <option value="medium">{{ __('staff.tasks.priority_medium') }}</option>
                <option value="high">{{ __('staff.tasks.priority_high') }}</option>
                <option value="urgent">{{ __('staff.tasks.priority_urgent') }}</option>
            </select>

            <select x-model="sortBy" @change="sortTasks()" class="filter-select">
                <option value="created_at">{{ __('staff.tasks.sort_created') }}</option>
                <option value="due_date">{{ __('staff.tasks.sort_due_date') }}</option>
                <option value="priority">{{ __('staff.tasks.sort_priority') }}</option>
                <option value="title">{{ __('staff.tasks.sort_title') }}</option>
            </select>

            <button @click="toggleSortOrder()" class="sort-toggle-btn" :class="{ 'desc': sortOrder === 'desc' }">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Tasks Table -->
    <div class="tasks-table-container">
        <table class="tasks-table">
            <thead>
                <tr>
                    <th class="table-header table-header--checkbox">
                        <input type="checkbox" 
                               @change="toggleSelectAll()"
                               :checked="selectedTasks.length === filteredTasks.length && filteredTasks.length > 0"
                               class="table-checkbox"
                               aria-label="{{ __('staff.tasks.select_all') }}">
                    </th>
                    <th class="table-header">{{ __('staff.tasks.task_title') }}</th>
                    <th class="table-header">{{ __('staff.tasks.assignee') }}</th>
                    <th class="table-header">{{ __('staff.tasks.priority') }}</th>
                    <th class="table-header">{{ __('staff.tasks.status') }}</th>
                    <th class="table-header">{{ __('staff.tasks.due_date') }}</th>
                    <th class="table-header">{{ __('staff.tasks.progress') }}</th>
                    <th class="table-header table-header--actions">{{ __('staff.tasks.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="task in filteredTasks" :key="task.id">
                    <tr class="table-row" :class="{ 'selected': selectedTasks.includes(task.id) }">
                        <td class="table-cell table-cell--checkbox">
                            <input type="checkbox" 
                                   :value="task.id"
                                   @change="toggleTaskSelection(task.id)"
                                   :checked="selectedTasks.includes(task.id)"
                                   class="table-checkbox"
                                   :aria-label="`Select ${task.title}`">
                        </td>
                        <td class="table-cell">
                            <div class="task-title-cell">
                                <h4 class="task-title" x-text="task.title"></h4>
                                <p class="task-description" x-text="task.description"></p>
                            </div>
                        </td>
                        <td class="table-cell">
                            <div class="assignee-cell">
                                <img :src="task.assignee_avatar" :alt="task.assignee" class="assignee-avatar">
                                <span x-text="task.assignee"></span>
                            </div>
                        </td>
                        <td class="table-cell">
                            <span class="priority-badge" :class="`priority-${task.priority}`" x-text="task.priority"></span>
                        </td>
                        <td class="table-cell">
                            <span class="status-badge" :class="`status-${task.status}`" x-text="task.status"></span>
                        </td>
                        <td class="table-cell">
                            <span class="due-date" :class="{ 'overdue': task.is_overdue }" x-text="task.due_date"></span>
                        </td>
                        <td class="table-cell">
                            <div class="progress-cell">
                                <div class="progress-bar">
                                    <div class="progress-fill" :style="`width: ${task.progress}%`"></div>
                                </div>
                                <span class="progress-text" x-text="`${task.progress}%`"></span>
                            </div>
                        </td>
                        <td class="table-cell table-cell--actions">
                            <div class="table-actions">
                                <button @click="viewTask(task)" class="table-action" aria-label="{{ __('staff.tasks.view_task') }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                                <button @click="editTask(task)" class="table-action" aria-label="{{ __('staff.tasks.edit_task') }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button @click="deleteTask(task)" class="table-action table-action--danger" aria-label="{{ __('staff.tasks.delete_task') }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>

        <!-- Empty State -->
        <div x-show="filteredTasks.length === 0" class="empty-state">
            <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <h3 class="empty-state-title">{{ __('staff.tasks.no_tasks_found') }}</h3>
            <p class="empty-state-description">{{ __('staff.tasks.no_tasks_description') }}</p>
            <button @click="openTaskModal()" class="btn btn-primary">
                {{ __('staff.tasks.create_first_task') }}
            </button>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div x-show="selectedTasks.length > 0" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-4"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         class="bulk-actions">
        <div class="bulk-actions-content">
            <span class="bulk-actions-count" x-text="`${selectedTasks.length} ${selectedTasks.length === 1 ? '{{ __('staff.tasks.task_selected') }}' : '{{ __('staff.tasks.tasks_selected') }}'}`"></span>
            <div class="bulk-actions-buttons">
                <button @click="bulkUpdateStatus('completed')" class="bulk-action-btn bulk-action-btn--success">
                    {{ __('staff.tasks.mark_completed') }}
                </button>
                <button @click="bulkUpdatePriority('high')" class="bulk-action-btn bulk-action-btn--warning">
                    {{ __('staff.tasks.mark_high_priority') }}
                </button>
                <button @click="bulkAssignTasks()" class="bulk-action-btn bulk-action-btn--info">
                    {{ __('staff.tasks.reassign') }}
                </button>
                <button @click="bulkDeleteTasks()" class="bulk-action-btn bulk-action-btn--danger">
                    {{ __('staff.tasks.delete_selected') }}
                </button>
            </div>
        </div>
    </div>
</div>
