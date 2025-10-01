@extends('layouts.admin')

@section('title', __('injera.production_batches.title') . ' - ' . config('app.name'))
@section('page_title', __('injera.production_batches.title'))

@push('styles')
@vite(['resources/css/admin/injera/production-batches.css'])
@endpush

@section('content')
<div class="production-batches-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">{{ __('injera.production_batches.title') }}</h1>
            <p class="page-subtitle">{{ __('injera.production_batches.subtitle') }}</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-secondary" onclick="exportBatches()">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                {{ __('injera.production_batches.export_batches') }}
            </button>
            <button class="btn btn-primary" onclick="startNewBatch()">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                {{ __('injera.production_batches.start_new_batch') }}
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card">
            <div class="summary-card-header">
                <h3 class="summary-card-title">{{ __('injera.production_batches.active_batches') }}</h3>
                <svg class="summary-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <p class="summary-card-value">{{ $statistics['active_batches'] }}</p>
        </div>

        <div class="summary-card">
            <div class="summary-card-header">
                <h3 class="summary-card-title">{{ __('injera.production_batches.completed_this_week') }}</h3>
                <svg class="summary-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="summary-card-value">{{ $statistics['completed_this_week'] }}</p>
        </div>

        <div class="summary-card">
            <div class="summary-card-header">
                <h3 class="summary-card-title">{{ __('injera.production_batches.total_injera_produced') }}</h3>
                <svg class="summary-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <p class="summary-card-value">{{ number_format($statistics['total_injera_produced']) }}</p>
        </div>

        <div class="summary-card">
            <div class="summary-card-header">
                <h3 class="summary-card-title">{{ __('injera.production_batches.avg_batch_time') }}</h3>
                <svg class="summary-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="summary-card-value">{{ $statistics['avg_batch_time'] }}d</p>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <button class="tab-btn active" data-filter="all" onclick="filterBatches('all')">
            {{ __('injera.production_batches.all_batches') }}
        </button>
        <button class="tab-btn" data-filter="active" onclick="filterBatches('active')">
            {{ __('injera.production_batches.active') }}
        </button>
        <button class="tab-btn" data-filter="completed" onclick="filterBatches('completed')">
            {{ __('injera.production_batches.completed') }}
        </button>
        <button class="tab-btn" data-filter="planning" onclick="filterBatches('planning')">
            {{ __('injera.production_batches.planning') }}
        </button>
    </div>

    <!-- Production Batches Grid -->
    <div class="batches-section">
        <div class="batches-grid">
            @foreach($batches as $batch)
            <div class="batch-card {{ $batch['status'] }}" data-batch-id="{{ $batch['id'] }}" data-status="{{ $batch['status'] }}">
                <!-- Batch Header -->
                <div class="batch-header">
                    <div class="batch-info">
                        <h3 class="batch-name">{{ $batch['batch_name'] }}</h3>
                        <div class="batch-meta">
                            <span class="batch-number">{{ $batch['batch_number'] }}</span>
                            <span class="batch-recipe">{{ $batch['bucket_name'] }}</span>
                        </div>
                    </div>
                    <div class="batch-status">
                        <span class="status-badge status-{{ $batch['status'] }}">
                            {{ __('injera.production_batches.' . $batch['status']) }}
                        </span>
                        @if($batch['priority'] === 'high')
                            <span class="priority-badge high">{{ __('injera.production_batches.high_priority') }}</span>
                        @elseif($batch['priority'] === 'medium')
                            <span class="priority-badge medium">{{ __('injera.production_batches.medium_priority') }}</span>
                        @elseif($batch['priority'] === 'normal')
                            <span class="priority-badge normal">{{ __('injera.production_batches.normal_priority') }}</span>
                        @endif
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="progress-section">
                    <div class="progress-info">
                        <span class="progress-label">{{ __('injera.production_batches.progress') }}</span>
                        <span class="progress-percentage">{{ $batch['stage_progress'] }}%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $batch['stage_progress'] }}%"></div>
                    </div>
                    <div class="current-stage">
                        {{ __('injera.production_batches.current_stage') }}: 
                        <strong>{{ __('injera.production_batches.stage_' . $batch['current_stage']) }}</strong>
                    </div>
                </div>

                <!-- 5-Stage Timeline -->
                <div class="stages-timeline">
                    <div class="stage-item {{ $batch['stages']['buy_flour']['status'] }}" data-stage="buy_flour">
                        <div class="stage-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                        <div class="stage-content">
                            <h4 class="stage-title">{{ __('injera.production_batches.stage_buy_flour') }}</h4>
                            @if(isset($batch['stages']['buy_flour']['completed_at']) && $batch['stages']['buy_flour']['completed_at'])
                                <span class="stage-time">{{ \Carbon\Carbon::parse($batch['stages']['buy_flour']['completed_at'])->format('M j, H:i') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="stage-item {{ $batch['stages']['mixing']['status'] }}" data-stage="mixing">
                        <div class="stage-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </div>
                        <div class="stage-content">
                            <h4 class="stage-title">{{ __('injera.production_batches.stage_mixing') }}</h4>
                            @if(isset($batch['stages']['mixing']['completed_at']) && $batch['stages']['mixing']['completed_at'])
                                <span class="stage-time">{{ \Carbon\Carbon::parse($batch['stages']['mixing']['completed_at'])->format('M j, H:i') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="stage-item {{ $batch['stages']['fermentation']['status'] }}" data-stage="fermentation">
                        <div class="stage-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="stage-content">
                            <h4 class="stage-title">{{ __('injera.production_batches.stage_fermentation') }}</h4>
                            @if($batch['stages']['fermentation']['status'] === 'in_progress' && isset($batch['stages']['fermentation']['started_at']))
                                <span class="stage-time">{{ __('injera.production_batches.day') }} {{ \Carbon\Carbon::parse($batch['stages']['fermentation']['started_at'])->diffInDays(now()) + 1 }}</span>
                            @elseif(isset($batch['stages']['fermentation']['completed_at']) && $batch['stages']['fermentation']['completed_at'])
                                <span class="stage-time">{{ \Carbon\Carbon::parse($batch['stages']['fermentation']['completed_at'])->format('M j, H:i') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="stage-item {{ $batch['stages']['hot_water']['status'] }}" data-stage="hot_water">
                        <div class="stage-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                        </div>
                        <div class="stage-content">
                            <h4 class="stage-title">{{ __('injera.production_batches.stage_hot_water') }}</h4>
                            @if(isset($batch['stages']['hot_water']['completed_at']) && $batch['stages']['hot_water']['completed_at'])
                                <span class="stage-time">{{ \Carbon\Carbon::parse($batch['stages']['hot_water']['completed_at'])->format('M j, H:i') }}</span>
                            @elseif(isset($batch['stages']['hot_water']['planned_at']) && $batch['stages']['hot_water']['planned_at'])
                                <span class="stage-time">{{ \Carbon\Carbon::parse($batch['stages']['hot_water']['planned_at'])->format('M j, H:i') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="stage-item {{ $batch['stages']['baking']['status'] }}" data-stage="baking">
                        <div class="stage-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                            </svg>
                        </div>
                        <div class="stage-content">
                            <h4 class="stage-title">{{ __('injera.production_batches.stage_baking') }}</h4>
                            @if(isset($batch['stages']['baking']['completed_at']) && $batch['stages']['baking']['completed_at'])
                                <span class="stage-time">{{ \Carbon\Carbon::parse($batch['stages']['baking']['completed_at'])->format('M j, H:i') }}</span>
                            @elseif(isset($batch['stages']['baking']['planned_at']) && $batch['stages']['baking']['planned_at'])
                                <span class="stage-time">{{ \Carbon\Carbon::parse($batch['stages']['baking']['planned_at'])->format('M j, H:i') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Batch Details -->
                <div class="batch-details">
                    <div class="detail-row">
                        <span class="detail-label">{{ __('injera.production_batches.baker') }}:</span>
                        <span class="detail-value">{{ $batch['baker_assigned'] ?? __('injera.production_batches.not_assigned') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">{{ __('injera.production_batches.expected_yield') }}:</span>
                        <span class="detail-value">{{ $batch['expected_yield'] }} {{ __('injera.production_batches.injeras') }}</span>
                    </div>
                    @if($batch['actual_yield'])
                    <div class="detail-row">
                        <span class="detail-label">{{ __('injera.production_batches.actual_yield') }}:</span>
                        <span class="detail-value {{ $batch['actual_yield'] >= $batch['expected_yield'] ? 'success' : 'warning' }}">
                            {{ $batch['actual_yield'] }} {{ __('injera.production_batches.injeras') }}
                        </span>
                    </div>
                    @endif
                    <div class="detail-row">
                        <span class="detail-label">{{ __('injera.production_batches.cost_per_injera') }}:</span>
                        <span class="detail-value">${{ number_format($batch['cost_per_injera'], 3) }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">{{ __('injera.production_batches.completion_date') }}:</span>
                        <span class="detail-value">
                            @if($batch['status'] === 'completed' && isset($batch['completion_date']))
                                {{ \Carbon\Carbon::parse($batch['completion_date'])->format('M j, Y') }}
                            @elseif(isset($batch['estimated_completion']))
                                {{ \Carbon\Carbon::parse($batch['estimated_completion'])->format('M j, Y') }}
                            @else
                                {{ __('injera.production_batches.not_scheduled') }}
                            @endif
                        </span>
                    </div>
                </div>

                @if($batch['notes'])
                <div class="batch-notes">
                    <p class="notes-text">{{ $batch['notes'] }}</p>
                </div>
                @endif

                <!-- Batch Actions -->
                <div class="batch-actions">
                    @if($batch['status'] === 'active')
                        <button class="action-btn primary" onclick="updateStage({{ $batch['id'] }})" title="{{ __('injera.production_batches.update_stage') }}">
                            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            {{ __('injera.production_batches.next_stage') }}
                        </button>
                    @endif
                    
                    @if($batch['status'] === 'active' && $batch['current_stage'] === 'baking')
                        <button class="action-btn success" onclick="completeBatch({{ $batch['id'] }})" title="{{ __('injera.production_batches.complete_batch') }}">
                            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ __('injera.production_batches.complete') }}
                        </button>
                    @endif
                    
                    <button class="action-btn secondary" onclick="viewBatchDetails({{ $batch['id'] }})" title="{{ __('injera.production_batches.view_details') }}">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </button>
                    
                    @if($batch['status'] === 'active')
                        <button class="action-btn danger" onclick="cancelBatch({{ $batch['id'] }})" title="{{ __('injera.production_batches.cancel_batch') }}">
                            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Start New Batch Modal -->
<div id="newBatchModal" class="modal" style="display: none;">
    <div class="modal-overlay" onclick="closeNewBatchModal()"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3>{{ __('injera.production_batches.start_new_batch') }}</h3>
            <button class="modal-close" onclick="closeNewBatchModal()">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form id="newBatchForm" class="modal-form">
            <div class="form-grid">
                <div class="form-group">
                    <label for="batchName">{{ __('injera.production_batches.batch_name') }} *</label>
                    <input type="text" id="batchName" name="batch_name" required>
                </div>
                
                <div class="form-group">
                    <label for="bucketConfiguration">{{ __('injera.production_batches.bucket_configuration') }} *</label>
                    <select id="bucketConfiguration" name="bucket_configuration_id" required onchange="updateBucketDetails()">
                        <option value="">{{ __('injera.production_batches.select_bucket') }}</option>
                        @foreach($bucketConfigurations as $config)
                            <option value="{{ $config['id'] }}" 
                                    data-capacity="{{ $config['capacity'] }}" 
                                    data-yield="{{ $config['expected_yield'] }}">
                                {{ $config['name'] }} ({{ $config['capacity'] }}L → {{ $config['expected_yield'] }} {{ __('injera.production_batches.injeras') }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="plannedStartDate">{{ __('injera.production_batches.planned_start_date') }} *</label>
                    <input type="date" id="plannedStartDate" name="planned_start_date" required>
                </div>
                
                <div class="form-group">
                    <label for="bakerAssigned">{{ __('injera.production_batches.baker_assigned') }}</label>
                    <input type="text" id="bakerAssigned" name="baker_assigned" list="bakersList">
                    <datalist id="bakersList">
                        <option value="Almaz Tadesse">
                        <option value="Meseret Alemu">
                        <option value="Tigist Bekele">
                        <option value="Hanan Mohammed">
                        <option value="Selamawit Desta">
                    </datalist>
                </div>
            </div>
            
            <div id="bucketPreview" class="bucket-preview" style="display: none;">
                <h4>{{ __('injera.production_batches.recipe_preview') }}</h4>
                <div id="bucketDetails"></div>
            </div>
            
            <div class="form-group">
                <label for="batchNotes">{{ __('injera.production_batches.notes') }}</label>
                <textarea id="batchNotes" name="notes" rows="3"></textarea>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeNewBatchModal()">
                    {{ __('injera.production_batches.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary">
                    {{ __('injera.production_batches.start_batch') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Update Stage Modal -->
<div id="stageModal" class="modal" style="display: none;">
    <div class="modal-overlay" onclick="closeStageModal()"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="stageModalTitle">{{ __('injera.production_batches.update_stage') }}</h3>
            <button class="modal-close" onclick="closeStageModal()">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form id="stageForm" class="modal-form">
            <input type="hidden" id="stageBatchId" name="batch_id">
            <input type="hidden" id="stageType" name="stage">
            
            <div class="form-group">
                <label for="stageStatus">{{ __('injera.production_batches.stage_status') }} *</label>
                <select id="stageStatus" name="status" required>
                    <option value="in_progress">{{ __('injera.production_batches.in_progress') }}</option>
                    <option value="completed">{{ __('injera.production_batches.completed') }}</option>
                </select>
            </div>
            
            <div class="form-group" id="actualYieldGroup" style="display: none;">
                <label for="actualYield">{{ __('injera.production_batches.actual_yield') }} *</label>
                <input type="number" id="actualYield" name="actual_yield" min="1">
            </div>
            
            <div class="form-group">
                <label for="stageNotes">{{ __('injera.production_batches.stage_notes') }}</label>
                <textarea id="stageNotes" name="notes" rows="3"></textarea>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeStageModal()">
                    {{ __('injera.production_batches.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary">
                    {{ __('injera.production_batches.update_stage') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Complete Batch Modal -->
<div id="completeBatchModal" class="modal" style="display: none;">
    <div class="modal-overlay" onclick="closeCompleteBatchModal()"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3>{{ __('injera.production_batches.complete_batch') }}</h3>
            <button class="modal-close" onclick="closeCompleteBatchModal()">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form id="completeBatchForm" class="modal-form">
            <input type="hidden" id="completeBatchId" name="batch_id">
            
            <div class="form-group">
                <label for="finalActualYield">{{ __('injera.production_batches.actual_yield') }} *</label>
                <input type="number" id="finalActualYield" name="actual_yield" min="1" required>
                <small class="form-hint">{{ __('injera.production_batches.actual_yield_hint') }}</small>
            </div>
            
            <div class="form-group">
                <label for="qualityNotes">{{ __('injera.production_batches.quality_notes') }}</label>
                <textarea id="qualityNotes" name="quality_notes" rows="4" placeholder="{{ __('injera.production_batches.quality_notes_placeholder') }}"></textarea>
            </div>
            
            <div class="completion-summary" id="completionSummary">
                <!-- Will be populated by JavaScript -->
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeCompleteBatchModal()">
                    {{ __('injera.production_batches.cancel') }}
                </button>
                <button type="submit" class="btn btn-success">
                    {{ __('injera.production_batches.complete_batch') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/admin/injera/production-batches.js'])
@endpush
