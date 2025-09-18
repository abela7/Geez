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
<div class="staff-directory-page" x-data="{ viewMode: 'grid' }" aria-live="polite">
    <!-- Page Header -->
    <div class="staff-directory-header">
        <div class="staff-directory-title">
            <h1 class="page-title">{{ __('staff.nav_directory') }}</h1>
            <p class="page-subtitle">{{ __('staff.directory_subtitle') }}</p>
        </div>
    </div>

    <!-- Filters + Actions -->
    <div class="staff-directory-filters">
        <form method="GET" class="staff-filters-form" role="search">
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
                <label class="sr-only" for="department">{{ __('staff.departments') }}</label>
                <select id="department" class="staff-filter-select" disabled title="Coming soon">
                    <option value="">{{ __('staff.all_departments') }}</option>
                    <option>{{ __('staff.kitchen') }}</option>
                    <option>{{ __('staff.service') }}</option>
                    <option>{{ __('staff.administration') }}</option>
                    <option>{{ __('staff.maintenance') }}</option>
                </select>

                <label class="sr-only" for="staff_type_id">{{ __('staff.staff_type') }}</label>
                <select id="staff_type_id" name="staff_type_id" class="staff-filter-select" onchange="this.form.submit()">
                    <option value="">{{ __('staff.all_types') }}</option>
                    @foreach (($staffTypes ?? []) as $type)
                        <option value="{{ $type->id }}" @selected(request('staff_type_id') === $type->id)>{{ $type->display_name }}</option>
                    @endforeach
                </select>

                <label class="sr-only" for="status">{{ __('common.status') }}</label>
                <select id="status" name="status" class="staff-filter-select" onchange="this.form.submit()">
                    <option value="">{{ __('staff.all_statuses') }}</option>
                    <option value="active" @selected(request('status') === 'active')>{{ __('staff.status_values.active') }}</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>{{ __('staff.status_values.inactive') }}</option>
                    <option value="suspended" @selected(request('status') === 'suspended')>{{ __('staff.status_values.suspended') }}</option>
                </select>
            </div>

            <!-- View toggle -->
            <div class="staff-view-toggle" role="tablist" aria-label="{{ __('common.view') }}">
                <button type="button" class="view-toggle-btn" :class="{ 'active': viewMode === 'grid' }" @click="viewMode = 'grid'" :aria-selected="viewMode === 'grid'" aria-label="{{ __('staff.view_grid') }}">
                    <i class="fas fa-grip"></i>
                </button>
                <button type="button" class="view-toggle-btn" :class="{ 'active': viewMode === 'list' }" @click="viewMode = 'list'" :aria-selected="viewMode === 'list'" aria-label="{{ __('staff.view_list') }}">
                    <i class="fas fa-list"></i>
                </button>
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
        <div x-show="viewMode === 'grid'" class="staff-grid-view" style="display: none;">
            @include('admin.staff.partials.directory-grid')
        </div>

        <!-- LIST VIEW -->
        <div x-show="viewMode === 'list'" class="staff-list-view" style="display: none;">
            @include('admin.staff.partials.directory-list')
        </div>
    </div>

    <!-- Pagination -->
    <div class="staff-directory-pagination">
        {{ isset($staff) ? $staff->links() : '' }}
    </div>
</div>
@endsection
