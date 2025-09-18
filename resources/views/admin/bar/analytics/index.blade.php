@extends('layouts.admin')

@section('title', __('bar.analytics.title') . ' - ' . config('app.name'))
@section('page_title', __('bar.analytics.title'))

@push('styles')
    {{-- CSS styles will be loaded via main layout --}}
@endpush

@push('scripts')
    @vite('resources/js/admin/bar/analytics.js')
@endpush

@section('content')
<div class="analytics-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('bar.analytics.title') }}</h1>
                <p class="page-subtitle">{{ __('bar.analytics.subtitle') }}</p>
            </div>
            <div class="page-actions">
                <button type="button" class="btn btn-secondary date-range-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ __('bar.analytics.date_range') }}
                </button>
                <button type="button" class="btn btn-secondary export-report-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ __('bar.analytics.export_report') }}
                </button>
                <button type="button" class="btn btn-primary refresh-data-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    {{ __('common.refresh_data') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Key Metrics Cards -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon revenue">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="total-revenue">$0.00</div>
                    <div class="stat-label">{{ __('bar.analytics.total_beverage_sales') }}</div>
                    <div class="stat-change positive" id="revenue-change">+12.5%</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon drinks">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="drinks-sold">0</div>
                    <div class="stat-label">{{ __('bar.analytics.drinks_sold') }}</div>
                    <div class="stat-change positive" id="drinks-change">+8.3%</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon aov">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="avg-order-value">$0.00</div>
                    <div class="stat-label">{{ __('bar.analytics.average_order_value') }}</div>
                    <div class="stat-change negative" id="aov-change">-2.1%</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon peak">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="peak-hour">8 PM</div>
                    <div class="stat-label">{{ __('bar.analytics.peak_hours') }}</div>
                    <div class="stat-change neutral" id="peak-change">{{ __('bar.analytics.consistent') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Time Period Filter -->
    <div class="filters-section">
        <div class="filters-panel">
            <div class="period-selector">
                <button type="button" class="period-btn active" data-period="today">{{ __('common.today') }}</button>
                <button type="button" class="period-btn" data-period="week">{{ __('common.this_week') }}</button>
                <button type="button" class="period-btn" data-period="month">{{ __('common.this_month') }}</button>
                <button type="button" class="period-btn" data-period="quarter">{{ __('bar.analytics.quarter') }}</button>
                <button type="button" class="period-btn" data-period="year">{{ __('common.this_year') }}</button>
                <button type="button" class="period-btn" data-period="custom">{{ __('bar.analytics.custom_range') }}</button>
            </div>
            
            <div class="date-inputs" id="custom-date-inputs" style="display: none;">
                <input type="date" id="start-date" class="form-input">
                <span class="date-separator">{{ __('common.to') }}</span>
                <input type="date" id="end-date" class="form-input">
                <button type="button" class="btn btn-primary apply-dates-btn">{{ __('common.apply') }}</button>
            </div>
        </div>
    </div>

    <!-- Analytics Content -->
    <div class="analytics-content">
        <!-- Charts Row 1 -->
        <div class="charts-row">
            <div class="chart-card large">
                <div class="chart-header">
                    <h3 class="chart-title">{{ __('bar.analytics.sales_by_category') }}</h3>
                    <div class="chart-controls">
                        <select class="chart-select" id="sales-period">
                            <option value="daily">{{ __('bar.analytics.daily') }}</option>
                            <option value="weekly">{{ __('bar.analytics.weekly') }}</option>
                            <option value="monthly">{{ __('bar.analytics.monthly') }}</option>
                        </select>
                    </div>
                </div>
                <div class="chart-body">
                    <canvas id="sales-category-chart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">{{ __('bar.analytics.popular_drinks') }}</h3>
                </div>
                <div class="chart-body">
                    <div class="popular-drinks-list" id="popular-drinks-list">
                        <!-- Popular drinks will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row 2 -->
        <div class="charts-row">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">{{ __('bar.analytics.hourly_sales') }}</h3>
                </div>
                <div class="chart-body">
                    <canvas id="hourly-sales-chart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">{{ __('bar.analytics.profit_analysis') }}</h3>
                </div>
                <div class="chart-body">
                    <canvas id="profit-analysis-chart"></canvas>
                </div>
            </div>
        </div>

        <!-- Charts Row 3 -->
        <div class="charts-row">
            <div class="chart-card large">
                <div class="chart-header">
                    <h3 class="chart-title">{{ __('bar.analytics.seasonal_trends') }}</h3>
                    <div class="chart-controls">
                        <div class="chart-legend" id="trends-legend">
                            <!-- Legend will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
                <div class="chart-body">
                    <canvas id="seasonal-trends-chart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">{{ __('bar.analytics.cocktail_vs_beer') }}</h3>
                </div>
                <div class="chart-body">
                    <canvas id="cocktail-beer-chart"></canvas>
                </div>
            </div>
        </div>

        <!-- Performance Insights -->
        <div class="insights-section">
            <div class="insights-header">
                <h3 class="insights-title">{{ __('bar.analytics.performance_insights') }}</h3>
            </div>
            <div class="insights-grid">
                <div class="insight-card">
                    <div class="insight-icon top-selling">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    </div>
                    <div class="insight-content">
                        <h4 class="insight-title">{{ __('bar.analytics.top_selling_drink') }}</h4>
                        <p class="insight-value" id="top-selling-drink">Classic Martini</p>
                        <p class="insight-description">{{ __('bar.analytics.top_selling_description') }}</p>
                    </div>
                </div>

                <div class="insight-card">
                    <div class="insight-icon profitable">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <div class="insight-content">
                        <h4 class="insight-title">{{ __('bar.analytics.most_profitable') }}</h4>
                        <p class="insight-value" id="most-profitable-drink">Virgin Mojito</p>
                        <p class="insight-description">{{ __('bar.analytics.profitable_description') }}</p>
                    </div>
                </div>

                <div class="insight-card">
                    <div class="insight-icon happy-hour">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="insight-content">
                        <h4 class="insight-title">{{ __('bar.analytics.happy_hour_impact') }}</h4>
                        <p class="insight-value" id="happy-hour-impact">+35%</p>
                        <p class="insight-description">{{ __('bar.analytics.happy_hour_description') }}</p>
                    </div>
                </div>

                <div class="insight-card">
                    <div class="insight-icon inventory">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div class="insight-content">
                        <h4 class="insight-title">{{ __('bar.analytics.inventory_turnover') }}</h4>
                        <p class="insight-value" id="inventory-turnover">4.2x</p>
                        <p class="insight-description">{{ __('bar.analytics.turnover_description') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Reports -->
        <div class="reports-section">
            <div class="reports-header">
                <h3 class="reports-title">{{ __('bar.analytics.quick_reports') }}</h3>
            </div>
            <div class="reports-grid">
                <div class="report-card" onclick="analyticsManager.generateReport('daily')">
                    <div class="report-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="report-content">
                        <h4 class="report-title">{{ __('bar.analytics.daily_bar_report') }}</h4>
                        <p class="report-description">{{ __('bar.analytics.daily_report_description') }}</p>
                    </div>
                </div>

                <div class="report-card" onclick="analyticsManager.generateReport('weekly')">
                    <div class="report-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="report-content">
                        <h4 class="report-title">{{ __('bar.analytics.weekly_summary') }}</h4>
                        <p class="report-description">{{ __('bar.analytics.weekly_report_description') }}</p>
                    </div>
                </div>

                <div class="report-card" onclick="analyticsManager.generateReport('monthly')">
                    <div class="report-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="report-content">
                        <h4 class="report-title">{{ __('bar.analytics.monthly_analysis') }}</h4>
                        <p class="report-description">{{ __('bar.analytics.monthly_report_description') }}</p>
                    </div>
                </div>

                <div class="report-card" onclick="analyticsManager.generateReport('wastage')">
                    <div class="report-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <div class="report-content">
                        <h4 class="report-title">{{ __('bar.analytics.wastage_report') }}</h4>
                        <p class="report-description">{{ __('bar.analytics.wastage_report_description') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Date Range Modal -->
<div class="date-range-modal" id="date-range-modal" style="display: none;" role="dialog" aria-labelledby="date-range-modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="date-range-modal-title" class="modal-title">{{ __('bar.analytics.select_date_range') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('common.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form class="date-range-form" id="date-range-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="modal-start-date" class="form-label">{{ __('bar.analytics.start_date') }}</label>
                        <input type="date" id="modal-start-date" name="start_date" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="modal-end-date" class="form-label">{{ __('bar.analytics.end_date') }}</label>
                        <input type="date" id="modal-end-date" name="end_date" class="form-input" required>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">{{ __('bar.analytics.quick_presets') }}</label>
                        <div class="preset-buttons">
                            <button type="button" class="preset-btn" data-preset="last-7-days">{{ __('bar.analytics.last_7_days') }}</button>
                            <button type="button" class="preset-btn" data-preset="last-30-days">{{ __('bar.analytics.last_30_days') }}</button>
                            <button type="button" class="preset-btn" data-preset="last-quarter">{{ __('bar.analytics.last_quarter') }}</button>
                            <button type="button" class="preset-btn" data-preset="last-year">{{ __('bar.analytics.last_year') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary cancel-btn">
                {{ __('common.cancel') }}
            </button>
            <button type="submit" form="date-range-form" class="btn btn-primary save-btn">
                {{ __('bar.analytics.apply_date_range') }}
            </button>
        </div>
    </div>
</div>
@endsection
