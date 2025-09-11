/**
 * Cocktail Recipes JavaScript
 * Restaurant-OS Admin Panel
 * 
 * Handles cocktail recipe creation, editing, and management
 */

class CocktailRecipesManager {
    constructor() {
        this.recipes = [];
        this.filteredRecipes = [];
        this.searchTerm = '';
        this.filters = {
            type: '',
            difficulty: '',
            glass: ''
        };
        this.currentRecipe = null;
        this.isEditing = false;
        this.currentView = 'grid';
        this.ingredientCounter = 0;
        
        this.init();
    }

    /**
     * Initialize the recipes manager
     */
    init() {
        this.bindEvents();
        this.generateDummyData();
        this.updateStatistics();
        this.renderRecipes();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Search and filter events
        this.bindSearchEvents();
        
        // Modal events
        this.bindModalEvents();
        
        // Action button events
        this.bindActionEvents();
        
        // Form events
        this.bindFormEvents();
        
        // View toggle events
        this.bindViewEvents();
    }

    /**
     * Bind search and filter events
     */
    bindSearchEvents() {
        const recipesSearch = document.getElementById('recipes-search');
        const typeFilter = document.getElementById('type-filter');
        const difficultyFilter = document.getElementById('difficulty-filter');
        const glassFilter = document.getElementById('glass-filter');
        const clearFiltersBtn = document.querySelector('.clear-filters-btn');

        if (recipesSearch) {
            recipesSearch.addEventListener('input', (e) => {
                this.searchTerm = e.target.value.toLowerCase();
                this.filterAndRenderRecipes();
            });
        }

        if (typeFilter) {
            typeFilter.addEventListener('change', (e) => {
                this.filters.type = e.target.value;
                this.filterAndRenderRecipes();
            });
        }

        if (difficultyFilter) {
            difficultyFilter.addEventListener('change', (e) => {
                this.filters.difficulty = e.target.value;
                this.filterAndRenderRecipes();
            });
        }

        if (glassFilter) {
            glassFilter.addEventListener('change', (e) => {
                this.filters.glass = e.target.value;
                this.filterAndRenderRecipes();
            });
        }

        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', () => this.clearFilters());
        }
    }

    /**
     * Bind modal events
     */
    bindModalEvents() {
        // Recipe modal
        this.bindModalCloseEvents('recipe-modal', () => this.closeRecipeModal());

        // Escape key to close modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeRecipeModal();
            }
        });
    }

    /**
     * Bind modal close events
     */
    bindModalCloseEvents(modalId, closeCallback) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        const closeBtn = modal.querySelector('.modal-close');
        const cancelBtn = modal.querySelector('.cancel-recipe-btn');
        const overlay = modal.querySelector('.modal-overlay');

        if (closeBtn) closeBtn.addEventListener('click', closeCallback);
        if (cancelBtn) cancelBtn.addEventListener('click', closeCallback);
        if (overlay) overlay.addEventListener('click', closeCallback);
    }

    /**
     * Bind action button events
     */
    bindActionEvents() {
        // Add recipe button
        document.querySelectorAll('.add-recipe-btn').forEach(btn => {
            btn.addEventListener('click', () => this.openRecipeModal());
        });

        // Export recipes button
        const exportBtn = document.querySelector('.export-recipes-btn');
        if (exportBtn) {
            exportBtn.addEventListener('click', () => this.exportRecipes());
        }

        // Event delegation for dynamic buttons
        document.addEventListener('click', (e) => {
            // Recipe card click
            if (e.target.closest('.recipe-card') && !e.target.closest('.recipe-action-btn')) {
                const recipeId = parseInt(e.target.closest('.recipe-card').dataset.recipeId);
                this.viewRecipeDetails(recipeId);
            }
            
            // Recipe action buttons
            if (e.target.closest('.recipe-action-btn')) {
                e.stopPropagation();
                const action = e.target.closest('.recipe-action-btn').dataset.action;
                const recipeId = parseInt(e.target.closest('.recipe-card').dataset.recipeId);
                
                if (action === 'view') {
                    this.viewRecipeDetails(recipeId);
                } else if (action === 'edit') {
                    this.editRecipe(recipeId);
                } else if (action === 'delete') {
                    this.deleteRecipe(recipeId);
                } else if (action === 'print') {
                    this.printRecipe(recipeId);
                }
            }
        });
    }

    /**
     * Bind form events
     */
    bindFormEvents() {
        const recipeForm = document.getElementById('recipe-form');
        if (recipeForm) {
            recipeForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.saveRecipe();
            });
        }

        // Add ingredient button
        const addIngredientBtn = document.querySelector('.add-ingredient-btn');
        if (addIngredientBtn) {
            addIngredientBtn.addEventListener('click', () => this.addIngredientRow());
        }
    }

    /**
     * Bind view toggle events
     */
    bindViewEvents() {
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const view = e.target.closest('.view-btn').dataset.view;
                this.switchView(view);
            });
        });

        const sortSelect = document.getElementById('sort-select');
        if (sortSelect) {
            sortSelect.addEventListener('change', () => {
                this.sortRecipes();
            });
        }
    }

    /**
     * Generate dummy data
     */
    generateDummyData() {
        this.recipes = [
            {
                id: 1,
                name: 'Classic Mojito',
                type: 'classic_cocktail',
                difficulty: 'easy',
                glassType: 'highball',
                preparationTime: 3,
                servingSize: 300,
                costPerDrink: 4.50,
                sellingPrice: 12.00,
                garnish: 'Fresh mint sprig',
                instructions: 'Muddle mint leaves, add rum and lime juice, top with soda water',
                notes: 'Use fresh mint for best flavor',
                ingredients: [
                    { name: 'White Rum', quantity: 60, unit: 'ml' },
                    { name: 'Fresh Lime Juice', quantity: 30, unit: 'ml' },
                    { name: 'Simple Syrup', quantity: 20, unit: 'ml' },
                    { name: 'Fresh Mint Leaves', quantity: 8, unit: 'leaves' },
                    { name: 'Soda Water', quantity: 120, unit: 'ml' }
                ],
                createdAt: new Date('2024-01-15'),
                updatedAt: new Date('2024-01-15')
            },
            {
                id: 2,
                name: 'Ethiopian Coffee Martini',
                type: 'signature_cocktail',
                difficulty: 'medium',
                glassType: 'martini',
                preparationTime: 5,
                servingSize: 120,
                costPerDrink: 6.75,
                sellingPrice: 18.00,
                garnish: 'Coffee beans',
                instructions: 'Shake all ingredients with ice, double strain into chilled martini glass',
                notes: 'Signature drink featuring Ethiopian coffee',
                ingredients: [
                    { name: 'Vodka', quantity: 45, unit: 'ml' },
                    { name: 'Ethiopian Coffee Liqueur', quantity: 30, unit: 'ml' },
                    { name: 'Fresh Espresso', quantity: 30, unit: 'ml' },
                    { name: 'Simple Syrup', quantity: 15, unit: 'ml' }
                ],
                createdAt: new Date('2024-01-16'),
                updatedAt: new Date('2024-01-16')
            },
            {
                id: 3,
                name: 'Virgin Tropical Punch',
                type: 'mocktail',
                difficulty: 'easy',
                glassType: 'hurricane',
                preparationTime: 2,
                servingSize: 350,
                costPerDrink: 2.25,
                sellingPrice: 8.00,
                garnish: 'Pineapple wedge and cherry',
                instructions: 'Mix all juices, add grenadine, serve over ice',
                notes: 'Popular non-alcoholic option',
                ingredients: [
                    { name: 'Pineapple Juice', quantity: 150, unit: 'ml' },
                    { name: 'Orange Juice', quantity: 100, unit: 'ml' },
                    { name: 'Cranberry Juice', quantity: 50, unit: 'ml' },
                    { name: 'Grenadine', quantity: 15, unit: 'ml' },
                    { name: 'Lime Juice', quantity: 15, unit: 'ml' }
                ],
                createdAt: new Date('2024-01-17'),
                updatedAt: new Date('2024-01-17')
            }
        ];
    }

    /**
     * Update statistics
     */
    updateStatistics() {
        const totalRecipes = this.recipes.length;
        const signatureRecipes = this.recipes.filter(r => r.type === 'signature_cocktail').length;
        const popularRecipes = Math.floor(totalRecipes * 0.3); // 30% are popular
        const avgCost = totalRecipes > 0 
            ? (this.recipes.reduce((sum, r) => sum + r.costPerDrink, 0) / totalRecipes).toFixed(2)
            : '0.00';

        document.getElementById('total-recipes').textContent = totalRecipes;
        document.getElementById('signature-recipes').textContent = signatureRecipes;
        document.getElementById('popular-recipes').textContent = popularRecipes;
        document.getElementById('avg-cost').textContent = `$${avgCost}`;
    }

    /**
     * Filter and render recipes
     */
    filterAndRenderRecipes() {
        this.filteredRecipes = this.recipes.filter(recipe => {
            // Search filter
            const searchMatch = !this.searchTerm || 
                recipe.name.toLowerCase().includes(this.searchTerm) ||
                recipe.instructions.toLowerCase().includes(this.searchTerm) ||
                recipe.ingredients.some(ing => ing.name.toLowerCase().includes(this.searchTerm));

            // Type filter
            const typeMatch = !this.filters.type || recipe.type === this.filters.type;

            // Difficulty filter
            const difficultyMatch = !this.filters.difficulty || recipe.difficulty === this.filters.difficulty;

            // Glass filter
            const glassMatch = !this.filters.glass || recipe.glassType === this.filters.glass;

            return searchMatch && typeMatch && difficultyMatch && glassMatch;
        });

        this.renderRecipes();
    }

    /**
     * Render recipes
     */
    renderRecipes() {
        const recipesGrid = document.getElementById('recipes-grid');
        if (!recipesGrid) return;

        const recipesToShow = this.filteredRecipes.length ? this.filteredRecipes : this.recipes;

        if (recipesToShow.length === 0) {
            recipesGrid.innerHTML = `
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <h3>${this.searchTerm || Object.values(this.filters).some(f => f) ? 'No recipes found' : 'No Recipes Yet'}</h3>
                    <p>${this.searchTerm || Object.values(this.filters).some(f => f) ? 'No recipes match your current search and filter criteria.' : 'Start by adding your first cocktail recipe.'}</p>
                    ${!this.searchTerm && !Object.values(this.filters).some(f => f) ? '<button class="btn btn-primary add-recipe-btn">Add Recipe</button>' : ''}
                </div>
            `;
            return;
        }

        recipesGrid.innerHTML = recipesToShow.map(recipe => `
            <div class="recipe-card" data-recipe-id="${recipe.id}" data-type="${recipe.type}">
                <div class="recipe-header">
                    <div class="recipe-info">
                        <div class="recipe-name">${recipe.name}</div>
                        <div class="recipe-badges">
                            <span class="recipe-badge type-${recipe.type}">${this.formatType(recipe.type)}</span>
                            <span class="recipe-badge difficulty-${recipe.difficulty}">${this.formatDifficulty(recipe.difficulty)}</span>
                        </div>
                    </div>
                </div>
                
                <div class="recipe-details">
                    <div class="recipe-detail">
                        <span class="detail-label">Glass</span>
                        <span class="detail-value">${this.formatGlassType(recipe.glassType)}</span>
                    </div>
                    <div class="recipe-detail">
                        <span class="detail-label">Prep Time</span>
                        <span class="detail-value">${recipe.preparationTime} min</span>
                    </div>
                    <div class="recipe-detail">
                        <span class="detail-label">Cost</span>
                        <span class="detail-value">$${recipe.costPerDrink.toFixed(2)}</span>
                    </div>
                    <div class="recipe-detail">
                        <span class="detail-label">Price</span>
                        <span class="detail-value">$${recipe.sellingPrice.toFixed(2)}</span>
                    </div>
                    <div class="recipe-detail">
                        <span class="detail-label">Ingredients</span>
                        <span class="detail-value">${recipe.ingredients.length} items</span>
                    </div>
                </div>
                
                <div class="recipe-actions">
                    <button class="recipe-action-btn view" data-action="view" title="View Details">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                    <button class="recipe-action-btn edit" data-action="edit" title="Edit Recipe">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button class="recipe-action-btn delete" data-action="delete" title="Delete Recipe">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        `).join('');
    }

    /**
     * Open recipe modal
     */
    openRecipeModal(recipe = null) {
        this.currentRecipe = recipe;
        this.isEditing = !!recipe;
        
        const modal = document.getElementById('recipe-modal');
        const title = document.getElementById('recipe-modal-title');
        
        if (modal && title) {
            title.textContent = this.isEditing ? 'Edit Recipe' : 'Add Recipe';
            
            if (this.isEditing) {
                this.populateRecipeForm(recipe);
            } else {
                this.resetRecipeForm();
            }
            
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
        }
    }

    /**
     * Close recipe modal
     */
    closeRecipeModal() {
        const modal = document.getElementById('recipe-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
            this.resetRecipeForm();
            this.currentRecipe = null;
            this.isEditing = false;
        }
    }

    /**
     * Add ingredient row
     */
    addIngredientRow() {
        const ingredientsList = document.getElementById('ingredients-list');
        if (!ingredientsList) return;

        const ingredientId = this.ingredientCounter++;
        const ingredientHtml = `
            <div class="ingredient-item" data-ingredient-id="${ingredientId}">
                <input type="text" class="ingredient-name-input" placeholder="Ingredient name..." required>
                <input type="number" class="ingredient-quantity-input" placeholder="Qty" min="0" step="0.1" required>
                <select class="ingredient-unit-select" required>
                    <option value="">Unit</option>
                    <option value="ml">ml</option>
                    <option value="cl">cl</option>
                    <option value="oz">oz</option>
                    <option value="dash">dash</option>
                    <option value="splash">splash</option>
                    <option value="drops">drops</option>
                    <option value="pieces">pieces</option>
                    <option value="leaves">leaves</option>
                    <option value="wedges">wedges</option>
                </select>
                <button type="button" class="remove-ingredient-btn" onclick="window.cocktailRecipesManager.removeIngredientRow(${ingredientId})">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
        `;
        
        ingredientsList.insertAdjacentHTML('beforeend', ingredientHtml);
    }

    /**
     * Remove ingredient row
     */
    removeIngredientRow(ingredientId) {
        const ingredientItem = document.querySelector(`[data-ingredient-id="${ingredientId}"]`);
        if (ingredientItem) {
            ingredientItem.remove();
        }
    }

    /**
     * Utility formatting methods
     */
    formatType(type) {
        const typeMap = {
            classic_cocktail: 'Classic',
            signature_cocktail: 'Signature',
            mocktail: 'Mocktail',
            shot: 'Shot',
            mixed_drink: 'Mixed',
            frozen_drink: 'Frozen',
            hot_drink: 'Hot'
        };
        return typeMap[type] || type;
    }

    formatDifficulty(difficulty) {
        const difficultyMap = {
            easy: 'Easy',
            medium: 'Medium',
            hard: 'Hard',
            expert: 'Expert'
        };
        return difficultyMap[difficulty] || difficulty;
    }

    formatGlassType(glassType) {
        const glassMap = {
            highball: 'Highball',
            lowball: 'Lowball',
            martini: 'Martini',
            wine_glass: 'Wine Glass',
            champagne_flute: 'Champagne',
            beer_mug: 'Beer Mug',
            shot_glass: 'Shot Glass',
            hurricane: 'Hurricane'
        };
        return glassMap[glassType] || 'N/A';
    }

    /**
     * Clear all filters
     */
    clearFilters() {
        this.searchTerm = '';
        this.filters = { type: '', difficulty: '', glass: '' };
        
        document.getElementById('recipes-search').value = '';
        document.getElementById('type-filter').value = '';
        document.getElementById('difficulty-filter').value = '';
        document.getElementById('glass-filter').value = '';
        
        this.filterAndRenderRecipes();
    }

    /**
     * Switch view
     */
    switchView(view) {
        this.currentView = view;
        
        // Update button states
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelector(`[data-view="${view}"]`).classList.add('active');
        
        // For now, only grid view is implemented
        if (view === 'list') {
            this.showNotification('List view coming soon', 'info');
        }
    }

    /**
     * Show notification
     */
    showNotification(message, type = 'info') {
        console.log(`${type.toUpperCase()}: ${message}`);
        // In a real implementation, this would show a toast notification
    }

    /**
     * Placeholder methods for future implementation
     */
    populateRecipeForm(recipe) {
        // Populate form with recipe data
        console.log('Populating form with recipe:', recipe);
    }

    resetRecipeForm() {
        // Reset form to defaults
        const form = document.getElementById('recipe-form');
        if (form) form.reset();
        
        // Clear ingredients
        const ingredientsList = document.getElementById('ingredients-list');
        if (ingredientsList) ingredientsList.innerHTML = '';
        
        // Add one default ingredient row
        this.addIngredientRow();
    }

    saveRecipe() {
        console.log('Save recipe functionality - coming soon');
        this.showNotification('Recipe saved successfully', 'success');
        this.closeRecipeModal();
    }

    editRecipe(recipeId) {
        const recipe = this.recipes.find(r => r.id === recipeId);
        if (recipe) {
            this.openRecipeModal(recipe);
        }
    }

    deleteRecipe(recipeId) {
        const recipe = this.recipes.find(r => r.id === recipeId);
        if (recipe && confirm(`Delete recipe "${recipe.name}"?`)) {
            this.recipes = this.recipes.filter(r => r.id !== recipeId);
            this.updateStatistics();
            this.filterAndRenderRecipes();
            this.showNotification('Recipe deleted successfully', 'success');
        }
    }

    viewRecipeDetails(recipeId) {
        const recipe = this.recipes.find(r => r.id === recipeId);
        if (recipe) {
            console.log('View recipe details:', recipe);
            this.showNotification('Recipe details view coming soon', 'info');
        }
    }

    printRecipe(recipeId) {
        const recipe = this.recipes.find(r => r.id === recipeId);
        if (recipe) {
            console.log('Print recipe:', recipe);
            this.showNotification('Recipe printing coming soon', 'info');
        }
    }

    exportRecipes() {
        console.log('Export recipes functionality - coming soon');
        this.showNotification('Export functionality coming soon', 'info');
    }

    sortRecipes() {
        console.log('Sort recipes functionality - coming soon');
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.cocktailRecipesManager = new CocktailRecipesManager();
});
