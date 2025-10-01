@extends('layouts.admin')

@section('title', __('todos.overview.title') . ' - ' . config('app.name'))
@section('page_title', __('todos.overview.title'))

@section('content')
<div class="admin-content">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <h1 class="page-title">{{ __('todos.overview.title') }}</h1>
            <p class="page-subtitle">{{ __('todos.overview.subtitle') }}</p>
        </div>
        <div class="page-header-actions">
            <button class="btn btn-secondary" onclick="refreshDashboard()">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                {{ __('todos.overview.refresh') }}
            </button>
            <button class="btn btn-primary" onclick="openQuickAddModal()">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                {{ __('todos.overview.quick_add') }}
            </button>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="statistics-grid">
        <div class="stat-card primary">
            <div class="stat-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div class="stat-content">
                <h3 class="stat-title">{{ __('todos.overview.active_todos') }}</h3>
                <div class="stat-value">{{ $statistics['total_active_todos'] }}</div>
                <div class="stat-subtitle">{{ __('todos.overview.across_all_staff') }}</div>
            </div>
        </div>

        <div class="stat-card success">
            <div class="stat-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <h3 class="stat-title">{{ __('todos.overview.completed_today') }}</h3>
                <div class="stat-value">{{ $statistics['completed_today'] }}</div>
                <div class="stat-subtitle">{{ number_format($statistics['completion_rate_today'], 1) }}% {{ __('todos.overview.completion_rate') }}</div>
            </div>
        </div>

        <div class="stat-card warning">
            <div class="stat-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <h3 class="stat-title">{{ __('todos.overview.overdue_todos') }}</h3>
                <div class="stat-value">{{ $statistics['overdue_todos'] }}</div>
                <div class="stat-subtitle">{{ __('todos.overview.need_attention') }}</div>
            </div>
        </div>

        <div class="stat-card info">
            <div class="stat-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <h3 class="stat-title">{{ __('todos.overview.staff_with_todos') }}</h3>
                <div class="stat-value">{{ $statistics['staff_with_todos'] }}</div>
                <div class="stat-subtitle">{{ __('todos.overview.active_members') }}</div>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Content -->
    <div class="dashboard-grid">
        <!-- Today's To-Dos by Staff -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">{{ __('todos.overview.todays_todos') }}</h2>
                <div class="section-actions">
                    <button class="btn btn-sm btn-secondary" onclick="viewAllTodos()">
                        {{ __('todos.overview.view_all') }}
                    </button>
                </div>
            </div>
            
            <div class="staff-todos-list">
                @foreach($todaysTodos as $staff)
                <div class="staff-todo-card">
                    <div class="staff-header">
                        <div class="staff-info">
                            <div class="staff-avatar">
                                @if($staff['avatar'])
                                <img src="{{ $staff['avatar'] }}" alt="{{ $staff['staff_name'] }}">
                                @else
                                <div class="avatar-placeholder">
                                    {{ substr($staff['staff_name'], 0, 2) }}
                                </div>
                                @endif
                            </div>
                            <div class="staff-details">
                                <h3 class="staff-name">{{ $staff['staff_name'] }}</h3>
                                <span class="staff-role">{{ $staff['role'] }}</span>
                            </div>
                        </div>
                        <div class="staff-stats">
                            <div class="completion-rate">
                                <div class="rate-circle" style="--progress: {{ $staff['completion_rate'] }}%">
                                    <span class="rate-value">{{ number_format($staff['completion_rate'], 0) }}%</span>
                                </div>
                            </div>
                            <div class="todo-counts">
                                <span class="completed">{{ $staff['completed_todos'] }}/{{ $staff['total_todos'] }}</span>
                                @if($staff['overdue_todos'] > 0)
                                <span class="overdue">{{ $staff['overdue_todos'] }} {{ __('todos.overview.overdue') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="staff-todos">
                        @foreach($staff['current_todos'] as $todo)
                        <div class="todo-item status-{{ $todo['status'] }} priority-{{ $todo['priority'] }}">
                            <div class="todo-content">
                                <div class="todo-header">
                                    <h4 class="todo-title">{{ $todo['title'] }}</h4>
                                    <div class="todo-badges">
                                        <span class="priority-badge priority-{{ $todo['priority'] }}">
                                            {{ __('todos.overview.' . $todo['priority']) }}
                                        </span>
                                        <span class="category-badge">{{ $todo['category'] }}</span>
                                    </div>
                                </div>
                                <div class="todo-meta">
                                    <span class="due-time">
                                        <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $todo['due_time'] }}
                                    </span>
                                    <span class="duration">
                                        <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                        {{ $todo['estimated_duration'] }}{{ __('todos.overview.minutes') }}
                                    </span>
                                    <span class="recurring">
                                        <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        {{ __('todos.overview.' . $todo['recurring_type']) }}
                                    </span>
                                </div>
                            </div>
                            <div class="todo-actions">
                                @if($todo['status'] === 'pending')
                                <button class="action-btn start" onclick="startTodo({{ $todo['id'] }})" title="{{ __('todos.overview.start_todo') }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293H15M9 10V9a2 2 0 012-2h2a2 2 0 012 2v1M9 10v5a2 2 0 002 2h2a2 2 0 002-2v-5"/>
                                    </svg>
                                </button>
                                @elseif($todo['status'] === 'in_progress')
                                <button class="action-btn complete" onclick="completeTodo({{ $todo['id'] }})" title="{{ __('todos.overview.complete_todo') }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                                @endif
                                <button class="action-btn edit" onclick="editTodo({{ $todo['id'] }})" title="{{ __('todos.overview.edit_todo') }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Overdue To-Dos -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">{{ __('todos.overview.overdue_todos') }}</h2>
                <div class="section-actions">
                    <span class="overdue-count">{{ count($overdueTodos) }} {{ __('todos.overview.items') }}</span>
                </div>
            </div>
            
            <div class="overdue-list">
                @forelse($overdueTodos as $todo)
                <div class="overdue-item priority-{{ $todo['priority'] }}">
                    <div class="overdue-content">
                        <h4 class="overdue-title">{{ $todo['title'] }}</h4>
                        <div class="overdue-meta">
                            <span class="staff-info">{{ $todo['staff_name'] }} ({{ $todo['role'] }})</span>
                            <span class="category">{{ $todo['category'] }}</span>
                        </div>
                        <div class="overdue-details">
                            <span class="due-time">{{ __('todos.overview.due_at') }}: {{ $todo['due_time'] }}</span>
                            <span class="overdue-duration overdue">
                                {{ $todo['overdue_minutes'] }} {{ __('todos.overview.minutes_overdue') }}
                            </span>
                        </div>
                    </div>
                    <div class="overdue-actions">
                        <button class="btn btn-sm btn-warning" onclick="escalateTodo({{ $todo['id'] }})">
                            {{ __('todos.overview.escalate') }}
                        </button>
                        <button class="btn btn-sm btn-primary" onclick="completeTodo({{ $todo['id'] }})">
                            {{ __('todos.overview.complete') }}
                        </button>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p>{{ __('todos.overview.no_overdue') }}</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Completion Trends Chart -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">{{ __('todos.overview.completion_trends') }}</h2>
                <div class="section-actions">
                    <select class="trend-period-select" onchange="updateTrendPeriod(this.value)">
                        <option value="7">{{ __('todos.overview.last_7_days') }}</option>
                        <option value="30">{{ __('todos.overview.last_30_days') }}</option>
                        <option value="90">{{ __('todos.overview.last_90_days') }}</option>
                    </select>
                </div>
            </div>
            
            <div class="chart-container">
                <canvas id="completionTrendsChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Staff Performance -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">{{ __('todos.overview.staff_performance') }}</h2>
                <div class="section-actions">
                    <button class="btn btn-sm btn-secondary" onclick="viewDetailedPerformance()">
                        {{ __('todos.overview.view_details') }}
                    </button>
                </div>
            </div>
            
            <div class="performance-list">
                @foreach($staffPerformance as $staff)
                <div class="performance-item">
                    <div class="performance-header">
                        <div class="staff-info">
                            <h4 class="staff-name">{{ $staff['staff_name'] }}</h4>
                            <span class="staff-role">{{ $staff['role'] }}</span>
                        </div>
                        <div class="performance-trend trend-{{ $staff['performance_trend'] }}">
                            @if($staff['performance_trend'] === 'improving')
                            <svg class="trend-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            @elseif($staff['performance_trend'] === 'declining')
                            <svg class="trend-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                            </svg>
                            @else
                            <svg class="trend-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                            </svg>
                            @endif
                            <span>{{ __('todos.overview.' . $staff['performance_trend']) }}</span>
                        </div>
                    </div>
                    <div class="performance-metrics">
                        <div class="metric">
                            <span class="metric-label">{{ __('todos.overview.week_completion') }}</span>
                            <span class="metric-value">{{ number_format($staff['completion_rate_week'], 1) }}%</span>
                        </div>
                        <div class="metric">
                            <span class="metric-label">{{ __('todos.overview.month_completion') }}</span>
                            <span class="metric-value">{{ number_format($staff['completion_rate_month'], 1) }}%</span>
                        </div>
                        <div class="metric">
                            <span class="metric-label">{{ __('todos.overview.avg_time') }}</span>
                            <span class="metric-value">{{ $staff['average_completion_time'] }}h</span>
                        </div>
                        <div class="metric">
                            <span class="metric-label">{{ __('todos.overview.streak') }}</span>
                            <span class="metric-value">{{ $staff['streak_days'] }} {{ __('todos.overview.days') }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Upcoming Recurring Tasks -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">{{ __('todos.overview.upcoming_tasks') }}</h2>
                <div class="section-actions">
                    <button class="btn btn-sm btn-secondary" onclick="viewSchedule()">
                        {{ __('todos.overview.view_schedule') }}
                    </button>
                </div>
            </div>
            
            <div class="upcoming-list">
                @foreach($upcomingTasks as $task)
                <div class="upcoming-item priority-{{ $task['priority'] }}">
                    <div class="upcoming-content">
                        <h4 class="upcoming-title">{{ $task['title'] }}</h4>
                        <div class="upcoming-meta">
                            <span class="category">{{ $task['category'] }}</span>
                            <span class="assigned-to">{{ $task['assigned_to'] }}</span>
                        </div>
                        <div class="upcoming-details">
                            <span class="next-due">
                                {{ __('todos.overview.next_due') }}: {{ \Carbon\Carbon::parse($task['next_due'])->format('M j, H:i') }}
                            </span>
                            <span class="recurring-type">{{ __('todos.overview.' . $task['recurring_type']) }}</span>
                            <span class="duration">{{ $task['estimated_duration'] }} {{ __('todos.overview.minutes') }}</span>
                        </div>
                    </div>
                    <div class="upcoming-actions">
                        <button class="btn btn-sm btn-secondary" onclick="editRecurringTask({{ $task['id'] }})">
                            {{ __('todos.overview.edit') }}
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Quick Add Modal -->
<div id="quickAddModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>{{ __('todos.overview.quick_add_todo') }}</h3>
            <button class="modal-close" onclick="closeQuickAddModal()">&times;</button>
        </div>
        <form id="quickAddForm" onsubmit="quickAddTodo(event)">
            <div class="modal-body">
                <div class="form-group">
                    <label for="todoTitle">{{ __('todos.overview.todo_title') }} *</label>
                    <input type="text" id="todoTitle" name="title" required>
                </div>
                <div class="form-group">
                    <label for="assignedStaff">{{ __('todos.overview.assign_to') }} *</label>
                    <select id="assignedStaff" name="assigned_staff" required>
                        <option value="">{{ __('todos.overview.select_staff') }}</option>
                        @foreach($todaysTodos as $staff)
                        <option value="{{ $staff['staff_id'] }}">{{ $staff['staff_name'] }} ({{ $staff['role'] }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="todoPriority">{{ __('todos.overview.priority') }} *</label>
                    <select id="todoPriority" name="priority" required>
                        <option value="normal">{{ __('todos.overview.normal') }}</option>
                        <option value="high">{{ __('todos.overview.high') }}</option>
                        <option value="urgent">{{ __('todos.overview.urgent') }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="dueTime">{{ __('todos.overview.due_time') }}</label>
                    <input type="time" id="dueTime" name="due_time">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeQuickAddModal()">
                    {{ __('todos.overview.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary">
                    {{ __('todos.overview.add_todo') }}
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('styles')
@vite(['resources/css/admin/todos/overview.css'])
@endpush

@push('scripts')
@vite(['resources/js/admin/todos/overview.js'])
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Pass data to JavaScript
    window.completionTrends = @json($completionTrends);
    window.staffPerformance = @json($staffPerformance);
    window.statistics = @json($statistics);
</script>
@endpush
