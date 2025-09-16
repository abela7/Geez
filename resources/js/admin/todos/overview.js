/**
 * To-Do Overview Page JavaScript
 * Handles dashboard interactions, charts, and to-do management
 */

// Global variables
let completionChart = null;

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    initializePage();
    setupEventListeners();
    initializeCharts();
});

/**
 * Initialize the page
 */
function initializePage() {
    // Update real-time data
    updateRealTimeData();
    
    // Set up periodic refresh
    setInterval(updateRealTimeData, 30000); // Refresh every 30 seconds
}

/**
 * Setup event listeners
 */
function setupEventListeners() {
    // Modal close on outside click
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
            closeAllModals();
        }
    });

    // Modal close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllModals();
        }
    });
}

/**
 * Initialize charts
 */
function initializeCharts() {
    initializeCompletionTrendsChart();
}

/**
 * Initialize completion trends chart
 */
function initializeCompletionTrendsChart() {
    const ctx = document.getElementById('completionTrendsChart');
    if (!ctx || !window.completionTrends) return;

    const data = window.completionTrends;
    
    completionChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(item => {
                const date = new Date(item.date);
                return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            }),
            datasets: [
                {
                    label: 'Completion Rate (%)',
                    data: data.map(item => item.rate),
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgb(34, 197, 94)',
                    pointBorderColor: 'white',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                },
                {
                    label: 'Total To-Dos',
                    data: data.map(item => item.total),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.4,
                    pointBackgroundColor: 'rgb(59, 130, 246)',
                    pointBorderColor: 'white',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 12,
                            weight: '600'
                        }
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                    cornerRadius: 8,
                    padding: 12,
                    callbacks: {
                        label: function(context) {
                            if (context.datasetIndex === 0) {
                                return `Completion Rate: ${context.parsed.y.toFixed(1)}%`;
                            } else {
                                return `Total To-Dos: ${context.parsed.y}`;
                            }
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    min: 0,
                    max: 100,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        },
                        font: {
                            size: 11
                        }
                    },
                    title: {
                        display: true,
                        text: 'Completion Rate (%)',
                        font: {
                            size: 12,
                            weight: '600'
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false,
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    },
                    title: {
                        display: true,
                        text: 'Total To-Dos',
                        font: {
                            size: 12,
                            weight: '600'
                        }
                    }
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            },
            elements: {
                point: {
                    hoverBorderWidth: 3
                }
            }
        }
    });
}

/**
 * Update trend period
 */
function updateTrendPeriod(period) {
    showNotification(`Loading ${period} days trend data...`, 'info');
    
    // Simulate API call
    setTimeout(() => {
        showNotification('Trend data updated successfully!', 'success');
        // In real implementation, this would fetch new data and update the chart
    }, 1000);
}

/**
 * Refresh dashboard data
 */
function refreshDashboard() {
    showNotification('Refreshing dashboard data...', 'info');
    
    // Simulate refresh
    setTimeout(() => {
        updateRealTimeData();
        showNotification('Dashboard refreshed successfully!', 'success');
    }, 1500);
}

/**
 * Update real-time data
 */
function updateRealTimeData() {
    // Update completion rates with animation
    updateCompletionRates();
    
    // Update overdue counts
    updateOverdueCounts();
    
    // Update staff status indicators
    updateStaffStatus();
}

/**
 * Update completion rates
 */
function updateCompletionRates() {
    const rateCircles = document.querySelectorAll('.rate-circle');
    rateCircles.forEach(circle => {
        const progress = circle.style.getPropertyValue('--progress');
        if (progress) {
            // Add subtle animation to show data is live
            circle.style.transform = 'scale(1.05)';
            setTimeout(() => {
                circle.style.transform = 'scale(1)';
            }, 200);
        }
    });
}

/**
 * Update overdue counts
 */
function updateOverdueCounts() {
    const overdueElements = document.querySelectorAll('.overdue-count');
    overdueElements.forEach(element => {
        // Add pulse animation for overdue items
        if (parseInt(element.textContent) > 0) {
            element.style.animation = 'pulse 2s infinite';
        }
    });
}

/**
 * Update staff status
 */
function updateStaffStatus() {
    // Update last activity timestamps
    const activityElements = document.querySelectorAll('[data-last-activity]');
    activityElements.forEach(element => {
        // In real implementation, this would update with actual timestamps
    });
}

/**
 * Open quick add modal
 */
function openQuickAddModal() {
    const modal = document.getElementById('quickAddModal');
    if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
        
        // Focus on first input
        const firstInput = modal.querySelector('input[type="text"]');
        if (firstInput) {
            setTimeout(() => firstInput.focus(), 100);
        }
    }
}

/**
 * Close quick add modal
 */
function closeQuickAddModal() {
    const modal = document.getElementById('quickAddModal');
    if (modal) {
        modal.classList.remove('show');
        document.body.style.overflow = '';
        resetForm('quickAddForm');
    }
}

/**
 * Quick add to-do
 */
function quickAddTodo(event) {
    event.preventDefault();
    
    const form = event.target;
    if (!validateForm(form)) return;
    
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    
    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Adding...';
    submitBtn.disabled = true;
    
    // Simulate API call
    setTimeout(() => {
        showNotification('To-do added successfully!', 'success');
        closeQuickAddModal();
        
        // Reset button
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
        
        // Refresh data
        refreshDashboard();
    }, 1000);
}

