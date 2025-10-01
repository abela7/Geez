/**
 * Inventory Suppliers Page JavaScript
 * Handles drawer interactions, filtering, and supplier management
 */

// Alpine.js component for suppliers page
function suppliersPage() {
    return {
        // State
        showSupplierDrawer: false,
        showAddSupplierDrawer: false,
        showFilters: false,
        selectedSupplier: null,
        loadingSupplier: false,
        editingSupplier: false,
        
        // Form data
        supplierForm: {
            name: '',
            contact_person: '',
            phone: '',
            email: '',
            address: '',
            notes: '',
            status: 'active'
        },

        // Search debounce
        searchTimeout: null,

        // Initialize component
        init() {
            console.log('Suppliers page initialized');
        },

        // Open supplier details drawer
        async openSupplierDetails(supplierId) {
            this.showSupplierDrawer = true;
            this.loadingSupplier = true;
            this.selectedSupplier = null;

            try {
                const response = await fetch(`/admin/inventory/suppliers/${supplierId}`);
                const data = await response.json();
                
                if (data.success) {
                    this.selectedSupplier = data.supplier;
                    this.renderSupplierDetails();
                } else {
                    this.showError('Failed to load supplier details');
                }
            } catch (error) {
                console.error('Error loading supplier:', error);
                this.showError('Error loading supplier details');
            } finally {
                this.loadingSupplier = false;
            }
        },

        // Render supplier details in drawer
        renderSupplierDetails() {
            if (!this.selectedSupplier) return;

            const supplier = this.selectedSupplier;
            const detailsContainer = document.querySelector('.supplier-details');
            
            if (!detailsContainer) return;

            detailsContainer.innerHTML = `
                <div class="detail-section">
                    <h4 class="detail-section-title">${this.trans('inventory.suppliers.basic_information')}</h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>${this.trans('inventory.suppliers.supplier_name')}</label>
                            <div class="detail-value">${supplier.name}</div>
                        </div>
                        ${supplier.contact_person ? `
                            <div class="detail-item">
                                <label>${this.trans('inventory.suppliers.contact_person')}</label>
                                <div class="detail-value">${supplier.contact_person}</div>
                            </div>
                        ` : ''}
                        <div class="detail-item">
                            <label>${this.trans('inventory.suppliers.status')}</label>
                            <div class="detail-value">
                                <span class="status-badge status-${supplier.status}">
                                    ${this.getStatusLabel(supplier.status)}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <h4 class="detail-section-title">${this.trans('inventory.suppliers.contact_information')}</h4>
                    <div class="detail-grid">
                        ${supplier.phone ? `
                            <div class="detail-item">
                                <label>${this.trans('inventory.suppliers.phone_field')}</label>
                                <div class="detail-value">
                                    <a href="tel:${supplier.phone}" class="contact-link">${supplier.phone}</a>
                                </div>
                            </div>
                        ` : ''}
                        ${supplier.email ? `
                            <div class="detail-item">
                                <label>${this.trans('inventory.suppliers.email_field')}</label>
                                <div class="detail-value">
                                    <a href="mailto:${supplier.email}" class="contact-link">${supplier.email}</a>
                                </div>
                            </div>
                        ` : ''}
                        ${supplier.address ? `
                            <div class="detail-item">
                                <label>${this.trans('inventory.suppliers.address_field')}</label>
                                <div class="detail-value">${supplier.address}</div>
                            </div>
                        ` : ''}
                    </div>
                </div>

                <div class="detail-section">
                    <h4 class="detail-section-title">${this.trans('inventory.suppliers.items_information')}</h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>${this.trans('inventory.suppliers.items_supplied_count')}</label>
                            <div class="detail-value">${supplier.items_supplied_count} ${this.trans('inventory.suppliers.items')}</div>
                        </div>
                    </div>
                    
                    ${supplier.items_supplied && supplier.items_supplied.length > 0 ? `
                        <div class="items-list">
                            <h5>${this.trans('inventory.suppliers.items_supplied')}</h5>
                            <div class="items-grid">
                                ${supplier.items_supplied.map(item => `
                                    <div class="item-card">
                                        <div class="item-name">${item.name}</div>
                                        <div class="item-code">${item.code}</div>
                                        <div class="item-unit">${item.unit}</div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    ` : `
                        <div class="no-items">
                            <p>${this.trans('inventory.suppliers.no_items_supplied')}</p>
                        </div>
                    `}
                </div>

                ${supplier.recent_purchase_orders && supplier.recent_purchase_orders.length > 0 ? `
                    <div class="detail-section">
                        <h4 class="detail-section-title">${this.trans('inventory.suppliers.recent_orders')}</h4>
                        <div class="orders-list">
                            ${supplier.recent_purchase_orders.map(order => `
                                <div class="order-item">
                                    <div class="order-number">${order.po_number}</div>
                                    <div class="order-date">${order.order_date}</div>
                                    <div class="order-status">${order.status}</div>
                                    <div class="order-total">${order.total}</div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                ` : ''}

                ${supplier.notes ? `
                    <div class="detail-section">
                        <h4 class="detail-section-title">${this.trans('inventory.suppliers.notes_field')}</h4>
                        <div class="detail-item">
                            <div class="detail-value">${supplier.notes}</div>
                        </div>
                    </div>
                ` : ''}

                <div class="detail-section">
                    <h4 class="detail-section-title">${this.trans('inventory.suppliers.additional_info')}</h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>${this.trans('inventory.suppliers.created_date')}</label>
                            <div class="detail-value">${supplier.created_at}</div>
                        </div>
                        <div class="detail-item">
                            <label>${this.trans('inventory.suppliers.last_updated')}</label>
                            <div class="detail-value">${supplier.updated_at}</div>
                        </div>
                    </div>
                </div>
            `;
        },

        // Get status label
        getStatusLabel(status) {
            const labels = {
                'active': this.trans('inventory.suppliers.supplier_statuses.active'),
                'inactive': this.trans('inventory.suppliers.supplier_statuses.inactive')
            };
            return labels[status] || status;
        },

        // Close supplier details drawer
        closeSupplierDrawer() {
            this.showSupplierDrawer = false;
            this.selectedSupplier = null;
            this.loadingSupplier = false;
        },

        // Open add supplier drawer
        openAddSupplierDrawer() {
            this.showAddSupplierDrawer = true;
            this.editingSupplier = false;
            this.resetSupplierForm();
        },

        // Close add supplier drawer
        closeAddSupplierDrawer() {
            this.showAddSupplierDrawer = false;
            this.editingSupplier = false;
            this.resetSupplierForm();
        },

        // Reset supplier form
        resetSupplierForm() {
            this.supplierForm = {
                name: '',
                contact_person: '',
                phone: '',
                email: '',
                address: '',
                notes: '',
                status: 'active'
            };
        },

        // Edit supplier
        editSupplier() {
            if (!this.selectedSupplier) return;
            
            // Populate form with selected supplier data
            this.supplierForm = {
                name: this.selectedSupplier.name || '',
                contact_person: this.selectedSupplier.contact_person || '',
                phone: this.selectedSupplier.phone || '',
                email: this.selectedSupplier.email || '',
                address: this.selectedSupplier.address || '',
                notes: this.selectedSupplier.notes || '',
                status: this.selectedSupplier.status || 'active'
            };
            
            this.editingSupplier = true;
            this.closeSupplierDrawer();
            this.showAddSupplierDrawer = true;
        },

        // Save supplier (create or update)
        async saveSupplier() {
            try {
                // Validate form
                if (!this.validateSupplierForm()) {
                    return;
                }

                const url = this.editingSupplier 
                    ? `/admin/inventory/suppliers/${this.selectedSupplier.id}`
                    : '/admin/inventory/suppliers';
                
                const method = this.editingSupplier ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.supplierForm)
                });

                const data = await response.json();

                if (data.success) {
                    this.showSuccess(data.message);
                    this.closeAddSupplierDrawer();
                    // Reload page to show updated data
                    window.location.reload();
                } else {
                    this.showError(data.message || 'Failed to save supplier');
                }
            } catch (error) {
                console.error('Error saving supplier:', error);
                this.showError('Error saving supplier');
            }
        },

        // Validate supplier form
        validateSupplierForm() {
            const form = this.supplierForm;
            
            if (!form.name || form.name.trim() === '') {
                this.showError(this.trans('inventory.suppliers.name_required'));
                return false;
            }
            
            if (!form.status) {
                this.showError(this.trans('inventory.suppliers.status_required'));
                return false;
            }
            
            if (form.email && !this.isValidEmail(form.email)) {
                this.showError(this.trans('inventory.suppliers.email_valid'));
                return false;
            }
            
            return true;
        },

        // Validate email format
        isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        },

        // Delete supplier
        async deleteSupplier() {
            if (!this.selectedSupplier) return;
            
            if (!confirm(this.trans('inventory.suppliers.confirm_delete'))) {
                return;
            }

            try {
                const response = await fetch(`/admin/inventory/suppliers/${this.selectedSupplier.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.showSuccess(data.message);
                    this.closeSupplierDrawer();
                    // Reload page to show updated data
                    window.location.reload();
                } else {
                    this.showError(data.message || 'Failed to delete supplier');
                }
            } catch (error) {
                console.error('Error deleting supplier:', error);
                this.showError('Error deleting supplier');
            }
        },

        // Create PO for supplier
        createPOForSupplier() {
            if (!this.selectedSupplier) return;
            
            // Navigate to purchase order creation with supplier pre-selected
            window.location.href = `/admin/inventory/purchasing?supplier_id=${this.selectedSupplier.id}`;
        },

        // Debounced search
        debounceSearch(value) {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.performSearch(value);
            }, 300);
        },

        // Perform search
        performSearch(searchTerm) {
            const url = new URL(window.location);
            if (searchTerm && searchTerm.trim() !== '') {
                url.searchParams.set('search', searchTerm.trim());
            } else {
                url.searchParams.delete('search');
            }
            window.location.href = url.toString();
        },

        // Translation helper
        trans(key) {
            // This would be replaced with actual Laravel translation function
            // For now, return the key as fallback
            return key;
        },

        // Show success message
        showSuccess(message) {
            // You can implement a toast notification system here
            alert(message); // Temporary implementation
        },

        // Show error message
        showError(message) {
            // You can implement a toast notification system here
            alert(message); // Temporary implementation
        }
    };
}

// Filter functions (global scope for inline handlers)
function applyFilters() {
    const form = document.createElement('form');
    form.method = 'GET';
    form.action = window.location.pathname;

    // Get all filter inputs
    const filters = document.querySelectorAll('.filters-section select, .filters-section input[type="text"]');
    
    filters.forEach(input => {
        if (input.value && input.value !== 'all' && input.value !== '') {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = getFilterName(input);
            hiddenInput.value = input.value;
            form.appendChild(hiddenInput);
        }
    });

    document.body.appendChild(form);
    form.submit();
}

function clearFilters() {
    window.location.href = window.location.pathname;
}

function getFilterName(input) {
    // Map input elements to their filter parameter names
    const parent = input.closest('.filter-group');
    if (!parent) return 'search';
    
    const label = parent.querySelector('label').textContent.toLowerCase();
    
    if (label.includes('status')) return 'status';
    
    return 'search';
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Suppliers page JavaScript loaded');
    
    // Add styles for supplier details
    const style = document.createElement('style');
    style.textContent = `
        .detail-section {
            margin-bottom: var(--space-lg);
        }
        
        .detail-section-title {
            font-size: var(--font-size-md);
            font-weight: 600;
            color: var(--color-text-primary);
            margin: 0 0 var(--space-md) 0;
            padding-bottom: var(--space-xs);
            border-bottom: 1px solid var(--color-border);
        }
        
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: var(--space-md);
        }
        
        .detail-item {
            display: flex;
            flex-direction: column;
            gap: var(--space-xs);
        }
        
        .detail-item label {
            font-size: var(--font-size-xs);
            font-weight: 500;
            color: var(--color-text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }
        
        .detail-value {
            font-size: var(--font-size-sm);
            color: var(--color-text-primary);
            font-weight: 500;
        }
        
        .contact-link {
            color: var(--color-primary);
            text-decoration: none;
        }
        
        .contact-link:hover {
            text-decoration: underline;
        }
        
        .items-list {
            margin-top: var(--space-md);
        }
        
        .items-list h5 {
            font-size: var(--font-size-sm);
            font-weight: 600;
            color: var(--color-text-primary);
            margin: 0 0 var(--space-sm) 0;
        }
        
        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: var(--space-sm);
        }
        
        .item-card {
            background: var(--color-bg-tertiary);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-sm);
            padding: var(--space-sm);
        }
        
        .item-name {
            font-weight: 500;
            color: var(--color-text-primary);
            font-size: var(--font-size-sm);
        }
        
        .item-code {
            font-size: var(--font-size-xs);
            color: var(--color-text-secondary);
            font-family: 'Courier New', monospace;
        }
        
        .item-unit {
            font-size: var(--font-size-xs);
            color: var(--color-text-muted);
        }
        
        .no-items {
            text-align: center;
            padding: var(--space-lg);
            color: var(--color-text-muted);
            font-style: italic;
        }
        
        .orders-list {
            display: flex;
            flex-direction: column;
            gap: var(--space-sm);
        }
        
        .order-item {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
            gap: var(--space-sm);
            padding: var(--space-sm);
            background: var(--color-bg-tertiary);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-sm);
        }
        
        .order-number {
            font-weight: 500;
            color: var(--color-text-primary);
        }
        
        .order-date,
        .order-status,
        .order-total {
            font-size: var(--font-size-sm);
            color: var(--color-text-secondary);
        }
    `;
    document.head.appendChild(style);
});
