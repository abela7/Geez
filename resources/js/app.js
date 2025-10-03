import './bootstrap';

// Defer Alpine's start until Livewire initializes to avoid multiple instances
// This MUST be defined BEFORE importing Alpine
window.deferLoadingAlpine = function(callback) {
  window.addEventListener('livewire:init', callback);
};

// Import Alpine.js
import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';
import { shiftCreateComponent, shiftEditComponent } from './admin/shifts/create-component.js';

// Expose Alpine globally so Livewire detects it and doesn't inject its own
window.Alpine = Alpine;

// Register plugins and components before Alpine starts
Alpine.plugin(persist);
Alpine.data('shiftCreateData', shiftCreateComponent);
Alpine.data('shiftEditData', shiftEditComponent);

// Register shifts manage component with full functionality
Alpine.data('shiftsManageData', () => ({
    // Filters
    searchQuery: '',
    filterDepartment: 'all',
    filterStatus: 'all',
    filterType: 'all',
    
    // Sorting
    sortField: 'name',
    sortDirection: 'asc',
    
    // Selection
    selectAll: false,
    selectedShifts: [],
    showBulkActions: false,
    
    // Initialize
    init() {
        console.log('Shifts manage component initialized');
        this.applyFilters();
    },
    
    // Filter functionality
    applyFilters() {
        const rows = document.querySelectorAll('.shift-row');
        let visibleCount = 0;
        
        rows.forEach(row => {
            const shiftId = row.dataset.shiftId;
            const name = row.querySelector('.shift-name')?.textContent?.toLowerCase() || '';
            const description = row.querySelector('.shift-description')?.textContent?.toLowerCase() || '';
            const department = row.querySelector('.department-badge')?.textContent?.trim() || '';
            const status = this.getRowStatus(row);
            const type = this.getRowType(row);
            
            let visible = true;
            
            // Search filter
            if (this.searchQuery) {
                const query = this.searchQuery.toLowerCase();
                if (!name.includes(query) && !description.includes(query)) {
                    visible = false;
                }
            }
            
            // Department filter
            if (this.filterDepartment !== 'all' && !department.toLowerCase().includes(this.filterDepartment.toLowerCase())) {
                visible = false;
            }
            
            // Status filter
            if (this.filterStatus !== 'all' && status !== this.filterStatus) {
                visible = false;
            }
            
            // Type filter
            if (this.filterType !== 'all' && type !== this.filterType) {
                visible = false;
            }
            
            row.style.display = visible ? '' : 'none';
            if (visible) visibleCount++;
        });
        
        this.showFilterNotification(visibleCount, rows.length);
    },
    
    resetFilters() {
        this.searchQuery = '';
        this.filterDepartment = 'all';
        this.filterStatus = 'all';
        this.filterType = 'all';
        this.applyFilters();
        this.showNotification('Filters reset successfully!', 'info');
    },
    
    getRowStatus(row) {
        const statusBadge = row.querySelector('.status-badge');
        if (statusBadge?.classList.contains('status-active')) return 'active';
        if (statusBadge?.classList.contains('status-draft')) return 'draft';
        if (statusBadge?.classList.contains('status-inactive')) return 'inactive';
        return 'unknown';
    },
    
    getRowType(row) {
        const typeBadge = row.querySelector('.type-badge');
        if (typeBadge?.classList.contains('type-regular')) return 'regular';
        if (typeBadge?.classList.contains('type-weekend')) return 'weekend';
        if (typeBadge?.classList.contains('type-overtime')) return 'overtime';
        if (typeBadge?.classList.contains('type-training')) return 'training';
        return 'regular';
    },
    
    // Sorting functionality
    sortBy(field) {
        if (this.sortField === field) {
            this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            this.sortField = field;
            this.sortDirection = 'asc';
        }
        
        this.applySorting();
    },
    
    applySorting() {
        const tbody = document.querySelector('.shifts-table tbody');
        if (!tbody) return;
        
        const rows = Array.from(tbody.querySelectorAll('.shift-row'));
        
        rows.sort((a, b) => {
            let aValue, bValue;
            
            switch (this.sortField) {
                case 'name':
                    aValue = a.querySelector('.shift-name')?.textContent || '';
                    bValue = b.querySelector('.shift-name')?.textContent || '';
                    break;
                case 'department':
                    aValue = a.querySelector('.department-badge')?.textContent || '';
                    bValue = b.querySelector('.department-badge')?.textContent || '';
                    break;
                case 'start_time':
                    aValue = a.querySelector('.shift-time')?.textContent?.split(' - ')[0] || '';
                    bValue = b.querySelector('.shift-time')?.textContent?.split(' - ')[0] || '';
                    break;
                case 'required_staff':
                    aValue = parseInt(a.querySelector('.required')?.textContent || '0');
                    bValue = parseInt(b.querySelector('.required')?.textContent || '0');
                    break;
                case 'type':
                    aValue = a.querySelector('.type-badge')?.textContent || '';
                    bValue = b.querySelector('.type-badge')?.textContent || '';
                    break;
                case 'status':
                    aValue = a.querySelector('.status-badge')?.textContent || '';
                    bValue = b.querySelector('.status-badge')?.textContent || '';
                    break;
                default:
                    return 0;
            }
            
            if (typeof aValue === 'string') {
                aValue = aValue.toLowerCase();
                bValue = bValue.toLowerCase();
            }
            
            let comparison = 0;
            if (aValue > bValue) comparison = 1;
            else if (aValue < bValue) comparison = -1;
            
            return this.sortDirection === 'desc' ? -comparison : comparison;
        });
        
        // Re-append sorted rows
        rows.forEach(row => tbody.appendChild(row));
    },
    
    // Selection functionality
    toggleSelectAll() {
        const checkboxes = document.querySelectorAll('.shift-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.selectAll;
        });
        this.updateSelectedShifts();
    },
    
    updateSelectedShifts() {
        const checkboxes = document.querySelectorAll('.shift-checkbox:checked');
        this.selectedShifts = Array.from(checkboxes).map(cb => cb.value);
    },
    
    // Shift actions
    toggleShiftStatus(shiftId, currentStatus) {
        const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
        const row = document.querySelector(`[data-shift-id="${shiftId}"]`);
        
        if (row) {
            const statusBadge = row.querySelector('.status-badge');
            if (statusBadge) {
                statusBadge.className = `status-badge status-${newStatus}`;
                statusBadge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                
                this.showNotification(`Shift ${newStatus === 'active' ? 'activated' : 'deactivated'} successfully!`, 'success');
            }
        }
    },
    
    deleteShift(shiftId) {
        if (confirm('Are you sure you want to delete this shift? This action cannot be undone.')) {
            // Create a form to submit DELETE request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/shifts/manage/${shiftId}`;
            form.style.display = 'none';
            
            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);
            }
            
            // Add method override for DELETE
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            
            // Append form to body and submit
            document.body.appendChild(form);
            form.submit();
        }
    },
    
    duplicateShift(shiftId) {
        window.location.href = `/admin/shifts/manage/create?duplicate=true&source=${shiftId}`;
    },
    
    // Bulk actions
    bulkActivate() {
        if (this.selectedShifts.length === 0) return;
        this.showNotification(`${this.selectedShifts.length} shifts activated!`, 'success');
    },
    
    bulkDeactivate() {
        if (this.selectedShifts.length === 0) return;
        this.showNotification(`${this.selectedShifts.length} shifts deactivated!`, 'warning');
    },
    
    bulkDelete() {
        if (this.selectedShifts.length === 0) return;
        if (confirm(`Are you sure you want to delete ${this.selectedShifts.length} shifts? This action cannot be undone.`)) {
            this.showNotification(`${this.selectedShifts.length} shifts deleted!`, 'success');
        }
    },
    
    // Notification system
    showFilterNotification(visible, total) {
        if (visible < total) {
            this.showNotification(`Showing ${visible} of ${total} shifts`, 'info');
        }
    },
    
    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        
        // Style the notification
        Object.assign(notification.style, {
            position: 'fixed',
            top: '20px',
            right: '20px',
            padding: '12px 24px',
            borderRadius: '8px',
            color: 'white',
            fontWeight: '500',
            zIndex: '9999',
            transform: 'translateX(100%)',
            transition: 'transform 0.3s ease',
            maxWidth: '400px'
        });
        
        // Set background color based on type
        const colors = {
            success: '#10B981',
            error: '#EF4444',
            warning: '#F59E0B',
            info: '#3B82F6'
        };
        notification.style.backgroundColor = colors[type] || colors.info;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
}));

