<!-- Kanban View -->
<div x-show="currentView === 'kanban'" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-y-4"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     id="kanban-view"
     role="tabpanel">
    
    <!-- Kanban Filters -->
    <div class="kanban-filters">
        <div class="filter-group">
            <label for="assignee-filter" class="filter-label">{{ __('staff.tasks.filter_assignee') }}</label>
            <select id="assignee-filter" x-model="filters.assignee" @change="applyFilters()" class="filter-select">
                <option value="">{{ __('staff.tasks.all_assignees') }}</option>
                <template x-for="assignee in assignees" :key="assignee.id">
                    <option :value="assignee.id" x-text="assignee.name"></option>
                </template>
            </select>
        </div>

        <div class="filter-group">
            <label for="priority-filter" class="filter-label">{{ __('staff.tasks.filter_priority') }}</label>
            <select id="priority-filter" x-model="filters.priority" @change="applyFilters()" class="filter-select">
                <option value="">{{ __('staff.tasks.all_priorities') }}</option>
                <option value="low">{{ __('staff.tasks.priority_low') }}</option>
                <option value="medium">{{ __('staff.tasks.priority_medium') }}</option>
                <option value="high">{{ __('staff.tasks.priority_high') }}</option>
                <option value="urgent">{{ __('staff.tasks.priority_urgent') }}</option>
            </select>
        </div>

        <div class="filter-group">
            <label for="category-filter" class="filter-label">{{ __('staff.tasks.filter_category') }}</label>
            <select id="category-filter" x-model="filters.category" @change="applyFilters()" class="filter-select">
                <option value="">{{ __('staff.tasks.all_categories') }}</option>
                <option value="kitchen">{{ __('staff.tasks.category_kitchen') }}</option>
                <option value="service">{{ __('staff.tasks.category_service') }}</option>
                <option value="cleaning">{{ __('staff.tasks.category_cleaning') }}</option>
                <option value="maintenance">{{ __('staff.tasks.category_maintenance') }}</option>
                <option value="admin">{{ __('staff.tasks.category_admin') }}</option>
            </select>
        </div>

        <button @click="clearFilters()" class="btn btn-secondary btn-sm">
            {{ __('staff.tasks.clear_filters') }}
        </button>
    </div>

    <!-- Kanban Board -->
    <div class="kanban-board">
        <div class="kanban-column" data-status="todo">
            <div class="kanban-column-header">
                <h3 class="kanban-column-title">
                    <span class="kanban-status-indicator kanban-status-indicator--todo"></span>
                    {{ __('staff.tasks.status_todo') }}
                    <span class="kanban-task-count" x-text="getTaskCount('todo')">0</span>
                </h3>
            </div>
            <div class="kanban-column-body" 
                 x-ref="todoColumn"
                 @drop="handleDrop($event, 'todo')"
                 @dragover.prevent
                 @dragenter.prevent>
                <template x-for="task in getTasksByStatus('todo')" :key="task.id">
                    <div class="kanban-task" 
                         :draggable="true"
                         @dragstart="handleDragStart($event, task)"
                         @click="viewTask(task)">
                        <div class="kanban-task-header">
                            <span class="kanban-task-priority" :class="`priority-${task.priority}`"></span>
                            <div class="kanban-task-actions">
                                <button @click.stop="editTask(task)" class="kanban-task-action" aria-label="{{ __('staff.tasks.edit_task') }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <h4 class="kanban-task-title" x-text="task.title"></h4>
                        <p class="kanban-task-description" x-text="task.description"></p>
                        <div class="kanban-task-footer">
                            <div class="kanban-task-assignee">
                                <img :src="task.assignee_avatar" :alt="task.assignee" class="kanban-assignee-avatar">
                                <span x-text="task.assignee"></span>
                            </div>
                            <span class="kanban-task-due" x-text="task.due_date"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="kanban-column" data-status="in_progress">
            <div class="kanban-column-header">
                <h3 class="kanban-column-title">
                    <span class="kanban-status-indicator kanban-status-indicator--progress"></span>
                    {{ __('staff.tasks.status_in_progress') }}
                    <span class="kanban-task-count" x-text="getTaskCount('in_progress')">0</span>
                </h3>
            </div>
            <div class="kanban-column-body" 
                 x-ref="progressColumn"
                 @drop="handleDrop($event, 'in_progress')"
                 @dragover.prevent
                 @dragenter.prevent>
                <template x-for="task in getTasksByStatus('in_progress')" :key="task.id">
                    <div class="kanban-task" 
                         :draggable="true"
                         @dragstart="handleDragStart($event, task)"
                         @click="viewTask(task)">
                        <div class="kanban-task-header">
                            <span class="kanban-task-priority" :class="`priority-${task.priority}`"></span>
                            <div class="kanban-task-actions">
                                <button @click.stop="editTask(task)" class="kanban-task-action" aria-label="{{ __('staff.tasks.edit_task') }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <h4 class="kanban-task-title" x-text="task.title"></h4>
                        <p class="kanban-task-description" x-text="task.description"></p>
                        <div class="kanban-task-footer">
                            <div class="kanban-task-assignee">
                                <img :src="task.assignee_avatar" :alt="task.assignee" class="kanban-assignee-avatar">
                                <span x-text="task.assignee"></span>
                            </div>
                            <span class="kanban-task-due" x-text="task.due_date"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="kanban-column" data-status="review">
            <div class="kanban-column-header">
                <h3 class="kanban-column-title">
                    <span class="kanban-status-indicator kanban-status-indicator--review"></span>
                    {{ __('staff.tasks.status_review') }}
                    <span class="kanban-task-count" x-text="getTaskCount('review')">0</span>
                </h3>
            </div>
            <div class="kanban-column-body" 
                 x-ref="reviewColumn"
                 @drop="handleDrop($event, 'review')"
                 @dragover.prevent
                 @dragenter.prevent>
                <template x-for="task in getTasksByStatus('review')" :key="task.id">
                    <div class="kanban-task" 
                         :draggable="true"
                         @dragstart="handleDragStart($event, task)"
                         @click="viewTask(task)">
                        <div class="kanban-task-header">
                            <span class="kanban-task-priority" :class="`priority-${task.priority}`"></span>
                            <div class="kanban-task-actions">
                                <button @click.stop="editTask(task)" class="kanban-task-action" aria-label="{{ __('staff.tasks.edit_task') }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <h4 class="kanban-task-title" x-text="task.title"></h4>
                        <p class="kanban-task-description" x-text="task.description"></p>
                        <div class="kanban-task-footer">
                            <div class="kanban-task-assignee">
                                <img :src="task.assignee_avatar" :alt="task.assignee" class="kanban-assignee-avatar">
                                <span x-text="task.assignee"></span>
                            </div>
                            <span class="kanban-task-due" x-text="task.due_date"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="kanban-column" data-status="completed">
            <div class="kanban-column-header">
                <h3 class="kanban-column-title">
                    <span class="kanban-status-indicator kanban-status-indicator--completed"></span>
                    {{ __('staff.tasks.status_completed') }}
                    <span class="kanban-task-count" x-text="getTaskCount('completed')">0</span>
                </h3>
            </div>
            <div class="kanban-column-body" 
                 x-ref="completedColumn"
                 @drop="handleDrop($event, 'completed')"
                 @dragover.prevent
                 @dragenter.prevent>
                <template x-for="task in getTasksByStatus('completed')" :key="task.id">
                    <div class="kanban-task" 
                         :draggable="true"
                         @dragstart="handleDragStart($event, task)"
                         @click="viewTask(task)">
                        <div class="kanban-task-header">
                            <span class="kanban-task-priority" :class="`priority-${task.priority}`"></span>
                            <div class="kanban-task-actions">
                                <button @click.stop="editTask(task)" class="kanban-task-action" aria-label="{{ __('staff.tasks.edit_task') }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <h4 class="kanban-task-title" x-text="task.title"></h4>
                        <p class="kanban-task-description" x-text="task.description"></p>
                        <div class="kanban-task-footer">
                            <div class="kanban-task-assignee">
                                <img :src="task.assignee_avatar" :alt="task.assignee" class="kanban-assignee-avatar">
                                <span x-text="task.assignee"></span>
                            </div>
                            <span class="kanban-task-due" x-text="task.due_date"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
