@extends('layouts.admin')

@section('title', __('admin.payroll.periods.title'))

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('admin.payroll.periods.title') }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('admin.payroll.periods.description') }}
                    </p>
                </div>
                <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    New Period
                </button>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                    Pay Periods
                </h2>
            </div>
            <div class="p-6">
                <p class="text-gray-600 dark:text-gray-400">
                    Pay periods page - Coming soon with Livewire component
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
