@extends('layouts.admin')

@section('title', __('staff.tasks.title') . ' - ' . config('app.name'))
@section('page_title', __('staff.tasks.title'))

@section('content')
<div class="tasks-page">
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success" style="background: #10B981; color: white; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-error" style="background: #EF4444; color: white; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('staff.tasks.title') }}</h1>
                <p class="page-subtitle">{{ __('staff.tasks.subtitle') }}</p>
            </div>
            
            <div class="page-actions">
                <a href="{{ route('admin.staff.tasks.create') }}" class="btn btn-primary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('staff.tasks.create_task') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Dashboard Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon stat-icon-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $dashboardData['total_tasks'] ?? 0 }}</div>
                <div class="stat-label">{{ __('staff.tasks.total_tasks') }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-info">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $dashboardData['total_assignments'] ?? 0 }}</div>
                <div class="stat-label">{{ __('staff.tasks.total_assignments') }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-success">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $dashboardData['completed_assignments'] ?? 0 }}</div>
                <div class="stat-label">{{ __('staff.tasks.completed_assignments') }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-warning">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $dashboardData['overdue_assignments'] ?? 0 }}</div>
                <div class="stat-label">{{ __('staff.tasks.overdue_assignments') }}</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <form method="GET" action="{{ route('admin.staff.tasks.index') }}" class="filters-form">
            <div class="filters-row">
                <div class="filter-group">
                    <label for="search" class="filter-label">{{ __('common.search') }}</label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="{{ __('staff.tasks.search_tasks') }}"
                           class="filter-input">
                </div>

                <div class="filter-group">
                    <label for="status" class="filter-label">{{ __('common.status') }}</label>
                    <select id="status" name="status" class="filter-select">
                        <option value="">{{ __('staff.tasks.all_statuses') }}</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('staff.tasks.pending') }}</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>{{ __('staff.tasks.in_progress') }}</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>{{ __('staff.tasks.completed') }}</option>
                        <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>{{ __('staff.tasks.overdue') }}</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="priority" class="filter-label">{{ __('staff.tasks.priority') }}</label>
                    <select id="priority" name="priority" class="filter-select">
                        <option value="">{{ __('staff.tasks.all_priorities') }}</option>
                        @foreach($taskPriorities as $priority)
                            <option value="{{ $priority->slug }}" {{ request('priority') === $priority->slug ? 'selected' : '' }}>
                                {{ $priority->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label for="category" class="filter-label">{{ __('staff.tasks.category') }}</label>
                    <select id="category" name="category" class="filter-select">
                        <option value="">{{ __('staff.tasks.all_categories') }}</option>
                        @foreach($taskCategories as $category)
                            <option value="{{ $category->slug }}" {{ request('category') === $category->slug ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label for="assignee" class="filter-label">{{ __('common.assignee') }}</label>
                    <select id="assignee" name="assignee" class="filter-select">
                        <option value="">{{ __('staff.tasks.all_assignees') }}</option>
                        @foreach($staffMembers as $staff)
                            <option value="{{ $staff->id }}" {{ request('assignee') === $staff->id ? 'selected' : '' }}>
                                {{ $staff->first_name }} {{ $staff->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn btn-secondary">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        {{ __('common.filter') }}
                    </button>
                    <a href="{{ route('admin.staff.tasks.index') }}" class="btn btn-outline-secondary">
                        {{ __('common.clear') }}
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Bulk Actions Bar -->
    <div class="bulk-actions-bar" id="bulk-actions-bar" style="display: none;">
        <div class="bulk-actions-content">
            <div class="bulk-selection-info">
                <span id="selected-count">0</span> {{ __('staff.tasks.tasks_selected') }}
            </div>
            <div class="bulk-actions-buttons">
                <button type="button" class="btn btn-sm btn-success" onclick="bulkAction('complete')">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ __('staff.tasks.mark_complete') }}
                </button>
                <button type="button" class="btn btn-sm btn-warning" onclick="bulkAction('pending')">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ __('staff.tasks.mark_pending') }}
                </button>
                <button type="button" class="btn btn-sm btn-secondary" onclick="bulkAction('in_progress')">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    {{ __('staff.tasks.mark_in_progress') }}
                </button>
                <button type="button" class="btn btn-sm btn-error" onclick="bulkAction('delete')">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    {{ __('common.delete') }}
                </button>
                <button type="button" class="btn btn-sm btn-outline" onclick="clearSelection()">
                    {{ __('common.clear_selection') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Tasks List -->
    <div class="tasks-list-section">
        @if($assignments && $assignments->count() > 0)
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="checkbox-column">
                                <input type="checkbox" id="select-all" class="bulk-checkbox" onchange="toggleSelectAll(this)">
                            </th>
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
                            <tr class="table-row">
                                <td class="checkbox-cell">
                                    <input type="checkbox" class="task-checkbox" value="{{ $assignment->id }}" onchange="updateBulkActions()">
                                </td>
                                <td class="task-info-cell">
                                    <div class="task-info">
                                        <h4 class="task-title">{{ $assignment->task->title ?? 'Untitled Task' }}</h4>
                                        <p class="task-description">{{ Str::limit($assignment->task->description ?? '', 60) }}</p>
                                        <div class="task-meta">
                                            @if($assignment->task->taskType ?? null)
                                                <span class="task-type">{{ $assignment->task->taskType->name }}</span>
                                            @endif
                                            @if($assignment->task->taskCategory ?? null)
                                                <span class="task-category">{{ $assignment->task->taskCategory->name }}</span>
                                            @endif
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
                                    @php
                                        $priority = $assignment->task->taskPriority ?? null;
                                        $prioritySlug = $priority ? $priority->slug : 'medium';
                                        $priorityName = $priority ? $priority->name : 'Medium';
                                    @endphp
                                    <span class="priority-badge priority-{{ $prioritySlug }}">
                                        {{ $priorityName }}
                                    </span>
                                </td>
                                
                                <td class="status-cell">
                                    <span class="status-badge status-{{ $assignment->status }}">
                                        @if($assignment->status === 'in_progress')
                                            {{ __('staff.tasks.in_progress_short') }}
                                        @else
                                            {{ __('staff.tasks.' . $assignment->status) }}
                                        @endif
                                    </span>
                                </td>
                                
                                <td class="due-date-cell">
                                    @if($assignment->due_date)
                                        <div class="due-date {{ $assignment->due_date < now() && $assignment->status !== 'completed' ? 'overdue' : '' }}">
                                            <svg class="due-date-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <div class="due-date-info">
                                                <span class="due-date-text">{{ $assignment->due_date->format('M j, Y') }}</span>
                                                @if($assignment->scheduled_time)
                                                    <span class="due-time-text">{{ $assignment->scheduled_time->format('g:i A') }}</span>
                                                @endif
                                            </div>
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
                                    <div class="action-buttons">
                                        <button type="button" 
                                                onclick="openTaskModal('{{ $assignment->task->id }}')"
                                                class="btn-action btn-action-secondary" 
                                                title="{{ __('staff.tasks.view_details') }}">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                        
                                        <a href="{{ route('admin.staff.tasks.edit', $assignment->task->id) }}" 
                                           class="btn-action btn-action-secondary" 
                                           title="{{ __('staff.tasks.edit_task') }}">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        
                                        @if($assignment->status !== 'completed')
                                            <form method="POST"
                                                  action="{{ route('admin.staff.task-assignments.update-status', $assignment->id) }}"
                                                  class="inline-form"
                                                  onsubmit="return handleAssignmentStatusUpdate(this)">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit"
                                                        class="btn-action btn-action-success"
                                                        title="{{ __('staff.tasks.mark_complete') }}"
                                                        onclick="return confirm('{{ __('staff.tasks.confirm_complete') }}')">
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    {{ $assignments->appends(request()->query())->links() }}
                </div>
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
                <a href="{{ route('admin.staff.tasks.create') }}" class="btn btn-primary">
                    {{ __('staff.tasks.create_first_task') }}
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.tasks-page {
    padding: var(--page-padding);
    max-width: var(--page-max-width);
    margin: 0 auto;
    background: var(--color-bg-primary);
}

.page-header {
    margin-bottom: var(--section-spacing);
}

.page-header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: var(--card-spacing);
}

.page-title {
    font-size: 1.875rem;
    font-weight: 700;
    color: var(--color-text-primary);
    margin: 0;
}

.page-subtitle {
    color: var(--color-text-secondary);
    margin: 0.25rem 0 0 0;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: var(--grid-gap);
    margin-bottom: var(--section-spacing);
}

.stat-card {
    background: var(--color-surface-card);
    border: 1px solid var(--color-surface-card-border);
    border-radius: 0.75rem;
    padding: var(--card-spacing);
    box-shadow: var(--color-surface-card-shadow);
    display: flex;
    align-items: center;
    gap: var(--card-spacing);
    transition: var(--transition-all);
}

.stat-card:hover {
    background: var(--color-surface-card-hover);
    transform: var(--hover-scale);
}

.stat-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-icon svg {
    width: 1.5rem;
    height: 1.5rem;
}

.stat-icon-primary { background: var(--color-primary); color: var(--color-bg-secondary); }
.stat-icon-info { background: var(--color-info); color: var(--color-bg-secondary); }
.stat-icon-success { background: var(--color-success); color: var(--color-bg-secondary); }
.stat-icon-warning { background: var(--color-warning); color: var(--color-bg-secondary); }

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: var(--color-text-primary);
}

.stat-label {
    color: var(--color-text-secondary);
    font-size: 0.875rem;
}

.filters-section {
    background: var(--color-surface-card);
    border: 1px solid var(--color-surface-card-border);
    border-radius: 0.75rem;
    padding: var(--card-spacing);
    margin-bottom: var(--section-spacing);
    box-shadow: var(--color-surface-card-shadow);
}

.filters-row {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr auto;
    gap: var(--card-spacing);
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-label {
    font-weight: 500;
    color: var(--color-text-secondary);
    font-size: 0.875rem;
}

.filter-input, .filter-select {
    padding: 0.75rem;
    border: 1px solid var(--form-input-border);
    border-radius: 0.5rem;
    background: var(--form-input-bg);
    color: var(--form-input-text);
    transition: var(--transition-all);
}

.filter-input:focus, .filter-select:focus {
    outline: none;
    border-color: var(--form-input-border-focus);
    box-shadow: var(--form-input-shadow-focus);
}

.filter-input::placeholder {
    color: var(--form-input-placeholder);
}

.filter-actions {
    display: flex;
    gap: 0.5rem;
}

.tasks-list-section {
    background: var(--color-surface-card);
    border: 1px solid var(--color-surface-card-border);
    border-radius: 0.75rem;
    overflow: hidden;
    box-shadow: var(--color-surface-card-shadow);
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th {
    background: var(--table-header-bg);
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: var(--table-header-text);
    border-bottom: 1px solid var(--table-row-border);
}

.data-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--table-row-border);
    vertical-align: top;
}

.table-row:hover {
    background: var(--table-row-hover);
}

.task-title {
    font-weight: 600;
    color: var(--color-text-primary);
    margin: 0 0 0.25rem 0;
}

.task-description {
    color: var(--color-text-secondary);
    font-size: 0.875rem;
    margin: 0 0 0.5rem 0;
}

.task-meta {
    display: flex;
    gap: 0.5rem;
}

.task-type, .task-category {
    background: var(--color-bg-tertiary);
    color: var(--color-text-secondary);
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 500;
}

.assignee-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.assignee-avatar {
    width: 2.5rem;
    height: 2.5rem;
    min-width: 2.5rem;
    min-height: 2.5rem;
    border-radius: 50%;
    background: var(--color-primary);
    color: var(--button-primary-text);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.875rem;
    flex-shrink: 0;
}

.assignee-name {
    font-weight: 500;
    color: var(--color-text-primary);
}

.assignee-type {
    color: var(--color-text-secondary);
    font-size: 0.75rem;
}

.priority-badge, .status-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
    font-size: 0.6875rem;
    font-weight: 600;
    border: 1px solid transparent;
    min-width: fit-content;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    line-height: 1.2;
    text-transform: capitalize;
}

.priority-low { 
    background: var(--task-priority-low-bg); 
    color: var(--task-priority-low);
    border-color: var(--task-priority-low);
}
.priority-medium { 
    background: var(--task-priority-medium-bg); 
    color: var(--task-priority-medium);
    border-color: var(--task-priority-medium);
}
.priority-high { 
    background: var(--task-priority-high-bg); 
    color: var(--task-priority-high);
    border-color: var(--task-priority-high);
}
.priority-urgent { 
    background: var(--task-priority-urgent-bg); 
    color: var(--task-priority-urgent);
    border-color: var(--task-priority-urgent);
}

.status-pending { 
    background: var(--task-status-pending-bg); 
    color: var(--task-status-pending);
    border-color: var(--task-status-pending);
}
.status-in_progress { 
    background: var(--task-status-in-progress-bg); 
    color: var(--task-status-in-progress);
    border-color: var(--task-status-in-progress);
}
.status-completed { 
    background: var(--task-status-completed-bg); 
    color: var(--task-status-completed);
    border-color: var(--task-status-completed);
}
.status-overdue { 
    background: var(--task-status-overdue-bg); 
    color: var(--task-status-overdue);
    border-color: var(--task-status-overdue);
}

.due-date {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--color-text-secondary);
    white-space: nowrap;
}

.due-date.overdue {
    color: var(--task-status-overdue);
    font-weight: 500;
}

.due-date-icon {
    width: 1rem;
    height: 1rem;
    flex-shrink: 0;
}

.due-date-info {
    display: flex;
    flex-direction: column;
    gap: 0.125rem;
}

.due-date-text {
    white-space: nowrap;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--color-text-primary);
}

