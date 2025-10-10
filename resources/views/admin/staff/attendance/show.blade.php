@extends('layouts.admin')

@section('title', 'Attendance Details - ' . $staffAttendance->staff->full_name)
@section('page_title', 'Attendance Details')

@section('content')
<div class="attendance-detail-page">

    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <div class="breadcrumb">
                    <a href="{{ route('admin.staff.attendance.index') }}" class="breadcrumb-link">
                        <svg class="breadcrumb-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Staff Attendance
                    </a>
                    <svg class="breadcrumb-separator" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="breadcrumb-current">Attendance Details</span>
                </div>
                <h1 class="page-title">Attendance Details</h1>
                <p class="page-subtitle">{{ $staffAttendance->staff->full_name }} • {{ $staffAttendance->clock_in->format('M j, Y') }}</p>
            </div>
            
            <div class="page-actions">
                <div class="status-badges">
                    <span class="status-badge status-{{ $staffAttendance->status }}">
                        <svg class="status-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ ucfirst(str_replace('_', ' ', $staffAttendance->status)) }}
                    </span>
                    <span class="state-badge state-{{ $staffAttendance->current_state }}">
                        <svg class="state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ ucfirst(str_replace('_', ' ', $staffAttendance->current_state)) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Compact Stats Overview -->
    <div class="compact-stats-overview">
        <div class="compact-stats-container">
            <div class="compact-stat-item">
                <div class="compact-stat-icon clock-in">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="compact-stat-info">
                    <span class="compact-stat-value">{{ $staffAttendance->clock_in->format('g:i A') }}</span>
                    <span class="compact-stat-label">Clock In</span>
                </div>
            </div>

            <div class="compact-stat-item">
                <div class="compact-stat-icon clock-out">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </div>
                <div class="compact-stat-info">
                    <span class="compact-stat-value">{{ $staffAttendance->clock_out ? $staffAttendance->clock_out->format('g:i A') : 'Still Active' }}</span>
                    <span class="compact-stat-label">Clock Out</span>
                </div>
            </div>

            <div class="compact-stat-item">
                <div class="compact-stat-icon total-time">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="compact-stat-info">
                    <span class="compact-stat-value">
                        @php
                            $totalMinutes = $staffAttendance->clock_in->diffInMinutes($staffAttendance->clock_out);
                            $totalHours = floor($totalMinutes / 60);
                            $totalMins = $totalMinutes % 60;
                            echo $totalHours > 0 ? $totalHours . 'h ' . $totalMins . 'm' : $totalMins . 'm';
                        @endphp
                    </span>
                    <span class="compact-stat-label">Total Time</span>
                </div>
            </div>

            <div class="compact-stat-item">
                <div class="compact-stat-icon break-time">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <div class="compact-stat-info">
                    <span class="compact-stat-value">
                        @php
                            $breakMinutes = $staffAttendance->total_break_minutes ?? 0;
                            $breakHours = floor($breakMinutes / 60);
                            $breakMins = $breakMinutes % 60;
                            echo $breakHours > 0 ? $breakHours . 'h ' . $breakMins . 'm' : $breakMins . 'm';
                        @endphp
                    </span>
                    <span class="compact-stat-label">Break Time</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="content-grid">
        <!-- Staff Information - Compact -->
        <div class="compact-card">
            <div class="compact-card-header">
                <div class="compact-card-title">
                    <svg class="compact-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Staff Information
                </div>
            </div>
            <div class="compact-card-body">
                <div class="staff-info-compact">
                    <div class="staff-avatar">
                        {{ strtoupper(substr($staffAttendance->staff->first_name, 0, 1) . substr($staffAttendance->staff->last_name, 0, 1)) }}
                    </div>
                    <div class="staff-details-compact">
                        <h3 class="staff-name-compact">{{ $staffAttendance->staff->full_name }}</h3>
                        <p class="staff-position-compact">{{ $staffAttendance->staff->staffType->display_name ?? 'Team Member' }}</p>
                        <span class="staff-id">ID: {{ $staffAttendance->staff->profile->employee_id ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Overview - Compact -->
        <div class="compact-card">
            <div class="compact-card-header">
                <div class="compact-card-title">
                    <svg class="compact-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Performance
                </div>
            </div>
            <div class="compact-card-body">
                <div class="performance-metrics-compact">
                    <div class="metric-item-compact">
                        <div class="metric-score-compact">{{ $metrics['punctuality_score'] }}%</div>
                        <div class="metric-label-compact">Punctuality</div>
                    </div>
                    <div class="metric-item-compact">
                        <div class="metric-score-compact">{{ $metrics['efficiency_score'] }}%</div>
                        <div class="metric-label-compact">Efficiency</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shift Information - Collapsible -->
        <div class="collapsible-card">
            <div class="collapsible-header" onclick="toggleCard('shift-info')">
                <div class="collapsible-header-content">
                    <svg class="collapsible-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="collapsible-title">Scheduled Shift</span>
                </div>
                <svg class="collapsible-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
            <div class="collapsible-content" id="shift-info" style="display: none;">
                @if($staffAttendance->shift_assignment_id && $staffAttendance->shiftAssignment)
                    <div class="shift-info-compact">
                        <div class="shift-name-compact">{{ $staffAttendance->shiftAssignment->shift->name ?? 'Unknown Shift' }}</div>
                        <div class="shift-schedule-compact">
                            {{ $staffAttendance->shiftAssignment->shift->start_time ?? 'N/A' }} - {{ $staffAttendance->shiftAssignment->shift->end_time ?? 'N/A' }}
                        </div>
                        <div class="compliance-status-compact compliance-{{ $metrics['compliance_status'] }}">
                            {{ $metrics['compliance_status'] === 'on_time' ? 'On Time' : ($metrics['compliance_status'] === 'late_arrival' ? 'Late Start' : ($metrics['compliance_status'] === 'early_departure' ? 'Left Early' : 'Unscheduled')) }}
                        </div>
                    </div>
                @else
                    <div class="unscheduled-work-compact">
                        <p>No scheduled shift - worked without pre-assigned schedule</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Work Sessions - Collapsible -->
        @if($staffAttendance->intervals->count() > 0)
            <div class="collapsible-card">
                <div class="collapsible-header" onclick="toggleCard('work-sessions')">
                    <div class="collapsible-header-content">
                        <svg class="collapsible-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <span class="collapsible-title">Work Sessions</span>
                    </div>
                    <svg class="collapsible-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
                <div class="collapsible-content" id="work-sessions" style="display: none;">
                    <div class="sessions-list-compact">
                        @foreach($staffAttendance->intervals as $interval)
                            <div class="session-item-compact session-{{ $interval->interval_type }}">
                                <div class="session-type-compact">
                                    @if($interval->interval_type === 'work')
                                        <svg class="session-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"/>
                                        </svg>
                                        Working
                                    @else
                                        <svg class="session-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                        Break
                                    @endif
                                </div>
                                <div class="session-time-compact">
                                    @if($interval->end_time)
                                        {{ $interval->start_time->format('g:i A') }} - {{ $interval->end_time->format('g:i A') }}
                                    @else
                                        {{ $interval->start_time->format('g:i A') }} - Still Active
                                    @endif
                                </div>
                                <div class="session-duration-compact">
                                    @if($interval->end_time)
                                        @php
                                            $hours = floor($interval->duration_minutes / 60);
                                            $mins = $interval->duration_minutes % 60;
                                            echo $hours > 0 ? $hours . 'h ' . $mins . 'm' : $mins . 'm';
                                        @endphp
                                    @else
                                        @php
                                            // For active sessions, use clock_out time if available, otherwise use now()
                                            $endTime = $staffAttendance->clock_out ?? now();
                                            $activeMinutes = $interval->start_time->diffInMinutes($endTime);
                                            $activeHours = floor($activeMinutes / 60);
                                            $activeMins = $activeMinutes % 60;
                                            $activeDuration = $activeHours > 0 ? $activeHours . 'h ' . $activeMins . 'm' : $activeMins . 'm';
                                        @endphp
                                        @if($staffAttendance->clock_out)
                                            {{ $activeDuration }}
                                        @else
                                            {{ $activeDuration }} so far
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Record Details - Collapsible -->
        <div class="collapsible-card">
            <div class="collapsible-header" onclick="toggleCard('record-details')">
                <div class="collapsible-header-content">
                    <svg class="collapsible-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="collapsible-title">Record Details</span>
                </div>
                <svg class="collapsible-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
            <div class="collapsible-content" id="record-details" style="display: none;">
                <div class="record-details-compact">
                    @if($staffAttendance->notes)
                        <div class="detail-item-compact">
                            <span class="detail-label-compact">Notes:</span>
                            <span class="detail-value-compact">{{ $staffAttendance->notes }}</span>
                        </div>
                    @endif
                    <div class="detail-item-compact">
                        <span class="detail-label-compact">Created by:</span>
                        <span class="detail-value-compact">{{ $staffAttendance->creator->full_name ?? 'System' }}</span>
                    </div>
                    <div class="detail-item-compact">
                        <span class="detail-label-compact">Created:</span>
                        <span class="detail-value-compact">{{ $staffAttendance->created_at->format('M j, Y \a\t g:i A') }}</span>
                    </div>
                    @if($staffAttendance->review_needed)
                        <div class="detail-item-compact">
                            <span class="detail-label-compact">Status:</span>
                            <span class="detail-value-compact review-needed">⚠️ Needs Review</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* ======================================================================
   Attendance Detail Page - Modern Clean Design
   ====================================================================== */

.attendance-detail-page {
    background: var(--color-bg-primary);
    min-height: 100vh;
    padding: var(--page-padding);
}

/* ==========================================
   PAGE HEADER
   ========================================== */
.page-header {
    margin-bottom: var(--section-spacing);
}

.page-header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: var(--space-xl);
    flex-wrap: wrap;
}

.page-title-section {
    flex: 1;
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: var(--space-sm);
    margin-bottom: var(--space-md);
}

.breadcrumb-link {
    display: flex;
    align-items: center;
    gap: var(--space-xs);
    color: var(--color-text-secondary);
    text-decoration: none;
    font-size: var(--font-size-sm);
    font-weight: var(--font-weight-medium);
    transition: color 0.2s ease;
}

.breadcrumb-link:hover {
    color: var(--color-primary);
}

.breadcrumb-icon {
    width: 16px;
    height: 16px;
}

.breadcrumb-separator {
    width: 16px;
    height: 16px;
    color: var(--color-text-tertiary);
}

.breadcrumb-current {
    color: var(--color-text-primary);
    font-size: var(--font-size-sm);
    font-weight: var(--font-weight-medium);
}

.page-title {
    font-size: var(--font-size-2xl);
    font-weight: var(--font-weight-bold);
    color: var(--color-text-primary);
    margin: 0 0 var(--space-sm) 0;
    letter-spacing: -0.025em;
}

.page-subtitle {
    font-size: var(--font-size-base);
    color: var(--color-text-secondary);
    margin: 0;
}

.page-actions {
    display: flex;
    gap: var(--space-md);
}

.status-badges {
    display: flex;
    gap: var(--space-sm);
    flex-wrap: wrap;
}

.status-badge,
.state-badge {
    display: flex;
    align-items: center;
    gap: var(--space-xs);
    padding: var(--space-sm) var(--space-md);
    border-radius: var(--radius-md);
    font-size: var(--font-size-sm);
    font-weight: var(--font-weight-medium);
}

.status-badge.status-present {
    background: var(--alert-success-bg);
    color: var(--alert-success-text);
    border: 1px solid var(--alert-success-border);
}

.status-badge.status-absent {
    background: var(--alert-error-bg);
    color: var(--alert-error-text);
    border: 1px solid var(--alert-error-border);
}

.status-badge.status-late {
    background: var(--alert-warning-bg);
    color: var(--alert-warning-text);
    border: 1px solid var(--alert-warning-border);
}

.state-badge.state-clocked_in {
    background: var(--color-primary-light);
    color: var(--color-primary);
    border: 1px solid var(--color-primary-border);
}

.state-badge.state-clocked_out {
    background: var(--color-surface-secondary);
    color: var(--color-text-secondary);
    border: 1px solid var(--color-border-base);
}

.status-icon,
.state-icon {
    width: 16px;
    height: 16px;
}

/* ==========================================
   COMPACT STATS OVERVIEW
   ========================================== */
.compact-stats-overview {
    background: var(--color-surface-card);
    border: 1px solid var(--color-surface-card-border);
    border-radius: var(--radius-lg);
    padding: var(--space-lg);
    margin-bottom: var(--space-xl);
    box-shadow: var(--color-surface-card-shadow);
}

.compact-stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--space-lg);
}

