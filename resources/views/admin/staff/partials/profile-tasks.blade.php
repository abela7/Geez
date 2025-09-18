<div class="space-y-6">
    <!-- Task Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-background border border-main rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-blue-600">{{ $activeTasks->count() }}</div>
            <div class="text-sm text-secondary">{{ __('staff.active_tasks') }}</div>
        </div>
        <div class="bg-background border border-main rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-green-600">{{ $stats['task_completion_rate'] }}%</div>
            <div class="text-sm text-secondary">{{ __('staff.completion_rate') }}</div>
        </div>
        <div class="bg-background border border-main rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-orange-600">{{ $activeTasks->where('due_date', '<', now())->count() }}</div>
            <div class="text-sm text-secondary">{{ __('staff.overdue_tasks') }}</div>
        </div>
    </div>

    <!-- Active Tasks -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-primary">{{ __('staff.active_tasks') }}</h3>
            <button class="btn btn-secondary btn-sm" onclick="alert('{{ __('common.coming_soon') }}')">
                <i class="fas fa-plus mr-2"></i>{{ __('staff.assign_task') }}
            </button>
        </div>

        @if ($activeTasks->count() > 0)
        <div class="space-y-4">
            @foreach ($activeTasks as $taskAssignment)
            <div class="bg-background border border-main rounded-lg p-4">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h4 class="font-medium text-primary">{{ $taskAssignment->task->title }}</h4>
                            @php
                                $priorityColors = [
                                    'low' => 'bg-gray-100 text-gray-800',
                                    'medium' => 'bg-blue-100 text-blue-800',
                                    'high' => 'bg-orange-100 text-orange-800',
                                    'urgent' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $priorityColors[$taskAssignment->task->priority] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($taskAssignment->task->priority) }}
                            </span>
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'in_progress' => 'bg-blue-100 text-blue-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-gray-100 text-gray-800',
                                    'overdue' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$taskAssignment->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst(str_replace('_', ' ', $taskAssignment->status)) }}
                            </span>
                        </div>
                        
                        @if ($taskAssignment->task->description)
                        <p class="text-secondary text-sm mb-3">{{ $taskAssignment->task->description }}</p>
                        @endif
                        
                        <div class="flex items-center gap-4 text-sm text-secondary">
                            <span class="flex items-center gap-1">
                                <i class="fas fa-calendar"></i>
                                {{ __('staff.assigned') }}: {{ $taskAssignment->assigned_date->format('M d, Y') }}
                            </span>
                            @if ($taskAssignment->due_date)
                            <span class="flex items-center gap-1 {{ $taskAssignment->due_date->isPast() ? 'text-red-600' : '' }}">
                                <i class="fas fa-clock"></i>
                                {{ __('staff.due') }}: {{ $taskAssignment->due_date->format('M d, Y') }}
                                @if ($taskAssignment->due_date->isPast())
                                    ({{ __('staff.overdue') }})
                                @endif
                            </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2 ml-4">
                        @if ($taskAssignment->status === 'pending')
                        <button class="btn btn-sm btn-primary" onclick="alert('{{ __('common.coming_soon') }}')">
                            {{ __('staff.start_task') }}
                        </button>
                        @elseif ($taskAssignment->status === 'in_progress')
                        <button class="btn btn-sm btn-success" onclick="alert('{{ __('common.coming_soon') }}')">
                            {{ __('staff.complete_task') }}
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-background border border-main rounded-lg p-8 text-center">
            <i class="fas fa-tasks text-4xl text-muted mb-4"></i>
            <h3 class="text-lg font-medium text-primary mb-2">{{ __('staff.no_active_tasks') }}</h3>
            <p class="text-secondary">{{ __('staff.no_active_tasks_description') }}</p>
        </div>
        @endif
    </div>

    <!-- Task Performance (Placeholder) -->
    <div>
        <h3 class="text-lg font-semibold text-primary mb-4">{{ __('staff.task_performance') }}</h3>
        <div class="bg-background border border-main rounded-lg p-8 text-center">
            <i class="fas fa-chart-line text-4xl text-muted mb-4"></i>
            <h3 class="text-lg font-medium text-primary mb-2">{{ __('common.coming_soon') }}</h3>
            <p class="text-secondary">{{ __('staff.task_analytics_description') }}</p>
        </div>
    </div>
</div>