.due-time-text {
    white-space: nowrap;
    font-size: 0.75rem;
    color: var(--color-text-secondary);
    font-weight: 400;
}

.progress-bar {
    width: 100%;
    height: 1rem;
    background: var(--progress-bg);
    border-radius: 0.5rem;
    overflow: hidden;
    position: relative;
}

.progress-fill {
    height: 100%;
    background: var(--progress-fill);
    transition: var(--transition-all);
}

.progress-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--color-bg-secondary);
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
}

.action-buttons {
    display: flex;
    gap: 0.25rem;
}

.inline-form {
    display: inline;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-state-icon {
    width: 4rem;
    height: 4rem;
    margin: 0 auto 1rem;
    color: var(--color-text-muted);
}

.empty-state-icon svg {
    width: 100%;
    height: 100%;
}

.empty-state-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--color-text-primary);
    margin: 0 0 0.5rem 0;
}

.empty-state-description {
    color: var(--color-text-secondary);
    margin: 0 0 1.5rem 0;
}

.pagination-wrapper {
    padding: 1rem;
    border-top: 1px solid var(--table-row-border);
    background: var(--color-bg-tertiary);
}

.alert {
    padding: 1rem 1.5rem;
    border-radius: 0.75rem;
    margin-bottom: 1.5rem;
    font-weight: 500;
    border: 1px solid;
}

