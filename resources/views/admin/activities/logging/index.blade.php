@extends('layouts.admin')

@section('title', __('activities.logging.title'))

@section('content')
<div class="activities-logging-page" x-data="loggingData()">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-header-left">
                <h1 class="page-title">{{ __('activities.logging.title') }}</h1>
                <p class="page-description">{{ __('activities.logging.subtitle') }}</p>
            </div>
            <div class="page-header-right">
                <div class="current-time">
                    <svg class="time-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span x-text="currentTime"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Stats -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon stat-icon-time">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ floor($todaysStats['total_time'] / 60) }}h {{ $todaysStats['total_time'] % 60 }}m</div>
                    <div class="stat-label">{{ __('activities.logging.total_time_today') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon stat-icon-completed">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $todaysStats['activities_completed'] }}</div>
                    <div class="stat-label">{{ __('activities.logging.activities_completed') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon stat-icon-progress">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $todaysStats['activities_in_progress'] }}</div>
                    <div class="stat-label">{{ __('activities.logging.activities_in_progress') }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon stat-icon-efficiency">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $todaysStats['efficiency_score'] }}%</div>
                    <div class="stat-label">{{ __('activities.logging.current_efficiency') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-content">
        <!-- Left Column: Current Activities & Quick Start -->
        <div class="left-column">
            <!-- Current Activities -->
            <div class="section-card">
                <div class="section-header">
                    <h2 class="section-title">{{ __('activities.logging.current_activities') }}</h2>
                    <div class="section-badge">{{ count($currentActivities) }}</div>
                </div>
                
                <div class="current-activities-list">
                    @forelse($currentActivities as $activity)
                    <div class="current-activity-item" x-data="{ elapsed: {{ $activity['elapsed_time'] }} }" x-init="updateElapsed()">
                        <div class="activity-header">
                            <div class="activity-info">
                                <h3 class="activity-name">{{ $activity['activity_name'] }}</h3>
                                <div class="activity-meta">
                                    <span class="activity-time">{{ __('activities.logging.started_at') }}: {{ \Carbon\Carbon::parse($activity['started_at'])->format('H:i') }}</span>
                                    <span class="activity-status status-{{ $activity['status'] }}">
                                        {{ __('activities.logging.status_' . $activity['status']) }}
                                    </span>
                                </div>
                            </div>
                            <div class="activity-timer">
                                <div class="timer-display" x-text="formatTime(elapsed)"></div>
                                <div class="timer-label">{{ __('activities.logging.elapsed_time') }}</div>
                            </div>
                        </div>
                        
                        <div class="activity-progress">
                            <div class="progress-bar">
                                <div class="progress-fill" :style="`width: ${Math.min(elapsed / {{ $activity['estimated_duration'] }} * 100, 100)}%`"></div>
                            </div>
                            <div class="progress-text">
                                <span x-text="`${Math.min(Math.round(elapsed / {{ $activity['estimated_duration'] }} * 100), 100)}%`"></span>
                                <span>{{ $activity['estimated_duration'] }}min {{ __('activities.logging.estimated') }}</span>
                            </div>
                        </div>
                        
                        @if($activity['notes'])
                        <div class="activity-notes">
                            <strong>{{ __('activities.logging.notes') }}:</strong> {{ $activity['notes'] }}
                        </div>
                        @endif
                        
                        <div class="activity-actions">
                            @if($activity['status'] === 'in_progress')
                                <button class="btn btn-sm btn-warning" @click="pauseActivity({{ $activity['id'] }})">
                                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ __('activities.logging.pause_activity') }}
                                </button>
                            @else
                                <button class="btn btn-sm btn-success" @click="resumeActivity({{ $activity['id'] }})">
                                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m-5-9V3m0 0V1m0 2l2-2M7 3l2 2"/>
                                    </svg>
                                    {{ __('activities.logging.resume_activity') }}
                                </button>
                            @endif
                            
                            <button class="btn btn-sm btn-danger" @click="stopActivity({{ $activity['id'] }})">
                                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10h6v4H9z"/>
                                </svg>
                                {{ __('activities.logging.stop_activity') }}
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="empty-state-title">{{ __('activities.logging.no_active_activities') }}</h3>
                        <p class="empty-state-description">{{ __('activities.logging.no_active_activities_description') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Quick Start -->
            <div class="section-card">
                <div class="section-header">
                    <h2 class="section-title">{{ __('activities.logging.quick_start') }}</h2>
                </div>
                
                <div class="quick-start-form">
                    <div class="form-group">
                        <label for="activity_select" class="form-label">{{ __('activities.logging.select_activity') }}</label>
                        <select id="activity_select" x-model="selectedActivityId" class="form-select">
                            <option value="">{{ __('activities.logging.select_activity') }}...</option>
                            @foreach($availableActivities as $activity)
                                <option value="{{ $activity['id'] }}">
                                    {{ $activity['name'] }} ({{ $activity['estimated_duration'] }}min)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group" x-show="selectedActivityId">
                        <label for="activity_notes" class="form-label">{{ __('activities.logging.notes') }} ({{ __('activities.common.optional') }})</label>
                        <textarea id="activity_notes" 
                                  x-model="activityNotes" 
                                  class="form-textarea" 
                                  rows="2" 
                                  placeholder="{{ __('activities.logging.notes_placeholder') }}"></textarea>
                    </div>
                    
                    <button class="btn btn-primary btn-block" 
                            @click="startActivity()" 
                            :disabled="!selectedActivityId || isStarting"
                            :class="{ 'loading': isStarting }">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m-5-9V3m0 0V1m0 2l2-2M7 3l2 2"/>
                        </svg>
                        <span x-show="!isStarting">{{ __('activities.logging.start_activity') }}</span>
                        <span x-show="isStarting">{{ __('activities.common.starting') }}...</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Right Column: Recent Activities -->
        <div class="right-column">
            <div class="section-card">
                <div class="section-header">
                    <h2 class="section-title">{{ __('activities.logging.todays_activities') }}</h2>
                    <button class="btn btn-sm btn-secondary" @click="refreshHistory()">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        {{ __('activities.common.refresh') }}
                    </button>
                </div>
                
                <div class="recent-activities-list">
                    @forelse($recentActivities as $activity)
                    <div class="recent-activity-item">
                        <div class="activity-header">
                            <div class="activity-info">
                                <h4 class="activity-name">{{ $activity['activity_name'] }}</h4>
                                <div class="activity-time">
                                    {{ \Carbon\Carbon::parse($activity['started_at'])->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($activity['completed_at'])->format('H:i') }}
                                </div>
                            </div>
                            <div class="activity-duration">
                                <div class="duration-value">{{ $activity['duration'] }}min</div>
                                <div class="efficiency-badge efficiency-{{ $activity['efficiency'] >= 100 ? 'good' : ($activity['efficiency'] >= 80 ? 'average' : 'poor') }}">
                                    {{ $activity['efficiency'] }}%
                                </div>
                            </div>
                        </div>
                        
                        @if($activity['notes'])
                        <div class="activity-notes">
                            {{ $activity['notes'] }}
                        </div>
                        @endif
                        
                        <div class="activity-meta">
                            <span class="status-indicator status-{{ $activity['status'] }}">
                                {{ __('activities.logging.status_' . $activity['status']) }}
                            </span>
                            <span class="estimated-time">
                                {{ __('activities.logging.estimated') }}: {{ $activity['estimated_duration'] }}min
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <h3 class="empty-state-title">{{ __('activities.logging.no_activities_today') }}</h3>
                        <p class="empty-state-description">{{ __('activities.logging.no_activities_today_description') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
@vite('resources/css/admin/activities/logging.css')
@endpush

@push('scripts')
@vite('resources/js/admin/activities/logging.js')
<script>
function loggingData() {
    return {
        currentTime: '',
        selectedActivityId: '',
        activityNotes: '',
        isStarting: false,
        
        init() {
            this.updateCurrentTime();
            setInterval(() => {
                this.updateCurrentTime();
            }, 1000);
        },
        
        updateCurrentTime() {
            const now = new Date();
            this.currentTime = now.toLocaleTimeString('en-US', { 
                hour12: false, 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit' 
            });
        },
        
        updateElapsed() {
            setInterval(() => {
                this.elapsed++;
            }, 60000); // Update every minute
        },
        
        formatTime(minutes) {
            const hours = Math.floor(minutes / 60);
            const mins = minutes % 60;
            return hours > 0 ? `${hours}h ${mins}m` : `${mins}m`;
        },
        
        startActivity() {
            if (!this.selectedActivityId) return;
            
            this.isStarting = true;
            
            // Simulate API call
            setTimeout(() => {
                this.showNotification('Activity started successfully!', 'success');
                this.selectedActivityId = '';
                this.activityNotes = '';
                this.isStarting = false;
                
                // In real implementation, refresh current activities
                setTimeout(() => window.location.reload(), 1000);
            }, 1000);
        },
        
        pauseActivity(activityLogId) {
            this.showNotification('Activity paused', 'info');
            // In real implementation, make API call and update UI
            setTimeout(() => window.location.reload(), 500);
        },
        
        resumeActivity(activityLogId) {
            this.showNotification('Activity resumed', 'success');
            // In real implementation, make API call and update UI
            setTimeout(() => window.location.reload(), 500);
        },
        
        stopActivity(activityLogId) {
            if (!confirm('Are you sure you want to stop this activity?')) {
                return;
            }
            
            this.showNotification('Activity completed!', 'success');
            // In real implementation, make API call and update UI
            setTimeout(() => window.location.reload(), 1000);
        },
        
        refreshHistory() {
            this.showNotification('Refreshing activity history...', 'info');
            // In real implementation, make API call and update history
            setTimeout(() => {
                this.showNotification('History refreshed', 'success');
            }, 1000);
        },
        
        showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.innerHTML = `
                <div class="notification-content">
                    <span class="notification-message">${message}</span>
                    <button class="notification-close" onclick="this.parentElement.parentElement.remove()">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            `;
            
            // Add to page
            document.body.appendChild(notification);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }
    };
}
</script>
@endpush