.compact-stat-item {
    display: flex;
    align-items: center;
    gap: var(--space-md);
    padding: var(--space-md);
    background: var(--color-bg-primary);
    border-radius: var(--radius-md);
    border: 1px solid var(--color-border-base);
    transition: all 0.2s ease;
}

.compact-stat-item:hover {
    border-color: var(--color-primary);
    box-shadow: 0 0 0 2px rgba(var(--color-primary-rgb), 0.1);
    transform: translateY(-1px);
}

.compact-stat-icon {
    width: 40px;
    height: 40px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.compact-stat-icon svg {
    width: 20px;
    height: 20px;
}

.compact-stat-icon.clock-in {
    background: rgba(34, 197, 94, 0.1);
    color: #22c55e;
}

.compact-stat-icon.clock-out {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.compact-stat-icon.total-time {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
}

.compact-stat-icon.break-time {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
}

.compact-stat-info {
    display: flex;
    flex-direction: column;
    gap: var(--space-xs);
}

.compact-stat-value {
    font-size: var(--font-size-xl);
    font-weight: var(--font-weight-bold);
    color: var(--color-text-primary);
    line-height: 1;
}

.compact-stat-label {
    font-size: var(--font-size-sm);
    color: var(--color-text-secondary);
    font-weight: var(--font-weight-medium);
}

/* ==========================================
   CONTENT GRID
   ========================================== */
.content-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: var(--space-lg);
}

/* ==========================================
   COMPACT CARDS
   ========================================== */
.compact-card {
    background: var(--color-surface-card);
    border: 1px solid var(--color-surface-card-border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--color-surface-card-shadow);
    transition: all 0.2s ease;
}

.compact-card:hover {
    box-shadow: var(--color-surface-card-shadow-hover);
}

.compact-card-header {
    background: var(--color-surface-secondary);
    padding: var(--space-md) var(--space-lg);
    border-bottom: 1px solid var(--color-border-base);
}

.compact-card-title {
    font-size: var(--font-size-base);
    font-weight: var(--font-weight-semibold);
    color: var(--color-text-primary);
    display: flex;
    align-items: center;
    gap: var(--space-sm);
    margin: 0;
}

.compact-card-icon {
    width: 18px;
    height: 18px;
    color: var(--color-text-secondary);
}

.compact-card-body {
    padding: var(--space-lg);
}

/* ==========================================
   STAFF INFO COMPACT
   ========================================== */
.staff-info-compact {
    display: flex;
    align-items: center;
    gap: var(--space-md);
}

.staff-avatar {
    width: 48px;
    height: 48px;
    border-radius: var(--radius-full);
    background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: var(--font-size-base);
    font-weight: var(--font-weight-bold);
    flex-shrink: 0;
}

.staff-details-compact {
    flex: 1;
    min-width: 0;
}

.staff-name-compact {
    font-size: var(--font-size-lg);
    font-weight: var(--font-weight-bold);
    color: var(--color-text-primary);
    margin: 0 0 var(--space-xs) 0;
}

.staff-position-compact {
    font-size: var(--font-size-sm);
    color: var(--color-text-secondary);
    margin: 0 0 var(--space-xs) 0;
}

.staff-id {
    font-size: var(--font-size-xs);
    color: var(--color-text-tertiary);
}

/* ==========================================
   PERFORMANCE METRICS COMPACT
   ========================================== */
.performance-metrics-compact {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--space-lg);
}

