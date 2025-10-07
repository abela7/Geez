@extends('layouts.admin')

@section('title', __('admin.shifts.templates.title'))

@push('styles')
@vite(['resources/css/admin/shifts/templates.css'])
@endpush

@push('scripts')
@vite(['resources/js/admin/shifts/templates.js'])
@endpush

@section('content')
<div class="templates-page" x-data="templatesPageData(@js($templates ?? []), @js($shifts ?? []), @js($staff ?? []))">
    <!-- Modern Page Header -->
    <div class="page-header-modern">
        <div class="page-header-content">
            <div class="page-header-left">
                <div class="page-title-section">
                    <h1 class="page-title">
                        <svg class="page-title-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        {{ __('admin.shifts.templates.title') }}
                    </h1>
                    <p class="page-description">{{ __('admin.shifts.templates.subtitle') }}</p>
                </div>
            </div>
            <div class="page-header-right">
                <div class="header-actions">
                    <a href="{{ route('admin.shifts.assignments.index') }}" class="btn btn-secondary">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h6a2 2 0 012 2v4m-6 8v10m0 0l-3-3m3 3l3-3"/>
                        </svg>
                        {{ __('admin.shifts.templates.view_assignments') }}
                    </a>
                    <a href="{{ route('admin.shifts.shifts.templates.create') }}" class="btn btn-primary">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        {{ __('admin.shifts.templates.create_template') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalTemplates }}</div>
                <div class="stat-label">{{ __('admin.shifts.templates.total_templates') }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $activeTemplates }}</div>
                <div class="stat-label">{{ __('admin.shifts.templates.active_templates') }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $draftTemplates }}</div>
                <div class="stat-label">{{ __('admin.shifts.templates.draft_templates') }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalUsage }}</div>
                <div class="stat-label">{{ __('admin.shifts.templates.total_usage') }}</div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="filters-section">
        <div class="filters-content">
            <div class="search-box">
                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text"
                       x-model="searchQuery"
                       @input.debounce.300ms="filterTemplates()"
                       placeholder="{{ __('admin.shifts.templates.search_templates') }}"
                       class="search-input">
            </div>

            <div class="filter-buttons">
                <select x-model="filterType" @change="filterTemplates()" class="filter-select">
                    <option value="all">{{ __('admin.shifts.templates.all_types') }}</option>
                    @foreach($templateTypeOptions as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>

                <select x-model="filterStatus" @change="filterTemplates()" class="filter-select">
                    <option value="all">{{ __('admin.shifts.templates.all_status') }}</option>
                    <option value="active">{{ __('admin.shifts.templates.active') }}</option>
                    <option value="draft">{{ __('admin.shifts.templates.draft') }}</option>
                </select>

                <select x-model="sortBy" @change="filterTemplates()" class="filter-select">
                    <option value="created_at">{{ __('admin.shifts.templates.sort_newest') }}</option>
                    <option value="name">{{ __('admin.shifts.templates.sort_name') }}</option>
                    <option value="usage_count">{{ __('admin.shifts.templates.sort_usage') }}</option>
                    <option value="type">{{ __('admin.shifts.templates.sort_type') }}</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Templates Grid -->
    <div class="templates-grid" x-show="filteredTemplates.length > 0" x-transition>
        <div class="templates-container">
            @foreach($templates as $template)
                <div class="template-card"
                     x-show="isTemplateVisible('{{ $template['id'] }}')"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100">
                    <div class="template-header">
                        <div class="template-title-section">
                            <h3 class="template-title">{{ $template['name'] }}</h3>
                            <div class="template-meta">
                                <span class="template-type type-{{ $template['type'] }}">
                                    {{ ucfirst($template['type']) }}
                                </span>
                                <span class="template-status status-{{ $template['status'] }}">
                                    {{ ucfirst($template['status']) }}
                                </span>
                                @if($template['is_default'])
                                    <span class="template-default">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                        </svg>
                                        {{ __('admin.shifts.templates.default') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="template-actions">
                            <div class="dropdown">
                                <button class="dropdown-trigger" @click="toggleDropdown('{{ $template['id'] }}')">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                    </svg>
                                </button>
                                <div class="dropdown-menu"
                                     x-show="activeDropdown === '{{ $template['id'] }}'"
                                     x-transition
                                     @click.outside="activeDropdown = null">
                                    <a href="{{ route('admin.shifts.shifts.templates.show', $template['id']) }}"
                                       class="dropdown-item">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        {{ __('admin.shifts.templates.view_details') }}
                                    </a>
                                    <a href="{{ route('admin.shifts.shifts.templates.edit', $template['id']) }}"
                                       class="dropdown-item">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        {{ __('admin.shifts.templates.edit') }}
                                    </a>
                                    <button class="dropdown-item"
                                            @click="duplicateTemplate('{{ $template['id'] }}')">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                        {{ __('admin.shifts.templates.duplicate') }}
                                    </button>
                                    @if(!$template['is_default'])
                                        <button class="dropdown-item"
                                                @click="setAsDefault('{{ $template['id'] }}')">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                            </svg>
                                            {{ __('admin.shifts.templates.set_default') }}
                                        </button>
                                    @endif
                                    <button class="dropdown-item"
                                            @click="toggleTemplateStatus('{{ $template['id'] }}')">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $template['status'] === 'active' ? __('admin.shifts.templates.deactivate') : __('admin.shifts.templates.activate') }}
                                    </button>
                                    <div class="dropdown-divider"></div>
                                    @if($template['usage_count'] === 0)
                                        <button class="dropdown-item text-danger"
                                                @click="deleteTemplate('{{ $template['id'] }}', '{{ $template['name'] }}')">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            {{ __('admin.shifts.templates.delete') }}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="template-content">
                        @if($template['description'])
                            <p class="template-description">{{ Str::limit($template['description'], 100) }}</p>
                        @endif

                        <div class="template-stats">
                            <div class="stat-item">
                                <span class="stat-value">{{ $template['total_assignments'] }}</span>
                                <span class="stat-label">{{ __('admin.shifts.templates.assignments') }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-value">{{ $template['total_staff'] }}</span>
                                <span class="stat-label">{{ __('admin.shifts.templates.staff') }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-value">{{ $template['usage_count'] }}</span>
                                <span class="stat-label">{{ __('admin.shifts.templates.usage') }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-value">£{{ number_format($template['estimated_cost'], 0) }}</span>
                                <span class="stat-label">{{ __('admin.shifts.templates.cost') }}</span>
                            </div>
                        </div>

                        <div class="template-footer">
                            <div class="template-meta-info">
                                <span class="created-by">{{ __('admin.shifts.templates.created_by') }}: {{ $template['created_by'] }}</span>
                                @if($template['last_used'])
                                    <span class="last-used">{{ __('admin.shifts.templates.last_used') }}: {{ $template['last_used']->format('M j, Y') }}</span>
                                @endif
                            </div>
                            <div class="template-quick-actions">
                                <button class="btn-apply-template"
                                        @click="applyTemplate('{{ $template['id'] }}', '{{ $template['name'] }}')">
                                    {{ __('admin.shifts.templates.apply') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Empty State -->
    <div class="empty-state" x-show="filteredTemplates.length === 0" x-transition>
        <div class="empty-state-content">
            <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="empty-title">{{ __('admin.shifts.templates.no_templates') }}</h3>
            <p class="empty-description">{{ __('admin.shifts.templates.no_templates_desc') }}</p>
            <a href="{{ route('admin.shifts.shifts.templates.create') }}" class="btn btn-primary">
                {{ __('admin.shifts.templates.create_first_template') }}
            </a>
        </div>
    </div>

    <!-- Template Type Statistics -->
    @if(count($templateTypes) > 0)
        <div class="template-types-section">
            <h3 class="section-title">{{ __('admin.shifts.templates.template_types') }}</h3>
            <div class="types-grid">
                @foreach($templateTypes as $type)
                    <div class="type-card">
                        <div class="type-header">
                            <span class="type-name">{{ $type['name'] }}</span>
                            <span class="type-count">{{ $type['count'] }}</span>
                        </div>
                        <div class="type-stats">
                            <span class="type-usage">{{ $type['total_usage'] }} {{ __('admin.shifts.templates.times_used') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Popular Templates -->
    @if($popularTemplates->count() > 0)
        <div class="popular-templates-section">
            <h3 class="section-title">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="section-title-icon">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m0 0A1.828 1.828 0 016.582 7H9m0 0a2 2 0 012 2v2m-2-2v+2m0-4h2m2-4H9M19 4v5h.582m0 0A1.828 1.828 0 0017.582 7H15m0 0a2 2 0 012 2v2m-2-2v+2m0-4h2m2-4H15"/>
                </svg>
                {{ __('admin.shifts.templates.popular_templates') }}
            </h3>
            <div class="popular-grid">
                @foreach($popularTemplates as $template)
                    <div class="popular-item">
                        <div class="popular-content">
                            <h4 class="popular-name">{{ $template['name'] }}</h4>
                            <div class="popular-meta">
                                <span class="popular-usage">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                    {{ $template['usage_count'] }} {{ __('admin.shifts.templates.times_used') }}
                                </span>
                                <span class="popular-type">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    {{ ucfirst($template['type']) }}
                                </span>
                            </div>
                        </div>
                        <a href="{{ route('admin.shifts.shifts.templates.show', $template['id']) }}" class="btn-view-popular">
                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            {{ __('admin.shifts.templates.view') }}
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
