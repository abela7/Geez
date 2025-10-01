@extends('layouts.admin')

@section('title', __('injera.management.title') . ' - ' . config('app.name'))
@section('page_title', __('injera.management.title'))

@push('styles')
    @vite('resources/css/admin/injera/management.css')
@endpush

@push('scripts')
    @vite('resources/js/admin/injera/management.js')
@endpush

@section('content')
<div class="injera-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('injera.management.title') }}</h1>
                <p class="page-subtitle">{{ __('injera.management.subtitle') }}</p>
            </div>
            <div class="page-actions">
                <button type="button" class="btn btn-secondary analytics-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4"/>
                    </svg>
                    {{ __('injera.management.sales_analytics') }}
                </button>
                <button type="button" class="btn btn-secondary new-batch-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('injera.management.start_new_batch') }}
                </button>
                <button type="button" class="btn btn-primary recommendation-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                    {{ __('injera.management.get_recommendation') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon production">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="daily-production">0</div>
                    <div class="stat-label">{{ __('injera.management.daily_production') }}</div>
                    <div class="stat-change" id="production-trend">{{ __('injera.management.vs_yesterday') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon remaining">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="injera-remaining">0</div>
                    <div class="stat-label">{{ __('injera.management.injera_remaining') }}</div>
                    <div class="stat-note" id="estimated-hours">{{ __('injera.management.estimated_hours') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon efficiency">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="flour-efficiency">0</div>
                    <div class="stat-label">{{ __('injera.management.injera_per_kg') }}</div>
                    <div class="stat-note">{{ __('injera.management.flour_efficiency') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon recommendation">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value" id="recommendation-status">{{ __('injera.management.good') }}</div>
                    <div class="stat-label">{{ __('injera.management.selling_recommendation') }}</div>
                    <div class="stat-note" id="recommendation-reason">{{ __('injera.management.based_on_analysis') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Tabs -->
    <div class="content-tabs">
        <div class="tab-navigation">
            <button type="button" class="tab-btn active" data-tab="production">
                <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                </svg>
                {{ __('injera.management.production_cycle') }}
            </button>
            <button type="button" class="tab-btn" data-tab="inventory">
                <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                {{ __('injera.management.inventory_tracking') }}
            </button>
            <button type="button" class="tab-btn" data-tab="sales">
                <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4"/>
                </svg>
                {{ __('injera.management.sales_analysis') }}
            </button>
            <button type="button" class="tab-btn" data-tab="recommendations">
                <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
                {{ __('injera.management.recommendations') }}
            </button>
        </div>

        <div class="tab-content-area">
            <!-- Production Cycle Tab -->
            <div class="tab-panel active" data-tab="production">
                <!-- Current Production Status -->
                <div class="production-status-section">
                    <div class="section-header">
                        <h3 class="section-title">{{ __('injera.management.current_production_status') }}</h3>
                        <button type="button" class="btn btn-primary start-production-btn">
                            {{ __('injera.management.start_new_production') }}
                        </button>
                    </div>
                    
                    <div class="production-timeline" id="production-timeline">
                        <!-- Production batches will be populated by JavaScript -->
                    </div>
                </div>

                <!-- Production Steps -->
                <div class="production-steps-section">
                    <div class="section-header">
                        <h3 class="section-title">{{ __('injera.management.production_steps') }}</h3>
                    </div>
                    
                    <div class="steps-grid">
                        <div class="step-card" data-step="1">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h4 class="step-title">{{ __('injera.management.buy_ingredients') }}</h4>
                                <p class="step-description">{{ __('injera.management.buy_ingredients_description') }}</p>
                                <div class="step-ingredients">
                                    <div class="ingredient-item">
                                        <span class="ingredient-name">{{ __('injera.management.teff_flour') }}</span>
                                        <span class="ingredient-amount">10 kg</span>
                                    </div>
                                    <div class="ingredient-item">
                                        <span class="ingredient-name">{{ __('injera.management.wheat_flour') }}</span>
                                        <span class="ingredient-amount">2 kg</span>
                                    </div>
                                    <div class="ingredient-item">
                                        <span class="ingredient-name">{{ __('injera.management.water') }}</span>
                                        <span class="ingredient-amount">8 L</span>
                                    </div>
                                </div>
                            </div>
                            <div class="step-actions">
                                <button type="button" class="btn btn-sm btn-primary record-purchase-btn">
                                    {{ __('injera.management.record_purchase') }}
                                </button>
                            </div>
                        </div>

                        <div class="step-card" data-step="2">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h4 class="step-title">{{ __('injera.management.mix_flour') }}</h4>
                                <p class="step-description">{{ __('injera.management.mix_flour_description') }}</p>
                                <div class="step-timing">
                                    <div class="timing-item">
                                        <span class="timing-label">{{ __('injera.management.mixing_time') }}</span>
                                        <span class="timing-value">30 {{ __('common.minutes') }}</span>
                                    </div>
                                    <div class="timing-item">
                                        <span class="timing-label">{{ __('injera.management.fermentation_time') }}</span>
                                        <span class="timing-value">2-3 {{ __('common.days') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="step-actions">
                                <button type="button" class="btn btn-sm btn-primary start-mixing-btn">
                                    {{ __('injera.management.start_mixing') }}
                                </button>
                            </div>
                        </div>

                        <div class="step-card" data-step="3">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <h4 class="step-title">{{ __('injera.management.add_hot_water') }}</h4>
                                <p class="step-description">{{ __('injera.management.add_hot_water_description') }}</p>
                                <div class="step-timing">
                                    <div class="timing-item">
                                        <span class="timing-label">{{ __('injera.management.water_temperature') }}</span>
                                        <span class="timing-value">80-90°C</span>
                                    </div>
                                    <div class="timing-item">
                                        <span class="timing-label">{{ __('injera.management.resting_time') }}</span>
                                        <span class="timing-value">2-4 {{ __('common.hours') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="step-actions">
                                <button type="button" class="btn btn-sm btn-primary add-water-btn">
                                    {{ __('injera.management.add_hot_water') }}
                                </button>
                            </div>
                        </div>

                        <div class="step-card" data-step="4">
                            <div class="step-number">4</div>
                            <div class="step-content">
                                <h4 class="step-title">{{ __('injera.management.bake_injera') }}</h4>
                                <p class="step-description">{{ __('injera.management.bake_injera_description') }}</p>
                                <div class="step-timing">
                                    <div class="timing-item">
                                        <span class="timing-label">{{ __('injera.management.baking_time') }}</span>
                                        <span class="timing-value">2-3 {{ __('common.minutes') }}</span>
                                    </div>
                                    <div class="timing-item">
                                        <span class="timing-label">{{ __('injera.management.expected_yield') }}</span>
                                        <span class="timing-value">40-50 {{ __('injera.management.injeras') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="step-actions">
                                <button type="button" class="btn btn-sm btn-primary start-baking-btn">
                                    {{ __('injera.management.start_baking') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inventory Tracking Tab -->
            <div class="tab-panel" data-tab="inventory">
                <!-- Flour Inventory -->
                <div class="inventory-section">
                    <div class="section-header">
                        <h3 class="section-title">{{ __('injera.management.flour_inventory') }}</h3>
                        <button type="button" class="btn btn-secondary update-inventory-btn">
                            {{ __('injera.management.update_inventory') }}
                        </button>
                    </div>
                    
                    <div class="flour-inventory-grid">
                        <div class="flour-card">
                            <div class="flour-header">
                                <h4 class="flour-name">{{ __('injera.management.teff_flour') }}</h4>
                                <span class="flour-status in-stock">{{ __('injera.management.in_stock') }}</span>
                            </div>
                            <div class="flour-details">
                                <div class="flour-amount">
                                    <span class="amount-value" id="teff-amount">25.5</span>
                                    <span class="amount-unit">kg</span>
                                </div>
                                <div class="flour-info">
                                    <div class="info-item">
                                        <span class="info-label">{{ __('injera.management.cost_per_kg') }}</span>
                                        <span class="info-value">$8.50</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">{{ __('injera.management.last_purchase') }}</span>
                                        <span class="info-value">2 {{ __('common.days') }} {{ __('injera.management.ago') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flour-card">
                            <div class="flour-header">
                                <h4 class="flour-name">{{ __('injera.management.wheat_flour') }}</h4>
                                <span class="flour-status low-stock">{{ __('injera.management.low_stock') }}</span>
                            </div>
                            <div class="flour-details">
                                <div class="flour-amount">
                                    <span class="amount-value" id="wheat-amount">3.2</span>
                                    <span class="amount-unit">kg</span>
                                </div>
                                <div class="flour-info">
                                    <div class="info-item">
                                        <span class="info-label">{{ __('injera.management.cost_per_kg') }}</span>
                                        <span class="info-value">$2.80</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">{{ __('injera.management.reorder_level') }}</span>
                                        <span class="info-value">5 kg</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Batches -->
                <div class="batches-section">
                    <div class="section-header">
                        <h3 class="section-title">{{ __('injera.management.current_batches') }}</h3>
                    </div>
                    
                    <div class="batches-grid" id="batches-grid">
                        <!-- Batches will be populated by JavaScript -->
                    </div>
                </div>

                <!-- Finished Injera Stock -->
                <div class="stock-section">
                    <div class="section-header">
                        <h3 class="section-title">{{ __('injera.management.finished_injera_stock') }}</h3>
                    </div>
                    
                    <div class="stock-summary">
                        <div class="stock-card">
                            <div class="stock-info">
                                <div class="stock-amount">
                                    <span class="stock-value" id="total-injera">0</span>
                                    <span class="stock-unit">{{ __('injera.management.injeras') }}</span>
                                </div>
                                <div class="stock-details">
                                    <div class="detail-item">
                                        <span class="detail-label">{{ __('injera.management.fresh_today') }}</span>
                                        <span class="detail-value" id="fresh-injera">0</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">{{ __('injera.management.yesterday') }}</span>
                                        <span class="detail-value" id="yesterday-injera">0</span>
                                    </div>
                                </div>
                            </div>
                            <div class="stock-actions">
                                <button type="button" class="btn btn-sm btn-secondary count-stock-btn">
                                    {{ __('injera.management.count_stock') }}
                                </button>
                                <button type="button" class="btn btn-sm btn-secondary adjust-stock-btn">
                                    {{ __('injera.management.adjust_stock') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sales Analysis Tab -->
            <div class="tab-panel" data-tab="sales">
                <!-- Sales Performance -->
                <div class="sales-performance-section">
                    <div class="section-header">
                        <h3 class="section-title">{{ __('injera.management.sales_performance') }}</h3>
                        <div class="performance-period">
                            <select class="period-select" id="sales-period">
                                <option value="today">{{ __('common.today') }}</option>
                                <option value="week">{{ __('common.this_week') }}</option>
                                <option value="month">{{ __('common.this_month') }}</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="sales-metrics-grid">
                        <div class="sales-metric-card">
                            <div class="metric-icon food-service">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <div class="metric-content">
                                <div class="metric-value" id="food-service-injera">0</div>
                                <div class="metric-label">{{ __('injera.management.used_for_food_service') }}</div>
                                <div class="metric-percentage" id="food-service-percentage">0%</div>
                            </div>
                        </div>

                        <div class="sales-metric-card">
                            <div class="metric-icon direct-sales">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                            </div>
                            <div class="metric-content">
                                <div class="metric-value" id="direct-sales-injera">0</div>
                                <div class="metric-label">{{ __('injera.management.sold_directly') }}</div>
                                <div class="metric-revenue">$<span id="direct-sales-revenue">0.00</span></div>
                            </div>
                        </div>

                        <div class="sales-metric-card">
                            <div class="metric-icon waste">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </div>
                            <div class="metric-content">
                                <div class="metric-value" id="waste-injera">0</div>
                                <div class="metric-label">{{ __('injera.management.wasted_injera') }}</div>
                                <div class="metric-cost text-danger">$<span id="waste-cost">0.00</span></div>
                            </div>
                        </div>

                        <div class="sales-metric-card">
                            <div class="metric-icon efficiency-rate">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                            <div class="metric-content">
                                <div class="metric-value" id="efficiency-rate">0%</div>
                                <div class="metric-label">{{ __('injera.management.efficiency_rate') }}</div>
                                <div class="metric-target">{{ __('injera.management.target') }}: 85%</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sales Chart -->
                <div class="sales-chart-section">
                    <div class="chart-card">
                        <div class="chart-header">
                            <h4 class="chart-title">{{ __('injera.management.daily_sales_pattern') }}</h4>
                            <div class="chart-controls">
                                <select class="chart-select" id="chart-period">
                                    <option value="week">{{ __('injera.management.this_week') }}</option>
                                    <option value="month">{{ __('injera.management.this_month') }}</option>
                                    <option value="quarter">{{ __('injera.management.quarter') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="chart-body">
                            <canvas id="sales-pattern-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recommendations Tab -->
            <div class="tab-panel" data-tab="recommendations">
                <!-- AI Recommendation Engine -->
                <div class="recommendations-section">
                    <div class="section-header">
                        <h3 class="section-title">{{ __('injera.management.intelligent_recommendations') }}</h3>
                        <div class="recommendation-status">
                            <span class="status-indicator good" id="overall-status"></span>
                            <span class="status-text" id="overall-status-text">{{ __('injera.management.system_analysis') }}</span>
                        </div>
                    </div>
                    
                    <div class="recommendations-grid">
                        <!-- Production Recommendation -->
                        <div class="recommendation-card production">
                            <div class="recommendation-header">
                                <div class="recommendation-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                    </svg>
                                </div>
                                <div class="recommendation-title">{{ __('injera.management.production_recommendation') }}</div>
                                <div class="recommendation-confidence">95%</div>
                            </div>
                            <div class="recommendation-content">
                                <p class="recommendation-text">{{ __('injera.management.production_recommendation_text') }}</p>
                                <div class="recommendation-details">
                                    <div class="detail-item">
                                        <span class="detail-label">{{ __('injera.management.recommended_batch_size') }}</span>
                                        <span class="detail-value">12 kg {{ __('injera.management.flour') }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">{{ __('injera.management.expected_yield') }}</span>
                                        <span class="detail-value">48-52 {{ __('injera.management.injeras') }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">{{ __('injera.management.start_mixing') }}</span>
                                        <span class="detail-value">{{ __('common.tomorrow') }} 8:00 AM</span>
                                    </div>
                                </div>
                            </div>
                            <div class="recommendation-actions">
                                <button type="button" class="btn btn-primary accept-recommendation-btn">
                                    {{ __('injera.management.accept_recommendation') }}
                                </button>
                            </div>
                        </div>

                        <!-- Sales Recommendation -->
                        <div class="recommendation-card sales">
                            <div class="recommendation-header">
                                <div class="recommendation-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4"/>
                                    </svg>
                                </div>
                                <div class="recommendation-title">{{ __('injera.management.sales_recommendation') }}</div>
                                <div class="recommendation-confidence">88%</div>
                            </div>
                            <div class="recommendation-content">
                                <p class="recommendation-text">{{ __('injera.management.sales_recommendation_text') }}</p>
                                <div class="recommendation-analysis">
                                    <div class="analysis-item positive">
                                        <span class="analysis-label">{{ __('injera.management.demand_trend') }}</span>
                                        <span class="analysis-value">+15% {{ __('injera.management.vs_last_week') }}</span>
                                    </div>
                                    <div class="analysis-item neutral">
                                        <span class="analysis-label">{{ __('injera.management.day_comparison') }}</span>
                                        <span class="analysis-value">{{ __('injera.management.similar_to_last_friday') }}</span>
                                    </div>
                                    <div class="analysis-item positive">
                                        <span class="analysis-label">{{ __('injera.management.weather_factor') }}</span>
                                        <span class="analysis-value">{{ __('injera.management.favorable') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="recommendation-actions">
                                <button type="button" class="btn btn-success enable-sales-btn">
                                    {{ __('injera.management.enable_injera_sales') }}
                                </button>
                            </div>
                        </div>

                        <!-- Waste Reduction -->
                        <div class="recommendation-card waste-reduction">
                            <div class="recommendation-header">
                                <div class="recommendation-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <div class="recommendation-title">{{ __('injera.management.waste_reduction') }}</div>
                                <div class="recommendation-confidence">92%</div>
                            </div>
                            <div class="recommendation-content">
                                <p class="recommendation-text">{{ __('injera.management.waste_reduction_text') }}</p>
                                <div class="waste-insights">
                                    <div class="insight-item">
                                        <span class="insight-label">{{ __('injera.management.current_waste_rate') }}</span>
                                        <span class="insight-value">8.5%</span>
                                    </div>
                                    <div class="insight-item">
                                        <span class="insight-label">{{ __('injera.management.target_waste_rate') }}</span>
                                        <span class="insight-value">5%</span>
                                    </div>
                                    <div class="insight-item">
                                        <span class="insight-label">{{ __('injera.management.potential_savings') }}</span>
                                        <span class="insight-value">$45/{{ __('common.week') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="recommendation-actions">
                                <button type="button" class="btn btn-warning implement-strategy-btn">
                                    {{ __('injera.management.implement_strategy') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Historical Comparison -->
                <div class="comparison-section">
                    <div class="section-header">
                        <h3 class="section-title">{{ __('injera.management.historical_comparison') }}</h3>
                    </div>
                    
                    <div class="comparison-chart">
                        <canvas id="comparison-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Production Batch Modal -->
<div class="production-modal" id="production-modal" style="display: none;" role="dialog" aria-labelledby="production-modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content large">
        <div class="modal-header">
            <h2 id="production-modal-title" class="modal-title">{{ __('injera.management.start_new_production') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('common.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form class="production-form" id="production-form">
                <div class="production-wizard">
                    <div class="wizard-steps">
                        <div class="wizard-step active" data-step="1">
                            <div class="step-circle">1</div>
                            <div class="step-label">{{ __('injera.management.ingredients') }}</div>
                        </div>
                        <div class="wizard-step" data-step="2">
                            <div class="step-circle">2</div>
                            <div class="step-label">{{ __('injera.management.mixing') }}</div>
                        </div>
                        <div class="wizard-step" data-step="3">
                            <div class="step-circle">3</div>
                            <div class="step-label">{{ __('injera.management.fermentation') }}</div>
                        </div>
                        <div class="wizard-step" data-step="4">
                            <div class="step-circle">4</div>
                            <div class="step-label">{{ __('injera.management.baking') }}</div>
                        </div>
                    </div>
                    
                    <div class="wizard-content">
                        <!-- Step 1: Ingredients -->
                        <div class="wizard-panel active" data-step="1">
                            <h4 class="wizard-title">{{ __('injera.management.select_ingredients') }}</h4>
                            <div class="ingredients-form">
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="teff-amount" class="form-label">{{ __('injera.management.teff_flour') }}</label>
                                        <div class="input-with-suffix">
                                            <input type="number" id="teff-amount" name="teff_amount" class="form-input" 
                                                   min="1" max="50" step="0.1" value="10" required>
                                            <span class="input-suffix">kg</span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="wheat-amount" class="form-label">{{ __('injera.management.wheat_flour') }}</label>
                                        <div class="input-with-suffix">
                                            <input type="number" id="wheat-amount" name="wheat_amount" class="form-input" 
                                                   min="0" max="10" step="0.1" value="2">
                                            <span class="input-suffix">kg</span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="water-amount" class="form-label">{{ __('injera.management.water') }}</label>
                                        <div class="input-with-suffix">
                                            <input type="number" id="water-amount" name="water_amount" class="form-input" 
                                                   min="1" max="20" step="0.1" value="8">
                                            <span class="input-suffix">L</span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">{{ __('injera.management.expected_yield') }}</label>
                                        <div class="yield-display">
                                            <span class="yield-value" id="expected-yield">40-45</span>
                                            <span class="yield-unit">{{ __('injera.management.injeras') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Step 2: Mixing -->
                        <div class="wizard-panel" data-step="2">
                            <h4 class="wizard-title">{{ __('injera.management.mixing_schedule') }}</h4>
                            <div class="mixing-form">
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="mixing-date" class="form-label">{{ __('injera.management.mixing_date') }}</label>
                                        <input type="datetime-local" id="mixing-date" name="mixing_date" class="form-input" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="mixing-duration" class="form-label">{{ __('injera.management.mixing_duration') }}</label>
                                        <div class="input-with-suffix">
                                            <input type="number" id="mixing-duration" name="mixing_duration" class="form-input" 
                                                   min="15" max="60" value="30">
                                            <span class="input-suffix">{{ __('common.minutes') }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="fermentation-days" class="form-label">{{ __('injera.management.fermentation_period') }}</label>
                                        <div class="input-with-suffix">
                                            <input type="number" id="fermentation-days" name="fermentation_days" class="form-input" 
                                                   min="1" max="5" value="3">
                                            <span class="input-suffix">{{ __('common.days') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Step 3: Hot Water Addition -->
                        <div class="wizard-panel" data-step="3">
                            <h4 class="wizard-title">{{ __('injera.management.hot_water_addition') }}</h4>
                            <div class="water-form">
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="water-addition-date" class="form-label">{{ __('injera.management.water_addition_date') }}</label>
                                        <input type="datetime-local" id="water-addition-date" name="water_addition_date" class="form-input" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="hot-water-amount" class="form-label">{{ __('injera.management.hot_water_amount') }}</label>
                                        <div class="input-with-suffix">
                                            <input type="number" id="hot-water-amount" name="hot_water_amount" class="form-input" 
                                                   min="0.5" max="5" step="0.1" value="2">
                                            <span class="input-suffix">L</span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="water-temperature" class="form-label">{{ __('injera.management.water_temperature') }}</label>
                                        <div class="input-with-suffix">
                                            <input type="number" id="water-temperature" name="water_temperature" class="form-input" 
                                                   min="70" max="100" value="85">
                                            <span class="input-suffix">°C</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Step 4: Baking Schedule -->
                        <div class="wizard-panel" data-step="4">
                            <h4 class="wizard-title">{{ __('injera.management.baking_schedule') }}</h4>
                            <div class="baking-form">
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="baking-start-date" class="form-label">{{ __('injera.management.baking_start_date') }}</label>
                                        <input type="datetime-local" id="baking-start-date" name="baking_start_date" class="form-input" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="baker-assigned" class="form-label">{{ __('injera.management.baker_assigned') }}</label>
                                        <select id="baker-assigned" name="baker_assigned" class="form-select" required>
                                            <option value="">{{ __('injera.management.select_baker') }}</option>
                                            <option value="1">Almaz Tadesse (Head Baker)</option>
                                            <option value="2">Tigist Mekonen (Assistant Baker)</option>
                                            <option value="3">Hanan Ahmed (Backup Baker)</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="target-quantity" class="form-label">{{ __('injera.management.target_quantity') }}</label>
                                        <div class="input-with-suffix">
                                            <input type="number" id="target-quantity" name="target_quantity" class="form-input" 
                                                   min="10" max="100" value="45">
                                            <span class="input-suffix">{{ __('injera.management.injeras') }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="production-summary">
                                    <h5 class="summary-title">{{ __('injera.management.production_summary') }}</h5>
                                    <div class="summary-grid">
                                        <div class="summary-item">
                                            <span class="summary-label">{{ __('injera.management.total_flour') }}</span>
                                            <span class="summary-value" id="total-flour-summary">12 kg</span>
                                        </div>
                                        <div class="summary-item">
                                            <span class="summary-label">{{ __('injera.management.total_cost') }}</span>
                                            <span class="summary-value" id="total-cost-summary">$90.60</span>
                                        </div>
                                        <div class="summary-item">
                                            <span class="summary-label">{{ __('injera.management.cost_per_injera') }}</span>
                                            <span class="summary-value" id="cost-per-injera">$2.01</span>
                                        </div>
                                        <div class="summary-item">
                                            <span class="summary-label">{{ __('injera.management.completion_date') }}</span>
                                            <span class="summary-value" id="completion-date">Jan 18, 10:00 AM</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="wizard-navigation">
                        <button type="button" class="btn btn-secondary prev-step-btn" style="display: none;">
                            {{ __('common.previous') }}
                        </button>
                        <button type="button" class="btn btn-primary next-step-btn">
                            {{ __('common.next') }}
                        </button>
                        <button type="submit" class="btn btn-success start-production-btn" style="display: none;">
                            {{ __('injera.management.start_production') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
