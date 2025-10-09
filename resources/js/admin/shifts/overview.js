/**
 * Shift Overview JavaScript
 * Handles shift overview functionality, calendar interactions, and modal management
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize shift overview functionality
    initializeShiftOverview();
});

function initializeShiftOverview() {
    // Initialize notification styles
    initializeNotifications();
    
    // Set up shift overview handlers
    setupShiftOverviewHandlers();
    
    // Initialize calendar interactions
    initializeCalendarInteractions();
}

function setupShiftOverviewHandlers() {
    // Handle coverage gap assignment clicks
    document.addEventListener('click', function(e) {
        if (e.target.matches('.assign-gap-btn')) {
            const gapData = JSON.parse(e.target.dataset.gap);
            handleGapAssignment(gapData);
        }
    });
    
    // Handle export schedule clicks
    document.addEventListener('click', function(e) {
        if (e.target.matches('.export-schedule-btn')) {
            handleScheduleExport();
        }
    });
}

function initializeCalendarInteractions() {
    // Add hover effects to shift blocks
    const shiftBlocks = document.querySelectorAll('.shift-block');
    shiftBlocks.forEach(block => {
        block.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        block.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Add keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllModals();
        }
    });
}

function closeAllModals() {
    // Close other modals if they exist (Alpine handles its own)
}

function handleGapAssignment(gapData) {
    const params = new URLSearchParams({
        gap: gapData.shift_name,
        date: gapData.date,
        department: gapData.department
    });
    
    window.location.href = `/admin/shifts/assignments?${params.toString()}`;
}

function handleScheduleExport() {
    // Show export options modal or directly trigger export
    const exportOptions = {
        format: 'pdf', // or 'excel', 'csv'
        week: getCurrentWeek(),
        includeStaff: true,
        includeCoverage: true
    };
    
    exportSchedule(exportOptions);
}

function exportSchedule(options) {
    // Simulate export process
    showNotification('Preparing schedule export...', 'info');
    
    setTimeout(() => {
        // In real implementation, this would make an API call to generate the export
        showNotification('Schedule exported successfully!', 'success');
        
        // Simulate file download
        const link = document.createElement('a');
        link.href = '#'; // Would be actual file URL
        link.download = `schedule-${options.week}.${options.format}`;
        // link.click(); // Uncomment for actual download
    }, 2000);
}

function getCurrentWeek() {
    // Get current week from URL or default to current week
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('week') || new Date().toISOString().split('T')[0];
}

function initializeNotifications() {
    // Add notification styles if not already present
    if (!document.getElementById('shift-overview-notification-styles')) {
        const style = document.createElement('style');
        style.id = 'shift-overview-notification-styles';
        style.textContent = `
            .notification {
                position: fixed;
                top: 1rem;
                right: 1rem;
                z-index: 10001;
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

function showNotification(message, type = 'info') {
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

// Shift Overview API functions
window.ShiftOverviewAPI = {
    getWeeklySchedule: function(weekStart) {
        return fetch(`/admin/shifts/overview?week=${weekStart}`)
            .then(response => response.json());
    },
    
    getShiftDetails: function(shiftId) {
        return fetch(`/admin/shifts/manage/${shiftId}`)
            .then(response => response.json());
    },
    
    exportSchedule: function(options) {
        return fetch('/admin/shifts/overview/export', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify(options)
        }).then(response => response.json());
    },
    
    assignStaffToGap: function(gapId, staffIds) {
        return fetch('/admin/shifts/assignments/fill-gap', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify({
                gap_id: gapId,
                staff_ids: staffIds
            })
        }).then(response => response.json());
    }
};

// Utility functions
window.ShiftOverviewUtils = {
    formatTime: function(time24) {
        const [hours, minutes] = time24.split(':');
        const hour = parseInt(hours);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const hour12 = hour % 12 || 12;
        return `${hour12}:${minutes} ${ampm}`;
    },
    
    formatDuration: function(startTime, endTime) {
        const start = new Date(`2000-01-01 ${startTime}`);
        const end = new Date(`2000-01-01 ${endTime}`);
        const diff = end - start;
        const hours = Math.floor(diff / (1000 * 60 * 60));
        return `${hours}h`;
    },
    
    getStatusColor: function(status) {
        const colors = {
            'fully_covered': 'var(--color-success)',
            'partially_covered': 'var(--color-warning)',
            'not_covered': 'var(--color-danger)',
            'confirmed': 'var(--color-success)',
            'pending': 'var(--color-warning)',
            'in_progress': 'var(--color-info)'
        };
        return colors[status] || 'var(--color-text-muted)';
    },
    
    calculateCoveragePercentage: function(assigned, required) {
        return Math.round((assigned / required) * 100);
    },
    
    isShiftCurrent: function(startTime, endTime) {
        const now = new Date();
        const currentTime = now.getHours() * 60 + now.getMinutes();
        const [startHour, startMin] = startTime.split(':').map(Number);
        const [endHour, endMin] = endTime.split(':').map(Number);
        const shiftStart = startHour * 60 + startMin;
        const shiftEnd = endHour * 60 + endMin;
        
        return currentTime >= shiftStart && currentTime <= shiftEnd;
    }
};

// Export for global access
window.initializeShiftOverview = initializeShiftOverview;
window.showNotification = showNotification;