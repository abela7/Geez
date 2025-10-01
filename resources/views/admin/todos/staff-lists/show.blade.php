@extends('layouts.admin')

@section('title', __('todos.staff_lists.staff_details') . ' - ' . $staffMember['name'])

@section('content')
<div class="staff-details-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-header-left">
                <div class="breadcrumb">
                    <a href="{{ route('admin.todos.staff-lists.index') }}" class="breadcrumb-link">
                        {{ __('todos.staff_lists.title') }}
                    </a>
                    <span class="breadcrumb-separator">/</span>
                    <span class="breadcrumb-current">{{ $staffMember['name'] }}</span>
                </div>
                <h1 class="page-title">{{ $staffMember['name'] }}</h1>
                <p class="page-description">{{ $staffMember['role'] }} • {{ $staffMember['department'] }}</p>
            </div>
            <div class="page-header-right">
                <button class="btn btn-secondary" @click="goBack()">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('todos.staff_lists.back_to_list') }}
                </button>
                <button class="btn btn-primary" @click="openAssignModal()">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('todos.staff_lists.assign_todo') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Staff Info Card -->
    <div class="staff-info-card">
        <div class="staff-info-header">
            <img src="{{ $staffMember['avatar'] }}" alt="{{ $staffMember['name'] }}" class="staff-avatar-large">
            <div class="staff-info-details">
                <h2 class="staff-name">{{ $staffMember['name'] }}</h2>
                <p class="staff-role">{{ $staffMember['role'] }} • {{ $staffMember['department'] }}</p>
                <div class="staff-contact">
                    <div class="contact-item">
                        <svg class="contact-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ $staffMember['email'] }}
                    </div>
                    <div class="contact-item">
                        <svg class="contact-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        {{ $staffMember['phone'] }}
                    </div>
                    <div class="contact-item">
                        <svg class="contact-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ __('todos.staff_lists.hired_on') }} {{ \Carbon\Carbon::parse($staffMember['hire_date'])->format('M d, Y') }}
                    </div>
                </div>
            </div>
            <div class="staff-status-section">
                <div class="status-badge status-{{ $staffMember['status'] }}">
                    {{ ucfirst($staffMember['status']) }}
                </div>
                <div class="performance-score">
                    <div class="score-circle">
                        <svg class="score-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="score-details">
                        <div class="score-value">{{ $staffMember['performance_score'] }}%</div>
                        <div class="score-label">{{ __('todos.staff_lists.performance_score') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <div class="metric-content">
                <div class="metric-value">{{ $staffMember['total_todos'] }}</div>
                <div class="metric-label">{{ __('todos.staff_lists.total_todos') }}</div>
            </div>
        </div>
        
        <div class="metric-card">
            <div class="metric-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div class="metric-content">
                <div class="metric-value">{{ $staffMember['completed_todos'] }}</div>
                <div class="metric-label">{{ __('todos.staff_lists.completed') }}</div>
            </div>
        </div>
        
        <div class="metric-card">
            <div class="metric-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="metric-content">
                <div class="metric-value">{{ $staffMember['overdue_todos'] }}</div>
                <div class="metric-label">{{ __('todos.staff_lists.overdue') }}</div>
            </div>
        </div>
        
        <div class="metric-card">
            <div class="metric-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
            <div class="metric-content">
                <div class="metric-value">{{ $staffMember['completion_rate'] }}%</div>
                <div class="metric-label">{{ __('todos.staff_lists.completion_rate') }}</div>
            </div>
        </div>
    </div>

    <!-- Todos Section -->
    <div class="todos-section">
        <div class="todos-header">
            <h3 class="todos-title">{{ __('todos.staff_lists.all_todos') }}</h3>
            <div class="todos-filters">
                <select class="filter-select" x-model="todoFilter" @change="filterTodos()">
                    <option value="all">{{ __('todos.staff_lists.filter_all') }}</option>
                    <option value="pending">{{ __('todos.staff_lists.filter_pending') }}</option>
                    <option value="in_progress">{{ __('todos.staff_lists.filter_in_progress') }}</option>
                    <option value="completed">{{ __('todos.staff_lists.filter_completed') }}</option>
                    <option value="overdue">{{ __('todos.staff_lists.filter_overdue') }}</option>
                </select>
            </div>
        </div>
        
        <div class="todos-list" x-data="staffDetailsData()">
            @foreach($staffMember['todos'] as $todo)
            <div class="todo-card" 
                 x-show="isTodoVisible({{ json_encode($todo) }})"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                
                <div class="todo-header">
                    <div class="todo-priority priority-{{ $todo['priority'] }}"></div>
                    <div class="todo-title">{{ $todo['title'] }}</div>
                    <div class="todo-status status-{{ $todo['status'] }}">
                        {{ __('todos.staff_lists.status_' . $todo['status']) }}
                    </div>
                </div>
                
                @if($todo['description'])
                <div class="todo-description">{{ $todo['description'] }}</div>
                @endif
                
                <div class="todo-meta">
                    <div class="todo-due">
                        <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ __('todos.staff_lists.due') }}: {{ \Carbon\Carbon::parse($todo['due_date'])->format('M d, Y H:i') }}
                    </div>
                    
                    @if(isset($todo['estimated_duration']))
                    <div class="todo-duration">
                        <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ __('todos.staff_lists.estimated') }}: {{ $todo['estimated_duration'] }} {{ __('todos.staff_lists.minutes') }}
                    </div>
                    @endif
                    
                    @if($todo['recurring'])
                    <div class="todo-recurring">
                        <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        {{ __('todos.staff_lists.recurring') }}: {{ ucfirst($todo['frequency']) }}
                    </div>
                    @endif
                </div>
                
                @if($todo['status'] === 'completed' && isset($todo['completed_at']))
                <div class="todo-completed">
                    <svg class="completed-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ __('todos.staff_lists.completed_at') }}: {{ \Carbon\Carbon::parse($todo['completed_at'])->format('M d, Y H:i') }}
                </div>
                @endif
                
                <div class="todo-actions">
                    @if($todo['status'] === 'pending')
                        <button class="btn btn-sm btn-primary" @click="startTodo({{ $todo['id'] }})">
                            {{ __('todos.staff_lists.start') }}
                        </button>
                        <button class="btn btn-sm btn-secondary" @click="editTodo({{ $todo['id'] }})">
                            {{ __('todos.staff_lists.edit') }}
                        </button>
                    @elseif($todo['status'] === 'in_progress')
                        <button class="btn btn-sm btn-success" @click="completeTodo({{ $todo['id'] }})">
                            {{ __('todos.staff_lists.complete') }}
                        </button>
                        <button class="btn btn-sm btn-secondary" @click="editTodo({{ $todo['id'] }})">
                            {{ __('todos.staff_lists.edit') }}
                        </button>
                    @elseif($todo['status'] === 'completed')
                        <button class="btn btn-sm btn-outline" @click="reopenTodo({{ $todo['id'] }})">
                            {{ __('todos.staff_lists.reopen') }}
                        </button>
                    @endif
                    
                    <button class="btn btn-sm btn-danger" @click="deleteTodo({{ $todo['id'] }})">
                        {{ __('todos.staff_lists.delete') }}
                    </button>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Empty State -->
        <div x-show="filteredTodos.length === 0" class="empty-state">
            <div class="empty-state-content">
                <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <h3 class="empty-state-title">{{ __('todos.staff_lists.no_todos_found') }}</h3>
                <p class="empty-state-description">{{ __('todos.staff_lists.no_todos_found_description') }}</p>
                <button class="btn btn-primary" @click="openAssignModal()">
                    {{ __('todos.staff_lists.assign_todo') }}
                </button>
            </div>
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