.alert-success {
    background: var(--alert-success-bg);
    color: var(--alert-success-text);
    border-color: var(--alert-success-border);
}

.alert-error {
    background: var(--alert-error-bg);
    color: var(--alert-error-text);
    border-color: var(--alert-error-border);
}

/* Centralized Button System */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: var(--btn-padding);
    border-radius: var(--btn-border-radius);
    font-weight: var(--btn-font-weight);
    text-decoration: none;
    border: 1px solid transparent;
    cursor: pointer;
    transition: var(--btn-transition);
    font-size: 0.875rem;
    line-height: 1.25rem;
    gap: 0.5rem;
}

.btn:focus {
    outline: none;
    box-shadow: var(--form-input-shadow-focus);
}

.btn-sm {
    padding: var(--btn-padding-sm);
    font-size: 0.8125rem;
}

.btn-lg {
    padding: var(--btn-padding-lg);
    font-size: 1rem;
}

.btn-primary {
    background: var(--button-primary-bg);
    color: var(--button-primary-text);
    border-color: var(--button-primary-bg);
    box-shadow: var(--button-primary-shadow);
}

.btn-primary:hover {
    background: var(--button-primary-hover-bg);
    border-color: var(--button-primary-hover-bg);
    box-shadow: var(--button-primary-hover-shadow);
    transform: translateY(-1px);
}

.btn-secondary {
    background: var(--button-secondary-bg);
    color: var(--button-secondary-text);
    border-color: var(--button-secondary-bg);
    box-shadow: var(--button-secondary-shadow);
}

.btn-secondary:hover {
    background: var(--button-secondary-hover-bg);
    border-color: var(--button-secondary-hover-bg);
    transform: translateY(-1px);
}

.btn-outline-primary {
    background: transparent;
    color: var(--color-primary);
    border-color: var(--color-primary);
}

.btn-outline-primary:hover {
    background: var(--color-primary);
    color: var(--button-primary-text);
}

