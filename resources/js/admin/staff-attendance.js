/**
 * STAFF ATTENDANCE - SECTION-SPECIFIC JAVASCRIPT
 * Handles filtering, table interactions, bulk actions, and modal management
 */

/* ==========================================================================
   1. INITIALIZATION & SETUP
   ========================================================================== */

document.addEventListener('DOMContentLoaded', function() {
    initializeFilters();
    initializeTable();
    initializeBulkActions();
    initializeModal();
    initializeViewToggle();
    initializeInlineEditing();
    initializePagination();
    initializeKeyboardNavigation();
    initializeDrawer();
});

/* ==========================================================================
   2. FILTER MANAGEMENT
   ========================================================================== */

/**
 * Initialize all filter functionality
 */
function initializeFilters() {
    const dateRangeSelect = document.getElementById('dateRangeSelect');
    const customDateInputs = document.getElementById('customDateInputs');
    const staffFilter = document.getElementById('staffFilter');
    const statusFilter = document.getElementById('statusFilter');
    const searchInput = document.getElementById('searchInput');
    const clearFiltersBtn = document.getElementById('clearFiltersBtn');

    // Date range filter
    if (dateRangeSelect) {
        dateRangeSelect.addEventListener('change', function() {
            const isCustom = this.value === 'custom';
            if (customDateInputs) {
                customDateInputs.style.display = isCustom ? 'flex' : 'none';
            }
            applyFilters();
        });
    }

    // Custom date inputs
    const startDate = document.getElementById('startDate');
    const endDate = document.getElementById('endDate');
    if (startDate && endDate) {
        startDate.addEventListener('change', applyFilters);
        endDate.addEventListener('change', applyFilters);
    }

    // Other filters
    if (staffFilter) staffFilter.addEventListener('change', applyFilters);
    if (statusFilter) statusFilter.addEventListener('change', applyFilters);
    if (searchInput) {
        searchInput.addEventListener('input', debounce(applyFilters, 300));
    }

    // Clear filters
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', clearAllFilters);
    }
}

/**
 * Apply all active filters
 */
function applyFilters() {
    const filters = getActiveFilters();
    filterAttendanceRecords(filters);
    updateURL(filters);
}

/**
 * Get current filter values
 */
function getActiveFilters() {
    const dateRange = document.getElementById('dateRangeSelect')?.value || '';
    const startDate = document.getElementById('startDate')?.value || '';
    const endDate = document.getElementById('endDate')?.value || '';
    const staff = document.getElementById('staffFilter')?.value || '';
    const status = document.getElementById('statusFilter')?.value || '';
    const search = document.getElementById('searchInput')?.value || '';

    return {
        dateRange,
        startDate,
        endDate,
        staff,
        status,
        search: search.toLowerCase()
    };
}

/**
 * Filter attendance records based on criteria
 */
function filterAttendanceRecords(filters) {
    const tableRows = document.querySelectorAll('.attendance-row');
    const cards = document.querySelectorAll('.attendance-card');
    let visibleCount = 0;

    // Filter table rows
    tableRows.forEach(row => {
        const isVisible = shouldShowRecord(row, filters);
        row.style.display = isVisible ? '' : 'none';
        if (isVisible) visibleCount++;
    });

    // Filter cards
    cards.forEach(card => {
        const isVisible = shouldShowRecord(card, filters);
        card.style.display = isVisible ? '' : 'none';
    });

    // Update UI based on results
    updateFilterResults(visibleCount);
}

/**
 * Check if a record should be visible based on filters
 */
