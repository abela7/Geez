<!-- List View Filters -->
<div class="list-filters">
    <div class="list-search">
        <div class="search-input-wrapper">
            <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" 
                   x-model="searchQuery" 
                   placeholder="{{ __('staff.tasks.search_tasks') }}"
                   class="search-input">
        </div>
    </div>

    <div class="list-filters-row">
        <select x-model="filters.status" class="filter-select">
            <option value="">{{ __('staff.tasks.all_statuses') }}</option>
            <option value="assigned">{{ __('staff.tasks.assigned') }}</option>
            <option value="in_progress">{{ __('staff.tasks.in_progress') }}</option>
            <option value="completed">{{ __('staff.tasks.completed') }}</option>
            <option value="cancelled">{{ __('staff.tasks.cancelled') }}</option>
        </select>

        <select x-model="filters.priority" class="filter-select">
            <option value="">{{ __('staff.tasks.all_priorities') }}</option>
            <option value="low">{{ __('staff.tasks.low') }}</option>
            <option value="medium">{{ __('staff.tasks.medium') }}</option>
            <option value="high">{{ __('staff.tasks.high') }}</option>
            <option value="urgent">{{ __('staff.tasks.urgent') }}</option>
        </select>

        <select x-model="filters.category" class="filter-select">
            <option value="">{{ __('staff.tasks.all_categories') }}</option>
            <option value="kitchen">{{ __('staff.tasks.kitchen') }}</option>
            <option value="service">{{ __('staff.tasks.service') }}</option>
            <option value="cleaning">{{ __('staff.tasks.cleaning') }}</option>
            <option value="administration">{{ __('staff.tasks.administration') }}</option>
            <option value="maintenance">{{ __('staff.tasks.maintenance_cat') }}</option>
            <option value="inventory">{{ __('staff.tasks.inventory') }}</option>
        </select>
    </div>
</div>

<!-- Tasks Table -->
<div class="tasks-table-container">
    @if($assignments->count() > 0)
        <table class="tasks-table">
            <thead>
                <tr>
                    <th>{{ __('staff.tasks.task_title') }}</th>
                    <th>{{ __('common.assignee') }}</th>
                    <th>{{ __('staff.tasks.priority') }}</th>
                    <th>{{ __('common.status') }}</th>
                    <th>{{ __('staff.tasks.due_date') }}</th>
                    <th>{{ __('staff.tasks.progress') }}</th>
                    <th>{{ __('common.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assignments as $assignment)
                    <tr class="task-row">
                        <td class="task-info-cell">
                            <div class="task-info">
                                <h4 class="task-title">{{ $assignment->task->title ?? 'Untitled Task' }}</h4>
                                <p class="task-description">{{ Str::limit($assignment->task->description ?? '', 60) }}</p>
                                <div class="task-meta">
                                    <span class="task-type">{{ __('staff.tasks.' . ($assignment->task->task_type ?? 'project')) }}</span>
                                    <span class="task-category">{{ __('staff.tasks.' . ($assignment->task->category ?? 'general')) }}</span>
                                </div>
                            </div>
                        </td>
                        
                        <td class="assignee-cell">
                            <div class="assignee-info">
                                <div class="assignee-avatar">
                                    {{ substr($assignment->staff->first_name ?? 'U', 0, 1) }}{{ substr($assignment->staff->last_name ?? 'U', 0, 1) }}
                                </div>
                                <div class="assignee-details">
                                    <div class="assignee-name">{{ $assignment->staff->full_name ?? 'Unknown' }}</div>
                                    <div class="assignee-type">{{ $assignment->staff->staffType->display_name ?? 'No Type' }}</div>
                                </div>
                            </div>
                        </td>
                        
                        <td class="priority-cell">
                            <span class="priority-badge priority-{{ $assignment->priority_override ?? $assignment->task->priority ?? 'medium' }}">
                                {{ __('staff.tasks.' . ($assignment->priority_override ?? $assignment->task->priority ?? 'medium')) }}
                            </span>
                        </td>
                        
                        <td class="status-cell">
                            <span class="status-badge status-{{ $assignment->status }}">
                                {{ __('staff.tasks.' . $assignment->status) }}
                            </span>
                        </td>
                        
                        <td class="due-date-cell">
                            @if($assignment->due_date)
                                <div class="due-date {{ $assignment->due_date < now() && $assignment->status !== 'completed' ? 'overdue' : '' }}">
                                    <svg class="due-date-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="due-date-text">{{ $assignment->due_date->format('M j, Y') }}</span>
                                </div>
                            @else
                                <span class="no-due-date">{{ __('common.no_due_date') }}</span>
                            @endif
                        </td>
                        
                        <td class="progress-cell">
                            @if($assignment->progress_percentage)
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ $assignment->progress_percentage }}%"></div>
                                    <span class="progress-text">{{ $assignment->progress_percentage }}%</span>
                                </div>
                            @else
                                <span class="no-progress">0%</span>
                            @endif
                        </td>
                        
                        <td class="actions-cell">
                            <div class="task-actions">
                                <button @click="viewTask({{ $assignment->task->id ?? 0 }})" 
                                        class="action-btn action-btn-view" 
                                        title="{{ __('staff.tasks.view_details') }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                                
                                <button @click="editTask({{ $assignment->task->id ?? 0 }})" 
                                        class="action-btn action-btn-edit" 
                                        title="{{ __('staff.tasks.edit') }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                
                                @if($assignment->status !== 'completed')
                                    <button @click="updateAssignmentStatus({{ $assignment->id }}, 'completed')" 
                                            class="action-btn action-btn-complete" 
                                            title="{{ __('staff.tasks.mark_complete') }}">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="tasks-pagination">
            {{ $assignments->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-state-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <h3 class="empty-state-title">{{ __('staff.tasks.no_assignments_title') }}</h3>
            <p class="empty-state-description">{{ __('staff.tasks.no_assignments_description') }}</p>
        </div>
    @endif
</div>