@extends('layouts.admin')

@section('title', __('reports.financial.title') . ' - ' . config('app.name'))
@section('page_title', __('reports.financial.title'))

@push('styles')
    {{-- CSS styles will be loaded via main layout --}}
@endpush

@push('scripts')
    @vite('resources/js/admin/reports/financial-reports.js')
@endpush

@section('content')
<div class="reports-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('reports.financial.title') }}</h1>
                <p class="page-subtitle">{{ __('reports.financial.subtitle') }}</p>
            </div>
        </div>
    </div>

    <!-- Coming Soon Content -->
    <div class="coming-soon-container">
        <div class="coming-soon-card">
            <div class="coming-soon-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h2 class="coming-soon-title">{{ __('reports.coming_soon.title') }}</h2>
            <p class="coming-soon-description">{{ __('reports.coming_soon.description') }}</p>
            
            <div class="coming-soon-features">
                <h3 class="features-title">{{ __('reports.coming_soon.features_title') }}</h3>
                <ul class="features-list">
                    <li>{{ __('reports.financial.features.profit_loss') }}</li>
                    <li>{{ __('reports.financial.features.cost_breakdown') }}</li>
                    <li>{{ __('reports.financial.features.budget_analysis') }}</li>
                    <li>{{ __('reports.financial.features.cash_flow') }}</li>
                    <li>{{ __('reports.financial.features.tax_reports') }}</li>
                </ul>
            </div>
            
            <div class="coming-soon-timeline">
                <p class="timeline-text">{{ __('reports.coming_soon.timeline') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
