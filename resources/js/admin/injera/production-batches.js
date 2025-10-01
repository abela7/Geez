/**
 * Production Batches JavaScript
 * Handles 5-stage lifecycle tracking and batch management
 */

// Global variables
let currentBatchId = null;
let currentStage = null;
let bucketConfigurations = [];

// DOM Content Loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeProductionBatches();
});

/**
 * Initialize production batches functionality
 */
function initializeProductionBatches() {
    setupEventListeners();
    setupFormValidation();
    loadBucketConfigurations();
    updateFilterCounts();
}

/**
 * Setup event listeners
 */
function setupEventListeners() {
    // Modal close events
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeNewBatchModal();
            closeStageModal();
            closeCompleteBatchModal();
        }
    });

    // Form submissions
    const newBatchForm = document.getElementById('newBatchForm');
    const stageForm = document.getElementById('stageForm');
    const completeBatchForm = document.getElementById('completeBatchForm');
    
    if (newBatchForm) {
        newBatchForm.addEventListener('submit', handleNewBatchSubmit);
    }
    
    if (stageForm) {
        stageForm.addEventListener('submit', handleStageSubmit);
    }
    
    if (completeBatchForm) {
        completeBatchForm.addEventListener('submit', handleCompleteBatchSubmit);
    }

    // Stage status change
    const stageStatus = document.getElementById('stageStatus');
    if (stageStatus) {
        stageStatus.addEventListener('change', toggleActualYieldField);
    }

    // Set default start date to today
    const plannedStartDate = document.getElementById('plannedStartDate');
    if (plannedStartDate) {
        plannedStartDate.value = new Date().toISOString().split('T')[0];
    }
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
 * Load bucket configurations
 */
function loadBucketConfigurations() {
    const bucketSelect = document.getElementById('bucketConfiguration');
    if (bucketSelect) {
        bucketConfigurations = Array.from(bucketSelect.options)
            .filter(option => option.value)
            .map(option => ({
                id: option.value,
                name: option.textContent,
                capacity: parseFloat(option.dataset.capacity) || 0,
                yield: parseInt(option.dataset.yield) || 0
            }));
    }
}

/**
 * Start new production batch
 */
function startNewBatch() {
    currentBatchId = null;
    document.getElementById('newBatchForm').reset();
    
    // Set default start date
    document.getElementById('plannedStartDate').value = new Date().toISOString().split('T')[0];
    
    clearFormErrors();
    hideBucketPreview();
    showModal('newBatchModal');
}

/**
 * Update stage for batch
 */
function updateStage(batchId) {
    currentBatchId = batchId;
    
    // Get current batch data to determine next stage
    const batchCard = document.querySelector(`[data-batch-id="${batchId}"]`);
    const currentStageElement = batchCard.querySelector('.current-stage strong');
    currentStage = getCurrentStageFromText(currentStageElement.textContent);
    
    document.getElementById('stageBatchId').value = batchId;
    document.getElementById('stageType').value = currentStage;
    document.getElementById('stageModalTitle').textContent = `Update Stage: ${currentStageElement.textContent}`;
    
    // Reset form
    document.getElementById('stageForm').reset();
    document.getElementById('stageBatchId').value = batchId;
    document.getElementById('stageType').value = currentStage;
    
    // Show/hide actual yield field based on stage
    toggleActualYieldField();
    clearFormErrors();
    showModal('stageModal');
}

/**
 * Complete production batch
 */
function completeBatch(batchId) {
    currentBatchId = batchId;
    document.getElementById('completeBatchId').value = batchId;
    document.getElementById('completeBatchForm').reset();
    document.getElementById('completeBatchId').value = batchId;
    
    // Populate completion summary
    updateCompletionSummary(batchId);
    clearFormErrors();
    showModal('completeBatchModal');
}

/**
 * Cancel production batch
 */
function cancelBatch(batchId) {
    if (confirm('Are you sure you want to cancel this production batch? This action cannot be undone.')) {
        fetch(`/admin/injera/production-batches/${batchId}/cancel`, {
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
                refreshBatches();
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Failed to cancel batch', 'error');
        });
    }
}

/**
 * View batch details
 */
function viewBatchDetails(batchId) {
    // In a real app, this would show detailed batch information
    showNotification('Batch details view will be implemented soon', 'info');
}

/**
 * Filter batches by status
 */
function filterBatches(filter) {
    // Update active tab
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    document.querySelector(`[data-filter="${filter}"]`).classList.add('active');
    
    // Filter batch cards
    const batchCards = document.querySelectorAll('.batch-card');
    batchCards.forEach(card => {
        const status = card.dataset.status;
        let show = true;
        
        if (filter !== 'all') {
            show = status === filter;
        }
        
        card.style.display = show ? 'block' : 'none';
    });
    
    updateFilterCounts();
}

/**
 * Update filter tab counts
 */
function updateFilterCounts() {
    const allBatches = document.querySelectorAll('.batch-card').length;
    const activeBatches = document.querySelectorAll('.batch-card[data-status="active"]').length;
    const completedBatches = document.querySelectorAll('.batch-card[data-status="completed"]').length;
    const planningBatches = document.querySelectorAll('.batch-card[data-status="planning"]').length;
    
    // Update tab labels with counts (if you want to show counts)
    // This is optional - you can enable it if desired
}

/**
 * Handle new batch form submission
 */
