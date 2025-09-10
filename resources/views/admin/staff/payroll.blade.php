@extends('layouts.admin')

@section('title', __('staff.payroll.title') . ' - ' . config('app.name'))
@section('page_title', __('staff.payroll.title'))

@push('styles')
@vite(['resources/css/admin/staff-payroll.css'])
@endpush

@push('scripts')
@vite(['resources/js/admin/staff-payroll.js'])
@endpush

@section('content')
<div class="payroll-container" x-data="payrollManager()" x-init="init()">
    <!-- Page Header -->
    <div class="payroll-header">
        <div class="payroll-header-content">
            <div class="payroll-title-section">
                <h1 class="page-title">{{ __('staff.payroll.title') }}</h1>
                <p class="page-subtitle">{{ __('staff.payroll.subtitle') }}</p>
            </div>
            
            <!-- Primary Actions -->
            <div class="payroll-header-actions">
                <button @click="openPayrollModal()" class="btn btn-primary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('staff.payroll.process_payroll') }}
                </button>
                
                <div class="payroll-view-toggle" role="tablist" aria-label="{{ __('staff.payroll.view_modes') }}">
                    <button @click="setView('dashboard')" 
                            :class="{ 'active': currentView === 'dashboard' }"
                            class="view-toggle-btn"
                            role="tab"
                            :aria-selected="currentView === 'dashboard'"
                            aria-controls="dashboard-view">
                        <svg class="view-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        {{ __('staff.payroll.dashboard_view') }}
                    </button>
                    
                    <button @click="setView('employees')" 
                            :class="{ 'active': currentView === 'employees' }"
                            class="view-toggle-btn"
                            role="tab"
                            :aria-selected="currentView === 'employees'"
                            aria-controls="employees-view">
                        <svg class="view-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        {{ __('staff.payroll.employees_view') }}
                    </button>
                    
                    <button @click="setView('history')" 
                            :class="{ 'active': currentView === 'history' }"
                            class="view-toggle-btn"
                            role="tab"
                            :aria-selected="currentView === 'history'"
                            aria-controls="history-view">
                        <svg class="view-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ __('staff.payroll.history_view') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard View -->
    <div x-show="currentView === 'dashboard'" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-4"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         id="dashboard-view"
         role="tabpanel">
        
        <!-- Payroll Statistics -->
        <div class="payroll-stats-grid">
            <div class="stat-card stat-card--primary">
                <div class="stat-card-header">
                    <div class="stat-card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="stat-card-label">{{ __('staff.payroll.total_payroll') }}</span>
                </div>
                <div class="stat-card-value" x-text="formatCurrency(payrollStats.totalPayroll)">$0</div>
                <div class="stat-card-trend stat-card-trend--positive">
                    <svg class="trend-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    <span>+5.2% {{ __('staff.payroll.this_month') }}</span>
                </div>
            </div>

            <div class="stat-card stat-card--success">
                <div class="stat-card-header">
                    <div class="stat-card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <span class="stat-card-label">{{ __('staff.payroll.employees_paid') }}</span>
                </div>
                <div class="stat-card-value" x-text="payrollStats.employeesPaid">0</div>
                <div class="stat-card-trend stat-card-trend--neutral">
                    <svg class="trend-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                    </svg>
                    <span>{{ __('staff.payroll.no_change') }}</span>
                </div>
            </div>

            <div class="stat-card stat-card--warning">
                <div class="stat-card-header">
                    <div class="stat-card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <span class="stat-card-label">{{ __('staff.payroll.pending_approvals') }}</span>
                </div>
                <div class="stat-card-value" x-text="payrollStats.pendingApprovals">0</div>
                <div class="stat-card-trend stat-card-trend--negative">
                    <svg class="trend-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                    </svg>
                    <span>+3 {{ __('staff.payroll.this_week') }}</span>
                </div>
            </div>

            <div class="stat-card stat-card--info">
                <div class="stat-card-header">
                    <div class="stat-card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="stat-card-label">{{ __('staff.payroll.avg_salary') }}</span>
                </div>
                <div class="stat-card-value" x-text="formatCurrency(payrollStats.avgSalary)">$0</div>
                <div class="stat-card-trend stat-card-trend--positive">
                    <svg class="trend-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    <span>+2.1% {{ __('staff.payroll.this_month') }}</span>
                </div>
            </div>
        </div>

        @include('admin.staff.partials.payroll-dashboard-content')
    </div>

    @include('admin.staff.partials.payroll-employees')
    @include('admin.staff.partials.payroll-history')
    @include('admin.staff.partials.payroll-modals')
</div>
@endsection