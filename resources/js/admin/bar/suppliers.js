/**
 * Bar Suppliers Page JavaScript
 * Restaurant-OS Admin Panel
 * 
 * Handles supplier management, creation, editing, and organization
 */

class BarSuppliersPage {
    constructor() {
        this.suppliers = [];
        this.filteredSuppliers = [];
        this.currentView = 'grid';
        this.currentSupplier = null;
        this.isLoading = false;
        
        this.init();
    }

    /**
     * Initialize the suppliers page
     */
    init() {
        this.bindEvents();
        this.generateDummyData();
        this.loadSuppliers();
        this.updateStatistics();
        this.setupFormTabs();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Search functionality
        const searchInput = document.getElementById('supplier-search');
        if (searchInput) {
            searchInput.addEventListener('input', this.debounce(this.handleSearch.bind(this), 300));
        }

        // Filter functionality
        const specialtyFilter = document.getElementById('specialty-filter');
        if (specialtyFilter) {
            specialtyFilter.addEventListener('change', this.handleFilter.bind(this));
        }

        const ratingFilter = document.getElementById('rating-filter');
        if (ratingFilter) {
            ratingFilter.addEventListener('change', this.handleFilter.bind(this));
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

        // Add supplier buttons
        const addSupplierButtons = document.querySelectorAll('.add-supplier-btn');
        addSupplierButtons.forEach(btn => {
            btn.addEventListener('click', this.showAddSupplierModal.bind(this));
        });

        // Export suppliers button
        const exportSuppliersBtn = document.querySelector('.export-suppliers-btn');
        if (exportSuppliersBtn) {
            exportSuppliersBtn.addEventListener('click', this.exportSuppliers.bind(this));
        }

        // Import suppliers button
        const importSuppliersBtn = document.querySelector('.import-suppliers-btn');
        if (importSuppliersBtn) {
            importSuppliersBtn.addEventListener('click', this.importSuppliers.bind(this));
        }

        // Modal functionality
        this.bindModalEvents();

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

        const cancelBtns = document.querySelectorAll('.cancel-btn, .close-details-btn');
        cancelBtns.forEach(btn => {
            btn.addEventListener('click', this.closeModals.bind(this));
        });

        // Form submission
        const supplierForm = document.getElementById('supplier-form');
        if (supplierForm) {
            supplierForm.addEventListener('submit', this.handleSupplierSubmit.bind(this));
        }
    }

    /**
     * Setup form tabs functionality
     */
    setupFormTabs() {
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabPanels = document.querySelectorAll('.tab-panel');

        tabButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const targetTab = e.target.dataset.tab;
                
                // Update button states
                tabButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                
                // Update panel visibility
                tabPanels.forEach(panel => {
                    panel.classList.remove('active');
                    if (panel.dataset.tab === targetTab) {
                        panel.classList.add('active');
                    }
                });
            });
        });
    }

    /**
     * Generate dummy data for demonstration
     */
    generateDummyData() {
        this.suppliers = [
            {
                id: 1,
                name: 'Premium Wine Distributors',
                specialty: 'wine_distributor',
                contactPerson: 'Sarah Johnson',
                phoneNumber: '+1-555-0123',
                emailAddress: 'sarah@premiumwines.com',
                website: 'https://premiumwines.com',
                address: '123 Wine Street, Napa Valley, CA',
                paymentTerms: 'net_30',
                deliveryDays: 3,
                minimumOrder: 500.00,
                deliveryRating: 4.8,
                qualityRating: 4.9,
                priceRating: 4.2,
                lastOrderDate: '2024-01-15',
                totalOrders: 24,
                averageDeliveryTime: 2.5,
                active: true
            },
            {
                id: 2,
                name: 'Craft Beer Supply Co.',
                specialty: 'beer_distributor',
                contactPerson: 'Mike Rodriguez',
                phoneNumber: '+1-555-0456',
                emailAddress: 'mike@craftbeersupply.com',
                website: 'https://craftbeersupply.com',
                address: '456 Brewery Lane, Portland, OR',
                paymentTerms: 'net_15',
                deliveryDays: 2,
                minimumOrder: 300.00,
                deliveryRating: 4.9,
                qualityRating: 4.7,
                priceRating: 4.5,
                lastOrderDate: '2024-01-20',
                totalOrders: 18,
                averageDeliveryTime: 1.8,
                active: true
            },
            {
                id: 3,
                name: 'Elite Spirits International',
                specialty: 'spirits_distributor',
                contactPerson: 'James Wilson',
                phoneNumber: '+1-555-0789',
                emailAddress: 'james@elitespirits.com',
                website: 'https://elitespirits.com',
                address: '789 Distillery Road, Louisville, KY',
                paymentTerms: 'net_30',
                deliveryDays: 5,
                minimumOrder: 1000.00,
                deliveryRating: 4.6,
                qualityRating: 4.8,
                priceRating: 3.9,
                lastOrderDate: '2024-01-10',
                totalOrders: 32,
                averageDeliveryTime: 4.2,
                active: true
            },
            {
                id: 4,
                name: 'Fresh Coffee Roasters',
                specialty: 'coffee_supplier',
                contactPerson: 'Lisa Chen',
                phoneNumber: '+1-555-0321',
                emailAddress: 'lisa@freshcoffee.com',
                website: 'https://freshcoffee.com',
                address: '321 Roast Avenue, Seattle, WA',
                paymentTerms: 'net_15',
                deliveryDays: 1,
                minimumOrder: 150.00,
                deliveryRating: 5.0,
                qualityRating: 4.9,
                priceRating: 4.6,
                lastOrderDate: '2024-01-25',
                totalOrders: 45,
                averageDeliveryTime: 1.2,
                active: true
            }
        ];
    }

    /**
     * Load and display suppliers
     */
    loadSuppliers() {
        this.isLoading = true;
        
        // Simulate loading delay
        setTimeout(() => {
            this.filteredSuppliers = [...this.suppliers];
            this.renderSuppliers();
            this.isLoading = false;
        }, 500);
    }

    /**
     * Render suppliers based on current view
     */
    renderSuppliers() {
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
        const gridContainer = document.getElementById('suppliers-grid');
        if (!gridContainer) return;

        if (this.filteredSuppliers.length === 0) {
            this.showEmptyState();
            return;
        }

        gridContainer.innerHTML = this.filteredSuppliers.map(supplier => {
            const avgRating = ((supplier.deliveryRating + supplier.qualityRating + supplier.priceRating) / 3).toFixed(1);
            const stars = this.generateStars(avgRating);
            
            return `
                <div class="supplier-card" data-supplier-id="${supplier.id}">
                    <div class="supplier-header">
                        <h3 class="supplier-title">${supplier.name}</h3>
                        <span class="supplier-specialty specialty-${supplier.specialty}">${supplier.specialty.replace('_', ' ')}</span>
                    </div>
                    <div class="supplier-body" onclick="suppliersManager.showSupplierDetails(${supplier.id})">
                        <div class="supplier-details">
                            <div class="supplier-contact">
                                ${supplier.contactPerson ? `
                                <div class="contact-item">
                                    <svg class="contact-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    ${supplier.contactPerson}
                                </div>
                                ` : ''}
                                ${supplier.phoneNumber ? `
                                <div class="contact-item">
                                    <svg class="contact-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    ${supplier.phoneNumber}
                                </div>
                                ` : ''}
                                ${supplier.emailAddress ? `
                                <div class="contact-item">
                                    <svg class="contact-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    ${supplier.emailAddress}
                                </div>
                                ` : ''}
                            </div>
                            <div class="supplier-rating">
                                <div class="rating-stars">${stars}</div>
                                <span class="rating-text">${avgRating}/5 (${supplier.totalOrders} orders)</span>
                            </div>
                        </div>
                    </div>
                    <div class="supplier-actions">
                        <button class="supplier-action-btn" onclick="event.stopPropagation(); suppliersManager.editSupplier(${supplier.id})" title="Edit Supplier">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <button class="supplier-action-btn" onclick="event.stopPropagation(); suppliersManager.contactSupplier(${supplier.id})" title="Contact Supplier">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </button>
                        <button class="supplier-action-btn" onclick="event.stopPropagation(); suppliersManager.placeOrder(${supplier.id})" title="Place Order">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
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
        const tableBody = document.querySelector('.suppliers-table-body');
        if (!tableBody) return;

        if (this.filteredSuppliers.length === 0) {
            this.showEmptyState();
            return;
        }

        tableBody.innerHTML = this.filteredSuppliers.map(supplier => {
            const avgRating = ((supplier.deliveryRating + supplier.qualityRating + supplier.priceRating) / 3).toFixed(1);
            const ratingClass = avgRating >= 4.5 ? 'excellent' : avgRating >= 4.0 ? 'good' : avgRating >= 3.0 ? 'average' : 'poor';
            
            return `
                <tr data-supplier-id="${supplier.id}" onclick="suppliersManager.showSupplierDetails(${supplier.id})">
                    <td class="supplier-name-cell">${supplier.name}</td>
                    <td><span class="supplier-specialty specialty-${supplier.specialty}">${supplier.specialty.replace('_', ' ')}</span></td>
                    <td class="supplier-contact-cell">${supplier.contactPerson || 'N/A'}</td>
                    <td class="supplier-contact-cell">${supplier.phoneNumber || 'N/A'}</td>
                    <td class="rating-cell">
                        <span class="rating-badge rating-${ratingClass}">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            ${avgRating}
                        </span>
                    </td>
                    <td class="supplier-contact-cell">${new Date(supplier.lastOrderDate).toLocaleDateString()}</td>
                    <td>
                        <div class="supplier-actions">
                            <button class="supplier-action-btn" onclick="event.stopPropagation(); suppliersManager.editSupplier(${supplier.id})" title="Edit">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button class="supplier-action-btn" onclick="event.stopPropagation(); suppliersManager.contactSupplier(${supplier.id})" title="Contact">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </button>
                            <button class="supplier-action-btn" onclick="event.stopPropagation(); suppliersManager.placeOrder(${supplier.id})" title="Order">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    }

    /**
     * Generate star rating HTML
     */
    generateStars(rating) {
        const fullStars = Math.floor(rating);
        const hasHalfStar = rating % 1 >= 0.5;
        const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
        
        let starsHTML = '';
        
        // Full stars
        for (let i = 0; i < fullStars; i++) {
            starsHTML += '<svg class="star" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>';
        }
        
        // Half star
        if (hasHalfStar) {
            starsHTML += '<svg class="star" fill="currentColor" viewBox="0 0 20 20"><defs><linearGradient id="half"><stop offset="50%" stop-color="currentColor"/><stop offset="50%" stop-color="#d1d5db"/></linearGradient></defs><path fill="url(#half)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>';
        }
        
        // Empty stars
        for (let i = 0; i < emptyStars; i++) {
            starsHTML += '<svg class="star empty" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>';
        }
        
        return starsHTML;
    }

    /**
     * Switch between grid and list view
     */
    switchView(view) {
        this.currentView = view;
        
        const gridView = document.getElementById('suppliers-grid');
        const listView = document.getElementById('suppliers-list');
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
        localStorage.setItem('suppliers_view_preference', view);
    }

    /**
     * Update statistics
     */
    updateStatistics() {
        const totalSuppliers = this.suppliers.length;
        const activeSuppliers = this.suppliers.filter(s => s.active).length;
        const totalOrders = this.suppliers.reduce((sum, s) => sum + s.totalOrders, 0);
        const avgRating = this.suppliers.reduce((sum, s) => {
            return sum + (s.deliveryRating + s.qualityRating + s.priceRating) / 3;
        }, 0) / totalSuppliers || 0;

        document.getElementById('total-suppliers').textContent = totalSuppliers;
        document.getElementById('active-suppliers').textContent = activeSuppliers;
        document.getElementById('total-orders').textContent = totalOrders;
        document.getElementById('avg-rating').textContent = avgRating.toFixed(1);
    }

    /**
     * Handle search input
     */
    handleSearch(event) {
        const searchTerm = event.target.value.toLowerCase().trim();
        
        if (searchTerm === '') {
            this.filteredSuppliers = [...this.suppliers];
        } else {
            this.filteredSuppliers = this.suppliers.filter(supplier => 
                supplier.name.toLowerCase().includes(searchTerm) ||
                supplier.contactPerson.toLowerCase().includes(searchTerm) ||
                supplier.specialty.toLowerCase().includes(searchTerm)
            );
        }
        
        this.renderSuppliers();
    }

    /**
     * Handle filter changes
     */
    handleFilter() {
        const specialtyFilter = document.getElementById('specialty-filter').value;
        const ratingFilter = document.getElementById('rating-filter').value;
        
        this.filteredSuppliers = this.suppliers.filter(supplier => {
            // Specialty filter
            if (specialtyFilter && supplier.specialty !== specialtyFilter) return false;
            
            // Rating filter
            if (ratingFilter) {
                const avgRating = (supplier.deliveryRating + supplier.qualityRating + supplier.priceRating) / 3;
                const minRating = parseFloat(ratingFilter);
                if (avgRating < minRating) return false;
            }
            
            return true;
        });
        
        this.renderSuppliers();
    }

    /**
     * Handle sort changes
     */
    handleSort() {
        const sortBy = document.getElementById('sort-filter').value;
        
        this.filteredSuppliers.sort((a, b) => {
            switch (sortBy) {
                case 'name':
                    return a.name.localeCompare(b.name);
                case 'rating':
                    const avgA = (a.deliveryRating + a.qualityRating + a.priceRating) / 3;
                    const avgB = (b.deliveryRating + b.qualityRating + b.priceRating) / 3;
                    return avgB - avgA;
                case 'orders':
                    return b.totalOrders - a.totalOrders;
                case 'delivery_time':
                    return a.averageDeliveryTime - b.averageDeliveryTime;
                default:
                    return 0;
            }
        });
        
        this.renderSuppliers();
    }

    /**
     * Clear all filters
     */
    clearFilters() {
        document.getElementById('supplier-search').value = '';
        document.getElementById('specialty-filter').value = '';
        document.getElementById('rating-filter').value = '';
        document.getElementById('sort-filter').value = 'name';
        
        this.filteredSuppliers = [...this.suppliers];
        this.renderSuppliers();
    }

    /**
     * Show empty state
     */
    showEmptyState() {
        const gridView = document.getElementById('suppliers-grid');
        const listView = document.getElementById('suppliers-list');
        const emptyState = document.querySelector('.empty-state');
        
        if (gridView) gridView.style.display = 'none';
        if (listView) listView.style.display = 'none';
        if (emptyState) emptyState.style.display = 'block';
    }

    /**
     * Show add supplier modal
     */
    showAddSupplierModal() {
        const modal = document.getElementById('supplier-modal');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            // Reset form
            this.resetSupplierForm();
            
            // Update modal title
            document.getElementById('supplier-modal-title').textContent = 'Add Supplier';
        }
    }

    /**
     * Show supplier details modal
     */
    showSupplierDetails(supplierId) {
        const supplier = this.suppliers.find(s => s.id === supplierId);
        if (!supplier) return;

        const modal = document.getElementById('supplier-details-modal');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            // Populate details
            this.populateSupplierDetails(supplier);
        }
    }

    /**
     * Populate supplier details
     */
    populateSupplierDetails(supplier) {
        const container = document.getElementById('supplier-details-content');
        if (!container) return;
        
        const avgRating = ((supplier.deliveryRating + supplier.qualityRating + supplier.priceRating) / 3).toFixed(1);
        
        container.innerHTML = `
            <div class="supplier-details-content">
                <!-- Basic Information -->
                <div class="detail-section">
                    <h4 class="detail-section-title">Basic Information</h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="detail-label">Supplier Name</span>
                            <span class="detail-value">${supplier.name}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Specialty</span>
                            <span class="detail-value">${supplier.specialty.replace('_', ' ')}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Status</span>
                            <span class="detail-value">${supplier.active ? 'Active' : 'Inactive'}</span>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="detail-section">
                    <h4 class="detail-section-title">Contact Information</h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="detail-label">Contact Person</span>
                            <span class="detail-value">${supplier.contactPerson || 'N/A'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Phone</span>
                            <span class="detail-value">${supplier.phoneNumber || 'N/A'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Email</span>
                            <span class="detail-value">${supplier.emailAddress || 'N/A'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Website</span>
                            <span class="detail-value">${supplier.website || 'N/A'}</span>
                        </div>
                    </div>
                </div>

                <!-- Performance Metrics -->
                <div class="detail-section">
                    <h4 class="detail-section-title">Performance Metrics</h4>
                    <div class="performance-metrics">
                        <div class="metric-card">
                            <div class="metric-value">${supplier.deliveryRating}</div>
                            <div class="metric-label">Delivery Rating</div>
                        </div>
                        <div class="metric-card">
                            <div class="metric-value">${supplier.qualityRating}</div>
                            <div class="metric-label">Quality Rating</div>
                        </div>
                        <div class="metric-card">
                            <div class="metric-value">${supplier.priceRating}</div>
                            <div class="metric-label">Price Rating</div>
                        </div>
                    </div>
                </div>

                <!-- Business Terms -->
                <div class="detail-section">
                    <h4 class="detail-section-title">Business Terms</h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="detail-label">Payment Terms</span>
                            <span class="detail-value">${supplier.paymentTerms.replace('_', ' ').toUpperCase()}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Delivery Days</span>
                            <span class="detail-value">${supplier.deliveryDays} days</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Minimum Order</span>
                            <span class="detail-value">$${supplier.minimumOrder.toFixed(2)}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Total Orders</span>
                            <span class="detail-value">${supplier.totalOrders}</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    /**
     * Close all modals
     */
    closeModals() {
        const modals = document.querySelectorAll('.supplier-modal, .supplier-details-modal');
        modals.forEach(modal => {
            modal.style.display = 'none';
        });
        document.body.style.overflow = '';
    }

    /**
     * Reset supplier form
     */
    resetSupplierForm() {
        const form = document.getElementById('supplier-form');
        if (form) {
            form.reset();
            
            // Reset to first tab
            document.querySelector('.tab-btn[data-tab="basic"]').click();
        }
    }

    /**
     * Handle supplier form submission
     */
    handleSupplierSubmit(event) {
        event.preventDefault();
        
        const formData = new FormData(event.target);
        
        // Process form data
        const supplierData = {
            name: formData.get('supplier_name'),
            specialty: formData.get('specialty'),
            contactPerson: formData.get('contact_person'),
            phoneNumber: formData.get('phone_number'),
            emailAddress: formData.get('email_address'),
            active: formData.has('active')
        };
        
        this.saveSupplier(supplierData);
    }

    /**
     * Save supplier data
     */
    saveSupplier(supplierData) {
        // Simulate saving
        this.showNotification('Supplier saved successfully', 'success');
        this.closeModals();
        
        // In real implementation, this would make an API call
        console.log('Saving supplier:', supplierData);
    }

    /**
     * Edit supplier
     */
    editSupplier(supplierId) {
        const supplier = this.suppliers.find(s => s.id === supplierId);
        if (!supplier) return;

        this.showNotification(`Edit supplier: ${supplier.name}`, 'info');
    }

    /**
     * Contact supplier
     */
    contactSupplier(supplierId) {
        const supplier = this.suppliers.find(s => s.id === supplierId);
        if (!supplier) return;

        if (supplier.phoneNumber) {
            window.open(`tel:${supplier.phoneNumber}`);
        } else if (supplier.emailAddress) {
            window.open(`mailto:${supplier.emailAddress}`);
        } else {
            this.showNotification('No contact information available', 'warning');
        }
    }

    /**
     * Place order with supplier
     */
    placeOrder(supplierId) {
        const supplier = this.suppliers.find(s => s.id === supplierId);
        if (!supplier) return;

        this.showNotification(`Place order functionality for ${supplier.name} will be implemented`, 'info');
    }

    /**
     * Export suppliers
     */
    exportSuppliers() {
        this.showNotification('Exporting suppliers data...', 'info');
        
        setTimeout(() => {
            this.showNotification('Suppliers exported successfully', 'success');
        }, 1500);
    }

    /**
     * Import suppliers
     */
    importSuppliers() {
        this.showNotification('Import suppliers functionality will be implemented', 'info');
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
                    document.getElementById('supplier-search').focus();
                    break;
                case '1':
                    event.preventDefault();
                    this.switchView('grid');
                    break;
                case '2':
                    event.preventDefault();
                    this.switchView('list');
                    break;
                case 'n':
                    event.preventDefault();
                    this.showAddSupplierModal();
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
let suppliersManager;

document.addEventListener('DOMContentLoaded', function() {
    suppliersManager = new BarSuppliersPage();
    
    // Restore view preference
    const savedView = localStorage.getItem('suppliers_view_preference') || 'grid';
    suppliersManager.switchView(savedView);
});
