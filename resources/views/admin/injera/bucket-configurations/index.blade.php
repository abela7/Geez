@extends('layouts.admin')

@section('title', __('injera.bucket_configurations.title') . ' - ' . config('app.name'))
@section('page_title', __('injera.bucket_configurations.title'))

@push('styles')
@vite(['resources/css/admin/injera/bucket-configurations.css'])
@endpush

@section('content')
<div class="bucket-configurations-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">{{ __('injera.bucket_configurations.title') }}</h1>
            <p class="page-subtitle">{{ __('injera.bucket_configurations.subtitle') }}</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-secondary" onclick="exportConfigurations()">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                {{ __('injera.bucket_configurations.export_configurations') }}
            </button>
            <button class="btn btn-primary" onclick="createBucket()">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                {{ __('injera.bucket_configurations.create_bucket') }}
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card">
            <div class="summary-card-header">
                <h3 class="summary-card-title">{{ __('injera.bucket_configurations.total_configurations') }}</h3>
                <svg class="summary-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <p class="summary-card-value">{{ $statistics['total_configurations'] }}</p>
        </div>

        <div class="summary-card">
            <div class="summary-card-header">
                <h3 class="summary-card-title">{{ __('injera.bucket_configurations.active_configurations') }}</h3>
                <svg class="summary-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="summary-card-value">{{ $statistics['active_configurations'] }}</p>
        </div>

        <div class="summary-card">
            <div class="summary-card-header">
                <h3 class="summary-card-title">{{ __('injera.bucket_configurations.avg_cost_per_injera') }}</h3>
                <svg class="summary-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                </svg>
            </div>
            <p class="summary-card-value">${{ number_format($statistics['avg_cost_per_injera'], 3) }}</p>
        </div>

        <div class="summary-card">
            <div class="summary-card-header">
                <h3 class="summary-card-title">{{ __('injera.bucket_configurations.total_capacity') }}</h3>
                <svg class="summary-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <p class="summary-card-value">{{ number_format($statistics['total_capacity'], 0) }}L</p>
        </div>
    </div>

    <!-- Bucket Configurations Grid -->
    <div class="configurations-section">
        <div class="section-header">
            <h2 class="section-title">{{ __('injera.bucket_configurations.bucket_recipes') }}</h2>
            <div class="section-actions">
                <button class="btn btn-outline" onclick="refreshConfigurations()">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    {{ __('injera.bucket_configurations.refresh') }}
                </button>
            </div>
        </div>

        <div class="configurations-grid">
            @foreach($buckets as $bucket)
            <div class="bucket-card {{ !$bucket['is_active'] ? 'inactive' : '' }}" data-bucket-id="{{ $bucket['id'] }}">
                <div class="bucket-card-header">
                    <div class="bucket-info">
                        <h3 class="bucket-name">{{ $bucket['name'] }}</h3>
                        <div class="bucket-capacity">{{ $bucket['capacity'] }}L {{ __('injera.bucket_configurations.capacity') }}</div>
                    </div>
                    <div class="bucket-status">
                        @if($bucket['is_active'])
                            <span class="status-badge active">{{ __('injera.bucket_configurations.active') }}</span>
                        @else
                            <span class="status-badge inactive">{{ __('injera.bucket_configurations.inactive') }}</span>
                        @endif
                    </div>
                </div>

                <div class="bucket-recipe">
                    <h4 class="recipe-title">{{ __('injera.bucket_configurations.flour_recipe') }}</h4>
                    <div class="flour-list">
                        @foreach($bucket['flour_recipe'] as $flour)
                        <div class="flour-item">
                            <span class="flour-type-badge flour-type-{{ strtolower($flour['flour_type']) }}">
                                {{ $flour['flour_type'] }}
                            </span>
                            <span class="flour-quantity">{{ $flour['quantity'] }}kg</span>
                            <span class="flour-cost">${{ number_format($flour['cost'], 2) }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="recipe-totals">
                        <div class="total-item">
                            <span class="total-label">{{ __('injera.bucket_configurations.total_flour') }}:</span>
                            <span class="total-value">{{ $bucket['total_flour'] }}kg</span>
                        </div>
                    </div>
                </div>

                <div class="bucket-water">
                    <h4 class="water-title">{{ __('injera.bucket_configurations.water_requirements') }}</h4>
                    <div class="water-breakdown">
                        <div class="water-item">
                            <span class="water-label">{{ __('injera.bucket_configurations.cold_water') }}:</span>
                            <span class="water-value">{{ $bucket['cold_water'] }}L</span>
                        </div>
                        <div class="water-item">
                            <span class="water-label">{{ __('injera.bucket_configurations.hot_water') }}:</span>
                            <span class="water-value">{{ $bucket['hot_water'] }}L</span>
                        </div>
                        <div class="water-total">
                            <span class="water-label">{{ __('injera.bucket_configurations.total_water') }}:</span>
                            <span class="water-value">{{ $bucket['total_water'] }}L</span>
                        </div>
                    </div>
                </div>

                <div class="bucket-yield">
                    <div class="yield-info">
                        <div class="yield-item">
                            <span class="yield-label">{{ __('injera.bucket_configurations.expected_yield') }}:</span>
                            <span class="yield-value">{{ $bucket['expected_yield'] }} {{ __('injera.bucket_configurations.injeras') }}</span>
                        </div>
                        <div class="yield-item">
                            <span class="yield-label">{{ __('injera.bucket_configurations.cost_per_injera') }}:</span>
                            <span class="yield-cost">${{ number_format($bucket['cost_per_injera'], 3) }}</span>
                        </div>
                    </div>
                </div>

                <div class="bucket-costs">
                    <h4 class="costs-title">{{ __('injera.bucket_configurations.cost_breakdown') }}</h4>
                    <div class="costs-breakdown">
                        <div class="cost-item">
                            <span class="cost-label">{{ __('injera.bucket_configurations.flour_cost') }}:</span>
                            <span class="cost-value">${{ number_format($bucket['total_flour_cost'], 2) }}</span>
                        </div>
                        <div class="cost-item">
                            <span class="cost-label">{{ __('injera.bucket_configurations.electricity') }}:</span>
                            <span class="cost-value">${{ number_format($bucket['electricity_cost'], 2) }}</span>
                        </div>
                        <div class="cost-item">
                            <span class="cost-label">{{ __('injera.bucket_configurations.labor') }}:</span>
                            <span class="cost-value">${{ number_format($bucket['labor_cost'], 2) }}</span>
                        </div>
                        <div class="cost-total">
                            <span class="cost-label">{{ __('injera.bucket_configurations.total_cost') }}:</span>
                            <span class="cost-value">${{ number_format($bucket['total_cost'], 2) }}</span>
                        </div>
                    </div>
                </div>

                @if($bucket['notes'])
                <div class="bucket-notes">
                    <p class="notes-text">{{ $bucket['notes'] }}</p>
                </div>
                @endif

                <div class="bucket-actions">
                    <button class="action-btn primary" onclick="useBucket({{ $bucket['id'] }})" title="{{ __('injera.bucket_configurations.use_recipe') }}">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m-10-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ __('injera.bucket_configurations.use_recipe') }}
                    </button>
                    <button class="action-btn secondary" onclick="duplicateBucket({{ $bucket['id'] }})" title="{{ __('injera.bucket_configurations.duplicate') }}">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </button>
                    <button class="action-btn secondary" onclick="editBucket({{ $bucket['id'] }})" title="{{ __('injera.bucket_configurations.edit') }}">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button class="action-btn danger" onclick="deleteBucket({{ $bucket['id'] }})" title="{{ __('injera.bucket_configurations.delete') }}">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Create/Edit Bucket Modal -->
