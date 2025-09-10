@extends('layouts.admin')

@section('title', __('dashboard.nav_sales') . ' - ' . config('app.name'))
@section('page_title', __('dashboard.nav_sales'))

{{-- Section-specific assets will be added in Step 3 --}}

@section('content')
<div class="sales-container">
    <!-- Sales Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-primary mb-2">
            {{ __('dashboard.nav_sales') }}
        </h2>
        <p class="text-secondary">
            {{ __('sales.subtitle') }}
        </p>
    </div>

    <!-- Sales Content Placeholder -->
    <div class="bg-card rounded-lg shadow-md p-8 border border-main text-center">
        <svg class="w-16 h-16 mx-auto text-icons mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        <h3 class="text-lg font-medium text-primary mb-2">
            {{ __('sales.placeholder_title') }}
        </h3>
        <p class="text-secondary">
            {{ __('sales.placeholder_description') }}
        </p>
    </div>
</div>
@endsection
