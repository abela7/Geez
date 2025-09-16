@extends('layouts.admin')

@section('title', __('todos.progress.title'))

@section('content')
<div class="progress-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-header-left">
                <h1 class="page-title">{{ __('todos.progress.title') }}</h1>
                <p class="page-description">{{ __('todos.progress.subtitle') }}</p>
            </div>
            <div class="page-header-right">
                <button class="btn btn-secondary" @click="refreshData()">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    {{ __('todos.common.refresh') }}
                </button>
                <button class="btn btn-outline" @click="openReportModal()">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ __('todos.progress.generate_report') }}
                </button>
                <button class="btn btn-primary" @click="openExportModal()">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ __('todos.progress.export_data') }}
                </button>
            </div>
        </div>
    </div>

    <div class="progress-dashboard" x-data="progressData()">
        <!-- Overview Statistics -->
        <div class="stats-grid">
            <div class="stat-card stat-primary">
                <div class="stat-header">
                    <div class="stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="stat-trend trend-{{ $progressData['efficiency_trend'] }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($progressData['efficiency_trend'] === 'up')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            @elseif($progressData['efficiency_trend'] === 'down')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                            @endif
                        </svg>
                        <span>{{ $progressData['efficiency_change'] }}%</span>
                    </div>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ number_format($progressData['completion_rate'], 1) }}%</div>
                    <div class="stat-label">{{ __('todos.progress.overall_completion_rate') }}</div>
                    <div class="stat-description">{{ $progressData['completed_todos'] }} {{ __('todos.progress.of') }} {{ $progressData['total_todos'] }} {{ __('todos.progress.todos_completed') }}</div>
                </div>
            </div>

            <div class="stat-card stat-success">
                <div class="stat-header">
                    <div class="stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $progressData['average_completion_time'] }}h</div>
                    <div class="stat-label">{{ __('todos.progress.average_completion_time') }}</div>
                    <div class="stat-description">{{ number_format($progressData['on_time_completion_rate'], 1) }}% {{ __('todos.progress.completed_on_time') }}</div>
                </div>
            </div>

            <div class="stat-card stat-warning">
                <div class="stat-header">
                    <div class="stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $progressData['overdue_todos'] }}</div>
                    <div class="stat-label">{{ __('todos.progress.overdue_todos') }}</div>
                    <div class="stat-description">{{ $progressData['pending_todos'] }} {{ __('todos.progress.pending_todos') }}</div>
                </div>
            </div>

            <div class="stat-card stat-info">
                <div class="stat-header">
                    <div class="stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $progressData['productivity_score'] }}/10</div>
                    <div class="stat-label">{{ __('todos.progress.productivity_score') }}</div>
                    <div class="stat-description">{{ __('todos.progress.quality_score') }}: {{ $progressData['quality_score'] }}/10</div>
                </div>
            </div>
        </div>

        <!-- Weekly Progress -->
        <div class="progress-section">
            <div class="section-header">
                <h3 class="section-title">{{ __('todos.progress.weekly_progress') }}</h3>
                <div class="section-actions">
                    <select class="filter-select" x-model="selectedPeriod" @change="updateCharts()">
                        <option value="week">{{ __('todos.progress.this_week') }}</option>
                        <option value="month">{{ __('todos.progress.this_month') }}</option>
                        <option value="quarter">{{ __('todos.progress.this_quarter') }}</option>
                    </select>
                </div>
            </div>
            
            <div class="weekly-progress-card">
                <div class="progress-info">
                    <div class="progress-stats">
                        <div class="progress-stat">
                            <span class="progress-value">{{ $progressData['this_week_completed'] }}</span>
                            <span class="progress-label">{{ __('todos.progress.completed') }}</span>
                        </div>
                        <div class="progress-stat">
                            <span class="progress-value">{{ $progressData['this_week_target'] }}</span>
                            <span class="progress-label">{{ __('todos.progress.target') }}</span>
                        </div>
                        <div class="progress-stat">
                            <span class="progress-value">{{ number_format($progressData['weekly_progress'], 1) }}%</span>
                            <span class="progress-label">{{ __('todos.progress.progress') }}</span>
                        </div>
                    </div>
                    <div class="progress-bar-container">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $progressData['weekly_progress'] }}%"></div>
                        </div>
                        <div class="progress-text">{{ $progressData['this_week_completed'] }} / {{ $progressData['this_week_target'] }} {{ __('todos.progress.todos') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-grid">
            <!-- Completion Trends Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h4 class="chart-title">{{ __('todos.progress.completion_trends') }}</h4>
                    <div class="chart-legend">
                        <div class="legend-item">
                            <div class="legend-color" style="background: #10B981;"></div>
                            <span>{{ __('todos.progress.completed') }}</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background: #3B82F6;"></div>
                            <span>{{ __('todos.progress.created') }}</span>
                        </div>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="trendsChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Category Breakdown Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h4 class="chart-title">{{ __('todos.progress.category_breakdown') }}</h4>
                </div>
                <div class="chart-container">
                    <canvas id="categoryChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Staff Performance Table -->
        <div class="performance-section">
            <div class="section-header">
                <h3 class="section-title">{{ __('todos.progress.staff_performance') }}</h3>
                <div class="section-actions">
                    <button class="btn btn-sm btn-outline" @click="sortStaffBy('completion_rate')">
                        {{ __('todos.progress.sort_by_completion') }}
                    </button>
                    <button class="btn btn-sm btn-outline" @click="sortStaffBy('quality_score')">
                        {{ __('todos.progress.sort_by_quality') }}
                    </button>
                </div>
            </div>

            <div class="performance-table-container">
                <table class="performance-table">
                    <thead>
                        <tr>
                            <th>{{ __('todos.progress.staff_member') }}</th>
                            <th>{{ __('todos.progress.assigned') }}</th>
                            <th>{{ __('todos.progress.completed') }}</th>
                            <th>{{ __('todos.progress.completion_rate') }}</th>
                            <th>{{ __('todos.progress.avg_time') }}</th>
                            <th>{{ __('todos.progress.on_time_rate') }}</th>
                            <th>{{ __('todos.progress.quality_score') }}</th>
                            <th>{{ __('todos.progress.trend') }}</th>
                            <th>{{ __('todos.progress.last_activity') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($staffPerformance as $staff)
                        <tr class="performance-row">
                            <td class="staff-info">
                                <div class="staff-name">{{ $staff['name'] }}</div>
                                <div class="staff-role">{{ $staff['role'] }}</div>
                            </td>
                            <td class="stat-number">{{ $staff['total_assigned'] }}</td>
                            <td class="stat-number">{{ $staff['completed'] }}</td>
                            <td class="completion-rate">
                                <div class="rate-bar">
                                    <div class="rate-fill" style="width: {{ $staff['completion_rate'] }}%"></div>
                                </div>
                                <span class="rate-text">{{ number_format($staff['completion_rate'], 1) }}%</span>
                            </td>
                            <td class="stat-time">{{ $staff['average_time'] }}h</td>
                            <td class="stat-percentage">{{ number_format($staff['on_time_rate'], 1) }}%</td>
                            <td class="quality-score">
                                <div class="score-badge score-{{ $staff['quality_score'] >= 9 ? 'excellent' : ($staff['quality_score'] >= 8 ? 'good' : 'average') }}">
                                    {{ $staff['quality_score'] }}/10
                                </div>
                            </td>
                            <td class="trend-indicator">
                                <div class="trend trend-{{ $staff['trend'] }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($staff['trend'] === 'up')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                        @elseif($staff['trend'] === 'down')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                        @endif
                                    </svg>
                                </div>
                            </td>
                            <td class="last-activity">{{ $staff['recent_activity'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Activity & Upcoming Deadlines -->
        <div class="activity-grid">
            <!-- Recent Activity -->
            <div class="activity-card">
                <div class="activity-header">
                    <h4 class="activity-title">{{ __('todos.progress.recent_activity') }}</h4>
                    <button class="btn btn-sm btn-outline" @click="refreshActivity()">
                        {{ __('todos.common.refresh') }}
                    </button>
                </div>
                <div class="activity-list">
                    @foreach($recentActivity as $activity)
                    <div class="activity-item activity-{{ $activity['type'] }}">
                        <div class="activity-icon">
                            @if($activity['type'] === 'completed')
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @elseif($activity['type'] === 'started')
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m-9 4h10a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            @elseif($activity['type'] === 'overdue')
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                            @else
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            @endif
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">{{ $activity['todo_title'] }}</div>
                            <div class="activity-meta">
                                <span class="activity-staff">{{ $activity['staff_name'] }}</span>
                                <span class="activity-category">{{ $activity['category'] }}</span>
                                <span class="activity-time">
                                    @if($activity['type'] === 'completed')
                                        {{ \Carbon\Carbon::parse($activity['completed_at'])->diffForHumans() }}
                                    @elseif($activity['type'] === 'started')
                                        {{ \Carbon\Carbon::parse($activity['started_at'])->diffForHumans() }}
                                    @elseif($activity['type'] === 'overdue')
                                        {{ __('todos.progress.overdue_by') }} {{ $activity['overdue_by'] }}min
                                    @else
                                        {{ \Carbon\Carbon::parse($activity['assigned_at'])->diffForHumans() }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Upcoming Deadlines -->
            <div class="deadlines-card">
                <div class="deadlines-header">
                    <h4 class="deadlines-title">{{ __('todos.progress.upcoming_deadlines') }}</h4>
                    <span class="deadlines-count">{{ count($upcomingDeadlines) }} {{ __('todos.progress.upcoming') }}</span>
                </div>
                <div class="deadlines-list">
                    @foreach($upcomingDeadlines as $deadline)
                    <div class="deadline-item priority-{{ $deadline['priority'] }}">
                        <div class="deadline-priority">
                            <div class="priority-badge priority-{{ $deadline['priority'] }}">
                                {{ __('todos.progress.' . $deadline['priority'] . '_priority') }}
                            </div>
                        </div>
                        <div class="deadline-content">
                            <div class="deadline-title">{{ $deadline['title'] }}</div>
                            <div class="deadline-meta">
                                <span class="deadline-staff">{{ $deadline['staff_name'] }}</span>
                                <span class="deadline-category">{{ $deadline['category'] }}</span>
                                <span class="deadline-time">{{ \Carbon\Carbon::parse($deadline['due_at'])->format('M d, H:i') }}</span>
                                <span class="deadline-duration">~{{ $deadline['estimated_duration'] }}min</span>
                            </div>
                        </div>
                        <div class="deadline-status">
                            <div class="status-badge status-{{ $deadline['status'] }}">
                                {{ __('todos.progress.' . $deadline['status']) }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Report Generation Modal -->
    <div x-show="showReportModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="modal-overlay" 
         @click="closeReportModal()"
         style="display: none;">
        <div class="modal-content" @click.stop>
            <div class="modal-header">
                <h3 class="modal-title">{{ __('todos.progress.generate_report') }}</h3>
                <button class="modal-close" @click="closeReportModal()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="generateReport()">
                    <div class="form-group">
                        <label class="form-label">{{ __('todos.progress.report_type') }}</label>
                        <select class="form-select" x-model="reportForm.report_type">
                            <option value="daily">{{ __('todos.progress.daily_report') }}</option>
                            <option value="weekly">{{ __('todos.progress.weekly_report') }}</option>
                            <option value="monthly">{{ __('todos.progress.monthly_report') }}</option>
                            <option value="custom">{{ __('todos.progress.custom_report') }}</option>
                        </select>
                    </div>
                    
                    <div x-show="reportForm.report_type === 'custom'" class="form-row">
                        <div class="form-group">
                            <label class="form-label">{{ __('todos.progress.start_date') }}</label>
                            <input type="date" class="form-input" x-model="reportForm.start_date">
                        </div>
                        <div class="form-group">
                            <label class="form-label">{{ __('todos.progress.end_date') }}</label>
                            <input type="date" class="form-input" x-model="reportForm.end_date">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">{{ __('todos.progress.format') }}</label>
                        <select class="form-select" x-model="reportForm.format">
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                    
                    <div class="modal-actions">
                        <button type="button" class="btn btn-secondary" @click="closeReportModal()">
                            {{ __('todos.common.cancel') }}
                        </button>
                        <button type="submit" class="btn btn-primary" :disabled="isGeneratingReport">
                            <span x-show="!isGeneratingReport">{{ __('todos.progress.generate') }}</span>
                            <span x-show="isGeneratingReport">{{ __('todos.progress.generating') }}...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Export Modal -->
    <div x-show="showExportModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="modal-overlay" 
         @click="closeExportModal()"
         style="display: none;">
        <div class="modal-content" @click.stop>
            <div class="modal-header">
                <h3 class="modal-title">{{ __('todos.progress.export_data') }}</h3>
                <button class="modal-close" @click="closeExportModal()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="exportData()">
                    <div class="form-group">
                        <label class="form-label">{{ __('todos.progress.data_type') }}</label>
                        <select class="form-select" x-model="exportForm.data_type">
                            <option value="overview">{{ __('todos.progress.overview_data') }}</option>
                            <option value="staff">{{ __('todos.progress.staff_performance_data') }}</option>
                            <option value="categories">{{ __('todos.progress.category_data') }}</option>
                            <option value="trends">{{ __('todos.progress.trends_data') }}</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">{{ __('todos.progress.format') }}</label>
                        <select class="form-select" x-model="exportForm.format">
                            <option value="excel">Excel</option>
                            <option value="csv">CSV</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                    
                    <div class="modal-actions">
                        <button type="button" class="btn btn-secondary" @click="closeExportModal()">
                            {{ __('todos.common.cancel') }}
                        </button>
                        <button type="submit" class="btn btn-primary" :disabled="isExporting">
                            <span x-show="!isExporting">{{ __('todos.progress.export') }}</span>
                            <span x-show="isExporting">{{ __('todos.progress.exporting') }}...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
@vite('resources/css/admin/todos/progress.css')
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@vite('resources/js/admin/todos/progress.js')
<script>
    // Pass data to JavaScript
    window.progressChartData = {
        trends: @json($completionTrends),
        categories: @json($categoryBreakdown),
        performance: @json($performanceMetrics)
    };
</script>
@endpush
