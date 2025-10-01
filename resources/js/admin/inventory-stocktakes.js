/**
 * Inventory Stocktakes Page JavaScript
 * Handles drawer interactions, variance calculations, and stocktake management
 */

// Alpine.js component for stocktakes page
function stocktakesPage() {
    return {
        // State
        showDetailsDrawer: false,
        showFormDrawer: false,
        showFilters: false,
        selectedStocktake: null,
        loadingDetails: false,
        editingStocktake: false,
        loading: false,
        isEmpty: false,
        
        // Form data
        stocktakeForm: {
            date: '',
            staff_member: '',
            location: '',
            notes: '',
            items: []
        },

        // Search debounce
        searchTimeout: null,

        // Mock data for expected quantities
        mockInventoryData: {
            1: { name: 'Tomatoes', code: 'TOM-001', expected_qty: 20, unit: 'kg', cost: 3.25 },
            2: { name: 'Rice', code: 'RIC-001', expected_qty: 15, unit: 'kg', cost: 2.80 },
            3: { name: 'Chicken Breast', code: 'CHK-001', expected_qty: 8, unit: 'kg', cost: 8.50 },
            4: { name: 'Olive Oil', code: 'OIL-003', expected_qty: 12, unit: 'L', cost: 15.75 },
            5: { name: 'Onions', code: 'ONI-001', expected_qty: 25, unit: 'kg', cost: 2.10 },
            6: { name: 'Pasta', code: 'PAS-001', expected_qty: 18, unit: 'kg', cost: 1.95 },
            7: { name: 'Cheese', code: 'CHE-001', expected_qty: 5, unit: 'kg', cost: 12.50 },
            8: { name: 'Garlic', code: 'GAR-001', expected_qty: 3, unit: 'kg', cost: 4.20 }
        },

        // Mock stocktake data
        mockStocktakes: {
            1: {
                id: 'ST-001',
                date: 'Dec 10, 2024',
                staff: 'John Doe',
                location: 'Main Kitchen',
                status: 'completed',
                variance: '-$125.50',
                total_value: '$5,420.00',
                items: [
                    { item_id: 1, expected_qty: 20, actual_qty: 18, variance: -2, unit: 'kg', cost: 3.25 },
                    { item_id: 2, expected_qty: 15, actual_qty: 16, variance: 1, unit: 'kg', cost: 2.80 },
                    { item_id: 3, expected_qty: 8, actual_qty: 8, variance: 0, unit: 'kg', cost: 8.50 },
                    { item_id: 4, expected_qty: 12, actual_qty: 8, variance: -4, unit: 'L', cost: 15.75 }
                ]
            },
            2: {
                id: 'ST-002',
                date: 'Dec 11, 2024',
                staff: 'Jane Smith',
                location: 'Cold Storage',
                status: 'in_progress',
                variance: 'Pending',
                total_value: '$2,850.00'
            }
        },

        // Initialize component
        init() {
            console.log('Stocktakes page initialized');
            this.resetStocktakeForm();
            
            // Set default date to today
            this.stocktakeForm.date = new Date().toISOString().split('T')[0];
        },

        // Open stocktake details drawer
        async openStocktakeDetails(stocktakeId) {
            this.showDetailsDrawer = true;
            this.loadingDetails = true;
            this.selectedStocktake = null;

            try {
                // Simulate API call delay
                await new Promise(resolve => setTimeout(resolve, 800));
                
                // Get mock data
                this.selectedStocktake = this.mockStocktakes[stocktakeId] || this.mockStocktakes[1];
                
            } catch (error) {
                console.error('Error loading stocktake details:', error);
                this.showError('Error loading stocktake details');
            } finally {
                this.loadingDetails = false;
            }
        },

        // Close details drawer
        closeDetailsDrawer() {
            this.showDetailsDrawer = false;
            this.selectedStocktake = null;
            this.loadingDetails = false;
        },

        // Open new stocktake drawer
        openNewStocktakeDrawer() {
            this.showFormDrawer = true;
            this.editingStocktake = false;
            this.resetStocktakeForm();
            this.stocktakeForm.date = new Date().toISOString().split('T')[0];
        },

        // Close form drawer
        closeFormDrawer() {
            this.showFormDrawer = false;
            this.editingStocktake = false;
            this.resetStocktakeForm();
        },

        // Reset stocktake form
        resetStocktakeForm() {
            this.stocktakeForm = {
                date: '',
                staff_member: '',
                location: '',
                notes: '',
                items: []
            };
        },

        // Edit stocktake
        editStocktake() {
            if (!this.selectedStocktake) return;
            
            // Populate form with selected stocktake data
            this.stocktakeForm = {
                date: this.selectedStocktake.date || '',
                staff_member: 'john_doe', // Mock data
                location: this.selectedStocktake.location?.toLowerCase().replace(' ', '_') || '',
                notes: '',
                items: this.selectedStocktake.items?.map(item => ({
                    item_id: item.item_id,
                    expected_qty: item.expected_qty,
                    actual_qty: item.actual_qty,
                    variance: item.variance,
                    variance_display: this.formatVariance(item.variance, item.unit)
                })) || []
            };
            
            this.editingStocktake = true;
            this.closeDetailsDrawer();
            this.showFormDrawer = true;
        },

        // Add item to stocktake
        addItem() {
            this.stocktakeForm.items.push({
                item_id: '',
                expected_qty: '',
                actual_qty: '',
                variance: 0,
                variance_display: '0'
            });
        },

        // Remove item from stocktake
        removeItem(index) {
            this.stocktakeForm.items.splice(index, 1);
        },

        // Update expected quantity when item is selected
        updateExpectedQuantity(index) {
            const item = this.stocktakeForm.items[index];
            const itemData = this.mockInventoryData[item.item_id];
            
            if (itemData) {
                item.expected_qty = itemData.expected_qty;
                item.unit = itemData.unit;
                item.cost = itemData.cost;
                
                // Recalculate variance if actual quantity exists
                if (item.actual_qty !== '') {
                    this.calculateVariance(index);
                }
            }
        },

        // Calculate variance for an item
        calculateVariance(index) {
            const item = this.stocktakeForm.items[index];
            const expectedQty = parseFloat(item.expected_qty) || 0;
            const actualQty = parseFloat(item.actual_qty) || 0;
            
            item.variance = actualQty - expectedQty;
            
            // Format variance display
            const itemData = this.mockInventoryData[item.item_id];
            const unit = itemData?.unit || 'units';
            item.variance_display = this.formatVariance(item.variance, unit);
        },

        // Format variance display
        formatVariance(variance, unit) {
            if (variance === 0) {
                return `0 ${unit}`;
            } else if (variance > 0) {
                return `+${variance} ${unit}`;
            } else {
                return `${variance} ${unit}`;
            }
        },

        // Get variance CSS class
        getVarianceClass(variance) {
            if (variance > 0) return 'positive';
            if (variance < 0) return 'negative';
            return 'zero';
        },

        // Get items with variance count
        getItemsWithVarianceCount() {
            return this.stocktakeForm.items.filter(item => 
                item.variance !== 0 && item.variance !== ''
            ).length;
        },

        // Get total variance
        getTotalVariance() {
            return this.stocktakeForm.items.reduce((total, item) => {
                const itemData = this.mockInventoryData[item.item_id];
                if (itemData && item.variance !== '') {
                    return total + (item.variance * itemData.cost);
                }
                return total;
            }, 0);
        },

        // Get total variance display
        getTotalVarianceDisplay() {
            const totalVariance = this.getTotalVariance();
            return this.formatCurrency(totalVariance);
        },

        // Get total variance CSS class
        getTotalVarianceClass() {
            const totalVariance = this.getTotalVariance();
            if (totalVariance > 0) return 'positive';
            if (totalVariance < 0) return 'negative';
            return 'zero';
        },

        // Save stocktake
        async saveStocktake() {
            try {
                // Validate form
                if (!this.validateStocktakeForm()) {
                    return;
                }

                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 1000));

                this.showSuccess(this.editingStocktake ? 
                    'Stocktake updated successfully' : 
                    'Stocktake created successfully'
                );
                
                this.closeFormDrawer();
                
                // Refresh page data (in real app, would reload from server)
                // window.location.reload();
                
            } catch (error) {
                console.error('Error saving stocktake:', error);
                this.showError('Error saving stocktake');
            }
        },

        // Save as draft
        async saveDraft() {
            try {
                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 800));

                this.showSuccess('Stocktake saved as draft');
                this.closeFormDrawer();
                
            } catch (error) {
                console.error('Error saving draft:', error);
                this.showError('Error saving draft');
            }
        },

        // Validate stocktake form
        validateStocktakeForm() {
            const form = this.stocktakeForm;
            
            if (!form.date) {
                this.showError('Stocktake date is required');
                return false;
            }
            
            if (!form.staff_member) {
                this.showError('Staff member is required');
                return false;
            }
            
            if (!form.location) {
                this.showError('Location is required');
                return false;
            }
            
            if (form.items.length === 0) {
                this.showError('At least one item must be counted');
                return false;
            }
            
            // Validate each item
            for (let i = 0; i < form.items.length; i++) {
                const item = form.items[i];
                
                if (!item.item_id) {
                    this.showError(`Item ${i + 1}: Please select an item`);
                    return false;
                }
                
                if (item.actual_qty === '' || item.actual_qty < 0) {
                    this.showError(`Item ${i + 1}: Please enter a valid quantity`);
                    return false;
                }
            }
            
            return true;
        },

        // Finalize stocktake
        async finalizeStocktake() {
            if (!this.selectedStocktake) return;
            
            if (!confirm('Are you sure you want to finalize this stocktake? This action cannot be undone.')) {
                return;
            }

            try {
                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 1000));

                this.showSuccess('Stocktake finalized successfully');
                this.closeDetailsDrawer();
                
                // Refresh page data
                // window.location.reload();
                
            } catch (error) {
                console.error('Error finalizing stocktake:', error);
                this.showError('Error finalizing stocktake');
            }
        },

        // Delete stocktake
        async deleteStocktake() {
            if (!this.selectedStocktake) return;
            
            if (!confirm('Are you sure you want to delete this stocktake?')) {
                return;
            }

            try {
                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 800));

                this.showSuccess('Stocktake deleted successfully');
                this.closeDetailsDrawer();
                
                // Refresh page data
                // window.location.reload();
                
            } catch (error) {
                console.error('Error deleting stocktake:', error);
                this.showError('Error deleting stocktake');
            }
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
            // In real app: window.location.href = url.toString();
            console.log('Search:', searchTerm);
        },

        // Format currency
        formatCurrency(value) {
            const absValue = Math.abs(value);
            const formatted = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(absValue);
            
            if (value < 0) {
                return `-${formatted}`;
            } else if (value > 0) {
                return `+${formatted}`;
            }
            return formatted;
        },

        // Show success message
        showSuccess(message) {
            // You can implement a toast notification system here
            console.log('Success:', message);
            // Temporary implementation
            alert(message);
        },

        // Show error message
        showError(message) {
            // You can implement a toast notification system here
            console.error('Error:', message);
            // Temporary implementation
            alert(message);
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
    if (label.includes('location')) return 'location';
    if (label.includes('date')) return 'date_range';
    
    return 'search';
}

