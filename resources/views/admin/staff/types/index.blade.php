@extends('layouts.admin')

@section('content')
<div class="staff-types-index">
    <div class="page-actions">
        <a href="{{ route('admin.staff.types.trashed') }}" class="btn-secondary">
            <i class="fas fa-trash-alt"></i>
            {{ __('admin.staff.types.trashed') }}
        </a>
        <a href="{{ route('admin.staff.types.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i>
            {{ __('admin.staff.types.add_new') }}
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
        @if($staffTypes->count() > 0)
            <div class="data-table">
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('staff.types.display_name') }}</th>
                            <th>{{ __('staff.types.name') }}</th>
                            <th>{{ __('staff.types.priority') }}</th>
                            <th>{{ __('staff.types.total_staff') }}</th>
                            <th>{{ __('staff.types.description') }}</th>
                            <th>{{ __('common.status') }}</th>
                            <th class="actions-col">{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($staffTypes as $staffType)
                            <tr>
                                <td>
                                    <div class="staff-type-info">
                                        <div class="priority-badge priority-{{ $staffType->priority }}">
                                            {{ $staffType->priority }}
                                        </div>
                                        <div class="type-details">
                                            <strong>{{ $staffType->display_name }}</strong>
                                            <div class="priority-level">{{ $staffType->priority_level }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <code class="internal-name">{{ $staffType->name }}</code>
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ $staffType->priority }}</span>
                                </td>
                                <td>
                                    @if($staffType->staff_count > 0)
                                        <span class="badge badge-primary">{{ $staffType->staff_count }}</span>
                                    @else
                                        <span class="text-muted">{{ __('staff.types.no_staff_assigned') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="description-text" title="{{ $staffType->description }}">
                                        {{ $staffType->description ?? __('common.no_description') }}
                                    </div>
                                </td>
                                <td>
                                    @if($staffType->is_active)
                                        <span class="badge badge-success">{{ __('staff.types.active') }}</span>
                                    @else
                                        <span class="badge badge-secondary">{{ __('staff.types.inactive') }}</span>
                                    @endif
                                </td>
                                <td class="actions-col">
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.staff.types.show', $staffType) }}" 
                                           class="btn-icon btn-info" 
                                           title="{{ __('staff.types.view_details') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.staff.types.edit', $staffType) }}" 
                                           class="btn-icon btn-primary" 
                                           title="{{ __('staff.types.edit_type') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.staff.types.toggle-active', $staffType) }}" 
                                              method="POST" 
                                              class="inline-form">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="btn-icon btn-{{ $staffType->is_active ? 'warning' : 'success' }}" 
                                                    title="{{ $staffType->is_active ? __('staff.types.deactivate') : __('staff.types.activate') }}"
                                                    onclick="return confirm('{{ __('staff.types.confirm_toggle_active') }}')">
                                                <i class="fas fa-{{ $staffType->is_active ? 'pause' : 'play' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.staff.types.destroy', $staffType) }}" 
                                              method="POST" 
                                              class="inline-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn-icon btn-danger" 
                                                    title="{{ __('staff.types.delete_type') }}"
                                                    onclick="return confirm('{{ __('staff.types.confirm_delete') }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
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
                        'first' => $staffTypes->firstItem(),
                        'last' => $staffTypes->lastItem(),
                        'total' => $staffTypes->total()
                    ]) }}
                </div>
                {{ $staffTypes->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-users-cog"></i>
                <h3>{{ __('admin.staff.types.no_types_found') }}</h3>
                <p>{{ __('staff.types.no_types_description') }}</p>
                <a href="{{ route('admin.staff.types.create') }}" class="btn-primary">
                    <i class="fas fa-plus"></i>
                    {{ __('admin.staff.types.add_new') }}
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.staff-types-index {
    padding: var(--nav-item-padding);
}

.page-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
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

.content-card {
    background: var(--color-surface-card);
    border-radius: var(--nav-item-radius);
    box-shadow: var(--color-surface-card-shadow);
    border: 1px solid var(--color-surface-card-border);
    overflow: hidden;
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

.priority-badge.priority-100 { background: var(--color-error); }
.priority-badge.priority-80 { background: var(--color-warning); }
.priority-badge.priority-60 { background: var(--color-primary); }
.priority-badge.priority-40 { background: var(--color-info); }
.priority-badge.priority-20 { background: var(--color-text-muted); }
.priority-badge { background: var(--color-text-muted); }

.type-details strong {
    color: var(--color-text-primary);
}

.priority-level {
    font-size: 0.75rem;
    color: var(--color-text-muted);
}

.internal-name {
    background: var(--color-bg-tertiary);
    color: var(--color-primary);
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-family: monospace;
}

.badge {
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 500;
}

.badge-success { background: var(--color-success-bg); color: var(--color-success); }
.badge-warning { background: var(--color-warning-bg); color: var(--color-warning); }
.badge-error { background: var(--color-error-bg); color: var(--color-error); }
.badge-info { background: var(--color-info-bg); color: var(--color-info); }
.badge-primary { background: var(--color-primary); color: var(--button-primary-text); }
.badge-secondary { background: var(--color-text-muted); color: var(--button-primary-text); }

.description-text {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.text-muted {
    color: var(--color-text-muted);
}

.actions-col {
    width: 200px;
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

.btn-icon.btn-info { background: var(--color-info-bg); color: var(--color-info); }
.btn-icon.btn-primary { background: var(--color-primary); color: var(--button-primary-text); }
.btn-icon.btn-warning { background: var(--color-warning-bg); color: var(--color-warning); }
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
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
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
</style>
@endpush