@extends('layouts.admin')

@section('title', __('dashboard.nav_reports') . ' - ' . config('app.name'))
@section('page_title', __('dashboard.nav_reports'))

{{-- Section-specific assets will be added in Step 3 --}}

@section('content')
<div class="reports-container">
    <!-- Reports Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-primary mb-2">
            {{ __('dashboard.nav_reports') }}
        </h2>
        <p class="text-secondary">
            {{ __('reports.subtitle') }}
        </p>
    </div>

    <!-- Reports Content Placeholder -->
    <div class="bg-card rounded-lg shadow-md p-8 border border-main text-center">
        <svg class="w-16 h-16 mx-auto text-icons mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
        </svg>
        <h3 class="text-lg font-medium text-primary mb-2">
            {{ __('reports.placeholder_title') }}
        </h3>
        <p class="text-secondary">
            {{ __('reports.placeholder_description') }}
        </p>
    </div>
</div>
@endsection
