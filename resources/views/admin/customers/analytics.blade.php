@extends('layouts.admin')

@section('title', __('customers.analytics.title') . ' - ' . config('app.name'))
@section('page_title', __('customers.analytics.title'))

@push('styles')
    @vite('resources/css/admin/customer-analytics.css')
@endpush

@push('scripts')
    @vite('resources/js/admin/customer-analytics.js')
@endpush

@section('content')
<div class="analytics-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('customers.analytics.title') }}</h1>
                <p class="page-subtitle">{{ __('customers.analytics.subtitle') }}</p>
            </div>
            <div class="page-actions">
                <button type="button" class="btn btn-secondary export-analytics-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ __('customers.analytics.export_report') }}
                </button>
                <button type="button" class="btn btn-primary record-service-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('customers.analytics.record_service') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Quick Stats Overview -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-card today">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="customers-today">0</div>
                    <div class="stat-label">{{ __('customers.analytics.customers_today') }}</div>
                    <div class="stat-change" id="today-change">+0%</div>
                </div>
            </div>
            
            <div class="stat-card week">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="customers-week">0</div>
                    <div class="stat-label">{{ __('customers.analytics.customers_this_week') }}</div>
                    <div class="stat-change" id="week-change">+0%</div>
                </div>
            </div>
            
            <div class="stat-card month">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="customers-month">0</div>
                    <div class="stat-label">{{ __('customers.analytics.customers_this_month') }}</div>
                    <div class="stat-change" id="month-change">+0%</div>
                </div>
            </div>
            
            <div class="stat-card average">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="average-daily">0</div>
                    <div class="stat-label">{{ __('customers.analytics.average_daily') }}</div>
                    <div class="stat-change" id="average-change">+0%</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Tabs -->
    <div class="content-section">
        <div class="analytics-tabs" x-data="{ activeTab: 'dashboard' }">
            <div class="tab-nav">
                <button type="button" 
                        class="tab-btn" 
                        :class="{ 'active': activeTab === 'dashboard' }"
                        @click="activeTab = 'dashboard'">
                    <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    {{ __('customers.analytics.dashboard') }}
                </button>
                <button type="button" 
                        class="tab-btn" 
                        :class="{ 'active': activeTab === 'tracking' }"
                        @click="activeTab = 'tracking'">
                    <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    {{ __('customers.analytics.service_tracking') }}
                </button>
                <button type="button" 
                        class="tab-btn" 
                        :class="{ 'active': activeTab === 'reports' }"
                        @click="activeTab = 'reports'">
                    <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ __('customers.analytics.reports') }}
                </button>
                <button type="button" 
                        class="tab-btn" 
                        :class="{ 'active': activeTab === 'insights' }"
                        @click="activeTab = 'insights'">
                    <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                    {{ __('customers.analytics.insights') }}
                </button>
            </div>

            <!-- Dashboard Tab -->
            <div class="tab-panel" x-show="activeTab === 'dashboard'" x-transition>
                <div class="dashboard-section">
                    <!-- Time Period Selector -->
                    <div class="dashboard-controls">
                        <div class="period-selector">
                            <label for="period-select" class="period-label">{{ __('customers.analytics.time_period') }}:</label>
                            <select id="period-select" class="period-select">
                                <option value="today">{{ __('customers.analytics.today') }}</option>
                                <option value="week" selected>{{ __('customers.analytics.this_week') }}</option>
                                <option value="month">{{ __('customers.analytics.this_month') }}</option>
                                <option value="quarter">{{ __('customers.analytics.this_quarter') }}</option>
                                <option value="year">{{ __('customers.analytics.this_year') }}</option>
                                <option value="custom">{{ __('customers.analytics.custom_range') }}</option>
                            </select>
                        </div>
                        <div class="date-range" id="custom-date-range" style="display: none;">
                            <input type="date" id="start-date" class="date-input">
                            <span class="date-separator">{{ __('customers.analytics.to') }}</span>
                            <input type="date" id="end-date" class="date-input">
                        </div>
                    </div>

                    <!-- Charts Section -->
                    <div class="charts-grid">
                        <div class="chart-card">
                            <div class="chart-header">
                                <h3 class="chart-title">{{ __('customers.analytics.daily_customer_flow') }}</h3>
                                <div class="chart-actions">
                                    <button type="button" class="chart-action-btn" title="{{ __('customers.analytics.refresh') }}">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="chart-container">
                                <canvas id="daily-flow-chart"></canvas>
                            </div>
                        </div>

                        <div class="chart-card">
                            <div class="chart-header">
                                <h3 class="chart-title">{{ __('customers.analytics.hourly_distribution') }}</h3>
                                <div class="chart-actions">
                                    <button type="button" class="chart-action-btn" title="{{ __('customers.analytics.refresh') }}">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="chart-container">
                                <canvas id="hourly-distribution-chart"></canvas>
                            </div>
                        </div>

                        <div class="chart-card">
                            <div class="chart-header">
                                <h3 class="chart-title">{{ __('customers.analytics.service_performance') }}</h3>
                                <div class="chart-actions">
                                    <button type="button" class="chart-action-btn" title="{{ __('customers.analytics.refresh') }}">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="chart-container">
                                <canvas id="service-performance-chart"></canvas>
                            </div>
                        </div>

                        <div class="chart-card">
                            <div class="chart-header">
                                <h3 class="chart-title">{{ __('customers.analytics.table_utilization') }}</h3>
                                <div class="chart-actions">
                                    <button type="button" class="chart-action-btn" title="{{ __('customers.analytics.refresh') }}">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="chart-container">
                                <canvas id="table-utilization-chart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Key Metrics -->
                    <div class="metrics-section">
                        <h3 class="metrics-title">{{ __('customers.analytics.key_metrics') }}</h3>
                        <div class="metrics-grid">
                            <div class="metric-card">
                                <div class="metric-label">{{ __('customers.analytics.peak_hour') }}</div>
                                <div class="metric-value" id="peak-hour">--:--</div>
                                <div class="metric-description">{{ __('customers.analytics.busiest_time') }}</div>
                            </div>
                            <div class="metric-card">
                                <div class="metric-label">{{ __('customers.analytics.avg_service_time') }}</div>
                                <div class="metric-value" id="avg-service-time">-- {{ __('customers.analytics.minutes') }}</div>
                                <div class="metric-description">{{ __('customers.analytics.per_customer') }}</div>
                            </div>
                            <div class="metric-card">
                                <div class="metric-label">{{ __('customers.analytics.table_turnover') }}</div>
                                <div class="metric-value" id="table-turnover">--x</div>
                                <div class="metric-description">{{ __('customers.analytics.per_day') }}</div>
                            </div>
                            <div class="metric-card">
                                <div class="metric-label">{{ __('customers.analytics.customer_satisfaction') }}</div>
                                <div class="metric-value" id="satisfaction-score">--%</div>
                                <div class="metric-description">{{ __('customers.analytics.based_on_service') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service Tracking Tab -->
            <div class="tab-panel" x-show="activeTab === 'tracking'" x-transition>
                <div class="tracking-section">
                    <!-- Quick Service Recording -->
                    <div class="quick-record-section">
                        <div class="quick-record-header">
                            <h3 class="section-title">{{ __('customers.analytics.quick_service_record') }}</h3>
                            <div class="current-time" id="current-time">--:--</div>
                        </div>
                        
                        <div class="quick-record-form">
                            <div class="record-input-group">
                                <label for="customer-count" class="record-label">{{ __('customers.analytics.number_of_customers') }}</label>
                                <div class="customer-counter">
                                    <button type="button" class="counter-btn decrease" id="decrease-customers">-</button>
                                    <input type="number" id="customer-count" class="counter-input" value="1" min="1" max="20">
                                    <button type="button" class="counter-btn increase" id="increase-customers">+</button>
                                </div>
                            </div>
                            
                            <div class="record-input-group">
                                <label for="table-select" class="record-label">{{ __('customers.analytics.table') }}</label>
                                <select id="table-select" class="record-select">
                                    <option value="">{{ __('customers.analytics.select_table') }}</option>
                                    <!-- Tables will be populated by JavaScript -->
                                </select>
                            </div>
                            
                            <div class="record-input-group">
                                <label for="waiter-select" class="record-label">{{ __('customers.analytics.waiter') }}</label>
                                <select id="waiter-select" class="record-select">
                                    <option value="">{{ __('customers.analytics.select_waiter') }}</option>
                                    <!-- Waiters will be populated by JavaScript -->
                                </select>
                            </div>
                            
                            <div class="record-actions">
                                <button type="button" class="btn btn-primary record-service-now-btn">
                                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    {{ __('customers.analytics.record_now') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Service Records -->
                    <div class="recent-records-section">
                        <div class="recent-records-header">
                            <h3 class="section-title">{{ __('customers.analytics.recent_service_records') }}</h3>
                            <div class="records-filters">
                                <select id="records-filter" class="filter-select">
                                    <option value="today">{{ __('customers.analytics.today') }}</option>
                                    <option value="week">{{ __('customers.analytics.this_week') }}</option>
                                    <option value="month">{{ __('customers.analytics.this_month') }}</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="records-list" id="service-records-list">
                            <!-- Service records will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reports Tab -->
            <div class="tab-panel" x-show="activeTab === 'reports'" x-transition>
                <div class="reports-section">
                    <div class="reports-header">
                        <h3 class="section-title">{{ __('customers.analytics.detailed_reports') }}</h3>
                        <div class="report-actions">
                            <button type="button" class="btn btn-secondary generate-report-btn">
                                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                {{ __('customers.analytics.generate_report') }}
                            </button>
                        </div>
                    </div>
                    
                    <div class="report-filters">
                        <div class="filter-group">
                            <label for="report-type" class="filter-label">{{ __('customers.analytics.report_type') }}</label>
                            <select id="report-type" class="filter-select">
                                <option value="daily">{{ __('customers.analytics.daily_summary') }}</option>
                                <option value="weekly">{{ __('customers.analytics.weekly_summary') }}</option>
                                <option value="monthly">{{ __('customers.analytics.monthly_summary') }}</option>
                                <option value="custom">{{ __('customers.analytics.custom_period') }}</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="report-waiter" class="filter-label">{{ __('customers.analytics.waiter') }}</label>
                            <select id="report-waiter" class="filter-select">
                                <option value="">{{ __('customers.analytics.all_waiters') }}</option>
                                <!-- Waiters will be populated by JavaScript -->
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="report-table" class="filter-label">{{ __('customers.analytics.table') }}</label>
                            <select id="report-table" class="filter-select">
                                <option value="">{{ __('customers.analytics.all_tables') }}</option>
                                <!-- Tables will be populated by JavaScript -->
                            </select>
                        </div>
                    </div>
                    
                    <div class="reports-content" id="reports-content">
                        <!-- Reports will be populated by JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Insights Tab -->
            <div class="tab-panel" x-show="activeTab === 'insights'" x-transition>
                <div class="insights-section">
                    <div class="insights-header">
                        <h3 class="section-title">{{ __('customers.analytics.business_insights') }}</h3>
                        <div class="insights-period">
                            <select id="insights-period" class="period-select">
                                <option value="week">{{ __('customers.analytics.last_7_days') }}</option>
                                <option value="month" selected>{{ __('customers.analytics.last_30_days') }}</option>
                                <option value="quarter">{{ __('customers.analytics.last_90_days') }}</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="insights-grid">
                        <div class="insight-card trend">
                            <div class="insight-header">
                                <h4 class="insight-title">{{ __('customers.analytics.customer_trends') }}</h4>
                                <div class="insight-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="insight-content" id="customer-trends">
                                <!-- Trends will be populated by JavaScript -->
                            </div>
                        </div>
                        
                        <div class="insight-card performance">
                            <div class="insight-header">
                                <h4 class="insight-title">{{ __('customers.analytics.staff_performance') }}</h4>
                                <div class="insight-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="insight-content" id="staff-performance">
                                <!-- Performance data will be populated by JavaScript -->
                            </div>
                        </div>
                        
                        <div class="insight-card recommendations">
                            <div class="insight-header">
                                <h4 class="insight-title">{{ __('customers.analytics.recommendations') }}</h4>
                                <div class="insight-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="insight-content" id="recommendations">
                                <!-- Recommendations will be populated by JavaScript -->
                            </div>
                        </div>
                        
                        <div class="insight-card forecasting">
                            <div class="insight-header">
                                <h4 class="insight-title">{{ __('customers.analytics.forecasting') }}</h4>
                                <div class="insight-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="insight-content" id="forecasting">
                                <!-- Forecasting data will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Record Service Modal -->
<div class="service-modal" id="service-modal" style="display: none;" role="dialog" aria-labelledby="service-modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="service-modal-title" class="modal-title">{{ __('customers.analytics.record_service') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('customers.analytics.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form id="service-form" class="service-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="service-customers" class="form-label required">{{ __('customers.analytics.number_of_customers') }}</label>
                        <div class="customer-counter">
                            <button type="button" class="counter-btn decrease">-</button>
                            <input type="number" id="service-customers" name="customers" class="counter-input" value="1" min="1" max="20" required>
                            <button type="button" class="counter-btn increase">+</button>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="service-table" class="form-label required">{{ __('customers.analytics.table') }}</label>
                        <select id="service-table" name="table" class="form-select" required>
                            <option value="">{{ __('customers.analytics.select_table') }}</option>
                            <!-- Tables will be populated by JavaScript -->
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="service-waiter" class="form-label required">{{ __('customers.analytics.waiter') }}</label>
                        <select id="service-waiter" name="waiter" class="form-select" required>
                            <option value="">{{ __('customers.analytics.select_waiter') }}</option>
                            <!-- Waiters will be populated by JavaScript -->
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="service-time" class="form-label">{{ __('customers.analytics.service_time') }}</label>
                        <input type="datetime-local" id="service-time" name="service_time" class="form-input">
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="service-notes" class="form-label">{{ __('customers.analytics.notes') }}</label>
                        <textarea id="service-notes" name="notes" class="form-textarea" rows="3" 
                                  placeholder="{{ __('customers.analytics.service_notes_placeholder') }}"></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary cancel-service-btn">
                {{ __('customers.analytics.cancel') }}
            </button>
            <button type="submit" form="service-form" class="btn btn-primary save-service-btn">
                {{ __('customers.analytics.record_service') }}
            </button>
        </div>
    </div>
</div>
@endsection
