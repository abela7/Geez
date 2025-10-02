@extends('layouts.admin')

@section('title', __('admin.shift_types.details'))

@section('content')
<div class="shift-types-page">
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success" style="background: #10B981; color: white; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error" style="background: #EF4444; color: white; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <div class="breadcrumb">
                    <a href="{{ route('admin.settings.shift-types.index') }}" class="breadcrumb-link">{{ __('admin.shift_types.title') }}</a>
                    <span class="breadcrumb-separator">/</span>
                    <span class="breadcrumb-current">{{ $shiftType->name }}</span>
                </div>
                <h1 class="page-title">{{ __('admin.shift_types.details') }}</h1>
                <p class="page-subtitle">{{ $shiftType->name }} - {{ __('admin.shift_types.description') }}</p>
            </div>
            
            <div class="page-actions">
                <a href="{{ route('admin.settings.shift-types.edit', $shiftType) }}" class="btn btn-primary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    {{ __('admin.common.edit') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Details Card -->
    <div class="details-grid">
        <div class="details-card">
            <div class="details-card-header">
                <h2 class="details-card-title">{{ __('admin.shift_types.basic_information') }}</h2>
                <p class="details-card-description">{{ __('admin.shift_types.basic_info_description') }}</p>
            </div>
            
            <div class="details-content">
                <div class="detail-item">
                    <div class="detail-label">{{ __('admin.shift_types.name') }}</div>
                    <div class="detail-value">{{ $shiftType->name }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">{{ __('admin.shift_types.description') }}</div>
                    <div class="detail-value">{{ $shiftType->description ?? '—' }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">{{ __('admin.shift_types.color') }}</div>
                    <div class="detail-value">
                        <div class="color-display">
                            <div class="color-preview" style="background-color: {{ $shiftType->color }}"></div>
                            <span class="color-text">{{ $shiftType->color }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">{{ __('admin.shift_types.sort_order') }}</div>
                    <div class="detail-value">{{ $shiftType->sort_order }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">{{ __('admin.common.status') }}</div>
                    <div class="detail-value">
                        <span class="status-badge {{ $shiftType->is_active ? 'status-active' : 'status-inactive' }}">
                            {{ $shiftType->is_active ? __('admin.common.active') : __('admin.common.inactive') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="details-card">
            <div class="details-card-header">
                <h2 class="details-card-title">{{ __('admin.shift_types.rate_settings') }}</h2>
                <p class="details-card-description">{{ __('admin.shift_types.rate_settings_description') }}</p>
            </div>
            
            <div class="details-content">
                <div class="detail-item">
                    <div class="detail-label">{{ __('admin.shift_types.default_hourly_rate') }}</div>
                    <div class="detail-value">
                        @if($shiftType->default_hourly_rate)
                            <span class="rate-display">£{{ number_format($shiftType->default_hourly_rate, 2) }}</span>
                        @else
                            <span class="text-secondary">—</span>
                        @endif
                    </div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">{{ __('admin.shift_types.default_overtime_rate') }}</div>
                    <div class="detail-value">
                        @if($shiftType->default_overtime_rate)
                            <span class="rate-display">£{{ number_format($shiftType->default_overtime_rate, 2) }}</span>
                        @else
                            <span class="text-secondary">—</span>
                        @endif
                    </div>
                </div>
                
                @if($shiftType->default_hourly_rate || $shiftType->default_overtime_rate)
                <div class="detail-item">
                    <div class="detail-label">{{ __('admin.shift_types.default_rates') }}</div>
                    <div class="detail-value">
                        <div class="rates-summary">
                            @if($shiftType->default_hourly_rate)
                                <div class="rate-item">
                                    <span class="rate-type">Hourly:</span>
                                    <span class="rate-amount">£{{ number_format($shiftType->default_hourly_rate, 2) }}</span>
                                </div>
                            @endif
                            @if($shiftType->default_overtime_rate)
                                <div class="rate-item">
                                    <span class="rate-type">Overtime:</span>
                                    <span class="rate-amount">£{{ number_format($shiftType->default_overtime_rate, 2) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="details-card">
            <div class="details-card-header">
                <h2 class="details-card-title">{{ __('admin.shift_types.activity') }}</h2>
                <p class="details-card-description">Created and updated information about this shift type</p>
            </div>
            
            <div class="details-content">
                <div class="detail-item">
                    <div class="detail-label">{{ __('admin.common.created_at') }}</div>
                    <div class="detail-value">{{ $shiftType->created_at->format('F j, Y g:i A') }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">{{ __('admin.common.updated_at') }}</div>
                    <div class="detail-value">{{ $shiftType->updated_at->format('F j, Y g:i A') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.shift-types-page {
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

.page-title-section {
    flex: 1;
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

.page-actions {
    display: flex;
    gap: 0.75rem;
}

.details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: var(--grid-gap);
}

.details-card {
    background: var(--color-surface-card);
    border: 1px solid var(--color-surface-card-border);
    border-radius: 0.75rem;
    overflow: hidden;
    box-shadow: var(--color-surface-card-shadow);
}

.details-card-header {
    padding: 1.5rem 1.5rem 0 1.5rem;
    border-bottom: 1px solid var(--color-surface-card-border);
    margin-bottom: 1.5rem;
}

.details-card-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--color-text-primary);
    margin: 0 0 0.5rem 0;
}

.details-card-description {
    color: var(--color-text-secondary);
    font-size: 0.875rem;
    margin: 0 0 1.5rem 0;
}

.details-content {
    padding: 0 1.5rem 1.5rem 1.5rem;
}

.detail-item {
    margin-bottom: 1rem;
}

.detail-item:last-child {
    margin-bottom: 0;
}

.detail-label {
    font-weight: 600;
    color: var(--color-text-primary);
    margin-bottom: 0.25rem;
    font-size: 0.875rem;
}

.detail-value {
    color: var(--color-text-secondary);
    font-size: 0.875rem;
}

.color-display {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.color-preview {
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    border: 2px solid var(--color-surface-card-border);
}

.color-text {
    font-family: monospace;
    font-weight: 500;
}

.rate-display {
    font-weight: 600;
    color: var(--color-text-primary);
}

.rates-summary {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.rate-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.rate-type {
    color: var(--color-text-secondary);
    font-weight: 500;
    min-width: 4rem;
}

.rate-amount {
    font-weight: 600;
    color: var(--color-text-primary);
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-active {
    background: var(--color-success-100);
    color: var(--color-success-800);
}

.status-inactive {
    background: var(--color-gray-100);
    color: var(--color-gray-800);
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.breadcrumb-link {
    color: var(--color-primary-600);
    text-decoration: none;
    font-weight: 500;
}

.breadcrumb-link:hover {
    color: var(--color-primary-700);
}

.breadcrumb-separator {
    color: var(--color-text-tertiary);
}

.breadcrumb-current {
    color: var(--color-text-secondary);
}

.text-secondary {
    color: var(--color-text-secondary);
}
</style>
@endsection
