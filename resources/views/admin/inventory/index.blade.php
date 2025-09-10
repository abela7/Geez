@extends('layouts.admin')

@section('title', __('dashboard.nav_inventory') . ' - ' . config('app.name'))
@section('page_title', __('dashboard.nav_inventory'))

{{-- Section-specific assets will be added in Step 3 --}}

@section('content')
<div class="inventory-container">
    <!-- Inventory Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-primary mb-2">
            {{ __('dashboard.nav_inventory') }}
        </h2>
        <p class="text-secondary">
            {{ __('inventory.subtitle') }}
        </p>
    </div>

    <!-- Inventory Subsections -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Stock Levels -->
        <div class="bg-card rounded-lg shadow-md border border-main overflow-hidden hover:shadow-lg transition-shadow">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-primary-light rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-primary">{{ __('inventory.stock_levels.title') }}</h3>
                        <p class="text-sm text-secondary">{{ __('inventory.stock_levels.subtitle') }}</p>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <a href="{{ route('admin.inventory.stock-levels.index') }}" 
                       class="btn btn-primary btn-sm">
                        {{ __('inventory.stock_levels.view_all_items') }}
                    </a>
                    <span class="text-xs text-secondary">Ready</span>
                </div>
            </div>
        </div>

        <!-- Ingredients (Coming Soon) -->
        <div class="bg-card rounded-lg shadow-md border border-main overflow-hidden opacity-60">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-secondary-light rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-primary">Ingredients</h3>
                        <p class="text-sm text-secondary">Master list of raw items and allergen info</p>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <button class="btn btn-secondary btn-sm" disabled>
                        Coming Soon
                    </button>
                    <span class="text-xs text-secondary">Next</span>
                </div>
            </div>
        </div>

        <!-- Units & Conversions (Coming Soon) -->
        <div class="bg-card rounded-lg shadow-md border border-main overflow-hidden opacity-60">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-secondary-light rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-primary">Units & Conversions</h3>
                        <p class="text-sm text-secondary">Define measurement units and conversion rates</p>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <button class="btn btn-secondary btn-sm" disabled>
                        Coming Soon
                    </button>
                    <span class="text-xs text-secondary">Planned</span>
                </div>
            </div>
        </div>

        <!-- More subsections will be added here as they are developed -->
    </div>

    <!-- Development Status -->
    <div class="mt-8 p-4 bg-info-light border border-info rounded-lg">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-info mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
            <div>
                <h4 class="font-medium text-info-dark">Development Progress</h4>
                <p class="text-sm text-info-dark mt-1">
                    Stock Levels subsection is now complete with full functionality including filtering, status badges, 
                    detail drawer, and stock adjustments. Additional inventory subsections will be developed one by one 
                    following the same modular structure.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
