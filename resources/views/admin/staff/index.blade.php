@extends('layouts.admin')

@section('title', __('staff.title') . ' - ' . config('app.name'))
@section('page_title', __('staff.title'))

@push('styles')
@vite(['resources/css/admin/staff.css'])
@endpush

@push('scripts')
@vite(['resources/js/admin/staff.js'])
@endpush

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold mb-2 text-primary">{{ __('staff.staff_overview') }}</h1>
        <p class="text-secondary">{{ __('staff.subtitle') }}</p>
    </div>

    <!-- Staff Statistics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Staff Card -->
        <div class="bg-card rounded-lg shadow-md p-6 border border-main hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-full bg-primary-btn/10 flex items-center justify-center">
                    <i class="fas fa-users text-primary-btn text-lg"></i>
                </div>
                <span class="text-sm font-medium text-secondary">{{ __('staff.total_staff') }}</span>
            </div>
            <div class="text-3xl font-bold text-primary mb-2">24</div>
            <div class="flex items-center text-sm text-success">
                <i class="fas fa-arrow-up text-xs mr-1"></i>
                <span>+2 this month</span>
            </div>
        </div>

        <!-- Active Staff Card -->
        <div class="bg-card rounded-lg shadow-md p-6 border border-main hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-full bg-success/10 flex items-center justify-center">
                    <i class="fas fa-user-check text-success text-lg"></i>
                </div>
                <span class="text-sm font-medium text-secondary">{{ __('staff.active_staff') }}</span>
            </div>
            <div class="text-3xl font-bold text-primary mb-2">22</div>
            <div class="text-sm text-secondary">
                <span>91.7% active rate</span>
            </div>
        </div>

        <!-- On Duty Card -->
        <div class="bg-card rounded-lg shadow-md p-6 border border-main hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-full bg-info/10 flex items-center justify-center">
                    <i class="fas fa-clock text-info text-lg"></i>
                </div>
                <span class="text-sm font-medium text-secondary">{{ __('staff.on_duty') }}</span>
            </div>
            <div class="text-3xl font-bold text-primary mb-2">18</div>
            <div class="text-sm text-info">
                <span>Current shift</span>
            </div>
        </div>

        <!-- On Leave Card -->
        <div class="bg-card rounded-lg shadow-md p-6 border border-main hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-full bg-warning/10 flex items-center justify-center">
                    <i class="fas fa-calendar-times text-warning text-lg"></i>
                </div>
                <span class="text-sm font-medium text-secondary">{{ __('staff.on_leave') }}</span>
            </div>
            <div class="text-3xl font-bold text-primary mb-2">3</div>
            <div class="text-sm text-warning">
                <span>2 returning today</span>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Quick Actions Panel -->
        <div class="lg:col-span-1">
            <div class="bg-card rounded-lg shadow-md border border-main">
                <div class="p-4 border-b border-main">
                    <h3 class="text-lg font-semibold text-primary">{{ __('staff.quick_actions') }}</h3>
                </div>
                <div class="p-4">
                    <div class="flex flex-col gap-3">
                        <button class="bg-primary-btn text-primary-btn px-4 py-3 rounded-md hover:opacity-90 transition-opacity flex items-center justify-center font-medium">
                            <i class="fas fa-user-plus mr-2"></i>
                            {{ __('staff.add_employee') }}
                        </button>
                        
                        <button class="bg-secondary-btn text-secondary-btn px-4 py-3 rounded-md hover:opacity-90 transition-opacity flex items-center justify-center font-medium">
                            <i class="fas fa-clock mr-2"></i>
                            {{ __('staff.view_attendance') }}
                        </button>
                        
                        <button class="bg-secondary-btn text-secondary-btn px-4 py-3 rounded-md hover:opacity-90 transition-opacity flex items-center justify-center font-medium">
                            <i class="fas fa-tasks mr-2"></i>
                            {{ __('staff.assign_task') }}
                        </button>
                        
                        <button class="bg-secondary-btn text-secondary-btn px-4 py-3 rounded-md hover:opacity-90 transition-opacity flex items-center justify-center font-medium">
                            <i class="fas fa-dollar-sign mr-2"></i>
                            {{ __('staff.generate_payroll') }}
                        </button>
                        
                        <button class="bg-secondary-btn text-secondary-btn px-4 py-3 rounded-md hover:opacity-90 transition-opacity flex items-center justify-center font-medium">
                            <i class="fas fa-chart-line mr-2"></i>
                            {{ __('staff.staff_reports') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity & Department Overview -->
        <div class="lg:col-span-2 flex flex-col gap-6">
            <!-- Recent Activity -->
            <div class="bg-card rounded-lg shadow-md border border-main">
                <div class="p-4 border-b border-main">
                    <h3 class="text-lg font-semibold text-primary">{{ __('staff.recent_activity') }}</h3>
                </div>
                <div class="p-4">
                    <div class="space-y-4">
                        <!-- Activity Item -->
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-success/10 flex items-center justify-center">
                                <i class="fas fa-user-plus text-sm text-success"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-primary">{{ __('staff.activity_employee_added') }}</p>
                                <p class="text-sm text-secondary">Sarah Johnson joined as Kitchen Staff</p>
                                <p class="text-xs text-muted">2 hours ago</p>
                            </div>
                        </div>
                        
                        <!-- Activity Item -->
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-info/10 flex items-center justify-center">
                                <i class="fas fa-clock text-sm text-info"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-primary">{{ __('staff.activity_attendance_marked') }}</p>
                                <p class="text-sm text-secondary">18 employees clocked in for morning shift</p>
                                <p class="text-xs text-muted">4 hours ago</p>
                            </div>
                        </div>
                        
                        <!-- Activity Item -->
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-warning/10 flex items-center justify-center">
                                <i class="fas fa-calendar-check text-sm text-warning"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-primary">{{ __('staff.activity_leave_approved') }}</p>
                                <p class="text-sm text-secondary">Mike Chen's vacation request approved</p>
                                <p class="text-xs text-muted">Yesterday</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Department Overview -->
            <div class="bg-card rounded-lg shadow-md border border-main">
                <div class="p-4 border-b border-main">
                    <h3 class="text-lg font-semibold text-primary">Department Overview</h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center p-4 rounded-lg bg-card-hover border border-main">
                            <div class="text-2xl font-bold text-primary mb-1">12</div>
                            <div class="text-sm text-secondary">{{ __('staff.kitchen') }}</div>
                        </div>
                        <div class="text-center p-4 rounded-lg bg-card-hover border border-main">
                            <div class="text-2xl font-bold text-primary mb-1">8</div>
                            <div class="text-sm text-secondary">{{ __('staff.service') }}</div>
                        </div>
                        <div class="text-center p-4 rounded-lg bg-card-hover border border-main">
                            <div class="text-2xl font-bold text-primary mb-1">3</div>
                            <div class="text-sm text-secondary">{{ __('staff.administration') }}</div>
                        </div>
                        <div class="text-center p-4 rounded-lg bg-card-hover border border-main">
                            <div class="text-2xl font-bold text-primary mb-1">1</div>
                            <div class="text-sm text-secondary">{{ __('staff.maintenance') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection