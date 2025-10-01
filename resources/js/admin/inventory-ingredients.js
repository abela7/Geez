/**
 * Ingredients - Interactive Features
 * Handles filtering, drawer, bulk actions, and AJAX operations
 */

class IngredientsManager {
    constructor() {
        this.selectedIngredients = new Set();
        this.drawer = null;
        this.bulkActionsBar = null;
        this.init();
    }

    init() {
        this.drawer = document.getElementById('ingredient-drawer');
        this.bulkActionsBar = document.getElementById('bulk-actions');
        this.setupEventListeners();
        this.initializeAlpineData();
    }

    setupEventListeners() {
        // Checkbox event listeners
        document.addEventListener('change', (e) => {
            if (e.target.classList.contains('ingredient-checkbox')) {
                this.handleIngredientSelection(e.target);
            }
        });

        // Escape key to close drawer
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.drawer && this.drawer.classList.contains('open')) {
                this.closeIngredientDrawer();
            }
        });

        // Form submissions
        document.addEventListener('submit', (e) => {
            if (e.target.classList.contains('filters-form')) {
                this.handleFilterSubmission(e);
            }
        });
    }

    initializeAlpineData() {
        // Initialize Alpine.js data for filters
        if (typeof Alpine !== 'undefined') {
            Alpine.data('ingredientsFilters', () => ({
                filtersOpen: false,
                toggleFilters() {
                    this.filtersOpen = !this.filtersOpen;
                }
            }));
        }
    }

    handleIngredientSelection(checkbox) {
        const ingredientId = parseInt(checkbox.value);
        
        if (checkbox.checked) {
            this.selectedIngredients.add(ingredientId);
        } else {
            this.selectedIngredients.delete(ingredientId);
        }

        this.updateBulkActionsVisibility();
        this.updateSelectAllCheckbox();
    }

    updateBulkActionsVisibility() {
        if (this.bulkActionsBar) {
            if (this.selectedIngredients.size > 0) {
                this.bulkActionsBar.style.display = 'flex';
            } else {
                this.bulkActionsBar.style.display = 'none';
            }
        }
    }

    updateSelectAllCheckbox() {
        const selectAllCheckbox = document.getElementById('select-all');
        const ingredientCheckboxes = document.querySelectorAll('.ingredient-checkbox');
        
        if (selectAllCheckbox && ingredientCheckboxes.length > 0) {
            const checkedCount = this.selectedIngredients.size;
            const totalCount = ingredientCheckboxes.length;
            
            selectAllCheckbox.checked = checkedCount === totalCount;
            selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < totalCount;
        }
    }

    toggleSelectAll(selectAllCheckbox) {
        const ingredientCheckboxes = document.querySelectorAll('.ingredient-checkbox');
        const shouldCheck = selectAllCheckbox.checked;

        ingredientCheckboxes.forEach(checkbox => {
            checkbox.checked = shouldCheck;
            const ingredientId = parseInt(checkbox.value);
            
            if (shouldCheck) {
                this.selectedIngredients.add(ingredientId);
            } else {
                this.selectedIngredients.delete(ingredientId);
            }
        });

        this.updateBulkActionsVisibility();
    }

    async viewIngredient(ingredientId) {
        try {
            this.showDrawerLoading();
            
            const response = await fetch(`/admin/inventory/ingredients/${ingredientId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            if (data.success) {
                this.populateDrawerContent(data.ingredient);
                this.openIngredientDrawer();
            } else {
                throw new Error(data.message || 'Failed to load ingredient details');
            }
        } catch (error) {
            console.error('Error loading ingredient details:', error);
            this.showDrawerError('Failed to load ingredient details. Please try again.');
        }
    }

    populateDrawerContent(ingredient) {
        const detailsContainer = document.getElementById('ingredient-details');
        if (!detailsContainer) return;

        const allergensList = ingredient.allergen_info && ingredient.allergen_info.length > 0 
            ? ingredient.allergen_info.map(allergen => `<span class="allergen-tag">${allergen}</span>`).join('')
            : '<span class="allergen-free">None</span>';

        const nutritionalInfo = ingredient.nutritional_info ? Object.entries(ingredient.nutritional_info)
            .map(([key, value]) => `<div class="nutrition-item"><span class="nutrition-label">${key}:</span> <span class="nutrition-value">${value}g</span></div>`)
            .join('') : '<p class="text-muted">No nutritional information available</p>';

        detailsContainer.innerHTML = `
            <div class="ingredient-detail-sections">
                <div class="detail-section">
                    <h3 class="detail-section-title">Basic Information</h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label class="detail-label">Name:</label>
                            <span class="detail-value">${ingredient.name}</span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Code:</label>
                            <span class="detail-value">${ingredient.code}</span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Category:</label>
                            <span class="detail-value category-badge category-${ingredient.category}">${ingredient.category}</span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Unit:</label>
                            <span class="detail-value">${ingredient.unit}</span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Status:</label>
                            <span class="detail-value status-badge status-${ingredient.status}">${ingredient.status}</span>
                        </div>
                    </div>
                    ${ingredient.description ? `<div class="detail-item full-width"><label class="detail-label">Description:</label><p class="detail-value">${ingredient.description}</p></div>` : ''}
                </div>

                <div class="detail-section">
                    <h3 class="detail-section-title">Pricing & Ordering</h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label class="detail-label">Cost per Unit:</label>
                            <span class="detail-value">${ingredient.formatted_cost}</span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Minimum Order:</label>
                            <span class="detail-value">${ingredient.minimum_order_qty} ${ingredient.unit}</span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Min Order Value:</label>
                            <span class="detail-value">${ingredient.formatted_minimum_order_value}</span>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Lead Time:</label>
                            <span class="detail-value">${ingredient.lead_time_days} days</span>
                        </div>
                    </div>
                </div>

                ${ingredient.supplier ? `
                <div class="detail-section">
                    <h3 class="detail-section-title">Supplier Information</h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label class="detail-label">Supplier:</label>
                            <span class="detail-value">${ingredient.supplier.name}</span>
                        </div>
                        ${ingredient.supplier.contact_person ? `
                        <div class="detail-item">
                            <label class="detail-label">Contact:</label>
                            <span class="detail-value">${ingredient.supplier.contact_person}</span>
                        </div>` : ''}
                        ${ingredient.supplier.email ? `
                        <div class="detail-item">
                            <label class="detail-label">Email:</label>
                            <span class="detail-value">${ingredient.supplier.email}</span>
                        </div>` : ''}
                        ${ingredient.supplier.phone ? `
                        <div class="detail-item">
                            <label class="detail-label">Phone:</label>
                            <span class="detail-value">${ingredient.supplier.phone}</span>
                        </div>` : ''}
                    </div>
                </div>` : ''}

                <div class="detail-section">
                    <h3 class="detail-section-title">Storage & Shelf Life</h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label class="detail-label">Storage:</label>
                            <span class="detail-value">${ingredient.storage_requirements}</span>
                        </div>
                        ${ingredient.shelf_life_days ? `
                        <div class="detail-item">
                            <label class="detail-label">Shelf Life:</label>
                            <span class="detail-value">${ingredient.shelf_life_days} days</span>
                        </div>` : ''}
                        ${ingredient.origin_country ? `
                        <div class="detail-item">
                            <label class="detail-label">Origin:</label>
                            <span class="detail-value">${ingredient.origin_country}</span>
                        </div>` : ''}
                    </div>
                </div>

                <div class="detail-section">
                    <h3 class="detail-section-title">Allergen Information</h3>
                    <div class="allergen-info">
                        ${allergensList}
                    </div>
                </div>

                <div class="detail-section">
                    <h3 class="detail-section-title">Nutritional Information (per 100g)</h3>
                    <div class="nutritional-info">
                        ${nutritionalInfo}
                    </div>
                </div>

                ${ingredient.notes ? `
                <div class="detail-section">
                    <h3 class="detail-section-title">Notes</h3>
                    <p class="detail-notes">${ingredient.notes}</p>
                </div>` : ''}

                <div class="detail-section">
                    <h3 class="detail-section-title">Timestamps</h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label class="detail-label">Created:</label>
                            <span class="detail-value">${new Date(ingredient.created_at).toLocaleString()}</span>
                        </div>
                        ${ingredient.last_updated ? `
                        <div class="detail-item">
                            <label class="detail-label">Last Updated:</label>
                            <span class="detail-value">${new Date(ingredient.last_updated).toLocaleString()}</span>
                        </div>` : ''}
                    </div>
                </div>
            </div>
        `;
    }

    showDrawerLoading() {
        const detailsContainer = document.getElementById('ingredient-details');
        if (detailsContainer) {
            detailsContainer.innerHTML = `
                <div class="loading-state">
                    <div class="loading-spinner"></div>
                    <p>Loading ingredient details...</p>
                </div>
            `;
        }
    }

    showDrawerError(message) {
        const detailsContainer = document.getElementById('ingredient-details');
        if (detailsContainer) {
            detailsContainer.innerHTML = `
                <div class="error-state">
                    <svg class="error-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p>${message}</p>
                    <button onclick="ingredientsManager.closeIngredientDrawer()" class="btn btn-secondary">Close</button>
                </div>
            `;
        }
    }

    openIngredientDrawer() {
        if (this.drawer) {
            this.drawer.classList.add('open');
            this.drawer.querySelector('.drawer-overlay').classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    closeIngredientDrawer() {
        if (this.drawer) {
            this.drawer.classList.remove('open');
            this.drawer.querySelector('.drawer-overlay').classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    async executeBulkAction() {
        const actionSelect = document.getElementById('bulk-action-select');
        const action = actionSelect?.value;

        if (!action || this.selectedIngredients.size === 0) {
            alert('Please select an action and at least one ingredient.');
            return;
        }

        const confirmMessage = this.getBulkActionConfirmMessage(action, this.selectedIngredients.size);
        if (!confirm(confirmMessage)) {
            return;
        }

        try {
            const response = await fetch('/admin/inventory/ingredients/bulk-action', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({
                    action: action,
                    ingredient_ids: Array.from(this.selectedIngredients)
                })
            });

            const data = await response.json();

            if (data.success) {
                this.showSuccessMessage(data.message);
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                throw new Error(data.message || 'Bulk action failed');
            }
        } catch (error) {
            console.error('Bulk action error:', error);
            this.showErrorMessage('Failed to execute bulk action. Please try again.');
        }
    }

    getBulkActionConfirmMessage(action, count) {
        const messages = {
            'activate': `Are you sure you want to activate ${count} ingredient(s)?`,
            'deactivate': `Are you sure you want to deactivate ${count} ingredient(s)?`,
            'discontinue': `Are you sure you want to discontinue ${count} ingredient(s)?`,
            'delete': `Are you sure you want to delete ${count} ingredient(s)? This action cannot be undone.`
        };
        return messages[action] || `Are you sure you want to perform this action on ${count} ingredient(s)?`;
    }

    sortIngredients(sortBy) {
        const url = new URL(window.location);
        url.searchParams.set('sort_by', sortBy);
        
        // Toggle sort order if same column
        const currentSort = url.searchParams.get('sort_by');
        const currentOrder = url.searchParams.get('sort_order') || 'asc';
        
        if (currentSort === sortBy) {
            url.searchParams.set('sort_order', currentOrder === 'asc' ? 'desc' : 'asc');
        } else {
            url.searchParams.set('sort_order', 'asc');
        }
        
        window.location.href = url.toString();
    }

    async exportIngredients() {
        try {
            const response = await fetch('/admin/inventory/ingredients/export', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });

            const data = await response.json();

            if (data.success) {
                this.showSuccessMessage(data.message);
                // In a real implementation, you would handle the actual file download
                // window.location.href = data.download_url;
            } else {
                throw new Error(data.message || 'Export failed');
            }
        } catch (error) {
            console.error('Export error:', error);
            this.showErrorMessage('Failed to export ingredients. Please try again.');
        }
    }

    addIngredient() {
        // In a real implementation, this would open a modal or redirect to create page
        alert('Add Ingredient functionality would be implemented here.');
    }

    editIngredient(ingredientId) {
        // In a real implementation, this would open an edit modal or redirect to edit page
        alert(`Edit Ingredient ${ingredientId} functionality would be implemented here.`);
    }

    async deleteIngredient(ingredientId) {
        if (!confirm('Are you sure you want to delete this ingredient? This action cannot be undone.')) {
            return;
        }

        try {
            const response = await fetch(`/admin/inventory/ingredients/${ingredientId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });

            const data = await response.json();

            if (data.success) {
                this.showSuccessMessage(data.message);
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                throw new Error(data.message || 'Delete failed');
            }
        } catch (error) {
            console.error('Delete error:', error);
            this.showErrorMessage('Failed to delete ingredient. Please try again.');
        }
    }

    showSuccessMessage(message) {
        this.showToast(message, 'success');
    }

    showErrorMessage(message) {
        this.showToast(message, 'error');
    }

    showToast(message, type = 'info') {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <div class="toast-content">
                <span class="toast-message">${message}</span>
                <button class="toast-close" onclick="this.parentElement.parentElement.remove()">×</button>
            </div>
        `;

        // Add to page
        document.body.appendChild(toast);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 5000);
    }

    handleFilterSubmission(event) {
        // Let the form submit naturally, but we could add loading states here
        const submitButton = event.target.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'Applying...';
        }
    }
}

// Global functions for onclick handlers
function toggleSelectAll(checkbox) {
    if (window.ingredientsManager) {
        window.ingredientsManager.toggleSelectAll(checkbox);
    }
}

function toggleBulkActions() {
    if (window.ingredientsManager) {
        window.ingredientsManager.updateBulkActionsVisibility();
    }
}

function viewIngredient(ingredientId) {
    if (window.ingredientsManager) {
        window.ingredientsManager.viewIngredient(ingredientId);
    }
}

function editIngredient(ingredientId) {
    if (window.ingredientsManager) {
        window.ingredientsManager.editIngredient(ingredientId);
    }
}

function deleteIngredient(ingredientId) {
    if (window.ingredientsManager) {
        window.ingredientsManager.deleteIngredient(ingredientId);
    }
}

function closeIngredientDrawer() {
    if (window.ingredientsManager) {
        window.ingredientsManager.closeIngredientDrawer();
    }
}

function executeBulkAction() {
    if (window.ingredientsManager) {
        window.ingredientsManager.executeBulkAction();
    }
}

function sortIngredients(sortBy) {
    if (window.ingredientsManager) {
        window.ingredientsManager.sortIngredients(sortBy);
    }
}

function exportIngredients() {
    if (window.ingredientsManager) {
        window.ingredientsManager.exportIngredients();
    }
}

function addIngredient() {
    if (window.ingredientsManager) {
        window.ingredientsManager.addIngredient();
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.ingredientsManager = new IngredientsManager();
});
