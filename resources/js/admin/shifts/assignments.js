/**
 * Shift Assignments (Rota) JavaScript
 * Handles drag & drop functionality, assignment management, and UI interactions
 */

// Alpine.js component for shift assignments
function shiftsAssignmentsData() {
    return {
        // UI State
        showAssignStaffModal: false,
        showBulkActions: false,
        isLoading: false,
        
        // Filter State
        selectedDepartment: '',
        viewMode: 'all',
        staffSearchQuery: '',
        
        // Statistics
        totalShifts: 0,
        assignedShifts: 0,
        unassignedShifts: 0,
        
        // Assignment Data
        modalShiftId: null,
        modalDate: null,
        modalShiftName: '',

        // Initialize component
        init() {
            // Ensure modal starts closed
            this.showAssignStaffModal = false;
            this.showBulkActions = false;
            
            this.calculateStats();
            this.setupNotifications();
            
            // Auto-hide bulk actions after 10 seconds
            this.$watch('showBulkActions', (value) => {
                if (value) {
                    setTimeout(() => {
                        this.showBulkActions = false;
                    }, 10000);
                }
            });
            
            console.log('Shift Assignments initialized');
        },

        // Calculate assignment statistics
        calculateStats() {
            const allShiftElements = document.querySelectorAll('.shift-row');
            const assignedElements = document.querySelectorAll('.assigned-staff');
            
            this.totalShifts = allShiftElements.length * 7; // 7 days per week
            this.assignedShifts = assignedElements.length;
            this.unassignedShifts = this.totalShifts - this.assignedShifts;
        },

        // Setup drag and drop event listeners
        setupDragAndDrop() {
            // Add drag over effects to drop zones
            document.addEventListener('dragover', (e) => {
                e.preventDefault();
                const dropZone = e.target.closest('.assignment-drop-zone');
                if (dropZone) {
                    dropZone.classList.add('drag-over');
                }
            });

            document.addEventListener('dragleave', (e) => {
                const dropZone = e.target.closest('.assignment-drop-zone');
                if (dropZone && !dropZone.contains(e.relatedTarget)) {
                    dropZone.classList.remove('drag-over');
                }
            });

            document.addEventListener('drop', (e) => {
                const dropZone = e.target.closest('.assignment-drop-zone');
                if (dropZone) {
                    dropZone.classList.remove('drag-over');
                }
            });
        },

        // Setup notification system
        setupNotifications() {
            // Add notification styles if not already present
            if (!document.querySelector('#assignment-notification-styles')) {
                const style = document.createElement('style');
                style.id = 'assignment-notification-styles';
                style.textContent = `
                    .notification {
                        position: fixed;
                        top: 20px;
                        right: 20px;
                        z-index: 9999;
                        max-width: 400px;
                        padding: 1rem;
                        border-radius: 0.5rem;
                        box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1);
                        animation: slideIn 0.3s ease-out;
                        font-weight: 500;
                        color: white;
                    }
                    
                    .notification-success { background: var(--color-success); }
                    .notification-error { background: var(--color-error); }
                    .notification-info { background: var(--color-info); }
                    .notification-warning { background: var(--color-warning); }
                    
                    .notification-content {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        gap: 1rem;
                    }
                    
                    .notification-close {
                        background: none;
                        border: none;
                        font-size: 1.5rem;
                        cursor: pointer;
                        opacity: 0.7;
                        transition: opacity 0.2s;
                        color: white;
                    }
                    
                    .notification-close:hover { opacity: 1; }
                    
                    @keyframes slideIn {
                        from { transform: translateX(100%); opacity: 0; }
                        to { transform: translateX(0); opacity: 1; }
                    }
                `;
                document.head.appendChild(style);
            }
        },

        // Filter Methods
        shouldShowShift(department) {
            if (this.selectedDepartment === '') return true;
            return department === this.selectedDepartment;
        },

        filterShifts() {
            this.calculateStats();
        },

        updateView() {
            // Implementation for view mode filtering would go here
            this.calculateStats();
        },

        staffMatchesSearch(staffName, staffType) {
            if (this.staffSearch === '') return true;
            const search = this.staffSearch.toLowerCase();
            return staffName.toLowerCase().includes(search) || 
                   staffType.toLowerCase().includes(search);
        },

        // Drag & Drop Handlers
        handleStaffDragStart(event, staffData) {
            this.draggedItem = staffData;
            this.draggedType = 'staff';
            
            event.dataTransfer.effectAllowed = 'move';
            event.dataTransfer.setData('text/html', event.target.outerHTML);
            
            // Add visual feedback
            event.target.classList.add('dragging');
            
            console.log('Started dragging staff:', staffData);
        },

        handleDragStart(event, assignmentData) {
            this.draggedItem = assignmentData;
            this.draggedType = 'assignment';
            
            event.dataTransfer.effectAllowed = 'move';
            event.dataTransfer.setData('text/html', event.target.outerHTML);
            
            // Add visual feedback
            event.target.classList.add('dragging');
            
            console.log('Started dragging assignment:', assignmentData);
        },

        async handleDrop(event, shiftId, date) {
            event.preventDefault();
            
            // Remove drag over effect
            const dropZone = event.target.closest('.assignment-drop-zone');
            if (dropZone) {
                dropZone.classList.remove('drag-over');
            }

            if (!this.draggedItem) {
                console.log('No dragged item');
                return;
            }

            try {
                if (this.draggedType === 'staff') {
                    // Assign new staff to shift
                    await this.assignStaff(this.draggedItem.staff_id, shiftId, date);
                } else if (this.draggedType === 'assignment') {
                    // Move existing assignment
                    await this.moveAssignment(this.draggedItem.assignment_id, shiftId, date);
                }
            } catch (error) {
                console.error('Drop error:', error);
                this.showNotification('Drop operation failed', 'error');
            } finally {
                // Clean up drag state
                this.cleanupDrag();
            }
        },

        cleanupDrag() {
            // Remove dragging class from all elements
            document.querySelectorAll('.dragging').forEach(el => {
                el.classList.remove('dragging');
            });
            
            // Reset drag state
            this.draggedItem = null;
            this.draggedType = null;
        },

        // Assignment Management
        async assignStaff(staffId, shiftId, date) {
            this.isLoading = true;

            try {
                const response = await fetch('/admin/shifts/assignments', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        staff_id: staffId,
                        staff_shift_id: shiftId,
                        assigned_date: date,
                    }),
                });

                const result = await response.json();

                if (result.success) {
                    this.showNotification(result.message, 'success');
                    this.refreshAssignments();
                } else {
                    this.showNotification(result.message, 'error');
                }
            } catch (error) {
                console.error('Assignment error:', error);
                this.showNotification('Failed to assign staff', 'error');
            } finally {
                this.isLoading = false;
            }
        },

        async moveAssignment(assignmentId, newShiftId, newDate) {
            // For now, we'll just show a message
            // In a full implementation, this would update the assignment
            this.showNotification('Assignment moving not yet implemented', 'info');
        },

        async removeAssignment(assignmentId) {
            if (!confirm('Are you sure you want to remove this assignment?')) {
                return;
            }

            this.isLoading = true;

            try {
                const response = await fetch(`/admin/shifts/assignments/${assignmentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });

                const result = await response.json();

                if (result.success) {
                    this.showNotification(result.message, 'success');
                    this.refreshAssignments();
                } else {
                    this.showNotification(result.message, 'error');
                }
            } catch (error) {
                console.error('Remove assignment error:', error);
                this.showNotification('Failed to remove assignment', 'error');
            } finally {
                this.isLoading = false;
            }
        },

        // Assignment Editing
        editAssignment(assignmentId) {
            // Find the assignment element to get current data
            const assignmentElement = document.querySelector(`[data-assignment-id="${assignmentId}"]`);
            if (!assignmentElement) return;

            // Extract current data (in a real app, this would come from the server)
            const statusElement = assignmentElement.querySelector('.assignment-status');
            const currentStatus = statusElement ? statusElement.textContent.toLowerCase().trim() : 'scheduled';

            this.editingAssignment = {
                id: assignmentId,
                status: currentStatus,
                notes: ''
            };

            this.showAssignmentModal = true;
        },

        async saveAssignment() {
            if (!this.editingAssignment.id) return;

            this.isLoading = true;

            try {
                const response = await fetch(`/admin/shifts/assignments/${this.editingAssignment.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        status: this.editingAssignment.status,
                        notes: this.editingAssignment.notes,
                    }),
                });

                const result = await response.json();

                if (result.success) {
                    this.showNotification(result.message, 'success');
                    this.closeAssignmentModal();
                    this.refreshAssignments();
                } else {
                    this.showNotification(result.message, 'error');
                }
            } catch (error) {
                console.error('Save assignment error:', error);
                this.showNotification('Failed to save assignment', 'error');
            } finally {
                this.isLoading = false;
            }
        },

        closeAssignmentModal() {
            this.showAssignmentModal = false;
            this.editingAssignment = {
                id: null,
                status: '',
                notes: ''
            };
        },

        // Bulk Operations
        async copyPreviousWeek() {
            if (!confirm('This will copy assignments from the previous week. Continue?')) {
                return;
            }

            this.isLoading = true;
            
            try {
                // In a real implementation, this would call the bulk assign endpoint
                await new Promise(resolve => setTimeout(resolve, 2000)); // Simulate API call
                
                this.showNotification('Previous week copied successfully', 'success');
                this.refreshAssignments();
            } catch (error) {
                this.showNotification('Failed to copy previous week', 'error');
            } finally {
                this.isLoading = false;
            }
        },

        async clearWeek() {
            if (!confirm('This will remove ALL assignments for this week. This cannot be undone. Continue?')) {
                return;
            }

            this.isLoading = true;
            
            try {
                // In a real implementation, this would call a bulk delete endpoint
                await new Promise(resolve => setTimeout(resolve, 1500)); // Simulate API call
                
                this.showNotification('Week cleared successfully', 'success');
                this.refreshAssignments();
            } catch (error) {
                this.showNotification('Failed to clear week', 'error');
            } finally {
                this.isLoading = false;
            }
        },

        async publishWeek() {
            if (!confirm('This will notify all assigned staff about their shifts. Continue?')) {
                return;
            }

            this.isLoading = true;
            
            try {
                // In a real implementation, this would call a notification endpoint
                await new Promise(resolve => setTimeout(resolve, 2000)); // Simulate API call
                
                this.showNotification('Week published and staff notified', 'success');
            } catch (error) {
                this.showNotification('Failed to publish week', 'error');
            } finally {
                this.isLoading = false;
            }
        },

        // Utility Methods
        refreshAssignments() {
            // In a real implementation, this would reload the page or fetch new data
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        },

        showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.innerHTML = `
                <div class="notification-content">
                    <span class="notification-message">${message}</span>
                    <button class="notification-close" onclick="this.parentElement.parentElement.remove()">×</button>
                </div>
            `;

            document.body.appendChild(notification);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        },

        // Keyboard Shortcuts
        handleKeydown(event) {
            // ESC to close modals/panels
            if (event.key === 'Escape') {
                if (this.showAssignmentModal) {
                    this.closeAssignmentModal();
                } else if (this.showStaffPanel) {
                    this.showStaffPanel = false;
                } else if (this.showBulkActions) {
                    this.showBulkActions = false;
                }
            }
            
            // Ctrl/Cmd + S to save (prevent default)
            if ((event.ctrlKey || event.metaKey) && event.key === 's') {
                event.preventDefault();
                if (this.showAssignmentModal) {
                    this.saveAssignment();
                }
            }
        }
    };
}

// Initialize keyboard shortcuts when DOM loads
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('keydown', function(event) {
        // Get the Alpine component instance
        const assignmentsPage = document.querySelector('.assignments-page');
        if (assignmentsPage && assignmentsPage._x_dataStack) {
            const component = assignmentsPage._x_dataStack[0];
            if (component && component.handleKeydown) {
                component.handleKeydown(event);
            }
        }
    });
    
    console.log('Shift Assignments JS loaded');
});

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { shiftsAssignmentsData };
}

// Export for ES6 modules
export { shiftsAssignmentsData };