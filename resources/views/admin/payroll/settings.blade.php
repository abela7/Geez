@extends('layouts.admin')

@section('title', __('admin.payroll.settings.title'))

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('admin.payroll.settings.title') }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('admin.payroll.settings.description') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                    Payroll Settings
                </h2>
            </div>
            <div class="p-6">
                <p class="text-gray-600 dark:text-gray-400">
                    Payroll settings page - Coming soon with Livewire component
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
