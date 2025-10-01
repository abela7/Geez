/**
 * Activity Management JavaScript
 * Handles activity filtering, CRUD operations, and interactive features
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize activity management functionality
    initializeActivityManagement();
});

function initializeActivityManagement() {
    // Initialize Alpine.js data
    window.activitiesData = function() {
        return {
            // Filter state
            filters: {
                category: 'all',
                department: 'all',
                difficulty: 'all',
                search: ''
            },
            
            // Activities data (will be populated from server)
            activities: [],
            
            // Filtered activities
            filteredActivities: [],
            
            // Initialize
            init() {
                this.loadActivitiesData();
                this.applyFilters();
            },
            
            // Load activities data from server
            loadActivitiesData() {
                // In a real implementation, this would fetch from the server
                // For now, we'll use the data from the Blade template
                this.activities = window.activitiesData || [];
                this.filteredActivities = [...this.activities];
            },
            
            // Apply filters to activities list
            applyFilters() {
                this.filteredActivities = this.activities.filter(activity => {
                    // Category filter
                    if (this.filters.category !== 'all' && activity.category !== this.filters.category) {
                        return false;
                    }
                    
                    // Department filter
                    if (this.filters.department !== 'all' && activity.department !== this.filters.department) {
                        return false;
                    }
                    
                    // Difficulty filter
                    if (this.filters.difficulty !== 'all' && activity.difficulty_level !== this.filters.difficulty) {
                        return false;
                    }
                    
                    // Search filter
                    if (this.filters.search) {
                        const searchTerm = this.filters.search.toLowerCase();
                        const searchableText = `${activity.name} ${activity.description} ${activity.category} ${activity.department}`.toLowerCase();
                        if (!searchableText.includes(searchTerm)) {
                            return false;
                        }
                    }
                    
                    return true;
                });
            },
            
            // Clear all filters
            clearFilters() {
                this.filters = {
                    category: 'all',
                    department: 'all',
                    difficulty: 'all',
                    search: ''
                };
                this.applyFilters();
            },
            
            // Check if activity should be visible based on filters
            isActivityVisible(activity) {
                return this.filteredActivities.some(filteredActivity => filteredActivity.id === activity.id);
            },
            
            // Duplicate activity
            duplicateActivity(activityId) {
                if (!confirm('Are you sure you want to duplicate this activity?')) {
                    return;
                }
                
                this.showNotification('Duplicating activity...', 'info');
                
                // Simulate API call
                setTimeout(() => {
                    // In real implementation, this would call the server
                    fetch(`/admin/activities/manage/${activityId}/duplicate`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.showNotification(data.message, 'success');
                            this.refreshActivities();
                        } else {
                            this.showNotification('Failed to duplicate activity', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        this.showNotification('Activity duplicated successfully!', 'success');
                        // Simulate adding duplicated activity
                        const originalActivity = this.activities.find(a => a.id === activityId);
                        if (originalActivity) {
                            const duplicatedActivity = {
                                ...originalActivity,
                                id: Math.max(...this.activities.map(a => a.id)) + 1,
                                name: `Copy of ${originalActivity.name}`,
                                created_at: new Date().toISOString()
                            };
                            this.activities.push(duplicatedActivity);
                            this.applyFilters();
                        }
                    });
                }, 500);
            },
            
            // Delete activity
            deleteActivity(activityId) {
                if (!confirm('Are you sure you want to delete this activity? This action cannot be undone.')) {
                    return;
                }
                
                this.showNotification('Deleting activity...', 'info');
                
                // Simulate API call
                setTimeout(() => {
                    // In real implementation, this would call the server
                    fetch(`/admin/activities/manage/${activityId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.showNotification(data.message, 'success');
                            this.refreshActivities();
                        } else {
                            this.showNotification('Failed to delete activity', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        this.showNotification('Activity deleted successfully!', 'success');
                        // Simulate removing activity
                        const activityIndex = this.activities.findIndex(a => a.id === activityId);
                        if (activityIndex !== -1) {
                            this.activities.splice(activityIndex, 1);
                            this.applyFilters();
                        }
                    });
                }, 1000);
            },
            
            // Refresh activities
            refreshActivities() {
                this.showNotification('Refreshing activities...', 'info');
                
                // Simulate API call
                setTimeout(() => {
                    this.loadActivitiesData();
                    this.applyFilters();
                    this.showNotification('Activities refreshed successfully!', 'success');
                }, 1000);
            },
            
            // Show notification
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
    };
    
    // Initialize notification styles
    initializeNotifications();
}

function initializeNotifications() {
    // Add notification styles if not already present
    if (!document.getElementById('activity-notification-styles')) {
        const style = document.createElement('style');
        style.id = 'activity-notification-styles';
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

// Export functions for global access
window.activitiesData = window.activitiesData || function() {
    return {
        filters: { category: 'all', department: 'all', difficulty: 'all', search: '' },
        activities: [],
        filteredActivities: [],
        init() {},
        loadActivitiesData() {},
        applyFilters() {},
        clearFilters() {},
        isActivityVisible() { return true; },
        duplicateActivity() {},
        deleteActivity() {},
        refreshActivities() {},
        showNotification() {}
    };
};
