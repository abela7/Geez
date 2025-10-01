@extends('layouts.admin')

@section('title', __('reports.customers.title') . ' - ' . config('app.name'))
@section('page_title', __('reports.customers.title'))

@push('styles')
    {{-- CSS styles will be loaded via main layout --}}
@endpush

@push('scripts')
    @vite('resources/js/admin/reports/customer-reports.js')
@endpush

@section('content')
<div class="reports-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('reports.customers.title') }}</h1>
                <p class="page-subtitle">{{ __('reports.customers.subtitle') }}</p>
            </div>
        </div>
    </div>

    <!-- Coming Soon Content -->
    <div class="coming-soon-container">
        <div class="coming-soon-card">
            <div class="coming-soon-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h2 class="coming-soon-title">{{ __('reports.coming_soon.title') }}</h2>
            <p class="coming-soon-description">{{ __('reports.coming_soon.description') }}</p>
            
            <div class="coming-soon-features">
                <h3 class="features-title">{{ __('reports.coming_soon.features_title') }}</h3>
                <ul class="features-list">
                    <li>{{ __('reports.customers.features.customer_analytics') }}</li>
                    <li>{{ __('reports.customers.features.loyalty_performance') }}</li>
                    <li>{{ __('reports.customers.features.feedback_analysis') }}</li>
                    <li>{{ __('reports.customers.features.reservation_patterns') }}</li>
                    <li>{{ __('reports.customers.features.demographics') }}</li>
                </ul>
            </div>
            
            <div class="coming-soon-timeline">
                <p class="timeline-text">{{ __('reports.coming_soon.timeline') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
