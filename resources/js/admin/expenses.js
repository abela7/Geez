/**
 * ==========================================================================
 * GEEZ RESTAURANT ADMIN - EXPENSES JAVASCRIPT
 * Interactive functionality for expense management interface
 * ==========================================================================
 */

/**
 * Expenses Management Module
 * Handles all interactions for the expenses page
 */
class ExpensesManager {
    constructor() {
        this.currentView = 'table';
        this.filtersCollapsed = true;
        this.isLoading = false;
        this.editingExpense = null;
        this.dummyData = this.generateDummyData();
        
        this.init();
    }

    /**
     * Initialize the expenses functionality
     */
    init() {
        this.bindEvents();
        this.loadInitialData();
        this.setupAccessibility();
        this.initializeForm();
    }

    /**
     * Bind all event listeners
     */
    bindEvents() {
        // Add expense button
        document.querySelectorAll('.add-expense-btn').forEach(btn => {
            btn.addEventListener('click', () => this.openExpenseModal());
        });

        // Filters toggle
        const filtersToggle = document.querySelector('.filters-toggle');
        if (filtersToggle) {
            filtersToggle.addEventListener('click', () => this.toggleFilters());
        }

        // Filter actions
        const applyFiltersBtn = document.querySelector('.apply-filters');
        const clearFiltersBtn = document.querySelector('.clear-filters');
        
        if (applyFiltersBtn) {
            applyFiltersBtn.addEventListener('click', () => this.applyFilters());
        }
        
        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', () => this.clearFilters());
        }

        // View toggle
        document.querySelectorAll('.view-toggle-btn').forEach(btn => {
            btn.addEventListener('click', (e) => this.handleViewToggle(e));
        });

        // Modal close
        const modalClose = document.querySelector('.modal-close');
        const modalOverlay = document.querySelector('.modal-overlay');
        const cancelBtn = document.querySelector('.cancel-btn');
        
        if (modalClose) {
            modalClose.addEventListener('click', () => this.closeExpenseModal());
        }
        
        if (modalOverlay) {
            modalOverlay.addEventListener('click', () => this.closeExpenseModal());
        }
        
        if (cancelBtn) {
            cancelBtn.addEventListener('click', () => this.closeExpenseModal());
        }

        // Form submission
        const expenseForm = document.getElementById('expense-form');
        if (expenseForm) {
            expenseForm.addEventListener('submit', (e) => this.handleFormSubmit(e));
        }

        // Export button
        const exportBtn = document.querySelector('.export-btn');
        if (exportBtn) {
            exportBtn.addEventListener('click', () => this.exportData());
        }

        // Retry button
        const retryBtn = document.querySelector('.retry-btn');
        if (retryBtn) {
            retryBtn.addEventListener('click', () => this.retryLoad());
        }

        // Keyboard navigation
        document.addEventListener('keydown', (e) => this.handleKeyboard(e));

        // Date range filter change
        const dateRangeFilter = document.getElementById('date-range-filter');
        if (dateRangeFilter) {
            dateRangeFilter.addEventListener('change', () => this.handleDateRangeChange());
        }

