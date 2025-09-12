/**
 * Orders & Allocation Page JavaScript
 * Handles order management, allocation, and interactive functionality
 */

// Global variables
let currentOrderId = null;
let currentFilter = '';
let allocationTotal = 0;

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    initializePage();
    setupEventListeners();
});

/**
 * Initialize the page
 */
function initializePage() {
    // Set up form validation
    setupFormValidation();
    
    // Initialize tooltips
    initializeTooltips();
    
    // Set default delivery date to tomorrow
    setDefaultDeliveryDate();
}

/**
 * Setup event listeners
 */
function setupEventListeners() {
    // Search functionality
    const searchInput = document.getElementById('orderSearch');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(searchOrders, 300));
    }

    // Filter functionality
    const priorityFilter = document.getElementById('priorityFilter');
    const typeFilter = document.getElementById('typeFilter');
    
    if (priorityFilter) {
        priorityFilter.addEventListener('change', filterOrders);
    }
    
    if (typeFilter) {
        typeFilter.addEventListener('change', filterOrders);
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

    // Allocation quantity inputs
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('allocation-quantity')) {
            updateAllocationTotal();
        }
    });
}

/**
 * Set default delivery date
 */
function setDefaultDeliveryDate() {
    const deliveryDateInput = document.getElementById('deliveryDate');
    if (deliveryDateInput) {
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        deliveryDateInput.value = tomorrow.toISOString().split('T')[0];
    }
}

/**
 * Toggle delivery fields based on order type
 */
function toggleDeliveryFields() {
    const orderType = document.getElementById('orderType').value;
    const deliveryFields = document.querySelectorAll('.delivery-only');
    
    deliveryFields.forEach(field => {
        if (orderType === 'delivery') {
            field.style.display = 'block';
            const textarea = field.querySelector('textarea');
            if (textarea) {
                textarea.required = true;
            }
        } else {
            field.style.display = 'none';
            const textarea = field.querySelector('textarea');
            if (textarea) {
                textarea.required = false;
            }
        }
    });
}

/**
 * Filter orders by status
 */
function filterByStatus(status) {
    currentFilter = status;
    
    // Update tab states
    document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Find and activate the correct tab
    document.querySelectorAll('.filter-tab').forEach(tab => {
        const tabText = tab.textContent.toLowerCase();
        if ((status === '' && tabText.includes('all')) || 
            (status !== '' && tabText.includes(status))) {
            tab.classList.add('active');
        }
    });
    
    // Filter order cards
    filterOrders();
}

/**
 * Search orders
 */
function searchOrders() {
    const searchTerm = document.getElementById('orderSearch').value.toLowerCase();
    const orders = document.querySelectorAll('.order-card');
    
    orders.forEach(order => {
        const orderNumber = order.querySelector('.order-number').textContent.toLowerCase();
        const customerName = order.querySelector('.customer-name').textContent.toLowerCase();
        const phone = order.querySelector('.phone')?.textContent.toLowerCase() || '';
        const email = order.querySelector('.email')?.textContent.toLowerCase() || '';
        
        const matches = orderNumber.includes(searchTerm) || 
                       customerName.includes(searchTerm) || 
                       phone.includes(searchTerm) ||
                       email.includes(searchTerm);
        
        order.style.display = matches ? '' : 'none';
    });
}

/**
 * Filter orders
 */
function filterOrders() {
    const priorityFilter = document.getElementById('priorityFilter').value;
    const typeFilter = document.getElementById('typeFilter').value;
    const orders = document.querySelectorAll('.order-card');
    
    orders.forEach(order => {
        const status = order.dataset.status;
        const priority = order.dataset.priority;
        const type = order.dataset.type;
        
        const statusMatch = !currentFilter || status === currentFilter;
        const priorityMatch = !priorityFilter || priority === priorityFilter;
        const typeMatch = !typeFilter || type === typeFilter;
        
        order.style.display = (statusMatch && priorityMatch && typeMatch) ? '' : 'none';
    });
}

/**
 * Clear all filters
 */
function clearFilters() {
    document.getElementById('priorityFilter').value = '';
    document.getElementById('typeFilter').value = '';
    document.getElementById('orderSearch').value = '';
    
    filterByStatus('');
}

/**
 * Open new order modal
 */
function openNewOrderModal() {
    const modal = document.getElementById('newOrderModal');
    if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
        setDefaultDeliveryDate();
    }
}

/**
 * Close new order modal
 */
function closeNewOrderModal() {
    const modal = document.getElementById('newOrderModal');
    if (modal) {
        modal.classList.remove('show');
        document.body.style.overflow = '';
        resetForm('newOrderForm');
    }
}

/**
 * Create new order
 */
function createOrder(event) {
    event.preventDefault();
    
    const form = event.target;
    if (!validateForm(form)) return;
    
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    
    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Creating...';
    submitBtn.disabled = true;
    
    // Simulate API call
    setTimeout(() => {
        showNotification('Order created successfully!', 'success');
        closeNewOrderModal();
        
        // Reset button
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
        
        // Refresh page data (in real implementation)
        refreshData();
    }, 1000);
}

/**
 * Open allocation modal
 */
