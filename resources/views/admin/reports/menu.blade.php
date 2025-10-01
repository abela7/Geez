@extends('layouts.admin')

@section('title', __('reports.menu.title') . ' - ' . config('app.name'))
@section('page_title', __('reports.menu.title'))

@push('styles')
    {{-- CSS styles will be loaded via main layout --}}
@endpush

@push('scripts')
    @vite('resources/js/admin/reports/menu-reports.js')
@endpush

@section('content')
<div class="reports-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('reports.menu.title') }}</h1>
                <p class="page-subtitle">{{ __('reports.menu.subtitle') }}</p>
            </div>
        </div>
    </div>

    <!-- Coming Soon Content -->
    <div class="coming-soon-container">
        <div class="coming-soon-card">
            <div class="coming-soon-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <h2 class="coming-soon-title">{{ __('reports.coming_soon.title') }}</h2>
            <p class="coming-soon-description">{{ __('reports.coming_soon.description') }}</p>
            
            <div class="coming-soon-features">
                <h3 class="features-title">{{ __('reports.coming_soon.features_title') }}</h3>
                <ul class="features-list">
                    <li>{{ __('reports.menu.features.item_popularity') }}</li>
                    <li>{{ __('reports.menu.features.category_analysis') }}</li>
                    <li>{{ __('reports.menu.features.pricing_insights') }}</li>
                    <li>{{ __('reports.menu.features.seasonal_trends') }}</li>
                    <li>{{ __('reports.menu.features.profit_margins') }}</li>
                </ul>
            </div>
            
            <div class="coming-soon-timeline">
                <p class="timeline-text">{{ __('reports.coming_soon.timeline') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
