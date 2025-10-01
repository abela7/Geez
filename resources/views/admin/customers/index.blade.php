@extends('layouts.admin')

@section('title', __('dashboard.nav_customers') . ' - ' . config('app.name'))
@section('page_title', __('dashboard.nav_customers'))

{{-- Section-specific assets will be added in Step 3 --}}

@section('content')
<div class="customers-container">
    <!-- Customers Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-primary mb-2">
            {{ __('dashboard.nav_customers') }}
        </h2>
        <p class="text-secondary">
            {{ __('customers.subtitle') }}
        </p>
    </div>

    <!-- Customers Content Placeholder -->
    <div class="bg-card rounded-lg shadow-md p-8 border border-main text-center">
        <svg class="w-16 h-16 mx-auto text-icons mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
        <h3 class="text-lg font-medium text-primary mb-2">
            {{ __('customers.placeholder_title') }}
        </h3>
        <p class="text-secondary">
            {{ __('customers.placeholder_description') }}
        </p>
    </div>
</div>
@endsection
