@extends('layouts.admin')

@section('content')
<div class="staff-overview-container w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <!-- Overview Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 sm:gap-8">
        <div class="flex-1 w-full sm:w-auto">
            <h1 class="text-2xl sm:text-3xl font-bold text-primary dark:text-white mb-1 sm:mb-2 leading-tight">{{ __('staff.title') }}</h1>
            <p class="text-sm sm:text-base text-secondary dark:text-gray-400 leading-relaxed">{{ __('staff.overview') }}</p>
        </div>
        <div class="flex flex-wrap gap-3 sm:gap-4 w-full sm:w-auto ml-0 sm:ml-auto mt-3 sm:mt-0 self-end sm:self-center">
            <a href="{{ route('admin.staff.directory.index') }}" class="btn-secondary flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 hover:shadow-md">
                <i class="fas fa-th-large"></i> {{ __('staff.directory') }}
            </a>
            <a href="{{ route('admin.staff.create') }}" class="btn-primary flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 hover:shadow-lg transform hover:-translate-y-0.5">
                <i class="fas fa-plus"></i> {{ __('staff.add_new') }}
            </a>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="filters-card card bg-surface-card border border-border rounded-xl overflow-hidden shadow-sm">
        <div class="p-6">
            <form method="GET" action="{{ route('admin.staff.index') }}" class="space-y-4 md:space-y-0">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="filter-group">
                        <label class="block text-sm font-medium text-text-secondary dark:text-text-secondary mb-2">{{ __('staff.search_label') }}</label>
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-icon-default"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('staff.search_placeholder') }}" class="w-full pl-10 pr-4 py-2 border border-border dark:border-border rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-all bg-surface-card text-text-primary placeholder-text-muted">
                        </div>
                    </div>
                    <div class="filter-group">
                        <label class="block text-sm font-medium text-text-secondary dark:text-text-secondary mb-2">{{ __('staff.staff_type_label') }}</label>
                        <select name="staff_type_id" class="form-select w-full px-4 py-2 border border-border dark:border-border rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-all bg-surface-card text-text-primary">
                            <option value="">{{ __('staff.all_types') }}</option>
                            @foreach($staffTypes as $staffType)
                            <option value="{{ $staffType->id }}" {{ request('staff_type_id') == $staffType->id ? 'selected' : '' }}>
                                {{ $staffType->display_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="block text-sm font-medium text-text-secondary dark:text-text-secondary mb-2">{{ __('staff.status_label') }}</label>
                        <select name="status" class="form-select w-full px-4 py-2 border border-border dark:border-border rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-all bg-surface-card text-text-primary">
                            <option value="">{{ __('staff.all_statuses') }}</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('staff.status_values.active') }}</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('staff.status_values.inactive') }}</option>
                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>{{ __('staff.status_values.suspended') }}</option>
                        </select>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 justify-start sm:justify-end mt-4 md:mt-0">
                    <button type="submit" class="btn-primary w-full sm:w-auto px-6 py-2 text-sm font-medium rounded-lg transition-all duration-200 hover:shadow-lg transform hover:-translate-y-0.5">
                        {{ __('common.apply') }}
                    </button>
                    <a href="{{ route('admin.staff.index') }}" class="btn-secondary w-full sm:w-auto px-6 py-2 text-sm font-medium rounded-lg transition-all duration-200 hover:shadow-md">
                        {{ __('common.clear') }}
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Total Staff -->
        <div class="stat-card staff-stat-total card bg-surface-card border border-border border-l-4 border-primary rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
            <div class="stat-card-inner flex items-center justify-between">
                <div class="stat-content flex-1">
                    <p class="stat-label text-sm font-medium text-text-secondary dark:text-text-secondary mb-1">{{ __('staff.total_staff') }}</p>
                    <p class="stat-value text-3xl font-bold text-primary dark:text-white tracking-tight">{{ $totalStats['total'] ?? 0 }}</p>
                </div>
                <div class="stat-icon bg-primary/10 dark:bg-primary/20 p-3 rounded-full flex-shrink-0 shadow-lg">
                    <i class="fas fa-users text-2xl text-primary dark:text-white"></i>
                </div>
            </div>
        </div>

        <!-- Active Staff -->
        <div class="stat-card staff-stat-active card bg-surface-card border border-border border-l-4 border-success rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
            <div class="stat-card-inner flex items-center justify-between">
                <div class="stat-content flex-1">
                    <p class="stat-label text-sm font-medium text-text-secondary dark:text-text-secondary mb-1">{{ __('staff.active_staff') }}</p>
                    <p class="stat-value text-3xl font-bold text-success dark:text-white tracking-tight">{{ $totalStats['active'] ?? 0 }}</p>
                </div>
                <div class="stat-icon bg-success/10 dark:bg-success/20 p-3 rounded-full flex-shrink-0 shadow-lg">
                    <i class="fas fa-user-check text-2xl text-success dark:text-white"></i>
                </div>
            </div>
        </div>

        <!-- Recent Hires -->
        <div class="stat-card card bg-surface-card border border-border border-l-4 border-info rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
            <div class="stat-card-inner flex items-center justify-between">
                <div class="stat-content flex-1">
                    <p class="stat-label text-sm font-medium text-text-secondary dark:text-text-secondary mb-1">{{ __('staff.recent_hires') }}</p>
                    <p class="stat-value text-3xl font-bold text-info dark:text-white tracking-tight">{{ $totalStats['recent_hires'] ?? 0 }}</p>
                </div>
                <div class="stat-icon bg-info/10 dark:bg-info/20 p-3 rounded-full flex-shrink-0 shadow-lg">
                    <i class="fas fa-user-plus text-2xl text-info dark:text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Staff Members Preview Section -->
    <div class="card bg-surface-card border border-border rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-border flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-primary dark:text-white mb-2">{{ __('staff.staff_members_title') }}</h2>
                <p class="text-text-secondary dark:text-gray-400">{{ __('staff.staff_members_count', ['count' => $staffPreview->count()]) }}</p>
            </div>
            <a href="{{ route('admin.staff.directory.index') }}" class="btn-secondary flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 hover:shadow-md">
                <i class="fas fa-th-large"></i> {{ __('staff.directory') }}
            </a>
        </div>
        <div class="p-6">
            @forelse($staffPreview as $member)
                <div class="staff-card flex items-center p-4 mb-4 last:mb-0 bg-bg-tertiary dark:bg-gray-900 border-l-4 border-primary/20 rounded-lg transition-all duration-200 hover:bg-surface-card-hover hover:border-primary/40 shadow-sm">
                    <div class="avatar-small flex-shrink-0 mr-4">
                        @if ($member->profile && $member->profile->photo_url)
                            <img src="{{ $member->profile->photo_url }}" alt="{{ $member->full_name }}" class="w-12 h-12 rounded-full object-cover border-2 border-border shadow-md">
                        @else
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center border-2 border-border shadow-md">
                                <i class="fas fa-user text-sm text-white"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base sm:text-lg font-semibold text-primary dark:text-white truncate">{{ $member->full_name }}</h3>
                        <p class="text-sm text-text-secondary dark:text-gray-400 truncate">{{ $member->staffType->display_name ?? 'Staff' }}</p>
                        <p class="text-xs text-text-muted dark:text-gray-400">{{ $member->hire_date ? $member->hire_date->format('M j, Y') : 'N/A' }}</p>
                    </div>
                    <div class="flex-shrink-0 text-right ml-4">
                        <span class="status-badge employee-status status-{{ $member->status }} inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide">
                            {{ __('staff.status_values.' . $member->status) }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <i class="fas fa-users text-4xl text-text-muted dark:text-gray-500 mb-4"></i>
                    <p class="text-text-secondary dark:text-gray-400 text-sm sm:text-base">{{ __('staff.no_staff_members') }}</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection