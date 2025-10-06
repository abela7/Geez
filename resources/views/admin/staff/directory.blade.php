@extends('layouts.admin')

@section('title', __('staff.nav_directory') . ' - ' . config('app.name'))
@section('page_title', __('staff.nav_directory'))

@push('styles')
@vite(['resources/css/admin/staff.css'])
@endpush

@push('scripts')
@vite(['resources/js/admin/staff.js'])
@endpush

@section('content')
<div class="staff-directory-page" x-data="{ viewMode: '{{ $viewMode ?? "grid" }}' }" x-init="viewMode = '{{ $viewMode ?? "grid" }}'" aria-live="polite">
    <!-- Page Header -->
    <div class="staff-directory-header">
        <div class="staff-directory-title">
            <h1 class="page-title">{{ __('staff.nav_directory') }}</h1>
            <p class="page-subtitle">{{ __('staff.directory_subtitle') }}</p>
        </div>
    </div>

    <!-- Filters + Actions -->
    <div class="staff-directory-filters">
        <form method="GET" action="{{ route('admin.staff.directory.index') }}" class="staff-filters-form" role="search">
            <!-- Search -->
            <div class="staff-search-container">
                <label for="search" class="sr-only">{{ __('common.search') }}</label>
                <div class="staff-search-input">
                    <i class="fas fa-search"></i>
                    <input id="search" name="search" type="text" value="{{ request('search') }}" placeholder="{{ __('staff.search_placeholder') }}" />
                </div>
            </div>

            <!-- Filter Group -->
            <div class="staff-filter-controls">
                <label for="department" class="sr-only">{{ __('staff.departments') }}</label>
                <select id="department" name="department" class="staff-filter-select" onchange="this.form.submit()">
                    @foreach ($departments ?? [] as $value => $label)
                        <option value="{{ $value }}" {{ request('department') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>

                <label for="staff_type_id" class="sr-only">{{ __('staff.staff_type') }}</label>
                <select id="staff_type_id" name="staff_type_id" class="staff-filter-select" onchange="this.form.submit()">
                    <option value="">{{ __('staff.all_types') }}</option>
                    @foreach (($staffTypes ?? []) as $type)
                        <option value="{{ $type->id }}" {{ request('staff_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->display_name }}
                        </option>
                    @endforeach
                </select>

                <label for="status" class="sr-submit-button" for="status">{{ __('common.status') }}</label>
                <select id="status" name="status" class="staff-filter-select" onchange="this.form.submit()">
                    <option value="">{{ __('staff.all_statuses') }}</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('staff.status_values.active') }}</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('staff.status_values.inactive') }}</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>{{ __('staff.status_values.suspended') }}</option>
                </select>
            </div>

            <!-- View toggle (server-side trigger with links) -->
            <div class="staff-view-toggle" role="tablist" aria-label="{{ __('common.view') }}">
                <a href="{{ route('admin.staff.directory.index', request()->except('view') + ['view' => 'grid']) }}" 
                   class="view-toggle-btn {{ request('view') != 'list' ? 'active' : '' }}" 
                   :aria-selected="viewMode === 'grid'"
                   aria-label="{{ __('staff.view_grid') }}">
                    <i class="fas fa-grip"></i>
                </a>
                <a href="{{ route('admin.staff.directory.index', request()->except('view') + ['view' => 'list']) }}" 
                   class="view-toggle-btn {{ request('view') == 'list' ? 'active' : '' }}" 
                   :aria-selected="viewMode === 'list'"
                   aria-label="{{ __('staff.view_list') }}">
                    <i class="fas fa-list"></i>
                </a>
            </div>

            <!-- Submit + Clear (separate from view toggle) -->
            <div class="flex gap-2 sm:gap-3">
                <button type="submit" class="btn-primary px-4 py-2 text-sm">{{ __('common.apply') }}</button>
                <a href="{{ route('admin.staff.directory.index') }}" class="btn-secondary px-4 py-2 text-sm">{{ __('common.clear') }}</a>
            </div>
        </form>
    </div>

    <!-- Summary Row -->
    <div class="staff-directory-summary">
        <div class="staff-results-count">
            {{ __('common.showing') }} {{ isset($staff) ? $staff->total() : 0 }} {{ __('common.results') }}
        </div>
        <div class="staff-sort-options">
            <!-- Placeholder for sort dropdown if needed later -->
        </div>
    </div>

    <!-- Staff Content -->
    <div class="staff-directory-content">
        <!-- GRID VIEW -->
        <div x-show="viewMode === 'grid'" class="staff-grid-view">
            @include('admin.staff.partials.directory-grid')
        </div>

        <!-- LIST VIEW -->
        <div x-show="viewMode === 'list'" class="staff-list-view">
            @include('admin.staff.partials.directory-list')
        </div>
    </div>

    <!-- Pagination -->
    <div class="staff-directory-pagination">
        {{ isset($staff) ? $staff->links() : '' }}
    </div>
</div>
@endsection
