@extends('layouts.admin')

@section('content')
<div class="staff-types-show">
    <div class="page-actions">
        <a href="{{ route('admin.staff.types.edit', $staffType) }}" class="btn-primary">
            <i class="fas fa-edit"></i>
            {{ __('common.edit') }}
        </a>
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

    <div class="content-layout">
        <!-- Staff Type Details -->
        <div class="details-card">
            <div class="card-header">
                <i class="fas fa-info-circle"></i>
                {{ __('admin.staff.types.details') }}
            </div>
            
            <div class="details-content">
                <div class="detail-row">
                    <span class="detail-label">{{ __('staff.types.display_name') }}:</span>
                    <span class="detail-value">{{ $staffType->display_name }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">{{ __('staff.types.name') }}:</span>
                    <span class="detail-value">
                        <code class="internal-name">{{ $staffType->name }}</code>
                    </span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">{{ __('staff.types.priority') }}:</span>
                    <span class="detail-value">
                        <span class="badge badge-info">{{ $staffType->priority }}</span>
                        <div class="priority-level">{{ $staffType->priority_level }}</div>
                    </span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">{{ __('common.status') }}:</span>
                    <span class="detail-value">
                        @if($staffType->is_active)
                            <span class="badge badge-success">{{ __('staff.types.active') }}</span>
                        @else
                            <span class="badge badge-secondary">{{ __('staff.types.inactive') }}</span>
                        @endif
                    </span>
                </div>
                
                @if($staffType->description)
                    <div class="detail-row">
                        <span class="detail-label">{{ __('staff.types.description') }}:</span>
                        <span class="detail-value">{{ $staffType->description }}</span>
                    </div>
                @endif
                
                <div class="detail-row">
                    <span class="detail-label">{{ __('common.created_at') }}:</span>
                    <span class="detail-value">
                        {{ $staffType->created_at->format('M d, Y H:i') }}
                        @if($staffType->creator)
                            <div class="created-by">{{ __('common.by') }} {{ $staffType->creator->full_name }}</div>
                        @endif
                    </span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">{{ __('common.updated_at') }}:</span>
                    <span class="detail-value">
                        {{ $staffType->updated_at->format('M d, Y H:i') }}
                        @if($staffType->updater)
                            <div class="updated-by">{{ __('common.by') }} {{ $staffType->updater->full_name }}</div>
                        @endif
                    </span>
                </div>

                <div class="action-buttons">
                    <form action="{{ route('admin.staff.types.toggle-active', $staffType) }}" method="POST" class="inline-form">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="btn-{{ $staffType->is_active ? 'warning' : 'success' }}"
                                onclick="return confirm('{{ __('staff.types.confirm_toggle_active') }}')">
                            <i class="fas fa-{{ $staffType->is_active ? 'pause' : 'play' }}"></i>
                            {{ $staffType->is_active ? __('staff.types.deactivate') : __('staff.types.activate') }}
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.staff.types.destroy', $staffType) }}" method="POST" class="inline-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="btn-danger"
                                onclick="return confirm('{{ __('staff.types.confirm_delete') }}')"
                                {{ $staffType->staff->where('status', 'active')->count() > 0 ? 'disabled title="'.__('staff.types.cannot_delete_has_active_staff').'"' : '' }}>
                            <i class="fas fa-trash"></i>
                            {{ __('common.delete') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Assigned Staff -->
        <div class="staff-card">
            <div class="card-header">
                <div class="header-content">
                    <i class="fas fa-users"></i>
                    {{ __('admin.staff.types.assigned_staff') }}
                    <span class="staff-count">{{ $staffType->staff->count() }}</span>
                </div>
                @if($staffType->staff->count() > 0)
                    <div class="active-count">
                        {{ __('staff.types.active_staff') }}: {{ $staffType->staff->where('status', 'active')->count() }}
                    </div>
                @endif
            </div>
            
            <div class="staff-content">
                @if($staffType->staff->count() > 0)
                    <div class="staff-list">
                        @foreach($staffType->staff as $staff)
                            <div class="staff-item">
                                <div class="staff-avatar">
                                    {{ strtoupper(substr($staff->first_name, 0, 1) . substr($staff->last_name, 0, 1)) }}
                                </div>
                                <div class="staff-info">
                                    <strong>{{ $staff->full_name }}</strong>
                                    <div class="staff-username">{{ $staff->username }}</div>
                                    @if($staff->years_of_service)
                                        <div class="years-service">{{ number_format($staff->years_of_service, 1) }} {{ __('staff.years_of_service') }}</div>
                                    @endif
                                </div>
                                <div class="staff-details">
                                    @if($staff->hire_date)
                                        <div class="hire-date">{{ $staff->hire_date->format('M d, Y') }}</div>
                                    @endif
                                    <div class="staff-status">
                                        @switch($staff->status)
                                            @case('active')
                                                <span class="badge badge-success">{{ __('staff.status.active') }}</span>
                                                @break
                                            @case('inactive')
                                                <span class="badge badge-secondary">{{ __('staff.status.inactive') }}</span>
                                                @break
                                            @case('suspended')
                                                <span class="badge badge-warning">{{ __('staff.status.suspended') }}</span>
                                                @break
                                            @default
                                                <span class="badge badge-light">{{ ucfirst($staff->status) }}</span>
                                        @endswitch
                                    </div>
                                </div>
                                <div class="staff-actions">
                                    <a href="#" class="btn-icon btn-info" title="{{ __('staff.view_profile') }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" class="btn-icon btn-primary" title="{{ __('staff.edit_staff') }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-users"></i>
                        <h3>{{ __('staff.types.no_staff_assigned') }}</h3>
                        <p>{{ __('staff.types.no_staff_description') }}</p>
                        <a href="#" class="btn-primary">
                            <i class="fas fa-plus"></i>
                            {{ __('staff.add_staff') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.staff-types-show {
    padding: var(--nav-item-padding);
}

.page-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
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

.content-layout {
    display: grid;
    grid-template-columns: 400px 1fr;
    gap: 1.5rem;
}

@media (max-width: 1024px) {
    .content-layout {
        grid-template-columns: 1fr;
    }
}

.details-card,
.staff-card {
    background: var(--color-surface-card);
    border-radius: var(--nav-item-radius);
    box-shadow: var(--color-surface-card-shadow);
    border: 1px solid var(--color-surface-card-border);
    overflow: hidden;
}

.card-header {
    padding: 1.5rem;
    background: var(--color-bg-tertiary);
    border-bottom: 1px solid var(--color-border-base);
    color: var(--color-text-primary);
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
}

.header-content {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.staff-count {
    background: var(--color-primary);
    color: var(--button-primary-text);
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
}

.active-count {
    color: var(--color-text-muted);
    font-size: 0.75rem;
    font-weight: normal;
}

.details-content {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.detail-row {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.detail-label {
    font-weight: 500;
    color: var(--color-text-primary);
    font-size: 0.875rem;
}

.detail-value {
    color: var(--color-text-secondary);
}

.internal-name {
    background: var(--color-bg-tertiary);
    color: var(--color-primary);
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-family: monospace;
}

.priority-level {
    font-size: 0.75rem;
    color: var(--color-text-muted);
    margin-top: 0.25rem;
}

.created-by,
.updated-by {
    font-size: 0.75rem;
    color: var(--color-text-muted);
    margin-top: 0.25rem;
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
.badge-light { background: var(--color-bg-tertiary); color: var(--color-text-muted); }

.action-buttons {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--color-border-base);
}

.staff-content {
    padding: 1.5rem;
}

.staff-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.staff-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border: 1px solid var(--color-border-base);
    border-radius: var(--nav-item-radius);
    transition: var(--transition-all);
}

.staff-item:hover {
    background: var(--color-surface-card-hover);
    border-color: var(--color-primary);
}

.staff-avatar {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    background: var(--color-primary);
    color: var(--button-primary-text);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.875rem;
}

.staff-info {
    flex: 1;
}

.staff-info strong {
    color: var(--color-text-primary);
}

.staff-username {
    font-family: monospace;
    color: var(--color-primary);
    font-size: 0.875rem;
}

.years-service {
    color: var(--color-text-muted);
    font-size: 0.75rem;
}

.staff-details {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.25rem;
}

.hire-date {
    color: var(--color-text-muted);
    font-size: 0.75rem;
}

.staff-actions {
    display: flex;
    gap: 0.25rem;
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
.btn-secondary,
.btn-warning,
.btn-success,
.btn-danger {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: var(--nav-item-radius);
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition-all);
    border: none;
    cursor: pointer;
    font-size: 0.875rem;
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

.btn-warning {
    background: var(--color-warning-bg);
    color: var(--color-warning);
    border: 1px solid var(--color-warning);
}

.btn-warning:hover {
    background: var(--color-warning);
    color: var(--button-primary-text);
    transform: var(--hover-scale);
}

.btn-success {
    background: var(--color-success-bg);
    color: var(--color-success);
    border: 1px solid var(--color-success);
}

.btn-success:hover {
    background: var(--color-success);
    color: var(--button-primary-text);
    transform: var(--hover-scale);
}

.btn-danger {
    background: var(--color-error-bg);
    color: var(--color-error);
    border: 1px solid var(--color-error);
}

.btn-danger:hover {
    background: var(--color-error);
    color: var(--button-primary-text);
    transform: var(--hover-scale);
}

.btn-danger:disabled {
    opacity: 0.5;
    cursor: not-allowed;
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

.btn-icon:hover {
    transform: var(--hover-scale);
    opacity: 0.8;
}

.inline-form {
    display: inline;
}
</style>
@endpush