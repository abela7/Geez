<div class="min-h-screen" style="background-color: var(--color-bg-primary);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold" style="color: var(--color-text-primary);">
                        Payroll Overview
                    </h1>
                    <p class="mt-1 text-sm" style="color: var(--color-text-secondary);">
                        Monitor payroll activities, manage periods, and track expenses
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.staff.payroll.periods') }}" 
                       class="inline-flex items-center px-4 py-2 border rounded-md shadow-sm text-sm font-medium btn transition-colors"
                       style="background-color: var(--color-bg-tertiary); border-color: var(--color-border-base); color: var(--color-text-primary);"
                       onmouseover="this.style.backgroundColor='var(--color-surface-card-hover)'"
                       onmouseout="this.style.backgroundColor='var(--color-bg-tertiary)'">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 4v10m-4-10v10m8-10v10m-4-10H8m8 0h4"></path>
                        </svg>
                        Manage Periods
                    </a>
                    <a href="{{ route('admin.staff.payroll.settings') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white btn transition-colors"
                       style="background-color: var(--color-primary);"
                       onmouseover="this.style.backgroundColor='var(--color-secondary)'"
                       onmouseout="this.style.backgroundColor='var(--color-primary)'">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Settings
                    </a>
                </div>
            </div>
        </div>

        <!-- Key Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Staff -->
            <div class="overflow-hidden shadow rounded-lg" style="background-color: var(--color-bg-secondary); box-shadow: var(--shadow-md);">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-primary);">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium truncate" style="color: var(--color-text-secondary);">
                                    Active Staff
                                </dt>
                                <dd class="text-lg font-medium" style="color: var(--color-text-primary);">
                                    {{ number_format($stats['total_staff']) }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Periods -->
            <div class="overflow-hidden shadow rounded-lg" style="background-color: var(--color-bg-secondary); box-shadow: var(--shadow-md);">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-warning);">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 4v10m-4-10v10m8-10v10m-4-10H8m8 0h4"/>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium truncate" style="color: var(--color-text-secondary);">
                                    Active Periods
                                </dt>
                                <dd class="text-lg font-medium" style="color: var(--color-text-primary);">
                                    {{ number_format($stats['active_periods']) }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- This Month Total -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                    This Month
                                </dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                    ${{ number_format($stats['this_month_total'], 2) }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                    <div class="mt-2">
                        @php
                            $change = $stats['last_month_total'] > 0 
                                ? (($stats['this_month_total'] - $stats['last_month_total']) / $stats['last_month_total']) * 100 
                                : 0;
                        @endphp
                        <div class="flex items-center text-sm">
                            @if($change > 0)
                                <svg class="h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                                <span class="text-green-600 ml-1">+{{ number_format(abs($change), 1) }}%</span>
                            @elseif($change < 0)
                                <svg class="h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                                </svg>
                                <span class="text-red-600 ml-1">{{ number_format($change, 1) }}%</span>
                            @else
                                <span class="text-gray-500 ml-1">No change</span>
                            @endif
                            <span class="text-gray-500 ml-1">from last month</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- YTD Total -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                    Year to Date
                                </dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                    ${{ number_format($stats['ytd_total'], 2) }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Quick Actions -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                            Quick Actions
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        @foreach($quickActions as $action)
                            <div class="relative">
                                @if($action['route'] ?? false)
                                    <a href="{{ route($action['route']) }}" 
                                       class="block p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ !$action['enabled'] ? 'opacity-50 cursor-not-allowed' : '' }}">
                                @elseif($action['action'] ?? false)
                                    <button wire:click="{{ $action['action'] }}" 
                                            {{ !$action['enabled'] ? 'disabled' : '' }}
                                            class="block w-full text-left p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ !$action['enabled'] ? 'opacity-50 cursor-not-allowed' : '' }}">
                                @else
                                    <div class="block p-4 border border-gray-200 dark:border-gray-700 rounded-lg {{ !$action['enabled'] ? 'opacity-50' : '' }}">
                                @endif
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 bg-{{ $action['color'] }}-100 rounded-lg flex items-center justify-center">
                                                @if($action['icon'] === 'plus')
                                                    <svg class="w-5 h-5 text-{{ $action['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                    </svg>
                                                @elseif($action['icon'] === 'calculator')
                                                    <svg class="w-5 h-5 text-{{ $action['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                    </svg>
                                                @elseif($action['icon'] === 'eye')
                                                    <svg class="w-5 h-5 text-{{ $action['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 text-{{ $action['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $action['title'] }}
                                            </h3>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $action['description'] }}
                                            </p>
                                        </div>
                                    </div>
                                @if($action['route'] ?? false)
                                    </a>
                                @elseif($action['action'] ?? false)
                                    </button>
                                @else
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Recent Activity & Periods -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Current Period Status -->
                @if($currentPeriod)
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                                Current Period
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                        {{ $currentPeriod->name }}
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $currentPeriod->period_start->format('M d') }} - {{ $currentPeriod->period_end->format('M d, Y') }}
                                        • Pay Date: {{ $currentPeriod->pay_date->format('M d, Y') }}
                                    </p>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        {{ $currentPeriod->status === 'draft' ? 'bg-gray-100 text-gray-800' : '' }}
                                        {{ $currentPeriod->status === 'calculated' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $currentPeriod->status === 'approved' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $currentPeriod->status === 'paid' ? 'bg-green-100 text-green-800' : '' }}">
                                        {{ ucfirst($currentPeriod->status) }}
                                    </span>
                                    @if($currentPeriod->status === 'draft')
                                        <a href="{{ route('admin.staff.payroll.periods') }}" 
                                           class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                            Generate Payroll
                                        </a>
                                    @elseif($currentPeriod->status === 'calculated')
                                        <a href="{{ route('admin.staff.payroll.review', $currentPeriod->id) }}" 
                                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            Review
                                        </a>
                                    @endif
                                </div>
                            </div>
                            @if($currentPeriod->total_records)
                                <div class="mt-4 grid grid-cols-3 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Records</p>
                                        <p class="text-lg font-medium text-gray-900 dark:text-white">{{ $currentPeriod->total_records }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Gross Amount</p>
                                        <p class="text-lg font-medium text-gray-900 dark:text-white">${{ number_format($currentPeriod->total_gross_pay ?? 0, 2) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Net Amount</p>
                                        <p class="text-lg font-medium text-gray-900 dark:text-white">${{ number_format($currentPeriod->total_net_pay ?? 0, 2) }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Recent Periods -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                            Recent Periods
                        </h2>
                    </div>
                    <div class="p-6">
                        @if($recentPeriods->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentPeriods as $period)
                                    <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700 last:border-0">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $period->name }}
                                            </h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $period->period_start->format('M d') }} - {{ $period->period_end->format('M d, Y') }}
                                            </p>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                {{ $period->status === 'draft' ? 'bg-gray-100 text-gray-700' : '' }}
                                                {{ $period->status === 'calculated' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                                {{ $period->status === 'approved' ? 'bg-blue-100 text-blue-700' : '' }}
                                                {{ $period->status === 'paid' ? 'bg-green-100 text-green-700' : '' }}">
                                                {{ ucfirst($period->status) }}
                                            </span>
                                            @if($period->total_net_pay)
                                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                                    ${{ number_format($period->total_net_pay, 2) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-center py-4">
                                No payroll periods yet. Create your first period to get started.
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Recent Activities -->
                @if(count($recentActivities) > 0)
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                                Recent Activity
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="flow-root">
                                <ul class="-mb-8">
                                    @foreach($recentActivities as $index => $activity)
                                        <li>
                                            <div class="relative pb-8">
                                                @if($index < count($recentActivities) - 1)
                                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-600" aria-hidden="true"></span>
                                                @endif
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span class="h-8 w-8 rounded-full bg-{{ $activity['color'] }}-100 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                            @if($activity['icon'] === 'calendar')
                                                                <svg class="h-4 w-4 text-{{ $activity['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 4v10m-4-10v10m8-10v10m-4-10H8m8 0h4"/>
                                                                </svg>
                                                            @else
                                                                <svg class="h-4 w-4 text-{{ $activity['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                                </svg>
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="min-w-0 flex-1 pt-1.5">
                                                        <div>
                                                            <p class="text-sm text-gray-900 dark:text-white">
                                                                {{ $activity['message'] }}
                                                            </p>
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                                by {{ $activity['user'] }} • {{ $activity['time']->diffForHumans() }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