/**
 * Start to-do
 */
function startTodo(todoId) {
    showNotification('Starting to-do...', 'info');
    
    // Simulate API call
    setTimeout(() => {
        showNotification('To-do started successfully!', 'success');
        updateTodoStatus(todoId, 'in_progress');
    }, 500);
}

/**
 * Complete to-do
 */
function completeTodo(todoId) {
    showNotification('Completing to-do...', 'info');
    
    // Simulate API call
    setTimeout(() => {
        showNotification('To-do completed successfully!', 'success');
        updateTodoStatus(todoId, 'completed');
        updateRealTimeData();
    }, 500);
}

/**
 * Edit to-do
 */
function editTodo(todoId) {
    showNotification('Opening to-do editor...', 'info');
    
    // In real implementation, this would open an edit modal
    setTimeout(() => {
        showNotification('Edit functionality coming soon!', 'warning');
    }, 500);
}

/**
 * Escalate to-do
 */
function escalateTodo(todoId) {
    showNotification('Escalating to-do...', 'info');
    
    // Simulate API call
    setTimeout(() => {
        showNotification('To-do escalated to manager!', 'success');
        // Remove from overdue list or update priority
    }, 1000);
}

/**
 * Update to-do status in UI
 */
function updateTodoStatus(todoId, newStatus) {
    const todoElement = document.querySelector(`[data-todo-id="${todoId}"]`);
    if (todoElement) {
        // Update status class
        todoElement.className = todoElement.className.replace(/status-\w+/, `status-${newStatus}`);
        
        // Update action buttons
        const actionsContainer = todoElement.querySelector('.todo-actions');
        if (actionsContainer && newStatus === 'completed') {
            actionsContainer.innerHTML = `
                <div class="completion-badge">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Completed
                </div>
            `;
        }
    }
}

/**
 * View all to-dos
 */
function viewAllTodos() {
    showNotification('Redirecting to staff to-do lists...', 'info');
    
    // In real implementation, this would navigate to the staff lists page
    setTimeout(() => {
        window.location.href = '/admin/todos/staff-lists';
    }, 500);
}

/**
 * View detailed performance
 */
function viewDetailedPerformance() {
    showNotification('Redirecting to progress tracking...', 'info');
    
    // In real implementation, this would navigate to the progress page
    setTimeout(() => {
        window.location.href = '/admin/todos/progress';
    }, 500);
}

/**
 * View schedule
 */
function viewSchedule() {
    showNotification('Redirecting to schedule management...', 'info');
    
    // In real implementation, this would navigate to the schedules page
    setTimeout(() => {
        window.location.href = '/admin/todos/schedules';
    }, 500);
}

/**
 * Edit recurring task
 */
function editRecurringTask(taskId) {
    showNotification('Redirecting to template editor...', 'info');
    
    // In real implementation, this would navigate to the templates page
    setTimeout(() => {
        window.location.href = `/admin/todos/templates/${taskId}/edit`;
    }, 500);
}

/**
 * Close all modals
 */
function closeAllModals() {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.classList.remove('show');
    });
    document.body.style.overflow = '';
}

/**
 * Validate form
 */
function validateForm(form) {
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            showFieldError(field, 'This field is required');
            isValid = false;
        } else {
            clearFieldError(field);
        }
    });
    
    return isValid;
}

/**
 * Show field error
 */
function showFieldError(field, message) {
    clearFieldError(field);
    
    field.style.borderColor = '#DC2626';
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.textContent = message;
    errorDiv.style.cssText = `
        color: #DC2626;
        font-size: 12px;
        margin-top: 4px;
    `;
    
    field.parentNode.appendChild(errorDiv);
}

/**
 * Clear field error
 */
function clearFieldError(field) {
    field.style.borderColor = '';
    
    const errorDiv = field.parentNode.querySelector('.field-error');
    if (errorDiv) {
        errorDiv.remove();
    }
}

/**
 * Reset form
 */
function resetForm(formId) {
    const form = document.getElementById(formId);
    if (form) {
        form.reset();
        
        // Clear all field errors
        const errorDivs = form.querySelectorAll('.field-error');
        errorDivs.forEach(div => div.remove());
        
        // Reset field styles
        const fields = form.querySelectorAll('input, select, textarea');
        fields.forEach(field => {
            field.style.borderColor = '';
        });
    }
}

/**
 * Show notification
 */
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 20px;
        border-radius: 6px;
        color: white;
        font-weight: 500;
        z-index: 10000;
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease;
        max-width: 300px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    `;
    
    // Set background color based on type
    const colors = {
        success: '#059669',
        error: '#DC2626',
        warning: '#D97706',
        info: '#2563EB'
    };
    
    notification.style.backgroundColor = colors[type] || colors.info;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateX(0)';
    }, 10);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 4000);
}

// Add CSS for pulse animation
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.7; }
        100% { opacity: 1; }
    }
    
    .completion-badge {
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 4px 8px;
        background: var(--color-success);
        color: white;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .completion-badge svg {
        width: 14px;
        height: 14px;
    }
`;
document.head.appendChild(style);
