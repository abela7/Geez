@extends('layouts.admin')

@section('title', __('staff.performance.title') . ' - ' . config('app.name'))
@section('page_title', __('staff.performance.title'))

@push('styles')
@vite(['resources/css/admin/staff-performance.css'])
@endpush

@push('scripts')
@vite(['resources/js/admin/staff-performance.js'])
@endpush

@section('content')
<div class="admin-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('staff.performance.title') }}</h1>
                <p class="page-subtitle">{{ __('staff.performance.subtitle') }}</p>
            </div>
            <div class="page-actions">
                <select class="form-select">
                    <option value="monthly">{{ __('staff.performance.filter_monthly') }}</option>
                    <option value="quarterly">{{ __('staff.performance.filter_quarterly') }}</option>
                    <option value="yearly">{{ __('staff.performance.filter_yearly') }}</option>
                </select>
                <button class="btn btn-primary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ __('staff.performance.export_report') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Performance Overview Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Overall Performance Card -->
        <div class="card performance-card performance-card-primary">
            <div class="performance-card-header">
                <div class="performance-card-icon performance-icon-primary">
                    <svg class="performance-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <span class="performance-card-label">{{ __('staff.performance.overall_score') }}</span>
            </div>
            <div class="performance-card-value">87.5%</div>
            <div class="performance-card-trend performance-trend-positive">
                <svg class="performance-trend-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                </svg>
                <span>{{ __('staff.performance.improvement_this_month') }}</span>
            </div>
        </div>

        <!-- Top Performers Card -->
        <div class="card performance-card performance-card-success">
            <div class="performance-card-header">
                <div class="performance-card-icon performance-icon-success">
                    <svg class="performance-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3l1.5 4.5L18 9l-4.5 1.5L12 15l-1.5-4.5L6 9l4.5-1.5L12 3z"></path>
                    </svg>
                </div>
                <span class="performance-card-label">{{ __('staff.performance.top_performers') }}</span>
            </div>
            <div class="performance-card-value">8</div>
            <div class="performance-card-trend performance-trend-neutral">
                <span>{{ __('staff.performance.above_target') }}</span>
            </div>
        </div>

        <!-- Needs Improvement Card -->
        <div class="card performance-card performance-card-warning">
            <div class="performance-card-header">
                <div class="performance-card-icon performance-icon-warning">
                    <svg class="performance-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <span class="performance-card-label">{{ __('staff.performance.needs_improvement') }}</span>
            </div>
            <div class="performance-card-value">3</div>
            <div class="performance-card-trend performance-trend-negative">
                <span>{{ __('staff.performance.below_target') }}</span>
            </div>
        </div>

        <!-- Reviews Due Card -->
        <div class="card performance-card performance-card-info">
            <div class="performance-card-header">
                <div class="performance-card-icon performance-icon-info">
                    <svg class="performance-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <span class="performance-card-label">{{ __('staff.performance.reviews_due') }}</span>
            </div>
            <div class="performance-card-value">5</div>
            <div class="performance-card-trend performance-trend-neutral">
                <span>{{ __('staff.performance.this_week') }}</span>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- Performance Chart & Top Performers -->
        <div class="xl:col-span-2 space-y-6">
            <!-- Performance Trends Chart -->
            <div class="bg-card rounded-lg shadow-md border border-main">
                <div class="p-4 border-b border-main">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-primary">{{ __('staff.performance.performance_trends') }}</h3>
                        <div class="flex items-center gap-2">
                            <button class="performance-chart-toggle active" data-period="3m">{{ __('staff.performance.three_months') }}</button>
                            <button class="performance-chart-toggle" data-period="6m">{{ __('staff.performance.six_months') }}</button>
                            <button class="performance-chart-toggle" data-period="1y">{{ __('staff.performance.one_year') }}</button>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="performance-chart-container">
                        <canvas id="performanceChart" class="w-full h-80"></canvas>
                    </div>
                </div>
            </div>

            <!-- Top Performers List -->
            <div class="bg-card rounded-lg shadow-md border border-main">
                <div class="p-4 border-b border-main">
                    <h3 class="text-lg font-semibold text-primary">{{ __('staff.performance.top_performers_list') }}</h3>
                </div>
                <div class="p-4">
                    <div class="space-y-4">
                        <!-- Top Performer Item -->
                        <div class="flex items-center gap-4 p-4 rounded-lg bg-card-hover border border-main">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-full bg-success/10 flex items-center justify-center">
                                    <span class="text-success font-bold">1</span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold text-primary">Sarah Johnson</h4>
                                        <p class="text-sm text-secondary">{{ __('staff.performance.kitchen_staff') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-lg font-bold text-success">95.2%</div>
                                        <div class="text-xs text-muted">{{ __('staff.performance.score') }}</div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <div class="performance-bar">
                                        <div class="performance-bar-fill" style="width: 95.2%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Top Performer Item -->
                        <div class="flex items-center gap-4 p-4 rounded-lg bg-card-hover border border-main">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-full bg-success/10 flex items-center justify-center">
                                    <span class="text-success font-bold">2</span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold text-primary">Mike Chen</h4>
                                        <p class="text-sm text-secondary">{{ __('staff.performance.service_staff') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-lg font-bold text-success">92.8%</div>
                                        <div class="text-xs text-muted">{{ __('staff.performance.score') }}</div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <div class="performance-bar">
                                        <div class="performance-bar-fill" style="width: 92.8%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Top Performer Item -->
                        <div class="flex items-center gap-4 p-4 rounded-lg bg-card-hover border border-main">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-full bg-success/10 flex items-center justify-center">
                                    <span class="text-success font-bold">3</span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold text-primary">Emma Rodriguez</h4>
                                        <p class="text-sm text-secondary">{{ __('staff.performance.kitchen_staff') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-lg font-bold text-success">91.5%</div>
                                        <div class="text-xs text-muted">{{ __('staff.performance.score') }}</div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <div class="performance-bar">
                                        <div class="performance-bar-fill" style="width: 91.5%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar: Actions & Reviews -->
        <div class="xl:col-span-1 space-y-6">
            <!-- Quick Actions -->
            <div class="bg-card rounded-lg shadow-md border border-main">
                <div class="p-4 border-b border-main">
                    <h3 class="text-lg font-semibold text-primary">{{ __('staff.performance.quick_actions') }}</h3>
                </div>
                <div class="p-4">
                    <div class="space-y-3">
                        <button class="w-full bg-primary-btn text-primary-btn px-4 py-3 rounded-md hover:opacity-90 transition-opacity flex items-center justify-center gap-2 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ __('staff.performance.schedule_review') }}
                        </button>
                        <button class="w-full bg-secondary-btn text-secondary-btn px-4 py-3 rounded-md hover:opacity-90 transition-opacity flex items-center justify-center gap-2 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            {{ __('staff.performance.view_analytics') }}
                        </button>
                        <button class="w-full bg-secondary-btn text-secondary-btn px-4 py-3 rounded-md hover:opacity-90 transition-opacity flex items-center justify-center gap-2 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            {{ __('staff.performance.team_comparison') }}
                        </button>
                        <button class="w-full bg-secondary-btn text-secondary-btn px-4 py-3 rounded-md hover:opacity-90 transition-opacity flex items-center justify-center gap-2 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ __('staff.performance.performance_settings') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Upcoming Reviews -->
            <div class="bg-card rounded-lg shadow-md border border-main">
                <div class="p-4 border-b border-main">
                    <h3 class="text-lg font-semibold text-primary">{{ __('staff.performance.upcoming_reviews') }}</h3>
                </div>
                <div class="p-4">
                    <div class="space-y-4">
                        <!-- Review Item -->
                        <div class="flex items-start gap-3 p-3 rounded-lg bg-card-hover border border-main">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-warning/10 flex items-center justify-center">
                                <svg class="w-4 h-4 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V6a2 2 0 012-2h4a2 2 0 012 2v1m-6 0h8m-8 0l1 12a2 2 0 002 2h6a2 2 0 002-2l1-12m-8 0V9a2 2 0 012-2h4a2 2 0 012 2v0"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-medium text-primary text-sm">David Wilson</h4>
                                <p class="text-xs text-secondary">{{ __('staff.performance.quarterly_review') }}</p>
                                <p class="text-xs text-warning mt-1">{{ __('staff.performance.due_tomorrow') }}</p>
                            </div>
                        </div>

                        <!-- Review Item -->
                        <div class="flex items-start gap-3 p-3 rounded-lg bg-card-hover border border-main">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-info/10 flex items-center justify-center">
                                <svg class="w-4 h-4 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V6a2 2 0 012-2h4a2 2 0 012 2v1m-6 0h8m-8 0l1 12a2 2 0 002 2h6a2 2 0 002-2l1-12m-8 0V9a2 2 0 012-2h4a2 2 0 012 2v0"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-medium text-primary text-sm">Lisa Park</h4>
                                <p class="text-xs text-secondary">{{ __('staff.performance.annual_review') }}</p>
                                <p class="text-xs text-info mt-1">{{ __('staff.performance.due_this_week') }}</p>
                            </div>
                        </div>

                        <!-- Review Item -->
                        <div class="flex items-start gap-3 p-3 rounded-lg bg-card-hover border border-main">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-info/10 flex items-center justify-center">
                                <svg class="w-4 h-4 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V6a2 2 0 012-2h4a2 2 0 012 2v1m-6 0h8m-8 0l1 12a2 2 0 002 2h6a2 2 0 002-2l1-12m-8 0V9a2 2 0 012-2h4a2 2 0 012 2v0"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-medium text-primary text-sm">James Taylor</h4>
                                <p class="text-xs text-secondary">{{ __('staff.performance.probation_review') }}</p>
                                <p class="text-xs text-info mt-1">{{ __('staff.performance.due_next_week') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Metrics -->
            <div class="bg-card rounded-lg shadow-md border border-main">
                <div class="p-4 border-b border-main">
                    <h3 class="text-lg font-semibold text-primary">{{ __('staff.performance.key_metrics') }}</h3>
                </div>
                <div class="p-4">
                    <div class="space-y-4">
                        <!-- Metric Item -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-secondary">{{ __('staff.performance.punctuality') }}</span>
                            <div class="flex items-center gap-2">
                                <div class="metric-bar">
                                    <div class="metric-bar-fill bg-success" style="width: 92%"></div>
                                </div>
                                <span class="text-sm font-medium text-primary">92%</span>
                            </div>
                        </div>

                        <!-- Metric Item -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-secondary">{{ __('staff.performance.productivity') }}</span>
                            <div class="flex items-center gap-2">
                                <div class="metric-bar">
                                    <div class="metric-bar-fill bg-success" style="width: 88%"></div>
                                </div>
                                <span class="text-sm font-medium text-primary">88%</span>
                            </div>
                        </div>

                        <!-- Metric Item -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-secondary">{{ __('staff.performance.customer_satisfaction') }}</span>
                            <div class="flex items-center gap-2">
                                <div class="metric-bar">
                                    <div class="metric-bar-fill bg-success" style="width: 94%"></div>
                                </div>
                                <span class="text-sm font-medium text-primary">94%</span>
                            </div>
                        </div>

                        <!-- Metric Item -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-secondary">{{ __('staff.performance.teamwork') }}</span>
                            <div class="flex items-center gap-2">
                                <div class="metric-bar">
                                    <div class="metric-bar-fill bg-warning" style="width: 76%"></div>
                                </div>
                                <span class="text-sm font-medium text-primary">76%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection