/**
 * Bar Settings Page JavaScript
 * Restaurant-OS Admin Panel
 * 
 * Handles bar settings, conversion calculations, and inventory thresholds
 */

class BarSettingsPage {
    constructor() {
        this.conversionRates = {};
        this.thresholds = {};
        this.customConversions = [];
        this.isLoading = false;
        
        this.init();
    }

    /**
     * Initialize the settings page
     */
    init() {
        this.bindEvents();
        this.loadDefaultConversions();
        this.setupCalculator();
        this.setupFormTabs();
        this.loadSettings();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Save settings button
        const saveBtn = document.querySelector('.save-settings-btn');
        if (saveBtn) {
            saveBtn.addEventListener('click', this.saveSettings.bind(this));
        }

        // Reset settings button
        const resetBtn = document.querySelector('.reset-settings-btn');
        if (resetBtn) {
            resetBtn.addEventListener('click', this.resetToDefaults.bind(this));
        }

        // Add custom conversion button
        const addConversionBtn = document.querySelector('.add-conversion-btn');
        if (addConversionBtn) {
            addConversionBtn.addEventListener('click', this.showCustomConversionModal.bind(this));
        }

        // Calculator button
        const calculateBtn = document.querySelector('.calculate-btn');
        if (calculateBtn) {
            calculateBtn.addEventListener('click', this.calculateConversion.bind(this));
        }

        // Modal functionality
        this.bindModalEvents();

        // Real-time calculation updates
        this.bindCalculatorEvents();

        // Form change tracking
        this.bindFormChangeEvents();

        // Keyboard shortcuts
        document.addEventListener('keydown', this.handleKeyboardShortcuts.bind(this));
    }

    /**
     * Bind modal events
     */
    bindModalEvents() {
        // Close modal events
        const modalClose = document.querySelector('.modal-close');
        if (modalClose) {
            modalClose.addEventListener('click', this.closeCustomConversionModal.bind(this));
        }

        const modalOverlay = document.querySelector('.modal-overlay');
        if (modalOverlay) {
            modalOverlay.addEventListener('click', this.closeCustomConversionModal.bind(this));
        }

        const cancelBtn = document.querySelector('.cancel-btn');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', this.closeCustomConversionModal.bind(this));
        }

