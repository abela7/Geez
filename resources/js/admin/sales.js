/**
 * ==========================================================================
 * GEEZ RESTAURANT ADMIN - SALES REPORTS JAVASCRIPT
 * Interactive functionality for sales reporting interface
 * ==========================================================================
 */

/**
 * Sales Reports Module
 * Handles all interactions for the sales reports page
 */
class SalesReports {
    constructor() {
        this.currentPeriod = 'today';
        this.currentChartView = 'daily';
        this.filtersCollapsed = true;
        this.isLoading = false;
        this.dummyData = this.generateDummyData();
        
        this.init();
    }

    /**
     * Initialize the sales reports functionality
     */
    init() {
        this.bindEvents();
        this.loadInitialData();
        this.setupAccessibility();
    }

    /**
     * Bind all event listeners
     */
    bindEvents() {
        // Date filter buttons
        document.querySelectorAll('.date-filter-btn').forEach(btn => {
            btn.addEventListener('click', (e) => this.handleDateFilter(e));
        });

        // Filters toggle
        const filtersToggle = document.querySelector('.filters-toggle');
        if (filtersToggle) {
            filtersToggle.addEventListener('click', () => this.toggleFilters());
        }

        // Chart view toggle
        document.querySelectorAll('.chart-view-btn').forEach(btn => {
            btn.addEventListener('click', (e) => this.handleChartView(e));
        });

        // Filter actions
        const applyFiltersBtn = document.querySelector('.apply-filters');
        const clearFiltersBtn = document.querySelector('.clear-filters');
        
        if (applyFiltersBtn) {
            applyFiltersBtn.addEventListener('click', () => this.applyFilters());
        }
        
        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', () => this.clearFilters());
        }

        // Table row clicks
        document.addEventListener('click', (e) => {
            const row = e.target.closest('.sales-table tbody tr:not(.loading-row)');
            if (row) {
                this.openOrderDrawer(row.dataset.orderId || 'ORD-001');
            }
        });

        // Drawer close
        const drawerClose = document.querySelector('.drawer-close');
        const drawerOverlay = document.querySelector('.drawer-overlay');
        
        if (drawerClose) {
            drawerClose.addEventListener('click', () => this.closeOrderDrawer());
        }
        
        if (drawerOverlay) {
            drawerOverlay.addEventListener('click', () => this.closeOrderDrawer());
        }

        // Export button
        const exportBtn = document.querySelector('.export-btn');
        if (exportBtn) {
            exportBtn.addEventListener('click', () => this.exportData());
        }

