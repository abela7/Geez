@extends('layouts.admin')

@section('title', __('finance.budgeting.title') . ' - ' . config('app.name'))
@section('page_title', __('finance.budgeting.title'))

@push('styles')
    @vite('resources/css/admin/budgeting.css')
@endpush

@push('scripts')
    @vite('resources/js/admin/budgeting.js')
@endpush

@section('content')
<div class="budgeting-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('finance.budgeting.title') }}</h1>
                <p class="page-subtitle">{{ __('finance.budgeting.subtitle') }}</p>
            </div>
            <div class="page-actions">
                <button type="button" class="btn btn-secondary export-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ __('finance.budgeting.export_budget') }}
                </button>
                <button type="button" class="btn btn-primary create-budget-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    {{ __('finance.budgeting.create_budget') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Budget Overview Cards -->
    <div class="budget-overview-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <!-- Total Budget Card -->
        <div class="card budget-overview-card" data-overview="total-budget">
            <div class="card-body">
                <div class="overview-header">
                    <div class="overview-icon">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <span class="overview-label">{{ __('finance.budgeting.total_budget') }}</span>
                </div>
                <div class="overview-value loading-skeleton" data-value="total-budget">$0.00</div>
                <div class="overview-meta">
                    <span class="budget-period">{{ __('finance.budgeting.current_period') }}</span>
                </div>
            </div>
        </div>

        <!-- Spent Amount Card -->
        <div class="card budget-overview-card" data-overview="spent-amount">
            <div class="card-body">
                <div class="overview-header">
                    <div class="overview-icon">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                        </svg>
                    </div>
                    <span class="overview-label">{{ __('finance.budgeting.spent_amount') }}</span>
                </div>
                <div class="overview-value loading-skeleton" data-value="spent-amount">$0.00</div>
                <div class="overview-progress">
                    <div class="progress-bar">
                        <div class="progress-fill" data-progress="0"></div>
                    </div>
                    <span class="progress-text">0% {{ __('finance.budgeting.of_budget') }}</span>
                </div>
            </div>
        </div>

        <!-- Remaining Budget Card -->
        <div class="card budget-overview-card" data-overview="remaining-budget">
            <div class="card-body">
                <div class="overview-header">
                    <div class="overview-icon">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <span class="overview-label">{{ __('finance.budgeting.remaining_budget') }}</span>
                </div>
                <div class="overview-value loading-skeleton" data-value="remaining-budget">$0.00</div>
                <div class="overview-change positive">
                    <svg class="change-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7H7"></path>
                    </svg>
                    <span>{{ __('finance.budgeting.days_remaining') }}: 0</span>
                </div>
            </div>
        </div>

        <!-- Budget Variance Card -->
        <div class="card budget-overview-card" data-overview="budget-variance">
            <div class="card-body">
                <div class="overview-header">
                    <div class="overview-icon">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <span class="overview-label">{{ __('finance.budgeting.budget_variance') }}</span>
                </div>
                <div class="overview-value loading-skeleton" data-value="budget-variance">$0.00</div>
                <div class="overview-meta">
                    <span class="variance-status">{{ __('finance.budgeting.on_track') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Budget Period Selector -->
    <div class="card budget-period-selector mb-6">
        <div class="card-body">
            <div class="period-selector-content">
                <div class="period-info">
                    <h3 class="period-title">{{ __('finance.budgeting.budget_period') }}</h3>
                    <p class="period-description">{{ __('finance.budgeting.select_period_description') }}</p>
                </div>
                <div class="period-controls">
                    <select id="budget-period" class="form-select">
                        <option value="current-month">{{ __('finance.budgeting.current_month') }}</option>
                        <option value="current-quarter">{{ __('finance.budgeting.current_quarter') }}</option>
                        <option value="current-year">{{ __('finance.budgeting.current_year') }}</option>
                        <option value="custom">{{ __('finance.budgeting.custom_period') }}</option>
                    </select>
                    <div class="custom-period-inputs" style="display: none;">
                        <input type="date" id="period-start" class="form-input" placeholder="{{ __('finance.budgeting.start_date') }}">
                        <input type="date" id="period-end" class="form-input" placeholder="{{ __('finance.budgeting.end_date') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Budget Categories -->
    <div class="card budget-categories-container">
        <div class="card-header">
            <h3 class="card-title">{{ __('finance.budgeting.budget_categories') }}</h3>
            <div class="budget-actions">
                <div class="view-toggle">
                    <button type="button" class="btn btn-secondary view-toggle-btn active" data-view="categories">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        {{ __('finance.budgeting.categories_view') }}
                    </button>
                    <button type="button" class="btn btn-secondary view-toggle-btn" data-view="comparison">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        {{ __('finance.budgeting.comparison_view') }}
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Categories View -->
            <div class="categories-view" id="categories-view">
                <div class="budget-categories-grid">
                    <!-- Budget categories will be loaded here -->
                    <div class="loading-skeleton-grid">
                        <div class="budget-category-card loading">
                            <div class="skeleton-header"></div>
                            <div class="skeleton-content"></div>
                            <div class="skeleton-progress"></div>
                        </div>
                        <div class="budget-category-card loading">
                            <div class="skeleton-header"></div>
                            <div class="skeleton-content"></div>
                            <div class="skeleton-progress"></div>
                        </div>
                        <div class="budget-category-card loading">
                            <div class="skeleton-header"></div>
                            <div class="skeleton-content"></div>
                            <div class="skeleton-progress"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comparison View -->
            <div class="comparison-view" id="comparison-view" style="display: none;">
                <div class="budget-comparison-table-wrapper">
                    <table class="budget-comparison-table" role="table" aria-label="{{ __('finance.budgeting.budget_vs_actual') }}">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('finance.budgeting.category') }}</th>
                                <th scope="col">{{ __('finance.budgeting.budgeted_amount') }}</th>
                                <th scope="col">{{ __('finance.budgeting.actual_spent') }}</th>
                                <th scope="col">{{ __('finance.budgeting.variance') }}</th>
                                <th scope="col">{{ __('finance.budgeting.progress') }}</th>
                                <th scope="col">{{ __('finance.budgeting.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="budget-comparison-body">
                            <!-- Loading skeleton rows -->
                            <tr class="loading-row">
                                <td><div class="skeleton-text"></div></td>
                                <td><div class="skeleton-text"></div></td>
                                <td><div class="skeleton-text"></div></td>
                                <td><div class="skeleton-badge"></div></td>
                                <td><div class="skeleton-progress"></div></td>
                                <td><div class="skeleton-actions"></div></td>
                            </tr>
                            <tr class="loading-row">
                                <td><div class="skeleton-text"></div></td>
                                <td><div class="skeleton-text"></div></td>
                                <td><div class="skeleton-text"></div></td>
                                <td><div class="skeleton-badge"></div></td>
                                <td><div class="skeleton-progress"></div></td>
                                <td><div class="skeleton-actions"></div></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Empty State -->
            <div class="empty-state" style="display: none;">
                <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3>{{ __('finance.budgeting.no_budgets_found') }}</h3>
                <p>{{ __('finance.budgeting.no_budgets_description') }}</p>
                <button type="button" class="btn btn-primary create-budget-btn">
                    {{ __('finance.budgeting.create_first_budget') }}
                </button>
            </div>

            <!-- Error State -->
            <div class="error-state" style="display: none;">
                <svg class="error-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3>{{ __('finance.budgeting.error_loading') }}</h3>
                <p>{{ __('finance.budgeting.error_description') }}</p>
                <button type="button" class="btn btn-primary retry-btn">
                    {{ __('finance.budgeting.retry') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Create/Edit Budget Modal -->
<div class="budget-modal" id="budget-modal" style="display: none;" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modal-title" class="modal-title">{{ __('finance.budgeting.create_budget') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('finance.budgeting.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form class="budget-form" id="budget-form">
                <div class="form-grid grid grid-cols-1 md:grid-cols-2">
                    <!-- Budget Name -->
                    <div class="form-group md:col-span-2">
                        <label for="budget-name" class="form-label required">
                            {{ __('finance.budgeting.budget_name') }}
                        </label>
                        <input type="text" id="budget-name" name="name" class="form-input" required
                               placeholder="{{ __('finance.budgeting.budget_name_placeholder') }}">
                    </div>

                    <!-- Budget Period -->
                    <div class="form-group">
                        <label for="budget-period-type" class="form-label required">
                            {{ __('finance.budgeting.period_type') }}
                        </label>
                        <select id="budget-period-type" name="period_type" class="form-select" required>
                            <option value="">{{ __('finance.budgeting.select_period_type') }}</option>
                            <option value="monthly">{{ __('finance.budgeting.monthly') }}</option>
                            <option value="quarterly">{{ __('finance.budgeting.quarterly') }}</option>
                            <option value="yearly">{{ __('finance.budgeting.yearly') }}</option>
                            <option value="custom">{{ __('finance.budgeting.custom') }}</option>
                        </select>
                    </div>

                    <!-- Total Budget Amount -->
                    <div class="form-group">
                        <label for="budget-total-amount" class="form-label required">
                            {{ __('finance.budgeting.total_amount') }}
                        </label>
                        <div class="input-group">
                            <span class="input-prefix">$</span>
                            <input type="number" id="budget-total-amount" name="total_amount" class="form-input" required
                                   placeholder="0.00" step="0.01" min="0">
                        </div>
                    </div>

                    <!-- Start Date -->
                    <div class="form-group">
                        <label for="budget-start-date" class="form-label required">
                            {{ __('finance.budgeting.start_date') }}
                        </label>
                        <input type="date" id="budget-start-date" name="start_date" class="form-input" required>
                    </div>

                    <!-- End Date -->
                    <div class="form-group">
                        <label for="budget-end-date" class="form-label required">
                            {{ __('finance.budgeting.end_date') }}
                        </label>
                        <input type="date" id="budget-end-date" name="end_date" class="form-input" required>
                    </div>

                    <!-- Description -->
                    <div class="form-group md:col-span-2">
                        <label for="budget-description" class="form-label">
                            {{ __('finance.budgeting.description') }}
                        </label>
                        <textarea id="budget-description" name="description" class="form-textarea" rows="3"
                                  placeholder="{{ __('finance.budgeting.description_placeholder') }}"></textarea>
                    </div>
                </div>

                <!-- Budget Categories Section -->
                <div class="budget-categories-section">
                    <div class="section-header">
                        <h3 class="section-title">{{ __('finance.budgeting.budget_allocation') }}</h3>
                        <p class="section-description">{{ __('finance.budgeting.allocation_description') }}</p>
                    </div>
                    
                    <div class="budget-allocation-grid" id="budget-allocation-grid">
                        <!-- Budget allocation items will be added here -->
                    </div>

                    <button type="button" class="btn btn-secondary add-category-btn">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        {{ __('finance.budgeting.add_category') }}
                    </button>

                    <div class="allocation-summary">
                        <div class="summary-item">
                            <span class="summary-label">{{ __('finance.budgeting.total_allocated') }}:</span>
                            <span class="summary-value" id="total-allocated">$0.00</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">{{ __('finance.budgeting.remaining_budget') }}:</span>
                            <span class="summary-value" id="remaining-allocation">$0.00</span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary cancel-btn">
                {{ __('finance.budgeting.cancel') }}
            </button>
            <button type="submit" form="budget-form" class="btn btn-primary save-btn">
                {{ __('finance.budgeting.save_budget') }}
            </button>
        </div>
    </div>
</div>

<!-- Budget Category Allocation Template -->
<template id="budget-allocation-template">
    <div class="budget-allocation-item">
        <div class="allocation-controls">
            <select class="form-select category-select" name="categories[]" required>
                <option value="">{{ __('finance.budgeting.select_category') }}</option>
                <option value="food-supplies">{{ __('finance.budgeting.food_supplies') }}</option>
                <option value="utilities">{{ __('finance.budgeting.utilities') }}</option>
                <option value="rent">{{ __('finance.budgeting.rent') }}</option>
                <option value="marketing">{{ __('finance.budgeting.marketing') }}</option>
                <option value="equipment">{{ __('finance.budgeting.equipment') }}</option>
                <option value="maintenance">{{ __('finance.budgeting.maintenance') }}</option>
                <option value="other">{{ __('finance.budgeting.other') }}</option>
            </select>
            <div class="input-group">
                <span class="input-prefix">$</span>
                <input type="number" class="form-input amount-input" name="amounts[]" 
                       placeholder="0.00" step="0.01" min="0" required>
            </div>
            <button type="button" class="btn btn-danger remove-category-btn">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </div>
    </div>
</template>
@endsection
