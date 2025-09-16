/**
 * Activity Logging JavaScript
 * Handles activity logging, timers, and real-time updates
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize activity logging functionality
    initializeActivityLogging();
});

function initializeActivityLogging() {
    // Initialize notification styles
    initializeNotifications();
    
    // Set up real-time updates for activity timers
    initializeTimers();
    
    // Set up auto-refresh for current activities
    initializeAutoRefresh();
}

function initializeTimers() {
    // Update all activity timers every minute
    setInterval(() => {
        updateActivityTimers();
    }, 60000); // 60 seconds
}

function updateActivityTimers() {
    // Find all current activity items and update their elapsed time
    const activityItems = document.querySelectorAll('.current-activity-item');
    
    activityItems.forEach(item => {
        const timerDisplay = item.querySelector('.timer-display');
        const progressFill = item.querySelector('.progress-fill');
        const progressText = item.querySelector('.progress-text span:first-child');
        
        if (timerDisplay && progressFill && progressText) {
            // Get current elapsed time from Alpine.js data
            const alpineData = Alpine.$data(item);
            if (alpineData && typeof alpineData.elapsed !== 'undefined') {
                alpineData.elapsed += 1; // Increment by 1 minute
                
                // Update timer display
                timerDisplay.textContent = formatTime(alpineData.elapsed);
                
                // Update progress bar (assuming estimated duration is available)
                const estimatedDuration = parseInt(item.dataset.estimatedDuration) || 60;
                const progressPercentage = Math.min((alpineData.elapsed / estimatedDuration) * 100, 100);
                progressFill.style.width = `${progressPercentage}%`;
                progressText.textContent = `${Math.round(progressPercentage)}%`;
            }
        }
    });
}

function formatTime(minutes) {
    const hours = Math.floor(minutes / 60);
    const mins = minutes % 60;
    return hours > 0 ? `${hours}h ${mins}m` : `${mins}m`;
}

function initializeAutoRefresh() {
    // Auto-refresh current activities every 5 minutes
    setInterval(() => {
        refreshCurrentActivities();
    }, 300000); // 5 minutes
}

function refreshCurrentActivities() {
    // In a real implementation, this would fetch current activities from the server
    console.log('Auto-refreshing current activities...');
    
    // Make API call to get current activities
    fetch('/admin/activities/logging/current')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the current activities section
                updateCurrentActivitiesDisplay(data.current_activities);
            }
        })
        .catch(error => {
            console.error('Error refreshing current activities:', error);
        });
}

function updateCurrentActivitiesDisplay(currentActivities) {
    // In a real implementation, this would update the DOM with new activity data
    console.log('Updating current activities display:', currentActivities);
}

function initializeNotifications() {
    // Add notification styles if not already present
    if (!document.getElementById('activity-logging-notification-styles')) {
        const style = document.createElement('style');
        style.id = 'activity-logging-notification-styles';
        style.textContent = `
            .notification {
                position: fixed;
                top: 1rem;
                right: 1rem;
                z-index: 1001;
                max-width: 400px;
                border-radius: var(--border-radius-lg);
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                animation: slideIn 0.3s ease-out;
            }
            
            .notification-content {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 1rem;
                color: white;
                font-weight: 500;
            }
            
            .notification-info {
                background: var(--color-primary);
            }
            
            .notification-success {
                background: var(--color-success);
            }
            
            .notification-error {
                background: var(--color-danger);
            }
            
            .notification-warning {
                background: var(--color-warning);
            }
            
            .notification-message {
                flex: 1;
                margin-right: 0.75rem;
            }
            
            .notification-close {
                background: none;
                border: none;
                color: white;
                cursor: pointer;
                padding: 0.25rem;
                border-radius: var(--border-radius);
                transition: background-color 0.2s ease;
            }
            
            .notification-close:hover {
                background: rgba(255, 255, 255, 0.2);
            }
            
            .notification-close svg {
                width: 1rem;
                height: 1rem;
            }
            
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
        `;
        document.head.appendChild(style);
    }
}

// Activity logging API functions
window.ActivityLoggingAPI = {
    startActivity: function(activityId, notes = '') {
        return fetch('/admin/activities/logging/start', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify({
                activity_id: activityId,
                notes: notes
            })
        }).then(response => response.json());
    },
    
    stopActivity: function(activityLogId, notes = '') {
        return fetch('/admin/activities/logging/stop', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify({
                activity_log_id: activityLogId,
                notes: notes
            })
        }).then(response => response.json());
    },
    
    pauseActivity: function(activityLogId) {
        return fetch('/admin/activities/logging/pause', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify({
                activity_log_id: activityLogId
            })
        }).then(response => response.json());
    },
    
    resumeActivity: function(activityLogId) {
        return fetch('/admin/activities/logging/resume', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify({
                activity_log_id: activityLogId
            })
        }).then(response => response.json());
    },
    
    getCurrentActivities: function() {
        return fetch('/admin/activities/logging/current')
            .then(response => response.json());
    },
    
    getHistory: function(date = null) {
        const url = date 
            ? `/admin/activities/logging/history?date=${date}`
            : '/admin/activities/logging/history';
        
        return fetch(url).then(response => response.json());
    }
};

// Utility functions
window.ActivityLoggingUtils = {
    formatTime: formatTime,
    
    formatDuration: function(startTime, endTime) {
        const start = new Date(startTime);
        const end = new Date(endTime);
        const diffMinutes = Math.round((end - start) / (1000 * 60));
        return formatTime(diffMinutes);
    },
    
    calculateEfficiency: function(actualDuration, estimatedDuration) {
        if (actualDuration <= 0 || estimatedDuration <= 0) return 0;
        return Math.round((estimatedDuration / actualDuration) * 100);
    },
    
    getEfficiencyClass: function(efficiency) {
        if (efficiency >= 100) return 'good';
        if (efficiency >= 80) return 'average';
        return 'poor';
    },
    
    getStatusColor: function(status) {
        const colors = {
            'in_progress': 'var(--color-success)',
            'paused': 'var(--color-warning)',
            'completed': 'var(--color-info)',
            'cancelled': 'var(--color-danger)'
        };
        return colors[status] || 'var(--color-text-muted)';
    }
};

// Export for global access
window.initializeActivityLogging = initializeActivityLogging;
window.updateActivityTimers = updateActivityTimers;
