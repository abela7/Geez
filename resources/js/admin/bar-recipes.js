/**
 * Bar Recipes - Interactive Features
 * Handles grid/list view, filtering, drawer, bulk actions, and AJAX operations
 */

class BarRecipesManager {
    constructor() {
        this.selectedRecipes = new Set();
        this.drawer = null;
        this.bulkActionsBar = null;
        this.currentView = 'grid';
        this.init();
    }

    init() {
        this.drawer = document.getElementById('recipeDetailsDrawer');
        this.bulkActionsBar = document.getElementById('bulkActionsBar');
        this.bindEvents();
        this.initializeFilters();
        this.initializeView();
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

        // Handle recipe card clicks for mobile
        this.bindRecipeCardEvents();
    }

    bindRecipeCardEvents() {
        const recipeCards = document.querySelectorAll('.recipe-card .recipe-content');
        recipeCards.forEach(card => {
            // Add touch/click handlers for mobile
            card.addEventListener('click', (e) => {
                const recipeCard = card.closest('.recipe-card');
                const recipeId = recipeCard.dataset.recipeId;
                this.showRecipeDetails(recipeId);
            });
        });

        const tableRows = document.querySelectorAll('.table-row');
        tableRows.forEach(row => {
            row.addEventListener('click', (e) => {
                if (window.innerWidth <= 767 && !e.target.closest('.recipe-checkbox') && !e.target.closest('.action-buttons')) {
                    const recipeId = row.dataset.recipeId;
                    this.showRecipeDetails(recipeId);
                }
            });
        });
    }

    initializeFilters() {
        // Restore filter state from URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        
        // Set form values from URL
        ['search', 'recipe_type', 'difficulty', 'glass_type'].forEach(param => {
            const element = document.getElementById(param);
            if (element && urlParams.has(param)) {
                element.value = urlParams.get(param);
            }
        });
    }

