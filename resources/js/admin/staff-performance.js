/**
 * STAFF PERFORMANCE - SECTION-SPECIFIC JAVASCRIPT
 * Handles chart rendering, data filtering, and interactive elements
 * Uses Chart.js for performance visualization
 */

/* ==========================================================================
   1. INITIALIZATION & SETUP
   ========================================================================== */

document.addEventListener('DOMContentLoaded', function() {
    initializePerformanceChart();
    initializeChartToggles();
    initializeFilterDropdown();
    initializeExportButton();
    initializeQuickActions();
});

/* ==========================================================================
   2. PERFORMANCE CHART MANAGEMENT
   ========================================================================== */

let performanceChart = null;

/**
 * Initialize the main performance trends chart
 */
function initializePerformanceChart() {
    const canvas = document.getElementById('performanceChart');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    
    // Sample data - in real app, this would come from API
    const chartData = {
        '3m': {
            labels: ['Jan', 'Feb', 'Mar'],
            datasets: [{
                label: 'Overall Performance',
                data: [82, 85, 87.5],
                borderColor: 'var(--color-primary)',
                backgroundColor: 'rgba(205, 175, 86, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Team Average',
                data: [78, 80, 83],
                borderColor: 'var(--color-secondary)',
                backgroundColor: 'rgba(77, 64, 82, 0.1)',
                tension: 0.4,
                fill: false
            }]
        },
        '6m': {
            labels: ['Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan'],
            datasets: [{
                label: 'Overall Performance',
                data: [75, 78, 80, 82, 85, 87.5],
                borderColor: 'var(--color-primary)',
                backgroundColor: 'rgba(205, 175, 86, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Team Average',
                data: [72, 74, 76, 78, 80, 83],
                borderColor: 'var(--color-secondary)',
                backgroundColor: 'rgba(77, 64, 82, 0.1)',
                tension: 0.4,
                fill: false
            }]
        },
        '1y': {
            labels: ['Q1', 'Q2', 'Q3', 'Q4'],
            datasets: [{
                label: 'Overall Performance',
                data: [70, 75, 82, 87.5],
                borderColor: 'var(--color-primary)',
                backgroundColor: 'rgba(205, 175, 86, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Team Average',
                data: [68, 72, 78, 83],
                borderColor: 'var(--color-secondary)',
                backgroundColor: 'rgba(77, 64, 82, 0.1)',
                tension: 0.4,
                fill: false
            }]
        }
    };

    // Chart configuration
    const config = {
        type: 'line',
        data: chartData['3m'], // Default to 3 months
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        color: 'var(--color-text-primary)'
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'var(--color-bg-secondary)',
                    titleColor: 'var(--color-text-primary)',
                    bodyColor: 'var(--color-text-secondary)',
                    borderColor: 'var(--color-border)',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y + '%';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        color: 'var(--color-border)',
                        drawBorder: false
                    },
                    ticks: {
                        color: 'var(--color-text-secondary)',
                        font: {
                            size: 12
                        }
                    }
                },
                y: {
                    beginAtZero: false,
                    min: 60,
                    max: 100,
                    grid: {
                        color: 'var(--color-border)',
                        drawBorder: false
                    },
                    ticks: {
                        color: 'var(--color-text-secondary)',
                        font: {
                            size: 12
                        },
                        callback: function(value) {
                            return value + '%';
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
                    radius: 4,
                    hoverRadius: 6,
                    borderWidth: 2,
                    hoverBorderWidth: 3
                },
                line: {
                    borderWidth: 3
                }
            }
        }
    };

    // Create chart instance
    performanceChart = new Chart(ctx, config);
    
    // Store chart data for period switching
    performanceChart.chartData = chartData;
}

/**
 * Initialize chart period toggle buttons
 */
function initializeChartToggles() {
    const toggleButtons = document.querySelectorAll('.performance-chart-toggle');
    
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const period = this.dataset.period;
            
            // Update active state
            toggleButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Update chart data
            if (performanceChart && performanceChart.chartData[period]) {
                performanceChart.data = performanceChart.chartData[period];
                performanceChart.update('active');
            }
        });
    });
}

/* ==========================================================================
   3. FILTER & EXPORT FUNCTIONALITY
   ========================================================================== */

/**
 * Initialize the period filter dropdown
 */
function initializeFilterDropdown() {
    const filterSelect = document.querySelector('select[class*="bg-card"]');
    if (!filterSelect) return;
    
    filterSelect.addEventListener('change', function() {
        const selectedPeriod = this.value;
        console.log('Filter changed to:', selectedPeriod);
        
        // In a real app, this would trigger an API call to fetch new data
        // For now, we'll just log the change
        showNotification('Performance data filtered by ' + selectedPeriod, 'info');
    });
}

/**
 * Initialize export report button
 */
function initializeExportButton() {
    const exportButton = document.querySelector('button[class*="bg-primary-btn"]');
    if (!exportButton) return;
    
    exportButton.addEventListener('click', function() {
        // Simulate export process
        this.disabled = true;
        const originalText = this.innerHTML;
        
        // Show loading state
        this.innerHTML = `
            <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Exporting...
        `;
        
        // Simulate export delay
        setTimeout(() => {
            this.disabled = false;
            this.innerHTML = originalText;
            showNotification('Performance report exported successfully!', 'success');
        }, 2000);
    });
}