function shouldShowRecord(element, filters) {
    const staffName = element.querySelector('.staff-name')?.textContent.toLowerCase() || '';
    const staffId = element.querySelector('.staff-id')?.textContent.toLowerCase() || '';
    const role = element.querySelector('.role-name')?.textContent.toLowerCase() || '';
    const department = element.querySelector('.department-name')?.textContent.toLowerCase() || '';
    const status = element.querySelector('.status-badge')?.textContent.toLowerCase().trim() || '';

    // Search filter
    if (filters.search) {
        const searchMatch = staffName.includes(filters.search) || 
                           staffId.includes(filters.search) ||
                           role.includes(filters.search);
        if (!searchMatch) return false;
    }

    // Staff filter (by department)
    if (filters.staff) {
        if (!department.includes(filters.staff)) return false;
    }

    // Status filter
    if (filters.status) {
        if (!status.includes(filters.status)) return false;
    }

    // Date range filter would be handled by backend in real app
    // For now, we'll show all records

    return true;
}

/**
 * Update UI based on filter results
 */
function updateFilterResults(visibleCount) {
    const emptyState = document.getElementById('emptyState');
    const tableView = document.getElementById('tableView');
    const cardsView = document.getElementById('cardsView');

    if (visibleCount === 0) {
        if (emptyState) emptyState.style.display = 'block';
        if (tableView) tableView.style.display = 'none';
        if (cardsView) cardsView.style.display = 'none';
    } else {
        if (emptyState) emptyState.style.display = 'none';
        // Show appropriate view based on current toggle
        const isTableView = document.getElementById('tableViewBtn')?.classList.contains('active');
        if (tableView) tableView.style.display = isTableView ? 'block' : 'none';
        if (cardsView) cardsView.style.display = isTableView ? 'none' : 'block';
    }

    // Update pagination info
    updatePaginationInfo(visibleCount);
}

/**
 * Clear all filters
 */
function clearAllFilters() {
    document.getElementById('dateRangeSelect').value = 'today';
    document.getElementById('customDateInputs').style.display = 'none';
    document.getElementById('staffFilter').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('searchInput').value = '';
    document.getElementById('startDate').value = '';
    document.getElementById('endDate').value = '';
    
    applyFilters();
    showNotification('Filters cleared', 'info');
}

/**
 * Update URL with current filters (for bookmarking)
 */
function updateURL(filters) {
    const params = new URLSearchParams();
    Object.entries(filters).forEach(([key, value]) => {
        if (value) params.set(key, value);
    });
    
    const newURL = `${window.location.pathname}${params.toString() ? '?' + params.toString() : ''}`;
    window.history.replaceState({}, '', newURL);
}

/* ==========================================================================
   3. TABLE MANAGEMENT
   ========================================================================== */

/**
 * Initialize table functionality
 */
function initializeTable() {
    initializeSelectAll();
    initializeRowSelection();
    initializeActionButtons();
    initializeSorting();
}

/**
 * Initialize select all functionality
 */
function initializeSelectAll() {
    const selectAllCheckbox = document.getElementById('selectAll');
    if (!selectAllCheckbox) return;

    selectAllCheckbox.addEventListener('change', function() {
        const rowCheckboxes = document.querySelectorAll('.row-checkbox, .card-checkbox');
        const isChecked = this.checked;

        rowCheckboxes.forEach(checkbox => {
            if (checkbox.closest('.attendance-row, .attendance-card').style.display !== 'none') {
                checkbox.checked = isChecked;
            }
        });

        updateBulkActions();
    });
}

/**
 * Initialize individual row selection
 */
function initializeRowSelection() {
    document.addEventListener('change', function(e) {
        if (e.target.matches('.row-checkbox, .card-checkbox')) {
            updateSelectAllState();
            updateBulkActions();
        }
    });
}

/**
 * Update select all checkbox state
 */
function updateSelectAllState() {
    const selectAllCheckbox = document.getElementById('selectAll');
    if (!selectAllCheckbox) return;

    const visibleCheckboxes = Array.from(document.querySelectorAll('.row-checkbox, .card-checkbox'))
        .filter(cb => cb.closest('.attendance-row, .attendance-card').style.display !== 'none');
    
    const checkedCount = visibleCheckboxes.filter(cb => cb.checked).length;
    
    selectAllCheckbox.checked = checkedCount > 0 && checkedCount === visibleCheckboxes.length;
    selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < visibleCheckboxes.length;
}

