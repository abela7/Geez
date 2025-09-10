/**
 * Menu Dish Cost Page JavaScript
 * Restaurant-OS Admin Panel
 * 
 * Handles dish cost calculation, ingredient management, and pricing
 */

class DishCostCalculator {
    constructor() {
        this.ingredients = [];
        this.currentIngredient = null;
        this.calculations = {
            ingredientsCost: 0,
            overheadCost: 0,
            totalCost: 0,
            profitMargin: 70,
            suggestedPrice: 0,
            finalPrice: 0
        };
        this.overheadBreakdown = {
            labor: 8,
            utilities: 3,
            rent: 2,
            other: 2
        };
        
        this.init();
    }

    /**
     * Initialize the dish cost calculator
     */
    init() {
        this.bindEvents();
        this.updateCalculations();
        this.updateDisplay();
        this.generateDummyDishes();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Ingredient management events
        this.bindIngredientEvents();
        
        // Overhead calculation events
        this.bindOverheadEvents();
        
        // Pricing events
        this.bindPricingEvents();
        
        // Modal events
        this.bindModalEvents();
        
        // Form events
        this.bindFormEvents();
        
        // Action button events
        this.bindActionEvents();
    }

    /**
     * Bind ingredient management events
     */
    bindIngredientEvents() {
        // Add ingredient buttons
        document.querySelectorAll('.add-ingredient-btn').forEach(btn => {
            btn.addEventListener('click', () => this.openIngredientModal());
        });
    }

    /**
     * Bind overhead calculation events
     */
    bindOverheadEvents() {
        // Overhead type radio buttons
        document.querySelectorAll('input[name="overhead_type"]').forEach(radio => {
            radio.addEventListener('change', (e) => this.handleOverheadTypeChange(e.target.value));
        });

        // Overhead inputs
        const overheadPercentage = document.getElementById('overhead-percentage');
        const overheadFixed = document.getElementById('overhead-fixed');

        if (overheadPercentage) {
            overheadPercentage.addEventListener('input', () => this.updateCalculations());
        }

        if (overheadFixed) {
            overheadFixed.addEventListener('input', () => this.updateCalculations());
        }

        // Breakdown inputs
        document.querySelectorAll('.breakdown-input').forEach(input => {
            input.addEventListener('input', () => this.updateOverheadBreakdown());
        });
    }