        // Custom conversion form
        const customConversionForm = document.getElementById('custom-conversion-form');
        if (customConversionForm) {
            customConversionForm.addEventListener('submit', this.handleCustomConversionSubmit.bind(this));
        }
    }

    /**
     * Bind calculator events
     */
    bindCalculatorEvents() {
        const calcInputs = document.querySelectorAll('#calc-quantity, #calc-from-unit, #calc-to-unit');
        calcInputs.forEach(input => {
            input.addEventListener('change', this.updateCalculatorResult.bind(this));
            input.addEventListener('input', this.updateCalculatorResult.bind(this));
        });
    }

    /**
     * Bind form change events
     */
    bindFormChangeEvents() {
        const formInputs = document.querySelectorAll('.form-input, .form-select, .form-textarea');
        formInputs.forEach(input => {
            input.addEventListener('change', this.markFormAsChanged.bind(this));
        });

        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', this.markFormAsChanged.bind(this));
        });
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
     * Load default conversion rates
     */
    loadDefaultConversions() {
        this.conversionRates = {
            // Beer conversions
            pintToGlasses: 80,          // 1 pint = 80 glasses
            gallonToPints: 8,           // 1 gallon = 8 pints
            kegToPints: 124,            // 1 keg = 124 pints
            
            // Spirit conversions
            bottleToSingles: 30,        // 1 bottle (750ml) = 30 single shots (25ml)
            bottleToDoubles: 15,        // 1 bottle (750ml) = 15 double shots (50ml)
            literToSingles: 40,         // 1 liter = 40 single shots
            
            // Wine conversions
            wineBottleToGlasses: 5,     // 1 wine bottle = 5 glasses (150ml each)
            wineCaseToBottles: 12,      // 1 case = 12 bottles
            
            // Standard pour sizes
            standardShot: 25,           // ml
            doubleShot: 50,             // ml
            winePour: 150,              // ml
            beerPour: 330,              // ml
        };

        this.thresholds = {
            beer: 3,        // pints remaining
            spirits: 10,    // shots remaining
            wine: 2,        // glasses remaining
            mixers: 20      // servings remaining
        };
    }

    /**
     * Setup calculator
     */
    setupCalculator() {
        this.updateCalculatorResult();
    }

    /**
     * Load current settings
     */
    loadSettings() {
        // In real implementation, this would load from backend
        // For now, populate with default values
        this.populateFormWithDefaults();
    }

    /**
     * Populate form with default values
     */
    populateFormWithDefaults() {
        const rates = this.conversionRates;
        
        // Populate pour sizes
        document.getElementById('standard-shot').value = rates.standardShot;
        document.getElementById('double-shot').value = rates.doubleShot;
        document.getElementById('wine-pour').value = rates.winePour;
        document.getElementById('beer-pour').value = rates.beerPour;
        
        // Populate conversion rates
        document.getElementById('pint-to-glasses').value = rates.pintToGlasses;
        document.getElementById('gallon-to-pints').value = rates.gallonToPints;
        document.getElementById('keg-to-pints').value = rates.kegToPints;
        document.getElementById('bottle-to-singles').value = rates.bottleToSingles;
        document.getElementById('bottle-to-doubles').value = rates.bottleToDoubles;
        document.getElementById('liter-to-singles').value = rates.literToSingles;
        document.getElementById('wine-bottle-to-glasses').value = rates.wineBottleToGlasses;
        document.getElementById('wine-case-to-bottles').value = rates.wineCaseToBottles;
        
        // Populate thresholds
        document.getElementById('beer-threshold').value = this.thresholds.beer;
        document.getElementById('spirits-threshold').value = this.thresholds.spirits;
        document.getElementById('wine-threshold').value = this.thresholds.wine;
        document.getElementById('mixers-threshold').value = this.thresholds.mixers;
    }

    /**
     * Calculate conversion result
     */
    calculateConversion() {
        this.updateCalculatorResult();
        this.showNotification('Conversion calculated successfully', 'success');
    }

    /**
     * Update calculator result in real-time
     */
    updateCalculatorResult() {
        const quantity = parseFloat(document.getElementById('calc-quantity').value) || 1;
        const fromUnit = document.getElementById('calc-from-unit').value;
        const toUnit = document.getElementById('calc-to-unit').value;
        
        let result = 0;
        
        // Perform conversion calculations
        switch (fromUnit) {
            case 'gallon':
                if (toUnit === 'glasses') {
                    result = quantity * this.conversionRates.gallonToPints * this.conversionRates.pintToGlasses;
                } else if (toUnit === 'pints') {
                    result = quantity * this.conversionRates.gallonToPints;
                }
                break;
                
            case 'pint':
                if (toUnit === 'glasses') {
                    result = quantity * this.conversionRates.pintToGlasses;
                }
                break;
                
            case 'bottle_750ml':
                if (toUnit === 'shots') {
                    result = quantity * this.conversionRates.bottleToSingles;
                } else if (toUnit === 'servings') {
                    result = quantity * this.conversionRates.bottleToDoubles;
                } else if (toUnit === 'glasses') {
                    result = quantity * this.conversionRates.wineBottleToGlasses;
                }
                break;
                
            case 'liter':
                if (toUnit === 'shots') {
                    result = quantity * this.conversionRates.literToSingles;
                }
                break;
                
            case 'keg':
                if (toUnit === 'pints') {
                    result = quantity * this.conversionRates.kegToPints;
                } else if (toUnit === 'glasses') {
                    result = quantity * this.conversionRates.kegToPints * this.conversionRates.pintToGlasses;
                }
                break;
                
            case 'case':
                if (toUnit === 'bottles') {
                    result = quantity * this.conversionRates.wineCaseToBottles;
                } else if (toUnit === 'glasses') {
                    result = quantity * this.conversionRates.wineCaseToBottles * this.conversionRates.wineBottleToGlasses;
                }
                break;
        }
        
        // Update display
        document.getElementById('result-value').textContent = Math.round(result * 10) / 10;
        document.getElementById('result-unit').textContent = toUnit;
        
        // Store result for potential use
        this.lastCalculationResult = {
            quantity,
            fromUnit,
            toUnit,
            result
        };
    }

    /**
     * Save all settings
     */
    saveSettings() {
        const form = document.getElementById('settings-form');
        if (!form) return;
        
        const formData = new FormData(form);
        
        // Update conversion rates from form
        this.conversionRates = {
            standardShot: parseFloat(formData.get('standard_shot_size')),
            doubleShot: parseFloat(formData.get('double_shot_size')),
            winePour: parseFloat(formData.get('wine_pour_size')),
            beerPour: parseFloat(formData.get('beer_pour_size')),
            pintToGlasses: parseFloat(formData.get('pint_to_glasses')),
            gallonToPints: parseFloat(formData.get('gallon_to_pints')),
            kegToPints: parseFloat(formData.get('keg_to_pints')),
            bottleToSingles: parseFloat(formData.get('bottle_to_singles')),
            bottleToDoubles: parseFloat(formData.get('bottle_to_doubles')),
            literToSingles: parseFloat(formData.get('liter_to_singles')),
            wineBottleToGlasses: parseFloat(formData.get('wine_bottle_to_glasses')),
            wineCaseToBottles: parseFloat(formData.get('wine_case_to_bottles'))
        };
        
        // Update thresholds
        this.thresholds = {
            beer: parseFloat(formData.get('beer_threshold')),
            spirits: parseFloat(formData.get('spirits_threshold')),
            wine: parseFloat(formData.get('wine_threshold')),
            mixers: parseFloat(formData.get('mixers_threshold'))
        };
        
        // Show saving indicator
        this.showSavingIndicator();
        
        // Simulate API call
        setTimeout(() => {
            this.hideSavingIndicator();
            this.showNotification('Bar settings saved successfully', 'success');
            this.markFormAsSaved();
        }, 1500);
    }

    /**
     * Reset to default values
     */
    resetToDefaults() {
        if (confirm('Are you sure you want to reset all settings to default values?')) {
            this.loadDefaultConversions();
            this.populateFormWithDefaults();
            this.updateCalculatorResult();
            this.showNotification('Settings reset to defaults', 'info');
        }
    }

    /**
     * Show custom conversion modal
     */
    showCustomConversionModal() {
        const modal = document.getElementById('custom-conversion-modal');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            // Reset form
            document.getElementById('custom-conversion-form').reset();
        }
    }

    /**
     * Close custom conversion modal
     */
    closeCustomConversionModal() {
        const modal = document.getElementById('custom-conversion-modal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    }

    /**
     * Handle custom conversion form submission
     */
    handleCustomConversionSubmit(event) {
        event.preventDefault();
        
        const formData = new FormData(event.target);
        
        const customConversion = {
            id: Date.now(),
            name: formData.get('conversion_name'),
            fromContainer: formData.get('from_container'),
            toUnit: formData.get('to_unit'),
            rate: parseFloat(formData.get('conversion_rate')),
            notes: formData.get('conversion_notes')
        };
        
        this.addCustomConversion(customConversion);
        this.closeCustomConversionModal();
    }

    /**
     * Add custom conversion
     */
    addCustomConversion(conversion) {
        this.customConversions.push(conversion);
        this.renderCustomConversion(conversion);
        this.showNotification(`Custom conversion "${conversion.name}" added successfully`, 'success');
    }

    /**
     * Render custom conversion in the list
     */
    renderCustomConversion(conversion) {
        const conversionList = document.getElementById('conversion-list');
        if (!conversionList) return;
        
        const conversionHTML = `
            <div class="conversion-item custom" data-conversion-id="${conversion.id}">
                <div class="conversion-header">
                    <h5 class="conversion-title">${conversion.name}</h5>
                    <button type="button" class="btn btn-sm btn-danger remove-conversion-btn" 
                            onclick="settingsManager.removeCustomConversion(${conversion.id})">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
                <div class="conversion-grid">
                    <div class="form-group">
                        <div class="conversion-input">
                            <span class="conversion-label">1 ${conversion.fromContainer} =</span>
                            <input type="number" value="${conversion.rate}" class="form-input" readonly>
                            <span class="conversion-unit">${conversion.toUnit}</span>
                        </div>
                        ${conversion.notes ? `<small class="form-hint">${conversion.notes}</small>` : ''}
                    </div>
                </div>
            </div>
        `;
        
        conversionList.insertAdjacentHTML('beforeend', conversionHTML);
    }

    /**
     * Remove custom conversion
     */
    removeCustomConversion(conversionId) {
        if (confirm('Are you sure you want to remove this custom conversion?')) {
            this.customConversions = this.customConversions.filter(c => c.id !== conversionId);
            
            const conversionElement = document.querySelector(`[data-conversion-id="${conversionId}"]`);
            if (conversionElement) {
                conversionElement.remove();
            }
            
            this.showNotification('Custom conversion removed', 'info');
        }
    }

    /**
     * Calculate stock alert based on thresholds
     */
    calculateStockAlert(currentStock, containerType, containerSize) {
        let servingsRemaining = 0;
        
        switch (containerType) {
            case 'pint':
                servingsRemaining = currentStock * this.conversionRates.pintToGlasses;
                return servingsRemaining <= this.thresholds.beer;
                
            case 'bottle_750ml':
                servingsRemaining = currentStock * this.conversionRates.bottleToSingles;
                return servingsRemaining <= this.thresholds.spirits;
                
            case 'wine_bottle':
                servingsRemaining = currentStock * this.conversionRates.wineBottleToGlasses;
                return servingsRemaining <= this.thresholds.wine;
                
            case 'gallon':
                servingsRemaining = currentStock * this.conversionRates.gallonToPints * this.conversionRates.pintToGlasses;
                return servingsRemaining <= this.thresholds.beer;
                
            default:
                return false;
        }
    }

    /**
     * Get servings remaining
     */
    getServingsRemaining(currentStock, containerType) {
        switch (containerType) {
            case 'pint':
                return Math.floor(currentStock * this.conversionRates.pintToGlasses);
                
            case 'bottle_750ml':
                return Math.floor(currentStock * this.conversionRates.bottleToSingles);
                
            case 'wine_bottle':
                return Math.floor(currentStock * this.conversionRates.wineBottleToGlasses);
                
            case 'gallon':
                return Math.floor(currentStock * this.conversionRates.gallonToPints * this.conversionRates.pintToGlasses);
                
            default:
                return 0;
        }
    }

    /**
     * Mark form as changed
     */
    markFormAsChanged() {
        const saveBtn = document.querySelector('.save-settings-btn');
        if (saveBtn && !saveBtn.classList.contains('changed')) {
            saveBtn.classList.add('changed');
            saveBtn.innerHTML = saveBtn.innerHTML.replace('Save Settings', 'Save Changes');
        }
    }

    /**
     * Mark form as saved
     */
    markFormAsSaved() {
        const saveBtn = document.querySelector('.save-settings-btn');
        if (saveBtn) {
            saveBtn.classList.remove('changed');
            saveBtn.innerHTML = saveBtn.innerHTML.replace('Save Changes', 'Save Settings');
        }
    }

    /**
     * Show saving indicator
     */
    showSavingIndicator() {
        let indicator = document.querySelector('.save-indicator');
        if (!indicator) {
            indicator = document.createElement('div');
            indicator.className = 'save-indicator';
            indicator.innerHTML = 'Saving settings...';
            document.body.appendChild(indicator);
        }
        
        indicator.classList.add('show');
        document.getElementById('settings-form').classList.add('saving');
    }

    /**
     * Hide saving indicator
     */
    hideSavingIndicator() {
        const indicator = document.querySelector('.save-indicator');
        if (indicator) {
            indicator.classList.remove('show');
        }
        
        document.getElementById('settings-form').classList.remove('saving');
    }

    /**
     * Show notification
     */
    showNotification(message, type = 'info') {
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

    /**
     * Handle keyboard shortcuts
     */
    handleKeyboardShortcuts(event) {
        if (event.ctrlKey || event.metaKey) {
            switch (event.key) {
                case 's':
                    event.preventDefault();
                    this.saveSettings();
                    break;
                case 'r':
                    event.preventDefault();
                    this.resetToDefaults();
                    break;
                case 'c':
                    event.preventDefault();
                    this.calculateConversion();
                    break;
                case 'n':
                    event.preventDefault();
                    this.showCustomConversionModal();
                    break;
            }
        }
        
        if (event.key === 'Escape') {
            this.closeCustomConversionModal();
        }
    }

    /**
     * Export conversion settings
     */
    exportSettings() {
        const settings = {
            conversionRates: this.conversionRates,
            thresholds: this.thresholds,
            customConversions: this.customConversions
        };
        
        const blob = new Blob([JSON.stringify(settings, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        
        const a = document.createElement('a');
        a.href = url;
        a.download = 'bar-settings.json';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        
        this.showNotification('Settings exported successfully', 'success');
    }

    /**
     * Get conversion rates (for use by other modules)
     */
    getConversionRates() {
        return this.conversionRates;
    }

    /**
     * Get threshold settings (for use by other modules)
     */
    getThresholds() {
        return this.thresholds;
    }

    /**
     * Check if item needs reorder (public method for inventory system)
     */
    needsReorder(currentStock, containerType) {
        return this.calculateStockAlert(currentStock, containerType);
    }

    /**
     * Get reorder message (public method for inventory system)
     */
    getReorderMessage(itemName, currentStock, containerType) {
        const servingsLeft = this.getServingsRemaining(currentStock, containerType);
        const threshold = this.thresholds[containerType] || 0;
        
        return `${itemName} is running low! Only ${servingsLeft} servings remaining (threshold: ${threshold}).`;
    }
}

// Initialize the page when DOM is loaded
let settingsManager;

document.addEventListener('DOMContentLoaded', function() {
    settingsManager = new BarSettingsPage();
});

// Make conversion functions available globally for inventory system
window.BarSettingsManager = BarSettingsPage;
