/**
 * Customer Analytics JavaScript
 * Restaurant-OS Admin Panel
 * 
 * Handles customer tracking, service recording, analytics, and reporting
 */

// Import Chart.js if available, otherwise use a fallback
let Chart;
try {
    Chart = window.Chart || require('chart.js');
} catch (e) {
    // Fallback for Chart.js - will create simple visual representations
    Chart = null;
}

class CustomerAnalyticsManager {
    constructor() {
        this.serviceRecords = [];
        this.tables = [];
        this.waiters = [];
        this.charts = {};
        this.currentPeriod = 'week';
        this.isRecording = false;
        
        this.init();
    }

    /**
     * Initialize the analytics manager
     */
    init() {
        this.bindEvents();
        this.generateDummyData();
        this.updateStatistics();
        this.renderCharts();
        this.renderServiceRecords();
        this.renderReports();
        this.renderInsights();
        this.populateSelects();
        this.startClock();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Period selector
        this.bindPeriodEvents();
        
        // Service recording events
        this.bindServiceEvents();
        
        // Modal events
        this.bindModalEvents();
        
        // Action button events
        this.bindActionEvents();
        
        // Form events
        this.bindFormEvents();
        
        // Chart refresh events
        this.bindChartEvents();
    }

    /**
     * Bind period selection events
     */
    bindPeriodEvents() {
        const periodSelect = document.getElementById('period-select');
        const customDateRange = document.getElementById('custom-date-range');
        const startDate = document.getElementById('start-date');
        const endDate = document.getElementById('end-date');

        if (periodSelect) {
            periodSelect.addEventListener('change', (e) => {
                this.currentPeriod = e.target.value;
                
                if (this.currentPeriod === 'custom') {
                    customDateRange.style.display = 'flex';
                } else {
                    customDateRange.style.display = 'none';
                    this.updateAnalytics();
                }
            });
        }

        if (startDate && endDate) {
            [startDate, endDate].forEach(input => {
                input.addEventListener('change', () => {
                    if (this.currentPeriod === 'custom' && startDate.value && endDate.value) {
                        this.updateAnalytics();
                    }
                });
            });
        }
    }

    /**
     * Bind service recording events
     */
    bindServiceEvents() {
        const decreaseBtn = document.getElementById('decrease-customers');
        const increaseBtn = document.getElementById('increase-customers');
        const customerCount = document.getElementById('customer-count');
        const recordBtn = document.querySelector('.record-service-now-btn');

        if (decreaseBtn && customerCount) {
            decreaseBtn.addEventListener('click', () => {
                const current = parseInt(customerCount.value) || 1;
                if (current > 1) {
                    customerCount.value = current - 1;
                }
            });
        }

        if (increaseBtn && customerCount) {
            increaseBtn.addEventListener('click', () => {
                const current = parseInt(customerCount.value) || 1;
                if (current < 20) {
                    customerCount.value = current + 1;
                }
            });
        }

        if (recordBtn) {
            recordBtn.addEventListener('click', () => this.recordServiceQuick());
        }

        // Records filter
        const recordsFilter = document.getElementById('records-filter');
        if (recordsFilter) {
            recordsFilter.addEventListener('change', () => this.renderServiceRecords());
        }
    }