/**
 * Initialize action buttons
 */
function initializeActionButtons() {
    document.addEventListener('click', function(e) {
        if (e.target.closest('.action-btn')) {
            const btn = e.target.closest('.action-btn');
            const action = btn.dataset.action;
            const id = btn.dataset.id;
            
            handleRowAction(action, id, btn);
        }
    });
}

/**
 * Handle individual row actions
 */
function handleRowAction(action, id, button) {
    switch (action) {
        case 'edit':
            openEditModal(id);
            break;
        case 'leave':
            markAsLeave(id);
            break;
        case 'delete':
            confirmDelete(id);
            break;
        default:
            console.warn('Unknown action:', action);
    }
}

/**
 * Initialize table sorting (basic implementation)
 */
function initializeSorting() {
    const headers = document.querySelectorAll('.attendance-th');
    headers.forEach(header => {
        if (header.classList.contains('attendance-th-checkbox') || 
            header.classList.contains('attendance-th-actions')) {
            return; // Skip non-sortable columns
        }

        header.style.cursor = 'pointer';
        header.addEventListener('click', function() {
            sortTable(this);
        });
    });
}

/**
 * Sort table by column (basic implementation)
 */
function sortTable(header) {
    // This would typically be handled by backend with proper sorting
    showNotification('Sorting functionality would be implemented with backend integration', 'info');
}

/* ==========================================================================
   4. BULK ACTIONS
   ========================================================================== */

/**
 * Initialize bulk actions
 */
function initializeBulkActions() {
    const bulkMarkPresent = document.getElementById('bulkMarkPresent');
    const bulkMarkAbsent = document.getElementById('bulkMarkAbsent');
    const bulkExport = document.getElementById('bulkExport');

    if (bulkMarkPresent) {
        bulkMarkPresent.addEventListener('click', () => handleBulkAction('present'));
    }
    if (bulkMarkAbsent) {
        bulkMarkAbsent.addEventListener('click', () => handleBulkAction('absent'));
    }
    if (bulkExport) {
        bulkExport.addEventListener('click', handleBulkExport);
    }
}

/**
 * Update bulk actions visibility
 */
function updateBulkActions() {
    const selectedCheckboxes = document.querySelectorAll('.row-checkbox:checked, .card-checkbox:checked');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');

    if (bulkActions && selectedCount) {
        const count = selectedCheckboxes.length;
        bulkActions.style.display = count > 0 ? 'flex' : 'none';
        selectedCount.textContent = `${count} selected`;
    }
}

/**
 * Handle bulk status changes
 */
function handleBulkAction(status) {
    const selectedCheckboxes = document.querySelectorAll('.row-checkbox:checked, .card-checkbox:checked');
    const ids = Array.from(selectedCheckboxes).map(cb => cb.value);

    if (ids.length === 0) {
        showNotification('No records selected', 'warning');
        return;
    }

    // Simulate bulk update
    ids.forEach(id => {
        updateRecordStatus(id, status);
    });

    // Clear selection
    selectedCheckboxes.forEach(cb => cb.checked = false);
    updateBulkActions();
    updateSelectAllState();

    showNotification(`${ids.length} records marked as ${status}`, 'success');
}

/**
 * Handle bulk export
 */
function handleBulkExport() {
    const selectedCheckboxes = document.querySelectorAll('.row-checkbox:checked, .card-checkbox:checked');
    const ids = Array.from(selectedCheckboxes).map(cb => cb.value);

    if (ids.length === 0) {
        showNotification('No records selected for export', 'warning');
        return;
    }

    // Simulate export
    showNotification(`Exporting ${ids.length} records...`, 'info');
    
    // In real app, this would trigger a download
    setTimeout(() => {
        showNotification('Export completed successfully', 'success');
    }, 2000);
}

/**
 * Update record status in UI
 */
