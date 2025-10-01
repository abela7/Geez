/**
 * Finance Settings Page JavaScript
 * Restaurant-OS Admin Panel
 * 
 * Handles finance and sales settings management
 */

class FinanceSettingsPage {
    constructor() {
        this.categories = [];
        this.methods = [];
        this.settings = {};
        this.isLoading = false;
        this.currentCategory = null;
        this.currentMethod = null;
        
        this.init();
    }

    /**
     * Initialize the finance settings page
     */
    init() {
        this.bindEvents();
        this.generateDummyData();
        this.loadCategories();
        this.loadMethods();
        this.loadDefaults();
        this.setupFormValidation();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Category events
        this.bindCategoryEvents();
        
        // Method events
        this.bindMethodEvents();
        
        // Modal events
        this.bindModalEvents();
        
        // Form events
        this.bindFormEvents();
        
        // Settings events
        this.bindSettingsEvents();
    }

    /**
     * Bind category events
     */
    bindCategoryEvents() {
        // Add category buttons
        document.querySelectorAll('.add-category-btn').forEach(btn => {
            btn.addEventListener('click', () => this.openCategoryModal());
        });
    }

    /**
     * Bind method events
     */
    bindMethodEvents() {
        // Add method button
        const addMethodBtn = document.querySelector('.add-method-btn');
        if (addMethodBtn) {
            addMethodBtn.addEventListener('click', () => this.openMethodModal());
        }
    }

