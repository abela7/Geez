@extends('layouts.admin')

@section('title', 'Payroll Overview - ' . config('app.name'))
@section('page_title', 'Payroll Overview')

@section('content')
<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-[#301934] dark:text-[#F8F6F1] mb-2">Payroll Management Hub</h1>
        <p class="text-sm sm:text-base text-[#4D4052] dark:text-[#D1CBC1]">Manage all payroll operations from one central dashboard</p>
    </div>

    <!-- Payroll Quick Actions Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        <!-- Pay Periods -->
        <a href="{{ route('admin.staff.payroll.periods') }}" class="bg-white dark:bg-[#1C1B2E] hover:bg-[#F8F6F1] dark:hover:bg-[#252340] rounded-lg shadow-lg p-4 sm:p-5 border border-[#E8E0D5] dark:border-[#3A3654] transition-all duration-300 block min-h-[140px] flex flex-col">
            <div class="flex items-center mb-3">
                <div class="w-10 h-10 bg-[#CDAF56]/20 dark:bg-[#CDAF56]/30 rounded-full flex items-center justify-center shadow-md flex-shrink-0">
                    <svg class="w-5 h-5 text-[#CDAF56]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-base font-bold text-[#301934] dark:text-[#F8F6F1] mb-1 flex-grow">Pay Periods</h3>
            <p class="text-xs sm:text-sm text-[#6B5B73] dark:text-[#9B8FA3] leading-tight">Manage payroll cycles</p>
        </a>

        <!-- Add Payroll -->
        <a href="{{ route('admin.staff.payroll.add') }}" class="bg-white dark:bg-[#1C1B2E] hover:bg-[#F8F6F1] dark:hover:bg-[#252340] rounded-lg shadow-lg p-4 sm:p-5 border border-[#E8E0D5] dark:border-[#3A3654] transition-all duration-300 block min-h-[140px] flex flex-col">
            <div class="flex items-center mb-3">
                <div class="w-10 h-10 bg-[#CDAF56]/20 dark:bg-[#CDAF56]/30 rounded-full flex items-center justify-center shadow-md flex-shrink-0">
                    <svg class="w-5 h-5 text-[#CDAF56]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-base font-bold text-[#301934] dark:text-[#F8F6F1] mb-1 flex-grow">Add Payroll</h3>
            <p class="text-xs sm:text-sm text-[#6B5B73] dark:text-[#9B8FA3] leading-tight">Generate new payroll</p>
        </a>

        <!-- Review Payroll -->
        <a href="{{ route('admin.staff.payroll.periods') }}" class="bg-white dark:bg-[#1C1B2E] hover:bg-[#F8F6F1] dark:hover:bg-[#252340] rounded-lg shadow-lg p-4 sm:p-5 border border-[#E8E0D5] dark:border-[#3A3654] transition-all duration-300 block min-h-[140px] flex flex-col">
            <div class="flex items-center mb-3">
                <div class="w-10 h-10 bg-[#CDAF56]/20 dark:bg-[#CDAF56]/30 rounded-full flex items-center justify-center shadow-md flex-shrink-0">
                    <svg class="w-5 h-5 text-[#CDAF56]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-base font-bold text-[#301934] dark:text-[#F8F6F1] mb-1 flex-grow">Review Payroll</h3>
            <p class="text-xs sm:text-sm text-[#6B5B73] dark:text-[#9B8FA3] leading-tight">Review & approve</p>
        </a>

        <!-- Process Payment -->
        <a href="{{ route('admin.staff.payroll.periods') }}" class="bg-white dark:bg-[#1C1B2E] hover:bg-[#F8F6F1] dark:hover:bg-[#252340] rounded-lg shadow-lg p-4 sm:p-5 border border-[#E8E0D5] dark:border-[#3A3654] transition-all duration-300 block min-h-[140px] flex flex-col">
            <div class="flex items-center mb-3">
                <div class="w-10 h-10 bg-[#CDAF56]/20 dark:bg-[#CDAF56]/30 rounded-full flex items-center justify-center shadow-md flex-shrink-0">
                    <svg class="w-5 h-5 text-[#CDAF56]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-base font-bold text-[#301934] dark:text-[#F8F6F1] mb-1 flex-grow">Process Payment</h3>
            <p class="text-xs sm:text-sm text-[#6B5B73] dark:text-[#9B8FA3] leading-tight">Process payments</p>
        </a>

        <!-- View Reports -->
        <a href="{{ route('admin.staff.payroll.periods') }}" class="bg-white dark:bg-[#1C1B2E] hover:bg-[#F8F6F1] dark:hover:bg-[#252340] rounded-lg shadow-lg p-4 sm:p-5 border border-[#E8E0D5] dark:border-[#3A3654] transition-all duration-300 block min-h-[140px] flex flex-col">
            <div class="flex items-center mb-3">
                <div class="w-10 h-10 bg-[#CDAF56]/20 dark:bg-[#CDAF56]/30 rounded-full flex items-center justify-center shadow-md flex-shrink-0">
                    <svg class="w-5 h-5 text-[#CDAF56]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-base font-bold text-[#301934] dark:text-[#F8F6F1] mb-1 flex-grow">View Reports</h3>
            <p class="text-xs sm:text-sm text-[#6B5B73] dark:text-[#9B8FA3] leading-tight">Generate payslips</p>
        </a>

        <!-- Settings -->
        <a href="{{ route('admin.staff.payroll.settings') }}" class="bg-white dark:bg-[#1C1B2E] hover:bg-[#F8F6F1] dark:hover:bg-[#252340] rounded-lg shadow-lg p-4 sm:p-5 border border-[#E8E0D5] dark:border-[#3A3654] transition-all duration-300 block min-h-[140px] flex flex-col">
            <div class="flex items-center mb-3">
                <div class="w-10 h-10 bg-[#CDAF56]/20 dark:bg-[#CDAF56]/30 rounded-full flex items-center justify-center shadow-md flex-shrink-0">
                    <svg class="w-5 h-5 text-[#CDAF56]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-base font-bold text-[#301934] dark:text-[#F8F6F1] mb-1 flex-grow">Payroll Settings</h3>
            <p class="text-xs sm:text-sm text-[#6B5B73] dark:text-[#9B8FA3] leading-tight">Configure payroll</p>
        </a>
    </div>
</div>
@endsection