function updateRecordStatus(id, status) {
    const row = document.querySelector(`[data-staff-id="${id}"]`);
    if (!row) return;

    const statusBadge = row.querySelector('.status-badge');
    if (statusBadge) {
        // Remove old status classes
        statusBadge.className = 'status-badge';
        // Add new status class
        statusBadge.classList.add(`status-${status}`);
        statusBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
    }
}

/* ==========================================================================
   5. MODAL MANAGEMENT
   ========================================================================== */

/**
 * Initialize modal functionality
 */
function initializeModal() {
    const addAttendanceBtn = document.getElementById('addAttendanceBtn');
    const addFirstAttendance = document.getElementById('addFirstAttendance');
    const closeModal = document.getElementById('closeModal');
    const cancelModal = document.getElementById('cancelModal');
    const saveAttendance = document.getElementById('saveAttendance');
    const modal = document.getElementById('attendanceModal');

    // Open modal buttons
    if (addAttendanceBtn) {
        addAttendanceBtn.addEventListener('click', () => openAddModal());
    }
    if (addFirstAttendance) {
        addFirstAttendance.addEventListener('click', () => openAddModal());
    }

    // Close modal buttons
    if (closeModal) {
        closeModal.addEventListener('click', closeAttendanceModal);
    }
    if (cancelModal) {
        cancelModal.addEventListener('click', closeAttendanceModal);
    }

    // Save button
    if (saveAttendance) {
        saveAttendance.addEventListener('click', handleSaveAttendance);
    }

    // Close on overlay click
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeAttendanceModal();
            }
        });
    }

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal && modal.style.display !== 'none') {
            closeAttendanceModal();
        }
    });
}

/**
 * Open add attendance modal
 */
function openAddModal() {
    const modal = document.getElementById('attendanceModal');
    const modalTitle = document.getElementById('modalTitle');
    const form = document.getElementById('attendanceForm');

    if (modalTitle) {
        modalTitle.textContent = 'Add Attendance Record';
    }

    // Reset form
    if (form) {
        form.reset();
        // Set today's date as default
        const dateInput = document.getElementById('modalDate');
        if (dateInput) {
            dateInput.value = new Date().toISOString().split('T')[0];
        }
    }

    if (modal) {
        modal.style.display = 'flex';
        // Focus first input
        const firstInput = modal.querySelector('input, select');
        if (firstInput) {
            setTimeout(() => firstInput.focus(), 100);
        }
    }
}

/**
 * Open edit attendance modal
 */
function openEditModal(id) {
    const modal = document.getElementById('attendanceModal');
    const modalTitle = document.getElementById('modalTitle');
    const form = document.getElementById('attendanceForm');

    if (modalTitle) {
        modalTitle.textContent = 'Edit Attendance Record';
    }

    // Populate form with existing data
    populateEditForm(id);

    if (modal) {
        modal.style.display = 'flex';
    }
}

/**
 * Populate edit form with existing data
 */
function populateEditForm(id) {
    const row = document.querySelector(`[data-staff-id="${id}"]`);
    if (!row) return;

    // Get existing values
    const staffSelect = document.getElementById('modalStaffSelect');
    const dateInput = document.getElementById('modalDate');
    const checkInInput = document.getElementById('modalCheckIn');
    const checkOutInput = document.getElementById('modalCheckOut');
    const statusSelect = document.getElementById('modalStatus');

    if (staffSelect) staffSelect.value = id;
    if (dateInput) dateInput.value = new Date().toISOString().split('T')[0];
    
    // Get time values from data attributes or text content
    const checkInElement = row.querySelector('[data-field="check_in"]');
    const checkOutElement = row.querySelector('[data-field="check_out"]');
    
    if (checkInInput && checkInElement) {
        const checkInValue = checkInElement.dataset.value || '';
        checkInInput.value = checkInValue;
    }
    
    if (checkOutInput && checkOutElement) {
        const checkOutValue = checkOutElement.dataset.value || '';
        checkOutInput.value = checkOutValue;
    }

    // Get status
    const statusBadge = row.querySelector('.status-badge');
    if (statusSelect && statusBadge) {
        const currentStatus = statusBadge.textContent.toLowerCase().trim();
        statusSelect.value = currentStatus;
    }
}

