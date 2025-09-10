@extends('layouts.admin')

@section('title', __('finance.financial_reports.title') . ' - ' . config('app.name'))
@section('page_title', __('finance.financial_reports.title'))

@push('styles')
    @vite('resources/css/admin/financial-reports.css')
@endpush

@push('scripts')
    @vite('resources/js/admin/financial-reports.js')
@endpush

@section('content')
<div class="financial-reports-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('finance.financial_reports.title') }}</h1>
                <p class="page-subtitle">{{ __('finance.financial_reports.subtitle') }}</p>
            </div>
            <div class="page-actions">
                <button type="button" class="btn btn-secondary schedule-report-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ __('finance.financial_reports.schedule_report') }}
                </button>
                <button type="button" class="btn btn-primary generate-report-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ __('finance.financial_reports.generate_report') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Financial Overview Cards -->
    <div class="financial-overview-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <!-- Revenue Card -->
        <div class="card financial-overview-card" data-metric="revenue">
            <div class="card-body">
                <div class="metric-header">
                    <div class="metric-icon revenue">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <span class="metric-label">{{ __('finance.financial_reports.total_revenue') }}</span>
                </div>
                <div class="metric-value loading-skeleton" data-value="revenue">$0.00</div>
                <div class="metric-change positive">
                    <svg class="change-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7H7"></path>
                    </svg>
                    <span>+12.5% {{ __('finance.financial_reports.vs_last_period') }}</span>
                </div>
            </div>
        </div>

        <!-- Expenses Card -->
        <div class="card financial-overview-card" data-metric="expenses">
            <div class="card-body">
                <div class="metric-header">
                    <div class="metric-icon expenses">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <span class="metric-label">{{ __('finance.financial_reports.total_expenses') }}</span>
                </div>
                <div class="metric-value loading-skeleton" data-value="expenses">$0.00</div>
                <div class="metric-change negative">
                    <svg class="change-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 7l-9.2 9.2M7 7v10h10"></path>
                    </svg>
                    <span>+8.3% {{ __('finance.financial_reports.vs_last_period') }}</span>
                </div>
            </div>
        </div>

        <!-- Net Profit Card -->
        <div class="card financial-overview-card" data-metric="profit">
            <div class="card-body">
                <div class="metric-header">
                    <div class="metric-icon profit">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <span class="metric-label">{{ __('finance.financial_reports.net_profit') }}</span>
                </div>
                <div class="metric-value loading-skeleton" data-value="profit">$0.00</div>
                <div class="metric-change positive">
                    <svg class="change-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7H7"></path>
                    </svg>
                    <span>+18.7% {{ __('finance.financial_reports.vs_last_period') }}</span>
                </div>
            </div>
        </div>

        <!-- Profit Margin Card -->
        <div class="card financial-overview-card" data-metric="margin">
            <div class="card-body">
                <div class="metric-header">
                    <div class="metric-icon margin">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <span class="metric-label">{{ __('finance.financial_reports.profit_margin') }}</span>
                </div>
                <div class="metric-value loading-skeleton" data-value="margin">0%</div>
                <div class="metric-change positive">
                    <svg class="change-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7H7"></path>
                    </svg>
                    <span>+2.1% {{ __('finance.financial_reports.vs_last_period') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Filters -->
    <div class="card report-filters-card mb-6">
        <div class="card-header">
            <h3 class="card-title">{{ __('finance.financial_reports.report_filters') }}</h3>
            <button type="button" class="btn btn-secondary reset-filters-btn">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                {{ __('finance.financial_reports.reset_filters') }}
            </button>
        </div>
        <div class="card-body">
            <div class="filters-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
                <!-- Date Range Filter -->
                <div class="filter-group">
                    <label for="report-date-range" class="filter-label">
                        {{ __('finance.financial_reports.date_range') }}
                    </label>
                    <select id="report-date-range" class="form-select">
                        <option value="this-month">{{ __('finance.financial_reports.this_month') }}</option>
                        <option value="last-month">{{ __('finance.financial_reports.last_month') }}</option>
                        <option value="this-quarter">{{ __('finance.financial_reports.this_quarter') }}</option>
                        <option value="last-quarter">{{ __('finance.financial_reports.last_quarter') }}</option>
                        <option value="this-year">{{ __('finance.financial_reports.this_year') }}</option>
                        <option value="last-year">{{ __('finance.financial_reports.last_year') }}</option>
                        <option value="custom">{{ __('finance.financial_reports.custom_range') }}</option>
                    </select>
                </div>

                <!-- Report Type Filter -->
                <div class="filter-group">
                    <label for="report-type" class="filter-label">
                        {{ __('finance.financial_reports.report_type') }}
                    </label>
                    <select id="report-type" class="form-select">
                        <option value="all">{{ __('finance.financial_reports.all_reports') }}</option>
                        <option value="income-statement">{{ __('finance.financial_reports.income_statement') }}</option>
                        <option value="balance-sheet">{{ __('finance.financial_reports.balance_sheet') }}</option>
                        <option value="cash-flow">{{ __('finance.financial_reports.cash_flow') }}</option>
                        <option value="profit-loss">{{ __('finance.financial_reports.profit_loss') }}</option>
                        <option value="expense-analysis">{{ __('finance.financial_reports.expense_analysis') }}</option>
                    </select>
                </div>

                <!-- Department Filter -->
                <div class="filter-group">
                    <label for="department-filter" class="filter-label">
                        {{ __('finance.financial_reports.department') }}
                    </label>
                    <select id="department-filter" class="form-select">
                        <option value="all">{{ __('finance.financial_reports.all_departments') }}</option>
                        <option value="kitchen">{{ __('finance.financial_reports.kitchen') }}</option>
                        <option value="service">{{ __('finance.financial_reports.service') }}</option>
                        <option value="management">{{ __('finance.financial_reports.management') }}</option>
                        <option value="marketing">{{ __('finance.financial_reports.marketing') }}</option>
                    </select>
                </div>

                <!-- Format Filter -->
                <div class="filter-group">
                    <label for="format-filter" class="filter-label">
                        {{ __('finance.financial_reports.format') }}
                    </label>
                    <select id="format-filter" class="form-select">
                        <option value="pdf">{{ __('finance.financial_reports.pdf_format') }}</option>
                        <option value="excel">{{ __('finance.financial_reports.excel_format') }}</option>
                        <option value="csv">{{ __('finance.financial_reports.csv_format') }}</option>
                        <option value="json">{{ __('finance.financial_reports.json_format') }}</option>
                    </select>
                </div>
            </div>

            <!-- Custom Date Range -->
            <div class="custom-date-range" style="display: none;">
                <div class="date-range-grid grid grid-cols-1 md:grid-cols-2">
                    <div class="filter-group">
                        <label for="custom-start-date" class="filter-label">
                            {{ __('finance.financial_reports.start_date') }}
                        </label>
                        <input type="date" id="custom-start-date" class="form-input">
                    </div>
                    <div class="filter-group">
                        <label for="custom-end-date" class="filter-label">
                            {{ __('finance.financial_reports.end_date') }}
                        </label>
                        <input type="date" id="custom-end-date" class="form-input">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Types Grid -->
    <div class="report-types-section mb-8">
        <h2 class="section-title">{{ __('finance.financial_reports.available_reports') }}</h2>
        <div class="report-types-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
            <!-- Income Statement -->
            <div class="report-type-card" data-report="income-statement">
                <div class="report-icon">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="report-content">
                    <h3 class="report-title">{{ __('finance.financial_reports.income_statement') }}</h3>
                    <p class="report-description">{{ __('finance.financial_reports.income_statement_desc') }}</p>
                    <div class="report-actions">
                        <button class="btn btn-primary generate-btn" data-report="income-statement">
                            {{ __('finance.financial_reports.generate') }}
                        </button>
                        <button class="btn btn-secondary preview-btn" data-report="income-statement">
                            {{ __('finance.financial_reports.preview') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Balance Sheet -->
            <div class="report-type-card" data-report="balance-sheet">
                <div class="report-icon">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <div class="report-content">
                    <h3 class="report-title">{{ __('finance.financial_reports.balance_sheet') }}</h3>
                    <p class="report-description">{{ __('finance.financial_reports.balance_sheet_desc') }}</p>
                    <div class="report-actions">
                        <button class="btn btn-primary generate-btn" data-report="balance-sheet">
                            {{ __('finance.financial_reports.generate') }}
                        </button>
                        <button class="btn btn-secondary preview-btn" data-report="balance-sheet">
                            {{ __('finance.financial_reports.preview') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Cash Flow Statement -->
            <div class="report-type-card" data-report="cash-flow">
                <div class="report-icon">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                    </svg>
                </div>
                <div class="report-content">
                    <h3 class="report-title">{{ __('finance.financial_reports.cash_flow') }}</h3>
                    <p class="report-description">{{ __('finance.financial_reports.cash_flow_desc') }}</p>
                    <div class="report-actions">
                        <button class="btn btn-primary generate-btn" data-report="cash-flow">
                            {{ __('finance.financial_reports.generate') }}
                        </button>
                        <button class="btn btn-secondary preview-btn" data-report="cash-flow">
                            {{ __('finance.financial_reports.preview') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Profit & Loss -->
            <div class="report-type-card" data-report="profit-loss">
                <div class="report-icon">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="report-content">
                    <h3 class="report-title">{{ __('finance.financial_reports.profit_loss') }}</h3>
                    <p class="report-description">{{ __('finance.financial_reports.profit_loss_desc') }}</p>
                    <div class="report-actions">
                        <button class="btn btn-primary generate-btn" data-report="profit-loss">
                            {{ __('finance.financial_reports.generate') }}
                        </button>
                        <button class="btn btn-secondary preview-btn" data-report="profit-loss">
                            {{ __('finance.financial_reports.preview') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Expense Analysis -->
            <div class="report-type-card" data-report="expense-analysis">
                <div class="report-icon">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="report-content">
                    <h3 class="report-title">{{ __('finance.financial_reports.expense_analysis') }}</h3>
                    <p class="report-description">{{ __('finance.financial_reports.expense_analysis_desc') }}</p>
                    <div class="report-actions">
                        <button class="btn btn-primary generate-btn" data-report="expense-analysis">
                            {{ __('finance.financial_reports.generate') }}
                        </button>
                        <button class="btn btn-secondary preview-btn" data-report="expense-analysis">
                            {{ __('finance.financial_reports.preview') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tax Reports -->
            <div class="report-type-card" data-report="tax-reports">
                <div class="report-icon">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="report-content">
                    <h3 class="report-title">{{ __('finance.financial_reports.tax_reports') }}</h3>
                    <p class="report-description">{{ __('finance.financial_reports.tax_reports_desc') }}</p>
                    <div class="report-actions">
                        <button class="btn btn-primary generate-btn" data-report="tax-reports">
                            {{ __('finance.financial_reports.generate') }}
                        </button>
                        <button class="btn btn-secondary preview-btn" data-report="tax-reports">
                            {{ __('finance.financial_reports.preview') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Reports -->
    <div class="card recent-reports-card">
        <div class="card-header">
            <h3 class="card-title">{{ __('finance.financial_reports.recent_reports') }}</h3>
            <div class="header-actions">
                <button type="button" class="btn btn-secondary refresh-reports-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    {{ __('finance.financial_reports.refresh') }}
                </button>
            </div>
        </div>
        <div class="card-body">
            <!-- Reports Table -->
            <div class="reports-table-wrapper">
                <table class="reports-table" role="table" aria-label="{{ __('finance.financial_reports.recent_reports') }}">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('finance.financial_reports.report_name') }}</th>
                            <th scope="col">{{ __('finance.financial_reports.type') }}</th>
                            <th scope="col">{{ __('finance.financial_reports.period') }}</th>
                            <th scope="col">{{ __('finance.financial_reports.generated_date') }}</th>
                            <th scope="col">{{ __('finance.financial_reports.status') }}</th>
                            <th scope="col">{{ __('finance.financial_reports.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="reports-table-body">
                        <!-- Loading skeleton rows -->
                        <tr class="loading-row">
                            <td><div class="skeleton-text"></div></td>
                            <td><div class="skeleton-badge"></div></td>
                            <td><div class="skeleton-text"></div></td>
                            <td><div class="skeleton-text"></div></td>
                            <td><div class="skeleton-badge"></div></td>
                            <td><div class="skeleton-actions"></div></td>
                        </tr>
                        <tr class="loading-row">
                            <td><div class="skeleton-text"></div></td>
                            <td><div class="skeleton-badge"></div></td>
                            <td><div class="skeleton-text"></div></td>
                            <td><div class="skeleton-text"></div></td>
                            <td><div class="skeleton-badge"></div></td>
                            <td><div class="skeleton-actions"></div></td>
                        </tr>
                        <tr class="loading-row">
                            <td><div class="skeleton-text"></div></td>
                            <td><div class="skeleton-badge"></div></td>
                            <td><div class="skeleton-text"></div></td>
                            <td><div class="skeleton-text"></div></td>
                            <td><div class="skeleton-badge"></div></td>
                            <td><div class="skeleton-actions"></div></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            <div class="empty-state" style="display: none;">
                <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3>{{ __('finance.financial_reports.no_reports_found') }}</h3>
                <p>{{ __('finance.financial_reports.no_reports_description') }}</p>
                <button type="button" class="btn btn-primary generate-report-btn">
                    {{ __('finance.financial_reports.generate_first_report') }}
                </button>
            </div>

            <!-- Error State -->
            <div class="error-state" style="display: none;">
                <svg class="error-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3>{{ __('finance.financial_reports.error_loading') }}</h3>
                <p>{{ __('finance.financial_reports.error_description') }}</p>
                <button type="button" class="btn btn-primary retry-btn">
                    {{ __('finance.financial_reports.retry') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Generate Report Modal -->
<div class="report-modal" id="report-modal" style="display: none;" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modal-title" class="modal-title">{{ __('finance.financial_reports.generate_report') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('finance.financial_reports.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form class="report-form" id="report-form">
                <div class="form-grid grid grid-cols-1 md:grid-cols-2">
                    <!-- Report Name -->
                    <div class="form-group md:col-span-2">
                        <label for="report-name" class="form-label required">
                            {{ __('finance.financial_reports.report_name') }}
                        </label>
                        <input type="text" id="report-name" name="name" class="form-input" required
                               placeholder="{{ __('finance.financial_reports.report_name_placeholder') }}">
                    </div>

                    <!-- Report Type -->
                    <div class="form-group">
                        <label for="modal-report-type" class="form-label required">
                            {{ __('finance.financial_reports.report_type') }}
                        </label>
                        <select id="modal-report-type" name="type" class="form-select" required>
                            <option value="">{{ __('finance.financial_reports.select_report_type') }}</option>
                            <option value="income-statement">{{ __('finance.financial_reports.income_statement') }}</option>
                            <option value="balance-sheet">{{ __('finance.financial_reports.balance_sheet') }}</option>
                            <option value="cash-flow">{{ __('finance.financial_reports.cash_flow') }}</option>
                            <option value="profit-loss">{{ __('finance.financial_reports.profit_loss') }}</option>
                            <option value="expense-analysis">{{ __('finance.financial_reports.expense_analysis') }}</option>
                            <option value="tax-reports">{{ __('finance.financial_reports.tax_reports') }}</option>
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div class="form-group">
                        <label for="modal-date-range" class="form-label required">
                            {{ __('finance.financial_reports.date_range') }}
                        </label>
                        <select id="modal-date-range" name="date_range" class="form-select" required>
                            <option value="this-month">{{ __('finance.financial_reports.this_month') }}</option>
                            <option value="last-month">{{ __('finance.financial_reports.last_month') }}</option>
                            <option value="this-quarter">{{ __('finance.financial_reports.this_quarter') }}</option>
                            <option value="last-quarter">{{ __('finance.financial_reports.last_quarter') }}</option>
                            <option value="this-year">{{ __('finance.financial_reports.this_year') }}</option>
                            <option value="last-year">{{ __('finance.financial_reports.last_year') }}</option>
                            <option value="custom">{{ __('finance.financial_reports.custom_range') }}</option>
                        </select>
                    </div>

                    <!-- Custom Date Range -->
                    <div class="form-group modal-custom-dates" style="display: none;">
                        <label for="modal-start-date" class="form-label">
                            {{ __('finance.financial_reports.start_date') }}
                        </label>
                        <input type="date" id="modal-start-date" name="start_date" class="form-input">
                    </div>

                    <div class="form-group modal-custom-dates" style="display: none;">
                        <label for="modal-end-date" class="form-label">
                            {{ __('finance.financial_reports.end_date') }}
                        </label>
                        <input type="date" id="modal-end-date" name="end_date" class="form-input">
                    </div>

                    <!-- Format -->
                    <div class="form-group">
                        <label for="modal-format" class="form-label required">
                            {{ __('finance.financial_reports.format') }}
                        </label>
                        <select id="modal-format" name="format" class="form-select" required>
                            <option value="pdf">{{ __('finance.financial_reports.pdf_format') }}</option>
                            <option value="excel">{{ __('finance.financial_reports.excel_format') }}</option>
                            <option value="csv">{{ __('finance.financial_reports.csv_format') }}</option>
                            <option value="json">{{ __('finance.financial_reports.json_format') }}</option>
                        </select>
                    </div>

                    <!-- Include Details -->
                    <div class="form-group md:col-span-2">
                        <label class="form-label">{{ __('finance.financial_reports.include_details') }}</label>
                        <div class="checkbox-group">
                            <label class="checkbox-item">
                                <input type="checkbox" name="include_charts" value="1" checked>
                                <span class="checkmark"></span>
                                {{ __('finance.financial_reports.include_charts') }}
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" name="include_comparisons" value="1" checked>
                                <span class="checkmark"></span>
                                {{ __('finance.financial_reports.include_comparisons') }}
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" name="include_notes" value="1">
                                <span class="checkmark"></span>
                                {{ __('finance.financial_reports.include_notes') }}
                            </label>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="form-group md:col-span-2">
                        <label for="report-notes" class="form-label">
                            {{ __('finance.financial_reports.notes') }}
                        </label>
                        <textarea id="report-notes" name="notes" class="form-textarea" rows="3"
                                  placeholder="{{ __('finance.financial_reports.notes_placeholder') }}"></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary cancel-btn">
                {{ __('finance.financial_reports.cancel') }}
            </button>
            <button type="submit" form="report-form" class="btn btn-primary generate-btn">
                {{ __('finance.financial_reports.generate_report') }}
            </button>
        </div>
    </div>
</div>

<!-- Schedule Report Modal -->
<div class="schedule-modal" id="schedule-modal" style="display: none;" role="dialog" aria-labelledby="schedule-modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="schedule-modal-title" class="modal-title">{{ __('finance.financial_reports.schedule_report') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('finance.financial_reports.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form class="schedule-form" id="schedule-form">
                <div class="form-grid grid grid-cols-1 md:grid-cols-2">
                    <!-- Schedule Name -->
                    <div class="form-group md:col-span-2">
                        <label for="schedule-name" class="form-label required">
                            {{ __('finance.financial_reports.schedule_name') }}
                        </label>
                        <input type="text" id="schedule-name" name="name" class="form-input" required
                               placeholder="{{ __('finance.financial_reports.schedule_name_placeholder') }}">
                    </div>

                    <!-- Frequency -->
                    <div class="form-group">
                        <label for="schedule-frequency" class="form-label required">
                            {{ __('finance.financial_reports.frequency') }}
                        </label>
                        <select id="schedule-frequency" name="frequency" class="form-select" required>
                            <option value="">{{ __('finance.financial_reports.select_frequency') }}</option>
                            <option value="daily">{{ __('finance.financial_reports.daily') }}</option>
                            <option value="weekly">{{ __('finance.financial_reports.weekly') }}</option>
                            <option value="monthly">{{ __('finance.financial_reports.monthly') }}</option>
                            <option value="quarterly">{{ __('finance.financial_reports.quarterly') }}</option>
                            <option value="yearly">{{ __('finance.financial_reports.yearly') }}</option>
                        </select>
                    </div>

                    <!-- Recipients -->
                    <div class="form-group">
                        <label for="schedule-recipients" class="form-label required">
                            {{ __('finance.financial_reports.recipients') }}
                        </label>
                        <input type="email" id="schedule-recipients" name="recipients" class="form-input" required
                               placeholder="{{ __('finance.financial_reports.recipients_placeholder') }}">
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary cancel-btn">
                {{ __('finance.financial_reports.cancel') }}
            </button>
            <button type="submit" form="schedule-form" class="btn btn-primary schedule-btn">
                {{ __('finance.financial_reports.schedule_report') }}
            </button>
        </div>
    </div>
</div>
@endsection
