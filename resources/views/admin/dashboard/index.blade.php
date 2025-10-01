@extends('layouts.admin')

@section('title', __('dashboard.page_title') . ' - ' . config('app.name'))
@section('page_title', __('dashboard.title'))

{{-- Section-specific assets will be added in Step 3 --}}

@section('content')
<div class="dashboard-container w-full max-w-full mx-auto px-0 sm:px-2 md:px-4 min-w-0">
    <!-- Dashboard Header -->
    <div class="mb-3 sm:mb-4 md:mb-8 px-1 sm:px-0">
        <h2 class="text-base sm:text-lg md:text-2xl font-bold text-[#301934] dark:text-[#F8F6F1] mb-1 sm:mb-2 break-words leading-tight">
            {{ __('dashboard.welcome_message') }}
        </h2>
        <p class="text-xs sm:text-sm md:text-base text-[#4D4052] dark:text-[#D1CBC1] break-words leading-relaxed">
            {{ __('dashboard.subtitle') }}
        </p>
    </div>

    <!-- Dashboard Stats Grid -->
    <div class="dashboard-stats-grid grid grid-cols-1 gap-2 sm:gap-3 md:gap-6 mb-3 sm:mb-4 md:mb-8 px-1 sm:px-0">
        <!-- Stat Card 1 -->
        <div class="dashboard-stat-card bg-white dark:bg-[#1C1B2E] hover:bg-[#F8F6F1] dark:hover:bg-[#252340] rounded-lg shadow-lg dark:shadow-xl p-2 sm:p-3 md:p-6 border border-[#E8E0D5] dark:border-[#3A3654] transition-all duration-300 w-full min-w-0">
            <div class="flex items-center justify-between mb-1 sm:mb-2 md:mb-4 min-w-0">
                <div class="w-6 h-6 sm:w-8 sm:h-8 md:w-12 md:h-12 bg-[#CDAF56]/20 dark:bg-[#CDAF56]/30 rounded-full flex items-center justify-center flex-shrink-0 shadow-md">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 md:w-6 md:h-6 text-[#CDAF56]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-xs sm:text-sm md:text-sm text-[#4D4052] dark:text-[#D1CBC1] ml-1 sm:ml-2 overflow-hidden text-ellipsis whitespace-nowrap min-w-0 flex-1 text-right">{{ __('dashboard.stat_revenue') }}</span>
            </div>
            <div class="text-base sm:text-lg md:text-2xl font-bold text-[#301934] dark:text-[#F8F6F1]">$0.00</div>
            <p class="text-xs text-[#6B5B73] dark:text-[#9B8FA3] mt-0.5 sm:mt-1 overflow-hidden text-ellipsis whitespace-nowrap">{{ __('dashboard.stat_placeholder') }}</p>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-white dark:bg-[#1C1B2E] hover:bg-[#F8F6F1] dark:hover:bg-[#252340] rounded-lg shadow-lg dark:shadow-xl p-2 sm:p-3 md:p-6 border border-[#E8E0D5] dark:border-[#3A3654] transition-all duration-300 w-full min-w-0">
            <div class="flex items-center justify-between mb-1 sm:mb-2 md:mb-4 min-w-0">
                <div class="w-6 h-6 sm:w-8 sm:h-8 md:w-12 md:h-12 bg-[#CDAF56]/20 dark:bg-[#CDAF56]/30 rounded-full flex items-center justify-center flex-shrink-0 shadow-md">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 md:w-6 md:h-6 text-[#CDAF56]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <span class="text-xs sm:text-sm md:text-sm text-[#4D4052] dark:text-[#D1CBC1] ml-1 sm:ml-2 overflow-hidden text-ellipsis whitespace-nowrap min-w-0 flex-1 text-right">{{ __('dashboard.stat_orders') }}</span>
            </div>
            <div class="text-base sm:text-lg md:text-2xl font-bold text-[#301934] dark:text-[#F8F6F1]">0</div>
            <p class="text-xs text-[#6B5B73] dark:text-[#9B8FA3] mt-0.5 sm:mt-1 overflow-hidden text-ellipsis whitespace-nowrap">{{ __('dashboard.stat_placeholder') }}</p>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-white dark:bg-[#1C1B2E] hover:bg-[#F8F6F1] dark:hover:bg-[#252340] rounded-lg shadow-lg dark:shadow-xl p-2 sm:p-3 md:p-6 border border-[#E8E0D5] dark:border-[#3A3654] transition-all duration-300 w-full min-w-0">
            <div class="flex items-center justify-between mb-1 sm:mb-2 md:mb-4 min-w-0">
                <div class="w-6 h-6 sm:w-8 sm:h-8 md:w-12 md:h-12 bg-[#CDAF56]/20 dark:bg-[#CDAF56]/30 rounded-full flex items-center justify-center flex-shrink-0 shadow-md">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 md:w-6 md:h-6 text-[#CDAF56]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <span class="text-xs sm:text-sm md:text-sm text-[#4D4052] dark:text-[#D1CBC1] ml-1 sm:ml-2 overflow-hidden text-ellipsis whitespace-nowrap min-w-0 flex-1 text-right">{{ __('dashboard.stat_customers') }}</span>
            </div>
            <div class="text-base sm:text-lg md:text-2xl font-bold text-[#301934] dark:text-[#F8F6F1]">0</div>
            <p class="text-xs text-[#6B5B73] dark:text-[#9B8FA3] mt-0.5 sm:mt-1 overflow-hidden text-ellipsis whitespace-nowrap">{{ __('dashboard.stat_placeholder') }}</p>
        </div>

        <!-- Stat Card 4 -->
        <div class="bg-white dark:bg-[#1C1B2E] hover:bg-[#F8F6F1] dark:hover:bg-[#252340] rounded-lg shadow-lg dark:shadow-xl p-2 sm:p-3 md:p-6 border border-[#E8E0D5] dark:border-[#3A3654] transition-all duration-300 w-full min-w-0">
            <div class="flex items-center justify-between mb-1 sm:mb-2 md:mb-4 min-w-0">
                <div class="w-6 h-6 sm:w-8 sm:h-8 md:w-12 md:h-12 bg-[#CDAF56]/20 dark:bg-[#CDAF56]/30 rounded-full flex items-center justify-center flex-shrink-0 shadow-md">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 md:w-6 md:h-6 text-[#CDAF56]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <span class="text-xs sm:text-sm md:text-sm text-[#4D4052] dark:text-[#D1CBC1] ml-1 sm:ml-2 overflow-hidden text-ellipsis whitespace-nowrap min-w-0 flex-1 text-right">{{ __('dashboard.stat_inventory') }}</span>
            </div>
            <div class="text-base sm:text-lg md:text-2xl font-bold text-[#301934] dark:text-[#F8F6F1]">0</div>
            <p class="text-xs text-[#6B5B73] dark:text-[#9B8FA3] mt-0.5 sm:mt-1 overflow-hidden text-ellipsis whitespace-nowrap">{{ __('dashboard.stat_placeholder') }}</p>
        </div>
    </div>

    <!-- Placeholder Content Area -->
    <div class="bg-white dark:bg-[#1C1B2E] hover:bg-[#F8F6F1] dark:hover:bg-[#252340] rounded-lg shadow-lg dark:shadow-xl p-2 sm:p-3 md:p-8 border border-[#E8E0D5] dark:border-[#3A3654] transition-all duration-300 w-full min-w-0 mx-1 sm:mx-0">
        <div class="text-center py-4 sm:py-6 md:py-12">
            <svg class="w-8 h-8 sm:w-10 sm:h-10 md:w-16 md:h-16 mx-auto text-[#CDAF56] mb-2 sm:mb-3 md:mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-xs sm:text-sm md:text-lg font-medium text-[#301934] dark:text-[#F8F6F1] mb-1 sm:mb-2 break-words px-2">
                {{ __('dashboard.content_placeholder_title') }}
            </h3>
            <p class="text-xs sm:text-sm md:text-base text-[#4D4052] dark:text-[#D1CBC1] break-words px-2 leading-relaxed">
                {{ __('dashboard.content_placeholder_text') }}
            </p>
        </div>
    </div>

    <!-- Test Scrolling Content -->
    @for ($i = 1; $i <= 20; $i++)
    <div class="bg-card hover:bg-card-hover rounded-lg shadow-md p-3 border border-main transition-colors w-full min-w-0 mx-1 sm:mx-0 mb-4">
        <div class="flex items-center justify-between mb-2">
            <div class="w-8 h-8 bg-primary-btn/20 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-icons font-bold text-sm">{{ $i }}</span>
            </div>
            <span class="text-sm text-secondary">Test Item {{ $i }}</span>
        </div>
        <div class="text-base font-bold text-primary mb-1">
            Scrollable Content Item {{ $i }}
        </div>
        <p class="text-xs text-muted">
            This content should scroll while the sidebar and top bar remain fixed. Item {{ $i }} of 20.
        </p>
    </div>
    @endfor

    <!-- End of Content Marker -->
    <div class="bg-primary-btn/10 rounded-lg shadow-md p-4 border border-primary-btn/20 text-center mx-1 sm:mx-0">
        <h3 class="text-lg font-bold text-primary mb-2">
            🎯 End of Scrollable Content
        </h3>
        <p class="text-sm text-secondary">
            If the sidebar and top bar stayed fixed while scrolling to this point, the layout is working correctly!
        </p>
    </div>
</div>
@endsection
