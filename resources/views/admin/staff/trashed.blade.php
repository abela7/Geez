@extends('layouts.admin')

@section('content')
<div class="staff-trashed">
    <!-- Page Actions -->
    <div class="page-actions">
        <a href="{{ route('admin.staff.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i>
            {{ __('common.back') }}
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Search Filter -->
    <div class="filters-card">
        <form method="GET" action="{{ route('admin.staff.trashed') }}" class="filters-form">
            <div class="filter-row">
                <div class="filter-group">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="{{ __('staff.search_placeholder') }}"
                           class="search-input">
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-search"></i>
                        {{ __('common.search') }}
                    </button>
                    <a href="{{ route('admin.staff.trashed') }}" class="btn-clear">
                        <i class="fas fa-times"></i>
                        {{ __('common.clear') }}
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Trashed Staff Table -->
    <div class="content-card">
        <div class="card-header">
            <i class="fas fa-trash-alt"></i>
            {{ __('staff.trashed') }}
        </div>

        @if($trashedStaff->count() > 0)
            <!-- Info Notice -->
            <div class="trashed-notice">
                <i class="fas fa-info-circle"></i>
                {{ __('staff.trashed_notice') }}
            </div>

            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>{{ __('staff.full_name') }}</th>
                            <th>{{ __('staff.username') }}</th>
                            <th>{{ __('staff.email') }}</th>
                            <th>{{ __('staff.staff_type') }}</th>
                            <th>{{ __('staff.status') }}</th>
                            <th>{{ __('common.deleted_at') }}</th>
                            <th>{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trashedStaff as $member)
                            <tr>
                                <td class="font-medium">
                                    {{ $member->full_name }}
                                </td>
                                <td>
                                    <span class="username-badge">{{ $member->username }}</span>
                                </td>
                                <td>
                                    @if($member->email)
                                        <span class="email-text">{{ $member->email }}</span>
                                    @else
                                        <span class="text-muted">{{ __('common.not_set') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="staff-type-badge trashed">
                                        {{ $member->staffType->display_name ?? __('common.not_set') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge status-{{ $member->status }}">
                                        {{ __('staff.status_values.' . $member->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="delete-date">
                                        {{ $member->deleted_at->format('M j, Y') }}
                                    </span>
                                    <small class="delete-time">
                                        {{ $member->deleted_at->format('g:i A') }}
                                    </small>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <form method="POST" 
                                              action="{{ route('admin.staff.restore', $member->id) }}" 
                                              class="inline-form">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="btn-icon btn-success" 
                                                    title="{{ __('staff.restore_staff') }}"
                                                    onclick="return confirm('{{ __('staff.confirm_restore') }}')">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        </form>
                                        
                                        @if($member->id !== Auth::id())
                                            <form method="POST" 
                                                  action="{{ route('admin.staff.force-delete', $member->id) }}" 
                                                  class="inline-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn-icon btn-danger" 
                                                        title="{{ __('staff.force_delete') }}"
                                                        onclick="return confirm('{{ __('staff.confirm_force_delete') }}')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                <div class="pagination-info">
                    {{ __('common.showing_results', [
                        'first' => $trashedStaff->firstItem() ?? 0,
                        'last' => $trashedStaff->lastItem() ?? 0,
                        'total' => $trashedStaff->total()
                    ]) }}
                </div>
                {{ $trashedStaff->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-trash-alt"></i>
                <h3>{{ __('staff.no_trashed_staff') }}</h3>
                @if(request('search'))
                    <p>{{ __('staff.no_search_results') }}</p>
                    <a href="{{ route('admin.staff.trashed') }}" class="btn-secondary">
                        <i class="fas fa-times"></i>
                        {{ __('common.clear_search') }}
                    </a>
                @else
                    <p>{{ __('staff.no_trashed_description') }}</p>
                    <a href="{{ route('admin.staff.index') }}" class="btn-primary">
                        <i class="fas fa-users"></i>
                        {{ __('staff.view_active_staff') }}
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.staff-trashed {
    padding: var(--nav-item-padding);
}

.page-actions {
    display: flex;
    justify-content: flex-start;
    margin-bottom: 1.5rem;
}

.btn-primary, .btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    border-radius: var(--nav-item-radius);
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition-all);
}

.btn-primary {
    background: var(--button-primary-bg);
    color: var(--button-primary-text);
    box-shadow: var(--button-primary-shadow);
}

.btn-primary:hover {
    background: var(--button-primary-hover-bg);
    box-shadow: var(--button-primary-hover-shadow);
    transform: var(--hover-scale);
}

.btn-secondary {
    background: var(--button-secondary-bg);
    color: var(--button-secondary-text);
    box-shadow: var(--button-secondary-shadow);
}

.btn-secondary:hover {
    background: var(--button-secondary-hover-bg);
    transform: var(--hover-scale);
}

.alert {
    padding: 1rem;
    border-radius: var(--nav-item-radius);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.alert-success {
    background: var(--color-success-bg);
    color: var(--color-success);
    border: 1px solid var(--color-success);
}

.alert-error {
    background: var(--color-error-bg);
    color: var(--color-error);
    border: 1px solid var(--color-error);
}

.filters-card {
    background: var(--color-surface-card);
    border: 1px solid var(--color-border-base);
    border-radius: var(--nav-item-radius);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.filters-form {
    width: 100%;
}

.filter-row {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 1rem;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.search-input {
    padding: 0.75rem;
    border: 1px solid var(--color-border-base);
    border-radius: var(--nav-item-radius);
    background: var(--color-bg-secondary);
    color: var(--color-text-primary);
    font-size: 0.875rem;
    transition: var(--transition-all);
}

.search-input:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(var(--color-primary-rgb), 0.1);
}

.filter-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-filter, .btn-clear {
    padding: 0.75rem 1rem;
    border-radius: var(--nav-item-radius);
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition-all);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    border: none;
    cursor: pointer;
    font-size: 0.875rem;
}

.btn-filter {
    background: var(--color-primary);
    color: var(--button-primary-text);
}

.btn-filter:hover {
    background: var(--color-secondary);
    transform: var(--hover-scale);
}

.btn-clear {
    background: var(--color-bg-tertiary);
    color: var(--color-text-secondary);
}

.btn-clear:hover {
    background: var(--color-border-base);
}

.content-card {
    background: var(--color-surface-card);
    border: 1px solid var(--color-border-base);
    border-radius: var(--nav-item-radius);
    overflow: hidden;
}

.card-header {
    background: var(--color-bg-tertiary);
    color: var(--color-text-primary);
    padding: 1.5rem;
    font-size: 1.125rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border-bottom: 1px solid var(--color-border-base);
}

.trashed-notice {
    background: var(--color-warning-bg);
    color: var(--color-warning);
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--color-border-base);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
}

.table-responsive {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th {
    background: var(--color-bg-tertiary);
    color: var(--color-text-primary);
    font-weight: 600;
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid var(--color-border-base);
    white-space: nowrap;
}

.data-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--color-border-base);
    color: var(--color-text-primary);
    vertical-align: middle;
}

.data-table tr:hover {
    background: var(--color-surface-card-hover);
}

.font-medium {
    font-weight: 500;
}

.text-muted {
    color: var(--color-text-muted);
    font-style: italic;
}

.username-badge {
    background: var(--color-bg-tertiary);
    color: var(--color-text-primary);
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-family: monospace;
    font-size: 0.875rem;
}

.email-text {
    color: var(--color-text-secondary);
}

.staff-type-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.staff-type-badge.trashed {
    background: var(--color-error-bg);
    color: var(--color-error);
}

.status-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-badge.status-active { background: var(--color-success-bg); color: var(--color-success); }
.status-badge.status-inactive { background: var(--color-bg-tertiary); color: var(--color-text-muted); }
.status-badge.status-suspended { background: var(--color-error-bg); color: var(--color-error); }

.delete-date {
    display: block;
    font-weight: 500;
}

.delete-time {
    color: var(--color-text-muted);
    font-size: 0.75rem;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.btn-icon {
    width: 2rem;
    height: 2rem;
    border-radius: var(--nav-item-radius);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: var(--transition-all);
    font-size: 0.875rem;
}

.btn-icon.btn-success { background: var(--color-success-bg); color: var(--color-success); }
.btn-icon.btn-danger { background: var(--color-error-bg); color: var(--color-error); }

.btn-icon:hover {
    transform: var(--hover-scale);
    opacity: 0.8;
}

.inline-form {
    display: inline;
}

.pagination-wrapper {
    padding: 1rem;
    background: var(--color-bg-tertiary);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.pagination-info {
    font-size: 0.875rem;
    color: var(--color-text-secondary);
}

.empty-state {
    text-align: center;
    padding: 3rem 2rem;
}

.empty-state i {
    font-size: 3rem;
    color: var(--color-text-muted);
    margin-bottom: 1rem;
}

.empty-state h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--color-text-primary);
    margin: 0 0 0.5rem 0;
}

.empty-state p {
    color: var(--color-text-secondary);
    margin: 0 0 1.5rem 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .filter-row {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .filter-actions {
        justify-content: center;
    }
    
    .data-table {
        font-size: 0.875rem;
    }
    
    .data-table th,
    .data-table td {
        padding: 0.5rem;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .btn-icon {
        width: 1.75rem;
        height: 1.75rem;
        font-size: 0.75rem;
    }
}
</style>
@endpush
