@extends('layouts.admin')

@section('title', __('reports.staff.title') . ' - ' . config('app.name'))
@section('page_title', __('reports.staff.title'))

@push('styles')
    {{-- CSS styles will be loaded via main layout --}}
@endpush

@push('scripts')
    @vite('resources/js/admin/reports/staff-reports.js')
@endpush

@section('content')
<div class="reports-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('reports.staff.title') }}</h1>
                <p class="page-subtitle">{{ __('reports.staff.subtitle') }}</p>
            </div>
        </div>
    </div>

    <!-- Coming Soon Content -->
    <div class="coming-soon-container">
        <div class="coming-soon-card">
            <div class="coming-soon-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h2 class="coming-soon-title">{{ __('reports.coming_soon.title') }}</h2>
            <p class="coming-soon-description">{{ __('reports.coming_soon.description') }}</p>
            
            <div class="coming-soon-features">
                <h3 class="features-title">{{ __('reports.coming_soon.features_title') }}</h3>
                <ul class="features-list">
                    <li>{{ __('reports.staff.features.service_analytics') }}</li>
                    <li>{{ __('reports.staff.features.productivity') }}</li>
                    <li>{{ __('reports.staff.features.customer_feedback') }}</li>
                    <li>{{ __('reports.staff.features.shift_performance') }}</li>
                    <li>{{ __('reports.staff.features.table_turnover') }}</li>
                </ul>
            </div>
            
            <div class="coming-soon-timeline">
                <p class="timeline-text">{{ __('reports.coming_soon.timeline') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
