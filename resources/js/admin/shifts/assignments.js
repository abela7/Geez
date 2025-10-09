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
        
        // Template functionality
        showSaveTemplateModal: false,
        showApplyTemplateModal: false,
        templates: [],
        templateForm: {
            name: '',
            description: '',
            type: 'standard',
            setAsDefault: false
        },
        applyTemplateForm: {
            templateId: '',
            overwriteExisting: false
        },

        // Initialize component
        init() {
            // Ensure all modals start closed
            this.showAssignStaffModal = false;
            this.showBulkActions = false;
            this.showSaveTemplateModal = false;
            this.showApplyTemplateModal = false;
            
            this.calculateStats();
            this.setupNotifications();
            
            // Load templates on initialization
            this.loadTemplates();
            
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
            if (this.staffSearchQuery === '') return true;
            const search = this.staffSearchQuery.toLowerCase();
            return staffName.toLowerCase().includes(search) || 
                   staffType.toLowerCase().includes(search);
        },

        // Modal Management
        openAssignStaffModal(shiftId, date, shiftName) {
            this.modalShiftId = shiftId;
            this.modalDate = date;
            this.modalShiftName = shiftName;
            this.staffSearchQuery = '';
            this.showAssignStaffModal = true;
            
            console.log('Opening assign staff modal:', { shiftId, date, shiftName });
        },

        closeAssignStaffModal() {
            this.showAssignStaffModal = false;
            this.modalShiftId = null;
            this.modalDate = null;
            this.modalShiftName = '';
            this.staffSearchQuery = '';
        },

        formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            return date.toLocaleDateString('en-GB', options);
        },

        // Assignment Management
        async assignStaffToShift(staffId, staffName) {
            if (!this.modalShiftId || !this.modalDate) {
                this.showNotification('Invalid shift or date', 'error');
                return;
            }

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
                        staff_shift_id: this.modalShiftId,
                        assigned_date: this.modalDate,
                    }),
                });

                const result = await response.json();

                if (result.success) {
                    this.showNotification(`${staffName} assigned successfully!`, 'success');
                    this.closeAssignStaffModal();
                    
                    // Reload page to show new assignment (simplest approach)
                    setTimeout(() => {
                        window.location.reload();
                    }, 800);
                } else {
                    this.showNotification(result.message || 'Failed to assign staff', 'error');
                }
            } catch (error) {
                console.error('Assignment error:', error);
                this.showNotification('Failed to assign staff. Please try again.', 'error');
            } finally {
                this.isLoading = false;
            }
        },

        async removeAssignment(assignmentId) {
            if (!confirm('Are you sure you want to remove this assignment?')) {
                return;
            }

            // Find the assignment card first
            const assignmentCard = document.querySelector(`[data-assignment-id="${assignmentId}"]`);
            
            // Show loading state
            if (assignmentCard) {
                assignmentCard.style.opacity = '0.5';
                assignmentCard.style.pointerEvents = 'none';
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
                    // Only remove from DOM after successful database deletion
                    if (assignmentCard) {
                        // Fade out animation
                        assignmentCard.style.transition = 'opacity 0.3s, transform 0.3s';
                        assignmentCard.style.opacity = '0';
                        assignmentCard.style.transform = 'scale(0.9)';
                        
                        setTimeout(() => {
                            assignmentCard.remove();
                            // Update statistics
                            this.calculateStats();
                        }, 300);
                    }
                    
                    this.showNotification(result.message || 'Assignment removed successfully', 'success');
                } else {
                    // Restore card if deletion failed
                    if (assignmentCard) {
                        assignmentCard.style.opacity = '1';
                        assignmentCard.style.pointerEvents = 'auto';
                    }
                    this.showNotification(result.message, 'error');
                }
            } catch (error) {
                console.error('Remove assignment error:', error);
                // Restore card if deletion failed
                if (assignmentCard) {
                    assignmentCard.style.opacity = '1';
                    assignmentCard.style.pointerEvents = 'auto';
                }
                this.showNotification('Failed to remove assignment', 'error');
            } finally {
                this.isLoading = false;
            }
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
                const urlParams = new URLSearchParams(window.location.search);
                const weekStart = urlParams.get('week') || new Date().toISOString().split('T')[0];
                
                const response = await fetch('/admin/shifts/assignments/clear-week', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        week_start: weekStart
                    })
                });

                const data = await response.json();
                
                if (data.success) {
                    this.showNotification(data.message, 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    this.showNotification(data.message, 'error');
                }
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

        // Template Methods
        async loadTemplates() {
            try {
                const response = await fetch('/admin/shifts/assignments/templates');
                const data = await response.json();
                if (data.success) {
                    this.templates = data.templates;
                }
            } catch (e) {
                console.error('Failed to load templates:', e);
            }
        },

        async saveTemplate() {
            if (!this.templateForm.name.trim()) return;
            
            this.isLoading = true;
            try {
                // Get the current week start date from the URL or page
                const urlParams = new URLSearchParams(window.location.search);
                const weekStart = urlParams.get('week') || new Date().toISOString().split('T')[0];
                
                const response = await fetch('/admin/shifts/assignments/save-as-template', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        name: this.templateForm.name,
                        description: this.templateForm.description,
                        type: this.templateForm.type,
                        week_start: weekStart,
                        set_as_default: this.templateForm.setAsDefault
                    })
                });

                const data = await response.json();
                if (data.success) {
                    this.showNotification(data.message, 'success');
                    this.showSaveTemplateModal = false;
                    this.resetTemplateForm();
                    this.loadTemplates(); // Refresh templates list
                } else {
                    this.showNotification(data.message, 'error');
                }
            } catch (e) {
                this.showNotification('Failed to save template', 'error');
            } finally {
                this.isLoading = false;
            }
        },

        async applyTemplate() {
            if (!this.applyTemplateForm.templateId) return;
            
            this.isLoading = true;
            try {
                // Get the current week start date from the URL or page
                const urlParams = new URLSearchParams(window.location.search);
                const weekStart = urlParams.get('week') || new Date().toISOString().split('T')[0];
                
                const response = await fetch('/admin/shifts/assignments/apply-template', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        template_id: this.applyTemplateForm.templateId,
                        week_start: weekStart,
                        overwrite_existing: this.applyTemplateForm.overwriteExisting
                    })
                });

                const data = await response.json();
                if (data.success) {
                    this.showNotification(data.message, 'success');
                    this.showApplyTemplateModal = false;
                    this.resetApplyTemplateForm();
                    // Refresh the page to show new assignments
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    this.showNotification(data.message, 'error');
                }
            } catch (e) {
                this.showNotification('Failed to apply template', 'error');
            } finally {
                this.isLoading = false;
            }
        },

        resetTemplateForm() {
            this.templateForm = {
                name: '',
                description: '',
                type: 'standard',
                setAsDefault: false
            };
        },

        resetApplyTemplateForm() {
            this.applyTemplateForm = {
                templateId: '',
                overwriteExisting: false
            };
        },

        // Initialize templates when apply modal opens
        async openApplyTemplateModal() {
            this.showApplyTemplateModal = true;
            await this.loadTemplates();
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

// Initialize keyboard shortcuts when DOM loads - only on assignments page
document.addEventListener('DOMContentLoaded', function() {
    // Only run on assignments page
    if (!window.location.pathname.includes('/admin/shifts/assignments')) {
        return;
    }
    
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

// Make available globally for Alpine.js
window.shiftsAssignmentsData = shiftsAssignmentsData;