    /**
     * Bind modal events
     */
    bindModalEvents() {
        // Service modal
        this.bindModalCloseEvents('service-modal', () => this.closeServiceModal());

        // Escape key to close modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeServiceModal();
            }
        });
    }

    /**
     * Bind modal close events for a specific modal
     */
    bindModalCloseEvents(modalId, closeCallback) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        const closeBtn = modal.querySelector('.modal-close');
        const cancelBtn = modal.querySelector('.cancel-service-btn');
        const overlay = modal.querySelector('.modal-overlay');

        if (closeBtn) closeBtn.addEventListener('click', closeCallback);
        if (cancelBtn) cancelBtn.addEventListener('click', closeCallback);
        if (overlay) overlay.addEventListener('click', closeCallback);
    }

    /**
     * Bind action button events
     */
    bindActionEvents() {
        // Record service button
        document.querySelectorAll('.record-service-btn').forEach(btn => {
            btn.addEventListener('click', () => this.openServiceModal());
        });

        // Export analytics button
        const exportBtn = document.querySelector('.export-analytics-btn');
        if (exportBtn) {
            exportBtn.addEventListener('click', () => this.exportAnalytics());
        }

        // Generate report button
        const generateReportBtn = document.querySelector('.generate-report-btn');
        if (generateReportBtn) {
            generateReportBtn.addEventListener('click', () => this.generateReport());
        }

        // Event delegation for dynamic buttons
        document.addEventListener('click', (e) => {
            // Service record edit/delete
            if (e.target.closest('.record-action-btn')) {
                const action = e.target.closest('.record-action-btn').dataset.action;
                const recordId = parseInt(e.target.closest('.service-record').dataset.recordId);
                
                if (action === 'edit') {
                    this.editServiceRecord(recordId);
                } else if (action === 'delete') {
                    this.deleteServiceRecord(recordId);
                }
            }
        });
    }

    /**
     * Bind form events
     */
    bindFormEvents() {
        const serviceForm = document.getElementById('service-form');
        if (serviceForm) {
            serviceForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.saveServiceRecord();
            });
        }

        // Counter buttons in modal
        const modalCounterBtns = document.querySelectorAll('#service-modal .counter-btn');
        modalCounterBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const input = e.target.parentNode.querySelector('.counter-input');
                const isIncrease = e.target.classList.contains('increase');
                const current = parseInt(input.value) || 1;
                
                if (isIncrease && current < 20) {
                    input.value = current + 1;
                } else if (!isIncrease && current > 1) {
                    input.value = current - 1;
                }
            });
        });
    }

    /**
     * Bind chart events
     */
    bindChartEvents() {
        document.querySelectorAll('.chart-action-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                this.refreshCharts();
            });
        });
    }

    /**
     * Generate dummy data
     */
    generateDummyData() {
        this.generateTables();
        this.generateWaiters();
        this.generateServiceRecords();
    }

    /**
     * Generate restaurant tables
     */
    generateTables() {
        this.tables = [
            { id: 1, number: 'T1', capacity: 2 },
            { id: 2, number: 'T2', capacity: 2 },
            { id: 3, number: 'T3', capacity: 4 },
            { id: 4, number: 'T4', capacity: 4 },
            { id: 5, number: 'T5', capacity: 6 },
            { id: 6, number: 'T6', capacity: 2 },
            { id: 7, number: 'T7', capacity: 4 },
            { id: 8, number: 'T8', capacity: 8 },
            { id: 9, number: 'P1', capacity: 4 },
            { id: 10, number: 'P2', capacity: 6 },
            { id: 11, number: 'VIP1', capacity: 10 },
            { id: 12, number: 'B1', capacity: 2 },
            { id: 13, number: 'B2', capacity: 4 },
            { id: 14, number: 'B3', capacity: 6 },
            { id: 15, number: 'B4', capacity: 4 }
        ];
    }

    /**
     * Generate waiters/staff
     */
    generateWaiters() {
        this.waiters = [
            { id: 1, name: 'Sarah Johnson', shift: 'morning' },
            { id: 2, name: 'Michael Chen', shift: 'afternoon' },
            { id: 3, name: 'Emily Rodriguez', shift: 'evening' },
            { id: 4, name: 'David Thompson', shift: 'morning' },
            { id: 5, name: 'Lisa Anderson', shift: 'afternoon' },
            { id: 6, name: 'James Wilson', shift: 'evening' },
            { id: 7, name: 'Maria Garcia', shift: 'morning' },
            { id: 8, name: 'Robert Brown', shift: 'afternoon' }
        ];
    }

    /**
     * Generate service records
     */
    generateServiceRecords() {
        this.serviceRecords = [];
        const now = new Date();
        
        // Generate records for the last 30 days
        for (let day = 0; day < 30; day++) {
            const date = new Date(now);
            date.setDate(date.getDate() - day);
            
            // Skip some days randomly to make it more realistic
            if (Math.random() < 0.1) continue;
            
            // Generate 10-30 service records per day
            const recordsPerDay = Math.floor(Math.random() * 20) + 10;
            
            for (let i = 0; i < recordsPerDay; i++) {
                const serviceTime = new Date(date);
                serviceTime.setHours(
                    Math.floor(Math.random() * 12) + 11, // 11 AM to 10 PM
                    Math.floor(Math.random() * 60),
                    Math.floor(Math.random() * 60)
                );
                
                const customers = Math.floor(Math.random() * 8) + 1; // 1-8 customers
                const table = this.tables[Math.floor(Math.random() * this.tables.length)];
                const waiter = this.waiters[Math.floor(Math.random() * this.waiters.length)];
                
                this.serviceRecords.push({
                    id: this.serviceRecords.length + 1,
                    customers: customers,
                    tableId: table.id,
                    tableName: table.number,
                    waiterId: waiter.id,
                    waiterName: waiter.name,
                    serviceTime: serviceTime,
                    notes: '',
                    recordedAt: serviceTime
                });
            }
        }
        
        // Sort by service time (most recent first)
        this.serviceRecords.sort((a, b) => b.serviceTime - a.serviceTime);
    }

    /**
     * Update statistics
     */
    updateStatistics() {
        const now = new Date();
        const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        const weekStart = this.getStartOfWeek(now);
        const monthStart = new Date(now.getFullYear(), now.getMonth(), 1);
        
        // Today's customers
        const todayRecords = this.serviceRecords.filter(r => 
            r.serviceTime >= today && r.serviceTime < new Date(today.getTime() + 24 * 60 * 60 * 1000)
        );
        const customersToday = todayRecords.reduce((sum, r) => sum + r.customers, 0);
        
        // This week's customers
        const weekRecords = this.serviceRecords.filter(r => r.serviceTime >= weekStart);
        const customersWeek = weekRecords.reduce((sum, r) => sum + r.customers, 0);
        
        // This month's customers
        const monthRecords = this.serviceRecords.filter(r => r.serviceTime >= monthStart);
        const customersMonth = monthRecords.reduce((sum, r) => sum + r.customers, 0);
        
        // Average daily customers (last 30 days)
        const thirtyDaysAgo = new Date(now.getTime() - 30 * 24 * 60 * 60 * 1000);
        const last30DaysRecords = this.serviceRecords.filter(r => r.serviceTime >= thirtyDaysAgo);
        const averageDaily = Math.round(last30DaysRecords.reduce((sum, r) => sum + r.customers, 0) / 30);
        
        // Calculate changes (dummy percentages for demo)
        const todayChange = Math.floor(Math.random() * 20) - 5; // -5% to +15%
        const weekChange = Math.floor(Math.random() * 15) + 2; // +2% to +17%
        const monthChange = Math.floor(Math.random() * 25) + 5; // +5% to +30%
        const averageChange = Math.floor(Math.random() * 10) + 1; // +1% to +11%
        
        // Update DOM
        document.getElementById('customers-today').textContent = customersToday;
        document.getElementById('customers-week').textContent = customersWeek;
        document.getElementById('customers-month').textContent = customersMonth;
        document.getElementById('average-daily').textContent = averageDaily;
        
        // Update change indicators
        this.updateChangeIndicator('today-change', todayChange);
        this.updateChangeIndicator('week-change', weekChange);
        this.updateChangeIndicator('month-change', monthChange);
        this.updateChangeIndicator('average-change', averageChange);
        
        // Update key metrics
        this.updateKeyMetrics();
    }

    /**
     * Update change indicator
     */
    updateChangeIndicator(elementId, change) {
        const element = document.getElementById(elementId);
        if (!element) return;
        
        const sign = change >= 0 ? '+' : '';
        element.textContent = `${sign}${change}%`;
        element.className = `stat-change ${change < 0 ? 'negative' : ''}`;
    }

    /**
     * Update key metrics
     */
    updateKeyMetrics() {
        // Calculate peak hour
        const hourCounts = {};
        this.serviceRecords.forEach(record => {
            const hour = record.serviceTime.getHours();
            hourCounts[hour] = (hourCounts[hour] || 0) + record.customers;
        });
        
        const peakHour = Object.keys(hourCounts).reduce((a, b) => 
            hourCounts[a] > hourCounts[b] ? a : b
        );
        
        document.getElementById('peak-hour').textContent = `${peakHour}:00`;
        
        // Average service time (dummy data)
        document.getElementById('avg-service-time').textContent = `${Math.floor(Math.random() * 30) + 45}`;
        
        // Table turnover (dummy data)
        document.getElementById('table-turnover').textContent = `${(Math.random() * 2 + 2).toFixed(1)}`;
        
        // Customer satisfaction (dummy data)
        document.getElementById('satisfaction-score').textContent = `${Math.floor(Math.random() * 15) + 85}`;
    }

    /**
     * Render charts
     */
    renderCharts() {
        this.renderDailyFlowChart();
        this.renderHourlyDistributionChart();
        this.renderServicePerformanceChart();
        this.renderTableUtilizationChart();
    }

    /**
     * Render daily flow chart
     */
    renderDailyFlowChart() {
        const canvas = document.getElementById('daily-flow-chart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        
        // Generate data for the last 7 days
        const labels = [];
        const data = [];
        const now = new Date();
        
        for (let i = 6; i >= 0; i--) {
            const date = new Date(now);
            date.setDate(date.getDate() - i);
            labels.push(date.toLocaleDateString('en-US', { weekday: 'short' }));
            
            const dayRecords = this.serviceRecords.filter(r => 
                r.serviceTime.toDateString() === date.toDateString()
            );
            data.push(dayRecords.reduce((sum, r) => sum + r.customers, 0));
        }

        if (Chart) {
            this.charts.dailyFlow = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Customers Served',
                        data: data,
                        borderColor: '#8b5cf6',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        } else {
            this.renderFallbackChart(ctx, data, labels, 'line');
        }
    }

    /**
     * Render hourly distribution chart
     */
    renderHourlyDistributionChart() {
        const canvas = document.getElementById('hourly-distribution-chart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        
        // Generate hourly data
        const hourCounts = {};
        for (let hour = 11; hour <= 22; hour++) {
            hourCounts[hour] = 0;
        }
        
        this.serviceRecords.forEach(record => {
            const hour = record.serviceTime.getHours();
            if (hour >= 11 && hour <= 22) {
                hourCounts[hour] += record.customers;
            }
        });
        
        const labels = Object.keys(hourCounts).map(h => `${h}:00`);
        const data = Object.values(hourCounts);

        if (Chart) {
            this.charts.hourlyDistribution = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Customers per Hour',
                        data: data,
                        backgroundColor: 'rgba(6, 182, 212, 0.8)',
                        borderColor: '#06b6d4',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        } else {
            this.renderFallbackChart(ctx, data, labels, 'bar');
        }
    }

    /**
     * Render service performance chart
     */
    renderServicePerformanceChart() {
        const canvas = document.getElementById('service-performance-chart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        
        // Generate waiter performance data
        const waiterStats = {};
        this.waiters.forEach(waiter => {
            waiterStats[waiter.name] = 0;
        });
        
        this.serviceRecords.forEach(record => {
            waiterStats[record.waiterName] = (waiterStats[record.waiterName] || 0) + record.customers;
        });
        
        const labels = Object.keys(waiterStats);
        const data = Object.values(waiterStats);

        if (Chart) {
            this.charts.servicePerformance = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: [
                            '#8b5cf6', '#06b6d4', '#10b981', '#f59e0b',
                            '#ef4444', '#6366f1', '#ec4899', '#14b8a6'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });
        } else {
            this.renderFallbackChart(ctx, data, labels, 'doughnut');
        }
    }

    /**
     * Render table utilization chart
     */
    renderTableUtilizationChart() {
        const canvas = document.getElementById('table-utilization-chart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        
        // Generate table utilization data
        const tableStats = {};
        this.tables.forEach(table => {
            tableStats[table.number] = 0;
        });
        
        this.serviceRecords.forEach(record => {
            tableStats[record.tableName] = (tableStats[record.tableName] || 0) + 1;
        });
        
        const labels = Object.keys(tableStats);
        const data = Object.values(tableStats);

        if (Chart) {
            this.charts.tableUtilization = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Times Used',
                        data: data,
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                        borderColor: '#10b981',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        } else {
            this.renderFallbackChart(ctx, data, labels, 'bar');
        }
    }

    /**
     * Render fallback chart when Chart.js is not available
     */
    renderFallbackChart(ctx, data, labels, type) {
        const canvas = ctx.canvas;
        const width = canvas.width;
        const height = canvas.height;
        
        ctx.clearRect(0, 0, width, height);
        ctx.fillStyle = '#6b7280';
        ctx.font = '14px Arial';
        ctx.textAlign = 'center';
        ctx.fillText(`${type.charAt(0).toUpperCase() + type.slice(1)} Chart`, width / 2, height / 2 - 10);
        ctx.fillText('(Chart.js not loaded)', width / 2, height / 2 + 10);
    }

    /**
     * Refresh all charts
     */
    refreshCharts() {
        Object.values(this.charts).forEach(chart => {
            if (chart && chart.destroy) {
                chart.destroy();
            }
        });
        this.charts = {};
        this.renderCharts();
        this.showNotification('Charts refreshed', 'success');
    }

    /**
     * Render service records
     */
    renderServiceRecords() {
        const recordsList = document.getElementById('service-records-list');
        if (!recordsList) return;

        const filter = document.getElementById('records-filter')?.value || 'today';
        const now = new Date();
        let filteredRecords = [];

        switch (filter) {
            case 'today':
                const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
                filteredRecords = this.serviceRecords.filter(r => 
                    r.serviceTime >= today && r.serviceTime < new Date(today.getTime() + 24 * 60 * 60 * 1000)
                );
                break;
            case 'week':
                const weekStart = this.getStartOfWeek(now);
                filteredRecords = this.serviceRecords.filter(r => r.serviceTime >= weekStart);
                break;
            case 'month':
                const monthStart = new Date(now.getFullYear(), now.getMonth(), 1);
                filteredRecords = this.serviceRecords.filter(r => r.serviceTime >= monthStart);
                break;
            default:
                filteredRecords = this.serviceRecords.slice(0, 20); // Recent 20
        }

        if (filteredRecords.length === 0) {
            recordsList.innerHTML = `
                <div class="empty-state">
                    <p>No service records found for the selected period.</p>
                </div>
            `;
            return;
        }

        recordsList.innerHTML = filteredRecords.slice(0, 20).map(record => `
            <div class="service-record" data-record-id="${record.id}">
                <div class="record-info">
                    <div class="record-time">${this.formatTime(record.serviceTime)}</div>
                    <div class="record-details">
                        <div class="record-customers">${record.customers} customer${record.customers !== 1 ? 's' : ''}</div>
                        <div class="record-meta">Table ${record.tableName} • ${record.waiterName}</div>
                    </div>
                </div>
                <div class="record-actions">
                    <button class="record-action-btn" data-action="edit" title="Edit Record">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button class="record-action-btn" data-action="delete" title="Delete Record">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        `).join('');
    }

    /**
     * Render reports
     */
    renderReports() {
        const reportsContent = document.getElementById('reports-content');
        if (!reportsContent) return;

        // Generate sample report
        const now = new Date();
        const weekStart = this.getStartOfWeek(now);
        const weekRecords = this.serviceRecords.filter(r => r.serviceTime >= weekStart);
        
        const dailyTotals = {};
        for (let i = 0; i < 7; i++) {
            const date = new Date(weekStart);
            date.setDate(date.getDate() + i);
            const dateStr = date.toISOString().split('T')[0];
            dailyTotals[dateStr] = {
                date: date.toLocaleDateString('en-US', { weekday: 'long', month: 'short', day: 'numeric' }),
                customers: 0,
                services: 0
            };
        }
        
        weekRecords.forEach(record => {
            const dateStr = record.serviceTime.toISOString().split('T')[0];
            if (dailyTotals[dateStr]) {
                dailyTotals[dateStr].customers += record.customers;
                dailyTotals[dateStr].services += 1;
            }
        });

        reportsContent.innerHTML = `
            <div class="report-summary">
                <h4>Weekly Summary Report</h4>
                <p>Service data for the week of ${weekStart.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}</p>
            </div>
            
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Services</th>
                        <th>Customers</th>
                        <th>Avg per Service</th>
                    </tr>
                </thead>
                <tbody>
                    ${Object.values(dailyTotals).map(day => `
                        <tr>
                            <td>${day.date}</td>
                            <td>${day.services}</td>
                            <td>${day.customers}</td>
                            <td>${day.services > 0 ? (day.customers / day.services).toFixed(1) : '0'}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;
    }

    /**
     * Render insights
     */
    renderInsights() {
        this.renderCustomerTrends();
        this.renderStaffPerformance();
        this.renderRecommendations();
        this.renderForecasting();
    }

    /**
     * Render customer trends
     */
    renderCustomerTrends() {
        const trendsContainer = document.getElementById('customer-trends');
        if (!trendsContainer) return;

        const now = new Date();
        const last30Days = new Date(now.getTime() - 30 * 24 * 60 * 60 * 1000);
        const recentRecords = this.serviceRecords.filter(r => r.serviceTime >= last30Days);
        
        const totalCustomers = recentRecords.reduce((sum, r) => sum + r.customers, 0);
        const avgPerDay = Math.round(totalCustomers / 30);
        const peakDay = this.getMostBusyDay(recentRecords);

        trendsContainer.innerHTML = `
            <div class="insight-item">
                <span class="insight-item-label">Total Customers (30 days)</span>
                <span class="insight-item-value">${totalCustomers}</span>
            </div>
            <div class="insight-item">
                <span class="insight-item-label">Average per Day</span>
                <span class="insight-item-value">${avgPerDay}</span>
            </div>
            <div class="insight-item">
                <span class="insight-item-label">Busiest Day</span>
                <span class="insight-item-value">${peakDay}</span>
            </div>
            <div class="insight-item">
                <span class="insight-item-label">Growth Trend</span>
                <span class="insight-item-value">+${Math.floor(Math.random() * 15) + 5}%</span>
            </div>
        `;
    }

    /**
     * Render staff performance
     */
    renderStaffPerformance() {
        const performanceContainer = document.getElementById('staff-performance');
        if (!performanceContainer) return;

        // Calculate waiter performance
        const waiterStats = {};
        this.waiters.forEach(waiter => {
            waiterStats[waiter.name] = { customers: 0, services: 0 };
        });
        
        this.serviceRecords.forEach(record => {
            if (waiterStats[record.waiterName]) {
                waiterStats[record.waiterName].customers += record.customers;
                waiterStats[record.waiterName].services += 1;
            }
        });

        const topPerformer = Object.keys(waiterStats).reduce((a, b) => 
            waiterStats[a].customers > waiterStats[b].customers ? a : b
        );

        performanceContainer.innerHTML = `
            <div class="insight-item">
                <span class="insight-item-label">Top Performer</span>
                <span class="insight-item-value">${topPerformer}</span>
            </div>
            <div class="insight-item">
                <span class="insight-item-label">Customers Served</span>
                <span class="insight-item-value">${waiterStats[topPerformer].customers}</span>
            </div>
            <div class="insight-item">
                <span class="insight-item-label">Total Services</span>
                <span class="insight-item-value">${waiterStats[topPerformer].services}</span>
            </div>
            <div class="insight-item">
                <span class="insight-item-label">Avg per Service</span>
                <span class="insight-item-value">${(waiterStats[topPerformer].customers / waiterStats[topPerformer].services).toFixed(1)}</span>
            </div>
        `;
    }

    /**
     * Render recommendations
     */
    renderRecommendations() {
        const recommendationsContainer = document.getElementById('recommendations');
        if (!recommendationsContainer) return;

        const recommendations = [
            {
                title: 'Peak Hour Staffing',
                description: 'Consider adding more staff during 7-9 PM when customer volume is highest.'
            },
            {
                title: 'Table Optimization',
                description: 'Tables T3 and T5 are underutilized. Consider repositioning or promotional seating.'
            },
            {
                title: 'Service Training',
                description: 'Average service time can be improved with additional training for new staff.'
            }
        ];

        recommendationsContainer.innerHTML = recommendations.map(rec => `
            <div class="insight-recommendation">
                <div class="recommendation-title">${rec.title}</div>
                <div class="recommendation-description">${rec.description}</div>
            </div>
        `).join('');
    }

    /**
     * Render forecasting
     */
    renderForecasting() {
        const forecastingContainer = document.getElementById('forecasting');
        if (!forecastingContainer) return;

        // Simple forecasting based on trends
        const avgDaily = Math.round(this.serviceRecords.length / 30);
        const tomorrowForecast = Math.round(avgDaily * (1 + (Math.random() * 0.2 - 0.1))); // ±10% variation
        const weekForecast = Math.round(avgDaily * 7 * (1 + (Math.random() * 0.15 - 0.05))); // ±5-15% variation

        forecastingContainer.innerHTML = `
            <div class="insight-item">
                <span class="insight-item-label">Tomorrow's Forecast</span>
                <span class="insight-item-value">${tomorrowForecast} customers</span>
            </div>
            <div class="insight-item">
                <span class="insight-item-label">Next Week</span>
                <span class="insight-item-value">${weekForecast} customers</span>
            </div>
            <div class="insight-item">
                <span class="insight-item-label">Confidence Level</span>
                <span class="insight-item-value">${Math.floor(Math.random() * 15) + 80}%</span>
            </div>
            <div class="insight-item">
                <span class="insight-item-label">Trend Direction</span>
                <span class="insight-item-value">↗ Growing</span>
            </div>
        `;
    }

    /**
     * Populate select dropdowns
     */
    populateSelects() {
        // Populate table selects
        const tableSelects = ['table-select', 'service-table', 'report-table'];
        tableSelects.forEach(selectId => {
            const select = document.getElementById(selectId);
            if (select) {
                const options = this.tables.map(table => 
                    `<option value="${table.id}">Table ${table.number} (${table.capacity} seats)</option>`
                ).join('');
                select.innerHTML = select.innerHTML + options;
            }
        });

        // Populate waiter selects
        const waiterSelects = ['waiter-select', 'service-waiter', 'report-waiter'];
        waiterSelects.forEach(selectId => {
            const select = document.getElementById(selectId);
            if (select) {
                const options = this.waiters.map(waiter => 
                    `<option value="${waiter.id}">${waiter.name}</option>`
                ).join('');
                select.innerHTML = select.innerHTML + options;
            }
        });
    }

    /**
     * Start real-time clock
     */
    startClock() {
        const updateClock = () => {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', { 
                hour12: false,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            
            const clockElement = document.getElementById('current-time');
            if (clockElement) {
                clockElement.textContent = timeString;
            }
        };

        updateClock();
        setInterval(updateClock, 1000);
    }

    /**
     * Record service quickly
     */
    recordServiceQuick() {
        const customers = parseInt(document.getElementById('customer-count')?.value) || 1;
        const tableId = document.getElementById('table-select')?.value;
        const waiterId = document.getElementById('waiter-select')?.value;

        if (!tableId || !waiterId) {
            this.showNotification('Please select table and waiter', 'error');
            return;
        }

        const table = this.tables.find(t => t.id == tableId);
        const waiter = this.waiters.find(w => w.id == waiterId);

        const newRecord = {
            id: this.serviceRecords.length + 1,
            customers: customers,
            tableId: parseInt(tableId),
            tableName: table.number,
            waiterId: parseInt(waiterId),
            waiterName: waiter.name,
            serviceTime: new Date(),
            notes: '',
            recordedAt: new Date()
        };

        this.serviceRecords.unshift(newRecord);
        this.updateStatistics();
        this.renderServiceRecords();
        this.refreshCharts();

        // Reset form
        document.getElementById('customer-count').value = '1';
        document.getElementById('table-select').value = '';
        document.getElementById('waiter-select').value = '';

        this.showNotification('Service recorded successfully!', 'success');
    }

    /**
     * Open service modal
     */
    openServiceModal() {
        const modal = document.getElementById('service-modal');
        if (modal) {
            // Set current time
            const now = new Date();
            const timeString = now.toISOString().slice(0, 16);
            document.getElementById('service-time').value = timeString;
            
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
        }
    }

    /**
     * Close service modal
     */
    closeServiceModal() {
        const modal = document.getElementById('service-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
            this.resetServiceForm();
        }
    }

    /**
     * Reset service form
     */
    resetServiceForm() {
        const form = document.getElementById('service-form');
        if (form) {
            form.reset();
            document.getElementById('service-customers').value = '1';
        }
    }

    /**
     * Save service record
     */
    saveServiceRecord() {
        const formData = new FormData(document.getElementById('service-form'));
        
        const customers = parseInt(formData.get('customers')) || 1;
        const tableId = formData.get('table');
        const waiterId = formData.get('waiter');
        const serviceTime = new Date(formData.get('service_time') || new Date());
        const notes = formData.get('notes') || '';

        if (!tableId || !waiterId) {
            this.showNotification('Please select table and waiter', 'error');
            return;
        }

        const table = this.tables.find(t => t.id == tableId);
        const waiter = this.waiters.find(w => w.id == waiterId);

        const newRecord = {
            id: this.serviceRecords.length + 1,
            customers: customers,
            tableId: parseInt(tableId),
            tableName: table.number,
            waiterId: parseInt(waiterId),
            waiterName: waiter.name,
            serviceTime: serviceTime,
            notes: notes,
            recordedAt: new Date()
        };

        this.serviceRecords.unshift(newRecord);
        this.updateStatistics();
        this.renderServiceRecords();
        this.refreshCharts();
        this.closeServiceModal();

        this.showNotification('Service recorded successfully!', 'success');
    }

    /**
     * Edit service record
     */
    editServiceRecord(recordId) {
        const record = this.serviceRecords.find(r => r.id === recordId);
        if (!record) return;

        // For demo purposes, just show a notification
        this.showNotification(`Edit functionality for record #${recordId} coming soon`, 'info');
    }

    /**
     * Delete service record
     */
    deleteServiceRecord(recordId) {
        if (confirm('Are you sure you want to delete this service record?')) {
            this.serviceRecords = this.serviceRecords.filter(r => r.id !== recordId);
            this.updateStatistics();
            this.renderServiceRecords();
            this.refreshCharts();
            this.showNotification('Service record deleted', 'success');
        }
    }

    /**
     * Update analytics based on period
     */
    updateAnalytics() {
        this.updateStatistics();
        this.refreshCharts();
        this.renderServiceRecords();
        this.renderReports();
        this.renderInsights();
    }

    /**
     * Generate report
     */
    generateReport() {
        this.renderReports();
        this.showNotification('Report generated successfully', 'success');
    }

    /**
     * Export analytics
     */
    exportAnalytics() {
        const csvContent = this.generateAnalyticsCSV();
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `customer-analytics-${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        
        this.showNotification('Analytics exported successfully', 'success');
    }

    /**
     * Generate analytics CSV
     */
    generateAnalyticsCSV() {
        const headers = [
            'Date', 'Time', 'Customers', 'Table', 'Waiter', 'Notes'
        ];
        
        const rows = this.serviceRecords.map(record => [
            record.serviceTime.toISOString().split('T')[0],
            record.serviceTime.toTimeString().split(' ')[0],
            record.customers,
            record.tableName,
            record.waiterName,
            record.notes || ''
        ]);
        
        return [headers, ...rows].map(row => 
            row.map(field => `"${String(field).replace(/"/g, '""')}"`).join(',')
        ).join('\n');
    }

    /**
     * Utility methods
     */
    getStartOfWeek(date) {
        const result = new Date(date);
        const day = result.getDay();
        const diff = result.getDate() - day;
        return new Date(result.setDate(diff));
    }

    getMostBusyDay(records) {
        const dayCounts = {};
        const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        
        records.forEach(record => {
            const day = record.serviceTime.getDay();
            dayCounts[day] = (dayCounts[day] || 0) + record.customers;
        });
        
        const busiestDay = Object.keys(dayCounts).reduce((a, b) => 
            dayCounts[a] > dayCounts[b] ? a : b
        );
        
        return dayNames[busiestDay] || 'N/A';
    }

    formatTime(date) {
        return date.toLocaleTimeString('en-US', { 
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        });
    }

    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <span class="notification-message">${message}</span>
                <button class="notification-close">&times;</button>
            </div>
        `;
        
        // Add to page
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 5000);
        
        // Manual close
        const closeBtn = notification.querySelector('.notification-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            });
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.customerAnalyticsManager = new CustomerAnalyticsManager();
});