    /**
     * Bind modal events
     */
    bindModalEvents() {
        // Category modal
        const categoryModal = document.getElementById('category-modal');
        const categoryCloseBtn = categoryModal?.querySelector('.modal-close');
        const categoryCancelBtn = categoryModal?.querySelector('.cancel-btn');
        const categoryOverlay = categoryModal?.querySelector('.modal-overlay');

        if (categoryCloseBtn) categoryCloseBtn.addEventListener('click', () => this.closeCategoryModal());
        if (categoryCancelBtn) categoryCancelBtn.addEventListener('click', () => this.closeCategoryModal());
        if (categoryOverlay) categoryOverlay.addEventListener('click', () => this.closeCategoryModal());

        // Method modal
        const methodModal = document.getElementById('method-modal');
        const methodCloseBtn = methodModal?.querySelector('.modal-close');
        const methodCancelBtn = methodModal?.querySelector('.cancel-btn');
        const methodOverlay = methodModal?.querySelector('.modal-overlay');

        if (methodCloseBtn) methodCloseBtn.addEventListener('click', () => this.closeMethodModal());
        if (methodCancelBtn) methodCancelBtn.addEventListener('click', () => this.closeMethodModal());
        if (methodOverlay) methodOverlay.addEventListener('click', () => this.closeMethodModal());

        // Escape key to close modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeCategoryModal();
                this.closeMethodModal();
            }
        });
    }

    /**
     * Bind form events
     */
    bindFormEvents() {
        // Category form
        const categoryForm = document.getElementById('category-form');
        if (categoryForm) {
            categoryForm.addEventListener('submit', (e) => this.handleCategoryFormSubmit(e));
        }

        // Method form
        const methodForm = document.getElementById('method-form');
        if (methodForm) {
            methodForm.addEventListener('submit', (e) => this.handleMethodFormSubmit(e));
        }

        // Color preset buttons
        document.querySelectorAll('.color-preset').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const color = e.target.dataset.color;
                const colorInput = document.getElementById('category-color');
                if (colorInput) {
                    colorInput.value = color;
                }
            });
        });
    }

    /**
     * Bind settings events
     */
    bindSettingsEvents() {
        // Save all button
        const saveAllBtn = document.querySelector('.save-all-btn');
        if (saveAllBtn) {
            saveAllBtn.addEventListener('click', () => this.saveAllSettings());
        }

        // Save defaults button
        const saveDefaultsBtn = document.querySelector('.save-defaults-btn');
        if (saveDefaultsBtn) {
            saveDefaultsBtn.addEventListener('click', () => this.saveDefaults());
        }

        // Export settings button
        const exportBtn = document.querySelector('.export-settings-btn');
        if (exportBtn) {
            exportBtn.addEventListener('click', () => this.exportSettings());
        }
    }

    /**
     * Open category modal
     */
    openCategoryModal(category = null) {
        const modal = document.getElementById('category-modal');
        const title = modal?.querySelector('.modal-title');
        const form = document.getElementById('category-form');

        if (modal && title && form) {
            this.currentCategory = category;
            
            if (category) {
                title.textContent = 'Edit Category';
                this.populateCategoryForm(category);
            } else {
                title.textContent = 'Create Category';
                form.reset();
                // Set default color
                const colorInput = document.getElementById('category-color');
                if (colorInput) colorInput.value = '#3b82f6';
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
     * Close category modal
     */
    closeCategoryModal() {
        const modal = document.getElementById('category-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
            this.currentCategory = null;
        }
    }

    /**
     * Open method modal
     */
    openMethodModal(method = null) {
        const modal = document.getElementById('method-modal');
        const title = modal?.querySelector('.modal-title');
        const form = document.getElementById('method-form');

        if (modal && title && form) {
            this.currentMethod = method;
            
            if (method) {
                title.textContent = 'Edit Method';
                this.populateMethodForm(method);
            } else {
                title.textContent = 'Create Method';
                form.reset();
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
     * Close method modal
     */
    closeMethodModal() {
        const modal = document.getElementById('method-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
            this.currentMethod = null;
        }
    }

    /**
     * Handle category form submit
     */
    handleCategoryFormSubmit(e) {
        e.preventDefault();
        
        if (this.validateCategoryForm()) {
            this.saveCategory();
        }
    }

    /**
     * Handle method form submit
     */
    handleMethodFormSubmit(e) {
        e.preventDefault();
        
        if (this.validateMethodForm()) {
            this.saveMethod();
        }
    }

    /**
     * Validate category form
     */
    validateCategoryForm() {
        const form = document.getElementById('category-form');
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
     * Validate method form
     */
    validateMethodForm() {
        const form = document.getElementById('method-form');
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
     * Save category
     */
    saveCategory() {
        this.showLoading();
        
        // Simulate API call
        setTimeout(() => {
            const formData = this.getCategoryFormData();
            
            if (this.currentCategory) {
                // Update existing category
                const index = this.categories.findIndex(c => c.id === this.currentCategory.id);
                if (index !== -1) {
                    this.categories[index] = { ...this.currentCategory, ...formData };
                }
                this.showNotification('Category updated successfully', 'success');
            } else {
                // Create new category
                const newCategory = {
                    id: Date.now(),
                    ...formData,
                    created_at: new Date().toISOString()
                };
                this.categories.push(newCategory);
                this.showNotification('Category created successfully', 'success');
            }
            
            this.hideLoading();
            this.closeCategoryModal();
            this.loadCategories();
        }, 1000);
    }

    /**
     * Save method
     */
    saveMethod() {
        this.showLoading();
        
        // Simulate API call
        setTimeout(() => {
            const formData = this.getMethodFormData();
            
            if (this.currentMethod) {
                // Update existing method
                const index = this.methods.findIndex(m => m.id === this.currentMethod.id);
                if (index !== -1) {
                    this.methods[index] = { ...this.currentMethod, ...formData };
                }
                this.showNotification('Method updated successfully', 'success');
            } else {
                // Create new method
                const newMethod = {
                    id: Date.now(),
                    ...formData,
                    created_at: new Date().toISOString()
                };
                this.methods.push(newMethod);
                this.showNotification('Method created successfully', 'success');
            }
            
            this.hideLoading();
            this.closeMethodModal();
            this.loadMethods();
        }, 1000);
    }

    /**
     * Get category form data
     */
    getCategoryFormData() {
        const form = document.getElementById('category-form');
        if (!form) return {};

        const formData = new FormData(form);
        const data = {};
        
        for (let [key, value] of formData.entries()) {
            if (key === 'active') {
                data[key] = true;
            } else {
                data[key] = value;
            }
        }

        // Handle unchecked checkbox
        if (!formData.has('active')) {
            data.active = false;
        }

        return data;
    }

    /**
     * Get method form data
     */
    getMethodFormData() {
        const form = document.getElementById('method-form');
        if (!form) return {};

        const formData = new FormData(form);
        const data = {};
        
        for (let [key, value] of formData.entries()) {
            if (key === 'active') {
                data[key] = true;
            } else {
                data[key] = value;
            }
        }

        // Handle unchecked checkbox
        if (!formData.has('active')) {
            data.active = false;
        }

        return data;
    }

    /**
     * Populate category form
     */
    populateCategoryForm(category) {
        const form = document.getElementById('category-form');
        if (!form || !category) return;

        // Populate basic fields
        Object.keys(category).forEach(key => {
            const input = form.querySelector(`[name="${key}"]`);
            if (input) {
                if (input.type === 'checkbox') {
                    input.checked = category[key];
                } else {
                    input.value = category[key];
                }
            }
        });
    }

    /**
     * Populate method form
     */
    populateMethodForm(method) {
        const form = document.getElementById('method-form');
        if (!form || !method) return;

        // Populate basic fields
        Object.keys(method).forEach(key => {
            const input = form.querySelector(`[name="${key}"]`);
            if (input) {
                if (input.type === 'checkbox') {
                    input.checked = method[key];
                } else {
                    input.value = method[key];
                }
            }
        });
    }

    /**
     * Load categories
     */
    loadCategories() {
        this.showCategoriesLoading();
        
        // Simulate API call
        setTimeout(() => {
            this.populateCategoriesGrid();
            this.hideCategoriesLoading();
            
            if (this.categories.length === 0) {
                this.showEmptyState();
            }
        }, 1000);
    }

    /**
     * Load methods
     */
    loadMethods() {
        this.showMethodsLoading();
        
        // Simulate API call
        setTimeout(() => {
            this.populateMethodsTable();
            this.hideMethodsLoading();
        }, 1000);
    }

    /**
     * Load defaults
     */
    loadDefaults() {
        // Simulate loading default settings
        this.settings = {
            default_currency: 'ETB',
            fiscal_year_start: 'july',
            decimal_places: '2',
            date_format: 'DD/MM/YYYY',
            default_report_format: 'pdf',
            auto_backup: 'weekly',
            notify_low_budget: true,
            notify_expense_approval: true,
            notify_report_ready: false,
            notify_monthly_summary: true
        };
    }

    /**
     * Populate categories grid
     */
    populateCategoriesGrid() {
        const grid = document.getElementById('categories-grid');
        if (!grid) return;

        grid.innerHTML = '';

        this.categories.forEach(category => {
            const categoryCard = this.createCategoryCard(category);
            grid.appendChild(categoryCard);
        });
    }

    /**
     * Populate methods table
     */
    populateMethodsTable() {
        const tbody = document.querySelector('.methods-table-body');
        if (!tbody) return;

        tbody.innerHTML = '';

        this.methods.forEach(method => {
            const row = this.createMethodRow(method);
            tbody.appendChild(row);
        });
    }

    /**
     * Create category card
     */
    createCategoryCard(category) {
        const card = document.createElement('div');
        card.className = 'category-card';
        
        card.innerHTML = `
            <div class="category-header">
                <div class="category-color-indicator" style="background-color: ${category.color};"></div>
                <div class="category-name">${category.name}</div>
                <div class="category-menu">
                    <button class="category-menu-btn" onclick="financeSettingsPage.editCategory(${category.id})">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="category-description">${category.description || 'No description provided'}</div>
            <div class="category-stats">
                <span>Used in ${Math.floor(Math.random() * 50)} expenses</span>
                <div class="category-status">
                    <div class="status-indicator ${category.active ? 'active' : 'inactive'}"></div>
                    <span>${category.active ? 'Active' : 'Inactive'}</span>
                </div>
            </div>
        `;
        
        return card;
    }

    /**
     * Create method row
     */
    createMethodRow(method) {
        const row = document.createElement('tr');
        
        row.innerHTML = `
            <td>
                <div style="font-weight: 600;">${method.name}</div>
            </td>
            <td>
                <span class="method-type-badge ${method.type}">${this.getMethodTypeName(method.type)}</span>
            </td>
            <td>${method.account || '-'}</td>
            <td>
                <span class="status-badge ${method.active ? 'active' : 'inactive'}">${method.active ? 'Active' : 'Inactive'}</span>
            </td>
            <td>
                <div class="table-actions">
                    <button class="btn btn-sm btn-secondary edit-btn" onclick="financeSettingsPage.editMethod(${method.id})">
                        Edit
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" onclick="financeSettingsPage.deleteMethod(${method.id})">
                        Delete
                    </button>
                </div>
            </td>
        `;
        
        return row;
    }

    /**
     * Edit category
     */
    editCategory(categoryId) {
        const category = this.categories.find(c => c.id === categoryId);
        if (category) {
            this.openCategoryModal(category);
        }
    }

    /**
     * Edit method
     */
    editMethod(methodId) {
        const method = this.methods.find(m => m.id === methodId);
        if (method) {
            this.openMethodModal(method);
        }
    }

    /**
     * Delete method
     */
    deleteMethod(methodId) {
        if (confirm('Are you sure you want to delete this payment method?')) {
            this.methods = this.methods.filter(m => m.id !== methodId);
            this.loadMethods();
            this.showNotification('Payment method deleted successfully', 'success');
        }
    }

    /**
     * Save all settings
     */
    saveAllSettings() {
        this.showLoading();
        
        // Simulate saving all settings
        setTimeout(() => {
            this.hideLoading();
            this.showNotification('All settings saved successfully', 'success');
        }, 1500);
    }

    /**
     * Save defaults
     */
    saveDefaults() {
        const form = document.getElementById('defaults-form');
        if (!form) return;

        this.showLoading();
        
        // Get form data
        const formData = new FormData(form);
        const data = {};
        
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }

        // Handle checkboxes
        const checkboxes = form.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            data[checkbox.name] = checkbox.checked;
        });

        // Simulate API call
        setTimeout(() => {
            this.settings = { ...this.settings, ...data };
            this.hideLoading();
            this.showNotification('Default settings saved successfully', 'success');
        }, 1000);
    }

    /**
     * Export settings
     */
    exportSettings() {
        this.showLoading();
        
        // Simulate export
        setTimeout(() => {
            const data = {
                categories: this.categories,
                methods: this.methods,
                settings: this.settings,
                exported_at: new Date().toISOString()
            };
            
            const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `finance-settings-${new Date().toISOString().split('T')[0]}.json`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            
            this.hideLoading();
            this.showNotification('Settings exported successfully', 'success');
        }, 1000);
    }

    /**
     * Get method type name
     */
    getMethodTypeName(type) {
        const names = {
            'cash': 'Cash',
            'bank': 'Bank Transfer',
            'card': 'Credit Card',
            'check': 'Check',
            'other': 'Other'
        };
        return names[type] || type;
    }

    /**
     * Generate dummy data
     */
    generateDummyData() {
        // Generate categories
        this.categories = [
            {
                id: 1,
                name: 'Food Supplies',
                description: 'Ingredients and food items for restaurant operations',
                color: '#f59e0b',
                active: true
            },
            {
                id: 2,
                name: 'Utilities',
                description: 'Electricity, water, gas, and other utility expenses',
                color: '#3b82f6',
                active: true
            },
            {
                id: 3,
                name: 'Rent & Lease',
                description: 'Property rent and equipment lease payments',
                color: '#8b5cf6',
                active: true
            },
            {
                id: 4,
                name: 'Marketing',
                description: 'Advertising and promotional expenses',
                color: '#ec4899',
                active: true
            },
            {
                id: 5,
                name: 'Equipment',
                description: 'Kitchen equipment and maintenance',
                color: '#06b6d4',
                active: false
            }
        ];

        // Generate methods
        this.methods = [
            {
                id: 1,
                name: 'Cash Payments',
                type: 'cash',
                account: 'Cash Register',
                active: true
            },
            {
                id: 2,
                name: 'Business Bank Account',
                type: 'bank',
                account: 'CBE-123456789',
                active: true
            },
            {
                id: 3,
                name: 'Company Credit Card',
                type: 'card',
                account: '**** **** **** 1234',
                active: true
            },
            {
                id: 4,
                name: 'Check Payments',
                type: 'check',
                account: 'Business Checkbook',
                active: false
            }
        ];
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
     * Show categories loading
     */
    showCategoriesLoading() {
        const loadingCards = document.querySelectorAll('.category-card.loading');
        loadingCards.forEach(card => card.style.display = 'block');
    }

    /**
     * Hide categories loading
     */
    hideCategoriesLoading() {
        const loadingCards = document.querySelectorAll('.category-card.loading');
        loadingCards.forEach(card => card.style.display = 'none');
    }

    /**
     * Show methods loading
     */
    showMethodsLoading() {
        const loadingRows = document.querySelectorAll('.loading-row');
        loadingRows.forEach(row => row.style.display = 'table-row');
    }

    /**
     * Hide methods loading
     */
    hideMethodsLoading() {
        const loadingRows = document.querySelectorAll('.loading-row');
        loadingRows.forEach(row => row.style.display = 'none');
    }

    /**
     * Show empty state
     */
    showEmptyState() {
        const emptyState = document.querySelector('.empty-state');
        const grid = document.getElementById('categories-grid');
        
        if (emptyState) emptyState.style.display = 'block';
        if (grid) grid.style.display = 'none';
    }

    /**
     * Hide empty state
     */
    hideEmptyState() {
        const emptyState = document.querySelector('.empty-state');
        const grid = document.getElementById('categories-grid');
        
        if (emptyState) emptyState.style.display = 'none';
        if (grid) grid.style.display = 'grid';
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
        const forms = [
            document.getElementById('category-form'),
            document.getElementById('method-form'),
            document.getElementById('defaults-form')
        ];
        
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
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.financeSettingsPage = new FinanceSettingsPage();
});