.btn-outline-secondary {
    background: transparent;
    color: var(--color-text-secondary);
    border-color: var(--color-border-base);
}

.btn-outline-secondary:hover {
    background: var(--color-bg-tertiary);
    border-color: var(--color-text-secondary);
}

/* Action Buttons */
.btn-action {
    width: var(--btn-action-size);
    height: var(--btn-action-size);
    padding: var(--btn-action-padding);
    border-radius: var(--btn-action-border-radius);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid transparent;
    cursor: pointer;
    transition: var(--btn-transition);
    text-decoration: none;
}

.btn-action:focus {
    outline: none;
    box-shadow: var(--form-input-shadow-focus);
}

.btn-action-primary {
    background: var(--color-primary);
    color: var(--button-primary-text);
}

.btn-action-primary:hover {
    background: var(--color-secondary);
    transform: var(--hover-scale);
}

.btn-action-secondary {
    background: var(--color-bg-tertiary);
    color: var(--color-text-secondary);
    border-color: var(--color-border-base);
}

.btn-action-secondary:hover {
    background: var(--color-surface-card-hover);
    color: var(--color-text-primary);
    border-color: var(--color-primary);
}

.btn-action-success {
    background: var(--color-success);
    color: var(--color-bg-secondary);
}

.btn-action-success:hover {
    background: var(--task-status-completed);
    transform: var(--hover-scale);
}

.btn-action-danger {
    background: var(--color-error);
    color: var(--color-bg-secondary);
}

.btn-action-danger:hover {
    background: var(--task-status-overdue);
    transform: var(--hover-scale);
}

/* Button Icons */
.btn-icon {
    width: 1rem;
    height: 1rem;
}

.btn-action svg {
    width: 1rem;
    height: 1rem;
}

@media (max-width: 768px) {
    .page-header-content {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filters-row {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .filter-actions {
        justify-content: stretch;
    }
    
    .stats-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 0.5rem;
    }

    .stat-card {
        padding: 0.75rem;
    }

    .stat-icon {
        width: 2rem;
        height: 2rem;
    }

    .stat-icon svg {
        width: 1rem;
        height: 1rem;
    }

    .stat-value {
        font-size: 1.5rem;
    }

    .stat-label {
        font-size: 0.75rem;
    }
    
    .data-table {
        font-size: 0.875rem;
    }
    
    .data-table th,
    .data-table td {
        padding: 0.75rem 0.5rem;
    }
}

/* Task Detail Modal Styles */
.task-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: var(--task-modal-overlay);
    z-index: var(--z-modal-backdrop);
    opacity: 0;
    visibility: hidden;
    display: none;
    transition: all 0.3s ease;
}

.task-modal-overlay.active {
    opacity: 1;
    visibility: visible;
    display: block;
}

.task-modal {
    position: fixed;
    top: 0;
    right: -500px;
    width: 500px;
    height: 100vh;
    background: var(--task-modal-bg);
    color: var(--task-modal-text-primary);
    box-shadow: var(--task-modal-shadow);
    z-index: var(--z-modal);
    transition: right 0.3s ease;
    overflow-y: auto;
}

.task-modal.active {
    right: 0;
}

.task-modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--task-modal-border);
    background: var(--task-modal-header-bg);
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    z-index: 10;
}

.task-modal-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--task-modal-text-primary);
    margin: 0;
}

.task-modal-close {
    background: none;
    border: none;
    padding: 0.5rem;
    cursor: pointer;
    color: var(--task-modal-text-secondary);
    border-radius: 0.375rem;
    transition: all 0.2s ease;
}

.task-modal-close:hover {
    background: var(--task-modal-hover-bg);
    color: var(--task-modal-text-primary);
}

.task-modal-close svg {
    width: 1.25rem;
    height: 1.25rem;
}

.task-modal-content {
    padding: 1.5rem;
}

.task-modal-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem;
    color: var(--task-modal-text-secondary);
}

.task-modal-loading svg {
    width: 2rem;
    height: 2rem;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.task-detail-section {
    margin-bottom: 2rem;
}

.task-detail-section:last-child {
    margin-bottom: 0;
}

.task-detail-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--task-modal-text-secondary);
    margin-bottom: 0.5rem;
    display: block;
}

.task-detail-value {
    color: var(--task-modal-text-primary);
    line-height: 1.5;
}

