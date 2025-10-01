/**
 * Bucket Configurations JavaScript
 * Handles interactive functionality for the bucket configurations page
 */

// Global variables
let currentBucketId = null;
let flourItemCount = 1;
let availableFlours = [];

// DOM Content Loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeBucketConfigurations();
});

/**
 * Initialize bucket configurations functionality
 */
function initializeBucketConfigurations() {
    setupEventListeners();
    setupFormValidation();
    loadAvailableFlours();
}

/**
 * Setup event listeners
 */
function setupEventListeners() {
    // Modal close events
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeBucketModal();
        }
    });

    // Form submission
    const bucketForm = document.getElementById('bucketForm');
    if (bucketForm) {
        bucketForm.addEventListener('submit', handleBucketSubmit);
    }

    // Real-time calculations
    const calculationInputs = [
        'bucketCapacity', 'coldWater', 'hotWater', 
        'expectedYield', 'electricityCost', 'laborCost'
    ];
    
    calculationInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('input', updateCalculations);
        }
    });
}

/**
 * Setup form validation
 */
function setupFormValidation() {
    const requiredInputs = document.querySelectorAll('input[required], select[required]');
    
    requiredInputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('error')) {
                validateField(this);
            }
        });
    });
}

/**
 * Load available flours data
 */
function loadAvailableFlours() {
    // In a real app, this would fetch from the server
    // For now, we'll extract from the select options
    const flourSelect = document.querySelector('.flour-type-select');
    if (flourSelect) {
        availableFlours = Array.from(flourSelect.options)
            .filter(option => option.value)
            .map(option => ({
                type: option.value,
                price: parseFloat(option.dataset.price) || 0,
                stock: parseFloat(option.dataset.stock) || 0
            }));
    }
}

/**
 * Create new bucket configuration
 */
function createBucket() {
    currentBucketId = null;
    document.getElementById('modalTitle').textContent = 'Create New Bucket Configuration';
    document.getElementById('bucketForm').reset();
    
    // Reset flour recipe to single item
    resetFlourRecipe();
    clearFormErrors();
    updateCalculations();
    showModal('bucketModal');
}

/**
 * Edit existing bucket configuration
 */
function editBucket(bucketId) {
    currentBucketId = bucketId;
    document.getElementById('modalTitle').textContent = 'Edit Bucket Configuration';
    
    // In a real app, fetch bucket data from server
    const mockBucketData = getMockBucketData(bucketId);
    populateBucketForm(mockBucketData);
    showModal('bucketModal');
}

/**
 * Duplicate bucket configuration
 */
function duplicateBucket(bucketId) {
    if (confirm('Duplicate this bucket configuration?')) {
        // In a real app, send duplicate request to server
        fetch(`/admin/injera/bucket-configurations/${bucketId}/duplicate`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                refreshConfigurations();
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Failed to duplicate bucket configuration', 'error');
        });
    }
}

/**
 * Delete bucket configuration
 */
