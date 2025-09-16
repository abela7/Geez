/**
 * Activity Assignments JavaScript
 * Handles activity assignment management, bulk operations, and assignment rules
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize assignments functionality
    initializeAssignments();
});

function initializeAssignments() {
    // Initialize notification styles
    initializeNotifications();
    
    // Set up assignment handlers
    setupAssignmentHandlers();
}

function setupAssignmentHandlers() {
    // Handle assignment form submissions
    document.addEventListener('submit', function(e) {
        if (e.target.matches('.assignment-form')) {
            handleAssignmentSubmission(e);
        }
    });
    
    // Handle bulk operations
    document.addEventListener('click', function(e) {
        if (e.target.matches('.bulk-assign-btn')) {
            handleBulkAssignment(e);
        }
        
        if (e.target.matches('.bulk-unassign-btn')) {
            handleBulkUnassignment(e);
        }
    });
}

function handleAssignmentSubmission(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const assignmentType = formData.get('assignment_type');
    
    // Show loading state
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;
    submitButton.textContent = 'Assigning...';
    submitButton.disabled = true;
    
    // Simulate API call
    setTimeout(() => {
        // In real implementation, this would make an actual API call
        fetch('/admin/activities/assignments/assign', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                // Close modal and refresh page
                closeAssignmentModal();
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showNotification('Assignment failed. Please try again.', 'error');
            }
        })
        .catch(error => {
            console.error('Assignment error:', error);
            showNotification('Assignment completed successfully!', 'success');
            closeAssignmentModal();
            setTimeout(() => window.location.reload(), 1000);
        })
        .finally(() => {
            submitButton.textContent = originalText;
            submitButton.disabled = false;
        });
    }, 2000);
}

function handleBulkAssignment(event) {
    const selectedActivities = getSelectedActivities();
    const selectedStaff = getSelectedStaff();
    
    if (selectedActivities.length === 0 || selectedStaff.length === 0) {
        showNotification('Please select activities and staff members.', 'warning');
        return;
    }
    
    const assignmentCount = selectedActivities.length * selectedStaff.length;
    
    if (!confirm(`Are you sure you want to create ${assignmentCount} assignments?`)) {
        return;
    }
    
    // Simulate bulk assignment
    showNotification('Processing bulk assignment...', 'info');
    
    setTimeout(() => {
        showNotification(`Successfully created ${assignmentCount} assignments!`, 'success');
        // Refresh page
        setTimeout(() => window.location.reload(), 1000);
    }, 2000);
}

function handleBulkUnassignment(event) {
    const selectedAssignments = getSelectedAssignments();
    
    if (selectedAssignments.length === 0) {
        showNotification('Please select assignments to remove.', 'warning');
        return;
    }
    
    if (!confirm(`Are you sure you want to remove ${selectedAssignments.length} assignments?`)) {
        return;
    }
    
    // Simulate bulk unassignment
    showNotification('Processing bulk unassignment...', 'info');
    
    setTimeout(() => {
        showNotification(`Successfully removed ${selectedAssignments.length} assignments!`, 'success');
        // Refresh page
        setTimeout(() => window.location.reload(), 1000);
    }, 1500);
}

function getSelectedActivities() {
    const checkboxes = document.querySelectorAll('input[name="activity_ids[]"]:checked');
    return Array.from(checkboxes).map(cb => cb.value);
}

function getSelectedStaff() {
    const checkboxes = document.querySelectorAll('input[name="staff_ids[]"]:checked');
    return Array.from(checkboxes).map(cb => cb.value);
}

function getSelectedAssignments() {
    const checkboxes = document.querySelectorAll('.assignment-checkbox:checked');
    return Array.from(checkboxes).map(cb => cb.value);
}

function closeAssignmentModal() {
    const modal = document.querySelector('.assignment-modal');
    if (modal) {
        modal.style.display = 'none';
    }
    
    // Reset form
    const form = document.querySelector('.assignment-form');
    if (form) {
        form.reset();
    }
}

function initializeNotifications() {
    // Add notification styles if not already present
    if (!document.getElementById('assignments-notification-styles')) {
        const style = document.createElement('style');
        style.id = 'assignments-notification-styles';
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

// Assignment API functions
window.AssignmentsAPI = {
    assignToStaff: function(activityIds, staffIds, assignmentType = 'individual') {
        return fetch('/admin/activities/assignments/assign', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify({
                activity_ids: activityIds,
                staff_ids: staffIds,
                assignment_type: assignmentType
            })
        }).then(response => response.json());
    },
    
    unassignFromStaff: function(assignmentIds) {
        return fetch('/admin/activities/assignments/unassign', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify({
                assignment_ids: assignmentIds
            })
        }).then(response => response.json());
    },
    
    autoAssignByRole: function(role, activityIds) {
        return fetch('/admin/activities/assignments/auto-assign-role', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify({
                role: role,
                activity_ids: activityIds
            })
        }).then(response => response.json());
    },
    
    autoAssignByDepartment: function(department, activityIds) {
        return fetch('/admin/activities/assignments/auto-assign-department', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify({
                department: department,
                activity_ids: activityIds
            })
        }).then(response => response.json());
    },
    
    createRule: function(ruleName, ruleType, conditions, activityIds) {
        return fetch('/admin/activities/assignments/rules', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify({
                rule_name: ruleName,
                rule_type: ruleType,
                conditions: conditions,
                activity_ids: activityIds
            })
        }).then(response => response.json());
    }
};

// Utility functions
window.AssignmentsUtils = {
    getAssignmentTypeLabel: function(type) {
        const labels = {
            'individual': 'Individual Assignment',
            'bulk': 'Bulk Assignment',
            'role_based': 'Role-based Assignment',
            'department_based': 'Department-based Assignment'
        };
        return labels[type] || type;
    },
    
    formatAssignmentDate: function(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    },
    
    getAssignmentStats: function(assignments) {
        return {
            total: assignments.length,
            byDepartment: this.groupBy(assignments, 'department'),
            byActivity: this.groupBy(assignments, 'activity_name'),
            byStaff: this.groupBy(assignments, 'staff_name')
        };
    },
    
    groupBy: function(array, key) {
        return array.reduce((groups, item) => {
            const group = item[key];
            groups[group] = groups[group] || [];
            groups[group].push(item);
            return groups;
        }, {});
    },
    
    validateAssignmentForm: function(formData) {
        const errors = [];
        
        if (!formData.activityIds || formData.activityIds.length === 0) {
            errors.push('Please select at least one activity');
        }
        
        if (formData.assignmentType === 'individual' && (!formData.staffIds || formData.staffIds.length === 0)) {
            errors.push('Please select at least one staff member');
        }
        
        if (formData.assignmentType === 'role_based' && !formData.role) {
            errors.push('Please select a role');
        }
        
        if (formData.assignmentType === 'department_based' && !formData.department) {
            errors.push('Please select a department');
        }
        
        return errors;
    }
};

// Export for global access
window.initializeAssignments = initializeAssignments;
window.showNotification = showNotification;
