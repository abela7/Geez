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

    <!-- Overview Stats -->
    <div class="stats-grid">
        <div class="stat-card stat-card-highlight">
            <div class="stat-icon stat-icon-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $staffAttendance->clock_in->format('g:i A') }}</div>
                <div class="stat-label">Started Working</div>
                <div class="stat-subtitle">{{ $staffAttendance->clock_in->format('M j, Y') }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-success">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $staffAttendance->clock_out ? $staffAttendance->clock_out->format('g:i A') : 'Still Here' }}</div>
                <div class="stat-label">Finished Work</div>
                <div class="stat-subtitle">{{ $staffAttendance->clock_out ? $staffAttendance->clock_out->format('M j, Y') : 'Currently Active' }}</div>
            </div>
        </div>

        <div class="stat-card stat-card-efficiency">
            <div class="stat-icon stat-icon-warning">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">
                    @php
                        $totalMinutes = $staffAttendance->clock_in->diffInMinutes($staffAttendance->clock_out);
                        $totalHours = floor($totalMinutes / 60);
                        $totalMins = $totalMinutes % 60;
                        echo $totalHours > 0 ? $totalHours . 'h ' . $totalMins . 'm' : $totalMins . 'm';
                    @endphp
                </div>
                <div class="stat-label">Total Time Here</div>
                <div class="stat-subtitle">
                    @php
                        $breakMinutes = $staffAttendance->total_break_minutes ?? 0;
                        $netMinutes = $totalMinutes - $breakMinutes;
                        $netHours = floor($netMinutes / 60);
                        $netMins = $netMinutes % 60;
                        $netDuration = $netHours > 0 ? $netHours . 'h ' . $netMins . 'm' : $netMins . 'm';
                        echo $netDuration . ' productive work';
                    @endphp
                </div>
            </div>
        </div>

        <div class="stat-card stat-card-break">
            <div class="stat-icon stat-icon-info">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">
                    @php
                        $breakMinutes = $staffAttendance->total_break_minutes ?? 0;
                        $breakHours = floor($breakMinutes / 60);
                        $breakMins = $breakMinutes % 60;
                        echo $breakHours > 0 ? $breakHours . 'h ' . $breakMins . 'm' : $breakMins . 'm';
                    @endphp
                </div>
                <div class="stat-label">Time on Break</div>
                <div class="stat-subtitle">{{ $staffAttendance->break_count ?? 0 }} break{{ ($staffAttendance->break_count ?? 0) !== 1 ? 's' : '' }} taken</div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="content-grid">
        <!-- Staff Information Card -->
        <div class="card card-profile">
            <div class="card-header">
                <div class="card-title">
                    <svg class="card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Team Member Details
                </div>
                <div class="card-subtitle">Who was working this shift</div>
            </div>
            <div class="card-body">
                <div class="staff-profile">
                    <div class="staff-avatar-large">
                        {{ strtoupper(substr($staffAttendance->staff->first_name, 0, 1) . substr($staffAttendance->staff->last_name, 0, 1)) }}
                    </div>
                    <div class="staff-details">
                        <h3 class="staff-name">{{ $staffAttendance->staff->full_name }}</h3>
                        <p class="staff-position">{{ $staffAttendance->staff->staffType->display_name ?? 'Team Member' }}</p>
                        <div class="staff-meta">
                            <span class="meta-item">
                                <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                Employee ID: {{ $staffAttendance->staff->employee_id ?? $staffAttendance->id }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Time Tracking Card -->
        <div class="card card-time-enhanced">
            <div class="card-header">
                <div class="card-title">
                    <svg class="card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Time Summary
                </div>
                <div class="card-subtitle">How long they worked and when</div>
            </div>
            <div class="card-body">
                <!-- Work Session Timeline -->
                <div class="time-timeline">
                    <div class="timeline-item timeline-work">
                        <div class="timeline-dot timeline-dot-work">
                            <svg class="timeline-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"/>
                            </svg>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-title">Work Session</div>
                            <div class="timeline-time">
                                @php
                                    $totalMinutes = $staffAttendance->clock_in->diffInMinutes($staffAttendance->clock_out);
                                    $breakMinutes = $staffAttendance->total_break_minutes ?? 0;
                                    $netMinutes = $totalMinutes - $breakMinutes;
                                    $netHours = floor($netMinutes / 60);
                                    $netMins = $netMinutes % 60;
                                    $netDuration = $netHours > 0 ? $netHours . 'h ' . $netMins . 'm' : $netMins . 'm';
                                    echo $netDuration . ' productive work';
                                @endphp
                            </div>
                            <div class="timeline-sessions">{{ $staffAttendance->intervals->where('interval_type', 'work')->count() }} session{{ $staffAttendance->intervals->where('interval_type', 'work')->count() !== 1 ? 's' : '' }}</div>
                        </div>
                    </div>

                    @php
                        $breakMinutes = $staffAttendance->total_break_minutes ?? 0;
                        $hasBreaks = $breakMinutes > 0;
                    @endphp
                    @if($hasBreaks)
                        <div class="timeline-connector">
                            <div class="timeline-line timeline-line-break"></div>
                        </div>

                        <div class="timeline-item timeline-break">
                            <div class="timeline-dot timeline-dot-break">
                                <svg class="timeline-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Break Time</div>
                                <div class="timeline-time">
                                    @php
                                        $breakHours = floor($breakMinutes / 60);
                                        $breakMins = $breakMinutes % 60;
                                        $breakDuration = $breakHours > 0 ? $breakHours . 'h ' . $breakMins . 'm' : $breakMins . 'm';
                                        echo $breakDuration . ' total breaks';
                                    @endphp
                                </div>
                                <div class="timeline-sessions">{{ $staffAttendance->break_count ?? 0 }} break{{ ($staffAttendance->break_count ?? 0) !== 1 ? 's' : '' }}</div>
                            </div>
                        </div>
                    @endif

                    @php
                        $totalMinutes = $staffAttendance->clock_in->diffInMinutes($staffAttendance->clock_out);
                        $breakMinutes = $staffAttendance->total_break_minutes ?? 0;
                        $netMinutes = $totalMinutes - $breakMinutes;
                        $scheduledMinutes = $staffAttendance->scheduled_minutes ?? 0;
                        $overtimeMinutes = $scheduledMinutes > 0 ? max(0, $netMinutes - $scheduledMinutes) : 0;
                        $hasOvertime = $overtimeMinutes > 0;
                    @endphp
                    @if($hasOvertime)
                        <div class="timeline-connector">
                            <div class="timeline-line timeline-line-overtime"></div>
                        </div>

                        <div class="timeline-item timeline-overtime">
                            <div class="timeline-dot timeline-dot-overtime">
                                <svg class="timeline-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Extra Hours</div>
                                <div class="timeline-time">
                                    @php
                                        $overtimeHours = floor($overtimeMinutes / 60);
                                        $overtimeMins = $overtimeMinutes % 60;
                                        $overtimeDuration = $overtimeHours > 0 ? $overtimeHours . 'h ' . $overtimeMins . 'm' : $overtimeMins . 'm';
                                        echo $overtimeDuration . ' beyond schedule';
                                    @endphp
                                </div>
                                <div class="timeline-sessions">Bonus work time!</div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Efficiency Overview -->
                <div class="efficiency-overview">
                    <div class="efficiency-main">
                        <div class="efficiency-label">
                            <svg class="efficiency-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Work Efficiency
                        </div>
                        <div class="efficiency-value">{{ round(($metrics['net_hours'] / max($metrics['total_hours'], 0.01)) * 100, 1) }}%</div>
                    </div>

                    <div class="efficiency-bar">
                        <div class="efficiency-fill" style="width: {{ round(($metrics['net_hours'] / max($metrics['total_hours'], 0.01)) * 100, 1) }}%"></div>
                    </div>

                    <div class="efficiency-description">
                        @if(round(($metrics['net_hours'] / max($metrics['total_hours'], 0.01)) * 100, 1) >= 90)
                            Excellent productivity - minimal breaks
                        @elseif(round(($metrics['net_hours'] / max($metrics['total_hours'], 0.01)) * 100, 1) >= 75)
                            Good work focus with reasonable breaks
                        @elseif(round(($metrics['net_hours'] / max($metrics['total_hours'], 0.01)) * 100, 1) >= 50)
                            Moderate efficiency - could improve
                        @else
                            Low productivity - too much break time
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Shift Information Card -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <svg class="card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Scheduled Shift
                </div>
                <div class="card-subtitle">What shift were they supposed to work</div>
            </div>
            <div class="card-body">
                @if($staffAttendance->shift_assignment_id && $staffAttendance->shiftAssignment)
                    <div class="shift-info">
                        <div class="shift-header">
                            <h4 class="shift-name">{{ $staffAttendance->shiftAssignment->shift->name ?? 'Unknown Shift' }}</h4>
                            <span class="compliance-badge compliance-{{ $metrics['compliance_status'] }}">
                                {{ $metrics['compliance_status'] === 'on_time' ? 'On Time' : ($metrics['compliance_status'] === 'late_arrival' ? 'Late Start' : ($metrics['compliance_status'] === 'early_departure' ? 'Left Early' : 'Unscheduled')) }}
                            </span>
                        </div>
                        <div class="shift-schedule">
                            <div class="schedule-item">
                                <svg class="schedule-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="schedule-details">
                                    <div class="schedule-time">Should work from {{ $staffAttendance->shiftAssignment->shift->start_time ?? 'N/A' }} to {{ $staffAttendance->shiftAssignment->shift->end_time ?? 'N/A' }}</div>
                                    <div class="schedule-date">On {{ \Carbon\Carbon::parse($staffAttendance->shiftAssignment->assigned_date)->format('M j, Y') }}</div>
                                </div>
                            </div>
                        </div>
                        @if($staffAttendance->scheduled_minutes)
                            <div class="shift-metrics">
                                <div class="metric-item">
                                    <div class="metric-label">Expected Shift Length</div>
                                    <div class="metric-value">
                                        @php
                                            $scheduledMinutes = $staffAttendance->scheduled_minutes;
                                            $scheduledHours = floor($scheduledMinutes / 60);
                                            $scheduledMins = $scheduledMinutes % 60;
                                            $scheduledDuration = $scheduledHours > 0 ? $scheduledHours . 'h ' . $scheduledMins . 'm' : $scheduledMins . 'm';
                                            echo $scheduledDuration;
                                        @endphp
                                    </div>
                                </div>
                                @if($staffAttendance->actual_minutes)
                                    <div class="metric-item">
                                        <div class="metric-label">Time Actually Worked</div>
                                        <div class="metric-value">
                                            @php
                                                $actualMinutes = $staffAttendance->actual_minutes;
                                                $actualHours = floor($actualMinutes / 60);
                                                $actualMins = $actualMinutes % 60;
                                                $actualDuration = $actualHours > 0 ? $actualHours . 'h ' . $actualMins . 'm' : $actualMins . 'm';
                                                echo $actualDuration;
                                            @endphp
                                        </div>
                                    </div>
                                @endif
                                @if($staffAttendance->variance_minutes)
                                    <div class="metric-item {{ $staffAttendance->variance_minutes > 0 ? 'positive' : 'negative' }}">
                                        <div class="metric-label">Difference from Schedule</div>
                                        <div class="metric-value">
                                            @php
                                                $varianceMinutes = abs($staffAttendance->variance_minutes);
                                                $varianceHours = floor($varianceMinutes / 60);
                                                $varianceMins = $varianceMinutes % 60;
                                                $varianceDuration = $varianceHours > 0 ? $varianceHours . 'h ' . $varianceMins . 'm' : $varianceMins . 'm';
                                                $varianceSign = $staffAttendance->variance_minutes > 0 ? '+' : '';
                                                $varianceText = $staffAttendance->variance_minutes > 0 ? 'extra' : 'short';
                                                echo $varianceSign . $varianceDuration . ' ' . $varianceText;
                                            @endphp
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @else
                    <div class="unscheduled-work">
                        <svg class="unscheduled-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h4>No Scheduled Shift</h4>
                        <p>This person worked without a pre-assigned shift schedule.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Performance Metrics Card -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <svg class="card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Performance Overview
                </div>
                <div class="card-subtitle">How well did they perform this shift</div>
            </div>
            <div class="card-body">
                <div class="metrics-grid">
                    <div class="metric-card">
                        <div class="metric-score">
                            <div class="score-circle score-{{ $metrics['punctuality_score'] >= 80 ? 'excellent' : ($metrics['punctuality_score'] >= 60 ? 'good' : 'needs-work') }}">
                                <span class="score-value">{{ $metrics['punctuality_score'] }}</span>
                                <span class="score-label">%</span>
                            </div>
                        </div>
                        <div class="metric-info">
                            <h4>On-Time Arrival</h4>
                            <p class="metric-description">
                                @if($metrics['punctuality_score'] >= 90)
                                    Started right on time!
                                @elseif($metrics['punctuality_score'] >= 70)
                                    Arrived a bit late but acceptable
                                @elseif($metrics['punctuality_score'] >= 50)
                                    Could be more punctual
                                @else
                                    Needs to improve arrival time
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-score">
                            <div class="score-circle score-{{ $metrics['efficiency_score'] >= 80 ? 'excellent' : ($metrics['efficiency_score'] >= 60 ? 'good' : 'needs-work') }}">
                                <span class="score-value">{{ $metrics['efficiency_score'] }}</span>
                                <span class="score-label">%</span>
                            </div>
                        </div>
                        <div class="metric-info">
                            <h4>Work Efficiency</h4>
                            <p class="metric-description">
                                @if($metrics['efficiency_score'] >= 90)
                                    Excellent focus and productivity
                                @elseif($metrics['efficiency_score'] >= 70)
                                    Good balance of work and breaks
                                @elseif($metrics['efficiency_score'] >= 50)
                                    Takes regular breaks as needed
                                @else
                                    Could minimize break time
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Work Sessions Card -->
        @if($staffAttendance->intervals->count() > 0)
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <svg class="card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Work Sessions
                    </div>
                    <div class="card-subtitle">Periods of active work vs breaks during this shift</div>
                </div>
                <div class="card-body">
                    <div class="work-sessions-intro">
                        <p class="intro-text">
                            <svg class="intro-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            These are the continuous periods when {{ $staffAttendance->staff->first_name }} was actively working. Breaks pause the timer, and work resumes when they clock back in.
                        </p>
                    </div>
                    <div class="intervals-list">
                        @foreach($staffAttendance->intervals as $interval)
                            <div class="interval-item interval-{{ $interval->interval_type }}">
                                <div class="interval-type">
                                    <svg class="interval-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($interval->interval_type === 'work')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"/>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        @endif
                                    </svg>
                                    <span class="interval-type-label">
                                        @if($interval->interval_type === 'work')
                                            Working Period
                                        @else
                                            Break Time
                                        @endif
                                    </span>
                                </div>
                                <div class="interval-time">
                                    <div class="time-range">
                                        @if($interval->end_time)
                                            <span class="start-time">{{ $interval->start_time->format('g:i A') }}</span>
                                            <span class="time-separator">to</span>
                                            <span class="end-time">{{ $interval->end_time->format('g:i A') }}</span>
                                        @else
                                            <span class="start-time">{{ $interval->start_time->format('g:i A') }}</span>
                                            <span class="time-separator">until now</span>
                                            <span class="end-time">Still Active</span>
                                        @endif
                                    </div>
                                    <div class="interval-duration">
                                        @if($interval->end_time)
                                            <span class="duration-badge duration-{{ $interval->interval_type }}">
                                                @php
                                                    $hours = floor($interval->duration_minutes / 60);
                                                    $mins = $interval->duration_minutes % 60;
                                                    echo $hours > 0 ? $hours . 'h ' . $mins . 'm' : $mins . 'm';
                                                @endphp
                                            </span>
                                        @else
                                            @php
                                                $activeMinutes = $interval->start_time->diffInMinutes(now());
                                                $activeHours = floor($activeMinutes / 60);
                                                $activeMins = $activeMinutes % 60;
                                                $activeDuration = $activeHours > 0 ? $activeHours . 'h ' . $activeMins . 'm' : $activeMins . 'm';
                                            @endphp
                                            <span class="duration-badge duration-active">
                                                {{ $activeDuration }} so far
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                @if($interval->interval_type === 'break' && $interval->reason)
                                    <div class="break-reason">
                                        <span class="reason-label">Reason:</span>
                                        <span class="reason-text">{{ $interval->reason }}</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <div class="sessions-summary">
                        <div class="summary-item">
                            <span class="summary-label">Total Work Sessions:</span>
                            <span class="summary-value">{{ $staffAttendance->intervals->where('interval_type', 'work')->count() }}</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Total Breaks Taken:</span>
                            <span class="summary-value">{{ $staffAttendance->intervals->where('interval_type', '!=', 'work')->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Additional Information Card -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <svg class="card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Record Details
                </div>
                <div class="card-subtitle">Who created this record and when</div>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    @if($staffAttendance->notes)
                        <div class="info-item">
                            <div class="info-label">Notes from the shift</div>
                            <div class="info-value notes">{{ $staffAttendance->notes }}</div>
                        </div>
                    @endif
                    <div class="info-item">
                        <div class="info-label">Record created by</div>
                        <div class="info-value">{{ $staffAttendance->creator->full_name ?? 'System' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Created on</div>
                        <div class="info-value">{{ $staffAttendance->created_at->format('M j, Y \a\t g:i A') }}</div>
                    </div>
                    @if($staffAttendance->updated_at != $staffAttendance->created_at)
                        <div class="info-item">
                            <div class="info-label">Last modified</div>
                            <div class="info-value">{{ $staffAttendance->updated_at->format('M j, Y \a\t g:i A') }}</div>
                        </div>
                    @endif
                    @if($staffAttendance->review_needed)
                        <div class="info-item">
                            <div class="info-label">Review Status</div>
                            <div class="info-value">
                                <span class="review-badge review-needed">⚠️ Needs Manager Review</span>
                                @if($staffAttendance->review_reason)
                                    <p class="review-reason">{{ $staffAttendance->review_reason }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* ======================================================================
   Attendance Detail Page - Modern Design Following Central Theme
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
   STATS GRID
   ========================================== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: var(--space-lg);
    margin-bottom: var(--section-spacing);
}

.stat-card {
    background: var(--color-surface-card);
    border: 1px solid var(--color-surface-card-border);
    border-radius: var(--radius-lg);
    padding: var(--space-xl);
    display: flex;
    align-items: center;
    gap: var(--space-lg);
    transition: all 0.2s ease;
    box-shadow: var(--color-surface-card-shadow);
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--color-surface-card-shadow-hover);
}

.stat-card-highlight {
    border-left: 4px solid var(--color-primary);
    background: linear-gradient(135deg, var(--color-primary-light) 0%, var(--color-surface-card) 100%);
}

.stat-card-efficiency {
    background: linear-gradient(135deg, var(--color-success-light) 0%, var(--color-surface-card) 100%);
}

.stat-card-break {
    background: linear-gradient(135deg, var(--color-warning-light) 0%, var(--color-surface-card) 100%);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.stat-icon-primary {
    background: var(--color-primary-light);
    color: var(--color-primary);
}

.stat-icon-success {
    background: var(--alert-success-bg);
    color: var(--alert-success-text);
}

.stat-icon-warning {
    background: var(--alert-warning-bg);
    color: var(--alert-warning-text);
}

.stat-icon-info {
    background: var(--color-info-light);
    color: var(--color-info);
}

.stat-icon svg {
    width: 24px;
    height: 24px;
}

.stat-content {
    flex: 1;
    min-width: 0;
}

.stat-value {
    font-size: var(--font-size-xl);
    font-weight: var(--font-weight-bold);
    color: var(--color-text-primary);
    margin-bottom: var(--space-xs);
    line-height: 1.2;
}

.stat-label {
    font-size: var(--font-size-sm);
    font-weight: var(--font-weight-medium);
    color: var(--color-text-secondary);
    margin-bottom: var(--space-xs);
}

.stat-subtitle {
    font-size: var(--font-size-xs);
    color: var(--color-text-tertiary);
}

/* ==========================================
   CONTENT GRID
   ========================================== */
.content-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: var(--space-xl);
}

/* ==========================================
   CARDS
   ========================================== */
.card {
    background: var(--color-surface-card);
    border: 1px solid var(--color-surface-card-border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--color-surface-card-shadow);
    transition: all 0.2s ease;
}

.card:hover {
    box-shadow: var(--color-surface-card-shadow-hover);
}

.card-header {
    background: var(--color-surface-secondary);
    padding: var(--space-lg) var(--space-xl);
    border-bottom: 1px solid var(--color-border-base);
}

.card-title {
    font-size: var(--font-size-lg);
    font-weight: var(--font-weight-semibold);
    color: var(--color-text-primary);
    display: flex;
    align-items: center;
    gap: var(--space-sm);
    margin: 0;
}

.card-subtitle {
    font-size: var(--font-size-sm);
    color: var(--color-text-secondary);
    margin: var(--space-xs) 0 0 0;
    font-weight: var(--font-weight-normal);
}

.card-icon {
    width: 20px;
    height: 20px;
    color: var(--color-text-secondary);
}

.card-profile {
    border-left: 4px solid var(--color-primary);
}

.card-time-enhanced {
    border-left: 4px solid var(--color-warning);
    background: linear-gradient(135deg, var(--color-warning-light) 0%, var(--color-surface-card) 100%);
}

.card-body {
    padding: var(--space-xl);
}

/* ==========================================
   STAFF PROFILE
   ========================================== */
.staff-profile {
    display: flex;
    align-items: center;
    gap: var(--space-lg);
}

.staff-avatar-large {
    width: 64px;
    height: 64px;
    border-radius: var(--radius-full);
    background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: var(--font-size-lg);
    font-weight: var(--font-weight-bold);
    flex-shrink: 0;
}

.staff-details {
    flex: 1;
    min-width: 0;
}

.staff-name {
    font-size: var(--font-size-xl);
    font-weight: var(--font-weight-bold);
    color: var(--color-text-primary);
    margin: 0 0 var(--space-xs) 0;
}

.staff-position {
    font-size: var(--font-size-base);
    color: var(--color-text-secondary);
    margin: 0 0 var(--space-sm) 0;
}

.staff-meta {
    display: flex;
    gap: var(--space-lg);
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: var(--space-xs);
    font-size: var(--font-size-sm);
    color: var(--color-text-tertiary);
}

.meta-icon {
    width: 16px;
    height: 16px;
}

/* ==========================================
   TIME GRID
   ========================================== */
.time-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--space-lg);
}

.time-item {
    text-align: center;
}

.time-label {
    font-size: var(--font-size-sm);
    font-weight: var(--font-weight-medium);
    color: var(--color-text-secondary);
    margin-bottom: var(--space-xs);
}

.time-value {
    font-size: var(--font-size-lg);
    font-weight: var(--font-weight-semibold);
    color: var(--color-text-primary);
}

.time-item.overtime .time-value {
    color: var(--color-warning);
}

/* ==========================================
   SHIFT INFORMATION
   ========================================== */
.shift-info {
    display: flex;
    flex-direction: column;
    gap: var(--space-lg);
}

.shift-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: var(--space-md);
}

.shift-name {
    font-size: var(--font-size-lg);
    font-weight: var(--font-weight-semibold);
    color: var(--color-text-primary);
    margin: 0;
}

.compliance-badge {
    padding: var(--space-xs) var(--space-sm);
    border-radius: var(--radius-sm);
    font-size: var(--font-size-xs);
    font-weight: var(--font-weight-medium);
}

.compliance-badge.compliance-on_time {
    background: var(--alert-success-bg);
    color: var(--alert-success-text);
}

.compliance-badge.compliance-late_arrival {
    background: var(--alert-warning-bg);
    color: var(--alert-warning-text);
}

.compliance-badge.compliance-early_departure {
    background: var(--alert-error-bg);
    color: var(--alert-error-text);
}

.compliance-badge.compliance-overtime {
    background: var(--color-primary-light);
    color: var(--color-primary);
}

.compliance-badge.compliance-unscheduled {
    background: var(--color-surface-secondary);
    color: var(--color-text-secondary);
}

.shift-schedule {
    background: var(--color-surface-secondary);
    border-radius: var(--radius-md);
    padding: var(--space-lg);
}

.schedule-item {
    display: flex;
    align-items: center;
    gap: var(--space-md);
}

.schedule-icon {
    width: 20px;
    height: 20px;
    color: var(--color-text-secondary);
    flex-shrink: 0;
}

.schedule-details {
    flex: 1;
}

.schedule-time {
    font-size: var(--font-size-base);
    font-weight: var(--font-weight-medium);
    color: var(--color-text-primary);
    margin-bottom: var(--space-xs);
}

.schedule-date {
    font-size: var(--font-size-sm);
    color: var(--color-text-secondary);
}

.shift-metrics {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: var(--space-md);
}

.metric-item {
    text-align: center;
    padding: var(--space-md);
    background: var(--color-surface-secondary);
    border-radius: var(--radius-md);
}

.metric-item.positive .metric-value {
    color: var(--color-success);
}

.metric-item.negative .metric-value {
    color: var(--color-error);
}

.metric-label {
    font-size: var(--font-size-sm);
    color: var(--color-text-secondary);
    margin-bottom: var(--space-xs);
}

.metric-value {
    font-size: var(--font-size-base);
    font-weight: var(--font-weight-semibold);
    color: var(--color-text-primary);
}

.unscheduled-work {
    text-align: center;
    padding: var(--space-xl);
    color: var(--color-text-secondary);
}

.unscheduled-icon {
    width: 48px;
    height: 48px;
    margin: 0 auto var(--space-md);
    color: var(--color-text-tertiary);
}

.unscheduled-work h4 {
    font-size: var(--font-size-lg);
    font-weight: var(--font-weight-semibold);
    color: var(--color-text-primary);
    margin: 0 0 var(--space-sm) 0;
}

.unscheduled-work p {
    margin: 0;
}

/* ==========================================
   PERFORMANCE METRICS
   ========================================== */
.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--space-lg);
}

