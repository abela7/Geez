@extends('layouts.admin')

@section('title', __('staff.tasks.title') . ' - ' . config('app.name'))
@section('page_title', __('staff.tasks.title'))

@push('styles')
@vite(['resources/css/admin/staff-tasks.css'])
@endpush

@push('scripts')
@vite(['resources/js/admin/staff-tasks.js'])
@endpush

@section('content')
<div class="tasks-container" x-data="taskManager()" x-init="init()">
    <!-- Page Header -->
    <div class="tasks-header">
        <div class="tasks-header-content">
            <div class="tasks-title-section">
                <h1 class="page-title">{{ __('staff.tasks.title') }}</h1>
                <p class="page-subtitle">{{ __('staff.tasks.subtitle') }}</p>
            </div>
            
            <!-- Primary Actions -->
            <div class="tasks-header-actions">
                <button @click="openTaskModal()" class="btn btn-primary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('staff.tasks.create_task') }}
                </button>
                
                <div class="tasks-view-toggle" role="tablist" aria-label="{{ __('staff.tasks.view_modes') }}">
                    <button @click="setView('dashboard')" 
                            :class="{ 'active': currentView === 'dashboard' }"
                            class="view-toggle-btn"
                            role="tab"
                            :aria-selected="currentView === 'dashboard'"
                            aria-controls="dashboard-view">
                        <svg class="view-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        {{ __('staff.tasks.dashboard_view') }}
                    </button>
                    
                    <button @click="setView('kanban')" 
                            :class="{ 'active': currentView === 'kanban' }"
                            class="view-toggle-btn"
                            role="tab"
                            :aria-selected="currentView === 'kanban'"
                            aria-controls="kanban-view">
                        <svg class="view-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 0V17m0-10a2 2 0 012-2h2a2 2 0 012 2v10a2 2 0 01-2 2h-2a2 2 0 01-2-2"/>
                        </svg>
                        {{ __('staff.tasks.kanban_view') }}
                    </button>
                    
                    <button @click="setView('list')" 
                            :class="{ 'active': currentView === 'list' }"
                            class="view-toggle-btn"
                            role="tab"
                            :aria-selected="currentView === 'list'"
                            aria-controls="list-view">
                        <svg class="view-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        {{ __('staff.tasks.list_view') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard View -->
    <div x-show="currentView === 'dashboard'" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-4"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         id="dashboard-view"
         role="tabpanel">
        
        <!-- Task Statistics -->
        <div class="tasks-stats-grid">
            <div class="stat-card stat-card--primary">
                <div class="stat-card-header">
                    <div class="stat-card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <span class="stat-card-label">{{ __('staff.tasks.total_tasks') }}</span>
                </div>
                <div class="stat-card-value" x-text="taskStats.total">0</div>
                <div class="stat-card-trend stat-card-trend--positive">
                    <svg class="trend-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    <span>+5 {{ __('staff.tasks.this_week') }}</span>
                </div>
            </div>

            <div class="stat-card stat-card--success">
                <div class="stat-card-header">
                    <div class="stat-card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="stat-card-label">{{ __('staff.tasks.completed') }}</span>
                </div>
                <div class="stat-card-value" x-text="taskStats.completed">0</div>
                <div class="stat-card-trend stat-card-trend--positive">
                    <svg class="trend-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    <span>+12 {{ __('staff.tasks.this_week') }}</span>
                </div>
            </div>

            <div class="stat-card stat-card--warning">
                <div class="stat-card-header">
                    <div class="stat-card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="stat-card-label">{{ __('staff.tasks.in_progress') }}</span>
                </div>
                <div class="stat-card-value" x-text="taskStats.inProgress">0</div>
                <div class="stat-card-trend stat-card-trend--neutral">
                    <svg class="trend-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                    </svg>
                    <span>{{ __('staff.tasks.no_change') }}</span>
                </div>
            </div>

            <div class="stat-card stat-card--danger">
                <div class="stat-card-header">
                    <div class="stat-card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <span class="stat-card-label">{{ __('staff.tasks.overdue') }}</span>
                </div>
                <div class="stat-card-value" x-text="taskStats.overdue">0</div>
                <div class="stat-card-trend stat-card-trend--negative">
                    <svg class="trend-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                    </svg>
                    <span>-2 {{ __('staff.tasks.this_week') }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Recent Tasks -->
        <div class="dashboard-content-grid">
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('staff.tasks.quick_actions') }}</h3>
                </div>
                <div class="card-body">
                    <div class="quick-actions-grid">
                        <button @click="openTaskModal('high')" class="quick-action-btn quick-action-btn--urgent">
                            <div class="quick-action-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                            </div>
                            <span>{{ __('staff.tasks.urgent_task') }}</span>
                        </button>

                        <button @click="openTaskModal('routine')" class="quick-action-btn quick-action-btn--routine">
                            <div class="quick-action-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span>{{ __('staff.tasks.routine_task') }}</span>
                        </button>

                        <button @click="openBulkAssign()" class="quick-action-btn quick-action-btn--bulk">
                            <div class="quick-action-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <span>{{ __('staff.tasks.bulk_assign') }}</span>
                        </button>

                        <button @click="openTemplates()" class="quick-action-btn quick-action-btn--template">
                            <div class="quick-action-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <span>{{ __('staff.tasks.from_template') }}</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Recent Tasks -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('staff.tasks.recent_tasks') }}</h3>
                    <button @click="setView('list')" class="card-action">
                        {{ __('staff.tasks.view_all') }}
                        <svg class="card-action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
                <div class="card-body">
                    <div class="recent-tasks-list">
                        <template x-for="task in recentTasks" :key="task.id">
                            <div class="recent-task-item" @click="viewTask(task)">
                                <div class="recent-task-priority" :class="`priority-${task.priority}`"></div>
                                <div class="recent-task-content">
                                    <h4 class="recent-task-title" x-text="task.title"></h4>
                                    <p class="recent-task-assignee" x-text="task.assignee"></p>
                                </div>
                                <div class="recent-task-meta">
                                    <span class="recent-task-status" :class="`status-${task.status}`" x-text="task.status"></span>
                                    <span class="recent-task-date" x-text="task.due_date"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.staff.partials.tasks-kanban')
    @include('admin.staff.partials.tasks-list')
    @include('admin.staff.partials.tasks-modal')
</div>
@endsection