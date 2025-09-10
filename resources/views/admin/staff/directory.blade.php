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
<div class="container" x-data="{ viewMode: 'grid' }" aria-live="polite">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold mb-2">{{ __('staff.nav_directory') }}</h1>
        <p class="text-secondary">{{ __('staff.directory_subtitle') }}</p>
    </div>

    <!-- Filters + Actions -->
    <div class="staff-filters">
        <!-- Search -->
        <div class="staff-search">
            <label for="search" class="sr-only">{{ __('common.search') }}</label>
            <div class="flex items-center gap-sm bg-card border border-main rounded-md px-3 py-2">
                <i class="fas fa-search text-muted"></i>
                <input id="search" type="text" class="flex-1 bg-transparent outline-none text-primary" placeholder="{{ __('staff.search_employees') }}" />
            </div>
        </div>

        <!-- Filter Group -->
        <div class="staff-filter-group">
            <label class="sr-only" for="department">{{ __('staff.departments') }}</label>
            <select id="department" class="bg-card border border-main rounded-md px-3 py-2 text-primary">
                <option value="">{{ __('staff.all_departments') }}</option>
                <option>{{ __('staff.kitchen') }}</option>
                <option>{{ __('staff.service') }}</option>
                <option>{{ __('staff.administration') }}</option>
                <option>{{ __('staff.maintenance') }}</option>
            </select>

            <label class="sr-only" for="position">{{ __('staff.positions') }}</label>
            <select id="position" class="bg-card border border-main rounded-md px-3 py-2 text-primary">
                <option value="">{{ __('staff.all_positions') }}</option>
                <option>{{ __('staff.manager') }}</option>
                <option>{{ __('staff.supervisor') }}</option>
                <option>{{ __('staff.chef') }}</option>
                <option>{{ __('staff.waiter') }}</option>
                <option>{{ __('staff.cashier') }}</option>
            </select>

            <label class="sr-only" for="status">{{ __('common.status') }}</label>
            <select id="status" class="bg-card border border-main rounded-md px-3 py-2 text-primary">
                <option value="">{{ __('staff.all_statuses') }}</option>
                <option>{{ __('common.active') }}</option>
                <option>{{ __('common.inactive') }}</option>
            </select>
        </div>

        <!-- View toggle -->
        <div class="ml-auto view-toggle" role="tablist" aria-label="{{ __('common.view') }}">
            <button type="button" class="view-toggle-btn" :class="{ 'active': viewMode === 'grid' }" @click="viewMode = 'grid'" :aria-selected="viewMode === 'grid'" aria-label="{{ __('staff.view_grid') }}">
                <i class="fas fa-grip"></i>
            </button>
            <button type="button" class="view-toggle-btn" :class="{ 'active': viewMode === 'list' }" @click="viewMode = 'list'" :aria-selected="viewMode === 'list'" aria-label="{{ __('staff.view_list') }}">
                <i class="fas fa-list"></i>
            </button>
        </div>
    </div>

    <!-- Summary Row -->
    <div class="flex items-center justify-between mb-4 text-sm text-secondary">
        <div>
            {{ __('common.showing') }} 8 {{ __('common.results') }}
        </div>
        <div>
            <!-- Placeholder for sort dropdown if needed later -->
        </div>
    </div>

    <!-- GRID VIEW -->
    <div x-show="viewMode === 'grid'" class="employee-grid" style="display: none;">
        @include('admin.staff.partials.directory-grid')
    </div>

    <!-- LIST VIEW -->
    <div x-show="viewMode === 'list'" class="flex flex-col gap-md" style="display: none;">
        @include('admin.staff.partials.directory-list')
    </div>
</div>
@endsection