    initializeView() {
        // Check localStorage for preferred view
        const savedView = localStorage.getItem('recipes_view') || 'grid';
        this.switchView(savedView, false);
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
                case '1':
                    e.preventDefault();
                    this.switchView('grid');
                    break;
                case '2':
                    e.preventDefault();
                    this.switchView('list');
                    break;
            }
        }
    }

    // View Management
    switchView(view, savePreference = true) {
        this.currentView = view;
        
        const gridView = document.getElementById('recipesGrid');
        const listView = document.getElementById('recipesList');
        const gridBtn = document.querySelector('.view-btn--grid');
        const listBtn = document.querySelector('.view-btn--list');
        
        if (view === 'grid') {
            gridView.style.display = 'grid';
            listView.style.display = 'none';
            gridBtn.classList.add('active');
            listBtn.classList.remove('active');
        } else {
            gridView.style.display = 'none';
            listView.style.display = 'block';
            gridBtn.classList.remove('active');
            listBtn.classList.add('active');
        }
        
        if (savePreference) {
            localStorage.setItem('recipes_view', view);
        }
    }

    // Selection Management
    toggleSelectAll() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const recipeCheckboxes = document.querySelectorAll('.recipe-checkbox');
        
        if (selectAllCheckbox && selectAllCheckbox.checked) {
            recipeCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
                this.selectedRecipes.add(checkbox.value);
            });
        } else {
            recipeCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            this.selectedRecipes.clear();
        }
        
        this.updateBulkActions();
    }

    updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.recipe-checkbox:checked');
        const selectedCount = checkedBoxes.length;
        
        // Update selected recipes set
        this.selectedRecipes.clear();
        checkedBoxes.forEach(checkbox => {
            this.selectedRecipes.add(checkbox.value);
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
        const totalCheckboxes = document.querySelectorAll('.recipe-checkbox').length;
        
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
        const recipeCheckboxes = document.querySelectorAll('.recipe-checkbox');
        recipeCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        this.selectedRecipes.clear();
        this.updateBulkActions();
    }

    // Recipe Details Drawer
    async showRecipeDetails(recipeId) {
        if (!this.drawer) return;
        
        try {
            this.showDrawerLoading();
            this.openDrawer();
            
            const response = await fetch(`/admin/bar/recipes/${recipeId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (!response.ok) {
                throw new Error('Failed to fetch recipe details');
            }
            
            const data = await response.json();
            this.renderRecipeDetails(data);
            
        } catch (error) {
            console.error('Error fetching recipe details:', error);
            this.showDrawerError('Failed to load recipe details. Please try again.');
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
                    <p>Loading recipe details...</p>
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
                    <button type="button" class="btn btn-primary btn-sm" onclick="barRecipesManager.closeDrawer()">
                        Close
                    </button>
                </div>
            `;
        }
    }

    renderRecipeDetails(data) {
        const drawerContent = document.getElementById('drawerContent');
        if (!drawerContent) return;
        
        const recipe = data.recipe;
        const ingredients = data.ingredients || [];
        const instructions = data.instructions || [];
        
        drawerContent.innerHTML = `
            <div class="recipe-details">
                <!-- Basic Information -->
                <div class="detail-section">
                    <h4 class="detail-section-title">Recipe Information</h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>Recipe Name</label>
                            <span>${recipe.name}</span>
                        </div>
                        <div class="detail-item">
                            <label>Type</label>
                            <span class="type-badge type-${recipe.recipe_type}">${recipe.recipe_type_display}</span>
                        </div>
                        <div class="detail-item">
                            <label>Difficulty</label>
                            <span class="difficulty-badge difficulty-${recipe.difficulty}">${recipe.difficulty_display}</span>
                        </div>
                        <div class="detail-item">
                            <label>Glass Type</label>
                            <span>${recipe.glass_type_display || 'N/A'}</span>
                        </div>
                        <div class="detail-item">
                            <label>Preparation Time</label>
                            <span>${recipe.preparation_time || 5} minutes</span>
                        </div>
                        <div class="detail-item">
                            <label>Serving Size</label>
                            <span>${recipe.serving_size || 1} serving</span>
                        </div>
                        ${recipe.garnish ? `
                        <div class="detail-item">
                            <label>Garnish</label>
                            <span>${recipe.garnish}</span>
                        </div>
                        ` : ''}
                        ${recipe.description ? `
                        <div class="detail-item detail-item-full">
                            <label>Description</label>
                            <span>${recipe.description}</span>
                        </div>
                        ` : ''}
                    </div>
                </div>

                <!-- Cost Information -->
                <div class="detail-section">
                    <h4 class="detail-section-title">Cost & Pricing</h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>Cost per Drink</label>
                            <span>$${parseFloat(recipe.cost_per_drink || 0).toFixed(2)}</span>
                        </div>
                        <div class="detail-item">
                            <label>Selling Price</label>
                            <span>$${parseFloat(recipe.selling_price || 0).toFixed(2)}</span>
                        </div>
                        <div class="detail-item">
                            <label>Profit Margin</label>
                            <span>${parseFloat(data.profit_margin || 0).toFixed(1)}%</span>
                        </div>
                        <div class="detail-item">
                            <label>Markup</label>
                            <span>${parseFloat(data.markup || 0).toFixed(1)}%</span>
                        </div>
                    </div>
                </div>

                <!-- Ingredients -->
                <div class="detail-section">
                    <h4 class="detail-section-title">Ingredients (${ingredients.length})</h4>
                    ${ingredients.length > 0 ? `
                        <ul class="ingredients-list">
                            ${ingredients.map(ingredient => `
                                <li class="ingredient-item">
                                    <span class="ingredient-name">${ingredient.name}</span>
                                    <span class="ingredient-quantity">${ingredient.quantity} ${ingredient.unit}${ingredient.optional ? ' (optional)' : ''}</span>
                                </li>
                            `).join('')}
                        </ul>
                    ` : `
                        <p class="text-muted">No ingredients specified.</p>
                    `}
                </div>

                <!-- Instructions -->
                <div class="detail-section">
                    <h4 class="detail-section-title">Instructions</h4>
                    ${instructions.length > 0 ? `
                        <ol class="instructions-list">
                            ${instructions.map(instruction => `
                                <li class="instruction-step">
                                    <span class="instruction-text">${instruction.instruction}</span>
                                </li>
                            `).join('')}
                        </ol>
                    ` : recipe.instructions ? `
                        <div class="instructions-text">
                            <p>${recipe.instructions}</p>
                        </div>
                    ` : `
                        <p class="text-muted">No instructions provided.</p>
                    `}
                </div>

                ${recipe.notes ? `
                <!-- Notes -->
                <div class="detail-section">
                    <h4 class="detail-section-title">Notes</h4>
                    <p>${recipe.notes}</p>
                </div>
                ` : ''}

                <!-- Action Buttons -->
                <div class="drawer-actions">
                    <button type="button" class="btn btn-primary" onclick="barRecipesManager.showEditRecipeModal(${recipe.id})">
                        Edit Recipe
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="barRecipesManager.duplicateRecipe(${recipe.id})">
                        Duplicate Recipe
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="barRecipesManager.printRecipe(${recipe.id})">
                        Print Recipe
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
    showAddRecipeModal() {
        // Placeholder for add recipe modal
        this.showNotification('Add recipe functionality will be implemented', 'info');
    }

    showEditRecipeModal(recipeId) {
        // Placeholder for edit recipe modal
        this.showNotification(`Edit recipe ${recipeId} functionality will be implemented`, 'info');
    }

    duplicateRecipe(recipeId) {
        // Placeholder for duplicate recipe functionality
        this.showNotification(`Duplicate recipe ${recipeId} functionality will be implemented`, 'info');
    }

    printRecipe(recipeId) {
        // Open print view in new window
        const printUrl = `/admin/bar/recipes/${recipeId}/print`;
        window.open(printUrl, '_blank', 'width=800,height=600');
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
    exportRecipes() {
        const url = new URL('/admin/bar/recipes/export', window.location.origin);
        
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
    bulkExportRecipes() {
        if (this.selectedRecipes.size === 0) return;
        
        const url = new URL('/admin/bar/recipes/bulk-export', window.location.origin);
        url.searchParams.set('recipes', Array.from(this.selectedRecipes).join(','));
        
        window.open(url.toString(), '_blank');
    }

    bulkPrintRecipes() {
        if (this.selectedRecipes.size === 0) return;
        
        const url = new URL('/admin/bar/recipes/bulk-print', window.location.origin);
        url.searchParams.set('recipes', Array.from(this.selectedRecipes).join(','));
        
        window.open(url.toString(), '_blank', 'width=800,height=600');
    }

    async performBulkAction(action, data) {
        try {
            const response = await fetch(`/admin/bar/recipes/${action}`, {
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
let barRecipesManager;

document.addEventListener('DOMContentLoaded', function() {
    barRecipesManager = new BarRecipesManager();
});

// Global functions for HTML onclick handlers
function showRecipeDetails(recipeId) {
    barRecipesManager?.showRecipeDetails(recipeId);
}

function closeDrawer() {
    barRecipesManager?.closeDrawer();
}

function switchView(view) {
    barRecipesManager?.switchView(view);
}

function toggleSelectAll() {
    barRecipesManager?.toggleSelectAll();
}

function updateBulkActions() {
    barRecipesManager?.updateBulkActions();
}

function clearSelection() {
    barRecipesManager?.clearSelection();
}

function showAddRecipeModal() {
    barRecipesManager?.showAddRecipeModal();
}

function showEditRecipeModal(recipeId) {
    barRecipesManager?.showEditRecipeModal(recipeId);
}

function printRecipe(recipeId) {
    barRecipesManager?.printRecipe(recipeId);
}

function updateSort() {
    barRecipesManager?.updateSort();
}

function toggleSortDirection() {
    barRecipesManager?.toggleSortDirection();
}

function clearFilters() {
    barRecipesManager?.clearFilters();
}

function exportRecipes() {
    barRecipesManager?.exportRecipes();
}

function refreshData() {
    barRecipesManager?.refreshData();
}

function bulkExportRecipes() {
    barRecipesManager?.bulkExportRecipes();
}

function bulkPrintRecipes() {
    barRecipesManager?.bulkPrintRecipes();
}
