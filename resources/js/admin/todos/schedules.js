/**
 * Schedules JavaScript
 * Handles schedule management, filtering, and CRUD operations
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize schedules functionality
    initializeSchedules();
});

function initializeSchedules() {
    // Initialize Alpine.js data
    window.schedulesData = function() {
        return {
            // Filter state
            filters: {
                frequency: 'all',
                status: 'all',
                search: ''
            },
            
            // Schedules data (will be populated from server)
            schedules: [],
            
            // Filtered schedules
            filteredSchedules: [],
            
            // Initialize
            init() {
                this.loadSchedulesData();
                this.applyFilters();
            },
            
            // Load schedules data from server
            loadSchedulesData() {
                // In a real implementation, this would fetch from the server
                // For now, we'll use the data from the Blade template
                this.schedules = window.schedulesData || [];
                this.filteredSchedules = [...this.schedules];
            },
            
            // Apply filters to schedules list
            applyFilters() {
                this.filteredSchedules = this.schedules.filter(schedule => {
                    // Frequency filter
                    if (this.filters.frequency !== 'all' && schedule.frequency_type !== this.filters.frequency) {
                        return false;
                    }
                    
                    // Status filter
                    if (this.filters.status !== 'all') {
                        const statusMatch = this.filters.status === 'active' ? schedule.is_active : !schedule.is_active;
                        if (!statusMatch) {
                            return false;
                        }
                    }
                    
                    // Search filter
                    if (this.filters.search) {
                        const searchTerm = this.filters.search.toLowerCase();
                        const searchableText = `${schedule.name} ${schedule.description} ${schedule.template_name || ''}`.toLowerCase();
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
                    frequency: 'all',
                    status: 'all',
                    search: ''
                };
                this.applyFilters();
            },
            
            // Check if schedule should be visible based on filters
            isScheduleVisible(schedule) {
                return this.filteredSchedules.some(filteredSchedule => filteredSchedule.id === schedule.id);
            },
            
            // Toggle schedule active status
            toggleScheduleStatus(scheduleId) {
                this.showNotification('Updating schedule status...', 'info');
                
                // Simulate API call
                setTimeout(() => {
                    // Update local data
                    const schedule = this.schedules.find(s => s.id === scheduleId);
                    if (schedule) {
                        schedule.is_active = !schedule.is_active;
                        this.applyFilters();
                        this.showNotification(
                            `Schedule ${schedule.is_active ? 'activated' : 'deactivated'} successfully!`, 
                            'success'
                        );
                    }
                }, 500);
            },
            
            // Activate schedule
            activateSchedule(scheduleId) {
                if (!confirm('Are you sure you want to activate this schedule?')) {
                    return;
                }
                
                this.showNotification('Activating schedule...', 'info');
                
                // Simulate API call
                setTimeout(() => {
                    const schedule = this.schedules.find(s => s.id === scheduleId);
                    if (schedule) {
                        schedule.is_active = true;
                        this.applyFilters();
                        this.showNotification('Schedule activated successfully!', 'success');
                    }
                }, 1000);
            },
            
            // Deactivate schedule
            deactivateSchedule(scheduleId) {
                if (!confirm('Are you sure you want to deactivate this schedule? This will stop generating new todos.')) {
                    return;
                }
                
                this.showNotification('Deactivating schedule...', 'info');
                
                // Simulate API call
                setTimeout(() => {
                    const schedule = this.schedules.find(s => s.id === scheduleId);
                    if (schedule) {
                        schedule.is_active = false;
                        this.applyFilters();
                        this.showNotification('Schedule deactivated successfully!', 'success');
                    }
                }, 1000);
            },
            
            // Delete schedule
            deleteSchedule(scheduleId) {
                if (!confirm('Are you sure you want to delete this schedule? This action cannot be undone.')) {
                    return;
                }
                
                this.showNotification('Deleting schedule...', 'info');
                
                // Simulate API call
                setTimeout(() => {
                    const scheduleIndex = this.schedules.findIndex(s => s.id === scheduleId);
                    if (scheduleIndex !== -1) {
                        this.schedules.splice(scheduleIndex, 1);
                        this.applyFilters();
                        this.showNotification('Schedule deleted successfully!', 'success');
                    }
                }, 1000);
            },
            
            // Refresh schedules
            refreshSchedules() {
                this.showNotification('Refreshing schedules...', 'info');
                
                // Simulate API call
                setTimeout(() => {
                    this.loadSchedulesData();
                    this.applyFilters();
                    this.showNotification('Schedules refreshed successfully!', 'success');
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

// Schedule form handling
function initializeScheduleForm() {
    return {
        // Form data
        form: {
            name: '',
            description: '',
            frequency_type: 'daily',
            frequency_value: 1,
            specific_time: '09:00',
            days_of_week: [],
            days_of_month: [],
            start_date: new Date().toISOString().split('T')[0],
            end_date: '',
            template_id: '',
            assigned_staff: [],
            auto_assign: true,
            is_active: true
        },
        
        // Form state
        isSubmitting: false,
        errors: {},
        
        // Additional state
        monthType: 'specific_dates',
        weekOccurrence: '1',
        weekDay: '1',
        
        // Days of week
        daysOfWeek: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        
        // Update frequency options based on type
        updateFrequencyOptions() {
            // Reset dependent fields when frequency type changes
            this.form.days_of_week = [];
            this.form.days_of_month = [];
            
            // Set default values based on frequency type
            switch (this.form.frequency_type) {
                case 'hourly':
                    this.form.frequency_value = 1;
                    this.form.specific_time = '';
                    break;
                case 'daily':
                    this.form.frequency_value = 1;
                    this.form.specific_time = '09:00';
                    break;
                case 'weekly':
                    this.form.frequency_value = 1;
                    this.form.specific_time = '09:00';
                    this.form.days_of_week = [1]; // Monday
                    break;
                case 'monthly':
                    this.form.frequency_value = 1;
                    this.form.specific_time = '09:00';
                    this.monthType = 'specific_dates';
                    this.form.days_of_month = [1]; // 1st of month
                    break;
                case 'custom':
                    this.form.frequency_value = 3;
                    this.form.specific_time = '09:00';
                    break;
            }
        },
        
        // Get maximum frequency value based on type
        getMaxFrequencyValue() {
            switch (this.form.frequency_type) {
                case 'hourly': return 23;
                case 'daily': return 30;
                case 'weekly': return 52;
                case 'monthly': return 12;
                case 'custom': return 365;
                default: return 100;
            }
        },
        
        // Get frequency unit text
        getFrequencyUnit() {
            switch (this.form.frequency_type) {
                case 'hourly': return this.form.frequency_value === 1 ? 'hour' : 'hours';
                case 'daily': return this.form.frequency_value === 1 ? 'day' : 'days';
                case 'weekly': return this.form.frequency_value === 1 ? 'week' : 'weeks';
                case 'monthly': return this.form.frequency_value === 1 ? 'month' : 'months';
                case 'custom': return this.form.frequency_value === 1 ? 'day' : 'days';
                default: return '';
            }
        },
        
        // Get frequency help text
        getFrequencyHelp() {
            switch (this.form.frequency_type) {
                case 'hourly': return `Runs every ${this.form.frequency_value} hour(s) during operating hours`;
                case 'daily': return `Runs every ${this.form.frequency_value} day(s)`;
                case 'weekly': return `Runs every ${this.form.frequency_value} week(s) on selected days`;
                case 'monthly': return `Runs every ${this.form.frequency_value} month(s) on selected dates`;
                case 'custom': return `Runs every ${this.form.frequency_value} day(s) - custom interval`;
                default: return '';
            }
        },
        
        // Get schedule summary
        getScheduleSummary() {
            let summary = `${this.form.name} - `;
            
            switch (this.form.frequency_type) {
                case 'hourly':
                    summary += `Every ${this.form.frequency_value} hour(s)`;
                    break;
                case 'daily':
                    summary += `Every ${this.form.frequency_value} day(s)`;
                    if (this.form.specific_time) {
                        summary += ` at ${this.form.specific_time}`;
                    }
                    break;
                case 'weekly':
                    summary += `Every ${this.form.frequency_value} week(s)`;
                    if (this.form.days_of_week.length > 0) {
                        const dayNames = this.form.days_of_week.map(day => this.daysOfWeek[day]);
                        summary += ` on ${dayNames.join(', ')}`;
                    }
                    if (this.form.specific_time) {
                        summary += ` at ${this.form.specific_time}`;
                    }
                    break;
                case 'monthly':
                    summary += `Every ${this.form.frequency_value} month(s)`;
                    if (this.form.specific_time) {
                        summary += ` at ${this.form.specific_time}`;
                    }
                    break;
                case 'custom':
                    summary += `Every ${this.form.frequency_value} day(s)`;
                    if (this.form.specific_time) {
                        summary += ` at ${this.form.specific_time}`;
                    }
                    break;
            }
            
            return summary;
        },
        
        // Get next execution time
        getNextExecution() {
            // This is a simplified calculation for demo purposes
            const now = new Date();
            const startDate = new Date(this.form.start_date);
            const baseDate = startDate > now ? startDate : now;
            
            let nextDate = new Date(baseDate);
            
            switch (this.form.frequency_type) {
                case 'hourly':
                    nextDate.setHours(nextDate.getHours() + this.form.frequency_value);
                    break;
                case 'daily':
                    nextDate.setDate(nextDate.getDate() + this.form.frequency_value);
                    if (this.form.specific_time) {
                        const [hours, minutes] = this.form.specific_time.split(':');
                        nextDate.setHours(parseInt(hours), parseInt(minutes), 0, 0);
                    }
                    break;
                case 'weekly':
                    nextDate.setDate(nextDate.getDate() + (7 * this.form.frequency_value));
                    if (this.form.specific_time) {
                        const [hours, minutes] = this.form.specific_time.split(':');
                        nextDate.setHours(parseInt(hours), parseInt(minutes), 0, 0);
                    }
                    break;
                case 'monthly':
                    nextDate.setMonth(nextDate.getMonth() + this.form.frequency_value);
                    if (this.form.specific_time) {
                        const [hours, minutes] = this.form.specific_time.split(':');
                        nextDate.setHours(parseInt(hours), parseInt(minutes), 0, 0);
                    }
                    break;
                case 'custom':
                    nextDate.setDate(nextDate.getDate() + this.form.frequency_value);
                    if (this.form.specific_time) {
                        const [hours, minutes] = this.form.specific_time.split(':');
                        nextDate.setHours(parseInt(hours), parseInt(minutes), 0, 0);
                    }
                    break;
            }
            
            return nextDate.toLocaleString();
        },
        
        // Submit form
        submitForm() {
            if (this.isSubmitting) return;
            
            this.isSubmitting = true;
            this.errors = {};
            
            // Handle monthly week day selection
            if (this.form.frequency_type === 'monthly' && this.monthType === 'week_day') {
                this.form.days_of_month = [`${this.weekOccurrence}-${this.weekDay}`];
            }
            
            // Simulate form submission
            setTimeout(() => {
                // In real implementation, this would submit to the server
                console.log('Form submitted:', this.form);
                
                // Redirect to schedules list
                window.location.href = '/admin/todos/schedules';
            }, 1000);
        },
        
        // Cancel form
        cancelForm() {
            if (confirm('Are you sure you want to cancel? Any unsaved changes will be lost.')) {
                window.location.href = '/admin/todos/schedules';
            }
        }
    };
}

function initializeNotifications() {
    // Add notification styles if not already present
    if (!document.getElementById('notification-styles')) {
        const style = document.createElement('style');
        style.id = 'notification-styles';
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
window.schedulesData = window.schedulesData || function() {
    return {
        filters: { frequency: 'all', status: 'all', search: '' },
        schedules: [],
        filteredSchedules: [],
        init() {},
        loadSchedulesData() {},
        applyFilters() {},
        clearFilters() {},
        isScheduleVisible() { return true; },
        toggleScheduleStatus() {},
        activateSchedule() {},
        deactivateSchedule() {},
        deleteSchedule() {},
        refreshSchedules() {},
        showNotification() {}
    };
};

window.initializeScheduleForm = initializeScheduleForm;