.metric-item-compact {
    text-align: center;
    padding: var(--space-md);
    background: var(--color-surface-secondary);
    border-radius: var(--radius-md);
}

.metric-score-compact {
    font-size: var(--font-size-xl);
    font-weight: var(--font-weight-bold);
    color: var(--color-primary);
    margin-bottom: var(--space-xs);
}

.metric-label-compact {
    font-size: var(--font-size-sm);
    color: var(--color-text-secondary);
    font-weight: var(--font-weight-medium);
}

/* ==========================================
   COLLAPSIBLE CARDS
   ========================================== */
.collapsible-card {
    background: var(--color-surface-card);
    border: 1px solid var(--color-surface-card-border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--color-surface-card-shadow);
    transition: all 0.2s ease;
}

.collapsible-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
    padding: var(--space-md) var(--space-lg);
    background: var(--color-surface-secondary);
    border-bottom: 1px solid var(--color-border-base);
    transition: background-color 0.2s ease;
}

.collapsible-header:hover {
    background: var(--color-bg-secondary);
}

.collapsible-header-content {
    display: flex;
    align-items: center;
    gap: var(--space-sm);
}

.collapsible-icon {
    width: 18px;
    height: 18px;
    color: var(--color-text-secondary);
}

.collapsible-title {
    font-size: var(--font-size-base);
    font-weight: var(--font-weight-semibold);
    color: var(--color-text-primary);
}

