/**
 * Menu Categories Page JavaScript
 * Restaurant-OS Admin Panel
 * 
 * Handles category management, creation, editing, and organization
 */

class MenuCategoriesPage {
    constructor() {
        this.categories = [];
        this.filteredCategories = [];
        this.currentView = 'grid';
        this.currentCategory = null;
        this.isLoading = false;
        
        this.init();
    }

    /**
     * Initialize the categories page
     */
    init() {
        this.bindEvents();
        this.generateDummyData();
        this.loadCategories();
        this.updateStatistics();
        this.setupIconSelection();
        this.setupColorPicker();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Category management events
        this.bindCategoryEvents();
        
        // Filter and search events
        this.bindFilterEvents();
        
        // View toggle events
        this.bindViewEvents();
        
        // Modal events
        this.bindModalEvents();
        
        // Form events
        this.bindFormEvents();
        
        // Import/Export events
        this.bindImportExportEvents();
    }

    /**
     * Bind category management events
     */
    bindCategoryEvents() {
        // Add category buttons
        document.querySelectorAll('.add-category-btn').forEach(btn => {
            btn.addEventListener('click', () => this.openCategoryModal());
        });
    }

    /**
     * Bind filter and search events
     */
    bindFilterEvents() {
        // Search input
        const searchInput = document.getElementById('category-search');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => this.handleSearch(e.target.value));
        }

        // Filter selects
        const filters = ['status-filter', 'sort-filter'];
        filters.forEach(filterId => {
            const filter = document.getElementById(filterId);
            if (filter) {
                filter.addEventListener('change', () => this.applyFilters());
            }
        });

        // Clear filters
        const clearBtn = document.querySelector('.clear-filters-btn');
        if (clearBtn) {
            clearBtn.addEventListener('click', () => this.clearFilters());
        }
    }

    /**
     * Bind view toggle events
     */
    bindViewEvents() {
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const view = e.currentTarget.dataset.view;
                this.toggleView(view);
            });
        });
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

        // Details modal
        const detailsModal = document.getElementById('category-details-modal');
        const detailsCloseBtn = detailsModal?.querySelector('.modal-close');
        const detailsCloseBtn2 = detailsModal?.querySelector('.close-details-btn');
        const detailsEditBtn = detailsModal?.querySelector('.edit-category-btn');
        const detailsOverlay = detailsModal?.querySelector('.modal-overlay');

        if (detailsCloseBtn) detailsCloseBtn.addEventListener('click', () => this.closeCategoryDetailsModal());
        if (detailsCloseBtn2) detailsCloseBtn2.addEventListener('click', () => this.closeCategoryDetailsModal());
        if (detailsEditBtn) detailsEditBtn.addEventListener('click', () => this.editFromDetails());
        if (detailsOverlay) detailsOverlay.addEventListener('click', () => this.closeCategoryDetailsModal());

        // Escape key to close modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeCategoryModal();
                this.closeCategoryDetailsModal();
            }
        });
    }

    /**
     * Bind form events
     */
    bindFormEvents() {
        // Category form submission
        const categoryForm = document.getElementById('category-form');
        if (categoryForm) {
            categoryForm.addEventListener('submit', (e) => this.handleCategoryFormSubmit(e));
        }
    }

    /**
     * Bind import/export events
     */
    bindImportExportEvents() {
        // Import categories
        const importBtn = document.querySelector('.import-categories-btn');
        if (importBtn) {
            importBtn.addEventListener('click', () => this.importCategories());
        }

        // Export categories
        const exportBtn = document.querySelector('.export-categories-btn');
        if (exportBtn) {
            exportBtn.addEventListener('click', () => this.exportCategories());
        }
    }

    /**
     * Setup icon selection
     */
    setupIconSelection() {
        document.querySelectorAll('.icon-option').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                
                // Remove active class from all icons
                document.querySelectorAll('.icon-option').forEach(icon => {
                    icon.classList.remove('active');
                });
                
                // Add active class to clicked icon
                btn.classList.add('active');
                
                // Update hidden input
                const selectedIconInput = document.getElementById('selected-icon');
                if (selectedIconInput) {
                    selectedIconInput.value = btn.dataset.icon;
                }
            });
        });
    }

    /**
     * Setup color picker
     */
    setupColorPicker() {
        document.querySelectorAll('.color-preset').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const color = e.target.dataset.color;
                const colorInput = document.getElementById('category-color');
                if (colorInput) {
                    colorInput.value = color;
                }
            });
        });
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
                title.textContent = 'Add New Category';
                form.reset();
                this.resetIconSelection();
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
     * Open category details modal
     */
    openCategoryDetailsModal(category) {
        const modal = document.getElementById('category-details-modal');
        const content = document.getElementById('category-details-content');

        if (modal && content && category) {
            this.currentCategory = category;
            this.populateCategoryDetails(category);
            
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
        }
    }

    /**
     * Close category details modal
     */
    closeCategoryDetailsModal() {
        const modal = document.getElementById('category-details-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
            this.currentCategory = null;
        }
    }

    /**
     * Edit from details modal
     */
    editFromDetails() {
        if (this.currentCategory) {
            this.closeCategoryDetailsModal();
            this.openCategoryModal(this.currentCategory);
        }
    }

    /**
     * Handle search
     */
    handleSearch(query) {
        this.applyFilters();
    }

    /**
     * Apply filters
     */
    applyFilters() {
        const searchQuery = document.getElementById('category-search')?.value.toLowerCase() || '';
        const statusFilter = document.getElementById('status-filter')?.value || '';
        const sortFilter = document.getElementById('sort-filter')?.value || 'name';

        // Filter categories
        this.filteredCategories = this.categories.filter(category => {
            // Search filter
            const matchesSearch = !searchQuery || 
                category.name.toLowerCase().includes(searchQuery) ||
                category.description.toLowerCase().includes(searchQuery);

            // Status filter
            const matchesStatus = !statusFilter || 
                (statusFilter === 'active' && category.active) ||
                (statusFilter === 'inactive' && !category.active);

            return matchesSearch && matchesStatus;
        });

        // Sort categories
        this.sortCategories(sortFilter);
        this.renderCategories();
    }

    /**
     * Sort categories
     */
    sortCategories(sortBy) {
        this.filteredCategories.sort((a, b) => {
            switch (sortBy) {
                case 'name':
                    return a.name.localeCompare(b.name);
                case 'dishes':
                    return (b.dishes_count || 0) - (a.dishes_count || 0);
                case 'created':
                    return new Date(b.created_at) - new Date(a.created_at);
                case 'updated':
                    return new Date(b.updated_at) - new Date(a.updated_at);
                default:
                    return a.display_order - b.display_order;
            }
        });
    }

    /**
     * Clear filters
     */
    clearFilters() {
        document.getElementById('category-search').value = '';
        document.getElementById('status-filter').value = '';
        document.getElementById('sort-filter').value = 'name';
        
        this.filteredCategories = [...this.categories];
        this.sortCategories('name');
        this.renderCategories();
    }

    /**
     * Toggle view between grid and list
     */
    toggleView(view) {
        this.currentView = view;
        
        // Update view buttons
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.view === view);
        });

        // Show/hide views
        const gridView = document.getElementById('categories-grid');
        const listView = document.getElementById('categories-list');

        if (view === 'grid') {
            gridView.style.display = 'grid';
            listView.style.display = 'none';
        } else {
            gridView.style.display = 'none';
            listView.style.display = 'block';
        }

        this.renderCategories();
    }

    /**
     * Handle category form submission
     */
    handleCategoryFormSubmit(e) {
        e.preventDefault();
        
        if (this.validateCategoryForm()) {
            this.saveCategory();
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
                    this.categories[index] = { ...this.currentCategory, ...formData, updated_at: new Date().toISOString() };
                }
                this.showNotification('Category updated successfully', 'success');
            } else {
                // Create new category
                const newCategory = {
                    id: Date.now(),
                    ...formData,
                    dishes_count: 0,
                    created_at: new Date().toISOString(),
                    updated_at: new Date().toISOString()
                };
                this.categories.push(newCategory);
                this.showNotification('Category created successfully', 'success');
            }
            
            this.hideLoading();
            this.closeCategoryModal();
            this.loadCategories();
            this.updateStatistics();
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

        // Get selected icon
        const selectedIcon = document.getElementById('selected-icon');
        if (selectedIcon) {
            data.icon = selectedIcon.value;
        }

        return data;
    }

    /**
     * Load categories
     */
    loadCategories() {
        this.showCategoriesLoading();
        
        // Simulate API call
        setTimeout(() => {
            this.filteredCategories = [...this.categories];
            this.sortCategories('name');
            this.renderCategories();
            this.hideCategoriesLoading();
            
            if (this.categories.length === 0) {
                this.showEmptyState();
            }
        }, 1000);
    }

    /**
     * Render categories based on current view
     */
    renderCategories() {
        if (this.currentView === 'grid') {
            this.renderCategoriesGrid();
        } else {
            this.renderCategoriesList();
        }

        if (this.filteredCategories.length === 0 && this.categories.length > 0) {
            this.showEmptyState();
        } else {
            this.hideEmptyState();
        }
    }

    /**
     * Render categories grid
     */
    renderCategoriesGrid() {
        const grid = document.getElementById('categories-grid');
        if (!grid) return;

        grid.innerHTML = '';

        this.filteredCategories.forEach(category => {
            const categoryCard = this.createCategoryCard(category);
            grid.appendChild(categoryCard);
        });
    }

    /**
     * Render categories list
     */
    renderCategoriesList() {
        const tbody = document.querySelector('.categories-table-body');
        if (!tbody) return;

        tbody.innerHTML = '';

        this.filteredCategories.forEach(category => {
            const row = this.createCategoryRow(category);
            tbody.appendChild(row);
        });
    }

    /**
     * Create category card for grid view
     */
    createCategoryCard(category) {
        const card = document.createElement('div');
        card.className = 'category-card';
        card.onclick = () => this.openCategoryDetailsModal(category);
        
        card.innerHTML = `
            <div class="category-header">
                <div class="category-color" style="background-color: ${category.color};">
                    ${this.getIconSVG(category.icon)}
                </div>
                <div class="category-info">
                    <div class="category-name">${category.name}</div>
                    <div class="category-order">Order: ${category.display_order || 0}</div>
                </div>
                <div class="category-menu">
                    <button class="category-menu-btn" onclick="event.stopPropagation(); menuCategoriesPage.editCategory(${category.id})">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="category-description">${category.description || 'No description provided'}</div>
            <div class="category-footer">
                <div class="category-stats">
                    <div class="stat-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <span>${category.dishes_count || 0} dishes</span>
                    </div>
                </div>
                <div class="category-status">
                    <div class="status-indicator ${category.active ? 'active' : 'inactive'}"></div>
                    <span>${category.active ? 'Active' : 'Inactive'}</span>
                </div>
            </div>
        `;
        
        return card;
    }

    /**
     * Create category row for list view
     */
    createCategoryRow(category) {
        const row = document.createElement('tr');
        
        row.innerHTML = `
            <td>
                <div class="category-info-cell">
                    <div class="category-color-small" style="background-color: ${category.color};">
                        ${this.getIconSVG(category.icon)}
                    </div>
                    <div class="category-info-content">
                        <div class="category-info-name">${category.name}</div>
                        <div class="category-info-order">Order: ${category.display_order || 0}</div>
                    </div>
                </div>
            </td>
            <td class="description-cell">${category.description || 'No description'}</td>
            <td class="dishes-count-cell">${category.dishes_count || 0}</td>
            <td class="order-cell">${category.display_order || 0}</td>
            <td><span class="status-badge ${category.active ? 'active' : 'inactive'}">${category.active ? 'Active' : 'Inactive'}</span></td>
            <td>
                <div class="table-actions">
                    <button class="btn btn-sm btn-secondary" onclick="menuCategoriesPage.editCategory(${category.id})">Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="menuCategoriesPage.deleteCategory(${category.id})">Delete</button>
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
     * Delete category
     */
    deleteCategory(categoryId) {
        const category = this.categories.find(c => c.id === categoryId);
        if (!category) return;

        const dishesCount = category.dishes_count || 0;
        let confirmMessage = 'Are you sure you want to delete this category?';
        
        if (dishesCount > 0) {
            confirmMessage = `This category has ${dishesCount} dishes. Deleting it will remove the category from all dishes. Are you sure?`;
        }

        if (confirm(confirmMessage)) {
            this.categories = this.categories.filter(c => c.id !== categoryId);
            this.loadCategories();
            this.updateStatistics();
            this.showNotification('Category deleted successfully', 'success');
        }
    }

    /**
     * Import categories
     */
    importCategories() {
        // Create file input for import
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = '.json,.csv';
        input.onchange = (e) => {
            const file = e.target.files[0];
            if (file) {
                this.handleImportFile(file);
            }
        };
        input.click();
    }

    /**
     * Handle import file
     */
    handleImportFile(file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            try {
                const data = JSON.parse(e.target.result);
                if (Array.isArray(data)) {
                    // Add unique IDs and timestamps
                    const importedCategories = data.map(category => ({
                        ...category,
                        id: Date.now() + Math.random(),
                        created_at: new Date().toISOString(),
                        updated_at: new Date().toISOString(),
                        dishes_count: category.dishes_count || 0
                    }));
                    
                    this.categories.push(...importedCategories);
                    this.loadCategories();
                    this.updateStatistics();
                    this.showNotification(`Imported ${importedCategories.length} categories successfully`, 'success');
                }
            } catch (error) {
                this.showNotification('Invalid file format', 'error');
            }
        };
        reader.readAsText(file);
    }

    /**
     * Export categories
     */
    exportCategories() {
        const data = {
            categories: this.categories,
            exported_at: new Date().toISOString(),
            total_categories: this.categories.length
        };
        
        const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `categories-export-${new Date().toISOString().split('T')[0]}.json`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        
        this.showNotification('Categories exported successfully', 'success');
    }

    /**
     * Update statistics
     */
    updateStatistics() {
        const totalCategories = this.categories.length;
        const activeCategories = this.categories.filter(c => c.active).length;
        const totalDishes = this.categories.reduce((sum, c) => sum + (c.dishes_count || 0), 0);
        const popularCategory = this.categories.reduce((prev, current) => 
            (current.dishes_count || 0) > (prev.dishes_count || 0) ? current : prev, 
            { dishes_count: 0, name: '-' }
        );

        // Update stat cards
        document.getElementById('total-categories').textContent = totalCategories;
        document.getElementById('active-categories').textContent = activeCategories;
        document.getElementById('total-dishes').textContent = totalDishes;
        document.getElementById('popular-category').textContent = popularCategory.name;
    }

    /**
     * Populate category details
     */
    populateCategoryDetails(category) {
        const content = document.getElementById('category-details-content');
        if (!content || !category) return;

        content.innerHTML = `
            <div class="details-header">
                <div class="details-color" style="background-color: ${category.color};">
                    ${this.getIconSVG(category.icon)}
                </div>
                <div class="details-info">
                    <div class="details-name">${category.name}</div>
                    <div class="details-description">${category.description || 'No description provided'}</div>
                </div>
            </div>
            <div class="details-stats">
                <div class="details-stat">
                    <div class="details-stat-value">${category.dishes_count || 0}</div>
                    <div class="details-stat-label">Dishes</div>
                </div>
                <div class="details-stat">
                    <div class="details-stat-value">${category.display_order || 0}</div>
                    <div class="details-stat-label">Display Order</div>
                </div>
                <div class="details-stat">
                    <div class="details-stat-value">${category.active ? 'Active' : 'Inactive'}</div>
                    <div class="details-stat-label">Status</div>
                </div>
                <div class="details-stat">
                    <div class="details-stat-value">${new Date(category.created_at).toLocaleDateString()}</div>
                    <div class="details-stat-label">Created</div>
                </div>
            </div>
        `;
    }

    /**
     * Generate dummy data
     */
    generateDummyData() {
        this.categories = [
            {
                id: 1,
                name: 'Appetizers',
                description: 'Light dishes served before the main course to stimulate appetite',
                color: '#8b5cf6',
                icon: 'utensils',
                display_order: 1,
                active: true,
                dishes_count: 8,
                created_at: '2024-01-15T10:00:00Z',
                updated_at: '2024-01-15T10:00:00Z'
            },
            {
                id: 2,
                name: 'Main Courses',
                description: 'Hearty and satisfying dishes that form the centerpiece of the meal',
                color: '#3b82f6',
                icon: 'pizza',
                display_order: 2,
                active: true,
                dishes_count: 15,
                created_at: '2024-01-15T10:05:00Z',
                updated_at: '2024-01-15T10:05:00Z'
            },
            {
                id: 3,
                name: 'Desserts',
                description: 'Sweet treats and delightful endings to your dining experience',
                color: '#ec4899',
                icon: 'cake',
                display_order: 3,
                active: true,
                dishes_count: 6,
                created_at: '2024-01-15T10:10:00Z',
                updated_at: '2024-01-15T10:10:00Z'
            },
            {
                id: 4,
                name: 'Beverages',
                description: 'Refreshing drinks, hot beverages, and specialty cocktails',
                color: '#06b6d4',
                icon: 'coffee',
                display_order: 4,
                active: true,
                dishes_count: 12,
                created_at: '2024-01-15T10:15:00Z',
                updated_at: '2024-01-15T10:15:00Z'
            },
            {
                id: 5,
                name: 'Seafood',
                description: 'Fresh fish and seafood specialties',
                color: '#10b981',
                icon: 'fish',
                display_order: 5,
                active: false,
                dishes_count: 4,
                created_at: '2024-01-15T10:20:00Z',
                updated_at: '2024-01-15T10:20:00Z'
            },
            {
                id: 6,
                name: 'Salads',
                description: 'Fresh and healthy salad options',
                color: '#84cc16',
                icon: 'salad',
                display_order: 6,
                active: true,
                dishes_count: 7,
                created_at: '2024-01-15T10:25:00Z',
                updated_at: '2024-01-15T10:25:00Z'
            }
        ];
    }

    /**
     * Get icon SVG
     */
    getIconSVG(iconName) {
        const icons = {
            utensils: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6"/></svg>',
            coffee: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>',
            cake: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0A2.704 2.704 0 003 15.546V12c0-.55.45-1 1-1h16c.55 0 1 .45 1 1v3.546z"/></svg>',
            pizza: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>',
            fish: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/></svg>',
            salad: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/></svg>'
        };
        return icons[iconName] || icons.utensils;
    }

    /**
     * Utility methods
     */
    showLoading() {
        this.isLoading = true;
    }

    hideLoading() {
        this.isLoading = false;
    }

    showCategoriesLoading() {
        const loadingCards = document.querySelectorAll('.category-card.loading');
        const loadingRows = document.querySelectorAll('.loading-row');
        loadingCards.forEach(card => card.style.display = 'block');
        loadingRows.forEach(row => row.style.display = 'table-row');
    }

    hideCategoriesLoading() {
        const loadingCards = document.querySelectorAll('.category-card.loading');
        const loadingRows = document.querySelectorAll('.loading-row');
        loadingCards.forEach(card => card.style.display = 'none');
        loadingRows.forEach(row => row.style.display = 'none');
    }

    showEmptyState() {
        const emptyState = document.querySelector('.empty-state');
        const categoriesGrid = document.getElementById('categories-grid');
        const categoriesList = document.getElementById('categories-list');
        
        if (emptyState) emptyState.style.display = 'block';
        if (categoriesGrid) categoriesGrid.style.display = 'none';
        if (categoriesList) categoriesList.style.display = 'none';
    }

    hideEmptyState() {
        const emptyState = document.querySelector('.empty-state');
        const categoriesGrid = document.getElementById('categories-grid');
        const categoriesList = document.getElementById('categories-list');
        
        if (emptyState) emptyState.style.display = 'none';
        if (this.currentView === 'grid' && categoriesGrid) {
            categoriesGrid.style.display = 'grid';
        } else if (this.currentView === 'list' && categoriesList) {
            categoriesList.style.display = 'block';
        }
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

    showFieldError(field, message) {
        this.clearFieldError(field);
        
        field.classList.add('error');
        const errorElement = document.createElement('div');
        errorElement.className = 'field-error';
        errorElement.textContent = message;
        
        field.parentNode.appendChild(errorElement);
    }

    clearFieldError(field) {
        field.classList.remove('error');
        const errorElement = field.parentNode.querySelector('.field-error');
        if (errorElement) {
            errorElement.remove();
        }
    }

    resetIconSelection() {
        // Reset to first icon
        document.querySelectorAll('.icon-option').forEach(icon => {
            icon.classList.remove('active');
        });
        document.querySelector('.icon-option[data-icon="utensils"]')?.classList.add('active');
        
        const selectedIconInput = document.getElementById('selected-icon');
        if (selectedIconInput) {
            selectedIconInput.value = 'utensils';
        }
    }

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

        // Set icon selection
        document.querySelectorAll('.icon-option').forEach(icon => {
            icon.classList.toggle('active', icon.dataset.icon === category.icon);
        });
        
        const selectedIconInput = document.getElementById('selected-icon');
        if (selectedIconInput) {
            selectedIconInput.value = category.icon || 'utensils';
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.menuCategoriesPage = new MenuCategoriesPage();
});
