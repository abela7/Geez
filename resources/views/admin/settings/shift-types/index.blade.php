@extends('layouts.admin')

@section('title', __('admin.shift_types.title') . ' - ' . config('app.name'))
@section('page_title', __('admin.shift_types.title'))

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
                <h1 class="page-title">{{ __('admin.shift_types.title') }}</h1>
                <p class="page-subtitle">{{ __('admin.shift_types.description') }}</p>
            </div>
            
            <div class="page-actions">
                <a href="{{ route('admin.settings.shift-types.create') }}" class="btn btn-primary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('admin.shift_types.create_new') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Dashboard Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon stat-icon-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $shiftTypes->total() ?? 0 }}</div>
                <div class="stat-label">{{ __('admin.shift_types.total_shift_types') }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-success">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $shiftTypes->where('is_active', true)->count() ?? 0 }}</div>
                <div class="stat-label">{{ __('admin.shift_types.active_shift_types') }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-warning">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $shiftTypes->where('is_active', false)->count() ?? 0 }}</div>
                <div class="stat-label">{{ __('admin.shift_types.inactive_shift_types') }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-info">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $shiftTypes->whereNotNull('default_hourly_rate')->count() ?? 0 }}</div>
                <div class="stat-label">{{ __('admin.shift_types.with_rates') }}</div>
            </div>
        </div>
    </div>

    <!-- Shift Types List -->
    <div class="shift-types-list-section">
        @if($shiftTypes->count() > 0)
            <div class="table-container">
                <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('admin.shift_types.name') }}</th>
                        <th>{{ __('admin.shift_types.description') }}</th>
                        <th>{{ __('admin.shift_types.color') }}</th>
                        <th>{{ __('admin.shift_types.default_rates') }}</th>
                        <th>{{ __('admin.shift_types.status') }}</th>
                        <th>{{ __('admin.shift_types.sort_order') }}</th>
                        <th class="actions-column">{{ __('admin.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shiftTypes as $shiftType)
                    <tr class="table-row">
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="shift-type-color-indicator" style="background-color: {{ $shiftType->color }}"></div>
                                <span class="font-medium">{{ $shiftType->name }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="text-secondary">{{ $shiftType->description ?? '—' }}</span>
                        </td>
                        <td>
                            <div class="color-swatch" style="background-color: {{ $shiftType->color }}">
                                {{ $shiftType->color }}
                            </div>
                        </td>
                        <td>
                            <div class="rates-display">
                                @if($shiftType->default_hourly_rate)
                                <div class="rate-item">
                                    <span class="rate-label">Hourly:</span>
                                    <span class="rate-value">£{{ number_format($shiftType->default_hourly_rate, 2) }}</span>
                                </div>
                                @endif
                                @if($shiftType->default_overtime_rate)
                                <div class="rate-item">
                                    <span class="rate-label">Overtime:</span>
                                    <span class="rate-value">£{{ number_format($shiftType->default_overtime_rate, 2) }}</span>
                                </div>
                                @endif
                                @if(!$shiftType->default_hourly_rate && !$shiftType->default_overtime_rate)
                                <span class="text-secondary">—</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="status-badge {{ $shiftType->is_active ? 'status-active' : 'status-inactive' }}">
                                {{ $shiftType->is_active ? __('admin.common.active') : __('admin.common.inactive') }}
                            </span>
                        </td>
                        <td>{{ $shiftType->sort_order }}</td>
                        <td class="actions-column">
                            <div class="actions-menu-container">
                                <button class="actions-trigger" onclick="toggleActionsMenu(this)">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                    </svg>
                                </button>
                                <div class="actions-menu">
                                    <a href="{{ route('admin.settings.shift-types.show', $shiftType) }}" class="action-item">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        {{ __('admin.common.view') }}
                                    </a>
                                    <a href="{{ route('admin.settings.shift-types.edit', $shiftType) }}" class="action-item">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        {{ __('admin.common.edit') }}
                                    </a>
                                    <form method="POST" action="{{ route('admin.settings.shift-types.toggle-status', $shiftType) }}" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="action-item">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                            </svg>
                                            {{ $shiftType->is_active ? __('admin.common.deactivate') : __('admin.common.activate') }}
                                        </button>
                                    </form>
                                    <div class="action-separator"></div>
                                    <form method="POST" action="{{ route('admin.settings.shift-types.destroy', $shiftType) }}" style="display: inline;" onsubmit="return confirm('{{ __('admin.shift_types.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-item action-danger">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            {{ __('admin.common.delete') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="empty-state">
                            <div class="empty-state-content">
                                <div class="empty-state-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h3 class="empty-state-title">{{ __('admin.shift_types.no_shift_types') }}</h3>
                                <p class="empty-state-description">{{ __('admin.shift_types.no_shift_types_description') }}</p>
                                <a href="{{ route('admin.settings.shift-types.create') }}" class="btn btn-primary">
                                    {{ __('admin.shift_types.create_first') }}
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            @if($shiftTypes->hasPages())
                <div class="table-pagination">
                    {{ $shiftTypes->links() }}
                </div>
            @endif
        </div>
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="empty-state-title">{{ __('admin.shift_types.no_shift_types') }}</h3>
                <p class="empty-state-description">{{ __('admin.shift_types.no_shift_types_description') }}</p>
                <a href="{{ route('admin.settings.shift-types.create') }}" class="btn btn-primary">
                    {{ __('admin.shift_types.create_first') }}
                </a>
            </div>
        @endif
</div>

@endsection

@push('styles')
<style>
.shift-types-page {
    padding: var(--page-padding);
    max-width: var(--page-max-width);
    margin: 0 auto;
    background: var(--color-bg-primary);
    min-height: calc(100vh - 200px);
    padding-bottom: 200px; /* Extra space for dropdowns */
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

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--grid-gap);
    margin-bottom: var(--section-spacing);
}

.stat-card {
    background: var(--color-surface-card);
    border: 1px solid var(--color-surface-card-border);
    border-radius: 0.75rem;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: var(--color-surface-card-shadow);
    transition: var(--transition-all);
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
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
}

.stat-icon-primary {
    background: var(--color-primary-50);
    color: var(--color-primary-600);
}

.stat-icon-success {
    background: var(--color-success-50);
    color: var(--color-success-600);
}

.stat-icon-warning {
    background: var(--color-warning-50);
    color: var(--color-warning-600);
}

.stat-icon-info {
    background: var(--color-info-50);
    color: var(--color-info-600);
}

.stat-content {
    flex: 1;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: var(--color-text-primary);
    line-height: 1;
}

.stat-label {
    color: var(--color-text-secondary);
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.shift-types-list-section {
    background: var(--color-surface-card);
    border: 1px solid var(--color-surface-card-border);
    border-radius: 0.75rem;
    overflow: visible;
    box-shadow: var(--color-surface-card-shadow);
}

.table-container {
    overflow-x: auto;
    overflow-y: visible;
    scroll-behavior: smooth;
}

/* Custom scrollbar for better visibility */
.table-container::-webkit-scrollbar {
    width: 12px;
}

.table-container::-webkit-scrollbar-track {
    background: var(--color-bg-secondary);
    border-radius: 6px;
}

.table-container::-webkit-scrollbar-thumb {
    background: var(--color-primary-600);
    border-radius: 6px;
}

.table-container::-webkit-scrollbar-thumb:hover {
    background: var(--color-primary-700);
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th {
    background: var(--table-header-bg);
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: var(--table-header-text);
    border-bottom: 1px solid var(--table-row-border);
}

.data-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--table-row-border);
    vertical-align: top;
}

.table-row:hover {
    background: var(--table-row-hover);
}

.shift-type-color-indicator {
    width: 1rem;
    height: 1rem;
    border-radius: 50%;
    border: 2px solid var(--color-surface-card-border);
    flex-shrink: 0;
}

.color-swatch {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-family: monospace;
    color: white;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
    font-weight: 500;
}

.rates-display {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.rate-item {
    display: flex;
    gap: 0.5rem;
    font-size: 0.875rem;
}

.rate-label {
    color: var(--color-text-secondary);
    font-weight: 500;
}

.rate-value {
    color: var(--color-text-primary);
    font-weight: 600;
}

.actions-menu-container {
    position: relative;
}

.actions-trigger {
    padding: 0.5rem;
    border: none;
    background: var(--color-bg-secondary);
    border-radius: 0.375rem;
    cursor: pointer;
    color: var(--color-text-secondary);
    transition: var(--transition-all);
}

.actions-trigger:hover {
    background: var(--color-bg-tertiary);
    color: var(--color-text-primary);
}

.actions-trigger svg {
    width: 16px;
    height: 16px;
}

.actions-menu {
    position: absolute;
    right: 0;
    top: calc(100% + 0.5rem);
    background: var(--color-surface-card);
    border: 1px solid var(--color-surface-card-border);
    border-radius: 0.5rem;
    box-shadow: var(--shadow-lg);
    padding: 0.5rem;
    min-width: 200px;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: var(--transition-all);
    pointer-events: none;
}

.actions-menu-container {
    position: relative;
    z-index: 1000;
}

.actions-menu.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
    pointer-events: auto;
}

.action-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem 0.75rem;
    color: var(--color-text-secondary);
    text-decoration: none;
    border-radius: 0.375rem;
    transition: var(--transition-all);
    font-size: 0.875rem;
    border: none;
    background: none;
    cursor: pointer;
    width: 100%;
    text-align: left;
}

.action-item:hover {
    background: var(--color-bg-secondary);
    color: var(--color-text-primary);
}

.action-item.action-danger {
    color: var(--color-error-600);
}

.action-item.action-danger:hover {
    background: var(--color-error-50);
    color: var(--color-error-700);
}

.action-item svg {
    width: 16px;
    height: 16px;
}

.action-separator {
    height: 1px;
    background: var(--color-surface-card-border);
    margin: 0.5rem 0;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-state-icon {
    width: 4rem;
    height: 4rem;
    margin: 0 auto 1rem;
    color: var(--color-text-tertiary);
}

.empty-state-icon svg {
    width: 100%;
    height: 100%;
}

.empty-state-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--color-text-primary);
    margin: 0 0 0.5rem 0;
}

.empty-state-description {
    color: var(--color-text-secondary);
    margin: 0 0 2rem 0;
}

.table-pagination {
    padding: 1rem;
    border-top: 1px solid var(--table-row-border);
}
</style>
@endpush

<script>
function toggleActionsMenu(button) {
    const menu = button.nextElementSibling;
    const isOpen = menu.classList.contains('show');
    
    // Close all open menus
    document.querySelectorAll('.actions-menu.show').forEach(m => {
        m.classList.remove('show');
    });
    
    // Toggle current menu
    if (!isOpen) {
        menu.classList.add('show');
    }
}

// Close menus when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.actions-menu-container')) {
        document.querySelectorAll('.actions-menu.show').forEach(menu => {
            menu.classList.remove('show');
        });
    }
});
</script>
