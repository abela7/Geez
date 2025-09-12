/**
 * Flour Management JavaScript
 * Handles interactive functionality for the flour management page
 */

// Global variables
let currentFlourId = null;
let sortColumn = 'name';
let sortDirection = 'asc';

// DOM Content Loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeFlourManagement();
});

/**
 * Initialize flour management functionality
 */
function initializeFlourManagement() {
    setupEventListeners();
    setupTableSorting();
    setupFormValidation();
}

/**
 * Setup event listeners
 */
function setupEventListeners() {
    // Search functionality
    const searchInput = document.getElementById('flourSearch');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                filterFlours();
            }, 300);
        });
    }

    // Filter dropdowns
    const typeFilter = document.getElementById('flourTypeFilter');
    const statusFilter = document.getElementById('stockStatusFilter');
    
    if (typeFilter) {
        typeFilter.addEventListener('change', filterFlours);
    }
    
    if (statusFilter) {
        statusFilter.addEventListener('change', filterFlours);
    }

    // Modal close events
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeFlourModal();
            closeStockModal();
        }
    });

    // Form submissions
    const flourForm = document.getElementById('flourForm');
    const stockForm = document.getElementById('stockForm');
    
    if (flourForm) {
        flourForm.addEventListener('submit', handleFlourSubmit);
    }
    
    if (stockForm) {
        stockForm.addEventListener('submit', handleStockSubmit);
    }

    // Auto-calculate price per kg
    const packageSizeInput = document.getElementById('packageSize');
    const pricePerPackageInput = document.getElementById('pricePerPackage');
    
    if (packageSizeInput && pricePerPackageInput) {
        [packageSizeInput, pricePerPackageInput].forEach(input => {
            input.addEventListener('input', calculatePricePerKg);
        });
    }
}

/**
 * Setup table sorting
 */
function setupTableSorting() {
    const sortableHeaders = document.querySelectorAll('.data-table th.sortable');
    
    sortableHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const column = this.dataset.sort;
            
            if (sortColumn === column) {
                sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                sortColumn = column;
                sortDirection = 'asc';
            }
            
            sortTable(column, sortDirection);
            updateSortIcons(this);
        });
    });
}

/**
 * Setup form validation
 */
function setupFormValidation() {
    // Real-time validation for required fields
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
 * Add new flour
 */
function addFlour() {
    currentFlourId = null;
    document.getElementById('modalTitle').textContent = 'Add New Flour';
    document.getElementById('flourForm').reset();
    clearFormErrors();
    showModal('flourModal');
}

/**
 * Edit existing flour
 */
function editFlour(flourId) {
    currentFlourId = flourId;
    document.getElementById('modalTitle').textContent = 'Edit Flour';
    
    // In a real app, fetch flour data from server
    // For now, we'll simulate with mock data
    const mockFlourData = {
        name: 'Premium Teff Flour',
        type: 'Teff',
        package_size: 25.0,
        price_per_package: 45.00,
        supplier_name: 'Ethiopian Import Co.',
        current_stock: 150.0,
        notes: 'High quality, dark teff'
    };
    
    populateFlourForm(mockFlourData);
    showModal('flourModal');
}

/**
 * Update stock for flour
 */
function updateStock(flourId) {
    currentFlourId = flourId;
    document.getElementById('stockFlourId').value = flourId;
    document.getElementById('stockForm').reset();
    document.getElementById('stockFlourId').value = flourId; // Reset removes this, so set again
    clearFormErrors();
    showModal('stockModal');
}

/**
 * Delete flour
 */
function deleteFlour(flourId) {
    if (confirm('Are you sure you want to delete this flour? This action cannot be undone.')) {
        // In a real app, send delete request to server
        fetch(`/admin/injera/flour-management/${flourId}`, {
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
                refreshData();
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Failed to delete flour', 'error');
        });
    }
}

/**
 * Handle flour form submission
 */
function handleFlourSubmit(e) {
    e.preventDefault();
    
    if (!validateForm('flourForm')) {
        return;
    }
    
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());
    
    const url = currentFlourId 
        ? `/admin/injera/flour-management/${currentFlourId}` 
        : '/admin/injera/flour-management';
    
    const method = currentFlourId ? 'PUT' : 'POST';
    
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
            closeFlourModal();
            refreshData();
        } else {
            showNotification(data.message, 'error');
            if (data.errors) {
                showFormErrors(data.errors);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to save flour', 'error');
    })
    .finally(() => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
}

/**
 * Handle stock form submission
 */
