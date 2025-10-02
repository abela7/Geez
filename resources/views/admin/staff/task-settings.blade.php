@extends('layouts.admin')

@section('title', __('staff.tasks.settings.title'))

@section('content')
<div class="task-settings-page">
    <!-- Mobile-First Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('staff.tasks.settings.title') }}</h1>
                <p class="page-subtitle">{{ __('staff.tasks.settings.subtitle') }}</p>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.staff.tasks.index') }}" class="btn btn-secondary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('common.back_to_tasks') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Stats Overview -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--task-priority-low-bg);">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a.997.997 0 01-1.414 0l-7-7A1.997 1.997 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['total_types'] }}</div>
                <div class="stat-label">{{ __('staff.tasks.settings.task_types') }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: var(--task-priority-medium-bg);">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['total_priorities'] }}</div>
                <div class="stat-label">{{ __('staff.tasks.settings.priorities') }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: var(--task-priority-high-bg);">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['total_categories'] }}</div>
                <div class="stat-label">{{ __('staff.tasks.settings.categories') }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: var(--task-priority-urgent-bg);">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a.997.997 0 01-1.414 0l-7-7A1.997 1.997 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['total_tags'] }}</div>
                <div class="stat-label">{{ __('staff.tasks.settings.tags') }}</div>
            </div>
        </div>
    </div>

    <!-- Settings Tabs -->
    <div class="settings-tabs">
        <div class="tab-nav">
            <button class="tab-btn active" data-tab="types">
                <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a.997.997 0 01-1.414 0l-7-7A1.997 1.997 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                {{ __('staff.tasks.settings.task_types') }}
            </button>
            <button class="tab-btn" data-tab="priorities">
                <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                {{ __('staff.tasks.settings.priorities') }}
            </button>
            <button class="tab-btn" data-tab="categories">
                <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                {{ __('staff.tasks.settings.categories') }}
            </button>
            <button class="tab-btn" data-tab="tags">
                <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a.997.997 0 01-1.414 0l-7-7A1.997 1.997 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                {{ __('staff.tasks.settings.tags') }}
            </button>
        </div>

        <!-- Task Types Tab -->
        <div class="tab-content active" id="types-tab">
            <div class="tab-header">
                <h2 class="tab-title">{{ __('staff.tasks.settings.manage_task_types') }}</h2>
                <button class="btn btn-primary" data-modal-target="add-type-modal">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('staff.tasks.settings.add_task_type') }}
                </button>
            </div>

            <div class="items-grid">
                @forelse($taskTypes as $type)
                    <div class="item-card">
                        <div class="item-header">
                            <div class="item-color" style="background-color: {{ $type->color }};"></div>
                            <div class="item-info">
                                <h3 class="item-name">{{ $type->name }}</h3>
                                @if($type->description)
                                    <p class="item-description">{{ $type->description }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="item-actions">
                            <button class="btn-action btn-action-secondary" onclick="editTaskType('{{ $type->slug }}')" title="{{ __('common.edit') }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button class="btn-action btn-action-danger" onclick="deleteTaskType('{{ $type->slug }}')" title="{{ __('common.delete') }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a.997.997 0 01-1.414 0l-7-7A1.997 1.997 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <h3>{{ __('staff.tasks.settings.no_task_types') }}</h3>
                        <p>{{ __('staff.tasks.settings.no_task_types_description') }}</p>
                        <button class="btn btn-primary" data-modal-target="add-type-modal">
                            {{ __('staff.tasks.settings.add_first_task_type') }}
                        </button>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Priorities Tab -->
        <div class="tab-content" id="priorities-tab">
            <div class="tab-header">
                <h2 class="tab-title">{{ __('staff.tasks.settings.manage_priorities') }}</h2>
                <button class="btn btn-primary" data-modal-target="add-priority-modal">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('staff.tasks.settings.add_task_priority') }}
                </button>
            </div>

            <div class="items-grid">
                @forelse($taskPriorities as $priority)
                    <div class="item-card">
                        <div class="item-header">
                            <div class="item-color" style="background-color: {{ $priority->color }};"></div>
                            <div class="item-info">
                                <h3 class="item-name">{{ $priority->name }}</h3>
                                <div class="priority-level">Level: {{ $priority->level }}</div>
                                @if($priority->description)
                                    <p class="item-description">{{ $priority->description }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="item-actions">
                            <button class="btn-action btn-action-secondary" onclick="editTaskPriority('{{ $priority->slug }}')" title="{{ __('common.edit') }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button class="btn-action btn-action-danger" onclick="deleteTaskPriority('{{ $priority->slug }}')" title="{{ __('common.delete') }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <h3>{{ __('staff.tasks.settings.no_priorities') }}</h3>
                        <p>{{ __('staff.tasks.settings.no_priorities_description') }}</p>
                        <button class="btn btn-primary" data-modal-target="add-priority-modal">
                            {{ __('staff.tasks.settings.add_first_priority') }}
                        </button>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Categories Tab -->
        <div class="tab-content" id="categories-tab">
            <div class="tab-header">
                <h2 class="tab-title">{{ __('staff.tasks.settings.manage_categories') }}</h2>
                <button class="btn btn-primary" data-modal-target="add-category-modal">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('staff.tasks.settings.add_task_category') }}
                </button>
            </div>

            <div class="items-grid">
                @forelse($taskCategories as $category)
                    <div class="item-card">
                        <div class="item-header">
                            <div class="item-color" style="background-color: {{ $category->color }};"></div>
                            <div class="item-info">
                                <h3 class="item-name">{{ $category->name }}</h3>
                                @if($category->children->count() > 0)
                                    <div class="category-children">{{ $category->children->count() }} subcategories</div>
                                @endif
                                @if($category->description)
                                    <p class="item-description">{{ $category->description }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="item-actions">
                            <button class="btn-action btn-action-secondary" onclick="editTaskCategory('{{ $category->slug }}')" title="{{ __('common.edit') }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button class="btn-action btn-action-danger" onclick="deleteTaskCategory('{{ $category->slug }}')" title="{{ __('common.delete') }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <h3>{{ __('staff.tasks.settings.no_categories') }}</h3>
                        <p>{{ __('staff.tasks.settings.no_categories_description') }}</p>
                        <button class="btn btn-primary" data-modal-target="add-category-modal">
                            {{ __('staff.tasks.settings.add_first_category') }}
                        </button>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Tags Tab -->
        <div class="tab-content" id="tags-tab">
            <div class="tab-header">
                <h2 class="tab-title">{{ __('staff.tasks.settings.manage_tags') }}</h2>
                <button class="btn btn-primary" data-modal-target="add-tag-modal">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('staff.tasks.settings.add_task_tag') }}
                </button>
            </div>

            <div class="items-grid">
                @forelse($taskTags as $tag)
                    <div class="item-card">
                        <div class="item-header">
                            <div class="item-color" style="background-color: {{ $tag->color }};"></div>
                            <div class="item-info">
                                <h3 class="item-name">{{ $tag->name }}</h3>
                                <div class="tag-usage">Used {{ $tag->usage_count }} times</div>
                                @if($tag->description)
                                    <p class="item-description">{{ $tag->description }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="item-actions">
                            <button class="btn-action btn-action-secondary" onclick="editTaskTag('{{ $tag->slug }}')" title="{{ __('common.edit') }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button class="btn-action btn-action-danger" onclick="deleteTaskTag('{{ $tag->slug }}')" title="{{ __('common.delete') }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a.997.997 0 01-1.414 0l-7-7A1.997 1.997 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <h3>{{ __('staff.tasks.settings.no_tags') }}</h3>
                        <p>{{ __('staff.tasks.settings.no_tags_description') }}</p>
                        <button class="btn btn-primary" data-modal-target="add-tag-modal">
                            {{ __('staff.tasks.settings.add_first_tag') }}
                        </button>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Add Task Type Modal -->
<div class="modal" id="add-type-modal" role="dialog" aria-modal="true" aria-hidden="true" aria-labelledby="add-type-modal-title">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="add-type-modal-title">{{ __('staff.tasks.settings.add_task_type') }}</h3>
            <button type="button" class="modal-close" aria-label="Close modal">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.staff.tasks.settings.types.store') }}" class="modal-form">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="type-name">
                        {{ __('staff.tasks.settings.name') }} <span class="required">*</span>
                    </label>
                    <input type="text" id="type-name" name="name" class="form-input" required 
                           placeholder="{{ __('staff.tasks.settings.type_name_placeholder') }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="type-color">
                        {{ __('staff.tasks.settings.color') }} <span class="required">*</span>
                    </label>
                    <input type="color" id="type-color" name="color" class="form-color" value="#6B7280" required>
                </div>

                <div class="form-group form-group-full">
                    <label class="form-label" for="type-description">
                        {{ __('staff.tasks.settings.description') }}
                    </label>
                    <textarea id="type-description" name="description" class="form-textarea" rows="3"
                              placeholder="{{ __('staff.tasks.settings.type_description_placeholder') }}"></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="type-icon">
                        {{ __('staff.tasks.settings.icon') }}
                    </label>
                    <input type="text" id="type-icon" name="icon" class="form-input" 
                           placeholder="{{ __('staff.tasks.settings.icon_placeholder') }}">
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" data-modal-close>
                    {{ __('common.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary">
                    {{ __('staff.tasks.settings.create_task_type') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add Priority Modal -->
<div class="modal" id="add-priority-modal" role="dialog" aria-modal="true" aria-hidden="true" aria-labelledby="add-priority-modal-title">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="add-priority-modal-title">{{ __('staff.tasks.settings.add_task_priority') }}</h3>
            <button type="button" class="modal-close" aria-label="Close modal">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.staff.tasks.settings.priorities.store') }}" class="modal-form">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="priority-name">
                        {{ __('staff.tasks.settings.name') }} <span class="required">*</span>
                    </label>
                    <input type="text" id="priority-name" name="name" class="form-input" required 
                           placeholder="{{ __('staff.tasks.settings.priority_name_placeholder') }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="priority-level">
                        {{ __('staff.tasks.settings.level') }} <span class="required">*</span>
                    </label>
                    <select id="priority-level" name="level" class="form-input" required>
                        <option value="">{{ __('staff.tasks.settings.select_level') }}</option>
                        <option value="1">1 - {{ __('staff.tasks.settings.lowest') }}</option>
                        <option value="2">2 - {{ __('staff.tasks.settings.low') }}</option>
                        <option value="3">3 - {{ __('staff.tasks.settings.medium') }}</option>
                        <option value="4">4 - {{ __('staff.tasks.settings.high') }}</option>
                        <option value="5">5 - {{ __('staff.tasks.settings.highest') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="priority-color">
                        {{ __('staff.tasks.settings.color') }} <span class="required">*</span>
                    </label>
                    <input type="color" id="priority-color" name="color" class="form-color" value="#F59E0B" required>
                </div>

                <div class="form-group form-group-full">
                    <label class="form-label" for="priority-description">
                        {{ __('staff.tasks.settings.description') }}
                    </label>
                    <textarea id="priority-description" name="description" class="form-textarea" rows="3"
                              placeholder="{{ __('staff.tasks.settings.priority_description_placeholder') }}"></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="priority-icon">
                        {{ __('staff.tasks.settings.icon') }}
                    </label>
                    <input type="text" id="priority-icon" name="icon" class="form-input" 
                           placeholder="{{ __('staff.tasks.settings.icon_placeholder') }}">
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" data-modal-close>
                    {{ __('common.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary">
                    {{ __('staff.tasks.settings.create_task_priority') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal" id="add-category-modal" role="dialog" aria-modal="true" aria-hidden="true" aria-labelledby="add-category-modal-title">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="add-category-modal-title">{{ __('staff.tasks.settings.add_task_category') }}</h3>
            <button type="button" class="modal-close" aria-label="Close modal">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.staff.tasks.settings.categories.store') }}" class="modal-form">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="category-name">
                        {{ __('staff.tasks.settings.name') }} <span class="required">*</span>
                    </label>
                    <input type="text" id="category-name" name="name" class="form-input" required 
                           placeholder="{{ __('staff.tasks.settings.category_name_placeholder') }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="category-parent">
                        {{ __('staff.tasks.settings.parent_category') }}
                    </label>
                    <select id="category-parent" name="parent_id" class="form-input">
                        <option value="">{{ __('staff.tasks.settings.no_parent') }}</option>
                        @foreach($taskCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="category-color">
                        {{ __('staff.tasks.settings.color') }} <span class="required">*</span>
                    </label>
                    <input type="color" id="category-color" name="color" class="form-color" value="#10B981" required>
                </div>

                <div class="form-group form-group-full">
                    <label class="form-label" for="category-description">
                        {{ __('staff.tasks.settings.description') }}
                    </label>
                    <textarea id="category-description" name="description" class="form-textarea" rows="3"
                              placeholder="{{ __('staff.tasks.settings.category_description_placeholder') }}"></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="category-icon">
                        {{ __('staff.tasks.settings.icon') }}
                    </label>
                    <input type="text" id="category-icon" name="icon" class="form-input" 
                           placeholder="{{ __('staff.tasks.settings.icon_placeholder') }}">
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" data-modal-close>
                    {{ __('common.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary">
                    {{ __('staff.tasks.settings.create_task_category') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add Tag Modal -->
<div class="modal" id="add-tag-modal" role="dialog" aria-modal="true" aria-hidden="true" aria-labelledby="add-tag-modal-title">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="add-tag-modal-title">{{ __('staff.tasks.settings.add_task_tag') }}</h3>
            <button type="button" class="modal-close" aria-label="Close modal">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.staff.tasks.settings.tags.store') }}" class="modal-form">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="tag-name">
                        {{ __('staff.tasks.settings.name') }} <span class="required">*</span>
                    </label>
                    <input type="text" id="tag-name" name="name" class="form-input" required 
                           placeholder="{{ __('staff.tasks.settings.tag_name_placeholder') }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="tag-color">
                        {{ __('staff.tasks.settings.color') }} <span class="required">*</span>
                    </label>
                    <input type="color" id="tag-color" name="color" class="form-color" value="#8B5CF6" required>
                </div>

                <div class="form-group form-group-full">
                    <label class="form-label" for="tag-description">
                        {{ __('staff.tasks.settings.description') }}
                    </label>
                    <textarea id="tag-description" name="description" class="form-textarea" rows="3"
                              placeholder="{{ __('staff.tasks.settings.tag_description_placeholder') }}"></textarea>
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" data-modal-close>
                    {{ __('common.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary">
                    {{ __('staff.tasks.settings.create_task_tag') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Mobile-First Task Settings Design */
.task-settings-page {
    padding: 1rem;
    background: var(--color-bg-primary);
    min-height: 100vh;
}

/* Mobile-First Header */
.page-header {
    margin-bottom: 1.5rem;
}

.page-header-content {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.page-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--color-text-primary);
    margin: 0 0 0.25rem 0;
}

.page-subtitle {
    color: var(--color-text-secondary);
    font-size: 0.875rem;
    margin: 0;
}

.page-actions {
    display: flex;
    gap: 0.75rem;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: var(--color-surface-card);
    border: 1px solid var(--color-surface-card-border);
    border-radius: 1rem;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: var(--color-surface-card-shadow);
}

.stat-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.stat-icon svg {
    width: 1.5rem;
    height: 1.5rem;
    color: white;
}

.stat-content {
    flex: 1;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--color-text-primary);
    line-height: 1;
}

.stat-label {
    font-size: 0.75rem;
    color: var(--color-text-secondary);
    margin-top: 0.25rem;
}

/* Settings Tabs */
.settings-tabs {
    background: var(--color-surface-card);
    border: 1px solid var(--color-surface-card-border);
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: var(--color-surface-card-shadow);
}

.tab-nav {
    display: flex;
    overflow-x: auto;
    background: var(--color-bg-tertiary);
    border-bottom: 1px solid var(--color-border-base);
}

.tab-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    background: none;
    border: none;
    color: var(--color-text-secondary);
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition-all);
    white-space: nowrap;
    border-bottom: 2px solid transparent;
}

.tab-btn:hover {
    color: var(--color-text-primary);
    background: var(--color-bg-secondary);
}

.tab-btn.active {
    color: var(--color-primary);
    background: var(--color-surface-card);
    border-bottom-color: var(--color-primary);
}

.tab-icon {
    width: 1rem;
    height: 1rem;
}

.tab-content {
    display: none;
    padding: 1.5rem;
}

.tab-content.active {
    display: block;
}

.tab-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    gap: 1rem;
}

.tab-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--color-text-primary);
    margin: 0;
}

/* Items Grid */
.items-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
}

.item-card {
    background: var(--color-bg-secondary);
    border: 1px solid var(--color-border-base);
    border-radius: 0.75rem;
    padding: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    transition: var(--transition-all);
}

.item-card:hover {
    border-color: var(--color-primary);
    box-shadow: var(--form-input-shadow-focus);
}

.item-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex: 1;
}

.item-color {
    width: 1rem;
    height: 1rem;
    border-radius: 0.25rem;
    flex-shrink: 0;
}

.item-info {
    flex: 1;
}

.item-name {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--color-text-primary);
    margin: 0 0 0.25rem 0;
}

.item-description {
    font-size: 0.75rem;
    color: var(--color-text-secondary);
    margin: 0;
    line-height: 1.4;
}

.item-actions {
    display: flex;
    gap: 0.5rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: var(--color-text-secondary);
}

.empty-icon {
    width: 4rem;
    height: 4rem;
    margin: 0 auto 1rem;
    color: var(--color-text-muted);
}

.empty-state h3 {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--color-text-primary);
    margin: 0 0 0.5rem 0;
}

.empty-state p {
    margin: 0 0 1.5rem 0;
    font-size: 0.875rem;
}

/* Priority Level Display */
.priority-level {
    font-size: 0.75rem;
    color: var(--color-text-secondary);
    font-weight: 500;
    margin-bottom: 0.25rem;
}

/* Category Children Display */
.category-children {
    font-size: 0.75rem;
    color: var(--color-text-secondary);
    font-weight: 500;
    margin-bottom: 0.25rem;
}

/* Tag Usage Display */
.tag-usage {
    font-size: 0.75rem;
    color: var(--color-text-secondary);
    font-weight: 500;
    margin-bottom: 0.25rem;
}

/* Modal styles are now handled by the professional modal system */

/* Form Styles */
.form-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
}

.form-group-full {
    grid-column: 1 / -1;
}

.form-label {
    display: block;
    font-weight: 500;
    color: var(--color-text-primary);
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.required {
    color: var(--task-priority-urgent);
}

.form-input, .form-textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--form-input-border);
    border-radius: 0.5rem;
    background: var(--form-input-bg);
    color: var(--form-input-text);
    font-size: 0.875rem;
    transition: var(--transition-all);
}

.form-input:focus, .form-textarea:focus {
    outline: none;
    border-color: var(--form-input-border-focus);
    box-shadow: var(--form-input-shadow-focus);
}

.form-color {
    width: 100%;
    height: 3rem;
    padding: 0.25rem;
    border: 1px solid var(--form-input-border);
    border-radius: 0.5rem;
    background: var(--form-input-bg);
    cursor: pointer;
}

.form-textarea {
    resize: vertical;
    min-height: 4rem;
}

/* Button System */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    font-weight: 500;
    font-size: 0.875rem;
    text-decoration: none;
    border: 1px solid transparent;
    cursor: pointer;
    transition: var(--transition-all);
    gap: 0.5rem;
    min-height: 44px;
}

.btn-primary {
    background: var(--button-primary-bg);
    color: var(--button-primary-text);
    border-color: var(--button-primary-bg);
    box-shadow: var(--button-primary-shadow);
}

.btn-primary:hover {
    background: var(--button-primary-hover-bg);
    border-color: var(--button-primary-hover-bg);
    box-shadow: var(--button-primary-hover-shadow);
}

.btn-secondary {
    background: var(--button-secondary-bg);
    color: var(--button-secondary-text);
    border-color: var(--color-border-base);
    box-shadow: var(--button-secondary-shadow);
}

.btn-secondary:hover {
    background: var(--button-secondary-hover-bg);
    border-color: var(--color-text-secondary);
}

.btn-icon {
    width: 1rem;
    height: 1rem;
}

.btn-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2.25rem;
    height: 2.25rem;
    border-radius: 0.375rem;
    border: 1px solid transparent;
    cursor: pointer;
    transition: var(--transition-all);
}

.btn-action svg {
    width: 1rem;
    height: 1rem;
}

.btn-action-secondary {
    background: var(--color-bg-tertiary);
    color: var(--color-text-secondary);
    border-color: var(--color-border-base);
}

.btn-action-secondary:hover {
    background: var(--color-bg-secondary);
    color: var(--color-text-primary);
    border-color: var(--color-text-secondary);
}

.btn-action-danger {
    background: var(--task-priority-urgent-bg);
    color: var(--task-priority-urgent);
    border-color: var(--task-priority-urgent);
}

.btn-action-danger:hover {
    background: var(--task-priority-urgent);
    color: white;
}

/* Alert Messages */
.alert {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    border-radius: 0.75rem;
    margin-bottom: 1.5rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.alert-icon {
    width: 1.25rem;
    height: 1.25rem;
    flex-shrink: 0;
}

.alert-success {
    background: var(--alert-success-bg);
    color: var(--alert-success-text);
    border: 1px solid var(--alert-success-border);
}

.alert-error {
    background: var(--alert-error-bg);
    color: var(--alert-error-text);
    border: 1px solid var(--alert-error-border);
}

/* Responsive Design */
@media (min-width: 640px) {
    .task-settings-page {
        padding: var(--page-padding);
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .page-header-content {
        flex-direction: row;
        align-items: flex-start;
        justify-content: space-between;
    }
    
    .stats-grid {
        grid-template-columns: repeat(4, 1fr);
    }
    
    .tab-header {
        align-items: center;
    }
    
    .items-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .form-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .modal-actions {
        flex-direction: row;
    }
}

@media (min-width: 1024px) {
    .items-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}
</style>
@endpush

@push('scripts')
<script>
// Tab Management
document.addEventListener('DOMContentLoaded', function() {
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const tabId = this.dataset.tab;
            
            // Remove active class from all tabs and contents
            tabBtns.forEach(b => b.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            
            // Add active class to clicked tab and corresponding content
            this.classList.add('active');
            document.getElementById(tabId + '-tab').classList.add('active');
        });
    });
});

// Task Type Management
function editTaskType(typeId) {
    // Redirect to edit page
    window.location.href = `/admin/staff/tasks/settings/types/${typeId}/edit`;
}

function deleteTaskType(typeId) {
    if (window.modalSystem) {
        window.modalSystem.confirm({
            title: 'Delete Task Type',
            message: 'Are you sure you want to delete this task type? This action cannot be undone.',
            confirmText: 'Delete',
            cancelText: 'Cancel',
            type: 'danger'
        }).then((confirmed) => {
            if (confirmed) {
                // Create a form and submit it
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/staff/tasks/settings/types/${typeId}`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                
                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        });
    } else {
        // Fallback for older browsers
        if (confirm('{{ __("staff.tasks.settings.confirm_delete_type") }}')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/staff/tasks/settings/types/${typeId}`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    }
}

// Task Priority Management
function editTaskPriority(priorityId) {
    // Redirect to edit page
    window.location.href = `/admin/staff/tasks/settings/priorities/${priorityId}/edit`;
}

function deleteTaskPriority(priorityId) {
    if (window.modalSystem) {
        window.modalSystem.confirm({
            title: 'Delete Priority',
            message: 'Are you sure you want to delete this priority? This action cannot be undone.',
            confirmText: 'Delete',
            cancelText: 'Cancel',
            type: 'danger'
        }).then((confirmed) => {
            if (confirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/staff/tasks/settings/priorities/${priorityId}`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                
                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        });
    } else {
        if (confirm('Are you sure you want to delete this priority?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/staff/tasks/settings/priorities/${priorityId}`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    }
}

// Task Category Management
function editTaskCategory(categoryId) {
    // Redirect to edit page
    window.location.href = `/admin/staff/tasks/settings/categories/${categoryId}/edit`;
}

function deleteTaskCategory(categoryId) {
    if (window.modalSystem) {
        window.modalSystem.confirm({
            title: 'Delete Category',
            message: 'Are you sure you want to delete this category? This action cannot be undone.',
            confirmText: 'Delete',
            cancelText: 'Cancel',
            type: 'danger'
        }).then((confirmed) => {
            if (confirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/staff/tasks/settings/categories/${categoryId}`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                
                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        });
    } else {
        if (confirm('Are you sure you want to delete this category?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/staff/tasks/settings/categories/${categoryId}`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    }
}

// Task Tag Management
function editTaskTag(tagId) {
    // Redirect to edit page
    window.location.href = `/admin/staff/tasks/settings/tags/${tagId}/edit`;
}

function deleteTaskTag(tagId) {
    if (window.modalSystem) {
        window.modalSystem.confirm({
            title: 'Delete Tag',
            message: 'Are you sure you want to delete this tag? This action cannot be undone.',
            confirmText: 'Delete',
            cancelText: 'Cancel',
            type: 'danger'
        }).then((confirmed) => {
            if (confirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/staff/tasks/settings/tags/${tagId}`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                
                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        });
    } else {
        if (confirm('Are you sure you want to delete this tag?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/staff/tasks/settings/tags/${tagId}`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    }
}
</script>
@endpush
