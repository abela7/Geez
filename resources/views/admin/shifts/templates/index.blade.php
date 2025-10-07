@extends('layouts.admin')

@section('title', __('admin.shifts.templates.title'))

@section('content')
<div class="shifts-templates-page" x-data="shiftsTemplatesData()">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-header-left">
                <h1 class="page-title">{{ __('admin.shifts.templates.title') }}</h1>
                <p class="page-description">{{ __('admin.shifts.templates.subtitle') }}</p>
            </div>
            <div class="page-header-right">
                <div class="header-actions">
                    <button class="btn btn-secondary" @click="showImportModal = true">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                        </svg>
                        {{ __('admin.shifts.templates.import_template') }}
                    </button>
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

    <!-- Summary Dashboard -->
    <div class="summary-section">
        <div class="summary-grid">
            <div class="summary-card summary-card-primary">
                <div class="summary-icon">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a1 1 0 102 0V3h4v1a1 1 0 102 0V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="summary-content">
                    <div class="summary-value">{{ $totalTemplates }}</div>
                    <div class="summary-label">{{ __('admin.shifts.templates.total_templates') }}</div>
                </div>
            </div>

            <div class="summary-card summary-card-success">
                <div class="summary-icon">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="summary-content">
                    <div class="summary-value">{{ $activeTemplates }}</div>
                    <div class="summary-label">{{ __('admin.shifts.templates.active_templates') }}</div>
                </div>
            </div>

            <div class="summary-card summary-card-warning">
                <div class="summary-icon">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a1 1 0 102 0V3h4v1a1 1 0 102 0V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm8 8a1 1 0 01-1-1V8a1 1 0 012 0v4a1 1 0 01-1 1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="summary-content">
                    <div class="summary-value">{{ $draftTemplates }}</div>
                    <div class="summary-label">{{ __('admin.shifts.templates.draft_templates') }}</div>
                </div>
            </div>

            <div class="summary-card summary-card-info">
                <div class="summary-icon">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="summary-content">
                    <div class="summary-value">{{ $totalUsage }}</div>
                    <div class="summary-label">{{ __('admin.shifts.templates.total_applications') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Template Types Overview -->
    <div class="template-types-section">
        <div class="section-header">
            <h2 class="section-title">{{ __('admin.shifts.templates.template_types') }}</h2>
        </div>
        <div class="template-types-grid">
            @foreach($templateTypes as $type => $data)
            <div class="template-type-card">
                <div class="type-header">
                    <h3 class="type-name">{{ $data['name'] }}</h3>
                    <span class="type-count">{{ $data['count'] }} {{ __('admin.shifts.templates.templates') }}</span>
                </div>
                <div class="type-stats">
                    <div class="stat-item">
                        <span class="stat-value">{{ $data['total_usage'] }}</span>
                        <span class="stat-label">{{ __('admin.shifts.templates.times_used') }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Quick Access Sections -->
    <div class="quick-access-grid">
        <!-- Popular Templates -->
        <div class="popular-templates-section">
            <div class="section-header">
                <h3 class="section-title">{{ __('admin.shifts.templates.popular_templates') }}</h3>
                <a href="#all-templates" class="view-all-link">{{ __('admin.shifts.templates.view_all') }}</a>
            </div>
            <div class="templates-quick-list">
                @foreach($popularTemplates as $template)
                <div class="template-quick-card">
                    <div class="template-info">
                        <h4 class="template-name">{{ $template['name'] }}</h4>
                        <p class="template-description">{{ Str::limit($template['description'], 60) }}</p>
                        <div class="template-meta">
                            <span class="usage-count">{{ $template['usage_count'] }} {{ __('admin.shifts.templates.uses') }}</span>
                            <span class="template-type">{{ ucfirst($template['type']) }}</span>
                        </div>
                    </div>
                    <div class="template-actions">
                        <button class="btn btn-sm btn-primary" @click="showApplyModal({{ json_encode($template) }})">
                            {{ __('admin.shifts.templates.apply_template') }}
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Templates -->
        <div class="recent-templates-section">
            <div class="section-header">
                <h3 class="section-title">{{ __('admin.shifts.templates.recent_templates') }}</h3>
                <a href="#all-templates" class="view-all-link">{{ __('admin.shifts.templates.view_all') }}</a>
            </div>
            <div class="templates-quick-list">
                @foreach($recentTemplates as $template)
                <div class="template-quick-card">
                    <div class="template-info">
                        <h4 class="template-name">{{ $template['name'] }}</h4>
                        <p class="template-description">{{ Str::limit($template['description'], 60) }}</p>
                        <div class="template-meta">
                            <span class="last-updated">{{ $template['updated_at']->diffForHumans() }}</span>
                            <span class="created-by">{{ __('admin.shifts.templates.by') }} {{ $template['created_by'] }}</span>
                        </div>
                    </div>
                    <div class="template-actions">
                        <button class="btn btn-sm btn-secondary" @click="editTemplate({{ $template['id'] }})">
                            {{ __('admin.shifts.common.edit') }}
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- All Templates Section -->
    <div class="all-templates-section" id="all-templates">
        <div class="section-header">
            <h2 class="section-title">{{ __('admin.shifts.templates.all_templates') }}</h2>
            <div class="section-filters">
                <select x-model="filterType" @change="applyFilters()" class="filter-select">
                    <option value="all">{{ __('admin.shifts.templates.all_types') }}</option>
                    <option value="weekly">{{ __('admin.shifts.templates.weekly') }}</option>
                    <option value="weekend">{{ __('admin.shifts.templates.weekend') }}</option>
                    <option value="special">{{ __('admin.shifts.templates.special') }}</option>
                    <option value="minimal">{{ __('admin.shifts.templates.minimal') }}</option>
                    <option value="training">{{ __('admin.shifts.templates.training') }}</option>
                </select>
                <select x-model="filterStatus" @change="applyFilters()" class="filter-select">
                    <option value="all">{{ __('admin.shifts.common.all_statuses') }}</option>
                    <option value="active">{{ __('admin.shifts.statuses.active') }}</option>
                    <option value="draft">{{ __('admin.shifts.statuses.draft') }}</option>
                </select>
                <input type="text" x-model="searchQuery" @input="applyFilters()" class="search-input" placeholder="{{ __('admin.shifts.templates.search_templates') }}">
            </div>
        </div>
        
        <div class="templates-grid">
            @foreach($templates as $template)
            <div class="template-card" data-type="{{ $template['type'] }}" data-status="{{ $template['status'] }}" data-name="{{ strtolower($template['name']) }}">
                <div class="template-card-header">
                    <div class="template-title-section">
                        <h3 class="template-name">{{ $template['name'] }}</h3>
                        <div class="template-badges">
                            <span class="template-type-badge type-{{ $template['type'] }}">
                                {{ ucfirst($template['type']) }}
                            </span>
                            <span class="template-status-badge status-{{ $template['status'] }}">
                                {{ ucfirst($template['status']) }}
                            </span>
                        </div>
                    </div>
                    <div class="template-menu" x-data="{ open: false }">
                        <button class="menu-trigger" @click="open = !open">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition class="menu-dropdown">
                            <a href="{{ route('admin.shifts.shifts.templates.edit', $template['id']) }}" class="menu-item">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                {{ __('admin.shifts.common.edit') }}
                            </a>
                            <button class="menu-item" @click="duplicateTemplate({{ $template['id'] }})">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                {{ __('admin.shifts.templates.duplicate') }}
                            </button>
                            <button class="menu-item" @click="exportTemplate({{ $template['id'] }})">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                {{ __('admin.shifts.templates.export') }}
                            </button>
                            <div class="menu-separator"></div>
                            <button class="menu-item menu-item-danger" @click="deleteTemplate({{ $template['id'] }})">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                {{ __('admin.shifts.common.delete') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="template-description">
                    <p>{{ $template['description'] }}</p>
                </div>

                <div class="template-stats">
                    <div class="stats-grid">
                        <div class="stat-item">
                            <span class="stat-value">{{ $template['total_shifts'] }}</span>
                            <span class="stat-label">{{ __('admin.shifts.common.shifts') }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">{{ $template['total_staff_required'] }}</span>
                            <span class="stat-label">{{ __('admin.shifts.common.staff') }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">${{ number_format($template['estimated_cost']) }}</span>
                            <span class="stat-label">{{ __('admin.shifts.templates.estimated_cost') }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">{{ $template['usage_count'] }}</span>
                            <span class="stat-label">{{ __('admin.shifts.templates.uses') }}</span>
                        </div>
                    </div>
                </div>

                <div class="template-shifts-preview">
                    <h4 class="preview-title">{{ __('admin.shifts.templates.shifts_included') }}</h4>
                    <div class="shifts-preview-list">
                        @foreach(array_slice($template['shifts'], 0, 3) as $shift)
                        <div class="shift-preview-item">
                            <span class="shift-name">{{ $shift['name'] }}</span>
                            <span class="shift-time">{{ $shift['start_time'] }}-{{ $shift['end_time'] }}</span>
                            <span class="shift-department">{{ $shift['department'] }}</span>
                        </div>
                        @endforeach
                        @if(count($template['shifts']) > 3)
                        <div class="more-shifts">
                            +{{ count($template['shifts']) - 3 }} {{ __('admin.shifts.templates.more_shifts') }}
                        </div>
                        @endif
                    </div>
                </div>

                <div class="template-meta">
                    <div class="meta-info">
                        <span class="created-by">{{ __('admin.shifts.templates.created_by') }} {{ $template['created_by'] }}</span>
                        <span class="created-date">{{ $template['created_at']->format('M d, Y') }}</span>
                        @if($template['last_used'])
                        <span class="last-used">{{ __('admin.shifts.templates.last_used') }} {{ $template['last_used']->diffForHumans() }}</span>
                        @endif
                    </div>
                </div>

                <div class="template-actions">
                    <button class="btn btn-primary" @click="showApplyModal({{ json_encode($template) }})">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ __('admin.shifts.templates.apply_template') }}
                    </button>
                    <button class="btn btn-secondary" @click="previewTemplate({{ json_encode($template) }})">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        {{ __('admin.shifts.templates.preview') }}
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Apply Template Modal -->
    <div x-show="showApplyTemplateModal" x-transition class="modal-overlay" @click.self="showApplyTemplateModal = false">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">{{ __('admin.shifts.templates.apply_template') }}</h3>
                <button class="modal-close" @click="showApplyTemplateModal = false">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <div class="modal-body">
                <div class="template-summary" x-show="selectedTemplate">
                    <h4>{{ __('admin.shifts.templates.template_details') }}</h4>
                    <div class="template-info-grid">
                        <div class="info-item">
                            <span class="info-label">{{ __('admin.shifts.templates.template_name') }}</span>
                            <span class="info-value" x-text="selectedTemplate?.name"></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">{{ __('admin.shifts.common.type') }}</span>
                            <span class="info-value" x-text="selectedTemplate?.type"></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">{{ __('admin.shifts.templates.total_shifts') }}</span>
                            <span class="info-value" x-text="selectedTemplate?.total_shifts"></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">{{ __('admin.shifts.templates.estimated_cost') }}</span>
                            <span class="info-value" x-text="formatCurrency(selectedTemplate?.estimated_cost)"></span>
                        </div>
                    </div>
                </div>

                <div class="application-settings">
                    <h4>{{ __('admin.shifts.templates.application_settings') }}</h4>
                    <div class="settings-grid">
                        <div class="form-group">
                            <label class="form-label">{{ __('admin.shifts.templates.start_date') }}</label>
                            <input type="date" x-model="applicationSettings.startDate" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">{{ __('admin.shifts.templates.end_date') }}</label>
                            <input type="date" x-model="applicationSettings.endDate" class="form-input">
                        </div>
                        <div class="form-group form-group-full">
                            <label class="form-checkbox">
                                <input type="checkbox" x-model="applicationSettings.overwriteExisting">
                                <span class="checkbox-label">{{ __('admin.shifts.templates.overwrite_existing') }}</span>
                            </label>
                            <div class="form-help">{{ __('admin.shifts.templates.overwrite_help') }}</div>
                        </div>
                    </div>
                </div>

                <div class="preview-section" x-show="applicationPreview">
                    <h4>{{ __('admin.shifts.templates.application_preview') }}</h4>
                    <div class="preview-stats">
                        <div class="preview-stat">
                            <span class="stat-value" x-text="applicationPreview?.total_shifts"></span>
                            <span class="stat-label">{{ __('admin.shifts.templates.shifts_to_create') }}</span>
                        </div>
                        <div class="preview-stat">
                            <span class="stat-value" x-text="applicationPreview?.weeks_affected"></span>
                            <span class="stat-label">{{ __('admin.shifts.templates.weeks_affected') }}</span>
                        </div>
                        <div class="preview-stat">
                            <span class="stat-value" x-text="formatCurrency(applicationPreview?.estimated_cost)"></span>
                            <span class="stat-label">{{ __('admin.shifts.templates.total_cost') }}</span>
                        </div>
                    </div>
                    <div x-show="applicationPreview?.date_conflicts > 0 || applicationPreview?.staff_conflicts > 0" class="preview-warnings">
                        <div x-show="applicationPreview?.date_conflicts > 0" class="warning-item">
                            <svg class="warning-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span x-text="`${applicationPreview.date_conflicts} date conflicts detected`"></span>
                        </div>
                        <div x-show="applicationPreview?.staff_conflicts > 0" class="warning-item">
                            <svg class="warning-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span x-text="`${applicationPreview.staff_conflicts} staff conflicts detected`"></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-actions">
                <button class="btn btn-secondary" @click="generatePreview()">
                    {{ __('admin.shifts.templates.preview_application') }}
                </button>
                <button class="btn btn-secondary" @click="showApplyTemplateModal = false">
                    {{ __('admin.shifts.common.cancel') }}
                </button>
                <button class="btn btn-primary" @click="confirmApplication()" :disabled="!applicationSettings.startDate || !applicationSettings.endDate">
                    {{ __('admin.shifts.templates.apply_template') }}
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
@vite(['resources/css/admin/shifts/templates.css'])
@endpush

@push('scripts')
@vite(['resources/js/admin/shifts/templates.js'])
@endpush
@endsection
