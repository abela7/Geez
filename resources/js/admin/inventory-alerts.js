/**
 * Inventory Alerts Page JavaScript
 * Handles drawer interactions, filtering, and alert rule management
 */

// Alpine.js component for alerts page
function alertsPage() {
    return {
        // State
        showRuleDrawer: false,
        showAddRuleDrawer: false,
        showFilters: false,
        selectedRule: null,
        loadingRule: false,
        editingRule: false,
        
        // Form data
        ruleForm: {
            inventory_item_id: '',
            location: '',
            minimum_threshold: '',
            is_active: true
        },

        // Search debounce
        searchTimeout: null,

        // Initialize component
        init() {
            console.log('Alerts page initialized');
        },

        // Filter by status (from overview cards)
        filterByStatus(status) {
            const url = new URL(window.location);
            url.searchParams.set('status', status);
            window.location.href = url.toString();
        },

        // Open alert rule details drawer
        async openRuleDetails(ruleId) {
            this.showRuleDrawer = true;
            this.loadingRule = true;
            this.selectedRule = null;

            try {
                const response = await fetch(`/admin/inventory/alerts/${ruleId}`);
                const data = await response.json();
                
                if (data.success) {
                    this.selectedRule = data.alertRule;
                    this.renderRuleDetails();
                } else {
                    this.showError('Failed to load alert rule details');
                }
            } catch (error) {
                console.error('Error loading alert rule:', error);
                this.showError('Error loading alert rule details');
            } finally {
                this.loadingRule = false;
            }
        },

        // Render alert rule details in drawer
        renderRuleDetails() {
            if (!this.selectedRule) return;

            const rule = this.selectedRule;
            const detailsContainer = document.querySelector('.rule-details');
            
            if (!detailsContainer) return;

            detailsContainer.innerHTML = `
                <div class="detail-section">
                    <h4 class="detail-section-title">${this.trans('inventory.alerts.item_information')}</h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>${this.trans('inventory.alerts.item_name')}</label>
                            <div class="detail-value">${rule.inventory_item.name}</div>
                        </div>
                        <div class="detail-item">
                            <label>${this.trans('inventory.alerts.item_code')}</label>
                            <div class="detail-value">${rule.inventory_item.code}</div>
                        </div>
                        <div class="detail-item">
                            <label>${this.trans('inventory.alerts.item_category')}</label>
                            <div class="detail-value">${rule.inventory_item.category}</div>
                        </div>
                        <div class="detail-item">
                            <label>${this.trans('inventory.alerts.current_stock_level')}</label>
                            <div class="detail-value">
                                <span class="stock-amount stock-${rule.status}">${rule.inventory_item.current_stock}</span>
                                <span class="stock-unit">${rule.inventory_item.unit}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <h4 class="detail-section-title">${this.trans('inventory.alerts.rule_configuration')}</h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>${this.trans('inventory.alerts.threshold_value')}</label>
                            <div class="detail-value">
                                ${rule.minimum_threshold} ${rule.inventory_item.unit}
                            </div>
                        </div>
                        ${rule.location ? `
                            <div class="detail-item">
                                <label>${this.trans('inventory.alerts.rule_location')}</label>
                                <div class="detail-value">${rule.location}</div>
                            </div>
                        ` : ''}
                        <div class="detail-item">
                            <label>${this.trans('inventory.alerts.rule_status')}</label>
                            <div class="detail-value">
                                <span class="status-badge status-${rule.is_active ? 'ok' : 'inactive'}">
                                    ${rule.is_active ? this.trans('inventory.alerts.rule_active') : this.trans('inventory.alerts.rule_inactive')}
                                </span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label>${this.trans('inventory.alerts.current_status')}</label>
                            <div class="detail-value">
                                <span class="status-badge status-${rule.status}">
                                    ${this.getStatusLabel(rule.status)}
                                </span>
                                ${rule.is_triggered ? ' 🚨' : ''}
                            </div>
                        </div>
                    </div>
                </div>

                ${rule.last_triggered_at ? `
                    <div class="detail-section">
                        <h4 class="detail-section-title">${this.trans('inventory.alerts.alert_history')}</h4>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <label>${this.trans('inventory.alerts.last_triggered')}</label>
                                <div class="detail-value">${rule.last_triggered_at}</div>
                            </div>
                        </div>
                    </div>
                ` : `
                    <div class="detail-section">
                        <h4 class="detail-section-title">${this.trans('inventory.alerts.alert_history')}</h4>
                        <div class="no-history">
                            <p>${this.trans('inventory.alerts.no_alert_history')}</p>
                        </div>
                    </div>
                `}

                <div class="detail-section">
                    <h4 class="detail-section-title">${this.trans('inventory.alerts.rule_details')}</h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>${this.trans('inventory.alerts.created_date')}</label>
                            <div class="detail-value">${rule.created_at}</div>
                        </div>
                        <div class="detail-item">
                            <label>${this.trans('inventory.alerts.last_updated')}</label>
                            <div class="detail-value">${rule.updated_at}</div>
                        </div>
                    </div>
                </div>
            `;
        },

        // Get status label
        getStatusLabel(status) {
            const labels = {
                'ok': this.trans('inventory.alerts.alert_statuses.ok'),
                'low': this.trans('inventory.alerts.alert_statuses.low'),
                'out': this.trans('inventory.alerts.alert_statuses.out'),
                'inactive': this.trans('inventory.alerts.alert_statuses.inactive')
            };
            return labels[status] || status;
        },

        // Close rule details drawer
        closeRuleDrawer() {
            this.showRuleDrawer = false;
            this.selectedRule = null;
            this.loadingRule = false;
        },

        // Open add rule drawer
        openAddRuleDrawer() {
            this.showAddRuleDrawer = true;
            this.editingRule = false;
            this.resetRuleForm();
        },

        // Close add rule drawer
        closeAddRuleDrawer() {
            this.showAddRuleDrawer = false;
            this.editingRule = false;
            this.resetRuleForm();
        },

        // Reset rule form
        resetRuleForm() {
            this.ruleForm = {
                inventory_item_id: '',
                location: '',
                minimum_threshold: '',
                is_active: true
            };
        },

        // Edit rule
        editRule() {
            if (!this.selectedRule) return;
            
            // Populate form with selected rule data
            this.ruleForm = {
                inventory_item_id: this.selectedRule.inventory_item.id || '',
                location: this.selectedRule.location || '',
                minimum_threshold: this.selectedRule.minimum_threshold || '',
                is_active: this.selectedRule.is_active !== false
            };
            
            this.editingRule = true;
            this.closeRuleDrawer();
            this.showAddRuleDrawer = true;
        },

        // Save rule (create or update)
        async saveRule() {
            try {
                // Validate form
                if (!this.validateRuleForm()) {
                    return;
                }

                const url = this.editingRule 
                    ? `/admin/inventory/alerts/${this.selectedRule.id}`
                    : '/admin/inventory/alerts';
                
                const method = this.editingRule ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.ruleForm)
                });

                const data = await response.json();

                if (data.success) {
                    this.showSuccess(data.message);
                    this.closeAddRuleDrawer();
                    // Reload page to show updated data
                    window.location.reload();
                } else {
                    this.showError(data.message || 'Failed to save alert rule');
                }
            } catch (error) {
                console.error('Error saving alert rule:', error);
                this.showError('Error saving alert rule');
            }
        },

        // Validate rule form
        validateRuleForm() {
            const form = this.ruleForm;
            
            if (!form.inventory_item_id) {
                this.showError(this.trans('inventory.alerts.item_required'));
                return false;
            }
            
            if (!form.minimum_threshold || form.minimum_threshold < 0) {
                this.showError(this.trans('inventory.alerts.threshold_positive'));
                return false;
            }
            
            if (isNaN(parseFloat(form.minimum_threshold))) {
                this.showError(this.trans('inventory.alerts.threshold_numeric'));
                return false;
            }
            
            return true;
        },

        // Delete rule
        async deleteRule() {
            if (!this.selectedRule) return;
            
            if (!confirm(this.trans('inventory.alerts.confirm_delete'))) {
                return;
            }

            try {
                const response = await fetch(`/admin/inventory/alerts/${this.selectedRule.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.showSuccess(data.message);
                    this.closeRuleDrawer();
                    // Reload page to show updated data
                    window.location.reload();
                } else {
                    this.showError(data.message || 'Failed to delete alert rule');
                }
            } catch (error) {
                console.error('Error deleting alert rule:', error);
                this.showError('Error deleting alert rule');
            }
        },

        // Create PO from rule
        createPOFromRule() {
            if (!this.selectedRule) return;
            
            // Navigate to purchase order creation with item pre-selected
            window.location.href = `/admin/inventory/purchasing?item_id=${this.selectedRule.inventory_item.id}`;
        },

        // Create PO for specific item
        createPOForItem(itemId) {
            // Navigate to purchase order creation with item pre-selected
            window.location.href = `/admin/inventory/purchasing?item_id=${itemId}`;
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
    if (label.includes('location')) return 'location';
    
    return 'search';
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Alerts page JavaScript loaded');
    
    // Add styles for alert rule details
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
        
        .stock-amount {
            font-weight: 600;
        }
        
        .stock-amount.stock-ok {
            color: var(--color-success);
        }
        
        .stock-amount.stock-low {
            color: var(--color-warning);
        }
        
        .stock-amount.stock-out {
            color: var(--color-danger);
        }
        
        .stock-unit {
            font-size: var(--font-size-xs);
            color: var(--color-text-secondary);
            text-transform: uppercase;
            margin-left: var(--space-xs);
        }
        
        .no-history {
            text-align: center;
            padding: var(--space-lg);
            color: var(--color-text-muted);
            font-style: italic;
        }
        
        .alert-indicator {
            display: inline-block;
            width: 8px;
            height: 8px;
            background: var(--color-danger);
            border-radius: 50%;
            margin-left: var(--space-xs);
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
    `;
    document.head.appendChild(style);
});