/**
 * Close attendance modal
 */
function closeAttendanceModal() {
    const modal = document.getElementById('attendanceModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

/**
 * Handle save attendance
 */
function handleSaveAttendance() {
    const form = document.getElementById('attendanceForm');
    if (!form) return;

    // Validate form
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    // Get form data
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());

    // Simulate save
    showNotification('Saving attendance record...', 'info');
    
    setTimeout(() => {
        showNotification('Attendance record saved successfully', 'success');
        closeAttendanceModal();
        // In real app, would refresh data or update UI
    }, 1000);
}

/* ==========================================================================
   6. VIEW TOGGLE
   ========================================================================== */

/**
 * Initialize view toggle functionality
 */
function initializeViewToggle() {
    const tableViewBtn = document.getElementById('tableViewBtn');
    const cardsViewBtn = document.getElementById('cardsViewBtn');
    const tableView = document.getElementById('tableView');
    const cardsView = document.getElementById('cardsView');

    if (tableViewBtn) {
        tableViewBtn.addEventListener('click', () => switchView('table'));
    }
    if (cardsViewBtn) {
        cardsViewBtn.addEventListener('click', () => switchView('cards'));
    }

    // Set initial view based on screen size
    if (window.innerWidth <= 767) {
        switchView('cards');
    }
}

/**
 * Switch between table and cards view
 */
function switchView(viewType) {
    const tableViewBtn = document.getElementById('tableViewBtn');
    const cardsViewBtn = document.getElementById('cardsViewBtn');
    const tableView = document.getElementById('tableView');
    const cardsView = document.getElementById('cardsView');

    // Update button states
    if (tableViewBtn && cardsViewBtn) {
        tableViewBtn.classList.toggle('active', viewType === 'table');
        cardsViewBtn.classList.toggle('active', viewType === 'cards');
    }

    // Update view visibility
    if (tableView && cardsView) {
        tableView.style.display = viewType === 'table' ? 'block' : 'none';
        cardsView.style.display = viewType === 'cards' ? 'block' : 'none';
    }

    // Store preference
    localStorage.setItem('attendanceViewPreference', viewType);
}

/* ==========================================================================
   7. INLINE EDITING
   ========================================================================== */

/**
 * Initialize inline editing functionality
 */
function initializeInlineEditing() {
    document.addEventListener('click', function(e) {
        if (e.target.matches('.editable') || e.target.closest('.editable')) {
            const element = e.target.matches('.editable') ? e.target : e.target.closest('.editable');
            startInlineEdit(element);
        }
    });
}

/**
 * Start inline editing for time fields
 */
function startInlineEdit(element) {
    const currentValue = element.dataset.value || element.textContent.trim();
    const field = element.dataset.field;
    
    // Create input
    const input = document.createElement('input');
    input.type = 'time';
    input.value = currentValue;
    input.className = 'inline-edit-input';
    input.style.cssText = `
        width: 100%;
        padding: 4px;
        border: 1px solid var(--color-accent);
        border-radius: 4px;
        background: var(--color-bg-secondary);
        color: var(--color-text-primary);
        font-size: inherit;
    `;

    // Replace content
    const originalContent = element.innerHTML;
    element.innerHTML = '';
    element.appendChild(input);
    input.focus();
    input.select();

    // Handle save/cancel
    function saveEdit() {
        const newValue = input.value;
        if (newValue && newValue !== currentValue) {
            element.dataset.value = newValue;
            element.innerHTML = formatTime(newValue);
            showNotification('Time updated', 'success');
            // In real app, would send update to backend
        } else {
            element.innerHTML = originalContent;
        }
    }

    function cancelEdit() {
        element.innerHTML = originalContent;
    }

    input.addEventListener('blur', saveEdit);
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            saveEdit();
        } else if (e.key === 'Escape') {
            e.preventDefault();
            cancelEdit();
        }
    });
}

