@extends('layouts.admin')

@section('title', __('finance.expenses.title') . ' - ' . config('app.name'))
@section('page_title', __('finance.expenses.title'))

@push('styles')
    @vite('resources/css/admin/expenses.css')
@endpush

@push('scripts')
    @vite('resources/js/admin/expenses.js')
@endpush

@section('content')
<div class="expenses-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('finance.expenses.title') }}</h1>
                <p class="page-subtitle">{{ __('finance.expenses.subtitle') }}</p>
            </div>
            <div class="page-actions">
                <button type="button" class="btn btn-secondary export-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ __('finance.export') }}
                </button>
                <button type="button" class="btn btn-primary add-expense-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    {{ __('finance.add_expense') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <!-- Total Expenses Card -->
        <div class="card summary-card" data-summary="total-expenses">
            <div class="card-body">
                <div class="summary-header">
                    <div class="summary-icon">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <span class="summary-label">{{ __('finance.total_expenses') }}</span>
                </div>
                <div class="summary-value loading-skeleton" data-value="total-expenses">$0.00</div>
                <div class="summary-change negative">
                    <svg class="change-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 7l-9.2 9.2M7 7v10h10"></path>
                    </svg>
                    <span>+15.2%</span>
                </div>
            </div>
        </div>

        <!-- This Month Card -->
        <div class="card summary-card" data-summary="monthly-expenses">
            <div class="card-body">
                <div class="summary-header">
                    <div class="summary-icon">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <span class="summary-label">{{ __('finance.this_month') }}</span>
                </div>
                <div class="summary-value loading-skeleton" data-value="monthly-expenses">$0.00</div>
                <div class="summary-change positive">
                    <svg class="change-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7H7"></path>
                    </svg>
                    <span>-8.5%</span>
                </div>
            </div>
        </div>

        <!-- Pending Approvals Card -->
        <div class="card summary-card" data-summary="pending-approvals">
            <div class="card-body">
                <div class="summary-header">
                    <div class="summary-icon">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="summary-label">{{ __('finance.pending_approvals') }}</span>
                </div>
                <div class="summary-value loading-skeleton" data-value="pending-approvals">0</div>
                <div class="summary-meta">
                    <span class="pending-amount">$0.00 {{ __('finance.pending') }}</span>
                </div>
            </div>
        </div>

        <!-- Top Category Card -->
        <div class="card summary-card" data-summary="top-category">
            <div class="card-body">
                <div class="summary-header">
                    <div class="summary-icon">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <span class="summary-label">{{ __('finance.top_category') }}</span>
                </div>
                <div class="summary-value loading-skeleton" data-value="top-category">{{ __('finance.no_data') }}</div>
                <div class="summary-meta">
                    <span class="category-amount">$0.00 {{ __('finance.spent') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Panel -->
    <div class="card filters-panel" data-collapsed="true">
        <div class="card-header">
            <button type="button" class="filters-toggle" aria-expanded="false" aria-controls="filters-content">
                <h3 class="card-title">{{ __('finance.filters') }}</h3>
                <svg class="chevron-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </div>
        <div class="filters-content" id="filters-content" style="display: none;">
            <div class="card-body">
                <div class="filters-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
                    <!-- Date Range Filter -->
                    <div class="filter-group">
                        <label for="date-range-filter" class="filter-label">
                            {{ __('finance.date_range') }}
                        </label>
                        <select id="date-range-filter" class="form-select">
                            <option value="today">{{ __('finance.today') }}</option>
                            <option value="week">{{ __('finance.this_week') }}</option>
                            <option value="month" selected>{{ __('finance.this_month') }}</option>
                            <option value="quarter">{{ __('finance.this_quarter') }}</option>
                            <option value="year">{{ __('finance.this_year') }}</option>
                            <option value="custom">{{ __('finance.custom_range') }}</option>
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div class="filter-group">
                        <label for="category-filter" class="filter-label">
                            {{ __('finance.category') }}
                        </label>
                        <select id="category-filter" class="form-select" multiple>
                            <option value="food-supplies">{{ __('finance.food_supplies') }}</option>
                            <option value="utilities">{{ __('finance.utilities') }}</option>
                            <option value="rent">{{ __('finance.rent') }}</option>
                            <option value="marketing">{{ __('finance.marketing') }}</option>
                            <option value="equipment">{{ __('finance.equipment') }}</option>
                            <option value="maintenance">{{ __('finance.maintenance') }}</option>
                            <option value="other">{{ __('finance.other') }}</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="filter-group">
                        <label for="status-filter" class="filter-label">
                            {{ __('finance.status') }}
                        </label>
                        <select id="status-filter" class="form-select">
                            <option value="">{{ __('finance.all_statuses') }}</option>
                            <option value="pending">{{ __('finance.pending') }}</option>
                            <option value="approved">{{ __('finance.approved') }}</option>
                            <option value="paid">{{ __('finance.paid') }}</option>
                            <option value="rejected">{{ __('finance.rejected') }}</option>
                        </select>
                    </div>

                    <!-- Search -->
                    <div class="filter-group">
                        <label for="search-filter" class="filter-label">
                            {{ __('finance.search') }}
                        </label>
                        <input type="text" id="search-filter" class="form-input" 
                               placeholder="{{ __('finance.search_expenses_placeholder') }}">
                    </div>
                </div>

                <!-- Custom Date Range -->
                <div class="custom-date-range" style="display: none;">
                    <div class="date-range-grid grid grid-cols-1 md:grid-cols-2">
                        <div class="filter-group">
                            <label for="start-date" class="filter-label">
                                {{ __('finance.start_date') }}
                            </label>
                            <input type="date" id="start-date" class="form-input">
                        </div>
                        <div class="filter-group">
                            <label for="end-date" class="filter-label">
                                {{ __('finance.end_date') }}
                            </label>
                            <input type="date" id="end-date" class="form-input">
                        </div>
                    </div>
                </div>

                <!-- Filter Actions -->
                <div class="filter-actions">
                    <button type="button" class="btn btn-primary apply-filters">
                        {{ __('finance.apply_filters') }}
                    </button>
                    <button type="button" class="btn btn-secondary clear-filters">
                        {{ __('finance.clear_filters') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Expenses Table -->
    <div class="card expenses-table-container">
        <div class="card-header">
            <h3 class="card-title">{{ __('finance.expense_records') }}</h3>
            <div class="table-actions">
                <div class="view-toggle">
                    <button type="button" class="btn btn-secondary view-toggle-btn active" data-view="table">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18m-9 8h9"></path>
                        </svg>
                        {{ __('finance.table_view') }}
                    </button>
                    <button type="button" class="btn btn-secondary view-toggle-btn" data-view="cards">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        {{ __('finance.card_view') }}
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Table View -->
            <div class="table-view" id="table-view">
                <div class="table-wrapper">
                    <table class="expenses-table" role="table" aria-label="{{ __('finance.expense_records') }}">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('finance.date') }}</th>
                                <th scope="col">{{ __('finance.description') }}</th>
                                <th scope="col">{{ __('finance.category') }}</th>
                                <th scope="col">{{ __('finance.amount') }}</th>
                                <th scope="col">{{ __('finance.status') }}</th>
                                <th scope="col">{{ __('finance.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="expenses-table-body">
                            <!-- Loading skeleton rows -->
                            <tr class="loading-row">
                                <td><div class="skeleton-text"></div></td>
                                <td><div class="skeleton-text"></div></td>
                                <td><div class="skeleton-badge"></div></td>
                                <td><div class="skeleton-text"></div></td>
                                <td><div class="skeleton-badge"></div></td>
                                <td><div class="skeleton-actions"></div></td>
                            </tr>
                            <tr class="loading-row">
                                <td><div class="skeleton-text"></div></td>
                                <td><div class="skeleton-text"></div></td>
                                <td><div class="skeleton-badge"></div></td>
                                <td><div class="skeleton-text"></div></td>
                                <td><div class="skeleton-badge"></div></td>
                                <td><div class="skeleton-actions"></div></td>
                            </tr>
                            <tr class="loading-row">
                                <td><div class="skeleton-text"></div></td>
                                <td><div class="skeleton-text"></div></td>
                                <td><div class="skeleton-badge"></div></td>
                                <td><div class="skeleton-text"></div></td>
                                <td><div class="skeleton-badge"></div></td>
                                <td><div class="skeleton-actions"></div></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Card View -->
            <div class="card-view" id="card-view" style="display: none;">
                <div class="expense-cards-grid">
                    <!-- Expense cards will be loaded here -->
                </div>
            </div>

            <!-- Empty State -->
            <div class="empty-state" style="display: none;">
                <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3>{{ __('finance.no_expenses_found') }}</h3>
                <p>{{ __('finance.no_expenses_description') }}</p>
                <button type="button" class="btn btn-primary add-expense-btn">
                    {{ __('finance.add_first_expense') }}
                </button>
            </div>

            <!-- Error State -->
            <div class="error-state" style="display: none;">
                <svg class="error-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3>{{ __('finance.error_loading') }}</h3>
                <p>{{ __('finance.error_description') }}</p>
                <button type="button" class="btn btn-primary retry-btn">
                    {{ __('finance.retry') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Expense Modal -->
<div class="expense-modal" id="expense-modal" style="display: none;" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modal-title" class="modal-title">{{ __('finance.add_expense') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('finance.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form class="expense-form" id="expense-form">
                <div class="form-grid grid grid-cols-1 md:grid-cols-2">
                    <!-- Description -->
                    <div class="form-group md:col-span-2">
                        <label for="expense-description" class="form-label required">
                            {{ __('finance.description') }}
                        </label>
                        <input type="text" id="expense-description" name="description" class="form-input" required
                               placeholder="{{ __('finance.expense_description_placeholder') }}">
                    </div>

                    <!-- Amount -->
                    <div class="form-group">
                        <label for="expense-amount" class="form-label required">
                            {{ __('finance.amount') }}
                        </label>
                        <div class="input-group">
                            <span class="input-prefix">$</span>
                            <input type="number" id="expense-amount" name="amount" class="form-input" required
                                   placeholder="0.00" step="0.01" min="0">
                        </div>
                    </div>

                    <!-- Date -->
                    <div class="form-group">
                        <label for="expense-date" class="form-label required">
                            {{ __('finance.date') }}
                        </label>
                        <input type="date" id="expense-date" name="date" class="form-input" required>
                    </div>

                    <!-- Category -->
                    <div class="form-group">
                        <label for="expense-category" class="form-label required">
                            {{ __('finance.category') }}
                        </label>
                        <select id="expense-category" name="category" class="form-select" required>
                            <option value="">{{ __('finance.select_category') }}</option>
                            <option value="food-supplies">{{ __('finance.food_supplies') }}</option>
                            <option value="utilities">{{ __('finance.utilities') }}</option>
                            <option value="rent">{{ __('finance.rent') }}</option>
                            <option value="marketing">{{ __('finance.marketing') }}</option>
                            <option value="equipment">{{ __('finance.equipment') }}</option>
                            <option value="maintenance">{{ __('finance.maintenance') }}</option>
                            <option value="other">{{ __('finance.other') }}</option>
                        </select>
                    </div>

                    <!-- Payment Method -->
                    <div class="form-group">
                        <label for="expense-payment-method" class="form-label required">
                            {{ __('finance.payment_method') }}
                        </label>
                        <select id="expense-payment-method" name="payment_method" class="form-select" required>
                            <option value="">{{ __('finance.select_payment_method') }}</option>
                            <option value="cash">{{ __('finance.cash') }}</option>
                            <option value="card">{{ __('finance.card') }}</option>
                            <option value="bank_transfer">{{ __('finance.bank_transfer') }}</option>
                            <option value="check">{{ __('finance.check') }}</option>
                        </select>
                    </div>

                    <!-- Notes -->
                    <div class="form-group md:col-span-2">
                        <label for="expense-notes" class="form-label">
                            {{ __('finance.notes') }}
                        </label>
                        <textarea id="expense-notes" name="notes" class="form-textarea" rows="3"
                                  placeholder="{{ __('finance.expense_notes_placeholder') }}"></textarea>
                    </div>

                    <!-- Receipt Upload -->
                    <div class="form-group md:col-span-2">
                        <label for="expense-receipt" class="form-label">
                            {{ __('finance.receipt') }}
                        </label>
                        <div class="file-upload">
                            <input type="file" id="expense-receipt" name="receipt" class="file-input" accept="image/*,.pdf">
                            <div class="file-upload-area">
                                <svg class="upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p>{{ __('finance.upload_receipt') }}</p>
                                <span class="file-types">{{ __('finance.supported_formats') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary cancel-btn">
                {{ __('finance.cancel') }}
            </button>
            <button type="submit" form="expense-form" class="btn btn-primary save-btn">
                {{ __('finance.save_expense') }}
            </button>
        </div>
    </div>
</div>
@endsection
