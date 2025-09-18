@extends('layouts.admin')

@section('title', __('staff.attendance.title') . ' - ' . config('app.name'))
@section('page_title', __('staff.attendance.title'))

@push('styles')
@vite(['resources/css/admin/staff-attendance.css'])
@endpush

@section('content')
<div class="space-y-6" x-data="{ showAttendanceDrawer: false }">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl">{{ __('staff.attendance.title') }}</h1>
            <p class="text-sm">{{ __('staff.attendance.subtitle') }}</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <!-- Filters -->
            <form method="GET" class="flex gap-2">
                <input type="date" name="date" value="{{ $date }}" class="form-select" onchange="this.form.submit()">
                <select name="staff_type_id" class="form-select" onchange="this.form.submit()">
                    <option value="">{{ __('staff.all_types') }}</option>
                    @foreach($staffTypes as $type)
                        <option value="{{ $type->id }}" @selected($staffTypeId === $type->id)>{{ $type->display_name }}</option>
                    @endforeach
                </select>
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">{{ __('staff.attendance.all_statuses') }}</option>
                    <option value="present" @selected($status === 'present')>{{ __('staff.attendance.present') }}</option>
                    <option value="absent" @selected($status === 'absent')>{{ __('staff.attendance.absent') }}</option>
                    <option value="late" @selected($status === 'late')>{{ __('staff.attendance.late') }}</option>
                    <option value="overtime" @selected($status === 'overtime')>{{ __('staff.attendance.overtime') }}</option>
                </select>
            </form>
            <!-- Action Buttons -->
            <button onclick="alert('{{ __('common.coming_soon') }}')" class="btn btn-secondary">
                <i class="fas fa-download"></i>
                {{ __('staff.attendance.export') }}
            </button>
            <button @click="showAttendanceDrawer = true" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                {{ __('staff.attendance.add_attendance') }}
            </button>
        </div>
    </div>

    <!-- Today's Overview Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Staff -->
        <div class="card bg-gradient-to-r from-blue-500 to-blue-600 text-white">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">{{ __('staff.attendance.total_staff_today') }}</p>
                        <p class="text-3xl font-bold">{{ $todayStats['total_staff'] }}</p>
                    </div>
                    <div class="p-3 bg-white bg-opacity-20 rounded-full">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                </div>
                <p class="text-blue-100 text-sm mt-4">{{ __('staff.attendance.scheduled') }}</p>
            </div>
        </div>

        <!-- Present Count -->
        <div class="card bg-gradient-to-r from-green-500 to-green-600 text-white">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">{{ __('staff.attendance.present_count') }}</p>
                        <p class="text-3xl font-bold">{{ $todayStats['present_count'] }}</p>
                    </div>
                    <div class="p-3 bg-white bg-opacity-20 rounded-full">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                </div>
                <p class="text-green-100 text-sm mt-4">{{ $todayStats['attendance_rate'] }}% {{ __('staff.attendance.attendance_rate') }}</p>
            </div>
        </div>

        <!-- Absent Count -->
        <div class="card bg-gradient-to-r from-red-500 to-red-600 text-white">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm font-medium">{{ __('staff.attendance.absent_count') }}</p>
                        <p class="text-3xl font-bold">{{ $todayStats['absent_count'] }}</p>
                    </div>
                    <div class="p-3 bg-white bg-opacity-20 rounded-full">
                        <i class="fas fa-times-circle text-xl"></i>
                    </div>
                </div>
                <p class="text-red-100 text-sm mt-4">{{ __('staff.attendance.unexcused') }}</p>
            </div>
        </div>

        <!-- Late Arrivals -->
        <div class="card bg-gradient-to-r from-orange-500 to-orange-600 text-white">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium">{{ __('staff.attendance.late_arrivals') }}</p>
                        <p class="text-3xl font-bold">{{ $todayStats['late_count'] }}</p>
                    </div>
                    <div class="p-3 bg-white bg-opacity-20 rounded-full">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                </div>
                <p class="text-orange-100 text-sm mt-4">{{ __('staff.attendance.within_grace_period') }}</p>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- Left Column - Attendance Records -->
        <div class="xl:col-span-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('staff.attendance.attendance_records') }}</h3>
                    <div class="flex items-center gap-2">
                        <input type="text" name="search" value="{{ $search }}" placeholder="{{ __('staff.attendance.search_staff') }}" 
                               class="form-select" style="width: 200px;" 
                               onchange="document.querySelector('form').submit()">
                        <span class="text-sm text-gray-500">{{ $attendanceRecords->total() }} {{ __('staff.attendance.records') }}</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($attendanceRecords->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __('staff.attendance.staff_member') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __('staff.attendance.clock_in') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __('staff.attendance.clock_out') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __('staff.attendance.hours_worked') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __('staff.attendance.status') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($attendanceRecords as $record)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                                {{ substr($record->staff->first_name, 0, 1) }}{{ substr($record->staff->last_name, 0, 1) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                            {{ $record->staff->full_name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $record->staff->staffType->display_name ?? 'N/A' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $record->clock_in->format('H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $record->clock_out ? $record->clock_out->format('H:i') : '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $record->hours_worked ? number_format($record->hours_worked, 1) . 'h' : '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                    {{ $record->status === 'present' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                                       ($record->status === 'late' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                                        ($record->status === 'absent' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 
                                                         'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200')) }}">
                                                    {{ __('staff.attendance.' . $record->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                            {{ $attendanceRecords->links() }}
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-12 text-gray-500 dark:text-gray-400">
                            <i class="fas fa-calendar-times text-4xl mb-4"></i>
                            <p>{{ __('staff.attendance.no_records') }}</p>
                            <p class="text-sm">{{ __('staff.attendance.no_records_description') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Currently Clocked In & Recent Activity -->
        <div class="space-y-6">
            <!-- Currently Clocked In -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('staff.attendance.currently_clocked_in') }}</h3>
                    <span class="text-sm text-gray-500">{{ $currentlyClocked->count() }} {{ __('staff.attendance.active') }}</span>
                </div>
                <div class="card-body">
                    @if($currentlyClocked->count() > 0)
                        <div class="space-y-3">
                            @foreach($currentlyClocked as $record)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $record->staff->full_name }}</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $record->staff->staffType->display_name ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $record->clock_in->format('H:i') }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $record->clock_in->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-8 text-gray-500 dark:text-gray-400">
                            <i class="fas fa-user-clock text-3xl mb-3"></i>
                            <p class="text-sm">{{ __('staff.attendance.no_one_clocked_in') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('staff.attendance.recent_activity') }}</h3>
                    <span class="text-sm text-gray-500">{{ __('staff.attendance.last_7_days') }}</span>
                </div>
                <div class="card-body">
                    @if($recentActivity->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentActivity->take(8) as $record)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs
                                                {{ $record->status === 'present' ? 'bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300' : 
                                                   ($record->status === 'late' ? 'bg-yellow-100 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-300' : 
                                                    'bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-300') }}">
                                                <i class="fas fa-{{ $record->status === 'present' ? 'check' : ($record->status === 'late' ? 'clock' : 'times') }}"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $record->staff->full_name }}</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $record->clock_in->format('M j, H:i') }}</p>
                                        </div>
                                    </div>
                                    <span class="text-xs px-2 py-1 rounded-full
                                        {{ $record->status === 'present' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 
                                           ($record->status === 'late' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300' : 
                                            'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300') }}">
                                        {{ __('staff.attendance.' . $record->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-8 text-gray-500 dark:text-gray-400">
                            <i class="fas fa-history text-3xl mb-3"></i>
                            <p class="text-sm">{{ __('staff.attendance.no_recent_activity') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add Attendance Modal -->
    <div x-show="showAttendanceDrawer" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-hidden" 
         style="display: none;">
        
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black bg-opacity-50" @click="showAttendanceDrawer = false"></div>
        
        <!-- Drawer -->
        <div class="absolute right-0 top-0 h-full w-full max-w-md bg-white dark:bg-gray-900 shadow-xl"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full">
            
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('staff.attendance.add_attendance') }}</h3>
                <button @click="showAttendanceDrawer = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Form -->
            <div class="p-6">
                <form class="space-y-4" onsubmit="alert('{{ __('common.coming_soon') }}'); return false;">
                    <!-- Staff Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('staff.attendance.select_staff') }}
                        </label>
                        <select class="form-select w-full" required>
                            <option value="">{{ __('staff.attendance.choose_staff') }}</option>
                            @foreach($allStaff as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->full_name }} - {{ $staff->staffType->display_name ?? 'N/A' }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('staff.attendance.date') }}
                        </label>
                        <input type="date" class="form-select w-full" value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                    
                    <!-- Clock In Time -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('staff.attendance.clock_in_time') }}
                        </label>
                        <input type="time" class="form-select w-full" required>
                    </div>
                    
                    <!-- Clock Out Time -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('staff.attendance.clock_out_time') }}
                        </label>
                        <input type="time" class="form-select w-full">
                    </div>
                    
                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('staff.attendance.status') }}
                        </label>
                        <select class="form-select w-full" required>
                            <option value="present">{{ __('staff.attendance.present') }}</option>
                            <option value="absent">{{ __('staff.attendance.absent') }}</option>
                            <option value="late">{{ __('staff.attendance.late') }}</option>
                            <option value="overtime">{{ __('staff.attendance.overtime') }}</option>
                        </select>
                    </div>
                    
                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('staff.attendance.notes') }}
                        </label>
                        <textarea class="form-select w-full" rows="3" placeholder="{{ __('staff.attendance.notes_placeholder') }}"></textarea>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" @click="showAttendanceDrawer = false" class="btn btn-secondary">
                            {{ __('common.cancel') }}
                        </button>
                        <button type="submit" class="btn btn-primary">
                            {{ __('staff.attendance.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@vite(['resources/js/admin/staff-attendance.js'])
@endpush
@endsection
