@extends('layouts.admin')

@section('title', __('finance.settings.title') . ' - ' . config('app.name'))
@section('page_title', __('finance.settings.title'))

@push('styles')
    @vite('resources/css/admin/finance-settings.css')
@endpush

@push('scripts')
    @vite('resources/js/admin/finance-settings.js')
@endpush

@section('content')
<div class="finance-settings-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('finance.settings.title') }}</h1>
                <p class="page-subtitle">{{ __('finance.settings.subtitle') }}</p>
            </div>
            <div class="page-actions">
                <button type="button" class="btn btn-secondary export-settings-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ __('finance.settings.export_settings') }}
                </button>
                <button type="button" class="btn btn-primary save-all-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ __('finance.settings.save_all_changes') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Settings Navigation Tabs -->
    <div class="settings-tabs-container">
        <div class="settings-tabs" x-data="{ activeTab: 'expense-categories' }">
            <!-- Tab Navigation -->
            <div class="tab-nav">
                <button @click="activeTab = 'expense-categories'" 
                        :class="{ 'active': activeTab === 'expense-categories' }"
                        class="tab-button">
                    <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    {{ __('finance.settings.expense_categories.title') }}
                </button>
                
                <button @click="activeTab = 'expense-methods'" 
                        :class="{ 'active': activeTab === 'expense-methods' }"
                        class="tab-button">
                    <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    {{ __('finance.settings.expense_methods.title') }}
                </button>
                
                <button @click="activeTab = 'tax-tip-rules'" 
                        :class="{ 'active': activeTab === 'tax-tip-rules' }"
                        class="tab-button">
                    <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ __('finance.settings.tax_tip_rules.title') }}
                </button>
                
                <button @click="activeTab = 'finance-defaults'" 
                        :class="{ 'active': activeTab === 'finance-defaults' }"
                        class="tab-button">
                    <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ __('finance.settings.finance_defaults.title') }}
                </button>
            </div>

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Expense Categories Tab -->
                <div x-show="activeTab === 'expense-categories'" class="tab-panel">
                    <div class="card">
                        <div class="card-header">
                            <div class="header-content">
                                <h3 class="card-title">{{ __('finance.settings.expense_categories.title') }}</h3>
                                <p class="card-subtitle">{{ __('finance.settings.expense_categories.subtitle') }}</p>
                            </div>
                            <button type="button" class="btn btn-primary add-category-btn">
                                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                {{ __('finance.settings.expense_categories.create') }}
                            </button>
                        </div>
                        <div class="card-body">
                            <!-- Categories Grid -->
                            <div class="categories-grid" id="categories-grid">
                                <!-- Loading skeleton -->
                                <div class="category-card loading">
                                    <div class="skeleton-header"></div>
                                    <div class="skeleton-content"></div>
                                    <div class="skeleton-actions"></div>
                                </div>
                                <div class="category-card loading">
                                    <div class="skeleton-header"></div>
                                    <div class="skeleton-content"></div>
                                    <div class="skeleton-actions"></div>
                                </div>
                                <div class="category-card loading">
                                    <div class="skeleton-header"></div>
                                    <div class="skeleton-content"></div>
                                    <div class="skeleton-actions"></div>
                                </div>
                            </div>

                            <!-- Empty State -->
                            <div class="empty-state" style="display: none;">
                                <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                <h3>{{ __('finance.settings.no_categories_found') }}</h3>
                                <p>{{ __('finance.settings.no_categories_description') }}</p>
                                <button type="button" class="btn btn-primary add-category-btn">
                                    {{ __('finance.settings.create_first_category') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Expense Methods Tab -->
                <div x-show="activeTab === 'expense-methods'" class="tab-panel">
                    <div class="card">
                        <div class="card-header">
                            <div class="header-content">
                                <h3 class="card-title">{{ __('finance.settings.expense_methods.title') }}</h3>
                                <p class="card-subtitle">{{ __('finance.settings.expense_methods.subtitle') }}</p>
                            </div>
                            <button type="button" class="btn btn-primary add-method-btn">
                                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                {{ __('finance.settings.expense_methods.create') }}
                            </button>
                        </div>
                        <div class="card-body">
                            <!-- Methods Table -->
                            <div class="methods-table-wrapper">
                                <table class="methods-table" role="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">{{ __('finance.settings.expense_methods.name') }}</th>
                                            <th scope="col">{{ __('finance.settings.expense_methods.type') }}</th>
                                            <th scope="col">{{ __('finance.settings.expense_methods.account') }}</th>
                                            <th scope="col">{{ __('finance.settings.status') }}</th>
                                            <th scope="col">{{ __('finance.settings.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="methods-table-body">
                                        <!-- Loading skeleton rows -->
                                        <tr class="loading-row">
                                            <td><div class="skeleton-text"></div></td>
                                            <td><div class="skeleton-badge"></div></td>
                                            <td><div class="skeleton-text"></div></td>
                                            <td><div class="skeleton-badge"></div></td>
                                            <td><div class="skeleton-actions"></div></td>
                                        </tr>
                                        <tr class="loading-row">
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
                    </div>
                </div>

                <!-- Tax/Tip Rules Tab -->
                <div x-show="activeTab === 'tax-tip-rules'" class="tab-panel">
                    <div class="card">
                        <div class="card-header">
                            <div class="header-content">
                                <h3 class="card-title">{{ __('finance.settings.tax_tip_rules.title') }}</h3>
                                <p class="card-subtitle">{{ __('finance.settings.tax_tip_rules.subtitle') }}</p>
                            </div>
                            <div class="coming-soon-badge">
                                {{ __('finance.settings.coming_soon') }}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="coming-soon-content">
                                <svg class="coming-soon-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <h3>{{ __('finance.settings.tax_tip_rules.coming_soon_title') }}</h3>
                                <p>{{ __('finance.settings.tax_tip_rules.coming_soon_description') }}</p>
                                
                                <!-- Preview of future features -->
                                <div class="feature-preview">
                                    <h4>{{ __('finance.settings.planned_features') }}</h4>
                                    <ul class="feature-list">
                                        <li>{{ __('finance.settings.tax_tip_rules.tax_rate') }}</li>
                                        <li>{{ __('finance.settings.tax_tip_rules.tip_rate') }}</li>
                                        <li>{{ __('finance.settings.tax_tip_rules.auto_calculate') }}</li>
                                        <li>{{ __('finance.settings.tax_tip_rules.regional_settings') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Finance Defaults Tab -->
                <div x-show="activeTab === 'finance-defaults'" class="tab-panel">
                    <div class="card">
                        <div class="card-header">
                            <div class="header-content">
                                <h3 class="card-title">{{ __('finance.settings.finance_defaults.title') }}</h3>
                                <p class="card-subtitle">{{ __('finance.settings.finance_defaults.subtitle') }}</p>
                            </div>
                            <button type="button" class="btn btn-primary save-defaults-btn">
                                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ __('finance.settings.save_changes') }}
                            </button>
                        </div>
                        <div class="card-body">
                            <form class="defaults-form" id="defaults-form">
                                <div class="form-sections">
                                    <!-- General Settings -->
                                    <div class="form-section">
                                        <h4 class="section-title">{{ __('finance.settings.general_settings') }}</h4>
                                        <div class="form-grid grid grid-cols-1 md:grid-cols-2">
                                            <div class="form-group">
                                                <label for="default-currency" class="form-label">
                                                    {{ __('finance.settings.finance_defaults.default_currency') }}
                                                </label>
                                                <select id="default-currency" name="default_currency" class="form-select">
                                                    <option value="USD">USD - US Dollar</option>
                                                    <option value="EUR">EUR - Euro</option>
                                                    <option value="ETB" selected>ETB - Ethiopian Birr</option>
                                                    <option value="GBP">GBP - British Pound</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="fiscal-year-start" class="form-label">
                                                    {{ __('finance.settings.finance_defaults.fiscal_year_start') }}
                                                </label>
                                                <select id="fiscal-year-start" name="fiscal_year_start" class="form-select">
                                                    <option value="january">{{ __('finance.settings.january') }}</option>
                                                    <option value="april">{{ __('finance.settings.april') }}</option>
                                                    <option value="july" selected>{{ __('finance.settings.july') }}</option>
                                                    <option value="october">{{ __('finance.settings.october') }}</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="decimal-places" class="form-label">
                                                    {{ __('finance.settings.finance_defaults.decimal_places') }}
                                                </label>
                                                <select id="decimal-places" name="decimal_places" class="form-select">
                                                    <option value="0">0 (1234)</option>
                                                    <option value="2" selected>2 (1234.56)</option>
                                                    <option value="3">3 (1234.567)</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="date-format" class="form-label">
                                                    {{ __('finance.settings.finance_defaults.date_format') }}
                                                </label>
                                                <select id="date-format" name="date_format" class="form-select">
                                                    <option value="MM/DD/YYYY">MM/DD/YYYY</option>
                                                    <option value="DD/MM/YYYY" selected>DD/MM/YYYY</option>
                                                    <option value="YYYY-MM-DD">YYYY-MM-DD</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Report Settings -->
                                    <div class="form-section">
                                        <h4 class="section-title">{{ __('finance.settings.report_settings') }}</h4>
                                        <div class="form-grid grid grid-cols-1 md:grid-cols-2">
                                            <div class="form-group">
                                                <label for="default-report-format" class="form-label">
                                                    {{ __('finance.settings.default_report_format') }}
                                                </label>
                                                <select id="default-report-format" name="default_report_format" class="form-select">
                                                    <option value="pdf" selected>PDF</option>
                                                    <option value="excel">Excel</option>
                                                    <option value="csv">CSV</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="auto-backup" class="form-label">
                                                    {{ __('finance.settings.auto_backup_frequency') }}
                                                </label>
                                                <select id="auto-backup" name="auto_backup" class="form-select">
                                                    <option value="daily">{{ __('finance.settings.daily') }}</option>
                                                    <option value="weekly" selected>{{ __('finance.settings.weekly') }}</option>
                                                    <option value="monthly">{{ __('finance.settings.monthly') }}</option>
                                                    <option value="disabled">{{ __('finance.settings.disabled') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Notification Settings -->
                                    <div class="form-section">
                                        <h4 class="section-title">{{ __('finance.settings.notification_settings') }}</h4>
                                        <div class="checkbox-group">
                                            <label class="checkbox-item">
                                                <input type="checkbox" name="notify_low_budget" value="1" checked>
                                                <span class="checkmark"></span>
                                                {{ __('finance.settings.notify_low_budget') }}
                                            </label>
                                            <label class="checkbox-item">
                                                <input type="checkbox" name="notify_expense_approval" value="1" checked>
                                                <span class="checkmark"></span>
                                                {{ __('finance.settings.notify_expense_approval') }}
                                            </label>
                                            <label class="checkbox-item">
                                                <input type="checkbox" name="notify_report_ready" value="1">
                                                <span class="checkmark"></span>
                                                {{ __('finance.settings.notify_report_ready') }}
                                            </label>
                                            <label class="checkbox-item">
                                                <input type="checkbox" name="notify_monthly_summary" value="1" checked>
                                                <span class="checkmark"></span>
                                                {{ __('finance.settings.notify_monthly_summary') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Category Modal -->
<div class="category-modal" id="category-modal" style="display: none;" role="dialog" aria-labelledby="category-modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="category-modal-title" class="modal-title">{{ __('finance.settings.expense_categories.create') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('finance.settings.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form class="category-form" id="category-form">
                <div class="form-grid grid grid-cols-1 md:grid-cols-2">
                    <!-- Category Name -->
                    <div class="form-group md:col-span-2">
                        <label for="category-name" class="form-label required">
                            {{ __('finance.settings.expense_categories.name') }}
                        </label>
                        <input type="text" id="category-name" name="name" class="form-input" required
                               placeholder="{{ __('finance.settings.category_name_placeholder') }}">
                    </div>

                    <!-- Category Color -->
                    <div class="form-group">
                        <label for="category-color" class="form-label">
                            {{ __('finance.settings.expense_categories.color') }}
                        </label>
                        <div class="color-picker-group">
                            <input type="color" id="category-color" name="color" class="color-input" value="#3b82f6">
                            <div class="color-presets">
                                <button type="button" class="color-preset" data-color="#ef4444" style="background: #ef4444;"></button>
                                <button type="button" class="color-preset" data-color="#f59e0b" style="background: #f59e0b;"></button>
                                <button type="button" class="color-preset" data-color="#10b981" style="background: #10b981;"></button>
                                <button type="button" class="color-preset" data-color="#3b82f6" style="background: #3b82f6;"></button>
                                <button type="button" class="color-preset" data-color="#8b5cf6" style="background: #8b5cf6;"></button>
                                <button type="button" class="color-preset" data-color="#ec4899" style="background: #ec4899;"></button>
                            </div>
                        </div>
                    </div>

                    <!-- Category Status -->
                    <div class="form-group">
                        <label class="form-label">{{ __('finance.settings.status') }}</label>
                        <div class="toggle-group">
                            <label class="toggle-switch">
                                <input type="checkbox" name="active" value="1" checked>
                                <span class="toggle-slider"></span>
                                <span class="toggle-label">{{ __('finance.settings.expense_categories.active') }}</span>
                            </label>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="form-group md:col-span-2">
                        <label for="category-description" class="form-label">
                            {{ __('finance.settings.expense_categories.description') }}
                        </label>
                        <textarea id="category-description" name="description" class="form-textarea" rows="3"
                                  placeholder="{{ __('finance.settings.category_description_placeholder') }}"></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary cancel-btn">
                {{ __('finance.settings.cancel') }}
            </button>
            <button type="submit" form="category-form" class="btn btn-primary save-btn">
                {{ __('finance.settings.save_category') }}
            </button>
        </div>
    </div>
</div>

<!-- Add/Edit Method Modal -->
<div class="method-modal" id="method-modal" style="display: none;" role="dialog" aria-labelledby="method-modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="method-modal-title" class="modal-title">{{ __('finance.settings.expense_methods.create') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('finance.settings.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form class="method-form" id="method-form">
                <div class="form-grid grid grid-cols-1 md:grid-cols-2">
                    <!-- Method Name -->
                    <div class="form-group md:col-span-2">
                        <label for="method-name" class="form-label required">
                            {{ __('finance.settings.expense_methods.name') }}
                        </label>
                        <input type="text" id="method-name" name="name" class="form-input" required
                               placeholder="{{ __('finance.settings.method_name_placeholder') }}">
                    </div>

                    <!-- Method Type -->
                    <div class="form-group">
                        <label for="method-type" class="form-label required">
                            {{ __('finance.settings.expense_methods.type') }}
                        </label>
                        <select id="method-type" name="type" class="form-select" required>
                            <option value="">{{ __('finance.settings.select_type') }}</option>
                            <option value="cash">{{ __('finance.settings.cash') }}</option>
                            <option value="bank">{{ __('finance.settings.bank_transfer') }}</option>
                            <option value="card">{{ __('finance.settings.credit_card') }}</option>
                            <option value="check">{{ __('finance.settings.check') }}</option>
                            <option value="other">{{ __('finance.settings.other') }}</option>
                        </select>
                    </div>

                    <!-- Account -->
                    <div class="form-group">
                        <label for="method-account" class="form-label">
                            {{ __('finance.settings.expense_methods.account') }}
                        </label>
                        <input type="text" id="method-account" name="account" class="form-input"
                               placeholder="{{ __('finance.settings.account_placeholder') }}">
                    </div>

                    <!-- Status -->
                    <div class="form-group md:col-span-2">
                        <label class="form-label">{{ __('finance.settings.status') }}</label>
                        <div class="toggle-group">
                            <label class="toggle-switch">
                                <input type="checkbox" name="active" value="1" checked>
                                <span class="toggle-slider"></span>
                                <span class="toggle-label">{{ __('finance.settings.expense_methods.active') }}</span>
                            </label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary cancel-btn">
                {{ __('finance.settings.cancel') }}
            </button>
            <button type="submit" form="method-form" class="btn btn-primary save-btn">
                {{ __('finance.settings.save_method') }}
            </button>
        </div>
    </div>
</div>
@endsection