function deleteBucket(bucketId) {
    if (confirm('Are you sure you want to delete this bucket configuration? This action cannot be undone.')) {
        fetch(`/admin/injera/bucket-configurations/${bucketId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                refreshConfigurations();
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Failed to delete bucket configuration', 'error');
        });
    }
}

/**
 * Use bucket recipe (start production batch)
 */
function useBucket(bucketId) {
    if (confirm('Use this recipe to start a new production batch?')) {
        // In a real app, this would redirect to production batch creation
        showNotification('Redirecting to production batch creation...', 'info');
        // setTimeout(() => {
        //     window.location.href = `/admin/injera/production-batches/create?bucket_id=${bucketId}`;
        // }, 1500);
    }
}

/**
 * Handle bucket form submission
 */
function handleBucketSubmit(e) {
    e.preventDefault();
    
    if (!validateBucketForm()) {
        return;
    }
    
    const formData = new FormData(e.target);
    
    // Build flour recipe array
    const flourRecipe = [];
    const flourTypes = formData.getAll('flour_recipe[0][flour_type]');
    const flourQuantities = formData.getAll('flour_recipe[0][quantity]');
    
    // Get all flour recipe items
    const flourItems = document.querySelectorAll('.flour-recipe-item');
    flourItems.forEach((item, index) => {
        const typeSelect = item.querySelector('select[name*="flour_type"]');
        const quantityInput = item.querySelector('input[name*="quantity"]');
        
        if (typeSelect && quantityInput && typeSelect.value && quantityInput.value) {
            flourRecipe.push({
                flour_type: typeSelect.value,
                quantity: parseFloat(quantityInput.value)
            });
        }
    });
    
    const data = {
        name: formData.get('name'),
        capacity: parseFloat(formData.get('capacity')),
        flour_recipe: flourRecipe,
        cold_water: parseFloat(formData.get('cold_water')),
        hot_water: parseFloat(formData.get('hot_water')),
        expected_yield: parseInt(formData.get('expected_yield')),
        electricity_cost: parseFloat(formData.get('electricity_cost')),
        labor_cost: parseFloat(formData.get('labor_cost')),
        notes: formData.get('notes')
    };
    
    const url = currentBucketId 
        ? `/admin/injera/bucket-configurations/${currentBucketId}` 
        : '/admin/injera/bucket-configurations';
    
    const method = currentBucketId ? 'PUT' : 'POST';
    
    // Show loading state
    const submitBtn = e.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Saving...';
    submitBtn.disabled = true;
    
    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            closeBucketModal();
            refreshConfigurations();
        } else {
            showNotification(data.message, 'error');
            if (data.errors) {
                showFormErrors(data.errors);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to save bucket configuration', 'error');
    })
    .finally(() => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
}

/**
 * Add flour item to recipe
 */
function addFlourItem() {
    const flourRecipe = document.getElementById('flourRecipe');
    const newItem = createFlourRecipeItem(flourItemCount);
    flourRecipe.appendChild(newItem);
    flourItemCount++;
    updateRemoveButtons();
    updateCalculations();
}

/**
 * Remove flour item from recipe
 */
function removeFlourItem(button) {
    const item = button.closest('.flour-recipe-item');
    if (item) {
        item.remove();
        updateRemoveButtons();
        updateCalculations();
    }
}

/**
 * Create flour recipe item HTML
 */
function createFlourRecipeItem(index) {
    const div = document.createElement('div');
    div.className = 'flour-recipe-item';
    div.innerHTML = `
        <div class="recipe-grid">
            <div class="form-group">
                <label>Flour Type *</label>
                <select name="flour_recipe[${index}][flour_type]" class="flour-type-select" required onchange="updateFlourCost(this)">
                    <option value="">Select Flour Type</option>
                    ${availableFlours.map(flour => 
                        `<option value="${flour.type}" data-price="${flour.price}" data-stock="${flour.stock}">
                            ${flour.type} - $${flour.price.toFixed(2)}/kg (${flour.stock}kg Available)
                        </option>`
                    ).join('')}
                </select>
            </div>
            <div class="form-group">
                <label>Quantity (kg) *</label>
                <input type="number" name="flour_recipe[${index}][quantity]" step="0.1" min="0.1" class="flour-quantity" required onchange="calculateFlourCost(this)">
            </div>
            <div class="form-group">
                <label>Cost ($)</label>
                <input type="number" class="flour-cost" step="0.01" readonly>
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-danger remove-flour" onclick="removeFlourItem(this)">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
        </div>
    `;
    return div;
}

/**
 * Update flour cost when type or quantity changes
 */
function updateFlourCost(selectElement) {
    const recipeItem = selectElement.closest('.flour-recipe-item');
    const quantityInput = recipeItem.querySelector('.flour-quantity');
    const costInput = recipeItem.querySelector('.flour-cost');
    
    calculateFlourCost(quantityInput);
}

/**
 * Calculate flour cost
 */
function calculateFlourCost(quantityInput) {
    const recipeItem = quantityInput.closest('.flour-recipe-item');
    const typeSelect = recipeItem.querySelector('.flour-type-select');
    const costInput = recipeItem.querySelector('.flour-cost');
    
    if (typeSelect.value && quantityInput.value) {
        const selectedOption = typeSelect.options[typeSelect.selectedIndex];
        const pricePerKg = parseFloat(selectedOption.dataset.price) || 0;
        const quantity = parseFloat(quantityInput.value) || 0;
        const cost = pricePerKg * quantity;
        
        costInput.value = cost.toFixed(2);
    } else {
        costInput.value = '';
    }
    
    updateCalculations();
}

/**
 * Calculate total water
 */
function calculateTotalWater() {
    const coldWater = parseFloat(document.getElementById('coldWater').value) || 0;
    const hotWater = parseFloat(document.getElementById('hotWater').value) || 0;
    const totalWater = coldWater + hotWater;
    
    document.getElementById('totalWater').value = totalWater.toFixed(1);
}

/**
 * Update all calculations
 */
function updateCalculations() {
    calculateTotalWater();
    calculateTotalFlour();
    calculateTotalCost();
}

/**
 * Calculate total flour and cost
 */
function calculateTotalFlour() {
    let totalFlour = 0;
    let totalFlourCost = 0;
    
    const flourItems = document.querySelectorAll('.flour-recipe-item');
    flourItems.forEach(item => {
        const quantityInput = item.querySelector('.flour-quantity');
        const costInput = item.querySelector('.flour-cost');
        
        if (quantityInput.value && costInput.value) {
            totalFlour += parseFloat(quantityInput.value) || 0;
            totalFlourCost += parseFloat(costInput.value) || 0;
        }
    });
    
    document.getElementById('totalFlour').textContent = totalFlour.toFixed(1);
    document.getElementById('totalFlourCost').textContent = totalFlourCost.toFixed(2);
}

/**
 * Calculate total cost and cost per injera
 */
function calculateTotalCost() {
    const totalFlourCost = parseFloat(document.getElementById('totalFlourCost').textContent) || 0;
    const electricityCost = parseFloat(document.getElementById('electricityCost').value) || 0;
    const laborCost = parseFloat(document.getElementById('laborCost').value) || 0;
    const expectedYield = parseInt(document.getElementById('expectedYield').value) || 1;
    
    const totalCost = totalFlourCost + electricityCost + laborCost;
    const costPerInjera = totalCost / expectedYield;
    
    document.getElementById('totalCost').textContent = totalCost.toFixed(2);
    document.getElementById('costPerInjera').textContent = costPerInjera.toFixed(3);
}

/**
 * Update remove buttons visibility
 */
function updateRemoveButtons() {
    const removeButtons = document.querySelectorAll('.remove-flour');
    removeButtons.forEach((button, index) => {
        button.style.display = removeButtons.length > 1 ? 'flex' : 'none';
    });
}

/**
 * Reset flour recipe to single item
 */
function resetFlourRecipe() {
    const flourRecipe = document.getElementById('flourRecipe');
    flourRecipe.innerHTML = '';
    flourItemCount = 0;
    addFlourItem();
}

/**
 * Validate bucket form
 */
function validateBucketForm() {
    const form = document.getElementById('bucketForm');
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    // Validate basic required fields
    requiredFields.forEach(field => {
        if (!validateField(field)) {
            isValid = false;
        }
    });
    
    // Validate flour recipe
    const flourItems = document.querySelectorAll('.flour-recipe-item');
    if (flourItems.length === 0) {
        showNotification('Please add at least one flour to the recipe', 'error');
        isValid = false;
    }
    
    let hasValidFlour = false;
    flourItems.forEach(item => {
        const typeSelect = item.querySelector('.flour-type-select');
        const quantityInput = item.querySelector('.flour-quantity');
        
        if (typeSelect.value && quantityInput.value && parseFloat(quantityInput.value) > 0) {
            hasValidFlour = true;
        }
    });
    
    if (!hasValidFlour) {
        showNotification('Please add at least one valid flour with quantity', 'error');
        isValid = false;
    }
    
    return isValid;
}

/**
 * Validate individual field
 */
function validateField(field) {
    const value = field.value.trim();
    let isValid = true;
    let errorMessage = '';
    
    // Required validation
    if (field.hasAttribute('required') && !value) {
        isValid = false;
        errorMessage = 'This field is required';
    }
    
    // Numeric validation
    if (field.type === 'number' && value) {
        const numValue = parseFloat(value);
        if (isNaN(numValue)) {
            isValid = false;
            errorMessage = 'Please enter a valid number';
        } else if (field.min && numValue < parseFloat(field.min)) {
            isValid = false;
            errorMessage = `Value must be at least ${field.min}`;
        }
    }
    
    // Update field styling
    if (isValid) {
        field.classList.remove('error');
        clearFieldError(field);
    } else {
        field.classList.add('error');
        showFieldError(field, errorMessage);
    }
    
    return isValid;
}

/**
 * Show field error
 */
function showFieldError(field, message) {
    clearFieldError(field);
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.textContent = message;
    
    field.parentNode.appendChild(errorDiv);
}

/**
 * Clear field error
 */
function clearFieldError(field) {
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
}

/**
 * Clear all form errors
 */
function clearFormErrors() {
    document.querySelectorAll('.field-error').forEach(error => error.remove());
    document.querySelectorAll('.error').forEach(field => field.classList.remove('error'));
}

/**
 * Show form errors from server
 */
function showFormErrors(errors) {
    Object.keys(errors).forEach(fieldName => {
        const field = document.querySelector(`[name="${fieldName}"]`);
        if (field) {
            field.classList.add('error');
            showFieldError(field, errors[fieldName][0]);
        }
    });
}

/**
 * Show modal
 */
function showModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        
        // Focus first input
        const firstInput = modal.querySelector('input, select, textarea');
        if (firstInput) {
            setTimeout(() => firstInput.focus(), 100);
        }
    }
}

/**
 * Close bucket modal
 */
function closeBucketModal() {
    const modal = document.getElementById('bucketModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
        clearFormErrors();
    }
}

/**
 * Populate bucket form with data
 */
function populateBucketForm(data) {
    document.getElementById('bucketName').value = data.name || '';
    document.getElementById('bucketCapacity').value = data.capacity || '';
    document.getElementById('coldWater').value = data.cold_water || '';
    document.getElementById('hotWater').value = data.hot_water || '';
    document.getElementById('expectedYield').value = data.expected_yield || '';
    document.getElementById('electricityCost').value = data.electricity_cost || '';
    document.getElementById('laborCost').value = data.labor_cost || '';
    document.getElementById('bucketNotes').value = data.notes || '';
    
    // Populate flour recipe
    if (data.flour_recipe && data.flour_recipe.length > 0) {
        resetFlourRecipe();
        
        data.flour_recipe.forEach((flour, index) => {
            if (index > 0) {
                addFlourItem();
            }
            
            const flourItem = document.querySelectorAll('.flour-recipe-item')[index];
            const typeSelect = flourItem.querySelector('.flour-type-select');
            const quantityInput = flourItem.querySelector('.flour-quantity');
            
            typeSelect.value = flour.flour_type;
            quantityInput.value = flour.quantity;
            
            updateFlourCost(typeSelect);
        });
    }
    
    updateCalculations();
}

/**
 * Get mock bucket data (for demo purposes)
 */
function getMockBucketData(bucketId) {
    // Mock data - in real app, this would come from server
    const mockData = {
        1: {
            name: 'Large Production Bucket',
            capacity: 90,
            flour_recipe: [
                { flour_type: 'Teff', quantity: 15 },
                { flour_type: 'Wheat', quantity: 5 }
            ],
            cold_water: 25,
            hot_water: 8,
            expected_yield: 130,
            electricity_cost: 2.50,
            labor_cost: 15.00,
            notes: 'Standard production recipe for high-volume days'
        }
    };
    
    return mockData[bucketId] || {};
}

/**
 * Export configurations
 */
function exportConfigurations() {
    showNotification('Export functionality will be implemented soon', 'info');
}

/**
 * Refresh configurations
 */
function refreshConfigurations() {
    location.reload();
}

/**
 * Show notification
 */
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => notification.classList.add('show'), 100);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

// Add notification styles if not already present
if (!document.querySelector('#bucket-notification-styles')) {
    const style = document.createElement('style');
    style.id = 'bucket-notification-styles';
    style.textContent = `
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            z-index: 10000;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            max-width: 400px;
        }
        .notification.show {
            transform: translateX(0);
        }
        .notification-success {
            background: #059669;
        }
        .notification-error {
            background: #DC2626;
        }
        .notification-info {
            background: #2563EB;
        }
        .field-error {
            color: #DC2626;
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }
        .error {
            border-color: #DC2626 !important;
        }
    `;
    document.head.appendChild(style);
}
