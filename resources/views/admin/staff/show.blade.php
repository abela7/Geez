@extends('layouts.admin')

@section('title', $staff->full_name . ' - ' . __('staff.show') . ' - ' . config('app.name'))
@section('page_title', $staff->full_name)

@push('styles')
@vite(['resources/css/admin/staff.css'])
@endpush

@push('scripts')
@vite(['resources/js/admin/staff.js'])
@endpush

@section('content')
<div class="staff-profile-page">
    <!-- Breadcrumbs -->
    <nav class="breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb-list">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">
                    <i class="fas fa-home"></i>{{ __('common.dashboard') }}
                </a>
            </li>
            <li class="breadcrumb-item">
                <i class="fas fa-chevron-right breadcrumb-separator"></i>
                <a href="{{ route('admin.staff.directory.index') }}" class="breadcrumb-link">{{ __('staff.nav_directory') }}</a>
            </li>
            <li class="breadcrumb-item breadcrumb-current" aria-current="page">
                <i class="fas fa-chevron-right breadcrumb-separator"></i>
                <span>{{ $staff->full_name }}</span>
            </li>
        </ol>
    </nav>

    <!-- Staff Header -->
    <div class="staff-profile-header">
        <div class="staff-header-content">
            <div class="staff-header-info">
                <!-- Profile Photo -->
                <div class="staff-avatar-large">
                    @if ($staff->profile && $staff->profile->photo_url)
                        <img src="{{ $staff->profile->photo_url }}" alt="{{ $staff->full_name }}" class="staff-avatar-image" />
                    @else
                        <i class="fas fa-user staff-avatar-icon"></i>
    @endif
        </div>
                
                <!-- Basic Info -->
                <div class="staff-header-details">
                    <h1 class="staff-name">{{ $staff->full_name }}</h1>
                    <div class="staff-meta">
                        <span class="staff-meta-item">
                            <i class="fas fa-id-badge"></i>
                            {{ $staff->staffType?->display_name ?? __('staff.staff_type') }}
                        </span>
                        @if ($staff->profile && $staff->profile->employee_id)
                        <span class="staff-meta-item">
                            <i class="fas fa-hashtag"></i>
                            {{ $staff->profile->employee_id }}
                        </span>
                        @endif
                        <span class="staff-meta-item">
                            <i class="fas fa-calendar"></i>
                            {{ $stats['years_of_service'] }} {{ __('staff.years_of_service') }}
                        </span>
                    </div>
                    
                    <!-- Status Badge -->
                    <span class="employee-status {{ $staff->status }}">
                        <i class="fas fa-circle"></i>
                            {{ __('staff.status_values.' . $staff->status) }}
                        </span>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="staff-header-actions">
                <a href="{{ route('admin.staff.edit', $staff) }}" class="btn-secondary">
                    <i class="fas fa-edit"></i>{{ __('staff.edit_staff') }}
                </a>
                <button type="button" class="btn-primary" onclick="alert('{{ __('common.coming_soon') }}')">
                    <i class="fas fa-tasks"></i>{{ __('staff.assign_task') }}
                </button>
                </div>
            </div>
        </div>

    <!-- Statistics Cards -->
    <div class="staff-stats-grid">
        <!-- Hours This Month -->
        <div class="stat-card staff-stat-hours">
            <div class="stat-card-content">
                <div class="stat-card-info">
                    <p class="stat-card-label">{{ __('staff.total_hours') }} ({{ __('common.this_month') }})</p>
                    <p class="stat-card-value">{{ number_format($stats['total_hours_this_month'], 1) }}h</p>
                    </div>
                <div class="stat-card-icon">
                    <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>

        <!-- Attendance Rate -->
        <div class="stat-card staff-stat-attendance">
            <div class="stat-card-content">
                <div class="stat-card-info">
                    <p class="stat-card-label">{{ __('staff.attendance_rate') }}</p>
                    <p class="stat-card-value">{{ $stats['attendance_rate'] }}%</p>
                    </div>
                <div class="stat-card-icon">
                    <i class="fas fa-user-check"></i>
                    </div>
                </div>
            </div>

        <!-- Task Completion -->
        <div class="stat-card staff-stat-tasks">
            <div class="stat-card-content">
                <div class="stat-card-info">
                    <p class="stat-card-label">{{ __('staff.task_completion_rate') }}</p>
                    <p class="stat-card-value">{{ $stats['task_completion_rate'] }}%</p>
                    </div>
                <div class="stat-card-icon">
                    <i class="fas fa-tasks"></i>
                    </div>
                </div>
            </div>

        <!-- Performance Rating -->
        <div class="stat-card staff-stat-performance">
            <div class="stat-card-content">
                <div class="stat-card-info">
                    <p class="stat-card-label">{{ __('staff.performance_review') }}</p>
                    <p class="stat-card-value">
                        @if ($stats['average_performance_rating'])
                            {{ number_format($stats['average_performance_rating'], 1) }}/5
                            @else
                            —
                            @endif
                    </p>
                    </div>
                <div class="stat-card-icon">
                    <i class="fas fa-star"></i>
                    </div>
                    </div>
                </div>
            </div>

    <!-- Main Content Tabs -->
    <div x-data="{ activeTab: 'overview' }" class="staff-profile-tabs">
        <!-- Tab Navigation -->
        <div class="staff-nav-tabs">
            <nav class="staff-nav-list" aria-label="Tabs">
                <button @click="activeTab = 'overview'" :class="{ 'active': activeTab === 'overview' }" class="staff-nav-tab">
                    <i class="fas fa-user"></i>{{ __('staff.overview') }}
                </button>
                <button @click="activeTab = 'attendance'" :class="{ 'active': activeTab === 'attendance' }" class="staff-nav-tab">
                    <i class="fas fa-calendar-check"></i>{{ __('staff.nav_attendance') }}
                </button>
                <button @click="activeTab = 'tasks'" :class="{ 'active': activeTab === 'tasks' }" class="staff-nav-tab">
                    <i class="fas fa-tasks"></i>{{ __('staff.nav_tasks') }}
                </button>
                <button @click="activeTab = 'payroll'" :class="{ 'active': activeTab === 'payroll' }" class="staff-nav-tab">
                    <i class="fas fa-money-bill-wave"></i>{{ __('staff.nav_payroll') }}
                </button>
                <button @click="activeTab = 'performance'" :class="{ 'active': activeTab === 'performance' }" class="staff-nav-tab">
                    <i class="fas fa-chart-line"></i>{{ __('staff.nav_performance') }}
                </button>
                <button @click="activeTab = 'shifts'" :class="{ 'active': activeTab === 'shifts' }" class="staff-nav-tab">
                    <i class="fas fa-clock"></i>{{ __('staff.nav_shifts') }}
                </button>
            </nav>
                        </div>

        <!-- Tab Content -->
        <div class="staff-tab-content">
            <!-- Overview Tab -->
            <div x-show="activeTab === 'overview'" x-transition>
                @include('admin.staff.partials.profile-overview')
                        </div>

            <!-- Attendance Tab -->
            <div x-show="activeTab === 'attendance'" x-transition>
                @include('admin.staff.partials.profile-attendance')
                    </div>

            <!-- Tasks Tab -->
            <div x-show="activeTab === 'tasks'" x-transition>
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h3 class="empty-state-title">{{ __('staff.tasks.section_unavailable') }}</h3>
                    <p class="empty-state-description">{{ __('staff.tasks.section_unavailable_description') }}</p>
                </div>
                </div>

            <!-- Payroll Tab -->
            <div x-show="activeTab === 'payroll'" x-transition>
                @include('admin.staff.partials.profile-payroll')
                    </div>

            <!-- Performance Tab -->
            <div x-show="activeTab === 'performance'" x-transition>
                @include('admin.staff.partials.profile-performance')
                </div>

            <!-- Shifts Tab -->
            <div x-show="activeTab === 'shifts'" x-transition>
                @include('admin.staff.partials.profile-shifts')
            </div>
        </div>
    </div>

        <!-- Simple Delete Button at Bottom -->
        @if($staff->id !== Auth::id())
        <div class="mt-8 p-4 text-center border-t border-gray-200 dark:border-gray-700">
            <form action="{{ route('admin.staff.destroy', $staff) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('staff.confirm_delete') }} - {{ $staff->full_name }}?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger inline-flex items-center px-6 py-3 rounded-lg font-medium transition-colors bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white">
                    <i class="fas fa-trash-alt mr-2"></i>{{ __('staff.delete_staff') }}
                </button>
            </form>
        </div>
        @endif

</div>
@endsection