.task-detail-badges {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.task-detail-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.task-detail-badge.priority-high {
    background: #fef2f2;
    color: #dc2626;
}

.task-detail-badge.priority-medium {
    background: #fef3c7;
    color: #d97706;
}

.task-detail-badge.priority-low {
    background: #f0fdf4;
    color: #16a34a;
}

.task-detail-badge.status-pending {
    background: #fef3c7;
    color: #d97706;
}

.task-detail-badge.status-in-progress {
    background: #dbeafe;
    color: #2563eb;
}

.task-detail-badge.status-completed {
    background: #f0fdf4;
    color: #16a34a;
}

.task-detail-badge.status-cancelled {
    background: #fef2f2;
    color: #dc2626;
}

.task-assignees {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.task-assignee {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: var(--task-modal-assignee-bg);
    border: 1px solid var(--task-modal-assignee-border);
    border-radius: 0.5rem;
}

.task-assignee-avatar {
    width: 2.5rem;
    height: 2.5rem;
    min-width: 2.5rem;
    min-height: 2.5rem;
    background: var(--color-primary);
    color: var(--button-primary-text);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.875rem;
    flex-shrink: 0;
}

.task-assignee-info {
    flex: 1;
}

.task-assignee-name {
    font-weight: 500;
    color: var(--task-modal-text-primary);
}

.task-assignee-type {
    font-size: 0.875rem;
    color: var(--task-modal-text-secondary);
}

.task-tags {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.task-tag {
    padding: 0.25rem 0.5rem;
    background: #e0e7ff;
    color: #3730a3;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 500;
}

/* Task title styling */
.task-modal-title-main {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--task-modal-title-main-color);
    line-height: 1.2;
}

/* Task detail styling */
.task-detail-muted {
    color: var(--task-modal-text-muted);
}

.task-category-badge {
    background: rgba(var(--color-primary-rgb), 0.1) !important;
    color: var(--color-primary) !important;
}

.task-metadata-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    font-size: 0.875rem;
    color: var(--task-modal-text-muted);
}

/* Button hover effects for modal */
.task-modal .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.task-modal-btn-primary {
    background: var(--task-modal-button-primary-bg) !important;
}

.task-modal-btn-primary:hover {
    background: var(--task-modal-button-primary-hover) !important;
}

.task-modal-btn-secondary {
    background: var(--task-modal-button-secondary-bg) !important;
    border: 1px solid var(--task-modal-button-secondary-border) !important;
}

.task-modal-btn-secondary:hover {
    background: var(--task-modal-button-secondary-hover) !important;
    border-color: var(--task-modal-button-secondary-border) !important;
}


/* Bulk Actions Styles */
.bulk-actions-bar {
    background: var(--color-bg-secondary);
    border: 1px solid var(--color-border-base);
    border-radius: var(--border-radius);
    padding: 1rem;
    margin-bottom: 1rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.bulk-actions-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}

.bulk-selection-info {
    font-weight: 600;
    color: var(--color-text-primary);
    font-size: 0.875rem;
}

.bulk-actions-buttons {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.bulk-actions-buttons .btn {
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-weight: 500;
    transition: all 0.2s ease;
}

.bulk-actions-buttons .btn-success {
    background: var(--color-success);
    color: white;
    border: none;
}

.bulk-actions-buttons .btn-success:hover {
    background: var(--color-success-dark);
    transform: translateY(-1px);
}

.bulk-actions-buttons .btn-warning {
    background: var(--color-warning);
    color: white;
    border: none;
}

.bulk-actions-buttons .btn-warning:hover {
    background: var(--color-warning-dark);
    transform: translateY(-1px);
}

.bulk-actions-buttons .btn-error {
    background: var(--color-error);
    color: white;
    border: none;
}

.bulk-actions-buttons .btn-error:hover {
    background: var(--color-error-dark);
    transform: translateY(-1px);
}

.bulk-actions-buttons .btn-outline {
    background: transparent;
    color: var(--color-text-secondary);
    border: 1px solid var(--color-border-strong);
}

.bulk-actions-buttons .btn-outline:hover {
    background: var(--color-bg-tertiary);
    border-color: var(--color-primary);
    color: var(--color-text-primary);
}

/* Checkbox Styling */
.checkbox-column {
    width: 40px;
    text-align: center;
}

/* Table Column Sizing */
.data-table th:nth-child(1) { width: 40px; }      /* Checkbox */
.data-table th:nth-child(2) { width: 30%; }       /* Task Title */
.data-table th:nth-child(3) { width: 15%; }       /* Assignee */
.data-table th:nth-child(4) { width: 10%; }       /* Priority */
.data-table th:nth-child(5) { width: 11%; }       /* Status */
.data-table th:nth-child(6) { width: 14%; }       /* Due Date - More space to prevent wrapping */
.data-table th:nth-child(7) { width: 10%; }       /* Progress */
.data-table th:nth-child(8) { width: 10%; }       /* Actions */

.status-cell {
    min-width: 90px;
    text-align: center;
}

.priority-cell {
    min-width: 80px;
    text-align: center;
}

.due-date-cell {
    min-width: 140px;
}

.checkbox-cell {
    text-align: center;
    vertical-align: middle;
    padding: 0.75rem 0.5rem;
}

.bulk-checkbox,
.task-checkbox {
    width: 1rem;
    height: 1rem;
    accent-color: var(--color-primary);
    cursor: pointer;
}

.bulk-checkbox:indeterminate {
    background: var(--color-primary);
    border-color: var(--color-primary);
}

@media (max-width: 768px) {
    .bulk-actions-content {
        flex-direction: column;
        align-items: stretch;
        gap: 0.75rem;
    }
    
    .bulk-actions-buttons {
        justify-content: center;
    }
    
    .bulk-actions-buttons .btn {
        flex: 1;
        min-width: 0;
    }
}
    .task-modal {
        width: 100%;
        right: -100%;
    }
}

/* ===== ENHANCED TASK MODAL STYLING ===== */

/* Task Modal Header Card */
.task-modal-header-card {
    background: var(--color-surface-card);
    border: 1px solid var(--color-surface-card-border);
    border-radius: 0.75rem;
    padding: var(--card-spacing);
    margin-bottom: var(--section-spacing);
    box-shadow: var(--color-surface-card-shadow);
}

.task-header-content {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: var(--card-spacing);
}

.task-title-section {
    flex: 1;
}

.task-modal-title-main {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--color-text-primary);
    margin: 0 0 0.5rem 0;
    line-height: 1.2;
}

.task-type-indicator {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    margin-bottom: 0.5rem;
}

.task-type-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    border: 1px solid transparent;
    transition: var(--transition-all);
}

.task-priority-indicator {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Task Detail Cards */
.task-detail-card {
    background: var(--color-surface-card);
    border: 1px solid var(--color-surface-card-border);
    border-radius: 0.75rem;
    margin-bottom: var(--section-spacing);
    box-shadow: var(--color-surface-card-shadow);
    overflow: hidden;
    transition: var(--transition-all);
}

.task-detail-card:hover {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-1px);
}

.card-header {
    padding: var(--card-spacing);
    border-bottom: 1px solid var(--color-surface-card-border);
    background: var(--color-bg-tertiary);
}

