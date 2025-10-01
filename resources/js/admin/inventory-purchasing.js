/**
 * Inventory Purchasing Page JavaScript
 * Handles drawer interactions, filtering, and PO management
 */

// Alpine.js component for purchasing page
function purchasingPage() {
    return {
        // State
        showPODrawer: false,
        showAddPODrawer: false,
        selectedPO: null,
        loadingPO: false,
        editingPO: false,
        
        // Form data
        poForm: {
            supplier_id: '',
            order_date: '',
            delivery_date: '',
            notes: '',
            line_items: [
                {
                    item_id: '',
                    quantity: '',
                    unit_price: '',
                    line_total: 0
                }
            ],
            subtotal: 0,
            tax_rate: 10, // 10% tax rate
            tax_amount: 0,
            shipping_cost: 0,
            grand_total: 0
        },

        // Initialize component
        init() {
            console.log('Purchasing page initialized');
            // Set default dates
            const today = new Date().toISOString().split('T')[0];
            const nextWeek = new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
            this.poForm.order_date = today;
            this.poForm.delivery_date = nextWeek;
        },

        // Open PO details drawer
        async openPODetails(poId) {
            this.showPODrawer = true;
            this.loadingPO = true;
            this.selectedPO = null;

            try {
                const response = await fetch(`/admin/inventory/purchasing/${poId}`);
                const data = await response.json();
                
                if (data.success) {
                    this.selectedPO = data.purchase_order;
                    this.renderPODetails();
                } else {
                    this.showError('Failed to load purchase order details');
                }
            } catch (error) {
                console.error('Error loading PO:', error);
                this.showError('Error loading purchase order details');
            } finally {
                this.loadingPO = false;
            }
        },

        // Render PO details in drawer
        renderPODetails() {
            if (!this.selectedPO) return;

            const po = this.selectedPO;
            const detailsContainer = document.querySelector('.po-details');
            
            if (!detailsContainer) return;

            detailsContainer.innerHTML = `
                <div class="detail-section">
                    <h4 class="detail-section-title">Purchase Order Information</h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>PO Number</label>
                            <div class="detail-value">${po.po_number}</div>
                        </div>
                        <div class="detail-item">
                            <label>Status</label>
                            <div class="detail-value">
                                <span class="po-status-badge po-${po.status}">
                                    ${this.getPOStatusLabel(po.status)}
                                </span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label>Total Amount</label>
                            <div class="detail-value">$${po.total_amount.toFixed(2)}</div>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <h4 class="detail-section-title">Supplier Information</h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>Supplier Name</label>
                            <div class="detail-value">${po.supplier_name}</div>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <h4 class="detail-section-title">Order Details</h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>Order Date</label>
                            <div class="detail-value">${po.order_date || 'Not specified'}</div>
                        </div>
                        <div class="detail-item">
                            <label>Expected Delivery</label>
                            <div class="detail-value">${po.delivery_date || 'Not specified'}</div>
                        </div>
                    </div>
                </div>
            `;
        },

        // Get PO status label
        getPOStatusLabel(status) {
            const labels = {
                'draft': 'Draft',
                'sent': 'Sent',
                'received': 'Received',
                'cancelled': 'Cancelled',
                'partial': 'Partial',
                'overdue': 'Overdue'
            };
            return labels[status] || status;
        },

        // Close PO details drawer
        closePODrawer() {
            this.showPODrawer = false;
            this.selectedPO = null;
            this.loadingPO = false;
        },

        // Open add PO drawer
        openAddPODrawer() {
            this.showAddPODrawer = true;
            this.editingPO = false;
            this.resetPOForm();
        },

        // Close add PO drawer
        closeAddPODrawer() {
            this.showAddPODrawer = false;
            this.editingPO = false;
            this.resetPOForm();
        },

        // Reset PO form
        resetPOForm() {
            const today = new Date().toISOString().split('T')[0];
            const nextWeek = new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
            
            this.poForm = {
                supplier_id: '',
                order_date: today,
                delivery_date: nextWeek,
                notes: '',
                line_items: [
                    {
                        item_id: '',
                        quantity: '',
                        unit_price: '',
                        line_total: 0
                    }
                ],
                subtotal: 0,
                tax_rate: 10,
                tax_amount: 0,
                shipping_cost: 0,
                grand_total: 0
            };
        },

        // Add line item
        addLineItem() {
            this.poForm.line_items.push({
                item_id: '',
                quantity: '',
                unit_price: '',
                line_total: 0
            });
        },

        // Remove line item
        removeLineItem(index) {
            if (this.poForm.line_items.length > 1) {
                this.poForm.line_items.splice(index, 1);
                this.calculateTotals();
            }
        },

        // Calculate line total
        calculateLineTotal(index) {
            const item = this.poForm.line_items[index];
            const quantity = parseFloat(item.quantity) || 0;
            const unitPrice = parseFloat(item.unit_price) || 0;
            item.line_total = quantity * unitPrice;
            this.calculateTotals();
        },

        // Calculate totals
        calculateTotals() {
            // Calculate subtotal
            this.poForm.subtotal = this.poForm.line_items.reduce((sum, item) => {
                return sum + (item.line_total || 0);
            }, 0);

            // Calculate tax
            this.poForm.tax_amount = (this.poForm.subtotal * this.poForm.tax_rate) / 100;

            // Calculate grand total
            this.poForm.grand_total = this.poForm.subtotal + this.poForm.tax_amount + (parseFloat(this.poForm.shipping_cost) || 0);
        },

        // Edit PO
        editPO() {
            if (!this.selectedPO) return;
            
            // Populate form with selected PO data
            this.poForm.supplier_id = this.selectedPO.supplier_name || '';
            this.poForm.order_date = this.selectedPO.order_date || '';
            this.poForm.delivery_date = this.selectedPO.delivery_date || '';
            this.poForm.notes = this.selectedPO.notes || '';
            
            this.editingPO = true;
            this.closePODrawer();
            this.showAddPODrawer = true;
        },

        // Save PO (create or update)
        async savePO() {
            try {
                // Validate form
                if (!this.validatePOForm()) {
                    return;
                }

                const url = this.editingPO 
                    ? `/admin/inventory/purchasing/${this.selectedPO.id}`
                    : '/admin/inventory/purchasing';
                
                const method = this.editingPO ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.poForm)
                });

                const data = await response.json();

                if (data.success) {
                    this.showSuccess(data.message);
                    this.closeAddPODrawer();
                    // Reload page to show updated data
                    window.location.reload();
                } else {
                    this.showError(data.message || 'Failed to save purchase order');
                }
            } catch (error) {
                console.error('Error saving PO:', error);
                this.showError('Error saving purchase order');
            }
        },

        // Validate PO form
        validatePOForm() {
            const form = this.poForm;
            
            if (!form.supplier_id) {
                this.showError('Please select a supplier');
                return false;
            }
            
            if (!form.order_date) {
                this.showError('Please select an order date');
                return false;
            }
            
            if (!form.delivery_date) {
                this.showError('Please select a delivery date');
                return false;
            }
            
            if (form.line_items.length === 0) {
                this.showError('Please add at least one line item');
                return false;
            }
            
            // Validate line items
            for (let i = 0; i < form.line_items.length; i++) {
                const item = form.line_items[i];
                if (!item.item_id) {
                    this.showError(`Please select an item for line ${i + 1}`);
                    return false;
                }
                if (!item.quantity || item.quantity <= 0) {
                    this.showError(`Please enter a valid quantity for line ${i + 1}`);
                    return false;
                }
                if (!item.unit_price || item.unit_price <= 0) {
                    this.showError(`Please enter a valid unit price for line ${i + 1}`);
                    return false;
                }
            }
            
            return true;
        },

        // Mark PO as received
        async markPOReceived() {
            if (!this.selectedPO) return;
            
            if (!confirm('Mark this purchase order as received?')) {
                return;
            }

            try {
                const response = await fetch(`/admin/inventory/purchasing/${this.selectedPO.id}/mark-received`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.showSuccess(data.message);
                    this.closePODrawer();
                    // Reload page to show updated data
                    window.location.reload();
                } else {
                    this.showError(data.message || 'Failed to mark PO as received');
                }
            } catch (error) {
                console.error('Error marking PO as received:', error);
                this.showError('Error marking PO as received');
            }
        },

        // Delete PO
        async deletePO() {
            if (!this.selectedPO) return;
            
            if (!confirm('Are you sure you want to delete this purchase order? This action cannot be undone.')) {
                return;
            }

            try {
                const response = await fetch(`/admin/inventory/purchasing/${this.selectedPO.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.showSuccess(data.message);
                    this.closePODrawer();
                    // Reload page to show updated data
                    window.location.reload();
                } else {
                    this.showError(data.message || 'Failed to delete purchase order');
                }
            } catch (error) {
                console.error('Error deleting PO:', error);
                this.showError('Error deleting purchase order');
            }
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
function applyPOFilters() {
    const form = document.createElement('form');
    form.method = 'GET';
    form.action = window.location.pathname;

    // Get all filter inputs
    const filters = document.querySelectorAll('.filters-section select, .filters-section input[type="text"], .filters-section input[type="date"]');
    
    filters.forEach(input => {
        if (input.value && input.value !== 'all') {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = getPOFilterName(input);
            hiddenInput.value = input.value;
            form.appendChild(hiddenInput);
        }
    });

    document.body.appendChild(form);
    form.submit();
}

function clearPOFilters() {
    window.location.href = window.location.pathname;
}

function getPOFilterName(input) {
    // Map input elements to their filter parameter names
    const parent = input.closest('.filter-group');
    if (!parent) return 'search';
    
    const label = parent.querySelector('label').textContent.toLowerCase();
    
    if (label.includes('supplier')) return 'supplier';
    if (label.includes('status')) return 'status';
    if (label.includes('date')) {
        // Check if it's the first or second date input
        const dateInputs = parent.querySelectorAll('input[type="date"]');
        const index = Array.from(dateInputs).indexOf(input);
        return index === 0 ? 'order_date_from' : 'order_date_to';
    }
    
    return 'search';
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Purchasing page JavaScript loaded');
    
    // Add styles for PO details
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
    `;
    document.head.appendChild(style);
});