// Register shifts edit component (similar to create but for editing)
Alpine.data('shiftEditData', (initialData) => ({
    // Form data
    form: {
        name: initialData?.name || '',
        department: initialData?.department || '',
        type: initialData?.type || '',
        description: initialData?.description || '',
        start_time: initialData?.start_time || '',
        end_time: initialData?.end_time || '',
        break_duration: initialData?.break_duration || 30,
        days_of_week: initialData?.days_of_week || [],
        required_staff: initialData?.required_staff || 1,
        hourly_rate: initialData?.hourly_rate || '',
        overtime_rate: initialData?.overtime_rate || '',
        status: initialData?.status || 'draft',
        duration_hours: 0
    },

    // UI state
    isSubmitting: false,

    // Initialize component
    init() {
        this.calculateDuration();
        
        // Watch specifically for time changes
        this.$watch('form.start_time', () => {
            this.calculateDuration();
        });
        this.$watch('form.end_time', () => {
            this.calculateDuration();
        });

        console.log('Shift Edit Form initialized');
    },

    // Calculate shift duration
    calculateDuration() {
        if (!this.form.start_time || !this.form.end_time) {
            this.form.duration_hours = 0;
            return;
        }

        try {
            const start = new Date(`2000-01-01T${this.form.start_time}:00`);
            const end = new Date(`2000-01-01T${this.form.end_time}:00`);
            
            // Handle overnight shifts
            if (end < start) {
                end.setDate(end.getDate() + 1);
            }
            
            const diffMs = end - start;
            const diffHours = diffMs / (1000 * 60 * 60);
            
            this.form.duration_hours = Math.round(diffHours * 100) / 100; // Round to 2 decimal places
        } catch (error) {
            console.error('Error calculating duration:', error);
            this.form.duration_hours = 0;
        }
    },

    // Format duration for display
    formatDuration(hours) {
        if (!hours || hours === 0) {
            return 'Select start and end times';
        }
        
        const wholeHours = Math.floor(hours);
        const minutes = Math.round((hours - wholeHours) * 60);
        
        if (minutes === 0) {
            return `${wholeHours} hour${wholeHours !== 1 ? 's' : ''}`;
        } else {
            return `${wholeHours}h ${minutes}m`;
        }
    },

    // Calculate costs
    calculateDailyCost() {
        if (!this.form.hourly_rate || !this.form.required_staff || !this.form.duration_hours) {
            return 0;
        }
        
        const workHours = this.form.duration_hours - (this.form.break_duration / 60 || 0);
        return this.form.hourly_rate * this.form.required_staff * workHours;
    },

    calculateWeeklyCost() {
        const dailyCost = this.calculateDailyCost();
        return dailyCost * this.form.days_of_week.length;
    },

    calculateMonthlyCost() {
        const weeklyCost = this.calculateWeeklyCost();
        return weeklyCost * 4.33; // Average weeks per month
    },

    // Format currency
    formatCurrency(amount) {
        if (!amount || isNaN(amount)) {
            return '£0.00';
        }
        
        // Simple and reliable: format as number with 2 decimals and add £ symbol
        const formatted = parseFloat(amount).toFixed(2);
        return `£${formatted}`;
    }
}));

// Start Alpine (Livewire will trigger the deferred start)
Alpine.start();
