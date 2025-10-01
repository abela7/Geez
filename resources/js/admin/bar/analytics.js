/**
 * Bar Analytics Page JavaScript
 * Restaurant-OS Admin Panel
 * 
 * Handles analytics charts, data visualization, and reporting
 */

class BarAnalyticsPage {
    constructor() {
        this.currentPeriod = 'today';
        this.charts = {};
        this.analyticsData = {};
        this.isLoading = false;
        
        this.init();
    }

    /**
     * Initialize the analytics page
     */
    init() {
        this.bindEvents();
        this.generateDummyData();
        this.loadAnalytics();
        this.updateStatistics();
        this.initializeCharts();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Period selector buttons
        const periodButtons = document.querySelectorAll('.period-btn');
        periodButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const period = e.target.dataset.period;
                this.selectPeriod(period);
            });
        });

        // Date range functionality
        this.bindDateRangeEvents();

        // Export report button
        const exportBtn = document.querySelector('.export-report-btn');
        if (exportBtn) {
            exportBtn.addEventListener('click', this.exportReport.bind(this));
        }

        // Refresh data button
        const refreshBtn = document.querySelector('.refresh-data-btn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', this.refreshData.bind(this));
        }

        // Chart controls
        const salesPeriodSelect = document.getElementById('sales-period');
        if (salesPeriodSelect) {
            salesPeriodSelect.addEventListener('change', this.updateSalesChart.bind(this));
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', this.handleKeyboardShortcuts.bind(this));
    }

    /**
     * Bind date range events
     */
    bindDateRangeEvents() {
        const dateRangeBtn = document.querySelector('.date-range-btn');
        if (dateRangeBtn) {
            dateRangeBtn.addEventListener('click', this.showDateRangeModal.bind(this));
        }

        // Modal functionality
        const modalClose = document.querySelector('.modal-close');
        if (modalClose) {
            modalClose.addEventListener('click', this.closeDateRangeModal.bind(this));
        }

        const modalOverlay = document.querySelector('.modal-overlay');
        if (modalOverlay) {
            modalOverlay.addEventListener('click', this.closeDateRangeModal.bind(this));
        }

        const cancelBtn = document.querySelector('.cancel-btn');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', this.closeDateRangeModal.bind(this));
        }

        // Preset buttons
        const presetButtons = document.querySelectorAll('.preset-btn');
        presetButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const preset = e.target.dataset.preset;
                this.applyDatePreset(preset);
            });
        });

        // Date range form
        const dateRangeForm = document.getElementById('date-range-form');
        if (dateRangeForm) {
            dateRangeForm.addEventListener('submit', this.handleDateRangeSubmit.bind(this));
        }
    }

    /**
     * Generate dummy data for demonstration
     */
    generateDummyData() {
        this.analyticsData = {
            totalRevenue: 15420.50,
            drinksSold: 1247,
            averageOrderValue: 28.75,
            peakHour: '8 PM',
            revenueChange: 12.5,
            drinksChange: 8.3,
            aovChange: -2.1,
            
            salesByCategory: [
                { category: 'Cocktails', sales: 8500, percentage: 55.1 },
                { category: 'Beer', sales: 3200, percentage: 20.8 },
                { category: 'Wine', sales: 2400, percentage: 15.6 },
                { category: 'Spirits', sales: 900, percentage: 5.8 },
                { category: 'Mocktails', sales: 420, percentage: 2.7 }
            ],
            
            popularDrinks: [
                { name: 'Classic Martini', sales: 156, revenue: 2496 },
                { name: 'Heineken Beer', sales: 142, revenue: 1136 },
                { name: 'House Red Wine', sales: 98, revenue: 1176 },
                { name: 'Old Fashioned', sales: 87, revenue: 1566 },
                { name: 'Virgin Mojito', sales: 76, revenue: 684 }
            ],
            
            hourlySales: [
                { hour: '12 PM', sales: 45 },
                { hour: '1 PM', sales: 62 },
                { hour: '2 PM', sales: 38 },
                { hour: '3 PM', sales: 25 },
                { hour: '4 PM', sales: 31 },
                { hour: '5 PM', sales: 89 },
                { hour: '6 PM', sales: 156 },
                { hour: '7 PM', sales: 198 },
                { hour: '8 PM', sales: 234 },
                { hour: '9 PM', sales: 201 },
                { hour: '10 PM', sales: 167 },
                { hour: '11 PM', sales: 134 }
            ],
            
            profitAnalysis: {
                totalCost: 6420.25,
                totalRevenue: 15420.50,
                profitMargin: 58.4,
                topProfitableItems: [
                    { name: 'Virgin Mojito', margin: 66.7 },
                    { name: 'Classic Martini', margin: 59.4 },
                    { name: 'House Wine', margin: 58.3 }
                ]
            }
        };
    }

    /**
     * Load analytics data
     */
    loadAnalytics() {
        this.isLoading = true;
        
        // Simulate loading delay
        setTimeout(() => {
            this.renderPopularDrinks();
            this.isLoading = false;
        }, 500);
    }

    /**
     * Update statistics
     */
    updateStatistics() {
        const data = this.analyticsData;
        
        document.getElementById('total-revenue').textContent = `$${data.totalRevenue.toLocaleString()}`;
        document.getElementById('drinks-sold').textContent = data.drinksSold.toLocaleString();
        document.getElementById('avg-order-value').textContent = `$${data.averageOrderValue.toFixed(2)}`;
        document.getElementById('peak-hour').textContent = data.peakHour;
        
        // Update change indicators
        this.updateChangeIndicator('revenue-change', data.revenueChange);
        this.updateChangeIndicator('drinks-change', data.drinksChange);
        this.updateChangeIndicator('aov-change', data.aovChange);
    }

    /**
     * Update change indicator
     */
    updateChangeIndicator(elementId, changeValue) {
        const element = document.getElementById(elementId);
        if (!element) return;
        
        const sign = changeValue > 0 ? '+' : '';
        element.textContent = `${sign}${changeValue.toFixed(1)}%`;
        
        // Update class based on change value
        element.className = 'stat-change';
        if (changeValue > 0) {
            element.classList.add('positive');
        } else if (changeValue < 0) {
            element.classList.add('negative');
        } else {
            element.classList.add('neutral');
        }
    }

    /**
     * Select time period
     */
    selectPeriod(period) {
        this.currentPeriod = period;
        
        // Update button states
        const periodButtons = document.querySelectorAll('.period-btn');
        periodButtons.forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.period === period) {
                btn.classList.add('active');
            }
        });
        
        // Show/hide custom date inputs
        const customInputs = document.getElementById('custom-date-inputs');
        if (period === 'custom') {
            customInputs.style.display = 'flex';
        } else {
            customInputs.style.display = 'none';
        }
        
        // Reload data for new period
        this.loadAnalyticsForPeriod(period);
    }

    /**
     * Load analytics for specific period
     */
    loadAnalyticsForPeriod(period) {
        // Simulate different data for different periods
        // In real implementation, this would make an API call
        console.log(`Loading analytics for period: ${period}`);
        
        this.updateCharts();
    }

    /**
     * Initialize all charts
     */
    initializeCharts() {
        // Wait for Chart.js to be available (would be loaded via CDN or npm)
        if (typeof Chart !== 'undefined') {
            this.initSalesCategoryChart();
            this.initHourlySalesChart();
            this.initProfitAnalysisChart();
            this.initSeasonalTrendsChart();
            this.initCocktailBeerChart();
        } else {
            // Fallback: show loading state or load Chart.js
            this.showChartLoadingState();
        }
    }

    /**
     * Show chart loading state
     */
    showChartLoadingState() {
        const chartBodies = document.querySelectorAll('.chart-body');
        chartBodies.forEach(body => {
            if (body.querySelector('canvas')) {
                body.innerHTML = `
                    <div class="chart-loading">
                        <div class="loading-spinner"></div>
                    </div>
                `;
            }
        });
    }

    /**
     * Render popular drinks list
     */
    renderPopularDrinks() {
        const container = document.getElementById('popular-drinks-list');
        if (!container) return;
        
        const drinks = this.analyticsData.popularDrinks;
        
        container.innerHTML = drinks.map((drink, index) => `
            <div class="popular-drink-item">
                <div class="drink-info">
                    <div class="drink-name">${drink.name}</div>
                    <div class="drink-sales">${drink.sales} sold • $${drink.revenue.toLocaleString()}</div>
                </div>
                <div class="drink-rank">${index + 1}</div>
            </div>
        `).join('');
    }

    /**
     * Update all charts
     */
    updateCharts() {
        // Update each chart with new data
        Object.keys(this.charts).forEach(chartKey => {
            const chart = this.charts[chartKey];
            if (chart && chart.update) {
                chart.update();
            }
        });
    }

    /**
     * Show date range modal
     */
    showDateRangeModal() {
        const modal = document.getElementById('date-range-modal');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            // Set default dates
            const today = new Date();
            const lastWeek = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
            
            document.getElementById('modal-start-date').value = this.formatDate(lastWeek);
            document.getElementById('modal-end-date').value = this.formatDate(today);
        }
    }

    /**
     * Close date range modal
     */
    closeDateRangeModal() {
        const modal = document.getElementById('date-range-modal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    }

    /**
     * Apply date preset
     */
    applyDatePreset(preset) {
        const today = new Date();
        let startDate, endDate = today;
        
        switch (preset) {
            case 'last-7-days':
                startDate = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
                break;
            case 'last-30-days':
                startDate = new Date(today.getTime() - 30 * 24 * 60 * 60 * 1000);
                break;
            case 'last-quarter':
                startDate = new Date(today.getTime() - 90 * 24 * 60 * 60 * 1000);
                break;
            case 'last-year':
                startDate = new Date(today.getTime() - 365 * 24 * 60 * 60 * 1000);
                break;
        }
        
        document.getElementById('modal-start-date').value = this.formatDate(startDate);
        document.getElementById('modal-end-date').value = this.formatDate(endDate);
    }

    /**
     * Handle date range form submission
     */
    handleDateRangeSubmit(event) {
        event.preventDefault();
        
        const formData = new FormData(event.target);
        const startDate = formData.get('start_date');
        const endDate = formData.get('end_date');
        
        if (startDate && endDate) {
            this.loadAnalyticsForDateRange(startDate, endDate);
            this.closeDateRangeModal();
            this.showNotification('Date range applied successfully', 'success');
        }
    }

    /**
     * Load analytics for date range
     */
    loadAnalyticsForDateRange(startDate, endDate) {
        console.log(`Loading analytics from ${startDate} to ${endDate}`);
        // In real implementation, this would make an API call
        this.updateCharts();
    }

    /**
     * Generate report
     */
    generateReport(reportType) {
        const reportNames = {
            'daily': 'Daily Bar Report',
            'weekly': 'Weekly Summary',
            'monthly': 'Monthly Analysis',
            'wastage': 'Wastage Report'
        };
        
        this.showNotification(`Generating ${reportNames[reportType]}...`, 'info');
        
        // Simulate report generation
        setTimeout(() => {
            this.showNotification(`${reportNames[reportType]} generated successfully`, 'success');
        }, 2000);
    }

    /**
     * Export report
     */
    exportReport() {
        this.showNotification('Exporting analytics report...', 'info');
        
        // Simulate export
        setTimeout(() => {
            this.showNotification('Report exported successfully', 'success');
        }, 1500);
    }

    /**
     * Refresh data
     */
    refreshData() {
        this.isLoading = true;
        this.showNotification('Refreshing analytics data...', 'info');
        
        // Simulate data refresh
        setTimeout(() => {
            this.loadAnalytics();
            this.updateStatistics();
            this.updateCharts();
            this.isLoading = false;
            this.showNotification('Data refreshed successfully', 'success');
        }, 2000);
    }

    /**
     * Update sales chart based on period selection
     */
    updateSalesChart() {
        const period = document.getElementById('sales-period').value;
        console.log(`Updating sales chart for period: ${period}`);
        
        // Update chart data based on period
        if (this.charts.salesCategory) {
            // Update chart data here
            this.charts.salesCategory.update();
        }
    }

    /**
     * Initialize sales by category chart
     */
    initSalesCategoryChart() {
        const ctx = document.getElementById('sales-category-chart');
        if (!ctx) return;
        
        // Placeholder for Chart.js implementation
        ctx.getContext('2d');
        
        // Show placeholder message
        const container = ctx.parentElement;
        container.innerHTML = `
            <div class="chart-placeholder">
                <svg class="chart-placeholder-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4"/>
                </svg>
                <p>Sales by Category Chart</p>
                <small>Chart.js integration needed</small>
            </div>
        `;
    }

    /**
     * Initialize hourly sales chart
     */
    initHourlySalesChart() {
        const ctx = document.getElementById('hourly-sales-chart');
        if (!ctx) return;
        
        const container = ctx.parentElement;
        container.innerHTML = `
            <div class="chart-placeholder">
                <svg class="chart-placeholder-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4"/>
                </svg>
                <p>Hourly Sales Chart</p>
                <small>Chart.js integration needed</small>
            </div>
        `;
    }

    /**
     * Initialize profit analysis chart
     */
    initProfitAnalysisChart() {
        const ctx = document.getElementById('profit-analysis-chart');
        if (!ctx) return;
        
        const container = ctx.parentElement;
        container.innerHTML = `
            <div class="chart-placeholder">
                <svg class="chart-placeholder-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
                <p>Profit Analysis</p>
                <small>Chart.js integration needed</small>
            </div>
        `;
    }

    /**
     * Initialize seasonal trends chart
     */
    initSeasonalTrendsChart() {
        const ctx = document.getElementById('seasonal-trends-chart');
        if (!ctx) return;
        
        const container = ctx.parentElement;
        container.innerHTML = `
            <div class="chart-placeholder">
                <svg class="chart-placeholder-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4"/>
                </svg>
                <p>Seasonal Trends</p>
                <small>Chart.js integration needed</small>
            </div>
        `;
    }

    /**
     * Initialize cocktail vs beer chart
     */
    initCocktailBeerChart() {
        const ctx = document.getElementById('cocktail-beer-chart');
        if (!ctx) return;
        
        const container = ctx.parentElement;
        container.innerHTML = `
            <div class="chart-placeholder">
                <svg class="chart-placeholder-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                </svg>
                <p>Cocktails vs Beer</p>
                <small>Chart.js integration needed</small>
            </div>
        `;
    }

    /**
     * Format date for input
     */
    formatDate(date) {
        return date.toISOString().split('T')[0];
    }

    /**
     * Show notification
     */
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <span>${message}</span>
                <button type="button" class="notification-close" onclick="this.parentElement.parentElement.remove()">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;

        document.body.appendChild(notification);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    }

    /**
     * Handle keyboard shortcuts
     */
    handleKeyboardShortcuts(event) {
        if (event.ctrlKey || event.metaKey) {
            switch (event.key) {
                case 'r':
                    event.preventDefault();
                    this.refreshData();
                    break;
                case 'd':
                    event.preventDefault();
                    this.showDateRangeModal();
                    break;
                case 'e':
                    event.preventDefault();
                    this.exportReport();
                    break;
                case '1':
                    event.preventDefault();
                    this.selectPeriod('today');
                    break;
                case '2':
                    event.preventDefault();
                    this.selectPeriod('week');
                    break;
                case '3':
                    event.preventDefault();
                    this.selectPeriod('month');
                    break;
            }
        }
        
        if (event.key === 'Escape') {
            this.closeDateRangeModal();
        }
    }
}

// Add Chart.js placeholder styles
const chartPlaceholderCSS = `
    .chart-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: var(--color-text-tertiary);
        text-align: center;
        padding: 2rem;
    }
    
    .chart-placeholder-icon {
        width: 3rem;
        height: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .chart-placeholder p {
        font-weight: 500;
        margin: 0 0 0.25rem 0;
        color: var(--color-text-secondary);
    }
    
    .chart-placeholder small {
        font-size: 0.75rem;
        color: var(--color-text-tertiary);
    }
`;

// Inject placeholder styles
const style = document.createElement('style');
style.textContent = chartPlaceholderCSS;
document.head.appendChild(style);

// Initialize the page when DOM is loaded
let analyticsManager;

document.addEventListener('DOMContentLoaded', function() {
    analyticsManager = new BarAnalyticsPage();
});
