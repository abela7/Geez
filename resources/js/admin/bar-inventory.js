/**
 * Bar Inventory - Interactive Features
 * Handles filtering, drawer, bulk actions, and AJAX operations
 */

class BarInventoryManager {
    constructor() {
        this.selectedBeverages = new Set();
        this.drawer = null;
        this.bulkActionsBar = null;
        this.init();
    }

    init() {
        this.drawer = document.getElementById('beverageDetailsDrawer');
        this.bulkActionsBar = document.getElementById('bulkActionsBar');
        this.bindEvents();
        this.initializeFilters();
    }

    bindEvents() {
        // Form submission with loading states
        const filtersForm = document.querySelector('.filters-form');
        if (filtersForm) {
            filtersForm.addEventListener('submit', this.handleFilterSubmit.bind(this));
        }

        // Real-time search with debouncing
        const searchInput = document.getElementById('search');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.handleSearch(e.target.value);
                }, 300);
            });
        }

        // Keyboard navigation
        document.addEventListener('keydown', this.handleKeyboardNavigation.bind(this));

        // Close drawer on escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.drawer && this.drawer.classList.contains('open')) {
                this.closeDrawer();
            }
        });

        // Handle table row clicks for mobile
        this.bindTableRowEvents();
    }

    bindTableRowEvents() {
        const tableRows = document.querySelectorAll('.table-row');
        tableRows.forEach(row => {
            // Add touch/click handlers for mobile
            row.addEventListener('click', (e) => {
                if (window.innerWidth <= 767 && !e.target.closest('.beverage-checkbox') && !e.target.closest('.action-buttons')) {
                    const beverageId = row.dataset.beverageId;
                    this.showBeverageDetails(beverageId);
                }
            });
        });
    }

    initializeFilters() {
        // Restore filter state from URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        
        // Set form values from URL
        ['search', 'beverage_type', 'storage_location', 'stock_status'].forEach(param => {
            const element = document.getElementById(param);
            if (element && urlParams.has(param)) {
                element.value = urlParams.get(param);
            }
        });
    }

    handleFilterSubmit(e) {
        const submitButton = e.target.querySelector('button[type="submit"]');
        if (submitButton) {
            const originalText = submitButton.innerHTML;
            submitButton.innerHTML = '<svg class="w-4 h-4 animate-spin mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Filtering...';
            submitButton.disabled = true;
            
            // Re-enable after form submission
            setTimeout(() => {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }, 1000);
        }
    }

    handleSearch(searchTerm) {
        // Update URL without page reload
        const url = new URL(window.location);
        if (searchTerm.trim()) {
            url.searchParams.set('search', searchTerm);
        } else {
            url.searchParams.delete('search');
        }
        
        // Reset to first page when searching
        url.searchParams.delete('page');
        
        window.history.pushState({}, '', url);
        
        // Trigger form submission
        document.querySelector('.filters-form').submit();
    }

    handleKeyboardNavigation(e) {
        // Implement keyboard shortcuts
        if (e.ctrlKey || e.metaKey) {
            switch (e.key) {
                case 'k':
                    e.preventDefault();
                    document.getElementById('search')?.focus();
                    break;
                case 'a':
                    e.preventDefault();
                    this.toggleSelectAll();
                    break;
            }
        }
    }

    // Selection Management
    toggleSelectAll() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const beverageCheckboxes = document.querySelectorAll('.beverage-checkbox');
        
        if (selectAllCheckbox.checked) {
            beverageCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
                this.selectedBeverages.add(checkbox.value);
            });
        } else {
            beverageCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            this.selectedBeverages.clear();
        }
        
        this.updateBulkActions();
    }

    updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.beverage-checkbox:checked');
        const selectedCount = checkedBoxes.length;
        
        // Update selected beverages set
        this.selectedBeverages.clear();
        checkedBoxes.forEach(checkbox => {
            this.selectedBeverages.add(checkbox.value);
        });
        
        // Update UI
        const countElement = document.getElementById('selectedCount');
        if (countElement) {
            countElement.textContent = selectedCount;
        }
        
        // Show/hide bulk actions bar
        if (this.bulkActionsBar) {
            if (selectedCount > 0) {
                this.bulkActionsBar.style.display = 'block';
            } else {
                this.bulkActionsBar.style.display = 'none';
            }
        }
        
        // Update select all checkbox state
        const selectAllCheckbox = document.getElementById('selectAll');
        const totalCheckboxes = document.querySelectorAll('.beverage-checkbox').length;
        
        if (selectAllCheckbox) {
            if (selectedCount === 0) {
                selectAllCheckbox.indeterminate = false;
                selectAllCheckbox.checked = false;
            } else if (selectedCount === totalCheckboxes) {
                selectAllCheckbox.indeterminate = false;
                selectAllCheckbox.checked = true;
            } else {
                selectAllCheckbox.indeterminate = true;
                selectAllCheckbox.checked = false;
            }
        }
    }

    clearSelection() {
        const beverageCheckboxes = document.querySelectorAll('.beverage-checkbox');
        beverageCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        this.selectedBeverages.clear();
        this.updateBulkActions();
    }

    // Beverage Details Drawer
    async showBeverageDetails(beverageId) {
        if (!this.drawer) return;
        
        try {
            this.showDrawerLoading();
            this.openDrawer();
            
            const response = await fetch(`/admin/bar/inventory/${beverageId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (!response.ok) {
                throw new Error('Failed to fetch beverage details');
            }
            
            const data = await response.json();
            this.renderBeverageDetails(data);
            
        } catch (error) {
            console.error('Error fetching beverage details:', error);
            this.showDrawerError('Failed to load beverage details. Please try again.');
        }
    }

    showDrawerLoading() {
        const drawerContent = document.getElementById('drawerContent');
        if (drawerContent) {
            drawerContent.innerHTML = `
                <div class="drawer-loading">
                    <div class="loading-spinner">
                        <svg class="animate-spin w-8 h-8" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <p>Loading beverage details...</p>
                </div>
            `;
        }
    }

    showDrawerError(message) {
        const drawerContent = document.getElementById('drawerContent');
        if (drawerContent) {
            drawerContent.innerHTML = `
                <div class="drawer-error">
                    <div class="error-icon">
                        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <p>${message}</p>
                    <button type="button" class="btn btn-primary btn-sm" onclick="barInventoryManager.closeDrawer()">
                        Close
                    </button>
                </div>
            `;
        }
    }

    renderBeverageDetails(data) {
        const drawerContent = document.getElementById('drawerContent');
        if (!drawerContent) return;
        
        const beverage = data.beverage;
        const stockHistory = data.stock_history || [];
        
        drawerContent.innerHTML = `
            <div class="beverage-details">
                <!-- Basic Information -->
                <div class="detail-section">
                    <h4 class="detail-section-title">Basic Information</h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>Beverage Name</label>
                            <span>${beverage.name}</span>
                        </div>
                        <div class="detail-item">
                            <label>Type</label>
                            <span class="type-badge type-${beverage.beverage_type}">${beverage.beverage_type_display}</span>
                        </div>
                        <div class="detail-item">
                            <label>Brand</label>
                            <span>${beverage.brand || 'N/A'}</span>
                        </div>
                        <div class="detail-item">
                            <label>Storage Location</label>
                            <span class="location-badge">${beverage.storage_location_display}</span>
                        </div>
                        ${beverage.barcode ? `
                        <div class="detail-item">
                            <label>Barcode</label>
                            <span class="font-mono">${beverage.barcode}</span>
                        </div>
                        ` : ''}
                        ${beverage.abv > 0 ? `
                        <div class="detail-item">
                            <label>ABV</label>
                            <span class="abv-badge">${beverage.abv}%</span>
                        </div>
                        ` : `
                        <div class="detail-item">
                            <label>Type</label>
                            <span class="non-alcoholic-badge">Non-Alcoholic</span>
                        </div>
                        `}
                        ${beverage.volume ? `
                        <div class="detail-item">
                            <label>Volume</label>
                            <span>${beverage.volume} ${beverage.unit}</span>
                        </div>
                        ` : ''}
                        ${beverage.expiry_date ? `
                        <div class="detail-item">
                            <label>Expiry Date</label>
                            <span>${new Date(beverage.expiry_date).toLocaleDateString()}</span>
                        </div>
                        ` : ''}
                    </div>
                </div>

                <!-- Stock Information -->
                <div class="detail-section">
                    <h4 class="detail-section-title">Stock Information</h4>
                    <div class="stock-metrics">
                        <div class="metric-card">
                            <div class="metric-value">${parseFloat(beverage.current_stock).toFixed(2)}</div>
                            <div class="metric-label">Current Stock</div>
                            <div class="metric-unit">${beverage.unit}</div>
                        </div>
                        <div class="metric-card">
                            <div class="metric-value">${parseFloat(beverage.minimum_stock || 0).toFixed(2)}</div>
                            <div class="metric-label">Minimum Stock</div>
                            <div class="metric-unit">${beverage.unit}</div>
                        </div>
                        <div class="metric-card">
                            <div class="metric-value">${parseFloat(beverage.maximum_stock || 0).toFixed(2)}</div>
                            <div class="metric-label">Maximum Stock</div>
                            <div class="metric-unit">${beverage.unit}</div>
                        </div>
                        <div class="metric-card">
                            <div class="metric-value">$${parseFloat(data.total_value || 0).toFixed(2)}</div>
                            <div class="metric-label">Total Value</div>
                        </div>
                    </div>
                    
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>Cost per Unit</label>
                            <span>$${parseFloat(beverage.cost_per_unit || 0).toFixed(2)}</span>
                        </div>
                        <div class="detail-item">
                            <label>Selling Price</label>
                            <span>$${parseFloat(beverage.selling_price || 0).toFixed(2)}</span>
                        </div>
                        <div class="detail-item">
                            <label>Status</label>
                            <span class="status-badge status-${data.stock_status}">${data.stock_status_display}</span>
                        </div>
                        ${data.profit_margin ? `
                        <div class="detail-item">
                            <label>Profit Margin</label>
                            <span>${data.profit_margin}%</span>
                        </div>
                        ` : ''}
                    </div>
                </div>

                <!-- Supplier Information -->
                ${beverage.supplier ? `
                <div class="detail-section">
                    <h4 class="detail-section-title">Supplier Information</h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>Supplier Name</label>
                            <span>${beverage.supplier.name}</span>
                        </div>
                        ${beverage.supplier.contact_person ? `
                        <div class="detail-item">
                            <label>Contact Person</label>
                            <span>${beverage.supplier.contact_person}</span>
                        </div>
                        ` : ''}
                        ${beverage.supplier.phone ? `
                        <div class="detail-item">
                            <label>Phone</label>
                            <span>${beverage.supplier.phone}</span>
                        </div>
                        ` : ''}
                        ${beverage.supplier.email ? `
                        <div class="detail-item">
                            <label>Email</label>
                            <span>${beverage.supplier.email}</span>
                        </div>
                        ` : ''}
                    </div>
                </div>
                ` : ''}

                <!-- Recent Stock History -->
                <div class="detail-section">
                    <h4 class="detail-section-title">Recent Stock History</h4>
                    ${stockHistory.length > 0 ? `
                        <div class="stock-history-list">
                            ${stockHistory.map(entry => `
                                <div class="history-item">
                                    <div class="history-header">
                                        <span class="history-type">${entry.type_display}</span>
                                        <span class="history-date">${new Date(entry.created_at).toLocaleDateString()}</span>
                                    </div>
                                    <div class="history-details">
                                        <span class="history-quantity ${entry.quantity >= 0 ? 'positive' : 'negative'}">
                                            ${entry.quantity >= 0 ? '+' : ''}${parseFloat(entry.quantity).toFixed(2)} ${beverage.unit}
                                        </span>
                                        ${entry.notes ? `<span class="history-notes">${entry.notes}</span>` : ''}
                                    </div>
                                    ${entry.user ? `
                                        <div class="history-user">by ${entry.user.name}</div>
                                    ` : ''}
                                </div>
                            `).join('')}
                        </div>
                    ` : `
                        <div class="no-history">
                            <p>No recent stock history found.</p>
                        </div>
                    `}
                </div>

                <!-- Action Buttons -->
                <div class="drawer-actions">
                    <button type="button" class="btn btn-primary" onclick="barInventoryManager.showEditBeverageModal(${beverage.id})">
                        Edit Beverage
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="barInventoryManager.showStockUpdateModal(${beverage.id})">
                        Update Stock
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="barInventoryManager.reorderBeverage(${beverage.id})">
                        Reorder
                    </button>
                </div>
            </div>
        `;
    }

    openDrawer() {
        if (this.drawer) {
            this.drawer.classList.add('open');
            document.body.style.overflow = 'hidden';
        }
    }

    closeDrawer() {
        if (this.drawer) {
            this.drawer.classList.remove('open');
            document.body.style.overflow = '';
        }
    }

    // Modal Functions (Placeholders)
    showAddBeverageModal() {
        // Placeholder for add beverage modal
        this.showNotification('Add beverage functionality will be implemented', 'info');
    }

    showEditBeverageModal(beverageId) {
        // Placeholder for edit beverage modal
        this.showNotification(`Edit beverage ${beverageId} functionality will be implemented`, 'info');
    }

    showStockUpdateModal(beverageId) {
        // Simple prompt for now - would be replaced with proper modal
        const quantity = prompt('Enter stock adjustment (positive for increase, negative for decrease):');
        if (quantity !== null && quantity !== '') {
            const notes = prompt('Enter notes (optional):') || '';
            this.updateStock(beverageId, parseFloat(quantity), notes);
        }
    }

    async updateStock(beverageId, quantity, notes = '') {
        try {
            const response = await fetch(`/admin/bar/inventory/${beverageId}/update-stock`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    quantity: quantity,
                    notes: notes
                })
            });

            const data = await response.json();

            if (data.success) {
                this.showNotification('Stock updated successfully', 'success');
                // Refresh the page or update the table row
                location.reload();
            } else {
                this.showNotification(data.message || 'Failed to update stock', 'error');
            }
        } catch (error) {
            console.error('Error updating stock:', error);
            this.showNotification('Failed to update stock. Please try again.', 'error');
        }
    }

    reorderBeverage(beverageId) {
        // Placeholder for reorder functionality
        this.showNotification(`Reorder beverage ${beverageId} functionality will be implemented`, 'info');
    }

    // Utility Functions
    showNotification(message, type = 'info') {
        // Create and show notification
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

    // Sorting Functions
    updateSort() {
        const sortBy = document.getElementById('sort_by').value;
        const url = new URL(window.location);
        url.searchParams.set('sort_by', sortBy);
        window.location.href = url.toString();
    }

    toggleSortDirection() {
        const url = new URL(window.location);
        const currentDirection = url.searchParams.get('sort_direction') || 'asc';
        const newDirection = currentDirection === 'asc' ? 'desc' : 'asc';
        url.searchParams.set('sort_direction', newDirection);
        window.location.href = url.toString();
    }

    // Filter Functions
    clearFilters() {
        const url = new URL(window.location);
        url.search = '';
        window.location.href = url.toString();
    }

    // Export Functions
    exportData() {
        const url = new URL('/admin/bar/inventory/export', window.location.origin);
        
        // Add current filters to export
        const currentParams = new URLSearchParams(window.location.search);
        currentParams.forEach((value, key) => {
            if (key !== 'page') {
                url.searchParams.set(key, value);
            }
        });
        
        window.open(url.toString(), '_blank');
    }

    // Bulk Actions
    bulkUpdateStock() {
        if (this.selectedBeverages.size === 0) return;
        
        const quantity = prompt('Enter stock adjustment for selected beverages:');
        if (quantity !== null && quantity !== '') {
            const notes = prompt('Enter notes (optional):') || '';
            this.performBulkAction('bulk-update-stock', {
                beverages: Array.from(this.selectedBeverages),
                quantity: parseFloat(quantity),
                notes: notes
            });
        }
    }

    async performBulkAction(action, data) {
        try {
            const response = await fetch(`/admin/bar/inventory/${action}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                this.showNotification('Bulk action completed successfully', 'success');
                location.reload();
            } else {
                this.showNotification(result.message || 'Bulk action failed', 'error');
            }
        } catch (error) {
            console.error('Error performing bulk action:', error);
            this.showNotification('Bulk action failed. Please try again.', 'error');
        }
    }

    // Data Refresh
    refreshData() {
        location.reload();
    }
}