.metric-card {
    display: flex;
    align-items: center;
    gap: var(--space-lg);
    padding: var(--space-lg);
    background: var(--color-surface-secondary);
    border-radius: var(--radius-md);
}

.metric-score {
    flex-shrink: 0;
}

.score-circle {
    width: 60px;
    height: 60px;
    border-radius: var(--radius-full);
    background: var(--color-primary-light);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    position: relative;
    transition: all 0.3s ease;
}

.score-circle.score-excellent {
    background: linear-gradient(135deg, var(--color-success) 0%, var(--color-success-light) 100%);
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
}

.score-circle.score-good {
    background: linear-gradient(135deg, #10b981 0%, #d1fae5 100%);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.score-circle.score-needs-work {
    background: linear-gradient(135deg, var(--color-warning) 0%, var(--color-warning-light) 100%);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

.score-value {
    font-size: var(--font-size-lg);
    font-weight: var(--font-weight-bold);
    color: white;
}

.score-label {
    font-size: var(--font-size-xs);
    color: rgba(255, 255, 255, 0.9);
}

.metric-info {
    flex: 1;
}

.metric-info h4 {
    font-size: var(--font-size-base);
    font-weight: var(--font-weight-semibold);
    color: var(--color-text-primary);
    margin: 0 0 var(--space-xs) 0;
}

.metric-description {
    font-size: var(--font-size-sm);
    color: var(--color-text-secondary);
    margin: 0;
}

/* ==========================================
   WORK SESSIONS
   ========================================== */
.work-sessions-intro {
    background: linear-gradient(135deg, var(--color-info-light) 0%, var(--color-surface-secondary) 100%);
    border: 1px solid var(--color-info-border);
    border-radius: var(--radius-md);
    padding: var(--space-lg);
    margin-bottom: var(--space-xl);
}

.intro-text {
    font-size: var(--font-size-sm);
    color: var(--color-text-secondary);
    margin: 0;
    display: flex;
    align-items: flex-start;
    gap: var(--space-sm);
    line-height: 1.5;
}

.intro-icon {
    width: 16px;
    height: 16px;
    color: var(--color-info);
    flex-shrink: 0;
    margin-top: 2px;
}

.intervals-list {
    display: flex;
    flex-direction: column;
    gap: var(--space-md);
    margin-bottom: var(--space-xl);
}

.interval-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: var(--space-lg);
    background: var(--color-surface-secondary);
    border-radius: var(--radius-md);
    border: 1px solid var(--color-border-base);
    transition: all 0.2s ease;
}

.interval-item:hover {
    background: var(--color-surface-card);
    transform: translateX(2px);
}

.interval-item.interval-work {
    border-left: 4px solid var(--color-success);
}

.interval-item.interval-break {
    border-left: 4px solid var(--color-warning);
}

.interval-type {
    display: flex;
    align-items: center;
    gap: var(--space-sm);
    font-weight: var(--font-weight-medium);
    color: var(--color-text-primary);
}

.interval-icon {
    width: 20px;
    height: 20px;
    color: var(--color-text-secondary);
}

.interval-type-label {
    font-size: var(--font-size-sm);
}

.interval-time {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: var(--space-xs);
    flex: 1;
    margin-left: var(--space-lg);
}

.time-range {
    display: flex;
    align-items: center;
    gap: var(--space-xs);
    font-size: var(--font-size-sm);
    color: var(--color-text-secondary);
}

.start-time,
.end-time {
    font-weight: var(--font-weight-medium);
}

.end-time {
    color: var(--color-success);
}

.time-separator {
    color: var(--color-text-tertiary);
    font-size: var(--font-size-xs);
}

.interval-duration {
    margin-top: var(--space-xs);
}

.duration-badge {
    display: inline-flex;
    align-items: center;
    padding: var(--space-xs) var(--space-sm);
    border-radius: var(--radius-sm);
    font-size: var(--font-size-xs);
    font-weight: var(--font-weight-medium);
}

.duration-work {
    background: var(--color-success-light);
    color: var(--color-success);
}

.duration-break {
    background: var(--color-warning-light);
    color: var(--color-warning);
}

.duration-active {
    background: var(--color-primary-light);
    color: var(--color-primary);
    animation: pulse 2s infinite;
}

.break-reason {
    margin-top: var(--space-sm);
    padding: var(--space-sm);
    background: rgba(245, 158, 11, 0.1);
    border-radius: var(--radius-sm);
    border-left: 3px solid var(--color-warning);
}

.reason-label {
    font-size: var(--font-size-xs);
    font-weight: var(--font-weight-medium);
    color: var(--color-warning);
}

.reason-text {
    font-size: var(--font-size-sm);
    color: var(--color-text-secondary);
    margin-left: var(--space-xs);
}

.sessions-summary {
    display: flex;
    gap: var(--space-xl);
    padding: var(--space-lg);
    background: var(--color-surface-secondary);
    border-radius: var(--radius-md);
    border: 1px solid var(--color-border-base);
}

.summary-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: var(--space-xs);
}

