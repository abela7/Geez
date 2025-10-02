// Shift Management JavaScript

// Main Shifts Management Data
function shiftsManageData() {
    return {
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
        
        // Methods
        init() {
            this.applyFilters();
        },
        
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
            if (statusBadge.classList.contains('status-active')) return 'active';
            if (statusBadge.classList.contains('status-draft')) return 'draft';
            if (statusBadge.classList.contains('status-inactive')) return 'inactive';
            return 'unknown';
        },
        
        getRowType(row) {
            const typeBadge = row.querySelector('.type-badge');
            if (typeBadge.classList.contains('type-regular')) return 'regular';
            if (typeBadge.classList.contains('type-weekend')) return 'weekend';
            if (typeBadge.classList.contains('type-overtime')) return 'overtime';
            if (typeBadge.classList.contains('type-training')) return 'training';
            return 'regular';
        },
        
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
                
                if (typeof aValue === 'number' && typeof bValue === 'number') {
                    return this.sortDirection === 'asc' ? aValue - bValue : bValue - aValue;
                }
                
                const comparison = aValue.localeCompare(bValue);
                return this.sortDirection === 'asc' ? comparison : -comparison;
            });
            
            rows.forEach(row => tbody.appendChild(row));
            this.updateSortIcons();
        },
        
        updateSortIcons() {
            document.querySelectorAll('.sort-icon').forEach(icon => {
                icon.classList.remove('active');
            });
            
            const activeHeader = document.querySelector(`th[onclick*="${this.sortField}"] .sort-icon`);
            if (activeHeader) {
                activeHeader.classList.add('active');
            }
        },
        
        toggleSelectAll() {
            const checkboxes = document.querySelectorAll('.shift-row input[type="checkbox"]');
            this.selectedShifts = [];
            
            if (this.selectAll) {
                checkboxes.forEach(checkbox => {
                    if (checkbox.closest('.shift-row').style.display !== 'none') {
                        checkbox.checked = true;
                        this.selectedShifts.push(checkbox.value);
                    }
                });
            } else {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
            }
        },
        
        bulkActivate() {
            if (this.selectedShifts.length === 0) return;
            
            this.selectedShifts.forEach(shiftId => {
                const row = document.querySelector(`[data-shift-id="${shiftId}"]`);
                const statusBadge = row?.querySelector('.status-badge');
                if (statusBadge) {
                    statusBadge.className = 'status-badge status-active';
                    statusBadge.textContent = 'Active';
                }
            });
            
            this.showNotification(`${this.selectedShifts.length} shift(s) activated successfully!`, 'success');
            this.clearSelection();
        },
        
        bulkDeactivate() {
            if (this.selectedShifts.length === 0) return;
            
            this.selectedShifts.forEach(shiftId => {
                const row = document.querySelector(`[data-shift-id="${shiftId}"]`);
                const statusBadge = row?.querySelector('.status-badge');
                if (statusBadge) {
                    statusBadge.className = 'status-badge status-inactive';
                    statusBadge.textContent = 'Inactive';
                }
            });
            
            this.showNotification(`${this.selectedShifts.length} shift(s) deactivated successfully!`, 'warning');
            this.clearSelection();
        },
        
        bulkDelete() {
            if (this.selectedShifts.length === 0) return;
            
            if (confirm(`Are you sure you want to delete ${this.selectedShifts.length} shift(s)? This action cannot be undone.`)) {
                this.selectedShifts.forEach(shiftId => {
                    const row = document.querySelector(`[data-shift-id="${shiftId}"]`);
                    if (row) {
                        row.remove();
                    }
                });
                
                this.showNotification(`${this.selectedShifts.length} shift(s) deleted successfully!`, 'success');
                this.clearSelection();
            }
        },
        
        clearSelection() {
            this.selectedShifts = [];
            this.selectAll = false;
            document.querySelectorAll('.shift-row input[type="checkbox"]').forEach(checkbox => {
                checkbox.checked = false;
            });
        },
        
        duplicateShift(shiftId) {
            this.showNotification('Shift duplicated successfully!', 'success');
        },
        
        toggleShiftStatus(shiftId, currentStatus) {
            const row = document.querySelector(`[data-shift-id="${shiftId}"]`);
            const statusBadge = row?.querySelector('.status-badge');
            
            if (statusBadge) {
                const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
                statusBadge.className = `status-badge status-${newStatus}`;
                statusBadge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                
                this.showNotification(`Shift ${newStatus === 'active' ? 'activated' : 'deactivated'} successfully!`, 'success');
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
            
            // Add to page
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
    };
}