function openAllocationModal(orderId) {
    currentOrderId = orderId;
    const modal = document.getElementById('allocationModal');
    
    if (modal) {
        // Find order data
        const orderCard = document.querySelector(`[data-order-id="${orderId}"]`);
        if (orderCard) {
            const orderNumber = orderCard.querySelector('.order-number').textContent;
            const quantity = orderCard.querySelector('.detail-value').textContent;
            
            document.getElementById('allocationOrderNumber').textContent = orderNumber;
            document.getElementById('allocationNeeded').textContent = quantity;
            document.getElementById('allocationAllocated').textContent = '0';
        }
        
        // Reset allocation inputs
        document.querySelectorAll('.allocation-quantity').forEach(input => {
            input.value = '0';
        });
        
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

/**
 * Close allocation modal
 */
function closeAllocationModal() {
    const modal = document.getElementById('allocationModal');
    if (modal) {
        modal.classList.remove('show');
        document.body.style.overflow = '';
        currentOrderId = null;
        allocationTotal = 0;
    }
}

/**
 * Update allocation total
 */
function updateAllocationTotal() {
    const inputs = document.querySelectorAll('.allocation-quantity');
    let total = 0;
    
    inputs.forEach(input => {
        total += parseInt(input.value) || 0;
    });
    
    allocationTotal = total;
    document.getElementById('allocationAllocated').textContent = total + ' pieces';
}

/**
 * Save allocation
 */
function saveAllocation() {
    if (!currentOrderId) return;
    
    const allocations = [];
    document.querySelectorAll('.stock-item').forEach(item => {
        const stockId = item.dataset.stockId;
        const quantity = parseInt(item.querySelector('.allocation-quantity').value) || 0;
        
        if (quantity > 0) {
            allocations.push({
                stock_id: stockId,
                quantity: quantity
            });
        }
    });
    
    if (allocations.length === 0) {
        showNotification('Please allocate at least one injera', 'warning');
        return;
    }
    
    showNotification('Allocating injera...', 'info');
    
    // Simulate API call
    setTimeout(() => {
        showNotification('Injera allocated successfully!', 'success');
        closeAllocationModal();
        refreshData();
    }, 1000);
}

/**
 * Open status modal
 */
function openStatusModal(orderId) {
    currentOrderId = orderId;
    const modal = document.getElementById('statusModal');
    
    if (modal) {
        // Find current status
        const orderCard = document.querySelector(`[data-order-id="${orderId}"]`);
        if (orderCard) {
            const currentStatus = orderCard.dataset.status;
            document.getElementById('orderStatus').value = currentStatus;
        }
        
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

/**
 * Close status modal
 */
function closeStatusModal() {
    const modal = document.getElementById('statusModal');
    if (modal) {
        modal.classList.remove('show');
        document.body.style.overflow = '';
        resetForm('statusForm');
        currentOrderId = null;
    }
}

/**
 * Update order status
 */
function updateOrderStatus(event) {
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
        showNotification('Order status updated successfully!', 'success');
        closeStatusModal();
        
        // Reset button
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
        
        refreshData();
    }, 1000);
}

/**
 * Quick status update
 */
function quickStatusUpdate(orderId, newStatus) {
    showNotification(`Updating order to ${newStatus}...`, 'info');
    
    // Simulate API call
    setTimeout(() => {
        showNotification('Order status updated successfully!', 'success');
        refreshData();
    }, 1000);
}

/**
 * Open cancel modal
 */
function openCancelModal(orderId) {
    currentOrderId = orderId;
    const modal = document.getElementById('cancelModal');
    
    if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

/**
 * Close cancel modal
 */
function closeCancelModal() {
    const modal = document.getElementById('cancelModal');
    if (modal) {
        modal.classList.remove('show');
        document.body.style.overflow = '';
        resetForm('cancelForm');
        currentOrderId = null;
    }
}

/**
 * Cancel order
 */
function cancelOrder(event) {
    event.preventDefault();
    
    const form = event.target;
    if (!validateForm(form)) return;
    
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    
    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Cancelling...';
    submitBtn.disabled = true;
    
    // Simulate API call
    setTimeout(() => {
        showNotification('Order cancelled successfully!', 'success');
        closeCancelModal();
        
        // Reset button
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
        
        refreshData();
    }, 1000);
}

/**
 * Edit order
 */
function editOrder(orderId) {
    // Find order data and populate edit form
    const orderCard = document.querySelector(`[data-order-id="${orderId}"]`);
    if (orderCard) {
        showNotification('Opening order editor...', 'info');
        // In real implementation, this would open an edit modal with pre-filled data
    }
}

/**
 * Export orders
 */
function exportOrders() {
    showNotification('Preparing orders export...', 'info');
    
    // Simulate export process
    setTimeout(() => {
        showNotification('Orders exported successfully!', 'success');
    }, 2000);
}

/**
 * Refresh data
 */
function refreshData() {
    showNotification('Refreshing order data...', 'info');
    
    // Simulate refresh
    setTimeout(() => {
        showNotification('Data refreshed successfully!', 'success');
        // In real implementation, this would reload the page data
    }, 1000);
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
    const phoneField = form.querySelector('input[name="customer_phone"]');
    if (phoneField && phoneField.value) {
        const phonePattern = /^[\+]?[0-9\-\(\)\s]+$/;
        if (!phonePattern.test(phoneField.value)) {
            showFieldError(phoneField, 'Please enter a valid phone number');
            isValid = false;
        }
    }
    
    const quantityField = form.querySelector('input[name="quantity"]');
    if (quantityField && quantityField.value) {
        const quantity = parseInt(quantityField.value);
        if (quantity < 1) {
            showFieldError(quantityField, 'Quantity must be at least 1');
            isValid = false;
        }
    }
    
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