// Global Functions (called from HTML)
let barInventoryManager;

document.addEventListener('DOMContentLoaded', function() {
    barInventoryManager = new BarInventoryManager();
});

// Global functions for HTML onclick handlers
function showBeverageDetails(beverageId) {
    barInventoryManager?.showBeverageDetails(beverageId);
}

function closeDrawer() {
    barInventoryManager?.closeDrawer();
}

function toggleSelectAll() {
    barInventoryManager?.toggleSelectAll();
}

function updateBulkActions() {
    barInventoryManager?.updateBulkActions();
}

function clearSelection() {
    barInventoryManager?.clearSelection();
}

function showAddBeverageModal() {
    barInventoryManager?.showAddBeverageModal();
}

function showEditBeverageModal(beverageId) {
    barInventoryManager?.showEditBeverageModal(beverageId);
}

function updateSort() {
    barInventoryManager?.updateSort();
}

function toggleSortDirection() {
    barInventoryManager?.toggleSortDirection();
}

function clearFilters() {
    barInventoryManager?.clearFilters();
}

function exportData() {
    barInventoryManager?.exportData();
}

function refreshData() {
    barInventoryManager?.refreshData();
}

function bulkUpdateStock() {
    barInventoryManager?.bulkUpdateStock();
}

function bulkExport() {
    barInventoryManager?.exportData();
}
