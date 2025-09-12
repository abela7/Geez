/**
 * Injera Stock Levels Page JavaScript
 * Handles all interactive functionality for stock management
 */

// Global variables
let currentStockId = null;
let stockData = [];

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    initializePage();
    setupEventListeners();
});

/**
 * Initialize the page
 */
function initializePage() {
    // Load stock data from the table
    loadStockData();
    
    // Set up form validation
    setupFormValidation();
    
    // Initialize tooltips
    initializeTooltips();
}

/**
 * Load stock data from the table
 */
function loadStockData() {
    const rows = document.querySelectorAll('.table-row');
    stockData = Array.from(rows).map(row => ({
        id: row.dataset.stockId || null,
        quality: row.dataset.quality,
        status: row.dataset.status,
        currentStock: parseInt(row.querySelector('.stock-value').textContent),
        element: row
    }));
}

/**
 * Setup event listeners
 */
function setupEventListeners() {
    // Search functionality
    const searchInput = document.getElementById('stockSearch');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(searchStock, 300));
    }

    // Filter functionality
    const qualityFilter = document.getElementById('qualityFilter');
    const statusFilter = document.getElementById('statusFilter');
    
    if (qualityFilter) {
        qualityFilter.addEventListener('change', filterStock);
    }
    
    if (statusFilter) {
        statusFilter.addEventListener('change', filterStock);
    }

    // Modal close on outside click
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
            closeAllModals();
        }
    });

    // Modal close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllModals();
        }
    });
}

/**
 * Setup form validation
 */
function setupFormValidation() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(form)) {
                e.preventDefault();
            }
        });
    });
}

/**
 * Initialize tooltips
 */
function initializeTooltips() {
    const tooltipElements = document.querySelectorAll('[title]');
    tooltipElements.forEach(element => {
        element.addEventListener('mouseenter', showTooltip);
        element.addEventListener('mouseleave', hideTooltip);
    });
}

/**
 * Show tooltip
 */
function showTooltip(e) {
    const element = e.target;
    const title = element.getAttribute('title');
    
    if (!title) return;
    
    // Remove title to prevent default tooltip
    element.removeAttribute('title');
    element.setAttribute('data-original-title', title);
    
    // Create tooltip element
    const tooltip = document.createElement('div');
    tooltip.className = 'custom-tooltip';
    tooltip.textContent = title;
    tooltip.style.cssText = `
        position: absolute;
        background: #1a1a1a;
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 12px;
        z-index: 1000;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.2s ease;
    `;
    
    document.body.appendChild(tooltip);
    
    // Position tooltip
    const rect = element.getBoundingClientRect();
    tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
    tooltip.style.top = rect.top - tooltip.offsetHeight - 8 + 'px';
    
    // Show tooltip
    setTimeout(() => {
        tooltip.style.opacity = '1';
    }, 10);
    
    element.tooltip = tooltip;
}

/**
 * Hide tooltip
 */
function hideTooltip(e) {
    const element = e.target;
    const originalTitle = element.getAttribute('data-original-title');
    
    if (originalTitle) {
        element.setAttribute('title', originalTitle);
        element.removeAttribute('data-original-title');
    }
    
    if (element.tooltip) {
        element.tooltip.remove();
        element.tooltip = null;
    }
}

/**
 * Search stock function
 */
function searchStock() {
    const searchTerm = document.getElementById('stockSearch').value.toLowerCase();
    const rows = document.querySelectorAll('.table-row');
    
    rows.forEach(row => {
        const batchNumber = row.querySelector('.batch-title').textContent.toLowerCase();
        const storageLocation = row.querySelector('.location-name').textContent.toLowerCase();
        const notes = row.querySelector('.batch-notes')?.textContent.toLowerCase() || '';
        
        const matches = batchNumber.includes(searchTerm) || 
                       storageLocation.includes(searchTerm) || 
                       notes.includes(searchTerm);
        
        row.style.display = matches ? '' : 'none';
    });
}

/**
 * Filter stock function
 */