/* ==========================================================================
   4. QUICK ACTIONS FUNCTIONALITY
   ========================================================================== */

/**
 * Initialize quick action buttons
 */
function initializeQuickActions() {
    const actionButtons = document.querySelectorAll('.performance-action-btn, button[class*="bg-secondary-btn"]');
    
    actionButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const buttonText = this.textContent.trim();
            
            // Handle different actions
            switch(true) {
                case buttonText.includes('Schedule Review'):
                    handleScheduleReview();
                    break;
                case buttonText.includes('View Analytics'):
                    handleViewAnalytics();
                    break;
                case buttonText.includes('Team Comparison'):
                    handleTeamComparison();
                    break;
                case buttonText.includes('Performance Settings'):
                    handlePerformanceSettings();
                    break;
                default:
                    showNotification('Action: ' + buttonText, 'info');
            }
        });
    });
}

/**
 * Handle schedule review action
 */
function handleScheduleReview() {
    showNotification('Opening review scheduling modal...', 'info');
    // In a real app, this would open a modal or navigate to scheduling page
}

/**
 * Handle view analytics action
 */
function handleViewAnalytics() {
    showNotification('Loading detailed analytics...', 'info');
    // In a real app, this would navigate to detailed analytics page
}

/**
 * Handle team comparison action
 */
function handleTeamComparison() {
    showNotification('Loading team comparison view...', 'info');
    // In a real app, this would show team comparison charts
}

/**
 * Handle performance settings action
 */
function handlePerformanceSettings() {
    showNotification('Opening performance settings...', 'info');
    // In a real app, this would navigate to settings page
}

/* ==========================================================================
   5. UTILITY FUNCTIONS
   ========================================================================== */

/**
 * Show notification to user
 * @param {string} message - The message to display
 * @param {string} type - The type of notification (success, error, info, warning)
 */
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 20px;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        z-index: 1000;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        max-width: 300px;
        word-wrap: break-word;
    `;
    
    // Set background color based on type
    const colors = {
        success: '#10B981',
        error: '#EF4444',
        warning: '#F59E0B',
        info: '#3B82F6'
    };
    notification.style.backgroundColor = colors[type] || colors.info;
    
    // Add message
    notification.textContent = message;
    
    // Add to DOM
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Remove after delay
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

/**
 * Format percentage values for display
 * @param {number} value - The numeric value to format
 * @returns {string} Formatted percentage string
 */
function formatPercentage(value) {
    return Math.round(value * 10) / 10 + '%';
}

/**
 * Animate progress bars on page load
 */
function animateProgressBars() {
    const progressBars = document.querySelectorAll('.performance-bar-fill, .metric-bar-fill');
    
    progressBars.forEach(bar => {
        const targetWidth = bar.style.width;
        bar.style.width = '0%';
        
        setTimeout(() => {
            bar.style.width = targetWidth;
        }, 500);
    });
}

/* ==========================================================================
   6. RESPONSIVE BEHAVIOR
   ========================================================================== */

/**
 * Handle responsive chart resizing
 */
function handleChartResize() {
    if (performanceChart) {
        performanceChart.resize();
    }
}

// Listen for window resize events
window.addEventListener('resize', debounce(handleChartResize, 250));

/**
 * Debounce function to limit function calls
 * @param {Function} func - Function to debounce
 * @param {number} wait - Wait time in milliseconds
 * @returns {Function} Debounced function
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/* ==========================================================================
   7. ACCESSIBILITY ENHANCEMENTS
   ========================================================================== */

/**
 * Initialize keyboard navigation for chart toggles
 */
function initializeKeyboardNavigation() {
    const toggleButtons = document.querySelectorAll('.performance-chart-toggle');
    
    toggleButtons.forEach((button, index) => {
        button.addEventListener('keydown', function(e) {
            let nextIndex;
            
            switch(e.key) {
                case 'ArrowLeft':
                    e.preventDefault();
                    nextIndex = index > 0 ? index - 1 : toggleButtons.length - 1;
                    toggleButtons[nextIndex].focus();
                    break;
                case 'ArrowRight':
                    e.preventDefault();
                    nextIndex = index < toggleButtons.length - 1 ? index + 1 : 0;
                    toggleButtons[nextIndex].focus();
                    break;
                case 'Enter':
                case ' ':
                    e.preventDefault();
                    button.click();
                    break;
            }
        });
    });
}

// Initialize keyboard navigation when DOM is ready
document.addEventListener('DOMContentLoaded', initializeKeyboardNavigation);

/* ==========================================================================
   8. PERFORMANCE MONITORING
   ========================================================================== */

/**
 * Monitor page performance and log metrics
 */
function monitorPerformance() {
    if ('performance' in window) {
        window.addEventListener('load', () => {
            setTimeout(() => {
                const perfData = performance.getEntriesByType('navigation')[0];
                console.log('Staff Performance Page Load Time:', perfData.loadEventEnd - perfData.loadEventStart, 'ms');
            }, 0);
        });
    }
}

// Initialize performance monitoring
monitorPerformance();
