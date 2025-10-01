/**
 * Inventory Movements Page JavaScript
 * Handles drawer interactions, filtering, and movement management
 */

// Alpine.js component for movements page
function movementsPage() {
    return {
        // State
        showMovementDrawer: false,
        showAddMovementDrawer: false,
        selectedMovement: null,
        loadingMovement: false,
        editingMovement: false,
        
        // Form data
        movementForm: {
            item_id: '',
            movement_type: '',
            quantity: '',
            from_location: '',
            to_location: '',
            notes: ''
        },

        // Initialize component
        init() {
            console.log('Movements page initialized');
        },

        // Open movement details drawer
        async openMovementDetails(movementId) {
            this.showMovementDrawer = true;
            this.loadingMovement = true;
            this.selectedMovement = null;

            try {
                const response = await fetch(`/admin/inventory/movements/${movementId}`);
                const data = await response.json();
                
                if (data.success) {
                    this.selectedMovement = data.movement;
                    this.renderMovementDetails();
                } else {
                    this.showError('Failed to load movement details');
                }
            } catch (error) {
                console.error('Error loading movement:', error);
                this.showError('Error loading movement details');
            } finally {
                this.loadingMovement = false;
            }
        },

        // Render movement details in drawer
        renderMovementDetails() {
            if (!this.selectedMovement) return;

            const movement = this.selectedMovement;
            const detailsContainer = document.querySelector('.movement-details');
            
            if (!detailsContainer) return;

            detailsContainer.innerHTML = `
                <div class="detail-section">
                    <h4 class="detail-section-title">Item Details</h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>Item Name</label>
                            <div class="detail-value">${movement.item_name}</div>
                        </div>
                        <div class="detail-item">
                            <label>Item Code</label>
                            <div class="detail-value">${movement.item_code}</div>
                        </div>
                        <div class="detail-item">
                            <label>Quantity</label>
                            <div class="detail-value">${movement.quantity} ${movement.unit}</div>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <h4 class="detail-section-title">Movement Information</h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>Movement Type</label>
                            <div class="detail-value">
                                <span class="movement-badge movement-${movement.movement_type}">
                                    ${this.getMovementTypeLabel(movement.movement_type)}
                                </span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label>Location</label>
                            <div class="detail-value">${movement.location_display}</div>
                        </div>
                        <div class="detail-item">
                            <label>Date & Time</label>
                            <div class="detail-value">${movement.formatted_date}</div>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <h4 class="detail-section-title">Staff Information</h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>Staff Member</label>
                            <div class="detail-value">${movement.staff_name}</div>
                        </div>
                        <div class="detail-item">
                            <label>Role</label>
                            <div class="detail-value">${movement.staff_role}</div>
                        </div>
                    </div>
                </div>

                ${movement.notes ? `
                    <div class="detail-section">
                        <h4 class="detail-section-title">Additional Information</h4>
                        <div class="detail-item">
                            <label>Notes</label>
                            <div class="detail-value">${movement.notes}</div>
                        </div>
                    </div>
                ` : ''}

                ${movement.reference ? `
                    <div class="detail-section">
                        <div class="detail-item">
                            <label>Reference</label>
                            <div class="detail-value">${movement.reference}</div>
                        </div>
                    </div>
                ` : ''}
            `;
        },

        // Get movement type label
        getMovementTypeLabel(type) {
            const labels = {
                'receive': 'Receive',
                'transfer': 'Transfer',
                'adjust': 'Adjust',
                'waste': 'Waste',
                'return': 'Return',
                'sale': 'Sale',
                'production': 'Production'
            };
            return labels[type] || type;
        },

        // Close movement details drawer
        closeMovementDrawer() {
            this.showMovementDrawer = false;
            this.selectedMovement = null;
            this.loadingMovement = false;
        },

        // Open add movement drawer
        openAddMovementDrawer() {
            this.showAddMovementDrawer = true;
            this.editingMovement = false;
            this.resetMovementForm();
        },

        // Close add movement drawer
        closeAddMovementDrawer() {
            this.showAddMovementDrawer = false;
            this.editingMovement = false;
            this.resetMovementForm();
        },

        // Reset movement form
        resetMovementForm() {
            this.movementForm = {
                item_id: '',
                movement_type: '',
                quantity: '',
                from_location: '',
                to_location: '',
                notes: ''
            };
        },

        // Edit movement
        editMovement() {
            if (!this.selectedMovement) return;
            
            // Populate form with selected movement data
            this.movementForm = {
                item_id: this.selectedMovement.item_id || '',
                movement_type: this.selectedMovement.movement_type,
                quantity: this.selectedMovement.quantity,
                from_location: this.selectedMovement.from_location || '',
                to_location: this.selectedMovement.to_location || '',
                notes: this.selectedMovement.notes || ''
            };
            
            this.editingMovement = true;
            this.closeMovementDrawer();
            this.showAddMovementDrawer = true;
        },

        // Save movement (create or update)
        async saveMovement() {
            try {
                // Validate form
                if (!this.validateMovementForm()) {
                    return;
                }

                const url = this.editingMovement 
                    ? `/admin/inventory/movements/${this.selectedMovement.id}`
                    : '/admin/inventory/movements';
                
                const method = this.editingMovement ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.movementForm)
                });

                const data = await response.json();

                if (data.success) {
                    this.showSuccess(data.message);
                    this.closeAddMovementDrawer();
                    // Reload page to show updated data
                    window.location.reload();
                } else {
                    this.showError(data.message || 'Failed to save movement');
                }
            } catch (error) {
                console.error('Error saving movement:', error);
                this.showError('Error saving movement');
            }
        },

        // Validate movement form
        validateMovementForm() {
            const form = this.movementForm;
            
            if (!form.item_id) {
                this.showError('Please select an item');
                return false;
            }
            
            if (!form.movement_type) {
                this.showError('Please select a movement type');
                return false;
            }
            
            if (!form.quantity || form.quantity <= 0) {
                this.showError('Please enter a valid quantity');
                return false;
            }
            
            if (!form.to_location) {
                this.showError('Please select a location');
                return false;
            }
            
            if (form.movement_type === 'transfer' && !form.from_location) {
                this.showError('Please select a from location for transfers');
                return false;
            }
            
            return true;
        },

        // Delete movement
        async deleteMovement() {
            if (!this.selectedMovement) return;
            
            if (!confirm('Are you sure you want to delete this movement? This action cannot be undone.')) {
                return;
            }

            try {
                const response = await fetch(`/admin/inventory/movements/${this.selectedMovement.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.showSuccess(data.message);
                    this.closeMovementDrawer();
                    // Reload page to show updated data
                    window.location.reload();
                } else {
                    this.showError(data.message || 'Failed to delete movement');
                }
            } catch (error) {
                console.error('Error deleting movement:', error);
                this.showError('Error deleting movement');
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
function applyFilters() {
    const form = document.createElement('form');
    form.method = 'GET';
    form.action = window.location.pathname;

    // Get all filter inputs
    const filters = document.querySelectorAll('.filters-section select, .filters-section input[type="text"], .filters-section input[type="date"]');
    
    filters.forEach(input => {
        if (input.value && input.value !== 'all') {
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
    
    if (label.includes('type')) return 'movement_type';
    if (label.includes('location')) return 'location';
    if (label.includes('staff')) return 'staff';
    if (label.includes('from')) return 'date_from';
    if (label.includes('to')) return 'date_to';
    
    return 'search';
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Movements page JavaScript loaded');
    
    // Add styles for movement details
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
