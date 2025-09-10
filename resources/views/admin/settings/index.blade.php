@extends('layouts.admin')

@section('title', __('dashboard.nav_settings') . ' - ' . config('app.name'))
@section('page_title', __('dashboard.nav_settings'))

{{-- Section-specific assets will be added in Step 3 --}}

@section('content')
<div class="settings-container">
    <!-- Settings Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-primary mb-2">
            {{ __('dashboard.nav_settings') }}
        </h2>
        <p class="text-secondary">
            {{ __('settings.subtitle') }}
        </p>
    </div>

    <!-- Settings Content Placeholder -->
    <div class="bg-card rounded-lg shadow-md p-8 border border-main text-center">
        <svg class="w-16 h-16 mx-auto text-icons mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
        </svg>
        <h3 class="text-lg font-medium text-primary mb-2">
            {{ __('settings.placeholder_title') }}
        </h3>
        <p class="text-secondary">
            {{ __('settings.placeholder_description') }}
        </p>
    </div>
</div>
@endsection