        // Keyboard navigation
        document.addEventListener('keydown', (e) => this.handleKeyboard(e));
    }

    /**
     * Handle date filter selection
     */
    handleDateFilter(e) {
        const btn = e.target;
        const period = btn.dataset.period;
        
        document.querySelectorAll('.date-filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        
        this.currentPeriod = period;
        
        const customDateRange = document.querySelector('.custom-date-range');
        if (customDateRange) {
            customDateRange.style.display = period === 'custom' ? 'block' : 'none';
        }
        
        this.loadData();
    }

    /**
     * Toggle filters panel
     */
    toggleFilters() {
        const filtersPanel = document.querySelector('.filters-panel');
        const filtersContent = document.querySelector('.filters-content');
        const toggle = document.querySelector('.filters-toggle');
        
        this.filtersCollapsed = !this.filtersCollapsed;
        
        filtersPanel.dataset.collapsed = this.filtersCollapsed;
        toggle.setAttribute('aria-expanded', !this.filtersCollapsed);
        
        if (this.filtersCollapsed) {
            filtersContent.style.display = 'none';
        } else {
            filtersContent.style.display = 'block';
        }
    }

    /**
     * Handle chart view toggle
     */
    handleChartView(e) {
        const btn = e.target;
        const view = btn.dataset.view;
        
        document.querySelectorAll('.chart-view-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        
        this.currentChartView = view;
        this.updateChart();
    }

    /**
     * Apply filters
     */
    applyFilters() {
        const filters = this.getFilterValues();
        console.log('Applying filters:', filters);
        
        this.showLoading();
        
        setTimeout(() => {
            this.loadData(filters);
        }, 1000);
    }

    /**
     * Clear all filters
     */
    clearFilters() {
        document.querySelectorAll('.filter-group select, .filter-group input').forEach(input => {
            if (input.type === 'select-multiple') {
                Array.from(input.options).forEach(option => option.selected = false);
            } else {
                input.value = '';
            }
        });
        
        this.loadData();
    }

    /**
     * Get current filter values
     */
    getFilterValues() {
        const paymentMethods = Array.from(document.querySelectorAll('#payment-method-filter option:checked'))
            .map(option => option.value);
        
        return {
            paymentMethods,
            staff: document.getElementById('staff-filter')?.value || '',
            terminal: document.getElementById('terminal-filter')?.value || '',
            search: document.getElementById('search-filter')?.value || '',
            startDate: document.getElementById('start-date')?.value || '',
            endDate: document.getElementById('end-date')?.value || ''
        };
    }

    /**
     * Load initial data
     */
    loadInitialData() {
        this.showLoading();
        
        setTimeout(() => {
            this.loadData();
        }, 1500);
    }

    /**
     * Load data with optional filters
     */
    loadData(filters = {}) {
        this.isLoading = true;
        
        this.updateKPICards();
        this.updateChart();
        this.updateTable();
        
        setTimeout(() => {
            this.hideLoading();
            this.isLoading = false;
        }, 800);
    }

    /**
     * Update KPI cards with data
     */
    updateKPICards() {
        const data = this.dummyData.kpis;
        
        const totalSalesValue = document.querySelector('[data-value="total-sales"]');
        if (totalSalesValue) {
            totalSalesValue.textContent = this.formatCurrency(data.totalSales);
            totalSalesValue.classList.remove('loading-skeleton');
        }
        
        const transactionsValue = document.querySelector('[data-value="transactions"]');
        if (transactionsValue) {
            transactionsValue.textContent = data.transactions.toLocaleString();
            transactionsValue.classList.remove('loading-skeleton');
        }
        
        const avgOrderValue = document.querySelector('[data-value="avg-order"]');
        if (avgOrderValue) {
            avgOrderValue.textContent = this.formatCurrency(data.avgOrderValue);
            avgOrderValue.classList.remove('loading-skeleton');
        }
        
        const topItemValue = document.querySelector('[data-value="top-item"]');
        if (topItemValue) {
            topItemValue.textContent = data.topItem.name;
            topItemValue.classList.remove('loading-skeleton');
        }
    }

    /**
     * Update chart with data
     */
    updateChart() {
        const chartPlaceholder = document.querySelector('.chart-placeholder');
        if (chartPlaceholder) {
            chartPlaceholder.classList.remove('loading-skeleton');
            
            chartPlaceholder.innerHTML = `
                <div class="chart-loading">
                    <svg class="chart-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <p>Chart placeholder - ${this.currentChartView} view</p>
                    <p style="font-size: 12px; opacity: 0.7;">Chart library integration pending</p>
                </div>
            `;
        }
    }

    /**
     * Update table with data
     */
    updateTable() {
        const tableBody = document.querySelector('.sales-table-body');
        if (!tableBody) return;
        
        tableBody.innerHTML = '';
        
        this.dummyData.sales.forEach(sale => {
            const row = this.createTableRow(sale);
            tableBody.appendChild(row);
        });
        
        this.hideEmptyState();
        this.hideErrorState();
    }

    /**
     * Create a table row element
     */
    createTableRow(sale) {
        const row = document.createElement('tr');
        row.dataset.orderId = sale.orderId;
        row.setAttribute('tabindex', '0');
        row.setAttribute('role', 'button');
        row.setAttribute('aria-label', `View details for order ${sale.orderId}`);
        
        row.innerHTML = `
            <td>${sale.orderId}</td>
            <td>${this.formatDateTime(sale.dateTime)}</td>
            <td>${sale.staff}</td>
            <td><span class="payment-badge ${sale.paymentMethod.toLowerCase().replace(' ', '_')}">${sale.paymentMethod}</span></td>
            <td>${this.formatCurrency(sale.totalAmount)}</td>
        `;
        
        return row;
    }

    /**
     * Open order details drawer
     */
    openOrderDrawer(orderId) {
        const drawer = document.getElementById('order-drawer');
        if (!drawer) return;
        
        this.loadOrderDetails(orderId);
        
        drawer.style.display = 'flex';
        drawer.setAttribute('aria-hidden', 'false');
        
        const closeBtn = drawer.querySelector('.drawer-close');
        if (closeBtn) {
            closeBtn.focus();
        }
        
        document.body.style.overflow = 'hidden';
    }

    /**
     * Close order details drawer
     */
    closeOrderDrawer() {
        const drawer = document.getElementById('order-drawer');
        if (!drawer) return;
        
        drawer.style.display = 'none';
        drawer.setAttribute('aria-hidden', 'true');
        
        document.body.style.overflow = '';
    }

    /**
     * Load order details
     */
    loadOrderDetails(orderId) {
        const content = document.querySelector('.order-details-content');
        if (!content) return;
        
        content.innerHTML = `
            <div class="loading-skeleton">
                <div class="skeleton-text"></div>
                <div class="skeleton-text"></div>
                <div class="skeleton-text"></div>
            </div>
        `;
        
        setTimeout(() => {
            const orderData = this.dummyData.sales.find(sale => sale.orderId === orderId) || this.dummyData.sales[0];
            
            content.innerHTML = `
                <div class="order-summary">
                    <h3>Order ${orderData.orderId}</h3>
                    <p><strong>Date:</strong> ${this.formatDateTime(orderData.dateTime)}</p>
                    <p><strong>Staff:</strong> ${orderData.staff}</p>
                    <p><strong>Payment Method:</strong> ${orderData.paymentMethod}</p>
                    <p><strong>Total Amount:</strong> ${this.formatCurrency(orderData.totalAmount)}</p>
                </div>
                <div class="order-items">
                    <h4>Order Items</h4>
                    <ul>
                        <li>Doro Wat - $12.99</li>
                        <li>Injera Bread - $3.99</li>
                        <li>Ethiopian Coffee - $4.99</li>
                    </ul>
                </div>
            `;
        }, 500);
    }

    /**
     * Export data functionality
     */
    exportData() {
        console.log('Exporting sales data...');
        
        const btn = document.querySelector('.export-btn');
        const originalText = btn.innerHTML;
        
        btn.innerHTML = `
            <svg class="btn-icon animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Exporting...
        `;
        
        setTimeout(() => {
            btn.innerHTML = originalText;
        }, 2000);
    }

    /**
     * Show/hide loading states
     */
    showLoading() {
        document.querySelectorAll('.kpi-value').forEach(el => {
            el.classList.add('loading-skeleton');
        });
        
        const tableBody = document.querySelector('.sales-table-body');
        if (tableBody) {
            tableBody.innerHTML = `
                <tr class="loading-row">
                    <td><div class="skeleton-text"></div></td>
                    <td><div class="skeleton-text"></div></td>
                    <td><div class="skeleton-text"></div></td>
                    <td><div class="skeleton-badge"></div></td>
                    <td><div class="skeleton-text"></div></td>
                </tr>
                <tr class="loading-row">
                    <td><div class="skeleton-text"></div></td>
                    <td><div class="skeleton-text"></div></td>
                    <td><div class="skeleton-text"></div></td>
                    <td><div class="skeleton-badge"></div></td>
                    <td><div class="skeleton-text"></div></td>
                </tr>
            `;
        }
    }

    hideLoading() {
        document.querySelectorAll('.loading-skeleton').forEach(el => {
            el.classList.remove('loading-skeleton');
        });
    }

    hideEmptyState() {
        const emptyState = document.querySelector('.empty-state');
        if (emptyState) emptyState.style.display = 'none';
        
        const tableWrapper = document.querySelector('.table-wrapper');
        if (tableWrapper) tableWrapper.style.display = 'block';
    }

    hideErrorState() {
        const errorState = document.querySelector('.error-state');
        if (errorState) errorState.style.display = 'none';
        
        const tableWrapper = document.querySelector('.table-wrapper');
        if (tableWrapper) tableWrapper.style.display = 'block';
    }

    /**
     * Handle keyboard navigation
     */
    handleKeyboard(e) {
        if (e.key === 'Escape') {
            const drawer = document.getElementById('order-drawer');
            if (drawer && drawer.style.display === 'flex') {
                this.closeOrderDrawer();
            }
        }
        
        if (e.key === 'Enter' && e.target.matches('.sales-table tbody tr')) {
            const orderId = e.target.dataset.orderId || 'ORD-001';
            this.openOrderDrawer(orderId);
        }
    }

    /**
     * Setup accessibility features
     */
    setupAccessibility() {
        document.querySelectorAll('.sales-table tbody tr').forEach(row => {
            row.setAttribute('role', 'button');
            row.setAttribute('tabindex', '0');
        });
    }

    /**
     * Generate dummy data for demonstration
     */
    generateDummyData() {
        return {
            kpis: {
                totalSales: 15420.50,
                transactions: 127,
                avgOrderValue: 121.42,
                topItem: {
                    name: 'Doro Wat',
                    quantity: 23
                }
            },
            sales: [
                {
                    orderId: 'ORD-001',
                    dateTime: new Date('2025-09-10T14:30:00'),
                    staff: 'John Doe',
                    paymentMethod: 'Cash',
                    totalAmount: 45.99
                },
                {
                    orderId: 'ORD-002',
                    dateTime: new Date('2025-09-10T15:15:00'),
                    staff: 'Jane Smith',
                    paymentMethod: 'Card',
                    totalAmount: 78.50
                },
                {
                    orderId: 'ORD-003',
                    dateTime: new Date('2025-09-10T16:00:00'),
                    staff: 'Mike Johnson',
                    paymentMethod: 'Mobile Money',
                    totalAmount: 32.25
                },
                {
                    orderId: 'ORD-004',
                    dateTime: new Date('2025-09-10T16:45:00'),
                    staff: 'John Doe',
                    paymentMethod: 'Bank Transfer',
                    totalAmount: 156.75
                },
                {
                    orderId: 'ORD-005',
                    dateTime: new Date('2025-09-10T17:30:00'),
                    staff: 'Jane Smith',
                    paymentMethod: 'Card',
                    totalAmount: 89.99
                }
            ]
        };
    }

    /**
     * Format currency value
     */
    formatCurrency(amount) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(amount);
    }

    /**
     * Format date and time
     */
    formatDateTime(date) {
        return new Intl.DateTimeFormat('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        }).format(date);
    }
}

/**
 * Initialize Sales Reports when DOM is ready
 */
document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('.sales-reports-container')) {
        new SalesReports();
    }
});

/**
 * Export for potential use in other modules
 */
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SalesReports;
}
