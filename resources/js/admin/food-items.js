/**
 * Food Items Page JavaScript
 * Restaurant-OS Admin Panel
 * 
 * Handles dish management, creation, editing, and organization
 */

class FoodItemsPage {
    constructor() {
        this.dishes = [];
        this.filteredDishes = [];
        this.currentView = 'grid';
        this.currentDish = null;
        this.ingredientCounter = 1;
        this.isLoading = false;
        
        this.init();
    }

    /**
     * Initialize the food items page
     */
    init() {
        this.bindEvents();
        this.generateDummyData();
        this.loadDishes();
        this.setupFormTabs();
        this.setupImageUpload();
        this.setupPricingCalculation();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Dish management events
        this.bindDishEvents();
        
        // Filter and search events
        this.bindFilterEvents();
        
        // View toggle events
        this.bindViewEvents();
        
        // Modal events
        this.bindModalEvents();
        
        // Form events
        this.bindFormEvents();
        
        // Import/Export events
        this.bindImportExportEvents();
    }

    /**
     * Bind dish management events
     */
    bindDishEvents() {
        // Add dish buttons
        document.querySelectorAll('.add-dish-btn').forEach(btn => {
            btn.addEventListener('click', () => this.openDishModal());
        });
    }

    /**
     * Bind filter and search events
     */
    bindFilterEvents() {
        // Search input
        const searchInput = document.getElementById('dish-search');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => this.handleSearch(e.target.value));
        }

        // Filter selects
        const filters = ['category-filter', 'status-filter', 'price-filter'];
        filters.forEach(filterId => {
            const filter = document.getElementById(filterId);
            if (filter) {
                filter.addEventListener('change', () => this.applyFilters());
            }
        });

        // Clear filters
        const clearBtn = document.querySelector('.clear-filters-btn');
        if (clearBtn) {
            clearBtn.addEventListener('click', () => this.clearFilters());
        }
    }

    /**
     * Bind view toggle events
     */
    bindViewEvents() {
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const view = e.currentTarget.dataset.view;
                this.toggleView(view);
            });
        });
    }

    /**
     * Bind modal events
     */
    bindModalEvents() {
        const modal = document.getElementById('dish-modal');
        const closeBtn = modal?.querySelector('.modal-close');
        const cancelBtn = modal?.querySelector('.cancel-btn');
        const overlay = modal?.querySelector('.modal-overlay');

        if (closeBtn) closeBtn.addEventListener('click', () => this.closeDishModal());
        if (cancelBtn) cancelBtn.addEventListener('click', () => this.closeDishModal());
        if (overlay) overlay.addEventListener('click', () => this.closeDishModal());

        // Escape key to close modal
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeDishModal();
            }
        });
    }

    /**
     * Bind form events
     */
    bindFormEvents() {
        // Dish form submission
        const dishForm = document.getElementById('dish-form');
        if (dishForm) {
            dishForm.addEventListener('submit', (e) => this.handleDishFormSubmit(e));
        }

        // Add ingredient button
        const addIngredientBtn = document.querySelector('.add-ingredient-btn');
        if (addIngredientBtn) {
            addIngredientBtn.addEventListener('click', () => this.addIngredientRow());
        }

        // Remove ingredient buttons (delegated)
        document.addEventListener('click', (e) => {
            if (e.target.closest('.remove-ingredient-btn')) {
                this.removeIngredientRow(e.target.closest('.ingredient-item'));
            }
        });
    }

    /**
     * Bind import/export events
     */
    bindImportExportEvents() {
        // Import dishes
        const importBtn = document.querySelector('.import-dishes-btn');
        if (importBtn) {
            importBtn.addEventListener('click', () => this.importDishes());
        }

        // Export menu
        const exportBtn = document.querySelector('.export-menu-btn');
        if (exportBtn) {
            exportBtn.addEventListener('click', () => this.exportMenu());
        }
    }

    /**
     * Setup form tabs
     */
    setupFormTabs() {
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const tabName = e.target.dataset.tab;
                this.switchTab(tabName);
            });
        });
    }

    /**
     * Setup image upload functionality
     */
    setupImageUpload() {
        const uploadArea = document.getElementById('image-upload-area');
        const fileInput = document.getElementById('dish-image');
        const selectBtn = document.querySelector('.select-image-btn');
        const changeBtn = document.querySelector('.change-image-btn');
        const removeBtn = document.querySelector('.remove-image-btn');

        if (selectBtn) {
            selectBtn.addEventListener('click', () => fileInput?.click());
        }

        if (changeBtn) {
            changeBtn.addEventListener('click', () => fileInput?.click());
        }

        if (removeBtn) {
            removeBtn.addEventListener('click', () => this.removeImage());
        }

        if (uploadArea) {
            uploadArea.addEventListener('click', () => fileInput?.click());
            
            // Drag and drop
            uploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                uploadArea.classList.add('dragover');
            });

            uploadArea.addEventListener('dragleave', () => {
                uploadArea.classList.remove('dragover');
            });

            uploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadArea.classList.remove('dragover');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    this.handleImageUpload(files[0]);
                }
            });
        }

        if (fileInput) {
            fileInput.addEventListener('change', (e) => {
                if (e.target.files.length > 0) {
                    this.handleImageUpload(e.target.files[0]);
                }
            });
        }
    }

    /**
     * Setup pricing calculation
     */
    setupPricingCalculation() {
        const costInput = document.getElementById('cost-price');
        const sellingInput = document.getElementById('selling-price');

        if (costInput && sellingInput) {
            [costInput, sellingInput].forEach(input => {
                input.addEventListener('input', () => this.calculateMargins());
            });
        }
    }

    /**
     * Open dish modal
     */
    openDishModal(dish = null) {
        const modal = document.getElementById('dish-modal');
        const title = modal?.querySelector('.modal-title');
        const form = document.getElementById('dish-form');

        if (modal && title && form) {
            this.currentDish = dish;
            
            if (dish) {
                title.textContent = 'Edit Dish';
                this.populateDishForm(dish);
            } else {
                title.textContent = 'Add New Dish';
                form.reset();
                this.resetFormTabs();
                this.resetIngredients();
                this.removeImage();
            }

            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
            
            // Focus first input
            const firstInput = form.querySelector('input, select');
            if (firstInput) {
                setTimeout(() => firstInput.focus(), 100);
            }
        }
    }

    /**
     * Close dish modal
     */
    closeDishModal() {
        const modal = document.getElementById('dish-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
            this.currentDish = null;
        }
    }

    /**
     * Switch form tab
     */
    switchTab(tabName) {
        // Update tab buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.tab === tabName);
        });

        // Update tab panels
        document.querySelectorAll('.tab-panel').forEach(panel => {
            panel.classList.toggle('active', panel.dataset.tab === tabName);
        });
    }

    /**
     * Handle search
     */
    handleSearch(query) {
        this.applyFilters();
    }

    /**
     * Apply filters
     */
    applyFilters() {
        const searchQuery = document.getElementById('dish-search')?.value.toLowerCase() || '';
        const categoryFilter = document.getElementById('category-filter')?.value || '';
        const statusFilter = document.getElementById('status-filter')?.value || '';
        const priceFilter = document.getElementById('price-filter')?.value || '';

        this.filteredDishes = this.dishes.filter(dish => {
            // Search filter
            const matchesSearch = !searchQuery || 
                dish.name.toLowerCase().includes(searchQuery) ||
                dish.description.toLowerCase().includes(searchQuery);

            // Category filter
            const matchesCategory = !categoryFilter || dish.category === categoryFilter;

            // Status filter
            const matchesStatus = !statusFilter || dish.status === statusFilter;

            // Price filter
            let matchesPrice = true;
            if (priceFilter) {
                const price = parseFloat(dish.selling_price);
                switch (priceFilter) {
                    case '0-50':
                        matchesPrice = price < 50;
                        break;
                    case '50-100':
                        matchesPrice = price >= 50 && price < 100;
                        break;
                    case '100-200':
                        matchesPrice = price >= 100 && price < 200;
                        break;
                    case '200+':
                        matchesPrice = price >= 200;
                        break;
                }
            }

            return matchesSearch && matchesCategory && matchesStatus && matchesPrice;
        });

        this.renderDishes();
    }

    /**
     * Clear filters
     */
    clearFilters() {
        document.getElementById('dish-search').value = '';
        document.getElementById('category-filter').value = '';
        document.getElementById('status-filter').value = '';
        document.getElementById('price-filter').value = '';
        
        this.filteredDishes = [...this.dishes];
        this.renderDishes();
    }

    /**
     * Toggle view between grid and list
     */
    toggleView(view) {
        this.currentView = view;
        
        // Update view buttons
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.view === view);
        });

        // Show/hide views
        const gridView = document.getElementById('dishes-grid');
        const listView = document.getElementById('dishes-list');

        if (view === 'grid') {
            gridView.style.display = 'grid';
            listView.style.display = 'none';
        } else {
            gridView.style.display = 'none';
            listView.style.display = 'block';
        }

        this.renderDishes();
    }

    /**
     * Handle dish form submission
     */
    handleDishFormSubmit(e) {
        e.preventDefault();
        
        if (this.validateDishForm()) {
            this.saveDish();
        }
    }

    /**
     * Validate dish form
     */
    validateDishForm() {
        const form = document.getElementById('dish-form');
        if (!form) return false;

        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                this.showFieldError(field, 'This field is required');
                isValid = false;
            } else {
                this.clearFieldError(field);
            }
        });

        return isValid;
    }

    /**
     * Save dish
     */
    saveDish() {
        this.showLoading();
        
        // Simulate API call
        setTimeout(() => {
            const formData = this.getDishFormData();
            
            if (this.currentDish) {
                // Update existing dish
                const index = this.dishes.findIndex(d => d.id === this.currentDish.id);
                if (index !== -1) {
                    this.dishes[index] = { ...this.currentDish, ...formData };
                }
                this.showNotification('Dish updated successfully', 'success');
            } else {
                // Create new dish
                const newDish = {
                    id: Date.now(),
                    ...formData,
                    created_at: new Date().toISOString()
                };
                this.dishes.push(newDish);
                this.showNotification('Dish created successfully', 'success');
            }
            
            this.hideLoading();
            this.closeDishModal();
            this.loadDishes();
        }, 1000);
    }

    /**
     * Get dish form data
     */
    getDishFormData() {
        const form = document.getElementById('dish-form');
        if (!form) return {};

        const formData = new FormData(form);
        const data = {};
        
        // Basic fields
        for (let [key, value] of formData.entries()) {
            if (key.startsWith('ingredients[')) continue; // Handle separately
            if (key === 'dietary[]') {
                if (!data.dietary) data.dietary = [];
                data.dietary.push(value);
            } else {
                data[key] = value;
            }
        }

        // Handle ingredients
        data.ingredients = this.getIngredientsData();

        // Handle image
        const imageInput = document.getElementById('dish-image');
        if (imageInput?.files.length > 0) {
            data.image = imageInput.files[0];
        }

        return data;
    }

    /**
     * Get ingredients data from form
     */
    getIngredientsData() {
        const ingredients = [];
        const ingredientItems = document.querySelectorAll('.ingredient-item');
        
        ingredientItems.forEach(item => {
            const ingredientSelect = item.querySelector('.ingredient-select');
            const quantityInput = item.querySelector('.quantity-input');
            const unitSelect = item.querySelector('.unit-select');
            
            if (ingredientSelect?.value && quantityInput?.value) {
                ingredients.push({
                    id: ingredientSelect.value,
                    quantity: parseFloat(quantityInput.value),
                    unit: unitSelect?.value || 'kg'
                });
            }
        });
        
        return ingredients;
    }

    /**
     * Add ingredient row
     */
    addIngredientRow() {
        const ingredientsList = document.getElementById('ingredients-list');
        if (!ingredientsList) return;

        const ingredientItem = document.createElement('div');
        ingredientItem.className = 'ingredient-item';
        ingredientItem.innerHTML = `
            <div class="ingredient-select-wrapper">
                <select class="form-select ingredient-select" name="ingredients[${this.ingredientCounter}][id]">
                    <option value="">Select ingredient...</option>
                    <option value="1">Tomatoes</option>
                    <option value="2">Onions</option>
                    <option value="3">Chicken Breast</option>
                    <option value="4">Rice</option>
                    <option value="5">Pasta</option>
                    <option value="6">Cheese</option>
                </select>
            </div>
            <div class="quantity-input-wrapper">
                <input type="number" class="form-input quantity-input" 
                       name="ingredients[${this.ingredientCounter}][quantity]" 
                       placeholder="0" min="0" step="0.01">
            </div>
            <div class="unit-select-wrapper">
                <select class="form-select unit-select" name="ingredients[${this.ingredientCounter}][unit]">
                    <option value="kg">kg</option>
                    <option value="g">g</option>
                    <option value="l">l</option>
                    <option value="ml">ml</option>
                    <option value="pcs">pcs</option>
                </select>
            </div>
            <button type="button" class="btn btn-sm btn-danger remove-ingredient-btn">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        `;

        ingredientsList.appendChild(ingredientItem);
        this.ingredientCounter++;
    }

    /**
     * Remove ingredient row
     */
    removeIngredientRow(item) {
        if (item) {
            item.remove();
        }
    }

    /**
     * Reset ingredients to default state
     */
    resetIngredients() {
        const ingredientsList = document.getElementById('ingredients-list');
        if (ingredientsList) {
            // Keep only the first ingredient row and reset it
            const items = ingredientsList.querySelectorAll('.ingredient-item');
            items.forEach((item, index) => {
                if (index === 0) {
                    item.querySelector('.ingredient-select').value = '';
                    item.querySelector('.quantity-input').value = '';
                    item.querySelector('.unit-select').value = 'kg';
                } else {
                    item.remove();
                }
            });
        }
        this.ingredientCounter = 1;
    }

    /**
     * Handle image upload
     */
    handleImageUpload(file) {
        if (!file.type.startsWith('image/')) {
            this.showNotification('Please select a valid image file', 'error');
            return;
        }

        if (file.size > 5 * 1024 * 1024) { // 5MB limit
            this.showNotification('Image size must be less than 5MB', 'error');
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            this.showImagePreview(e.target.result);
        };
        reader.readAsDataURL(file);
    }

    /**
     * Show image preview
     */
    showImagePreview(src) {
        const uploadArea = document.getElementById('image-upload-area');
        const preview = document.getElementById('image-preview');
        const previewImage = document.getElementById('preview-image');

        if (uploadArea && preview && previewImage) {
            uploadArea.style.display = 'none';
            preview.style.display = 'block';
            previewImage.src = src;
        }
    }

    /**
     * Remove image
     */
    removeImage() {
        const uploadArea = document.getElementById('image-upload-area');
        const preview = document.getElementById('image-preview');
        const fileInput = document.getElementById('dish-image');

        if (uploadArea && preview) {
            uploadArea.style.display = 'block';
            preview.style.display = 'none';
        }

        if (fileInput) {
            fileInput.value = '';
        }
    }

    /**
     * Calculate profit margins
     */
    calculateMargins() {
        const costPrice = parseFloat(document.getElementById('cost-price')?.value) || 0;
        const sellingPrice = parseFloat(document.getElementById('selling-price')?.value) || 0;

        const profitMargin = sellingPrice - costPrice;
        const profitPercentage = costPrice > 0 ? (profitMargin / costPrice) * 100 : 0;
        const markup = costPrice > 0 ? ((sellingPrice - costPrice) / costPrice) * 100 : 0;

        const profitMarginEl = document.getElementById('profit-margin');
        const markupEl = document.getElementById('markup');

        if (profitMarginEl) {
            profitMarginEl.textContent = `${profitMargin.toFixed(2)} ETB (${profitPercentage.toFixed(1)}%)`;
        }

        if (markupEl) {
            markupEl.textContent = `${markup.toFixed(1)}%`;
        }
    }

    /**
     * Load dishes
     */
    loadDishes() {
        this.showDishesLoading();
        
        // Simulate API call
        setTimeout(() => {
            this.filteredDishes = [...this.dishes];
            this.renderDishes();
            this.hideDishesLoading();
            
            if (this.dishes.length === 0) {
                this.showEmptyState();
            }
        }, 1000);
    }

    /**
     * Render dishes based on current view
     */
    renderDishes() {
        if (this.currentView === 'grid') {
            this.renderDishesGrid();
        } else {
            this.renderDishesList();
        }

        if (this.filteredDishes.length === 0 && this.dishes.length > 0) {
            this.showEmptyState();
        } else {
            this.hideEmptyState();
        }
    }

    /**
     * Render dishes grid
     */
    renderDishesGrid() {
        const grid = document.getElementById('dishes-grid');
        if (!grid) return;

        grid.innerHTML = '';

        this.filteredDishes.forEach(dish => {
            const dishCard = this.createDishCard(dish);
            grid.appendChild(dishCard);
        });
    }

    /**
     * Render dishes list
     */
    renderDishesList() {
        const tbody = document.querySelector('.dishes-table-body');
        if (!tbody) return;

        tbody.innerHTML = '';

        this.filteredDishes.forEach(dish => {
            const row = this.createDishRow(dish);
            tbody.appendChild(row);
        });
    }

    /**
     * Create dish card for grid view
     */
    createDishCard(dish) {
        const card = document.createElement('div');
        card.className = 'dish-card';
        card.onclick = () => this.openDishModal(dish);
        
        const dietaryBadges = dish.dietary?.map(diet => 
            `<span class="dietary-badge ${diet}">${diet}</span>`
        ).join('') || '';
        
        card.innerHTML = `
            <img src="${dish.image || '/images/placeholder-dish.jpg'}" alt="${dish.name}" class="dish-image">
            <div class="dish-content">
                <div class="dish-header">
                    <h3 class="dish-title">${dish.name}</h3>
                    <div class="dish-menu">
                        <button class="dish-menu-btn" onclick="event.stopPropagation(); foodItemsPage.editDish(${dish.id})">
                            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="dish-category ${dish.category}">${this.getCategoryName(dish.category)}</div>
                <p class="dish-description">${dish.description || 'No description available'}</p>
                <div class="dish-footer">
                    <div class="dish-price">${dish.selling_price} ETB</div>
                    <div class="dish-status">
                        <div class="status-indicator ${dish.status}"></div>
                        <span>${this.getStatusName(dish.status)}</span>
                    </div>
                </div>
                ${dietaryBadges ? `<div class="dish-dietary">${dietaryBadges}</div>` : ''}
            </div>
        `;
        
        return card;
    }

    /**
     * Create dish row for list view
     */
    createDishRow(dish) {
        const row = document.createElement('tr');
        
        const margin = dish.selling_price - dish.cost_price;
        const marginClass = margin > 0 ? 'positive' : margin < 0 ? 'negative' : '';
        
        row.innerHTML = `
            <td>
                <div class="dish-info">
                    <img src="${dish.image || '/images/placeholder-dish.jpg'}" alt="${dish.name}" class="dish-info-image">
                    <div class="dish-info-content">
                        <div class="dish-info-name">${dish.name}</div>
                        <div class="dish-info-description">${dish.description || 'No description'}</div>
                    </div>
                </div>
            </td>
            <td><span class="dish-category ${dish.category}">${this.getCategoryName(dish.category)}</span></td>
            <td class="price-cell">${dish.selling_price} ETB</td>
            <td class="cost-cell">${dish.cost_price} ETB</td>
            <td class="margin-cell ${marginClass}">${margin.toFixed(2)} ETB</td>
            <td><span class="status-badge ${dish.status}">${this.getStatusName(dish.status)}</span></td>
            <td>
                <div class="table-actions">
                    <button class="btn btn-sm btn-secondary" onclick="foodItemsPage.editDish(${dish.id})">Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="foodItemsPage.deleteDish(${dish.id})">Delete</button>
                </div>
            </td>
        `;
        
        return row;
    }

    /**
     * Edit dish
     */
    editDish(dishId) {
        const dish = this.dishes.find(d => d.id === dishId);
        if (dish) {
            this.openDishModal(dish);
        }
    }

    /**
     * Delete dish
     */
    deleteDish(dishId) {
        if (confirm('Are you sure you want to delete this dish?')) {
            this.dishes = this.dishes.filter(d => d.id !== dishId);
            this.loadDishes();
            this.showNotification('Dish deleted successfully', 'success');
        }
    }

    /**
     * Import dishes
     */
    importDishes() {
        // Create file input for import
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = '.json,.csv';
        input.onchange = (e) => {
            const file = e.target.files[0];
            if (file) {
                this.handleImportFile(file);
            }
        };
        input.click();
    }

    /**
     * Handle import file
     */
    handleImportFile(file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            try {
                const data = JSON.parse(e.target.result);
                if (Array.isArray(data)) {
                    this.dishes.push(...data);
                    this.loadDishes();
                    this.showNotification(`Imported ${data.length} dishes successfully`, 'success');
                }
            } catch (error) {
                this.showNotification('Invalid file format', 'error');
            }
        };
        reader.readAsText(file);
    }

    /**
     * Export menu
     */
    exportMenu() {
        const data = {
            dishes: this.dishes,
            exported_at: new Date().toISOString(),
            total_dishes: this.dishes.length
        };
        
        const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `menu-export-${new Date().toISOString().split('T')[0]}.json`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        
        this.showNotification('Menu exported successfully', 'success');
    }

    /**
     * Generate dummy data
     */
    generateDummyData() {
        this.dishes = [
            {
                id: 1,
                name: 'Grilled Chicken Breast',
                description: 'Tender grilled chicken breast served with seasonal vegetables and herb sauce',
                category: 'main-courses',
                cost_price: 45.00,
                selling_price: 85.00,
                prep_time: 25,
                status: 'active',
                dietary: ['gluten-free'],
                image: '/images/grilled-chicken.jpg',
                ingredients: [
                    { id: 3, quantity: 0.25, unit: 'kg' },
                    { id: 1, quantity: 0.1, unit: 'kg' }
                ]
            },
            {
                id: 2,
                name: 'Caesar Salad',
                description: 'Fresh romaine lettuce with parmesan cheese, croutons and caesar dressing',
                category: 'appetizers',
                cost_price: 25.00,
                selling_price: 55.00,
                prep_time: 10,
                status: 'active',
                dietary: ['vegetarian'],
                image: '/images/caesar-salad.jpg'
            },
            {
                id: 3,
                name: 'Chocolate Lava Cake',
                description: 'Warm chocolate cake with molten center, served with vanilla ice cream',
                category: 'desserts',
                cost_price: 20.00,
                selling_price: 65.00,
                prep_time: 15,
                status: 'active',
                dietary: ['vegetarian'],
                image: '/images/lava-cake.jpg'
            },
            {
                id: 4,
                name: 'Fresh Orange Juice',
                description: 'Freshly squeezed orange juice served chilled',
                category: 'beverages',
                cost_price: 8.00,
                selling_price: 25.00,
                prep_time: 5,
                status: 'active',
                dietary: ['vegan', 'gluten-free'],
                image: '/images/orange-juice.jpg'
            },
            {
                id: 5,
                name: 'Spicy Pasta Arrabbiata',
                description: 'Penne pasta in spicy tomato sauce with garlic and red chili peppers',
                category: 'main-courses',
                cost_price: 30.00,
                selling_price: 70.00,
                prep_time: 20,
                status: 'inactive',
                dietary: ['spicy', 'vegetarian'],
                image: '/images/pasta-arrabbiata.jpg'
            }
        ];
    }

    /**
     * Utility methods
     */
    getCategoryName(category) {
        const names = {
            'appetizers': 'Appetizers',
            'main-courses': 'Main Courses',
            'desserts': 'Desserts',
            'beverages': 'Beverages'
        };
        return names[category] || category;
    }

    getStatusName(status) {
        const names = {
            'active': 'Active',
            'inactive': 'Inactive',
            'out-of-stock': 'Out of Stock'
        };
        return names[status] || status;
    }

    /**
     * UI Helper methods
     */
    showLoading() {
        this.isLoading = true;
    }

    hideLoading() {
        this.isLoading = false;
    }

    showDishesLoading() {
        const loadingCards = document.querySelectorAll('.dish-card.loading');
        const loadingRows = document.querySelectorAll('.loading-row');
        loadingCards.forEach(card => card.style.display = 'block');
        loadingRows.forEach(row => row.style.display = 'table-row');
    }

    hideDishesLoading() {
        const loadingCards = document.querySelectorAll('.dish-card.loading');
        const loadingRows = document.querySelectorAll('.loading-row');
        loadingCards.forEach(card => card.style.display = 'none');
        loadingRows.forEach(row => row.style.display = 'none');
    }

    showEmptyState() {
        const emptyState = document.querySelector('.empty-state');
        const dishesGrid = document.getElementById('dishes-grid');
        const dishesList = document.getElementById('dishes-list');
        
        if (emptyState) emptyState.style.display = 'block';
        if (dishesGrid) dishesGrid.style.display = 'none';
        if (dishesList) dishesList.style.display = 'none';
    }

    hideEmptyState() {
        const emptyState = document.querySelector('.empty-state');
        const dishesGrid = document.getElementById('dishes-grid');
        const dishesList = document.getElementById('dishes-list');
        
        if (emptyState) emptyState.style.display = 'none';
        if (this.currentView === 'grid' && dishesGrid) {
            dishesGrid.style.display = 'grid';
        } else if (this.currentView === 'list' && dishesList) {
            dishesList.style.display = 'block';
        }
    }

    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <span class="notification-message">${message}</span>
                <button class="notification-close">&times;</button>
            </div>
        `;
        
        // Add to page
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 5000);
        
        // Manual close
        const closeBtn = notification.querySelector('.notification-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            });
        }
    }

    showFieldError(field, message) {
        this.clearFieldError(field);
        
        field.classList.add('error');
        const errorElement = document.createElement('div');
        errorElement.className = 'field-error';
        errorElement.textContent = message;
        
        field.parentNode.appendChild(errorElement);
    }

    clearFieldError(field) {
        field.classList.remove('error');
        const errorElement = field.parentNode.querySelector('.field-error');
        if (errorElement) {
            errorElement.remove();
        }
    }

    resetFormTabs() {
        // Reset to first tab
        this.switchTab('basic');
    }

    populateDishForm(dish) {
        const form = document.getElementById('dish-form');
        if (!form || !dish) return;

        // Populate basic fields
        Object.keys(dish).forEach(key => {
            const input = form.querySelector(`[name="${key}"]`);
            if (input) {
                if (input.type === 'checkbox' || input.type === 'radio') {
                    if (Array.isArray(dish[key])) {
                        dish[key].forEach(value => {
                            const checkbox = form.querySelector(`[name="${key}[]"][value="${value}"]`);
                            if (checkbox) checkbox.checked = true;
                        });
                    } else {
                        input.checked = input.value === dish[key];
                    }
                } else {
                    input.value = dish[key];
                }
            }
        });

        // Handle image
        if (dish.image) {
            this.showImagePreview(dish.image);
        }

        // Calculate margins
        this.calculateMargins();
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.foodItemsPage = new FoodItemsPage();
});
