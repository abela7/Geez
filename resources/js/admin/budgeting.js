/**
 * Budgeting Page JavaScript
 * Restaurant-OS Admin Panel
 * 
 * Handles budget management, category allocation, and budget vs actual comparison
 */

class BudgetingPage {
    constructor() {
        this.budgets = [];
        this.currentPeriod = 'current-month';
        this.currentView = 'categories';
        this.isLoading = false;
        this.currentBudget = null;
        this.allocationItems = [];
        
        this.init();
    }

    /**
     * Initialize the budgeting page
     */
    init() {
        this.bindEvents();
        this.generateDummyData();
        this.loadBudgetData();
        this.updateOverviewCards();
        this.setupFormValidation();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Period selector
        const periodSelect = document.getElementById('budget-period');
        if (periodSelect) {
            periodSelect.addEventListener('change', (e) => this.handlePeriodChange(e.target.value));
        }

        // View toggle buttons
        document.querySelectorAll('.view-toggle-btn').forEach(btn => {
            btn.addEventListener('click', (e) => this.handleViewToggle(e.target.dataset.view));
        });

        // Create budget buttons
        document.querySelectorAll('.create-budget-btn').forEach(btn => {
            btn.addEventListener('click', () => this.openBudgetModal());
        });

        // Export button
        const exportBtn = document.querySelector('.export-btn');
        if (exportBtn) {
            exportBtn.addEventListener('click', () => this.exportBudgetData());
        }

        // Modal events
        this.bindModalEvents();

        // Form events
        this.bindFormEvents();

        // Retry button
        const retryBtn = document.querySelector('.retry-btn');
        if (retryBtn) {
            retryBtn.addEventListener('click', () => this.loadBudgetData());
        }
    }

