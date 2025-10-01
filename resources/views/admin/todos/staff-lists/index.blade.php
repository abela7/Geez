@extends('layouts.admin')

@section('title', __('todos.staff_lists.title'))

@section('content')
<div class="staff-lists-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-header-left">
                <h1 class="page-title">{{ __('todos.staff_lists.title') }}</h1>
                <p class="page-description">{{ __('todos.staff_lists.description') }}</p>
            </div>
            <div class="page-header-right">
                <button class="btn btn-primary" @click="openAssignModal()">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('todos.staff_lists.assign_todo') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <div class="filters-row">
            <div class="filter-group">
                <label class="filter-label">{{ __('todos.staff_lists.filter_by_department') }}</label>
                <select class="filter-select" x-model="filters.department" @change="applyFilters()">
                    @foreach($departments as $department)
                        <option value="{{ $department }}">{{ $department }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">{{ __('todos.staff_lists.filter_by_role') }}</label>
                <select class="filter-select" x-model="filters.role" @change="applyFilters()">
                    @foreach($roles as $role)
                        <option value="{{ $role }}">{{ $role }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">{{ __('todos.staff_lists.filter_by_status') }}</label>
                <select class="filter-select" x-model="filters.status" @change="applyFilters()">
                    @foreach($statuses as $status)
                        <option value="{{ $status }}">{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">{{ __('todos.staff_lists.search') }}</label>
                <div class="search-input-group">
                    <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" class="search-input" 
                           x-model="filters.search" 
                           @input="applyFilters()"
                           :placeholder="__('todos.staff_lists.search_placeholder')">
                </div>
            </div>
            
            <div class="filter-actions">
                <button class="btn btn-secondary" @click="clearFilters()">
                    {{ __('todos.staff_lists.clear_filters') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Staff Cards Grid -->
    <div class="staff-grid" x-data="staffListsData()">
        @foreach($staffMembers as $staff)
        <div class="staff-card" 
             x-show="isStaffVisible({{ json_encode($staff) }})"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            
            <!-- Staff Header -->
            <div class="staff-header">
                <div class="staff-info">
                    <img src="{{ $staff['avatar'] }}" alt="{{ $staff['name'] }}" class="staff-avatar">
                    <div class="staff-details">
                        <h3 class="staff-name">{{ $staff['name'] }}</h3>
                        <p class="staff-role">{{ $staff['role'] }} • {{ $staff['department'] }}</p>
                        <div class="staff-status">
                            <span class="status-badge status-{{ $staff['status'] }}">
                                {{ ucfirst($staff['status']) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="staff-actions">
                    <a href="{{ route('admin.todos.staff-lists.show', $staff['id']) }}" 
                       class="btn btn-sm btn-outline">
                        {{ __('todos.staff_lists.view_details') }}
                    </a>
                </div>
            </div>

            <!-- Performance Metrics -->
            <div class="performance-metrics">
                <div class="metric">
                    <div class="metric-value">{{ $staff['total_todos'] }}</div>
                    <div class="metric-label">{{ __('todos.staff_lists.total_todos') }}</div>
                </div>
                <div class="metric">
                    <div class="metric-value">{{ $staff['completed_todos'] }}</div>
                    <div class="metric-label">{{ __('todos.staff_lists.completed') }}</div>
                </div>
                <div class="metric">
                    <div class="metric-value">{{ $staff['overdue_todos'] }}</div>
                    <div class="metric-label">{{ __('todos.staff_lists.overdue') }}</div>
                </div>
                <div class="metric">
                    <div class="metric-value">{{ $staff['completion_rate'] }}%</div>
                    <div class="metric-label">{{ __('todos.staff_lists.completion_rate') }}</div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="progress-section">
                <div class="progress-bar">
                    <div class="progress-fill" 
                         :style="`width: {{ $staff['completion_rate'] }}%`"
                         :class="getProgressColor({{ $staff['completion_rate'] }})">
                    </div>
                </div>
                <div class="progress-text">
                    {{ $staff['completion_rate'] }}% {{ __('todos.staff_lists.complete') }}
                </div>
            </div>

            <!-- Recent Todos -->
            <div class="recent-todos">
                <h4 class="todos-title">{{ __('todos.staff_lists.recent_todos') }}</h4>
                <div class="todos-list">
                    @foreach(array_slice($staff['todos'], 0, 3) as $todo)
                    <div class="todo-item todo-{{ $todo['status'] }}">
                        <div class="todo-priority priority-{{ $todo['priority'] }}"></div>
                        <div class="todo-content">
                            <div class="todo-title">{{ $todo['title'] }}</div>
                            <div class="todo-meta">
                                <span class="todo-status status-{{ $todo['status'] }}">
                                    {{ __('todos.staff_lists.status_' . $todo['status']) }}
                                </span>
                                <span class="todo-due">
                                    {{ \Carbon\Carbon::parse($todo['due_date'])->format('H:i') }}
                                </span>
                            </div>
                        </div>
                        <div class="todo-actions">
                            @if($todo['status'] === 'pending')
                                <button class="btn btn-sm btn-success" 
                                        @click="startTodo({{ $todo['id'] }})">
                                    {{ __('todos.staff_lists.start') }}
                                </button>
                            @elseif($todo['status'] === 'in_progress')
                                <button class="btn btn-sm btn-primary" 
                                        @click="completeTodo({{ $todo['id'] }})">
                                    {{ __('todos.staff_lists.complete') }}
                                </button>
                            @elseif($todo['status'] === 'completed')
                                <svg class="todo-completed-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                
                @if(count($staff['todos']) > 3)
                <div class="view-all-todos">
                    <a href="{{ route('admin.todos.staff-lists.show', $staff['id']) }}" 
                       class="view-all-link">
                        {{ __('todos.staff_lists.view_all_todos') }} ({{ count($staff['todos']) }})
                    </a>
                </div>
                @endif
            </div>

            <!-- Last Active -->
            <div class="last-active">
                <svg class="last-active-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ __('todos.staff_lists.last_active') }}: {{ \Carbon\Carbon::parse($staff['last_active'])->diffForHumans() }}
            </div>
        </div>
        @endforeach
    </div>

    <!-- Empty State -->
    <div x-show="filteredStaff.length === 0" class="empty-state">
        <div class="empty-state-content">
            <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <h3 class="empty-state-title">{{ __('todos.staff_lists.no_staff_found') }}</h3>
            <p class="empty-state-description">{{ __('todos.staff_lists.no_staff_found_description') }}</p>
            <button class="btn btn-primary" @click="clearFilters()">
                {{ __('todos.staff_lists.clear_filters') }}
            </button>
        </div>
    </div>
</div>

<!-- Assign Todo Modal -->
<div x-data="{ showAssignModal: false }" 
     x-show="showAssignModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="modal-overlay"
     @click.self="showAssignModal = false">
    
    <div class="modal-content"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">
        
        <div class="modal-header">
            <h3 class="modal-title">{{ __('todos.staff_lists.assign_todo') }}</h3>
            <button class="modal-close" @click="showAssignModal = false">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form class="modal-body" @submit.prevent="assignTodo()">
            <div class="form-group">
                <label class="form-label">{{ __('todos.staff_lists.select_staff') }}</label>
                <select class="form-select" x-model="assignForm.staff_id" required>
                    <option value="">{{ __('todos.staff_lists.choose_staff') }}</option>
                    @foreach($staffMembers as $staff)
                        <option value="{{ $staff['id'] }}">{{ $staff['name'] }} ({{ $staff['role'] }})</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">{{ __('todos.staff_lists.todo_title') }}</label>
                <input type="text" class="form-input" 
                       x-model="assignForm.title" 
                       :placeholder="__('todos.staff_lists.todo_title_placeholder')"
                       required>
            </div>
            
            <div class="form-group">
                <label class="form-label">{{ __('todos.staff_lists.description') }}</label>
                <textarea class="form-textarea" 
                          x-model="assignForm.description"
                          :placeholder="__('todos.staff_lists.description_placeholder')"
                          rows="3"></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">{{ __('todos.staff_lists.priority') }}</label>
                    <select class="form-select" x-model="assignForm.priority" required>
                        <option value="normal">{{ __('todos.staff_lists.priority_normal') }}</option>
                        <option value="medium">{{ __('todos.staff_lists.priority_medium') }}</option>
                        <option value="high">{{ __('todos.staff_lists.priority_high') }}</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">{{ __('todos.staff_lists.due_date') }}</label>
                    <input type="datetime-local" class="form-input" 
                           x-model="assignForm.due_date" 
                           required>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">{{ __('todos.staff_lists.estimated_duration') }} ({{ __('todos.staff_lists.minutes') }})</label>
                <input type="number" class="form-input" 
                       x-model="assignForm.estimated_duration" 
                       min="1" 
                       :placeholder="__('todos.staff_lists.estimated_duration_placeholder')">
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="showAssignModal = false">
                    {{ __('todos.staff_lists.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary">
                    {{ __('todos.staff_lists.assign_todo') }}
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('styles')
@vite('resources/css/admin/todos/staff-lists.css')
@endpush

@push('scripts')
@vite('resources/js/admin/todos/staff-lists.js')
@endpush
