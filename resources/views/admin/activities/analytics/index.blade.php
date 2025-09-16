@extends('layouts.admin')

@section('title', __('activities.analytics.title'))

@section('content')
<div class="activities-analytics-page" x-data="analyticsData()">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-header-left">
                <h1 class="page-title">{{ __('activities.analytics.title') }}</h1>
                <p class="page-description">{{ __('activities.analytics.subtitle') }}</p>
            </div>
            <div class="page-header-right">
                <div class="date-range-picker">
                    <input type="date" x-model="dateFrom" class="date-input" :max="dateTo">
                    <span class="date-separator">{{ __('activities.analytics.to') }}</span>
                    <input type="date" x-model="dateTo" class="date-input" :min="dateFrom">
                    <button class="btn btn-secondary" @click="applyDateFilter()">
                        {{ __('activities.analytics.apply_filter') }}
                    </button>
                </div>
                <button class="btn btn-primary" @click="showExportModal = true">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ __('activities.analytics.export_report') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Overview Stats -->
    <div class="overview-section">
        <div class="stats-grid">
            <div class="stat-card stat-card-primary">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ number_format($overviewStats['total_activities_logged']) }}</div>
                    <div class="stat-label">{{ __('activities.analytics.total_activities') }}</div>
                    <div class="stat-change positive">+{{ $overviewStats['activities_completed_today'] }} {{ __('activities.analytics.today') }}</div>
                </div>
            </div>
            
            <div class="stat-card stat-card-success">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ floor($overviewStats['total_time_tracked'] / 60) }}h {{ $overviewStats['total_time_tracked'] % 60 }}m</div>
                    <div class="stat-label">{{ __('activities.analytics.total_duration') }}</div>
                    <div class="stat-change positive">+{{ floor($overviewStats['time_tracked_today'] / 60) }}h {{ $overviewStats['time_tracked_today'] % 60 }}m {{ __('activities.analytics.today') }}</div>
                </div>
            </div>
            
            <div class="stat-card stat-card-info">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $overviewStats['average_efficiency'] }}%</div>
                    <div class="stat-label">{{ __('activities.analytics.average_duration') }}</div>
                    <div class="stat-change positive">{{ $overviewStats['efficiency_today'] }}% {{ __('activities.analytics.today') }}</div>
                </div>
            </div>
            
            <div class="stat-card stat-card-warning">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $overviewStats['total_staff_active'] }}</div>
                    <div class="stat-label">{{ __('activities.analytics.active_staff') }}</div>
                    <div class="stat-change neutral">{{ __('activities.analytics.across_departments', ['count' => 4]) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-section">
        <div class="charts-grid">
            <!-- Activity Trends Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">{{ __('activities.analytics.activity_trends') }}</h3>
                    <div class="chart-controls">
                        <select x-model="trendsTimeframe" @change="updateTrendsChart()" class="chart-select">
                            <option value="7">{{ __('activities.analytics.last_7_days') }}</option>
                            <option value="30">{{ __('activities.analytics.last_30_days') }}</option>
                            <option value="90">{{ __('activities.analytics.last_90_days') }}</option>
                        </select>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="trendsChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Department Comparison Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">{{ __('activities.analytics.department_comparison') }}</h3>
                </div>
                <div class="chart-container">
                    <canvas id="departmentChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Peak Hours Chart -->
        <div class="chart-card chart-card-full">
            <div class="chart-header">
                <h3 class="chart-title">{{ __('activities.analytics.peak_hours') }}</h3>
                <p class="chart-description">{{ __('activities.analytics.peak_hours_description') }}</p>
            </div>
            <div class="chart-container">
                <canvas id="peakHoursChart" width="800" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Data Tables Section -->
    <div class="tables-section">
        <div class="tables-grid">
            <!-- Staff Performance Table -->
            <div class="table-card">
                <div class="table-header">
                    <h3 class="table-title">{{ __('activities.analytics.staff_performance') }}</h3>
                    <div class="table-controls">
                        <select x-model="staffSortBy" @change="sortStaffData()" class="table-select">
                            <option value="efficiency">{{ __('activities.analytics.sort_by_efficiency') }}</option>
                            <option value="activities">{{ __('activities.analytics.sort_by_activities') }}</option>
                            <option value="time">{{ __('activities.analytics.sort_by_time') }}</option>
                        </select>
                    </div>
                </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>{{ __('activities.analytics.staff_member') }}</th>
                                <th>{{ __('activities.analytics.activities_logged') }}</th>
                                <th>{{ __('activities.analytics.total_time') }}</th>
                                <th>{{ __('activities.analytics.efficiency') }}</th>
                                <th>{{ __('activities.analytics.trend') }}</th>
                                <th>{{ __('activities.analytics.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($staffPerformance as $staff)
                            <tr>
                                <td>
                                    <div class="staff-info">
                                        <div class="staff-name">{{ $staff['name'] }}</div>
                                        <div class="staff-role">{{ $staff['role'] }} • {{ $staff['department'] }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="metric-value">{{ $staff['activities_logged'] }}</div>
                                    <div class="metric-label">{{ __('activities.analytics.activities') }}</div>
                                </td>
                                <td>
                                    <div class="metric-value">{{ floor($staff['total_time'] / 60) }}h {{ $staff['total_time'] % 60 }}m</div>
                                    <div class="metric-label">{{ $staff['avg_time_per_activity'] }}min {{ __('activities.analytics.avg') }}</div>
                                </td>
                                <td>
                                    <div class="efficiency-score efficiency-{{ $staff['efficiency_score'] >= 90 ? 'excellent' : ($staff['efficiency_score'] >= 80 ? 'good' : 'average') }}">
                                        {{ $staff['efficiency_score'] }}%
                                    </div>
                                </td>
                                <td>
                                    <div class="trend-indicator trend-{{ $staff['trend'] }}">
                                        @if($staff['trend'] === 'up')
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                            </svg>
                                        @elseif($staff['trend'] === 'down')
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                                            </svg>
                                        @else
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                            </svg>
                                        @endif
                                        {{ __('activities.analytics.trend_' . $staff['trend']) }}
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.activities.analytics.staff', $staff['id']) }}" class="btn btn-sm btn-outline">
                                        {{ __('activities.analytics.view_details') }}
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Activity Breakdown Table -->
            <div class="table-card">
                <div class="table-header">
                    <h3 class="table-title">{{ __('activities.analytics.activity_breakdown') }}</h3>
                </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>{{ __('activities.analytics.activity') }}</th>
                                <th>{{ __('activities.analytics.logs') }}</th>
                                <th>{{ __('activities.analytics.avg_time') }}</th>
                                <th>{{ __('activities.analytics.efficiency') }}</th>
                                <th>{{ __('activities.analytics.completion_rate') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activityBreakdown as $activity)
                            <tr>
                                <td>
                                    <div class="activity-info">
                                        <div class="activity-name">{{ $activity['name'] }}</div>
                                        <div class="activity-category">{{ $activity['category'] }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="metric-value">{{ $activity['total_logs'] }}</div>
                                </td>
                                <td>
                                    <div class="metric-value">{{ $activity['avg_duration'] }}min</div>
                                    <div class="metric-label">{{ __('activities.analytics.est') }} {{ $activity['estimated_duration'] }}min</div>
                                </td>
                                <td>
                                    <div class="efficiency-score efficiency-{{ $activity['efficiency'] >= 100 ? 'excellent' : ($activity['efficiency'] >= 90 ? 'good' : 'average') }}">
                                        {{ $activity['efficiency'] }}%
                                    </div>
                                </td>
                                <td>
                                    <div class="completion-rate">{{ $activity['completion_rate'] }}%</div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
                <h3 class="modal-title">{{ __('activities.analytics.export_report') }}</h3>
                <button class="modal-close" @click="closeExportModal()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form @submit.prevent="exportData()" class="modal-form">
                <div class="form-group">
                    <label for="export_type" class="form-label">{{ __('activities.analytics.data_type') }}</label>
                    <select id="export_type" x-model="exportForm.type" class="form-select" required>
                        <option value="overview">{{ __('activities.analytics.overview_data') }}</option>
                        <option value="staff">{{ __('activities.analytics.staff_performance_data') }}</option>
                        <option value="activities">{{ __('activities.analytics.activity_data') }}</option>
                        <option value="departments">{{ __('activities.analytics.department_data') }}</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="export_format" class="form-label">{{ __('activities.analytics.format') }}</label>
                    <select id="export_format" x-model="exportForm.format" class="form-select" required>
                        <option value="csv">CSV</option>
                        <option value="excel">Excel</option>
                        <option value="pdf">PDF</option>
                    </select>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="export_date_from" class="form-label">{{ __('activities.analytics.from_date') }}</label>
                        <input type="date" id="export_date_from" x-model="exportForm.dateFrom" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="export_date_to" class="form-label">{{ __('activities.analytics.to_date') }}</label>
                        <input type="date" id="export_date_to" x-model="exportForm.dateTo" class="form-input">
                    </div>
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" @click="closeExportModal()">
                        {{ __('activities.common.cancel') }}
                    </button>
                    <button type="submit" 
                            class="btn btn-primary"
                            :disabled="isExporting"
                            :class="{ 'loading': isExporting }">
                        <span x-show="!isExporting">{{ __('activities.analytics.export') }}</span>
                        <span x-show="isExporting">{{ __('activities.analytics.exporting') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
@vite('resources/css/admin/activities/analytics.css')
@endpush

@push('scripts')
@vite('resources/js/admin/activities/analytics.js')
<script>
// Pass data to JavaScript
window.analyticsChartData = {
    trends: @json($trendsData),
    departments: @json($departmentComparison),
    peakHours: @json($peakHours),
    timeAnalysis: @json($timeAnalysis)
};

function analyticsData() {
    return {
        dateFrom: '{{ now()->subDays(30)->format("Y-m-d") }}',
        dateTo: '{{ now()->format("Y-m-d") }}',
        trendsTimeframe: '30',
        staffSortBy: 'efficiency',
        showExportModal: false,
        isExporting: false,
        
        exportForm: {
            type: 'overview',
            format: 'csv',
            dateFrom: '',
            dateTo: ''
        },
        
        applyDateFilter() {
            this.showNotification('Applying date filter...', 'info');
            // In real implementation, this would reload data with new date range
            setTimeout(() => {
                this.showNotification('Data updated successfully!', 'success');
            }, 1000);
        },
        
        updateTrendsChart() {
            // In real implementation, this would update the chart data
            console.log('Updating trends chart for:', this.trendsTimeframe, 'days');
        },
        
        sortStaffData() {
            // In real implementation, this would sort the staff table
            console.log('Sorting staff data by:', this.staffSortBy);
        },
        
        closeExportModal() {
            this.showExportModal = false;
            this.exportForm = {
                type: 'overview',
                format: 'csv',
                dateFrom: '',
                dateTo: ''
            };
        },
        
        exportData() {
            this.isExporting = true;
            
            // Simulate export process
            setTimeout(() => {
                this.showNotification('Export completed successfully!', 'success');
                this.closeExportModal();
                this.isExporting = false;
            }, 2000);
        },
        
        showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.innerHTML = `
                <div class="notification-content">
                    <span class="notification-message">${message}</span>
                    <button class="notification-close" onclick="this.parentElement.parentElement.remove()">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            `;
            
            // Add to page
            document.body.appendChild(notification);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }
    };
}
</script>
@endpush
