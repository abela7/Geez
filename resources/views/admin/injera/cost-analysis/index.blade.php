@extends('layouts.admin')

@section('title', __('injera.cost_analysis.title') . ' - ' . config('app.name'))
@section('page_title', __('injera.cost_analysis.title'))

@section('content')
<div class="admin-content">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <h1 class="page-title">{{ __('injera.cost_analysis.title') }}</h1>
            <p class="page-subtitle">{{ __('injera.cost_analysis.subtitle') }}</p>
        </div>
        <div class="page-header-actions">
            <button class="btn btn-secondary" onclick="exportAnalysis()">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                {{ __('injera.cost_analysis.export_report') }}
            </button>
            <button class="btn btn-primary" onclick="openSettingsModal()">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                {{ __('injera.cost_analysis.settings') }}
            </button>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="filters-container">
        <div class="filters-content">
            <div class="date-range-filter">
                <label for="startDate">{{ __('injera.cost_analysis.date_range') }}:</label>
                <input type="date" id="startDate" value="{{ $startDate }}" onchange="updateAnalysis()">
                <span class="date-separator">{{ __('injera.cost_analysis.to') }}</span>
                <input type="date" id="endDate" value="{{ $endDate }}" onchange="updateAnalysis()">
            </div>

            <div class="filter-group">
                <select id="periodFilter" class="filter-select" onchange="updateAnalysis()">
                    <option value="daily" {{ $period === 'daily' ? 'selected' : '' }}>{{ __('injera.cost_analysis.daily') }}</option>
                    <option value="weekly" {{ $period === 'weekly' ? 'selected' : '' }}>{{ __('injera.cost_analysis.weekly') }}</option>
                    <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>{{ __('injera.cost_analysis.monthly') }}</option>
                </select>

                <button class="btn btn-secondary" onclick="resetFilters()">
                    {{ __('injera.cost_analysis.reset_filters') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Cost Metrics Summary -->
    <div class="metrics-grid">
        <div class="metric-card primary">
            <div class="metric-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                </svg>
            </div>
            <div class="metric-content">
                <h3 class="metric-title">{{ __('injera.cost_analysis.total_production_cost') }}</h3>
                <div class="metric-value">${{ number_format($costMetrics['total_production_cost'], 2) }}</div>
                <div class="metric-subtitle">{{ __('injera.cost_analysis.last_30_days') }}</div>
            </div>
        </div>

        <div class="metric-card success">
            <div class="metric-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
            <div class="metric-content">
                <h3 class="metric-title">{{ __('injera.cost_analysis.profit_margin') }}</h3>
                <div class="metric-value">{{ number_format($costMetrics['profit_margin'], 1) }}%</div>
                <div class="metric-subtitle">{{ __('injera.cost_analysis.gross_profit') }}</div>
            </div>
        </div>

        <div class="metric-card warning">
            <div class="metric-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div class="metric-content">
                <h3 class="metric-title">{{ __('injera.cost_analysis.cost_per_injera') }}</h3>
                <div class="metric-value">${{ number_format($costMetrics['cost_per_injera'], 3) }}</div>
                <div class="metric-subtitle">{{ __('injera.cost_analysis.average_cost') }}</div>
            </div>
        </div>

        <div class="metric-card info">
            <div class="metric-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <div class="metric-content">
                <h3 class="metric-title">{{ __('injera.cost_analysis.cost_efficiency') }}</h3>
                <div class="metric-value">{{ number_format($costMetrics['cost_efficiency'], 1) }}%</div>
                <div class="metric-subtitle">{{ __('injera.cost_analysis.efficiency_score') }}</div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-grid">
        <!-- Cost Trend Chart -->
        <div class="chart-container">
            <div class="chart-header">
                <h3>{{ __('injera.cost_analysis.cost_trends') }}</h3>
                <div class="chart-controls">
                    <button class="chart-btn active" data-chart="line" onclick="switchChartType('trends', 'line')">
                        {{ __('injera.cost_analysis.line_chart') }}
                    </button>
                    <button class="chart-btn" data-chart="bar" onclick="switchChartType('trends', 'bar')">
                        {{ __('injera.cost_analysis.bar_chart') }}
                    </button>
                </div>
            </div>
            <div class="chart-wrapper">
                <canvas id="trendsChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Cost Breakdown Pie Chart -->
        <div class="chart-container">
            <div class="chart-header">
                <h3>{{ __('injera.cost_analysis.cost_breakdown') }}</h3>
                <div class="chart-legend">
                    <div class="legend-item">
                        <span class="legend-color materials"></span>
                        <span class="legend-label">{{ __('injera.cost_analysis.materials') }}</span>
                        <span class="legend-value">{{ number_format($costMetrics['material_cost_percentage'], 1) }}%</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-color labor"></span>
                        <span class="legend-label">{{ __('injera.cost_analysis.labor') }}</span>
                        <span class="legend-value">{{ number_format($costMetrics['labor_cost_percentage'], 1) }}%</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-color overhead"></span>
                        <span class="legend-label">{{ __('injera.cost_analysis.overhead') }}</span>
                        <span class="legend-value">{{ number_format($costMetrics['overhead_cost_percentage'], 1) }}%</span>
                    </div>
                </div>
            </div>
            <div class="chart-wrapper">
                <canvas id="breakdownChart" width="300" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Profitability Analysis -->
    <div class="analysis-section">
        <div class="section-header">
            <h2>{{ __('injera.cost_analysis.profitability_analysis') }}</h2>
            <p>{{ __('injera.cost_analysis.profitability_subtitle') }}</p>
        </div>

        <div class="profitability-grid">
            <!-- By Quality Grade -->
            <div class="profitability-card">
                <h3>{{ __('injera.cost_analysis.by_quality_grade') }}</h3>
                <div class="quality-metrics">
                    @foreach($profitabilityData['by_quality'] as $grade => $data)
                    <div class="quality-row">
                        <div class="quality-info">
                            <span class="quality-badge quality-{{ strtolower($grade) }}">{{ $grade }}</span>
                            <div class="quality-stats">
                                <span class="stat-label">{{ __('injera.cost_analysis.volume') }}: {{ number_format($data['volume']) }}</span>
                                <span class="stat-label">{{ __('injera.cost_analysis.revenue') }}: ${{ number_format($data['revenue'], 2) }}</span>
                            </div>
                        </div>
                        <div class="quality-metrics-values">
                            <div class="metric-item">
                                <span class="metric-label">{{ __('injera.cost_analysis.profit') }}</span>
                                <span class="metric-value profit">${{ number_format($data['profit'], 2) }}</span>
                            </div>
                            <div class="metric-item">
                                <span class="metric-label">{{ __('injera.cost_analysis.margin') }}</span>
                                <span class="metric-value margin">{{ number_format($data['margin'], 1) }}%</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- By Batch Size -->
            <div class="profitability-card">
                <h3>{{ __('injera.cost_analysis.by_batch_size') }}</h3>
                <div class="batch-size-metrics">
                    @foreach($profitabilityData['by_batch_size'] as $size => $data)
                    <div class="batch-size-row">
                        <div class="batch-size-info">
                            <span class="batch-size-label">{{ __('injera.cost_analysis.' . $size . '_batch') }}</span>
                            <span class="batch-count">{{ $data['count'] }} {{ __('injera.cost_analysis.batches') }}</span>
                        </div>
                        <div class="batch-size-values">
                            <div class="value-item">
                                <span class="value-label">{{ __('injera.cost_analysis.avg_profit') }}</span>
                                <span class="value-amount">${{ number_format($data['avg_profit'], 2) }}</span>
                            </div>
                            <div class="value-item">
                                <span class="value-label">{{ __('injera.cost_analysis.margin') }}</span>
                                <span class="value-percentage">{{ number_format($data['margin'], 1) }}%</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Cost Breakdown -->
    <div class="breakdown-section">
        <div class="section-header">
            <h2>{{ __('injera.cost_analysis.detailed_breakdown') }}</h2>
            <p>{{ __('injera.cost_analysis.breakdown_subtitle') }}</p>
        </div>

        <div class="breakdown-tabs">
            <button class="tab-btn active" onclick="switchTab('materials')">{{ __('injera.cost_analysis.materials') }}</button>
            <button class="tab-btn" onclick="switchTab('labor')">{{ __('injera.cost_analysis.labor') }}</button>
            <button class="tab-btn" onclick="switchTab('overhead')">{{ __('injera.cost_analysis.overhead') }}</button>
        </div>

        <div class="breakdown-content">
            <!-- Materials Tab -->
            <div id="materials-tab" class="tab-content active">
                <div class="cost-items">
                    @foreach($costBreakdown['materials'] as $item => $data)
                    <div class="cost-item">
                        <div class="cost-item-info">
                            <span class="cost-item-name">{{ __('injera.cost_analysis.' . $item) }}</span>
                            <span class="cost-item-percentage">{{ number_format($data['percentage'], 1) }}%</span>
                        </div>
                        <div class="cost-item-values">
                            <span class="cost-total">${{ number_format($data['amount'], 2) }}</span>
                            <span class="cost-per-injera">${{ number_format($data['per_injera'], 3) }}/{{ __('injera.cost_analysis.per_injera') }}</span>
                        </div>
                        <div class="cost-progress">
                            <div class="progress-bar" style="width: {{ $data['percentage'] }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Labor Tab -->
            <div id="labor-tab" class="tab-content">
                <div class="cost-items">
                    @foreach($costBreakdown['labor'] as $item => $data)
                    <div class="cost-item">
                        <div class="cost-item-info">
                            <span class="cost-item-name">{{ __('injera.cost_analysis.' . $item) }}</span>
                            <span class="cost-item-percentage">{{ number_format($data['percentage'], 1) }}%</span>
                        </div>
                        <div class="cost-item-values">
                            <span class="cost-total">${{ number_format($data['amount'], 2) }}</span>
                            <span class="cost-per-injera">${{ number_format($data['per_injera'], 3) }}/{{ __('injera.cost_analysis.per_injera') }}</span>
                        </div>
                        <div class="cost-progress">
                            <div class="progress-bar labor" style="width: {{ $data['percentage'] }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Overhead Tab -->
            <div id="overhead-tab" class="tab-content">
                <div class="cost-items">
                    @foreach($costBreakdown['overhead'] as $item => $data)
                    <div class="cost-item">
                        <div class="cost-item-info">
                            <span class="cost-item-name">{{ __('injera.cost_analysis.' . $item) }}</span>
                            <span class="cost-item-percentage">{{ number_format($data['percentage'], 1) }}%</span>
                        </div>
                        <div class="cost-item-values">
                            <span class="cost-total">${{ number_format($data['amount'], 2) }}</span>
                            <span class="cost-per-injera">${{ number_format($data['per_injera'], 3) }}/{{ __('injera.cost_analysis.per_injera') }}</span>
                        </div>
                        <div class="cost-progress">
                            <div class="progress-bar overhead" style="width: {{ $data['percentage'] }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Batch Comparison Table -->
    <div class="table-container">
        <div class="table-header">
            <h2 class="table-title">{{ __('injera.cost_analysis.batch_comparison') }}</h2>
            <div class="table-actions">
                <button class="btn btn-outline" onclick="refreshData()">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    {{ __('injera.cost_analysis.refresh') }}
                </button>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="sortable" data-sort="batch_name">
                            {{ __('injera.cost_analysis.batch_name') }}
                            <svg class="sort-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                            </svg>
                        </th>
                        <th>{{ __('injera.cost_analysis.quality') }}</th>
                        <th class="sortable" data-sort="volume">
                            {{ __('injera.cost_analysis.volume') }}
                            <svg class="sort-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                            </svg>
                        </th>
                        <th class="sortable" data-sort="total_cost">
                            {{ __('injera.cost_analysis.total_cost') }}
                            <svg class="sort-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                            </svg>
                        </th>
                        <th>{{ __('injera.cost_analysis.cost_per_injera') }}</th>
                        <th>{{ __('injera.cost_analysis.revenue') }}</th>
                        <th class="sortable" data-sort="profit">
                            {{ __('injera.cost_analysis.profit') }}
                            <svg class="sort-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                            </svg>
                        </th>
                        <th>{{ __('injera.cost_analysis.margin') }}</th>
                        <th>{{ __('injera.cost_analysis.efficiency') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($batchComparison as $batch)
                    <tr class="table-row">
                        <td class="batch-name">
                            <div class="batch-info">
                                <span class="batch-title">{{ $batch['batch_name'] }}</span>
                                <span class="batch-notes">{{ $batch['batch_id'] }} - {{ \Carbon\Carbon::parse($batch['production_date'])->format('M j, Y') }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="quality-badge quality-{{ strtolower($batch['quality_grade']) }}">
                                {{ $batch['quality_grade'] }}
                            </span>
                        </td>
                        <td class="volume-cell">
                            <span class="volume-value">{{ number_format($batch['volume']) }}</span>
                            <span class="volume-unit">{{ __('injera.cost_analysis.pieces') }}</span>
                        </td>
                        <td class="cost-cell">
                            <span class="cost-value">${{ number_format($batch['total_cost'], 2) }}</span>
                        </td>
                        <td class="cost-per-injera-cell">
                            <span class="cost-per-injera-value">${{ number_format($batch['cost_per_injera'], 3) }}</span>
                        </td>
                        <td class="revenue-cell">
                            <span class="revenue-value">${{ number_format($batch['revenue'], 2) }}</span>
                        </td>
                        <td class="profit-cell">
                            <span class="profit-value">${{ number_format($batch['profit'], 2) }}</span>
                        </td>
                        <td class="margin-cell">
                            <span class="margin-badge {{ $batch['margin'] >= 30 ? 'high' : ($batch['margin'] >= 25 ? 'medium' : 'low') }}">
                                {{ number_format($batch['margin'], 1) }}%
                            </span>
                        </td>
                        <td class="efficiency-cell">
                            <div class="efficiency-score">
                                <span class="score-value">{{ number_format($batch['efficiency_score'], 1) }}%</span>
                                <div class="score-bar">
                                    <div class="score-fill" style="width: {{ $batch['efficiency_score'] }}%"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Settings Modal -->
<div id="settingsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>{{ __('injera.cost_analysis.analysis_settings') }}</h3>
            <button class="modal-close" onclick="closeSettingsModal()">&times;</button>
        </div>
        <form id="settingsForm" onsubmit="saveSettings(event)">
            <div class="modal-body">
                <div class="form-group">
                    <label for="currencySymbol">{{ __('injera.cost_analysis.currency_symbol') }}</label>
                    <input type="text" id="currencySymbol" name="currency_symbol" value="$" maxlength="3">
                </div>
                <div class="form-group">
                    <label for="decimalPlaces">{{ __('injera.cost_analysis.decimal_places') }}</label>
                    <select id="decimalPlaces" name="decimal_places">
                        <option value="2">2</option>
                        <option value="3" selected>3</option>
                        <option value="4">4</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="defaultPeriod">{{ __('injera.cost_analysis.default_period') }}</label>
                    <select id="defaultPeriod" name="default_period">
                        <option value="daily">{{ __('injera.cost_analysis.daily') }}</option>
                        <option value="weekly">{{ __('injera.cost_analysis.weekly') }}</option>
                        <option value="monthly" selected>{{ __('injera.cost_analysis.monthly') }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>{{ __('injera.cost_analysis.chart_options') }}</label>
                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="show_trends" checked>
                            {{ __('injera.cost_analysis.show_trend_lines') }}
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="animate_charts" checked>
                            {{ __('injera.cost_analysis.animate_charts') }}
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeSettingsModal()">
                    {{ __('injera.cost_analysis.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary">
                    {{ __('injera.cost_analysis.save_settings') }}
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('styles')
@vite(['resources/css/admin/injera/cost-analysis.css'])
@endpush

@push('scripts')
@vite(['resources/js/admin/injera/cost-analysis.js'])
<script>
    // Pass data to JavaScript
    window.trendData = @json($trendData);
    window.costBreakdown = @json($costBreakdown);
    window.costMetrics = @json($costMetrics);
</script>
@endpush
