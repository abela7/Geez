/**
 * Inventory Locations Page JavaScript
 * Handles drawer interactions, filtering, and location management
 */

// Alpine.js component for locations page
function locationsPage() {
    return {
        // State
        showLocationDrawer: false,
        showAddLocationDrawer: false,
        selectedLocation: null,
        loadingLocation: false,
        editingLocation: false,
        
        // Form data
        locationForm: {
            name: '',
            type: '',
            status: 'active',
            capacity_percentage: 0,
            description: ''
        },

        // Initialize component
        init() {
            console.log('Locations page initialized');
        },

        // Open location details drawer
        async openLocationDetails(locationId) {
            this.showLocationDrawer = true;
            this.loadingLocation = true;
            this.selectedLocation = null;

            try {
                const response = await fetch(`/admin/inventory/locations/${locationId}`);
                const data = await response.json();
                
                if (data.success) {
                    this.selectedLocation = data.location;
                    this.renderLocationDetails();
                } else {
                    this.showError('Failed to load location details');
                }
            } catch (error) {
                console.error('Error loading location:', error);
                this.showError('Error loading location details');
            } finally {
                this.loadingLocation = false;
            }
        },

        // Render location details in drawer
        renderLocationDetails() {
            if (!this.selectedLocation) return;

            const location = this.selectedLocation;
            const detailsContainer = document.querySelector('.location-details');
            
            if (!detailsContainer) return;

            detailsContainer.innerHTML = `
                <div class="detail-section">
                    <h4 class="detail-section-title">Basic Information</h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>Location Name</label>
                            <div class="detail-value">${location.name}</div>
                        </div>
                        <div class="detail-item">
                            <label>Type</label>
                            <div class="detail-value">
                                <span class="location-type-badge type-${location.type}">
                                    ${this.getLocationTypeLabel(location.type)}
                                </span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label>Status</label>
                            <div class="detail-value">
                                <span class="location-status-badge location-${location.status}">
                                    ${this.getLocationStatusLabel(location.status)}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <h4 class="detail-section-title">Capacity Information</h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>Current Capacity</label>
                            <div class="detail-value">${location.capacity_percentage}% full</div>
                        </div>
                        <div class="detail-item">
                            <label>Items Stored</label>
                            <div class="detail-value">${location.items_count} items</div>
                        </div>
                    </div>
                </div>

                ${location.description ? `
                    <div class="detail-section">
                        <h4 class="detail-section-title">Description</h4>
                        <div class="detail-item">
                            <div class="detail-value">${location.description}</div>
                        </div>
                    </div>
                ` : ''}

                ${location.items_stored && location.items_stored.length > 0 ? `
                    <div class="detail-section">
                        <h4 class="detail-section-title">Items in this Location</h4>
                        <div class="items-list">
                            ${location.items_stored.map(item => `
                                <div class="item-row">
                                    <div class="item-name">${item.name}</div>
                                    <div class="item-quantity">${item.quantity}</div>
                                    <div class="item-date">Added: ${item.date_added}</div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                ` : `
                    <div class="detail-section">
                        <h4 class="detail-section-title">Items in this Location</h4>
                        <div class="empty-items">
                            <p>No items currently stored in this location</p>
                        </div>
                    </div>
                `}
            `;
        },

        // Get location type label
        getLocationTypeLabel(type) {
            const labels = {
                'fridge': 'Fridge',
                'freezer': 'Freezer',
                'pantry': 'Pantry',
                'bar': 'Bar',
                'storage_room': 'Storage Room',
                'warehouse': 'Warehouse',
                'kitchen': 'Kitchen',
                'prep_area': 'Prep Area',
                'dry_storage': 'Dry Storage',
                'cold_storage': 'Cold Storage',
                'wine_cellar': 'Wine Cellar',
                'office': 'Office'
            };
            return labels[type] || type;
        },

        // Get location status label
        getLocationStatusLabel(status) {
            const labels = {
                'active': 'Active',
                'inactive': 'Inactive',
                'maintenance': 'Maintenance',
                'full': 'Full',
                'reserved': 'Reserved'
            };
            return labels[status] || status;
        },

        // Close location details drawer
        closeLocationDrawer() {
            this.showLocationDrawer = false;
            this.selectedLocation = null;
            this.loadingLocation = false;
        },

        // Open add location drawer
        openAddLocationDrawer() {
            this.showAddLocationDrawer = true;
            this.editingLocation = false;
            this.resetLocationForm();
        },

        // Close add location drawer
        closeAddLocationDrawer() {
            this.showAddLocationDrawer = false;
            this.editingLocation = false;
            this.resetLocationForm();
        },

        // Reset location form
        resetLocationForm() {
            this.locationForm = {
                name: '',
                type: '',
                status: 'active',
                capacity_percentage: 0,
                description: ''
            };
        },

        // Edit location
        editLocation() {
            if (!this.selectedLocation) return;
            
            // Populate form with selected location data
            this.locationForm = {
                name: this.selectedLocation.name || '',
                type: this.selectedLocation.type || '',
                status: this.selectedLocation.status || 'active',
                capacity_percentage: this.selectedLocation.capacity_percentage || 0,
                description: this.selectedLocation.description || ''
            };
            
            this.editingLocation = true;
            this.closeLocationDrawer();
            this.showAddLocationDrawer = true;
        },

        // Save location (create or update)
        async saveLocation() {
            try {
                // Validate form
                if (!this.validateLocationForm()) {
                    return;
                }

                const url = this.editingLocation 
                    ? `/admin/inventory/locations/${this.selectedLocation.id}`
                    : '/admin/inventory/locations';
                
                const method = this.editingLocation ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.locationForm)
                });

                const data = await response.json();

                if (data.success) {
                    this.showSuccess(data.message);
                    this.closeAddLocationDrawer();
                    // Reload page to show updated data
                    window.location.reload();
                } else {
                    this.showError(data.message || 'Failed to save location');
                }
            } catch (error) {
                console.error('Error saving location:', error);
                this.showError('Error saving location');
            }
        },

        // Validate location form
        validateLocationForm() {
            const form = this.locationForm;
            
            if (!form.name || form.name.trim().length < 2) {
                this.showError('Location name must be at least 2 characters');
                return false;
            }
            
            if (!form.type) {
                this.showError('Please select a location type');
                return false;
            }
            
            if (!form.status) {
                this.showError('Please select a status');
                return false;
            }
            
            if (form.capacity_percentage < 0 || form.capacity_percentage > 100) {
                this.showError('Capacity must be between 0 and 100');
                return false;
            }
            
            return true;
        },

        // Toggle location status (activate/deactivate)
        async toggleLocationStatus() {
            if (!this.selectedLocation) return;
            
            const isActive = this.selectedLocation.status === 'active';
            const action = isActive ? 'deactivate' : 'activate';
            const message = isActive ? 'deactivate this location' : 'activate this location';
            
            if (!confirm(`Are you sure you want to ${message}?`)) {
                return;
            }

            try {
                const response = await fetch(`/admin/inventory/locations/${this.selectedLocation.id}/${action}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.showSuccess(data.message);
                    this.closeLocationDrawer();
                    // Reload page to show updated data
                    window.location.reload();
                } else {
                    this.showError(data.message || `Failed to ${action} location`);
                }
            } catch (error) {
                console.error(`Error ${action}ing location:`, error);
                this.showError(`Error ${action}ing location`);
            }
        },

        // Delete location
        async deleteLocation() {
            if (!this.selectedLocation) return;
            
            if (!confirm('Are you sure you want to delete this location? This action cannot be undone. Items in this location will need to be moved.')) {
                return;
            }

            try {
                const response = await fetch(`/admin/inventory/locations/${this.selectedLocation.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.showSuccess(data.message);
                    this.closeLocationDrawer();
                    // Reload page to show updated data
                    window.location.reload();
                } else {
                    this.showError(data.message || 'Failed to delete location');
                }
            } catch (error) {
                console.error('Error deleting location:', error);
                this.showError('Error deleting location');
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
function applyLocationFilters() {
    const form = document.createElement('form');
    form.method = 'GET';
    form.action = window.location.pathname;

    // Get all filter inputs
    const filters = document.querySelectorAll('.filters-section select, .filters-section input[type="text"]');
    
    filters.forEach(input => {
        if (input.value && input.value !== 'all') {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = getLocationFilterName(input);
            hiddenInput.value = input.value;
            form.appendChild(hiddenInput);
        }
    });

    document.body.appendChild(form);
    form.submit();
}

function clearLocationFilters() {
    window.location.href = window.location.pathname;
}

function getLocationFilterName(input) {
    // Map input elements to their filter parameter names
    const parent = input.closest('.filter-group');
    if (!parent) return 'search';
    
    const label = parent.querySelector('label').textContent.toLowerCase();
    
    if (label.includes('type')) return 'type';
    if (label.includes('status')) return 'status';
    
    return 'search';
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Locations page JavaScript loaded');
    
    // Add styles for location details
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
        
        .items-list {
            display: flex;
            flex-direction: column;
            gap: var(--space-sm);
        }
        
        .item-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: var(--space-sm);
            padding: var(--space-sm);
            background: var(--color-bg-secondary);
            border-radius: var(--radius-sm);
            border: 1px solid var(--color-border);
        }
        
        .item-name {
            font-weight: 500;
            color: var(--color-text-primary);
        }
        
        .item-quantity {
            font-size: var(--font-size-sm);
            color: var(--color-text-secondary);
        }
        
        .item-date {
            font-size: var(--font-size-xs);
            color: var(--color-text-muted);
        }
        
        .empty-items {
            text-align: center;
            padding: var(--space-lg);
            color: var(--color-text-muted);
            font-style: italic;
        }
    `;
    document.head.appendChild(style);
});
