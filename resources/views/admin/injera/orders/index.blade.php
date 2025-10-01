@extends('layouts.admin')

@section('title', __('injera.orders.title') . ' - ' . config('app.name'))
@section('page_title', __('injera.orders.title'))

@section('content')
<div class="admin-content">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <h1 class="page-title">{{ __('injera.orders.title') }}</h1>
            <p class="page-subtitle">{{ __('injera.orders.subtitle') }}</p>
        </div>
        <div class="page-header-actions">
            <button class="btn btn-secondary" onclick="exportOrders()">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                {{ __('injera.orders.export_orders') }}
            </button>
            <button class="btn btn-primary" onclick="openNewOrderModal()">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                {{ __('injera.orders.new_order') }}
            </button>
        </div>
    </div>

    <!-- Order Statistics -->
    <div class="statistics-grid">
        <div class="stat-card total">
            <div class="stat-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div class="stat-content">
                <h3 class="stat-title">{{ __('injera.orders.total_orders') }}</h3>
                <div class="stat-value">{{ $statistics['total_orders'] }}</div>
                <div class="stat-subtitle">{{ __('injera.orders.all_time') }}</div>
            </div>
        </div>

        <div class="stat-card pending">
            <div class="stat-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <h3 class="stat-title">{{ __('injera.orders.pending_orders') }}</h3>
                <div class="stat-value">{{ $statistics['pending_orders'] }}</div>
                <div class="stat-subtitle">{{ __('injera.orders.need_allocation') }}</div>
            </div>
        </div>

        <div class="stat-card ready">
            <div class="stat-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <h3 class="stat-title">{{ __('injera.orders.ready_orders') }}</h3>
                <div class="stat-value">{{ $statistics['ready_orders'] }}</div>
                <div class="stat-subtitle">{{ __('injera.orders.ready_for_pickup') }}</div>
            </div>
        </div>

        <div class="stat-card revenue">
            <div class="stat-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                </svg>
            </div>
            <div class="stat-content">
                <h3 class="stat-title">{{ __('injera.orders.total_revenue') }}</h3>
                <div class="stat-value">${{ number_format($statistics['total_revenue'], 2) }}</div>
                <div class="stat-subtitle">{{ __('injera.orders.this_period') }}</div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-container">
        <div class="filters-content">
            <div class="filter-tabs">
                <button class="filter-tab active" onclick="filterByStatus('')">
                    {{ __('injera.orders.all_orders') }}
                    <span class="tab-count">{{ $statistics['total_orders'] }}</span>
                </button>
                <button class="filter-tab" onclick="filterByStatus('pending')">
                    {{ __('injera.orders.pending') }}
                    <span class="tab-count">{{ $statistics['pending_orders'] }}</span>
                </button>
                <button class="filter-tab" onclick="filterByStatus('confirmed')">
                    {{ __('injera.orders.confirmed') }}
                    <span class="tab-count">{{ $statistics['confirmed_orders'] }}</span>
                </button>
                <button class="filter-tab" onclick="filterByStatus('ready')">
                    {{ __('injera.orders.ready') }}
                    <span class="tab-count">{{ $statistics['ready_orders'] }}</span>
                </button>
                <button class="filter-tab" onclick="filterByStatus('completed')">
                    {{ __('injera.orders.completed') }}
                    <span class="tab-count">{{ $statistics['completed_orders'] }}</span>
                </button>
            </div>

            <div class="filter-controls">
                <div class="search-filter">
                    <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" 
                           id="orderSearch" 
                           class="search-input" 
                           placeholder="{{ __('injera.orders.search_placeholder') }}"
                           onkeyup="searchOrders()">
                </div>

                <div class="filter-group">
                    <select id="priorityFilter" class="filter-select" onchange="filterOrders()">
                        <option value="">{{ __('injera.orders.all_priorities') }}</option>
                        <option value="urgent">{{ __('injera.orders.urgent') }}</option>
                        <option value="high">{{ __('injera.orders.high') }}</option>
                        <option value="normal">{{ __('injera.orders.normal') }}</option>
                    </select>

                    <select id="typeFilter" class="filter-select" onchange="filterOrders()">
                        <option value="">{{ __('injera.orders.all_types') }}</option>
                        <option value="delivery">{{ __('injera.orders.delivery') }}</option>
                        <option value="pickup">{{ __('injera.orders.pickup') }}</option>
                        <option value="dine_in">{{ __('injera.orders.dine_in') }}</option>
                    </select>

                    <button class="btn btn-secondary" onclick="clearFilters()">
                        {{ __('injera.orders.clear_filters') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders List -->
    <div class="orders-container">
        @foreach($orders as $order)
        <div class="order-card" data-order-id="{{ $order['id'] }}" data-status="{{ $order['status'] }}" data-priority="{{ $order['priority'] }}" data-type="{{ $order['order_type'] }}">
            <div class="order-header">
                <div class="order-info">
                    <h3 class="order-number">{{ $order['order_number'] }}</h3>
                    <div class="order-badges">
                        <span class="status-badge status-{{ $order['status'] }}">
                            {{ __('injera.orders.' . $order['status']) }}
                        </span>
                        <span class="priority-badge priority-{{ $order['priority'] }}">
                            {{ __('injera.orders.' . $order['priority']) }}
                        </span>
                        <span class="type-badge type-{{ $order['order_type'] }}">
                            {{ __('injera.orders.' . $order['order_type']) }}
                        </span>
                    </div>
                </div>
                <div class="order-actions">
                    <button class="action-btn edit" onclick="editOrder({{ $order['id'] }})" title="{{ __('injera.orders.edit_order') }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    @if($order['status'] !== 'completed' && $order['status'] !== 'cancelled')
                    <button class="action-btn allocate" onclick="openAllocationModal({{ $order['id'] }})" title="{{ __('injera.orders.allocate_injera') }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </button>
                    @endif
                    <button class="action-btn status" onclick="openStatusModal({{ $order['id'] }})" title="{{ __('injera.orders.update_status') }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="order-content">
                <div class="customer-details">
                    <h4 class="customer-name">{{ $order['customer_name'] }}</h4>
                    <div class="customer-contact">
                        <span class="phone">{{ $order['customer_phone'] }}</span>
                        @if($order['customer_email'])
                        <span class="email">{{ $order['customer_email'] }}</span>
                        @endif
                    </div>
                </div>

                <div class="order-details">
                    <div class="detail-row">
                        <span class="detail-label">{{ __('injera.orders.quality_grade') }}:</span>
                        <span class="quality-badge quality-{{ strtolower($order['quality_grade']) }}">
                            {{ $order['quality_grade'] === 'mixed' ? __('injera.orders.mixed') : $order['quality_grade'] }}
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">{{ __('injera.orders.quantity') }}:</span>
                        <span class="detail-value">{{ $order['quantity'] }} {{ __('injera.orders.pieces') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">{{ __('injera.orders.total_amount') }}:</span>
                        <span class="detail-value amount">${{ number_format($order['total_amount'], 2) }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">{{ __('injera.orders.delivery_date') }}:</span>
                        <span class="detail-value">{{ \Carbon\Carbon::parse($order['delivery_date'])->format('M j, Y') }} {{ $order['delivery_time'] }}</span>
                    </div>
                    @if($order['delivery_address'])
                    <div class="detail-row">
                        <span class="detail-label">{{ __('injera.orders.address') }}:</span>
                        <span class="detail-value">{{ $order['delivery_address'] }}</span>
                    </div>
                    @endif
                </div>

                <!-- Allocation Progress -->
                <div class="allocation-progress">
                    <div class="progress-header">
                        <span class="progress-label">{{ __('injera.orders.allocation_progress') }}</span>
                        <span class="progress-value">{{ $order['allocated_quantity'] }}/{{ $order['quantity'] }}</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ ($order['allocated_quantity'] / $order['quantity']) * 100 }}%"></div>
                    </div>
                    @if($order['remaining_quantity'] > 0)
                    <div class="remaining-notice">
                        {{ $order['remaining_quantity'] }} {{ __('injera.orders.pieces_remaining') }}
                    </div>
                    @endif
                </div>

                <!-- Allocation Details -->
                @if(count($order['allocation_details']) > 0)
                <div class="allocation-details">
                    <h5>{{ __('injera.orders.allocated_from') }}:</h5>
                    <div class="allocation-list">
                        @foreach($order['allocation_details'] as $allocation)
                        <div class="allocation-item">
                            <span class="batch-number">{{ $allocation['batch_number'] }}</span>
                            <span class="allocation-quantity">{{ $allocation['quantity'] }} {{ __('injera.orders.pieces') }}</span>
                            <span class="quality-badge quality-{{ strtolower($allocation['quality']) }}">{{ $allocation['quality'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($order['special_instructions'])
                <div class="special-instructions">
                    <h5>{{ __('injera.orders.special_instructions') }}:</h5>
                    <p>{{ $order['special_instructions'] }}</p>
                </div>
                @endif
            </div>

            <div class="order-footer">
                <div class="order-meta">
                    <span class="created-date">{{ __('injera.orders.created') }}: {{ \Carbon\Carbon::parse($order['created_at'])->format('M j, Y H:i') }}</span>
                </div>
                <div class="quick-actions">
                    @if($order['status'] === 'pending')
                    <button class="quick-btn confirm" onclick="quickStatusUpdate({{ $order['id'] }}, 'confirmed')">
                        {{ __('injera.orders.confirm') }}
                    </button>
                    @elseif($order['status'] === 'confirmed')
                    <button class="quick-btn prepare" onclick="quickStatusUpdate({{ $order['id'] }}, 'preparing')">
                        {{ __('injera.orders.start_preparing') }}
                    </button>
                    @elseif($order['status'] === 'preparing')
                    <button class="quick-btn ready" onclick="quickStatusUpdate({{ $order['id'] }}, 'ready')">
                        {{ __('injera.orders.mark_ready') }}
                    </button>
                    @elseif($order['status'] === 'ready')
                    <button class="quick-btn complete" onclick="quickStatusUpdate({{ $order['id'] }}, 'completed')">
                        {{ __('injera.orders.complete_order') }}
                    </button>
                    @endif
                    @if($order['status'] !== 'completed' && $order['status'] !== 'cancelled')
                    <button class="quick-btn cancel" onclick="openCancelModal({{ $order['id'] }})">
                        {{ __('injera.orders.cancel') }}
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- New Order Modal -->
<div id="newOrderModal" class="modal">
    <div class="modal-content large">
        <div class="modal-header">
            <h3>{{ __('injera.orders.new_order') }}</h3>
            <button class="modal-close" onclick="closeNewOrderModal()">&times;</button>
        </div>
        <form id="newOrderForm" onsubmit="createOrder(event)">
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="customerName">{{ __('injera.orders.customer_name') }} *</label>
                        <input type="text" id="customerName" name="customer_name" required>
                    </div>
                    <div class="form-group">
                        <label for="customerPhone">{{ __('injera.orders.customer_phone') }} *</label>
                        <input type="tel" id="customerPhone" name="customer_phone" required>
                    </div>
                    <div class="form-group">
                        <label for="customerEmail">{{ __('injera.orders.customer_email') }}</label>
                        <input type="email" id="customerEmail" name="customer_email">
                    </div>
                    <div class="form-group">
                        <label for="orderType">{{ __('injera.orders.order_type') }} *</label>
                        <select id="orderType" name="order_type" required onchange="toggleDeliveryFields()">
                            <option value="">{{ __('injera.orders.select_type') }}</option>
                            <option value="pickup">{{ __('injera.orders.pickup') }}</option>
                            <option value="delivery">{{ __('injera.orders.delivery') }}</option>
                            <option value="dine_in">{{ __('injera.orders.dine_in') }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="qualityGrade">{{ __('injera.orders.quality_grade') }} *</label>
                        <select id="qualityGrade" name="quality_grade" required>
                            <option value="">{{ __('injera.orders.select_quality') }}</option>
                            <option value="A">{{ __('injera.orders.grade_a') }}</option>
                            <option value="B">{{ __('injera.orders.grade_b') }}</option>
                            <option value="C">{{ __('injera.orders.grade_c') }}</option>
                            <option value="mixed">{{ __('injera.orders.mixed') }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quantity">{{ __('injera.orders.quantity') }} *</label>
                        <input type="number" id="quantity" name="quantity" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="deliveryDate">{{ __('injera.orders.delivery_date') }} *</label>
                        <input type="date" id="deliveryDate" name="delivery_date" required>
                    </div>
                    <div class="form-group">
                        <label for="deliveryTime">{{ __('injera.orders.delivery_time') }}</label>
                        <input type="time" id="deliveryTime" name="delivery_time">
                    </div>
                    <div class="form-group delivery-only" style="display: none;">
                        <label for="deliveryAddress">{{ __('injera.orders.delivery_address') }}</label>
                        <textarea id="deliveryAddress" name="delivery_address" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="priority">{{ __('injera.orders.priority') }} *</label>
                        <select id="priority" name="priority" required>
                            <option value="normal">{{ __('injera.orders.normal') }}</option>
                            <option value="high">{{ __('injera.orders.high') }}</option>
                            <option value="urgent">{{ __('injera.orders.urgent') }}</option>
                        </select>
                    </div>
                    <div class="form-group full-width">
                        <label for="specialInstructions">{{ __('injera.orders.special_instructions') }}</label>
                        <textarea id="specialInstructions" name="special_instructions" rows="3"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeNewOrderModal()">
                    {{ __('injera.orders.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary">
                    {{ __('injera.orders.create_order') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Allocation Modal -->
<div id="allocationModal" class="modal">
    <div class="modal-content large">
        <div class="modal-header">
            <h3>{{ __('injera.orders.allocate_injera') }}</h3>
            <button class="modal-close" onclick="closeAllocationModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="allocation-summary">
                <div class="summary-item">
                    <span class="summary-label">{{ __('injera.orders.order') }}:</span>
                    <span id="allocationOrderNumber" class="summary-value"></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">{{ __('injera.orders.needed') }}:</span>
                    <span id="allocationNeeded" class="summary-value"></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">{{ __('injera.orders.allocated') }}:</span>
                    <span id="allocationAllocated" class="summary-value">0</span>
                </div>
            </div>
            
            <div class="available-stock">
                <h4>{{ __('injera.orders.available_stock') }}</h4>
                <div id="stockList" class="stock-list">
                    @foreach($availableStock as $stock)
                    <div class="stock-item" data-stock-id="{{ $stock['stock_id'] }}">
                        <div class="stock-info">
                            <span class="batch-number">{{ $stock['batch_number'] }}</span>
                            <span class="quality-badge quality-{{ strtolower($stock['quality_grade']) }}">{{ $stock['quality_grade'] }}</span>
                            <span class="available-qty">{{ $stock['available_quantity'] }} {{ __('injera.orders.available') }}</span>
                            <span class="expiry-info {{ $stock['days_until_expiry'] <= 1 ? 'urgent' : ($stock['days_until_expiry'] <= 3 ? 'warning' : 'normal') }}">
                                {{ $stock['days_until_expiry'] }} {{ __('injera.orders.days_left') }}
                            </span>
                        </div>
                        <div class="allocation-input">
                            <input type="number" 
                                   class="allocation-quantity" 
                                   min="0" 
                                   max="{{ $stock['available_quantity'] }}" 
                                   value="0"
                                   onchange="updateAllocationTotal()">
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeAllocationModal()">
                {{ __('injera.orders.cancel') }}
            </button>
            <button type="button" class="btn btn-primary" onclick="saveAllocation()">
                {{ __('injera.orders.allocate') }}
            </button>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div id="statusModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>{{ __('injera.orders.update_status') }}</h3>
            <button class="modal-close" onclick="closeStatusModal()">&times;</button>
        </div>
        <form id="statusForm" onsubmit="updateOrderStatus(event)">
            <div class="modal-body">
                <div class="form-group">
                    <label for="orderStatus">{{ __('injera.orders.status') }} *</label>
                    <select id="orderStatus" name="status" required>
                        <option value="pending">{{ __('injera.orders.pending') }}</option>
                        <option value="confirmed">{{ __('injera.orders.confirmed') }}</option>
                        <option value="preparing">{{ __('injera.orders.preparing') }}</option>
                        <option value="ready">{{ __('injera.orders.ready') }}</option>
                        <option value="completed">{{ __('injera.orders.completed') }}</option>
                        <option value="cancelled">{{ __('injera.orders.cancelled') }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="statusNotes">{{ __('injera.orders.notes') }}</label>
                    <textarea id="statusNotes" name="notes" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeStatusModal()">
                    {{ __('injera.orders.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary">
                    {{ __('injera.orders.update_status') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Cancel Order Modal -->
<div id="cancelModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>{{ __('injera.orders.cancel_order') }}</h3>
            <button class="modal-close" onclick="closeCancelModal()">&times;</button>
        </div>
        <form id="cancelForm" onsubmit="cancelOrder(event)">
            <div class="modal-body">
                <div class="form-group">
                    <label for="cancellationReason">{{ __('injera.orders.cancellation_reason') }} *</label>
                    <textarea id="cancellationReason" name="cancellation_reason" rows="4" required 
                              placeholder="{{ __('injera.orders.reason_placeholder') }}"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeCancelModal()">
                    {{ __('injera.orders.keep_order') }}
                </button>
                <button type="submit" class="btn btn-danger">
                    {{ __('injera.orders.cancel_order') }}
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('styles')
@vite(['resources/css/admin/injera/orders.css'])
@endpush

@push('scripts')
@vite(['resources/js/admin/injera/orders.js'])
<script>
    // Pass data to JavaScript
    window.availableStock = @json($availableStock);
    window.orderStatistics = @json($statistics);
</script>
@endpush
