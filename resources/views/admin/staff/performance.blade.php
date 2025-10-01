@extends('layouts.admin')

@section('title', __('staff.performance.title') . ' - ' . config('app.name'))
@section('page_title', __('staff.performance.title'))

@push('styles')
@vite(['resources/css/admin/staff-performance.css'])
@endpush

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl">{{ __('staff.performance.title') }}</h1>
            <p class="text-sm">{{ __('staff.performance.subtitle') }}</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <!-- Filters -->
            <form method="GET" class="flex gap-2">
                <select name="period" onchange="this.form.submit()" class="form-select">
                    <option value="monthly" @selected($period === 'monthly')>{{ __('staff.performance.filter_monthly') }}</option>
                    <option value="quarterly" @selected($period === 'quarterly')>{{ __('staff.performance.filter_quarterly') }}</option>
                    <option value="yearly" @selected($period === 'yearly')>{{ __('staff.performance.filter_yearly') }}</option>
                </select>
                <select name="staff_type_id" onchange="this.form.submit()" class="form-select">
                    <option value="">{{ __('staff.all_types') }}</option>
                    @foreach($staffTypes as $type)
                        <option value="{{ $type->id }}" @selected(request('staff_type_id') === $type->id)>{{ $type->display_name }}</option>
                    @endforeach
                </select>
            </form>
            <!-- Export Button -->
            <button onclick="alert('{{ __('common.coming_soon') }}')" 
                    class="btn btn-primary">
                <i class="fas fa-download"></i>
                {{ __('staff.performance.export_report') }}
            </button>
        </div>
    </div>

    <!-- Overview Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Overall Score -->
        <div class="card bg-gradient-to-r from-blue-500 to-blue-600 text-white">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">{{ __('staff.performance.overall_score') }}</p>
                        <p class="text-3xl font-bold">{{ $overviewStats['overall_score'] }}%</p>
                    </div>
                    <div class="p-3 bg-white bg-opacity-20 rounded-full">
                        <i class="fas fa-chart-line text-xl"></i>
                    </div>
                </div>
                <div class="flex items-center mt-4 text-sm">
                    <i class="fas fa-{{ $overviewStats['improvement_trend'] >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                    <span>{{ $overviewStats['improvement_trend'] > 0 ? '+' : '' }}{{ $overviewStats['improvement_trend'] }}% {{ $overviewStats['period_label'] }}</span>
                </div>
            </div>
        </div>

        <!-- Top Performers -->
        <div class="card bg-gradient-to-r from-green-500 to-green-600 text-white">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">{{ __('staff.performance.top_performers') }}</p>
                        <p class="text-3xl font-bold">{{ $overviewStats['top_performers'] }}</p>
                    </div>
                    <div class="p-3 bg-white bg-opacity-20 rounded-full">
                        <i class="fas fa-star text-xl"></i>
                    </div>
                </div>
                <p class="text-green-100 text-sm mt-4">{{ __('staff.performance.above_target') }}</p>
            </div>
        </div>

        <!-- Needs Improvement -->
        <div class="card bg-gradient-to-r from-orange-500 to-orange-600 text-white">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium">{{ __('staff.performance.needs_improvement') }}</p>
                        <p class="text-3xl font-bold">{{ $overviewStats['needs_improvement'] }}</p>
                    </div>
                    <div class="p-3 bg-white bg-opacity-20 rounded-full">
                        <i class="fas fa-exclamation-triangle text-xl"></i>
                    </div>
                </div>
                <p class="text-orange-100 text-sm mt-4">{{ __('staff.performance.below_target') }}</p>
            </div>
        </div>

        <!-- Reviews Due -->
        <div class="card bg-gradient-to-r from-purple-500 to-purple-600 text-white">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">{{ __('staff.performance.reviews_due') }}</p>
                        <p class="text-3xl font-bold">{{ $overviewStats['reviews_due'] }}</p>
                    </div>
                    <div class="p-3 bg-white bg-opacity-20 rounded-full">
                        <i class="fas fa-calendar-check text-xl"></i>
                    </div>
                </div>
                <p class="text-purple-100 text-sm mt-4">{{ __('staff.performance.this_week') }}</p>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- Left Column - Chart and Top Performers -->
        <div class="xl:col-span-2 space-y-6">
            <!-- Performance Trends Chart -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('staff.performance.trends_title') }}</h3>
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $overviewStats['period_label'] }}</span>
                </div>
                <div class="card-body">
                    @if(count($performanceTrends) > 0)
                        <div class="h-80">
                            <canvas id="performanceChart" class="w-full h-full"></canvas>
                        </div>
                        <script>
                            window.performanceTrendsData = @json($performanceTrends);
                        </script>
                    @else
                        <div class="flex flex-col items-center justify-center h-80 text-gray-500 dark:text-gray-400">
                            <i class="fas fa-chart-line text-4xl mb-4"></i>
                            <p>{{ __('staff.performance.no_trends_data') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Top Performers List -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('staff.performance.top_performers_list') }}</h3>
                    <a href="{{ route('admin.staff.directory.index') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">
                        {{ __('common.view_all') }}
                    </a>
                </div>
                <div class="card-body">
                    @if(count($topPerformers) > 0)
                        <div class="space-y-4">
                            @foreach($topPerformers as $index => $performer)
                                <div class="flex items-center space-x-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                    <!-- Rank Badge -->
                                    <div class="flex-shrink-0">
                                        @if($index === 0)
                                            <div class="w-10 h-10 bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-full flex items-center justify-center text-white font-bold">
                                                1
                                            </div>
                                        @elseif($index === 1)
                                            <div class="w-10 h-10 bg-gradient-to-r from-gray-400 to-gray-500 rounded-full flex items-center justify-center text-white font-bold">
                                                2
                                            </div>
                                        @elseif($index === 2)
                                            <div class="w-10 h-10 bg-gradient-to-r from-orange-400 to-orange-500 rounded-full flex items-center justify-center text-white font-bold">
                                                3
                                            </div>
                                        @else
                                            <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-600 dark:text-gray-400 font-bold">
                                                {{ $index + 1 }}
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Staff Info -->
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $performer['name'] }}</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $performer['position'] }}</p>
                                    </div>
                                    
                                    <!-- Score -->
                                    <div class="flex-shrink-0 text-right">
                                        <div class="text-lg font-bold {{ $performer['score'] >= 90 ? 'text-green-600' : ($performer['score'] >= 80 ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ $performer['score'] }}%
                                        </div>
                                        <div class="w-20 bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-1">
                                            <div class="h-2 rounded-full {{ $performer['score'] >= 90 ? 'bg-green-500' : ($performer['score'] >= 80 ? 'bg-yellow-500' : 'bg-red-500') }}" 
                                                 style="width: {{ $performer['score'] }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-12 text-gray-500 dark:text-gray-400">
                            <i class="fas fa-users text-4xl mb-4"></i>
                            <p>{{ __('staff.performance.no_top_performers') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Actions, Reviews, Metrics -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('staff.performance.quick_actions') }}</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        <button onclick="alert('{{ __('common.coming_soon') }}')" 
                                class="w-full btn btn-primary justify-start">
                            <i class="fas fa-plus"></i>
                            {{ __('staff.performance.schedule_review') }}
                        </button>
                        <button onclick="alert('{{ __('common.coming_soon') }}')" 
                                class="w-full btn btn-secondary justify-start">
                            <i class="fas fa-chart-bar"></i>
                            {{ __('staff.performance.view_analytics') }}
                        </button>
                        <button onclick="alert('{{ __('common.coming_soon') }}')" 
                                class="w-full btn btn-secondary justify-start">
                            <i class="fas fa-users"></i>
                            {{ __('staff.performance.team_comparison') }}
                        </button>
                        <button onclick="alert('{{ __('common.coming_soon') }}')" 
                                class="w-full btn btn-secondary justify-start">
                            <i class="fas fa-cog"></i>
                            {{ __('staff.performance.performance_settings') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Upcoming Reviews -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('staff.performance.upcoming_reviews') }}</h3>
                </div>
                <div class="card-body">
                    @if(count($upcomingReviews) > 0)
                        <div class="space-y-3">
                            @foreach($upcomingReviews as $review)
                                <div class="flex items-start space-x-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs
                                            {{ $review['urgency'] === 'urgent' ? 'bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-300' : 
                                               ($review['urgency'] === 'warning' ? 'bg-yellow-100 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-300' : 
                                                'bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300') }}">
                                            <i class="fas fa-calendar"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $review['staff_name'] }}</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $review['review_type'] }}</p>
                                        <p class="text-xs {{ $review['urgency'] === 'urgent' ? 'text-red-600 dark:text-red-400' : 
                                                              ($review['urgency'] === 'warning' ? 'text-yellow-600 dark:text-yellow-400' : 
                                                               'text-blue-600 dark:text-blue-400') }}">
                                            {{ $review['due_date_formatted'] }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-8 text-gray-500 dark:text-gray-400">
                            <i class="fas fa-calendar-check text-3xl mb-3"></i>
                            <p class="text-sm">{{ __('staff.performance.no_upcoming_reviews') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Key Metrics -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('staff.performance.key_metrics') }}</h3>
                </div>
                <div class="card-body">
                    @if(count($keyMetrics) > 0)
                        <div class="space-y-4">
                            @foreach($keyMetrics as $metric)
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $metric['name'] }}</span>
                                        <span class="text-sm font-medium {{ $metric['color'] === 'success' ? 'text-green-600' : 
                                                                            ($metric['color'] === 'warning' ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ $metric['value'] }}{{ $metric['unit'] }}
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="h-2 rounded-full {{ $metric['color'] === 'success' ? 'bg-green-500' : 
                                                                        ($metric['color'] === 'warning' ? 'bg-yellow-500' : 'bg-red-500') }}" 
                                             style="width: {{ min(100, ($metric['value'] / 100) * 100) }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-8 text-gray-500 dark:text-gray-400">
                            <i class="fas fa-chart-pie text-3xl mb-3"></i>
                            <p class="text-sm">{{ __('staff.performance.no_metrics_data') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@vite(['resources/js/admin/staff-performance.js'])
@endpush
@endsection