// Shift Create Data
function shiftCreateData() {
    return {
        form: {
            name: '',
            department: '',
            type: '',
            description: '',
            start_time: '',
            end_time: '',
            duration_hours: 0,
            break_duration: 30,
            days_of_week: [],
            required_staff: 1,
            hourly_rate: 15.00,
            overtime_rate: 22.50,
            status: 'draft'
        },
        
        init() {
            this.calculateDuration();
        },
        
        calculateDuration() {
            if (this.form.start_time && this.form.end_time) {
                const start = new Date(`2000-01-01 ${this.form.start_time}`);
                let end = new Date(`2000-01-01 ${this.form.end_time}`);
                
                // Handle overnight shifts
                if (end <= start) {
                    end.setDate(end.getDate() + 1);
                }
                
                const diffMs = end - start;
                this.form.duration_hours = diffMs / (1000 * 60 * 60);
            }
        },
        
        formatDuration(hours) {
            if (!hours || hours === 0) return 'Not calculated';
            
            const wholeHours = Math.floor(hours);
            const minutes = Math.round((hours - wholeHours) * 60);
            
            if (minutes === 0) {
                return `${wholeHours} hour${wholeHours !== 1 ? 's' : ''}`;
            } else {
                return `${wholeHours}h ${minutes}m`;
            }
        },
        
        calculateDailyCost() {
            return this.form.required_staff * this.form.duration_hours * this.form.hourly_rate;
        },
        
        calculateWeeklyCost() {
            return this.calculateDailyCost() * this.form.days_of_week.length;
        },
        
        calculateMonthlyCost() {
            return this.calculateWeeklyCost() * 4.33; // Average weeks per month
        },
        
        formatCurrency(amount) {
            return new Intl.NumberFormat('en-GB', {
                style: 'currency',
                currency: 'GBP'
            }).format(amount || 0);
        },
        
        resetForm() {
            this.form = {
                name: '',
                department: '',
                type: '',
                description: '',
                start_time: '',
                end_time: '',
                duration_hours: 0,
                break_duration: 30,
                days_of_week: [],
                required_staff: 1,
                hourly_rate: 15.00,
                overtime_rate: 22.50,
                status: 'draft'
            };
        },
        
        saveDraft() {
            this.form.status = 'draft';
            this.showNotification('Shift saved as draft!', 'info');
        },
        
        showNotification(message, type = 'info') {
            // Same notification function as in shiftsManageData
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.textContent = message;
            
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
    };
}

// Shift Edit Data - Based on Create Data Structure
function shiftEditData(shiftData) {
    return {
        form: {
            name: shiftData.name || '',
            department: shiftData.department || '',
            type: shiftData.type || '',
            description: shiftData.description || '',
            start_time: shiftData.start_time || '',
            end_time: shiftData.end_time || '',
            duration_hours: 0,
            break_duration: shiftData.break_duration || 30,
            days_of_week: Array.isArray(shiftData.days_of_week) ? shiftData.days_of_week : [],
            required_staff: shiftData.required_staff || 1,
            hourly_rate: shiftData.hourly_rate || 15.00,
            overtime_rate: shiftData.overtime_rate || 22.50,
            status: shiftData.status || 'draft'
        },
        
        init() {
            console.log('Initializing edit form with data:', shiftData);
            console.log('Form data:', this.form);
            this.calculateDuration();
        },
        
        calculateDuration() {
            if (this.form.start_time && this.form.end_time) {
                const start = new Date(`2000-01-01 ${this.form.start_time}`);
                let end = new Date(`2000-01-01 ${this.form.end_time}`);
                
                // Handle overnight shifts
                if (end <= start) {
                    end.setDate(end.getDate() + 1);
                }
                
                const diffMs = end - start;
                this.form.duration_hours = diffMs / (1000 * 60 * 60);
            }
        },
        
        formatDuration(hours) {
            if (!hours || hours === 0) return 'Not calculated';
            
            const wholeHours = Math.floor(hours);
            const minutes = Math.round((hours - wholeHours) * 60);
            
            if (minutes === 0) {
                return `${wholeHours} hour${wholeHours !== 1 ? 's' : ''}`;
            } else {
                return `${wholeHours}h ${minutes}m`;
            }
        },
        
        calculateDailyCost() {
            return this.form.required_staff * this.form.duration_hours * this.form.hourly_rate;
        },
        
        calculateWeeklyCost() {
            return this.calculateDailyCost() * this.form.days_of_week.length;
        },
        
        calculateMonthlyCost() {
            return this.calculateWeeklyCost() * 4.33; // Average weeks per month
        },
        
        calculateCosts() {
            // Trigger reactivity for cost calculations
            this.$nextTick();
        },
        
        formatCurrency(amount) {
            return new Intl.NumberFormat('en-GB', {
                style: 'currency',
                currency: 'GBP'
            }).format(amount || 0);
        },
        
        isFormValid() {
            return this.form.name && 
                   this.form.department && 
                   this.form.type && 
                   this.form.start_time && 
                   this.form.end_time && 
                   this.form.required_staff > 0 && 
                   this.form.days_of_week.length > 0;
        },
        
        duplicateShift() {
            window.location.href = `/admin/shifts/manage/create?duplicate=true&source=${shiftData.id}`;
        },
        
        deleteShift() {
            if (confirm('Are you sure you want to delete this shift? This action cannot be undone.')) {
                // Create a form to submit DELETE request
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/shifts/manage/${shiftData.id}`;
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
        
        saveDraft() {
            this.form.status = 'draft';
            this.showNotification('Saving as draft...', 'info');
            
            // Submit the form
            const formElement = document.querySelector('.shift-form');
            if (formElement) {
                formElement.submit();
            }
        },
        
        resetForm() {
            // Reset to original data
            this.form = {
                name: shiftData.name || '',
                department: shiftData.department || '',
                type: shiftData.type || '',
                description: shiftData.description || '',
                start_time: shiftData.start_time || '',
                end_time: shiftData.end_time || '',
                duration_hours: 0,
                break_duration: shiftData.break_duration || 30,
                days_of_week: Array.isArray(shiftData.days_of_week) ? [...shiftData.days_of_week] : [],
                required_staff: shiftData.required_staff || 1,
                hourly_rate: shiftData.hourly_rate || 15.00,
                overtime_rate: shiftData.overtime_rate || 22.50,
                status: shiftData.status || 'draft'
            };
            this.calculateDuration();
            this.showNotification('Form reset to original values', 'info');
        },
        
        showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            
            const content = document.createElement('div');
            content.className = 'notification-content';
            content.innerHTML = `
                <span>${message}</span>
                <button class="notification-close" onclick="this.parentElement.parentElement.remove()">&times;</button>
            `;
            
            notification.appendChild(content);
            
            // Add to page
            document.body.appendChild(notification);
            
            // Auto-remove after 3 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 3000);
        }
    };
}

// Make functions globally available immediately
window.shiftsManageData = shiftsManageData;
window.shiftCreateData = shiftCreateData;
window.shiftEditData = shiftEditData;

// Ensure Alpine.js can find our functions by deferring its initialization
document.addEventListener('DOMContentLoaded', function() {
    // Wait a bit for all scripts to load
    setTimeout(() => {
        if (window.Alpine) {
            // Find and reinitialize the main container
            const container = document.querySelector('.shifts-manage-page');
            if (container && window.shiftsManageData) {
                // Clear existing Alpine data
                if (container._x_dataStack) {
                    container._x_dataStack = [];
                }
                
                // Set the correct data
                const data = window.shiftsManageData();
                container._x_dataStack = [data];
                
                // Reinitialize Alpine for this element
                if (window.Alpine.initTree) {
                    window.Alpine.initTree(container);
                }
            }
        }
    }, 200);
});

// Also try to fix it when the window loads
window.addEventListener('load', function() {
    setTimeout(() => {
        if (window.Alpine) {
            const container = document.querySelector('.shifts-manage-page');
            if (container && window.shiftsManageData) {
                // Clear existing Alpine data
                if (container._x_dataStack) {
                    container._x_dataStack = [];
                }
                
                // Set the correct data
                const data = window.shiftsManageData();
                container._x_dataStack = [data];
                
                // Reinitialize Alpine for this element
                if (window.Alpine.initTree) {
                    window.Alpine.initTree(container);
                }
            }
        }
    }, 100);
});