/**
 * Format time for display
 */
function formatTime(timeValue) {
    if (!timeValue) return '--';
    
    const [hours, minutes] = timeValue.split(':');
    const hour12 = hours % 12 || 12;
    const ampm = hours >= 12 ? 'PM' : 'AM';
    
    return `${hour12}:${minutes} ${ampm}`;
}

/* ==========================================================================
   8. PAGINATION
   ========================================================================== */

/**
 * Initialize pagination
 */
function initializePagination() {
    const prevBtn = document.getElementById('prevPage');
    const nextBtn = document.getElementById('nextPage');

    if (prevBtn) {
        prevBtn.addEventListener('click', () => changePage(-1));
    }
    if (nextBtn) {
        nextBtn.addEventListener('click', () => changePage(1));
    }
}

/**
 * Change page (placeholder implementation)
 */
function changePage(direction) {
    // In real app, this would handle pagination with backend
    showNotification(`Page navigation would be implemented with backend pagination`, 'info');
}

/**
 * Update pagination info
 */
function updatePaginationInfo(visibleCount) {
    const showingStart = document.getElementById('showingStart');
    const showingEnd = document.getElementById('showingEnd');
    const totalRecords = document.getElementById('totalRecords');

    if (showingStart) showingStart.textContent = visibleCount > 0 ? '1' : '0';
    if (showingEnd) showingEnd.textContent = visibleCount.toString();
    if (totalRecords) totalRecords.textContent = visibleCount.toString();
}

/* ==========================================================================
   9. UTILITY FUNCTIONS
   ========================================================================== */

