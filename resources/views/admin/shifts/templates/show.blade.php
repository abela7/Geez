@extends('layouts.admin')

@section('title', __('admin.shifts.templates.view_template') . ': ' . $template->name)

@push('styles')
@vite(['resources/css/admin/shifts/templates.css'])
@endpush

@push('scripts')
@vite(['resources/js/admin/shifts/templates.js'])
@endpush

@section('content')
<div class="template-show-page" x-data="templateShowData({{ $template->id }})">
    <!-- Page Header - Matching Assignments Style -->
    <div class="page-header-assignments">
        <div class="header-content">
            <div class="header-text">
                <h1 class="header-title">
                    <svg class="title-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ $template->name }}
                </h1>
                <p class="header-description">
                    {{ $template->description ?: __('admin.shifts.templates.no_description') }}
                </p>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.shifts.shifts.templates.index') }}" class="btn btn-secondary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('admin.shifts.templates.back_to_templates') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Template Status and Meta -->
    <div class="template-meta-section">
        <div class="template-meta-grid">
            <div class="meta-card">
                <div class="meta-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <div class="meta-content">
                    <div class="meta-value">{{ ucfirst($template->type) }}</div>
                    <div class="meta-label">{{ __('admin.shifts.templates.template_type') }}</div>
                </div>
            </div>

            <div class="meta-card">
                <div class="meta-icon" :class="$template->is_active ? 'text-green-500' : 'text-gray-400'">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="meta-content">
                    <div class="meta-value" :class="$template->is_active ? 'text-green-600' : 'text-gray-500'">
                        {{ $template->is_active ? __('admin.shifts.templates.active') : __('admin.shifts.templates.draft') }}
                    </div>
                    <div class="meta-label">{{ __('admin.shifts.templates.status') }}</div>
                </div>
            </div>

            <div class="meta-card">
                <div class="meta-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <div class="meta-content">
                    <div class="meta-value">{{ $template->usage_count }}</div>
                    <div class="meta-label">{{ __('admin.shifts.templates.times_used') }}</div>
                </div>
            </div>

            <div class="meta-card">
                <div class="meta-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="meta-content">
                    <div class="meta-value">£{{ number_format($template->calculateRealWeeklyCost(), 2) }}</div>
                    <div class="meta-label">{{ __('admin.shifts.templates.estimated_cost') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Template Statistics -->
    <div class="template-stats-section">
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-header">
                    <h3 class="stat-title">{{ __('admin.shifts.templates.assignments') }}</h3>
                    <div class="stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-value">{{ $usageStats['total_assignments'] }}</div>
                <div class="stat-description">{{ __('admin.shifts.templates.total_assignments_in_template') }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <h3 class="stat-title">{{ __('admin.shifts.templates.staff') }}</h3>
                    <div class="stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-value">{{ $usageStats['unique_staff_count'] }}</div>
                <div class="stat-description">{{ __('admin.shifts.templates.unique_staff_members') }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <h3 class="stat-title">{{ __('admin.shifts.templates.shifts') }}</h3>
                    <div class="stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-value">{{ $usageStats['shifts_count'] }}</div>
                <div class="stat-description">{{ __('admin.shifts.templates.unique_shifts') }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <h3 class="stat-title">{{ __('admin.shifts.templates.created') }}</h3>
                    <div class="stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h6a2 2 0 012 2v4m-6 8v10m0 0l-3-3m3 3l3-3m-3 3V9"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-value">{{ $template->created_at->format('M j, Y') }}</div>
                <div class="stat-description">
                    {{ __('admin.shifts.templates.created_by') }} {{ $template->creator->full_name ?? 'Unknown' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Assignments by Day -->
    <div class="assignments-section">
        <div class="section-header">
            <h2 class="section-title">{{ __('admin.shifts.templates.assignments_by_day') }}</h2>
            <p class="section-description">{{ __('admin.shifts.templates.assignments_by_day_desc') }}</p>
        </div>

        <div class="days-grid">
            @foreach($assignmentsByDay as $dayNum => $assignments)
                <div class="day-card">
                    <div class="day-header">
                        <h3 class="day-name">{{ $daysOfWeek[$dayNum] ?? 'Unknown' }}</h3>
                        <span class="assignment-count">{{ count($assignments) }}</span>
                    </div>

                    <div class="day-assignments">
                        @if(count($assignments) > 0)
                            @foreach($assignments as $assignment)
                                <div class="assignment-item">
                                    <div class="assignment-main">
                                        <div class="assignment-shift">
                                            <strong>{{ $assignment->shift?->name ?? 'Unknown Shift' }}</strong>
                                            <span class="shift-time">
                                                {{ $assignment->shift ? \Carbon\Carbon::parse($assignment->shift->start_time)->format('H:i') : '00:00' }} -
                                                {{ $assignment->shift ? \Carbon\Carbon::parse($assignment->shift->end_time)->format('H:i') : '00:00' }}
                                            </span>
                                        </div>
                                        <div class="assignment-staff">
                                            {{ $assignment->staff?->full_name ?? 'Unknown Staff' }}
                                            <span class="staff-type">{{ $assignment->staff?->staffType?->display_name ?? '' }}</span>
                                        </div>
                                    </div>
                                    <div class="assignment-meta">
                                        <span class="status-badge status-{{ $assignment->status }}">
                                            {{ ucfirst($assignment->status) }}
                                        </span>
                                        @if($assignment->notes)
                                            <div class="assignment-notes" title="{{ $assignment->notes }}">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="no-assignments">
                                <svg class="no-assignments-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-5.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <p class="no-assignments-text">{{ __('admin.shifts.templates.no_assignments_this_day') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Cost Breakdown -->
    @if(count($costBreakdown) > 0)
        <div class="cost-breakdown-section">
            <div class="section-header">
                <h2 class="section-title">{{ __('admin.shifts.templates.cost_breakdown') }}</h2>
                <p class="section-description">{{ __('admin.shifts.templates.cost_breakdown_desc') }}</p>
            </div>

            <div class="cost-breakdown-grid">
                @foreach($costBreakdown as $dayName => $dayData)
                    <div class="cost-day-card">
                        <div class="cost-day-header">
                            <h3 class="cost-day-name">{{ $dayName }}</h3>
                            <div class="cost-day-total">£{{ number_format($dayData['total_cost'], 2) }}</div>
                        </div>

                        <div class="cost-assignments">
                            @foreach($dayData['assignments'] as $assignment)
                                <div class="cost-assignment">
                                    <div class="cost-assignment-info">
                                        <div class="cost-staff-name">{{ $assignment['staff_name'] }}</div>
                                        <div class="cost-shift-name">{{ $assignment['shift_name'] }}</div>
                                        <div class="cost-shift-type">{{ $assignment['shift_type'] }}</div>
                                    </div>
                                    <div class="cost-details">
                                        <div class="cost-rate">£{{ number_format($assignment['hourly_rate'], 2) }}/hr</div>
                                        <div class="cost-hours">{{ $assignment['hours_worked'] }}hrs</div>
                                        <div class="cost-daily">£{{ number_format($assignment['daily_cost'], 2) }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Recent Usage -->
    @if($template->usage_count > 0)
        <div class="usage-history-section">
            <div class="section-header">
                <h2 class="section-title">{{ __('admin.shifts.templates.usage_history') }}</h2>
                <p class="section-description">
                    {{ __('admin.shifts.templates.last_used') }}:
                    {{ $template->last_used_at ? $template->last_used_at->format('M j, Y \a\t g:i A') : 'Never' }}
                </p>
            </div>

            <div class="usage-stats">
                <div class="usage-stat">
                    <div class="usage-stat-value">{{ $template->usage_count }}</div>
                    <div class="usage-stat-label">{{ __('admin.shifts.templates.total_applications') }}</div>
                </div>
                @if($template->last_used_at)
                    <div class="usage-stat">
                        <div class="usage-stat-value">{{ $template->last_used_at->diffForHumans() }}</div>
                        <div class="usage-stat-label">{{ __('admin.shifts.templates.last_applied') }}</div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="template-actions-section">
        <div class="actions-grid">
            @if($template->is_active)
                <button @click="applyTemplate('{{ $template->name }}')" class="action-btn btn-primary">
                    <svg class="action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="action-text">{{ __('admin.shifts.templates.apply_to_week') }}</span>
                </button>
            @endif

            <a href="{{ route('admin.shifts.shifts.templates.edit', $template->id) }}" class="action-btn btn-secondary">
                <svg class="action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <span class="action-text">{{ __('admin.shifts.templates.edit_template') }}</span>
            </a>

            <button @click="duplicateTemplate('{{ $template->id }}', '{{ addslashes($template->name) }}')" class="action-btn btn-outline">
                <svg class="action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                <span class="action-text">{{ __('admin.shifts.templates.duplicate_template') }}</span>
            </button>

            @if(!$template->is_default)
                <button @click="setAsDefault()" class="action-btn btn-outline">
                    <svg class="action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                    <span class="action-text">{{ __('admin.shifts.templates.set_as_default') }}</span>
                </button>
            @endif
        </div>
    </div>
</div>
@endsection
