/**
 * Financial Reports Page JavaScript
 * Restaurant-OS Admin Panel
 * 
 * Handles financial report generation, scheduling, and management
 */

class FinancialReportsPage {
    constructor() {
        this.reports = [];
        this.currentFilters = {
            dateRange: 'this-month',
            reportType: 'all',
            department: 'all',
            format: 'pdf'
        };
        this.isLoading = false;
        this.currentReport = null;
        
        this.init();
    }

    /**
     * Initialize the financial reports page
     */
    init() {
        this.bindEvents();
        this.generateDummyData();
        this.loadFinancialOverview();
        this.loadRecentReports();
        this.setupFormValidation();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Filter events
        this.bindFilterEvents();
        
        // Report generation events
        this.bindReportEvents();
        
        // Modal events
        this.bindModalEvents();
        
        // Table events
        this.bindTableEvents();
        
        // Refresh button
        const refreshBtn = document.querySelector('.refresh-reports-btn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', () => this.refreshReports());
        }

        // Reset filters button
        const resetFiltersBtn = document.querySelector('.reset-filters-btn');
        if (resetFiltersBtn) {
            resetFiltersBtn.addEventListener('click', () => this.resetFilters());
        }

        // Retry button
        const retryBtn = document.querySelector('.retry-btn');
        if (retryBtn) {
            retryBtn.addEventListener('click', () => this.loadRecentReports());
        }
    }

    /**
     * Bind filter events
     */
    bindFilterEvents() {
        // Date range filter
        const dateRangeFilter = document.getElementById('report-date-range');
        if (dateRangeFilter) {
            dateRangeFilter.addEventListener('change', (e) => this.handleDateRangeChange(e.target.value));
        }

        // Report type filter
        const reportTypeFilter = document.getElementById('report-type');
        if (reportTypeFilter) {
            reportTypeFilter.addEventListener('change', (e) => this.handleReportTypeChange(e.target.value));
        }

        // Department filter
        const departmentFilter = document.getElementById('department-filter');
        if (departmentFilter) {
            departmentFilter.addEventListener('change', (e) => this.handleDepartmentChange(e.target.value));
        }

        // Format filter
        const formatFilter = document.getElementById('format-filter');
        if (formatFilter) {
            formatFilter.addEventListener('change', (e) => this.handleFormatChange(e.target.value));
        }

        // Custom date inputs
        const customStartDate = document.getElementById('custom-start-date');
        const customEndDate = document.getElementById('custom-end-date');
        
        if (customStartDate) {
            customStartDate.addEventListener('change', () => this.handleCustomDateChange());
        }
        
        if (customEndDate) {
            customEndDate.addEventListener('change', () => this.handleCustomDateChange());
        }
    }

    /**
     * Bind report generation events
     */
    bindReportEvents() {
        // Generate report buttons (main and modal)
        document.querySelectorAll('.generate-report-btn').forEach(btn => {
            btn.addEventListener('click', () => this.openReportModal());
        });

        // Schedule report button
        const scheduleBtn = document.querySelector('.schedule-report-btn');
        if (scheduleBtn) {
            scheduleBtn.addEventListener('click', () => this.openScheduleModal());
        }

        // Report type card generate buttons
        document.querySelectorAll('.generate-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const reportType = e.target.dataset.report;
                this.openReportModal(reportType);
            });
        });

        // Report type card preview buttons
        document.querySelectorAll('.preview-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const reportType = e.target.dataset.report;
                this.previewReport(reportType);
            });
        });
    }

    /**
     * Bind modal events
     */
    bindModalEvents() {
        // Report modal
        const reportModal = document.getElementById('report-modal');
        const reportCloseBtn = reportModal?.querySelector('.modal-close');
        const reportCancelBtn = reportModal?.querySelector('.cancel-btn');
        const reportOverlay = reportModal?.querySelector('.modal-overlay');

        if (reportCloseBtn) reportCloseBtn.addEventListener('click', () => this.closeReportModal());
        if (reportCancelBtn) reportCancelBtn.addEventListener('click', () => this.closeReportModal());
        if (reportOverlay) reportOverlay.addEventListener('click', () => this.closeReportModal());

        // Schedule modal
        const scheduleModal = document.getElementById('schedule-modal');
        const scheduleCloseBtn = scheduleModal?.querySelector('.modal-close');
        const scheduleCancelBtn = scheduleModal?.querySelector('.cancel-btn');
        const scheduleOverlay = scheduleModal?.querySelector('.modal-overlay');

        if (scheduleCloseBtn) scheduleCloseBtn.addEventListener('click', () => this.closeScheduleModal());
        if (scheduleCancelBtn) scheduleCancelBtn.addEventListener('click', () => this.closeScheduleModal());
        if (scheduleOverlay) scheduleOverlay.addEventListener('click', () => this.closeScheduleModal());

        // Form submissions
        const reportForm = document.getElementById('report-form');
        const scheduleForm = document.getElementById('schedule-form');

        if (reportForm) {
            reportForm.addEventListener('submit', (e) => this.handleReportFormSubmit(e));
        }

        if (scheduleForm) {
            scheduleForm.addEventListener('submit', (e) => this.handleScheduleFormSubmit(e));
        }

        // Modal date range change
        const modalDateRange = document.getElementById('modal-date-range');
        if (modalDateRange) {
            modalDateRange.addEventListener('change', (e) => this.handleModalDateRangeChange(e.target.value));
        }

        // Escape key to close modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeReportModal();
                this.closeScheduleModal();
            }
        });
    }

    /**
     * Bind table events
     */
    bindTableEvents() {
        // Table action buttons will be bound when table is populated
    }

    /**
     * Handle date range change
     */
    handleDateRangeChange(dateRange) {
        this.currentFilters.dateRange = dateRange;
        this.showCustomDateInputs(dateRange === 'custom');
        this.applyFilters();
    }

    /**
     * Handle report type change
     */
    handleReportTypeChange(reportType) {
        this.currentFilters.reportType = reportType;
        this.applyFilters();
    }

    /**
     * Handle department change
     */
    handleDepartmentChange(department) {
        this.currentFilters.department = department;
        this.applyFilters();
    }

    /**
     * Handle format change
     */
    handleFormatChange(format) {
        this.currentFilters.format = format;
    }

    /**
     * Handle custom date change
     */
    handleCustomDateChange() {
        if (this.currentFilters.dateRange === 'custom') {
            this.applyFilters();
        }
    }

    /**
     * Handle modal date range change
     */
    handleModalDateRangeChange(dateRange) {
        const customDates = document.querySelectorAll('.modal-custom-dates');
        customDates.forEach(element => {
            element.style.display = dateRange === 'custom' ? 'block' : 'none';
        });
    }

    /**
     * Show/hide custom date inputs
     */
    showCustomDateInputs(show) {
        const customDateRange = document.querySelector('.custom-date-range');
        if (customDateRange) {
            customDateRange.style.display = show ? 'block' : 'none';
        }
    }

    /**
     * Apply filters
     */
    applyFilters() {
        this.loadRecentReports();
        this.loadFinancialOverview();
    }

    /**
     * Reset filters
     */
    resetFilters() {
        this.currentFilters = {
            dateRange: 'this-month',
            reportType: 'all',
            department: 'all',
            format: 'pdf'
        };

        // Reset form values
        const dateRangeFilter = document.getElementById('report-date-range');
        const reportTypeFilter = document.getElementById('report-type');
        const departmentFilter = document.getElementById('department-filter');
        const formatFilter = document.getElementById('format-filter');

        if (dateRangeFilter) dateRangeFilter.value = 'this-month';
        if (reportTypeFilter) reportTypeFilter.value = 'all';
        if (departmentFilter) departmentFilter.value = 'all';
        if (formatFilter) formatFilter.value = 'pdf';

        this.showCustomDateInputs(false);
        this.applyFilters();
    }

    /**
     * Open report modal
     */
    openReportModal(reportType = null) {
        const modal = document.getElementById('report-modal');
        const form = document.getElementById('report-form');
        
        if (modal && form) {
            // Pre-fill report type if provided
            if (reportType) {
                const reportTypeSelect = document.getElementById('modal-report-type');
                if (reportTypeSelect) {
                    reportTypeSelect.value = reportType;
                }
            }

            // Set current filters
            const dateRangeSelect = document.getElementById('modal-date-range');
            const formatSelect = document.getElementById('modal-format');
            
            if (dateRangeSelect) dateRangeSelect.value = this.currentFilters.dateRange;
            if (formatSelect) formatSelect.value = this.currentFilters.format;

            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
            
            // Focus first input
            const firstInput = form.querySelector('input, select');
            if (firstInput) {
                setTimeout(() => firstInput.focus(), 100);
            }
        }
    }

    /**
     * Close report modal
     */
    closeReportModal() {
        const modal = document.getElementById('report-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
        }
    }

    /**
     * Open schedule modal
     */
    openScheduleModal() {
        const modal = document.getElementById('schedule-modal');
        const form = document.getElementById('schedule-form');
        
        if (modal && form) {
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
            
            // Focus first input
            const firstInput = form.querySelector('input, select');
            if (firstInput) {
                setTimeout(() => firstInput.focus(), 100);
            }
        }
    }

    /**
     * Close schedule modal
     */
    closeScheduleModal() {
        const modal = document.getElementById('schedule-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
        }
    }

    /**
     * Handle report form submit
     */
    handleReportFormSubmit(e) {
        e.preventDefault();
        
        if (this.validateReportForm()) {
            this.generateReport();
        }
    }

    /**
     * Handle schedule form submit
     */
    handleScheduleFormSubmit(e) {
        e.preventDefault();
        
        if (this.validateScheduleForm()) {
            this.scheduleReport();
        }
    }

    /**
     * Validate report form
     */
    validateReportForm() {
        const form = document.getElementById('report-form');
        if (!form) return false;

        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                this.showFieldError(field, 'This field is required');
                isValid = false;
            } else {
                this.clearFieldError(field);
            }
        });

        return isValid;
    }

    /**
     * Validate schedule form
     */
    validateScheduleForm() {
        const form = document.getElementById('schedule-form');
        if (!form) return false;

        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                this.showFieldError(field, 'This field is required');
                isValid = false;
            } else {
                this.clearFieldError(field);
            }
        });

        // Validate email format
        const emailField = document.getElementById('schedule-recipients');
        if (emailField && emailField.value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const emails = emailField.value.split(',').map(email => email.trim());
            
            for (let email of emails) {
                if (!emailRegex.test(email)) {
                    this.showFieldError(emailField, 'Please enter valid email addresses');
                    isValid = false;
                    break;
                }
            }
        }

        return isValid;
    }

    /**
     * Show field error
     */
    showFieldError(field, message) {
        this.clearFieldError(field);
        
        field.classList.add('error');
        const errorElement = document.createElement('div');
        errorElement.className = 'field-error';
        errorElement.textContent = message;
        
        field.parentNode.appendChild(errorElement);
    }

    /**
     * Clear field error
     */
    clearFieldError(field) {
        field.classList.remove('error');
        const errorElement = field.parentNode.querySelector('.field-error');
        if (errorElement) {
            errorElement.remove();
        }
    }

    /**
     * Generate report
     */
    generateReport() {
        this.showLoading();
        
        // Simulate report generation
        setTimeout(() => {
            const formData = this.getReportFormData();
            
            const newReport = {
                id: Date.now(),
                name: formData.name,
                type: formData.type,
                period: this.getFormattedPeriod(formData.date_range),
                generated_date: new Date().toISOString(),
                status: 'completed',
                format: formData.format,
                file_size: this.generateRandomFileSize(),
                download_url: '#'
            };
            
            this.reports.unshift(newReport);
            
            this.hideLoading();
            this.closeReportModal();
            this.loadRecentReports();
            this.showNotification('Report generated successfully', 'success');
        }, 2000);
    }

    /**
     * Schedule report
     */
    scheduleReport() {
        this.showLoading();
        
        // Simulate report scheduling
        setTimeout(() => {
            const formData = this.getScheduleFormData();
            
            this.hideLoading();
            this.closeScheduleModal();
            this.showNotification(`Report scheduled successfully. Recipients: ${formData.recipients}`, 'success');
        }, 1000);
    }

    /**
     * Preview report
     */
    previewReport(reportType) {
        this.showLoading();
        
        // Simulate report preview
        setTimeout(() => {
            this.hideLoading();
            this.showNotification(`Preview for ${this.getReportTypeName(reportType)} is being prepared`, 'info');
        }, 1000);
    }

    /**
     * Get report form data
     */
    getReportFormData() {
        const form = document.getElementById('report-form');
        if (!form) return {};

        const formData = new FormData(form);
        const data = {};
        
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }

        return data;
    }

    /**
     * Get schedule form data
     */
    getScheduleFormData() {
        const form = document.getElementById('schedule-form');
        if (!form) return {};

        const formData = new FormData(form);
        const data = {};
        
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }

        return data;
    }

    /**
     * Load financial overview
     */
    loadFinancialOverview() {
        this.showOverviewLoading();
        
        // Simulate API call
        setTimeout(() => {
            const overviewData = this.generateOverviewData();
            this.updateOverviewCards(overviewData);
            this.hideOverviewLoading();
        }, 1000);
    }

    /**
     * Load recent reports
     */
    loadRecentReports() {
        this.showTableLoading();
        this.hideEmptyState();
        this.hideErrorState();
        
        // Simulate API call
        setTimeout(() => {
            try {
                const filteredReports = this.getFilteredReports();
                this.populateReportsTable(filteredReports);
                this.hideTableLoading();
                
                if (filteredReports.length === 0) {
                    this.showEmptyState();
                }
            } catch (error) {
                this.hideTableLoading();
                this.showErrorState();
            }
        }, 1000);
    }

    /**
     * Refresh reports
     */
    refreshReports() {
        this.loadRecentReports();
        this.loadFinancialOverview();
    }

    /**
     * Get filtered reports
     */
    getFilteredReports() {
        let filtered = [...this.reports];
        
        if (this.currentFilters.reportType !== 'all') {
            filtered = filtered.filter(report => report.type === this.currentFilters.reportType);
        }
        
        // Additional filtering logic can be added here
        
        return filtered;
    }

    /**
     * Populate reports table
     */
    populateReportsTable(reports) {
        const tbody = document.querySelector('.reports-table-body');
        if (!tbody) return;

        tbody.innerHTML = '';

        reports.forEach(report => {
            const row = this.createReportRow(report);
            tbody.appendChild(row);
        });
    }

    /**
     * Create report row
     */
    createReportRow(report) {
        const row = document.createElement('tr');
        
        row.innerHTML = `
            <td>
                <div style="font-weight: 600;">${report.name}</div>
                <div style="font-size: 0.75rem; color: var(--color-text-secondary);">${report.file_size}</div>
            </td>
            <td>
                <span class="report-type-badge ${report.type}">${this.getReportTypeName(report.type)}</span>
            </td>
            <td>${report.period}</td>
            <td>${this.formatDateTime(report.generated_date)}</td>
            <td>
                <span class="status-badge ${report.status}">${this.getStatusName(report.status)}</span>
            </td>
            <td>
                <div class="table-actions">
                    <button class="btn btn-sm btn-secondary download-btn" data-report-id="${report.id}">
                        Download
                    </button>
                    <button class="btn btn-sm btn-secondary view-btn" data-report-id="${report.id}">
                        View
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-report-id="${report.id}">
                        Delete
                    </button>
                </div>
            </td>
        `;

        // Bind action buttons
        const downloadBtn = row.querySelector('.download-btn');
        const viewBtn = row.querySelector('.view-btn');
        const deleteBtn = row.querySelector('.delete-btn');

        if (downloadBtn) {
            downloadBtn.addEventListener('click', () => this.downloadReport(report.id));
        }

        if (viewBtn) {
            viewBtn.addEventListener('click', () => this.viewReport(report.id));
        }

        if (deleteBtn) {
            deleteBtn.addEventListener('click', () => this.deleteReport(report.id));
        }

        return row;
    }

    /**
     * Download report
     */
    downloadReport(reportId) {
        const report = this.reports.find(r => r.id === reportId);
        if (report) {
            this.showNotification(`Downloading ${report.name}...`, 'info');
            // Simulate download
            setTimeout(() => {
                this.showNotification('Download completed', 'success');
            }, 1500);
        }
    }

    /**
     * View report
     */
    viewReport(reportId) {
        const report = this.reports.find(r => r.id === reportId);
        if (report) {
            this.showNotification(`Opening ${report.name}...`, 'info');
        }
    }

    /**
     * Delete report
     */
    deleteReport(reportId) {
        if (confirm('Are you sure you want to delete this report?')) {
            this.reports = this.reports.filter(r => r.id !== reportId);
            this.loadRecentReports();
            this.showNotification('Report deleted successfully', 'success');
        }
    }

    /**
     * Update overview cards
     */
    updateOverviewCards(data) {
        this.updateCardValue('revenue', data.revenue);
        this.updateCardValue('expenses', data.expenses);
        this.updateCardValue('profit', data.profit);
        this.updateCardValue('margin', data.margin + '%');
    }

    /**
     * Update card value
     */
    updateCardValue(metric, value) {
        const element = document.querySelector(`[data-value="${metric}"]`);
        if (element) {
            element.textContent = typeof value === 'number' ? this.formatCurrency(value) : value;
            element.classList.remove('loading-skeleton');
        }
    }

    /**
     * Generate overview data
     */
    generateOverviewData() {
        const revenue = 125000 + Math.random() * 50000;
        const expenses = 85000 + Math.random() * 30000;
        const profit = revenue - expenses;
        const margin = ((profit / revenue) * 100);
        
        return {
            revenue: revenue,
            expenses: expenses,
            profit: profit,
            margin: margin.toFixed(1)
        };
    }

    /**
     * Generate dummy data
     */
    generateDummyData() {
        const reportTypes = ['income-statement', 'balance-sheet', 'cash-flow', 'profit-loss', 'expense-analysis', 'tax-reports'];
        const statuses = ['completed', 'processing', 'failed', 'scheduled'];
        const periods = ['December 2024', 'Q4 2024', 'November 2024', 'October 2024', 'Q3 2024'];
        
        this.reports = [];
        
        for (let i = 0; i < 15; i++) {
            const type = reportTypes[Math.floor(Math.random() * reportTypes.length)];
            const status = statuses[Math.floor(Math.random() * statuses.length)];
            const period = periods[Math.floor(Math.random() * periods.length)];
            
            this.reports.push({
                id: i + 1,
                name: `${this.getReportTypeName(type)} - ${period}`,
                type: type,
                period: period,
                generated_date: new Date(Date.now() - Math.random() * 30 * 24 * 60 * 60 * 1000).toISOString(),
                status: status,
                format: 'pdf',
                file_size: this.generateRandomFileSize(),
                download_url: '#'
            });
        }
    }

    /**
     * Generate random file size
     */
    generateRandomFileSize() {
        const sizes = ['1.2 MB', '856 KB', '2.1 MB', '743 KB', '1.8 MB', '1.1 MB'];
        return sizes[Math.floor(Math.random() * sizes.length)];
    }

    /**
     * Get report type name
     */
    getReportTypeName(type) {
        const names = {
            'income-statement': 'Income Statement',
            'balance-sheet': 'Balance Sheet',
            'cash-flow': 'Cash Flow',
            'profit-loss': 'Profit & Loss',
            'expense-analysis': 'Expense Analysis',
            'tax-reports': 'Tax Reports'
        };
        return names[type] || type;
    }

    /**
     * Get status name
     */
    getStatusName(status) {
        const names = {
            'completed': 'Completed',
            'processing': 'Processing',
            'failed': 'Failed',
            'scheduled': 'Scheduled'
        };
        return names[status] || status;
    }

    /**
     * Get formatted period
     */
    getFormattedPeriod(dateRange) {
        const periods = {
            'this-month': 'December 2024',
            'last-month': 'November 2024',
            'this-quarter': 'Q4 2024',
            'last-quarter': 'Q3 2024',
            'this-year': '2024',
            'last-year': '2023',
            'custom': 'Custom Range'
        };
        return periods[dateRange] || dateRange;
    }

    /**
     * Show loading state
     */
    showLoading() {
        this.isLoading = true;
    }

    /**
     * Hide loading state
     */
    hideLoading() {
        this.isLoading = false;
    }

    /**
     * Show overview loading
     */
    showOverviewLoading() {
        const elements = document.querySelectorAll('[data-value]');
        elements.forEach(el => el.classList.add('loading-skeleton'));
    }

    /**
     * Hide overview loading
     */
    hideOverviewLoading() {
        const elements = document.querySelectorAll('[data-value]');
        elements.forEach(el => el.classList.remove('loading-skeleton'));
    }

    /**
     * Show table loading
     */
    showTableLoading() {
        const loadingRows = document.querySelectorAll('.loading-row');
        loadingRows.forEach(row => row.style.display = 'table-row');
        
        const tableWrapper = document.querySelector('.reports-table-wrapper');
        if (tableWrapper) tableWrapper.style.display = 'block';
    }

    /**
     * Hide table loading
     */
    hideTableLoading() {
        const loadingRows = document.querySelectorAll('.loading-row');
        loadingRows.forEach(row => row.style.display = 'none');
    }

    /**
     * Show empty state
     */
    showEmptyState() {
        const emptyState = document.querySelector('.empty-state');
        const tableWrapper = document.querySelector('.reports-table-wrapper');
        
        if (emptyState) emptyState.style.display = 'block';
        if (tableWrapper) tableWrapper.style.display = 'none';
    }

    /**
     * Hide empty state
     */
    hideEmptyState() {
        const emptyState = document.querySelector('.empty-state');
        if (emptyState) emptyState.style.display = 'none';
    }

    /**
     * Show error state
     */
    showErrorState() {
        const errorState = document.querySelector('.error-state');
        const tableWrapper = document.querySelector('.reports-table-wrapper');
        
        if (errorState) errorState.style.display = 'block';
        if (tableWrapper) tableWrapper.style.display = 'none';
    }

    /**
     * Hide error state
     */
    hideErrorState() {
        const errorState = document.querySelector('.error-state');
        if (errorState) errorState.style.display = 'none';
    }

    /**
     * Show notification
     */
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

    /**
     * Setup form validation
     */
    setupFormValidation() {
        const forms = [document.getElementById('report-form'), document.getElementById('schedule-form')];
        
        forms.forEach(form => {
            if (!form) return;

            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('blur', () => {
                    if (input.hasAttribute('required') && !input.value.trim()) {
                        this.showFieldError(input, 'This field is required');
                    } else {
                        this.clearFieldError(input);
                    }
                });
            });
        });
    }

    /**
     * Format currency
     */
    formatCurrency(amount) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(amount);
    }

    /**
     * Format date time
     */
    formatDateTime(dateString) {
        return new Date(dateString).toLocaleString();
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.financialReportsPage = new FinancialReportsPage();
});