/**
 * Debounce function to limit function calls
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

/**
 * Show notification to user
 */
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 20px;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        z-index: 1000;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        max-width: 300px;
        word-wrap: break-word;
    `;
    
    // Set background color based on type
    const colors = {
        success: '#10B981',
        error: '#EF4444',
        warning: '#F59E0B',
        info: '#3B82F6'
    };
    notification.style.backgroundColor = colors[type] || colors.info;
    
    // Add message
    notification.textContent = message;
    
    // Add to DOM
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Remove after delay
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

/**
 * Mark record as leave
 */
function markAsLeave(id) {
    const confirmed = confirm('Mark this staff member as on leave?');
    if (confirmed) {
        updateRecordStatus(id, 'on_leave');
        showNotification('Staff marked as on leave', 'success');
    }
}

/**
 * Confirm and delete record
 */
function confirmDelete(id) {
    const confirmed = confirm('Are you sure you want to delete this attendance record?');
    if (confirmed) {
        const row = document.querySelector(`[data-staff-id="${id}"]`);
        if (row) {
            row.style.display = 'none';
            showNotification('Attendance record deleted', 'success');
            
            // Show undo option
            setTimeout(() => {
                const undo = confirm('Undo deletion?');
                if (undo) {
                    row.style.display = '';
                    showNotification('Deletion undone', 'info');
                }
            }, 2000);
        }
    }
}

/* ==========================================================================
   10. KEYBOARD NAVIGATION & ACCESSIBILITY
   ========================================================================== */

/**
 * Initialize keyboard navigation
 */
function initializeKeyboardNavigation() {
    // Add keyboard support for action buttons
    document.addEventListener('keydown', function(e) {
        if (e.target.matches('.action-btn')) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                e.target.click();
            }
        }
    });

    // Add keyboard support for view toggle
    const viewToggleBtns = document.querySelectorAll('.view-toggle-btn');
    viewToggleBtns.forEach((btn, index) => {
        btn.addEventListener('keydown', function(e) {
            let nextIndex;
            
            switch(e.key) {
                case 'ArrowLeft':
                    e.preventDefault();
                    nextIndex = index > 0 ? index - 1 : viewToggleBtns.length - 1;
                    viewToggleBtns[nextIndex].focus();
                    break;
                case 'ArrowRight':
                    e.preventDefault();
                    nextIndex = index < viewToggleBtns.length - 1 ? index + 1 : 0;
                    viewToggleBtns[nextIndex].focus();
                    break;
                case 'Enter':
                case ' ':
                    e.preventDefault();
                    btn.click();
                    break;
            }
        });
    });
}

/* ==========================================================================
   11. RESPONSIVE BEHAVIOR
   ========================================================================== */

/**
 * Handle responsive behavior
 */
window.addEventListener('resize', debounce(function() {
    // Auto-switch to cards view on mobile
    if (window.innerWidth <= 767) {
        const cardsViewBtn = document.getElementById('cardsViewBtn');
        if (cardsViewBtn && !cardsViewBtn.classList.contains('active')) {
            switchView('cards');
        }
    }
}, 250));

/* ==========================================================================
   12. EXPORT FUNCTIONALITY
   ========================================================================== */

/**
 * Initialize export functionality
 */
document.getElementById('exportBtn')?.addEventListener('click', function() {
    showNotification('Preparing export...', 'info');
    
    // Simulate export process
    setTimeout(() => {
        showNotification('Attendance data exported successfully', 'success');
        // In real app, this would trigger a file download
    }, 2000);
});

/* ==========================================================================
   13. DRAWER FUNCTIONALITY
   ========================================================================== */

/**
 * Initialize drawer functionality
 */
function initializeDrawer() {
    // Make saveAttendance function globally available for Alpine.js
    window.saveAttendance = function() {
        const form = document.getElementById('attendanceForm');
        const saveButton = document.querySelector('.drawer-btn-primary');
        
        if (!form) return;

        // Validate form
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Get form data
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        // Add loading state to button
        if (saveButton) {
            saveButton.classList.add('loading');
            saveButton.disabled = true;
        }

        // Show loading notification
        showNotification('Saving attendance record...', 'info');
        
        // Simulate save process
        setTimeout(() => {
            try {
                // Simulate potential error (10% chance for demo)
                if (Math.random() < 0.1) {
                    throw new Error('Network error occurred');
                }

                showNotification('Attendance record saved successfully', 'success');
                
                // Close drawer
                const drawer = document.querySelector('[x-data*="showAttendanceDrawer"]');
                if (drawer) {
                    drawer._x_dataStack[0].showAttendanceDrawer = false;
                }
                
                // Reset form
                form.reset();
                
                // In real app, would refresh data or update UI
                console.log('Attendance data saved:', data);
                
            } catch (error) {
                showNotification('Error saving attendance record: ' + error.message, 'error');
                console.error('Save error:', error);
            } finally {
                // Remove loading state
                if (saveButton) {
                    saveButton.classList.remove('loading');
                    saveButton.disabled = false;
                }
            }
        }, 1500);
    };

    // Add form validation enhancements
    const form = document.getElementById('attendanceForm');
    if (form) {
        // Real-time validation
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
        });

        // Prevent form submission on Enter key
        form.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.target.type !== 'textarea') {
                e.preventDefault();
            }
        });
    }
}

/**
 * Validate individual form field
 */
function validateField(field) {
    const value = field.value.trim();
    const isRequired = field.hasAttribute('required');
    
    // Remove existing validation classes
    field.classList.remove('error', 'success');
    
    if (isRequired && !value) {
        field.classList.add('error');
        return false;
    }
    
    // Additional validation based on field type
    if (field.type === 'email' && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            field.classList.add('error');
            return false;
        }
    }
    
    if (field.type === 'time' && value) {
        const timeRegex = /^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/;
        if (!timeRegex.test(value)) {
            field.classList.add('error');
            return false;
        }
    }
    
    field.classList.add('success');
    return true;
}
