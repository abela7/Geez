/**
 * Menu Pricing Page JavaScript
 * Restaurant-OS Admin Panel
 * 
 * Handles pricing management, price tracking, and bulk updates
 */

class MenuPricingManager {
    constructor() {
        this.menuItems = [];
        this.filteredItems = [];
        this.priceHistory = [];
        this.currentView = 'table';
        this.currentItem = null;
        this.selectedItems = [];
        this.isLoading = false;
        
        this.init();
    }

    /**
     * Initialize the pricing manager
     */
    init() {
        this.bindEvents();
        this.generateDummyData();
        this.loadMenuItems();
        this.updateStatistics();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Item management events
        this.bindItemEvents();
        
        // Filter and search events
        this.bindFilterEvents();
        
        // View toggle events
        this.bindViewEvents();
        
        // Modal events
        this.bindModalEvents();
        
        // Form events
        this.bindFormEvents();
        
        // Selection events
        this.bindSelectionEvents();
        
        // Action button events
        this.bindActionEvents();
    }

    /**
     * Bind item management events
     */
    bindItemEvents() {
        // Edit price buttons will be bound dynamically
    }

    /**
     * Bind filter and search events
     */
    bindFilterEvents() {
        // Search input
        const searchInput = document.getElementById('item-search');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => this.handleSearch(e.target.value));
        }

        // Filter selects
        const filters = ['category-filter', 'price-range-filter', 'sort-filter'];
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
        // Edit price modal
        this.bindModalCloseEvents('edit-price-modal', () => this.closeEditPriceModal());
        
        // Bulk update modal
        this.bindModalCloseEvents('bulk-update-modal', () => this.closeBulkUpdateModal());
        
        // Price history modal
        this.bindModalCloseEvents('price-history-modal', () => this.closePriceHistoryModal());

        // Escape key to close modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeEditPriceModal();
                this.closeBulkUpdateModal();
                this.closePriceHistoryModal();
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
        const cancelBtn = modal.querySelector('.cancel-price-btn, .cancel-bulk-btn, .close-history-btn');
        const overlay = modal.querySelector('.modal-overlay');

        if (closeBtn) closeBtn.addEventListener('click', closeCallback);
        if (cancelBtn) cancelBtn.addEventListener('click', closeCallback);
        if (overlay) overlay.addEventListener('click', closeCallback);
    }

    /**
     * Bind form events
     */
    bindFormEvents() {
        // Edit price form submission
        const editPriceForm = document.getElementById('edit-price-form');
        if (editPriceForm) {
            editPriceForm.addEventListener('submit', (e) => this.handleEditPriceSubmit(e));
        }

        // Bulk update form submission
        const bulkUpdateForm = document.getElementById('bulk-update-form');
        if (bulkUpdateForm) {
            bulkUpdateForm.addEventListener('submit', (e) => this.handleBulkUpdateSubmit(e));
        }

        // New price input change
        const newPriceInput = document.getElementById('new-price');
        if (newPriceInput) {
            newPriceInput.addEventListener('input', () => this.updatePriceAnalysis());
        }

        // Bulk update type change
        document.querySelectorAll('input[name="bulk_type"]').forEach(radio => {
            radio.addEventListener('change', (e) => this.handleBulkTypeChange(e.target.value));
        });

        // Bulk update inputs
        const bulkPercentage = document.getElementById('bulk-percentage');
        const bulkFixed = document.getElementById('bulk-fixed');

        if (bulkPercentage) {
            bulkPercentage.addEventListener('input', () => this.updateBulkPreview());
        }

        if (bulkFixed) {
            bulkFixed.addEventListener('input', () => this.updateBulkPreview());
        }

        // Category checkboxes
        document.querySelectorAll('input[name="categories[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', () => this.updateBulkPreview());
        });
    }

    /**
     * Bind selection events
     */
    bindSelectionEvents() {
        // Select all checkbox
        const selectAllCheckbox = document.getElementById('select-all');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', (e) => this.handleSelectAll(e.target.checked));
        }
    }

    /**
     * Bind action button events
     */
    bindActionEvents() {
        // Export prices
        const exportBtn = document.querySelector('.export-prices-btn');
        if (exportBtn) {
            exportBtn.addEventListener('click', () => this.exportPrices());
        }

        // Bulk update
        const bulkUpdateBtn = document.querySelector('.bulk-update-btn');
        if (bulkUpdateBtn) {
            bulkUpdateBtn.addEventListener('click', () => this.openBulkUpdateModal());
        }

        // Price history
        const priceHistoryBtn = document.querySelector('.price-history-btn');
        if (priceHistoryBtn) {
            priceHistoryBtn.addEventListener('click', () => this.openPriceHistoryModal());
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
        const searchQuery = document.getElementById('item-search')?.value.toLowerCase() || '';
        const categoryFilter = document.getElementById('category-filter')?.value || '';
        const priceRangeFilter = document.getElementById('price-range-filter')?.value || '';
        const sortFilter = document.getElementById('sort-filter')?.value || 'name';

        // Filter items
        this.filteredItems = this.menuItems.filter(item => {
            // Search filter
            const matchesSearch = !searchQuery || 
                item.name.toLowerCase().includes(searchQuery) ||
                item.description.toLowerCase().includes(searchQuery);

            // Category filter
            const matchesCategory = !categoryFilter || item.category === categoryFilter;

            // Price range filter
            let matchesPriceRange = true;
            if (priceRangeFilter) {
                const price = item.current_price;
                switch (priceRangeFilter) {
                    case '0-10':
                        matchesPriceRange = price >= 0 && price <= 10;
                        break;
                    case '10-20':
                        matchesPriceRange = price > 10 && price <= 20;
                        break;
                    case '20-30':
                        matchesPriceRange = price > 20 && price <= 30;
                        break;
                    case '30+':
                        matchesPriceRange = price > 30;
                        break;
                }
            }

            return matchesSearch && matchesCategory && matchesPriceRange;
        });

        // Sort items
        this.sortItems(sortFilter);
        this.renderItems();
    }

    /**
     * Sort items
     */
    sortItems(sortBy) {
        this.filteredItems.sort((a, b) => {
            switch (sortBy) {
                case 'name':
                    return a.name.localeCompare(b.name);
                case 'price_asc':
                    return a.current_price - b.current_price;
                case 'price_desc':
                    return b.current_price - a.current_price;
                case 'updated':
                    return new Date(b.last_updated) - new Date(a.last_updated);
                default:
                    return a.name.localeCompare(b.name);
            }
        });
    }

    /**
     * Clear filters
     */
    clearFilters() {
        document.getElementById('item-search').value = '';
        document.getElementById('category-filter').value = '';
        document.getElementById('price-range-filter').value = '';
        document.getElementById('sort-filter').value = 'name';
        
        this.filteredItems = [...this.menuItems];
        this.sortItems('name');
        this.renderItems();
    }

    /**
     * Toggle view between table and cards
     */
    toggleView(view) {
        this.currentView = view;
        
        // Update view buttons
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.view === view);
        });

        // Show/hide views
        const tableView = document.getElementById('pricing-table-view');
        const cardsView = document.getElementById('pricing-cards-view');

        if (view === 'table') {
            tableView.style.display = 'block';
            cardsView.style.display = 'none';
        } else {
            tableView.style.display = 'none';
            cardsView.style.display = 'block';
        }

        this.renderItems();
    }

    /**
     * Handle select all
     */
    handleSelectAll(checked) {
        this.selectedItems = checked ? [...this.filteredItems.map(item => item.id)] : [];
        this.updateItemCheckboxes();
    }

    /**
     * Update item checkboxes
     */
    updateItemCheckboxes() {
        document.querySelectorAll('.item-checkbox').forEach(checkbox => {
            const itemId = parseInt(checkbox.dataset.itemId);
            checkbox.checked = this.selectedItems.includes(itemId);
        });

        // Update select all checkbox
        const selectAllCheckbox = document.getElementById('select-all');
        if (selectAllCheckbox) {
            const allSelected = this.filteredItems.length > 0 && 
                this.filteredItems.every(item => this.selectedItems.includes(item.id));
            selectAllCheckbox.checked = allSelected;
        }
    }

    /**
     * Handle item selection
     */
    handleItemSelection(itemId, checked) {
        if (checked) {
            if (!this.selectedItems.includes(itemId)) {
                this.selectedItems.push(itemId);
            }
        } else {
            this.selectedItems = this.selectedItems.filter(id => id !== itemId);
        }
        this.updateItemCheckboxes();
    }

    /**
     * Open edit price modal
     */
    openEditPriceModal(item) {
        const modal = document.getElementById('edit-price-modal');
        if (modal && item) {
            this.currentItem = item;
            this.populateEditPriceForm(item);
            
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
            
            // Focus new price input
            const newPriceInput = document.getElementById('new-price');
            if (newPriceInput) {
                setTimeout(() => newPriceInput.focus(), 100);
            }
        }
    }

    /**
     * Close edit price modal
     */
    closeEditPriceModal() {
        const modal = document.getElementById('edit-price-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
            this.currentItem = null;
        }
    }

    /**
     * Open bulk update modal
     */
    openBulkUpdateModal() {
        const modal = document.getElementById('bulk-update-modal');
        if (modal) {
            this.resetBulkUpdateForm();
            this.updateBulkPreview();
            
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
        }
    }

    /**
     * Close bulk update modal
     */
    closeBulkUpdateModal() {
        const modal = document.getElementById('bulk-update-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
        }
    }

    /**
     * Open price history modal
     */
    openPriceHistoryModal() {
        const modal = document.getElementById('price-history-modal');
        if (modal) {
            this.populatePriceHistory();
            
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
        }
    }

    /**
     * Close price history modal
     */
    closePriceHistoryModal() {
        const modal = document.getElementById('price-history-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
        }
    }

    /**
     * Handle bulk type change
     */
    handleBulkTypeChange(type) {
        const percentageInput = document.getElementById('bulk-percentage');
        const fixedInput = document.getElementById('bulk-fixed');

        if (type === 'percentage') {
            percentageInput.disabled = false;
            fixedInput.disabled = true;
        } else {
            percentageInput.disabled = true;
            fixedInput.disabled = false;
        }

        this.updateBulkPreview();
    }

    /**
     * Handle edit price form submission
     */
    handleEditPriceSubmit(e) {
        e.preventDefault();
        
        if (this.validateEditPriceForm()) {
            this.savePrice();
        }
    }

    /**
     * Handle bulk update form submission
     */
    handleBulkUpdateSubmit(e) {
        e.preventDefault();
        
        if (this.validateBulkUpdateForm()) {
            this.applyBulkUpdate();
        }
    }

    /**
     * Validate edit price form
     */
    validateEditPriceForm() {
        const form = document.getElementById('edit-price-form');
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

        // Validate new price
        const newPrice = parseFloat(document.getElementById('new-price').value);
        if (newPrice <= 0) {
            this.showFieldError(document.getElementById('new-price'), 'Price must be greater than 0');
            isValid = false;
        }

        return isValid;
    }

    /**
     * Validate bulk update form
     */
    validateBulkUpdateForm() {
        const selectedCategories = document.querySelectorAll('input[name="categories[]"]:checked');
        if (selectedCategories.length === 0) {
            this.showNotification('Please select at least one category', 'error');
            return false;
        }

        const bulkType = document.querySelector('input[name="bulk_type"]:checked').value;
        const value = bulkType === 'percentage' ? 
            parseFloat(document.getElementById('bulk-percentage').value) :
            parseFloat(document.getElementById('bulk-fixed').value);

        if (isNaN(value)) {
            this.showNotification('Please enter a valid value', 'error');
            return false;
        }

        return true;
    }

    /**
     * Save price
     */
    savePrice() {
        this.showLoading();
        
        // Simulate API call
        setTimeout(() => {
            const formData = this.getEditPriceFormData();
            
            if (this.currentItem) {
                const oldPrice = this.currentItem.current_price;
                const newPrice = formData.new_price;
                
                // Update item price
                const index = this.menuItems.findIndex(item => item.id === this.currentItem.id);
                if (index !== -1) {
                    this.menuItems[index].current_price = newPrice;
                    this.menuItems[index].last_updated = new Date().toISOString();
                    
                    // Calculate new margin
                    const cost = this.menuItems[index].cost || 0;
                    this.menuItems[index].margin = cost > 0 ? ((newPrice - cost) / cost * 100) : 0;
                }
                
                // Add to price history
                this.addToPriceHistory({
                    item_id: this.currentItem.id,
                    item_name: this.currentItem.name,
                    old_price: oldPrice,
                    new_price: newPrice,
                    change_amount: newPrice - oldPrice,
                    change_percentage: ((newPrice - oldPrice) / oldPrice * 100),
                    reason: formData.reason,
                    notes: formData.notes,
                    effective_date: formData.effective_date || new Date().toISOString(),
                    created_at: new Date().toISOString()
                });
                
                this.showNotification('Price updated successfully', 'success');
            }
            
            this.hideLoading();
            this.closeEditPriceModal();
            this.loadMenuItems();
            this.updateStatistics();
        }, 1000);
    }

    /**
     * Apply bulk update
     */
    applyBulkUpdate() {
        this.showLoading();
        
        // Simulate API call
        setTimeout(() => {
            const bulkType = document.querySelector('input[name="bulk_type"]:checked').value;
            const value = bulkType === 'percentage' ? 
                parseFloat(document.getElementById('bulk-percentage').value) :
                parseFloat(document.getElementById('bulk-fixed').value);
            
            const selectedCategories = Array.from(document.querySelectorAll('input[name="categories[]"]:checked'))
                .map(cb => cb.value);
            
            let updatedCount = 0;
            
            this.menuItems.forEach(item => {
                if (selectedCategories.includes(item.category)) {
                    const oldPrice = item.current_price;
                    let newPrice;
                    
                    if (bulkType === 'percentage') {
                        newPrice = oldPrice * (1 + value / 100);
                    } else {
                        newPrice = oldPrice + value;
                    }
                    
                    // Ensure minimum price
                    newPrice = Math.max(0.01, newPrice);
                    
                    if (newPrice !== oldPrice) {
                        item.current_price = newPrice;
                        item.last_updated = new Date().toISOString();
                        
                        // Calculate new margin
                        const cost = item.cost || 0;
                        item.margin = cost > 0 ? ((newPrice - cost) / cost * 100) : 0;
                        
                        // Add to price history
                        this.addToPriceHistory({
                            item_id: item.id,
                            item_name: item.name,
                            old_price: oldPrice,
                            new_price: newPrice,
                            change_amount: newPrice - oldPrice,
                            change_percentage: ((newPrice - oldPrice) / oldPrice * 100),
                            reason: 'bulk_update',
                            notes: `Bulk ${bulkType} update: ${value}${bulkType === 'percentage' ? '%' : ''}`,
                            effective_date: new Date().toISOString(),
                            created_at: new Date().toISOString()
                        });
                        
                        updatedCount++;
                    }
                }
            });
            
            this.hideLoading();
            this.closeBulkUpdateModal();
            this.loadMenuItems();
            this.updateStatistics();
            this.showNotification(`Updated ${updatedCount} items successfully`, 'success');
        }, 1000);
    }

    /**
     * Get edit price form data
     */
    getEditPriceFormData() {
        const form = document.getElementById('edit-price-form');
        if (!form) return {};

        const formData = new FormData(form);
        const data = {};
        
        for (let [key, value] of formData.entries()) {
            if (key === 'new_price') {
                data[key] = parseFloat(value) || 0;
            } else if (key === 'apply_to_similar') {
                data[key] = true;
            } else {
                data[key] = value;
            }
        }

        // Handle unchecked checkbox
        if (!formData.has('apply_to_similar')) data.apply_to_similar = false;

        return data;
    }

    /**
     * Load menu items
     */
    loadMenuItems() {
        this.showItemsLoading();
        
        // Simulate API call
        setTimeout(() => {
            this.filteredItems = [...this.menuItems];
            this.sortItems('name');
            this.renderItems();
            this.hideItemsLoading();
            
            if (this.menuItems.length === 0) {
                this.showEmptyState();
            }
        }, 1000);
    }

    /**
     * Render items based on current view
     */
    renderItems() {
        if (this.currentView === 'table') {
            this.renderItemsTable();
        } else {
            this.renderItemsCards();
        }

        if (this.filteredItems.length === 0 && this.menuItems.length > 0) {
            this.showEmptyState();
        } else {
            this.hideEmptyState();
        }
    }

    /**
     * Render items table
     */
    renderItemsTable() {
        const tbody = document.getElementById('pricing-table-body');
        if (!tbody) return;

        // Clear existing content except loading skeletons
        const nonLoadingRows = tbody.querySelectorAll('tr:not(.loading-row)');
        nonLoadingRows.forEach(row => row.remove());

        this.filteredItems.forEach(item => {
            const row = this.createItemRow(item);
            tbody.appendChild(row);
        });
    }

    /**
     * Render items cards
     */
    renderItemsCards() {
        const grid = document.querySelector('.pricing-cards-grid');
        if (!grid) return;

        // Clear existing content except loading skeletons
        const nonLoadingCards = grid.querySelectorAll('.pricing-card:not(.loading)');
        nonLoadingCards.forEach(card => card.remove());

        this.filteredItems.forEach(item => {
            const card = this.createItemCard(item);
            grid.appendChild(card);
        });
    }

    /**
     * Create item row for table view
     */
    createItemRow(item) {
        const row = document.createElement('tr');
        
        const marginClass = item.margin >= 50 ? 'high' : item.margin >= 25 ? 'medium' : 'low';
        
        row.innerHTML = `
            <td>
                <label class="checkbox-wrapper">
                    <input type="checkbox" class="checkbox-input item-checkbox" data-item-id="${item.id}">
                    <span class="checkbox-indicator"></span>
                </label>
            </td>
            <td>
                <div class="item-info">
                    <div class="item-image">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div class="item-details">
                        <div class="item-name">${item.name}</div>
                        <div class="item-description">${item.description}</div>
                    </div>
                </div>
            </td>
            <td><span class="category-badge ${item.category}">${this.formatCategory(item.category)}</span></td>
            <td class="price-display">£${item.current_price.toFixed(2)}</td>
            <td class="cost-display">£${(item.cost || 0).toFixed(2)}</td>
            <td class="margin-display ${marginClass}">${item.margin.toFixed(1)}%</td>
            <td class="last-updated">${this.formatDate(item.last_updated)}</td>
            <td>
                <div class="table-actions">
                    <button class="btn btn-sm btn-primary" onclick="menuPricingManager.editPrice(${item.id})">Edit Price</button>
                    <button class="btn btn-sm btn-secondary" onclick="menuPricingManager.viewHistory(${item.id})">History</button>
                </div>
            </td>
        `;
        
        // Bind checkbox event
        const checkbox = row.querySelector('.item-checkbox');
        checkbox.addEventListener('change', (e) => {
            this.handleItemSelection(item.id, e.target.checked);
        });
        
        return row;
    }

    /**
     * Create item card for cards view
     */
    createItemCard(item) {
        const card = document.createElement('div');
        card.className = 'pricing-card';
        card.onclick = () => this.editPrice(item.id);
        
        const marginClass = item.margin >= 50 ? 'high' : item.margin >= 25 ? 'medium' : 'low';
        
        card.innerHTML = `
            <div class="card-header">
                <div class="card-item-info">
                    <div class="card-item-name">${item.name}</div>
                    <span class="category-badge ${item.category}">${this.formatCategory(item.category)}</span>
                </div>
                <div class="card-price">£${item.current_price.toFixed(2)}</div>
            </div>
            <div class="card-item-description">${item.description}</div>
            <div class="card-stats">
                <div class="card-stat">
                    <div class="card-stat-value">£${(item.cost || 0).toFixed(2)}</div>
                    <div class="card-stat-label">Cost</div>
                </div>
                <div class="card-stat">
                    <div class="card-stat-value margin-display ${marginClass}">${item.margin.toFixed(1)}%</div>
                    <div class="card-stat-label">Margin</div>
                </div>
                <div class="card-stat">
                    <div class="card-stat-value">${this.formatDate(item.last_updated, true)}</div>
                    <div class="card-stat-label">Updated</div>
                </div>
            </div>
        `;
        
        return card;
    }

    /**
     * Edit price for item
     */
    editPrice(itemId) {
        const item = this.menuItems.find(i => i.id === itemId);
        if (item) {
            this.openEditPriceModal(item);
        }
    }

    /**
     * View price history for item
     */
    viewHistory(itemId) {
        const item = this.menuItems.find(i => i.id === itemId);
        if (item) {
            // Set item filter and open history modal
            const historyItemFilter = document.getElementById('history-item-filter');
            if (historyItemFilter) {
                historyItemFilter.value = itemId;
            }
            this.openPriceHistoryModal();
        }
    }

    /**
     * Export prices
     */
    exportPrices() {
        const data = {
            menu_items: this.menuItems.map(item => ({
                id: item.id,
                name: item.name,
                category: item.category,
                current_price: item.current_price,
                cost: item.cost,
                margin: item.margin,
                last_updated: item.last_updated
            })),
            exported_at: new Date().toISOString(),
            total_items: this.menuItems.length
        };
        
        const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `menu-prices-${new Date().toISOString().split('T')[0]}.json`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        
        this.showNotification('Prices exported successfully', 'success');
    }

    /**
     * Update statistics
     */
    updateStatistics() {
        const totalItems = this.menuItems.length;
        const averagePrice = totalItems > 0 ? 
            this.menuItems.reduce((sum, item) => sum + item.current_price, 0) / totalItems : 0;
        
        const recentChanges = this.priceHistory.filter(change => {
            const changeDate = new Date(change.created_at);
            const weekAgo = new Date();
            weekAgo.setDate(weekAgo.getDate() - 7);
            return changeDate >= weekAgo;
        }).length;
        
        const prices = this.menuItems.map(item => item.current_price);
        const minPrice = prices.length > 0 ? Math.min(...prices) : 0;
        const maxPrice = prices.length > 0 ? Math.max(...prices) : 0;

        // Update stat cards
        document.getElementById('total-items').textContent = totalItems;
        document.getElementById('average-price').textContent = `£${averagePrice.toFixed(2)}`;
        document.getElementById('recent-changes').textContent = recentChanges;
        document.getElementById('price-range').textContent = `£${minPrice.toFixed(0)} - £${maxPrice.toFixed(0)}`;
    }

    /**
     * Populate edit price form
     */
    populateEditPriceForm(item) {
        // Populate item details
        const itemDetails = document.getElementById('edit-item-details');
        if (itemDetails) {
            itemDetails.innerHTML = `
                <div class="edit-item-image">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div class="edit-item-info">
                    <div class="edit-item-name">${item.name}</div>
                    <div class="edit-item-category">${this.formatCategory(item.category)}</div>
                </div>
            `;
        }

        // Set current price
        const currentPriceDisplay = document.getElementById('current-price-display');
        if (currentPriceDisplay) {
            currentPriceDisplay.textContent = `£${item.current_price.toFixed(2)}`;
        }

        // Clear new price
        const newPriceInput = document.getElementById('new-price');
        if (newPriceInput) {
            newPriceInput.value = '';
        }

        // Set effective date to now
        const effectiveDateInput = document.getElementById('effective-date');
        if (effectiveDateInput) {
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            effectiveDateInput.value = now.toISOString().slice(0, 16);
        }

        this.updatePriceAnalysis();
    }

    /**
     * Update price analysis
     */
    updatePriceAnalysis() {
        if (!this.currentItem) return;

        const newPrice = parseFloat(document.getElementById('new-price')?.value) || 0;
        const currentPrice = this.currentItem.current_price;
        const cost = this.currentItem.cost || 0;

        // Calculate changes
        const changeAmount = newPrice - currentPrice;
        const changePercentage = currentPrice > 0 ? (changeAmount / currentPrice * 100) : 0;

        // Update price change display
        const priceChangeDisplay = document.getElementById('price-change-display');
        if (priceChangeDisplay) {
            const changeAmountEl = priceChangeDisplay.querySelector('.change-amount');
            const changePercentageEl = priceChangeDisplay.querySelector('.change-percentage');
            
            if (changeAmountEl) {
                changeAmountEl.textContent = `£${Math.abs(changeAmount).toFixed(2)}`;
                changeAmountEl.className = `change-amount ${changeAmount > 0 ? 'positive' : changeAmount < 0 ? 'negative' : 'neutral'}`;
                if (changeAmount > 0) {
                    changeAmountEl.textContent = `+${changeAmountEl.textContent}`;
                } else if (changeAmount < 0) {
                    changeAmountEl.textContent = `-${changeAmountEl.textContent}`;
                }
            }
            
            if (changePercentageEl) {
                changePercentageEl.textContent = `(${changePercentage >= 0 ? '+' : ''}${changePercentage.toFixed(1)}%)`;
            }
        }

        // Update analysis
        const newMargin = cost > 0 && newPrice > 0 ? ((newPrice - cost) / cost * 100) : 0;
        const profitChange = changeAmount;
        const costPercentage = newPrice > 0 ? (cost / newPrice * 100) : 0;
        
        let pricePosition = 'Average';
        const avgPrice = this.menuItems.reduce((sum, item) => sum + item.current_price, 0) / this.menuItems.length;
        if (newPrice > avgPrice * 1.2) {
            pricePosition = 'High';
        } else if (newPrice < avgPrice * 0.8) {
            pricePosition = 'Low';
        }

        document.getElementById('new-margin').textContent = `${newMargin.toFixed(1)}%`;
        document.getElementById('profit-change').textContent = `£${profitChange.toFixed(2)}`;
        document.getElementById('cost-percentage').textContent = `${costPercentage.toFixed(1)}%`;
        document.getElementById('price-position').textContent = pricePosition;
    }

    /**
     * Reset bulk update form
     */
    resetBulkUpdateForm() {
        const form = document.getElementById('bulk-update-form');
        if (form) {
            form.reset();
            
            // Enable percentage input by default
            document.getElementById('bulk-percentage').disabled = false;
            document.getElementById('bulk-fixed').disabled = true;
        }
    }

    /**
     * Update bulk preview
     */
    updateBulkPreview() {
        const bulkType = document.querySelector('input[name="bulk_type"]:checked')?.value || 'percentage';
        const value = bulkType === 'percentage' ? 
            parseFloat(document.getElementById('bulk-percentage')?.value) || 0 :
            parseFloat(document.getElementById('bulk-fixed')?.value) || 0;
        
        const selectedCategories = Array.from(document.querySelectorAll('input[name="categories[]"]:checked'))
            .map(cb => cb.value);
        
        const affectedItems = this.menuItems.filter(item => selectedCategories.includes(item.category));
        
        const affectedItemsEl = document.getElementById('affected-items');
        if (affectedItemsEl) {
            affectedItemsEl.textContent = affectedItems.length;
        }
    }

    /**
     * Populate price history
     */
    populatePriceHistory() {
        const content = document.getElementById('price-history-content');
        const itemFilter = document.getElementById('history-item-filter');
        
        if (!content) return;

        // Populate item filter
        if (itemFilter) {
            itemFilter.innerHTML = '<option value="">All Items</option>';
            this.menuItems.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                itemFilter.appendChild(option);
            });
        }

        // Filter history
        const selectedItemId = itemFilter?.value ? parseInt(itemFilter.value) : null;
        const period = parseInt(document.getElementById('history-period-filter')?.value) || 30;
        
        const cutoffDate = new Date();
        cutoffDate.setDate(cutoffDate.getDate() - period);
        
        let filteredHistory = this.priceHistory.filter(change => {
            const changeDate = new Date(change.created_at);
            const matchesItem = !selectedItemId || change.item_id === selectedItemId;
            const matchesPeriod = changeDate >= cutoffDate;
            return matchesItem && matchesPeriod;
        });

        // Sort by date (newest first)
        filteredHistory.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

        // Render history
        content.innerHTML = '';
        
        if (filteredHistory.length === 0) {
            content.innerHTML = '<p style="text-align: center; color: var(--color-text-secondary); padding: 2rem;">No price changes found for the selected period.</p>';
            return;
        }

        filteredHistory.forEach(change => {
            const historyItem = document.createElement('div');
            historyItem.className = 'history-item';
            
            const changeClass = change.change_amount > 0 ? 'positive' : change.change_amount < 0 ? 'negative' : 'neutral';
            
            historyItem.innerHTML = `
                <div class="history-info">
                    <div class="history-item-name">${change.item_name}</div>
                    <div class="history-details">${change.reason.replace('_', ' ').toUpperCase()} • ${change.notes || 'No notes'}</div>
                </div>
                <div class="history-change">
                    <div class="history-price">
                        £${change.old_price.toFixed(2)} → £${change.new_price.toFixed(2)}
                        <span class="change-amount ${changeClass}">
                            (${change.change_amount >= 0 ? '+' : ''}£${change.change_amount.toFixed(2)})
                        </span>
                    </div>
                    <div class="history-date">${this.formatDate(change.created_at)}</div>
                </div>
            `;
            
            content.appendChild(historyItem);
        });

        // Bind filter events
        if (itemFilter) {
            itemFilter.addEventListener('change', () => this.populatePriceHistory());
        }
        
        const periodFilter = document.getElementById('history-period-filter');
        if (periodFilter) {
            periodFilter.addEventListener('change', () => this.populatePriceHistory());
        }
    }

    /**
     * Add to price history
     */
    addToPriceHistory(change) {
        this.priceHistory.push({
            id: Date.now() + Math.random(),
            ...change
        });
    }

    /**
     * Generate dummy data
     */
    generateDummyData() {
        this.menuItems = [
            {
                id: 1,
                name: 'Margherita Pizza',
                description: 'Classic pizza with tomato sauce, mozzarella, and fresh basil',
                category: 'main_courses',
                current_price: 12.99,
                cost: 4.25,
                margin: 205.6,
                last_updated: '2024-01-15T10:00:00Z'
            },
            {
                id: 2,
                name: 'Caesar Salad',
                description: 'Crisp romaine lettuce with parmesan, croutons, and caesar dressing',
                category: 'appetizers',
                current_price: 8.50,
                cost: 3.15,
                margin: 169.8,
                last_updated: '2024-01-14T15:30:00Z'
            },
            {
                id: 3,
                name: 'Chocolate Brownie',
                description: 'Rich chocolate brownie served warm with vanilla ice cream',
                category: 'desserts',
                current_price: 6.99,
                cost: 2.80,
                margin: 149.6,
                last_updated: '2024-01-13T12:15:00Z'
            },
            {
                id: 4,
                name: 'Fresh Orange Juice',
                description: 'Freshly squeezed orange juice served chilled',
                category: 'beverages',
                current_price: 3.99,
                cost: 1.20,
                margin: 232.5,
                last_updated: '2024-01-12T09:45:00Z'
            },
            {
                id: 5,
                name: 'Grilled Salmon',
                description: 'Atlantic salmon grilled to perfection with lemon herb butter',
                category: 'main_courses',
                current_price: 18.99,
                cost: 8.50,
                margin: 123.4,
                last_updated: '2024-01-11T14:20:00Z'
            },
            {
                id: 6,
                name: 'Garlic Bread',
                description: 'Toasted bread with garlic butter and herbs',
                category: 'appetizers',
                current_price: 4.99,
                cost: 1.50,
                margin: 232.7,
                last_updated: '2024-01-10T11:30:00Z'
            },
            {
                id: 7,
                name: 'Tiramisu',
                description: 'Classic Italian dessert with coffee-soaked ladyfingers',
                category: 'desserts',
                current_price: 7.99,
                cost: 3.20,
                margin: 149.7,
                last_updated: '2024-01-09T16:45:00Z'
            },
            {
                id: 8,
                name: 'Cappuccino',
                description: 'Espresso with steamed milk and foam, dusted with cocoa',
                category: 'beverages',
                current_price: 3.50,
                cost: 0.85,
                margin: 311.8,
                last_updated: '2024-01-08T08:15:00Z'
            }
        ];

        // Generate price history
        this.priceHistory = [
            {
                id: 1,
                item_id: 1,
                item_name: 'Margherita Pizza',
                old_price: 11.99,
                new_price: 12.99,
                change_amount: 1.00,
                change_percentage: 8.3,
                reason: 'cost_increase',
                notes: 'Cheese supplier increased prices',
                effective_date: '2024-01-15T10:00:00Z',
                created_at: '2024-01-15T10:00:00Z'
            },
            {
                id: 2,
                item_id: 2,
                item_name: 'Caesar Salad',
                old_price: 7.99,
                new_price: 8.50,
                change_amount: 0.51,
                change_percentage: 6.4,
                reason: 'market_adjustment',
                notes: 'Adjusted to match competitor pricing',
                effective_date: '2024-01-14T15:30:00Z',
                created_at: '2024-01-14T15:30:00Z'
            },
            {
                id: 3,
                item_id: 4,
                item_name: 'Fresh Orange Juice',
                old_price: 4.50,
                new_price: 3.99,
                change_amount: -0.51,
                change_percentage: -11.3,
                reason: 'promotion',
                notes: 'Happy hour promotion pricing',
                effective_date: '2024-01-12T09:45:00Z',
                created_at: '2024-01-12T09:45:00Z'
            }
        ];
    }

    /**
     * Utility methods
     */
    formatCategory(category) {
        const categories = {
            appetizers: 'Appetizers',
            main_courses: 'Main Courses',
            desserts: 'Desserts',
            beverages: 'Beverages'
        };
        return categories[category] || category;
    }

    formatDate(dateString, short = false) {
        const date = new Date(dateString);
        if (short) {
            return date.toLocaleDateString();
        }
        return date.toLocaleString();
    }

    showLoading() {
        this.isLoading = true;
    }

    hideLoading() {
        this.isLoading = false;
    }

    showItemsLoading() {
        const loadingRows = document.querySelectorAll('.loading-row');
        const loadingCards = document.querySelectorAll('.pricing-card.loading');
        loadingRows.forEach(row => row.style.display = 'table-row');
        loadingCards.forEach(card => card.style.display = 'block');
    }

    hideItemsLoading() {
        const loadingRows = document.querySelectorAll('.loading-row');
        const loadingCards = document.querySelectorAll('.pricing-card.loading');
        loadingRows.forEach(row => row.style.display = 'none');
        loadingCards.forEach(card => card.style.display = 'none');
    }

    showEmptyState() {
        const emptyState = document.querySelector('.empty-state');
        const tableView = document.getElementById('pricing-table-view');
        const cardsView = document.getElementById('pricing-cards-view');
        
        if (emptyState) emptyState.style.display = 'block';
        if (tableView) tableView.style.display = 'none';
        if (cardsView) cardsView.style.display = 'none';
    }

    hideEmptyState() {
        const emptyState = document.querySelector('.empty-state');
        
        if (emptyState) emptyState.style.display = 'none';
        
        if (this.currentView === 'table') {
            const tableView = document.getElementById('pricing-table-view');
            if (tableView) tableView.style.display = 'block';
        } else {
            const cardsView = document.getElementById('pricing-cards-view');
            if (cardsView) cardsView.style.display = 'block';
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
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.menuPricingManager = new MenuPricingManager();
});
