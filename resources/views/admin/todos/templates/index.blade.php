@extends('layouts.admin')

@section('title', __('todos.templates.title'))

@section('content')
<div class="templates-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-header-left">
                <h1 class="page-title">{{ __('todos.templates.title') }}</h1>
                <p class="page-description">{{ __('todos.templates.subtitle') }}</p>
            </div>
            <div class="page-header-right">
                <button class="btn btn-secondary" @click="refreshTemplates()">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    {{ __('todos.common.refresh') }}
                </button>
                <a href="{{ route('admin.todos.templates.create') }}" class="btn btn-primary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('todos.templates.create_template') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-section">
        <div class="filters-row">
            <div class="filter-group">
                <label class="filter-label">{{ __('todos.templates.category') }}</label>
                <select class="filter-select" x-model="filters.category" @change="applyFilters()">
                    @foreach($categories as $key => $value)
                        <option value="{{ $key }}">{{ __('todos.templates.' . $key) }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">{{ __('todos.templates.assigned_role') }}</label>
                <select class="filter-select" x-model="filters.role" @change="applyFilters()">
                    @foreach($roles as $key => $value)
                        <option value="{{ $key }}">{{ __('todos.templates.' . $key) }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">{{ __('todos.templates.recurring_type') }}</label>
                <select class="filter-select" x-model="filters.recurring_type" @change="applyFilters()">
                    @foreach($recurringTypes as $key => $value)
                        <option value="{{ $key }}">{{ __('todos.templates.' . $key) }}</option>
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
                           placeholder="{{ __('todos.templates.search_placeholder') }}">
                </div>
            </div>
            
            <div class="filter-actions">
                <button class="btn btn-secondary" @click="clearFilters()">
                    {{ __('todos.common.clear_filter') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Templates Grid -->
    <div class="templates-grid" x-data="templatesData()">
        @foreach($templates as $template)
        <div class="template-card" 
             x-show="isTemplateVisible({{ json_encode($template) }})"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            
            <!-- Template Header -->
            <div class="template-header">
                <div class="template-info">
                    <div class="template-category category-{{ $template['category'] }}">
                        {{ __('todos.templates.' . $template['category']) }}
                    </div>
                    <h3 class="template-name">{{ $template['name'] }}</h3>
                    <p class="template-description">{{ $template['description'] }}</p>
                </div>
                <div class="template-status">
                    <div class="status-toggle">
                        <input type="checkbox" 
                               class="status-checkbox" 
                               {{ $template['is_active'] ? 'checked' : '' }}
                               @change="toggleTemplateStatus({{ $template['id'] }})">
                        <span class="status-label">
                            {{ $template['is_active'] ? __('todos.common.active') : __('todos.common.inactive') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Template Meta -->
            <div class="template-meta">
                <div class="meta-item">
                    <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="meta-label">{{ __('todos.templates.role') }}:</span>
                    <span class="meta-value">{{ __('todos.templates.' . $template['assigned_role']) }}</span>
                </div>
                
                <div class="meta-item">
                    <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span class="meta-label">{{ __('todos.templates.frequency') }}:</span>
                    <span class="meta-value">{{ __('todos.templates.' . $template['recurring_type']) }}</span>
                </div>
                
                <div class="meta-item">
                    <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="meta-label">{{ __('todos.templates.duration') }}:</span>
                    <span class="meta-value">{{ $template['estimated_duration'] }} {{ __('todos.templates.minutes') }}</span>
                </div>
                
                <div class="meta-item">
                    <div class="priority-badge priority-{{ $template['priority'] }}">
                        {{ __('todos.templates.priority_' . $template['priority']) }}
                    </div>
                </div>
            </div>

            <!-- Template Stats -->
            <div class="template-stats">
                <div class="stat-item">
                    <div class="stat-value">{{ $template['usage_count'] }}</div>
                    <div class="stat-label">{{ __('todos.templates.times_used') }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $template['completion_rate'] }}%</div>
                    <div class="stat-label">{{ __('todos.templates.completion_rate') }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ \Carbon\Carbon::parse($template['updated_at'])->diffForHumans() }}</div>
                    <div class="stat-label">{{ __('todos.templates.last_updated') }}</div>
                </div>
            </div>

            <!-- Template Instructions Preview -->
            @if(count($template['instructions']) > 0)
            <div class="template-instructions">
                <h4 class="instructions-title">{{ __('todos.templates.instructions') }}</h4>
                <ul class="instructions-list">
                    @foreach(array_slice($template['instructions'], 0, 3) as $instruction)
                    <li class="instruction-item">{{ $instruction }}</li>
                    @endforeach
                    @if(count($template['instructions']) > 3)
                    <li class="instruction-more">
                        {{ __('todos.templates.and_more', ['count' => count($template['instructions']) - 3]) }}
                    </li>
                    @endif
                </ul>
            </div>
            @endif

            <!-- Template Tags -->
            @if(count($template['tags']) > 0)
            <div class="template-tags">
                @foreach($template['tags'] as $tag)
                <span class="tag">{{ $tag }}</span>
                @endforeach
            </div>
            @endif

            <!-- Template Actions -->
            <div class="template-actions">
                <a href="{{ route('admin.todos.templates.show', $template['id']) }}" 
                   class="btn btn-sm btn-outline">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    {{ __('todos.common.view') }}
                </a>
                
                <a href="{{ route('admin.todos.templates.edit', $template['id']) }}" 
                   class="btn btn-sm btn-secondary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    {{ __('todos.common.edit') }}
                </a>
                
                <button class="btn btn-sm btn-success" @click="duplicateTemplate({{ $template['id'] }})">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    {{ __('todos.common.duplicate') }}
                </button>
                
                <button class="btn btn-sm btn-danger" @click="deleteTemplate({{ $template['id'] }})">
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
    <div x-show="filteredTemplates.length === 0" class="empty-state">
        <div class="empty-state-content">
            <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="empty-state-title">{{ __('todos.templates.no_templates_found') }}</h3>
            <p class="empty-state-description">{{ __('todos.templates.no_templates_found_description') }}</p>
            <div class="empty-state-actions">
                <a href="{{ route('admin.todos.templates.create') }}" class="btn btn-primary">
                    {{ __('todos.templates.create_template') }}
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
@vite('resources/css/admin/todos/templates.css')
@endpush

@push('scripts')
@vite('resources/js/admin/todos/templates.js')
@endpush