function filterStock() {
    const qualityFilter = document.getElementById('qualityFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('.table-row');
    
    rows.forEach(row => {
        const quality = row.dataset.quality;
        const status = row.dataset.status;
        
        const qualityMatch = !qualityFilter || quality === qualityFilter;
        const statusMatch = !statusFilter || status === statusFilter;
        
        row.style.display = (qualityMatch && statusMatch) ? '' : 'none';
    });
}

/**
 * Clear all filters
 */
function clearFilters() {
    document.getElementById('qualityFilter').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('stockSearch').value = '';
    
    const rows = document.querySelectorAll('.table-row');
    rows.forEach(row => {
        row.style.display = '';
    });
}

/**
 * Refresh data
 */
function refreshData() {
    showNotification('Refreshing stock data...', 'info');
    
    // Simulate refresh
    setTimeout(() => {
        showNotification('Stock data refreshed successfully!', 'success');
        loadStockData();
    }, 1000);
}

/**
 * Open add stock modal
 */
function openAddStockModal() {
    const modal = document.getElementById('addStockModal');
    if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
        
        // Set default expiry date to 7 days from now
        const expiryDateInput = document.getElementById('expiryDate');
        if (expiryDateInput) {
            const futureDate = new Date();
            futureDate.setDate(futureDate.getDate() + 7);
            expiryDateInput.value = futureDate.toISOString().split('T')[0];
        }
    }
}

/**
 * Close add stock modal
 */
function closeAddStockModal() {
    const modal = document.getElementById('addStockModal');
    if (modal) {
        modal.classList.remove('show');
        document.body.style.overflow = '';
        resetForm('addStockForm');
    }
}

/**
 * Open update stock modal
 */
function updateStock(stockId) {
    currentStockId = stockId;
    const modal = document.getElementById('updateStockModal');
    
    if (modal) {
        // Find the stock data
        const stockRow = document.querySelector(`[data-stock-id="${stockId}"]`);
        if (stockRow) {
            const currentStock = stockRow.querySelector('.quantity-value').textContent;
            const storageLocation = stockRow.querySelector('.storage-location').textContent;
            
            // Populate form
            document.getElementById('updateCurrentStock').value = currentStock;
            document.getElementById('updateReservedStock').value = '0'; // Default value
            document.getElementById('updateAvailableStock').value = currentStock;
            document.getElementById('updateStorageLocation').value = storageLocation;
        }
        
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

/**
 * Close update stock modal
 */
function closeUpdateStockModal() {
    const modal = document.getElementById('updateStockModal');
    if (modal) {
        modal.classList.remove('show');
        document.body.style.overflow = '';
        resetForm('updateStockForm');
        currentStockId = null;
    }
}

/**
 * Open reserve stock modal
 */
function reserveStock(stockId) {
    currentStockId = stockId;
    const modal = document.getElementById('reserveStockModal');
    
    if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

/**
 * Close reserve stock modal
 */
function closeReserveStockModal() {
    const modal = document.getElementById('reserveStockModal');
    if (modal) {
        modal.classList.remove('show');
        document.body.style.overflow = '';
        resetForm('reserveStockForm');
        currentStockId = null;
    }
}

/**
 * Close all modals
 */
function closeAllModals() {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.classList.remove('show');
    });
    document.body.style.overflow = '';
}

/**
 * Add stock form submission
 */
function addStock(event) {
    event.preventDefault();
    
    const form = event.target;
    if (!validateForm(form)) return;
    
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    
    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Adding...';
    submitBtn.disabled = true;
    
    // Simulate API call
    setTimeout(() => {
        showNotification('Stock added successfully!', 'success');
        closeAddStockModal();
        resetForm('addStockForm');
        
        // Reset button
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    }, 1000);
}

/**
 * Update stock form submission
 */
function updateStockSubmit(event) {
    event.preventDefault();
    
    const form = event.target;
    if (!validateForm(form)) return;
    
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    
    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Updating...';
    submitBtn.disabled = true;
    
    // Simulate API call
    setTimeout(() => {
        showNotification('Stock updated successfully!', 'success');
        closeUpdateStockModal();
        resetForm('updateStockForm');
        
        // Reset button
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    }, 1000);
}

/**
 * Reserve stock form submission
 */
function reserveStockSubmit(event) {
    event.preventDefault();
    
    const form = event.target;
    if (!validateForm(form)) return;
    
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    
    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Reserving...';
    submitBtn.disabled = true;
    
    // Simulate API call
    setTimeout(() => {
        showNotification('Stock reserved successfully!', 'success');
        closeReserveStockModal();
        resetForm('reserveStockForm');
        
        // Reset button
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    }, 1000);
}

/**
 * View stock details
 */
function viewStockDetails(stockId) {
    // Find the stock data
    const stockRow = document.querySelector(`[data-stock-id="${stockId}"]`);
    if (stockRow) {
        const batchNumber = stockRow.querySelector('.batch-number').textContent;
        const quality = stockRow.dataset.quality;
        const currentStock = stockRow.querySelector('.quantity-value').textContent;
        const storageLocation = stockRow.querySelector('.storage-location').textContent;
        
        // Create details modal content
        const details = `
            <div class="stock-details">
                <h3>Stock Details</h3>
                <div class="detail-row">
                    <span class="detail-label">Batch Number:</span>
                    <span class="detail-value">${batchNumber}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Quality Grade:</span>
                    <span class="detail-value">${quality}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Current Stock:</span>
                    <span class="detail-value">${currentStock} pieces</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Storage Location:</span>
                    <span class="detail-value">${storageLocation}</span>
                </div>
            </div>
        `;
        
        showModal('Stock Details', details);
    }
}

/**
 * Export stock levels
 */
function exportStockLevels() {
    showNotification('Exporting stock levels...', 'info');
    
    // Simulate export process
    setTimeout(() => {
        showNotification('Stock levels exported successfully!', 'success');
    }, 2000);
}

/**
 * Validate form
 */
function validateForm(form) {
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            showFieldError(field, 'This field is required');
            isValid = false;
        } else {
            clearFieldError(field);
        }
    });
    
    // Additional validation
    const quantityFields = form.querySelectorAll('input[type="number"][min]');
    quantityFields.forEach(field => {
        const min = parseInt(field.getAttribute('min'));
        const value = parseInt(field.value);
        
        if (value < min) {
            showFieldError(field, `Value must be at least ${min}`);
            isValid = false;
        }
    });
    
    return isValid;
}

