@extends('layouts.admin')

@section('content')
<div class="staff-types-trashed">
    <div class="page-actions">
        <a href="{{ route('admin.staff.types.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i>
            {{ __('common.back') }}
        </a>
    </div>

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

    <div class="content-card">
        <div class="card-header">
            <i class="fas fa-trash-alt"></i>
            {{ __('admin.staff.types.trashed') }}
        </div>
        
        @if($trashedStaffTypes->count() > 0)
            <div class="notice-banner">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>{{ __('common.notice') }}:</strong>
                {{ __('staff.types.trashed_notice') }}
            </div>

            <div class="data-table">
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('staff.types.display_name') }}</th>
                            <th>{{ __('staff.types.name') }}</th>
                            <th>{{ __('staff.types.priority') }}</th>
                            <th>{{ __('staff.types.total_staff') }}</th>
                            <th>{{ __('common.deleted_at') }}</th>
                            <th class="actions-col">{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trashedStaffTypes as $staffType)
                            <tr class="trashed-row">
                                <td>
                                    <div class="staff-type-info">
                                        <div class="priority-badge trashed">
                                            {{ $staffType->priority }}
                                        </div>
                                        <div class="type-details">
                                            <strong class="deleted-text">{{ $staffType->display_name }}</strong>
                                            <div class="priority-level">{{ $staffType->priority_level }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <code class="internal-name deleted">{{ $staffType->name }}</code>
                                </td>
                                <td>
                                    <span class="badge badge-secondary">{{ $staffType->priority }}</span>
                                </td>
                                <td>
                                    @if($staffType->staff_count > 0)
                                        <span class="badge badge-warning">{{ $staffType->staff_count }}</span>
                                        <div class="has-staff-notice">{{ __('staff.types.has_staff_members') }}</div>
                                    @else
                                        <span class="text-muted">{{ __('staff.types.no_staff_assigned') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="deleted-info">
                                        <div>{{ $staffType->deleted_at->format('M d, Y H:i') }}</div>
                                        <div class="time-ago">{{ $staffType->deleted_at->diffForHumans() }}</div>
                                    </div>
                                </td>
                                <td class="actions-col">
                                    <div class="action-buttons">
                                        <form action="{{ route('admin.staff.types.restore', $staffType->id) }}" 
                                              method="POST" 
                                              class="inline-form">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="btn-icon btn-success" 
                                                    title="{{ __('staff.types.restore_type') }}"
                                                    onclick="return confirm('{{ __('staff.types.confirm_restore') }}')">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        </form>
                                        
                                        @if($staffType->staff_count == 0)
                                            <form action="{{ route('admin.staff.types.force-delete', $staffType->id) }}" 
                                                  method="POST" 
                                                  class="inline-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn-icon btn-danger" 
                                                        title="{{ __('staff.types.force_delete') }}"
                                                        onclick="return confirm('{{ __('staff.types.confirm_force_delete') }}')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn-icon btn-danger disabled" 
                                                    disabled 
                                                    title="{{ __('staff.types.cannot_force_delete_has_staff') }}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrapper">
                <div class="pagination-info">
                    {{ __('common.showing_results', [
                        'first' => $trashedStaffTypes->firstItem(),
                        'last' => $trashedStaffTypes->lastItem(),
                        'total' => $trashedStaffTypes->total()
                    ]) }}
                </div>
                {{ $trashedStaffTypes->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-trash-alt"></i>
                <h3>{{ __('admin.staff.types.no_trashed_types') }}</h3>
                <p>{{ __('staff.types.no_trashed_description') }}</p>
                <a href="{{ route('admin.staff.types.index') }}" class="btn-primary">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('admin.staff.types.back_to_list') }}
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.staff-types-trashed {
    padding: var(--nav-item-padding);
}

.page-actions {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 1.5rem;
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

.content-card {
    background: var(--color-surface-card);
    border-radius: var(--nav-item-radius);
    box-shadow: var(--color-surface-card-shadow);
    border: 1px solid var(--color-surface-card-border);
    overflow: hidden;
}

.card-header {
    padding: 1.5rem;
    background: var(--color-error-bg);
    border-bottom: 1px solid var(--color-error);
    color: var(--color-error);
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.notice-banner {
    padding: 1rem 1.5rem;
    background: var(--color-warning-bg);
    color: var(--color-warning);
    border-bottom: 1px solid var(--color-border-base);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.data-table {
    overflow-x: auto;
}

.data-table table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th,
.data-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid var(--color-border-base);
}

.data-table th {
    background: var(--color-bg-tertiary);
    color: var(--color-text-primary);
    font-weight: 600;
}

.data-table td {
    color: var(--color-text-secondary);
}

.trashed-row {
    opacity: 0.7;
}

.staff-type-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.priority-badge {
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.75rem;
    color: var(--button-primary-text);
}

.priority-badge.trashed {
    background: var(--color-error);
}

.type-details .deleted-text {
    color: var(--color-text-muted);
}

.priority-level {
    font-size: 0.75rem;
    color: var(--color-text-muted);
}

.internal-name {
    background: var(--color-bg-tertiary);
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-family: monospace;
}

.internal-name.deleted {
    color: var(--color-text-muted);
}

.badge {
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 500;
}

.badge-warning { background: var(--color-warning-bg); color: var(--color-warning); }
.badge-secondary { background: var(--color-text-muted); color: var(--button-primary-text); }

.has-staff-notice {
    font-size: 0.75rem;
    color: var(--color-text-muted);
    margin-top: 0.25rem;
}

.text-muted {
    color: var(--color-text-muted);
}

.deleted-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.time-ago {
    font-size: 0.75rem;
    color: var(--color-text-muted);
}

.actions-col {
    width: 120px;
}

.action-buttons {
    display: flex;
    gap: 0.25rem;
}

.btn-icon {
    width: 2rem;
    height: 2rem;
    border-radius: 0.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    cursor: pointer;
    transition: var(--transition-all);
    text-decoration: none;
}

.btn-icon.btn-success { background: var(--color-success-bg); color: var(--color-success); }
.btn-icon.btn-danger { background: var(--color-error-bg); color: var(--color-error); }

.btn-icon:hover:not(.disabled) {
    transform: var(--hover-scale);
    opacity: 0.8;
}

.btn-icon.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.inline-form {
    display: inline;
}

.pagination-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--color-border-base);
}

.pagination-info {
    color: var(--color-text-muted);
    font-size: 0.875rem;
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-state i {
    font-size: 3rem;
    color: var(--color-text-muted);
    margin-bottom: 1rem;
}

.empty-state h3 {
    color: var(--color-text-primary);
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: var(--color-text-muted);
    margin-bottom: 1.5rem;
}

.btn-primary,
.btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    border-radius: var(--nav-item-radius);
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition-all);
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: var(--button-primary-bg);
    color: var(--button-primary-text);
    box-shadow: var(--button-primary-shadow);
}

.btn-primary:hover {
    background: var(--button-primary-hover-bg);
    transform: var(--hover-scale);
}

.btn-secondary {
    background: var(--button-secondary-bg);
    color: var(--button-secondary-text);
}

.btn-secondary:hover {
    background: var(--button-secondary-hover-bg);
    transform: var(--hover-scale);
}
</style>
@endpush