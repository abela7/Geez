@extends('layouts.admin')

@section('title', __('admin.payroll.review.title'))

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('admin.staff.payroll.periods') }}" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                            Review Payroll
                        </h1>
                    </div>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Period: {{ $period->name }} ({{ $period->period_start->format('M d') }} - {{ $period->period_end->format('M d, Y') }})
                    </p>
                </div>
                <div class="flex space-x-2">
                    <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Recalculate All
                    </button>
                    <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Approve Selected
                    </button>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                    Payroll Records
                </h2>
            </div>
            <div class="p-6">
                <p class="text-gray-600 dark:text-gray-400">
                    Payroll review page - Coming soon with Livewire component
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