// Utility functions
function calculateItemVariance(expectedQty, actualQty, unitCost) {
    const qtyVariance = actualQty - expectedQty;
    const valueVariance = qtyVariance * unitCost;
    
    return {
        quantity: qtyVariance,
        value: valueVariance,
        percentage: expectedQty > 0 ? (qtyVariance / expectedQty) * 100 : 0
    };
}

function formatVariancePercentage(percentage) {
    const absPercentage = Math.abs(percentage);
    const formatted = absPercentage.toFixed(1) + '%';
    
    if (percentage > 0) {
        return `+${formatted}`;
    } else if (percentage < 0) {
        return `-${formatted}`;
    }
    return '0.0%';
}

function getVarianceStatus(variance) {
    if (variance > 0) return 'surplus';
    if (variance < 0) return 'shortage';
    return 'none';
}

function getVarianceColor(variance) {
    if (variance > 0) return 'var(--color-success)';
    if (variance < 0) return 'var(--color-danger)';
    return 'var(--color-text-muted)';
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Stocktakes page JavaScript loaded');
    
    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + N for new stocktake
        if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
            e.preventDefault();
            const component = Alpine.$data(document.querySelector('.stocktakes-container'));
            if (component) {
                component.openNewStocktakeDrawer();
            }
        }
        
        // Escape to close drawers
        if (e.key === 'Escape') {
            const component = Alpine.$data(document.querySelector('.stocktakes-container'));
            if (component) {
                if (component.showFormDrawer) {
                    component.closeFormDrawer();
                } else if (component.showDetailsDrawer) {
                    component.closeDetailsDrawer();
                }
            }
        }
    });
    
    // Add styles for variance calculations
    const style = document.createElement('style');
    style.textContent = `
        .variance-calculation {
            display: flex;
            flex-direction: column;
            gap: 4px;
            font-size: var(--font-size-xs);
        }
        
        .variance-qty-line {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .variance-value-line {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
        }
        
        .variance-positive {
            color: var(--color-success);
        }
        
        .variance-negative {
            color: var(--color-danger);
        }
        
        .variance-zero {
            color: var(--color-text-muted);
        }
        
        .stocktake-progress {
            display: flex;
            align-items: center;
            gap: var(--space-xs);
            font-size: var(--font-size-xs);
            color: var(--color-text-secondary);
        }
        
        .progress-bar {
            width: 60px;
            height: 4px;
            background: var(--color-bg-tertiary);
            border-radius: 2px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: var(--color-primary);
            transition: width 0.3s ease;
        }
        
        .variance-highlight {
            animation: variance-pulse 2s ease-in-out;
        }
        
        @keyframes variance-pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .item-form-row.has-variance {
            border-left: 3px solid var(--color-warning);
        }
        
        .item-form-row.has-positive-variance {
            border-left: 3px solid var(--color-success);
        }
        
        .item-form-row.has-negative-variance {
            border-left: 3px solid var(--color-danger);
        }
    `;
    document.head.appendChild(style);
});

// Export functions for global access if needed
window.stocktakeUtils = {
    calculateItemVariance,
    formatVariancePercentage,
    getVarianceStatus,
    getVarianceColor,
    
    formatCurrency: (value) => {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(value);
    },
    
    formatQuantity: (value, unit) => {
        return `${parseFloat(value).toFixed(2)} ${unit}`;
    },
    
    calculateStocktakeSummary: (items) => {
        let totalItems = items.length;
        let itemsWithVariance = 0;
        let totalVarianceValue = 0;
        
        items.forEach(item => {
            if (item.variance !== 0) {
                itemsWithVariance++;
                totalVarianceValue += item.variance * (item.cost || 0);
            }
        });
        
        return {
            totalItems,
            itemsWithVariance,
            totalVarianceValue,
            accuracyPercentage: totalItems > 0 ? 
                ((totalItems - itemsWithVariance) / totalItems) * 100 : 100
        };
    }
};
