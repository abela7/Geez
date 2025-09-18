@extends('layouts.admin')

@section('content')
<div class="staff-index">
    <!-- Page Actions -->
    <div class="page-actions">
        <a href="{{ route('admin.staff.trashed') }}" class="btn-secondary">
            <i class="fas fa-trash-alt"></i>
            {{ __('staff.trashed') }}
        </a>
        <a href="{{ route('admin.staff.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i>
            {{ __('staff.add_new') }}
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

    <!-- Search & Filters -->
    <div class="filters-card">
        <form method="GET" action="{{ route('admin.staff.index') }}" class="filters-form">
            <div class="filter-row">
                <div class="filter-group">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="{{ __('staff.search_placeholder') }}"
                           class="search-input">
                </div>
                
                <div class="filter-group">
                    <select name="staff_type_id" class="filter-select">
                        <option value="">{{ __('staff.all_types') }}</option>
                        @foreach($staffTypes as $staffType)
                            <option value="{{ $staffType->id }}" 
                                    {{ request('staff_type_id') == $staffType->id ? 'selected' : '' }}>
                                {{ $staffType->display_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="filter-group">
                    <select name="status" class="filter-select">
                        <option value="">{{ __('staff.all_statuses') }}</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                            {{ __('staff.status_values.active') }}
                        </option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                            {{ __('staff.status_values.inactive') }}
                        </option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>
                            {{ __('staff.status_values.suspended') }}
                        </option>
                    </select>
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-search"></i>
                        {{ __('common.search') }}
                    </button>
                    <a href="{{ route('admin.staff.index') }}" class="btn-clear">
                        <i class="fas fa-times"></i>
                        {{ __('common.clear') }}
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Staff Table -->
    <div class="content-card">
        @if($staff->count() > 0)
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>{{ __('staff.first_name') }}</th>
                            <th>{{ __('staff.last_name') }}</th>
                            <th>{{ __('staff.username') }}</th>
                            <th>{{ __('staff.email') }}</th>
                            <th>{{ __('staff.phone') }}</th>
                            <th>{{ __('staff.staff_type') }}</th>
                            <th>{{ __('staff.status') }}</th>
                            <th>{{ __('staff.hire_date') }}</th>
                            <th>{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($staff as $member)
                            <tr>
                                <td class="font-medium">{{ $member->first_name }}</td>
                                <td class="font-medium">{{ $member->last_name }}</td>
                                <td>
                                    <span class="username-badge">{{ $member->username }}</span>
                                </td>
                                <td>
                                    @if($member->email)
                                        <a href="mailto:{{ $member->email }}" class="email-link">
                                            {{ $member->email }}
                                        </a>
                                    @else
                                        <span class="text-muted">{{ __('common.not_set') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($member->phone)
                                        <a href="tel:{{ $member->phone }}" class="phone-link">
                                            {{ $member->phone }}
                                        </a>
                                    @else
                                        <span class="text-muted">{{ __('common.not_set') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="staff-type-badge priority-{{ $member->staffType->priority ?? 0 }}">
                                        {{ $member->staffType->display_name ?? __('common.not_set') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge status-{{ $member->status }}">
                                        {{ __('staff.status_values.' . $member->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($member->hire_date)
                                        <span class="hire-date">
                                            {{ $member->hire_date->format('M j, Y') }}
                                        </span>
                                        <small class="years-service">
                                            ({{ $member->hire_date->diffInYears(now()) }} {{ __('staff.years_of_service') }})
                                        </small>
                                    @else
                                        <span class="text-muted">{{ __('common.not_set') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.staff.show', $member) }}" 
                                           class="btn-icon btn-info" 
                                           title="{{ __('staff.view_details') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.staff.edit', $member) }}" 
                                           class="btn-icon btn-primary" 
                                           title="{{ __('staff.edit_staff') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($member->id !== Auth::id())
                                            <form method="POST" 
                                                  action="{{ route('admin.staff.destroy', $member) }}" 
                                                  class="inline-form"
                                                  onsubmit="return confirm('{{ __('staff.confirm_delete') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn-icon btn-danger" 
                                                        title="{{ __('staff.delete_staff') }}">
                                                    <i class="fas fa-trash"></i>
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
                        'first' => $staff->firstItem() ?? 0,
                        'last' => $staff->lastItem() ?? 0,
                        'total' => $staff->total()
                    ]) }}
                </div>
                {{ $staff->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <h3>{{ __('staff.no_staff_found') }}</h3>
                @if(request()->hasAny(['search', 'staff_type_id', 'status']))
                    <p>{{ __('staff.no_search_results') }}</p>
                    <a href="{{ route('admin.staff.index') }}" class="btn-secondary">
                        <i class="fas fa-times"></i>
                        {{ __('common.clear_filters') }}
                    </a>
                @else
                    <p>{{ __('staff.no_staff_description') }}</p>
                    <a href="{{ route('admin.staff.create') }}" class="btn-primary">
                        <i class="fas fa-plus"></i>
                        {{ __('staff.add_new') }}
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.staff-index {
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
    grid-template-columns: 2fr 1fr 1fr auto;
    gap: 1rem;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.search-input, .filter-select {
    padding: 0.75rem;
    border: 1px solid var(--color-border-base);
    border-radius: var(--nav-item-radius);
    background: var(--color-bg-secondary);
    color: var(--color-text-primary);
    font-size: 0.875rem;
    transition: var(--transition-all);
}

.search-input:focus, .filter-select:focus {
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

.table-responsive {
    overflow-x: auto;
    /* Hide horizontal scrollbar but keep functionality */
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none; /* Internet Explorer 10+ */
}

.table-responsive::-webkit-scrollbar {
    display: none; /* WebKit browsers (Chrome, Safari, Edge) */
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

.email-link, .phone-link {
    color: var(--color-primary);
    text-decoration: none;
    transition: var(--transition-all);
}

.email-link:hover, .phone-link:hover {
    color: var(--color-secondary);
    text-decoration: underline;
}

.staff-type-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.staff-type-badge.priority-100 { background: var(--color-error-bg); color: var(--color-error); }
.staff-type-badge.priority-80 { background: var(--color-warning-bg); color: var(--color-warning); }
.staff-type-badge.priority-60 { background: var(--color-primary); color: var(--button-primary-text); }
.staff-type-badge.priority-40 { background: var(--color-info-bg); color: var(--color-info); }
.staff-type-badge.priority-20 { background: var(--color-bg-tertiary); color: var(--color-text-muted); }
.staff-type-badge { background: var(--color-bg-tertiary); color: var(--color-text-muted); }

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

.hire-date {
    display: block;
    font-weight: 500;
}

.years-service {
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

.btn-icon.btn-info { background: var(--color-info-bg); color: var(--color-info); }
.btn-icon.btn-primary { background: var(--color-primary); color: var(--button-primary-text); }
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
@media (max-width: 1024px) {
    .filter-row {
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    
    .filter-actions {
        grid-column: 1 / -1;
        justify-content: center;
    }
}

@media (max-width: 768px) {
    .filter-row {
        grid-template-columns: 1fr;
    }
    
    .page-actions {
        flex-direction: column;
        align-items: stretch;
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