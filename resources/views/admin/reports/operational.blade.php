@extends('layouts.admin')

@section('title', __('reports.operational.title') . ' - ' . config('app.name'))
@section('page_title', __('reports.operational.title'))

@push('styles')
    {{-- CSS styles will be loaded via main layout --}}
@endpush

@push('scripts')
    @vite('resources/js/admin/reports/operational-reports.js')
@endpush

@section('content')
<div class="reports-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('reports.operational.title') }}</h1>
                <p class="page-subtitle">{{ __('reports.operational.subtitle') }}</p>
            </div>
        </div>
    </div>

    <!-- Coming Soon Content -->
    <div class="coming-soon-container">
        <div class="coming-soon-card">
            <div class="coming-soon-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <h2 class="coming-soon-title">{{ __('reports.coming_soon.title') }}</h2>
            <p class="coming-soon-description">{{ __('reports.coming_soon.description') }}</p>
            
            <div class="coming-soon-features">
                <h3 class="features-title">{{ __('reports.coming_soon.features_title') }}</h3>
                <ul class="features-list">
                    <li>{{ __('reports.operational.features.table_utilization') }}</li>
                    <li>{{ __('reports.operational.features.peak_hours') }}</li>
                    <li>{{ __('reports.operational.features.service_efficiency') }}</li>
                    <li>{{ __('reports.operational.features.reservation_analysis') }}</li>
                    <li>{{ __('reports.operational.features.capacity_planning') }}</li>
                </ul>
            </div>
            
            <div class="coming-soon-timeline">
                <p class="timeline-text">{{ __('reports.coming_soon.timeline') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
