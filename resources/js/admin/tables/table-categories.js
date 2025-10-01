/**
 * Table Categories JavaScript
 * Restaurant-OS Admin Panel
 * 
 * Handles table category creation, editing, and management
 */

class TableCategoriesManager {
    constructor() {
        this.categories = [];
        this.filteredCategories = [];
        this.searchTerm = '';
        this.filters = {
            capacity: ''
        };
        this.currentCategory = null;
        this.isEditing = false;
        this.selectedColor = '#3b82f6';
        this.selectedIcon = 'table';
        
        this.init();
    }

    /**
     * Initialize the categories manager
     */
    init() {
        this.bindEvents();
        this.generateDummyData();
        this.updateStatistics();
        this.renderCategories();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Search and filter events
        this.bindSearchEvents();
        
        // Modal events
        this.bindModalEvents();
        
        // Action button events
        this.bindActionEvents();
        
        // Form events
        this.bindFormEvents();
        
        // Color and icon selector events
        this.bindSelectorEvents();
    }

    /**
     * Bind search and filter events
     */
    bindSearchEvents() {
        const categoriesSearch = document.getElementById('categories-search');
        const capacityFilter = document.getElementById('capacity-filter');
        const clearFiltersBtn = document.querySelector('.clear-filters-btn');

        if (categoriesSearch) {
            categoriesSearch.addEventListener('input', (e) => {
                this.searchTerm = e.target.value.toLowerCase();
                this.filterAndRenderCategories();
            });
        }

        if (capacityFilter) {
            capacityFilter.addEventListener('change', (e) => {
                this.filters.capacity = e.target.value;
                this.filterAndRenderCategories();
            });
        }

        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', () => this.clearFilters());
        }
    }

    /**
     * Bind modal events
     */
    bindModalEvents() {
        // Category modal
        this.bindModalCloseEvents('category-modal', () => this.closeCategoryModal());

        // Escape key to close modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeCategoryModal();
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
        const cancelBtn = modal.querySelector('.cancel-category-btn');
        const overlay = modal.querySelector('.modal-overlay');

        if (closeBtn) closeBtn.addEventListener('click', closeCallback);
        if (cancelBtn) cancelBtn.addEventListener('click', closeCallback);
        if (overlay) overlay.addEventListener('click', closeCallback);
    }

    /**
     * Bind action button events
     */
    bindActionEvents() {
        // Add category button
        document.querySelectorAll('.add-category-btn').forEach(btn => {
            btn.addEventListener('click', () => this.openCategoryModal());
        });

        // Export categories button
        const exportBtn = document.querySelector('.export-categories-btn');
        if (exportBtn) {
            exportBtn.addEventListener('click', () => this.exportCategories());
        }

        // Event delegation for dynamic buttons
        document.addEventListener('click', (e) => {
            // Category card click (for viewing details)
            if (e.target.closest('.category-card') && !e.target.closest('.category-action-btn')) {
                const categoryId = parseInt(e.target.closest('.category-card').dataset.categoryId);
                this.viewCategoryDetails(categoryId);
            }
            
            // Category action buttons
            if (e.target.closest('.category-action-btn')) {
                e.stopPropagation();
                const action = e.target.closest('.category-action-btn').dataset.action;
                const categoryId = parseInt(e.target.closest('.category-card').dataset.categoryId);
                
                if (action === 'edit') {
                    this.editCategory(categoryId);
                } else if (action === 'delete') {
                    this.deleteCategory(categoryId);
                }
            }
        });
    }

    /**
     * Bind form events
     */
    bindFormEvents() {
        const categoryForm = document.getElementById('category-form');
        if (categoryForm) {
            categoryForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.saveCategory();
            });
        }

        // Auto-generate category code from category name
        const categoryNameInput = document.getElementById('category-name');
        const categoryCodeInput = document.getElementById('category-code');
        
        if (categoryNameInput && categoryCodeInput) {
            categoryNameInput.addEventListener('input', (e) => {
                if (!this.isEditing && !categoryCodeInput.value) {
                    const code = this.generateCategoryCode(e.target.value);
                    categoryCodeInput.value = code;
                }
            });
        }
    }

    /**
     * Bind color and icon selector events
     */
    bindSelectorEvents() {
        // Color selector
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('color-option')) {
                const color = e.target.dataset.color;
                this.selectColor(color);
            }
            
            if (e.target.closest('.icon-option')) {
                const iconOption = e.target.closest('.icon-option');
                const icon = iconOption.dataset.icon;
                this.selectIcon(icon);
            }
        });
    }

    /**
     * Generate dummy data
     */
    generateDummyData() {
        this.categories = [
            {
                id: 1,
                name: 'Standard Tables',
                code: 'STD',
                description: 'Regular dining tables for everyday service',
                color: '#3b82f6',
                icon: 'table',
                defaultCapacity: 4,
                pricingMultiplier: 1.0,
                createdAt: new Date('2024-01-15'),
                updatedAt: new Date('2024-01-15')
            },
            {
                id: 2,
                name: 'Premium Tables',
                code: 'PRM',
                description: 'Enhanced tables with better positioning and service',
                color: '#8b5cf6',
                icon: 'table',
                defaultCapacity: 4,
                pricingMultiplier: 1.2,
                createdAt: new Date('2024-01-15'),
                updatedAt: new Date('2024-01-15')
            },
            {
                id: 3,
                name: 'VIP Tables',
                code: 'VIP',
                description: 'Exclusive tables with premium service and privacy',
                color: '#dc2626',
                icon: 'vip',
                defaultCapacity: 6,
                pricingMultiplier: 1.5,
                createdAt: new Date('2024-01-16'),
                updatedAt: new Date('2024-01-16')
            },
            {
                id: 4,
                name: 'Outdoor Tables',
                code: 'OUT',
                description: 'Terrace and patio tables with outdoor ambiance',
                color: '#10b981',
                icon: 'outdoor',
                defaultCapacity: 4,
                pricingMultiplier: 1.1,
                createdAt: new Date('2024-01-16'),
                updatedAt: new Date('2024-01-16')
            },
            {
                id: 5,
                name: 'Bar Seating',
                code: 'BAR',
                description: 'High tables and bar stools in the bar area',
                color: '#f59e0b',
                icon: 'bar',
                defaultCapacity: 2,
                pricingMultiplier: 0.9,
                createdAt: new Date('2024-01-17'),
                updatedAt: new Date('2024-01-17')
            },
            {
                id: 6,
                name: 'Booth Seating',
                code: 'BTH',
                description: 'Comfortable booth seating for intimate dining',
                color: '#06b6d4',
                icon: 'booth',
                defaultCapacity: 4,
                pricingMultiplier: 1.1,
                createdAt: new Date('2024-01-17'),
                updatedAt: new Date('2024-01-17')
            }
        ];
    }

    /**
     * Update statistics
     */
    updateStatistics() {
        const totalCategories = this.categories.length;
        const activeCategories = this.categories.length; // All categories are active for now
        const avgCapacity = totalCategories > 0 
            ? Math.round(this.categories.reduce((sum, c) => sum + c.defaultCapacity, 0) / totalCategories)
            : 0;

        document.getElementById('total-categories').textContent = totalCategories;
        document.getElementById('active-categories').textContent = activeCategories;
        document.getElementById('avg-capacity').textContent = avgCapacity;
    }

    /**
     * Filter and render categories
     */
    filterAndRenderCategories() {
        this.filteredCategories = this.categories.filter(category => {
            // Search filter
            const searchMatch = !this.searchTerm || 
                category.name.toLowerCase().includes(this.searchTerm) ||
                category.code.toLowerCase().includes(this.searchTerm) ||
                category.description.toLowerCase().includes(this.searchTerm);

            // Capacity filter
            let capacityMatch = true;
            if (this.filters.capacity) {
                const capacity = category.defaultCapacity;
                switch (this.filters.capacity) {
                    case 'small':
                        capacityMatch = capacity <= 2;
                        break;
                    case 'medium':
                        capacityMatch = capacity >= 3 && capacity <= 4;
                        break;
                    case 'large':
                        capacityMatch = capacity >= 5 && capacity <= 8;
                        break;
                    case 'xlarge':
                        capacityMatch = capacity >= 9;
                        break;
                }
            }

            return searchMatch && capacityMatch;
        });

        this.renderCategories();
    }

    /**
     * Render categories
     */
    renderCategories() {
        const categoriesGrid = document.getElementById('categories-grid');
        if (!categoriesGrid) return;

        const categoriesToShow = this.filteredCategories.length ? this.filteredCategories : this.categories;

        if (categoriesToShow.length === 0) {
            categoriesGrid.innerHTML = `
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <h3>No categories found</h3>
                    <p>No table categories match your current search and filter criteria.</p>
                </div>
            `;
            return;
        }

        categoriesGrid.innerHTML = categoriesToShow.map(category => `
            <div class="category-card" data-category-id="${category.id}" style="border-top-color: ${category.color};">
                <div class="category-header">
                    <div class="category-icon-display" style="background-color: ${category.color};">
                        ${this.getIconSvg(category.icon)}
                    </div>
                    <div class="category-info">
                        <div class="category-name">${category.name}</div>
                        <div class="category-code">${category.code}</div>
                    </div>
                </div>
                
                <div class="category-details">
                    <div class="category-detail">
                        <span class="detail-label">Default Capacity</span>
                        <span class="detail-value">${category.defaultCapacity} people</span>
                    </div>
                    <div class="category-detail">
                        <span class="detail-label">Pricing Multiplier</span>
                        <span class="detail-value pricing-multiplier ${this.getPricingClass(category.pricingMultiplier)}">${category.pricingMultiplier}x</span>
                    </div>
                    <div class="category-detail">
                        <span class="detail-label">Created</span>
                        <span class="detail-value">${this.formatDate(category.createdAt)}</span>
                    </div>
                </div>
                
                ${category.description ? `<div class="category-description">${category.description}</div>` : ''}
                
                <div class="category-actions">
                    <button class="category-action-btn edit" data-action="edit" title="Edit Category">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button class="category-action-btn delete" data-action="delete" title="Delete Category">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        `).join('');
    }

    /**
     * Get icon SVG
     */
    getIconSvg(iconName) {
        const icons = {
            table: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0V7a2 2 0 012-2h16a2 2 0 012 2v11a2 2 0 01-2 2H5a2 2 0 01-2-2V10z"/></svg>',
            chair: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0h10m-9 0v16l2-2m7 2l2 2V4"/></svg>',
            booth: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>',
            bar: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18l-2 9H5L3 3zm0 0l-.5-2M7 13v8a2 2 0 002 2h6a2 2 0 002-2v-8"/></svg>',
            outdoor: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>',
            vip: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>',
            counter: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>',
            communal: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>'
        };
        return icons[iconName] || icons.table;
    }

    /**
     * Get pricing class
     */
    getPricingClass(multiplier) {
        if (multiplier >= 1.3) return 'luxury';
        if (multiplier >= 1.1) return 'premium';
        return 'standard';
    }

    /**
     * Select color
     */
    selectColor(color) {
        this.selectedColor = color;
        document.getElementById('category-color').value = color;
        
        // Update visual selection
        document.querySelectorAll('.color-option').forEach(option => {
            option.classList.remove('active');
        });
        document.querySelector(`[data-color="${color}"]`).classList.add('active');
    }

    /**
     * Select icon
     */
    selectIcon(icon) {
        this.selectedIcon = icon;
        document.getElementById('category-icon').value = icon;
        
        // Update visual selection
        document.querySelectorAll('.icon-option').forEach(option => {
            option.classList.remove('active');
        });
        document.querySelector(`[data-icon="${icon}"]`).classList.add('active');
    }

    /**
     * Open category modal
     */
    openCategoryModal(category = null) {
        this.currentCategory = category;
        this.isEditing = !!category;
        
        const modal = document.getElementById('category-modal');
        const title = document.getElementById('category-modal-title');
        
        if (modal && title) {
            title.textContent = this.isEditing ? 'Edit Table Category' : 'Add Table Category';
            
            if (this.isEditing) {
                this.populateCategoryForm(category);
            } else {
                this.resetCategoryForm();
            }
            
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
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
            this.resetCategoryForm();
            this.currentCategory = null;
            this.isEditing = false;
        }
    }

    /**
     * Populate category form
     */
    populateCategoryForm(category) {
        document.getElementById('category-name').value = category.name;
        document.getElementById('category-code').value = category.code;
        document.getElementById('default-capacity').value = category.defaultCapacity;
        document.getElementById('pricing-multiplier').value = category.pricingMultiplier;
        document.getElementById('category-description').value = category.description || '';
        
        this.selectColor(category.color);
        this.selectIcon(category.icon);
    }

    /**
     * Reset category form
     */
    resetCategoryForm() {
        const form = document.getElementById('category-form');
        if (form) {
            form.reset();
            document.getElementById('default-capacity').value = '4';
            document.getElementById('pricing-multiplier').value = '1.0';
        }
        
        this.selectColor('#3b82f6');
        this.selectIcon('table');
    }

    /**
     * Save category
     */
    saveCategory() {
        const formData = new FormData(document.getElementById('category-form'));
        
        const categoryData = {
            name: formData.get('category_name'),
            code: formData.get('category_code').toUpperCase(),
            defaultCapacity: parseInt(formData.get('default_capacity')),
            pricingMultiplier: parseFloat(formData.get('pricing_multiplier')),
            description: formData.get('description') || '',
            color: this.selectedColor,
            icon: this.selectedIcon
        };

        // Validate required fields
        if (!categoryData.name || !categoryData.code || !categoryData.defaultCapacity) {
            this.showNotification('Please fill in all required fields', 'error');
            return;
        }

        // Check for duplicate category code
        const duplicateCode = this.categories.find(c => 
            c.code === categoryData.code && (!this.isEditing || c.id !== this.currentCategory.id)
        );
        
        if (duplicateCode) {
            this.showNotification('Category code already exists. Please use a different code.', 'error');
            return;
        }

        if (this.isEditing) {
            // Update existing category
            const index = this.categories.findIndex(c => c.id === this.currentCategory.id);
            if (index !== -1) {
                this.categories[index] = { 
                    ...this.categories[index], 
                    ...categoryData, 
                    updatedAt: new Date() 
                };
                this.showNotification('Table category updated successfully', 'success');
            }
        } else {
            // Add new category
            const newCategory = {
                id: Math.max(...this.categories.map(c => c.id)) + 1,
                ...categoryData,
                createdAt: new Date(),
                updatedAt: new Date()
            };
            this.categories.push(newCategory);
            this.showNotification('Table category added successfully', 'success');
        }

        this.updateStatistics();
        this.filterAndRenderCategories();
        this.closeCategoryModal();
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
     * Delete category
     */
    deleteCategory(categoryId) {
        const category = this.categories.find(c => c.id === categoryId);
        if (category && confirm(`Are you sure you want to delete "${category.name}"?`)) {
            this.categories = this.categories.filter(c => c.id !== categoryId);
            this.updateStatistics();
            this.filterAndRenderCategories();
            this.showNotification('Table category deleted successfully', 'success');
        }
    }

    /**
     * View category details
     */
    viewCategoryDetails(categoryId) {
        const category = this.categories.find(c => c.id === categoryId);
        if (category) {
            // For now, just edit the category
            this.editCategory(categoryId);
        }
    }

    /**
     * Clear all filters
     */
    clearFilters() {
        this.searchTerm = '';
        this.filters = {
            capacity: ''
        };
        
        // Reset form inputs
        const categoriesSearch = document.getElementById('categories-search');
        const capacityFilter = document.getElementById('capacity-filter');
        
        if (categoriesSearch) categoriesSearch.value = '';
        if (capacityFilter) capacityFilter.value = '';
        
        this.filterAndRenderCategories();
    }

    /**
     * Export categories
     */
    exportCategories() {
        const csvContent = this.generateCategoriesCSV();
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `table-categories-${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        
        this.showNotification('Table categories exported successfully', 'success');
    }

    /**
     * Generate categories CSV
     */
    generateCategoriesCSV() {
        const headers = ['ID', 'Name', 'Code', 'Default Capacity', 'Pricing Multiplier', 'Color', 'Icon', 'Description', 'Created'];
        
        const rows = this.categories.map(category => [
            category.id,
            category.name,
            category.code,
            category.defaultCapacity,
            category.pricingMultiplier,
            category.color,
            category.icon,
            category.description || '',
            category.createdAt.toISOString().split('T')[0]
        ]);
        
        return [headers, ...rows].map(row => 
            row.map(field => `"${String(field).replace(/"/g, '""')}"`).join(',')
        ).join('\n');
    }

    /**
     * Generate category code from name
     */
    generateCategoryCode(name) {
        if (!name) return '';
        
        // Take first 3 characters of each word, max 5 characters
        const words = name.trim().split(/\s+/);
        let code = '';
        
        for (const word of words) {
            if (code.length >= 5) break;
            const chars = word.replace(/[^a-zA-Z]/g, '').toUpperCase();
            if (chars.length > 0) {
                code += chars.substring(0, Math.min(3, 5 - code.length));
            }
        }
        
        return code.substring(0, 5);
    }

    /**
     * Utility methods
     */
    formatDate(date) {
        return new Intl.DateTimeFormat('en-GB', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        }).format(new Date(date));
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
    window.tableCategoriesManager = new TableCategoriesManager();
});