function handleStockSubmit(e) {
    e.preventDefault();
    
    if (!validateForm('stockForm')) {
        return;
    }
    
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());
    
    // Show loading state
    const submitBtn = e.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Updating...';
    submitBtn.disabled = true;
    
    fetch('/admin/injera/flour-management/update-stock', {
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
            closeStockModal();
            refreshData();
        } else {
            showNotification(data.message, 'error');
            if (data.errors) {
                showFormErrors(data.errors);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to update stock', 'error');
    })
    .finally(() => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
}

/**
 * Filter flours based on search and filters
 */
function filterFlours() {
    const search = document.getElementById('flourSearch').value.toLowerCase();
    const typeFilter = document.getElementById('flourTypeFilter').value;
    const statusFilter = document.getElementById('stockStatusFilter').value;
    
    const rows = document.querySelectorAll('.table-row');
    
    rows.forEach(row => {
        const flourName = row.querySelector('.flour-title').textContent.toLowerCase();
        const flourType = row.querySelector('.flour-type-badge').textContent.trim();
        const flourStatus = row.querySelector('.status-badge').classList.contains('status-low_stock') ? 'low_stock' : 'in_stock';
        
        let show = true;
        
        // Search filter
        if (search && !flourName.includes(search)) {
            show = false;
        }
        
        // Type filter
        if (typeFilter && flourType !== typeFilter) {
            show = false;
        }
        
        // Status filter
        if (statusFilter && flourStatus !== statusFilter) {
            show = false;
        }
        
        row.style.display = show ? '' : 'none';
    });
}

/**
 * Clear all filters
 */
function clearFilters() {
    document.getElementById('flourSearch').value = '';
    document.getElementById('flourTypeFilter').value = '';
    document.getElementById('stockStatusFilter').value = '';
    
    // Show all rows
    document.querySelectorAll('.table-row').forEach(row => {
        row.style.display = '';
    });
}

/**
 * Export flour data
 */
function exportFlours() {
    // In a real app, this would generate and download a file
    showNotification('Export functionality will be implemented soon', 'info');
}

/**
 * Refresh data
 */
function refreshData() {
    // In a real app, this would reload the page or fetch fresh data
    location.reload();
}

/**
 * Calculate price per kg automatically
 */
function calculatePricePerKg() {
    const packageSize = parseFloat(document.getElementById('packageSize').value) || 0;
    const pricePerPackage = parseFloat(document.getElementById('pricePerPackage').value) || 0;
    
    if (packageSize > 0 && pricePerPackage > 0) {
        const pricePerKg = pricePerPackage / packageSize;
        
        // Show calculated price (you could add a display element for this)
        console.log(`Price per KG: $${pricePerKg.toFixed(2)}`);
    }
}

/**
 * Sort table by column
 */
function sortTable(column, direction) {
    const tbody = document.querySelector('.data-table tbody');
    const rows = Array.from(tbody.querySelectorAll('.table-row'));
    
    rows.sort((a, b) => {
        let aVal, bVal;
        
        switch (column) {
            case 'name':
                aVal = a.querySelector('.flour-title').textContent.toLowerCase();
                bVal = b.querySelector('.flour-title').textContent.toLowerCase();
                break;
            case 'current_stock':
                aVal = parseFloat(a.querySelector('.stock-value').textContent.replace(/[^\d.-]/g, ''));
                bVal = parseFloat(b.querySelector('.stock-value').textContent.replace(/[^\d.-]/g, ''));
                break;
            case 'price_per_kg':
                aVal = parseFloat(a.querySelector('.price-primary').textContent.replace(/[^\d.-]/g, ''));
                bVal = parseFloat(b.querySelector('.price-primary').textContent.replace(/[^\d.-]/g, ''));
                break;
            default:
                return 0;
        }
        
        if (direction === 'asc') {
            return aVal > bVal ? 1 : -1;
        } else {
            return aVal < bVal ? 1 : -1;
        }
    });
    
    // Re-append sorted rows
    rows.forEach(row => tbody.appendChild(row));
}

/**
 * Update sort icons
 */
function updateSortIcons(activeHeader) {
    // Reset all sort icons
    document.querySelectorAll('.sort-icon').forEach(icon => {
        icon.style.opacity = '0.5';
    });
    
    // Highlight active sort icon
    const activeIcon = activeHeader.querySelector('.sort-icon');
    if (activeIcon) {
        activeIcon.style.opacity = '1';
    }
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
 * Close flour modal
 */
function closeFlourModal() {
    const modal = document.getElementById('flourModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
        clearFormErrors();
    }
}

/**
 * Close stock modal
 */
function closeStockModal() {
    const modal = document.getElementById('stockModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
        clearFormErrors();
    }
}

/**
 * Populate flour form with data
 */
function populateFlourForm(data) {
    document.getElementById('flourName').value = data.name || '';
    document.getElementById('flourType').value = data.type || '';
    document.getElementById('packageSize').value = data.package_size || '';
    document.getElementById('pricePerPackage').value = data.price_per_package || '';
    document.getElementById('supplierName').value = data.supplier_name || '';
    document.getElementById('currentStock').value = data.current_stock || '';
    document.getElementById('flourNotes').value = data.notes || '';
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
 * Show notification
 */
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Show with animation
    setTimeout(() => notification.classList.add('show'), 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

// Add notification styles if not already present
if (!document.querySelector('#notification-styles')) {
    const style = document.createElement('style');
    style.id = 'notification-styles';
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