.collapsible-chevron {
    width: 20px;
    height: 20px;
    color: var(--color-text-secondary);
    transition: transform 0.2s ease;
}

.collapsible-card.expanded .collapsible-chevron {
    transform: rotate(180deg);
}

.collapsible-content {
    padding: var(--space-lg);
    transition: all 0.3s ease;
    overflow: hidden;
}

/* ==========================================
   SHIFT INFO COMPACT
   ========================================== */
.shift-info-compact {
    display: flex;
    flex-direction: column;
    gap: var(--space-sm);
}

.shift-name-compact {
    font-size: var(--font-size-lg);
    font-weight: var(--font-weight-semibold);
    color: var(--color-text-primary);
}

.shift-schedule-compact {
    font-size: var(--font-size-sm);
    color: var(--color-text-secondary);
}

.compliance-status-compact {
    display: inline-block;
    padding: var(--space-xs) var(--space-sm);
    border-radius: var(--radius-sm);
    font-size: var(--font-size-xs);
    font-weight: var(--font-weight-medium);
    width: fit-content;
}

.compliance-status-compact.compliance-on_time {
    background: var(--alert-success-bg);
    color: var(--alert-success-text);
}

.compliance-status-compact.compliance-late_arrival {
    background: var(--alert-warning-bg);
    color: var(--alert-warning-text);
}

