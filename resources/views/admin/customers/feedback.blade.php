@extends('layouts.admin')

@section('title', __('customers.feedback.title') . ' - ' . config('app.name'))
@section('page_title', __('customers.feedback.title'))

@push('styles')
    @vite('resources/css/admin/customer-feedback.css')
@endpush

@push('scripts')
    @vite('resources/js/admin/customer-feedback.js')
@endpush

@section('content')
<div class="feedback-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('customers.feedback.title') }}</h1>
                <p class="page-subtitle">{{ __('customers.feedback.subtitle') }}</p>
            </div>
            <div class="page-actions">
                <button type="button" class="btn btn-secondary export-feedback-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ __('customers.feedback.export_reviews') }}
                </button>
                <button type="button" class="btn btn-primary add-review-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('customers.feedback.add_review') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Overview -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-card overall-rating">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="overall-rating">0.0</div>
                    <div class="stat-label">{{ __('customers.feedback.overall_rating') }}</div>
                    <div class="rating-stars" id="overall-stars"></div>
                </div>
            </div>
            
            <div class="stat-card total-reviews">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="total-reviews">0</div>
                    <div class="stat-label">{{ __('customers.feedback.total_reviews') }}</div>
                    <div class="stat-change" id="reviews-change">+0%</div>
                </div>
            </div>
            
            <div class="stat-card food-rating">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="food-rating">0.0</div>
                    <div class="stat-label">{{ __('customers.feedback.food_rating') }}</div>
                    <div class="rating-stars" id="food-stars"></div>
                </div>
            </div>
            
            <div class="stat-card service-rating">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="service-rating">0.0</div>
                    <div class="stat-label">{{ __('customers.feedback.service_rating') }}</div>
                    <div class="rating-stars" id="service-stars"></div>
                </div>
            </div>
            
            <div class="stat-card atmosphere-rating">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="atmosphere-rating">0.0</div>
                    <div class="stat-label">{{ __('customers.feedback.atmosphere_rating') }}</div>
                    <div class="rating-stars" id="atmosphere-stars"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Tabs -->
    <div class="content-section">
        <div class="feedback-tabs" x-data="{ activeTab: 'reviews' }">
            <div class="tab-nav">
                <button type="button" 
                        class="tab-btn" 
                        :class="{ 'active': activeTab === 'reviews' }"
                        @click="activeTab = 'reviews'">
                    <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    {{ __('customers.feedback.reviews') }}
                </button>
                <button type="button" 
                        class="tab-btn" 
                        :class="{ 'active': activeTab === 'analytics' }"
                        @click="activeTab = 'analytics'">
                    <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    {{ __('customers.feedback.analytics') }}
                </button>
                <button type="button" 
                        class="tab-btn" 
                        :class="{ 'active': activeTab === 'reports' }"
                        @click="activeTab = 'reports'">
                    <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ __('customers.feedback.reports') }}
                </button>
                <button type="button" 
                        class="tab-btn" 
                        :class="{ 'active': activeTab === 'insights' }"
                        @click="activeTab = 'insights'">
                    <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                    {{ __('customers.feedback.insights') }}
                </button>
            </div>

            <!-- Reviews Tab -->
            <div class="tab-panel" x-show="activeTab === 'reviews'" x-transition>
                <div class="reviews-section">
                    <!-- Search and Filters -->
                    <div class="search-filters-container">
                        <div class="search-bar">
                            <div class="search-input-wrapper">
                                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <input type="text" 
                                       class="search-input" 
                                       placeholder="{{ __('customers.feedback.search_reviews') }}"
                                       id="reviews-search">
                            </div>
                        </div>
                        
                        <div class="filters-container">
                            <select class="filter-select" id="rating-filter">
                                <option value="">{{ __('customers.feedback.all_ratings') }}</option>
                                <option value="5">5 {{ __('customers.feedback.stars') }}</option>
                                <option value="4">4 {{ __('customers.feedback.stars') }}</option>
                                <option value="3">3 {{ __('customers.feedback.stars') }}</option>
                                <option value="2">2 {{ __('customers.feedback.stars') }}</option>
                                <option value="1">1 {{ __('customers.feedback.star') }}</option>
                            </select>
                            
                            <select class="filter-select" id="category-filter">
                                <option value="">{{ __('customers.feedback.all_categories') }}</option>
                                <option value="food">{{ __('customers.feedback.food') }}</option>
                                <option value="service">{{ __('customers.feedback.service') }}</option>
                                <option value="atmosphere">{{ __('customers.feedback.atmosphere') }}</option>
                            </select>
                            
                            <input type="date" class="filter-input" id="date-filter" placeholder="{{ __('customers.feedback.filter_date') }}">
                            
                            <select class="filter-select" id="status-filter">
                                <option value="">{{ __('customers.feedback.all_status') }}</option>
                                <option value="pending">{{ __('customers.feedback.pending') }}</option>
                                <option value="approved">{{ __('customers.feedback.approved') }}</option>
                                <option value="rejected">{{ __('customers.feedback.rejected') }}</option>
                            </select>
                            
                            <button type="button" class="btn btn-secondary clear-filters-btn">
                                {{ __('customers.feedback.clear_filters') }}
                            </button>
                        </div>
                    </div>

                    <!-- Reviews List -->
                    <div class="reviews-list-container">
                        <div class="reviews-grid" id="reviews-grid">
                            <!-- Reviews will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analytics Tab -->
            <div class="tab-panel" x-show="activeTab === 'analytics'" x-transition>
                <div class="analytics-section">
                    <!-- Time Period Selector -->
                    <div class="analytics-controls">
                        <div class="period-selector">
                            <label for="analytics-period" class="period-label">{{ __('customers.feedback.time_period') }}:</label>
                            <select id="analytics-period" class="period-select">
                                <option value="week" selected>{{ __('customers.feedback.this_week') }}</option>
                                <option value="month">{{ __('customers.feedback.this_month') }}</option>
                                <option value="quarter">{{ __('customers.feedback.this_quarter') }}</option>
                                <option value="year">{{ __('customers.feedback.this_year') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Charts Section -->
                    <div class="charts-grid">
                        <div class="chart-card">
                            <div class="chart-header">
                                <h3 class="chart-title">{{ __('customers.feedback.rating_distribution') }}</h3>
                                <div class="chart-actions">
                                    <button type="button" class="chart-action-btn" title="{{ __('customers.feedback.refresh') }}">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="chart-container">
                                <canvas id="rating-distribution-chart"></canvas>
                            </div>
                        </div>

                        <div class="chart-card">
                            <div class="chart-header">
                                <h3 class="chart-title">{{ __('customers.feedback.category_ratings') }}</h3>
                                <div class="chart-actions">
                                    <button type="button" class="chart-action-btn" title="{{ __('customers.feedback.refresh') }}">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="chart-container">
                                <canvas id="category-ratings-chart"></canvas>
                            </div>
                        </div>

                        <div class="chart-card">
                            <div class="chart-header">
                                <h3 class="chart-title">{{ __('customers.feedback.reviews_over_time') }}</h3>
                                <div class="chart-actions">
                                    <button type="button" class="chart-action-btn" title="{{ __('customers.feedback.refresh') }}">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="chart-container">
                                <canvas id="reviews-timeline-chart"></canvas>
                            </div>
                        </div>

                        <div class="chart-card">
                            <div class="chart-header">
                                <h3 class="chart-title">{{ __('customers.feedback.sentiment_analysis') }}</h3>
                                <div class="chart-actions">
                                    <button type="button" class="chart-action-btn" title="{{ __('customers.feedback.refresh') }}">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="chart-container">
                                <canvas id="sentiment-analysis-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reports Tab -->
            <div class="tab-panel" x-show="activeTab === 'reports'" x-transition>
                <div class="reports-section">
                    <div class="reports-header">
                        <h3 class="section-title">{{ __('customers.feedback.feedback_reports') }}</h3>
                        <div class="report-actions">
                            <button type="button" class="btn btn-secondary generate-report-btn">
                                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                {{ __('customers.feedback.generate_report') }}
                            </button>
                        </div>
                    </div>
                    
                    <div class="report-filters">
                        <div class="filter-group">
                            <label for="report-type" class="filter-label">{{ __('customers.feedback.report_type') }}</label>
                            <select id="report-type" class="filter-select">
                                <option value="weekly">{{ __('customers.feedback.weekly_report') }}</option>
                                <option value="monthly" selected>{{ __('customers.feedback.monthly_report') }}</option>
                                <option value="quarterly">{{ __('customers.feedback.quarterly_report') }}</option>
                                <option value="custom">{{ __('customers.feedback.custom_period') }}</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="report-category" class="filter-label">{{ __('customers.feedback.category') }}</label>
                            <select id="report-category" class="filter-select">
                                <option value="">{{ __('customers.feedback.all_categories') }}</option>
                                <option value="food">{{ __('customers.feedback.food') }}</option>
                                <option value="service">{{ __('customers.feedback.service') }}</option>
                                <option value="atmosphere">{{ __('customers.feedback.atmosphere') }}</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="report-rating" class="filter-label">{{ __('customers.feedback.rating') }}</label>
                            <select id="report-rating" class="filter-select">
                                <option value="">{{ __('customers.feedback.all_ratings') }}</option>
                                <option value="5">5 {{ __('customers.feedback.stars') }}</option>
                                <option value="4">4 {{ __('customers.feedback.stars') }}</option>
                                <option value="3">3 {{ __('customers.feedback.stars') }}</option>
                                <option value="2">2 {{ __('customers.feedback.stars') }}</option>
                                <option value="1">1 {{ __('customers.feedback.star') }}</option>
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
                        <h3 class="section-title">{{ __('customers.feedback.customer_insights') }}</h3>
                        <div class="insights-period">
                            <select id="insights-period" class="period-select">
                                <option value="week">{{ __('customers.feedback.last_7_days') }}</option>
                                <option value="month" selected>{{ __('customers.feedback.last_30_days') }}</option>
                                <option value="quarter">{{ __('customers.feedback.last_90_days') }}</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="insights-grid">
                        <div class="insight-card satisfaction">
                            <div class="insight-header">
                                <h4 class="insight-title">{{ __('customers.feedback.satisfaction_trends') }}</h4>
                                <div class="insight-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m-10 5.5a8.5 8.5 0 1117 0H3z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="insight-content" id="satisfaction-trends">
                                <!-- Satisfaction trends will be populated by JavaScript -->
                            </div>
                        </div>
                        
                        <div class="insight-card improvement">
                            <div class="insight-header">
                                <h4 class="insight-title">{{ __('customers.feedback.improvement_areas') }}</h4>
                                <div class="insight-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="insight-content" id="improvement-areas">
                                <!-- Improvement areas will be populated by JavaScript -->
                            </div>
                        </div>
                        
                        <div class="insight-card keywords">
                            <div class="insight-header">
                                <h4 class="insight-title">{{ __('customers.feedback.common_keywords') }}</h4>
                                <div class="insight-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="insight-content" id="common-keywords">
                                <!-- Common keywords will be populated by JavaScript -->
                            </div>
                        </div>
                        
                        <div class="insight-card recommendations">
                            <div class="insight-header">
                                <h4 class="insight-title">{{ __('customers.feedback.action_recommendations') }}</h4>
                                <div class="insight-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="insight-content" id="action-recommendations">
                                <!-- Action recommendations will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Review Modal -->
<div class="review-modal" id="review-modal" style="display: none;" role="dialog" aria-labelledby="review-modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="review-modal-title" class="modal-title">{{ __('customers.feedback.add_review') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('customers.feedback.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form id="review-form" class="review-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="customer-name" class="form-label required">{{ __('customers.feedback.customer_name') }}</label>
                        <input type="text" id="customer-name" name="customer_name" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="customer-email" class="form-label">{{ __('customers.feedback.customer_email') }}</label>
                        <input type="email" id="customer-email" name="customer_email" class="form-input">
                    </div>
                    
                    <div class="form-group">
                        <label for="review-date" class="form-label">{{ __('customers.feedback.review_date') }}</label>
                        <input type="datetime-local" id="review-date" name="review_date" class="form-input">
                    </div>
                    
                    <div class="form-group">
                        <label for="review-status" class="form-label">{{ __('customers.feedback.status') }}</label>
                        <select id="review-status" name="status" class="form-select">
                            <option value="pending">{{ __('customers.feedback.pending') }}</option>
                            <option value="approved" selected>{{ __('customers.feedback.approved') }}</option>
                            <option value="rejected">{{ __('customers.feedback.rejected') }}</option>
                        </select>
                    </div>
                    
                    <!-- Rating Sections -->
                    <div class="form-group full-width">
                        <label class="form-label">{{ __('customers.feedback.ratings') }}</label>
                        
                        <div class="rating-section">
                            <div class="rating-item">
                                <label class="rating-label">{{ __('customers.feedback.food_quality') }}</label>
                                <div class="star-rating" data-rating="food">
                                    <span class="star" data-value="1">★</span>
                                    <span class="star" data-value="2">★</span>
                                    <span class="star" data-value="3">★</span>
                                    <span class="star" data-value="4">★</span>
                                    <span class="star" data-value="5">★</span>
                                </div>
                                <input type="hidden" name="food_rating" id="food-rating" value="0">
                            </div>
                            
                            <div class="rating-item">
                                <label class="rating-label">{{ __('customers.feedback.service_quality') }}</label>
                                <div class="star-rating" data-rating="service">
                                    <span class="star" data-value="1">★</span>
                                    <span class="star" data-value="2">★</span>
                                    <span class="star" data-value="3">★</span>
                                    <span class="star" data-value="4">★</span>
                                    <span class="star" data-value="5">★</span>
                                </div>
                                <input type="hidden" name="service_rating" id="service-rating" value="0">
                            </div>
                            
                            <div class="rating-item">
                                <label class="rating-label">{{ __('customers.feedback.atmosphere_quality') }}</label>
                                <div class="star-rating" data-rating="atmosphere">
                                    <span class="star" data-value="1">★</span>
                                    <span class="star" data-value="2">★</span>
                                    <span class="star" data-value="3">★</span>
                                    <span class="star" data-value="4">★</span>
                                    <span class="star" data-value="5">★</span>
                                </div>
                                <input type="hidden" name="atmosphere_rating" id="atmosphere-rating" value="0">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="review-comment" class="form-label">{{ __('customers.feedback.comment') }}</label>
                        <textarea id="review-comment" name="comment" class="form-textarea" rows="4" 
                                  placeholder="{{ __('customers.feedback.comment_placeholder') }}"></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary cancel-review-btn">
                {{ __('customers.feedback.cancel') }}
            </button>
            <button type="submit" form="review-form" class="btn btn-primary save-review-btn">
                {{ __('customers.feedback.save_review') }}
            </button>
        </div>
    </div>
</div>

<!-- Review Details Modal -->
<div class="review-details-modal" id="review-details-modal" style="display: none;" role="dialog" aria-labelledby="review-details-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content large">
        <div class="modal-header">
            <h2 id="review-details-title" class="modal-title">{{ __('customers.feedback.review_details') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('customers.feedback.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="review-details-content" id="review-details-content">
                <!-- Review details will be populated by JavaScript -->
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary close-details-btn">
                {{ __('customers.feedback.close') }}
            </button>
            <button type="button" class="btn btn-warning reject-review-btn">
                {{ __('customers.feedback.reject') }}
            </button>
            <button type="button" class="btn btn-success approve-review-btn">
                {{ __('customers.feedback.approve') }}
            </button>
            <button type="button" class="btn btn-primary edit-review-btn">
                {{ __('customers.feedback.edit') }}
            </button>
        </div>
    </div>
</div>
@endsection
