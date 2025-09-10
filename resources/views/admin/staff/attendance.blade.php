@extends('layouts.admin')

@section('title', __('staff.attendance.title') . ' - ' . config('app.name'))
@section('page_title', __('staff.attendance.title'))

@push('styles')
@vite(['resources/css/admin/staff-attendance.css'])
@endpush

@push('scripts')
@vite(['resources/js/admin/staff-attendance.js'])
@endpush

@section('content')
<div class="admin-container" x-data="{ showAttendanceDrawer: false }">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('staff.attendance.title') }}</h1>
                <p class="page-subtitle">{{ __('staff.attendance.subtitle') }}</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-secondary" id="exportBtn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ __('staff.attendance.export') }}
                </button>
                <button class="btn btn-primary" @click="showAttendanceDrawer = true">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    {{ __('staff.attendance.add_attendance') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Quick Insights Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Staff Today -->
        <div class="card attendance-insight-card">
            <div class="attendance-insight-header">
                <div class="attendance-insight-icon attendance-icon-primary">
                    <svg class="attendance-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <span class="attendance-insight-label">{{ __('staff.attendance.total_staff_today') }}</span>
            </div>
            <div class="attendance-insight-value">24</div>
            <div class="attendance-insight-trend">{{ __('staff.attendance.scheduled') }}</div>
        </div>

        <!-- Present Count -->
        <div class="card attendance-insight-card">
            <div class="attendance-insight-header">
                <div class="attendance-insight-icon attendance-icon-success">
                    <svg class="attendance-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="attendance-insight-label">{{ __('staff.attendance.present_count') }}</span>
            </div>
            <div class="attendance-insight-value">21</div>
            <div class="attendance-insight-trend attendance-trend-positive">87.5% {{ __('staff.attendance.attendance_rate') }}</div>
        </div>

        <!-- Absent Count -->
        <div class="card attendance-insight-card">
            <div class="attendance-insight-header">
                <div class="attendance-insight-icon attendance-icon-error">
                    <svg class="attendance-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="attendance-insight-label">{{ __('staff.attendance.absent_count') }}</span>
            </div>
            <div class="attendance-insight-value">2</div>
            <div class="attendance-insight-trend attendance-trend-negative">{{ __('staff.attendance.unexcused') }}</div>
        </div>

        <!-- Late Arrivals -->
        <div class="card attendance-insight-card">
            <div class="attendance-insight-header">
                <div class="attendance-insight-icon attendance-icon-warning">
                    <svg class="attendance-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="attendance-insight-label">{{ __('staff.attendance.late_arrivals') }}</span>
            </div>
            <div class="attendance-insight-value">1</div>
            <div class="attendance-insight-trend attendance-trend-warning">{{ __('staff.attendance.within_grace_period') }}</div>
        </div>
    </div>

    <!-- Attendance Drawer -->
    <x-drawer 
        :show="false" 
        :title="__('staff.attendance.add_attendance')" 
        with-close-button 
        class="w-11/12 lg:w-1/3"
        position="right"
        x-model="showAttendanceDrawer"
    >
        <!-- Attendance Form -->
        <form id="attendanceForm" class="drawer-form h-full">
            <!-- Staff Selection -->
            <div class="form-group">
                <label for="staffSelect">
                    {{ __('staff.attendance.select_staff') }}
                </label>
                <select 
                    id="staffSelect" 
                    name="staff_id" 
                    required
                >
                    <option value="">{{ __('staff.attendance.choose_staff') }}</option>
                    <option value="1">Sarah Johnson - {{ __('staff.waiter') }}</option>
                    <option value="2">Michael Chen - {{ __('staff.chef') }}</option>
                    <option value="3">Amina Yusuf - {{ __('staff.cashier') }}</option>
                    <option value="4">Daniel Tesfaye - {{ __('staff.supervisor') }}</option>
                    <option value="5">Hanna Gebremedhin - {{ __('staff.manager') }}</option>
                    <option value="6">Samuel Alemu - {{ __('staff.cleaner') }}</option>
                </select>
            </div>

            <!-- Date -->
            <div class="form-group">
                <label for="attendanceDate">
                    {{ __('staff.attendance.date') }}
                </label>
                <input 
                    type="date" 
                    id="attendanceDate" 
                    name="date" 
                    required
                    value="{{ date('Y-m-d') }}"
                />
            </div>

            <!-- Check In Time -->
            <div class="form-group">
                <label for="checkInTime">
                    {{ __('staff.attendance.check_in_time') }}
                </label>
                <input 
                    type="time" 
                    id="checkInTime" 
                    name="check_in_time" 
                    required
                />
            </div>

            <!-- Check Out Time -->
            <div class="form-group">
                <label for="checkOutTime">
                    {{ __('staff.attendance.check_out_time') }}
                </label>
                <input 
                    type="time" 
                    id="checkOutTime" 
                    name="check_out_time"
                />
            </div>

            <!-- Status -->
            <div class="form-group">
                <label for="attendanceStatus">
                    {{ __('staff.attendance.status') }}
                </label>
                <select 
                    id="attendanceStatus" 
                    name="status" 
                    required
                >
                    <option value="present">{{ __('staff.attendance.present') }}</option>
                    <option value="absent">{{ __('staff.attendance.absent') }}</option>
                    <option value="late">{{ __('staff.attendance.late') }}</option>
                    <option value="on_leave">{{ __('staff.attendance.on_leave') }}</option>
                </select>
            </div>

            <!-- Notes -->
            <div class="form-group">
                <label for="attendanceNotes">
                    {{ __('staff.attendance.notes') }}
                </label>
                <textarea 
                    id="attendanceNotes" 
                    name="notes" 
                    rows="3"
                    placeholder="{{ __('staff.attendance.notes_placeholder') }}"
                ></textarea>
            </div>
        </form>

        <x-slot:actions>
            <div class="drawer-actions">
                <button 
                    type="button" 
                    @click="showAttendanceDrawer = false"
                    class="drawer-btn drawer-btn-secondary"
                >
                    {{ __('staff.attendance.cancel') }}
                </button>
                <button 
                    type="button" 
                    @click="saveAttendance()"
                    class="drawer-btn drawer-btn-primary"
                >
                    {{ __('staff.attendance.save') }}
                </button>
            </div>
        </x-slot:actions>
    </x-drawer>
</div>
@endsection