.summary-label {
    font-size: var(--font-size-sm);
    color: var(--color-text-secondary);
}

.summary-value {
    font-size: var(--font-size-lg);
    font-weight: var(--font-weight-bold);
    color: var(--color-text-primary);
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
    }
}

/* ==========================================
   ENHANCED TIME SUMMARY CARD
   ========================================== */
.time-timeline {
    display: flex;
    flex-direction: column;
    gap: var(--space-lg);
    margin-bottom: var(--space-xl);
}

.timeline-item {
    display: flex;
    align-items: flex-start;
    gap: var(--space-lg);
    position: relative;
}

.timeline-dot {
    width: 48px;
    height: 48px;
    border-radius: var(--radius-full);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    position: relative;
    z-index: 2;
}

.timeline-dot-work {
    background: var(--color-success);
    color: white;
}

.timeline-dot-break {
    background: var(--color-warning);
    color: white;
}

.timeline-dot-overtime {
    background: var(--color-primary);
    color: white;
}

.pulsing-dot {
    width: 16px;
    height: 16px;
    border-radius: var(--radius-full);
    background: var(--color-primary);
    animation: pulsing 2s infinite;
}

@keyframes pulsing {
    0%, 100% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.2);
        opacity: 0.7;
    }
}

.timeline-icon {
    width: 20px;
    height: 20px;
}

