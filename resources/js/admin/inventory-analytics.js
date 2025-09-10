/**
 * Inventory Analytics Page JavaScript
 * Handles chart rendering, filters, and data visualization
 */

// Alpine.js component for analytics page
function analyticsPage() {
    return {
        // State
        loading: false,
        isEmpty: false,
        showFilters: false,
        
        // Filter state
        selectedDateRange: 'this_month',
        selectedCategory: 'all',
        selectedSupplier: 'all',
        usagePeriod: 'weekly',
        
        // Data
        totalInventoryValue: 45280,
        charts: {
            usage: null,
            category: null,
            waste: null,
            supplier: null
        },
        
        // Mock data
        mockData: {
            usageTrends: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [{
                    label: 'Tomatoes',
                    data: [65, 59, 80, 81],
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4
                }, {
                    label: 'Rice',
                    data: [28, 48, 40, 45],
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4
                }, {
                    label: 'Chicken',
                    data: [35, 25, 30, 35],
                    borderColor: '#F59E0B',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    tension: 0.4
                }]
            },
            categoryBreakdown: {
                labels: ['Vegetables', 'Meat', 'Dairy', 'Grains', 'Beverages', 'Other'],
                datasets: [{
                    data: [30, 25, 15, 12, 10, 8],
                    backgroundColor: [
                        '#10B981', '#3B82F6', '#F59E0B', 
                        '#8B5CF6', '#EF4444', '#6B7280'
                    ],
                    borderWidth: 0
                }]
            },
            wasteVsActual: {
                labels: ['Vegetables', 'Meat', 'Dairy', 'Grains', 'Beverages'],
                datasets: [{
                    label: 'Expected Usage',
                    data: [100, 85, 60, 45, 30],
                    backgroundColor: '#3B82F6',
                    borderRadius: 4
                }, {
                    label: 'Actual Usage',
                    data: [92, 80, 58, 43, 28],
                    backgroundColor: '#10B981',
                    borderRadius: 4
                }, {
                    label: 'Waste',
                    data: [8, 5, 2, 2, 2],
                    backgroundColor: '#EF4444',
                    borderRadius: 4
                }]
            },
            supplierPerformance: {
                labels: ['Fresh Farms', 'Quality Meats', 'Dairy Direct', 'Spice World', 'Ocean Catch'],
                datasets: [{
                    label: 'On-Time Delivery %',
                    data: [95, 88, 75, 92, 85],
                    backgroundColor: [
                        '#10B981', '#3B82F6', '#F59E0B', 
                        '#10B981', '#3B82F6'
                    ],
                    borderRadius: 4
                }]
            }
        },

        // Initialize component
        init() {
            console.log('Analytics page initialized');
            this.$nextTick(() => {
                this.initializeCharts();
            });
        },

        // Initialize all charts
        initializeCharts() {
            // Check if Chart.js is available
            if (typeof Chart === 'undefined') {
                console.warn('Chart.js not loaded, using placeholders');
                this.createChartPlaceholders();
                return;
            }

            // Set Chart.js defaults
            Chart.defaults.font.family = 'Inter, system-ui, sans-serif';
            Chart.defaults.color = getComputedStyle(document.documentElement)
                .getPropertyValue('--color-text-secondary').trim();

            this.initUsageTrendsChart();
            this.initCategoryBreakdownChart();
            this.initWasteVsActualChart();
            this.initSupplierPerformanceChart();
        },

        // Create chart placeholders when Chart.js isn't available
        createChartPlaceholders() {
            const chartContainers = document.querySelectorAll('.chart-container');
            chartContainers.forEach(container => {
                const canvas = container.querySelector('canvas');
                if (canvas) {
                    const placeholder = document.createElement('div');
                    placeholder.className = 'chart-placeholder';
                    placeholder.textContent = 'Chart will render here with Chart.js library';
                    container.replaceChild(placeholder, canvas);
                }
            });
        },

        // Initialize Usage Trends Chart (Line Chart)
        initUsageTrendsChart() {
            const ctx = document.getElementById('usageTrendsChart');
            if (!ctx) return;

            this.charts.usage = new Chart(ctx, {
                type: 'line',
                data: this.mockData.usageTrends,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: '#3B82F6',
                            borderWidth: 1
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            display: true,
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    }
                }
            });
        },

        // Initialize Category Breakdown Chart (Doughnut Chart)
        initCategoryBreakdownChart() {
            const ctx = document.getElementById('categoryBreakdownChart');
            if (!ctx) return;

            this.charts.category = new Chart(ctx, {
                type: 'doughnut',
                data: this.mockData.categoryBreakdown,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                generateLabels: function(chart) {
                                    const data = chart.data;
                                    if (data.labels.length && data.datasets.length) {
                                        return data.labels.map((label, i) => {
                                            const dataset = data.datasets[0];
                                            const value = dataset.data[i];
                                            const total = dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = ((value / total) * 100).toFixed(1);
                                            
                                            return {
                                                text: `${label} (${percentage}%)`,
                                                fillStyle: dataset.backgroundColor[i],
                                                strokeStyle: dataset.backgroundColor[i],
                                                lineWidth: 0,
                                                pointStyle: 'circle',
                                                hidden: false,
                                                index: i
                                            };
                                        });
                                    }
                                    return [];
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${label}: ${percentage}% ($${(value * 1000).toLocaleString()})`;
                                }
                            }
                        }
                    },
                    cutout: '60%'
                }
            });
        },

        // Initialize Waste vs Actual Chart (Bar Chart)
        initWasteVsActualChart() {
            const ctx = document.getElementById('wasteVsActualChart');
            if (!ctx) return;

            this.charts.waste = new Chart(ctx, {
                type: 'bar',
                data: this.mockData.wasteVsActual,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    const label = context.dataset.label || '';
                                    const value = context.parsed.y;
                                    return `${label}: ${value} kg`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            display: true,
                            beginAtZero: true,
                            stacked: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return value + ' kg';
                                }
                            }
                        }
                    }
                }
            });
        },

        // Initialize Supplier Performance Chart (Horizontal Bar Chart)
        initSupplierPerformanceChart() {
            const ctx = document.getElementById('supplierPerformanceChart');
            if (!ctx) return;

            this.charts.supplier = new Chart(ctx, {
                type: 'bar',
                data: this.mockData.supplierPerformance,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.parsed.x;
                                    return `On-Time Delivery: ${value}%`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                            beginAtZero: true,
                            max: 100,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        },
                        y: {
                            display: true,
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        },

        // Update usage chart based on period selection
        updateUsageChart() {
            if (!this.charts.usage) return;

            let newLabels, newData;
            
            switch (this.usagePeriod) {
                case 'daily':
                    newLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                    newData = [
                        [12, 15, 18, 14, 16, 10, 8],
                        [8, 10, 12, 9, 11, 6, 5],
                        [6, 8, 9, 7, 8, 5, 4]
                    ];
                    break;
                case 'monthly':
                    newLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
                    newData = [
                        [280, 250, 320, 290, 310, 275],
                        [180, 160, 200, 185, 195, 170],
                        [140, 125, 155, 145, 150, 135]
                    ];
                    break;
                default: // weekly
                    newLabels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
                    newData = [
                        [65, 59, 80, 81],
                        [28, 48, 40, 45],
                        [35, 25, 30, 35]
                    ];
            }

            this.charts.usage.data.labels = newLabels;
            this.charts.usage.data.datasets.forEach((dataset, index) => {
                dataset.data = newData[index] || [];
            });
            this.charts.usage.update();
        },

        // Update analytics data based on filters
        updateAnalytics() {
            this.loading = true;
            
            // Simulate API call delay
            setTimeout(() => {
                // Update KPI values based on filters
                this.updateKPIValues();
                
                // Update charts with filtered data
                this.updateChartsWithFilters();
                
                this.loading = false;
            }, 500);
        },

        // Update KPI values based on current filters
        updateKPIValues() {
            // Simulate different values based on filters
            const baseValue = 45280;
            const multiplier = this.getFilterMultiplier();
            
            this.totalInventoryValue = Math.round(baseValue * multiplier);
        },

        // Get multiplier based on current filters
        getFilterMultiplier() {
            let multiplier = 1;
            
            // Date range affects values
            switch (this.selectedDateRange) {
                case 'today':
                    multiplier *= 0.1;
                    break;
                case 'this_week':
                    multiplier *= 0.3;
                    break;
                case 'this_month':
                    multiplier *= 1;
                    break;
            }
            
            // Category affects values
            if (this.selectedCategory !== 'all') {
                multiplier *= 0.4;
            }
            
            // Supplier affects values
            if (this.selectedSupplier !== 'all') {
                multiplier *= 0.6;
            }
            
            return multiplier;
        },

        // Update charts with filtered data
        updateChartsWithFilters() {
            // This would normally fetch new data from the server
            // For now, we'll just simulate some changes
            
            if (this.charts.usage) {
                // Slightly modify the data to show filter effects
                this.charts.usage.data.datasets.forEach(dataset => {
                    dataset.data = dataset.data.map(value => 
                        Math.max(0, Math.round(value * (0.8 + Math.random() * 0.4)))
                    );
                });
                this.charts.usage.update();
            }
        },

        // Apply filters
        applyFilters() {
            this.updateAnalytics();
            this.showFilters = false;
        },

        // Clear all filters
        clearFilters() {
            this.selectedDateRange = 'this_month';
            this.selectedCategory = 'all';
            this.selectedSupplier = 'all';
            this.updateAnalytics();
        },

        // Export analytics data
        exportData() {
            // Simulate data export
            const data = {
                dateRange: this.selectedDateRange,
                category: this.selectedCategory,
                supplier: this.selectedSupplier,
                totalValue: this.totalInventoryValue,
                exportedAt: new Date().toISOString()
            };
            
            // Create and download a JSON file
            const blob = new Blob([JSON.stringify(data, null, 2)], {
                type: 'application/json'
            });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `inventory-analytics-${new Date().toISOString().split('T')[0]}.json`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            
            this.showSuccess('Analytics data exported successfully');
        },

        // Refresh data
        refreshData() {
            this.loading = true;
            this.isEmpty = false;
            
            setTimeout(() => {
                this.updateAnalytics();
            }, 1000);
        },

        // Format currency
        formatCurrency(value) {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(value);
        },

        // Show success message
        showSuccess(message) {
            // You can implement a toast notification system here
            console.log('Success:', message);
            // Temporary implementation
            alert(message);
        },

        // Show error message
        showError(message) {
            // You can implement a toast notification system here
            console.error('Error:', message);
            // Temporary implementation
            alert(message);
        }
    };
}

// Chart.js configuration and utilities
document.addEventListener('DOMContentLoaded', function() {
    console.log('Analytics page JavaScript loaded');
    
    // Load Chart.js if not already loaded
    if (typeof Chart === 'undefined') {
        console.log('Loading Chart.js...');
        loadChartJS();
    }
    
    // Add responsive chart resize handler
    window.addEventListener('resize', debounce(() => {
        // Charts will auto-resize with Chart.js responsive option
        console.log('Window resized, charts will auto-adjust');
    }, 250));
});

// Load Chart.js dynamically
function loadChartJS() {
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js';
    script.onload = function() {
        console.log('Chart.js loaded successfully');
        // Trigger chart initialization if Alpine component is ready
        const event = new CustomEvent('chartjs-loaded');
        document.dispatchEvent(event);
    };
    script.onerror = function() {
        console.error('Failed to load Chart.js');
    };
    document.head.appendChild(script);
}

// Utility function for debouncing
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

// Export functions for global access if needed
window.analyticsUtils = {
    formatCurrency: (value) => {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(value);
    },
    
    formatPercentage: (value) => {
        return new Intl.NumberFormat('en-US', {
            style: 'percent',
            minimumFractionDigits: 1,
            maximumFractionDigits: 1
        }).format(value / 100);
    },
    
    formatNumber: (value) => {
        return new Intl.NumberFormat('en-US').format(value);
    }
};