<div id="bucketModal" class="modal" style="display: none;">
    <div class="modal-overlay" onclick="closeBucketModal()"></div>
    <div class="modal-content large">
        <div class="modal-header">
            <h3 id="modalTitle">{{ __('injera.bucket_configurations.create_bucket') }}</h3>
            <button class="modal-close" onclick="closeBucketModal()">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form id="bucketForm" class="modal-form">
            <div class="form-section">
                <h4 class="section-title">{{ __('injera.bucket_configurations.basic_info') }}</h4>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="bucketName">{{ __('injera.bucket_configurations.bucket_name') }} *</label>
                        <input type="text" id="bucketName" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="bucketCapacity">{{ __('injera.bucket_configurations.capacity') }} ({{ __('injera.bucket_configurations.liters') }}) *</label>
                        <input type="number" id="bucketCapacity" name="capacity" step="0.1" min="1" required>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h4 class="section-title">{{ __('injera.bucket_configurations.flour_recipe') }}</h4>
                <div id="flourRecipe">
                    <div class="flour-recipe-item">
                        <div class="recipe-grid">
                            <div class="form-group">
                                <label>{{ __('injera.bucket_configurations.flour_type') }} *</label>
                                <select name="flour_recipe[0][flour_type]" class="flour-type-select" required onchange="updateFlourCost(this)">
                                    <option value="">{{ __('injera.bucket_configurations.select_flour') }}</option>
                                    @foreach($availableFlours as $flour)
                                        <option value="{{ $flour['type'] }}" data-price="{{ $flour['price_per_kg'] }}" data-stock="{{ $flour['available_stock'] }}">
                                            {{ $flour['type'] }} - ${{ number_format($flour['price_per_kg'], 2) }}/kg ({{ $flour['available_stock'] }}kg {{ __('injera.bucket_configurations.available') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>{{ __('injera.bucket_configurations.quantity') }} (kg) *</label>
                                <input type="number" name="flour_recipe[0][quantity]" step="0.1" min="0.1" class="flour-quantity" required onchange="calculateFlourCost(this)">
                            </div>
                            <div class="form-group">
                                <label>{{ __('injera.bucket_configurations.cost') }} ($)</label>
                                <input type="number" class="flour-cost" step="0.01" readonly>
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-danger remove-flour" onclick="removeFlourItem(this)" style="display: none;">
                                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary" onclick="addFlourItem()">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('injera.bucket_configurations.add_flour') }}
                </button>
                <div class="total-flour">
                    <strong>{{ __('injera.bucket_configurations.total_flour') }}: <span id="totalFlour">0</span> kg - {{ __('injera.bucket_configurations.total_cost') }}: $<span id="totalFlourCost">0.00</span></strong>
                </div>
            </div>

            <div class="form-section">
                <h4 class="section-title">{{ __('injera.bucket_configurations.water_requirements') }}</h4>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="coldWater">{{ __('injera.bucket_configurations.cold_water') }} ({{ __('injera.bucket_configurations.liters') }}) *</label>
                        <input type="number" id="coldWater" name="cold_water" step="0.1" min="0" required onchange="calculateTotalWater()">
                    </div>
                    
                    <div class="form-group">
                        <label for="hotWater">{{ __('injera.bucket_configurations.hot_water') }} ({{ __('injera.bucket_configurations.liters') }}) *</label>
                        <input type="number" id="hotWater" name="hot_water" step="0.1" min="0" required onchange="calculateTotalWater()">
                    </div>
                    
                    <div class="form-group">
                        <label>{{ __('injera.bucket_configurations.total_water') }} ({{ __('injera.bucket_configurations.liters') }})</label>
                        <input type="number" id="totalWater" readonly>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h4 class="section-title">{{ __('injera.bucket_configurations.production_details') }}</h4>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="expectedYield">{{ __('injera.bucket_configurations.expected_yield') }} *</label>
                        <input type="number" id="expectedYield" name="expected_yield" min="1" required onchange="calculateTotalCost()">
                    </div>
                    
                    <div class="form-group">
                        <label for="electricityCost">{{ __('injera.bucket_configurations.electricity_cost') }} ($) *</label>
                        <input type="number" id="electricityCost" name="electricity_cost" step="0.01" min="0" required onchange="calculateTotalCost()">
                    </div>
                    
                    <div class="form-group">
                        <label for="laborCost">{{ __('injera.bucket_configurations.labor_cost') }} ($) *</label>
                        <input type="number" id="laborCost" name="labor_cost" step="0.01" min="0" required onchange="calculateTotalCost()">
                    </div>
                </div>
                
                <div class="cost-summary">
                    <div class="summary-item">
                        <span class="summary-label">{{ __('injera.bucket_configurations.total_cost') }}:</span>
                        <span class="summary-value">$<span id="totalCost">0.00</span></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">{{ __('injera.bucket_configurations.cost_per_injera') }}:</span>
                        <span class="summary-value">$<span id="costPerInjera">0.000</span></span>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <div class="form-group">
                    <label for="bucketNotes">{{ __('injera.bucket_configurations.notes') }}</label>
                    <textarea id="bucketNotes" name="notes" rows="3"></textarea>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeBucketModal()">
                    {{ __('injera.bucket_configurations.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary">
                    {{ __('injera.bucket_configurations.save_bucket') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/admin/injera/bucket-configurations.js'])
@endpush