.timeline-content {
    flex: 1;
    min-width: 0;
}

.timeline-title {
    font-size: var(--font-size-lg);
    font-weight: var(--font-weight-semibold);
    color: var(--color-text-primary);
    margin-bottom: var(--space-xs);
}

.timeline-title-active {
    color: var(--color-primary);
    animation: pulse 2s infinite;
}

.timeline-time {
    font-size: var(--font-size-base);
    font-weight: var(--font-weight-medium);
    color: var(--color-text-primary);
    margin-bottom: var(--space-xs);
}

.timeline-date {
    font-size: var(--font-size-sm);
    color: var(--color-text-secondary);
    margin-bottom: var(--space-xs);
}

.timeline-relative {
    font-size: var(--font-size-xs);
    color: var(--color-text-tertiary);
}

.timeline-sessions {
    font-size: var(--font-size-sm);
    color: var(--color-text-secondary);
    margin-top: var(--space-xs);
}

.timeline-connector {
    display: flex;
    align-items: center;
    margin-left: 24px;
    position: relative;
    z-index: 1;
}

.timeline-connector-active {
    animation: pulse 3s infinite;
}

.timeline-line {
    width: 2px;
    height: 40px;
    background: var(--color-border-base);
    border-radius: 1px;
    position: relative;
}