    /**
     * Bind pricing events
     */
    bindPricingEvents() {
        // Profit margin input
        const profitMarginInput = document.getElementById('profit-margin');
        if (profitMarginInput) {
            profitMarginInput.addEventListener('input', (e) => {
                this.calculations.profitMargin = parseFloat(e.target.value) || 0;
                this.updatePricingCalculations();
                this.updatePricingDisplay();
                this.updatePresetButtons();
            });
        }

        // Final price input
        const finalPriceInput = document.getElementById('final-price');
        if (finalPriceInput) {
            finalPriceInput.addEventListener('input', (e) => {
                this.calculations.finalPrice = parseFloat(e.target.value) || 0;
                this.updatePricingAnalysis();
            });
        }

        // Preset buttons
        document.querySelectorAll('.preset-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const margin = parseInt(e.target.dataset.margin);
                this.setProfitMargin(margin);
            });
        });
    }

    /**
     * Bind modal events
     */
    bindModalEvents() {
        // Ingredient modal
        this.bindModalCloseEvents('ingredient-modal', () => this.closeIngredientModal());
        
        // Load dish modal
        this.bindModalCloseEvents('load-dish-modal', () => this.closeLoadDishModal());

        // Escape key to close modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeIngredientModal();
                this.closeLoadDishModal();
            }
        });
    }

    /**
     * Bind modal close events for a specific modal
     */
    bindModalCloseEvents(modalId, closeCallback) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        const closeBtn = modal.querySelector('.modal-close');
        const cancelBtn = modal.querySelector('.cancel-ingredient-btn, .close-load-modal-btn');
        const overlay = modal.querySelector('.modal-overlay');

        if (closeBtn) closeBtn.addEventListener('click', closeCallback);
        if (cancelBtn) cancelBtn.addEventListener('click', closeCallback);
        if (overlay) overlay.addEventListener('click', closeCallback);
    }

    /**
     * Bind form events
     */
    bindFormEvents() {
        // Ingredient form submission
        const ingredientForm = document.getElementById('ingredient-form');
        if (ingredientForm) {
            ingredientForm.addEventListener('submit', (e) => this.handleIngredientFormSubmit(e));
        }

        // Dish information inputs
        const dishInputs = ['dish-name', 'dish-category', 'serving-size'];
        dishInputs.forEach(inputId => {
            const input = document.getElementById(inputId);
            if (input) {
                input.addEventListener('input', () => this.updateCalculations());
            }
        });
    }

    /**
     * Bind action button events
     */
    bindActionEvents() {
        // Load existing dish
        const loadDishBtn = document.querySelector('.load-dish-btn');
        if (loadDishBtn) {
            loadDishBtn.addEventListener('click', () => this.openLoadDishModal());
        }

        // Save as template
        const saveTemplateBtn = document.querySelector('.save-template-btn');
        if (saveTemplateBtn) {
            saveTemplateBtn.addEventListener('click', () => this.saveAsTemplate());
        }

        // New calculation
        const newCalculationBtn = document.querySelector('.new-calculation-btn');
        if (newCalculationBtn) {
            newCalculationBtn.addEventListener('click', () => this.newCalculation());
        }

        // Reset calculation
        const resetBtn = document.querySelector('.reset-calculation-btn');
        if (resetBtn) {
            resetBtn.addEventListener('click', () => this.resetCalculation());
        }

        // Save calculation
        const saveBtn = document.querySelector('.save-calculation-btn');
        if (saveBtn) {
            saveBtn.addEventListener('click', () => this.saveCalculation());
        }
    }

    /**
     * Handle overhead type change
     */
    handleOverheadTypeChange(type) {
        const percentageInput = document.getElementById('overhead-percentage');
        const fixedInput = document.getElementById('overhead-fixed');

        if (type === 'percentage') {
            percentageInput.disabled = false;
            fixedInput.disabled = true;
        } else {
            percentageInput.disabled = true;
            fixedInput.disabled = false;
        }

        this.updateCalculations();
    }

    /**
     * Update overhead breakdown
     */
    updateOverheadBreakdown() {
        const breakdownInputs = document.querySelectorAll('.breakdown-input');
        let total = 0;

        breakdownInputs.forEach(input => {
            const type = input.dataset.type;
            const value = parseFloat(input.value) || 0;
            this.overheadBreakdown[type] = value;
            total += value;
        });

        // Update the main overhead percentage
        const overheadPercentageInput = document.getElementById('overhead-percentage');
        if (overheadPercentageInput && !overheadPercentageInput.disabled) {
            overheadPercentageInput.value = total.toFixed(1);
        }

        this.updateCalculations();
    }

    /**
     * Set profit margin
     */
    setProfitMargin(margin) {
        this.calculations.profitMargin = margin;
        
        const profitMarginInput = document.getElementById('profit-margin');
        if (profitMarginInput) {
            profitMarginInput.value = margin;
        }

        this.updatePricingCalculations();
        this.updatePricingDisplay();
        this.updatePresetButtons();
    }

    /**
     * Update preset buttons
     */
    updatePresetButtons() {
        document.querySelectorAll('.preset-btn').forEach(btn => {
            const margin = parseInt(btn.dataset.margin);
            btn.classList.toggle('active', margin === this.calculations.profitMargin);
        });
    }

    /**
     * Open ingredient modal
     */
    openIngredientModal(ingredient = null) {
        const modal = document.getElementById('ingredient-modal');
        const title = modal?.querySelector('.modal-title');
        const form = document.getElementById('ingredient-form');
        const saveBtn = modal?.querySelector('.save-ingredient-btn');

        if (modal && title && form && saveBtn) {
            this.currentIngredient = ingredient;
            
            if (ingredient) {
                title.textContent = 'Edit Ingredient';
                saveBtn.textContent = 'Update Ingredient';
                this.populateIngredientForm(ingredient);
            } else {
                title.textContent = 'Add Ingredient';
                saveBtn.textContent = 'Add Ingredient';
                form.reset();
            }

            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
            
            // Focus first input
            const firstInput = form.querySelector('input');
            if (firstInput) {
                setTimeout(() => firstInput.focus(), 100);
            }
        }
    }

    /**
     * Close ingredient modal
     */
    closeIngredientModal() {
        const modal = document.getElementById('ingredient-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
            this.currentIngredient = null;
        }
    }

    /**
     * Open load dish modal
     */
    openLoadDishModal() {
        const modal = document.getElementById('load-dish-modal');
        if (modal) {
            this.populateDishesList();
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
        }
    }

    /**
     * Close load dish modal
     */
    closeLoadDishModal() {
        const modal = document.getElementById('load-dish-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
        }
    }

    /**
     * Handle ingredient form submission
     */
    handleIngredientFormSubmit(e) {
        e.preventDefault();
        
        if (this.validateIngredientForm()) {
            this.saveIngredient();
        }
    }

    /**
     * Validate ingredient form
     */
    validateIngredientForm() {
        const form = document.getElementById('ingredient-form');
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
     * Save ingredient
     */
    saveIngredient() {
        const formData = this.getIngredientFormData();
        
        if (this.currentIngredient) {
            // Update existing ingredient
            const index = this.ingredients.findIndex(i => i.id === this.currentIngredient.id);
            if (index !== -1) {
                this.ingredients[index] = { ...this.currentIngredient, ...formData };
            }
            this.showNotification('Ingredient updated successfully', 'success');
        } else {
            // Create new ingredient
            const newIngredient = {
                id: Date.now(),
                ...formData
            };
            this.ingredients.push(newIngredient);
            this.showNotification('Ingredient added successfully', 'success');
        }
        
        this.closeIngredientModal();
        this.renderIngredientsTable();
        this.updateCalculations();
    }

    /**
     * Get ingredient form data
     */
    getIngredientFormData() {
        const form = document.getElementById('ingredient-form');
        if (!form) return {};

        const formData = new FormData(form);
        const data = {};
        
        for (let [key, value] of formData.entries()) {
            if (key === 'quantity' || key === 'cost_per_unit') {
                data[key] = parseFloat(value) || 0;
            } else {
                data[key] = value;
            }
        }

        // Calculate total cost
        data.total_cost = data.quantity * data.cost_per_unit;

        return data;
    }

    /**
     * Render ingredients table
     */
    renderIngredientsTable() {
        const tbody = document.getElementById('ingredients-table-body');
        const emptyState = document.getElementById('ingredients-empty');
        const tableWrapper = document.querySelector('.ingredients-table-wrapper');

        if (!tbody || !emptyState || !tableWrapper) return;

        if (this.ingredients.length === 0) {
            tableWrapper.style.display = 'none';
            emptyState.style.display = 'block';
            return;
        }

        tableWrapper.style.display = 'block';
        emptyState.style.display = 'none';
        tbody.innerHTML = '';

        this.ingredients.forEach(ingredient => {
            const row = this.createIngredientRow(ingredient);
            tbody.appendChild(row);
        });
    }

    /**
     * Create ingredient row
     */
    createIngredientRow(ingredient) {
        const row = document.createElement('tr');
        
        row.innerHTML = `
            <td>
                <div class="ingredient-name">${ingredient.name}</div>
                ${ingredient.notes ? `<div class="ingredient-notes">${ingredient.notes}</div>` : ''}
            </td>
            <td class="ingredient-quantity">${ingredient.quantity}</td>
            <td class="ingredient-unit">${ingredient.unit}</td>
            <td class="ingredient-cost">£${ingredient.cost_per_unit.toFixed(3)}</td>
            <td class="ingredient-total">£${ingredient.total_cost.toFixed(2)}</td>
            <td>
                <div class="ingredient-actions">
                    <button class="btn btn-sm btn-secondary" onclick="dishCostCalculator.editIngredient(${ingredient.id})">Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="dishCostCalculator.deleteIngredient(${ingredient.id})">Delete</button>
                </div>
            </td>
        `;
        
        return row;
    }

    /**
     * Edit ingredient
     */
    editIngredient(ingredientId) {
        const ingredient = this.ingredients.find(i => i.id === ingredientId);
        if (ingredient) {
            this.openIngredientModal(ingredient);
        }
    }

    /**
     * Delete ingredient
     */
    deleteIngredient(ingredientId) {
        if (confirm('Are you sure you want to delete this ingredient?')) {
            this.ingredients = this.ingredients.filter(i => i.id !== ingredientId);
            this.renderIngredientsTable();
            this.updateCalculations();
            this.showNotification('Ingredient deleted successfully', 'success');
        }
    }

    /**
     * Update all calculations
     */
    updateCalculations() {
        this.updateIngredientsCost();
        this.updateOverheadCost();
        this.updateTotalCost();
        this.updatePricingCalculations();
        this.updateDisplay();
    }

    /**
     * Update ingredients cost
     */
    updateIngredientsCost() {
        this.calculations.ingredientsCost = this.ingredients.reduce((total, ingredient) => {
            return total + (ingredient.total_cost || 0);
        }, 0);
    }

    /**
     * Update overhead cost
     */
    updateOverheadCost() {
        const overheadType = document.querySelector('input[name="overhead_type"]:checked')?.value || 'percentage';
        
        if (overheadType === 'percentage') {
            const percentage = parseFloat(document.getElementById('overhead-percentage')?.value) || 0;
            this.calculations.overheadCost = (this.calculations.ingredientsCost * percentage) / 100;
        } else {
            this.calculations.overheadCost = parseFloat(document.getElementById('overhead-fixed')?.value) || 0;
        }
    }

    /**
     * Update total cost
     */
    updateTotalCost() {
        this.calculations.totalCost = this.calculations.ingredientsCost + this.calculations.overheadCost;
    }

    /**
     * Update pricing calculations
     */
    updatePricingCalculations() {
        const margin = this.calculations.profitMargin / 100;
        this.calculations.suggestedPrice = this.calculations.totalCost * (1 + margin);
        
        // Update final price if not manually set
        const finalPriceInput = document.getElementById('final-price');
        if (finalPriceInput && (finalPriceInput.value === '' || finalPriceInput.value === '0' || finalPriceInput.value === '0.00')) {
            this.calculations.finalPrice = this.calculations.suggestedPrice;
            finalPriceInput.value = this.calculations.finalPrice.toFixed(2);
        }
    }

    /**
     * Update display
     */
    updateDisplay() {
        this.updateCostDisplay();
        this.updatePricingDisplay();
        this.updatePricingAnalysis();
    }

    /**
     * Update cost display
     */
    updateCostDisplay() {
        const ingredientsCostEl = document.getElementById('ingredients-cost');
        const overheadCostEl = document.getElementById('overhead-cost');
        const totalCostEl = document.getElementById('total-cost');

        if (ingredientsCostEl) ingredientsCostEl.textContent = `£${this.calculations.ingredientsCost.toFixed(2)}`;
        if (overheadCostEl) overheadCostEl.textContent = `£${this.calculations.overheadCost.toFixed(2)}`;
        if (totalCostEl) totalCostEl.textContent = `£${this.calculations.totalCost.toFixed(2)}`;
    }

    /**
     * Update pricing display
     */
    updatePricingDisplay() {
        const suggestedPriceEl = document.getElementById('suggested-price');
        if (suggestedPriceEl) {
            suggestedPriceEl.textContent = `£${this.calculations.suggestedPrice.toFixed(2)}`;
        }
    }

    /**
     * Update pricing analysis
     */
    updatePricingAnalysis() {
        const finalPrice = this.calculations.finalPrice || 0;
        const totalCost = this.calculations.totalCost || 0;
        
        // Calculate actual margin
        const actualMargin = totalCost > 0 ? ((finalPrice - totalCost) / totalCost) * 100 : 0;
        
        // Calculate profit per dish
        const profitPerDish = finalPrice - totalCost;
        
        // Calculate cost percentage
        const costPercentage = finalPrice > 0 ? (totalCost / finalPrice) * 100 : 0;

        // Update display
        const actualMarginEl = document.getElementById('actual-margin');
        const profitPerDishEl = document.getElementById('profit-per-dish');
        const costPercentageEl = document.getElementById('cost-percentage');

        if (actualMarginEl) actualMarginEl.textContent = `${actualMargin.toFixed(1)}%`;
        if (profitPerDishEl) profitPerDishEl.textContent = `£${profitPerDish.toFixed(2)}`;
        if (costPercentageEl) costPercentageEl.textContent = `${costPercentage.toFixed(1)}%`;
    }

    /**
     * New calculation
     */
    newCalculation() {
        if (this.ingredients.length > 0 || document.getElementById('dish-name').value) {
            if (!confirm('This will clear all current data. Are you sure?')) {
                return;
            }
        }
        this.resetCalculation();
    }

    /**
     * Reset calculation
     */
    resetCalculation() {
        // Clear ingredients
        this.ingredients = [];
        
        // Reset form inputs
        const form = document.querySelector('.dish-cost-container');
        if (form) {
            const inputs = form.querySelectorAll('input[type="text"], input[type="number"], select, textarea');
            inputs.forEach(input => {
                if (input.id === 'overhead-percentage') {
                    input.value = '15';
                } else if (input.id === 'profit-margin') {
                    input.value = '70';
                } else if (input.id === 'serving-size') {
                    input.value = '1';
                } else if (input.name === 'overhead_type' && input.value === 'percentage') {
                    input.checked = true;
                } else if (input.type === 'number') {
                    input.value = input.min || '0';
                } else {
                    input.value = '';
                }
            });
        }

        // Reset calculations
        this.calculations = {
            ingredientsCost: 0,
            overheadCost: 0,
            totalCost: 0,
            profitMargin: 70,
            suggestedPrice: 0,
            finalPrice: 0
        };

        // Reset overhead breakdown
        const breakdownInputs = document.querySelectorAll('.breakdown-input');
        breakdownInputs.forEach(input => {
            const type = input.dataset.type;
            input.value = this.overheadBreakdown[type];
        });

        // Update display
        this.renderIngredientsTable();
        this.updateCalculations();
        this.setProfitMargin(70);
        
        this.showNotification('Calculation reset successfully', 'success');
    }

    /**
     * Save calculation
     */
    saveCalculation() {
        const dishName = document.getElementById('dish-name').value;
        if (!dishName.trim()) {
            this.showNotification('Please enter a dish name before saving', 'error');
            return;
        }

        if (this.ingredients.length === 0) {
            this.showNotification('Please add at least one ingredient before saving', 'error');
            return;
        }

        // Simulate saving
        setTimeout(() => {
            this.showNotification('Calculation saved successfully', 'success');
        }, 500);
    }

    /**
     * Save as template
     */
    saveAsTemplate() {
        if (this.ingredients.length === 0) {
            this.showNotification('Please add ingredients before saving as template', 'error');
            return;
        }

        const templateName = prompt('Enter template name:');
        if (templateName) {
            // Simulate saving template
            setTimeout(() => {
                this.showNotification(`Template "${templateName}" saved successfully`, 'success');
            }, 500);
        }
    }

    /**
     * Populate dishes list for loading
     */
    populateDishesList() {
        const dishesList = document.getElementById('dishes-list');
        if (!dishesList) return;

        const dishes = this.getDummyDishes();
        dishesList.innerHTML = '';

        dishes.forEach(dish => {
            const dishItem = document.createElement('div');
            dishItem.className = 'dish-item';
            dishItem.onclick = () => this.loadDish(dish);
            
            dishItem.innerHTML = `
                <div class="dish-info">
                    <div class="dish-name">${dish.name}</div>
                    <div class="dish-details">${dish.category} • ${dish.ingredients.length} ingredients</div>
                </div>
                <div class="dish-cost">£${dish.totalCost.toFixed(2)}</div>
            `;
            
            dishesList.appendChild(dishItem);
        });
    }

    /**
     * Load dish data
     */
    loadDish(dish) {
        // Populate dish information
        document.getElementById('dish-name').value = dish.name;
        document.getElementById('dish-category').value = dish.category;
        document.getElementById('serving-size').value = dish.servingSize;

        // Load ingredients
        this.ingredients = [...dish.ingredients];

        // Load overhead settings
        document.getElementById('overhead-percentage').value = dish.overheadPercentage;
        document.getElementById('profit-margin').value = dish.profitMargin;

        // Update calculations and display
        this.calculations.profitMargin = dish.profitMargin;
        this.renderIngredientsTable();
        this.updateCalculations();
        this.setProfitMargin(dish.profitMargin);

        this.closeLoadDishModal();
        this.showNotification(`Loaded "${dish.name}" successfully`, 'success');
    }

    /**
     * Generate dummy dishes data
     */
    generateDummyDishes() {
        this.dummyDishes = [
            {
                id: 1,
                name: 'Margherita Pizza',
                category: 'main_courses',
                servingSize: 1,
                overheadPercentage: 15,
                profitMargin: 70,
                totalCost: 4.25,
                ingredients: [
                    { id: 1, name: 'Pizza Dough', quantity: 250, unit: 'g', cost_per_unit: 0.008, total_cost: 2.00, notes: 'Fresh made daily' },
                    { id: 2, name: 'Tomato Sauce', quantity: 80, unit: 'ml', cost_per_unit: 0.012, total_cost: 0.96, notes: 'House special recipe' },
                    { id: 3, name: 'Mozzarella Cheese', quantity: 120, unit: 'g', cost_per_unit: 0.018, total_cost: 2.16, notes: 'Fresh mozzarella' },
                    { id: 4, name: 'Fresh Basil', quantity: 5, unit: 'g', cost_per_unit: 0.025, total_cost: 0.13, notes: 'Organic basil leaves' }
                ]
            },
            {
                id: 2,
                name: 'Caesar Salad',
                category: 'appetizers',
                servingSize: 1,
                overheadPercentage: 12,
                profitMargin: 85,
                totalCost: 3.15,
                ingredients: [
                    { id: 5, name: 'Romaine Lettuce', quantity: 150, unit: 'g', cost_per_unit: 0.008, total_cost: 1.20, notes: 'Fresh crisp lettuce' },
                    { id: 6, name: 'Caesar Dressing', quantity: 30, unit: 'ml', cost_per_unit: 0.020, total_cost: 0.60, notes: 'Homemade dressing' },
                    { id: 7, name: 'Parmesan Cheese', quantity: 25, unit: 'g', cost_per_unit: 0.035, total_cost: 0.88, notes: 'Aged parmesan' },
                    { id: 8, name: 'Croutons', quantity: 20, unit: 'g', cost_per_unit: 0.015, total_cost: 0.30, notes: 'House-made croutons' },
                    { id: 9, name: 'Anchovies', quantity: 3, unit: 'piece', cost_per_unit: 0.12, total_cost: 0.36, notes: 'Optional garnish' }
                ]
            },
            {
                id: 3,
                name: 'Chocolate Brownie',
                category: 'desserts',
                servingSize: 1,
                overheadPercentage: 18,
                profitMargin: 120,
                totalCost: 2.80,
                ingredients: [
                    { id: 10, name: 'Dark Chocolate', quantity: 80, unit: 'g', cost_per_unit: 0.025, total_cost: 2.00, notes: '70% cocoa chocolate' },
                    { id: 11, name: 'Butter', quantity: 60, unit: 'g', cost_per_unit: 0.008, total_cost: 0.48, notes: 'Unsalted butter' },
                    { id: 12, name: 'Sugar', quantity: 50, unit: 'g', cost_per_unit: 0.002, total_cost: 0.10, notes: 'Caster sugar' },
                    { id: 13, name: 'Eggs', quantity: 1, unit: 'piece', cost_per_unit: 0.15, total_cost: 0.15, notes: 'Free-range eggs' },
                    { id: 14, name: 'Flour', quantity: 30, unit: 'g', cost_per_unit: 0.002, total_cost: 0.06, notes: 'Plain flour' }
                ]
            }
        ];
    }

    /**
     * Get dummy dishes
     */
    getDummyDishes() {
        return this.dummyDishes || [];
    }

    /**
     * Utility methods
     */
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

    populateIngredientForm(ingredient) {
        const form = document.getElementById('ingredient-form');
        if (!form || !ingredient) return;

        // Populate fields
        Object.keys(ingredient).forEach(key => {
            const input = form.querySelector(`[name="${key}"]`);
            if (input) {
                input.value = ingredient[key];
            }
        });
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.dishCostCalculator = new DishCostCalculator();
});