        // File upload
        const fileInput = document.getElementById('expense-receipt');
        if (fileInput) {
            fileInput.addEventListener('change', (e) => this.handleFileUpload(e));
        }
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
     * Handle view toggle between table and cards
     */
    handleViewToggle(e) {
        const btn = e.target.closest('.view-toggle-btn');
        const view = btn.dataset.view;
        
        // Update active state
        document.querySelectorAll('.view-toggle-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        
        this.currentView = view;
        this.switchView();
    }

    /**
     * Switch between table and card views
     */
    switchView() {
        const tableView = document.getElementById('table-view');
        const cardView = document.getElementById('card-view');
        
        if (this.currentView === 'table') {
            tableView.style.display = 'block';
            cardView.style.display = 'none';
        } else {
            tableView.style.display = 'none';
            cardView.style.display = 'block';
            this.renderCardView();
        }
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
        
        // Reset to default date range
        const dateRangeFilter = document.getElementById('date-range-filter');
        if (dateRangeFilter) {
            dateRangeFilter.value = 'month';
        }
        
        this.loadData();
    }

    /**
     * Get current filter values
     */
    getFilterValues() {
        const categories = Array.from(document.querySelectorAll('#category-filter option:checked'))
            .map(option => option.value);
        
        return {
            dateRange: document.getElementById('date-range-filter')?.value || 'month',
            categories,
            status: document.getElementById('status-filter')?.value || '',
            search: document.getElementById('search-filter')?.value || '',
            startDate: document.getElementById('start-date')?.value || '',
            endDate: document.getElementById('end-date')?.value || ''
        };
    }

    /**
     * Handle date range change
     */
    handleDateRangeChange() {
        const dateRange = document.getElementById('date-range-filter').value;
        const customDateRange = document.querySelector('.custom-date-range');
        
        if (customDateRange) {
            customDateRange.style.display = dateRange === 'custom' ? 'block' : 'none';
        }
    }

    /**
     * Open expense modal
     */
    openExpenseModal(expense = null) {
        const modal = document.getElementById('expense-modal');
        const modalTitle = document.getElementById('modal-title');
        
        this.editingExpense = expense;
        
        if (expense) {
            modalTitle.textContent = this.translate('finance.edit_expense');
            this.populateForm(expense);
        } else {
            modalTitle.textContent = this.translate('finance.add_expense');
            this.resetForm();
        }
        
        modal.style.display = 'flex';
        modal.setAttribute('aria-hidden', 'false');
        
        // Focus management
        const firstInput = modal.querySelector('input, select, textarea');
        if (firstInput) {
            firstInput.focus();
        }
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
    }

    /**
     * Close expense modal
     */
    closeExpenseModal() {
        const modal = document.getElementById('expense-modal');
        
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');
        
        this.editingExpense = null;
        this.resetForm();
        
        // Restore body scroll
        document.body.style.overflow = '';
    }

    /**
     * Initialize form with current date
     */
    initializeForm() {
        const dateInput = document.getElementById('expense-date');
        if (dateInput) {
            dateInput.value = new Date().toISOString().split('T')[0];
        }
    }

    /**
     * Reset form to initial state
     */
    resetForm() {
        const form = document.getElementById('expense-form');
        if (form) {
            form.reset();
            this.initializeForm();
        }
        
        // Reset file upload area
        const fileUploadArea = document.querySelector('.file-upload-area');
        if (fileUploadArea) {
            fileUploadArea.classList.remove('has-file');
        }
    }

    /**
     * Populate form with expense data
     */
    populateForm(expense) {
        document.getElementById('expense-description').value = expense.description || '';
        document.getElementById('expense-amount').value = expense.amount || '';
        document.getElementById('expense-date').value = expense.date || '';
        document.getElementById('expense-category').value = expense.category || '';
        document.getElementById('expense-payment-method').value = expense.paymentMethod || '';
        document.getElementById('expense-notes').value = expense.notes || '';
    }

    /**
     * Handle form submission
     */
    handleFormSubmit(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const expenseData = Object.fromEntries(formData.entries());
        
        console.log('Saving expense:', expenseData);
        
        // Show loading state
        const saveBtn = document.querySelector('.save-btn');
        const originalText = saveBtn.innerHTML;
        
        saveBtn.innerHTML = `
            <svg class="btn-icon animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            ${this.translate('finance.saving')}
        `;
        
        // Simulate API call
        setTimeout(() => {
            if (this.editingExpense) {
                this.updateExpense(expenseData);
            } else {
                this.addExpense(expenseData);
            }
            
            saveBtn.innerHTML = originalText;
            this.closeExpenseModal();
            this.loadData();
            
            this.showNotification(
                this.editingExpense ? 
                this.translate('finance.expense_updated') : 
                this.translate('finance.expense_added'), 
                'success'
            );
        }, 1500);
    }

    /**
     * Add new expense to dummy data
     */
    addExpense(expenseData) {
        const newExpense = {
            id: Date.now(),
            description: expenseData.description,
            amount: parseFloat(expenseData.amount),
            date: expenseData.date,
            category: expenseData.category,
            paymentMethod: expenseData.payment_method,
            status: 'pending',
            notes: expenseData.notes,
            createdAt: new Date()
        };
        
        this.dummyData.expenses.unshift(newExpense);
        this.updateSummaryData();
    }

    /**
     * Update existing expense
     */
    updateExpense(expenseData) {
        const index = this.dummyData.expenses.findIndex(e => e.id === this.editingExpense.id);
        if (index !== -1) {
            this.dummyData.expenses[index] = {
                ...this.dummyData.expenses[index],
                ...expenseData,
                amount: parseFloat(expenseData.amount),
                paymentMethod: expenseData.payment_method
            };
            this.updateSummaryData();
        }
    }

    /**
     * Delete expense
     */
    deleteExpense(expenseId) {
        if (confirm(this.translate('finance.confirm_delete_expense'))) {
            const index = this.dummyData.expenses.findIndex(e => e.id === expenseId);
            if (index !== -1) {
                this.dummyData.expenses.splice(index, 1);
                this.updateSummaryData();
                this.loadData();
                this.showNotification(this.translate('finance.expense_deleted'), 'success');
            }
        }
    }

    /**
     * Update summary data based on current expenses
     */
    updateSummaryData() {
        const expenses = this.dummyData.expenses;
        const currentMonth = new Date().getMonth();
        const currentYear = new Date().getFullYear();
        
        // Calculate totals
        const totalExpenses = expenses.reduce((sum, expense) => sum + expense.amount, 0);
        const monthlyExpenses = expenses
            .filter(expense => {
                const expenseDate = new Date(expense.date);
                return expenseDate.getMonth() === currentMonth && expenseDate.getFullYear() === currentYear;
            })
            .reduce((sum, expense) => sum + expense.amount, 0);
        
        const pendingApprovals = expenses.filter(expense => expense.status === 'pending').length;
        const pendingAmount = expenses
            .filter(expense => expense.status === 'pending')
            .reduce((sum, expense) => sum + expense.amount, 0);
        
        // Find top category
        const categoryTotals = {};
        expenses.forEach(expense => {
            categoryTotals[expense.category] = (categoryTotals[expense.category] || 0) + expense.amount;
        });
        
        const topCategory = Object.keys(categoryTotals).reduce((a, b) => 
            categoryTotals[a] > categoryTotals[b] ? a : b, Object.keys(categoryTotals)[0]
        );
        
        // Update summary data
        this.dummyData.summary = {
            totalExpenses,
            monthlyExpenses,
            pendingApprovals,
            pendingAmount,
            topCategory: {
                name: topCategory,
                amount: categoryTotals[topCategory] || 0
            }
        };
    }

    /**
     * Handle file upload
     */
    handleFileUpload(e) {
        const file = e.target.files[0];
        const uploadArea = document.querySelector('.file-upload-area');
        
        if (file) {
            uploadArea.classList.add('has-file');
            const fileName = uploadArea.querySelector('p');
            fileName.textContent = `Selected: ${file.name}`;
        } else {
            uploadArea.classList.remove('has-file');
            const fileName = uploadArea.querySelector('p');
            fileName.textContent = this.translate('finance.upload_receipt');
        }
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
        
        this.updateSummaryCards();
        this.updateTable();
        
        if (this.currentView === 'cards') {
            this.renderCardView();
        }
        
        setTimeout(() => {
            this.hideLoading();
            this.isLoading = false;
        }, 800);
    }

    /**
     * Update summary cards with data
     */
    updateSummaryCards() {
        const data = this.dummyData.summary;
        
        // Total Expenses
        const totalExpensesValue = document.querySelector('[data-value="total-expenses"]');
        if (totalExpensesValue) {
            totalExpensesValue.textContent = this.formatCurrency(data.totalExpenses);
            totalExpensesValue.classList.remove('loading-skeleton');
        }
        
        // Monthly Expenses
        const monthlyExpensesValue = document.querySelector('[data-value="monthly-expenses"]');
        if (monthlyExpensesValue) {
            monthlyExpensesValue.textContent = this.formatCurrency(data.monthlyExpenses);
            monthlyExpensesValue.classList.remove('loading-skeleton');
        }
        
        // Pending Approvals
        const pendingApprovalsValue = document.querySelector('[data-value="pending-approvals"]');
        if (pendingApprovalsValue) {
            pendingApprovalsValue.textContent = data.pendingApprovals.toString();
            pendingApprovalsValue.classList.remove('loading-skeleton');
        }
        
        const pendingAmount = document.querySelector('.pending-amount');
        if (pendingAmount) {
            pendingAmount.textContent = `${this.formatCurrency(data.pendingAmount)} ${this.translate('finance.pending')}`;
        }
        
        // Top Category
        const topCategoryValue = document.querySelector('[data-value="top-category"]');
        if (topCategoryValue) {
            topCategoryValue.textContent = this.translate(`finance.${data.topCategory.name.replace('-', '_')}`);
            topCategoryValue.classList.remove('loading-skeleton');
        }
        
        const categoryAmount = document.querySelector('.category-amount');
        if (categoryAmount) {
            categoryAmount.textContent = `${this.formatCurrency(data.topCategory.amount)} ${this.translate('finance.spent')}`;
        }
    }

    /**
     * Update table with data
     */
    updateTable() {
        const tableBody = document.querySelector('.expenses-table-body');
        if (!tableBody) return;
        
        tableBody.innerHTML = '';
        
        this.dummyData.expenses.forEach(expense => {
            const row = this.createTableRow(expense);
            tableBody.appendChild(row);
        });
        
        this.hideEmptyState();
        this.hideErrorState();
    }

    /**
     * Create a table row element
     */
    createTableRow(expense) {
        const row = document.createElement('tr');
        
        row.innerHTML = `
            <td>${this.formatDate(expense.date)}</td>
            <td>${expense.description}</td>
            <td><span class="category-badge ${expense.category}">${this.translate(`finance.${expense.category.replace('-', '_')}`)}</span></td>
            <td>${this.formatCurrency(expense.amount)}</td>
            <td><span class="status-badge ${expense.status}">${this.translate(`finance.${expense.status}`)}</span></td>
            <td>
                <div class="action-buttons">
                    <button type="button" class="action-btn edit" onclick="expensesManager.openExpenseModal(${JSON.stringify(expense).replace(/"/g, '&quot;')})" title="${this.translate('finance.edit')}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <button type="button" class="action-btn delete" onclick="expensesManager.deleteExpense(${expense.id})" title="${this.translate('finance.delete')}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </td>
        `;
        
        return row;
    }

    /**
     * Render card view
     */
    renderCardView() {
        const cardGrid = document.querySelector('.expense-cards-grid');
        if (!cardGrid) return;
        
        cardGrid.innerHTML = '';
        
        this.dummyData.expenses.forEach(expense => {
            const card = this.createExpenseCard(expense);
            cardGrid.appendChild(card);
        });
    }

    /**
     * Create expense card element
     */
    createExpenseCard(expense) {
        const card = document.createElement('div');
        card.className = 'expense-card';
        
        card.innerHTML = `
            <div class="expense-card-header">
                <div>
                    <div class="expense-card-title">${expense.description}</div>
                    <div class="expense-card-date">${this.formatDate(expense.date)}</div>
                </div>
                <div class="expense-card-amount">${this.formatCurrency(expense.amount)}</div>
            </div>
            <div class="expense-card-meta">
                <span class="category-badge ${expense.category}">${this.translate(`finance.${expense.category.replace('-', '_')}`)}</span>
                <span class="status-badge ${expense.status}">${this.translate(`finance.${expense.status}`)}</span>
            </div>
            <div class="expense-card-footer">
                <div class="action-buttons">
                    <button type="button" class="action-btn edit" onclick="expensesManager.openExpenseModal(${JSON.stringify(expense).replace(/"/g, '&quot;')})" title="${this.translate('finance.edit')}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <button type="button" class="action-btn delete" onclick="expensesManager.deleteExpense(${expense.id})" title="${this.translate('finance.delete')}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        `;
        
        return card;
    }

    /**
     * Export data functionality
     */
    exportData() {
        console.log('Exporting expenses data...');
        
        const btn = document.querySelector('.export-btn');
        const originalText = btn.innerHTML;
        
        btn.innerHTML = `
            <svg class="btn-icon animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            ${this.translate('finance.exporting')}
        `;
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            this.showNotification(this.translate('finance.expenses_exported'), 'success');
        }, 2000);
    }

    /**
     * Retry loading data
     */
    retryLoad() {
        this.hideErrorState();
        this.loadData();
    }

    /**
     * Show/hide loading states
     */
    showLoading() {
        document.querySelectorAll('.summary-value').forEach(el => {
            el.classList.add('loading-skeleton');
        });
        
        const tableBody = document.querySelector('.expenses-table-body');
        if (tableBody) {
            tableBody.innerHTML = `
                <tr class="loading-row">
                    <td><div class="skeleton-text"></div></td>
                    <td><div class="skeleton-text"></div></td>
                    <td><div class="skeleton-badge"></div></td>
                    <td><div class="skeleton-text"></div></td>
                    <td><div class="skeleton-badge"></div></td>
                    <td><div class="skeleton-actions"></div></td>
                </tr>
                <tr class="loading-row">
                    <td><div class="skeleton-text"></div></td>
                    <td><div class="skeleton-text"></div></td>
                    <td><div class="skeleton-badge"></div></td>
                    <td><div class="skeleton-text"></div></td>
                    <td><div class="skeleton-badge"></div></td>
                    <td><div class="skeleton-actions"></div></td>
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
            const modal = document.getElementById('expense-modal');
            if (modal && modal.style.display === 'flex') {
                this.closeExpenseModal();
            }
        }
    }

    /**
     * Setup accessibility features
     */
    setupAccessibility() {
        // Add ARIA labels to interactive elements
        document.querySelectorAll('.action-btn').forEach(btn => {
            btn.setAttribute('tabindex', '0');
        });
    }

    /**
     * Show notification
     */
    showNotification(message, type = 'info') {
        console.log(`${type.toUpperCase()}: ${message}`);
    }

    /**
     * Simple translation helper
     */
    translate(key) {
        const translations = {
            'finance.edit_expense': 'Edit Expense',
            'finance.add_expense': 'Add Expense',
            'finance.saving': 'Saving...',
            'finance.expense_updated': 'Expense updated successfully',
            'finance.expense_added': 'Expense added successfully',
            'finance.expense_deleted': 'Expense deleted successfully',
            'finance.confirm_delete_expense': 'Are you sure you want to delete this expense?',
            'finance.upload_receipt': 'Click to upload receipt',
            'finance.exporting': 'Exporting...',
            'finance.expenses_exported': 'Expenses exported successfully',
            'finance.edit': 'Edit',
            'finance.delete': 'Delete',
            'finance.pending': 'Pending',
            'finance.approved': 'Approved',
            'finance.paid': 'Paid',
            'finance.rejected': 'Rejected',
            'finance.spent': 'spent',
            'finance.food_supplies': 'Food Supplies',
            'finance.utilities': 'Utilities',
            'finance.rent': 'Rent',
            'finance.marketing': 'Marketing',
            'finance.equipment': 'Equipment',
            'finance.maintenance': 'Maintenance',
            'finance.other': 'Other'
        };
        
        return translations[key] || key;
    }

    /**
     * Generate dummy data for demonstration
     */
    generateDummyData() {
        const expenses = [
            {
                id: 1,
                description: 'Fresh vegetables and fruits',
                amount: 245.50,
                date: '2025-09-10',
                category: 'food-supplies',
                paymentMethod: 'card',
                status: 'paid',
                notes: 'Weekly grocery shopping for kitchen',
                createdAt: new Date('2025-09-10')
            },
            {
                id: 2,
                description: 'Electricity bill - September',
                amount: 180.00,
                date: '2025-09-09',
                category: 'utilities',
                paymentMethod: 'bank_transfer',
                status: 'pending',
                notes: 'Monthly electricity bill',
                createdAt: new Date('2025-09-09')
            },
            {
                id: 3,
                description: 'Social media advertising',
                amount: 150.00,
                date: '2025-09-08',
                category: 'marketing',
                paymentMethod: 'card',
                status: 'approved',
                notes: 'Facebook and Instagram ads campaign',
                createdAt: new Date('2025-09-08')
            },
            {
                id: 4,
                description: 'Kitchen equipment repair',
                amount: 320.75,
                date: '2025-09-07',
                category: 'maintenance',
                paymentMethod: 'cash',
                status: 'paid',
                notes: 'Oven repair and maintenance',
                createdAt: new Date('2025-09-07')
            },
            {
                id: 5,
                description: 'Monthly rent payment',
                amount: 2500.00,
                date: '2025-09-01',
                category: 'rent',
                paymentMethod: 'bank_transfer',
                status: 'paid',
                notes: 'September rent payment',
                createdAt: new Date('2025-09-01')
            }
        ];
        
        const summary = {
            totalExpenses: expenses.reduce((sum, expense) => sum + expense.amount, 0),
            monthlyExpenses: expenses.filter(expense => 
                new Date(expense.date).getMonth() === new Date().getMonth()
            ).reduce((sum, expense) => sum + expense.amount, 0),
            pendingApprovals: expenses.filter(expense => expense.status === 'pending').length,
            pendingAmount: expenses.filter(expense => expense.status === 'pending')
                .reduce((sum, expense) => sum + expense.amount, 0),
            topCategory: {
                name: 'rent',
                amount: 2500.00
            }
        };
        
        return { expenses, summary };
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
     * Format date
     */
    formatDate(date) {
        return new Intl.DateTimeFormat('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        }).format(new Date(date));
    }
}

/**
 * Initialize Expenses Manager when DOM is ready
 */
document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('.expenses-container')) {
        window.expensesManager = new ExpensesManager();
    }
});

/**
 * Export for potential use in other modules
 */
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ExpensesManager;
}