.timeline-line-break {
    background: var(--color-warning);
}

.timeline-line-overtime {
    background: var(--color-primary);
}

.timeline-duration {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    background: var(--color-surface-card);
    border: 1px solid var(--color-border-base);
    border-radius: var(--radius-md);
    padding: var(--space-xs) var(--space-sm);
    font-size: var(--font-size-xs);
    font-weight: var(--font-weight-medium);
    color: var(--color-text-secondary);
    box-shadow: var(--color-surface-card-shadow);
}

.duration-text {
    color: var(--color-text-primary);
}

.duration-active {
    color: var(--color-primary);
    font-weight: var(--font-weight-bold);
}



/* ==========================================
   EFFICIENCY OVERVIEW
   ========================================== */
.efficiency-overview {
    background: linear-gradient(135deg, var(--color-info-light) 0%, var(--color-surface-card) 100%);
    border-radius: var(--radius-lg);
    padding: var(--space-lg);
    margin-top: var(--space-xl);
    border: 1px solid var(--color-info-border);
}

.efficiency-main {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--space-md);
}

.efficiency-label {
    display: flex;
    align-items: center;
    gap: var(--space-sm);
    font-size: var(--font-size-sm);
    font-weight: var(--font-weight-medium);
    color: var(--color-text-primary);
}

