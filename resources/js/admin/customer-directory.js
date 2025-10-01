/**
 * Customer Directory Page JavaScript
 * Restaurant-OS Admin Panel
 * 
 * Handles customer management, search, filtering, and CRUD operations
 */

class CustomerDirectoryManager {
    constructor() {
        this.customers = [];
        this.filteredCustomers = [];
        this.currentView = 'grid';
        this.searchTerm = '';
        this.filters = {
            status: '',
            visitFrequency: '',
            location: ''
        };
        this.currentCustomer = null;
        this.isEditing = false;
        
        this.init();
    }

    /**
     * Initialize the customer directory manager
     */
    init() {
        this.bindEvents();
        this.generateDummyCustomers();
        this.updateStatistics();
        this.renderCustomers();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Search and filter events
        this.bindSearchEvents();
        
        // View toggle events
        this.bindViewEvents();
        
        // Customer CRUD events
        this.bindCustomerEvents();
        
        // Modal events
        this.bindModalEvents();
        
        // Action button events
        this.bindActionEvents();
        
        // Form events
        this.bindFormEvents();
    }

    /**
     * Bind search and filter events
     */
    bindSearchEvents() {
        const searchInput = document.getElementById('customer-search');
        const statusFilter = document.getElementById('status-filter');
        const visitFrequencyFilter = document.getElementById('visit-frequency-filter');
        const locationFilter = document.getElementById('location-filter');
        const clearFiltersBtn = document.querySelector('.clear-filters-btn');

        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                this.searchTerm = e.target.value.toLowerCase();
                this.filterAndRenderCustomers();
            });
        }

        if (statusFilter) {
            statusFilter.addEventListener('change', (e) => {
                this.filters.status = e.target.value;
                this.filterAndRenderCustomers();
            });
        }

        if (visitFrequencyFilter) {
            visitFrequencyFilter.addEventListener('change', (e) => {
                this.filters.visitFrequency = e.target.value;
                this.filterAndRenderCustomers();
            });
        }

        if (locationFilter) {
            locationFilter.addEventListener('change', (e) => {
                this.filters.location = e.target.value;
                this.filterAndRenderCustomers();
            });
        }

        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', () => this.clearFilters());
        }
    }

    /**
     * Bind view toggle events
     */
    bindViewEvents() {
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const view = e.currentTarget.dataset.view;
                this.switchView(view);
            });
        });
    }

    /**
     * Bind customer CRUD events
     */
    bindCustomerEvents() {
        // Add customer button
        const addCustomerBtn = document.querySelector('.add-customer-btn');
        const addFirstCustomerBtn = document.querySelector('.add-first-customer-btn');
        
        if (addCustomerBtn) {
            addCustomerBtn.addEventListener('click', () => this.openCustomerModal());
        }
        
        if (addFirstCustomerBtn) {
            addFirstCustomerBtn.addEventListener('click', () => this.openCustomerModal());
        }

        // Event delegation for customer cards and table rows
        document.addEventListener('click', (e) => {
            // Customer card click
            if (e.target.closest('.customer-card')) {
                const customerId = parseInt(e.target.closest('.customer-card').dataset.customerId);
                this.openCustomerDetails(customerId);
            }
            
            // Action buttons
            if (e.target.closest('.action-btn.edit')) {
                e.stopPropagation();
                const customerId = parseInt(e.target.closest('[data-customer-id]').dataset.customerId);
                this.editCustomer(customerId);
            }
            
            if (e.target.closest('.action-btn.delete')) {
                e.stopPropagation();
                const customerId = parseInt(e.target.closest('[data-customer-id]').dataset.customerId);
                this.deleteCustomer(customerId);
            }
        });
    }

    /**
     * Bind modal events
     */
    bindModalEvents() {
        // Customer modal
        this.bindModalCloseEvents('customer-modal', () => this.closeCustomerModal());
        
        // Customer details modal
        this.bindModalCloseEvents('customer-details-modal', () => this.closeCustomerDetails());
        
        // Edit customer from details modal
        const editCustomerBtn = document.querySelector('.edit-customer-btn');
        if (editCustomerBtn) {
            editCustomerBtn.addEventListener('click', () => {
                this.closeCustomerDetails();
                this.editCustomer(this.currentCustomer.id);
            });
        }

        // Escape key to close modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeCustomerModal();
                this.closeCustomerDetails();
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
        const cancelBtn = modal.querySelector('.cancel-customer-btn, .close-details-btn');
        const overlay = modal.querySelector('.modal-overlay');

        if (closeBtn) closeBtn.addEventListener('click', closeCallback);
        if (cancelBtn) cancelBtn.addEventListener('click', closeCallback);
        if (overlay) overlay.addEventListener('click', closeCallback);
    }

    /**
     * Bind action button events
     */
    bindActionEvents() {
        // Import customers
        const importBtn = document.querySelector('.import-customers-btn');
        if (importBtn) {
            importBtn.addEventListener('click', () => this.importCustomers());
        }

        // Export customers
        const exportBtn = document.querySelector('.export-customers-btn');
        if (exportBtn) {
            exportBtn.addEventListener('click', () => this.exportCustomers());
        }
    }

    /**
     * Bind form events
     */
    bindFormEvents() {
        const customerForm = document.getElementById('customer-form');
        if (customerForm) {
            customerForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.saveCustomer();
            });
        }
    }

    /**
     * Generate dummy customer data
     */
    generateDummyCustomers() {
        const firstNames = ['John', 'Jane', 'Michael', 'Sarah', 'David', 'Emily', 'Robert', 'Lisa', 'James', 'Maria', 'William', 'Jennifer', 'Richard', 'Patricia', 'Charles', 'Linda', 'Thomas', 'Barbara', 'Christopher', 'Susan'];
        const lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Gonzalez', 'Wilson', 'Anderson', 'Thomas', 'Taylor', 'Moore', 'Jackson', 'Martin'];
        const domains = ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 'company.com'];
        const statuses = ['active', 'inactive', 'vip'];
        const visitFrequencies = ['frequent', 'regular', 'occasional', 'new'];
        const locations = ['local', 'nearby', 'distant'];
        const cities = ['London', 'Manchester', 'Birmingham', 'Leeds', 'Glasgow', 'Sheffield', 'Bradford', 'Liverpool', 'Edinburgh', 'Bristol'];
        
        this.customers = [];
        
        for (let i = 1; i <= 50; i++) {
            const firstName = firstNames[Math.floor(Math.random() * firstNames.length)];
            const lastName = lastNames[Math.floor(Math.random() * lastNames.length)];
            const email = `${firstName.toLowerCase()}.${lastName.toLowerCase()}@${domains[Math.floor(Math.random() * domains.length)]}`;
            const status = statuses[Math.floor(Math.random() * statuses.length)];
            const visitFrequency = visitFrequencies[Math.floor(Math.random() * visitFrequencies.length)];
            const location = locations[Math.floor(Math.random() * locations.length)];
            const city = cities[Math.floor(Math.random() * cities.length)];
            
            // Generate visit count based on frequency
            let visits;
            switch (visitFrequency) {
                case 'frequent': visits = Math.floor(Math.random() * 20) + 15; break;
                case 'regular': visits = Math.floor(Math.random() * 10) + 5; break;
                case 'occasional': visits = Math.floor(Math.random() * 5) + 2; break;
                case 'new': visits = 1; break;
                default: visits = Math.floor(Math.random() * 10) + 1;
            }
            
            const totalSpent = visits * (Math.random() * 50 + 20); // £20-70 per visit
            const lastVisitDays = Math.floor(Math.random() * 90); // Within last 90 days
            const lastVisit = new Date();
            lastVisit.setDate(lastVisit.getDate() - lastVisitDays);
            
            const customer = {
                id: i,
                firstName: firstName,
                lastName: lastName,
                email: email,
                phone: `+44 ${Math.floor(Math.random() * 9000) + 1000} ${Math.floor(Math.random() * 900000) + 100000}`,
                dateOfBirth: this.generateRandomDate(new Date(1950, 0, 1), new Date(2000, 11, 31)),
                gender: ['male', 'female', 'other', ''][Math.floor(Math.random() * 4)],
                address: `${Math.floor(Math.random() * 999) + 1} ${['High Street', 'Main Road', 'Church Lane', 'Victoria Street', 'King Street'][Math.floor(Math.random() * 5)]}`,
                city: city,
                postalCode: `${String.fromCharCode(65 + Math.floor(Math.random() * 26))}${String.fromCharCode(65 + Math.floor(Math.random() * 26))}${Math.floor(Math.random() * 10)} ${Math.floor(Math.random() * 10)}${String.fromCharCode(65 + Math.floor(Math.random() * 26))}${String.fromCharCode(65 + Math.floor(Math.random() * 26))}`,
                status: status,
                visitFrequency: visitFrequency,
                location: location,
                visits: visits,
                totalSpent: totalSpent,
                lastVisit: lastVisit,
                preferredSeating: ['window', 'booth', 'bar', 'outdoor', 'quiet', ''][Math.floor(Math.random() * 6)],
                dietaryRestrictions: this.generateDietaryRestrictions(),
                allergies: Math.random() > 0.7 ? 'Nuts, shellfish' : '',
                emailNotifications: Math.random() > 0.3,
                smsNotifications: Math.random() > 0.5,
                promotionalOffers: Math.random() > 0.4,
                notes: Math.random() > 0.6 ? 'Regular customer, prefers quiet tables. Always orders the salmon.' : '',
                internalNotes: Math.random() > 0.8 ? 'VIP customer - provide extra attention.' : '',
                createdAt: this.generateRandomDate(new Date(2023, 0, 1), new Date()),
                updatedAt: new Date()
            };
            
            this.customers.push(customer);
        }
        
        // Sort by last visit (most recent first)
        this.customers.sort((a, b) => b.lastVisit - a.lastVisit);
    }

    /**
     * Generate random date between two dates
     */
    generateRandomDate(start, end) {
        return new Date(start.getTime() + Math.random() * (end.getTime() - start.getTime()));
    }

    /**
     * Generate dietary restrictions
     */
    generateDietaryRestrictions() {
        const restrictions = ['vegetarian', 'vegan', 'gluten_free', 'dairy_free', 'nut_allergy', 'shellfish_allergy'];
        const selected = [];
        
        restrictions.forEach(restriction => {
            if (Math.random() > 0.8) {
                selected.push(restriction);
            }
        });
        
        return selected;
    }

    /**
     * Update statistics
     */
    updateStatistics() {
        const totalCustomers = this.customers.length;
        const newCustomers = this.customers.filter(c => {
            const oneMonthAgo = new Date();
            oneMonthAgo.setMonth(oneMonthAgo.getMonth() - 1);
            return c.createdAt >= oneMonthAgo;
        }).length;
        const vipCustomers = this.customers.filter(c => c.status === 'vip').length;
        const activeCustomers = this.customers.filter(c => c.status === 'active').length;

        document.getElementById('total-customers').textContent = totalCustomers;
        document.getElementById('new-customers').textContent = newCustomers;
        document.getElementById('vip-customers').textContent = vipCustomers;
        document.getElementById('active-customers').textContent = activeCustomers;
    }

    /**
     * Filter and render customers
     */
    filterAndRenderCustomers() {
        this.filteredCustomers = this.customers.filter(customer => {
            // Search filter
            const searchMatch = !this.searchTerm || 
                customer.firstName.toLowerCase().includes(this.searchTerm) ||
                customer.lastName.toLowerCase().includes(this.searchTerm) ||
                customer.email.toLowerCase().includes(this.searchTerm) ||
                customer.phone.includes(this.searchTerm);

            // Status filter
            const statusMatch = !this.filters.status || customer.status === this.filters.status;

            // Visit frequency filter
            const frequencyMatch = !this.filters.visitFrequency || customer.visitFrequency === this.filters.visitFrequency;

            // Location filter
            const locationMatch = !this.filters.location || customer.location === this.filters.location;

            return searchMatch && statusMatch && frequencyMatch && locationMatch;
        });

        this.renderCustomers();
        this.updateResultsCount();
    }

    /**
     * Render customers
     */
    renderCustomers() {
        if (this.filteredCustomers.length === 0) {
            this.showEmptyState();
            return;
        }

        this.hideEmptyState();
        
        if (this.currentView === 'grid') {
            this.renderCustomersGrid();
        } else {
            this.renderCustomersList();
        }
    }

    /**
     * Render customers grid
     */
    renderCustomersGrid() {
        const grid = document.getElementById('customers-grid');
        if (!grid) return;

        grid.innerHTML = this.filteredCustomers.map(customer => `
            <div class="customer-card" data-customer-id="${customer.id}">
                <div class="customer-card-header">
                    <div class="customer-avatar">
                        ${this.getCustomerInitials(customer)}
                    </div>
                    <div class="customer-info">
                        <div class="customer-name">${customer.firstName} ${customer.lastName}</div>
                        <div class="customer-email">${customer.email}</div>
                    </div>
                </div>
                <div class="customer-status">
                    <span class="status-badge ${customer.status}">${this.formatStatus(customer.status)}</span>
                </div>
                <div class="customer-details">
                    <div class="detail-item">
                        <div class="detail-label">Visits</div>
                        <div class="detail-value">${customer.visits}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Total Spent</div>
                        <div class="detail-value">£${customer.totalSpent.toFixed(2)}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Phone</div>
                        <div class="detail-value">${customer.phone}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Location</div>
                        <div class="detail-value">${customer.city}</div>
                    </div>
                </div>
                <div class="customer-actions">
                    <div class="customer-meta">
                        <span>Last visit: ${this.formatDate(customer.lastVisit)}</span>
                    </div>
                    <div class="action-buttons">
                        <button class="action-btn edit" title="Edit Customer">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button class="action-btn delete" title="Delete Customer">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    }

    /**
     * Render customers list
     */
    renderCustomersList() {
        const tableBody = document.getElementById('customers-table-body');
        if (!tableBody) return;

        tableBody.innerHTML = this.filteredCustomers.map(customer => `
            <tr data-customer-id="${customer.id}">
                <td>
                    <div class="table-customer-info">
                        <div class="table-customer-avatar">
                            ${this.getCustomerInitials(customer)}
                        </div>
                        <div class="table-customer-details">
                            <div class="table-customer-name">${customer.firstName} ${customer.lastName}</div>
                            <div class="table-customer-email">${customer.email}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="table-contact-info">
                        <div class="table-contact-item">${customer.phone}</div>
                        <div class="table-contact-item">${customer.city}</div>
                    </div>
                </td>
                <td>
                    <span class="status-badge ${customer.status}">${this.formatStatus(customer.status)}</span>
                </td>
                <td class="table-visits">${customer.visits}</td>
                <td class="table-spent">£${customer.totalSpent.toFixed(2)}</td>
                <td class="table-last-visit">${this.formatDate(customer.lastVisit)}</td>
                <td>
                    <div class="table-actions">
                        <button class="action-btn edit" title="Edit Customer">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button class="action-btn delete" title="Delete Customer">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    /**
     * Switch view between grid and list
     */
    switchView(view) {
        this.currentView = view;
        
        // Update view buttons
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.view === view);
        });
        
        // Show/hide views
        const grid = document.getElementById('customers-grid');
        const list = document.getElementById('customers-list');
        
        if (grid && list) {
            if (view === 'grid') {
                grid.style.display = 'grid';
                list.style.display = 'none';
            } else {
                grid.style.display = 'none';
                list.style.display = 'block';
            }
        }
        
        this.renderCustomers();
    }

    /**
     * Clear all filters
     */
    clearFilters() {
        this.searchTerm = '';
        this.filters = {
            status: '',
            visitFrequency: '',
            location: ''
        };
        
        // Reset form inputs
        document.getElementById('customer-search').value = '';
        document.getElementById('status-filter').value = '';
        document.getElementById('visit-frequency-filter').value = '';
        document.getElementById('location-filter').value = '';
        
        this.filterAndRenderCustomers();
    }

    /**
     * Update results count
     */
    updateResultsCount() {
        const resultsCount = document.getElementById('results-count');
        if (resultsCount) {
            const showing = this.filteredCustomers.length;
            const total = this.customers.length;
            resultsCount.textContent = `Showing ${showing} of ${total} customers`;
        }
    }

    /**
     * Show empty state
     */
    showEmptyState() {
        const grid = document.getElementById('customers-grid');
        const list = document.getElementById('customers-list');
        const emptyState = document.getElementById('empty-state');
        
        if (grid) grid.style.display = 'none';
        if (list) list.style.display = 'none';
        if (emptyState) emptyState.style.display = 'flex';
    }

    /**
     * Hide empty state
     */
    hideEmptyState() {
        const emptyState = document.getElementById('empty-state');
        if (emptyState) emptyState.style.display = 'none';
        
        // Show current view
        const grid = document.getElementById('customers-grid');
        const list = document.getElementById('customers-list');
        
        if (this.currentView === 'grid' && grid) {
            grid.style.display = 'grid';
        } else if (this.currentView === 'list' && list) {
            list.style.display = 'block';
        }
    }

    /**
     * Open customer modal for adding/editing
     */
    openCustomerModal(customer = null) {
        this.currentCustomer = customer;
        this.isEditing = !!customer;
        
        const modal = document.getElementById('customer-modal');
        const title = document.getElementById('customer-modal-title');
        
        if (modal && title) {
            title.textContent = this.isEditing ? 'Edit Customer' : 'Add Customer';
            
            if (this.isEditing) {
                this.populateCustomerForm(customer);
            } else {
                this.resetCustomerForm();
            }
            
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
        }
    }

    /**
     * Close customer modal
     */
    closeCustomerModal() {
        const modal = document.getElementById('customer-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
            this.resetCustomerForm();
            this.currentCustomer = null;
            this.isEditing = false;
        }
    }

    /**
     * Open customer details modal
     */
    openCustomerDetails(customerId) {
        const customer = this.customers.find(c => c.id === customerId);
        if (!customer) return;
        
        this.currentCustomer = customer;
        
        const modal = document.getElementById('customer-details-modal');
        const content = document.getElementById('customer-details-content');
        
        if (modal && content) {
            content.innerHTML = this.generateCustomerDetailsHtml(customer);
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
        }
    }

    /**
     * Close customer details modal
     */
    closeCustomerDetails() {
        const modal = document.getElementById('customer-details-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
            this.currentCustomer = null;
        }
    }

    /**
     * Generate customer details HTML
     */
    generateCustomerDetailsHtml(customer) {
        return `
            <div class="details-section">
                <h3 class="details-section-title">Personal Information</h3>
                <div class="details-item">
                    <div class="details-label">Full Name</div>
                    <div class="details-value">${customer.firstName} ${customer.lastName}</div>
                </div>
                <div class="details-item">
                    <div class="details-label">Date of Birth</div>
                    <div class="details-value ${customer.dateOfBirth ? '' : 'empty'}">
                        ${customer.dateOfBirth ? this.formatDate(customer.dateOfBirth) : 'Not provided'}
                    </div>
                </div>
                <div class="details-item">
                    <div class="details-label">Gender</div>
                    <div class="details-value ${customer.gender ? '' : 'empty'}">
                        ${customer.gender ? this.formatGender(customer.gender) : 'Not specified'}
                    </div>
                </div>
                <div class="details-item">
                    <div class="details-label">Status</div>
                    <div class="details-value">
                        <span class="status-badge ${customer.status}">${this.formatStatus(customer.status)}</span>
                    </div>
                </div>
            </div>
            
            <div class="details-section">
                <h3 class="details-section-title">Contact Information</h3>
                <div class="details-item">
                    <div class="details-label">Email</div>
                    <div class="details-value">${customer.email}</div>
                </div>
                <div class="details-item">
                    <div class="details-label">Phone</div>
                    <div class="details-value">${customer.phone}</div>
                </div>
                <div class="details-item">
                    <div class="details-label">Address</div>
                    <div class="details-value">${customer.address}</div>
                </div>
                <div class="details-item">
                    <div class="details-label">City</div>
                    <div class="details-value">${customer.city}</div>
                </div>
                <div class="details-item">
                    <div class="details-label">Postal Code</div>
                    <div class="details-value">${customer.postalCode}</div>
                </div>
            </div>
            
            <div class="details-section">
                <h3 class="details-section-title">Visit History</h3>
                <div class="details-item">
                    <div class="details-label">Total Visits</div>
                    <div class="details-value">${customer.visits}</div>
                </div>
                <div class="details-item">
                    <div class="details-label">Total Spent</div>
                    <div class="details-value">£${customer.totalSpent.toFixed(2)}</div>
                </div>
                <div class="details-item">
                    <div class="details-label">Average per Visit</div>
                    <div class="details-value">£${(customer.totalSpent / customer.visits).toFixed(2)}</div>
                </div>
                <div class="details-item">
                    <div class="details-label">Last Visit</div>
                    <div class="details-value">${this.formatDate(customer.lastVisit)}</div>
                </div>
                <div class="details-item">
                    <div class="details-label">Visit Frequency</div>
                    <div class="details-value">${this.formatVisitFrequency(customer.visitFrequency)}</div>
                </div>
            </div>
            
            <div class="details-section">
                <h3 class="details-section-title">Preferences</h3>
                <div class="details-item">
                    <div class="details-label">Preferred Seating</div>
                    <div class="details-value ${customer.preferredSeating ? '' : 'empty'}">
                        ${customer.preferredSeating ? this.formatPreferredSeating(customer.preferredSeating) : 'No preference'}
                    </div>
                </div>
                <div class="details-item">
                    <div class="details-label">Dietary Restrictions</div>
                    <div class="details-value ${customer.dietaryRestrictions.length ? '' : 'empty'}">
                        ${customer.dietaryRestrictions.length ? customer.dietaryRestrictions.map(r => this.formatDietaryRestriction(r)).join(', ') : 'None'}
                    </div>
                </div>
                <div class="details-item">
                    <div class="details-label">Allergies</div>
                    <div class="details-value ${customer.allergies ? '' : 'empty'}">
                        ${customer.allergies || 'None reported'}
                    </div>
                </div>
                <div class="details-item">
                    <div class="details-label">Communication Preferences</div>
                    <div class="details-value">
                        ${this.formatCommunicationPreferences(customer)}
                    </div>
                </div>
            </div>
            
            ${customer.notes || customer.internalNotes ? `
            <div class="details-section">
                <h3 class="details-section-title">Notes</h3>
                ${customer.notes ? `
                <div class="details-item">
                    <div class="details-label">Customer Notes</div>
                    <div class="details-value">${customer.notes}</div>
                </div>
                ` : ''}
                ${customer.internalNotes ? `
                <div class="details-item">
                    <div class="details-label">Internal Notes</div>
                    <div class="details-value">${customer.internalNotes}</div>
                </div>
                ` : ''}
            </div>
            ` : ''}
        `;
    }

    /**
     * Edit customer
     */
    editCustomer(customerId) {
        const customer = this.customers.find(c => c.id === customerId);
        if (customer) {
            this.openCustomerModal(customer);
        }
    }

    /**
     * Delete customer
     */
    deleteCustomer(customerId) {
        if (confirm('Are you sure you want to delete this customer? This action cannot be undone.')) {
            this.customers = this.customers.filter(c => c.id !== customerId);
            this.updateStatistics();
            this.filterAndRenderCustomers();
            this.showNotification('Customer deleted successfully', 'success');
        }
    }

    /**
     * Populate customer form with data
     */
    populateCustomerForm(customer) {
        document.getElementById('first-name').value = customer.firstName;
        document.getElementById('last-name').value = customer.lastName;
        document.getElementById('email').value = customer.email;
        document.getElementById('phone').value = customer.phone;
        document.getElementById('date-of-birth').value = customer.dateOfBirth ? customer.dateOfBirth.toISOString().split('T')[0] : '';
        document.getElementById('gender').value = customer.gender || '';
        document.getElementById('address').value = customer.address;
        document.getElementById('city').value = customer.city;
        document.getElementById('postal-code').value = customer.postalCode;
        document.getElementById('customer-status').value = customer.status;
        document.getElementById('preferred-seating').value = customer.preferredSeating || '';
        document.getElementById('allergies').value = customer.allergies || '';
        document.getElementById('customer-notes').value = customer.notes || '';
        document.getElementById('internal-notes').value = customer.internalNotes || '';
        
        // Set dietary restrictions
        const dietarySelect = document.getElementById('dietary-restrictions');
        if (dietarySelect) {
            Array.from(dietarySelect.options).forEach(option => {
                option.selected = customer.dietaryRestrictions.includes(option.value);
            });
        }
        
        // Set communication preferences
        document.querySelector('input[name="email_notifications"]').checked = customer.emailNotifications;
        document.querySelector('input[name="sms_notifications"]').checked = customer.smsNotifications;
        document.querySelector('input[name="promotional_offers"]').checked = customer.promotionalOffers;
    }

    /**
     * Reset customer form
     */
    resetCustomerForm() {
        const form = document.getElementById('customer-form');
        if (form) {
            form.reset();
        }
    }

    /**
     * Save customer
     */
    saveCustomer() {
        const formData = new FormData(document.getElementById('customer-form'));
        const customerData = {
            firstName: formData.get('first_name'),
            lastName: formData.get('last_name'),
            email: formData.get('email'),
            phone: formData.get('phone'),
            dateOfBirth: formData.get('date_of_birth') ? new Date(formData.get('date_of_birth')) : null,
            gender: formData.get('gender'),
            address: formData.get('address'),
            city: formData.get('city'),
            postalCode: formData.get('postal_code'),
            status: formData.get('status'),
            preferredSeating: formData.get('preferred_seating'),
            dietaryRestrictions: formData.getAll('dietary_restrictions'),
            allergies: formData.get('allergies'),
            emailNotifications: formData.has('email_notifications'),
            smsNotifications: formData.has('sms_notifications'),
            promotionalOffers: formData.has('promotional_offers'),
            notes: formData.get('notes'),
            internalNotes: formData.get('internal_notes')
        };

        if (this.isEditing) {
            // Update existing customer
            const index = this.customers.findIndex(c => c.id === this.currentCustomer.id);
            if (index !== -1) {
                this.customers[index] = { ...this.customers[index], ...customerData, updatedAt: new Date() };
                this.showNotification('Customer updated successfully', 'success');
            }
        } else {
            // Add new customer
            const newCustomer = {
                id: Math.max(...this.customers.map(c => c.id)) + 1,
                ...customerData,
                visitFrequency: 'new',
                location: 'local',
                visits: 0,
                totalSpent: 0,
                lastVisit: null,
                createdAt: new Date(),
                updatedAt: new Date()
            };
            this.customers.unshift(newCustomer);
            this.showNotification('Customer added successfully', 'success');
        }

        this.updateStatistics();
        this.filterAndRenderCustomers();
        this.closeCustomerModal();
    }

    /**
     * Import customers
     */
    importCustomers() {
        this.showNotification('Import functionality coming soon', 'info');
    }

    /**
     * Export customers
     */
    exportCustomers() {
        const csvContent = this.generateCSV();
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `customers-${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        
        this.showNotification('Customers exported successfully', 'success');
    }

    /**
     * Generate CSV content
     */
    generateCSV() {
        const headers = [
            'First Name', 'Last Name', 'Email', 'Phone', 'Date of Birth', 'Gender',
            'Address', 'City', 'Postal Code', 'Status', 'Visits', 'Total Spent',
            'Last Visit', 'Preferred Seating', 'Dietary Restrictions', 'Allergies'
        ];
        
        const rows = this.customers.map(customer => [
            customer.firstName,
            customer.lastName,
            customer.email,
            customer.phone,
            customer.dateOfBirth ? customer.dateOfBirth.toISOString().split('T')[0] : '',
            customer.gender || '',
            customer.address,
            customer.city,
            customer.postalCode,
            customer.status,
            customer.visits,
            customer.totalSpent.toFixed(2),
            customer.lastVisit ? customer.lastVisit.toISOString().split('T')[0] : '',
            customer.preferredSeating || '',
            customer.dietaryRestrictions.join('; '),
            customer.allergies || ''
        ]);
        
        return [headers, ...rows].map(row => 
            row.map(field => `"${String(field).replace(/"/g, '""')}"`).join(',')
        ).join('\n');
    }

    /**
     * Utility methods
     */
    getCustomerInitials(customer) {
        return `${customer.firstName.charAt(0)}${customer.lastName.charAt(0)}`.toUpperCase();
    }

    formatStatus(status) {
        const statusMap = {
            active: 'Active',
            inactive: 'Inactive',
            vip: 'VIP'
        };
        return statusMap[status] || status;
    }

    formatGender(gender) {
        const genderMap = {
            male: 'Male',
            female: 'Female',
            other: 'Other',
            prefer_not_to_say: 'Prefer not to say'
        };
        return genderMap[gender] || gender;
    }

    formatVisitFrequency(frequency) {
        const frequencyMap = {
            frequent: 'Frequent Visitor',
            regular: 'Regular Visitor',
            occasional: 'Occasional Visitor',
            new: 'First Time'
        };
        return frequencyMap[frequency] || frequency;
    }

    formatPreferredSeating(seating) {
        const seatingMap = {
            window: 'Window Seat',
            booth: 'Booth',
            bar: 'Bar Seating',
            outdoor: 'Outdoor',
            quiet: 'Quiet Area'
        };
        return seatingMap[seating] || seating;
    }

    formatDietaryRestriction(restriction) {
        const restrictionMap = {
            vegetarian: 'Vegetarian',
            vegan: 'Vegan',
            gluten_free: 'Gluten Free',
            dairy_free: 'Dairy Free',
            nut_allergy: 'Nut Allergy',
            shellfish_allergy: 'Shellfish Allergy'
        };
        return restrictionMap[restriction] || restriction;
    }

    formatCommunicationPreferences(customer) {
        const prefs = [];
        if (customer.emailNotifications) prefs.push('Email');
        if (customer.smsNotifications) prefs.push('SMS');
        if (customer.promotionalOffers) prefs.push('Promotions');
        return prefs.length ? prefs.join(', ') : 'None';
    }

    formatDate(date) {
        if (!date) return '';
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
    window.customerDirectoryManager = new CustomerDirectoryManager();
});
