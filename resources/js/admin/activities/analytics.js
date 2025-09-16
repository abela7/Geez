/**
 * Activity Analytics JavaScript
 * Handles charts, data visualization, and interactive analytics features
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize analytics functionality
    initializeAnalytics();
});

function initializeAnalytics() {
    // Initialize charts
    initializeCharts();
    
    // Initialize notification styles
    initializeNotifications();
    
    // Set up chart update handlers
    setupChartHandlers();
}

function initializeCharts() {
    // Initialize Trends Chart
    initializeTrendsChart();
    
    // Initialize Department Chart
    initializeDepartmentChart();
    
    // Initialize Peak Hours Chart
    initializePeakHoursChart();
}

function initializeTrendsChart() {
    const ctx = document.getElementById('trendsChart');
    if (!ctx) return;
    
    const chartData = window.analyticsChartData?.trends?.last_30_days || [];
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.map(item => {
                const date = new Date(item.date);
                return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            }),
            datasets: [
                {
                    label: 'Activities',
                    data: chartData.map(item => item.activities),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y'
                },
                {
                    label: 'Efficiency %',
                    data: chartData.map(item => item.efficiency),
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: false,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Date'
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Activities'
                    },
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Efficiency %'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });
}

function initializeDepartmentChart() {
    const ctx = document.getElementById('departmentChart');
    if (!ctx) return;
    
    const departments = window.analyticsChartData?.departments || [];
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: departments.map(dept => dept.name),
            datasets: [{
                data: departments.map(dept => dept.activities_logged),
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(239, 68, 68, 0.8)'
                ],
                borderColor: [
                    'rgb(59, 130, 246)',
                    'rgb(16, 185, 129)',
                    'rgb(245, 158, 11)',
                    'rgb(239, 68, 68)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const dept = departments[context.dataIndex];
                            return `${context.label}: ${context.parsed} activities (${dept.avg_efficiency}% efficiency)`;
                        }
                    }
                }
            }
        }
    });
}

function initializePeakHoursChart() {
    const ctx = document.getElementById('peakHoursChart');
    if (!ctx) return;
    
    const timeAnalysis = window.analyticsChartData?.timeAnalysis?.hourly_distribution || {};
    const hours = Object.keys(timeAnalysis);
    const activities = Object.values(timeAnalysis);
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: hours,
            datasets: [{
                label: 'Activities per Hour',
                data: activities,
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 1,
                borderRadius: 4,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        title: function(context) {
                            return `${context[0].label}:00`;
                        },
                        label: function(context) {
                            return `${context.parsed.y} activities logged`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Hour of Day'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Number of Activities'
                    },
                    beginAtZero: true
                }
            }
        }
    });
}

function setupChartHandlers() {
    // Handle trends timeframe changes
    document.addEventListener('change', function(e) {
        if (e.target.matches('[x-model="trendsTimeframe"]')) {
            updateTrendsChart(e.target.value);
        }
    });
}

function updateTrendsChart(timeframe) {
    // In a real implementation, this would fetch new data and update the chart
    console.log('Updating trends chart for timeframe:', timeframe);
    
    // Simulate chart update
    const ctx = document.getElementById('trendsChart');
    if (ctx && ctx.chart) {
        // Update chart data based on timeframe
        // This is a simplified example
        ctx.chart.update();
    }
}

function initializeNotifications() {
    // Add notification styles if not already present
    if (!document.getElementById('analytics-notification-styles')) {
        const style = document.createElement('style');
        style.id = 'analytics-notification-styles';
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

// Analytics API functions
window.AnalyticsAPI = {
    exportData: function(type, format, dateFrom = null, dateTo = null) {
        return fetch('/admin/activities/analytics/export', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify({
                type: type,
                format: format,
                date_from: dateFrom,
                date_to: dateTo
            })
        }).then(response => response.json());
    },
    
    getStaffAnalytics: function(staffId, dateFrom = null, dateTo = null) {
        const params = new URLSearchParams();
        if (dateFrom) params.append('date_from', dateFrom);
        if (dateTo) params.append('date_to', dateTo);
        
        const url = `/admin/activities/analytics/staff/${staffId}${params.toString() ? '?' + params.toString() : ''}`;
        return fetch(url).then(response => response.json());
    },
    
    updateDateRange: function(dateFrom, dateTo) {
        return fetch('/admin/activities/analytics', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify({
                date_from: dateFrom,
                date_to: dateTo
            })
        }).then(response => response.json());
    }
};

// Utility functions
window.AnalyticsUtils = {
    formatTime: function(minutes) {
        const hours = Math.floor(minutes / 60);
        const mins = minutes % 60;
        return hours > 0 ? `${hours}h ${mins}m` : `${mins}m`;
    },
    
    formatEfficiency: function(efficiency) {
        return `${efficiency}%`;
    },
    
    getEfficiencyClass: function(efficiency) {
        if (efficiency >= 90) return 'excellent';
        if (efficiency >= 80) return 'good';
        return 'average';
    },
    
    getTrendClass: function(trend) {
        return `trend-${trend}`;
    },
    
    formatNumber: function(number) {
        return new Intl.NumberFormat().format(number);
    },
    
    calculateGrowth: function(current, previous) {
        if (previous === 0) return 0;
        return Math.round(((current - previous) / previous) * 100);
    },
    
    getGrowthClass: function(growth) {
        if (growth > 0) return 'positive';
        if (growth < 0) return 'negative';
        return 'neutral';
    }
};

// Chart color schemes
window.ChartColors = {
    primary: 'rgb(59, 130, 246)',
    success: 'rgb(16, 185, 129)',
    warning: 'rgb(245, 158, 11)',
    danger: 'rgb(239, 68, 68)',
    info: 'rgb(6, 182, 212)',
    purple: 'rgb(139, 92, 246)',
    pink: 'rgb(236, 72, 153)',
    
    withAlpha: function(color, alpha) {
        return color.replace('rgb', 'rgba').replace(')', `, ${alpha})`);
    }
};

// Export for global access
window.initializeAnalytics = initializeAnalytics;
window.updateTrendsChart = updateTrendsChart;