.efficiency-value {
    font-size: var(--font-size-xl);
    font-weight: var(--font-weight-bold);
    color: var(--color-info);
}

.efficiency-bar {
    width: 100%;
    height: 8px;
    background: var(--color-surface-secondary);
    border-radius: var(--radius-sm);
    overflow: hidden;
    margin-bottom: var(--space-md);
}

.efficiency-description {
    font-size: var(--font-size-sm);
    color: var(--color-text-secondary);
    font-style: italic;
}


/* ==========================================
   RESPONSIVE ENHANCEMENTS
   ========================================== */
@media (max-width: 768px) {
    .time-timeline {
        gap: var(--space-md);
    }

    .timeline-item {
        gap: var(--space-md);
    }

    .timeline-dot {
        width: 40px;
        height: 40px;
    }

    .timeline-connector {
        margin-left: 20px;
    }

    .efficiency-overview {
        padding: var(--space-md);
    }
}

/* ==========================================
   ADDITIONAL INFORMATION
   ========================================== */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--space-lg);
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: var(--space-xs);
}

.info-label {
    font-size: var(--font-size-sm);
    font-weight: var(--font-weight-medium);
    color: var(--color-text-secondary);
}

.info-value {
    font-size: var(--font-size-base);
    color: var(--color-text-primary);
}

.info-value.notes {
    background: var(--color-surface-secondary);
    padding: var(--space-md);
    border-radius: var(--radius-md);
    white-space: pre-wrap;
    font-style: italic;
}

.review-badge.review-needed {
    background: var(--alert-warning-bg);
    color: var(--alert-warning-text);
    padding: var(--space-xs) var(--space-sm);
    border-radius: var(--radius-sm);
    font-size: var(--font-size-xs);
    font-weight: var(--font-weight-medium);
}

.review-reason {
    margin-top: var(--space-sm);
    font-size: var(--font-size-sm);
    color: var(--color-text-secondary);
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
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .content-grid {
        grid-template-columns: 1fr;
    }
    
    .staff-profile {
        flex-direction: column;
        text-align: center;
    }
    
    .time-grid {
        grid-template-columns: 1fr;
    }
    
    .shift-metrics {
        grid-template-columns: 1fr;
    }
    
    .metrics-grid {
        grid-template-columns: 1fr;
    }
    
    .metric-card {
        flex-direction: column;
        text-align: center;
    }
    
    .interval-item {
        flex-direction: column;
        gap: var(--space-md);
        text-align: center;
    }
    
    .interval-time {
        align-items: center;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
