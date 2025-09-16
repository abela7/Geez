@extends('layouts.admin')

@section('title', __('activities.manage.title'))

@section('content')
<div class="activities-manage-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-header-left">
                <h1 class="page-title">{{ __('activities.manage.title') }}</h1>
                <p class="page-description">{{ __('activities.manage.subtitle') }}</p>
            </div>
            <div class="page-header-right">
                <a href="{{ route('admin.activities.manage.settings') }}" class="btn btn-outline">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ __('activities.manage.settings') }}
                </a>
                <button class="btn btn-secondary" @click="refreshActivities()">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    {{ __('activities.common.refresh') }}
                </button>
                <a href="{{ route('admin.activities.manage.create') }}" class="btn btn-primary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('activities.manage.create_activity') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-section">
        <div class="filters-row">
            <div class="filter-group">
                <label class="filter-label">{{ __('activities.manage.category') }}</label>
                <select class="filter-select" x-model="filters.category" @change="applyFilters()">
                    <option value="all">{{ __('activities.manage.all_categories') }}</option>
                    @foreach($categories as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">{{ __('activities.manage.department') }}</label>
                <select class="filter-select" x-model="filters.department" @change="applyFilters()">
                    <option value="all">{{ __('activities.manage.all_departments') }}</option>
                    @foreach($departments as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">{{ __('activities.manage.difficulty_level') }}</label>
                <select class="filter-select" x-model="filters.difficulty" @change="applyFilters()">
                    <option value="all">{{ __('activities.manage.all_difficulties') }}</option>
                    <option value="easy">{{ __('activities.manage.easy') }}</option>
                    <option value="medium">{{ __('activities.manage.medium') }}</option>
                    <option value="hard">{{ __('activities.manage.hard') }}</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">{{ __('activities.common.search') }}</label>
                <div class="search-input-group">
                    <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" class="search-input" 
                           x-model="filters.search" 
                           @input="applyFilters()"
                           placeholder="{{ __('activities.manage.search_placeholder') }}">
                </div>
            </div>
            
            <div class="filter-actions">
                <button class="btn btn-secondary" @click="clearFilters()">
                    {{ __('activities.common.clear_filter') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Activities Grid -->
    <div class="activities-grid" x-data="activitiesData()">
        @foreach($activities as $activity)
        <div class="activity-card" 
             x-show="isActivityVisible({{ json_encode($activity) }})"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            
            <!-- Activity Header -->
            <div class="activity-header">
                <div class="activity-info">
                    <div class="activity-badges">
                        <div class="category-badge category-{{ strtolower(str_replace(' ', '-', $activity['category'])) }}">
                            {{ $activity['category'] }}
                        </div>
                        <div class="difficulty-badge difficulty-{{ $activity['difficulty_level'] }}">
                            {{ __('activities.manage.' . $activity['difficulty_level']) }}
                        </div>
                        <div class="department-badge department-{{ strtolower(str_replace(' ', '-', $activity['department'])) }}">
                            {{ $activity['department'] }}
                        </div>
                    </div>
                    <h3 class="activity-name">{{ $activity['name'] }}</h3>
                    <p class="activity-description">{{ $activity['description'] }}</p>
                </div>
                <div class="activity-status">
                    <div class="status-indicator status-{{ $activity['is_active'] ? 'active' : 'inactive' }}">
                        {{ $activity['is_active'] ? __('activities.common.active') : __('activities.common.inactive') }}
                    </div>
                </div>
            </div>

            <!-- Activity Details -->
            <div class="activity-details">
                <div class="detail-row">
                    <div class="detail-item">
                        <svg class="detail-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="detail-label">{{ __('activities.manage.estimated_duration') }}:</span>
                        <span class="detail-value">{{ $activity['estimated_duration'] }} {{ __('activities.common.minutes') }}</span>
                    </div>
                    
                    @if($activity['requires_equipment'])
                    <div class="detail-item">
                        <svg class="detail-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                        <span class="detail-label">{{ __('activities.manage.requires_equipment') }}</span>
                        <span class="detail-value equipment-required">{{ __('activities.common.yes') }}</span>
                    </div>
                    @endif
                </div>
                
                @if($activity['equipment_list'])
                <div class="equipment-list">
                    <h4 class="equipment-title">{{ __('activities.manage.equipment_list') }}:</h4>
                    <p class="equipment-items">{{ $activity['equipment_list'] }}</p>
                </div>
                @endif
                
                @if($activity['instructions'])
                <div class="instructions">
                    <h4 class="instructions-title">{{ __('activities.manage.instructions') }}:</h4>
                    <div class="instructions-content">
                        {!! nl2br(e($activity['instructions'])) !!}
                    </div>
                </div>
                @endif
            </div>

            <!-- Activity Stats -->
            <div class="activity-stats">
                <div class="stat-item">
                    <div class="stat-value">{{ $activity['total_logs'] }}</div>
                    <div class="stat-label">{{ __('activities.manage.total_logs') }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $activity['average_actual_duration'] }}min</div>
                    <div class="stat-label">{{ __('activities.manage.average_duration') }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $activity['assigned_staff_count'] }}</div>
                    <div class="stat-label">{{ __('activities.manage.assigned_staff') }}</div>
                </div>
            </div>

            <!-- Last Performed -->
            <div class="last-performed">
                <div class="last-performed-label">{{ __('activities.manage.last_performed') }}:</div>
                <div class="last-performed-value">
                    {{ \Carbon\Carbon::parse($activity['last_performed'])->format('M d, Y H:i') }}
                    <span class="last-performed-relative">({{ \Carbon\Carbon::parse($activity['last_performed'])->diffForHumans() }})</span>
                </div>
            </div>

            <!-- Activity Actions -->
            <div class="activity-actions">
                <a href="{{ route('admin.activities.manage.show', $activity['id']) }}" 
                   class="btn btn-sm btn-outline">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    {{ __('activities.common.view') }}
                </a>
                
                <a href="{{ route('admin.activities.manage.edit', $activity['id']) }}" 
                   class="btn btn-sm btn-secondary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    {{ __('activities.common.edit') }}
                </a>
                
                <button class="btn btn-sm btn-info" @click="duplicateActivity({{ $activity['id'] }})">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    {{ __('activities.common.duplicate') }}
                </button>
                
                <button class="btn btn-sm btn-danger" @click="deleteActivity({{ $activity['id'] }})">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    {{ __('activities.common.delete') }}
                </button>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Empty State -->
    <div x-show="filteredActivities.length === 0" class="empty-state">
        <div class="empty-state-content">
            <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="empty-state-title">{{ __('activities.manage.no_activities_found') }}</h3>
            <p class="empty-state-description">{{ __('activities.manage.no_activities_found_description') }}</p>
            <div class="empty-state-actions">
                <a href="{{ route('admin.activities.manage.create') }}" class="btn btn-primary">
                    {{ __('activities.manage.create_activity') }}
                </a>
                <button class="btn btn-secondary" @click="clearFilters()">
                    {{ __('activities.common.clear_filter') }}
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
@vite('resources/css/admin/activities/manage.css')
@endpush

@push('scripts')
@vite('resources/js/admin/activities/manage.js')
@endpush