.card-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1rem;
    font-weight: 600;
    color: var(--color-text-primary);
    margin: 0;
}

.card-icon {
    width: 1.25rem;
    height: 1.25rem;
    color: var(--color-primary);
    flex-shrink: 0;
}

.assignment-count, .tag-count {
    background: var(--color-primary);
    color: var(--button-primary-text);
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.125rem 0.5rem;
    border-radius: 9999px;
    margin-left: 0.25rem;
}

.card-content {
    padding: var(--card-spacing);
}

/* Detail Rows */
.detail-row {
    padding: 1rem;
    border: 1px solid var(--color-surface-card-border);
    border-radius: 0.5rem;
    margin-bottom: 0.75rem;
    background: var(--color-bg-tertiary);
    transition: var(--transition-all);
}

.detail-row:last-child {
    margin-bottom: 0;
}

.detail-row:hover {
    background: var(--color-surface-card-hover);
    border-color: var(--color-primary);
}

.detail-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--color-text-secondary);
    margin-bottom: 0.375rem;
    display: block;
}

.detail-value {
    color: var(--color-text-primary);
    line-height: 1.5;
}

.detail-instructions {
    white-space: pre-line;
    font-family: inherit;
}

/* Classification Grid */
.classification-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0.75rem;
}

.classification-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: var(--color-bg-tertiary);
    border: 1px solid var(--color-surface-card-border);
    border-radius: 0.5rem;
    transition: var(--transition-all);
}

.classification-item:hover {
    background: var(--color-surface-card-hover);
    border-color: var(--color-primary);
}

.item-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--color-text-secondary);
    min-width: 4rem;
}

.category-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.25rem 0.75rem;
    background: var(--color-secondary);
    color: var(--button-primary-text);
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    border: 1px solid transparent;
}

/* Assignments Grid */
.assignments-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0.75rem;
}

.assignment-card {
    padding: 1rem;
    background: var(--color-bg-tertiary);
    border: 1px solid var(--color-surface-card-border);
    border-radius: 0.5rem;
    transition: var(--transition-all);
}

.assignment-card:hover {
    background: var(--color-surface-card-hover);
    border-color: var(--color-primary);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.assignment-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}

.assignee-avatar {
    width: 2.5rem;
    height: 2.5rem;
    min-width: 2.5rem;
    min-height: 2.5rem;
    border-radius: 50%;
    background: var(--color-primary);
    color: var(--button-primary-text);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.875rem;
    flex-shrink: 0;
}

.assignee-details {
    flex: 1;
}

.assignee-name {
    font-weight: 600;
    color: var(--color-text-primary);
    font-size: 0.875rem;
}

.assignee-type {
    color: var(--color-text-secondary);
    font-size: 0.75rem;
}

.assignment-status {
    flex-shrink: 0;
}

.assignment-meta {
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.75rem;
    color: var(--color-text-secondary);
}

.meta-icon {
    width: 1rem;
    height: 1rem;
    flex-shrink: 0;
}

.time-text {
    color: var(--color-text-primary);
    font-weight: 500;
}

/* Timing Grid */
.timing-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0.75rem;
}

.timing-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: var(--color-bg-tertiary);
    border: 1px solid var(--color-surface-card-border);
    border-radius: 0.5rem;
    transition: var(--transition-all);
}

.timing-item:hover {
    background: var(--color-surface-card-hover);
    border-color: var(--color-primary);
}

.timing-item-full {
    grid-column: 1 / -1;
}

.timing-icon {
    width: 2rem;
    height: 2rem;
    border-radius: 0.5rem;
    background: var(--color-primary);
    color: var(--button-primary-text);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.timing-icon svg {
    width: 1rem;
    height: 1rem;
}

.timing-content {
    flex: 1;
}

.timing-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--color-text-secondary);
    margin-bottom: 0.125rem;
}

.timing-value {
    font-size: 0.875rem;
    color: var(--color-text-primary);
    font-weight: 500;
}

.schedule-time {
    color: var(--color-primary);
    font-weight: 600;
}

/* Tags Container */
.tags-container {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.tag-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.375rem 0.75rem;
    background: var(--color-primary);
    color: var(--button-primary-text);
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    border: 1px solid transparent;
    transition: var(--transition-all);
}

.tag-pill:hover {
    background: var(--color-secondary);
    transform: translateY(-1px);
}

.tag-icon {
    width: 0.75rem;
    height: 0.75rem;
    flex-shrink: 0;
}

/* Settings Grid */
.settings-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0.75rem;
}

.setting-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: var(--color-bg-tertiary);
    border: 1px solid var(--color-surface-card-border);
    border-radius: 0.5rem;
    transition: var(--transition-all);
}

.setting-item:hover {
    background: var(--color-surface-card-hover);
    border-color: var(--color-primary);
}

.setting-item-full {
    grid-column: 1 / -1;
}

.setting-icon {
    width: 2rem;
    height: 2rem;
    border-radius: 0.5rem;
    background: var(--color-primary);
    color: var(--button-primary-text);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.setting-icon svg {
    width: 1rem;
    height: 1rem;
}

.setting-content {
    flex: 1;
}

.setting-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--color-text-secondary);
    margin-bottom: 0.125rem;
}

.setting-value {
    font-size: 0.875rem;
    font-weight: 500;
}

.status-indicator {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.125rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: capitalize;
}

.status-active {
    background: var(--color-success);
    color: white;
}

.status-inactive {
    background: var(--color-text-muted);
    color: white;
}

/* Metadata Grid */
.metadata-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0.75rem;
}

.metadata-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: var(--color-bg-tertiary);
    border: 1px solid var(--color-surface-card-border);
    border-radius: 0.5rem;
    transition: var(--transition-all);
}

