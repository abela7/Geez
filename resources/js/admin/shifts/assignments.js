// Shift Assignments JavaScript

// Main Shifts Assignments Data
function shiftsAssignmentsData() {
    return {
        // Filters
        filterDepartment: 'all',
        filterStatus: 'all',
        staffSearchQuery: '',
        staffFilterDepartment: 'all',
        
        // Modal states
        showAssignmentModal: false,
        showAutoAssign: false,
        showBulkAssign: false,
        
        // Selection states
        selectedShift: null,
        selectedStaffForAssignment: null,
        
        // Data
        availableStaff: [],
        
        // Methods
        init() {
            this.applyFilters();
            this.filterStaff();
        },
        
        applyFilters() {
            const cards = document.querySelectorAll('.shift-assignment-card');
            let visibleCount = 0;
            
            cards.forEach(card => {
                const department = card.dataset.department;
                const status = card.dataset.status;
                
                let visible = true;
                
                // Department filter
                if (this.filterDepartment !== 'all' && department !== this.filterDepartment) {
                    visible = false;
                }
                
                // Status filter
                if (this.filterStatus !== 'all' && status !== this.filterStatus) {
                    visible = false;
                }
                
                card.style.display = visible ? 'block' : 'none';
                if (visible) visibleCount++;
            });
            
            this.showFilterNotification(visibleCount, cards.length);
        },
        
        filterStaff() {
            const cards = document.querySelectorAll('.staff-card');
            let visibleCount = 0;
            
            cards.forEach(card => {
                const department = card.dataset.department;
                const name = card.dataset.name;
                
                let visible = true;
                
                // Department filter
                if (this.staffFilterDepartment !== 'all' && department !== this.staffFilterDepartment) {
                    visible = false;
                }
                
                // Search filter
                if (this.staffSearchQuery) {
                    const query = this.staffSearchQuery.toLowerCase();
                    if (!name.includes(query)) {
                        visible = false;
                    }
                }
                
                card.style.display = visible ? 'flex' : 'none';
                if (visible) visibleCount++;
            });
        },
        
        showAssignModal(shift) {
            this.selectedShift = shift;
            this.selectedStaffForAssignment = null;
            this.showAssignmentModal = true;
            this.loadAvailableStaff(shift);
        },
        
        async loadAvailableStaff(shift) {
            try {
                // In a real app, this would be an API call
                const response = await fetch('/admin/shifts/assignments/availability', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify({
                        date: shift.date,
                        start_time: shift.start_time,
                        end_time: shift.end_time,
                        department: shift.department
                    })
                });
                
                if (response.ok) {
                    const data = await response.json();
                    this.availableStaff = data.available_staff;
                }
            } catch (error) {
                console.error('Error loading available staff:', error);
                this.showNotification('Error loading available staff', 'error');
            }
        },
        
        selectStaffForAssignment(staff) {
            // Remove previous selection
            document.querySelectorAll('.staff-option').forEach(option => {
                option.classList.remove('selected');
            });
            
            // Add selection to clicked option
            event.currentTarget.classList.add('selected');
            this.selectedStaffForAssignment = staff;
        },
        
        async confirmAssignment() {
            if (!this.selectedShift || !this.selectedStaffForAssignment) {
                this.showNotification('Please select a staff member', 'warning');
                return;
            }
            
            try {
                const response = await fetch('/admin/shifts/assignments/assign', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify({
                        shift_id: this.selectedShift.id,
                        staff_id: this.selectedStaffForAssignment.id,
                        role: this.selectedStaffForAssignment.role
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showNotification(data.message, 'success');
                    this.showAssignmentModal = false;
                    this.updateShiftAssignment(this.selectedShift.id, this.selectedStaffForAssignment);
                } else {
                    this.showNotification(data.message || 'Assignment failed', 'error');
                }
            } catch (error) {
                console.error('Error assigning staff:', error);
                this.showNotification('Error assigning staff', 'error');
            }
        },
        
        async unassignStaff(shiftId, staffId) {
            if (!confirm('Are you sure you want to unassign this staff member?')) {
                return;
            }
            
            try {
                const response = await fetch('/admin/shifts/assignments/unassign', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify({
                        shift_id: shiftId,
                        staff_id: staffId
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showNotification(data.message, 'success');
                    this.removeShiftAssignment(shiftId, staffId);
                } else {
                    this.showNotification(data.message || 'Unassignment failed', 'error');
                }
            } catch (error) {
                console.error('Error unassigning staff:', error);
                this.showNotification('Error unassigning staff', 'error');
            }
        },
        
        updateShiftAssignment(shiftId, staff) {
            // Find the shift card and update it
            const shiftCard = document.querySelector(`[data-shift-id="${shiftId}"]`);
            if (!shiftCard) return;
            
            // Update assignments list
            const assignmentsList = shiftCard.querySelector('.assignments-list');
            if (assignmentsList) {
                const newAssignment = document.createElement('div');
                newAssignment.className = 'assignment-item';
                newAssignment.innerHTML = `
                    <div class="assignment-info">
                        <span class="staff-name">${staff.name}</span>
                        <span class="staff-role">${staff.role}</span>
                    </div>
                    <div class="assignment-actions">
                        <span class="assignment-status status-pending">Pending</span>
                        <button class="btn-icon-sm btn-danger" onclick="unassignStaff(${shiftId}, ${staff.id})">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                `;
                assignmentsList.appendChild(newAssignment);
            }
            
            // Update staffing progress
            this.updateStaffingProgress(shiftCard);
        },
        
        removeShiftAssignment(shiftId, staffId) {
            // Find and remove the assignment item
            const shiftCard = document.querySelector(`[data-shift-id="${shiftId}"]`);
            if (!shiftCard) return;
            
            const assignmentItems = shiftCard.querySelectorAll('.assignment-item');
            assignmentItems.forEach(item => {
                const staffName = item.querySelector('.staff-name')?.textContent;
                // In a real app, you'd have a data attribute with staff ID
                if (staffName && this.shouldRemoveAssignment(staffName, staffId)) {
                    item.remove();
                }
            });
            
            // Update staffing progress
            this.updateStaffingProgress(shiftCard);
        },
        
        shouldRemoveAssignment(staffName, staffId) {
            // This is a simplified check - in a real app you'd use proper IDs
            return true; // For demo purposes
        },
        
        updateStaffingProgress(shiftCard) {
            const assignmentItems = shiftCard.querySelectorAll('.assignment-item');
            const assignedCount = assignmentItems.length;
            
            // Update staffing text and progress bar
            const staffingText = shiftCard.querySelector('.staffing-text');
            const progressFill = shiftCard.querySelector('.progress-fill');
            const statusBadge = shiftCard.querySelector('.status-badge');
            
            if (staffingText && progressFill && statusBadge) {
                // Extract required count from existing text
                const requiredMatch = staffingText.textContent.match(/\/\s*(\d+)/);
                const requiredCount = requiredMatch ? parseInt(requiredMatch[1]) : 1;
                
                // Update text
                staffingText.textContent = `${assignedCount} / ${requiredCount} Staff`;
                
                // Update progress
                const percentage = (assignedCount / requiredCount) * 100;
                progressFill.style.width = `${percentage}%`;
                
                // Update status
                let newStatus, newStatusText;
                if (assignedCount === 0) {
                    newStatus = 'not_covered';
                    newStatusText = 'Not Covered';
                } else if (assignedCount < requiredCount) {
                    newStatus = 'partially_covered';
                    newStatusText = 'Partially Covered';
                } else {
                    newStatus = 'fully_covered';
                    newStatusText = 'Fully Covered';
                }
                
                statusBadge.className = `status-badge status-${newStatus}`;
                statusBadge.textContent = newStatusText;
                
                // Update card data attribute
                shiftCard.dataset.status = newStatus;
            }
        },
        
        async checkAvailability(shift) {
            try {
                const response = await this.loadAvailableStaff(shift);
                this.showNotification(`Found ${this.availableStaff.length} available staff members`, 'info');
            } catch (error) {
                this.showNotification('Error checking availability', 'error');
            }
        },
        
        quickAssign(staffId) {
            // Find the first shift that needs staff
            const needsStaffCards = document.querySelectorAll('[data-status="not_covered"], [data-status="partially_covered"]');
            
            if (needsStaffCards.length === 0) {
                this.showNotification('No shifts currently need staff assignments', 'info');
                return;
            }
            
            // For demo, assign to the first available shift
            const firstShift = needsStaffCards[0];
            const shiftName = firstShift.querySelector('.shift-name')?.textContent;
            
            this.showNotification(`Quick assigned staff to ${shiftName}`, 'success');
        },
        
        viewStaffSchedule(staffId) {
            // In a real app, this would open a detailed schedule view
            this.showNotification('Opening staff schedule...', 'info');
        },
        
        formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                weekday: 'short',
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
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

// Auto Assignment Logic
function autoAssignShifts() {
    return {
        isProcessing: false,
        
        async process() {
            if (this.isProcessing) return;
            
            this.isProcessing = true;
            
            try {
                // Mock auto-assignment logic
                const unassignedShifts = document.querySelectorAll('[data-status="not_covered"], [data-status="partially_covered"]');
                
                for (let i = 0; i < Math.min(unassignedShifts.length, 3); i++) {
                    await new Promise(resolve => setTimeout(resolve, 500)); // Simulate processing
                    
                    const shift = unassignedShifts[i];
                    const shiftName = shift.querySelector('.shift-name')?.textContent;
                    
                    // Mock assignment
                    this.showNotification(`Auto-assigned staff to ${shiftName}`, 'success');
                }
                
                this.showNotification('Auto-assignment completed!', 'success');
            } catch (error) {
                this.showNotification('Auto-assignment failed', 'error');
            } finally {
                this.isProcessing = false;
            }
        },
        
        showNotification(message, type) {
            // Reuse the notification function from main component
            const event = new CustomEvent('show-notification', {
                detail: { message, type }
            });
            document.dispatchEvent(event);
        }
    };
}

// Bulk Assignment Logic
function bulkAssignShifts() {
    return {
        selectedShifts: [],
        selectedStaff: [],
        
        toggleShiftSelection(shiftId) {
            const index = this.selectedShifts.indexOf(shiftId);
            if (index > -1) {
                this.selectedShifts.splice(index, 1);
            } else {
                this.selectedShifts.push(shiftId);
            }
        },
        
        toggleStaffSelection(staffId) {
            const index = this.selectedStaff.indexOf(staffId);
            if (index > -1) {
                this.selectedStaff.splice(index, 1);
            } else {
                this.selectedStaff.push(staffId);
            }
        },
        
        async processBulkAssignment() {
            if (this.selectedShifts.length === 0 || this.selectedStaff.length === 0) {
                this.showNotification('Please select both shifts and staff members', 'warning');
                return;
            }
            
            try {
                // Mock bulk assignment
                const assignments = this.selectedShifts.length * this.selectedStaff.length;
                
                this.showNotification(`Processing ${assignments} bulk assignments...`, 'info');
                
                // Simulate processing
                await new Promise(resolve => setTimeout(resolve, 1500));
                
                this.showNotification(`Successfully created ${assignments} assignments!`, 'success');
                
                // Reset selections
                this.selectedShifts = [];
                this.selectedStaff = [];
            } catch (error) {
                this.showNotification('Bulk assignment failed', 'error');
            }
        },
        
        showNotification(message, type) {
            const event = new CustomEvent('show-notification', {
                detail: { message, type }
            });
            document.dispatchEvent(event);
        }
    };
}

// Listen for custom notification events
document.addEventListener('show-notification', function(event) {
    const { message, type } = event.detail;
    
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
});
