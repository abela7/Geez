/**
 * Bar Pricing Page JavaScript
 * Restaurant-OS Admin Panel
 * 
 * Handles pricing management, bulk updates, and happy hour setup
 */

class BarPricingPage {
    constructor() {
        this.drinks = [];
        this.filteredDrinks = [];
        this.currentView = 'grid';
        this.isLoading = false;
        this.happyHourSettings = {
            enabled: false,
            startTime: '17:00',
            endTime: '19:00',
            discountPercentage: 20,
            applicableDays: ['friday']
        };
        
        this.init();
    }

    /**
     * Initialize the pricing page
     */
    init() {
        this.bindEvents();
        this.generateDummyData();
        this.loadPricing();
        this.updateStatistics();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Search functionality
        const searchInput = document.getElementById('drink-search');
        if (searchInput) {
            searchInput.addEventListener('input', this.debounce(this.handleSearch.bind(this), 300));
        }

        // Filter functionality
        const categoryFilter = document.getElementById('category-filter');
        if (categoryFilter) {
            categoryFilter.addEventListener('change', this.handleFilter.bind(this));
        }

        const pricingFilter = document.getElementById('pricing-filter');
        if (pricingFilter) {
            pricingFilter.addEventListener('change', this.handleFilter.bind(this));
        }

        const sortFilter = document.getElementById('sort-filter');
        if (sortFilter) {
            sortFilter.addEventListener('change', this.handleSort.bind(this));
        }

        // View toggle
        const viewButtons = document.querySelectorAll('.view-btn');
        viewButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const view = e.currentTarget.dataset.view;
                this.switchView(view);
            });
        });

        // Clear filters
        const clearFiltersBtn = document.querySelector('.clear-filters-btn');
        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', this.clearFilters.bind(this));
        }

        // Bulk update button
        const bulkUpdateBtn = document.querySelector('.bulk-update-btn');
        if (bulkUpdateBtn) {
            bulkUpdateBtn.addEventListener('click', this.showBulkUpdateModal.bind(this));
        }

        // Happy hour button
        const happyHourBtn = document.querySelector('.happy-hour-btn');
        if (happyHourBtn) {
            happyHourBtn.addEventListener('click', this.showHappyHourModal.bind(this));
        }

        // Export pricing button
        const exportBtn = document.querySelector('.export-pricing-btn');
        if (exportBtn) {
            exportBtn.addEventListener('click', this.exportPricing.bind(this));
        }

        // Modal functionality
        this.bindModalEvents();

        // Form functionality
        this.bindFormEvents();

        // Keyboard shortcuts
        document.addEventListener('keydown', this.handleKeyboardShortcuts.bind(this));
    }

    /**
     * Bind modal events
     */
    bindModalEvents() {
        // Close modal events
        const modalCloses = document.querySelectorAll('.modal-close');
        modalCloses.forEach(btn => {
            btn.addEventListener('click', this.closeModals.bind(this));
        });

        const modalOverlays = document.querySelectorAll('.modal-overlay');
        modalOverlays.forEach(overlay => {
            overlay.addEventListener('click', this.closeModals.bind(this));
        });

        const cancelBtns = document.querySelectorAll('.cancel-btn');
        cancelBtns.forEach(btn => {
            btn.addEventListener('click', this.closeModals.bind(this));
        });
    }

    /**
     * Bind form events
     */
    bindFormEvents() {
        // Update type radio buttons
        const updateTypeRadios = document.querySelectorAll('input[name="update_type"]');
        updateTypeRadios.forEach(radio => {
            radio.addEventListener('change', this.handleUpdateTypeChange.bind(this));
        });

        // Category checkboxes
        const allCategoriesCheckbox = document.querySelector('input[name="categories[]"][value="all"]');
        if (allCategoriesCheckbox) {
            allCategoriesCheckbox.addEventListener('change', this.handleAllCategoriesChange.bind(this));
        }

        // Form submissions
        const pricingForm = document.getElementById('pricing-form');
        if (pricingForm) {
            pricingForm.addEventListener('submit', this.handleBulkUpdate.bind(this));
        }

        const happyHourForm = document.getElementById('happy-hour-form');
        if (happyHourForm) {
            happyHourForm.addEventListener('submit', this.handleHappyHourUpdate.bind(this));
        }
    }

    /**
     * Generate dummy data for demonstration
     */
    generateDummyData() {
        this.drinks = [
            {
                id: 1,
                name: 'Johnnie Walker Black Label',
                category: 'spirits',
                basePrice: 18.00,
                happyHourPrice: 14.40,
                costPrice: 8.50,
                margin: 52.8,
                active: true
            },
            {
                id: 2,
                name: 'Heineken Beer',
                category: 'beer',
                basePrice: 8.00,
                happyHourPrice: 6.40,
                costPrice: 3.50,
                margin: 56.3,
                active: true
            },
            {
                id: 3,
                name: 'House Red Wine',
                category: 'wine',
                basePrice: 12.00,
                happyHourPrice: 9.60,
                costPrice: 5.00,
                margin: 58.3,
                active: true
            },
            {
                id: 4,
                name: 'Classic Martini',
                category: 'cocktails',
                basePrice: 16.00,
                happyHourPrice: 12.80,
                costPrice: 6.50,
                margin: 59.4,
                active: true
            },
            {
                id: 5,
                name: 'Virgin Mojito',
                category: 'mocktails',
                basePrice: 9.00,
                happyHourPrice: 7.20,
                costPrice: 3.00,
                margin: 66.7,
                active: true
            }
        ];
    }

    /**
     * Load and display pricing
     */
    loadPricing() {
        this.isLoading = true;
        
        // Simulate loading delay
        setTimeout(() => {
            this.filteredDrinks = [...this.drinks];
            this.renderPricing();
            this.isLoading = false;
        }, 500);
    }

    /**
     * Render pricing based on current view
     */
    renderPricing() {
        if (this.currentView === 'grid') {
            this.renderGridView();
        } else {
            this.renderListView();
        }
    }

    /**
     * Render grid view
     */
    renderGridView() {
        const gridContainer = document.getElementById('pricing-grid');
        if (!gridContainer) return;

        if (this.filteredDrinks.length === 0) {
            this.showEmptyState();
            return;
        }

        gridContainer.innerHTML = this.filteredDrinks.map(drink => {
            const marginClass = drink.margin < 40 ? 'low' : drink.margin < 60 ? 'good' : 'excellent';
            
            return `
                <div class="pricing-card" data-drink-id="${drink.id}">
                    <div class="pricing-header">
                        <h3 class="pricing-title">${drink.name}</h3>
                        <span class="pricing-category category-${drink.category}">${drink.category}</span>
                    </div>
                    <div class="pricing-body">
                        <div class="pricing-details">
                            <div class="price-row">
                                <span class="price-label">Base Price</span>
                                <span class="price-value base">$${drink.basePrice.toFixed(2)}</span>
                            </div>
                            <div class="price-row">
                                <span class="price-label">Happy Hour</span>
                                <span class="price-value happy-hour">$${drink.happyHourPrice.toFixed(2)}</span>
                            </div>
                            <div class="price-row">
                                <span class="price-label">Cost</span>
                                <span class="price-value cost">$${drink.costPrice.toFixed(2)}</span>
                            </div>
                            <div class="margin-indicator">
                                <div class="margin-bar">
                                    <div class="margin-fill ${marginClass}" style="width: ${drink.margin}%"></div>
                                </div>
                                <span class="margin-text">${drink.margin.toFixed(1)}%</span>
                            </div>
                        </div>
                    </div>
                    <div class="pricing-actions">
                        <button class="pricing-action-btn" onclick="pricingManager.editPrice(${drink.id})" title="Edit Price">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <button class="pricing-action-btn" onclick="pricingManager.viewHistory(${drink.id})" title="Price History">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `;
        }).join('');
    }

    /**
     * Render list view
     */
    renderListView() {
        const tableBody = document.querySelector('.pricing-table-body');
        if (!tableBody) return;

        if (this.filteredDrinks.length === 0) {
            this.showEmptyState();
            return;
        }

        tableBody.innerHTML = this.filteredDrinks.map(drink => {
            const marginClass = drink.margin < 40 ? 'low' : drink.margin < 60 ? 'good' : 'excellent';
            
            return `
                <tr data-drink-id="${drink.id}">
                    <td class="drink-name-cell">${drink.name}</td>
                    <td><span class="pricing-category category-${drink.category}">${drink.category}</span></td>
                    <td class="price-cell">$${drink.basePrice.toFixed(2)}</td>
                    <td class="price-cell happy-hour">$${drink.happyHourPrice.toFixed(2)}</td>
                    <td class="price-cell cost">$${drink.costPrice.toFixed(2)}</td>
                    <td class="margin-cell">
                        <span class="margin-badge ${marginClass}">${drink.margin.toFixed(1)}%</span>
                    </td>
                    <td>
                        <div class="pricing-actions">
                            <button class="pricing-action-btn" onclick="pricingManager.editPrice(${drink.id})" title="Edit Price">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button class="pricing-action-btn" onclick="pricingManager.viewHistory(${drink.id})" title="Price History">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    }

    /**
     * Switch between grid and list view
     */
    switchView(view) {
        this.currentView = view;
        
        const gridView = document.getElementById('pricing-grid');
        const listView = document.getElementById('pricing-list');
        const viewButtons = document.querySelectorAll('.view-btn');
        
        // Update button states
        viewButtons.forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.view === view) {
                btn.classList.add('active');
            }
        });
        
        // Show/hide views
        if (view === 'grid') {
            gridView.style.display = 'grid';
            listView.style.display = 'none';
            this.renderGridView();
        } else {
            gridView.style.display = 'none';
            listView.style.display = 'block';
            this.renderListView();
        }
        
        // Save preference
        localStorage.setItem('pricing_view_preference', view);
    }

    /**
     * Update statistics
     */
    updateStatistics() {
        const totalDrinks = this.drinks.length;
        const avgPrice = this.drinks.reduce((sum, d) => sum + d.basePrice, 0) / totalDrinks || 0;
        const avgMargin = this.drinks.reduce((sum, d) => sum + d.margin, 0) / totalDrinks || 0;
        const happyHourStatus = this.happyHourSettings.enabled ? 'Active' : 'Inactive';

        document.getElementById('total-drinks').textContent = totalDrinks;
        document.getElementById('avg-price').textContent = `$${avgPrice.toFixed(2)}`;
        document.getElementById('avg-margin').textContent = `${avgMargin.toFixed(1)}%`;
        document.getElementById('happy-hour-status').textContent = happyHourStatus;
    }

    /**
     * Handle search input
     */
    handleSearch(event) {
        const searchTerm = event.target.value.toLowerCase().trim();
        
        if (searchTerm === '') {
            this.filteredDrinks = [...this.drinks];
        } else {
            this.filteredDrinks = this.drinks.filter(drink => 
                drink.name.toLowerCase().includes(searchTerm) ||
                drink.category.toLowerCase().includes(searchTerm)
            );
        }
        
        this.renderPricing();
    }

    /**
     * Handle filter changes
     */
    handleFilter() {
        const categoryFilter = document.getElementById('category-filter').value;
        const pricingFilter = document.getElementById('pricing-filter').value;
        
        this.filteredDrinks = this.drinks.filter(drink => {
            // Category filter
            if (categoryFilter && drink.category !== categoryFilter) return false;
            
            // Pricing filter
            if (pricingFilter) {
                const price = drink.basePrice;
                switch (pricingFilter) {
                    case 'under-10':
                        if (price >= 10) return false;
                        break;
                    case '10-20':
                        if (price < 10 || price > 20) return false;
                        break;
                    case '20-50':
                        if (price < 20 || price > 50) return false;
                        break;
                    case 'over-50':
                        if (price <= 50) return false;
                        break;
                }
            }
            
            return true;
        });
        
        this.renderPricing();
    }

    /**
     * Handle sort changes
     */
    handleSort() {
        const sortBy = document.getElementById('sort-filter').value;
        
        this.filteredDrinks.sort((a, b) => {
            switch (sortBy) {
                case 'name':
                    return a.name.localeCompare(b.name);
                case 'price':
                    return a.basePrice - b.basePrice;
                case 'margin':
                    return b.margin - a.margin;
                case 'category':
                    return a.category.localeCompare(b.category);
                default:
                    return 0;
            }
        });
        
        this.renderPricing();
    }

    /**
     * Clear all filters
     */
    clearFilters() {
        document.getElementById('drink-search').value = '';
        document.getElementById('category-filter').value = '';
        document.getElementById('pricing-filter').value = '';
        document.getElementById('sort-filter').value = 'name';
        
        this.filteredDrinks = [...this.drinks];
        this.renderPricing();
    }

    /**
     * Show empty state
     */
    showEmptyState() {
        const gridView = document.getElementById('pricing-grid');
        const listView = document.getElementById('pricing-list');
        const emptyState = document.querySelector('.empty-state');
        
        if (gridView) gridView.style.display = 'none';
        if (listView) listView.style.display = 'none';
        if (emptyState) emptyState.style.display = 'block';
    }

    /**
     * Show bulk update modal
     */
    showBulkUpdateModal() {
        const modal = document.getElementById('pricing-modal');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }

    /**
     * Show happy hour modal
     */
    showHappyHourModal() {
        const modal = document.getElementById('happy-hour-modal');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            // Populate current settings
            this.populateHappyHourForm();
        }
    }

    /**
     * Close all modals
     */
    closeModals() {
        const modals = document.querySelectorAll('.pricing-modal, .happy-hour-modal');
        modals.forEach(modal => {
            modal.style.display = 'none';
        });
        document.body.style.overflow = '';
    }

    /**
     * Handle update type change
     */
    handleUpdateTypeChange(event) {
        const updateType = event.target.value;
        
        // Hide all input groups
        document.getElementById('percentage-input').style.display = 'none';
        document.getElementById('fixed-input').style.display = 'none';
        document.getElementById('margin-input').style.display = 'none';
        
        // Show relevant input group
        document.getElementById(`${updateType}-input`).style.display = 'block';
    }

    /**
     * Handle all categories checkbox
     */
    handleAllCategoriesChange(event) {
        const categoryCheckboxes = document.querySelectorAll('input[name="categories[]"]:not([value="all"])');
        
        if (event.target.checked) {
            categoryCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
                checkbox.disabled = true;
            });
        } else {
            categoryCheckboxes.forEach(checkbox => {
                checkbox.disabled = false;
            });
        }
    }

    /**
     * Populate happy hour form
     */
    populateHappyHourForm() {
        const form = document.getElementById('happy-hour-form');
        if (!form) return;
        
        const settings = this.happyHourSettings;
        
        form.querySelector('input[name="happy_hour_enabled"]').checked = settings.enabled;
        form.querySelector('input[name="start_time"]').value = settings.startTime;
        form.querySelector('input[name="end_time"]').value = settings.endTime;
        form.querySelector('input[name="discount_percentage"]').value = settings.discountPercentage;
        
        // Set applicable days
        settings.applicableDays.forEach(day => {
            const checkbox = form.querySelector(`input[name="applicable_days[]"][value="${day}"]`);
            if (checkbox) checkbox.checked = true;
        });
    }

    /**
     * Handle bulk update form submission
     */
    handleBulkUpdate(event) {
        event.preventDefault();
        
        const formData = new FormData(event.target);
        const updateType = formData.get('update_type');
        const categories = formData.getAll('categories[]');
        
        let value;
        switch (updateType) {
            case 'percentage':
                value = parseFloat(formData.get('percentage_value'));
                break;
            case 'fixed':
                value = parseFloat(formData.get('fixed_value'));
                break;
            case 'margin':
                value = parseFloat(formData.get('margin_value'));
                break;
        }
        
        this.applyBulkUpdate(updateType, value, categories);
        this.closeModals();
    }

    /**
     * Apply bulk price update
     */
    applyBulkUpdate(updateType, value, categories) {
        this.drinks.forEach(drink => {
            if (categories.includes('all') || categories.includes(drink.category)) {
                switch (updateType) {
                    case 'percentage':
                        drink.basePrice *= (1 + value / 100);
                        drink.happyHourPrice = drink.basePrice * 0.8; // 20% discount
                        break;
                    case 'fixed':
                        drink.basePrice += value;
                        drink.happyHourPrice = drink.basePrice * 0.8;
                        break;
                    case 'margin':
                        drink.basePrice = drink.costPrice / (1 - value / 100);
                        drink.happyHourPrice = drink.basePrice * 0.8;
                        break;
                }
                
                // Recalculate margin
                drink.margin = ((drink.basePrice - drink.costPrice) / drink.basePrice) * 100;
            }
        });
        
        this.filteredDrinks = [...this.drinks];
        this.renderPricing();
        this.updateStatistics();
        
        this.showNotification('Pricing updated successfully', 'success');
    }

    /**
     * Handle happy hour form submission
     */
    handleHappyHourUpdate(event) {
        event.preventDefault();
        
        const formData = new FormData(event.target);
        
        this.happyHourSettings = {
            enabled: formData.has('happy_hour_enabled'),
            startTime: formData.get('start_time'),
            endTime: formData.get('end_time'),
            discountPercentage: parseFloat(formData.get('discount_percentage')),
            applicableDays: formData.getAll('applicable_days[]')
        };
        
        // Update happy hour prices
        const discountMultiplier = 1 - (this.happyHourSettings.discountPercentage / 100);
        this.drinks.forEach(drink => {
            drink.happyHourPrice = drink.basePrice * discountMultiplier;
        });
        
        this.filteredDrinks = [...this.drinks];
        this.renderPricing();
        this.updateStatistics();
        this.closeModals();
        
        this.showNotification('Happy hour settings updated successfully', 'success');
    }

    /**
     * Edit individual price
     */
    editPrice(drinkId) {
        const drink = this.drinks.find(d => d.id === drinkId);
        if (!drink) return;

        const newPrice = prompt(`Enter new price for ${drink.name}:`, drink.basePrice.toFixed(2));
        if (newPrice && !isNaN(parseFloat(newPrice))) {
            drink.basePrice = parseFloat(newPrice);
            drink.happyHourPrice = drink.basePrice * 0.8;
            drink.margin = ((drink.basePrice - drink.costPrice) / drink.basePrice) * 100;
            
            this.filteredDrinks = [...this.drinks];
            this.renderPricing();
            this.updateStatistics();
            
            this.showNotification(`Price updated for ${drink.name}`, 'success');
        }
    }

    /**
     * View price history
     */
    viewHistory(drinkId) {
        const drink = this.drinks.find(d => d.id === drinkId);
        if (!drink) return;

        alert(`Price history for ${drink.name} will be implemented`);
    }

    /**
     * Export pricing
     */
    exportPricing() {
        alert('Export pricing functionality will be implemented');
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
                case 'k':
                    event.preventDefault();
                    document.getElementById('drink-search').focus();
                    break;
                case '1':
                    event.preventDefault();
                    this.switchView('grid');
                    break;
                case '2':
                    event.preventDefault();
                    this.switchView('list');
                    break;
                case 'u':
                    event.preventDefault();
                    this.showBulkUpdateModal();
                    break;
                case 'h':
                    event.preventDefault();
                    this.showHappyHourModal();
                    break;
            }
        }
        
        if (event.key === 'Escape') {
            this.closeModals();
        }
    }

    /**
     * Debounce utility function
     */
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
}

// Initialize the page when DOM is loaded
let pricingManager;

document.addEventListener('DOMContentLoaded', function() {
    pricingManager = new BarPricingPage();
    
    // Restore view preference
    const savedView = localStorage.getItem('pricing_view_preference') || 'grid';
    pricingManager.switchView(savedView);
});