    /**
     * Bind modal events
     */
    bindModalEvents() {
        const modal = document.getElementById('budget-modal');
        const closeBtn = modal?.querySelector('.modal-close');
        const cancelBtn = modal?.querySelector('.cancel-btn');
        const overlay = modal?.querySelector('.modal-overlay');

        if (closeBtn) closeBtn.addEventListener('click', () => this.closeBudgetModal());
        if (cancelBtn) cancelBtn.addEventListener('click', () => this.closeBudgetModal());
        if (overlay) overlay.addEventListener('click', () => this.closeBudgetModal());

        // Escape key to close modal
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal?.style.display !== 'none') {
                this.closeBudgetModal();
            }
        });
    }

    /**
     * Bind form events
     */
    bindFormEvents() {
        const form = document.getElementById('budget-form');
        if (form) {
            form.addEventListener('submit', (e) => this.handleFormSubmit(e));
        }

        // Period type change
        const periodTypeSelect = document.getElementById('budget-period-type');
        if (periodTypeSelect) {
            periodTypeSelect.addEventListener('change', (e) => this.handlePeriodTypeChange(e.target.value));
        }

        // Total amount change
        const totalAmountInput = document.getElementById('budget-total-amount');
        if (totalAmountInput) {
            totalAmountInput.addEventListener('input', () => this.updateAllocationSummary());
        }

        // Add category button
        const addCategoryBtn = document.querySelector('.add-category-btn');
        if (addCategoryBtn) {
            addCategoryBtn.addEventListener('click', () => this.addAllocationItem());
        }
    }

    /**
     * Handle period change
     */
    handlePeriodChange(period) {
        this.currentPeriod = period;
        this.showCustomPeriodInputs(period === 'custom');
        this.loadBudgetData();
    }

    /**
     * Show/hide custom period inputs
     */
    showCustomPeriodInputs(show) {
        const customInputs = document.querySelector('.custom-period-inputs');
        if (customInputs) {
            customInputs.style.display = show ? 'flex' : 'none';
        }
    }

    /**
     * Handle view toggle
     */
    handleViewToggle(view) {
        this.currentView = view;
        
        // Update button states
        document.querySelectorAll('.view-toggle-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.view === view);
        });

        // Show/hide views
        const categoriesView = document.getElementById('categories-view');
        const comparisonView = document.getElementById('comparison-view');

        if (categoriesView && comparisonView) {
            categoriesView.style.display = view === 'categories' ? 'block' : 'none';
            comparisonView.style.display = view === 'comparison' ? 'block' : 'none';
        }

        if (view === 'comparison') {
            this.loadComparisonData();
        } else {
            this.loadCategoriesData();
        }
    }

    /**
     * Open budget modal
     */
    openBudgetModal(budget = null) {
        const modal = document.getElementById('budget-modal');
        const title = modal?.querySelector('.modal-title');
        const form = document.getElementById('budget-form');

        if (modal && title && form) {
            this.currentBudget = budget;
            
            if (budget) {
                title.textContent = 'Edit Budget';
                this.populateForm(budget);
            } else {
                title.textContent = 'Create Budget';
                form.reset();
                this.clearAllocationItems();
                this.addAllocationItem(); // Add initial allocation item
            }

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
     * Close budget modal
     */
    closeBudgetModal() {
        const modal = document.getElementById('budget-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
            this.currentBudget = null;
        }
    }

    /**
     * Handle form submit
     */
    handleFormSubmit(e) {
        e.preventDefault();
        
        if (this.validateForm()) {
            this.saveBudget();
        }
    }

    /**
     * Handle period type change in form
     */
    handlePeriodTypeChange(periodType) {
        const startDateInput = document.getElementById('budget-start-date');
        const endDateInput = document.getElementById('budget-end-date');
        
        if (startDateInput && endDateInput) {
            const today = new Date();
            let startDate, endDate;

            switch (periodType) {
                case 'monthly':
                    startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                    endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                    break;
                case 'quarterly':
                    const quarter = Math.floor(today.getMonth() / 3);
                    startDate = new Date(today.getFullYear(), quarter * 3, 1);
                    endDate = new Date(today.getFullYear(), (quarter + 1) * 3, 0);
                    break;
                case 'yearly':
                    startDate = new Date(today.getFullYear(), 0, 1);
                    endDate = new Date(today.getFullYear(), 11, 31);
                    break;
                default:
                    return;
            }

            startDateInput.value = this.formatDate(startDate);
            endDateInput.value = this.formatDate(endDate);
        }
    }

    /**
     * Add allocation item
     */
    addAllocationItem() {
        const template = document.getElementById('budget-allocation-template');
        const container = document.getElementById('budget-allocation-grid');
        
        if (template && container) {
            const clone = template.content.cloneNode(true);
            const item = clone.querySelector('.budget-allocation-item');
            
            // Add event listeners
            const removeBtn = item.querySelector('.remove-category-btn');
            const amountInput = item.querySelector('.amount-input');
            
            if (removeBtn) {
                removeBtn.addEventListener('click', () => this.removeAllocationItem(item));
            }
            
            if (amountInput) {
                amountInput.addEventListener('input', () => this.updateAllocationSummary());
            }
            
            container.appendChild(clone);
            this.updateAllocationSummary();
        }
    }

    /**
     * Remove allocation item
     */
    removeAllocationItem(item) {
        item.remove();
        this.updateAllocationSummary();
    }

    /**
     * Clear all allocation items
     */
    clearAllocationItems() {
        const container = document.getElementById('budget-allocation-grid');
        if (container) {
            container.innerHTML = '';
        }
    }

    /**
     * Update allocation summary
     */
    updateAllocationSummary() {
        const totalBudget = parseFloat(document.getElementById('budget-total-amount')?.value || 0);
        const amountInputs = document.querySelectorAll('.amount-input');
        
        let totalAllocated = 0;
        amountInputs.forEach(input => {
            totalAllocated += parseFloat(input.value || 0);
        });
        
        const remaining = totalBudget - totalAllocated;
        
        const totalAllocatedElement = document.getElementById('total-allocated');
        const remainingElement = document.getElementById('remaining-allocation');
        
        if (totalAllocatedElement) {
            totalAllocatedElement.textContent = this.formatCurrency(totalAllocated);
        }
        
        if (remainingElement) {
            remainingElement.textContent = this.formatCurrency(remaining);
            remainingElement.style.color = remaining < 0 ? 'var(--color-budget-danger)' : 'var(--color-text-primary)';
        }
    }

    /**
     * Validate form
     */
    validateForm() {
        const form = document.getElementById('budget-form');
        if (!form) return false;

        // Check required fields
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

        // Check allocation items
        const categorySelects = document.querySelectorAll('.category-select');
        const amountInputs = document.querySelectorAll('.amount-input');
        
        if (categorySelects.length === 0) {
            this.showNotification('Please add at least one budget category', 'error');
            isValid = false;
        }

        // Check for duplicate categories
        const selectedCategories = Array.from(categorySelects).map(select => select.value);
        const uniqueCategories = [...new Set(selectedCategories)];
        
        if (selectedCategories.length !== uniqueCategories.length) {
            this.showNotification('Duplicate categories are not allowed', 'error');
            isValid = false;
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
     * Save budget
     */
    saveBudget() {
        this.showLoading();
        
        // Simulate API call
        setTimeout(() => {
            const formData = this.getFormData();
            
            if (this.currentBudget) {
                // Update existing budget
                const index = this.budgets.findIndex(b => b.id === this.currentBudget.id);
                if (index !== -1) {
                    this.budgets[index] = { ...this.currentBudget, ...formData };
                }
                this.showNotification('Budget updated successfully', 'success');
            } else {
                // Create new budget
                const newBudget = {
                    id: Date.now(),
                    ...formData,
                    created_at: new Date().toISOString()
                };
                this.budgets.push(newBudget);
                this.showNotification('Budget created successfully', 'success');
            }
            
            this.hideLoading();
            this.closeBudgetModal();
            this.loadBudgetData();
            this.updateOverviewCards();
        }, 1000);
    }

    /**
     * Get form data
     */
    getFormData() {
        const form = document.getElementById('budget-form');
        if (!form) return {};

        const formData = new FormData(form);
        const data = {};
        
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }

        // Get allocation data
        const categorySelects = document.querySelectorAll('.category-select');
        const amountInputs = document.querySelectorAll('.amount-input');
        
        data.allocations = [];
        categorySelects.forEach((select, index) => {
            if (select.value && amountInputs[index]?.value) {
                data.allocations.push({
                    category: select.value,
                    amount: parseFloat(amountInputs[index].value)
                });
            }
        });

        return data;
    }

    /**
     * Populate form with budget data
     */
    populateForm(budget) {
        const form = document.getElementById('budget-form');
        if (!form || !budget) return;

        // Populate basic fields
        Object.keys(budget).forEach(key => {
            const input = form.querySelector(`[name="${key}"]`);
            if (input) {
                input.value = budget[key];
            }
        });

        // Populate allocations
        this.clearAllocationItems();
        if (budget.allocations && budget.allocations.length > 0) {
            budget.allocations.forEach(allocation => {
                this.addAllocationItem();
                const items = document.querySelectorAll('.budget-allocation-item');
                const lastItem = items[items.length - 1];
                
                if (lastItem) {
                    const categorySelect = lastItem.querySelector('.category-select');
                    const amountInput = lastItem.querySelector('.amount-input');
                    
                    if (categorySelect) categorySelect.value = allocation.category;
                    if (amountInput) amountInput.value = allocation.amount;
                }
            });
        } else {
            this.addAllocationItem();
        }

        this.updateAllocationSummary();
    }

    /**
     * Load budget data
     */
    loadBudgetData() {
        this.showLoading();
        this.hideEmptyState();
        this.hideErrorState();
        
        // Simulate API call
        setTimeout(() => {
            try {
                if (this.currentView === 'categories') {
                    this.loadCategoriesData();
                } else {
                    this.loadComparisonData();
                }
                this.hideLoading();
            } catch (error) {
                this.hideLoading();
                this.showErrorState();
            }
        }, 1000);
    }

    /**
     * Load categories data
     */
    loadCategoriesData() {
        const container = document.querySelector('.budget-categories-grid');
        if (!container) return;

        if (this.budgets.length === 0) {
            this.showEmptyState();
            return;
        }

        const categoriesData = this.generateCategoriesData();
        container.innerHTML = '';

        categoriesData.forEach(category => {
            const categoryCard = this.createCategoryCard(category);
            container.appendChild(categoryCard);
        });
    }

    /**
     * Load comparison data
     */
    loadComparisonData() {
        const tbody = document.querySelector('.budget-comparison-body');
        if (!tbody) return;

        if (this.budgets.length === 0) {
            this.showEmptyState();
            return;
        }

        const comparisonData = this.generateComparisonData();
        tbody.innerHTML = '';

        comparisonData.forEach(item => {
            const row = this.createComparisonRow(item);
            tbody.appendChild(row);
        });
    }

    /**
     * Create category card
     */
    createCategoryCard(category) {
        const card = document.createElement('div');
        card.className = `budget-category-card ${category.status}`;
        
        const progressPercentage = Math.min((category.actual / category.budgeted) * 100, 100);
        
        card.innerHTML = `
            <div class="category-header">
                <div class="category-name">
                    <div class="category-icon" style="background-color: ${category.color}20; color: ${category.color}">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    ${category.name}
                </div>
                <div class="category-menu">
                    <button class="category-menu-btn" onclick="budgetingPage.editCategory('${category.id}')">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="category-amounts">
                <div class="amount-row">
                    <span class="amount-label">Budgeted:</span>
                    <span class="amount-value">${this.formatCurrency(category.budgeted)}</span>
                </div>
                <div class="amount-row">
                    <span class="amount-label">Spent:</span>
                    <span class="amount-value ${category.status}">${this.formatCurrency(category.actual)}</span>
                </div>
                <div class="amount-row">
                    <span class="amount-label">Remaining:</span>
                    <span class="amount-value">${this.formatCurrency(category.remaining)}</span>
                </div>
            </div>
            <div class="category-progress">
                <div class="progress-bar">
                    <div class="progress-fill" style="width: ${progressPercentage}%; background-color: ${this.getProgressColor(progressPercentage)}"></div>
                </div>
                <div class="progress-text">${progressPercentage.toFixed(1)}% used</div>
            </div>
            <div class="category-status">
                <div class="status-indicator ${category.status}"></div>
                <span>${this.getStatusText(category.status)}</span>
            </div>
        `;
        
        return card;
    }

    /**
     * Create comparison row
     */
    createComparisonRow(item) {
        const row = document.createElement('tr');
        const variance = item.actual - item.budgeted;
        const variancePercentage = item.budgeted > 0 ? (variance / item.budgeted) * 100 : 0;
        const progressPercentage = Math.min((item.actual / item.budgeted) * 100, 100);
        
        row.innerHTML = `
            <td>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 0.75rem; height: 0.75rem; border-radius: 50%; background-color: ${item.color};"></div>
                    ${item.category}
                </div>
            </td>
            <td>${this.formatCurrency(item.budgeted)}</td>
            <td>${this.formatCurrency(item.actual)}</td>
            <td>
                <span class="variance-badge ${variance >= 0 ? 'negative' : 'positive'}">
                    ${variance >= 0 ? '+' : ''}${this.formatCurrency(variance)} (${variancePercentage.toFixed(1)}%)
                </span>
            </td>
            <td>
                <div class="table-progress">
                    <div class="progress-fill" style="width: ${progressPercentage}%; background-color: ${this.getProgressColor(progressPercentage)}"></div>
                </div>
            </td>
            <td>
                <button class="btn btn-sm btn-secondary" onclick="budgetingPage.editCategory('${item.id}')">
                    Edit
                </button>
            </td>
        `;
        
        return row;
    }

    /**
     * Get progress color based on percentage
     */
    getProgressColor(percentage) {
        if (percentage >= 100) return 'var(--color-progress-danger)';
        if (percentage >= 80) return 'var(--color-progress-warning)';
        return 'var(--color-progress-fill)';
    }

    /**
     * Get status text
     */
    getStatusText(status) {
        const statusMap = {
            'on-track': 'On Track',
            'warning': 'Warning',
            'over-budget': 'Over Budget'
        };
        return statusMap[status] || 'Unknown';
    }

    /**
     * Update overview cards
     */
    updateOverviewCards() {
        const overviewData = this.calculateOverviewData();
        
        // Update values
        this.updateCardValue('total-budget', overviewData.totalBudget);
        this.updateCardValue('spent-amount', overviewData.spentAmount);
        this.updateCardValue('remaining-budget', overviewData.remainingBudget);
        this.updateCardValue('budget-variance', overviewData.variance);
        
        // Update progress bar
        const progressFill = document.querySelector('[data-progress]');
        if (progressFill && overviewData.totalBudget > 0) {
            const percentage = Math.min((overviewData.spentAmount / overviewData.totalBudget) * 100, 100);
            progressFill.style.width = `${percentage}%`;
            progressFill.setAttribute('data-progress', percentage.toFixed(0));
        }
        
        // Update progress text
        const progressText = document.querySelector('.progress-text');
        if (progressText && overviewData.totalBudget > 0) {
            const percentage = (overviewData.spentAmount / overviewData.totalBudget) * 100;
            progressText.textContent = `${percentage.toFixed(1)}% of budget`;
        }
    }

    /**
     * Update card value
     */
    updateCardValue(cardType, value) {
        const element = document.querySelector(`[data-value="${cardType}"]`);
        if (element) {
            element.textContent = typeof value === 'number' ? this.formatCurrency(value) : value;
            element.classList.remove('loading-skeleton');
        }
    }

    /**
     * Calculate overview data
     */
    calculateOverviewData() {
        const totalBudget = this.budgets.reduce((sum, budget) => sum + (budget.total_amount || 0), 0);
        const spentAmount = this.budgets.reduce((sum, budget) => {
            return sum + (budget.allocations || []).reduce((catSum, allocation) => {
                return catSum + (allocation.actual || allocation.amount * 0.6); // Simulate spending
            }, 0);
        }, 0);
        
        return {
            totalBudget,
            spentAmount,
            remainingBudget: totalBudget - spentAmount,
            variance: spentAmount - totalBudget,
            daysRemaining: this.calculateDaysRemaining()
        };
    }

    /**
     * Calculate days remaining in current period
     */
    calculateDaysRemaining() {
        const today = new Date();
        let endDate;
        
        switch (this.currentPeriod) {
            case 'current-month':
                endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                break;
            case 'current-quarter':
                const quarter = Math.floor(today.getMonth() / 3);
                endDate = new Date(today.getFullYear(), (quarter + 1) * 3, 0);
                break;
            case 'current-year':
                endDate = new Date(today.getFullYear(), 11, 31);
                break;
            default:
                return 0;
        }
        
        const diffTime = endDate.getTime() - today.getTime();
        return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    }

    /**
     * Generate dummy data
     */
    generateDummyData() {
        this.budgets = [
            {
                id: 1,
                name: 'Q4 2024 Budget',
                period_type: 'quarterly',
                total_amount: 50000,
                start_date: '2024-10-01',
                end_date: '2024-12-31',
                description: 'Fourth quarter operational budget',
                allocations: [
                    { category: 'food-supplies', amount: 20000, actual: 12000 },
                    { category: 'utilities', amount: 8000, actual: 6500 },
                    { category: 'rent', amount: 12000, actual: 12000 },
                    { category: 'marketing', amount: 5000, actual: 3200 },
                    { category: 'equipment', amount: 3000, actual: 1800 },
                    { category: 'maintenance', amount: 2000, actual: 2400 }
                ]
            },
            {
                id: 2,
                name: 'December 2024 Budget',
                period_type: 'monthly',
                total_amount: 18000,
                start_date: '2024-12-01',
                end_date: '2024-12-31',
                description: 'December monthly budget',
                allocations: [
                    { category: 'food-supplies', amount: 7000, actual: 4200 },
                    { category: 'utilities', amount: 2500, actual: 2100 },
                    { category: 'rent', amount: 4000, actual: 4000 },
                    { category: 'marketing', amount: 2000, actual: 1500 },
                    { category: 'equipment', amount: 1500, actual: 800 },
                    { category: 'other', amount: 1000, actual: 600 }
                ]
            }
        ];
    }

    /**
     * Generate categories data
     */
    generateCategoriesData() {
        const categories = [];
        const categoryColors = {
            'food-supplies': '#f59e0b',
            'utilities': '#3b82f6',
            'rent': '#8b5cf6',
            'marketing': '#ec4899',
            'equipment': '#06b6d4',
            'maintenance': '#84cc16',
            'other': '#6b7280'
        };
        
        const categoryNames = {
            'food-supplies': 'Food Supplies',
            'utilities': 'Utilities',
            'rent': 'Rent',
            'marketing': 'Marketing',
            'equipment': 'Equipment',
            'maintenance': 'Maintenance',
            'other': 'Other'
        };
        
        // Aggregate data from all budgets
        const aggregated = {};
        this.budgets.forEach(budget => {
            if (budget.allocations) {
                budget.allocations.forEach(allocation => {
                    if (!aggregated[allocation.category]) {
                        aggregated[allocation.category] = {
                            budgeted: 0,
                            actual: 0
                        };
                    }
                    aggregated[allocation.category].budgeted += allocation.amount;
                    aggregated[allocation.category].actual += allocation.actual || 0;
                });
            }
        });
        
        Object.keys(aggregated).forEach(categoryKey => {
            const data = aggregated[categoryKey];
            const remaining = data.budgeted - data.actual;
            const percentage = data.budgeted > 0 ? (data.actual / data.budgeted) * 100 : 0;
            
            let status = 'on-track';
            if (percentage >= 100) status = 'over-budget';
            else if (percentage >= 80) status = 'warning';
            
            categories.push({
                id: categoryKey,
                name: categoryNames[categoryKey] || categoryKey,
                budgeted: data.budgeted,
                actual: data.actual,
                remaining: remaining,
                color: categoryColors[categoryKey] || '#6b7280',
                status: status
            });
        });
        
        return categories;
    }

    /**
     * Generate comparison data
     */
    generateComparisonData() {
        return this.generateCategoriesData().map(category => ({
            ...category,
            category: category.name
        }));
    }

    /**
     * Export budget data
     */
    exportBudgetData() {
        this.showLoading();
        
        // Simulate export
        setTimeout(() => {
            const data = {
                period: this.currentPeriod,
                budgets: this.budgets,
                overview: this.calculateOverviewData(),
                exported_at: new Date().toISOString()
            };
            
            const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `budget-report-${this.currentPeriod}-${new Date().toISOString().split('T')[0]}.json`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            
            this.hideLoading();
            this.showNotification('Budget data exported successfully', 'success');
        }, 1000);
    }

    /**
     * Edit category
     */
    editCategory(categoryId) {
        // Find budget containing this category
        const budget = this.budgets.find(b => 
            b.allocations && b.allocations.some(a => a.category === categoryId)
        );
        
        if (budget) {
            this.openBudgetModal(budget);
        }
    }

    /**
     * Show loading state
     */
    showLoading() {
        this.isLoading = true;
        const loadingElements = document.querySelectorAll('.loading-skeleton');
        loadingElements.forEach(el => el.classList.add('loading-skeleton'));
        
        // Show skeleton grid
        const skeletonGrid = document.querySelector('.loading-skeleton-grid');
        if (skeletonGrid) {
            skeletonGrid.style.display = 'grid';
        }
    }

    /**
     * Hide loading state
     */
    hideLoading() {
        this.isLoading = false;
        const loadingElements = document.querySelectorAll('.loading-skeleton');
        loadingElements.forEach(el => el.classList.remove('loading-skeleton'));
        
        // Hide skeleton grid
        const skeletonGrid = document.querySelector('.loading-skeleton-grid');
        if (skeletonGrid) {
            skeletonGrid.style.display = 'none';
        }
    }

    /**
     * Show empty state
     */
    showEmptyState() {
        const emptyState = document.querySelector('.empty-state');
        const categoriesGrid = document.querySelector('.budget-categories-grid');
        const comparisonTable = document.querySelector('.budget-comparison-table-wrapper');
        
        if (emptyState) emptyState.style.display = 'block';
        if (categoriesGrid) categoriesGrid.style.display = 'none';
        if (comparisonTable) comparisonTable.style.display = 'none';
    }

    /**
     * Hide empty state
     */
    hideEmptyState() {
        const emptyState = document.querySelector('.empty-state');
        const categoriesGrid = document.querySelector('.budget-categories-grid');
        const comparisonTable = document.querySelector('.budget-comparison-table-wrapper');
        
        if (emptyState) emptyState.style.display = 'none';
        if (categoriesGrid) categoriesGrid.style.display = 'grid';
        if (comparisonTable) comparisonTable.style.display = 'block';
    }

    /**
     * Show error state
     */
    showErrorState() {
        const errorState = document.querySelector('.error-state');
        const categoriesGrid = document.querySelector('.budget-categories-grid');
        const comparisonTable = document.querySelector('.budget-comparison-table-wrapper');
        
        if (errorState) errorState.style.display = 'block';
        if (categoriesGrid) categoriesGrid.style.display = 'none';
        if (comparisonTable) comparisonTable.style.display = 'none';
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
        const form = document.getElementById('budget-form');
        if (!form) return;

        // Real-time validation
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
     * Format date
     */
    formatDate(date) {
        return date.toISOString().split('T')[0];
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
    window.budgetingPage = new BudgetingPage();
});
