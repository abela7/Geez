@extends('layouts.admin')

@section('title', __('finance.tips.title') . ' - ' . config('app.name'))
@section('page_title', __('finance.tips.title'))

@push('styles')
    @vite('resources/css/admin/finance/tips.css')
@endpush

@push('scripts')
    @vite('resources/js/admin/finance/tips.js')
@endpush

@section('content')
<div class="tips-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('finance.tips.title') }}</h1>
                <p class="page-subtitle">{{ __('finance.tips.subtitle') }}</p>
            </div>
            <div class="page-actions">
                <button type="button" class="btn btn-secondary tip-calculator-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    {{ __('finance.tips.tip_calculator') }}
                </button>
                <button type="button" class="btn btn-secondary export-tips-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ __('finance.tips.export_tips') }}
                </button>
                <button type="button" class="btn btn-primary distribute-tips-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                    {{ __('finance.tips.distribute_tips') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon total">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="total-tips">$0.00</div>
                    <div class="stat-label">{{ __('finance.tips.total_tips_today') }}</div>
                    <div class="stat-change positive" id="tips-change">+15.3%</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon pending">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="pending-distribution">$0.00</div>
                    <div class="stat-label">{{ __('finance.tips.pending_distribution') }}</div>
                    <div class="stat-note" id="pending-count">0 {{ __('finance.tips.transactions') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon distributed">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="distributed-tips">$0.00</div>
                    <div class="stat-label">{{ __('finance.tips.distributed_today') }}</div>
                    <div class="stat-note" id="staff-count">0 {{ __('finance.tips.staff_members') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon average">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="avg-tip-per-staff">$0.00</div>
                    <div class="stat-label">{{ __('finance.tips.avg_per_staff') }}</div>
                    <div class="stat-note" id="hourly-rate">{{ __('finance.tips.per_hour') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tip Distribution Rules -->
    <div class="rules-section">
        <div class="section-header">
            <h3 class="section-title">{{ __('finance.tips.distribution_rules') }}</h3>
            <button type="button" class="btn btn-secondary edit-rules-btn">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                {{ __('finance.tips.edit_rules') }}
            </button>
        </div>
        
        <div class="rules-cards">
            <div class="rule-card active" data-rule="direct">
                <div class="rule-header">
                    <div class="rule-icon direct">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h4 class="rule-title">{{ __('finance.tips.direct_to_server') }}</h4>
                    <div class="rule-status">{{ __('finance.tips.active') }}</div>
                </div>
                <div class="rule-body">
                    <p class="rule-description">{{ __('finance.tips.direct_description') }}</p>
                    <div class="rule-details">
                        <span class="rule-percentage">100%</span>
                        <span class="rule-text">{{ __('finance.tips.to_receiver') }}</span>
                    </div>
                </div>
            </div>

            <div class="rule-card" data-rule="shared">
                <div class="rule-header">
                    <div class="rule-icon shared">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h4 class="rule-title">{{ __('finance.tips.shared_equally') }}</h4>
                    <div class="rule-status">{{ __('finance.tips.inactive') }}</div>
                </div>
                <div class="rule-body">
                    <p class="rule-description">{{ __('finance.tips.shared_description') }}</p>
                    <div class="rule-breakdown">
                        <div class="breakdown-item">
                            <span class="breakdown-label">{{ __('finance.tips.front_of_house') }}</span>
                            <span class="breakdown-value">60%</span>
                        </div>
                        <div class="breakdown-item">
                            <span class="breakdown-label">{{ __('finance.tips.kitchen_staff') }}</span>
                            <span class="breakdown-value">40%</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rule-card" data-rule="custom">
                <div class="rule-header">
                    <div class="rule-icon custom">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        </svg>
                    </div>
                    <h4 class="rule-title">{{ __('finance.tips.custom_distribution') }}</h4>
                    <div class="rule-status">{{ __('finance.tips.inactive') }}</div>
                </div>
                <div class="rule-body">
                    <p class="rule-description">{{ __('finance.tips.custom_description') }}</p>
                    <div class="rule-breakdown">
                        <div class="breakdown-item">
                            <span class="breakdown-label">{{ __('finance.tips.servers') }}</span>
                            <span class="breakdown-value">45%</span>
                        </div>
                        <div class="breakdown-item">
                            <span class="breakdown-label">{{ __('finance.tips.bartenders') }}</span>
                            <span class="breakdown-value">25%</span>
                        </div>
                        <div class="breakdown-item">
                            <span class="breakdown-label">{{ __('finance.tips.kitchen') }}</span>
                            <span class="breakdown-value">20%</span>
                        </div>
                        <div class="breakdown-item">
                            <span class="breakdown-label">{{ __('finance.tips.management') }}</span>
                            <span class="breakdown-value">10%</span>
                        </div>
                    </div>
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
                <button type="button" class="period-btn" data-period="custom">{{ __('finance.tips.custom_range') }}</button>
            </div>
            
            <div class="filter-controls">
                <select class="filter-select" id="shift-filter">
                    <option value="">{{ __('finance.tips.all_shifts') }}</option>
                    <option value="morning">{{ __('finance.tips.morning_shift') }}</option>
                    <option value="afternoon">{{ __('finance.tips.afternoon_shift') }}</option>
                    <option value="evening">{{ __('finance.tips.evening_shift') }}</option>
                    <option value="night">{{ __('finance.tips.night_shift') }}</option>
                </select>
                
                <select class="filter-select" id="status-filter">
                    <option value="">{{ __('finance.tips.all_statuses') }}</option>
                    <option value="pending">{{ __('finance.tips.pending') }}</option>
                    <option value="distributed">{{ __('finance.tips.distributed') }}</option>
                    <option value="disputed">{{ __('finance.tips.disputed') }}</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tips Content -->
    <div class="tips-content">
        <!-- Tips Table -->
        <div class="tips-table-section">
            <div class="table-header">
                <h3 class="table-title">{{ __('finance.tips.tip_transactions') }}</h3>
                <div class="table-actions">
                    <button type="button" class="btn btn-sm btn-secondary bulk-distribute-btn">
                        {{ __('finance.tips.bulk_distribute') }}
                    </button>
                </div>
            </div>
            
            <div class="table-card">
                <div class="table-responsive">
                    <table class="tips-table" role="table">
                        <thead>
                            <tr>
                                <th class="table-checkbox">
                                    <input type="checkbox" id="selectAll" class="form-checkbox" onchange="toggleSelectAll()">
                                </th>
                                <th scope="col">{{ __('finance.tips.transaction_id') }}</th>
                                <th scope="col">{{ __('finance.tips.amount') }}</th>
                                <th scope="col">{{ __('finance.tips.payment_method') }}</th>
                                <th scope="col">{{ __('finance.tips.received_by') }}</th>
                                <th scope="col">{{ __('finance.tips.shift') }}</th>
                                <th scope="col">{{ __('finance.tips.time') }}</th>
                                <th scope="col">{{ __('common.status') }}</th>
                                <th scope="col">{{ __('common.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="tips-table-body" id="tips-table-body">
                            <!-- Tips will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Staff Distribution Summary -->
        <div class="distribution-section">
            <div class="section-header">
                <h3 class="section-title">{{ __('finance.tips.staff_distribution') }}</h3>
                <div class="distribution-period" id="distribution-period">{{ __('common.today') }}</div>
            </div>
            
            <div class="staff-distribution-grid" id="staff-distribution-grid">
                <!-- Staff distribution will be populated by JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- Tip Calculator Modal -->
<div class="tip-calculator-modal" id="tip-calculator-modal" style="display: none;" role="dialog" aria-labelledby="calculator-modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content large">
        <div class="modal-header">
            <h2 id="calculator-modal-title" class="modal-title">{{ __('finance.tips.tip_calculator') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('common.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="calculator-sections">
                <!-- Input Section -->
                <div class="calculator-section">
                    <h4 class="calculator-section-title">{{ __('finance.tips.tip_details') }}</h4>
                    <div class="calculator-grid">
                        <div class="form-group">
                            <label for="tip-amount" class="form-label required">{{ __('finance.tips.tip_amount') }}</label>
                            <div class="input-with-prefix">
                                <span class="input-prefix">$</span>
                                <input type="number" id="tip-amount" name="tip_amount" class="form-input" 
                                       min="0" step="0.01" placeholder="0.00" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="payment-method" class="form-label">{{ __('finance.tips.payment_method') }}</label>
                            <select id="payment-method" name="payment_method" class="form-select">
                                <option value="cash">{{ __('finance.tips.cash') }}</option>
                                <option value="card">{{ __('finance.tips.card') }}</option>
                                <option value="mobile">{{ __('finance.tips.mobile_payment') }}</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="received-by" class="form-label">{{ __('finance.tips.received_by') }}</label>
                            <select id="received-by" name="received_by" class="form-select">
                                <option value="">{{ __('finance.tips.select_staff') }}</option>
                                <option value="1">Sarah Johnson (Server)</option>
                                <option value="2">Mike Rodriguez (Bartender)</option>
                                <option value="3">Lisa Chen (Server)</option>
                                <option value="4">James Wilson (Manager)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="distribution-rule" class="form-label">{{ __('finance.tips.distribution_rule') }}</label>
                            <select id="distribution-rule" name="distribution_rule" class="form-select">
                                <option value="direct">{{ __('finance.tips.direct_to_server') }}</option>
                                <option value="shared">{{ __('finance.tips.shared_equally') }}</option>
                                <option value="custom">{{ __('finance.tips.custom_distribution') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Staff Selection Section -->
                <div class="calculator-section" id="staff-selection-section">
                    <h4 class="calculator-section-title">{{ __('finance.tips.staff_on_shift') }}</h4>
                    <div class="staff-grid" id="staff-grid">
                        <!-- Staff will be populated by JavaScript -->
                    </div>
                </div>

                <!-- Distribution Preview -->
                <div class="calculator-section">
                    <h4 class="calculator-section-title">{{ __('finance.tips.distribution_preview') }}</h4>
                    <div class="distribution-preview" id="distribution-preview">
                        <!-- Distribution breakdown will be shown here -->
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary cancel-btn">
                {{ __('common.cancel') }}
            </button>
            <button type="button" class="btn btn-primary process-tip-btn">
                {{ __('finance.tips.process_tip') }}
            </button>
        </div>
    </div>
</div>

<!-- Distribution Rules Modal -->
<div class="rules-modal" id="rules-modal" style="display: none;" role="dialog" aria-labelledby="rules-modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="rules-modal-title" class="modal-title">{{ __('finance.tips.edit_distribution_rules') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('common.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form class="rules-form" id="rules-form">
                <div class="form-tabs">
                    <div class="tab-nav">
                        <button type="button" class="tab-btn active" data-tab="shared">
                            {{ __('finance.tips.shared_rules') }}
                        </button>
                        <button type="button" class="tab-btn" data-tab="custom">
                            {{ __('finance.tips.custom_rules') }}
                        </button>
                    </div>
                    
                    <div class="tab-content">
                        <!-- Shared Rules Tab -->
                        <div class="tab-panel active" data-tab="shared">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="foh-percentage" class="form-label">{{ __('finance.tips.front_of_house_percentage') }}</label>
                                    <div class="input-with-suffix">
                                        <input type="number" id="foh-percentage" name="foh_percentage" class="form-input" 
                                               min="0" max="100" step="1" value="60">
                                        <span class="input-suffix">%</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="kitchen-percentage" class="form-label">{{ __('finance.tips.kitchen_percentage') }}</label>
                                    <div class="input-with-suffix">
                                        <input type="number" id="kitchen-percentage" name="kitchen_percentage" class="form-input" 
                                               min="0" max="100" step="1" value="40">
                                        <span class="input-suffix">%</span>
                                    </div>
                                </div>

                                <div class="form-group full-width">
                                    <div class="percentage-total">
                                        <span class="total-label">{{ __('finance.tips.total_percentage') }}:</span>
                                        <span class="total-value" id="shared-total">100%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Custom Rules Tab -->
                        <div class="tab-panel" data-tab="custom">
                            <div class="custom-rules-grid">
                                <div class="role-group">
                                    <h5 class="role-title">{{ __('finance.tips.servers') }}</h5>
                                    <div class="input-with-suffix">
                                        <input type="number" name="servers_percentage" class="form-input" 
                                               min="0" max="100" step="1" value="45">
                                        <span class="input-suffix">%</span>
                                    </div>
                                </div>

                                <div class="role-group">
                                    <h5 class="role-title">{{ __('finance.tips.bartenders') }}</h5>
                                    <div class="input-with-suffix">
                                        <input type="number" name="bartenders_percentage" class="form-input" 
                                               min="0" max="100" step="1" value="25">
                                        <span class="input-suffix">%</span>
                                    </div>
                                </div>

                                <div class="role-group">
                                    <h5 class="role-title">{{ __('finance.tips.kitchen_staff') }}</h5>
                                    <div class="input-with-suffix">
                                        <input type="number" name="kitchen_percentage" class="form-input" 
                                               min="0" max="100" step="1" value="20">
                                        <span class="input-suffix">%</span>
                                    </div>
                                </div>

                                <div class="role-group">
                                    <h5 class="role-title">{{ __('finance.tips.management') }}</h5>
                                    <div class="input-with-suffix">
                                        <input type="number" name="management_percentage" class="form-input" 
                                               min="0" max="100" step="1" value="10">
                                        <span class="input-suffix">%</span>
                                    </div>
                                </div>

                                <div class="role-group total-group">
                                    <h5 class="role-title">{{ __('finance.tips.total') }}</h5>
                                    <div class="total-display">
                                        <span class="total-percentage" id="custom-total">100%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary cancel-btn">
                {{ __('common.cancel') }}
            </button>
            <button type="submit" form="rules-form" class="btn btn-primary save-btn">
                {{ __('finance.tips.save_rules') }}
            </button>
        </div>
    </div>
</div>
@endsection
