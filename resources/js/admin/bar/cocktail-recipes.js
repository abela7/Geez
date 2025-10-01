/**
 * Bar Cocktail Recipes Page JavaScript
 * Restaurant-OS Admin Panel
 * 
 * Handles recipe management, creation, editing, and organization
 */

class BarCocktailRecipesPage {
    constructor() {
        this.recipes = [];
        this.filteredRecipes = [];
        this.currentView = 'grid';
        this.currentRecipe = null;
        this.isLoading = false;
        
        this.init();
    }

    /**
     * Initialize the recipes page
     */
    init() {
        this.bindEvents();
        this.generateDummyData();
        this.loadRecipes();
        this.updateStatistics();
        this.setupFormTabs();
        this.setupLanguageTabs();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Search functionality
        const searchInput = document.getElementById('recipe-search');
        if (searchInput) {
            searchInput.addEventListener('input', this.debounce(this.handleSearch.bind(this), 300));
        }

        // Filter functionality
        const statusFilter = document.getElementById('status-filter');
        if (statusFilter) {
            statusFilter.addEventListener('change', this.handleFilter.bind(this));
        }

        const sortFilter = document.getElementById('sort-filter');
        if (sortFilter) {
            sortFilter.addEventListener('change', this.handleSort.bind(this));
        }

        // View toggle
        const viewButtons = document.querySelectorAll('.view-btn');
        viewButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const view = e.currentTarget.dataset.view;
                this.switchView(view);
            });
        });

        // Clear filters
        const clearFiltersBtn = document.querySelector('.clear-filters-btn');
        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', this.clearFilters.bind(this));
        }

        // Add recipe buttons
        const addRecipeButtons = document.querySelectorAll('.add-recipe-btn');
        addRecipeButtons.forEach(btn => {
            btn.addEventListener('click', this.showAddRecipeModal.bind(this));
        });

        // Modal close functionality
        const modalClose = document.querySelector('.modal-close');
        if (modalClose) {
            modalClose.addEventListener('click', this.closeRecipeModal.bind(this));
        }

        const modalOverlay = document.querySelector('.modal-overlay');
        if (modalOverlay) {
            modalOverlay.addEventListener('click', this.closeRecipeModal.bind(this));
        }

        const cancelBtn = document.querySelector('.cancel-btn');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', this.closeRecipeModal.bind(this));
        }

        // Export recipes button
        const exportRecipesBtn = document.querySelector('.export-recipes-btn');
        if (exportRecipesBtn) {
            exportRecipesBtn.addEventListener('click', this.exportRecipes.bind(this));
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', this.handleKeyboardShortcuts.bind(this));
    }

    /**
     * Generate dummy data for demonstration
     */
    generateDummyData() {
        this.recipes = [
            {
                id: 1,
                name: 'Classic Old Fashioned',
                type: 'classic',
                difficulty: 'medium',
                glassType: 'lowball',
                preparationTime: 3,
                servingSize: 1,
                cost: 12.50,
                price: 18.00,
                description: 'A timeless whiskey cocktail with sugar, bitters, and orange peel.',
                garnish: 'Orange peel',
                ingredients: [
                    { name: 'Bourbon Whiskey', quantity: 60, unit: 'ml' },
                    { name: 'Simple Syrup', quantity: 10, unit: 'ml' },
                    { name: 'Angostura Bitters', quantity: 3, unit: 'dashes' },
                    { name: 'Orange Peel', quantity: 1, unit: 'piece' }
                ],
                instructions: [
                    'Add simple syrup and bitters to glass',
                    'Add whiskey and stir with ice',
                    'Strain into glass with fresh ice',
                    'Garnish with orange peel'
                ],
                active: true,
                created: '2024-01-15',
                popularity: 85
            },
            {
                id: 2,
                name: 'Signature Martini',
                type: 'signature',
                difficulty: 'easy',
                glassType: 'martini',
                preparationTime: 2,
                servingSize: 1,
                cost: 15.00,
                price: 22.00,
                description: 'Our house special martini with premium gin and dry vermouth.',
                garnish: 'Olive or lemon twist',
                ingredients: [
                    { name: 'Premium Gin', quantity: 60, unit: 'ml' },
                    { name: 'Dry Vermouth', quantity: 10, unit: 'ml' },
                    { name: 'Olive', quantity: 1, unit: 'piece' }
                ],
                instructions: [
                    'Chill martini glass',
                    'Add gin and vermouth to mixing glass with ice',
                    'Stir until well chilled',
                    'Strain into chilled glass',
                    'Garnish with olive or lemon twist'
                ],
                active: true,
                created: '2024-01-20',
                popularity: 92
            },
            {
                id: 3,
                name: 'Virgin Mojito',
                type: 'mocktail',
                difficulty: 'easy',
                glassType: 'highball',
                preparationTime: 5,
                servingSize: 1,
                cost: 6.50,
                price: 12.00,
                description: 'Refreshing non-alcoholic mojito with mint, lime, and soda water.',
                garnish: 'Fresh mint sprig',
                ingredients: [
                    { name: 'Fresh Mint Leaves', quantity: 8, unit: 'leaves' },
                    { name: 'Lime Juice', quantity: 30, unit: 'ml' },
                    { name: 'Simple Syrup', quantity: 20, unit: 'ml' },
                    { name: 'Soda Water', quantity: 150, unit: 'ml' },
                    { name: 'Ice', quantity: 1, unit: 'cup' }
                ],
                instructions: [
                    'Muddle mint leaves in glass',
                    'Add lime juice and simple syrup',
                    'Fill with ice',
                    'Top with soda water',
                    'Stir gently and garnish with mint'
                ],
                active: true,
                created: '2024-01-25',
                popularity: 78
            },
            {
                id: 4,
                name: 'Flaming Shot',
                type: 'shot',
                difficulty: 'expert',
                glassType: 'shot',
                preparationTime: 2,
                servingSize: 1,
                cost: 8.00,
                price: 15.00,
                description: 'Spectacular flaming shot that requires expert handling.',
                garnish: 'None',
                ingredients: [
                    { name: 'Sambuca', quantity: 25, unit: 'ml' },
                    { name: 'Kahlua', quantity: 15, unit: 'ml' }
                ],
                instructions: [
                    'Layer Kahlua in shot glass',
                    'Float Sambuca on top',
                    'Light carefully with long lighter',
                    'Serve immediately with safety precautions'
                ],
                active: true,
                created: '2024-02-01',
                popularity: 65
            }
        ];
    }

    /**
     * Load and display recipes
     */
    loadRecipes() {
        this.isLoading = true;
        
        // Simulate loading delay
        setTimeout(() => {
            this.filteredRecipes = [...this.recipes];
            this.renderRecipes();
            this.isLoading = false;
        }, 500);
    }

    /**
     * Render recipes based on current view
     */
    renderRecipes() {
        if (this.currentView === 'grid') {
            this.renderGridView();
        } else {
            this.renderListView();
        }
    }

    /**
     * Render grid view
     */
    renderGridView() {
        const gridContainer = document.getElementById('recipes-grid');
        if (!gridContainer) return;

        if (this.filteredRecipes.length === 0) {
            this.showEmptyState();
            return;
        }

        gridContainer.innerHTML = this.filteredRecipes.map(recipe => `
            <div class="recipe-card" data-recipe-id="${recipe.id}">
                <div class="recipe-header">
                        <div class="recipe-badges">
                        <span class="recipe-difficulty-badge ${recipe.difficulty}">${recipe.difficulty}</span>
                        <span class="recipe-type-badge ${recipe.type}">${recipe.type}</span>
                    </div>
                </div>
                <div class="recipe-body" onclick="recipeManager.showRecipeDetails(${recipe.id})">
                    <h3 class="recipe-title">${recipe.name}</h3>
                    <p class="recipe-description">${recipe.description}</p>
                    <div class="recipe-meta">
                        <div class="recipe-meta-item">
                            <svg class="recipe-meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            ${recipe.preparationTime} min
                    </div>
                        <div class="recipe-meta-item">
                            <svg class="recipe-meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            ${recipe.glassType}
                    </div>
                        <div class="recipe-meta-item">
                            <svg class="recipe-meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            ${recipe.ingredients.length} ingredients
                    </div>
                    </div>
                </div>
                <div class="recipe-actions">
                    <div class="recipe-cost">$${recipe.cost.toFixed(2)}</div>
                    <div class="recipe-action-buttons">
                        <button class="recipe-action-btn" onclick="recipeManager.editRecipe(${recipe.id})" title="Edit">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                        <button class="recipe-action-btn" onclick="recipeManager.printRecipe(${recipe.id})" title="Print">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                    </button>
                        <button class="recipe-action-btn" onclick="recipeManager.duplicateRecipe(${recipe.id})" title="Duplicate">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </button>
                    </div>
                </div>
            </div>
        `).join('');
    }

    /**
     * Render list view
     */
    renderListView() {
        const tableBody = document.querySelector('.recipes-table-body');
        if (!tableBody) return;

        if (this.filteredRecipes.length === 0) {
            this.showEmptyState();
            return;
        }

        tableBody.innerHTML = this.filteredRecipes.map(recipe => `
            <tr data-recipe-id="${recipe.id}" onclick="recipeManager.showRecipeDetails(${recipe.id})">
                <td class="recipe-name-cell">
                    <div class="recipe-title">${recipe.name}</div>
                    <div class="recipe-description">${recipe.description}</div>
                </td>
                <td><span class="recipe-type-badge ${recipe.type}">${recipe.type}</span></td>
                <td><span class="recipe-difficulty-badge ${recipe.difficulty}">${recipe.difficulty}</span></td>
                <td>${recipe.glassType}</td>
                <td>$${recipe.cost.toFixed(2)}</td>
                <td>${recipe.ingredients.length}</td>
                <td>
                    <div class="recipe-action-buttons">
                        <button class="recipe-action-btn" onclick="event.stopPropagation(); recipeManager.editRecipe(${recipe.id})" title="Edit">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <button class="recipe-action-btn" onclick="event.stopPropagation(); recipeManager.printRecipe(${recipe.id})" title="Print">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    /**
     * Switch between grid and list view
     */
    switchView(view) {
        this.currentView = view;
        
        const gridView = document.getElementById('recipes-grid');
        const listView = document.getElementById('recipes-list');
        const viewButtons = document.querySelectorAll('.view-btn');
        
        // Update button states
        viewButtons.forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.view === view) {
                btn.classList.add('active');
            }
        });
        
        // Show/hide views
        if (view === 'grid') {
            gridView.style.display = 'grid';
            listView.style.display = 'none';
            this.renderGridView();
        } else {
            gridView.style.display = 'none';
            listView.style.display = 'block';
            this.renderListView();
        }
        
        // Save preference
        localStorage.setItem('recipes_view_preference', view);
    }

    /**
     * Update statistics
     */
    updateStatistics() {
        const totalRecipes = this.recipes.length;
        const signatureRecipes = this.recipes.filter(r => r.type === 'signature').length;
        const popularRecipes = this.recipes.filter(r => r.popularity > 80).length;
        const avgCost = this.recipes.reduce((sum, r) => sum + r.cost, 0) / totalRecipes || 0;

        document.getElementById('total-recipes').textContent = totalRecipes;
        document.getElementById('signature-recipes').textContent = signatureRecipes;
        document.getElementById('popular-recipes').textContent = popularRecipes;
        document.getElementById('avg-cost').textContent = `$${avgCost.toFixed(2)}`;
    }

    /**
     * Handle search input
     */
    handleSearch(event) {
        const searchTerm = event.target.value.toLowerCase().trim();
        
        if (searchTerm === '') {
            this.filteredRecipes = [...this.recipes];
        } else {
            this.filteredRecipes = this.recipes.filter(recipe => 
                recipe.name.toLowerCase().includes(searchTerm) ||
                recipe.description.toLowerCase().includes(searchTerm) ||
                recipe.type.toLowerCase().includes(searchTerm)
            );
        }
        
        this.renderRecipes();
    }

    /**
     * Handle filter changes
     */
    handleFilter() {
        const statusFilter = document.getElementById('status-filter').value;
        
        this.filteredRecipes = this.recipes.filter(recipe => {
            if (statusFilter === 'active') return recipe.active;
            if (statusFilter === 'inactive') return !recipe.active;
            return true;
        });
        
        this.renderRecipes();
    }

    /**
     * Handle sort changes
     */
    handleSort() {
        const sortBy = document.getElementById('sort-filter').value;
        
        this.filteredRecipes.sort((a, b) => {
            switch (sortBy) {
                case 'name':
                    return a.name.localeCompare(b.name);
                case 'cost':
                    return a.cost - b.cost;
                case 'difficulty':
                    const difficultyOrder = { easy: 1, medium: 2, hard: 3, expert: 4 };
                    return difficultyOrder[a.difficulty] - difficultyOrder[b.difficulty];
                case 'popularity':
                    return b.popularity - a.popularity;
                case 'created':
                    return new Date(b.created) - new Date(a.created);
                default:
                    return 0;
            }
        });
        
        this.renderRecipes();
    }

    /**
     * Clear all filters
     */
    clearFilters() {
        document.getElementById('recipe-search').value = '';
        document.getElementById('status-filter').value = '';
        document.getElementById('sort-filter').value = 'name';
        
        this.filteredRecipes = [...this.recipes];
        this.renderRecipes();
    }

    /**
     * Show empty state
     */
    showEmptyState() {
        const gridView = document.getElementById('recipes-grid');
        const listView = document.getElementById('recipes-list');
        const emptyState = document.querySelector('.empty-state');
        
        if (gridView) gridView.style.display = 'none';
        if (listView) listView.style.display = 'none';
        if (emptyState) emptyState.style.display = 'block';
    }

    /**
     * Show recipe details modal
     */
    showRecipeDetails(recipeId) {
        const recipe = this.recipes.find(r => r.id === recipeId);
        if (!recipe) return;

        // For now, just show an alert with recipe details
        alert(`Recipe: ${recipe.name}\nType: ${recipe.type}\nDifficulty: ${recipe.difficulty}\nCost: $${recipe.cost.toFixed(2)}\nIngredients: ${recipe.ingredients.length}`);
    }

    /**
     * Setup form tabs functionality
     */
    setupFormTabs() {
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabPanels = document.querySelectorAll('.tab-panel');

        tabButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const targetTab = e.target.dataset.tab;
                
                // Update button states
                tabButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                
                // Update panel visibility
                tabPanels.forEach(panel => {
                    panel.classList.remove('active');
                    if (panel.dataset.tab === targetTab) {
                        panel.classList.add('active');
                    }
                });
            });
        });
    }

    /**
     * Setup language tabs functionality
     */
    setupLanguageTabs() {
        const langButtons = document.querySelectorAll('.lang-btn');
        const langPanels = document.querySelectorAll('.lang-panel');

        langButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const targetLang = e.target.dataset.lang || e.target.closest('.lang-btn').dataset.lang;
                
                // Update button states
                langButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                
                // Update panel visibility
                langPanels.forEach(panel => {
                    panel.classList.remove('active');
                    if (panel.dataset.lang === targetLang) {
                        panel.classList.add('active');
                    }
                });
            });
        });
    }

    /**
     * Show add recipe modal
     */
    showAddRecipeModal() {
        const modal = document.getElementById('recipe-modal');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            // Reset form
            this.resetRecipeForm();
            
            // Update modal title
            document.getElementById('recipe-modal-title').textContent = 'Add Recipe';
        }
    }

    /**
     * Close recipe modal
     */
    closeRecipeModal() {
        const modal = document.getElementById('recipe-modal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    }

    /**
     * Reset recipe form
     */
    resetRecipeForm() {
        const form = document.getElementById('recipe-form');
        if (form) {
            form.reset();
            
            // Reset to first tab
            document.querySelector('.tab-btn[data-tab="basic"]').click();
            
            // Reset to English language
            const englishBtn = document.querySelector('.lang-btn[data-lang="en"]');
            if (englishBtn) {
                englishBtn.click();
            }
        }
    }

    /**
     * Edit recipe
     */
    editRecipe(recipeId) {
        const recipe = this.recipes.find(r => r.id === recipeId);
        if (!recipe) return;

        alert(`Edit recipe: ${recipe.name}`);
    }

    /**
     * Print recipe
     */
    printRecipe(recipeId) {
        const recipe = this.recipes.find(r => r.id === recipeId);
        if (!recipe) return;

        // Create printable content
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>${recipe.name} - Recipe</title>
                    <style>
                        body { font-family: Arial, sans-serif; padding: 20px; }
                        h1 { color: #333; }
                        .ingredients, .instructions { margin: 20px 0; }
                        .ingredients li, .instructions li { margin: 5px 0; }
                    </style>
                </head>
                <body>
                    <h1>${recipe.name}</h1>
                    <p><strong>Type:</strong> ${recipe.type}</p>
                    <p><strong>Difficulty:</strong> ${recipe.difficulty}</p>
                    <p><strong>Glass:</strong> ${recipe.glassType}</p>
                    <p><strong>Prep Time:</strong> ${recipe.preparationTime} minutes</p>
                    <p>${recipe.description}</p>
                    
                    <h3>Ingredients:</h3>
                    <ul class="ingredients">
                        ${recipe.ingredients.map(ing => `<li>${ing.quantity} ${ing.unit} ${ing.name}</li>`).join('')}
                    </ul>
                    
                    <h3>Instructions:</h3>
                    <ol class="instructions">
                        ${recipe.instructions.map(inst => `<li>${inst}</li>`).join('')}
                    </ol>
                    
                    ${recipe.garnish ? `<p><strong>Garnish:</strong> ${recipe.garnish}</p>` : ''}
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    }

    /**
     * Duplicate recipe
     */
    duplicateRecipe(recipeId) {
        const recipe = this.recipes.find(r => r.id === recipeId);
        if (!recipe) return;

        alert(`Duplicate recipe: ${recipe.name}`);
    }

    /**
     * Export recipes
     */
    exportRecipes() {
        alert('Export recipes functionality will be implemented');
    }

    /**
     * Handle keyboard shortcuts
     */
    handleKeyboardShortcuts(event) {
        if (event.ctrlKey || event.metaKey) {
            switch (event.key) {
                case 'k':
                    event.preventDefault();
                    document.getElementById('recipe-search').focus();
                    break;
                case '1':
                    event.preventDefault();
                    this.switchView('grid');
                    break;
                case '2':
                    event.preventDefault();
                    this.switchView('list');
                    break;
            }
        }
    }

    /**
     * Debounce utility function
     */
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
}

// Initialize the page when DOM is loaded
let recipeManager;

document.addEventListener('DOMContentLoaded', function() {
    recipeManager = new BarCocktailRecipesPage();
    
    // Restore view preference
    const savedView = localStorage.getItem('recipes_view_preference') || 'grid';
    recipeManager.switchView(savedView);
});