function handleNewBatchSubmit(e) {
    e.preventDefault();
    
    if (!validateForm('newBatchForm')) {
        return;
    }
    
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());
    
    // Show loading state
    const submitBtn = e.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Starting...';
    submitBtn.disabled = true;
    
    fetch('/admin/injera/production-batches', {
        method: 'POST',
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
            closeNewBatchModal();
            refreshBatches();
        } else {
            showNotification(data.message, 'error');
            if (data.errors) {
                showFormErrors(data.errors);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to start production batch', 'error');
    })
    .finally(() => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
}

/**
 * Handle stage form submission
 */
function handleStageSubmit(e) {
    e.preventDefault();
    
    if (!validateForm('stageForm')) {
        return;
    }
    
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());
    
    // Show loading state
    const submitBtn = e.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Updating...';
    submitBtn.disabled = true;
    
    fetch(`/admin/injera/production-batches/${currentBatchId}/update-stage`, {
        method: 'POST',
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
            closeStageModal();
            refreshBatches();
        } else {
            showNotification(data.message, 'error');
            if (data.errors) {
                showFormErrors(data.errors);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to update stage', 'error');
    })
    .finally(() => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
}

/**
 * Handle complete batch form submission
 */
function handleCompleteBatchSubmit(e) {
    e.preventDefault();
    
    if (!validateForm('completeBatchForm')) {
        return;
    }
    
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());
    
    // Show loading state
    const submitBtn = e.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Completing...';
    submitBtn.disabled = true;
    
    fetch(`/admin/injera/production-batches/${currentBatchId}/complete`, {
        method: 'POST',
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
            closeCompleteBatchModal();
            refreshBatches();
        } else {
            showNotification(data.message, 'error');
            if (data.errors) {
                showFormErrors(data.errors);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to complete batch', 'error');
    })
    .finally(() => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
}

/**
 * Update bucket details when configuration is selected
 */
function updateBucketDetails() {
    const select = document.getElementById('bucketConfiguration');
    const preview = document.getElementById('bucketPreview');
    const detailsDiv = document.getElementById('bucketDetails');
    
    if (select.value) {
        const selectedConfig = bucketConfigurations.find(config => config.id == select.value);
        if (selectedConfig) {
            detailsDiv.innerHTML = `
                <div class="bucket-detail-item">
                    <strong>Capacity:</strong> ${selectedConfig.capacity}L
                </div>
                <div class="bucket-detail-item">
                    <strong>Expected Yield:</strong> ${selectedConfig.yield} injeras
                </div>
            `;
            preview.style.display = 'block';
        }
    } else {
        hideBucketPreview();
    }
}

/**
 * Hide bucket preview
 */
function hideBucketPreview() {
    const preview = document.getElementById('bucketPreview');
    if (preview) {
        preview.style.display = 'none';
    }
}

/**
 * Toggle actual yield field based on stage status
 */
function toggleActualYieldField() {
    const stageStatus = document.getElementById('stageStatus');
    const actualYieldGroup = document.getElementById('actualYieldGroup');
    const actualYieldInput = document.getElementById('actualYield');
    
    if (stageStatus && actualYieldGroup) {
        const showYield = stageStatus.value === 'completed' && 
                         (currentStage === 'baking' || currentStage === 'stage_baking');
        
        actualYieldGroup.style.display = showYield ? 'block' : 'none';
        actualYieldInput.required = showYield;
    }
}

/**
 * Update completion summary
 */
function updateCompletionSummary(batchId) {
    const summaryDiv = document.getElementById('completionSummary');
    const batchCard = document.querySelector(`[data-batch-id="${batchId}"]`);
    
    if (batchCard && summaryDiv) {
        const batchName = batchCard.querySelector('.batch-name').textContent;
        const expectedYield = batchCard.querySelector('.detail-value').textContent;
        
        summaryDiv.innerHTML = `
            <h4>Completion Summary</h4>
            <div class="summary-item">
                <span>Batch:</span>
                <span><strong>${batchName}</strong></span>
            </div>
            <div class="summary-item">
                <span>Expected Yield:</span>
                <span>${expectedYield}</span>
            </div>
        `;
    }
}

/**
 * Get current stage from display text
 */
function getCurrentStageFromText(stageText) {
    const stageMap = {
        'Buy Flour': 'buy_flour',
        'Mixing': 'mixing',
        'Fermentation': 'fermentation',
        'Add Hot Water': 'hot_water',
        'Baking': 'baking'
    };
    
    return stageMap[stageText] || 'buy_flour';
}

/**
 * Validate form
 */
function validateForm(formId) {
    const form = document.getElementById(formId);
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!validateField(field)) {
            isValid = false;
        }
    });
    
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
    
    // Date validation
    if (field.type === 'date' && value) {
        const selectedDate = new Date(value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (selectedDate < today) {
            isValid = false;
            errorMessage = 'Date cannot be in the past';
        }
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
 * Close new batch modal
 */
function closeNewBatchModal() {
    const modal = document.getElementById('newBatchModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
        clearFormErrors();
    }
}

/**
 * Close stage modal
 */
function closeStageModal() {
    const modal = document.getElementById('stageModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
        clearFormErrors();
    }
}

/**
 * Close complete batch modal
 */
function closeCompleteBatchModal() {
    const modal = document.getElementById('completeBatchModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
        clearFormErrors();
    }
}

/**
 * Export batches
 */
function exportBatches() {
    showNotification('Export functionality will be implemented soon', 'info');
}

/**
 * Refresh batches
 */
function refreshBatches() {
    location.reload();
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
if (!document.querySelector('#batch-notification-styles')) {
    const style = document.createElement('style');
    style.id = 'batch-notification-styles';
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
        .bucket-detail-item {
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--color-border);
            font-size: 0.9rem;
        }
        .bucket-detail-item:last-child {
            border-bottom: none;
        }
    `;
    document.head.appendChild(style);
}
