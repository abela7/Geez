/**
 * Stock Levels - Interactive Features
 * Handles filtering, drawer, bulk actions, and AJAX operations
 */

class StockLevelsManager {
    constructor() {
        this.selectedItems = new Set();
        this.drawer = null;
        this.bulkActionsBar = null;
        this.init();
    }

    init() {
        this.drawer = document.getElementById('itemDetailsDrawer');
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
                if (window.innerWidth <= 767 && !e.target.closest('.item-checkbox') && !e.target.closest('.action-buttons')) {
                    const itemId = row.dataset.itemId;
                    this.showItemDetails(itemId);
                }
            });
        });
    }

    initializeFilters() {
        // Restore filter state from URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        
        // Set form values from URL
        ['search', 'category', 'location', 'supplier_id', 'status_filter'].forEach(param => {
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
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        
        if (selectAllCheckbox.checked) {
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
                this.selectedItems.add(checkbox.value);
            });
        } else {
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            this.selectedItems.clear();
        }
        
        this.updateBulkActions();
    }

    updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
        const selectedCount = checkedBoxes.length;
        
        // Update selected items set
        this.selectedItems.clear();
        checkedBoxes.forEach(checkbox => {
            this.selectedItems.add(checkbox.value);
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
        const totalCheckboxes = document.querySelectorAll('.item-checkbox').length;
        
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
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        itemCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        this.selectedItems.clear();
        this.updateBulkActions();
    }

    // Item Details Drawer
    async showItemDetails(itemId) {
        if (!this.drawer) return;
        
        try {
            this.showDrawerLoading();
            this.openDrawer();
            
            const response = await fetch(`/admin/inventory/stock-levels/${itemId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (!response.ok) {
                throw new Error('Failed to fetch item details');
            }
            
            const data = await response.json();
            this.renderItemDetails(data);
            
        } catch (error) {
            console.error('Error fetching item details:', error);
            this.showDrawerError('Failed to load item details. Please try again.');
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
                    <p>Loading item details...</p>
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
                    <button type="button" class="btn btn-primary btn-sm" onclick="stockLevelsManager.closeDrawer()">
                        Close
                    </button>
                </div>
            `;
        }
    }

    renderItemDetails(data) {
        const drawerContent = document.getElementById('drawerContent');
        if (!drawerContent) return;
        
        const item = data.item;
        const movements = data.movements || [];
        
        drawerContent.innerHTML = `
            <div class="item-details">
                <!-- Basic Information -->
                <div class="detail-section">
                    <h4 class="detail-section-title">Basic Information</h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>Item Name</label>
                            <span>${item.name}</span>
                        </div>
                        <div class="detail-item">
                            <label>Item Code</label>
                            <span class="font-mono">${item.code}</span>
                        </div>
                        <div class="detail-item">
                            <label>Category</label>
                            <span class="category-badge category-${item.category}">${item.category}</span>
                        </div>
                        <div class="detail-item">
                            <label>Location</label>
                            <span class="location-badge">${item.location}</span>
                        </div>
                        ${item.barcode ? `
                        <div class="detail-item">
                            <label>Barcode</label>
                            <span class="font-mono">${item.barcode}</span>
                        </div>
                        ` : ''}
                        ${item.description ? `
                        <div class="detail-item detail-item-full">
                            <label>Description</label>
                            <span>${item.description}</span>
                        </div>
                        ` : ''}
                    </div>
                </div>

                <!-- Stock Information -->
                <div class="detail-section">
                    <h4 class="detail-section-title">Stock Information</h4>
                    <div class="stock-metrics">
                        <div class="metric-card">
                            <div class="metric-value">${parseFloat(item.current_stock).toFixed(2)}</div>
                            <div class="metric-label">Current Stock</div>
                            <div class="metric-unit">${item.unit}</div>
                        </div>
                        <div class="metric-card">
                            <div class="metric-value">${parseFloat(data.available_stock).toFixed(2)}</div>
                            <div class="metric-label">Available</div>
                            <div class="metric-unit">${item.unit}</div>
                        </div>
                        <div class="metric-card">
                            <div class="metric-value">${parseFloat(item.reserved_stock).toFixed(2)}</div>
                            <div class="metric-label">Reserved</div>
                            <div class="metric-unit">${item.unit}</div>
                        </div>
                        <div class="metric-card">
                            <div class="metric-value">$${parseFloat(data.total_value).toFixed(2)}</div>
                            <div class="metric-label">Total Value</div>
                        </div>
                    </div>
                    
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>Reorder Level</label>
                            <span>${parseFloat(item.reorder_level).toFixed(2)} ${item.unit}</span>
                        </div>
                        <div class="detail-item">
                            <label>Status</label>
                            <span class="status-badge status-${data.stock_status}">${data.stock_status}</span>
                        </div>
                        ${data.days_remaining ? `
                        <div class="detail-item">
                            <label>Days Remaining</label>
                            <span>${data.days_remaining} days</span>
                        </div>
                        ` : ''}
                        <div class="detail-item">
                            <label>Cost per Unit</label>
                            <span>$${parseFloat(item.cost_per_unit).toFixed(2)}</span>
                        </div>
                    </div>
                </div>

                <!-- Supplier Information -->
                ${item.supplier ? `
                <div class="detail-section">
                    <h4 class="detail-section-title">Supplier Information</h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>Supplier Name</label>
                            <span>${item.supplier.name}</span>
                        </div>
                        <div class="detail-item">
                            <label>Supplier Code</label>
                            <span class="font-mono">${item.supplier.code}</span>
                        </div>
                        ${item.supplier.contact_person ? `
                        <div class="detail-item">
                            <label>Contact Person</label>
                            <span>${item.supplier.contact_person}</span>
                        </div>
                        ` : ''}
                        ${item.supplier.phone ? `
                        <div class="detail-item">
                            <label>Phone</label>
                            <span>${item.supplier.phone}</span>
                        </div>
                        ` : ''}
                    </div>
                </div>
                ` : ''}

                <!-- Recent Movements -->
                <div class="detail-section">
                    <h4 class="detail-section-title">Recent Stock Movements</h4>
                    ${movements.length > 0 ? `
                        <div class="movements-list">
                            ${movements.map(movement => `
                                <div class="movement-item">
                                    <div class="movement-header">
                                        <span class="movement-type">${movement.formatted_type}</span>
                                        <span class="movement-date">${new Date(movement.movement_date).toLocaleDateString()}</span>
                                    </div>
                                    <div class="movement-details">
                                        <span class="movement-quantity ${movement.quantity >= 0 ? 'positive' : 'negative'}">
                                            ${movement.quantity >= 0 ? '+' : ''}${parseFloat(movement.quantity).toFixed(2)} ${item.unit}
                                        </span>
                                        ${movement.reason ? `<span class="movement-reason">${movement.reason}</span>` : ''}
                                    </div>
                                    ${movement.user ? `
                                        <div class="movement-user">by ${movement.user.name}</div>
                                    ` : ''}
                                </div>
                            `).join('')}
                        </div>
                    ` : `
                        <div class="no-movements">
                            <p>No recent stock movements found.</p>
                        </div>
                    `}
                </div>

                <!-- Action Buttons -->
                <div class="drawer-actions">
                    <button type="button" class="btn btn-primary" onclick="stockLevelsManager.showAdjustStockModal(${item.id})">
                        Adjust Stock
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="stockLevelsManager.showTransferStockModal(${item.id})">
                        Transfer Stock
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="stockLevelsManager.createPurchaseOrder(${item.id})">
                        Create PO
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

    // Stock Adjustment Modal
    showAdjustStockModal(itemId) {
        // This would show a modal for stock adjustment
        // For now, we'll use a simple prompt
        const quantity = prompt('Enter quantity adjustment (positive for increase, negative for decrease):');
        if (quantity !== null && quantity !== '') {
            const reason = prompt('Enter reason for adjustment (optional):') || '';
            this.adjustStock(itemId, parseFloat(quantity), 'adjusted', reason);
        }
    }

    async adjustStock(itemId, quantity, type, reason = '') {
        try {
            const response = await fetch(`/admin/inventory/stock-levels/${itemId}/update-stock`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    quantity: quantity,
                    type: type,
                    reason: reason
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
        const url = new URL('/admin/inventory/stock-levels/export', window.location.origin);
        
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
    bulkAdjustStock() {
        if (this.selectedItems.size === 0) return;
        
        const quantity = prompt('Enter quantity adjustment for selected items:');
        if (quantity !== null && quantity !== '') {
            const reason = prompt('Enter reason for adjustment (optional):') || '';
            this.performBulkAction('bulk-update', {
                items: Array.from(this.selectedItems).map(id => ({ id, quantity: parseFloat(quantity) })),
                type: 'adjusted',
                reason: reason
            });
        }
    }

    async performBulkAction(action, data) {
        try {
            const response = await fetch(`/admin/inventory/stock-levels/${action}`, {
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
let stockLevelsManager;

document.addEventListener('DOMContentLoaded', function() {
    stockLevelsManager = new StockLevelsManager();
});

// Global functions for HTML onclick handlers
function showItemDetails(itemId) {
    stockLevelsManager?.showItemDetails(itemId);
}

function closeDrawer() {
    stockLevelsManager?.closeDrawer();
}

function toggleSelectAll() {
    stockLevelsManager?.toggleSelectAll();
}

function updateBulkActions() {
    stockLevelsManager?.updateBulkActions();
}

function clearSelection() {
    stockLevelsManager?.clearSelection();
}

function showAdjustStockModal(itemId) {
    stockLevelsManager?.showAdjustStockModal(itemId);
}

function updateSort() {
    stockLevelsManager?.updateSort();
}

function toggleSortDirection() {
    stockLevelsManager?.toggleSortDirection();
}

function clearFilters() {
    stockLevelsManager?.clearFilters();
}

function exportData() {
    stockLevelsManager?.exportData();
}

function refreshData() {
    stockLevelsManager?.refreshData();
}

function bulkAdjustStock() {
    stockLevelsManager?.bulkAdjustStock();
}

function bulkTransferStock() {
    // Placeholder for bulk transfer functionality
    alert('Bulk transfer functionality will be implemented');
}

function bulkExport() {
    stockLevelsManager?.exportData();
}

function showAddItemModal() {
    // Placeholder for add item functionality
    alert('Add item functionality will be implemented');
}