/**
 * Show field error
 */
function showFieldError(field, message) {
    clearFieldError(field);
    
    field.style.borderColor = '#DC2626';
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.textContent = message;
    errorDiv.style.cssText = `
        color: #DC2626;
        font-size: 12px;
        margin-top: 4px;
    `;
    
    field.parentNode.appendChild(errorDiv);
}

/**
 * Clear field error
 */
function clearFieldError(field) {
    field.style.borderColor = '';
    
    const errorDiv = field.parentNode.querySelector('.field-error');
    if (errorDiv) {
        errorDiv.remove();
    }
}

/**
 * Reset form
 */
function resetForm(formId) {
    const form = document.getElementById(formId);
    if (form) {
        form.reset();
        
        // Clear all field errors
        const errorDivs = form.querySelectorAll('.field-error');
        errorDivs.forEach(div => div.remove());
        
        // Reset field styles
        const fields = form.querySelectorAll('input, select, textarea');
        fields.forEach(field => {
            field.style.borderColor = '';
        });
    }
}

/**
 * Show notification
 */
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 20px;
        border-radius: 6px;
        color: white;
        font-weight: 500;
        z-index: 10000;
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease;
    `;
    
    // Set background color based on type
    const colors = {
        success: '#059669',
        error: '#DC2626',
        warning: '#D97706',
        info: '#2563EB'
    };
    
    notification.style.backgroundColor = colors[type] || colors.info;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateX(0)';
    }, 10);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

/**
 * Show modal
 */
function showModal(title, content) {
    const modal = document.createElement('div');
    modal.className = 'modal show';
    modal.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h3>${title}</h3>
                <button class="modal-close" onclick="this.closest('.modal').remove()">&times;</button>
            </div>
            <div class="modal-body">
                ${content}
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="this.closest('.modal').remove()">Close</button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    document.body.style.overflow = 'hidden';
}

/**
 * Debounce function
 */
function debounce(func, wait) {
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