.metadata-item:hover {
    background: var(--color-surface-card-hover);
    border-color: var(--color-primary);
}

.metadata-icon {
    width: 2rem;
    height: 2rem;
    border-radius: 0.5rem;
    background: var(--color-primary);
    color: var(--button-primary-text);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.metadata-icon svg {
    width: 1rem;
    height: 1rem;
}

.metadata-content {
    flex: 1;
}

.metadata-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--color-text-secondary);
    margin-bottom: 0.125rem;
}

.metadata-value {
    font-size: 0.875rem;
    color: var(--color-text-primary);
    font-weight: 500;
}

/* Action Buttons Grid */
.action-buttons-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0.75rem;
}

.action-button {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: var(--color-bg-tertiary);
    border: 1px solid var(--color-surface-card-border);
    border-radius: 0.5rem;
    text-decoration: none;
    color: inherit;
    transition: var(--transition-all);
}

.action-button:hover {
    background: var(--color-surface-card-hover);
    border-color: var(--color-primary);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.action-button-primary {
    border-color: var(--color-primary);
    background: var(--color-primary);
    color: var(--button-primary-text);
}

.action-button-primary:hover {
    background: var(--color-secondary);
    border-color: var(--color-secondary);
}

.action-button-secondary {
    border-color: var(--color-border-base);
}

.action-button-secondary:hover {
    border-color: var(--color-primary);
    background: var(--color-surface-card-hover);
}

.action-icon {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.5rem;
    background: currentColor;
    opacity: 0.1;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: var(--transition-all);
}

.action-button:hover .action-icon {
    opacity: 0.2;
}

.action-icon svg {
    width: 1.25rem;
    height: 1.25rem;
}

.action-content {
    flex: 1;
}

.action-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--color-text-primary);
    margin-bottom: 0.125rem;
}

.action-description {
    font-size: 0.75rem;
    color: var(--color-text-secondary);
}

.action-arrow {
    width: 1.5rem;
    height: 1.5rem;
    color: var(--color-text-muted);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition-all);
}

.action-button:hover .action-arrow {
    color: var(--color-primary);
    transform: translateX(2px);
}

.action-arrow svg {
    width: 1rem;
    height: 1rem;
}

/* Enhanced Badge Icons */
.badge-icon {
    width: 0.75rem;
    height: 0.75rem;
    flex-shrink: 0;
}

/* Responsive Design */
@media (min-width: 768px) {
    .classification-grid {
        grid-template-columns: 1fr 1fr;
    }

    .timing-grid {
        grid-template-columns: 1fr 1fr;
    }

    .settings-grid {
        grid-template-columns: 1fr 1fr;
    }

    .metadata-grid {
        grid-template-columns: 1fr 1fr;
    }

    .action-buttons-grid {
        grid-template-columns: 1fr 1fr;
    }
}

@media (max-width: 768px) {
    .task-modal {
        width: 100%;
        right: -100%;
    }

    .task-header-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .task-priority-indicator {
        align-self: flex-start;
    }

    .assignment-card {
        padding: 0.75rem;
    }

    .assignment-header {
        gap: 0.5rem;
    }

    .assignee-details {
        min-width: 0;
    }

    .action-button {
        padding: 0.75rem;
    }
}

/* ==========================================================================
   Toast Notifications
   ========================================================================== */

.toast {
    position: fixed;
    top: var(--space-lg);
    right: var(--space-lg);
    z-index: var(--z-toast);
    padding: var(--space-lg) var(--space-xl);
    border-radius: 0.5rem;
    background: var(--alert-success-bg);
    color: var(--alert-success-text);
    border: 1px solid var(--alert-success-border);
    display: flex;
    align-items: center;
    gap: var(--space-md);
    font-weight: 500;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    animation: slideInRight 0.3s ease-out;
    max-width: 400px;
    word-wrap: break-word;
}

.toast svg {
    width: var(--space-lg);
    height: var(--space-lg);
    flex-shrink: 0;
}

.toast-success {
    background: var(--alert-success-bg);
    color: var(--alert-success-text);
    border: 1px solid var(--alert-success-border);
}

.toast-error {
    background: var(--alert-error-bg);
    color: var(--alert-error-text);
    border: 1px solid var(--alert-error-border);
}

.toast-warning {
    background: var(--alert-warning-bg);
    color: var(--alert-warning-text);
    border: 1px solid var(--alert-warning-border);
}