.compliance-status-compact.compliance-early_departure {
    background: var(--alert-error-bg);
    color: var(--alert-error-text);
}

.compliance-status-compact.compliance-unscheduled {
    background: var(--color-surface-secondary);
    color: var(--color-text-secondary);
}

.unscheduled-work-compact {
    text-align: center;
    color: var(--color-text-secondary);
    font-style: italic;
}

/* ==========================================
   SESSIONS LIST COMPACT
   ========================================== */
.sessions-list-compact {
    display: flex;
    flex-direction: column;
    gap: var(--space-sm);
}

.session-item-compact {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: var(--space-sm) var(--space-md);
    background: var(--color-surface-secondary);
    border-radius: var(--radius-md);
    border-left: 3px solid var(--color-border-base);
}

.session-item-compact.session-work {
    border-left-color: var(--color-success);
}

.session-item-compact.session-break {
    border-left-color: var(--color-warning);
}

.session-type-compact {
    display: flex;
    align-items: center;
    gap: var(--space-xs);
    font-size: var(--font-size-sm);
    font-weight: var(--font-weight-medium);
    color: var(--color-text-primary);
}

.session-icon {
    width: 16px;
    height: 16px;
    color: var(--color-text-secondary);
}

.session-time-compact {
    font-size: var(--font-size-sm);
    color: var(--color-text-secondary);
    flex: 1;
    margin: 0 var(--space-md);
}

.session-duration-compact {
    font-size: var(--font-size-xs);
    font-weight: var(--font-weight-medium);
    color: var(--color-text-tertiary);
}

/* ==========================================
   RECORD DETAILS COMPACT
   ========================================== */
.record-details-compact {
    display: flex;
    flex-direction: column;
    gap: var(--space-sm);
}

.detail-item-compact {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--space-sm) 0;
    border-bottom: 1px solid var(--color-border-base);
}

.detail-item-compact:last-child {
    border-bottom: none;
}

.detail-label-compact {
    font-size: var(--font-size-sm);
    font-weight: var(--font-weight-medium);
    color: var(--color-text-secondary);
}

.detail-value-compact {
    font-size: var(--font-size-sm);
    color: var(--color-text-primary);
    text-align: right;
    max-width: 60%;
    word-wrap: break-word;
}

.detail-value-compact.review-needed {
    color: var(--color-warning);
    font-weight: var(--font-weight-medium);
}

/* ==========================================
   RESPONSIVE DESIGN
   ========================================== */
@media (max-width: 768px) {
    .attendance-detail-page {
        padding: var(--space-lg);
    }
    
    .page-header-content {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .page-actions {
        width: 100%;
        justify-content: flex-start;
    }
    
    .compact-stats-container {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--space-md);
    }
    
    .content-grid {
        grid-template-columns: 1fr;
    }
    
    .staff-info-compact {
        flex-direction: column;
        text-align: center;
    }
    
    .performance-metrics-compact {
        grid-template-columns: 1fr;
    }
    
    .session-item-compact {
        flex-direction: column;
        gap: var(--space-xs);
        text-align: center;
    }
    
    .session-time-compact {
        margin: 0;
    }
    
    .detail-item-compact {
        flex-direction: column;
        align-items: flex-start;
        gap: var(--space-xs);
    }
    
    .detail-value-compact {
        text-align: left;
        max-width: 100%;
    }
}
</style>

<script>
/**
 * Toggle collapsible card visibility
 */
function toggleCard(cardId) {
    const content = document.getElementById(cardId);
    const card = content.closest('.collapsible-card');
    const chevron = card.querySelector('.collapsible-chevron');
    
    if (content && chevron) {
        const isVisible = content.style.display !== 'none';
        content.style.display = isVisible ? 'none' : 'block';
        card.classList.toggle('expanded');
        
        // Animate chevron
        chevron.style.transform = isVisible ? 'rotate(0deg)' : 'rotate(180deg)';
    }
}
</script>
@endsection