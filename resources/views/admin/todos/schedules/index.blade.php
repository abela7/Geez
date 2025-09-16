@extends('layouts.admin')

@section('title', __('todos.schedules.title'))

@section('content')
<div class="schedules-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-header-left">
                <h1 class="page-title">{{ __('todos.schedules.title') }}</h1>
                <p class="page-description">{{ __('todos.schedules.subtitle') }}</p>
            </div>
            <div class="page-header-right">
                <button class="btn btn-secondary" @click="refreshSchedules()">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    {{ __('todos.common.refresh') }}
                </button>
                <a href="{{ route('admin.todos.schedules.create') }}" class="btn btn-primary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('todos.schedules.create_schedule') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-section">
        <div class="filters-row">
            <div class="filter-group">
                <label class="filter-label">{{ __('todos.schedules.frequency') }}</label>
                <select class="filter-select" x-model="filters.frequency" @change="applyFilters()">
                    @foreach($frequencies as $key => $value)
                        <option value="{{ $key }}">{{ __('todos.schedules.' . $key) }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">{{ __('todos.schedules.status') }}</label>
                <select class="filter-select" x-model="filters.status" @change="applyFilters()">
                    @foreach($statuses as $key => $value)
                        <option value="{{ $key }}">{{ __('todos.schedules.' . $key) }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">{{ __('todos.common.search') }}</label>
                <div class="search-input-group">
                    <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" class="search-input" 
                           x-model="filters.search" 
                           @input="applyFilters()"
                           placeholder="{{ __('todos.schedules.search_placeholder') }}">
                </div>
            </div>
            
            <div class="filter-actions">
                <button class="btn btn-secondary" @click="clearFilters()">
                    {{ __('todos.common.clear_filter') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Schedules Grid -->
    <div class="schedules-grid" x-data="schedulesData()">
        @foreach($schedules as $schedule)
        <div class="schedule-card" 
             x-show="isScheduleVisible({{ json_encode($schedule) }})"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            
            <!-- Schedule Header -->
            <div class="schedule-header">
                <div class="schedule-info">
                    <div class="schedule-frequency frequency-{{ $schedule['frequency_type'] }}">
                        {{ __('todos.schedules.' . $schedule['frequency_type']) }}
                    </div>
                    <h3 class="schedule-name">{{ $schedule['name'] }}</h3>
                    <p class="schedule-description">{{ $schedule['description'] }}</p>
                </div>
                <div class="schedule-status">
                    <div class="status-toggle">
                        <input type="checkbox" 
                               class="status-checkbox" 
                               {{ $schedule['is_active'] ? 'checked' : '' }}
                               @change="toggleScheduleStatus({{ $schedule['id'] }})">
                        <span class="status-label status-{{ $schedule['is_active'] ? 'active' : 'inactive' }}">
                            {{ $schedule['is_active'] ? __('todos.schedules.active') : __('todos.schedules.inactive') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Schedule Details -->
            <div class="schedule-details">
                <div class="detail-item">
                    <svg class="detail-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="detail-label">{{ __('todos.schedules.frequency') }}:</span>
                    <span class="detail-value">{{ $schedule['frequency_display'] }}</span>
                </div>
                
                @if($schedule['template_name'])
                <div class="detail-item">
                    <svg class="detail-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="detail-label">{{ __('todos.schedules.template') }}:</span>
                    <span class="detail-value">{{ $schedule['template_name'] }}</span>
                </div>
                @endif
                
                @if(count($schedule['staff_names']) > 0)
                <div class="detail-item">
                    <svg class="detail-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="detail-label">{{ __('todos.schedules.assigned_staff') }}:</span>
                    <span class="detail-value">{{ implode(', ', array_slice($schedule['staff_names'], 0, 2)) }}{{ count($schedule['staff_names']) > 2 ? ' +' . (count($schedule['staff_names']) - 2) : '' }}</span>
                </div>
                @endif
                
                <div class="detail-item">
                    <svg class="detail-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="detail-label">{{ __('todos.schedules.date_range') }}:</span>
                    <span class="detail-value">
                        {{ \Carbon\Carbon::parse($schedule['start_date'])->format('M d, Y') }}
                        @if($schedule['end_date'])
                            - {{ \Carbon\Carbon::parse($schedule['end_date'])->format('M d, Y') }}
                        @else
                            - {{ __('todos.schedules.ongoing') }}
                        @endif
                    </span>
                </div>
            </div>

            <!-- Schedule Stats -->
            <div class="schedule-stats">
                <div class="stat-item">
                    <div class="stat-value">{{ $schedule['total_runs'] }}</div>
                    <div class="stat-label">{{ __('todos.schedules.total_runs') }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $schedule['successful_runs'] }}</div>
                    <div class="stat-label">{{ __('todos.schedules.successful') }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $schedule['total_runs'] > 0 ? round(($schedule['successful_runs'] / $schedule['total_runs']) * 100) : 0 }}%</div>
                    <div class="stat-label">{{ __('todos.schedules.success_rate') }}</div>
                </div>
            </div>

            <!-- Next/Last Run -->
            <div class="schedule-timing">
                <div class="timing-item">
                    <div class="timing-label">{{ __('todos.schedules.next_run') }}</div>
                    <div class="timing-value next-run">
                        {{ \Carbon\Carbon::parse($schedule['next_run'])->format('M d, Y H:i') }}
                        <span class="timing-relative">({{ \Carbon\Carbon::parse($schedule['next_run'])->diffForHumans() }})</span>
                    </div>
                </div>
                <div class="timing-item">
                    <div class="timing-label">{{ __('todos.schedules.last_run') }}</div>
                    <div class="timing-value last-run">
                        {{ \Carbon\Carbon::parse($schedule['last_run'])->format('M d, Y H:i') }}
                        <span class="timing-relative">({{ \Carbon\Carbon::parse($schedule['last_run'])->diffForHumans() }})</span>
                    </div>
                </div>
            </div>

            <!-- Schedule Actions -->
            <div class="schedule-actions">
                <a href="{{ route('admin.todos.schedules.show', $schedule['id']) }}" 
                   class="btn btn-sm btn-outline">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    {{ __('todos.common.view') }}
                </a>
                
                <a href="{{ route('admin.todos.schedules.edit', $schedule['id']) }}" 
                   class="btn btn-sm btn-secondary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    {{ __('todos.common.edit') }}
                </a>
                
                @if($schedule['is_active'])
                    <button class="btn btn-sm btn-warning" @click="deactivateSchedule({{ $schedule['id'] }})">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ __('todos.schedules.deactivate') }}
                    </button>
                @else
                    <button class="btn btn-sm btn-success" @click="activateSchedule({{ $schedule['id'] }})">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m-9 4h10a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ __('todos.schedules.activate') }}
                    </button>
                @endif
                
                <button class="btn btn-sm btn-danger" @click="deleteSchedule({{ $schedule['id'] }})">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    {{ __('todos.common.delete') }}
                </button>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Empty State -->
    <div x-show="filteredSchedules.length === 0" class="empty-state">
        <div class="empty-state-content">
            <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <h3 class="empty-state-title">{{ __('todos.schedules.no_schedules_found') }}</h3>
            <p class="empty-state-description">{{ __('todos.schedules.no_schedules_found_description') }}</p>
            <div class="empty-state-actions">
                <a href="{{ route('admin.todos.schedules.create') }}" class="btn btn-primary">
                    {{ __('todos.schedules.create_schedule') }}
                </a>
                <button class="btn btn-secondary" @click="clearFilters()">
                    {{ __('todos.common.clear_filter') }}
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
@vite('resources/css/admin/todos/schedules.css')
@endpush

@push('scripts')
@vite('resources/js/admin/todos/schedules.js')
@endpush