.toast-info {
    background: var(--alert-info-bg);
    color: var(--alert-info-text);
    border: 1px solid var(--alert-info-border);
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
</style>
@endpush

<!-- Task Detail Modal -->
<div id="taskModalOverlay" class="task-modal-overlay" onclick="closeTaskModal()">
    <div id="taskModal" class="task-modal" onclick="event.stopPropagation()">
        <div class="task-modal-header">
            <h3 class="task-modal-title">{{ __('staff.tasks.task_details') }}</h3>
            <button type="button" class="task-modal-close" onclick="closeTaskModal()">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div id="taskModalContent" class="task-modal-content">
            <div class="task-modal-loading">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <span style="margin-left: 0.5rem;">{{ __('common.loading') }}...</span>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script>
// Bulk Actions Functionality
function toggleSelectAll(selectAllCheckbox) {
    const taskCheckboxes = document.querySelectorAll('.task-checkbox');
    taskCheckboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
    updateBulkActions();
}

function updateBulkActions() {
    const taskCheckboxes = document.querySelectorAll('.task-checkbox');
    const checkedCheckboxes = document.querySelectorAll('.task-checkbox:checked');
    const selectAllCheckbox = document.getElementById('select-all');
    const bulkActionsBar = document.getElementById('bulk-actions-bar');
    const selectedCount = document.getElementById('selected-count');
    
    // Update select all checkbox state
    if (checkedCheckboxes.length === 0) {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = false;
    } else if (checkedCheckboxes.length === taskCheckboxes.length) {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = true;
    } else {
        selectAllCheckbox.indeterminate = true;
    }
    
    // Show/hide bulk actions bar
    if (checkedCheckboxes.length > 0) {
        bulkActionsBar.style.display = 'block';
        selectedCount.textContent = checkedCheckboxes.length;
    } else {
        bulkActionsBar.style.display = 'none';
    }
}

function clearSelection() {
    const taskCheckboxes = document.querySelectorAll('.task-checkbox');
    const selectAllCheckbox = document.getElementById('select-all');
    
    taskCheckboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    selectAllCheckbox.checked = false;
    selectAllCheckbox.indeterminate = false;
    
    updateBulkActions();
}

function bulkAction(action) {
    const checkedCheckboxes = document.querySelectorAll('.task-checkbox:checked');
    const assignmentIds = Array.from(checkedCheckboxes).map(cb => cb.value);
    
    if (assignmentIds.length === 0) {
        alert('Please select at least one task.');
        return;
    }
    
    let confirmMessage = '';
    switch (action) {
        case 'delete':
            confirmMessage = `Are you sure you want to delete ${assignmentIds.length} task(s)? This action cannot be undone.`;
            break;
        case 'complete':
            confirmMessage = `Mark ${assignmentIds.length} task(s) as completed?`;
            break;
        case 'pending':
            confirmMessage = `Mark ${assignmentIds.length} task(s) as pending?`;
            break;
        case 'in_progress':
            confirmMessage = `Mark ${assignmentIds.length} task(s) as in progress?`;
            break;
    }
    
    if (confirm(confirmMessage)) {
        // Create and submit form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.staff.tasks.bulk-action") }}';
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Add action
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = action;
        form.appendChild(actionInput);
        
        // Add assignment IDs
        assignmentIds.forEach(id => {
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'assignment_ids[]';
            idInput.value = id;
            form.appendChild(idInput);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
}

/**
 * Show toast notification
 */
function showToast(message, type = 'info') {
    // Remove existing toasts
    const existingToasts = document.querySelectorAll('.toast');
    existingToasts.forEach(toast => toast.remove());

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;

    const icon = type === 'success' ? '✓' : type === 'error' ? '✗' : 'ℹ';
    toast.innerHTML = `
        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
            <text x="10" y="15" text-anchor="middle" font-size="14">${icon}</text>
        </svg>
        <span>${message}</span>
    `;

    document.body.appendChild(toast);

    // Auto remove after 5 seconds
    setTimeout(() => {
        toast.remove();
    }, 5000);
}

/**
 * Handle assignment status update
 */
function handleAssignmentStatusUpdate(form) {
    const formData = new FormData(form);
    const button = form.querySelector('button[type="submit"]');
    const originalText = button.innerHTML;

    // Show loading state
    button.disabled = true;
    button.innerHTML = '<svg class="animate-spin" width="16" height="16" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" stroke-dasharray="31.416" stroke-dashoffset="31.416"><animate attributeName="stroke-dashoffset" dur="1s" repeatCount="indefinite" values="31.416;0"/></circle></svg>';

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message || 'Assignment status updated successfully', 'success');

            // Update the button and UI
            const card = form.closest('.task-card');
            if (card) {
                const statusBadge = card.querySelector('.status-badge');
                if (statusBadge) {
                    statusBadge.className = 'status-badge status-completed';
                    statusBadge.textContent = 'Completed';
                }

                // Remove the form since it's completed
                form.remove();
            }

            // Refresh the page data after a short delay
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showToast(data.message || 'Failed to update assignment status', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred while updating the assignment status', 'error');
    })
    .finally(() => {
        // Restore button state
        button.disabled = false;
        button.innerHTML = originalText;
    });

    return false; // Prevent default form submission
}

function openTaskModal(taskId) {
    const overlay = document.getElementById('taskModalOverlay');
    const modal = document.getElementById('taskModal');
    const content = document.getElementById('taskModalContent');

    // Show modal
    overlay.classList.add('active');
    modal.classList.add('active');
    
    // Reset content to loading state
    content.innerHTML = `
        <div class="task-modal-loading">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            <span style="margin-left: 0.5rem;">{{ __('common.loading') }}...</span>
        </div>
    `;
    
    // Fetch task details
    fetch(`/admin/staff/tasks/${taskId}/modal`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                content.innerHTML = data.html;
            } else {
                content.innerHTML = `
                    <div class="task-modal-loading" style="color: #dc2626;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span style="margin-left: 0.5rem;">{{ __('common.error_loading') }}</span>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading task details:', error);
            content.innerHTML = `
                <div class="task-modal-loading" style="color: #dc2626;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span style="margin-left: 0.5rem;">{{ __('common.error_loading') }}</span>
                </div>
            `;
        });
}

function closeTaskModal() {
    const overlay = document.getElementById('taskModalOverlay');
    const modal = document.getElementById('taskModal');
    
    overlay.classList.remove('active');
    modal.classList.remove('active');
}

// Coming Soon Function
function showComingSoon() {
    showNotification('Edit Task feature is coming soon!', 'info');
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: 0.5rem;
        color: white;
        font-weight: 500;
        z-index: 9999;
        max-width: 400px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        transform: translateX(100%);
        transition: transform 0.3s ease;
    `;
    
    // Set background color based on type
    if (type === 'success') {
        notification.style.background = '#10b981';
    } else if (type === 'error') {
        notification.style.background = '#ef4444';
    } else {
        notification.style.background = '#3b82f6';
    }
    
    notification.textContent = message;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Remove after 5 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 5000);
}

// Close modal on Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeTaskModal();
    }
});
</script>
@endpush
