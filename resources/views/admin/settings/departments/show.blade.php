@extends('layouts.admin')

@section('title', $department->name . ' - ' . config('app.name'))
@section('page_title', $department->name)

@section('content')
<div class="departments-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <div class="breadcrumb">
                    <a href="{{ route('admin.settings.departments.index') }}" class="breadcrumb-link">{{ __('admin.departments.title') }}</a>
                    <span class="breadcrumb-separator">/</span>
                    <span class="breadcrumb-current">{{ $department->name }}</span>
                </div>
                <div class="page-title-with-indicator">
                    <div class="department-color-indicator" style="background-color: {{ $department->color }}"></div>
                    <h1 class="page-title">{{ $department->name }}</h1>
                    <span class="status-badge {{ $department->is_active ? 'status-active' : 'status-inactive' }}">
                        {{ $department->is_active ? __('admin.common.active') : __('admin.common.inactive') }}
                    </span>
                </div>
                @if($department->description)
                <p class="page-subtitle">{{ $department->description }}</p>
                @endif
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.settings.departments.edit', $department) }}" class="btn btn-primary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    {{ __('admin.common.edit') }}
                </a>
            </div>
        </div>
    </div>

    <div class="details-grid">
        <!-- Department Details -->
        <div class="details-card">
            <div class="card-header">
                <h2 class="card-title">{{ __('admin.departments.details') }}</h2>
            </div>
            <div class="card-content">
                <div class="details-grid">
                    <div class="detail-item">
                        <label class="detail-label">{{ __('admin.departments.name') }}</label>
                        <div class="detail-value">{{ $department->name }}</div>
                    </div>
                    
                    <div class="detail-item">
                        <label class="detail-label">{{ __('admin.departments.slug') }}</label>
                        <div class="detail-value">
                            <code class="detail-code">{{ $department->slug }}</code>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <label class="detail-label">{{ __('admin.departments.color') }}</label>
                        <div class="detail-value">
                            <div class="color-display">
                                <div class="color-swatch" style="background-color: {{ $department->color }}"></div>
                                <code class="detail-code">{{ $department->color }}</code>
                            </div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <label class="detail-label">{{ __('admin.departments.status') }}</label>
                        <div class="detail-value">
                            <span class="status-badge {{ $department->is_active ? 'status-active' : 'status-inactive' }}">
                                {{ $department->is_active ? __('admin.common.active') : __('admin.common.inactive') }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <label class="detail-label">{{ __('admin.departments.sort_order') }}</label>
                        <div class="detail-value">{{ $department->sort_order }}</div>
                    </div>
                    
                    @if($department->description)
                    <div class="detail-item detail-item-full">
                        <label class="detail-label">{{ __('admin.departments.description') }}</label>
                        <div class="detail-value">{{ $department->description }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Activity Information -->
        <div class="details-card">
            <div class="card-header">
                <h2 class="card-title">{{ __('admin.departments.activity') }}</h2>
            </div>
            <div class="card-content">
                <div class="details-grid">
                    <div class="detail-item">
                        <label class="detail-label">{{ __('admin.common.created_at') }}</label>
                        <div class="detail-value">
                            <time datetime="{{ $department->created_at->toISOString() }}">
                                {{ $department->created_at->format('M j, Y \a\t g:i A') }}
                            </time>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <label class="detail-label">{{ __('admin.common.updated_at') }}</label>
                        <div class="detail-value">
                            <time datetime="{{ $department->updated_at->toISOString() }}">
                                {{ $department->updated_at->format('M j, Y \a\t g:i A') }}
                            </time>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.departments-page {
    padding: var(--page-padding);
    max-width: var(--page-max-width);
    margin: 0 auto;
    background: var(--color-bg-primary);
}

.page-header {
    margin-bottom: var(--section-spacing);
}

.page-header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: var(--card-spacing);
}

.page-title {
    font-size: 1.875rem;
    font-weight: 700;
    color: var(--color-text-primary);
    margin: 0;
}

.page-subtitle {
    color: var(--color-text-secondary);
    margin: 0.25rem 0 0 0;
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.breadcrumb-link {
    color: var(--color-text-secondary);
    text-decoration: none;
    transition: var(--transition-all);
}

.breadcrumb-link:hover {
    color: var(--color-text-primary);
}

.breadcrumb-separator {
    color: var(--color-text-tertiary);
}

.breadcrumb-current {
    color: var(--color-text-primary);
    font-weight: 500;
}

.page-title-with-indicator {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.department-color-indicator {
    width: 1rem;
    height: 1rem;
    border-radius: 50%;
    border: 2px solid var(--color-surface-card-border);
    flex-shrink: 0;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-active {
    background: var(--color-success-50);
    color: var(--color-success-700);
}

.status-inactive {
    background: var(--color-warning-50);
    color: var(--color-warning-700);
}

.page-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: var(--transition-all);
}

.btn-primary {
    background: var(--color-primary-600);
    color: white;
}

.btn-primary:hover {
    background: var(--color-primary-700);
}

.btn-secondary {
    background: var(--color-bg-secondary);
    color: var(--color-text-primary);
    border: 1px solid var(--color-surface-border);
}

.btn-secondary:hover {
    background: var(--color-bg-tertiary);
}

.btn-icon {
    width: 1rem;
    height: 1rem;
}

.details-grid {
    display: grid;
    gap: 2rem;
}

.details-card {
    background: var(--color-surface-card);
    border: 1px solid var(--color-surface-card-border);
    border-radius: 0.75rem;
    box-shadow: var(--color-surface-card-shadow);
}

.card-header {
    padding: 1.5rem 1.5rem 0 1.5rem;
    border-bottom: 1px solid var(--color-surface-border);
    margin-bottom: 1.5rem;
}

.card-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--color-text-primary);
    margin: 0 0 1rem 0;
}

.card-content {
    padding: 0 1.5rem 1.5rem 1.5rem;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
}

.detail-item:last-child {
    margin-bottom: 0;
}

.detail-label {
    font-weight: 500;
    color: var(--color-text-secondary);
    font-size: 0.875rem;
}

.detail-value {
    color: var(--color-text-primary);
    margin: 0;
}

.color-swatch {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.25rem 0.75rem;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    font-family: monospace;
    color: white;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
    font-weight: 500;
    width: fit-content;
}
</style>
@endpush
