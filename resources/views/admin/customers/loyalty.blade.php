@extends('layouts.admin')

@section('title', __('customers.loyalty.title') . ' - ' . config('app.name'))
@section('page_title', __('customers.loyalty.title'))

@push('styles')
    @vite('resources/css/admin/customer-loyalty.css')
@endpush

@push('scripts')
    @vite('resources/js/admin/customer-loyalty.js')
@endpush

@section('content')
<div class="loyalty-program-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('customers.loyalty.title') }}</h1>
                <p class="page-subtitle">{{ __('customers.loyalty.subtitle') }}</p>
            </div>
            <div class="page-actions">
                <button type="button" class="btn btn-secondary loyalty-settings-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ __('customers.loyalty.program_settings') }}
                </button>
                <button type="button" class="btn btn-secondary export-loyalty-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ __('customers.loyalty.export_data') }}
                </button>
                <button type="button" class="btn btn-primary add-reward-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('customers.loyalty.add_reward') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon total-members">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="total-members">0</div>
                    <div class="stat-label">{{ __('customers.loyalty.total_members') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon active-members">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="active-members">0</div>
                    <div class="stat-label">{{ __('customers.loyalty.active_members') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon points-issued">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="points-issued">0</div>
                    <div class="stat-label">{{ __('customers.loyalty.points_issued') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon rewards-redeemed">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="rewards-redeemed">0</div>
                    <div class="stat-label">{{ __('customers.loyalty.rewards_redeemed') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Tabs -->
    <div class="content-section">
        <div class="loyalty-tabs" x-data="{ activeTab: 'members' }">
            <div class="tab-nav">
                <button type="button" 
                        class="tab-btn" 
                        :class="{ 'active': activeTab === 'members' }"
                        @click="activeTab = 'members'">
                    <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    {{ __('customers.loyalty.members') }}
                </button>
                <button type="button" 
                        class="tab-btn" 
                        :class="{ 'active': activeTab === 'rewards' }"
                        @click="activeTab = 'rewards'">
                    <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ __('customers.loyalty.rewards') }}
                </button>
                <button type="button" 
                        class="tab-btn" 
                        :class="{ 'active': activeTab === 'tiers' }"
                        @click="activeTab = 'tiers'">
                    <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                    {{ __('customers.loyalty.tiers') }}
                </button>
                <button type="button" 
                        class="tab-btn" 
                        :class="{ 'active': activeTab === 'transactions' }"
                        @click="activeTab = 'transactions'">
                    <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    {{ __('customers.loyalty.transactions') }}
                </button>
            </div>

            <!-- Members Tab -->
            <div class="tab-panel" x-show="activeTab === 'members'" x-transition>
                <div class="members-section">
                    <!-- Search and Filters -->
                    <div class="search-filters-container">
                        <div class="search-bar">
                            <div class="search-input-wrapper">
                                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <input type="text" 
                                       class="search-input" 
                                       placeholder="{{ __('customers.loyalty.search_members') }}"
                                       id="members-search">
                            </div>
                        </div>
                        
                        <div class="filters-container">
                            <select class="filter-select" id="tier-filter">
                                <option value="">{{ __('customers.loyalty.all_tiers') }}</option>
                                <option value="bronze">{{ __('customers.loyalty.bronze') }}</option>
                                <option value="silver">{{ __('customers.loyalty.silver') }}</option>
                                <option value="gold">{{ __('customers.loyalty.gold') }}</option>
                                <option value="platinum">{{ __('customers.loyalty.platinum') }}</option>
                            </select>
                            
                            <select class="filter-select" id="status-filter">
                                <option value="">{{ __('customers.loyalty.all_status') }}</option>
                                <option value="active">{{ __('customers.loyalty.active') }}</option>
                                <option value="inactive">{{ __('customers.loyalty.inactive') }}</option>
                            </select>
                            
                            <button type="button" class="btn btn-secondary clear-filters-btn">
                                {{ __('customers.loyalty.clear_filters') }}
                            </button>
                        </div>
                    </div>

                    <!-- Members List -->
                    <div class="members-list" id="members-list">
                        <!-- Members will be populated by JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Rewards Tab -->
            <div class="tab-panel" x-show="activeTab === 'rewards'" x-transition>
                <div class="rewards-section">
                    <div class="rewards-header">
                        <h3 class="section-title">{{ __('customers.loyalty.available_rewards') }}</h3>
                        <button type="button" class="btn btn-primary add-reward-btn">
                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            {{ __('customers.loyalty.add_reward') }}
                        </button>
                    </div>
                    
                    <div class="rewards-grid" id="rewards-grid">
                        <!-- Rewards will be populated by JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Tiers Tab -->
            <div class="tab-panel" x-show="activeTab === 'tiers'" x-transition>
                <div class="tiers-section">
                    <div class="tiers-header">
                        <h3 class="section-title">{{ __('customers.loyalty.membership_tiers') }}</h3>
                        <button type="button" class="btn btn-primary edit-tiers-btn">
                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            {{ __('customers.loyalty.edit_tiers') }}
                        </button>
                    </div>
                    
                    <div class="tiers-grid" id="tiers-grid">
                        <!-- Tiers will be populated by JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Transactions Tab -->
            <div class="tab-panel" x-show="activeTab === 'transactions'" x-transition>
                <div class="transactions-section">
                    <div class="transactions-header">
                        <h3 class="section-title">{{ __('customers.loyalty.recent_transactions') }}</h3>
                        <div class="transaction-filters">
                            <select class="filter-select" id="transaction-type-filter">
                                <option value="">{{ __('customers.loyalty.all_types') }}</option>
                                <option value="earned">{{ __('customers.loyalty.points_earned') }}</option>
                                <option value="redeemed">{{ __('customers.loyalty.points_redeemed') }}</option>
                                <option value="expired">{{ __('customers.loyalty.points_expired') }}</option>
                            </select>
                            
                            <input type="date" class="filter-input" id="date-from" placeholder="{{ __('customers.loyalty.from_date') }}">
                            <input type="date" class="filter-input" id="date-to" placeholder="{{ __('customers.loyalty.to_date') }}">
                        </div>
                    </div>
                    
                    <div class="transactions-table-wrapper">
                        <table class="transactions-table">
                            <thead>
                                <tr>
                                    <th>{{ __('customers.loyalty.date') }}</th>
                                    <th>{{ __('customers.loyalty.customer') }}</th>
                                    <th>{{ __('customers.loyalty.type') }}</th>
                                    <th>{{ __('customers.loyalty.points') }}</th>
                                    <th>{{ __('customers.loyalty.description') }}</th>
                                    <th>{{ __('customers.loyalty.balance') }}</th>
                                </tr>
                            </thead>
                            <tbody id="transactions-table-body">
                                <!-- Transactions will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Reward Modal -->
<div class="reward-modal" id="reward-modal" style="display: none;" role="dialog" aria-labelledby="reward-modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="reward-modal-title" class="modal-title">{{ __('customers.loyalty.add_reward') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('customers.loyalty.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form id="reward-form" class="reward-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="reward-name" class="form-label required">{{ __('customers.loyalty.reward_name') }}</label>
                        <input type="text" id="reward-name" name="name" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="reward-type" class="form-label required">{{ __('customers.loyalty.reward_type') }}</label>
                        <select id="reward-type" name="type" class="form-select" required>
                            <option value="">{{ __('customers.loyalty.select_type') }}</option>
                            <option value="discount">{{ __('customers.loyalty.discount') }}</option>
                            <option value="free_item">{{ __('customers.loyalty.free_item') }}</option>
                            <option value="cashback">{{ __('customers.loyalty.cashback') }}</option>
                            <option value="upgrade">{{ __('customers.loyalty.upgrade') }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="points-required" class="form-label required">{{ __('customers.loyalty.points_required') }}</label>
                        <input type="number" id="points-required" name="points_required" class="form-input" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="reward-value" class="form-label">{{ __('customers.loyalty.reward_value') }}</label>
                        <input type="text" id="reward-value" name="value" class="form-input" placeholder="e.g., 10% off, £5 off">
                    </div>
                    <div class="form-group full-width">
                        <label for="reward-description" class="form-label">{{ __('customers.loyalty.description') }}</label>
                        <textarea id="reward-description" name="description" class="form-textarea" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="expiry-days" class="form-label">{{ __('customers.loyalty.expiry_days') }}</label>
                        <input type="number" id="expiry-days" name="expiry_days" class="form-input" min="1" placeholder="30">
                    </div>
                    <div class="form-group">
                        <label for="reward-status" class="form-label">{{ __('customers.loyalty.status') }}</label>
                        <select id="reward-status" name="status" class="form-select">
                            <option value="active">{{ __('customers.loyalty.active') }}</option>
                            <option value="inactive">{{ __('customers.loyalty.inactive') }}</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary cancel-reward-btn">
                {{ __('customers.loyalty.cancel') }}
            </button>
            <button type="submit" form="reward-form" class="btn btn-primary save-reward-btn">
                {{ __('customers.loyalty.save_reward') }}
            </button>
        </div>
    </div>
</div>

<!-- Member Details Modal -->
<div class="member-details-modal" id="member-details-modal" style="display: none;" role="dialog" aria-labelledby="member-details-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content large">
        <div class="modal-header">
            <h2 id="member-details-title" class="modal-title">{{ __('customers.loyalty.member_details') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('customers.loyalty.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="member-details-content" id="member-details-content">
                <!-- Member details will be populated by JavaScript -->
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary close-member-details-btn">
                {{ __('customers.loyalty.close') }}
            </button>
            <button type="button" class="btn btn-primary adjust-points-btn">
                {{ __('customers.loyalty.adjust_points') }}
            </button>
        </div>
    </div>
</div>

<!-- Points Adjustment Modal -->
<div class="points-modal" id="points-modal" style="display: none;" role="dialog" aria-labelledby="points-modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="points-modal-title" class="modal-title">{{ __('customers.loyalty.adjust_points') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('customers.loyalty.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form id="points-form" class="points-form">
                <div class="current-points-display">
                    <span class="current-points-label">{{ __('customers.loyalty.current_points') }}:</span>
                    <span class="current-points-value" id="current-points-display">0</span>
                </div>
                
                <div class="form-group">
                    <label for="adjustment-type" class="form-label required">{{ __('customers.loyalty.adjustment_type') }}</label>
                    <select id="adjustment-type" name="type" class="form-select" required>
                        <option value="">{{ __('customers.loyalty.select_type') }}</option>
                        <option value="add">{{ __('customers.loyalty.add_points') }}</option>
                        <option value="subtract">{{ __('customers.loyalty.subtract_points') }}</option>
                        <option value="set">{{ __('customers.loyalty.set_points') }}</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="points-amount" class="form-label required">{{ __('customers.loyalty.points_amount') }}</label>
                    <input type="number" id="points-amount" name="amount" class="form-input" min="0" required>
                </div>
                
                <div class="form-group">
                    <label for="adjustment-reason" class="form-label required">{{ __('customers.loyalty.reason') }}</label>
                    <textarea id="adjustment-reason" name="reason" class="form-textarea" rows="3" required 
                              placeholder="{{ __('customers.loyalty.reason_placeholder') }}"></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary cancel-points-btn">
                {{ __('customers.loyalty.cancel') }}
            </button>
            <button type="submit" form="points-form" class="btn btn-primary save-points-btn">
                {{ __('customers.loyalty.save_adjustment') }}
            </button>
        </div>
    </div>
</div>
@endsection
