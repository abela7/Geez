@extends('layouts.admin')

@section('content')
<div class="staff-settings-index">
    <div class="settings-grid">
        <!-- Staff Types Management Card -->
        <div class="settings-card">
            <div class="settings-card-header">
                <div class="settings-card-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="settings-card-title">
                    <h3>{{ __('staff.settings.staff_types.title') }}</h3>
                    <p>{{ __('staff.settings.staff_types.description') }}</p>
                </div>
            </div>

            <div class="settings-card-stats">
                <div class="stat-item">
                    <span class="stat-value">{{ $staffTypesCount }}</span>
                    <span class="stat-label">{{ __('common.total') }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">{{ $activeStaffTypesCount }}</span>
                    <span class="stat-label">{{ __('staff.types.active') }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">{{ $inactiveStaffTypesCount }}</span>
                    <span class="stat-label">{{ __('staff.types.inactive') }}</span>
                </div>
            </div>

            <div class="settings-card-actions">
                <a href="{{ route('admin.staff.types.index') }}" class="btn-primary">
                    <i class="fas fa-cog"></i>
                    {{ __('staff.settings.staff_types.manage_button') }}
                </a>
            </div>
        </div>

        <!-- Future Settings Cards -->
        <div class="settings-card coming-soon">
            <div class="settings-card-header">
                <div class="settings-card-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="settings-card-title">
                    <h3>{{ __('staff.nav_attendance') }} {{ __('common.settings') }}</h3>
                    <p>Configure attendance tracking and policies</p>
                </div>
            </div>
            <div class="coming-soon-badge">{{ __('common.coming_soon') }}</div>
        </div>

        <div class="settings-card coming-soon">
            <div class="settings-card-header">
                <div class="settings-card-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="settings-card-title">
                    <h3>{{ __('staff.nav_payroll') }} {{ __('common.settings') }}</h3>
                    <p>Manage payroll configurations and rates</p>
                </div>
            </div>
            <div class="coming-soon-badge">{{ __('common.coming_soon') }}</div>
        </div>

        <div class="settings-card coming-soon">
            <div class="settings-card-header">
                <div class="settings-card-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <div class="settings-card-title">
                    <h3>{{ __('staff.nav_performance') }} {{ __('common.settings') }}</h3>
                    <p>Configure performance review criteria and schedules</p>
                </div>
            </div>
            <div class="coming-soon-badge">{{ __('common.coming_soon') }}</div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.staff-settings-index {
    padding: var(--nav-item-padding);
}

.settings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 1.5rem;
}

.settings-card {
    background: var(--color-surface-card);
    border: 1px solid var(--color-border-base);
    border-radius: var(--nav-item-radius);
    padding: 1.5rem;
    transition: var(--transition-all);
    position: relative;
}

.settings-card:hover {
    background: var(--color-surface-card-hover);
    border-color: var(--color-primary);
    transform: var(--hover-scale);
}

.settings-card.coming-soon {
    opacity: 0.7;
}

.settings-card.coming-soon:hover {
    transform: none;
    border-color: var(--color-border-base);
}

.settings-card-header {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.settings-card-icon {
    width: 3rem;
    height: 3rem;
    background: var(--color-primary);
    color: var(--button-primary-text);
    border-radius: var(--nav-item-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.settings-card-icon svg {
    width: 1.5rem;
    height: 1.5rem;
}

.settings-card-title h3 {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--color-text-primary);
    margin: 0 0 0.5rem 0;
}

.settings-card-title p {
    font-size: 0.875rem;
    color: var(--color-text-secondary);
    margin: 0;
    line-height: 1.4;
}

.settings-card-stats {
    display: flex;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: var(--color-bg-tertiary);
    border-radius: var(--nav-item-radius);
}

.stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--color-primary);
    line-height: 1;
}

.stat-label {
    font-size: 0.75rem;
    color: var(--color-text-muted);
    margin-top: 0.25rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.settings-card-actions {
    display: flex;
    justify-content: flex-end;
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    background: var(--button-primary-bg);
    color: var(--button-primary-text);
    border-radius: var(--nav-item-radius);
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition-all);
    box-shadow: var(--button-primary-shadow);
}

.btn-primary:hover {
    background: var(--button-primary-hover-bg);
    box-shadow: var(--button-primary-hover-shadow);
    transform: var(--hover-scale);
}

.coming-soon-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: var(--color-warning-bg);
    color: var(--color-warning);
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Responsive Design */
@media (max-width: 768px) {
    .settings-grid {
        grid-template-columns: 1fr;
    }
    
    .settings-card-stats {
        flex-direction: column;
        gap: 1rem;
    }
    
    .stat-item {
        flex-direction: row;
        justify-content: space-between;
        text-align: left;
    }
}
</style>
@endpush
