@extends('layouts.admin')

@section('title', __('shifts.weekly_schedule.title'))

@push('styles')
@vite(['resources/css/admin/shifts/assignments.css'])
@endpush

@push('scripts')
@vite(['resources/js/admin/shifts/assignments.js'])
@endpush

@section('content')
<div class="weekly-schedule-page" x-data="weeklyScheduleData()">

    <!-- Week Navigation -->
    <div class="week-navigation">
        <div class="week-nav-content">
            <div class="week-nav-controls">
                <a href="{{ route('admin.shifts.weekly-schedule.index', ['date' => $weekStart->copy()->subWeek()->format('Y-m-d')]) }}" 
                   class="btn-week-nav">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    {{ __('shifts.overview.previous_week') }}
                </a>
                
                <div class="current-week">
                    <h2 class="week-title">{{ $weekStart->format('M j') }} - {{ $weekEnd->format('M j, Y') }}</h2>
                    <p class="week-subtitle">Week {{ $weekStart->weekOfYear }} of {{ $weekStart->year }}</p>
                </div>
                
                <a href="{{ route('admin.shifts.weekly-schedule.index', ['date' => $weekStart->copy()->addWeek()->format('Y-m-d')]) }}" 
                   class="btn-week-nav">
                    {{ __('shifts.overview.next_week') }}
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            
            <div class="week-actions">
                <div class="schedule-status">
                    <span class="status-badge status-{{ $weeklySchedule->status }}">
                        {{ __('shifts.weekly_schedule.' . $weeklySchedule->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly Schedule Summary -->
    <div class="schedule-summary">
        <div class="summary-cards">
            <div class="summary-card">
                <div class="card-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="card-content">
                    <div class="card-value">{{ $weeklySchedule->total_shifts }}</div>
                    <div class="card-label">{{ __('shifts.weekly_schedule.total_shifts') }}</div>
                </div>
            </div>

            <div class="summary-card">
                <div class="card-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="card-content">
                    <div class="card-value">{{ $weeklySchedule->total_staff_assignments }}</div>
                    <div class="card-label">{{ __('shifts.weekly_schedule.total_assignments') }}</div>
                </div>
            </div>

            <div class="summary-card">
                <div class="card-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="card-content">
                    <div class="card-value">{{ number_format($weeklySchedule->total_scheduled_hours, 1) }}</div>
                    <div class="card-label">{{ __('shifts.weekly_schedule.scheduled_hours') }}</div>
                </div>
            </div>

            <div class="summary-card">
                <div class="card-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
                <div class="card-content">
                    <div class="card-value">${{ number_format($weeklySchedule->estimated_labor_cost, 2) }}</div>
                    <div class="card-label">{{ __('shifts.weekly_schedule.labor_cost') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Panel -->
    <div class="actions-panel">
        <div class="action-buttons">
            @if($weeklySchedule->canBeEdited())
                <button @click="showTemplateModal = true" class="btn btn-primary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                    </svg>
                    {{ __('shifts.weekly_schedule.apply_template') }}
                </button>

                @if($weeklySchedule->status === 'draft')
                    <button @click="publishSchedule()" class="btn btn-success">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ __('shifts.weekly_schedule.publish_schedule') }}
                    </button>
                @endif
            @endif

            <a href="{{ route('admin.shifts.assignments.index', ['week' => $weekStart->format('Y-m-d')]) }}" class="btn btn-outline">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Assignments
            </a>
        </div>
    </div>

    <!-- Weekly Schedule Grid -->
    <div class="schedule-grid">
        <div class="grid-header">
            <div class="day-header">Shifts</div>
            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                <div class="day-header">{{ $day }}</div>
            @endforeach
        </div>

        <div class="grid-body">
            @foreach($assignmentsByDay as $dayOfWeek => $dayAssignments)
                @if($dayAssignments->isNotEmpty())
                    @foreach($dayAssignments->groupBy('staff_shift_id') as $shiftId => $shiftAssignments)
                        @php
                            $shift = $shiftAssignments->first()->staffShiftAssignment->shift;
                        @endphp
                        <div class="shift-row">
                            <div class="shift-info">
                                <h4>{{ $shift->name }}</h4>
                                <p>{{ Carbon\Carbon::parse($shift->start_time)->format('H:i') }} - {{ Carbon\Carbon::parse($shift->end_time)->format('H:i') }}</p>
                                <span class="department-badge">{{ $shift->department }}</span>
                            </div>
                            
                            @for($day = 1; $day <= 7; $day++)
                                @php
                                    $dayAssignment = $shiftAssignments->where('day_of_week', $day === 7 ? 0 : $day)->first();
                                @endphp
                                <div class="assignment-cell">
                                    @if($dayAssignment)
                                        <div class="assigned-staff">
                                            <div class="staff-avatar">
                                                {{ substr($dayAssignment->staffShiftAssignment->staff->first_name, 0, 1) }}{{ substr($dayAssignment->staffShiftAssignment->staff->last_name, 0, 1) }}
                                            </div>
                                            <div class="staff-info">
                                                <div class="staff-name">{{ $dayAssignment->staffShiftAssignment->staff->first_name }}</div>
                                                <div class="staff-role">{{ $dayAssignment->staffShiftAssignment->staff->staffType->display_name ?? 'Staff' }}</div>
                                            </div>
                                            <div class="assignment-status">
                                                <span class="status-dot status-{{ $dayAssignment->assignment_status }}"></span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="empty-assignment">
                                            <span class="text-muted">No assignment</span>
                                        </div>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    @endforeach
                @endif
            @endforeach
        </div>
    </div>

    <!-- Template Selection Modal -->
    <div x-show="showTemplateModal" class="modal-overlay" @click="showTemplateModal = false">
        <div class="modal-content" @click.stop>
            <div class="modal-header">
                <h3>{{ __('shifts.weekly_schedule.apply_template') }}</h3>
                <button @click="showTemplateModal = false" class="modal-close">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <div class="modal-body">
                <div class="template-list">
                    @foreach($templates as $template)
                        <div class="template-item" @click="applyTemplate('{{ $template->id }}')">
                            <div class="template-info">
                                <h4>{{ $template->name }}</h4>
                                <p>{{ $template->description }}</p>
                                <div class="template-meta">
                                    <span class="usage-count">Used {{ $template->usage_count }} times</span>
                                    @if($template->is_default)
                                        <span class="default-badge">Default</span>
                                    @endif
                                </div>
                            </div>
                            <div class="template-actions">
                                <button class="btn btn-sm btn-primary">Apply</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Schedules -->
    @if($upcomingSchedules->isNotEmpty())
        <div class="upcoming-schedules">
            <h3>Upcoming Schedules Needing Attention</h3>
            <div class="schedule-list">
                @foreach($upcomingSchedules as $schedule)
                    <div class="schedule-item">
                        <div class="schedule-info">
                            <h4>{{ $schedule->getDisplayName() }}</h4>
                            <p>{{ $schedule->week_start_date->format('M j') }} - {{ $schedule->week_end_date->format('M j, Y') }}</p>
                        </div>
                        <div class="schedule-status">
                            <span class="status-badge status-{{ $schedule->status }}">{{ __('shifts.weekly_schedule.' . $schedule->status) }}</span>
                        </div>
                        <div class="schedule-actions">
                            <a href="{{ route('admin.shifts.weekly-schedule.index', ['date' => $schedule->week_start_date->format('Y-m-d')]) }}" class="btn btn-sm btn-outline">
                                Manage
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>

<script>
function weeklyScheduleData() {
    return {
        showTemplateModal: false,
        
        async applyTemplate(templateId) {
            if (!confirm('{{ __('shifts.weekly_schedule.confirm_apply_template') }}')) {
                return;
            }
            
            try {
                const response = await fetch(`{{ route('admin.shifts.weekly-schedule.apply-template', $weeklySchedule) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ template_id: templateId })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showTemplateModal = false;
                    location.reload();
                } else {
                    alert(data.message || 'Error applying template');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error applying template');
            }
        },
        
        async publishSchedule() {
            if (!confirm('{{ __('shifts.weekly_schedule.confirm_publish') }}')) {
                return;
            }
            
            try {
                const response = await fetch(`{{ route('admin.shifts.weekly-schedule.publish', $weeklySchedule) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Error publishing schedule');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error publishing schedule');
            }
        }
    }
}
</script>

<style>
.status-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
}

.status-draft { background: #fef3c7; color: #92400e; }
.status-published { background: #dbeafe; color: #1e40af; }
.status-active { background: #d1fae5; color: #065f46; }
.status-completed { background: #e5e7eb; color: #374151; }
.status-archived { background: #f3f4f6; color: #6b7280; }

.schedule-summary {
    margin: 1.5rem 0;
}

.summary-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.summary-card {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    border: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.card-icon {
    width: 3rem;
    height: 3rem;
    color: #6366f1;
}

.card-value {
    font-size: 2rem;
    font-weight: 700;
    color: #111827;
}

.card-label {
    font-size: 0.875rem;
    color: #6b7280;
}

.actions-panel {
    margin: 1.5rem 0;
    padding: 1rem;
    background: #f9fafb;
    border-radius: 8px;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.schedule-grid {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #e5e7eb;
}

.grid-header {
    display: grid;
    grid-template-columns: 200px repeat(7, 1fr);
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}

.day-header {
    padding: 1rem;
    font-weight: 600;
    text-align: center;
    border-right: 1px solid #e5e7eb;
}

.shift-row {
    display: grid;
    grid-template-columns: 200px repeat(7, 1fr);
    border-bottom: 1px solid #e5e7eb;
}

.shift-info {
    padding: 1rem;
    border-right: 1px solid #e5e7eb;
    background: #fafafa;
}

.shift-info h4 {
    margin: 0 0 0.5rem 0;
    font-size: 0.875rem;
    font-weight: 600;
}

.shift-info p {
    margin: 0 0 0.5rem 0;
    font-size: 0.75rem;
    color: #6b7280;
}

.department-badge {
    font-size: 0.625rem;
    padding: 2px 6px;
    background: #e5e7eb;
    border-radius: 4px;
    color: #374151;
}

.assignment-cell {
    padding: 0.5rem;
    border-right: 1px solid #e5e7eb;
    min-height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.assigned-staff {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    width: 100%;
}

.staff-avatar {
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    background: #6366f1;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 600;
}

.staff-info {
    flex: 1;
}

.staff-name {
    font-size: 0.75rem;
    font-weight: 500;
    color: #111827;
}

.staff-role {
    font-size: 0.625rem;
    color: #6b7280;
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
}

.status-dot.status-scheduled { background: #fbbf24; }
.status-dot.status-confirmed { background: #10b981; }
.status-dot.status-cancelled { background: #ef4444; }
.status-dot.status-completed { background: #6b7280; }

.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 50;
}

.modal-content {
    background: white;
    border-radius: 8px;
    max-width: 600px;
    width: 90%;
    max-height: 80vh;
    overflow-y: auto;
}

.modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-close {
    background: none;
    border: none;
    padding: 0.5rem;
    cursor: pointer;
    color: #6b7280;
}

.modal-close:hover {
    color: #374151;
}

.modal-close svg {
    width: 1.5rem;
    height: 1.5rem;
}

.template-list {
    padding: 1.5rem;
}

.template-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    margin-bottom: 1rem;
    cursor: pointer;
    transition: all 0.2s;
}

.template-item:hover {
    border-color: #6366f1;
    background: #f8fafc;
}

.template-info h4 {
    margin: 0 0 0.5rem 0;
    font-size: 1rem;
    font-weight: 600;
}

.template-info p {
    margin: 0 0 0.5rem 0;
    color: #6b7280;
    font-size: 0.875rem;
}

.template-meta {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.usage-count {
    font-size: 0.75rem;
    color: #6b7280;
}

.default-badge {
    background: #10b981;
    color: white;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.625rem;
    font-weight: 500;
}

.upcoming-schedules {
    margin-top: 2rem;
    padding: 1.5rem;
    background: white;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.upcoming-schedules h3 {
    margin: 0 0 1rem 0;
    color: #111827;
}

.schedule-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.schedule-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
}

.schedule-info h4 {
    margin: 0 0 0.25rem 0;
    font-size: 1rem;
    font-weight: 600;
}

.schedule-info p {
    margin: 0;
    color: #6b7280;
    font-size: 0.875rem;
}

.schedule-actions {
    display: flex;
    gap: 0.5rem;
}
</style>
@endsection
