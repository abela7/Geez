/**
 * Progress Tracking JavaScript
 * Handles charts, data visualization, and interactive features
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize progress tracking functionality
    initializeProgressTracking();
});

function initializeProgressTracking() {
    // Initialize charts
    initializeCharts();
    
    // Initialize Alpine.js data
    window.progressData = function() {
        return {
            // Modal states
            showReportModal: false,
            showExportModal: false,
            
            // Form states
            isGeneratingReport: false,
            isExporting: false,
            
            // Form data
            reportForm: {
                report_type: 'weekly',
                start_date: '',
                end_date: '',
                format: 'pdf'
            },
            
            exportForm: {
                data_type: 'overview',
                format: 'excel'
            },
            
            // Data filters
            selectedPeriod: 'week',
            
            // Staff performance data
            staffPerformance: [],
            
            // Initialize
            init() {
                this.loadStaffPerformance();
                this.setupDateDefaults();
            },
            
            // Setup default dates for custom reports
            setupDateDefaults() {
                const today = new Date();
                const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
                
                this.reportForm.start_date = firstDay.toISOString().split('T')[0];
                this.reportForm.end_date = today.toISOString().split('T')[0];
            },
            
            // Load staff performance data
            loadStaffPerformance() {
                // In a real implementation, this would fetch from the server
                // For now, we'll use the data from the Blade template
                this.staffPerformance = window.staffPerformanceData || [];
            },
            
            // Refresh all data
            refreshData() {
                this.showNotification('Refreshing progress data...', 'info');
                
                // Simulate API call
                setTimeout(() => {
                    this.loadStaffPerformance();
                    this.updateCharts();
                    this.showNotification('Progress data refreshed successfully!', 'success');
                }, 1000);
            },
            
            // Update charts based on selected period
            updateCharts() {
                if (window.trendsChart) {
                    updateTrendsChart(this.selectedPeriod);
                }
                if (window.categoryChart) {
                    updateCategoryChart();
                }
            },
            
            // Sort staff performance
            sortStaffBy(criteria) {
                this.staffPerformance.sort((a, b) => {
                    if (criteria === 'completion_rate') {
                        return b.completion_rate - a.completion_rate;
                    } else if (criteria === 'quality_score') {
                        return b.quality_score - a.quality_score;
                    }
                    return 0;
                });
                
                this.showNotification(`Staff sorted by ${criteria.replace('_', ' ')}`, 'info');
            },
            
            // Refresh activity data
            refreshActivity() {
                this.showNotification('Refreshing activity data...', 'info');
                
                // Simulate API call
                setTimeout(() => {
                    this.showNotification('Activity data refreshed!', 'success');
                }, 500);
            },
            
            // Report modal functions
            openReportModal() {
                this.showReportModal = true;
            },
            
            closeReportModal() {
                this.showReportModal = false;
                this.isGeneratingReport = false;
            },
            
            // Export modal functions
            openExportModal() {
                this.showExportModal = true;
            },
            
            closeExportModal() {
                this.showExportModal = false;
                this.isExporting = false;
            },
            
            // Generate report
            generateReport() {
                if (this.isGeneratingReport) return;
                
                this.isGeneratingReport = true;
                
                // Simulate report generation
                setTimeout(() => {
                    this.isGeneratingReport = false;
                    this.closeReportModal();
                    this.showNotification('Report generated successfully! Download will start shortly.', 'success');
                    
                    // Simulate file download
                    setTimeout(() => {
                        const filename = `progress_report_${this.reportForm.report_type}_${new Date().toISOString().split('T')[0]}.${this.reportForm.format}`;
                        this.simulateDownload(filename);
                    }, 1000);
                }, 2000);
            },
            
            // Export data
            exportData() {
                if (this.isExporting) return;
                
                this.isExporting = true;
                
                // Simulate data export
                setTimeout(() => {
                    this.isExporting = false;
                    this.closeExportModal();
                    this.showNotification('Data exported successfully! Download will start shortly.', 'success');
                    
                    // Simulate file download
                    setTimeout(() => {
                        const filename = `progress_${this.exportForm.data_type}_${new Date().toISOString().split('T')[0]}.${this.exportForm.format}`;
                        this.simulateDownload(filename);
                    }, 1000);
                }, 1500);
            },
            
            // Simulate file download
            simulateDownload(filename) {
                // Create a temporary link element
                const link = document.createElement('a');
                link.href = '#'; // In real implementation, this would be the actual file URL
                link.download = filename;
                link.style.display = 'none';
                
                document.body.appendChild(link);
                // link.click(); // Commented out to avoid actual download in demo
                document.body.removeChild(link);
                
                this.showNotification(`Download started: ${filename}`, 'info');
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

function initializeCharts() {
    // Wait for Chart.js to be available
    if (typeof Chart === 'undefined') {
        setTimeout(initializeCharts, 100);
        return;
    }
    
    // Initialize trends chart
    initializeTrendsChart();
    
    // Initialize category chart
    initializeCategoryChart();
}

function initializeTrendsChart() {
    const ctx = document.getElementById('trendsChart');
    if (!ctx) return;
    
    const chartData = window.progressChartData?.trends?.last_30_days || [];
    
    const data = {
        labels: chartData.map(item => {
            const date = new Date(item.date);
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        }),
        datasets: [
            {
                label: 'Completed',
                data: chartData.map(item => item.completed),
                borderColor: '#10B981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                fill: true,
                tension: 0.4
            },
            {
                label: 'Created',
                data: chartData.map(item => item.created),
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                fill: true,
                tension: 0.4
            }
        ]
    };
    
    const options = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false // We have custom legend
            },
            tooltip: {
                mode: 'index',
                intersect: false,
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: '#fff',
                bodyColor: '#fff',
                borderColor: 'rgba(255, 255, 255, 0.1)',
                borderWidth: 1,
                cornerRadius: 8,
                displayColors: true,
                callbacks: {
                    afterBody: function(context) {
                        const dataIndex = context[0].dataIndex;
                        const rate = chartData[dataIndex]?.rate || 0;
                        return `Completion Rate: ${rate}%`;
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
                    color: '#6B7280'
                }
            },
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(107, 114, 128, 0.1)'
                },
                ticks: {
                    color: '#6B7280'
                }
            }
        },
        interaction: {
            mode: 'nearest',
            axis: 'x',
            intersect: false
        }
    };
    
    window.trendsChart = new Chart(ctx, {
        type: 'line',
        data: data,
        options: options
    });
}

function initializeCategoryChart() {
    const ctx = document.getElementById('categoryChart');
    if (!ctx) return;
    
    const chartData = window.progressChartData?.categories || [];
    
    const data = {
        labels: chartData.map(item => item.category),
        datasets: [{
            data: chartData.map(item => item.completed),
            backgroundColor: chartData.map(item => item.color),
            borderWidth: 0,
            hoverOffset: 4
        }]
    };
    
    const options = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true,
                    color: '#374151',
                    font: {
                        size: 12
                    }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: '#fff',
                bodyColor: '#fff',
                borderColor: 'rgba(255, 255, 255, 0.1)',
                borderWidth: 1,
                cornerRadius: 8,
                callbacks: {
                    label: function(context) {
                        const item = chartData[context.dataIndex];
                        return [
                            `${item.category}: ${item.completed}`,
                            `Total: ${item.total}`,
                            `Rate: ${item.completion_rate}%`,
                            `Avg Time: ${item.average_time}h`
                        ];
                    }
                }
            }
        }
    };
    
    window.categoryChart = new Chart(ctx, {
        type: 'doughnut',
        data: data,
        options: options
    });
}

function updateTrendsChart(period) {
    if (!window.trendsChart) return;
    
    // In a real implementation, this would fetch new data based on the period
    // For now, we'll just show a notification
    console.log(`Updating trends chart for period: ${period}`);
}

function updateCategoryChart() {
    if (!window.categoryChart) return;
    
    // In a real implementation, this would fetch new data
    // For now, we'll just refresh the chart
    window.categoryChart.update();
}

function initializeNotifications() {
    // Add notification styles if not already present
    if (!document.getElementById('progress-notification-styles')) {
        const style = document.createElement('style');
        style.id = 'progress-notification-styles';
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
window.progressData = window.progressData || function() {
    return {
        showReportModal: false,
        showExportModal: false,
        isGeneratingReport: false,
        isExporting: false,
        reportForm: { report_type: 'weekly', start_date: '', end_date: '', format: 'pdf' },
        exportForm: { data_type: 'overview', format: 'excel' },
        selectedPeriod: 'week',
        staffPerformance: [],
        init() {},
        setupDateDefaults() {},
        loadStaffPerformance() {},
        refreshData() {},
        updateCharts() {},
        sortStaffBy() {},
        refreshActivity() {},
        openReportModal() {},
        closeReportModal() {},
        openExportModal() {},
        closeExportModal() {},
        generateReport() {},
        exportData() {},
        simulateDownload() {},
        showNotification() {}
    };
};
